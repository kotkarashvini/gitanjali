<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModulePermissions extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_modulepermissions';
    public $primaryKey = 'modulepermission_id';

//    public function get_duplicate($languagelist) {
//        $duplicate['Table'] = 'ngdrstab_mst_modulepermissions';
//        $duplicate['PrimaryKey'] = 'modulepermission_id';
//        $fields = array();
//        foreach ($languagelist as $language) {
//            array_push($fields, 'auth_type_desc_' . $language['mainlanguage']['language_code']);
//        }
//        $duplicate['Fields'] = $fields;
//        return $duplicate;
//    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['module_id']['select'] = 'is_select_req';
     //   $fieldlist['role_id']['select'] = 'is_select_req';
        return $fieldlist;
    }

}
