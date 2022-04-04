<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CitizenPaymentEntry
 *
 * @author nic
 */
class CitizenPaymentEntry extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_citizen_payment_entry';
    public $primaryKey = 'payment_id';
function get_all_payment($token,$lang) {
        return $this->query("select pay.*,mode.payment_mode_desc_$lang,fi.fee_item_desc_$lang FROM ngdrstab_trn_citizen_payment_entry pay
LEFT Join ngdrstab_mst_article_fee_items fi on pay.account_head_code=fi.account_head_code
LEFT JOIN ngdrstab_mst_payment_mode mode on pay.payment_mode_id=mode.payment_mode_id
 WHERE  pay.token_no=? ", array($token));
    }
    
}
