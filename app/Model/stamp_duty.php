<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of State
 *
 * @author Acer
 */
class stamp_duty extends AppModel {

    //put your code here    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_stamp_duty';
    public $primaryKey = 'token_no';

    // -----------------------------by Shrihdar -----------------------------------------------------------------
    public function update_sd_amt() {
        $this->query("UPDATE ngdrstab_trn_stamp_duty
                        SET online_final_amt= (online_sd_amt - CASE online_adj_amt WHEN  online_adj_amt THEN online_adj_amt ELSE 0 END),
                        counter_final_amt= (counter_sd_amt - CASE counter_adj_amt WHEN  counter_adj_amt THEN counter_adj_amt ELSE 0 END)");

        $this->update_final_amt();
    }

    public function update_final_amt() {
        $this->query('UPDATE ngdrstab_trn_stamp_duty SET final_amt = ((CASE online_final_amt WHEN online_final_amt THEN online_final_amt ELSE 0 END) + ( CASE counter_final_amt  WHEN counter_final_amt THEN counter_final_amt ELSE 0 END ))');
    }

    //----------------------------By Shridhar on 22 March 2017----------------------------------------------------
    public function get_stamp_duty($doc_token_no, $user_id, $lang,$fee_type_id=array(1,2)) {
        return $this->find('all', array('fields' => array('item.fee_item_desc_' . $lang, 'SUM(fees.final_value) as fees'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_fee_calculation', 'alias' => 'feecalc', 'conditions' => array("feecalc.token_no=stamp_duty.token_no and feecalc.delete_flag='N'")),
                        array('table' => 'ngdrstab_trn_fee_calculation_detail', 'alias' => 'fees', 'conditions' => array('fees.fee_calc_id=feecalc.fee_calc_id')),
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees.fee_item_id and item.fee_param_type_id=2')),
                    ),
                    'conditions' => array('stamp_duty.token_no' => $doc_token_no,'item.fee_type_id'=>$fee_type_id),
                    'order' => 'item.fee_item_id',
                    'group' => array('fees.fee_item_id', 'item.fee_item_id', 'item.fee_item_desc_' . $lang)));
        //'stamp_duty.user_id'=>$user_id
    }
      //----------------------------By shrishail on 16-1 - 2019----------------------------------------------------
  
    public function get_stamp_duty_agriment($doc_token_no,$lang,$fee_item_id,$flag='Yes') {
        return $this->find('all', array('fields' => array('item.fee_item_desc_' . $lang, 'list.fee_item_list_desc_en'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_fee_calculation', 'alias' => 'feecalc', 'conditions' => array("feecalc.token_no=stamp_duty.token_no and feecalc.delete_flag='N'")),
                        array('table' => 'ngdrstab_trn_fee_calculation_detail', 'alias' => 'fees', 'conditions' => array('fees.fee_calc_id=feecalc.fee_calc_id')),
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array("item.fee_item_id=fees.fee_item_id and item.fee_param_type_id=1 and item.list_flag='Y'")),
                        array('table' => 'ngdrstab_conf_article_fee_items_list', 'alias' => 'list', 'conditions' => array("list.fee_item_id=fees.fee_item_id and list.fee_item_list_desc_en='Yes'")),
                        
                    ),
                    'conditions' => array('stamp_duty.token_no' => $doc_token_no,'item.fee_item_id'=>$fee_item_id),
                    'order' => 'item.fee_item_id',
                    'group' => array('fees.fee_item_id', 'item.fee_item_id', 'item.fee_item_desc_' . $lang,'list.fee_item_list_desc_en')
            )
                );
        //'stamp_duty.user_id'=>$user_id
    }
   
    //----------------------------By shrishail on 4-9 - 2017----------------------------------------------------
   
    public function get_stamp_duty_payment($doc_token_no, $user_id, $lang,$fee_type_id=array(1,2),$article_id=NULL) {
        return $this->find('all', array('fields' => array('item.fee_item_desc_' . $lang, 'SUM(fees.final_value) as fees'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_fee_calculation', 'alias' => 'feecalc', 'conditions' => array("feecalc.token_no=stamp_duty.token_no and feecalc.delete_flag='N'")),
                        array('table' => 'ngdrstab_trn_fee_calculation_detail', 'alias' => 'fees', 'conditions' => array('fees.fee_calc_id=feecalc.fee_calc_id')),
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees.fee_item_id and item.fee_param_type_id=2')),
                    ),
                    'conditions' => array('stamp_duty.token_no' => $doc_token_no,'item.fee_type_id'=>$fee_type_id,'feecalc.article_id'=>array('9999',$article_id)),
                    'order' => 'item.fee_item_id',
                    'group' => array('fees.fee_item_id', 'item.fee_item_id', 'item.fee_item_desc_' . $lang)));
        //'stamp_duty.user_id'=>$user_id
    }

}
