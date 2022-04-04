<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of subrule
 *
 * @author Administrator
 */
class subrule extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_evalsubrule';
    public $primaryKey = 'subrule_id';

//---------------------------(15-Feb-2017)-- Copy All Subrule from One rule to another (existing will be kept as it is) updated on 21-April 2017 -----------------------
    public function copy_subrule($frm_id = NULL, $to_id = NULL, $state_id = 27, $req_ip = '10.153.8.64', $created_date = NULL, $user_id = 0) {
        return $this->query("UPDATE ngdrstab_mst_evalsubrule to_subrule
                SET                     
                    evalsubrule_desc=from_subrule.evalsubrule_desc,
                    evalsubrule_cond1=from_subrule.evalsubrule_cond1,
                    evalsubrule_formula1=from_subrule.evalsubrule_formula1,
                    evalsubrule_cond2=from_subrule.evalsubrule_cond2,
                    evalsubrule_formula2=from_subrule.evalsubrule_formula2,
                    state_id=$state_id,
		    rate_id=from_subrule.rate_id,
                    eval_desc_display=from_subrule.eval_desc_display,
                    eval_display_sort_id=from_subrule.eval_display_sort_id,
                    max_value_formula=from_subrule.max_value_formula,
                    max_value_condition_flag=from_subrule.max_value_condition_flag,
                    construction_type_id=from_subrule.construction_type_id,
                    depreciation_id=from_subrule.depreciation_id,
                    road_vicinity_id=from_subrule.road_vicinity_id,
		    user_defined_dependency1_id=from_subrule.user_defined_dependency1_id,
                    user_defined_dependency2_id=from_subrule.user_defined_dependency2_id,    
		    rate1=from_subrule.rate1,
		    rate2=from_subrule.rate2,
		    req_ip='$req_ip',
                    created='$created_date',
                    user_id=$user_id,
		    out_item_order=from_subrule.out_item_order,

                    evalsubrule_cond3=from_subrule.evalsubrule_cond3,
                    evalsubrule_formula3=from_subrule.evalsubrule_formula3,
                    evalsubrule_cond4=from_subrule.evalsubrule_cond4,
                    evalsubrule_formula4=from_subrule.evalsubrule_formula4,
                    evalsubrule_cond5=from_subrule.evalsubrule_cond5,
                    evalsubrule_formula5=from_subrule.evalsubrule_formula5,
                    rate_revision_formula1=from_subrule.rate_revision_formula1,
                    rate_revision_formula2=from_subrule.rate_revision_formula2,
                    rate_revision_formula3=from_subrule.rate_revision_formula3,
                    rate_revision_formula4=from_subrule.rate_revision_formula4,
                    rate_revision_formula5=from_subrule.rate_revision_formula5,
                    alternate_formula=from_subrule.alternate_formula,
                    rate_revision_flag=from_subrule.rate_revision_flag,
                    rate_revision_formula_max=from_subrule.rate_revision_formula_max
                    
              FROM  ngdrstab_mst_evalsubrule from_subrule 
               where from_subrule.subrule_id=? and to_subrule.subrule_id=?", array($frm_id, $to_id));
    }

//-------------------------------------------------------------------------------------------------------------------------------------
    public function checkOutputItem_id($rule_id = null, $outItemId = null, $roadvicinity = NULL) {

        if ($roadvicinity != NULL) {
            return $this->find('count', array('conditions' => array('evalrule_id' => $rule_id, 'road_vicinity_id' => $roadvicinity)));
        } else {
            return $this->find('count', array('conditions' => array('evalrule_id' => $rule_id, 'output_item_id' => $outItemId)));
        }
    }

    public function saveSubrule($frm = NULL) {
        $subrule = $frm;
        $subrule['evalrule_id'] = $frm['hid'];
        $subrule['evalsubrule_cond1'] = $frm['evalrule_cond1'];
        $subrule['evalsubrule_formula1'] = $frm['evalrule_formula1'];
        $subrule['evalsubrule_cond2'] = $frm['evalrule_cond2'];
        $subrule['evalsubrule_formula2'] = $frm['evalrule_formula2'];
        $subrule['evalsubrule_cond3'] = $frm['evalrule_cond3'];
        $subrule['evalsubrule_formula3'] = $frm['evalrule_formula3'];
        $subrule['evalsubrule_cond4'] = $frm['evalrule_cond4'];
        $subrule['evalsubrule_formula4'] = $frm['evalrule_formula4'];
        $subrule['evalsubrule_cond5'] = $frm['evalrule_cond5'];
        $subrule['evalsubrule_formula5'] = $frm['evalrule_formula5'];
        $subrule['construction_type_id'] = 0;
        $subrule['depreciation_id'] = 0;
        if ($frm['road_vicinity_id']) {
            $subrule['road_vicinity_id'] = $frm['road_vicinity_id'];
        }
        if ($frm['user_defined_dependency1_id']) {
            $subrule['user_defined_dependency1_id'] = $frm['user_defined_dependency1_id'];
        }
        if ($frm['user_defined_dependency2_id']) {
            $subrule['user_defined_dependency2_id'] = $frm['user_defined_dependency2_id'];
        }

        if ($frm['subruleid']) {
            $subrule['subrule_id'] = $frm['subruleid'];
            $action = "Updated";
        } else {
            $action = "Added";
        }
        //$subrulecount = $this->checkOutputItem_id($frm['hid'], $frm['output_item_id'], $frm['road_vicinity_id']);
        $saveFlag = 'Y';
//        if ($frm['subruleid'] != NULL) {
//            if ($subrulecount <= 1) {
//                $saveFlag = 'Y';
//            }
//        } else if ($frm['subruleid'] == NULL) {
//            if ($subrulecount === 0) {
//                $saveFlag = 'Y';
//            }
//        }
        if ($saveFlag === 'Y') {
            if ($this->save($subrule)) {
                return 1;
            } else {
                return "Sub Rule Save Failed";
            }
        } else {
            return "Subrule Not Saved...OutputId For this Rule Already Exits...";
        }
    }

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_evalsubrule';
        $duplicate['PrimaryKey'] = 'subrule_id';
        $fields = array();

        array_push($fields, 'output_item_id,evalsubrule_cond1');
        array_push($fields, 'output_item_id,evalsubrule_cond2');
        array_push($fields, 'output_item_id,evalsubrule_cond3');
        array_push($fields, 'output_item_id,evalsubrule_cond4');
        array_push($fields, 'output_item_id,evalsubrule_cond5');


        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($rule = NULL) {

        $fieldlist = array();
        $fieldlist['output_item_id']['select'] = 'is_select_req'; // must require
        $fieldlist['out_item_order']['text'] = 'is_required,is_numeric';

        if (isset($rule['evalrule_id'])) {
            $usagelnk = ClassRegistry::init('usagelnk')->find('first', array('fields' => array('road_vicinity_flag', 'user_defined_dependency1_flag', 'user_defined_dependency2_flag'), 'conditions' => array('evalrule_id' => $rule['evalrule_id'])));

            if (!empty($usagelnk)) {
                if ($usagelnk['usagelnk']['road_vicinity_flag'] == 'Y') {
                    $fieldlist['road_vicinity_id']['select'] = 'is_select_req';
                }
                if ($usagelnk['usagelnk']['user_defined_dependency1_flag'] == 'Y') {
                    $fieldlist['user_defined_dependency1_id']['select'] = 'is_select_req';
                }
                if ($usagelnk['usagelnk']['user_defined_dependency2_flag'] == 'Y') {
                    $fieldlist['user_defined_dependency2_id']['select'] = 'is_select_req';
                }
            }
        }


        $fieldlist['max_value_condition_flag']['radio'] = 'is_yes_no';
        $fieldlist['rate_revision_flag']['radio'] = 'is_yes_no'; // require Y/N only
        
        $fieldlist['evalsubrule_cond1']['text'] = 'is_formula'; //is_subrule_formula
        $fieldlist['evalsubrule_cond2']['text'] = 'is_formula';
        $fieldlist['evalsubrule_cond3']['text'] = 'is_formula';
        $fieldlist['evalsubrule_cond4']['text'] = 'is_formula';
        $fieldlist['evalsubrule_cond5']['text'] = 'is_formula';
        
        $fieldlist['max_value_formula']['text'] = 'is_formula'; //dependent must require rate_revision_flag
        
        $fieldlist['rate_revision_formula1']['text'] = 'is_formula'; //dependent
        $fieldlist['rate_revision_formula2']['text'] = 'is_formula'; //dependent
        $fieldlist['rate_revision_formula3']['text'] = 'is_formula'; //dependent
        $fieldlist['rate_revision_formula4']['text'] = 'is_formula'; //dependent
        $fieldlist['rate_revision_formula5']['text'] = 'is_formula'; //dependent
        
        $fieldlist['evalsubrule_formula1']['text'] = 'is_formula';
        $fieldlist['evalsubrule_formula2']['text'] = 'is_formula';
        $fieldlist['evalsubrule_formula3']['text'] = 'is_formula';
        $fieldlist['evalsubrule_formula4']['text'] = 'is_formula';
        $fieldlist['evalsubrule_formula5']['text'] = 'is_formula';
        
        return $fieldlist;
    }

}
