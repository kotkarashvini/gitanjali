<?php

class VillageMapping extends AppModel {

    //put your code here.

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock7_village_mapping';
      public $primaryKey = 'village_id';
    

    public function get_ulb_land_type($village_id = NULL) {
        return (is_numeric($village_id)) ? $this->find('first', array('fields' => array('ulb_type_id', 'developed_land_types_id'), 'conditions' => array('village_id' => $village_id))) : 'please provide proper village Id';
    }

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock7_village_mapping';
        $duplicate['PrimaryKey'] = 'village_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'village_name_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'village_code');
        array_push($fields, 'census_code');
        
        
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
         if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
         if ($adminLevelConfig['adminLevelConfig']['is_circle'] == 'Y') {
            $fieldlist['circle_id']['select'] = 'is_select_req';
        }
        
        $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
        $fieldlist['corp_id']['select'] = 'is_select_req';
        
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['village_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedash,is_maxlength50';
            } else {
                $fieldlist['village_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';
            }
        }
        $fieldlist['village_code']['text'] = 'is_digit';
        $fieldlist['census_code']['text'] = 'is_digit';
//pr($fieldlist);
        return $fieldlist;
    }

}
