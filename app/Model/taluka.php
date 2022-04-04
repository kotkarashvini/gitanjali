<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of taluka
 *
 * @author Acer
 */
class taluka extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock5_taluka';
    public $primaryKey = 'taluka_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock5_taluka';
        $duplicate['PrimaryKey'] = 'taluka_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'taluka_name_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'taluka_code');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist,$adminLevelConfig) {

        $fieldlist = array();
        
        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
           $fieldlist['division_id']['select'] = 'is_select_req';  
        }
        $fieldlist['district_id']['select'] = 'is_select_req';
        if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
           $fieldlist['subdivision_id']['select'] = 'is_select_req';  
        }
         
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['taluka_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength50';
            } else {
                $fieldlist['taluka_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';;
            }
        }
         $fieldlist['taluka_code']['text'] = 'is_required,is_numeric_nonzero';  

        return $fieldlist;
    }

}
