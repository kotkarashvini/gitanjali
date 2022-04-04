<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BankPayment
 *
 * @author nic
 */
class BankPayment extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_bank_payment';
    public $primaryKey = 'trn_id';

   
    public function mapping_account_heads($payment_mode_id = NULL,$LIMIT=1) {
        $result = array();
        if ($payment_mode_id != NULL) {
           
            $result = $this->query("select item.fee_item_desc_en,item.account_head_code from ngdrstab_mst_payment_mode_mapping map
JOIN ngdrstab_mst_article_fee_items as item ON item.fee_item_id=map.fee_item_id
JOIN ngdrstab_mst_payment_mode as paymode ON paymode.payment_mode_id=map.payment_mode_id
where paymode.payment_mode_id=? ORDER BY mapping_id ASC LIMIT ".$LIMIT, array($payment_mode_id));
        }

        return $result;
    }

}
