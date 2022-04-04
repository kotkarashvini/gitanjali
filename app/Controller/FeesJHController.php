<?php

App::import('Controller', 'Reports'); // mention at top
App::import('Controller', 'DynamicVariables'); // mention at top

class FeesJHController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        $this->response->disableCache();
//        $this->Security->unlockedActions = array('article_fee_items', 'article_fee_item_delete', 'fee_item_list', 'get_article_fee_item_list_delete',
//            'fee_rule_index', 'remove_fee_rule', 'copy_fee_rule', 'article_fee_rule', 'article_fee_rule_item_linkage', 'remove_fee_rule_item', 'article_fee_sub_rule', 'copy_fee_sub_rule', 'remove_fee_sub_rule',
//            'get_json_article_rule_list', 'get_article_rule_check_list', 'get_article_fee_rule_items', 'get_article_fee_rule_item_input',
//            'get_article_desc', 'get_fee_max_order_id',
//            'calculate_mv', 'fee_calculation', 'get_article_gov_body_flag', 'calculate_fees', 'get_calc_fee_rule_list', 'save_fee_calculation_detail',
//            'check_fee_condition', 'add_value_to_formula', 'view_fee_calculation', 'view_sd_calc',
//            'update_fee_exemption', 'view_exemption',
//            'article_item_link_not_sd', 'get_articledependentfeild');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
        $this->request->addDetector('ssl', array('callback' => function() {
                return CakeRequest::header('X-Forwarded-Proto') == 'https';
            }));
    }

//------------------***-------------------------------------------- Get  article linked items with Input Box -------------------------------------------------------------------
       public function get_article_fee_rule_item_input_jh($fee_rule_id = NULL, $doc_token_no = NULL, $lang = 'en') {
        try {
            array_map([$this, 'loadModel'], ['party_entry', 'article_fee_subrule', 'article_fee_item_list', 'property_details_entry', 'valuation_details', 'fee_item_val']);
            $fee_rule_id = isset($this->request->data['feerule_id']) ? $this->request->data['feerule_id'] : $fee_rule_id;
            $exmption = isset($this->request->data['exmption']) ? 'Y' : 'N';
            $token = $this->Session->read('Selectedtoken');

            $this->set('exmption', $exmption);
            // print_r($fee_rule_id);
//            $token_no = isset($this->request->data['token_no']) ? $this->request->data['token_no'] : NULL; //18-8-2017s
            if ($fee_rule_id && is_numeric($fee_rule_id)) {

                $condition['conf_article_feerule_items.fee_rule_id'] = ($this->request->data['feerule_id']) ? $this->request->data['feerule_id'] : $fee_rule_id;
                $condition['item.sd_calc_flag'] = 'Y';
                $condition['item.fee_param_code !='] = 'FAR'; //for developed Land Type                
                $doc_token_no = isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : (($doc_token_no) ? $doc_token_no : NULL);
                $doc_token_no_cond = ($doc_token_no) ? (' and value.token_no=' . $doc_token_no) : (' AND value.token_no is null'); //18-8-2017
                $lang = isset($this->request->data['lang']) ? $this->request->data['lang'] : (($lang) ? $lang : $this->Session->read('sess_language'));
                /* --------------for Genral Info Form in Citizen Entry-------------- */
                if (isset($this->request->data['gen_info'])) {
                    $condition['item.gen_dis_flag'] = 'Y';
                }

                // pr($doc_token_no); exit;
                //$itemvalue=$this->article_fee_item_list->query("select * from ngdrstab_trn_generalinformation where token_no=$doc_token_no");
                /* --------------------------------------------------------------- */
                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.display_flag', 'is_hidden'), 'order' => array('item.display_flag', 'item.fee_item_desc_' . $lang), 'conditions' => $condition,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
                        array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
                    )
                ));
                //print_r($itemdata);
                $items_list = array();
                foreach ($itemdata as $FeeItem) {
                    if ($FeeItem['item']['list_flag'] == 'Y') {
                        $items_list[$FeeItem['item']['fee_param_code']] = $this->article_fee_item_list->find('list', array('fields' => array('list_item_value', 'fee_item_list_desc_' . $lang), 'conditions' => array('fee_item_id' => $FeeItem['item']['fee_item_id']))); //fee_item_list_id
                    }
                    //It is used to fetch no. of pages from generalinformation table
                    $item_code = $FeeItem['item']['fee_param_code'];
                    if ($item_code == 'FAJ') {
                        $itemvalue = $this->article_fee_item_list->query("select no_of_pages from ngdrstab_trn_generalinformation where token_no=$doc_token_no");
                        $value = $itemvalue[0][0]['no_of_pages'];
                    }
                    //It is used to fetch no. of pages from generalinformation table
                    //It is used to fetch no. of pages as per party count for Jharkhand FCX=No. of parties
                    if ($item_code == 'FCX') {
                        $total_party = $this->number_of_pages($doc_token_no);
                    }
                    //It is used to fetch no. of pages as per party count for Jharkhand

                    $flag = $this->get_prop_same_usage_flag($doc_token_no);
                    
                    //FCQ= Governing body(Urban/Rural) fetch from property on load for multiple property
                    if ($flag == 0) {
                        if ($item_code == 'FCQ') {
                            $tokenno = $this->property_details_entry->find('first', array('fields' => array('val_id'), 'conditions' => array('token_no' => $doc_token_no), 'order' => 'property_id'));
                            foreach ($tokenno as $tokenid) {
                                $valuation_id = $tokenid['val_id'];
                            }
                            $gov_body_result = $this->property_details_entry->find('first', array('fields' => array('developed_land_types_id'),
                                'conditions' => array('val_id' => $valuation_id),
                                'joins' => array(array('table' => 'ngdrstab_mst_developed_land_types', 'alias' => 'land', 'type' => 'inner', 'foreignKey' => false,
                                        'conditions' => array('property_details_entry.developed_land_types_id=land.developed_land_types_id')))));
                        }
                    }

                    //FCL= extent area fetch on load for multiple property
                    if ($flag == 0) {
                        if ($item_code == 'FCL') {
                            $tokenno = $this->property_details_entry->find('first', array('fields' => array('val_id'), 'conditions' => array('token_no' => $doc_token_no), 'order' => 'property_id'));
                            foreach ($tokenno as $tokenid) {
                                $valuation_id = $tokenid['val_id'];
                            }
                            $record = $this->valuation_details->find('first', array('fields' => array('item_id', 'item_value'), 'conditions' => array('val_id' => $valuation_id), 'joins' => array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('valuation_details.item_id=item.usage_param_id', 'item.usage_param_code' => 'AAA')))));
                            if (!empty($record)) {
                                $area = $record['valuation_details']['item_value'];
                            }
                        }
                    }

                    //FCK= Sale deed transfer type(M to F, etc.) fetch from party table
                    if ($item_code == 'FCK') {
                        $party_gender1 = array();
                        $party_gender2 = array();

                        $party_result1 = $this->fee_item_val->find('all', array('fields' => array('mapping_ref_val'), 'conditions' => array('token_no' => $doc_token_no, 'mapping_ref_typeid' => 56), 'order' => 'mapping_ref_id DESC'));
                        $party_result2 = $this->fee_item_val->find('all', array('fields' => array('mapping_ref_val'), 'conditions' => array('token_no' => $doc_token_no, 'mapping_ref_typeid' => 57), 'order' => 'mapping_ref_id DESC'));

                        if (!empty($party_result1)) {
                            foreach ($party_result1 as $party1) {
                                $gender1 = $party1['fee_item_val']['mapping_ref_val'];
                                array_push($party_gender1, $gender1);
                            }
                        }

                        if (!empty($party_result2)) {
                            foreach ($party_result2 as $party2) {
                                $gender2 = $party2['fee_item_val']['mapping_ref_val'];
                                array_push($party_gender2, $gender2);
                            }
                        }

                        if (!empty($party_gender1)) {
                            foreach ($party_gender1 as $gender_new1) {
                                if ($gender_new1 != $party_gender1[0]) {
                                    $gen_val1 = 3;
                                }
                                if (@$gen_val1 != 3) {
                                    if ($party_gender1[0] == 1) {
                                        $gen_val1 = 1;
                                    } else if ($party_gender1[0] == 2) {
                                        $gen_val1 = 2;
                                    }
                                }
                            }
                        }

                        if (!empty($party_gender2)) {
                            foreach ($party_gender2 as $gender_new2) {
                                if ($gender_new2 != $party_gender2[0]) {
                                    $gen_val2 = 3;
                                }
                                if (@$gen_val2 != 3) {
                                    if ($party_gender2[0] == 1) {
                                        $gen_val2 = 1;
                                    } else if ($party_gender2[0] == 2) {
                                        $gen_val2 = 2;
                                    }
                                }
                            }
                        }

                        if (isset($gen_val1) && isset($gen_val2)) {
                            if ($gen_val1 == 1 && $gen_val2 == 2) {
                                $gender_new = 1;
                            } else if ($gen_val1 == 2 && $gen_val2 == 1) {
                                $gender_new = 2;
                            } else {
                                $gender_new = 3;
                            }
                        } else {
                            $gender_new = 3;
                        }
                    }

                    if ($item_code == 'FDA') {
                        $party_result = $this->party_entry->find('all', array('fields' => array('gender_id', 'party_full_name_en'), 'conditions' => array('token_no' => $token)));
                        $gender_result = array();
                        foreach ($party_result as $party) {
                            $gender = $party['party_entry']['gender_id'];
                            array_push($gender_result, $gender);
                        }

                        $gender_value = 0;
                        foreach ($gender_result as $gender_result1) {
                            if ($gender_result1 != $gender_result[0]) {
                                $gender_value = 3;
                            } else {
                                if (@$gender_value != 3) {
                                    if ($gender_result1 == 1) {
                                        $gender_value = 1;
                                    } else if ($gender_result1 == 2) {
                                        $gender_value = 2;
                                    }
                                }
                            }
                        }
                    }
                }
                $form_name = (isset($this->request->data['form_name'])) ? $this->request->data['form_name'] : 'frm';
                $optional_fees = $this->article_fee_subrule->find('list', array('fields' => array('fee_subrule_id', 'item.fee_item_desc_' . $lang),
                    'conditions' => array('optional_flag' => 'Y', 'fee_rule_id' => $fee_rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))
                    )
                ));

                $this->set(compact('gender_value', 'area', 'gender_new', 'flag', 'gov_body_result', 'total_party', 'itemvalue', 'itemdata', 'items_list', 'form_name', 'lang', 'optional_fees'));
                $this->set('genderList', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang))));
            } else {
                return 'No Data Found';
            }
        } catch (Exception $e) {
            pr($e);
//            return 'error while getting fee rule inputs';
        }
    }

//------------------***------------------------------------------Fee Calulation for stamp duty,Registration,Handeling Charges and Others-----------------------------------------------      
    public function calculate_mv_jh($frm = NULL) {
        try {
            $this->autoRender = FALSE;
            $mv = 1;
            $this->loadModel('conf_article_feerule_items');
            $frm = ($frm) ? $frm : $this->request->data['frm'];
            if ($frm['fee_rule_id']) {
                //if market value is already available then return same else calculate Market Value
                if (isset($frm['FAA'])) {
                    return $frm['FAA'];
                } else {
                    $articleItems = $this->conf_article_feerule_items->get_linked_items($frm['fee_rule_id']);
                    foreach ($articleItems as $items) {
                        $mv *= $frm[$items];
                    }
                    return $mv;
                }
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            return 'error while calculating MV';
        }
    }

    //vishal changs FCR district I FEE
    public function calculate_fees_jh($itemValues = NULL, $returnCalc = 'N') {
        /*
          1) for Property SD (article=>(Agreemnet,Convenence etc.)) (call after property Save in Citizen Entry, and respective)
          $sd_values = array(
          'token_no'=>9
          'property_id' => 33,
          'article_id' => 68,
          'FAA' => 500000,//valuation Amount
          'village_id' => 17
          );
          $this->calculatefees($sd_values);

          2) for Scanning & Handeling Charges (Common Rule Article, Rule Like)
          $sd_values = array(
          'token_no'=>9
          'FAI' => 15,//No of Pages
          );
         */
        //  pr($itemValues);exit;
        try {
            $this->autoRender = FALSE;

            array_map([$this, 'loadModel'], ['article_screen_mapping', 'fees_calculation', 'conf_reg_bool_info', 'fees_calculation_detail', 'valuation', 'article_fee_rule', 'article_fee_subrule', 'article', 'article_fee_items', 'conf_article_feerule_items', 'property_details_entry', 'VillageMapping']);

            $frm = ($itemValues) ? $itemValues : (isset($this->request->data['frm']) ? $this->request->data['frm'] : $this->request->data);
            //  $this->check_csrf_token_withoutset($frm['csrftoken']);
            $frm['token_no'] = isset($frm['doc_token_no']) ? $frm['doc_token_no'] : (isset($frm['token_no']) ? $frm['token_no'] : NULL);
            $tmp = (isset($frm['village_id'])) ? ($this->VillageMapping->get_ulb_land_type($frm['village_id'])) : NULL;
            //pr($frm);
            $citizen_token_no = $this->Session->read('Selectedtoken');
            if (!is_null($citizen_token_no)) {
                $officedistrict = $this->article->query("select info.token_no from ngdrstab_trn_generalinformation as info
                                                            join ngdrstab_trn_office_village_linking as link on link.office_id=info.office_id
                                                            join ngdrstab_trn_property_details_entry as prop on prop.token_no=info.token_no
                                                            AND prop.village_id=link.village_id
                                                            where info.token_no=? and jurisdiction_flag='N'", array($citizen_token_no));


                if (!empty($officedistrict)) {


                    $itemValues['FCR'] = 1;


                    $this->request->data['frm']['FCR'] = 1;
                } else {
                    $this->request->data['frm']['FCR'] = 0;
                    $itemValues['FCR'] = 0;
                }
            } else {
                $this->request->data['frm']['FCR'] = 0;
                $itemValues['FCR'] = 0;
            }

            $frm['FCR'] = $itemValues['FCR'];
            // code changes for exemption 
            $vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);
            $consamount = (isset($frm['property_id'])) ? ($this->fees_calculation->field('cons_amt', array('property_id' => $frm['property_id']))) : (isset($frm['cons_amt']) ? $frm['cons_amt'] : NULL);
//          pr($consamount);
//           pr($vamout);
            // min amount fee calculation for config 94
            if (is_numeric($vamout) && is_numeric($consamount)) {
                //min considaration amount calculate config table regconfig
                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 94)));

                if (!empty($regconf)) {
                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                        if ($vamout > $consamount) {
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 107)));
                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                        $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                    $rounded_amt = $this->round_tonext($consamount, $roundto);
                                    $frm['FAA'] = $rounded_amt;
                                }
                                else {
                                    $frm['FAA'] = $consamount;
                                }
                            } else {
                                $frm['FAA'] = $consamount;
                            }
                        } else {
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 107)));
                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                        $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                    $rounded_amt = $this->round_tonext($vamout, $roundto);
                                    $frm['FAA'] = $rounded_amt;
                                }
                                else {
                                    $frm['FAA'] = $vamout;
                                }
                            } else {
                                $frm['FAA'] = $vamout;
                            }
                        }
                    } else {
                        if ($vamout > $consamount) {
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 107)));
                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                        $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                    $rounded_amt = $this->round_tonext($vamout, $roundto);
                                    $frm['FAA'] = $rounded_amt;
                                }
                                else {
                                    $frm['FAA'] = $vamout;
                                }
                            } else {
                                $frm['FAA'] = $vamout;
                            }
                        } else {
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 107)));

                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                    $rounded_amt = $this->round_tonext($consamount, $roundto);
                                    $frm['FAA'] = $rounded_amt;
                                } else {
                                    $frm['FAA'] = $consamount;
                                }
                            } else {
                                $frm['FAA'] = $consamount;
                            }
                        }
                    }
                }
            } else {
                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 107)));
                if (!empty($regconf)) {
                    if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                        if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                            $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                        $rounded_amt = $this->round_tonext($vamout, $roundto);
                        $frm['FAA'] = $rounded_amt;
                    }
                    else {
                        $frm['FAA'] = $vamout;
                    }
                } else {
                    $frm['FAA'] = $vamout;
                }
            }
            // code changes for exemption  end
            if ($tmp) {
                $frm['ulb_type_id'] = $tmp['VillageMapping']['ulb_type_id'];
                $frm['FAR'] = $tmp['VillageMapping']['developed_land_types_id'];
            }
            unset($tmp);

            $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));

            $frm['article_id'] = isset($frm['article_id']) ? $frm['article_id'] : ((isset($frm['exm']) && $frm['exm'] === 'Y') ? 9998 : 9999);

            $optional_subrule_id = $frm['subrule_id'] = isset($frm['subrule_id']) ? $frm['subrule_id'] : NULL;
            $frm['subrule_id'] = ($frm['subrule_id']) ? implode(',', $frm['subrule_id']) : NULL;

            //validations
            $file = new File(WWW_ROOT . 'files/vjsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
//            foreach ($json2array['fieldlist'] as $key => $value) {
//                $frm[$key] = $key;
//            }
            //            }
//            $frm = $this->istrim($frm);
//
//            $errarr = $this->validatedata($frm, $json2array['fieldlist']);
//            $flag = 0;
//            foreach ($errarr as $dd) {
//                if ($dd != "") {
//                    $flag = 1;
//                }
//            }
//            if ($flag == 1) {
//                $errarr['errorcode'] = $errarr;
//                return json_encode($errarr);
//            } else 
//            
             if (isset($this->request->data['property_list'])) {
                            $frm['property_id'] = $this->request->data['property_list'];
             }
             
            // Dynamic Veriable
            if (isset($frm['token_no'])) {
                $obj = new DynamicVariablesController();
                $frm = $obj->veriables_sd($frm['token_no'], $frm, @$frm['property_id']);
            }
            
            $feeRule = $this->get_calc_fee_rule_list_jh($frm);
//            pr($frm);exit;
            if ($feeRule) {
                $success_flag = 'N';
                $feeCalcDetail = array();
                foreach ($feeRule as $rule_id) {
                    $frm['fee_rule_id'] = $rule_id;
                    $articleItems = $this->conf_article_feerule_items->get_linked_items($rule_id);
                    $subruleFlag = $this->article_fee_rule->find('first', array('fields' => array('sub_fee_rule_flag'), 'conditions' => array('fee_rule_id' => $rule_id)));
                    if ($subruleFlag['article_fee_rule']['sub_fee_rule_flag'] == 'Y') {//if have subrule
                        $subrule_conditions['fee_rule_id'] = $rule_id;
                        $subrule_conditions['fee_subrule_id'] = $this->article_fee_subrule->find('list', array('conditions' => array('OR' => array(array('optional_flag' => 'N'), array('fee_subrule_id' => $optional_subrule_id)))));
                        $ulb_type_id = array(0);
                        if (isset($frm['ulb_type_id'])) {
                            array_push($ulb_type_id, $frm['ulb_type_id']);
                        }
                        $subrule_conditions['ulb_type_id'] = $ulb_type_id;

                        $subruleData = $this->article_fee_subrule->find('all', array('fields' => array('article_fee_subrule.*'), 'conditions' => $subrule_conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))), 'order' => 'article_fee_subrule.fee_output_item_id'));

//                        $subruleData = $this->article_fee_subrule->find('all', array('fields' => array('article_fee_subrule.*'), 'conditions' => $subrule_conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))), 'order' => 'article_fee_subrule.fee_output_item_order'));
                      if (isset($this->request->data['property_list'])) {
                            $frm['property_id'] = $this->request->data['property_list'];
                      }
                    
                        if ($subruleData) {
                            foreach ($subruleData as $fsrl) {
                                $fsrl = $fsrl['article_fee_subrule'];
                                $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition_jh($articleItems, $frm, $fsrl);
                                $tmpfeeCalcDetail['fee_subrule_id'] = $fsrl['fee_subrule_id'];
                                $tmpfeeCalcDetail['fee_item_id'] = $fsrl['fee_output_item_id'];
                                $tmpfeeCalcDetail['min_value'] = $fsrl['min_value'];
                                $tmpfeeCalcDetail['max_value'] = $fsrl['max_value'];
                                array_push($feeCalcDetail, $tmpfeeCalcDetail);
                            }
                        } else {
                            echo "Condition Not Match... in Subrule";
                            exit;
                        }
                    } else { //if don't have subrule
                        return 'Rule not Found';
                        exit;
                   }
                }
                if ($feeCalcDetail[0] ['calc_detail'] != -1) {
                    $success_flag = 'Y';
                } else {
                    return "Condition Not Match... in Rule";
                    exit;
                }
                if ($success_flag == 'Y') {
                    $update_condition = array();
                    if (isset($frm['token_no'])) {
                        if (isset($this->request->data['property_list'])) {
                            $frm['property_id'] = $this->request->data['property_list'];
                            if ((isset($frm['property_id']))) {
                                $update_condition['property_id'] = $frm['property_id'];
                            }
                        } else {
                            $property_exists = $this->article_screen_mapping->find('count', array('conditions' => array('minorfun_id' => 2, 'article_id' => $frm['article_id']))); //2 for Property        
                            if ($property_exists != 0) {
                                $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $citizen_token_no), 'order' => 'property_id'));
                                $frm['property_id'] = $prop_id['property_details_entry']['property_id'];
                            }
                        }
                        if (isset($frm['token_no']) && $frm['token_no']) {
                            $update_condition['token_no'] = $frm['token_no'];
                        }
                        $update_condition['fee_rule_id'] = $rule_id;
                        if ($this->fees_calculation->find('count', array('conditions' => $update_condition)) > 0) {
                            $this->fees_calculation->updateAll(array('delete_flag' => "'Y'"), $update_condition);
                            unset($update_condition);
                        }
                    }
                    $frm['property_id'] = (isset($frm['property_id'])) ? $frm['property_id'] : 0;
                    $tmp_article_id = $this->article_fee_rule->find('first', array('fields' => array('article_id'), 'conditions' => array('fee_rule_id' => $frm['fee_rule_id'])));
                    $frm['article_id'] = $tmp_article_id['article_fee_rule']['article_id'];
                    //pr($frm['property_id']);
                    /*                     * ******************chnages by madhuri ****************** */
                    if ($frm['article_id'] != 9999 && $frm['article_id'] != 9997) {
                        if ($frm['property_id']) {
                            $fees_cal = $this->fees_calculation->find('first', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $frm['token_no'], 'property_id' => $frm['property_id'], 'article_id' => $frm['article_id'])));
                            if (!empty($fees_cal)) {
                                $this->property_details_entry->updateAll(array('fee_calc_id' => NULL), array('property_id' => $frm['property_id']));
                                $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $fees_cal['fees_calculation']['fee_calc_id']));
                                $this->fees_calculation->deleteAll(array('token_no' => $frm['token_no'], 'property_id' => $frm['property_id'], 'article_id' => $frm['article_id']));
                            }
                        } else {
                            $fees_cal = $this->fees_calculation->find('first', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $frm['token_no'], 'article_id' => $frm['article_id'])));
                            if (!empty($fees_cal)) {
                                $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $fees_cal['fees_calculation']['fee_calc_id']));
                                $this->fees_calculation->deleteAll(array('token_no' => $frm['token_no'], 'article_id' => $frm['article_id']));
                            }
                        }
                    }
                    if ($frm['article_id'] == 9997) { // fine
                        $fees_cal = $this->fees_calculation->find('first', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $frm['token_no'], 'article_id' => $frm['article_id'], 'fee_rule_id' => $frm['fee_rule_id'])));
                        if (!empty($fees_cal)) {
                            $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $fees_cal['fees_calculation']['fee_calc_id']));
                            $this->fees_calculation->deleteAll(array('token_no' => $frm['token_no'], 'article_id' => $frm['article_id'], 'fee_rule_id' => $frm['fee_rule_id']));
                        }
                    }
                    /*                     * ************end*************** */

                    if ($this->fees_calculation->save($frm)) {
                        $fees_cal_id = $this->fees_calculation->getLastInsertID();
                        if (isset($frm['property_id']) && $frm['property_id']) {
                            $this->property_details_entry->updateAll(array('fee_calc_id' => $fees_cal_id), array('property_id' => $frm['property_id'])); // update fees_calc_id   in property entry table                
                        }
                         if($frm['article_id'] == 9999)
                        {
                            $fees_cal = $this->fees_calculation->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $frm['token_no'], 'article_id' => $frm['article_id'], 'fee_rule_id' => 222,'fee_calc_id' => $fees_cal_id)));
                            
                            if(!empty($fees_cal))
                            {
                                $property_id=$fees_cal['fees_calculation']['property_id'];
                                if($property_id!=0)
                                {
                                   $this->fees_calculation->deleteAll(array('fee_calc_id' => $fees_cal_id));
                                }
                            }
                        }
                        if ($this->save_fee_calculation_detail_jh($fees_cal_id, $frm, $feeCalcDetail, $articleItems)) {
                            if ($frm['article_id'] == 9998 && isset($frm['token_no']) && $frm['token_no']) {
                                $this->update_fee_exemption_jh($frm['token_no']);
                            } else if ($returnCalc == 'Y') {
                                $fees = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) AS fees'), 'conditions' => array('fee_calc_id' => $fees_cal_id, 'item_type_id' => 2)));
                                return $fees[0]['fees'];
                            } else {

                                return $this->view_fee_calculation_jh($fees_cal_id);
                            }
                        }
                    } else {
                        return "sorry! there is some error at saving data";
                    }
                } else {// if calculation not done properly
                    return "sorry! error while calculating Fees";
                }
            } else {// if rule not found
                return "Rule not found";
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            return 'error while calculating fees';
        }
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------
    public function get_calc_fee_rule_list_jh($frm) {
        try {
            $this->autoRender = FALSE;
            $feeRuleList = array();
            $ruleCondition = array();
            if (isset($frm['fee_rule_id'])) {
                $ruleCondition['fee_rule_id'] = $frm['fee_rule_id'];
            } else if (isset($frm['article_id'])) {
                $ruleCondition['article_id'] = $frm['article_id'];
            }
            $commonFeeRule = $this->article_fee_rule->Find('list', array('fields' => array('fee_rule_id'), 'conditions' => $ruleCondition));
            if ($commonFeeRule) {
                foreach ($commonFeeRule as $tfr) {
                    array_push($feeRuleList, $tfr);
                }
            }
            return $feeRuleList;
        } catch (Exception $ex) {
            echo 'error while getting calculation Fee Rule List';
        }
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------
    public function save_fee_calculation_detail_jh($fees_cal_id, $frm, $feeCalcDetail, $articleItems) {
        try {
               
            $this->autoRender = FALSE;
            $this->loadModel('fees_calculation_detail');
            $fee_calc_detail_final = array();
            $frm['fee_calc_id'] = $fees_cal_id;
            foreach ($articleItems as $key => $frmItem) {// Input Details
                $frm['fee_item_id'] = $key;
                $tempItemvalue = $frm[$frmItem];
                $frm['fee_item_value'] = $tempItemvalue;
                array_push($fee_calc_detail_final, $frm);
            }
            foreach ($feeCalcDetail as $value) {//Calculation Details
                $tmpValue = explode('=', $value['calc_detail']);
                if ($tmpValue) {
                    $frm['fee_calc_desc'] = $tmpValue[0];
                    $frm['final_value'] = $tmpValue[1];
                }
                $frm['fee_subrule_id'] = $value['fee_subrule_id'];
                $frm['fee_item_id'] = $value['fee_item_id'];
                $frm['fee_item_value'] = NULL;
                $frm['item_type_id'] = 2;
                $frm['min_value'] = $value['min_value'];
                $frm['max_value'] = $value['max_value'];
                if (is_numeric($frm['final_value']) && $frm['final_value'] > 0) {
                    array_push($fee_calc_detail_final, $frm);
                }
            }

            if (isset($frm['cons_amt'])) {
                $frm['fee_calc_desc'] = NULL;
                $frm['final_value'] = NULL;
                $frm['fee_subrule_id'] = NULL;
                $frm['fee_item_id'] = NULL;
                $frm['fee_item_value'] = $frm['cons_amt'];
                $frm['item_type_id'] = 99;
                array_push($fee_calc_detail_final, $frm);
            }
            if ($this->fees_calculation_detail->saveAll($fee_calc_detail_final)) {
                return $this->view_fee_calculation_jh($frm['fee_calc_id']);
            } else {
                return "Fees Calculation Failed!";
            }
        } catch (Exception $ex) {
            return 'There is some error in saving Calculation Detail';
        }
    }

//-------------------------------------------------------------------Check Fee Rule Conditions---------------------------------------------------------------------------------
    public function check_fee_condition_old_jh($articleItems, $frm, $fsrl) {
        try {
            //pr($frm);
 
            if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                $total_cons_amt = (isset($frm['property_id']) && is_numeric($frm['property_id'])) ? $this->fees_calculation->get_cons_amt($frm['token_no'], $frm['property_id']) : $this->fees_calculation->get_cons_amt($frm['token_no']);
                $this->loadModel('property_details_entry');
                $this->loadModel('valuation');
                $this->loadModel('valuation_details');
              
//                if ($total_cons_amt) {
//                    
//                    $frm['FAA'] = $total_cons_amt;
//                  
//                } else {
//                    $frm['FAA'] = $frm['FAA'];
//                }
//                $vamout = $frm['FAA'];
//                $consamount = (isset($frm['property_id'])) ? ($this->fees_calculation->field('cons_amt', array('property_id' => $frm['property_id']))) : (isset($frm['cons_amt']) ? $frm['cons_amt'] : NULL);
//                if (is_numeric($vamout) && is_numeric($consamount)) {
//                    if ($vamout > $consamount) {
//                        $frm['FAA'] = $vamout;
//                    } else {
//                        $frm['FAA'] = $consamount;
//                    }
//                } else {
//                    $frm['FAA'] = $vamout;
//                }
//                pr($frm);
                $token = $frm['doc_token_no'];
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                
                $rule_new = array();
                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];
                    $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                    foreach ($rule_id as $rule) {
                        $ruleid = $rule['rule_id'];
                        array_push($rule_new, $ruleid);
                    }
                }
                $flag = 0;
                $count = count($rule_new);
                if ($count == 1) {
                    $flag = 1;
                } else {
                    foreach ($rule_new as $ruleid_new) {
                        if ($ruleid_new != $rule_new[0]) {
                            $flag = 1;
                        }
                    }
                }
                //pr($flag);
                if ($flag == 1) {
//                    pr($frm['property_id']);
                    $property_id = $frm['property_id'];
                    $val_id_data = $this->property_details_entry->query("select val_id from ngdrstab_trn_property_details_entry where property_id=$property_id");
                    $frm['val_id'] = $val_id_data[0][0]['val_id'];
                    $vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);
                     
                    if ($total_cons_amt > $vamout) {
                        $frm['FAA'] = $total_cons_amt;
                    } else {
                        $frm['FAA'] = $vamout;
                    }
                } else {
                    $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                    foreach ($tokenno as $tokenid) {
                        $valuation_id = $tokenid['property_details_entry']['val_id'];
                        if ($valuation_id) {
                            $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                            $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                            if (!empty($record)) {
                                $frm['FAA'] += $record['valuation_details']['rounded_val_amt'];
                            }
                        }
                    }
                    $vamout=$frm['FAA'];
                    if ($total_cons_amt > $vamout) {
                        $frm['FAA'] = $total_cons_amt;
                    } else {
                        $frm['FAA'] = $vamout;
                    }
                }
            }

            $this->autoRender = FALSE;
            $this->loadModel('article_fee_items');
            $this->loadModel('conf_reg_bool_info');
            $output_item = ($fsrl['fee_output_item_id']) ? $this->article_fee_items->find('first', array('fields' => array('article_fee_items.min_value', 'article_fee_items.max_value', 'article_fee_items.fee_rounding_flag', 'rounding.next_rounding_value'),
                        'joins' => array(array('table' => 'ngdrstab_mst_rounding_value', 'type' => 'left', 'alias' => 'rounding', 'conditions' => array('rounding.rounding_id=article_fee_items.rounding_id'))),
                        'conditions' => array('article_fee_items.fee_item_id' => $fsrl['fee_output_item_id']))) : NULL;
            if ($output_item) {
                $result = NULL;
                $calculations = NULL;
                if ($fsrl['max_value_condition_flag'] === 'Y') {
                    // code is to be done if require
                }
                if ($fsrl['fee_rule_cond1']) {
                    if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond1']) . ");")) {
                        $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula1']);
                    } else if ($fsrl['fee_rule_cond2']) {
                        if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond2']) . ");")) {
                            $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula2']);
                        } else if ($fsrl['fee_rule_cond3']) {
                            if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond3']) . ");")) {
                                $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula3']);
                            } else if ($fsrl['fee_rule_cond4']) {
                                if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond4']) . ");")) {
                                    $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula4']);
                                } else if ($fsrl['fee_rule_cond5']) {
                                    $calculations = (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond5']) . ");")) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                } else {//if condition 5 not available
                                    $calculations = ($fsrl['fee_rule_formula5']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                }
                            } else {//if condition 4  not available
                                $calculations = ($fsrl['fee_rule_formula4']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula4']) : -1;
                            }
                        } else {// if condition 3  not available
                            $calculations = ($fsrl['fee_rule_formula3']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula3']) : -1;
                        }
                    } else {//if condition 2 not available
                        $calculations = ($fsrl['fee_rule_formula2']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula2']) : -1;
                    }
                } else {//condition 1  not Available              
                    $calculations = ($fsrl['fee_rule_formula1']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula1']) : -1;
                }
                //   pr($calculations);exit;
                $result = eval("return(" . $calculations . ");");
                if ($fsrl['min_value'] || $fsrl['max_value']) {
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            $result = ceil(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                        } else {
                            $result = round(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                        }
                    }
                } else {
                    //check max and min value for output field
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            $result = ceil(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                        } else {
                            $result = round(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                        }
                    }
                }

                //perform rounding
                if ($result == -1) {
                    $result = 0;
                }
                if ($result != 0) {
                    if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                        $result = $result;
                    } else {
                        //Shridhar code replace on 07-09-2018 for round fee item to next 500 is wrong
//                        $tmp_result = $result + ($output_item['rounding']['next_rounding_value'] / 2) - 0.001;
//                        $tmp_factor = - strlen((string) abs($output_item['rounding']['next_rounding_value']) - 1);
//                        $result = ($output_item['article_fee_items']['fee_rounding_flag'] == 'Y') ? round($tmp_result, $tmp_factor) : round($result);
                        //Shrishail/Kalyani code replace on 07-09-2018 for round fee item to next 500 is right
                        $value = $output_item['rounding']['next_rounding_value'];
                        $result = $this->round_tonext($result, $value);
                    }
                }
                if ($calculations == -1) {
                    $calculations = 0;
                }
//                echo $calculations . '=' . $result;
//                exit;
                return $calculations . '=' . $result;
            } else
                return 0;
        } catch (Exception $ex) {
            return '!Sorry, Error in Fee Rule';
        }
    }
    
    public function check_fee_condition_jh($articleItems, $frm, $fsrl) {
        try {
            //pr($frm);

            if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                $total_cons_amt = (isset($frm['property_id']) && is_numeric($frm['property_id'])) ? $this->fees_calculation->get_cons_amt($frm['token_no'], $frm['property_id']) : $this->fees_calculation->get_cons_amt($frm['token_no']);
                $this->loadModel('property_details_entry');
                $this->loadModel('valuation');
                $this->loadModel('valuation_details');
                $this->loadModel('conf_reg_bool_info');
                $token = $frm['doc_token_no'];
                $flag = $this->get_prop_same_usage_flag($token);
                $vamout = 0;
                if ($flag == 1) {
//                    pr($frm['property_id']);
                    $property_id = $frm['property_id'];
                    $val_id_data = $this->property_details_entry->query("select val_id from ngdrstab_trn_property_details_entry where property_id=$property_id");
                    $frm['val_id'] = $val_id_data[0][0]['val_id'];
                    //$vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);
                    $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $frm['val_id'], 'item_type_id' => 2)));
                    if (!empty($record)) {
                        foreach ($record as $record_result) {
                            $vamout += $record_result['valuation_details']['final_value'];
                        }
                        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                        $roundto = '';
                        if (!empty($regconf)) {
                            if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                    $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                            }
                        }
                        $vamout = $this->round_tonext($vamout, $roundto);
                    }
                    if ($total_cons_amt > $vamout) {
                        $frm['FAA'] = $total_cons_amt;
                    } else {
                        $frm['FAA'] = $vamout;
                    }
//                    pr($frm['FAA']);
                } else {
                    $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                    foreach ($tokenno as $tokenid) {
                        $valuation_id = $tokenid['property_details_entry']['val_id'];
                        if ($valuation_id) {
                            $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                            //$record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                            $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                            if (!empty($record)) {                                
                                foreach ($record as $record_result) {
                                    $vamout += $record_result['valuation_details']['final_value'];
                                }
                                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                                $roundto = '';
                                if (!empty($regconf)) {
                                    if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                        if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                            $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                    }
                                }
                                $vamout = $this->round_tonext($vamout, $roundto);
                            }
                        }
                    }
                    //$vamout = $frm['FAA'];
                    if ($total_cons_amt > $vamout) {
                        $frm['FAA'] = $total_cons_amt;
                    } else {
                        $frm['FAA'] = $vamout;
                    }
                    
                }
            }

            $this->autoRender = FALSE;
            $this->loadModel('article_fee_items');
            $this->loadModel('conf_reg_bool_info');
            $output_item = ($fsrl['fee_output_item_id']) ? $this->article_fee_items->find('first', array('fields' => array('article_fee_items.min_value', 'article_fee_items.max_value', 'article_fee_items.fee_rounding_flag', 'rounding.next_rounding_value'),
                        'joins' => array(array('table' => 'ngdrstab_mst_rounding_value', 'type' => 'left', 'alias' => 'rounding', 'conditions' => array('rounding.rounding_id=article_fee_items.rounding_id'))),
                        'conditions' => array('article_fee_items.fee_item_id' => $fsrl['fee_output_item_id']))) : NULL;
            if ($output_item) {
                $result = NULL;
                $calculations = NULL;
                if ($fsrl['max_value_condition_flag'] === 'Y') {
                    // code is to be done if require
                }
                if ($fsrl['fee_rule_cond1']) {
                    if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond1']) . ");")) {
                        $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula1']);
                    } else if ($fsrl['fee_rule_cond2']) {
                        if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond2']) . ");")) {
                            $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula2']);
                        } else if ($fsrl['fee_rule_cond3']) {
                            if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond3']) . ");")) {
                                $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula3']);
                            } else if ($fsrl['fee_rule_cond4']) {
                                if (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond4']) . ");")) {
                                    $calculations = $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula4']);
                                } else if ($fsrl['fee_rule_cond5']) {
                                    $calculations = (eval("return(" . $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_cond5']) . ");")) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                } else {//if condition 5 not available
                                    $calculations = ($fsrl['fee_rule_formula5']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                }
                            } else {//if condition 4  not available
                                $calculations = ($fsrl['fee_rule_formula4']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula4']) : -1;
                            }
                        } else {// if condition 3  not available
                            $calculations = ($fsrl['fee_rule_formula3']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula3']) : -1;
                        }
                    } else {//if condition 2 not available
                        $calculations = ($fsrl['fee_rule_formula2']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula2']) : -1;
                    }
                } else {//condition 1  not Available              
                    $calculations = ($fsrl['fee_rule_formula1']) ? $this->add_value_to_formula_jh($articleItems, $frm, $fsrl['fee_rule_formula1']) : -1;
                }
                //   pr($calculations);exit;
                $result = eval("return(" . $calculations . ");");
                if ($fsrl['min_value'] || $fsrl['max_value']) {
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            $result = ceil(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                        } else {
                            $result = round(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                        }
                    }
                } else {
                    //check max and min value for output field
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            $result = ceil(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                        } else {
                            $result = round(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                        }
                    }
                }

                //perform rounding
                if ($result == -1) {
                    $result = 0;
                }
                if ($result != 0) {
                    if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                        $result = $result;
                    } else {
                        //Shridhar code replace on 07-09-2018 for round fee item to next 500 is wrong
//                        $tmp_result = $result + ($output_item['rounding']['next_rounding_value'] / 2) - 0.001;
//                        $tmp_factor = - strlen((string) abs($output_item['rounding']['next_rounding_value']) - 1);
//                        $result = ($output_item['article_fee_items']['fee_rounding_flag'] == 'Y') ? round($tmp_result, $tmp_factor) : round($result);
                        //Shrishail/Kalyani code replace on 07-09-2018 for round fee item to next 500 is right
                        $value = $output_item['rounding']['next_rounding_value'];
                        $result = $this->round_tonext($result, $value);
                    }
                }
                if ($calculations == -1) {
                    $calculations = 0;
                }
//                echo $calculations . '=' . $result;
//                exit;
                return $calculations . '=' . $result;
            } else
                return 0;
        } catch (Exception $ex) {
            return '!Sorry, Error in Fee Rule';
        }
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------
    public function add_value_to_formula_jh($itemlist, $value, $formula) {// for replacing item code with value in formula
        try {
            $this->autoRender = FALSE;
//-----------------------for calculated Market Value for multiple Ietm-------------------
            $mv_item_flag = 'N';
            foreach ($itemlist as $item) {
                if ($item == 'FAA') {
                    $mv_item_flag = 'Y';
                    if (isset($value['cons_amt'])) {
                        if ($value['FAA'] < $value['cons_amt']) {
                            $value['FAA'] = ltrim($value['cons_amt'], 0);
                        }
                    }
                }
            }
            if ($mv_item_flag == 'N' && $itemlist) {
                array_push($itemlist, 'OMV');
                $value['OMV'] = $this->calculate_mv_jh($value);
                if (isset($value['cons_amt'])) {
                    if ($value['OMV'] < $value['cons_amt']) {
                        $value['OMV'] = trim($value['cons_amt']);
                    }
                }
            }
//----------------------------------------------------------------------------------------
            foreach ($itemlist as $item) {
                $formula = str_replace($item, trim($value[$item]), $formula);
            }
            return $formula;
        } catch (Exception $ex) {
            return 'Sorry! There is Some Error in adding value';
        }
    }

    //-------------------------------------------------get Certificate Fee---Dated 30-June-2017------------------------------------------------------------------------
    public function get_certificate_fees_jh() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['article_fee_rule', 'article_fee_subrule']);
            if (isset($this->request->data['ctype']) and ( $this->request->data['ctype'] == 'E' or $this->request->data['ctype'] == 'C')) {
                $fee_item_id = ($this->request->data['ctype'] == 'E') ? 33 : 32;
                $fee_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id'),
                    'conditions' => array('fee_rule_id' => $this->article_fee_subrule->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('fee_output_item_id' => $fee_item_id)))
                        , 'article_id' => 9999)));
                $fee_rule_id = array_values($fee_rule);
                $frm['fee_rule_id'] = is_array($fee_rule_id) ? $fee_rule_id[0] : $fee_rule_id;
                return $this->calculate_fees_jh($frm, 'Y');
            } else {
                return NULL;
            }
        } catch (Exception $ex) {
            if ($this->Session->read('sess_language') == NULL) {
                $this->redirect($this->webroot);
            }
        }
    }

//------------------***--------------------------------view Calculated Report-----------------------------------------------------------------------------------------
    public function view_fee_calculation_jh($fee_calc_id = NULL, $view_flag = NULL) {
        try {
            $this->autoRender = FALSE;
            $fee_calc_id = (isset($_POST['fee_calc_id'])) ? $_POST['fee_calc_id'] : $fee_calc_id;
            $view_flag = (isset($_POST['rpt_type_flag'])) ? $_POST['rpt_type_flag'] : $view_flag;
            $lang = $this->Session->read('sess_langauge');
            array_map([$this, 'loadModel'], ['fees_calculation', 'fees_calculation_detail', 'article_fee_rule', 'article_fee_subrule', 'article', 'article_fee_items', 'conf_article_feerule_items', 'valuation', 'conf_reg_bool_info']);
            $calcData = $this->fees_calculation->get_fee_calculation($fee_calc_id, $lang);
            $calcDetailData = NULL;
            if ($calcData) {
                $out_vwReport = NULL;
                $vwReport = "<h2 align=center>Fees Calculation</h2>"
                        . "<table width='100%'>"
                        . "<tr><td><b>Fee Calculation Id.</b>" . $fee_calc_id . " / " . date('Y') . "</b> </td><td align=right><b>Date: </b>" . date('d-F-Y') . "</td></tr></table>"
                        . "<style>td{padding:5px;}</style>"
                        . "<table width=100%>"
                        . "<tr><td><b>Article :-</b> " . $calcData[0]['article']['article_desc_' . $lang] . "</td></tr>"
                        . "<tr><td><b>Fee Rule :-</b>" . $calcData[0]['fees']['fee_rule_desc_' . $lang] . "</td></tr>"
                        . "</table>";

                $calcDetailData = $this->fees_calculation_detail->get_fee_calculation_detail($fee_calc_id, $lang);
                if ($calcDetailData) {
                    $srInput = $srOutput = $InputTotal = 1;
                    $imv_flag = 'N';
                    $itemtype = $calcDetailData[0]['fees_calculation_detail']['item_type_id'];
                    if ($itemtype != 2 && $itemtype != 6) {
                        $consideration_amount = NULL;
                        $vwReport .= "<h3 align=center width='80%'> Input Details </h3>"
                                . "<table width='100%' border=1 align=center>"
                                . "<tr style='background-color:#ffffb3;'> <td align=center > <b>Sr.No.</b> </td> <td><b>Particulars</b></td> <td><b>Value</b>  </td></tr>";
//--------------------for Input Details---------------------------------------
                        foreach ($calcDetailData as $calcDetail) {
                            $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                            if ($itemtype != 2 && $itemtype != 6 || $itemtype == 99) { // If Item are Input Only
                                if ($calcDetail['items']['fee_param_type_id'] == 5) {// for Exemption Input
                                    $inputValue = ($calcDetail['items']['fee_item_desc_en'] == 'Gender') ? (str_replace(array(1, 2, 3), array('Male', 'Female', 'Other'), $calcDetail['fees_calculation_detail']['fee_item_value'])) : ($this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')));
                                    $vwReport .= "<tr style='background-color:#E8E8E8;'><td align=center>" . $srInput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_en'] . "</b></td> <td>" . $inputValue . "</td> </tr>";
                                } else if ($calcDetail['fees_calculation_detail']['item_type_id'] == 99) { // for consideration amount--- if available
                                    $consideration_amount = "<tr style='background-color:#fdfCCC;'> <td></td> <td  ><b>Consideration Amount </b></td> <td><b>" . $this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')) . "</b></td> </tr>";
                                } else { //for Normal Input Items
                                    $vwReport .= "<tr><td align=center>" . $srInput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td>" . $this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')) . "</td> </tr>";
                                    $InputTotal *= number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '');
                                }

                                if ($calcDetail['items']['fee_item_id'] == 5) {// for Market Value Given
                                    $imv_flag = 'Y';
                                }
                            }
                        }
                        if ($imv_flag == 'N') {
                            $vwReport .= "<tr style='background-color:#fdfddd;'> <td></td> <td  ><b>Output Market Value </b></td> <td><b>" . $this->valuation->format_money_india(number_format((float) $InputTotal, 2, '.', '')) . "</b></td> </tr>";
                        }

                        if ($consideration_amount) {
                            $vwReport .= "<tr style='background-color:#AAAddd;'><td colspan=4></td></tr>";
                            $vwReport .= $consideration_amount;
                            $vwReport .= "<tr style='background-color:#AAAddd;'><td colspan=4></td></tr>";
                        }
                    }
                    $vwReport .= "</table>"
                            . "<h3 align=center >Calculation Details</h3>";
//------------------ for Output Details--------------------------------------------------------------------------------------------------------------------                
//                    $out_vwReport.="<table width=100% border=1 align=center>"
//                            . "<tr style='background-color:#ffffb3;'> <td align=center><b>Sr.No.</b></td> <td><b>Particulars<b></td> <td align=right><b>Min.</b></td><td align=right><b>Max.</b></td> <td><b>Calculation</b></td> <td align=right><b>Total</b></td><td align=right><b>Final Amount</b></td></tr>";
//                    $total = 0;
//
//                    foreach ($calcDetailData as $calcDetail) {
//                        $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
//                        if ($itemtype == 2 or $itemtype == 6) {//for output Items
//                            $min_value = ($calcDetail['fees_calculation_detail']['min_value']) ? ($calcDetail['fees_calculation_detail']['min_value']) : (($calcDetail['items']['min_value']) ? $calcDetail['items']['min_value'] : '-NA-');
//                            $max_value = ($calcDetail['fees_calculation_detail']['max_value']) ? ($calcDetail['fees_calculation_detail']['max_value']) : (($calcDetail['items']['max_value']) ? $calcDetail['items']['max_value'] : '-NA-');
//                            if ($calcDetail['items']['fee_param_type_id'] == 6) { // for Exemption  Output
//                                $total-=$calcDetail['fees_calculation_detail']['final_value'];
//                                $out_vwReport.="<tr style='background-color:#E8E8E8;'><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
//                            } else { //for Normal Items
//                                $total+=$calcDetail['fees_calculation_detail']['final_value'];
//                                $out_vwReport.="<tr><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td>  <td align=right>" . $min_value . " </td> <td align=right>" . $max_value . " </td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td align=right>" . eval("return(" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . ");") . "</td> <td align=right>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
//                            }
//                        }
//                    }
//                    $out_vwReport.="<tr style='background-color: #b3b300;color:white;'><td align=center colspan=6 ><b>Total</b></td> <td align=right> <b>" . $this->valuation->format_money_india(number_format((float) abs($total), 2, '.', '')) . "/-</b></td> </tr>";
//                    $out_vwReport.="</table>";

                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 83)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            $out_vwReport .= "<table width=100% border=1 align=center>"
                                    . "<tr style='background-color:#ffffb3;'> <td align=center><b>Sr.No.</b></td> <td><b>Particulars<b></td> <td width=30%><b>Payment Mode<b></td> <td align=right><b>Total</b></td><td align=right><b>Final Amount</b></td></tr>";

                            $total = 0;
                            foreach ($calcDetailData as $calcDetail) {
                                $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                                if ($itemtype == 2 or $itemtype == 6) {//for output Items
                                    $min_value = ($calcDetail['fees_calculation_detail']['min_value']) ? ($calcDetail['fees_calculation_detail']['min_value']) : (($calcDetail['items']['min_value']) ? $calcDetail['items']['min_value'] : '-NA-');
                                    $max_value = ($calcDetail['fees_calculation_detail']['max_value']) ? ($calcDetail['fees_calculation_detail']['max_value']) : (($calcDetail['items']['max_value']) ? $calcDetail['items']['max_value'] : '-NA-');
                                    if ($calcDetail['items']['fee_param_type_id'] == 6) { // for Exemption  Output
                                        $total -= $calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport .= "<tr style='background-color:#E8E8E8;'><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    } else { //for Normal Items
                                        $total += $calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport .= "<tr><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td align=right>" . eval("return(" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . ");") . "</td> <td align=right>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    }
                                }
                            }
                            $out_vwReport .= "<tr style='background-color: #b3b300;color:white;'><td align=center colspan=4><b>Total</b></td> <td align=right> <b>" . $this->valuation->format_money_india(number_format((float) abs($total), 2, '.', '')) . "/-</b></td> </tr>";
                            $out_vwReport .= "</table>";
                        } else {
                            $out_vwReport .= "<table width=100% border=1 align=center>"
                                    . "<tr style='background-color:#ffffb3;'> <td align=center><b>Sr.No.</b></td> <td><b>Particulars<b></td> <td width=30%><b>Payment Mode<b></td> <td align=right><b>Min.</b></td><td align=right><b>Max.</b></td> <td><b>Calculation</b></td> <td align=right><b>Total</b></td><td align=right><b>Final Amount</b></td></tr>";

                            $total = 0;

                            foreach ($calcDetailData as $calcDetail) {
                                $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                                if ($itemtype == 2 or $itemtype == 6) {//for output Items
                                    $min_value = ($calcDetail['fees_calculation_detail']['min_value']) ? ($calcDetail['fees_calculation_detail']['min_value']) : (($calcDetail['items']['min_value']) ? $calcDetail['items']['min_value'] : '-NA-');
                                    $max_value = ($calcDetail['fees_calculation_detail']['max_value']) ? ($calcDetail['fees_calculation_detail']['max_value']) : (($calcDetail['items']['max_value']) ? $calcDetail['items']['max_value'] : '-NA-');
                                    if ($calcDetail['items']['fee_param_type_id'] == 6) { // for Exemption  Output
                                        $total -= $calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport .= "<tr style='background-color:#E8E8E8;'><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    } else { //for Normal Items
                                        $total += $calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport .= "<tr><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td>  <td align=right>" . $min_value . " </td> <td align=right>" . $max_value . " </td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td align=right>" . eval("return(" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . ");") . "</td> <td align=right>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    }
                                }
                            }


                            $out_vwReport .= "<tr style='background-color: #b3b300;color:white;'><td align=center colspan=7><b>Total</b></td> <td align=right> <b>" . $this->valuation->format_money_india(number_format((float) abs($total), 2, '.', '')) . "/-</b></td> </tr>";
                            $out_vwReport .= "</table>";
                        }
                    }
                }
                $style = "<style>table {border-collapse: collapse;} td{padding:3px}</style>";

                if ($view_flag == 'D') {

                    $vwReport .= $out_vwReport;
                    $vwReport = $style . $vwReport;
                    $Reports = new ReportsController;
                    $Reports->create_pdf($vwReport, "Fees_" . $fee_calc_id);
                } else if ($view_flag == 'V') {
                    $vwReport .= $out_vwReport;
                } else {
                    $vwReport = $style . $out_vwReport;
                }
                return $vwReport;
            } else {
                return "No Data Available";
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Fetching Fee Calculation');
        }
    }

    //--------------***------------------------------------view Calculationn for perticular (token)--- Modified on 20 March 2017---------------------------------------------------------
    public function view_sd_calc_old_jh($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
        try {
            /*
             * Pass $fee_type_id=1 if u want to display only Online Fees;  
             * pass $fee_type_id=s2 if u want both Counter and Online Fees;
             */
            $this->autoRender = FALSE;
//            return 'SD Language :'.$sess_lang;exit;
            array_map([$this, 'loadModel'], ['conf_reg_bool_info', 'ReportLabel', 'article_fee_items', 'fees_calculation', 'fees_calculation_detail', 'valuation', 'genernalinfoentry', 'property_details_entry']);
            $doc_token_no = ($doc_token_no) ? $doc_token_no : (isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : NULL);
            $lang = (isset($this->request->data['lang'])) ? $this->request->data['lang'] : (($sess_lang) ? $sess_lang : $this->Session->read('sess_langauge'));
            $fee_type_id = (isset($this->request->data['fee_type_id'])) ? $this->request->data['fee_type_id'] : $fee_type_id; // provide NULL Value if want to display All Records(online+counter)                                                
            if ($doc_token_no) {
                $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 2)));
                if (is_numeric($doc_token_no) && in_array($lang, ['en', 'll', 'll1'])) {
                    $counter_fee_calc_ids = $online_fee_calc_ids = array();
                    $conditions['fees_calculation.token_no'] = $doc_token_no;
                    $conditions['fees_calculation.delete_flag'] = 'N';
                    $conditions['fees_calculation.property_id'] = $this->property_details_entry->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'val_id !=' => 0)));
                    if (count($conditions['fees_calculation.property_id']) > 0) {
                        array_push($conditions['fees_calculation.property_id'], 0);
                    } else {
                        $conditions['fees_calculation.property_id'] = 0;
                    }
                    //get Article Id for current TOken
//                    pr($conditions);exit;
                    $doc_article_id = $this->genernalinfoentry->find('first', array('fields' => array('article_id AS article_id'), 'conditions' => array('token_no' => $doc_token_no)));
                    if (!empty($doc_article_id)) {
                        $doc_article_id = $doc_article_id[0]['article_id'];
                        $conditions['fees_calculation.delete_flag'] = 'N';
                        $conditions['fee_rule.article_id'] = array(9999, $doc_article_id);
                    }

                    // get Calculation Ids for selected Token
                    $calc_ids = $this->fees_calculation->find('list', array('fields' => array('fees_calculation.fee_calc_id'), 'conditions' => $conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array("fee_rule.fee_rule_id=fees_calculation.fee_rule_id")))));

                    if ($calc_ids) {
                        $calc_Data = $this->fees_calculation->find('all', array('fields' => array('fees_calculation.property_id', 'fee_rule.fee_rule_id', 'fee_rule.fee_type_id', 'fees_calculation.fee_rule_id', 'fee_rule.fee_rule_desc_' . $lang, 'fees_calculation.fee_calc_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array('fee_rule.fee_rule_id=fees_calculation.fee_rule_id'))
                            ), 'conditions' => array('fee_calc_id' => $calc_ids), 'order' => 'fees_calculation.property_id,fees_calculation.fee_rule_id',
                        ));
//                        pr($calc_Data);
                        $total = NULL;
                        if ($calc_Data) {//display data if both detail available
                            $html = NULL;
                            $html .= "<style>td{padding:2px 10px 2px 10px;}</style>"
                                    . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            $rule_id = $prop_id = NULL;
                            $totalSD = 0;
                            $SrProp = $SrRule = $SrCalc = 1;
                            foreach ($calc_Data as $calc) {
                                if ($calc['fee_rule']['fee_type_id'] == 2) {
                                    array_push($counter_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                } else {
                                    array_push($online_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                }
//                                pr($calc);
//                            if ($calc['fee_rule']['fee_type_id'] == 1 || $fee_type_id == 2) {
                                if (1) {
                                    $propTotalSD = 0;
                                    if ($prop_id != $calc['fees_calculation']['property_id']) {//for Displaying Property Ids
                                        $html .= ($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                        $prop_id = $calc['fees_calculation']['property_id'];
                                        $SrRule = 1;
                                    }
                                    if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {

//                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "<button title='Remove' class='danger glyphicon glyphicon-trash' onClick='return removeFees('" . $calc['fees_calculation']['fee_calc_id'] . "')></button>" . "</td></tr>";                                        
                                        $html .= "<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        $rule_id = $calc['fees_calculation']['fee_rule_id'];
                                    }
//                                    pr($html);
                                    $newhtml = $html;
                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
                                        'joins' => array(
                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
                                        )
                                    ));
                                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {


//                   if($regconf)


                                        if ($calc_detail) {

                                            foreach ($calc_detail as $cd) {
                                                $cds = $cd['fees_calculation_detail'];
                                                if ($cd['item']['fee_param_type_id'] == 2) {
                                                    if ($cd['item']['group_display'] == 'Y') {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD += round($cds['final_value'], 1);
                                                    }

//                                                 pr($html);
                                                } else {
                                                    if ($cd['item']['group_display'] == 'Y') {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";

                                                        $propTotalSD -= round($cds['final_value'], 1);
                                                    }

//                                                 pr($html);
                                                }
                                            }
                                        }
                                        if ($calc_detail) {
                                            $SrCalc = 1;
                                            foreach ($calc_detail as $cd) {
                                                $cds = $cd['fees_calculation_detail'];
                                                if ($cd['item']['fee_param_type_id'] == 2) {
                                                    if ($cd['item']['group_display'] == 'N') {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //   $propTotalSD+=round($cds['final_value'], 1);
                                                    }
//                                                 pr($html);
                                                } else {
                                                    if ($cd['item']['group_display'] == 'N') {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //  $propTotalSD-=round($cds['final_value'], 1);
                                                    }
//                                                 pr($html);
                                                }
                                            }
                                        }
                                    } else {

                                        if ($calc_detail) {
                                            foreach ($calc_detail as $cd) {
                                                $cds = $cd['fees_calculation_detail'];
                                                if ($cd['item']['fee_param_type_id'] == 2) {
                                                    $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                    $propTotalSD += round($cds['final_value'], 1);
                                                } else {
                                                    $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                    $propTotalSD -= round($cds['final_value'], 1);
                                                }
                                            }
                                        }
                                    }
//                                     pr($html);exit;
                                    $totalSD += $propTotalSD;
                                    $html .= "<tr style='background-color: #C3C3E5;'><td colspan=2 align=center><b>" . $rptlabels[76] . "</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $propTotalSD, 2, '.', '')) . "</b></td></tr>";
                                    $html .= "<tr style='background-color: black;'><td colspan=3 align=center></td></tr>";
                                }
                            }
                            //$html.="<tr style='background-color: #8C489F; color: white;'><td colspan=2 align=center><b>Total</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $totalSD, 2, '.', '')) . "</b></td></tr>";
                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
                            )));

                            $totalSD = $online_sd[0]['online_sd'];
                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
                            )));
                            //pr($counterSD);
                            $counterSD = $counterSD[0]['counter_sd'];
                            $html .= "</table>"
                                    . "<input type='hidden' id='onlineSD' value=" . round($totalSD, 2) . ">"
                                    . "<input type='hidden' id='counterSD' value=" . round($counterSD, 2) . ">";
                            $newhtml .= "</table><br><br>";
                            $newhtml .= $html;
//                            pr($newhtml);exit;

                            return $newhtml;
                        }
//                    $this->set(compact('calc_Data', 'calc_detail'));
                    } else {
                        return "Please Calculate Stamp Duty"; //exit;
                    }
                } else {
                    return 'Sorry! Wrong Input';
                }
            } else {
                return 'invalid input provided';
            }
        } catch (Exception $ex) {
            print_r($ex);
            $this->Session->setFlash('Sorry! Error in getting SD Detail');
        }
    }

    public function view_sd_calc_new_jh($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
        try {
            /*
             * Pass $fee_type_id=1 if u want to display only Online Fees;  
             * pass $fee_type_id=2 if u want both Counter and Online Fees;
             */
            $this->autoRender = FALSE;
//            return 'SD Language :'.$sess_lang;exit;
            array_map([$this, 'loadModel'], ['conf_reg_bool_info', 'ReportLabel', 'article_fee_items', 'fees_calculation', 'fees_calculation_detail', 'valuation', 'genernalinfoentry', 'property_details_entry']);
            $doc_token_no = ($doc_token_no) ? $doc_token_no : (isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : NULL);
            $lang = (isset($this->request->data['lang'])) ? $this->request->data['lang'] : (($sess_lang) ? $sess_lang : $this->Session->read('sess_langauge'));
            $fee_type_id = (isset($this->request->data['fee_type_id'])) ? $this->request->data['fee_type_id'] : $fee_type_id; // provide NULL Value if want to display All Records(online+counter)                                                
            if ($doc_token_no) {
                $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 2)));
                if (is_numeric($doc_token_no) && in_array($lang, ['en', 'll', 'll1'])) {
                    $counter_fee_calc_ids = $online_fee_calc_ids = array();
                    $conditions['fees_calculation.token_no'] = $doc_token_no;
                    $conditions['fees_calculation.delete_flag'] = 'N';
                    $conditions['fees_calculation.property_id'] = $this->property_details_entry->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'val_id !=' => 0)));
                    if (count($conditions['fees_calculation.property_id']) > 0) {
                        array_push($conditions['fees_calculation.property_id'], 0);
                    } else {
                        $conditions['fees_calculation.property_id'] = 0;
                    }
                    //get Article Id for current TOken
//                    pr($conditions);exit;
                    $doc_article_id = $this->genernalinfoentry->find('first', array('fields' => array('article_id AS article_id'), 'conditions' => array('token_no' => $doc_token_no)));
                    if (!empty($doc_article_id)) {
                        $doc_article_id = $doc_article_id[0]['article_id'];
                        $conditions['fees_calculation.delete_flag'] = 'N';
                        $conditions['fee_rule.article_id'] = array(9999, $doc_article_id);
                    }

                    // get Calculation Ids for selected Token
                    $calc_ids = $this->fees_calculation->find('list', array('fields' => array('fees_calculation.fee_calc_id'), 'conditions' => $conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array("fee_rule.fee_rule_id=fees_calculation.fee_rule_id")))));

                    if ($calc_ids) {
                        $calc_Data = $this->fees_calculation->find('all', array('fields' => array('fees_calculation.property_id', 'fee_rule.fee_rule_id', 'fee_rule.fee_type_id', 'fees_calculation.fee_rule_id', 'fee_rule.fee_rule_desc_' . $lang, 'fees_calculation.fee_calc_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array('fee_rule.fee_rule_id=fees_calculation.fee_rule_id'))
                            ), 'conditions' => array('fee_calc_id' => $calc_ids), 'order' => 'fees_calculation.property_id,fees_calculation.fee_rule_id',
                        ));




//                        pr($calc_Data);
                        $total = NULL;
                        if ($calc_Data) {//display data if both detail available
                            $html = NULL;
                            $html .= "<style>td{padding:2px 10px 2px 10px;}</style>"
                                    . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            $rule_id = $prop_id = NULL;
                            $totalSD = 0;
                            $totalEX = 0;
                            $SrProp = $SrRule = $SrCalc = 1;
                            foreach ($calc_Data as $calc) {
                                if ($calc['fee_rule']['fee_type_id'] == 2) {
                                    array_push($counter_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                } else {
                                    array_push($online_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                }
//                                pr($calc);
//                            if ($calc['fee_rule']['fee_type_id'] == 1 || $fee_type_id == 2) {
                                if (1) {
                                    $propTotalSD = 0;
                                    $propTotalEX = 0;
                                    if ($prop_id != $calc['fees_calculation']['property_id']) {//for Displaying Property Ids
                                        $html .= ($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=4>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                        $prop_id = $calc['fees_calculation']['property_id'];
                                        $SrRule = 1;
                                    }
                                    if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {

//                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "<button title='Remove' class='danger glyphicon glyphicon-trash' onClick='return removeFees('" . $calc['fees_calculation']['fee_calc_id'] . "')></button>" . "</td></tr>";                                        
                                        $html .= "<tr>   <td colspan=4 style='color:red;font-weight:bold;'>" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        $html .= "<tr style='background-color: #48D1CC; color: black;'>"
                                                . "<td style='font-weight:bold;' align=center>Sr NO.</td>"
                                                . "<td style='font-weight:bold;' align=center>Fee Description</td>"
                                                . "<td style='font-weight:bold;' align=center>Fees</td>"
                                                . "<td style='font-weight:bold;' align=center>Exemption</td>"
                                                . "</tr>";
                                        $rule_id = $calc['fees_calculation']['fee_rule_id'];
                                    }
//                                    pr($html);
                                    $newhtml = $html;
//                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
//                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
//                                        'joins' => array(
//                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
//                                        )
//                                    ));
//                                    pr($calc_detail);
                                    $calc_detail = $this->fees_calculation_detail->query("SELECT feeitem.account_head_code,feeitem.fee_item_id,feeitem.fee_item_desc_en,feeitem. fee_preference,feeitem.group_display,
                                    feeitem.fee_param_type_id,stampd.item_type_id,stampd.fee_calc_id,stampd.fee_rule_id,SUM(stampd.final_value) as feecalculated,
                                    coalesce((SELECT SUM(stampd1.final_value) as totalsd1 FROM
                                    ngdrstab_trn_fee_calculation_detail stampd1
                                    LEFT JOIN ngdrstab_trn_fee_calculation stamp1 ON stampd1.fee_calc_id = stamp1.fee_calc_id
                                    LEFT JOIN ngdrstab_mst_article_fee_items feeitem1  ON feeitem1.fee_item_id=stampd1.fee_item_id
                                    WHERE  stamp1.token_no=stamp.token_no AND stamp1.delete_flag='N' AND feeitem1.fee_param_type_id=2 AND stamp1.article_id IN(9998) AND feeitem1.fee_item_id=feeitem.fee_item_id group by feeitem1.fee_item_id
                                    order by feeitem1.fee_preference ASC),0) as exemption,
                                    coalesce((select coalesce(online_adj_amt,counter_adj_amt,0) from ngdrstab_trn_stamp_duty_adjustment_detail where  token_no=stamp.token_no and feeitem.fee_item_id=2),0) as adjustment,
                                    coalesce((select coalesce(invest_stamp_amount,0) from ngdrstab_trn_stamp_duty_investment_detail where  token_no=stamp.token_no AND online_invest_doc_date > '2018-01-01'::date -365 AND  feeitem.fee_item_id=2),0) as investment
                                    FROM ngdrstab_trn_fee_calculation_detail stampd
                                    LEFT JOIN ngdrstab_trn_fee_calculation stamp     ON stampd.fee_calc_id = stamp.fee_calc_id
                                    LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.fee_item_id=stampd.fee_item_id
                                    WHERE  stamp.token_no=$doc_token_no
                                    AND stamp.delete_flag='N'
                                    AND feeitem.fee_param_type_id=2
                                    group by feeitem.fee_item_id,stampd.item_type_id,stampd.fee_calc_id,stampd.fee_rule_id,stamp.token_no
                                    order by feeitem.fee_preference ASC");
//                                  pr($calc_detail);  
                                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                        if ($calc_detail) {

                                            foreach ($calc_detail as $cd) {
                                                $cd = $cd[0];
                                                if ($cd['fee_param_type_id'] == 2) {
                                                    if ($cd['group_display'] == 'Y') {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD += round($cd['feecalculated'], 1);
                                                        $propTotalEX += round($cd['exemption'], 1);
                                                    }

//                                                 pr($html);
                                                } else {
                                                    if ($cd['group_display'] == 'Y') {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";

                                                        $propTotalSD -= round($cd['feecalculated'], 1);
                                                        $propTotalEX += round($cd['exemption'], 1);
                                                    }

//                                                 pr($html);
                                                }
                                            }
                                        }
                                        if ($calc_detail) {
                                            $SrCalc = 1;
                                            foreach ($calc_detail as $cd) {
                                                $cd = $cd[0];
                                                if ($cd['fee_param_type_id'] == 2) {
                                                    if ($cd['group_display'] == 'N') {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";
                                                        //   $propTotalSD+=round($cds['final_value'], 1);
                                                    }
//                                                 pr($html);
                                                } else {
                                                    if ($cd['group_display'] == 'N') {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";
                                                        //  $propTotalSD-=round($cds['final_value'], 1);
                                                    }
//                                                 pr($html);
                                                }
                                            }
                                        }
                                    } else {

                                        if ($calc_detail) {
                                            foreach ($calc_detail as $cd) {
                                                $cd = $cd[0];
                                                if ($cd['item']['fee_param_type_id'] == 2) {
                                                    $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";
                                                    $propTotalSD += round($cd['feecalculated'], 1);
                                                    $propTotalEX += round($cd['exemption'], 1);
                                                } else {
                                                    $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cd['feecalculated'], 0, '.', '')) . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cd['exemption'], 0, '.', '')) . "</td></tr>";
                                                    $propTotalSD -= round($cd['feecalculated'], 1);
                                                    $propTotalEX += round($cd['exemption'], 1);
                                                }
                                            }
                                        }
                                    }
//                                     pr($html);exit;
                                    $totalSD += $propTotalSD;
                                    $totalEX += $propTotalEX;
                                    $html .= "<tr style='background-color: #C3C3E5;'><td colspan=2 align=center><b>" . $rptlabels[76] . "</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $propTotalSD, 2, '.', '')) . "</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $propTotalEX, 2, '.', '')) . "</b></td></tr>";
                                    $html .= "<tr style='background-color: black;'><td colspan=4 align=center></td></tr>";
                                }
                            }
                            //$html.="<tr style='background-color: #8C489F; color: white;'><td colspan=2 align=center><b>Total</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $totalSD, 2, '.', '')) . "</b></td></tr>";
                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
                            )));

                            $totalSD = $online_sd[0]['online_sd'];
                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
                            )));
                            //pr($counterSD);
                            $counterSD = $counterSD[0]['counter_sd'];
                            $html .= "</table>"
                                    . "<input type='hidden' id='onlineSD' value=" . round($totalSD, 2) . ">"
                                    . "<input type='hidden' id='counterSD' value=" . round($counterSD, 2) . ">";
                            $newhtml .= "</table><br><br>";
                            $newhtml .= $html;
//                            pr($newhtml);exit;

                            return $newhtml;
                        }
//                    $this->set(compact('calc_Data', 'calc_detail'));
                    } else {
                        return "Please Calculate Stamp Duty"; //exit;
                    }
                } else {
                    return 'Sorry! Wrong Input';
                }
            } else {
                return 'invalid input provided';
            }
        } catch (Exception $ex) {
            print_r($ex);
            $this->Session->setFlash('Sorry! Error in getting SD Detail');
        }
    }
//108 code working
      public function view_sd_calc_jh_old($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
        try {
            /*
             * Pass $fee_type_id=1 if u want to display only Online Fees;  
             * pass $fee_type_id=2 if u want both Counter and Online Fees;
             */
            $this->autoRender = FALSE;
//            return 'SD Language :'.$sess_lang;exit;
            array_map([$this, 'loadModel'], ['valuation_details', 'conf_reg_bool_info', 'ReportLabel', 'article_fee_items', 'fees_calculation', 'fees_calculation_detail', 'valuation', 'genernalinfoentry', 'property_details_entry']);
            $doc_token_no = ($doc_token_no) ? $doc_token_no : (isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : NULL);
            $lang = (isset($this->request->data['lang'])) ? $this->request->data['lang'] : (($sess_lang) ? $sess_lang : $this->Session->read('sess_langauge'));
            $fee_type_id = (isset($this->request->data['fee_type_id'])) ? $this->request->data['fee_type_id'] : $fee_type_id; // provide NULL Value if want to display All Records(online+counter)                                                
            if ($doc_token_no) {
                $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 2)));
                if (is_numeric($doc_token_no) && in_array($lang, ['en', 'll', 'll1'])) {
                    $counter_fee_calc_ids = $online_fee_calc_ids = array();
                    $conditions['fees_calculation.token_no'] = $doc_token_no;
                    $conditions['fees_calculation.delete_flag'] = 'N';
                    $conditions['fees_calculation.property_id'] = $this->property_details_entry->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'val_id !=' => 0)));
                    if (count($conditions['fees_calculation.property_id']) > 0) {
                        array_push($conditions['fees_calculation.property_id'], 0);
                    } else {
                        $conditions['fees_calculation.property_id'] = 0;
                    }
                    //get Article Id for current TOken
//                    pr($conditions);exit;
                    $doc_article_id = $this->genernalinfoentry->find('first', array('fields' => array('article_id AS article_id'), 'conditions' => array('token_no' => $doc_token_no)));
                    if (!empty($doc_article_id)) {
                        $doc_article_id = $doc_article_id[0]['article_id'];
                        $conditions['fees_calculation.delete_flag'] = 'N';
                        $conditions['fee_rule.article_id'] = array(9999, $doc_article_id);
                    }

                    // get Calculation Ids for selected Token
                    $calc_ids = $this->fees_calculation->find('list', array('fields' => array('fees_calculation.fee_calc_id'), 'conditions' => $conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array("fee_rule.fee_rule_id=fees_calculation.fee_rule_id")))));

                    if ($calc_ids) {
                        $calc_Data = $this->fees_calculation->find('all', array('fields' => array('fees_calculation.property_id', 'fee_rule.fee_rule_id', 'fee_rule.fee_type_id', 'fees_calculation.fee_rule_id', 'fee_rule.fee_rule_desc_' . $lang, 'fees_calculation.fee_calc_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array('fee_rule.fee_rule_id=fees_calculation.fee_rule_id'))
                            ), 'conditions' => array('fee_calc_id' => $calc_ids), 'order' => 'fees_calculation.property_id,fees_calculation.fee_rule_id',
                        ));


//                        pr($calc_Data);
                        $total = NULL;
                        if ($calc_Data) {//display data if both detail available
                            $html = NULL;
                            $newhtml = NULL;
                            $html.="<style>td{padding:2px 10px 2px 10px;}</style>"
                                    . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                            if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                $newhtml.="<style>td{padding:2px 10px 2px 10px;}</style>"
                                        . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            }
                            $rule_id = $prop_id = NULL;
                            $totalSD = 0;
                            $SrProp = $SrRule = $SrCalc = 1;
                            foreach ($calc_Data as $calc) {
                                if ($calc['fee_rule']['fee_type_id'] == 2) {
                                    array_push($counter_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                } else {
                                    array_push($online_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                }
//                                pr($calc);
//                            if ($calc['fee_rule']['fee_type_id'] == 1 || $fee_type_id == 2) {
                                if (1) {
                                    $propTotalSD = 0;
                                    if ($prop_id != $calc['fees_calculation']['property_id']) {//for Displaying Property Ids
                                        $flag = $this->get_prop_same_usage_flag($doc_token_no);
                                        if ($flag == 0) {
                                            $prop_id = $calc['fees_calculation']['property_id'];
                                            $SrRule = 1;
                                        } else {
                                            $html.=($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                            if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                                $newhtml.=($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                            }
                                            $prop_id = $calc['fees_calculation']['property_id'];
                                            $SrRule = 1;
                                        }
                                    }
                                    if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {

//                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "<button title='Remove' class='danger glyphicon glyphicon-trash' onClick='return removeFees('" . $calc['fees_calculation']['fee_calc_id'] . "')></button>" . "</td></tr>";                                        
                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                            $newhtml.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        }
                                        $rule_id = $calc['fees_calculation']['fee_rule_id'];
                                    }
//                                    pr($html);
                                    //$newhtml = $html;
//                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
//                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
//                                        'joins' => array(
//                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
//                                        )
//                                    ));

                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'fee_calc_desc', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
                                        'joins' => array(
                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
                                        ), 'order' => 'item.display_order'
                                    ));

                                    if ($calc_detail) {

                                        foreach ($calc_detail as $cd) {
                                            $cds = $cd['fees_calculation_detail'];
                                            if ($cd['item']['fee_param_type_id'] == 2) {
                                                if ($cd['item']['group_display'] == 'Y') {
                                                    //$cds['fee_calc_desc'] 
                                                    if ($cds['final_value'] != 0) {
                                                        $html.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD+=round($cds['final_value'], 1);
                                                    }
                                                }

//                                                 pr($html);
                                            } else {
                                                if ($cd['item']['group_display'] == 'Y') {
                                                    if ($cds['final_value'] != 0) {
                                                        $html.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD-=round($cds['final_value'], 1);
                                                    }
                                                }

//                                                 pr($html);
                                            }
                                        }
                                    }
                                    if ($calc_detail) {
                                        $SrCalc = 1;
                                        foreach ($calc_detail as $cd) {
                                            $cds = $cd['fees_calculation_detail'];
                                            if ($cd['item']['fee_param_type_id'] == 2) {
                                                if ($cd['item']['group_display'] == 'N') {
                                                    if ($cds['final_value'] != 0) {
                                                        $newhtml.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //   $propTotalSD+=round($cds['final_value'], 1);
                                                    }
                                                }
//                                                 pr($html);
                                            } else {
                                                if ($cd['item']['group_display'] == 'N') {
                                                    if ($cds['final_value'] != 0) {
                                                        $newhtml.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //  $propTotalSD-=round($cds['final_value'], 1);
                                                    }
                                                }
//                                                 pr($html);
                                            }
                                        }
                                    }
//                                    
//                                     pr($html);exit;
                                    $totalSD+=$propTotalSD;
                                    $html.="<tr style='background-color: #C3C3E5;'><td colspan=2 align=center><b>" . $rptlabels[76] . "</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $propTotalSD, 2, '.', '')) . "</b></td></tr>";
                                    $html.="<tr style='background-color: black;'><td colspan=3 align=center></td></tr>";
                                }
                            }
                            //$html.="<tr style='background-color: #8C489F; color: white;'><td colspan=2 align=center><b>Total</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $totalSD, 2, '.', '')) . "</b></td></tr>";
//                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
//                                'conditions' => array('fee_calc_id' => $calc_ids,
//                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
//                            )));

                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
                            )));

                            if ($calc_detail[0]['fees_calculation_detail']['final_value'] != 0) {
                                $totalSD = $online_sd[0]['online_sd'];
                            }

//                            $totalSD = $online_sd[0]['online_sd'];
//                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
//                                'conditions' => array('fee_calc_id' => $calc_ids,
//                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
//                            )));

                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
                            )));
                            if ($calc_detail[0]['fees_calculation_detail']['final_value'] != 0) {
                                $counterSD = $counterSD[0]['counter_sd'];
                            }
                            //pr($counterSD);
//                            $counterSD = $counterSD[0]['counter_sd'];
                            $html.="</table>"
                                    . "<input type='hidden' id='onlineSD' value=" . round($totalSD, 2) . ">"
                                    . "<input type='hidden' id='counterSD' value=" . round($counterSD, 2) . ">";
                            $newhtml.="</table><br><br>";
                            $newhtml.=$html;
//                            pr($newhtml);exit;

                            return $newhtml;
                        }
//                    $this->set(compact('calc_Data', 'calc_detail'));
                    } else {
                        return "Please Calculate Stamp Duty"; //exit;
                    }
                } else {
                    return 'Sorry! Wrong Input';
                }
            } else {
                return 'invalid input provided';
            }
        } catch (Exception $ex) {
            print_r($ex);
            $this->Session->setFlash('Sorry! Error in getting SD Detail');
        }
    }
    
    public function view_sd_calc_jh($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
        try {
            /*
             * Pass $fee_type_id=1 if u want to display only Online Fees;  
             * pass $fee_type_id=2 if u want both Counter and Online Fees;
             */
            $this->autoRender = FALSE;
//            return 'SD Language :'.$sess_lang;exit;
            array_map([$this, 'loadModel'], ['valuation_details', 'conf_reg_bool_info', 'ReportLabel', 'article_fee_items', 'fees_calculation', 'fees_calculation_detail', 'valuation', 'genernalinfoentry', 'property_details_entry']);
            $doc_token_no = ($doc_token_no) ? $doc_token_no : (isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : NULL);
            $lang = (isset($this->request->data['lang'])) ? $this->request->data['lang'] : (($sess_lang) ? $sess_lang : $this->Session->read('sess_langauge'));
            $fee_type_id = (isset($this->request->data['fee_type_id'])) ? $this->request->data['fee_type_id'] : $fee_type_id; // provide NULL Value if want to display All Records(online+counter)                                                
            if ($doc_token_no) {
                $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 2)));
                if (is_numeric($doc_token_no) && in_array($lang, ['en', 'll', 'll1'])) {
                    $counter_fee_calc_ids = $online_fee_calc_ids = array();
                    $conditions['fees_calculation.token_no'] = $doc_token_no;
                    $conditions['fees_calculation.delete_flag'] = 'N';
                    $conditions['fees_calculation.property_id'] = $this->property_details_entry->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'val_id !=' => 0)));
                    if (count($conditions['fees_calculation.property_id']) > 0) {
                        array_push($conditions['fees_calculation.property_id'], 0);
                    } else {
                        $conditions['fees_calculation.property_id'] = 0;
                    }
                    //get Article Id for current TOken
//                    pr($conditions);exit;
                    $doc_article_id = $this->genernalinfoentry->find('first', array('fields' => array('article_id AS article_id'), 'conditions' => array('token_no' => $doc_token_no)));
                    if (!empty($doc_article_id)) {
                        $doc_article_id = $doc_article_id[0]['article_id'];
                        $conditions['fees_calculation.delete_flag'] = 'N';
                        $conditions['fee_rule.article_id'] = array(9999, $doc_article_id);
                    }
                    //pr($doc_article_id);
                    // get Calculation Ids for selected Token
                    $calc_ids = $this->fees_calculation->find('list', array('fields' => array('fees_calculation.fee_calc_id'), 'conditions' => $conditions, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array("fee_rule.fee_rule_id=fees_calculation.fee_rule_id")))));

                    if ($calc_ids) {
                        $calc_Data = $this->fees_calculation->find('all', array('fields' => array('fees_calculation.property_id', 'fee_rule.fee_rule_id', 'fee_rule.fee_type_id', 'fees_calculation.fee_rule_id', 'fee_rule.fee_rule_desc_' . $lang, 'fees_calculation.fee_calc_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fee_rule', 'conditions' => array('fee_rule.fee_rule_id=fees_calculation.fee_rule_id')),
                            ), 'conditions' => array('fee_calc_id' => $calc_ids), 'order' => 'fees_calculation.property_id,fees_calculation.fee_rule_id',
                        ));


                        
                        $total = NULL;
                        if ($calc_Data) {//display data if both detail available
                            $html = NULL;
                            $newhtml = NULL;
                            $html .= "<style>td{padding:2px 10px 2px 10px;}</style>"
                                    . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                            if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                $newhtml .= "<style>td{padding:2px 10px 2px 10px;}</style>"
                                        . "<table border=1 width=100% align=center style=background-color:#F0F0F0;>";
                            }

                            $rule_id = $prop_id = NULL;
                            $totalSD = 0;
                            $SrProp = $SrRule = $SrCalc = 1;
                            foreach ($calc_Data as $calc) { //pr($calc);
                                if ($calc['fee_rule']['fee_type_id'] == 2) {
                                    array_push($counter_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                } else {
                                    array_push($online_fee_calc_ids, $calc['fees_calculation']['fee_calc_id']);
                                }
                                //pr($calc); exit;
//                            if ($calc['fee_rule']['fee_type_id'] == 1 || $fee_type_id == 2) {
                               
                                if (1) {
                                    $propTotalSD = 0;
                                    if ($prop_id != $calc['fees_calculation']['property_id']) {//for Displaying Property Ids
                                        $flag = $this->get_prop_same_usage_flag($doc_token_no);
                                        if ($flag == 0) {
                                            if ($calc['fees_calculation']['property_id'] != 0) {
                                                $prop_id = $calc['fees_calculation']['property_id'];
                                                $SrRule = 1;
                                            }
                                        } else {
                                            if ($calc['fees_calculation']['property_id'] != 0) {
                                                $html .= ($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';

                                                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                                if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                                    $newhtml .= ($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                                }
                                                $prop_id = $calc['fees_calculation']['property_id'];
                                                $SrRule = 1;
                                            }
                                        }
                                    }


                                    if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {

//                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "<button title='Remove' class='danger glyphicon glyphicon-trash' onClick='return removeFees('" . $calc['fees_calculation']['fee_calc_id'] . "')></button>" . "</td></tr>";                                        
                                        $html .= "<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                                            $newhtml .= "<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
                                        }
                                        $rule_id = $calc['fees_calculation']['fee_rule_id'];
                                    }

//                                    pr($newhtml); exit;
                                    //$newhtml = $html;
//                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
//                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
//                                        'joins' => array(
//                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
//                                        )
//                                    ));

                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'fee_calc_desc', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
                                        'joins' => array(
                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
                                        ), 'order' => 'item.display_order'
                                    ));

                                    if ($calc_detail) {

                                        foreach ($calc_detail as $cd) {
                                            $cds = $cd['fees_calculation_detail'];
                                            if ($cd['item']['fee_param_type_id'] == 2) {
                                                if ($cd['item']['group_display'] == 'Y') {
                                                    if ($cds['final_value'] != 0) {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD += round($cds['final_value'], 1);
                                                    }
                                                }

//                                                 pr($html);
                                            } else {
                                                if ($cd['item']['group_display'] == 'Y') {
                                                    if ($cds['final_value'] != 0) {
                                                        $html .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD -= round($cds['final_value'], 1);
                                                    }
                                                }

//                                                 pr($html); exit;
                                            }
                                        }
                                    }
                                    if ($calc_detail) {
                                        $SrCalc = 1;
                                        foreach ($calc_detail as $cd) {
                                            $cds = $cd['fees_calculation_detail'];
                                            if ($cd['item']['fee_param_type_id'] == 2) {
                                                if ($cd['item']['group_display'] == 'N') {
                                                    if ($cds['final_value'] != 0) {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //   $propTotalSD+=round($cds['final_value'], 1);
                                                    }
                                                }
//                                                 pr($newhtml);exit;
                                            } else {
                                                if ($cd['item']['group_display'] == 'N') {
                                                    if ($cds['final_value'] != 0) {
                                                        $newhtml .= "<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        //  $propTotalSD-=round($cds['final_value'], 1);
                                                    }
                                                }
//                                                 pr($html);
                                            }
                                        }
                                    }


                                    $totalSD += $propTotalSD;
                                    $html .= "<tr style='background-color: #C3C3E5;'><td colspan=2 align=center><b>" . $rptlabels[76] . "</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $propTotalSD, 2, '.', '')) . "</b></td></tr>";
                                    $html .= "<tr style='background-color: black;'><td colspan=3 align=center></td></tr>";
                                }
                            }
//                            pr($newhtml); exit;
//                           pr($html); exit;
                            //$html.="<tr style='background-color: #8C489F; color: white;'><td colspan=2 align=center><b>Total</b></td><td align=right><b>" . $this->valuation->format_money_india(number_format((float) $totalSD, 2, '.', '')) . "</b></td></tr>";
//                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
//                                'conditions' => array('fee_calc_id' => $calc_ids,
//                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
//                            )));

                            $online_sd = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as online_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 1, 'fee_param_type_id' => array(2, 6))))
                            )));

                            if ($calc_detail[0]['fees_calculation_detail']['final_value'] != 0) {
                                $totalSD = $online_sd[0]['online_sd'];
                            }

//                            $totalSD = $online_sd[0]['online_sd'];
//                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
//                                'conditions' => array('fee_calc_id' => $calc_ids,
//                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
//                            )));

                            $counterSD = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as counter_sd'),
                                'conditions' => array('fee_calc_id' => $calc_ids,
                                    'fee_item_id' => $this->article_fee_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_type_id' => 2, 'fee_param_type_id' => array(2, 6))))
                            )));
                            if ($calc_detail[0]['fees_calculation_detail']['final_value'] != 0) {
                                $counterSD = $counterSD[0]['counter_sd'];
                            }
                            //pr($counterSD);
//                            $counterSD = $counterSD[0]['counter_sd'];
                            $html .= "</table>"
                                    . "<input type='hidden' id='onlineSD' value=" . round($totalSD, 2) . ">"
                                    . "<input type='hidden' id='counterSD' value=" . round($counterSD, 2) . ">";
//                            pr($html);exit;
                            $newhtml .= "</table><br><br>";
                            $newhtml .= $html;
//                            pr($newhtml);exit;

                            return $newhtml;
                        }
//                    $this->set(compact('calc_Data', 'calc_detail'));
                    } else {
                        return "Please Calculate Stamp Duty"; //exit;
                    }
                } else {
                    return 'Sorry! Wrong Input';
                }
            } else {
                return 'invalid input provided';
            }
        } catch (Exception $ex) {
            print_r($ex);
            $this->Session->setFlash('Sorry! Error in getting SD Detail');
        }
    }

//-------------------------------------------------------------------Update Fee Exemption --------------------------------------------------------------------------------
    public function update_fee_exemption_jh($doc_token_no) {
        try {
            $this->loadModel('fee_exemption');
            $this->fee_exemption->update_exemption($doc_token_no);
        } catch (Exception $ex) {
            pr($ex);
            exit;
            echo 'error in updating exemption';
        }
    }

//------------------***-------------view Exemption--------------------------------------------------------------

    public function delete_fee_exemption_jh() {
        try {

            $this->loadModel('fee_exemption');
            $this->loadModel('female_exemption');
            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));
            $this->female_exemption->query('delete  from ngdrstab_trn_female_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
            echo 1;

            exit;
        } catch (Exception $ex) {

            echo 'error in updating exemption';
        }
    }

    public function view_exemption_jh($doc_token_no = NULL, $sess_lang = NULL) {
        try {
            $this->autoRender = FALSE;
            $doc_token_no = isset($this->request->data['doc_token_no']) ? $this->request->data['doc_token_no'] : $doc_token_no;
            $sess_lang = isset($this->request->data['sess_lang']) ? $this->request->data['sess_lang'] : 'en';
            $lang = ($this->Session->read('sess_language')) ? $this->Session->read('sess_language') : $sess_lang;
            array_map([$this, 'loadModel'], ['fees_calculation', 'fees_calculation_detail', 'fee_exemption', 'exemption_article_rules', 'genernal_info']);
            $article_id = $this->genernal_info->field('article_id', array('token_no' => $doc_token_no));
            $exemption_data = $this->fees_calculation_detail->find('all', array(
                'fields' => array('rule.fee_rule_id', 'rule.fee_rule_desc_' . $lang, 'item.fee_item_desc_' . $lang, 'fees_calculation_detail.final_value'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'rule', 'conditions' => array('rule.fee_rule_id=fees_calculation_detail.fee_rule_id')),
                    array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
                ),
                'conditions' => array('fees_calculation_detail.item_type_id' => 2, 'fees_calculation_detail.fee_calc_id' => $this->fees_calculation->find('list', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $doc_token_no, 'delete_flag' => 'N', 'article_id' => 9998, 'fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id)))))))
            ));
            //pr($exemption_data);
            if ($exemption_data) {
                $html = "<div class='row'><div class='col-sm-2'></div><div class='col-sm-8'><div class='table-responsive'>"
                        . "<table class='table table-striped table-bordered table-hover'>"
                        . "<thead> <tr style='text-align:center'><th> Sr.No.</th> <th> Exemption Detail</th><th>Amount</th></tr></thead><tbody>";
                $total = 0;
                $srNo = 1;
                $exm_rule_id = 0;
                foreach ($exemption_data as $exm) {
                    if ($exm_rule_id != $exm['rule']['fee_rule_id']) {
                        $exm_rule_id = $exm['rule']['fee_rule_id'];
                        $html .= "<tr> <td colspan=3>" . $exm['rule']['fee_rule_desc_' . $lang] . " </tr>";
                    }
                    $html .= "<tr> "
                            . "<td>" . $srNo++ . ""
                            . "<td>" . $exm['item']['fee_item_desc_' . $lang] . "</td> <td align=right>" . $exm['fees_calculation_detail']['final_value'] . "</td></tr>";
                    $total += $exm['fees_calculation_detail']['final_value'];
                }
                $html .= "</tbody></table>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "<input type=hidden value=" . $total . " id='total_exemption'>";
                return $html;
            } else {
                return '';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in fetching data');
        }
    }

//--------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------for Adding additional Items to article (MRM-Citizon Entry) by Madhuri R------------------------------------------------------------------------
    function article_item_link_not_sd_jh() {
        try {

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            array_map([$this, 'loadModel'], ['article_fee_rule', 'article_fee_items', 'article', 'adminLevelConfig', 'conf_article_feerule_items']);
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);

            $stateid = $this->Auth->User("state_id");
            $articlelist = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => 'article_desc_' . $lang));
            $inputitemlist = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_input_item'), 'conditions' => array('fee_param_type_id' => 1, 'sd_calc_flag' => 'N'), 'order' => array('id' => 'ASC')));

            $this->set('articlelist', $articlelist);
            $this->set('inputitemlist', $inputitemlist);
            $fieldlist = array();
            $fieldlist['article_id']['select'] = 'is_select_req';
            $fieldlist['fee_item_id']['checkbox'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                $frmdata = array();
                if ($this->request->data['frm']['fee_item_id']) {
                    foreach ($this->request->data['frm']['fee_item_id'] as $item_id) {
                        $tmp = array('article_id' => $this->request->data['frm']['article_id'],
                            'fee_item_id' => $item_id,
                            'req_ip' => $ip,
                            'sd_calc_flag' => 'N',
                            'user_id' => $user_id,
                            //'created_date' => $created_date,
                            'state_id' => $stateid
                        );
                        $paramcode = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_param_code'), 'conditions' => array('fee_item_id' => $item_id)));
                        $tmp['fee_param_code'] = $paramcode[$item_id];
                        array_push($frmdata, $tmp);
                    }
                }
                $this->conf_article_feerule_items->Query("DELETE from ngdrstab_conf_article_feerule_items WHERE  fee_rule_id is NULL and sd_calc_flag='N' and article_id=?", array($this->request->data['frm']['article_id']));

                if ($this->conf_article_feerule_items->saveAll($frmdata)) {
                    $this->Session->setFlash(__("Article link to Item Successfully"));
                    $this->redirect(array('controller' => 'Fees', 'action' => 'article_item_link_not_sd'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! There is some errro');
        }
    }

//-----------------------------------Get Article Dependant Input Intems--by Madhuri R----------------------------
    public function get_article_dependent_feild_jh() {
        try {
            array_map([$this, 'loadModel'], ['conf_article_feerule_items']);
            $saveitem = $this->conf_article_feerule_items->query("select DISTINCT conf_item.fee_item_id from ngdrstab_conf_article_feerule_items conf_item ,ngdrstab_mst_article_fee_items items where  conf_item.fee_item_id=items.fee_item_id
                    and items.sd_calc_flag='N' and conf_item.article_id=" . $_POST['article_id']);
            $a = array();
            $i = 0;
            foreach ($saveitem as $item) {
                $a[$i] = $item[0]['fee_item_id'];
                $i++;
            }

            $str = implode(",", $a);
            echo ($str);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Fetching Data');
        }
    }

//--------------------------------------------------------------------------------------------------------------------------
}
