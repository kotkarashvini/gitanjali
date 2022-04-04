<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RateSearch extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_rate_search';
    public $primaryKey = "search_id";

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_rate_search';
        $duplicate['PrimaryKey'] = 'search_id';

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
        $fieldlist['usage_main_cat_id']['select'] = 'is_select_req';
        $fieldlist['ready_reckoner_rate_flag']['select'] = 'is_yes_no';

        $fieldlist['finyear_id']['select'] = 'is_yes_no';
        $fieldlist['division_id']['select'] = 'is_yes_no';
        $fieldlist['district_id']['select'] = 'is_yes_no';
        $fieldlist['subdivision_id']['select'] = 'is_yes_no';
        $fieldlist['taluka_id']['select'] = 'is_yes_no';
        $fieldlist['village_id']['select'] = 'is_yes_no';

        $fieldlist['ulb_type_id']['select'] = 'is_yes_no';
        $fieldlist['valutation_zone_id']['select'] = 'is_yes_no';
        $fieldlist['valutation_subzone_id']['select'] = 'is_yes_no';



        $fieldlist['usage_main_catg_id']['select'] = 'is_yes_no';
        $fieldlist['usage_sub_catg_id']['select'] = 'is_yes_no';
//        $fieldlist['usage_sub_sub_catg_id']['select'] = 'is_yes_no';
        $fieldlist['construction_type_id']['select'] = 'is_yes_no';
        $fieldlist['road_vicinity_id']['select'] = 'is_yes_no';
        $fieldlist['user_defined_dependency1_id']['select'] = 'is_yes_no';
        $fieldlist['user_defined_dependency2_id']['select'] = 'is_yes_no';
        return $fieldlist;
    }

}
