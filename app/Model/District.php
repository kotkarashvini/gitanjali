<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of District
 *
 * @author Acer
 */
class District extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock3_district';
    public $primaryKey = 'district_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock3_district';
        $duplicate['PrimaryKey'] = 'district_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'district_name_' . $language['mainlanguage']['language_code']);
        }
         array_push($fields, 'district_code');
         array_push($fields, 'census_code');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $adminLevelConfig) {

        $fieldlist = array();
        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
            $fieldlist['division_id']['select'] = 'is_select_req';
        }
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['district_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength50';
            } else {
                $fieldlist['district_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';
            }
        }
        $fieldlist['district_code']['text'] = 'is_required,is_positiveinteger,is_maxlength12';
        $fieldlist['census_code']['text'] = 'is_required,is_digit';
       // $fieldlist['old_census_code']['text'] = 'is_numeric';

        return $fieldlist;
    }

}
