<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payment
 *
 * @author nic
 */
class inspection_payment extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_inspection_payment';
    public $primaryKey = 'payment_id';

    function get_all_payment($token, $user_id) {
        return $this->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=? AND pay.user_id=? ", array($token, $user_id));
    }

    //----------------------get Payment Detail-- by Shridhar----------------------------------------------------------------
    function get_payment_detail($doc_token_id = NULL, $payment_id = NULL, $lang = 'en') {
        $condition['payment.token_no'] = $doc_token_id;
        if ($payment_id) {
            $condition['payment.payment_id'] = $payment_id;
        }
        return $this->find('first', array('fields' => array('payment.*', 'aps.office_id', 'office.office_name_en', 'office.office_name_ll', 'bank.bank_name_' . $lang, 'bank_branch.branch_name_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_application_submitted', 'alias' => 'aps', 'type' => 'left', 'conditions' => array('payment.token_no=aps.token_no')),
                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'type' => 'left', 'conditions' => array('office.office_id=aps.office_id')),
                        array('table' => 'ngdrstab_mst_bank', 'alias' => 'bank', 'type' => 'left', 'conditions' => array('bank.bank_id=CAST(payment.bank_id as integer)')),
                        array('table' => 'ngdrstab_mst_bank_branch', 'alias' => 'bank_branch', 'type' => 'left', 'conditions' => array('bank_branch.branch_id=CAST(payment.branch_id as bigint)'))
                    ),
                    'conditions' => $condition
        ));
    }

//    function get_account_wise_payment($token,$payment_id) {
//        $feedetails = $this->query("SELECT
//         feeitem. fee_item_id,
//feeitem.fee_item_desc_en,
//SUM(stampd.final_value) as totalsd
//
//FROM
//ngdrstab_trn_fee_calculation_detail stampd 
//LEFT JOIN ngdrstab_trn_fee_calculation stamp     ON stampd.fee_calc_id = stamp.fee_calc_id
//LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.fee_item_id=stampd.fee_item_id
// WHERE  stamp.token_no=?  
// AND stamp.delete_flag='N' 
// AND feeitem.fee_param_type_id=2 
// AND 
//group by feeitem.fee_item_id
//order by feeitem.fee_preference ASC
//", array($token));
//
//        return $feedetails;
//    }
    public function get_payment_acc_head_detail($doc_token_no = NULL, $payment_id = NULL) {

        return $this->find('all', array('fields' => array('payment.fee_item_id', 'item.fee_item_desc_en', 'payment.pamount', 'receipt.receipt_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.account_head_code=payment.account_head_code')),
                        array('table' => 'ngdrstab_trn_receipt_counter', 'alias' => 'receipt', 'type' => 'left', 'conditions' => array('receipt.payment_id=payment.payment_id'))
                    ),
                    'conditions' => array('payment.token_no' => $doc_token_no, 'payment.payment_id' => $payment_id)
        ));
    }

    public function stampduty_fee_details($token, $lang) {
        return $this->query("SELECT 
                            feeitem.account_head_code,
                   feeitem.fee_item_desc_$lang,
                   SUM(stampd.final_value) as totalsd

                   FROM
                   ngdrstab_trn_fee_calculation_detail stampd 
                   LEFT JOIN ngdrstab_trn_fee_calculation stamp     ON stampd.fee_calc_id = stamp.fee_calc_id
                   LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.fee_item_id=stampd.fee_item_id
                    WHERE  stamp.token_no=?  
                    AND stamp.delete_flag='N' 
                    AND feeitem.fee_param_type_id=2 
                   group by feeitem.fee_item_id
                   order by feeitem.fee_preference ASC
                   ", array($token));
    }

}
