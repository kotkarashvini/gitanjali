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
class fees_calculation_detail extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_fee_calculation_detail';
    public $primaryKey = 'fee_calc_detail_id';
    //    var $virtualFields = array('total' => 'SUM(Model.cost * Model.quantity)');
    var $virtualFields = array('fees_total' => 'SUM(fees_calculation_detail.final_value)'); // by Shridhar

    public function get_fee_calculation_detail($fee_calc_id = NULL, $lang = 'en') {
        return $this->find('all', array('fields' => array('items.fee_item_desc_' . $lang, 'items.fee_item_id', 'items.max_value', 'items.min_value', 'items.fee_param_type_id', 'item_type_id', 'fee_item_value', 'fee_calc_desc', 'final_value', 'fees_calculation_detail.min_value', 'fees_calculation_detail.max_value', 'paymentdesc.fee_item_id', 'paymentdesc.payment_mode_desc'),
        'joins' => array(
        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'items', 'type' => 'left outer', 'conditions' => array('fees_calculation_detail.fee_item_id = items.fee_item_id')),
        array('table' => 'ngdrstab_temp_payment_mode_mapping', 'alias' => 'paymentdesc', 'type' => 'left outer', 'conditions' => array('items.fee_item_id = paymentdesc.fee_item_id'))

        )
        , 'conditions' => array('fee_calc_id' => $fee_calc_id),
        'order' => 'item_type_id DESC'
        ));
    }

}
