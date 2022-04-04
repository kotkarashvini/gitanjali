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
class property_details_entry extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_property_details_entry';
    public $primaryKey = 'property_id';

//--------------------------------------By Shridhar---------------------------------------------------------
    public function get_property_detail_list($lang = NULL, $doc_token_no = NULL, $user_id = NULL) {
        $conditions = array('property_details_entry.token_no' => $doc_token_no);
      //  $conditions['property_details_entry.user_id'] = $user_id;
        return $this->find('all', array(
                    'fields' => array('property_details_entry.property_id', 'village.village_name_' . $lang, 'district.district_name_' . $lang, 'property_details_entry.fee_calc_id', 'property_details_entry.val_id', 'taluka.taluka_name_' . $lang, 'sdc.cons_amt', 'property_details_entry.boundries_east_' . $lang, 'property_details_entry.boundries_west_' . $lang, 'property_details_entry.boundries_south_' . $lang, 'property_details_entry.boundries_north_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'alias' => 'village', 'conditions' => array('village.village_id=property_details_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=property_details_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=property_details_entry.taluka_id')),
//                        array('table' => 'ngdrstab_trn_fee_calculation', 'type' => 'left', 'alias' => 'sdc', 'conditions' => array('sdc.token_no=property_details_entry.token_no AND sdc.fee_calc_id=property_details_entry.fee_calc_id'))
                        array('table' => 'ngdrstab_trn_fee_calculation', 'type' => 'left', 'alias' => 'sdc', 'conditions' => array('sdc.token_no=property_details_entry.token_no AND sdc.article_id != 9998'))
            ),
                    'conditions' => $conditions, 'order' => 'property_details_entry.property_id'
        ));
    }

//----------------------------------------by Shridhar--------------------------------------------------------------------
    public function get_property_list($lang, $token, $user_id) {

//        $q = $this->query("select prop.property_id,prop.val_id,prop.fee_calc_id,village.ulb_type_id,village.developed_land_types_id,village.village_name_$lang,e.evalrule_desc_$lang from ngdrstab_trn_property_details_entry prop ,ngdrstab_mst_evalrule_new e,ngdrstab_trn_valuation_details v,
//                    ngdrstab_conf_admblock7_village_mapping village where  village.village_id=prop.village_id  
//                  and  prop.token_no=?  and prop.val_id=v.val_id and v.rule_id=e.evalrule_id group by prop.property_id,village.village_name_$lang,e.evalrule_desc_$lang ,village.ulb_type_id,village.developed_land_types_id order by prop.property_id", array($token));
        $q=$this->query("select a.val_id, a.property_id, a.fee_calc_id,village.ulb_type_id,village.developed_land_types_id,village.village_name_$lang,
                        (SELECT string_agg( distinct rule_id::character varying, ',')
                        FROM ngdrstab_trn_valuation_details where val_id=a.val_id ) as rule_id,

                        (SELECT string_agg( distinct evalrule_desc_$lang::character varying, ', ')
                        FROM ngdrstab_trn_valuation_details as vd
                        JOIN  ngdrstab_mst_evalrule_new as rule ON evalrule_id= vd.rule_id
                        where val_id=a.val_id ) as evalrule_desc_$lang

                        from ngdrstab_trn_property_details_entry a
                        join ngdrstab_conf_admblock7_village_mapping village ON village.village_id=a.village_id
                        where a.token_no=?
                        group by a.val_id,a.property_id,a.fee_calc_id,village.ulb_type_id,village.developed_land_types_id,village.village_name_$lang", array($token));

        return $q;
    }
 //----------------------------------------By Kalyani--------------------------------------------------------------------
    
 public function get_property_list_stamp_duty($lang, $token, $user_id) { 
        $q = $this->query("select 
            prop.val_id,
village.ulb_type_id,
village.developed_land_types_id, 
prop.property_id,
rulemast.evalrule_desc_$lang ,
    fc.cons_amt,
village.village_name_$lang,
(
	select  string_agg(party1.party_full_name_$lang, ' , ')
	from 		ngdrstab_trn_party_entry_new as party1
		,ngdrstab_mst_party_type as ptype
	where ptype.party_type_id=party1.party_type_id
	and party_type_flag='1'
	and party1.token_no =prop.token_no
	and party1.property_id =prop.property_id
	group by 
	
       party1.token_no , 
       party1.property_id
     
) as seller_party_name,
(
	select  string_agg(party2.party_full_name_en, ' , ')
	from 
		ngdrstab_trn_party_entry_new as party2
		,ngdrstab_mst_party_type as ptype2
	where ptype2.party_type_id=party2.party_type_id
	and party_type_flag='0'
	and party2.token_no =prop.token_no
	and party2.property_id =prop.property_id
	group by 
	
       party2.token_no , 
       party2.property_id
     
) as buyer_party_name

from 
ngdrstab_trn_property_details_entry prop ,
ngdrstab_trn_valuation_details val,
ngdrstab_mst_evalrule_new rulemast,
ngdrstab_conf_admblock7_village_mapping village,
ngdrstab_trn_fee_calculation fc
where 
val.val_id=prop.val_id
and val.rule_id=rulemast.evalrule_id
and village.village_id=prop.village_id
and fc.property_id=prop.property_id
and prop.token_no=?

group by prop.property_id,
val.val_id ,
rulemast.evalrule_desc_$lang ,village.village_name_$lang,fc.cons_amt,village.ulb_type_id,village.developed_land_types_id
", array($token));
 
        return $q;
    }

     // by shrishail - 10-07-2017
     public function get_property_detail_list_edit($lang = NULL, $doc_token_no = NULL, $user_id = NULL) {
        $conditions = array('property_details_entry.token_no' => $doc_token_no);
        
        return $this->find('all', array(
                    'fields' => array(' DISTINCT ON ("property_details_entry"."property_id") "property_details_entry"."property_id" ','property_details_entry.*', 'village.village_name_' . $lang, 'district.district_name_' . $lang,   'taluka.taluka_name_' . $lang, 'level1.level_1_desc_'.$lang, 'level1_list.list_1_desc_' . $lang,'evalrule.evalrule_desc_'.$lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'alias' => 'village', 'conditions' => array('village.village_id=property_details_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=property_details_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=property_details_entry.taluka_id')),
                        array('table' => 'ngdrstab_mst_location_levels_1_property', 'type' => 'left', 'alias' => 'level1', 'conditions' => array('level1.level_1_id=property_details_entry.level1_id')),
                        array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'type' => 'left', 'alias' => 'level1_list', 'conditions' => array('level1_list.prop_level1_list_id=property_details_entry.level1_list_id')),
                        array('table' => 'ngdrstab_trn_valuation_details', 'type' => 'left', 'alias' => 'valuation', 'conditions' => array('valuation.val_id=property_details_entry.val_id')),
                        array('table' => 'ngdrstab_mst_evalrule_new', 'type' => 'left', 'alias' => 'evalrule', 'conditions' => array('evalrule.evalrule_id=valuation.rule_id'))
                  
                        
                        ),
                    'conditions' => $conditions, 'order' => 'property_details_entry.property_id'
        ));
    }
    
    
    public function get_property_list_32_old($lang, $token, $user_id) {
        $val_id = $this->Query("select val_id from ngdrstab_trn_valuation where val_id in(select val_id from ngdrstab_trn_property_details_entry where val_id != 0 and token_no=?) order by rounded_val_amt DESC limit 1",array($token));
        if (isset($val_id[0][0]['val_id'])) {
//        pr($val_id);exit;
            $q = $this->query("select prop.property_id,prop.val_id,prop.fee_calc_id,village.ulb_type_id,village.developed_land_types_id,village.village_name_$lang,e.evalrule_desc_$lang from ngdrstab_trn_property_details_entry prop ,ngdrstab_mst_evalrule_new e,ngdrstab_trn_valuation_details v,
                    ngdrstab_conf_admblock7_village_mapping village where  village.village_id=prop.village_id  
                  and  prop.token_no=? and prop.val_id=? and  prop.val_id=v.val_id and v.rule_id=e.evalrule_id group by prop.property_id,village.village_name_$lang,e.evalrule_desc_$lang ,village.ulb_type_id,village.developed_land_types_id order by prop.property_id", array($token,  $val_id[0][0]['val_id']));
            return $q;
        } else {
            return NULL;
        }
    }
    // exchange deed 
    public function get_property_list_32($lang, $token, $user_id) {

        $q = $this->query("select prop.property_id,prop.val_id,prop.fee_calc_id,village.ulb_type_id,village.developed_land_types_id,village.village_name_$lang,e.evalrule_desc_$lang from ngdrstab_trn_property_details_entry prop ,ngdrstab_mst_evalrule_new e,ngdrstab_trn_valuation_details v,
                    ngdrstab_conf_admblock7_village_mapping village where  village.village_id=prop.village_id  
                  and  prop.token_no=?   and prop.exchange_property_flag='Y'  and prop.val_id=v.val_id and v.rule_id=e.evalrule_id group by prop.property_id,village.village_name_$lang,e.evalrule_desc_$lang ,village.ulb_type_id,village.developed_land_types_id order by prop.property_id", array($token));
        return $q;
    }

    public function get_property_pattern($lang, $token, $user_id) {
        $q = $this->query("SELECT 
                    trn_patterns.field_id, trn_patterns.field_value_$lang, trn_patterns.mapping_ref_val ,village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                  FROM 
                   ngdrstab_trn_behavioral_patterns AS trn_patterns, 
                   ngdrstab_trn_property_details_entry AS prop, 
                   ngdrstab_conf_admblock7_village_mapping AS village,
                   ngdrstab_conf_behavioral_patterns AS conf_patterns
                  WHERE 
                    trn_patterns.mapping_ref_val = prop.property_id AND prop.village_id = village.village_id  and conf_patterns.field_id=trn_patterns.field_id AND prop.token_no=?  and trn_patterns.mapping_ref_id=1
                group by  trn_patterns.id,trn_patterns.mapping_ref_val,trn_patterns.field_id,  trn_patterns.field_value_$lang, village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                order by trn_patterns.id ASC   ", array($token));
        return $q;
    }

    public function get_property_record($token, $lang) {
        $property = $this->query("select DISTINCT a.id,a.property_id,c.finyear_desc,a.district_id,a.taluka_id,a.unique_property_no_$lang,v.rounded_val_amt,j.district_name_$lang,k.taluka_name_$lang,b.governingbody_name_$lang,l.village_name_$lang,
                    d.level_1_desc_$lang, h.list_1_desc_$lang,
                    a.unique_property_no_en,a.unique_property_no_ll,a.remark_en,a.remark_ll,
                    a.boundries_east_en,a.boundries_east_ll,a.boundries_west_en,a.boundries_west_ll,a.boundries_south_en,a.boundries_south_ll,
                    a.boundries_north_en,a.boundries_north_ll,a.additional_information_en,a.additional_information_ll,
                    ll.usage_main_catg_desc_$lang,m.usage_sub_catg_desc_$lang,n.usage_sub_sub_catg_desc_$lang,para.paramter_id,para.paramter_value,para.parameter_type,v.item_value,v.item_id
                    from ngdrstab_trn_property_details_entry a
                    left outer join ngdrstab_mst_finyear c on c.finyear_id=a.finyear_id
                    left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                    left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                    left outer join ngdrstab_conf_admblock_local_governingbody_list b on b.corp_id=a.corp_id
                    left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                    left outer join ngdrstab_mst_location_levels_1_property d on d.level_1_id = a.level1_id
                    left outer join ngdrstab_mst_loc_level_1_prop_list h on h.prop_level1_list_id = a.level1_list_id 
                     left outer join ngdrstab_trn_valuation_details v on v.val_id = a.val_id 
                     left outer join ngdrstab_mst_usage_lnk_category r on r.evalrule_id = v.rule_id
                   left outer join ngdrstab_mst_usage_items_list ul on ul.usage_param_id = v.item_id
                     inner join ngdrstab_mst_usage_main_category ll on ll.usage_main_catg_id = r.usage_main_catg_id
                    inner join ngdrstab_mst_usage_sub_category m on m.usage_sub_catg_id = r.usage_sub_catg_id
                    left outer join ngdrstab_mst_usage_sub_sub_category n on n.usage_sub_sub_catg_id = r.usage_sub_sub_catg_id
                     left outer join ngdrstab_trn_parameter para on para.property_id = a.property_id
                     
                    where a.token_no=? ", array($token));
        return $property;
    }

    function property_list_forcertificates($uniq_prop_id, $value, $lang) {
        $q = $this->query("select a.token_no,a.doc_reg_no,a.doc_reg_date,p.property_id,tp.paramter_value,village.village_name_$lang from ngdrstab_trn_property_details_entry p,ngdrstab_conf_admblock7_village_mapping village,
ngdrstab_trn_application_submitted a ,ngdrstab_trn_parameter tp where village.village_id=p.village_id and p.token_no=a.token_no and tp.property_id=p.property_id and p.unique_property_no_en=? and tp.paramter_value=? ", array($uniq_prop_id, $value));
        return $q;
    }

    public function get_property_forcertificates($lang, $prop_id) {
        $q = $this->query("SELECT 
                    trn_patterns.field_id, trn_patterns.field_value_$lang, trn_patterns.mapping_ref_val ,village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                  FROM 
                   ngdrstab_trn_behavioral_patterns AS trn_patterns, 
                   ngdrstab_trn_property_details_entry AS prop, 
                   ngdrstab_conf_admblock7_village_mapping AS village,
                   ngdrstab_conf_behavioral_patterns AS conf_patterns
                  WHERE 
                    trn_patterns.mapping_ref_val = prop.property_id AND prop.village_id = village.village_id  and conf_patterns.field_id=trn_patterns.field_id AND prop.unique_property_no_en=? 
                group by  trn_patterns.id,trn_patterns.mapping_ref_val,trn_patterns.field_id,  trn_patterns.field_value_$lang, village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                order by trn_patterns.id ASC   ", array($prop_id));
        return $q;
    }

}
