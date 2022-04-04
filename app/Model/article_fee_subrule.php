<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evalrule
 *
 * @author Administrator
 */
class article_fee_subrule extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article_fee_subrule';
    public $primaryKey = 'fee_subrule_id';

    public function checkOutputItem_id($rule_id = null, $outItemId = null, $ulb_type_id = null) {
        return $this->find('count', array('conditions' => array('fee_rule_id' => $rule_id, 'fee_output_item_id' => $outItemId, 'ulb_type_id' => $ulb_type_id)));
    }

//    public function saveFeeSubrule($feeruleMain_id, $feeRuleList, $subRuleMainId, $fee_calc_desc, $otItemId, $outItemOrder, $mxflag, $mxformula, $c1, $f1, $c2, $f2, $c3, $f3, $c4, $f4, $c5, $f5, $req_ip, $created_date, $usr_id, $stateid, $subrule_desc, $ulb_type_id, $udd1, $udd1) {
    public function saveFeeSubrule($feeruleMain_id, $feeRuleList, $subRuleMainId, $fee_calc_desc, $otItemId, $outItemOrder, $mxflag, $mxformula, $c1, $f1, $c2, $f2, $c3, $f3, $c4, $f4, $c5, $f5, $req_ip, $usr_id, $stateid, $subrule_desc, $ulb_type_id) {
        $subruleDataToSave = array();
        $subruleNotSaved = array();
        $subruleSaved = array();
        $ulb_type_id = ($ulb_type_id) ? $ulb_type_id : 0;
        if ($feeruleMain_id) {
            foreach ($feeRuleList as $feeRule_id) {
                $subRuleId = NULL;
                $subruleData = array(
                    'fee_rule_id' => $feeRule_id,
                    'fee_subrule_desc' => $subrule_desc,
                    'max_value_condition_flag' => $mxflag,
                    'max_value_formula' => $mxformula,
                    'fee_output_item_id' => $otItemId,
                    'fee_output_item_order' => $outItemOrder,
                    'fee_rule_cond1' => $c1,
                    'fee_rule_formula1' => $f1,
                    'fee_rule_cond2' => $c2,
                    'fee_rule_formula2' => $f2,
                    'fee_rule_cond3' => $c3,
                    'fee_rule_formula3' => $f3,
                    'fee_rule_cond4' => $c4,
                    'fee_rule_formula4' => $f4,
                    'fee_rule_cond5' => $c5,
                    'fee_rule_formula5' => $f5,
                    'fee_calucation_desc' => $fee_calc_desc,
                    'ulb_type_id' => $ulb_type_id,
//                    'udd1' => $udd1,
//                    'udd2' => $udd2,
                    'req_ip' => $req_ip,
                    //  'created_date' => $created_date,
                    'user_id' => $usr_id,
                    'state_id' => $stateid
                );

                if ($subRuleMainId) {//  get subrule_id
                    $subRuleId = $this->find('first', array('fields' => array('fee_subrule_id', 'fee_rule_id'), 'conditions' => array('fee_rule_id' => $feeRule_id, 'fee_output_item_id' => $otItemId)));
                    if ($subRuleId) {
                        $subruleData['fee_subrule_id'] = $subRuleId['article_fee_subrule']['fee_subrule_id'];
                    }
                }

                $subrulecount = $this->checkOutputItem_id($feeRule_id, $otItemId, $ulb_type_id);
                $saveFlag = 'N';
                if ($subRuleId != NULL) {
                    if ($subrulecount <= 1) {
                        $saveFlag = 'Y';
                    }
                } else if ($subRuleId == NULL) {
                    if ($subrulecount == 0) {
                        $saveFlag = 'Y';
                    }
                }

                if ($saveFlag === 'Y') {
                    array_push($subruleDataToSave, $subruleData);
                    unset($subruleData);
                    //array_push($subruleSaved, $subRuleId);
                } else {
                    //array_push($subruleNotSaved, $subRuleId);
                }
            }// foreach end of Rule

            if ($subruleDataToSave) {
                if ($this->saveAll($subruleDataToSave)) {
                    return 1;
                } else {
                    return "Sub Rule Save Failed";
                }
            } else {
                return "Sub Rule Save Failed";
            }
        }//if rule selected
    }

    //-------------------------Copy Subrule----------------------------------------------------------------------------------------
    public function copy_all_subrule($copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date) {
        return $this->query("INSERT INTO ngdrstab_mst_article_fee_subrule (
                fee_rule_id,fee_subrule_desc ,fee_rule_cond1 ,fee_rule_formula1 ,fee_rule_cond2 ,fee_rule_formula2 ,fee_subrule_desc_display,fee_subrule_display_sort_id ,
                max_value_formula ,  max_value_condition_flag ,  fee_output_item_id ,  fee_output_item_order ,
                fee_rule_cond3 ,  fee_rule_formula3 ,  fee_rule_cond4 ,  fee_rule_formula4 ,  fee_rule_cond5 ,  fee_rule_formula5 ,
                req_ip ,  created ,  user_id ,  state_id ,    fee_calucation_desc , 
                division_id ,  district_id ,  subdivision_id ,  taluka_id ,  circle_id ,  land_type_id ,  ulb_type_id ,  ulb_id ,  village_id ,  udd1 , udd2  
              )

              SELECT 
                 $pasteTo_id ,fee_subrule_desc ,fee_rule_cond1 ,fee_rule_formula1 ,fee_rule_cond2 ,fee_rule_formula2 ,fee_subrule_desc_display,fee_subrule_display_sort_id ,
                max_value_formula ,  max_value_condition_flag ,  fee_output_item_id ,  fee_output_item_order ,
                fee_rule_cond3 ,  fee_rule_formula3 ,  fee_rule_cond4 ,  fee_rule_formula4 ,  fee_rule_cond5 ,  fee_rule_formula5 ,
                '$req_ip' ,  '$created_date' ,  $user_id ,  $state_id ,    fee_calucation_desc , 
                division_id ,  district_id ,  subdivision_id ,  taluka_id ,  circle_id ,  land_type_id ,  ulb_type_id ,  ulb_id ,  village_id ,  udd1 , udd2  
              FROM 
              ngdrstab_mst_article_fee_subrule where fee_rule_id=? ", array($copyFrom_id));
    }

    public function copy_single_subrule($copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date) {
        return $this->query("UPDATE ngdrstab_mst_article_fee_subrule to_subrule
                SET 
                    fee_subrule_desc=from_subrule.fee_subrule_desc,
                    fee_rule_cond1=from_subrule.fee_rule_cond1,
                    fee_rule_formula1=from_subrule.fee_rule_formula1,
                    fee_rule_cond2=from_subrule.fee_rule_cond2,
                    fee_rule_formula2=from_subrule.fee_rule_formula2,
                    fee_subrule_desc_display=from_subrule.fee_subrule_desc_display,
                    fee_subrule_display_sort_id=from_subrule.fee_subrule_display_sort_id,
                    max_value_formula=from_subrule.max_value_formula,
                    max_value_condition_flag=from_subrule.max_value_condition_flag,
                    fee_rule_cond3=from_subrule.fee_rule_cond3,
                    fee_rule_formula3=from_subrule.fee_rule_formula3,
                    fee_rule_cond4=from_subrule.fee_rule_cond4,
                    fee_rule_formula4=from_subrule.fee_rule_formula4,
                    fee_rule_cond5=from_subrule.fee_rule_cond5,
                    fee_rule_formula5=from_subrule.fee_rule_formula5,
                    req_ip='$req_ip',
                    created='$created_date',
                    user_id=$user_id,
                    state_id=$state_id,
                    fee_calucation_desc=from_subrule.fee_calucation_desc,
                    division_id=from_subrule.division_id,
                    district_id=from_subrule.district_id,
                    subdivision_id=from_subrule.subdivision_id,
                    taluka_id=from_subrule.taluka_id,
                    circle_id=from_subrule.circle_id,
                    land_type_id=from_subrule.land_type_id,
                    ulb_type_id=from_subrule.ulb_type_id,
                    ulb_id=from_subrule.ulb_id,
                    village_id=from_subrule.village_id,
                    udd1=from_subrule.udd1,
                    udd2=from_subrule.udd2                      
              FROM  ngdrstab_mst_article_fee_subrule from_subrule 
              where from_subrule.fee_subrule_id=? and to_subrule.fee_subrule_id=?", array($copyFrom_id, $pasteTo_id));
    }

}
