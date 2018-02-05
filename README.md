# cards.iwwa.idiotproof

This CiviCRM extension tries to makes CiviCRM less intimidating for the
not so tech savy **user**. Note that it does require a tech savy **admin** to
configure this extension in your instance.

## What does it do?

It creates a new submenu in the navigation menu, which (at the moment) shows
only two menu items: 'members list' and 'organisation list'. Those items
link to two custom searches; the tasks you can apply to the search
results (via the drop down) are significantly cut down, so that you can easily
export the results.

## What I would like to add some day

* Make the search result tasks configurable for the admin.
* Add some basic links to the new submenu for e.g. adding a new contact.
* Create a new permission so that only the users with that permission can see
  the whole CiviCRM menu; the rest should only see the submenu.
* Add a custom search to search for event participants.

(Note that I have little time to work on this, so it might take several years before
I can handle these issues. I welcome pull requests.)

## Patches

You need to patch CiviCRM for this to work:

* [CiviCRM pull request !11596](https://github.com/civicrm/civicrm-core/pull/11596) to
  make it possible to remove the 'Print' action from the task list.
* Non-tech users seem to like Excel-exports. If you are in Belgium, like me, where
  Excel does not handle csv-files at all, you can use the
  [CiviCRM export to Excel](https://github.com/mlutfy/ca.bidon.civiexportexcel)-extension,
  but in that case you also need to apply
  [CiviCRM pull request !11517](https://github.com/civicrm/civicrm-core/pull/11517),
  to make the Excel export work with custom searches.

## Dependencies

This extension depends on two other extensions:

* [cards.iwwa.settingsform](https://github.com/johanv/settingsform), for the settings
  form.
* [be.chiro.civi.idcache](https://github.com/Chirojeugd-Vlaanderen/idcache), to figure
  out the ID's of the custom searches.

## Configuration

You can configure the extension by browsing to civicrm/idiotproof/settings in your
CiviCRM instance. There are three settings to configure:

* 'Menu caption': the caption for the new submenu, because people might not like it
  when it is just called 'idiot proof' :-)
* 'General members group': a (smart) group that determines which contacts are shown
  under the 'Members list' menu.
* 'Primary relationship type': the 'primary' relationsihp between individuals and 
  organizations. In the CiviCRM instances I've seen, there always seemed to be some
  relationship between contact and individual that is used the most. You need to
  enter the `name_a_b` of this relationship, and for the moment it is assumed that
  contact_a is the individual, and contact_b the organization.

You can also define these settings in the my.civicrm.conf file, like this:
```
$civicrm_setting['idiotproof']['idiotproof_menu_name'] = 'IWWA';
$civicrm_setting['idiotproof']['idiotproof_general_members_group_name'] = 'all_participants';
$civicrm_setting['idiotproof']['idiotproof_primary_relationship_type'] = 'lid_van';
```
