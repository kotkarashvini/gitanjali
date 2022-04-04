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
        return $this->Query("select distinct v.*,st.state_name_en,st.state_name_ll,
                        dt.district_name_en,dt.district_name_ll, tl.taluka_name_en,tl.taluka_name_ll,
                        ulbt.class_description_en,ulbt.class_description_ll, ulb.governingbody_name_en,ulb.governingbody_name_ll,
                        vl.village_name_en,vl.village_name_ll, lt.developed_land_types_desc_en,lt.developed_land_types_desc_ll,
                        l1.level_1_desc_en,l1.level_1_desc_ll, pl1.list_1_desc_en,pl1.list_1_desc_ll,
                        l2.level_2_desc_en,l2.level_2_desc_ll, pl2.list_2_desc_en,pl2.list_2_desc_ll,
                        l3.level_3_desc_en,l3.level_3_desc_ll, pl3.list_3_desc_en,pl3.list_3_desc_ll,
                        l4.level_4_desc_en,l4.level_4_desc_ll, pl4.list_4_desc_en,pl4.list_4_desc_ll,
                        ctype.construction_type_desc_en,ctype.construction_type_desc_ll,dtype.deprication_type_desc_en,dtype.deprication_type_desc_ll,rf.rate_factor
                        from  ngdrstab_trn_valuation v
                             left outer join ngdrstab_mst_rate_factor rf on rf.constructiontype_id=v.construction_type_id and rf.depreciation_id=v.depreciation_id 
                            left outer join ngdrstab_conf_admblock1_state st on st.state_id=v.state_id
                            left outer join ngdrstab_conf_admblock3_district dt on dt.id=v.district_id
                            left outer join ngdrstab_conf_admblock5_taluka tl on tl.taluka_id=v.taluka_id
                            left outer join ngdrstab_conf_admblock_local_governingbody ulbt on ulbt.ulb_type_id=(select ulb_type_id from ngdrstab_conf_admblock_local_governingbody_list where id=v.corp_id)
                            left outer join ngdrstab_conf_admblock_local_governingbody_list ulb on ulb.id=v.corp_id
                            left outer join ngdrstab_conf_admblock7_village_mapping vl on vl.village_id=v.village_id 
                            left outer join ngdrstab_mst_developed_land_types lt on lt.id=v.developed_land_types_id 
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
                        where v.val_id=?", array($val_id));
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
            return $thecash . "." . $nums[1];
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
            $rnd = "round(" . $eqe . ")";
            $rnd_new = $rnd;
            $rnd_new = str_replace("x", "*", $rnd_new);
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
        return substr($string, $ini, $len);
    }

}
