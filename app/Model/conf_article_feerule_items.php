<?php

class conf_article_feerule_items extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_article_feerule_items';
    public $virtualFields = array('fee_input_item' => 'CONCAT(conf_article_feerule_items.fee_param_code|| \' : \' || fee_item_desc_en)');
    public $primaryKey = 'article_rule_item_id';

    function get_feerule_item($article_id) {//by article
        return $this->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_en'),
                    'conditions' => array('conf_article_feerule_items.article_id' => $article_id, 'item.gen_dis_flag' => 'Y'), 'order' => 'item.fee_item_desc_en',
                    'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                            'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')))));
    }

    //-----------------------get Items by Rule ID----------------------------------------------------------------------------------
    function get_linked_items($rule_id) {
        return $this->find('list', array('fields' => array('fee_item_id', 'fee_param_code'), 'conditions' => array('item.sd_calc_flag' => 'Y', 'fee_rule_id' => $rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id = conf_article_feerule_items.fee_item_id'))
        )));
    }

    public function validateFields_sdcacl($lang, $feeRule_id = NULL, $article_id = NULL) {

        $GBodyflag = ClassRegistry::init('article')->find('first', array('fields' => array('gov_body_applicable'), 'conditions' => array('article_id' => $article_id)));
        $address_flag = ($GBodyflag) ? $GBodyflag['article']['gov_body_applicable'] : 'N';


        if (!is_null($feeRule_id)) {

            $optional_fees = ClassRegistry::init('article_fee_subrule')->find('list', array('fields' => array('fee_subrule_id', 'item.fee_item_desc_' . $lang),
                'conditions' => array('optional_flag' => 'Y', 'fee_rule_id' => $feeRule_id),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))
                )
            ));
            if ($optional_fees) {
             //  $fieldlist['subrule_id']['checkbox'] = 'is_required';
            }
        } else {
           //$fieldlist['subrule_id']['checkbox'] = 'is_required';
        }

        
        $fieldlist['article_id']['select'] = 'is_select_req';
        $fieldlist['fee_rule_id']['select'] = 'is_select_req';
        $fieldlist['cons_amt']['text'] = 'is_integer';
       if ($address_flag == 'Y' && is_numeric($article_id)) {
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
            $fieldlist['corp_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
        }else if (!is_numeric($article_id)){
			 $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
            $fieldlist['corp_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
		}

        $conditions = array();
        if ($feeRule_id) {
            $conditions['conf_article_feerule_items.fee_rule_id'] = $feeRule_id;
        }
        $itemdata = $this->find('all', array('fields' => array('item.fee_item_id', 'item.fee_param_code', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.vrule'), 'order' => 'fee_item_desc_' . $lang, 'conditions' => $conditions,
            'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id'))),
        ));

        foreach ($itemdata as $item) {
            $prm_code = $item['item']['fee_param_code'];
            if ($item['item']['list_flag'] == 'Y') {
                $fieldlist[$prm_code]['select'] = $item['item']['vrule'];
            } else {
                $fieldlist[$prm_code]['text'] = $item['item']['vrule'];
            }
        }
        //    pr($fieldlist);exit;
        return $fieldlist;
    }

    function get_linked_items_json($rule_id) {
        return $this->find('list', array('fields' => array('fee_param_code', 'fee_input_item'), 'conditions' => array('item.sd_calc_flag' => 'Y', 'fee_rule_id' => $rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id = conf_article_feerule_items.fee_item_id'))
        )));
    }

    //-----------------------------copy Fee Rule Items----Modified Date:12-April-2017--------------------------------------------------
    public function copy_items($article_id, $from_id, $to_id, $req_ip, $user_id, $state_id, $created_date) {
        return $this->query("insert into ngdrstab_conf_article_feerule_items(article_id,fee_rule_id,fee_item_id,fee_param_code,sd_calc_flag,vrule,req_ip,user_id,state_id,created)
                select $article_id,$to_id,fee_item_id,fee_param_code,sd_calc_flag,vrule,'$req_ip',$user_id,$state_id,'$created_date' from ngdrstab_conf_article_feerule_items where fee_rule_id=? ", array($from_id));
    }

    public function fieldlist($doc_lang, $advocate_name_flag, $article_id = NULL) {
       
        $fieldlist = array();
        if (!is_null($article_id)) {
           $dynamicfields = $this->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $doc_lang, 'conf_article_feerule_items.vrule'),
                'conditions' => array('item.gen_dis_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $doc_lang,
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                        'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id', 'article_id' => $article_id)))));
        } else {
            
            
             $dynamicfields = $this->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $doc_lang, 'conf_article_feerule_items.vrule'),
                'conditions' => array('item.gen_dis_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $doc_lang,
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                        'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')))));
        }
        //advocate name flag
        // pr($dynamicfields);exit;
        foreach ($dynamicfields as $field) {
            if (!empty($field['conf_article_feerule_items']['vrule'])) {
                $fieldlist['fieldval_' . $field['conf_article_feerule_items']['fee_param_code']]['text'] = $field['conf_article_feerule_items']['vrule'];
            }
        }
         $no_of_pages=$this->query('select conf_bool_value from ngdrstab_conf_reg_bool_info where reginfo_id=90');
       
          $circle=$this->query('select conf_bool_value from ngdrstab_conf_reg_bool_info where reginfo_id=100');
           $tal_compulsary=$this->query('select conf_bool_value from ngdrstab_conf_reg_bool_info where reginfo_id=102');
       
        $fieldlist['local_language_id']['select'] = 'is_select_req';
         $fieldlist['manual_reg_no']['text'] = 'is_required,is_alphanumspacedash';

        $fieldlist['article_id']['select'] = 'is_select_req';
        if(!empty($no_of_pages)){
            if($no_of_pages[0][0]['conf_bool_value']=='Y'){
        $fieldlist['no_of_pages']['text'] = 'is_required,is_numeric_nonzero';
            }
        }
        $fieldlist['exec_date']['text'] = 'is_required';
        $fieldlist['ref_doc_no']['text'] = 'is_doc_num_format';
        // $fieldlist['title_id']['select'] = 'is_select_req';
        $fieldlist['ref_doc_date']['text'] = 'is_date_empty';
        $fieldlist['link_doc_date']['text'] = 'is_date_empty';
        $fieldlist['doc_writer_name']['text'] = 'is_required,is_alphaspacedash';
        $fieldlist['link_doc_no']['text'] = 'is_doc_num_format';
        $fieldlist['district_id']['select'] = 'is_select_req';
        if(!empty($circle)){
            if($circle[0][0]['conf_bool_value']=='Y'){
                
                         if(!empty($tal_compulsary)){
                             if($tal_compulsary[0][0]['conf_bool_value']=='Y'){
                              $fieldlist['taluka_id']['select'] = 'is_select_req';
                         }
       
            }
        }
        }
       // $fieldlist['taluka_id']['select'] = 'is_select_req';
        $fieldlist['office_id']['select'] = 'is_select_req';
        if ($advocate_name_flag == 'Y') {
            $fieldlist['adv_name_en']['text'] = 'is_alphaspace';
        }

        return $fieldlist;
    }
    
     public function stampdutyfields($doc_lang,$article_id = NULL){
            $dynamicfields = $this->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $doc_lang, 'conf_article_feerule_items.vrule'),
                'conditions' => array('item.sd_calc_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $doc_lang,
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                        'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')))));

        $fieldlist = array();
          foreach ($dynamicfields as $field) {
            if (!empty($field['conf_article_feerule_items']['vrule'])) {
                $fieldlist[$field['conf_article_feerule_items']['fee_param_code']]['text'] = $field['conf_article_feerule_items']['vrule'];
            }
        }
        return $fieldlist;
        
        
    }


}
