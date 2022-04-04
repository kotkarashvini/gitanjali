<?php

// shaikh shaji ibrahim created [4-mar-2020]
class maincast extends AppModel {

//put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_maincaste';
    public $primaryKey = 'maincast_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_maincaste';
        $duplicate['PrimaryKey'] = 'maincast_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'cast_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['cast_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_aplhanumericspace';
            } else {
                $fieldlist['cast_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}
