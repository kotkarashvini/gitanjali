<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evalrule
 *
 * @author Administrator
 */
class article_fee_items extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article_fee_items';
    public $primaryKey = 'fee_item_id';
    public $virtualFields = array('fee_input_item' => 'CONCAT(fee_param_code|| \' : \' || fee_item_desc_en)');

    public function get_fee_items_old($lang, $sd_calc_flag) {
        return $this->query("select a.*,b.fee_type_desc_" . $lang . ",c.usage_param_type_desc_" . $lang . "
                            from ngdrstab_mst_article_fee_items a
                            left outer join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_param_type_id
                            left outer join ngdrstab_mst_items_types c on c.usage_param_type_id=a.fee_param_type_id
                            where a.sd_calc_flag=?", array($sd_calc_flag));
    }
    public function get_fee_items($lang) {
        return $this->query("select a.*,b.fee_type_desc_" . $lang . ",c.usage_param_type_desc_" . $lang . "
                            from ngdrstab_mst_article_fee_items a
                            left outer join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_param_type_id
                            left outer join ngdrstab_mst_items_types c on c.usage_param_type_id=a.fee_param_type_id
                            ");
    }

    public function get_param_code($item_id = NULL) {
        return $this->find('list', array('fields' => array('fee_item_id', 'fee_param_code'), 'conditions' => array('fee_item_id' => $item_id)));
    }

    public function get_certificate_accheadcode($flag) {
        if($flag=='C'){
        $result = $this->find('list', array('fields' => array('fee_item_id', 'account_head_code'), 'conditions' => array('fee_item_id' => 32)));
        if (!empty($result)) {
            return $result[32];
        } else {
            return NULL;
        }
        }else{
            $result = $this->find('list', array('fields' => array('fee_item_id', 'account_head_code'), 'conditions' => array('fee_item_id' => 33)));
            if (!empty($result)) {
            return $result[33];
        } else {
            return NULL;
        }
        }
 
        if (!empty($result)) {
            return $result[32];
        } else {
            return NULL;
        }
    }

}
