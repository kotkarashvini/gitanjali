<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of property_details_entry
 *
 * @author Anjali
 */
class Leg_property_details_entry extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_property_details_entry';
    public $primaryKey = 'property_id';
    
    
    
        public function get_property_detail_list($lang = NULL, $doc_token_no = NULL) {
        $conditions = array('Leg_property_details_entry.token_no' => $doc_token_no);
      //  $conditions['property_details_entry.user_id'] = $user_id;
        return $this->find('all', array(
                   // 'fields' => array('property_details_entry.property_id','village.village_name_' . $lang, 'district.district_name_' . $lang, 'taluka.taluka_name_' . $lang, 'sdc.cons_amt', 'property_details_entry.boundries_east_' . $lang, 'property_details_entry.boundries_west_' . $lang, 'property_details_entry.boundries_south_' . $lang, 'property_details_entry.boundries_north_' . $lang),
             'fields' => array('Leg_property_details_entry.property_id','village.village_name_' . $lang, 'district.district_name_' . $lang, 'taluka.taluka_name_' . $lang,  'Leg_property_details_entry.boundries_east_' . $lang, 'Leg_property_details_entry.boundries_west_' . $lang, 'Leg_property_details_entry.boundries_south_' . $lang, 'Leg_property_details_entry.boundries_north_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping','type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=Leg_property_details_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=Leg_property_details_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=Leg_property_details_entry.taluka_id'))
                    
                   
                        
                        //array('table' => 'ngdrstab_mst_location_levels_1_property', 'type' => 'left', 'alias' => 'loclevel1prop', 'conditions' => array('loclevel1prop.level_1_id=property_details_entry.level1_id')),
                        
                      //  array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'type' => 'left', 'alias' => 'level1prop', 'conditions' => array('level1prop.prop_level1_list_id=property_details_entry.level1_list_id')),
                        
                       //  array('table' => 'ngdrstab_trn_fee_calculation', 'type' => 'left', 'alias' => 'sdc', 'conditions' => array("sdc.token_no=property_details_entry.token_no AND sdc.article_id != 9998 AND sdc.delete_flag='N' AND sdc.property_id=property_details_entry.property_id AND sdc.cons_amt IS NOT NULL"))
                    ),
                    'conditions' => $conditions, 'order' => 'Leg_property_details_entry.property_id'
        ));
    }
}