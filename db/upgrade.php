<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Manual authentication plugin upgrade code
 *
 * @package    auth
 * @subpackage manual
 * @copyright  2014 Herson Cruz (http://paradisosolutions.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_saml_upgrade($oldversion) {
  global $CFG, $DB, $OUTPUT;
  
  if ($oldversion < 2014060900) {
    $table_field = 'user_info_field';
    $table_category = 'user_info_category';
    
    $field_category = 'Salesforce';
    
    $field_list = array('Phone'        => array('Phone',        'text'),
                        'Title'        => array('Title',        'text'),
                        'CompanyName'  => array('Company Name', 'text'),
                        'mstrsf'       => array('Street',       'text'),
                        'mcitsf'       => array('City',         'text'),
                        'mstaprosf'    => array('State',        'text'),
                        'pzipsf'       => array('Postal Code',  'textarea'),
                        'mctrysf'      => array('Country',      'text'),
                        'idsf'         => array('Id',           'text'),
                        'contsf'       => array('Contact Id',   'text'),
                        'coaccidsf'    => array('Account Id',   'text'),
                        'canasf'       => array('Account Name', 'text'),
                        'maddsf'       => array('Address',      'textarea'),
                        'ispartner'    => array('Is partner',   'checkbox'),
                        'syncedfromsf' => array('Synced from',  'text'));
    try {
      $catid = $DB->get_field($table_category, 'id', array('name' => $field_category));
      
      if(empty($catid)) {
        $row = new stdClass();
        $row->name = $field_category;
        $row->sortorder = 0;
        $catid = $DB->insert_record($table_category, $row);
      }
      
      $order = 1;
      foreach($field_list as $key => $val) {
        $fielid = $DB->get_field($table_field, 'id', array('shortname' => $key));
        
        if(empty($field)) {
          $row = new stdClass();
          $row->name = $val[0];
          $row->datatype = $val[1];
          $row->descriptionformat = 1;
          $row->categoryid = $catid;
          $row->sortorder = $order;
          $row->required = 0;
          $row->locked = 0;
          $row->visible = 2;
          $row->forceunique = 0;
          $row->signup = 0;
          
          if($key == 'ispartner') 
            $row->defaultdata = 0;
          else 
            unset($row->defaultdata);
          
          if($val[1] == 'textarea')
            $row->defaultdataformat = 1;
          else
            $row->defaultdataformat = 0;
          
          $DB->insert_record($table_field, $row, false);
        }
        
      }
    } catch (Exception $e) {
      var_dump($e);
    }
  }
  return true;
}
