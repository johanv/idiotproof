<?php

/**
 * A custom contact search
 */
class CRM_Idiotproof_Form_Search_GeneralMembers extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {
  function __construct(&$formValues) {
    parent::__construct($formValues);

    // Let's define the columns in this constructor, because the columns() method in the
    // return $this->columns, so I make sure there is something in there.

    // There are some concerns about the columns, that contradict each other. :(

    // 1. The names of the fields you select, have to match the column names as much as possible.
    //    So avoid using aliases. Only if the list is sorted on a field with a column name, selecting
    //    individual lines from the results wil work.
    //    I think it is ok to use prefixes.

    // 2. Field names have to be unique. If you have two fields with the same names, the content
    //    of the first field will also appear in the second field. So in this case you can use aliases,
    //    but bear in mind sorting on such an alias has undesired consequences. So alias the fields
    //    with the least probability to be sorted on.

    $this->_columns = array(
      ts('Last name') => 'contact_a.last_name',
      ts('First name') => 'contact_a.first_name',
      ts('Street Address') => 'contact_street_address',
      ts('Postal Code') => 'contact_postal_code',
      ts('City') => 'contact_city',
      ts('Country') => 'contact_country',
      ts('Email') => 'email',
      ts('Phone') => 'phone',
      ts('Club name') => 'clb.organization_name',
      ts('Preferred language') => 'preferred_language',
    );

    // Set default value for country in the constructor, for the state-province-magic to work.

  }

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   *
   * @return void
   * @throws HTML_QuickForm_Error
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(ts('General members'));

    // Don't use the 'default' state/province-country magic, because if the user
    // wants to filter on province, he will probably only be interested in the
    // provinces of the default country.

    // Drop down for state/province of default country
    $result = civicrm_api3('StateProvince', 'get', [
      'country_id' => Civi::settings()->get('defaultContactCountry')
    ]);
    $stateProvinceChoices = [0 => ts('(all)')];
    foreach ($result['values'] as $value) {
      $stateProvinceChoices[$value['id']] = $value['name'];
    }
    $form->add('select', 'state_province_id', ts('State/Province'), $stateProvinceChoices, FALSE, array('class' => 'crm-select2 huge'));

    /**
     * if you are using the standard template, this array tells the template what elements
     * are part of the search criteria
     */
    $form->assign('elements', array(
      'state_province_id'
    ));
  }

  /**
   * Define default search values. (optionally)
   *
   * @return array
   */
  public function setDefaultValues() {
    return [];
  }

  /**
   * Get a list of summary data points
   *
   * @return mixed; NULL or array with keys:
   *  - summary: string
   *  - total: numeric
   */
  function summary() {
    return NULL;
    // return array(
    //   'summary' => 'This is a summary',
    //   'total' => 50.0,
    // );
  }

  /**
   * Construct a full SQL query which returns one page worth of results
   *
   * @param int  $offset
   * @param int  $rowcount
   * @param null $sort
   * @param bool $includeContactIDs
   * @param bool $justIDs
   *
   * @return string, sql
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL, $includeContactIDs = FALSE, $justIDs = FALSE) {
    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    // Let's group by contact_a.id, so we don't get dupes if a contact is in multiple clubs.
    $groupBy = 'GROUP BY contact_a.id';
    $sql = $this->sql($this->select(), $offset, $rowcount, $sort, $includeContactIDs, $groupBy);
    return $sql;
  }

  /**
   * Construct a SQL SELECT clause
   *
   * @return string, sql fragment with SELECT arguments
   */
  function select() {
    // TODO: clb.organization_name should only be selected if a primary relationship exists.
    return "
      contact_a.id AS contact_id,
      contact_a.last_name,
      contact_a.first_name,
      contact_a.preferred_language,
      ca.street_address as contact_street_address,
      ca.postal_code as contact_postal_code,
      ca.city as contact_city,
      cc.name as contact_country,
      email,
      phone,
      clb.organization_name
    ";
  }

  /**
   * Construct a SQL FROM clause
   *
   * @return string, sql fragment with FROM and JOIN clauses
   * @throws CiviCRM_API3_Exception
   * @throws Exception
   */
  function from() {
    $primaryRelationshipResult = civicrm_api3(
      'Setting',
      'get',
      ['return' => 'idiotproof_primary_relationship_type']
    );

    $relationshipJoins = '';
    if ($primaryRelationshipResult['count']) {
      $value = CRM_Utils_Array::first($primaryRelationshipResult['values']);
      $relationshipTypeId = CRM_IdCache_Cache_RelationshipType::getId(
        'RelationshipType', $value['idiotproof_primary_relationship_type']
      );
      is_numeric($relationshipTypeId) or die('Relationship type ID should be numeric');
      $relationshipJoins = "
        LEFT OUTER JOIN civicrm_relationship r ON contact_a.id = r.contact_id_a AND r.relationship_type_id = $relationshipTypeId 
        LEFT OUTER JOIN civicrm_contact clb ON r.contact_id_b = clb.id ";
    }

    $groupJoin = '';
    // Check for a smart group with 'general members'
    $groupNameResult = civicrm_api3(
      'Setting',
      'get',
      ['return' => 'idiotproof_general_members_group_name']
    );
    if ($groupNameResult['count']) {
      $value = CRM_Utils_Array::first($groupNameResult['values']);
      $name = $value['idiotproof_general_members_group_name'];
      $groupId = CRM_IdCache_Cache_Generic::getId('Group', $name);
      is_numeric($groupId) or die ('Group ID should be numeric');
      // Make sure the contacts of the members group are preloaded
      CRM_Contact_BAO_GroupContactCache::loadAll($groupId);
      $groupJoin = "JOIN civicrm_group_contact_cache gcc ON contact_a.id = gcc.contact_id
        AND gcc.group_id=$groupId ";
    }

    $from = "
      FROM civicrm_contact contact_a
      $relationshipJoins
      $groupJoin
      LEFT OUTER JOIN civicrm_address ca ON ca.contact_id = contact_a.id AND ca.is_primary = 1
      LEFT OUTER JOIN civicrm_country cc ON ca.country_id = cc.id
      LEFT OUTER JOIN civicrm_email ce ON ce.contact_id = contact_a.id AND ce.is_primary = 1
      LEFT OUTER JOIN civicrm_phone cp ON cp.contact_id = contact_a.id AND cp.is_primary = 1
    ";
    return $from;
  }

  /**
   * Construct a SQL WHERE clause
   *
   * @param bool $includeContactIDs
   * @return string, sql fragment with conditional expressions
   */
  function where($includeContactIDs = FALSE) {
    $params = [];
    $clause = ["contact_a.contact_type = 'Individual'"];

    $stateId = CRM_Utils_Array::value('state_province_id', $this->_formValues);

    if ($stateId) {
      $params[1] = [$stateId, 'Integer'];
      $clause[] = "ca.state_province_id = %1";
    }

    // Admitted, this is a little stupid since there is at most 1 clause.
    // But hey, now we can add more filters to the form if needed.

    if (!empty($clause)) {
      $where = implode(' AND ', $clause);
    }

    return $this->whereClause($where, $params);
  }

  /**
   * Determine the Smarty template for the search screen
   *
   * @return string, template path (findable through Smarty template path)
   */
  function templateFile() {
    return 'CRM/Contact/Form/Search/Custom.tpl';
  }
}
