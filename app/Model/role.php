<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of role
 *
 * @author Administrator
 */
class role extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_role';
    public $primaryKey = 'role_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_role';
        $duplicate['PrimaryKey'] = 'role_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'role_name_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'role_id');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['role_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['role_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        $fieldlist['role_id']['text'] = 'is_required,is_integer';
//        $fieldlist['module_id']['select'] = 'is_integer';

        return $fieldlist;
    }

}
