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
class evalrule extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_evalrule_new';
    public $primaryKey = 'evalrule_id';

    //-----------------------------------------------Get Rule Details------------------------------------------------------------------------------------------
    function get_rule_detail($rule_id, $lang) {
        return $this->find('first', array('fields' => array('evalrule.evalrule_id', 'evalrule.evalrule_desc_' . $lang, 'evalrule.reference_no', 'mcat.usage_main_catg_desc_' . $lang, 'scat.usage_sub_catg_desc_' . $lang, 'sscat.usage_sub_sub_catg_desc_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'ucat', 'conditions' => array('ucat.evalrule_id = evalrule.evalrule_id')),
                        array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'mcat', 'conditions' => array('mcat.usage_main_catg_id = ucat.usage_main_catg_id')),
                        array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'scat', 'conditions' => array('scat.usage_sub_catg_id = ucat.usage_sub_catg_id')),
                        array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'sscat', 'conditions' => array('sscat.usage_sub_sub_catg_id = ucat.usage_sub_sub_catg_id')),
                    ),
                    'conditions' => array('evalrule.evalrule_id' => $rule_id)
        ));
    }

//-----------------------------------------------For New Valuation Rule -------------------------------------
    public function copy_rule($from_id, $to_id) {


        $this->Query("insert into ngdrstab_mst_evalsubrule(
            evalrule_id,evalsubrule_desc,evalsubrule_cond1,evalsubrule_formula1 ,evalsubrule_cond2 ,evalsubrule_formula2 ,state_id ,rate_id ,eval_desc_display ,eval_display_sort_id,
            max_value_formula ,max_value_condition_flag ,output_item_id ,construction_type_id ,depreciation_id ,road_vicinity_id ,user_defined_dependency1_id ,user_defined_dependency2_id ,
            rate1 ,rate2 ,out_item_order  ,req_ip ,user_id ,evalsubrule_cond3 ,evalsubrule_formula3 ,evalsubrule_cond4 ,evalsubrule_formula4 ,evalsubrule_cond5 ,evalsubrule_formula5 ,
            rate_revision_formula1 ,rate_revision_formula2 ,rate_revision_formula3 ,rate_revision_formula4 ,rate_revision_formula5 
          )
        SELECT 
        $to_id,evalsubrule_desc,evalsubrule_cond1,evalsubrule_formula1 ,evalsubrule_cond2 ,evalsubrule_formula2 ,state_id ,rate_id ,eval_desc_display ,eval_display_sort_id,
         max_value_formula ,max_value_condition_flag ,output_item_id ,construction_type_id ,depreciation_id ,road_vicinity_id ,user_defined_dependency1_id ,user_defined_dependency2_id ,
         rate1 ,rate2 ,out_item_order  ,req_ip ,user_id ,evalsubrule_cond3 ,evalsubrule_formula3 ,evalsubrule_cond4 ,evalsubrule_formula4 ,evalsubrule_cond5 ,evalsubrule_formula5 ,
         rate_revision_formula1 ,rate_revision_formula2 ,rate_revision_formula3 ,rate_revision_formula4 ,rate_revision_formula5 
         FROM ngdrstab_mst_evalsubrule  WHERE evalrule_id=?", array($from_id));
    }

    public function copy_usage_items($from_id, $to_id) {

        $result = $this->Query("select usage_sub_sub_catg_id from ngdrstab_mst_usage_category where evalrule_id=?", array($to_id));
        if (!empty($result)) {
            $this->Query("insert into ngdrstab_mst_usage_lnk_category(usage_main_catg_id,usage_sub_catg_id,usage_sub_sub_catg_id,usage_param_id,uasge_param_code,evalrule_id)
            select usage_main_catg_id,usage_sub_catg_id," . $result[0][0]['usage_sub_sub_catg_id'] . ",usage_param_id,uasge_param_code,$to_id from ngdrstab_mst_usage_lnk_category where evalrule_id=?", array($from_id));
        }
    }

    //-------------------------------------------------------------------------------------------------------------------------------

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_evalrule_new';
        $duplicate['PrimaryKey'] = 'evalrule_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'evalrule_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        //  $fieldlist['fin_year']['text'] = '';finyear_id
        //  $fieldlist['effective_date']['text'] = '';
        $fieldlist['reference_no']['text'] = 'is_textarea';
        $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
        $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
        foreach ($languagelist as $languagecode) {
            if ($languagecode['mainlanguage']['language_code'] == 'en') { 
                $fieldlist['evalrule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else { 
                $fieldlist['evalrule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
            }
        }
        $fieldlist['contsruction_type_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['depreciation_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['road_vicinity_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['user_defined_dependency1_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['user_defined_dependency2_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['additional_rate_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['additional1_rate_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['add_usage_main_catg_id']['select'] = 'is_select_req'; // dependent
        $fieldlist['add_usage_sub_catg_id']['select'] = 'is_select_req'; // dependent 
        $fieldlist['add1_usage_main_catg_id']['select'] = 'is_select_req'; // dependent add1_usage_sub_catg_id
        $fieldlist['add1_usage_sub_catg_id']['select'] = 'is_select_req';
        $fieldlist['add_usage_sub_catg_id']['select'] = 'is_select_req'; // dependent
        $fieldlist['rate_compare_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['cmp_usage_main_catg_id']['select'] = 'is_select_req'; // dependent
        $fieldlist['cmp_usage_sub_catg_id']['select'] = 'is_select_req'; // dependent
        $fieldlist['tdr_flag']['radio'] = 'is_required,is_alpha';
        $fieldlist['is_urban']['radio'] = 'is_required,is_alpha';
        $fieldlist['is_rural']['radio'] = 'is_required,is_alpha';
        $fieldlist['is_influence']['radio'] = 'is_required,is_alpha';

        return $fieldlist;
    }

}
