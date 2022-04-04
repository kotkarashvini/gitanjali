<?php

class officeshift extends AppModel {

    //put your code here

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_officeshifttime';
    public $primaryKey = 'shift_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_officeshifttime';
        $duplicate['PrimaryKey'] = 'shift_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        foreach ($languagelist as $languagecode) {
            if ($languagecode['mainlanguage']['language_code'] == 'en') {
                //list for english single fields
                $fieldlist['desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
            } else {
                //list for all unicode fields
                $fieldlist['desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
            }
        }
        $fieldlist['from_time']['text'] = 'is_required,is_hourminute';
        $fieldlist['to_time']['text'] = 'is_required,is_hourminute';
        
        $fieldlist['appnt_from_time']['text'] = 'is_required,is_hourminute';
        $fieldlist['appnt_to_time']['text'] = 'is_required,is_hourminute';
        
        $fieldlist['lunch_from_time']['text'] = 'is_required,is_hourminute';
        $fieldlist['lunch_to_time']['text'] = 'is_required,is_hourminute';
        
        $fieldlist['tatkal_from_time']['text'] = 'is_required,is_hourminute';          
        $fieldlist['tatkal_to_time']['text'] = 'is_required,is_hourminute';
        
        $fieldlist['tatkal_days']['text'] = 'is_required,is_positiveinteger';
        
       

        return $fieldlist;
    }

}
