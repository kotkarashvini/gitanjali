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
class CasePaymentDetails extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_case_payment_details';
    public $primaryKey = 'casepid';

    function get_all_payment($case_id, $user_id) {
        return $this->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_case_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.case_id=? AND pay.user_id=? ", array($case_id, $user_id));
    }

    //----------------------get Payment Detail-- by Shridhar----------------------------------------------------------------
//    function get_payment_detail($doc_token_id = NULL, $payment_id = NULL) {
//        return $this->find('first', array('fields' => array('payment.*', 'aps.office_id', 'office.office_name_en', 'office.office_name_ll', 'bank.bank_name', 'bank_branch.branch'),
//                    'joins' => array(
//                        array('table' => 'ngdrstab_trn_application_submitted', 'alias' => 'aps', 'conditions' => array('payment.case_id=aps.case_id')),
//                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'conditions' => array('office.office_id=aps.office_id')),
//                        array('table' => 'ngdrstab_mst_bank', 'alias' => 'bank', 'type' => 'left', 'conditions' => array('bank.bank_id=CAST(payment.bank_id as integer)')),
//                        array('table' => 'ngdrstab_mst_bank_branch', 'alias' => 'bank_branch', 'type' => 'left', 'conditions' => array('bank_branch.id=CAST(payment.branch_id as integer)'))
//                    ),
//                    'conditions' => array('pid' => $payment_id, 'payment.case_id' => $doc_token_id)
//        ));
//    }

}
