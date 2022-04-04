<?php
class fee_itemstype extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_items_types';
    public $primaryKey = 'id';
    
     public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_items_types';
        $duplicate['PrimaryKey'] = 'id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'usage_param_type_desc_' . $language['mainlanguage']['language_code']);
        }
        
        array_push($fields, 'usage_param_type_id');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         $fieldlist['usage_param_type_id']['text'] = 'is_required,is_numeric'; 
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['usage_param_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_aplhanumericspace';
            } else {
                $fieldlist['usage_param_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
          
//          $fieldlist['start_date']['text'] = 'is_required';
//            $fieldlist['end_date']['text'] = 'is_required';
//            $fieldlist['active_flag']['select'] = 'is_yes_no';
//            $fieldlist['verification_flag']['select'] = 'is_yes_no';
           

        return $fieldlist;
    }
    
    function get_payment_mode_counter($lang)
    {
        return $this->find('list', array('fields' => array('payment_mode.payment_mode_id', 'payment_mode.payment_mode_desc_' . $lang), 'conditions' => array('verification_flag' => 'N','active_flag'=>'Y'), 'order' => 'payment_mode_id ASC'));
    }
    function get_payment_mode_online($lang)
    {
        return $this->find('list', array('fields' => array('payment_mode.payment_mode_id', 'payment_mode.payment_mode_desc_' . $lang), 'conditions' => array('verification_flag' => 'Y','active_flag'=>'Y'), 'order' => 'payment_mode_id ASC'));
    }
    
    function get_all_payment($token,$user_id)
    {
      return $this->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=$token AND pay.user_id=$user_id ");  
    }
}
