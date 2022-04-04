<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BehaviouralDetails extends AppModel {
    //put your code here
      public $useDbConfig = 'ngprs';
      public $useTable = 'ngdrstab_conf_behavioral_details';
       public $primaryKey = 'behavioral_details_id'; 
      
public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_behavioral_details';
        $duplicate['PrimaryKey'] = 'behavioral_details_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'behavioral_details_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         $fieldlist['behavioral_id']['select'] = 'is_required'; 
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['behavioral_details_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['behavioral_details_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
          

        return $fieldlist;
    }
}
