<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of District
 *
 * @author Acer
 */
class fee_exemption extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_fee_exemption';
    public $primaryKey = 'fee_exemption_id';

//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );
//    public $primaryKey = 'rounding_id';
    public function update_exemption($doc_token_no) {
        $this->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($doc_token_no));
        $this->query("insert into ngdrstab_trn_fee_exemption(fee_calc_id,fee_rule_id,article_id,token_no,exemption_amt)
                        SELECT fc.fee_calc_id,fc.fee_rule_id,fc.article_id,fc.token_no,fcd.final_value as exemption_amt from ngdrstab_trn_fee_calculation fc
                            left outer join ngdrstab_trn_fee_calculation_detail fcd on fcd.fee_calc_id=fc.fee_calc_id and fcd.final_value is not null
                        where fc.token_no=? and fc.delete_flag='N' and fc.article_id=9998 ", array($doc_token_no));
    }

}
