<?php

class department extends AppModel{
    //put your code here
    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_department';
    public $primaryKey='dept_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_department';
        $duplicate['PrimaryKey'] = 'dept_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'dept_name_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['dept_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacecomma';
            } else {
                $fieldlist['dept_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }
}
