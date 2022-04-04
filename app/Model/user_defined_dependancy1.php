<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of District
 *
 * @author Acer
 */
class user_defined_dependancy1 extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_user_def_depe1'; 
    public $primaryKey='user_defined_dependency1_id'; 

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_user_def_depe1';
        $duplicate['PrimaryKey'] = 'user_defined_dependency1_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'user_defined_dependency1_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['user_defined_dependency1_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacecommaroundbrackets';
            } else {
                $fieldlist['user_defined_dependency1_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }
    
}
