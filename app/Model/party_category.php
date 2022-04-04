<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of organisation
 *
 * @author nic
 */
class party_category extends AppModel {
    //put your code here
  
    
    
        public $useDbConfigue = 'ngprs';
    public $useTable = 'ngdrstab_mst_party_category';
    public $primaryKey = 'category_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_party_category';
        $duplicate['PrimaryKey'] = 'category_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'category_name_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['category_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumdashslash';
            } else {
                $fieldlist['category_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
         //$fieldlist['taluka_code']['text'] = 'is_numeric';  

        return $fieldlist;
    }
}
