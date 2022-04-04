<?php

class officehierarchy extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_office_hierarchy';
    public $primaryKey = 'hierarchy_id';

    
    
      public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_office_hierarchy';
        $duplicate['PrimaryKey'] = 'hierarchy_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'hierarchy_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['hierarchy_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['hierarchy_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }

        return $fieldlist;
    }
    
    
}
