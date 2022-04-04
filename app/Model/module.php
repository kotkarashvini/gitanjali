<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of module
 *
 * @author Administrator
 */
class module extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_module';
    public $primaryKey='module_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_module';
        $duplicate['PrimaryKey'] = 'module_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'module_name_'. $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['module_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['module_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}
