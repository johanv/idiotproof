<?php

//    This file is part of cards.iwwa.idiotproof.
//    
//    cards.iwwa.idiotproof tries to add some easy shortcuts to CiviCRM.
//    Copyright (C) 2017 Johan Vervloet <johanv@johanv.org>
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <https://www.gnu.org/licenses/>.

/*
 * Settings metadata file
 */
return array(
  'idiotproof_menu_name' => array(
    'group_name' => 'Idiotproof Preferences',
    'group' => 'idiotproof',
    'name' => 'idiotproof_menu_name',
    'type' => 'String',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => 'Caption of the idiotproof menu',
    'title' => 'Menu caption',
    'help_text' => 'The idiotproof extension creates a menu in the navigation bar. Enter the title of this menu in here.',
    'html_type' => 'Text',
    'quick_form_type' => 'Element',
  ),
  'idiotproof_general_members_group_name' => array(
    'group_name' => 'Idiotproof Preferences',
    'group' => 'idiotproof',
    'name' => 'idiotproof_general_members_group_name',
    // TODO: Can't I have a drop down with available groups?
    'type' => 'String',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => 'Name of CiviCRM group that defines the "general members"',
    'title' => 'General members group',
    'help_text' => 'Name of a CiviCRM group. The idiotproof extension provides general functionality like "show me all the members"; Contacts in the CiviCRM group with this name are considered to be "the members".',
    'html_type' => 'Text',
    'quick_form_type' => 'Element',
  ),
  'idiotproof_primary_relationship_type' => array(
    'group_name' => 'Idiotproof Preferences',
    'group' => 'idiotproof',
    'name' => 'idiotproof_primary_relationship_type',
    // TODO: Can't I have a drop down with available types?
    'type' => 'String',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => 'Relationships of this type will be shown in the custom searches of the idiotproof extension.',
    'title' => 'Primary relationship type',
    // TODO: support relationship direction. For the moment only a_b is supported
    'help_text' => 'Name of a CiviCRM relationship type (name_a_b). Choose the "most important" relationship type for individuals in your CRM.',
    'html_type' => 'Text',
    'quick_form_type' => 'Element',
  )
);
