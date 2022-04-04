<?php
class fee_type extends AppModel {
    public $useDbConfigue = 'ngprs';
    public $useTable = 'ngdrstab_mst_fee_type';
    public $primaryKey = 'fee_type_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_fee_type';
        $duplicate['PrimaryKey'] = 'fee_type_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'fee_type_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['fee_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_aplhanumericspace';
            } else {
                $fieldlist['fee_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
         //$fieldlist['taluka_code']['text'] = 'is_numeric';  

        return $fieldlist;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

