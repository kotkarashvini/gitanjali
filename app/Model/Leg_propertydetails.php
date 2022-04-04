<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of propertydetails
 *
 * @author Nicsi
 */
class Leg_propertydetails extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_property_details';
    
    
             public function get_property_detail_information($token_no)
   {  
    $res=$this->query("select ngdrstab_trn_legacy_property_details_entry.property_id,additional_information_en,additional_information_ll,ngdrstab_conf_admblock5_taluka.taluka_name_en
, ngdrstab_conf_admblock5_taluka.taluka_name_ll,location1_en,location2_ll from ngdrstab_trn_legacy_property_details_entry
inner join ngdrstab_conf_admblock5_taluka on ngdrstab_conf_admblock5_taluka.taluka_id=ngdrstab_trn_legacy_property_details_entry.taluka_id
where token_no=$token_no order by property_id");
     return $res; 
//     and ngdrstab_trn_valuation.val_id='".$val_id."'
   } 
   
   
   public function get_property_info_1_from_finalsave($token_no,$property_id) {
             $data = $this->query("select ngdrstab_trn_legacy_property_details_entry.district_id,developed_land_types_id,ngdrstab_trn_legacy_property_details_entry.taluka_id,ngdrstab_trn_legacy_property_details_entry.village_id,unique_property_no_en,
location1_en,location2_ll,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,additional_information_en,unique_property_no_ll,boundries_east_ll,boundries_west_ll,boundries_south_ll,boundries_north_ll,additional_information_ll,subdivision_id,circle_id
from ngdrstab_trn_legacy_property_details_entry
where token_no=$token_no and ngdrstab_trn_legacy_property_details_entry.property_id=$property_id");
 //WHERE token_no=? and entry.property_id?" ,array($token_no,$property_id)); 
        

        return $data;
//where token_no=20200000000296 and ngdrstab_trn_property_details_entry.property_id=335
        
    }
    
            public function get_property_info_2_from_finalsave($token_no,$property_id) {
             $data = $this->query("select ngdrstab_trn_legacy_valuation.val_id,ngdrstab_trn_legacy_valuation.usage_main_catg_id,ngdrstab_trn_legacy_valuation.usage_sub_catg_id,item_value,final_value,area_unit,consideration_amt
from  ngdrstab_trn_legacy_valuation_details
inner join ngdrstab_trn_legacy_valuation on ngdrstab_trn_legacy_valuation_details.val_id=ngdrstab_trn_legacy_valuation.val_id --and property_id=property_id
left join ngdrstab_mst_usage_main_category on ngdrstab_trn_legacy_valuation.usage_main_catg_id=ngdrstab_mst_usage_main_category.usage_main_catg_id
left join ngdrstab_mst_usage_sub_category on ngdrstab_trn_legacy_valuation.usage_sub_catg_id=ngdrstab_mst_usage_sub_category.usage_sub_catg_id

where token_no=$token_no and property_id=$property_id");
 //WHERE token_no=? and entry.property_id?" ,array($token_no,$property_id)); 
        

        return $data;
//where token_no=20200000000296 and ngdrstab_trn_property_details_entry.property_id=335
     //inner join ngdrstab_mst_unit on ngdrstab_mst_unit.unit_id=ngdrstab_trn_valuation_details.area_unit   
    }
    
              public function get_property_info_3_from_finalsave($token_no,$property_id) {
             $data = $this->query("Select ngdrstab_trn_legacy_parameter.id,paramter_id,paramter_value,paramter_value1,paramter_value2
from ngdrstab_trn_legacy_parameter
inner join ngdrstab_mst_attribute_parameter on ngdrstab_trn_legacy_parameter.paramter_id=ngdrstab_mst_attribute_parameter.attribute_id
where token_id=$token_no and property_id=$property_id");

 //WHERE token_no=? and entry.property_id?" ,array($token_no,$property_id)); 
        

        return $data;
//where token_no=20200000000296 and ngdrstab_trn_property_details_entry.property_id=335
        
    }

}


