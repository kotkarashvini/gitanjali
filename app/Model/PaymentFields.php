<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class PaymentFields extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_payment_fields';
    public $primaryKey='id';
   public function fieldlist($payment_mode=NULL) {
        $options['is_input_flag']='Y';
        if(!is_null($payment_mode)){
            $options['payment_mode_id']=$payment_mode;
        }
        $result = $this->find("all", array('conditions' => $options));
        $fieldlist = array();
        foreach ($result as $record) {
            $record = $record['PaymentFields'];
            $fieldlist[$record['field_name']][$record['field_type']] = $record['vrule'];
        }
        return $fieldlist;
    }
}