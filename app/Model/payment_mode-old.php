<?php

class payment_mode extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_payment_mode';
    
    function get_payment_mode($lang)
    {
        return $this->find('list', array('fields' => array('payment_mode.payment_mode_id', 'payment_mode.payment_mode_desc_' . $lang), 'conditions' => array('payment_mode_id NOT IN' => array('3', '8')), 'order' => 'payment_mode_id ASC'));
    }
    
    function get_all_payment($token,$user_id)
    {
      return $this->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=$token AND pay.user_id=$user_id ");  
    }
}
