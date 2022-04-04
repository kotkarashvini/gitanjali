<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rate
 *
 * @author Administrator
 */
class rate extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_rate'; 
    
     public $primaryKey = "id";

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_rate';
        $duplicate['PrimaryKey'] = 'id';

        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'developed_land_types_id,usage_main_catg_id,ready_reckoner_rate_flag');
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
        $fieldlist['ready_reckoner_rate_flag']['select'] = 'is_required';
        $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
        
        
        
        $fieldlist['division_id']['select'] = 'is_select_req';
        $fieldlist['district_id']['select'] = 'is_select_req';
        $fieldlist['subdivision_id']['select'] = 'is_select_req';
        $fieldlist['taluka_id']['select'] = 'is_select_req';        
        $fieldlist['ulb_type_id']['select'] = 'is_select_req';
        $fieldlist['valutation_zone_id']['select'] = 'is_select_req';        
        $fieldlist['village_id']['select'] = 'is_select_req';
        $fieldlist['level1_id']['select'] = 'is_select_req';
        $fieldlist['level1_list_id']['select'] = 'is_select_req';        
        $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
        $fieldlist['usage_sub_sub_catg_id']['select'] = 'is_select_req';
        $fieldlist['valutation_subzone_id']['select'] = 'is_select_req';
        $fieldlist['construction_type_id']['select'] = 'is_select_req';
        $fieldlist['road_vicinity_id']['select'] = 'is_select_req';
        $fieldlist['user_defined_dependency1_id']['select'] = 'is_select_req';        
        $fieldlist['user_defined_dependency2_id']['select'] = 'is_select_req';
        return $fieldlist;
    }

}
