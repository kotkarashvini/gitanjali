



<?php

class OfficeCategory extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_office_category';
    public $primaryKey = 'office_cat_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_office_category';
        $duplicate['PrimaryKey'] = 'office_cat_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'office_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['office_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['office_desc_' . $language['mainlanguage']['language_code']]['text'] =  'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }

        return $fieldlist;
    }

}
?>