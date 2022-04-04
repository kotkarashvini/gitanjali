<?php

class circle extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock6_circle';
    public $primaryKey = 'circle_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock6_circle';
        $duplicate['PrimaryKey'] = 'circle_id';
        
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'circle_name_' . $language['mainlanguage']['language_code']);
        }
          array_push($fields, 'circle_code');
        
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $adminLevelConfig) {
        
        $fieldlist = array();

        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
            $fieldlist['division_id']['select'] = 'is_select_req';
        }
        $fieldlist['district_id']['select'] = 'is_select_req';


        if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
        $fieldlist['taluka_id']['select'] = 'is_select_req';

        
        
        if ($adminLevelConfig['adminLevelConfig']['is_taluka'] == 'Y') {
            $fieldlist['taluka_id']['select'] = 'is_select_req';
        }
        $fieldlist['circle_id']['select'] = 'is_select';


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['circle_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength50';
            } else {
                $fieldlist['circle_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';
            }
        }
        $fieldlist['circle_code']['text'] = 'is_required,is_positiveinteger,is_maxlength12';
       //    pr($fieldlist);exit;
        return $fieldlist;
    }

}
