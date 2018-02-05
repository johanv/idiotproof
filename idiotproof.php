<?php

require_once 'idiotproof.civix.php';
use CRM_Idiotproof_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function idiotproof_civicrm_config(&$config) {
  _idiotproof_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function idiotproof_civicrm_xmlMenu(&$files) {
  _idiotproof_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function idiotproof_civicrm_install() {
  _idiotproof_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function idiotproof_civicrm_postInstall() {
  _idiotproof_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function idiotproof_civicrm_uninstall() {
  _idiotproof_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function idiotproof_civicrm_enable() {
  _idiotproof_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function idiotproof_civicrm_disable() {
  _idiotproof_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function idiotproof_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _idiotproof_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function idiotproof_civicrm_managed(&$entities) {
  _idiotproof_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function idiotproof_civicrm_caseTypes(&$caseTypes) {
  _idiotproof_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function idiotproof_civicrm_angularModules(&$angularModules) {
  _idiotproof_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function idiotproof_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _idiotproof_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function idiotproof_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 * @throws CiviCRM_API3_Exception
 * @throws Exception
 */
function idiotproof_civicrm_navigationMenu(&$menu) {
  // E::ts() can be used to translate...
  $settingsResult = civicrm_api3('Setting', 'get', ['return' => 'idiotproof_menu_name']);
  $caption = CRM_Utils_Array::first($settingsResult['values'])['idiotproof_menu_name'];

  // Add the idiotproof submenu.
  _idiotproof_civix_insert_navigation_menu($menu, NULL, array(
    'label' => $caption,
    'name' => 'idiotproof_submenu',
    'url' => null,
    'permission' => 'access CiviCRM',
    'separator' => 0,
  ));

  // Add menu item for members list
  $membersSearchId = CRM_IdCache_Cache_CustomSearch::getSearchId('CRM_Idiotproof_Form_Search_GeneralMembers');
  _idiotproof_civix_insert_navigation_menu($menu, 'idiotproof_submenu', array(
    'label' => E::ts('Members list'),
    'name' => 'members_list',
    'url' => "civicrm/contact/search/custom?csid={$membersSearchId}&reset=1",
    'permission' => 'access CiviCRM',
    'separator' => 0,
  ));

  // Add menu item for organizations list
  $organizationsSearchId = CRM_IdCache_Cache_CustomSearch::getSearchId('CRM_Idiotproof_Form_Search_Organizations');
  _idiotproof_civix_insert_navigation_menu($menu, 'idiotproof_submenu', array(
    'label' => E::ts('Organizations list'),
    'name' => 'organizations_list',
    'url' => "civicrm/contact/search/custom?csid={$organizationsSearchId}&reset=1",
    'permission' => 'access CiviCRM',
    'separator' => 0,
  ));

  _idiotproof_civix_navigationMenu($menu);
}

function idiotproof_civicrm_searchTasks($objectType, &$tasks) {
  if ($objectType != 'contact') {
    return;
  }

  // TODO: make this configurable
  $allowed = [
    CRM_Contact_Task::TAG_CONTACTS,
    CRM_Contact_Task::REMOVE_TAGS,
    CRM_Contact_Task::EXPORT_CONTACTS,
    CRM_Contact_Task::MERGE_CONTACTS,
    CRM_Contact_Task::ADD_EVENT,
  ];

  foreach ($tasks as $key => $value) {
    if (!in_array($key, $allowed)) {
      unset($tasks[$key]);
    }
  }

  return;
}
