<?php

class authenticate_type extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
        public $useTable = 'ngdrstab_mst_user_authenticationtype';
    public $primaryKey = 'id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_user_authenticationtype';
        $duplicate['PrimaryKey'] = 'id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'auth_type_desc_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'user_auth_type_id');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['user_auth_type_id']['text'] = 'is_required,is_integer';
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['auth_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['auth_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}
