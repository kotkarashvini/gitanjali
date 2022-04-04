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
class Level1 extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_location_levels_1_property';
    
     public $primaryKey = 'level_1_id';
    

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_location_levels_1_property';
        $duplicate['PrimaryKey'] = 'level_1_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'level_1_desc_' . $language['mainlanguage']['language_code']);
        }
        //array_push($fields, 'village_code');
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
            $fieldlist['village_id']['select'] = 'is_select_req';
        }

        
        $fieldlist['village_id']['select'] = 'is_select_req';
        
        
//        $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
//        $fieldlist['corp_id']['select'] = 'is_select_req';
        //$fieldlist['village_id']['select'] = 'is_select_req';
        
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['level_1_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['level_1_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        //$fieldlist['village_code']['text'] = 'is_required,is_numeric';
        return $fieldlist;
    }

    
    
//    var $virtualFields = array(
//    'name' => "CONCAT(Level1.level_1_from_range, '-', Level1.level_1_to_range)"
//);

}
