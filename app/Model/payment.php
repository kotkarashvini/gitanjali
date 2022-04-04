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
class payment extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_payment_details';
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

    public function stampduty_fee_details($token, $lang, $article_id, $payment_mode_id = NULL) {
        $info = ClassRegistry::init('genernalinfoentry')->find("first", array('conditions' => array('token_no' => $token)));
        if (!empty($info) && !empty($info['genernalinfoentry']['presentation_date'])) {
            $pdate = date("Y-m-d", strtotime($info['genernalinfoentry']['presentation_date']));
        } else {
            $pdate = date('Y-m-d', strtotime('+2 years'));
        }

        if (is_null($payment_mode_id)) {

            return $this->query("SELECT 
feeitem.account_head_code,
feeitem.fee_item_id,
feeitem.fee_item_desc_$lang,
feeitem.receipt_flag, 
feeitem. fee_preference,
                   (
				   SUM(stampd.final_value) -
				   coalesce(
								(
								SELECT 
								SUM(stampd1.final_value) as totalsd1
												   FROM
												   ngdrstab_trn_fee_calculation_detail stampd1 
												   LEFT JOIN ngdrstab_trn_fee_calculation stamp1     ON stampd1.fee_calc_id = stamp1.fee_calc_id
												   LEFT JOIN ngdrstab_mst_article_fee_items feeitem1  ON feeitem1.fee_item_id=stampd1.fee_item_id
												   WHERE  stamp1.token_no=?  
												   AND stamp1.delete_flag='N' 
												   AND feeitem1.fee_param_type_id=2 
										           AND stamp1.article_id IN(9998)
										           AND feeitem1.fee_item_id=feeitem.fee_item_id
												   group by feeitem1.fee_item_id
												   order by feeitem1.fee_preference ASC
								)
						 ,0)
					)-coalesce(
(select coalesce(online_adj_amt,counter_adj_amt,0) from ngdrstab_trn_stamp_duty_adjustment_detail where  token_no=? and feeitem.fee_item_id=2)
					,0)- coalesce(
(select coalesce(invest_stamp_amount,0) from ngdrstab_trn_stamp_duty_investment_detail where  token_no=? AND online_invest_doc_date > '$pdate'::date -365 AND  feeitem.fee_item_id=2)
					,0)  as totalsd

FROM ngdrstab_trn_fee_calculation_detail stampd 
LEFT JOIN ngdrstab_trn_fee_calculation stamp     ON stampd.fee_calc_id = stamp.fee_calc_id
LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.fee_item_id=stampd.fee_item_id
WHERE  stamp.token_no=?  
AND stamp.delete_flag='N' 
AND feeitem.fee_param_type_id=2 
AND stamp.article_id IN(?,9999,9997)
group by feeitem.fee_item_id
order by feeitem.fee_preference ASC

                   ", array($token, $token, $token, $token, $article_id));
        } else {
            return $this->query("SELECT 
feeitem.account_head_code,
feeitem.fee_item_id,
feeitem.fee_item_desc_$lang,
feeitem. fee_preference,
                   (
				   SUM(stampd.final_value) -
				   coalesce(
								(
								SELECT 
								SUM(stampd1.final_value) as totalsd1
												   FROM
												   ngdrstab_trn_fee_calculation_detail stampd1 
												   LEFT JOIN ngdrstab_trn_fee_calculation stamp1     ON stampd1.fee_calc_id = stamp1.fee_calc_id
												   LEFT JOIN ngdrstab_mst_article_fee_items feeitem1  ON feeitem1.fee_item_id=stampd1.fee_item_id
												   WHERE  stamp1.token_no=?  
												   AND stamp1.delete_flag='N' 
												   AND feeitem1.fee_param_type_id=2 
										           AND stamp1.article_id IN(9998)
										           AND feeitem1.fee_item_id=feeitem.fee_item_id
												   group by feeitem1.fee_item_id
												   order by feeitem1.fee_preference ASC
								)
						 ,0)
					)-coalesce(
(select coalesce(online_adj_amt,counter_adj_amt,0) from ngdrstab_trn_stamp_duty_adjustment_detail where  token_no=? and feeitem.fee_item_id=2)
					,0)- coalesce(
(select coalesce(invest_stamp_amount,0) from ngdrstab_trn_stamp_duty_investment_detail where  token_no=? AND online_invest_doc_date > '$pdate'::date -365 AND  feeitem.fee_item_id=2)
					,0)  as totalsd

FROM ngdrstab_trn_fee_calculation_detail stampd 
LEFT JOIN ngdrstab_trn_fee_calculation stamp     ON stampd.fee_calc_id = stamp.fee_calc_id
LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.fee_item_id=stampd.fee_item_id
WHERE  stamp.token_no=?  
AND stamp.delete_flag='N' 
AND feeitem.fee_param_type_id=2 
AND feeitem.fee_item_id IN(select fee_item_id from ngdrstab_mst_payment_mode_mapping where payment_mode_id=?)
AND stamp.article_id IN(?,9999,9997)
group by feeitem.fee_item_id
order by feeitem.fee_preference ASC

                   ", array($token, $token, $token, $token, $payment_mode_id, $article_id));
        }
    }

    /* Created By Shrishail 2-jun-17 */

    function validate_payment($feedetails, $payment, $regconf_amount_tally) {

        if (empty($regconf_amount_tally)) {
            $sdamount = 0;
            $paidamount = 0;
            foreach ($feedetails as $fee):
                $sdamount += $fee[0]['totalsd'];
                $amount = 0;

                foreach ($payment as $paydetails):
                    $paydetails = $paydetails[0];

                    if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                        $amount += $paydetails['pamount'];
                        $paidamount += $paydetails['pamount'];
                    }
                endforeach;
            endforeach;

            if ($sdamount <= $paidamount) {
                return 1;
            } else {
                return 0;
            }
        } else {
            $headwise_tally_flag = 1;
            $sdamount = 0;
            $paidamount = 0;
            foreach ($feedetails as $fee):
                $sdamount += $fee[0]['totalsd'];
                $amount = 0;

                foreach ($payment as $paydetails):
                    $paydetails = $paydetails[0];

                    if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                        $amount += $paydetails['pamount'];
                        $paidamount += $paydetails['pamount'];
                        //$test++;
                    }
                endforeach;
                $balance = $fee[0]['totalsd'] - $amount;
                if ($balance > 0) {
                    $headwise_tally_flag = 0;
                }
            endforeach;
            return $headwise_tally_flag;
        }
    }

    //BY shrishail 20/07/2017
    function fee_headings($token, $lang, $article_id, $payment_mode_id) {
        if (!is_numeric($payment_mode_id)) {
            $payment_mode_id = NULL;
        }
        $feedetails = $this->stampduty_fee_details($token, $lang, $article_id);
        $mapping = $this->query("Select payment_mode_id,fee_item_id,max_amount from ngdrstab_mst_payment_mode_mapping where payment_mode_id=?", array($payment_mode_id));
        //pr($mapping);
        $list = array();
        if (!empty($feedetails)) {
            foreach ($feedetails as $fee) {
                foreach ($mapping as $map) {
                    if ($fee[0]['fee_item_id'] == $map[0]['fee_item_id']) {
                     //   pr($fee[0]['totalsd']);
                      //  pr($map[0]['max_amount']);
                        if (is_null($map[0]['max_amount']) || $fee[0]['totalsd'] <= $map[0]['max_amount']){
                            $list[$fee[0]['account_head_code']] = $fee[0]['fee_item_desc_' . $lang];
                        }
                    }
                }
            }
        }
        return $list;
    }
    function validate_online_payment($token = NULL) {
        $flag = 0;
        // pr($token);
        if ($token != NULL) {
            $flag = 1;
            $payment = $this->query("select pay.*,mode.payment_mode_desc_en,mode.verification_flag  FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=? AND mode.verification_flag=? ", array($token, 'Y'));
            //pr($payment);
            if (!empty($payment)) {
                foreach ($payment as $single) {
                    if ($single[0]['verification_flag'] == 'Y' && $single[0]['defacement_flag'] == 'N') {
                        $flag = 0;
                    }
                }
            }
        }
//exit;
        return $flag;
    }

}
