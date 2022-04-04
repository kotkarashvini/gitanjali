<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of behaviour
 *
 * @author Nicsi
 */
class Behavioural extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_behavioral';
    public $primaryKey='behavioral_id'; 


    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_behavioral';
        $duplicate['PrimaryKey'] = 'behavioral_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'behavioral_desc_' . $language['mainlanguage']['language_code']);
        } 
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) { 
        $fieldlist = array(); 
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['behavioral_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['behavioral_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        } 
        return $fieldlist;
    }

    
    
    
}
