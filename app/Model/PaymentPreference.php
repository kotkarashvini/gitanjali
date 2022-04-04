<?php

class PaymentPreference extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_payment_preference_details';

//-----------------------------------get Payment Account Head(Stamp Duty, Registration,...) by Shridhar-----------------------------------------------------------------------
    public function get_payment_acc_head_detail($doc_token_no = NULL, $payment_id = NULL) {
        return $this->find('all', array('fields' => array('PaymentPreference.fee_item_id', 'item.fee_item_desc_en', 'PaymentPreference.amount'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=PaymentPreference.fee_item_id'))
                    ),
                    'conditions' => array('token_no' => $doc_token_no, 'pid' => $payment_id)
        ));
    }

}
