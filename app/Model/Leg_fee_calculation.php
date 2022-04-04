<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Leg_fee_calculation extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_fee_calculation';
    public $primaryKey = 'fee_calc_id';
    
            public function get_fee_calc_id($token) {
             $data = $this->query("select max(fee_calc_id) from ngdrstab_trn_fee_calculation
 WHERE  ngdrstab_trn_fee_calculation.token_no=?",array( $token)); 
        return $data;
    }
    
              public function get_fee_deails_info($token) {
             $data = $this->query("select ngdrstab_trn_legacy_fee_calculation.fee_calc_id,ngdrstab_trn_legacy_fee_calculation_detail.id,ngdrstab_mst_article_fee_items. fee_item_desc_en,ngdrstab_mst_article_fee_items. fee_item_desc_ll,final_value from ngdrstab_trn_legacy_fee_calculation_detail
inner join ngdrstab_trn_legacy_fee_calculation on ngdrstab_trn_legacy_fee_calculation.fee_calc_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_calc_id
inner join ngdrstab_mst_article_fee_items on ngdrstab_mst_article_fee_items.fee_item_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_item_id
 WHERE  ngdrstab_trn_legacy_fee_calculation.token_no=?",array( $token)); 
        return $data;
    }
    
}