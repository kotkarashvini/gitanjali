<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of valuation
 *
 * @author Administrator
 */
class valuation extends AppModel {

    //put your code here.

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_valuation';

//--------------------------------------by Shridhar-------------------------------
    public function getValuationInfo($val_id = NULL) {
        return $this->Query("select distinct fyr.finyear_desc,v.*,v.rounded_val_amt as final_valuation_amount,st.state_name_en,st.state_name_ll,
                        dt.district_name_en,dt.district_name_ll, tl.taluka_name_en,tl.taluka_name_ll,
                        ulbt.class_description_en,ulbt.class_description_ll, ulb.governingbody_name_en,ulb.governingbody_name_ll,
                        vl.village_name_en,vl.village_name_ll, lt.developed_land_types_desc_en,lt.developed_land_types_desc_ll,
                        l1.level_1_desc_en,l1.level_1_desc_ll, pl1.list_1_desc_en,pl1.list_1_desc_ll,
                        l2.level_2_desc_en,l2.level_2_desc_ll, pl2.list_2_desc_en,pl2.list_2_desc_ll,
                        l3.level_3_desc_en,l3.level_3_desc_ll, pl3.list_3_desc_en,pl3.list_3_desc_ll,
                        l4.level_4_desc_en,l4.level_4_desc_ll, pl4.list_4_desc_en,pl4.list_4_desc_ll,
                        ctype.construction_type_desc_en,ctype.construction_type_desc_ll,dtype.deprication_type_desc_en,dtype.deprication_type_desc_ll,
                        road_vicinity.road_vicinity_desc_en,road_vicinity.road_vicinity_desc_ll,
                        udd1.user_defined_dependency1_id,user_defined_dependency1_desc_en,user_defined_dependency1_desc_ll,
                        udd2.user_defined_dependency2_id,user_defined_dependency2_desc_en,user_defined_dependency2_desc_ll,                     
                        rf.rate_factor
                        from  ngdrstab_trn_valuation v
                            left outer join ngdrstab_mst_finyear fyr on fyr.finyear_id=v.finyear_id
                             left outer join ngdrstab_mst_rate_factor rf on rf.constructiontype_id=v.construction_type_id and rf.depreciation_id=v.depreciation_id 
                            left outer join ngdrstab_conf_admblock1_state st on st.state_id=v.state_id
                            left outer join ngdrstab_conf_admblock3_district dt on dt.id=v.district_id
                            left outer join ngdrstab_conf_admblock5_taluka tl on tl.taluka_id=v.taluka_id
                            left outer join ngdrstab_conf_admblock_local_governingbody ulbt on ulbt.ulb_type_id=(select ulb_type_id from ngdrstab_conf_admblock_local_governingbody_list where id=v.corp_id)
                            left outer join ngdrstab_conf_admblock_local_governingbody_list ulb on ulb.id=v.corp_id
                            left outer join ngdrstab_conf_admblock7_village_mapping vl on vl.village_id=v.village_id 
                            left outer join ngdrstab_mst_developed_land_types lt on lt.developed_land_types_id=vl.developed_land_types_id 
                            left outer join ngdrstab_mst_location_levels_1_property l1 on l1.level_1_id=v.level1_id
                            left outer join ngdrstab_mst_loc_level_1_prop_list pl1 on pl1.prop_level1_list_id=v.level1_list_id
                            left outer join ngdrstab_mst_location_levels_2_property l2 on l2.level_2_id=v.level2_id
                            left outer join ngdrstab_mst_loc_level_2_prop_list pl2 on pl2.prop_level2_list_id=v.level2_list_id
                            left outer join ngdrstab_mst_location_levels_3_property l3 on l3.level_3_id=v.level3_id
                            left outer join ngdrstab_mst_loc_level_3_prop_list pl3 on pl3.prop_leve3_list_id=v.level3_list_id
                            left outer join ngdrstab_mst_location_levels_4_property l4 on l4.level_4_id=v.level4_id
                            left outer join ngdrstab_mst_loc_level_4_prop_list pl4 on pl4.prop_level4_list_id=v.level4_list_id
                            left outer join ngdrstab_mst_construction_type ctype on ctype.construction_type_id=v.construction_type_id
                            left outer join ngdrstab_mst_depreciation_type dtype on dtype.deprication_type_id=v.depreciation_id                            
                            left outer join ngdrstab_mst_road_vicinity road_vicinity on road_vicinity.road_vicinity_id=v.road_vicinity_id
                            left outer join ngdrstab_mst_user_def_depe1 udd1 on udd1.user_defined_dependency1_id=v.user_defined_dependency1_id
                            left outer join ngdrstab_mst_user_def_depe2 udd2 on udd2.user_defined_dependency2_id=v.user_defined_dependency2_id
                           
                        where v.val_id=?", array($val_id));
    }

    public function rate_revision_flag($val_id = NULL) {
        $q = $this->query("select DISTINCT v.val_id, r.rate_revision_flag from ngdrstab_mst_evalrule_new r ,ngdrstab_trn_valuation_details v
where r.evalrule_id=v.rule_id and v.val_id=?", array($val_id));
        return $q[0][0]['rate_revision_flag'];
    }

    public function format_money_india($number = NULL) {
        $nums = explode(".", $number);
        if (count($nums) > 2) {
            return "0";
        } else {
            if (count($nums) == 1) {
                $nums[1] = "00";
            }
            $num = $nums[0];
            $explrestunits = "";
            if (strlen($num) > 3) {
                $lastthree = substr($num, strlen($num) - 3, strlen($num));
                $restunits = substr($num, 0, strlen($num) - 3);
                $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
                $expunit = str_split($restunits, 2);
                for ($i = 0; $i < sizeof($expunit); $i++) {

                    if ($i == 0) {
                        $explrestunits .= (int) $expunit[$i] . ",";
                    } else {
                        $explrestunits .= $expunit[$i] . ",";
                    }
                }
                $thecash = $explrestunits . $lastthree;
            } else {
                $thecash = $num;
            }
//            return $thecash . "." . $nums[1];
            return $thecash;
        }
    }

    public function roundup($str1) {

        $strtemp = $str1;
        $strnew = $str1;
        $str2 = "round(";
        $str3 = ")";
        $countarr = explode("round", $str1);
        $i = 0;
        while ($i < count($countarr) - 1) {
            $i++;
            $eqe = $this->get_string_between($strtemp, $str2, $str3);

//             pr($eqe);
//    $eqe = eval("return ($eqe);");
            $rnd = "round(" . $eqe . ")";
            $rnd_new = $rnd;
            $rnd_new = str_replace("x", "*", $rnd_new);
//            pr($rnd_new);exit;
            $eval_val = eval("return ($rnd_new);");
            $strtemp = str_replace($rnd, " ", $strtemp);
            $strnew = str_replace($rnd, $eval_val, $strnew);
        }
        return $strnew;
    }

    function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
//        pr(substr($string, $ini, $len));
        return substr($string, $ini, $len);
    }

    function fieldlist($rulelist = NULL) {
        $options['rule.display_flag'] = "Y";
        if (!is_null($rulelist)) {
            $options['usagelinkcategory.evalrule_id'] = $rulelist;
        }
        $fieldlist['finyear_id']['select'] = 'is_select_req';

        $adminLevelConfig = ClassRegistry::init('adminLevelConfig')->find("first", array(
            'joins' => array(
                array('table' => 'ngdrs_current_state', 'alias' => 'cstate', 'conditions' => array('cstate.state_id=adminLevelConfig.state_id')),
            ),
        ));

        if (!empty($adminLevelConfig)) {
            $adminLevelConfig = $adminLevelConfig['adminLevelConfig'];
        }
        if (!empty($adminLevelConfig) && $adminLevelConfig['is_div'] == 'Y') {
            $fieldlist['division_id']['select'] = 'is_select_req';
        }
        $fieldlist['district_id']['select'] = 'is_select_req';
        if (!empty($adminLevelConfig) && $adminLevelConfig['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
        $fieldlist['developed_land_types_id']['select'] = 'is_select_req';

        $fieldlist['taluka_id']['select'] = 'is_select_req';
        
        if (!empty($adminLevelConfig) && $adminLevelConfig['is_circle'] == 'Y') {
            $fieldlist['circle_id']['select'] = 'is_select_req';
        }
        
        
        $confresult = ClassRegistry::init('regconfig')->find('first', array('conditions' => array('is_boolean' => 'Y', 'conf_bool_value' => 'Y', 'reginfo_id' => 69)));
        if (!empty($confresult)) {
            $fieldlist['survey_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
        }


        //pr($fieldlist);exit;
        
        $fieldlist['village_id']['select'] = 'is_select_req';
        $fieldlist['level1_id']['select'] = 'is_select_req';
        $fieldlist['level1_list_id']['select'] = 'is_select_req';
        $fieldlist['usage_cat_id']['checkbox'] = 'is_required,is_select_req'; //
        $fieldlist['construction_type_id']['select'] = 'is_select_req';
        $fieldlist['depreciation_id']['select'] = 'is_select_req';
        $fieldlist['road_vicinity_id']['select'] = 'is_select_req';
        $fieldlist['user_defined_dependency1_id']['select'] = 'is_select_req';
        $fieldlist['user_defined_dependency2_id']['select'] = 'is_select_req';


        $inputfields = ClassRegistry::init('usagelinkcategory')->find('all', array('fields' => array('items_list.usage_param_code', 'items_list.is_list_field_flag', 'items_list.area_field_flag', 'items_list.vrule_input', 'items_list.vrule_unit', 'items_list.vrule_areatype', 'usagelinkcategory.evalrule_id', 'usagelinkcategory.mandate_flag'),
            'conditions' => array('items_list.usage_param_type_id' => 1),
            'joins' => array(
                array('table' => 'ngdrstab_mst_usage_items_list', 'type' => 'INNER', 'alias' => 'items_list', 'conditions' => array('items_list.usage_param_id=usagelinkcategory.usage_param_id')),
                array('table' => 'ngdrstab_mst_evalrule_new', 'type' => 'INNER', 'alias' => 'rule', 'conditions' => array('rule.evalrule_id=usagelinkcategory.evalrule_id')),
            ),
            'conditions' => $options,
            'order' => 'items_list.id ASC'
        ));
        //pr($inputfields);
        // display_flag

        foreach ($inputfields as $input) {
            if (!empty($input['items_list']['vrule_input'])) {
                if ($input['items_list']['is_list_field_flag'] == 'Y') {
                    $fieldlist[$input['items_list']['usage_param_code'] . "_" . $input['usagelinkcategory']['evalrule_id']]['select'] = $input['items_list']['vrule_input']; // 'is_select_req'; 
                } else if ($input['items_list']['area_field_flag'] == 'Y' && $input['items_list']['is_list_field_flag'] == 'N') {
                    if ($input['usagelinkcategory']['mandate_flag'] == 'Y') {
                        $fieldlist[$input['items_list']['usage_param_code'] . "_" . $input['usagelinkcategory']['evalrule_id']]['text'] = $input['items_list']['vrule_input'] . ",is_nonzero"; // 'is_select_req'; 
                    } else {
                        $fieldlist[$input['items_list']['usage_param_code'] . "_" . $input['usagelinkcategory']['evalrule_id']]['text'] = $input['items_list']['vrule_input']; // 'is_select_req'; 
                    }
                    if (!empty($input['items_list']['vrule_unit'])) {
                        $fieldlist[$input['items_list']['usage_param_code'] . "unit_" . $input['usagelinkcategory']['evalrule_id']]['text'] = $input['items_list']['vrule_unit']; // 'is_select_req'; 
                    }if (!empty($input['items_list']['vrule_areatype'])) {
                        $fieldlist[$input['items_list']['usage_param_code'] . "areatype_" . $input['usagelinkcategory']['evalrule_id']]['text'] = $input['items_list']['vrule_areatype']; // 'is_select_req'; 
                    }
                } else {
                    $fieldlist[$input['items_list']['usage_param_code'] . "_" . $input['usagelinkcategory']['evalrule_id']]['text'] = $input['items_list']['vrule_input']; // 'is_select_req';                      
                }
            }
        }
        //pr(count($fieldlist));
        // pr($fieldlist);
        // exit;
        return $fieldlist;
    }

    function fieldlist_citizen($doclang = 'en', $lang = 'en', $rulelist = NULL, $regval = NULL) {
        $fieldlist['attribute_value']['text'] = 'is_alphanumspacedashdotslash';
        $fieldlist['attribute_value1']['text'] = 'is_alphanumspacedashdotslash';
        $fieldlist['attribute_value2']['text'] = 'is_alphanumspacedashdotslash';
        if ($regval == 'Y') {
            $fieldlist['attribute_value_p']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['attribute_value1_p']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['attribute_value2_p']['text'] = 'is_alphanumspacedashdotslash';
        }
        $flag = 0;
        if (!empty($rulelist)) {
            $usage = ClassRegistry::init('usagelnk')->find('first', array('fields' => array('evalrule_id', 'usage_main_catg_id', 'usage_sub_catg_id'), 'conditions' => array('evalrule_id' => $rulelist, 'is_boundary_applicable' => 'Y')));
            if (!empty($usage)) {
                $flag = 1;
            }
        } else {
            $flag = 1;
        }

        if ($flag) {
            $fieldlist['unique_property_no_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['boundries_east_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['boundries_west_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['boundries_south_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['boundries_north_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['remark_en']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['additional_information_en']['text'] = 'is_alphanumspacedashdotslash';
            if ($doclang != 'en') {
                $fieldlist['unique_property_no_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_east_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_west_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_south_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_north_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['remark_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['additional_information_ll']['text'] = 'unicode_rule_' . $lang;
            }
        }


        return $fieldlist;
    }

}
