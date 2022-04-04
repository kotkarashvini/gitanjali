<?php

class designation extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_designation'; 
    public $primaryKey='desg_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_designation';
        $duplicate['PrimaryKey'] = 'desg_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'desg_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['desg_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['desg_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}