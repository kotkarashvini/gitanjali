<?php

App::import('Controller', 'Reports'); // mention at top
App::import('Controller', 'DynamicVariables'); // mention at top

class FeesController extends AppController {

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

//------------------**------------------------------------------- Fee Items ---- updated on 14-June-2017 by Shridhar -----------------------------------------------------------------------------
    public function article_fee_items_old() {
        try {
            $this->check_role_escalation(); // for invalid user checking      
            array_map([$this, 'loadModel'], ['NGDRSErrorCode', 'language', 'mainlanguage', 'State', 'User', 'article_fee_items', 'mainlanguage']);

            //declare and assign values
            $result_codes = $this->NGDRSErrorCode->find("all");
            $laug = $this->Session->read("sess_langauge");
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id')))));
            $actiontypeval = $hfid = $hfupdateflag = NULL;
            $stateid = $this->Auth->User('state_id');
            $language2 = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $itemtype = ClassRegistry::init('items_type')->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_' . $laug), 'conditions' => array('usage_param_type_id' => array(1, 2, 5, 6)), 'order' => array('id' => 'ASC')));
            $feetype = ClassRegistry::init('fee_type')->find('list', array('fields' => array('fee_type_id', 'fee_type_desc_' . $laug), 'order' => array('fee_type_desc_' . $laug => 'ASC')));
            $itemtype = ClassRegistry::init('items_type')->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_' . $laug), 'order' => array('id' => 'ASC')));
            $fees_items = $this->article_fee_items->get_fee_items($laug, 'Y'); //$sd_display_flag='Y'            
            $rounding_list = ClassRegistry::init('Rounding')->find('list', array('fields' => array('rounding_id', 'rounding_desc_' . $laug), 'conditions' => array('state_id' => $stateid)));            //set values to ctp
            $this->set(compact('result_codes', 'rounding_list', 'laug', 'languagelist', 'actiontypeval', 'hfid', 'hfupdateflag', 'language', 'language2', 'feetype', 'itemtype', 'fees_items'));
            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    //list for all unicode fieldsis_alphaspacemaxlenghth
                    $fieldlist['fee_item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_ll';
                }
            }
            $fieldlist['fee_param_type_id']['select'] = 'is_select_req';
            $fieldlist['account_head_code']['text'] = 'is_alphanumeric';
            $fieldlist['max_value']['text'] = 'is_numeric';
            $fieldlist['min_value']['text'] = 'is_numeric';
            //  $fieldlist['rounding_id']['text'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //$this->check_csrf_token($this->request->data['fees_items']['csrftoken']);

                $frm = $this->request->data['fees_items'];
                $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));
//                $frm['created_date'] = date('Y/m/d H:i:s');
                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);

                if ($_POST['actiontype'] == '1') {
                    $check = $this->article_fee_items->query("SELECT MAX(fee_item_id) FROM ngdrstab_mst_article_fee_items");
                    $fee_item_id = $check[0][0]['max'];
                    $fee_item_id = ($fee_item_id != Null) ? ($fee_item_id + 1) : 1;
                    if (($frm['fee_param_type_id'] == 1 || $frm['fee_param_type_id'] == 5)) {
                        $prmCode = $this->article_fee_items->find('first', array('fields' => array('MAX(fee_param_code) AS param_code'), 'conditions' => array('fee_param_type_id' => array(1, 5))));
                        $fee_param_code = ($this->request->data['hfid']) ? $this->article_fee_items->field('fee_param_code', array('fee_item_id' => $this->request->data['hfid'])) : NULL;
                        $frm['fee_param_code'] = ($fee_param_code) ? $fee_param_code : ((is_numeric($prmCode[0]['param_code']) || !$prmCode[0]['param_code']) ? 'FAA' : ++$prmCode[0]['param_code']);
                    } else {
                        $frm['fee_param_code'] = NULL;
                    }

                    $frm['fee_item_id'] = ($this->request->data['hfupdateflag'] == 'Y') ? $this->request->data['hfid'] : $fee_item_id;

                    $actionvalue = ($this->request->data['hfupdateflag'] == 'Y') ? "lbleditmsg" : "lblsavemsg";
                    $this->request->data['article_fee_items'] = $this->istrim($this->request->data['fees_items']);
//                    pr($this->request->data['article_fee_items']);
                    $errarr = $this->validatedata($this->request->data['article_fee_items'], $fieldlist);
//                     pr($errarr);exit;
                    if ($this->ValidationError($errarr)) {
                        //date:30-March 2017 for duplicate item checking
                        $count = $this->article_fee_items->find('count', array('conditions' => array('upper(fee_item_desc_en)' => strtoupper($frm['fee_item_desc_en']))));
                        if ($count == 0 || ($_POST['hfid'] && $count == 1)) {
                            if ($this->article_fee_items->save($frm)) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_items'));
                            } else {
                                $this->Session->setFlash(__("lblnotsavemsg"));
                            }
                        } else {
                            $this->Session->setFlash(__("Already Exists"));
                        }
                    }
                    $this->Session->setFlash(__("Improper fields going to server"));
                } else if (['actiontype'] == '2') {
                    $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_items'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

//------------------**------------------------------------------Remove Fee Items- updated on 14-June-2017 by Shridhar---------------------------------------------
    public function remove_article_fee_item_old() {
        try {
            $this->autoRender = false;
            $id = (isset($this->request->data['remove_id'])) ? base64_decode($this->request->data['remove_id']) : NULL;
            $this->loadModel('article_fee_items');
            $id = (int) $id;
            if (is_integer($id) && $this->article_fee_items->find('count', array('conditions' => array('fee_item_id' => $id))) == 1) {
                $this->article_fee_items->id = $id;
                if ($this->article_fee_items->delete($id)) {
                    return 0;
                } else {
                    return 'lblnotdeletemsg';
                }
            } else {
                return 'invalid data';
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Sorry! Error in Fetching data'));
        }
    }

    
    
    //vishal update 25/2/2020 user interface start
      public function article_fee_items() {
        try {
            //$this->check_role_escalation(); // for invalid user checking      
            array_map([$this, 'loadModel'], ['NGDRSErrorCode', 'language', 'mainlanguage', 'State', 'User', 'article_fee_items', 'mainlanguage']);

            //declare and assign values
            $result_codes = $this->NGDRSErrorCode->find("all");
            $laug = $this->Session->read("sess_langauge");
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id')))));
            $actiontypeval = $hfid = $hfupdateflag = NULL;
            $stateid = $this->Auth->User('state_id');
            $language2 = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $itemtype = ClassRegistry::init('items_type')->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_' . $laug), 'conditions' => array('usage_param_type_id' => array(1, 2, 5, 6)), 'order' => array('id' => 'ASC')));
            $feetype = ClassRegistry::init('fee_type')->find('list', array('fields' => array('fee_type_id', 'fee_type_desc_' . $laug), 'order' => array('fee_type_desc_' . $laug => 'ASC')));
            //$itemtype = ClassRegistry::init('items_type')->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_' . $laug), 'order' => array('id' => 'ASC')));
            //$fees_items = $this->article_fee_items->get_fee_items($laug, 'Y'); //$sd_display_flag='Y'   
            $fees_items = $this->article_fee_items->get_fee_items($laug);
            $rounding_list = ClassRegistry::init('Rounding')->find('list', array('fields' => array('rounding_id', 'rounding_desc_' . $laug)));            //set values to ctp
            //echo($rounding_list);
            $this->set(compact('result_codes', 'rounding_list', 'laug', 'languagelist', 'actiontypeval', 'hfid', 'hfupdateflag', 'language', 'language2', 'feetype', 'itemtype', 'fees_items'));
           
            
            $fieldlist = array();
          
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspacedashslash,is_maxlength255';
                } else {
                    //list for all unicode fieldsis_alphaspacemaxlenghth
                    $fieldlist['fee_item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_ll';
                }
            }
           
             
           
            if(@$this->request->data['fees_items']['fee_param_type_id']==1 ){//1input
                unset($fieldlist['max_value']['text']);
                     unset($fieldlist['min_value']['text']);
//             $fieldlist['max_value']['text'] = 'is_numeric';
//            $fieldlist['min_value']['text'] = 'is_numeric';
            }else{
            $fieldlist['fee_param_type_id']['select'] = 'is_select_req';
            $fieldlist['account_head_code']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['max_value']['text'] = 'is_digit';
            $fieldlist['min_value']['text'] = 'is_digit';
             $fieldlist['rounding_id']['select'] = 'is_select_req';
            //unset($fieldlist['rounding_id']['select']);
            }
            //pr($this->request->data['fees_items']['rounding_id']);
             if(@$this->request->data['fees_items']['fee_rounding_flag']=='N' ){//1input
                unset($fieldlist['rounding_id']['select']);
             }
            
            //  $fieldlist['rounding_id']['text'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
//            pr($fieldlist);exit;
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //$this->check_csrf_token($this->request->data['fees_items']['csrftoken']);

                $frm = $this->request->data['fees_items'];
                $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));
//                $frm['created_date'] = date('Y/m/d H:i:s');
                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);

                if ($_POST['actiontype'] == '1') {
                    $check = $this->article_fee_items->query("SELECT MAX(fee_item_id) FROM ngdrstab_mst_article_fee_items");
                    $fee_item_id = $check[0][0]['max'];
                    $fee_item_id = ($fee_item_id != Null) ? ($fee_item_id + 1) : 1;
                    if (($frm['fee_param_type_id'] == 1 || $frm['fee_param_type_id'] == 5)) {
                        $prmCode = $this->article_fee_items->find('first', array('fields' => array('MAX(fee_param_code) AS param_code'), 'conditions' => array('fee_param_type_id' => array(1, 5))));
                        $fee_param_code = ($this->request->data['hfid']) ? $this->article_fee_items->field('fee_param_code', array('fee_item_id' => $this->request->data['hfid'])) : NULL;
                        $frm['fee_param_code'] = ($fee_param_code) ? $fee_param_code : ((is_numeric($prmCode[0]['param_code']) || !$prmCode[0]['param_code']) ? 'FAA' : ++$prmCode[0]['param_code']);
                    } else {
                        $frm['fee_param_code'] = NULL;
                    }

                    $frm['fee_item_id'] = ($this->request->data['hfupdateflag'] == 'Y') ? $this->request->data['hfid'] : $fee_item_id;

                    $actionvalue = ($this->request->data['hfupdateflag'] == 'Y') ? "lbleditmsg" : "lblsavemsg";
                    $this->request->data['article_fee_items'] = $this->istrim($this->request->data['fees_items']);
//                    pr($this->request->data['article_fee_items']);
                    $errarr = $this->validatedata($this->request->data['article_fee_items'], $fieldlist);
                     //pr($errarr);exit;
                    if ($this->ValidationError($errarr)) {
                        //date:30-March 2017 for duplicate item checking
                        $count = $this->article_fee_items->find('count', array('conditions' => array('upper(fee_item_desc_en)' => strtoupper($frm['fee_item_desc_en']))));
                        if ($count == 0 || ($_POST['hfid'] && $count == 1)) {
                            if ($this->article_fee_items->save($frm)) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_items'));
                            } else {
                                $this->Session->setFlash(__("lblnotsavemsg"));
                            }
                        } else {
                            $this->Session->setFlash(__("Already Exists"));
                             $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_items'));
                        }
                    }else{
                         $this->Session->setFlash(__("Improper fields going to server"));
                    }
                   
                } else if (['actiontype'] == '2') {
                    
                    $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_items'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }



    public function remove_article_fee_item($fee_item_id = null) {
        try {
           
             $this->autoRender = false;
//            $id = (isset($this->request->data['remove_id'])) ? ($this->request->data['remove_id']) : NULL;
            $this->loadModel('article_fee_items');
//            $id = (int) $id;
           
            if (isset($fee_item_id) && is_numeric($fee_item_id)) {
               $delete_permission_flag=$this->article_fee_items->query("select delete_permission_flag from ngdrstab_mst_article_fee_items where fee_item_id=$fee_item_id");  
             
                if($delete_permission_flag[0][0]['delete_permission_flag']=='Y'){
                 $this->article_fee_items->fee_item_id = $fee_item_id;
                if ($this->article_fee_items->delete($fee_item_id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('action' => 'article_fee_items'));
                }
                }else{
                    $this->Session->setFlash(__('The Record Has Been Not Permission To Deleted'));
                    return $this->redirect(array('action' => 'article_fee_items'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }
    //end
//------------------**-------------------------------------------Fee Item List--by Priyanka updated on 14-June-2017 by Shridhar------------------------------------------------------------------
    public function fee_item_list_old() {
        try {
            $this->check_role_escalation(); // for invalid user checking
            //load Model
            array_map([$this, 'loadModel'], ['article_fee_item_list', 'article_fee_items', 'NGDRSErrorCode', 'language', 'mainlanguage']);

            $result_codes = $this->NGDRSErrorCode->find("all");
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $user_id = $this->Auth->User("user_id");

            $feeitemdata = ClassRegistry::init('article_fee_items')->find('list', array('fields' => array('article_fee_items.fee_item_id', 'article_fee_items.fee_item_desc_en'), 'conditions' => array('list_flag' => 'Y'), 'order' => array('fee_item_desc_en' => 'ASC')));

            $feeitemlistrecord = $this->article_fee_item_list->find('all', array('fields' => array('article_fee_items.fee_item_desc_en', 'article_fee_item_list.list_item_value', 'article_fee_item_list.fee_item_list_id', 'article_fee_item_list.fee_item_id', 'article_fee_item_list.fee_item_list_desc_en', 'article_fee_item_list.fee_item_list_desc_ll', 'article_fee_item_list.fee_item_list_desc_ll1', 'article_fee_item_list.fee_item_list_desc_ll2', 'article_fee_item_list.fee_item_list_desc_ll3', 'article_fee_item_list.fee_item_list_desc_ll4'),
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'article_fee_items', 'conditions' => array('article_fee_items.fee_item_id=article_fee_item_list.fee_item_id')))));
            // set values
            $this->set(compact('feeitemlistrecord', 'feeitemdata', 'user_id', 'languagelist', 'result_codes'));

            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['fee_item_list_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    $fieldlist['fee_item_list_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
            $fieldlist['fee_item_id']['select'] = 'is_select_req';
            $fieldlist['list_item_value']['text'] = 'is_numeric';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['article_fee_item_list']['csrftoken']);
                $save_flag = 'Y';
                $formData = $this->request->data['article_fee_item_list'];
                $hfid = base64_decode($formData['item']);
                if ($hfid) {
                    if (is_numeric($hfid)) {
                        $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('fee_item_list_id' => $hfid)));
                        if ($record_count == 1) {
                            $formData['fee_item_list_id'] = $hfid;
                            $actionvalue = "lbleditmsg";
                        }
                        unset($record_count);
                    } else {
                        $save_flag = 'I';
                        $hfid = NULL;
                    }
                } else {
                    $actionvalue = "lblsavemsg";
                    $save_flag = 'Y';
                }
                //check for duplication of Item Case Insensitive
                $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('UPPER(fee_item_list_desc_en)' => strtoupper($formData['fee_item_list_desc_en']), 'fee_item_id' => $formData['fee_item_id'])));
                if (($record_count > 1 && $hfid) || ($record_count > 0 && $hfid == NULL)) {
                    $save_flag = 'A';
                }
                unset($record_count);
                //duplication checking for list_item_value
                $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('list_item_value' => $formData['list_item_value'], 'fee_item_id' => $formData['fee_item_id'])));
                if (($record_count > 1 && $hfid) || ($record_count > 0 && $hfid == NULL)) {
                    $save_flag = 'IIA';
                }
                $formData = $this->istrim($formData);
                $errarr = $this->validatedata($this->request->data['article_fee_item_list'], $fieldlist);
                if ($this->ValidationError($errarr)) {
                    if ($save_flag == 'Y') {
                        $formData['req_ip'] = $this->request->clientIp();
                        $formData['user_id'] = $user_id;
                        $formData['created_date'] = $created_date;
                        $formData['state_id'] = $stateid;
                        if ($this->article_fee_item_list->save($formData)) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fees', 'action' => 'fee_item_list'));
                            $this->set('articfeeitemlist', $this->article_fee_item_list->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else if ($save_flag == 'I') {
                        $this->Session->setFlash('Invalid Data Provided for Updation');
                    } else if ($save_flag == 'A') {
                        $this->Session->setFlash('Record already exists with this name');
                    } else if ($save_flag == 'IIA') {
                        $this->Session->setFlash('Record already exists with this Id');
                    } else {
                        $this->Session->setFlash('Invalid Input provided');
                    }
                }//post
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

//------------------**-------------------------------------------------------------------------------------------------------------------------------------
    public function remove_fee_item_list() {
        try {
            $this->autoRender = false;
            $id = (isset($this->request->data['remove_id'])) ? base64_decode($this->request->data['remove_id']) : NULL;
            $this->loadModel('article_fee_item_list');
            $id = (int) $id;
            if (is_integer($id) && $this->article_fee_item_list->find('count', array('conditions' => array('fee_item_list_id' => $id))) == 1) {
                $this->article_fee_item_list->id = $id;
                if ($this->article_fee_item_list->delete($id)) {
                    return 0;
                } else {
                    return 'lblnotdeletemsg';
                }
            } else {
                return 'invalid data';
            }
        } catch (exception $ex) {
            return $ex->getMessage();
        }
    }

    //vishal update 25/2/2020 user interface start
     public function fee_item_list() {
        try {
            $this->check_role_escalation(); // for invalid user checking
            //load Model
            array_map([$this, 'loadModel'], ['article_fee_item_list', 'article_fee_items', 'NGDRSErrorCode', 'language', 'mainlanguage']);

            $result_codes = $this->NGDRSErrorCode->find("all");
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $user_id = $this->Auth->User("user_id");

            $feeitemdata = ClassRegistry::init('article_fee_items')->find('list', array('fields' => array('article_fee_items.fee_item_id', 'article_fee_items.fee_item_desc_en'), 'conditions' => array('list_flag' => 'Y'), 'order' => array('fee_item_desc_en' => 'ASC')));

            $feeitemlistrecord = $this->article_fee_item_list->find('all', array('fields' => array('article_fee_item_list.display_order','article_fee_items.fee_item_desc_en', 'article_fee_item_list.list_item_value', 'article_fee_item_list.fee_item_list_id', 'article_fee_item_list.fee_item_id', 'article_fee_item_list.fee_item_list_desc_en', 'article_fee_item_list.fee_item_list_desc_ll', 'article_fee_item_list.fee_item_list_desc_ll1', 'article_fee_item_list.fee_item_list_desc_ll2', 'article_fee_item_list.fee_item_list_desc_ll3', 'article_fee_item_list.fee_item_list_desc_ll4'),
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'article_fee_items', 'conditions' => array('article_fee_items.fee_item_id=article_fee_item_list.fee_item_id')))));
            // set values
            $this->set(compact('feeitemlistrecord', 'feeitemdata', 'user_id', 'languagelist', 'result_codes'));

            $fieldlist = array();
            
             $fieldlist['fee_item_id']['select'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['fee_item_list_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    $fieldlist['fee_item_list_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
           
            $fieldlist['list_item_value']['text'] = 'is_numeric';
            $fieldlist['display_order']['text'] = 'is_numeric';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['article_fee_item_list']['csrftoken']);
                $save_flag = 'Y';
                $formData = $this->request->data['article_fee_item_list'];
                $hfid = base64_decode($formData['item']);
                if ($hfid) {
                    if (is_numeric($hfid)) {
                        $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('fee_item_list_id' => $hfid)));
                        if ($record_count == 1) {
                            $formData['fee_item_list_id'] = $hfid;
                            $actionvalue = "lbleditmsg";
                        }
                        unset($record_count);
                    } else {
                        $save_flag = 'I';
                        $hfid = NULL;
                    }
                } else {
                    $actionvalue = "lblsavemsg";
                    $save_flag = 'Y';
                }
                //check for duplication of Item Case Insensitive
                $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('UPPER(fee_item_list_desc_en)' => strtoupper($formData['fee_item_list_desc_en']), 'fee_item_id' => $formData['fee_item_id'])));
                if (($record_count > 1 && $hfid) || ($record_count > 0 && $hfid == NULL)) {
                    $save_flag = 'A';
                }
                unset($record_count);
                //duplication checking for list_item_value
                $record_count = $this->article_fee_item_list->find('count', array('conditions' => array('list_item_value' => $formData['list_item_value'], 'fee_item_id' => $formData['fee_item_id'])));
                if (($record_count > 1 && $hfid) || ($record_count > 0 && $hfid == NULL)) {
                    $save_flag = 'IIA';
                }
                $formData = $this->istrim($formData);
                $errarr = $this->validatedata($this->request->data['article_fee_item_list'], $fieldlist);
                if ($this->ValidationError($errarr)) {
                    if ($save_flag == 'Y') {
                        $formData['req_ip'] = $this->request->clientIp();
                        $formData['user_id'] = $user_id;
                        $formData['created_date'] = $created_date;
                        $formData['state_id'] = $stateid;
                        if ($this->article_fee_item_list->save($formData)) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fees', 'action' => 'fee_item_list'));
                            $this->set('articfeeitemlist', $this->article_fee_item_list->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else if ($save_flag == 'I') {
                        $this->Session->setFlash('Invalid Data Provided for Updation');
                    } else if ($save_flag == 'A') {
                        $this->Session->setFlash('Record already exists with this name');
                    } else if ($save_flag == 'IIA') {
                        $this->Session->setFlash('Record already exists with this Id');
                    } else {
                        $this->Session->setFlash('Invalid Input provided');
                    }
                }//post
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

//------------------**-------------------------------------------------------------------------------------------------------------------------------------
   

//-------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------- New Fee Rule with Tab Dated 05-April-2017 to 21 April-2017 -------------------------------------------------   
//------------------*****-------------------------------------------------------------------------------------------------------------------------------------
    public function fee_rule_index_old() {
        try {
            $this->check_role_escalation(); // for invalid user checking
            array_map([$this, 'loadModel'], ['article_fee_rule']);
            $lang = $this->Session->read('sess_langauge');
            $field = array_keys($this->article_fee_rule->getColumnTypes());
            $ruleList = $this->article_fee_rule->find('all', array('fields' => array('article.article_desc_' . $lang, 'article_fee_rule.fee_rule_id', 'article_fee_rule.reference_no', 'article_fee_rule.fee_rule_desc_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=article_fee_rule.article_id', 'article.display_flag' => 'Y'))
                ),
                'order' => array('article_fee_rule.created DESC')));
            $this->set(compact('lang', 'ruleList', 'field'));
            if ($this->request->is('POST')) {
                $this->Session->write('fee_rule_id', (isset($this->request->data['feeRuleId'])) ? $this->request->data['feeRuleId'] : NULL);
                $csrf_token = $this->Session->read('csrftoken');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $csrf_token));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in getting Fee Rules');
        }
    }
 public function fee_rule_index() {
        try {
            $this->check_role_escalation(); // for invalid user checking
            array_map([$this, 'loadModel'], ['article_fee_rule']);
            $lang = $this->Session->read('sess_langauge');
            $field = array_keys($this->article_fee_rule->getColumnTypes());
            $ruleList = $this->article_fee_rule->find('all', array('fields' => array('article.article_desc_' . $lang, 'article_fee_rule.fee_rule_id', 'article_fee_rule.reference_no', 'article_fee_rule.fee_rule_desc_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=article_fee_rule.article_id', 'article.display_flag' => 'Y'))
                ),
                'order' => array('article_fee_rule.created DESC')));
            $this->set(compact('lang', 'ruleList', 'field'));
            if ($this->request->is('POST')) {
                $this->Session->write('fee_rule_id', (isset($this->request->data['feeRuleId'])) ? $this->request->data['feeRuleId'] : NULL);
                $csrf_token = $this->Session->read('csrftoken');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $csrf_token));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in getting Fee Rules');
        }
    }
//-----------------*******---------------------- Remove Fee Rule with Item Linkage, Subrule ---------------------------------------
    public function remove_fee_rule() {
        try {

            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['article_fee_rule', 'conf_article_feerule_items', 'article_fee_subrule']);
            $rule_id = base64_decode($this->request->data['rule_id']);
            if ($this->article_fee_rule->delete($rule_id)) {
                if ($this->conf_article_feerule_items->deleteAll(array('fee_rule_id' => $rule_id), FALSE)) {
                    if ($this->article_fee_subrule->deleteAll(array('fee_rule_id' => $rule_id), FALSE)) {
                        return 0;
                    }
                }
            } else {
                return 1;
            }
        } catch (Exception $ex) {
            return 'Error in Removing Rule';
        }
    }

//------------------*******-------------------------------------------Copy  Fee Item, Subrule from one Rule to Another by Rule Id-----------------------------------------------
    public function copy_fee_rule_old() {
        try {
            $this->autoRender = FALSE;
//            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            array_map([$this, 'loadModel'], ['article_fee_rule', 'conf_article_feerule_items', 'article_fee_subrule']);
            $copyFrom_id = base64_decode($this->request->data['from_id']);
            $pasteTo_id = base64_decode($this->request->data['to_id']);
            $article_id = $this->article_fee_rule->field('article_id', array('fee_rule_id' => $pasteTo_id));
            $user_id = $this->Auth->User("user_id");
            $req_ip = $this->request->clientIp();
            $state_id = $this->Auth->User('state_id');
            $created_date = date('Y-m-d H:i:s');
            $returnFlag = 0;
            if (!$this->conf_article_feerule_items->copy_items($article_id, $copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date)) {
                $returnFlag = 1;
            } else {
                if ($this->conf_article_feerule_items->find('count', array('conditions' => array('fee_rule_id' => $copyFrom_id))) > 0) {
                    $returnFlag = 0;
                } else {
                    $returnFlag = 1;
                }
            }
            if (!$this->article_fee_subrule->copy_all_subrule($copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date)) {
                $returnFlag = 1;
            } else if ($this->article_fee_subrule->find('count', array('conditions' => array('fee_rule_id' => $copyFrom_id))) > 0) {
                $returnFlag = 0;
            } else {
                $returnFlag = 1;
            }
            return $returnFlag;
        } catch (Exception $ex) {
            return 'Sorry! Unable to copy Rule';
        }
    }

       public function copy_fee_rule() {
        try {
            $this->autoRender = FALSE;
//            pr($this->request->data['from_id']);
//            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            array_map([$this, 'loadModel'], ['article_fee_rule', 'conf_article_feerule_items', 'article_fee_subrule']);    
//            $copyFrom_id = base64_decode($this->request->data['from_id']);
            $copyFrom_id = $this->request->data['from_id'];
            $pasteTo_id = $this->request->data['to_id']; 
            $article_id = $this->article_fee_rule->field('article_id', array('fee_rule_id' => $pasteTo_id));
             //pr($article_id);exit;
            $user_id = $this->Auth->User("user_id");
            $req_ip = $this->request->clientIp();
            $state_id = $this->Auth->User('state_id');
            $created_date = date('Y-m-d H:i:s'); 
            $returnFlag = 0;
            if (!$this->conf_article_feerule_items->copy_items($article_id, $copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date)) {
                $returnFlag = 1;
            } else {
                if ($this->conf_article_feerule_items->find('count', array('conditions' => array('fee_rule_id' => $copyFrom_id))) > 0) {
                    $returnFlag = 0;
                } else {
                    $returnFlag = 1;
                }
            }
            if (!$this->article_fee_subrule->copy_all_subrule($copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date)) {
                $returnFlag = 1;
                
            } else if ($this->article_fee_subrule->find('count', array('conditions' => array('fee_rule_id' => $copyFrom_id))) > 0) {
                $returnFlag = 0;
            } else {
                $returnFlag = 1;
            }
            return $returnFlag;
        } catch (Exception $ex) {
            return 'Sorry! Unable to copy Rule';
        }
    }
//------------------**------------------------------------------- Article--Fee Rule-------------------------------------------------------------------------------
    public function article_fee_rule_10aug17($csrftoken = NULL) {
        try {
//load Models
            array_map([$this, 'loadModel'], ['article_fee_rule', 'exemption_article_rules', 'finyear', 'article', 'adminLevelConfig', 'language', 'user_defined_dependancy1', 'user_defined_dependancy2', 'usage_main_category', 'adminLevelConfig', 'mainlanguage', 'NGDRSErrorCode']);
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $fieldname = array_keys($this->article_fee_rule->getColumnTypes());
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id')))));
            $this->set('languagelist', $languagelist);
            $articlelist = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => 'article_desc_' . $lang));
            $fee_rule_id = $this->Session->read('fee_rule_id');
            $this->article_fee_rule->id = ($fee_rule_id) ? $fee_rule_id : NULL;
            $fee_rule = ($fee_rule_id) ? ($fee_rule_id . ' : ' . $this->article_fee_rule->field('fee_rule_desc_' . $lang)) : (NULL);
            $finyearList = $this->finyear->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('current_year DESC,finyear_id')));
            $maincatlist = $this->usage_main_category->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang)));
            $udd1list = $this->user_defined_dependancy1->find('list', array('fields' => array('user_defined_dependency1_id', 'user_defined_dependency1_desc_' . $lang)));
            $udd2list = $this->user_defined_dependancy2->find('list', array('fields' => array('user_defined_dependency2_id', 'user_defined_dependency2_desc_' . $lang)));
            $configure = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $configure = $configure['adminLevelConfig'];
            $this->set('statelist', ClassRegistry::init('State')->find('list', array('fields' => array('state_id', 'state_name_' . $lang), 'order' => array('state_name_en' => 'ASC'), 'conditions' => array('state_id' => $stateid))));
            $this->set('divisionlist', ClassRegistry::init('division')->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_name_en' => 'ASC'))));
            $this->set('gov_body_type', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'ulb_desc'), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('dev_land_type', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_' . $lang => 'ASC'))));

            $this->set(compact('fieldname', 'lang', 'finyearList', 'articlelist', 'fee_rule', 'maincatlist', 'udd1list', 'udd2list', 'configure'));
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['article_id']['select'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_rule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                } else {
                    //list for all unicode fieldsis_alphaspacemaxlenghth
                    $fieldlist['fee_rule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $this->set('fieldlist', $fieldlist);
            //setting the error message to empty as it shows error at first time
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            if ($this->request->is('post')) {// record to save, Update
                $frm = $this->request->data['frm'];
                $this->check_csrf_token($frm['csrftoken']);

                //if Rule Selected from Fee Rule Id
                $frm['rule_id'] = ctype_xdigit($this->Session->read('fee_rule_id')) ? $this->Session->read('fee_rule_id') : NULL;

                $frm['effective_date'] = date('Y-m-d', strtotime($frm['effective_date']));
                if ($frm['rule_id']) {
                    $frm['fee_rule_id'] = $frm['rule_id'];
                }
                $exm_articles = $frm['exm_article_id'];
                if ($frm['exm_article_id']) {
                    $frm['exm_article_id'] = implode(',', $frm['exm_article_id']);
                }
                $rc_count = $this->article_fee_rule->find('count', array('conditions' => array('fee_rule_desc_en' => $frm['fee_rule_desc_en'])));
                if (($rc_count == 0) || (isset($frm['fee_rule_id']) and $rc_count == 1)) {
                    //for setting default session user_id, Ip Address, State_id(User State_id)
                    $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));


                    if ($this->article_fee_rule->save($frm)) {
                        $saveType = "lbleditmsg";
                        if (!$frm['rule_id']) {
                            $lastInsertId = $this->article_fee_rule->getLastInsertId();
                            $this->Session->write('fee_rule_id', $lastInsertId);
                            $frm['fee_rule_id'] = $lastInsertId;
                            $saveType = "lblsavemsg";
                        }

                        if ($exm_articles) {
                            $exm_article_rules = array();
                            foreach ($exm_articles as $exm_rule_id) {
                                if ($this->exemption_article_rules->find('count', array('conditions' => array('article_id' => $exm_rule_id, 'fee_rule_id' => $frm['fee_rule_id']))) > 0) {
                                    continue;
                                } else {
                                    array_push($exm_article_rules, array('article_id' => $exm_rule_id, 'fee_rule_id' => $frm['fee_rule_id']));
                                }
                            }
                            $this->exemption_article_rules->saveAll($exm_article_rules);
                        }
                        $this->Session->setFlash(__($saveType));
                        $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule_item_linkage', $this->Session->read('csrftoken')));
                    } else {
                        $this->Session->setFlash("Sorry !  Failed to Save Record");
                    }
                } else {
                    $this->Session->setFlash("Sorry !  Record already Exists to Save Record");
                }
            } else {
                $this->check_csrf_token($csrftoken);
                $this->request->data['frm'] = $this->article_fee_rule->read()['article_fee_rule'];
            }
        } catch (Exception $ex) {
            pr($ex);
            echo $this->Session->setFlash('Sorry! Error while fetching data');
        }
    }

    //--------------------------------------------------------------
    public function article_fee_rule($csrftoken = NULL) {
        try {
//load Models
            array_map([$this, 'loadModel'], ['article_fee_rule', 'exemption_article_rules', 'finyear', 'article', 'adminLevelConfig', 'language', 'user_defined_dependancy1', 'user_defined_dependancy2', 'usage_main_category', 'adminLevelConfig', 'mainlanguage', 'NGDRSErrorCode']);
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $fieldname = array_keys($this->article_fee_rule->getColumnTypes());
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id')))));
            $this->set('languagelist', $languagelist);
            $articlelist = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => 'article_desc_' . $lang));
            $fee_rule_id = $this->Session->read('fee_rule_id');
            $this->article_fee_rule->id = ($fee_rule_id) ? $fee_rule_id : NULL;
            $fee_rule = ($fee_rule_id) ? ($fee_rule_id . ' : ' . $this->article_fee_rule->field('fee_rule_desc_' . $lang)) : (NULL);
            $finyearList = $this->finyear->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('current_year DESC,finyear_id')));
            $maincatlist = $this->usage_main_category->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang)));
            $udd1list = $this->user_defined_dependancy1->find('list', array('fields' => array('user_defined_dependency1_id', 'user_defined_dependency1_desc_' . $lang)));
            $udd2list = $this->user_defined_dependancy2->find('list', array('fields' => array('user_defined_dependency2_id', 'user_defined_dependency2_desc_' . $lang)));
            $configure = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $configure = $configure['adminLevelConfig'];
            $this->set('statelist', ClassRegistry::init('State')->find('list', array('fields' => array('state_id', 'state_name_' . $lang), 'order' => array('state_name_en' => 'ASC'), 'conditions' => array('state_id' => $stateid))));
            $this->set('divisionlist', ClassRegistry::init('division')->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_name_en' => 'ASC'))));
            $this->set('gov_body_type', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'ulb_desc'), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('dev_land_type', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_' . $lang => 'ASC'))));

            $this->set(compact('fieldname', 'lang', 'finyearList', 'articlelist', 'fee_rule', 'maincatlist', 'udd1list', 'udd2list', 'configure'));
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['finyear_id']['select'] = 'is_required,is_select_req';
//                $fieldlist['eff_date']['text'] = 'is_date_empty';
            $fieldlist['reference_no']['text'] = 'is_alphanumspacecommasqrroundbrackets';


            $fieldlist['article_id']['select'] = 'is_select_req';


            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_rule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                } else {
                    //list for all unicode fieldsis_alphaspacemaxlenghth
                    $fieldlist['fee_rule_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $this->set('fieldlist', $fieldlist);
            //setting the error message to empty as it shows error at first time
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            if ($this->request->is('post')) {// record to save, Update
                $frm = $this->request->data['frm'];
                //   $this->check_csrf_token($frm['csrftoken']);
                //if Rule Selected from Fee Rule Id
                $frm['rule_id'] = ctype_xdigit($this->Session->read('fee_rule_id')) ? $this->Session->read('fee_rule_id') : NULL;

                $frm['effective_date'] = date('Y-m-d', strtotime($frm['effective_date']));
                if ($frm['rule_id']) {
                    $frm['fee_rule_id'] = $frm['rule_id'];
                }
                $exm_articles = $frm['exm_article_id'];
                if ($frm['exm_article_id']) {
                    $frm['exm_article_id'] = implode(',', $frm['exm_article_id']);
                }
                $rc_count = $this->article_fee_rule->find('count', array('conditions' => array('fee_rule_desc_en' => $frm['fee_rule_desc_en'])));
                if (($rc_count == 0) || (isset($frm['fee_rule_id']) and $rc_count == 1)) {
                    //for setting default session user_id, Ip Address, State_id(User State_id)
                    $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));


                    if ($this->article_fee_rule->save($frm)) {
                        $saveType = "lbleditmsg";
                        if (!$frm['rule_id']) {
                            $lastInsertId = $this->article_fee_rule->getLastInsertId();
                            $this->Session->write('fee_rule_id', $lastInsertId);
                            $frm['fee_rule_id'] = $lastInsertId;
                            $saveType = "lblsavemsg";
                        }

                        if ($exm_articles) {
                            $exm_article_rules = array();
                            foreach ($exm_articles as $exm_rule_id) {
                                if ($this->exemption_article_rules->find('count', array('conditions' => array('article_id' => $exm_rule_id, 'fee_rule_id' => $frm['fee_rule_id']))) > 0) {
                                    continue;
                                } else {
                                    array_push($exm_article_rules, array('article_id' => $exm_rule_id, 'fee_rule_id' => $frm['fee_rule_id']));
                                }
                            }
                            $this->exemption_article_rules->saveAll($exm_article_rules);
                        }
                        $this->Session->setFlash(__($saveType));
                        $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule_item_linkage', $this->Session->read('csrftoken')));
                    } else {
                        $this->Session->setFlash("Sorry !  Failed to Save Record");
                    }
                } else {
                    $this->Session->setFlash("Sorry !  Record already Exists to Save Record");
                }
            } else {
                $this->check_csrf_token($csrftoken);
                $this->request->data['frm'] = $this->article_fee_rule->read()['article_fee_rule'];
            }
        } catch (Exception $ex) {
            pr($ex);
            echo $this->Session->setFlash('Sorry! Error while fetching data');
        }
    }

//-------------------**------------------------------------------ Article-Rule with item Linkage (Rule list according to Article FeeRule with Item Linkage)--------------------------------------------------------------
    public function article_fee_rule_item_linkage($csrftoken = NULL) {
        try {
            //--------------- For Session Fee Rule Id Validation---------------------------
            if (!$this->Session->read('fee_rule_id')) {
                $this->Session->setFlash('Please fill Fee Rule Details');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $this->Session->read('csrftoken')));
            }
            array_map([$this, 'loadModel'], ['article_fee_rule', 'article_fee_items', 'conf_article_feerule_items', 'article', 'adminLevelConfig', 'language', 'mainlanguage', 'NGDRSErrorCode']);            //------------------------------------------------Validations-----------------
            //check for correct Fee Rule Id
            if ($this->Session->read('fee_rule_id') && is_numeric($this->Session->read('fee_rule_id')) && $this->article_fee_rule->hasAny(array('fee_rule_id' => $this->Session->read('fee_rule_id')))) {
                //load Models
                $lang = $this->Session->read("sess_langauge");
                $stateid = $this->Auth->User("state_id");
                $fieldname = array_keys($this->conf_article_feerule_items->getColumnTypes());
                $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id')))));
                $this->set('languagelist', $languagelist);
                $fee_rule_id = $this->Session->read('fee_rule_id');
                $this->article_fee_rule->id = ($fee_rule_id) ? $fee_rule_id : NULL;
                
                $outputItemlist = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_input_item'), 'conditions' => array('fee_param_type_id !=' => array(1, 5), 'sd_calc_flag' => 'Y'), 'order' => array('fee_input_item' => 'ASC')));
//                pr($outputItemlist); exit;
                $linkedInputlist = $this->conf_article_feerule_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_rule_id' => $fee_rule_id, 'fee_rule_id is NOT NULL'), 'order' => array('fee_rule_id' => 'ASC')));
//                pr($fee_rule_id);exit;
//                $linkedInputs = $this->conf_article_feerule_items->find('all', array('fields' => array('conf_article_feerule_items.article_rule_item_id', 'item.fee_param_code', 'item.fee_item_desc_' . $lang),
//                    'joins' => array(
//                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id'))
//                    ),
//                    'conditions' => array('conf_article_feerule_items.fee_item_id' => $linkedInputlist, 'conf_article_feerule_items.fee_rule_id' => $fee_rule_id)
//                ));
                
                $inputItemlist = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_input_item'), 'conditions' => array('fee_param_type_id' => array(1, 5), 'fee_item_id !=' => $linkedInputlist, 'sd_calc_flag' => 'Y'), 'order' => array('fee_input_item' => 'ASC')));
//               pr($inputItemlist);exit;
                $fee_rule = ($fee_rule_id) ? ($fee_rule_id . ' : ' . $this->article_fee_rule->field('fee_rule_desc_' . $lang)) : (NULL);
                $this->set(compact('fieldname', 'lang', 'articlelist', 'outputItemlist', 'inputItemlist', 'fee_rule'));

                if ($this->request->is('post')) {// record to save, Update
                    $tmpitemlist = NULL;
                    $frm = $this->request->data['frm'];
                    $this->check_csrf_token($frm['csrftoken']);
                    //for user_id,Ip,State_id
                    $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));
                    $frm['article_id'] = $this->article_fee_rule->field('article_id', array('fee_rule_id' => $fee_rule_id));
                    $frm['fee_rule_id'] = $fee_rule_id;

                    if ($frm['fee_item_list']) {
                        $ItemLinkdata = array();
                        foreach ($frm['fee_item_list'] as $item_id) {
                            $tmp = array('article_id' => $frm['article_id'],
                                'fee_rule_id' => $frm['fee_rule_id'],
                                'fee_item_id' => $item_id
                            );
                            $paramcode = $this->article_fee_items->get_param_code($item_id);
                            $tmp['fee_param_code'] = $paramcode[$item_id];
                            $tmp = array_merge($tmp, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));
                            array_push($ItemLinkdata, $tmp);
                        }
                        if ($this->conf_article_feerule_items->saveAll($ItemLinkdata)) {
                            $this->article_fee_rule->id = $fee_rule_id;
                            $linkedFeeItems = $this->conf_article_feerule_items->find('list', array('fields' => array('fee_item_id', 'fee_item_id'), 'conditions' => array('fee_rule_id' => $fee_rule_id)));
                            if ($linkedFeeItems) {
                                $this->article_fee_rule->saveField('fee_item_list', implode(',', $linkedFeeItems));
                                $this->article_fee_rule->saveField('input_item_link_flag', 'Y');
                            }
                            $this->Session->setFlash(__("lblsavemsg"));
                             $this->redirect(array('controller' => 'Fees', 'action' => 'linked_feeitems_config', $this->Session->read('csrftoken')));
                            //$this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_sub_rule', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("lblsavemsg"));
                         $this->redirect(array('controller' => 'Fees', 'action' => 'linked_feeitems_config', $this->Session->read('csrftoken')));
                        //$this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_sub_rule', $this->Session->read('csrftoken')));
                    }
                } else {//else part of post
                    $this->check_csrf_token($csrftoken);
                }
            } else {
                $this->Session->setFlash("Sorry! No Data Found");
            }
        } catch (Exception $ex) {
            $this->Session->setFlash("Sorry! Error in Fetching data");
        }
    }

//------------------*****-------------------------remove Fee Input Item from Fee Rule (conf_article_feerule_items)-------------------------------------------------------
    public function remove_fee_rule_item() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('conf_article_feerule_items');
            $item_link_id = $this->request->data['item_id'];
            if (is_numeric($item_link_id) && ctype_digit($item_link_id)) {
                $this->conf_article_feerule_items->id = $item_link_id;
                if ($this->conf_article_feerule_items->delete()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 'wrong Input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Removing Item');
        }
    }

    public function linked_feeitems_config($csrftoken = NULL) {
        try {
            array_map([$this, 'loadModel'], ['article_fee_rule', 'conf_article_feerule_items', 'article_fee_items']);
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            //checking Selection of rule Id
            $fee_rule_id = $this->Session->read('fee_rule_id');
//            $ruleid = $this->Session->read('valuation_rule_id');
            if (!$fee_rule_id) {
                $this->Session->setFlash('Please Select or Save Rule.');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $this->Session->read('csrftoken')));
            }

            $this->article_fee_items->virtualFields = array(
                'fee_item_desc' => 'CONCAT(fee_param_code|| \' : \' || fee_item_desc_' . $lang . ')'
            );

            $linkedInputsList = $this->conf_article_feerule_items->find('list', array('fields' => array('fee_item_id'), 'conditions' => array('fee_rule_id' => $fee_rule_id)));
            $linkedInputs = $this->conf_article_feerule_items->find('all', array('fields' => array('conf_article_feerule_items.id', 'conf_article_feerule_items.display_order', 'conf_article_feerule_items.mandate_flag', 'feeitem.fee_param_code', 'feeitem.fee_item_desc_' . $lang), 'conditions' => array('feeitem.fee_item_id' => $linkedInputsList, 'fee_rule_id' => $fee_rule_id),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'feeitem', 'conditions' => array('feeitem.fee_item_id=conf_article_feerule_items.fee_item_id'))
                ), 'order' => array('conf_article_feerule_items.display_order', 'feeitem.display_order')
            ));
            $user_id = $this->Auth->User('user_id');
            $rulename = $this->article_fee_rule->field('fee_rule_desc_' . $lang, array('fee_rule_id' => $fee_rule_id));
            //pr($rulename);exit;
            $this->set(compact('fieldname', 'fee_rule_id', 'rulename', 'lang', 'linkedInputs'));

            if ($this->request->is('POST')) {
                // echo 1;exit;
                $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                unset($this->request->data['frm']);
                $formData = $this->request->data;
                $item_link_ids = ($this->request->data) ? array_keys($this->request->data) : NULL;
//                pr($item_link_ids);exit;
                //-------------Check If Already Items Linked----------------------------------------------------
                $linkage_input_data = array();
                foreach ($item_link_ids as $item_link_id) {
                    $data1 = array(
                        'display_order' => $formData[$item_link_id]['display_order'],
                        'mandate_flag' => "'" . $formData[$item_link_id]['mandate_flag'] . "'",
                        'state_id' => $stateid,
                        'user_id' => $user_id,
                        'req_ip' => "'" . $_SERVER['REMOTE_ADDR'] . "'"
                    );
                    $this->conf_article_feerule_items->create();
                    $this->conf_article_feerule_items->updateAll($data1, array('fee_rule_id' => $fee_rule_id));
                }


                $this->Session->setFlash(__('lblsavemsg'));
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_sub_rule', $this->Session->read('csrftoken')));
            } else {
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

//------------------****------------------------------------------- Article Fee Sub Rule--------------------------------------------------------------------------------------------------
    public function article_fee_sub_rule_10aug17($csrftoken = NULL) {
        try {

            //--------------- For Session Fee Rule Id---------------------------
            if (!$this->Session->read('fee_rule_id')) {
                $this->Session->setFlash('Please fill Fee Rule Details');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $this->Session->read('csrftoken')));
            }
            //------------------------------------------------------------------
//load Models
            array_map([$this, 'loadModel'], ['operators', 'finyear', 'conf_article_feerule_items', 'article_fee_rule', 'article_fee_subrule', 'article_fee_items', 'fee_dependancy_attribute', 'fee_dependancy_attribute_conf', 'article', 'fee_rule_article_conf']);
//set Data to ctp
            $fields = array_keys($this->article_fee_subrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $fee_rule_id = $this->Session->read('fee_rule_id');
            $ruleid = $sbrlflg = $hsrflg = $hfaction = NULL;
            $operators = $this->operators->find('list', array('fields' => array('operatorsign', 'optrname'), 'order' => array('operator_name_en' => 'ASC'))); //, 'order' => 'article_desc_' . $lang)
            $outitemlist = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_item_desc_' . $lang), 'conditions' => array('fee_param_type_id' => array(2, 6)), 'order' => array('fee_item_desc_' . $lang => 'ASC')));
            $inputItems = $this->conf_article_feerule_items->get_linked_items_json($fee_rule_id);
            $this->set('gov_body_type', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'ulb_desc'), 'order' => array('class_description_en' => 'ASC'))));
            $feeSubruleData = $this->article_fee_subrule->find('all', array('fields' => array('article_fee_subrule.*', 'gov_body.class_description_' . $lang, 'item.fee_item_desc_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id')),
                    array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'gov_body', 'type' => 'left', 'conditions' => array('gov_body.ulb_type_id=article_fee_subrule.ulb_type_id'))
                ),
                'conditions' => array('fee_rule_id' => $fee_rule_id),
                'order' => array('article_fee_subrule.created DESC')));

            $fee_rule = ($fee_rule_id) ? ($fee_rule_id . ' : ' . $this->article_fee_rule->field('fee_rule_desc_' . $lang, array('fee_rule_id' => $fee_rule_id))) : (NULL);
            $this->set(compact('fields', 'fee_rule', 'feetype', 'lang', 'stateid', 'ruleid', 'hsrflg', 'hfaction', 'dependancyattrlist', 'articlelist', 'operators', 'outitemlist', 'inputItems', 'finyearList', 'feeSubruleData'));
            //Code After Page Submit
            if ($this->request->is('post')) {
                $frm = $this->request->data['frm'];
                $this->check_csrf_token($frm['csrftoken']);
                $frm['fee_rule_id'] = $fee_rule_id;
                $frm[$fields[1]] = $frm['subruleid'];
                $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));

                // for replacing && and or with surrounding space for conditions
                for ($i = 3; $i < 18; $i+=2) {
                    $frm[$fields[$i]] = preg_replace('/\s+/', '', $frm[$fields[$i]]);
                    $frm[$fields[$i]] = str_replace('&&', ' && ', $frm[$fields[$i]]);
                    $frm[$fields[$i]] = str_replace('||', ' || ', $frm[$fields[$i]]);
                    if ($i === 5) {
                        $i = 11;
                    }
                }

                $savetype = ($frm[$fields[1]]) ? "lbleditmsg" : "lblsavemsg";
                $frm['ulb_type_id'] = (isset($frm['ulb_type_id']) && $frm['ulb_type_id']) ? $frm['ulb_type_id'] : 0;
                $rc_count = $this->article_fee_subrule->find('count', array('conditions' => array('fee_rule_id' => $frm['fee_rule_id'], 'fee_output_item_id' => $frm['fee_output_item_id'], 'ulb_type_id' => $frm['ulb_type_id'])));
                if (($rc_count == 0)or ( $frm[$fields[1]] and $rc_count == 1)) {
                    if ($this->article_fee_subrule->save($frm)) {
                        $this->Session->setFlash(__($savetype));
                        $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_sub_rule', $this->Session->read('csrftoken')));
                    } else {
                        $this->Session->setFlash("Sorry! Error in saving data");
                    }
                } else {
                    $this->Session->setFlash("Record Already Exists");
                }
            } else {//else part of post
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('There is some error in Saving Fee Rule');
        }
    }

    public function article_fee_sub_rule($csrftoken = NULL) {
        try {

            //--------------- For Session Fee Rule Id---------------------------
            if (!$this->Session->read('fee_rule_id')) {
                $this->Session->setFlash('Please fill Fee Rule Details');
                $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_rule', $this->Session->read('csrftoken')));
            }
            //------------------------------------------------------------------
//load Models
            array_map([$this, 'loadModel'], ['operators', 'finyear', 'conf_article_feerule_items', 'article_fee_rule', 'article_fee_subrule', 'article_fee_items', 'fee_dependancy_attribute', 'fee_dependancy_attribute_conf', 'article', 'fee_rule_article_conf']);
//set Data to ctp
            $fields = array_keys($this->article_fee_subrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $fee_rule_id = $this->Session->read('fee_rule_id');
            $ruleid = $sbrlflg = $hsrflg = $hfaction = NULL;
            $operators = $this->operators->find('list', array('fields' => array('operatorsign', 'optrname'), 'order' => array('operator_name_en' => 'ASC'))); //, 'order' => 'article_desc_' . $lang)
            $outitemlist = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_item_desc_' . $lang), 'conditions' => array('fee_param_type_id' => array(2, 6)), 'order' => array('fee_item_desc_' . $lang => 'ASC')));
            $inputItems = $this->conf_article_feerule_items->get_linked_items_json($fee_rule_id);
            $this->set('gov_body_type', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'ulb_desc'), 'order' => array('class_description_en' => 'ASC'))));
            $feeSubruleData = $this->article_fee_subrule->find('all', array('fields' => array('article_fee_subrule.*', 'gov_body.class_description_' . $lang, 'item.fee_item_desc_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id')),
                    array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'gov_body', 'type' => 'left', 'conditions' => array('gov_body.ulb_type_id=article_fee_subrule.ulb_type_id'))
                ),
                'conditions' => array('fee_rule_id' => $fee_rule_id),
                'order' => array('article_fee_subrule.created DESC')));

            $fee_rule = ($fee_rule_id) ? ($fee_rule_id . ' : ' . $this->article_fee_rule->field('fee_rule_desc_' . $lang, array('fee_rule_id' => $fee_rule_id))) : (NULL);
            $this->set(compact('fields', 'fee_rule', 'feetype', 'lang', 'stateid', 'ruleid', 'hsrflg', 'hfaction', 'dependancyattrlist', 'articlelist', 'operators', 'outitemlist', 'inputItems', 'finyearList', 'feeSubruleData'));

            //validations 
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist['min_value']['text'] = 'is_digit';
            $fieldlist['max_value']['text'] = 'is_digit';
            $fieldlist['local_gov_body']['text'] = 'is_select_req';
            $fieldlist['fee_subrule_desc']['text'] = 'is_alphanumericspace';
            $fieldlist['fee_calucation_desc']['text'] = 'is_alphanumspacecommaroundbrackets';
            $fieldlist['fee_output_item_id']['select'] = 'is_select_req'; // must require
            $fieldlist['fee_output_item_order']['select'] = 'is_select_req';
            $fieldlist['max_value_condition_flag']['radio'] = 'is_radiostring1';
//            $fieldlist['max_value_formula']['radio'] = 'is_numeric';
            $fieldlist['fee_rule_cond1']['text'] = 'is_formula'; //is_subrule_formula
            $fieldlist['fee_rule_cond2']['text'] = 'is_formula';
            $fieldlist['fee_rule_cond3']['text'] = 'is_formula';
            $fieldlist['fee_rule_cond4']['text'] = 'is_formula';
            $fieldlist['fee_rule_cond5']['text'] = 'is_formula';
            $fieldlist['fee_rule_formula1']['text'] = 'is_formula';
            $fieldlist['fee_rule_formula2']['text'] = 'is_formula';
            $fieldlist['fee_rule_formula3']['text'] = 'is_formula';
            $fieldlist['fee_rule_formula4']['text'] = 'is_formula';
            $fieldlist['fee_rule_formula5']['text'] = 'is_formula';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            //Code After Page Submit

            if ($this->request->is('post')) {
                $frm = $this->request->data['frm'];
                $this->check_csrf_token($frm['csrftoken']);
                $frm['fee_rule_id'] = $fee_rule_id;
                $frm[$fields[1]] = $frm['subruleid'];
                $frm = array_merge($frm, array('user_id' => $this->Auth->User("user_id"), 'req_ip' => $_SERVER['REMOTE_ADDR'], 'state_id' => $this->Auth->User('state_id')));

                // for replacing && and or with surrounding space for conditions
                for ($i = 3; $i < 18; $i+=2) {
                    $frm[$fields[$i]] = preg_replace('/\s+/', '', $frm[$fields[$i]]);
                    $frm[$fields[$i]] = str_replace('&&', ' && ', $frm[$fields[$i]]);
                    $frm[$fields[$i]] = str_replace('||', ' || ', $frm[$fields[$i]]);
                    if ($i === 5) {
                        $i = 11;
                    }
                }

                $savetype = ($frm[$fields[1]]) ? "lbleditmsg" : "lblsavemsg";
                $frm['ulb_type_id'] = (isset($frm['ulb_type_id']) && $frm['ulb_type_id']) ? $frm['ulb_type_id'] : 0;
                $rc_count = $this->article_fee_subrule->find('count', array('conditions' => array('fee_rule_id' => $frm['fee_rule_id'], 'fee_output_item_id' => $frm['fee_output_item_id'], 'ulb_type_id' => $frm['ulb_type_id'])));
//                if (($rc_count == 0)or ( $frm[$fields[1]] and $rc_count == 1)) {
                if ($this->article_fee_subrule->save($frm)) {
                    $this->Session->setFlash(__($savetype));
                    $this->redirect(array('controller' => 'Fees', 'action' => 'article_fee_sub_rule', $this->Session->read('csrftoken')));
                } else {
                    $this->Session->setFlash("Sorry! Error in saving data");
                }
//                } else {
//                    $this->Session->setFlash("Record Already Exists");
//                }
            } else {//else part of post
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('There is some error in Saving Fee Rule');
        }
    }

    //--------------***---------------------------------Copy Fee Sub Rule--------------------------------------------------------------------------------------
    public function copy_fee_sub_rule() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['article_fee_rule', 'article_fee_subrule']);
            $copyFrom_id = $this->request->data['from_subrule_id'];
            $pasteTo_id = $this->request->data['to_subrule_id'];
            $article_id = $this->article_fee_rule->field('article_id', array('fee_rule_id' => $pasteTo_id));
            $user_id = $this->Auth->User("user_id");
            $req_ip = $this->request->clientIp();
            $state_id = $this->Auth->User('state_id');
            $created_date = date('Y-m-d H:i:s');
            if (!$this->article_fee_subrule->copy_single_subrule($copyFrom_id, $pasteTo_id, $req_ip, $user_id, $state_id, $created_date)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Unable to copy Rule');
        }
    }

    //--------------***--------------------------------------------Delete Subrule------------------------------------------------------------------------------------------
    public function remove_fee_sub_rule() {
        try {
            $this->autoRender = FALSE;
            $this->layout = 'ajax';
            $this->loadModel('article_fee_subrule');
            $hfRuleid = $this->request->data['fee_rule_id'];
            $subruleid = $this->request->data['fee_sub_rule_id'];
            if ($this->article_fee_subrule->delete($subruleid)) {
//                $this->setSubruleFlag($hfRuleid);
                return 1;
            } else {
                return 'Subrule Record with id:' . $subruleid . '</b> Not deleted';
            }
        } catch (Exception $ex) {
            return '! There is some error for removing subrule';
        }
    }

//--------*-----*-----*------*-------*----------*-----*-------End of New Fee Rule-----------------------------------*---------*----------------*-----------*------------
//***********************************************************************************************************************************************************************//
//------------------***--------------------------------------------Get Article_fee_rule_list---------------------------------------------------------------------
    public function get_json_article_rule_list() {
        try {
            $this->autoRender = FALSE;
            $article_id = $this->request->data['article_id'];
            if (isset($article_id) && isset($this->request->data['rule_form_flag'])) {//get Rule List for Fees Rule config
                $list = ClassRegistry::init('article_fee_rule')->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array('article_id' => $article_id), 'order' => 'article_id ASC'));
            } else if (isset($article_id)) {//get Rule List for Calculation            
                $common_rule_flag = ClassRegistry::init('article')->find('first', array('fields' => array('use_common_rule_flag'), 'conditions' => array('article_id' => $article_id)));
                $articles = ($common_rule_flag['article']['use_common_rule_flag'] == 'Y') ? array($article_id, 9999) : $article_id;
                $conditions['article_id'] = $articles;
                if (isset($this->request->data['fee_type_id'])) {// for only online Payment Rule ... i.e for Citizin Entry
                    $conditions['fee_type_id'] = 1;
                }
                $list = ClassRegistry::init('article_fee_rule')->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $this->Session->read("sess_langauge")), 'conditions' => $conditions, 'order' => 'article_id ASC'));
            }
            return json_encode($list);
        } catch (Exception $ex) {
            return 'error in getting fee rule list';
        }
    }

//------------------***--------------------------------------------Get Fee Rule list based on Article Id----------------------------------------------------------
    public function get_article_rule_check_list() {
        try {
            $this->loadModel('article_fee_rule');
            $list = null;
            if (isset($this->request->data['article_id'])) {
                $article_id = $this->request->data['article_id'];
                $list = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array('article_id' => $article_id)));
            } else if (isset($this->request->data['feerule_id'])) {
                $feerule_id = $this->request->data['feerule_id'];
                $feeItemList = $this->article_fee_rule->find('first', array('fields' => array('fee_item_list'), 'conditions' => array('fee_rule_id' => $feerule_id)));
                if ($feeItemList)
                    $feeItemList = $feeItemList['article_fee_rule']['fee_item_list'];
                $list = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array("NOT" => array("fee_rule_id" => $feerule_id), 'fee_item_list' => $feeItemList)));
            } else {
                return "Article Id/ FeeRuleId Missing";
            }
            $this->set('feeRuleList', $list);
        } catch (Exception $e) {
            return 'error while fetcing rule checklist';
        }
    }

    public function remove_rule_fee_item() {
        try {
//            echo 'hi';exit;
            $this->autoRender = FALSE;
            $this->loadModel('conf_article_feerule_items');
            $item_link_id = $this->request->data['item_id'];
//            pr($item_link_id);exit;
            if (is_numeric($item_link_id) && ctype_digit($item_link_id)) {
                $this->conf_article_feerule_items->id = $item_link_id;

                if ($this->conf_article_feerule_items->delete($item_link_id)) {
                    return 1;
                } else {
                    return -1;
                }
            } else {
                return 'wrong Input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Removing Item');
        }
    }

//------------------***-------------------------------------------- Get  article linked items -------------------------------------------------------------------
    public function get_article_fee_rule_items() {
        try {
            $this->autoRender = FALSE;
            if (isset($this->request->data['feerule_id'])) {
                $this->loadModel('conf_article_feerule_items');
                $result = $this->conf_article_feerule_items->get_linked_items_json($this->request->data['feerule_id']);
                return json_encode($result);
            } else {
                return NULL;
            }
        } catch (Exception $e) {
            return 'error in getting feerule items';
        }
    }

//------------------***-------------------------------------------- Get  article linked items with Input Box -------------------------------------------------------------------
    public function get_article_fee_rule_item_input_old($fee_rule_id = NULL, $doc_token_no = NULL, $lang = 'en') {
        try {
            array_map([$this, 'loadModel'], ['article_fee_subrule', 'article_fee_item_list']);
            $fee_rule_id = isset($this->request->data['feerule_id']) ? $this->request->data['feerule_id'] : $fee_rule_id;
            $exmption = isset($this->request->data['exmption']) ? 'Y' : 'N';
            $this->set('exmption', $exmption);
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


                /* --------------------------------------------------------------- */
                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'is_hidden', 'item.display_order'), 'order' => array('item.display_order', 'item.fee_item_desc_' . $lang), 'conditions' => $condition,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
                        array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
                    )
                ));

                $items_list = array();
                foreach ($itemdata as $FeeItem) {
                    if ($FeeItem['item']['list_flag'] == 'Y') {
                        $items_list[$FeeItem['item']['fee_param_code']] = $this->article_fee_item_list->find('list', array('fields' => array('list_item_value', 'fee_item_list_desc_' . $lang), 'conditions' => array('fee_item_id' => $FeeItem['item']['fee_item_id']))); //fee_item_list_id
                    }
                }
                $form_name = (isset($this->request->data['form_name'])) ? $this->request->data['form_name'] : 'frm';
                $optional_fees = $this->article_fee_subrule->find('list', array('fields' => array('fee_subrule_id', 'item.fee_item_desc_' . $lang),
                    'conditions' => array('optional_flag' => 'Y', 'fee_rule_id' => $fee_rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))
                    )
                ));
                $this->set(compact('itemdata', 'items_list', 'form_name', 'lang', 'optional_fees'));
                $this->set('genderList', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang))));
            } else {
                return 'No Data Found';
            }
        } catch (Exception $e) {
            pr($e);
//            return 'error while getting fee rule inputs';
        }
    }

    public function get_article_fee_rule_item_input($fee_rule_id = NULL, $doc_token_no = NULL, $lang = 'en') {
        try {
            array_map([$this, 'loadModel'], ['party_entry', 'article_fee_subrule', 'article_fee_item_list', 'property_details_entry', 'valuation_details', 'fee_item_val']);
            $fee_rule_id = isset($this->request->data['feerule_id']) ? $this->request->data['feerule_id'] : $fee_rule_id;
            $exmption = isset($this->request->data['exmption']) ? 'Y' : 'N';

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
//                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.display_flag', 'is_hidden'), 'order' => array('item.display_flag', 'item.fee_item_desc_' . $lang), 'conditions' => $condition,
//                    'joins' => array(
//                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
//                        array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
//                    )
//                ));
                //print_r($itemdata);
                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.display_flag', 'item.display_order', 'is_hidden'), 'order' => array('item.display_flag', 'item.display_order'), 'conditions' => $condition,
                   'joins' => array(
                       array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
                       array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
                   )
               ));
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
                    
//                    //It is used to fetch no. of pages as per party count for Jharkhand FCX=No. of parties
//                    if ($item_code == 'FCX') {
//                        $total_party = $this->number_of_pages($doc_token_no);
//                    }
//                    //It is used to fetch no. of pages as per party count for Jharkhand
                    //Sonam changes FCY=Party shares it is used for count no. of parties for party1 and party2 of Partition Deed for goa
                    if ($item_code == 'FAS') {
                        $party1 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$doc_token_no");
                        //$party2 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where party_type_id=20  and token_no=$doc_token_no");
                        $party_count_new = $party1[0][0]['count'];
                        //$party2_count = $party2[0][0]['count'];
                        //$party_count_new = $party1_count + $party2_count;
                        
//                        if(empty($party_count_new)){
//                           // pr($party_count_new);exit;
//                            
//                        }
                    }
                    //Sonam Changes FCY=Party shares it is used for count no. of parties for party1 and party2 of Partition Deed for goa
//                    //Vishal changes for rule of Goa that needs no. of property FDF=No. of Property
//                    if ($item_code == 'FDF') {
//                        $property1 = $this->property_details_entry->query("select count(property_id) from ngdrstab_trn_property_details_entry where token_no=$doc_token_no");
//                        //$party2 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where party_type_id=20  and token_no=$doc_token_no");
//                        $property1_count_new = $property1[0][0]['count'];
//                        //$party2_count = $party2[0][0]['count'];
//                        //$party_count_new = $party1_count + $party2_count;
//                    }
//                    //Vishal changes for rule of Goa that needs no. of property

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
//                    if ($flag == 0) {
//                        if ($item_code == 'FCL') {
//                            $tokenno = $this->property_details_entry->find('first', array('fields' => array('val_id'), 'conditions' => array('token_no' => $doc_token_no), 'order' => 'property_id'));
//                            foreach ($tokenno as $tokenid) {
//                                $valuation_id = $tokenid['val_id'];
//                            }
//                            $record = $this->valuation_details->find('first', array('fields' => array('item_id', 'item_value'), 'conditions' => array('val_id' => $valuation_id), 'joins' => array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('valuation_details.item_id=item.usage_param_id', 'item.usage_param_code' => 'AAA')))));
//                            if (!empty($record)) {
//                                $area = $record['valuation_details']['item_value'];
//                            }
//                        }
//                    }

                    //FCK= Sale deed transfer type(M to F, etc.) fetch from party table
//                    if ($item_code == 'FCK') {
//                        $party_gender1 = array();
//                        $party_gender2 = array();
//
//                        $party_result1 = $this->fee_item_val->find('all', array('fields' => array('mapping_ref_val'), 'conditions' => array('token_no' => $doc_token_no, 'mapping_ref_typeid' => 56), 'order' => 'mapping_ref_id DESC'));
//                        $party_result2 = $this->fee_item_val->find('all', array('fields' => array('mapping_ref_val'), 'conditions' => array('token_no' => $doc_token_no, 'mapping_ref_typeid' => 57), 'order' => 'mapping_ref_id DESC'));
//
//                        if (!empty($party_result1)) {
//                            foreach ($party_result1 as $party1) {
//                                $gender1 = $party1['fee_item_val']['mapping_ref_val'];
//                                array_push($party_gender1, $gender1);
//                            }
//                        }
//
//                        if (!empty($party_result2)) {
//                            foreach ($party_result2 as $party2) {
//                                $gender2 = $party2['fee_item_val']['mapping_ref_val'];
//                                array_push($party_gender2, $gender2);
//                            }
//                        }
//
//                        if (!empty($party_gender1)) {
//                            foreach ($party_gender1 as $gender_new1) {
//                                if ($gender_new1 != $party_gender1[0]) {
//                                    $gen_val1 = 3;
//                                }
//                                if (@$gen_val1 != 3) {
//                                    if ($party_gender1[0] == 1) {
//                                        $gen_val1 = 1;
//                                    } else if ($party_gender1[0] == 2) {
//                                        $gen_val1 = 2;
//                                    }
//                                }
//                            }
//                        }
//
//                        if (!empty($party_gender2)) {
//                            foreach ($party_gender2 as $gender_new2) {
//                                if ($gender_new2 != $party_gender2[0]) {
//                                    $gen_val2 = 3;
//                                }
//                                if (@$gen_val2 != 3) {
//                                    if ($party_gender2[0] == 1) {
//                                        $gen_val2 = 1;
//                                    } else if ($party_gender2[0] == 2) {
//                                        $gen_val2 = 2;
//                                    }
//                                }
//                            }
//                        }
//
//                        if (isset($gen_val1) && isset($gen_val2)) {
//                            if ($gen_val1 == 1 && $gen_val2 == 2) {
//                                $gender_new = 1;
//                            } else if ($gen_val1 == 2 && $gen_val2 == 1) {
//                                $gender_new = 2;
//                            } else {
//                                $gender_new = 3;
//                            }
//                        } else {
//                            $gender_new = 3;
//                        }
//                    }
                }
                $form_name = (isset($this->request->data['form_name'])) ? $this->request->data['form_name'] : 'frm';
                $optional_fees = $this->article_fee_subrule->find('list', array('fields' => array('fee_subrule_id', 'item.fee_item_desc_' . $lang),
                    'conditions' => array('optional_flag' => 'Y', 'fee_rule_id' => $fee_rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))
                    )
                ));

                $this->set(compact('property1_count_new', 'party_count_new', 'total_party', 'area', 'gender_new', 'flag', 'gov_body_result', 'party_count', 'itemvalue', 'itemdata', 'items_list', 'form_name', 'lang', 'optional_fees'));
                $this->set('genderList', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang))));
            } else {
                return 'No Data Found';
            }
        } catch (Exception $e) {
            pr($e);
//            return 'error while getting fee rule inputs';
        }
    }
    
      public function get_article_fee_rule_item_input_sd($fee_rule_id = NULL, $doc_token_no = NULL, $lang = 'en') {
        try {
            array_map([$this, 'loadModel'], ['party_entry', 'article_fee_subrule', 'article_fee_item_list', 'property_details_entry', 'valuation_details', 'fee_item_val']);
            $fee_rule_id = isset($this->request->data['feerule_id']) ? $this->request->data['feerule_id'] : $fee_rule_id;
            $exmption = isset($this->request->data['exmption']) ? 'Y' : 'N';

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
//                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.display_flag', 'is_hidden'), 'order' => array('item.display_flag', 'item.fee_item_desc_' . $lang), 'conditions' => $condition,
//                    'joins' => array(
//                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
//                        array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
//                    )
//                ));
                //print_r($itemdata);
//                $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'value.articledepfield_value', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'item.display_flag', 'item.display_order', 'is_hidden'), 'order' => array('item.display_flag', 'item.display_order'), 'conditions' => $condition,
//                   'joins' => array(
//                       array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')),
//                       array('table' => 'ngdrstab_trn_articledepfields', 'alias' => 'value', 'type' => 'left', 'conditions' => array("value.articledepfield_id=conf_article_feerule_items.fee_param_code  and  value.article_id=conf_article_feerule_items.article_id " . $doc_token_no_cond))
//                   )
//               ));

               $itemdata = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT item.fee_item_id', 'item.fee_param_code', 'item.fee_item_desc_' . $lang, 'item.list_flag', 'is_hidden', 'item.display_order'),
                    'joins' => array(
                          array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id'))
                    ),
                    'conditions' =>array('conf_article_feerule_items.fee_rule_id' => $fee_rule_id),
                    'order' => array('item.display_order')
                ));
                $items_list = array();
                foreach ($itemdata as $FeeItem) {
                    if ($FeeItem['item']['list_flag'] == 'Y') {
                        $items_list[$FeeItem['item']['fee_param_code']] = $this->article_fee_item_list->find('list', array('fields' => array('list_item_value', 'fee_item_list_desc_' . $lang), 'conditions' => array('fee_item_id' => $FeeItem['item']['fee_item_id']))); //fee_item_list_id
                    }
                    //It is used to fetch no. of pages from generalinformation table
//                    $item_code = $FeeItem['item']['fee_param_code'];
//                    if ($item_code == 'FAJ') {
////                        $itemvalue = $this->article_fee_item_list->query("select no_of_pages from ngdrstab_trn_generalinformation where token_no=$doc_token_no");
//                        $value = 10;
//                    }
                    //It is used to fetch no. of pages from generalinformation table
                    //It is used to fetch no. of pages as per party count for Jharkhand FCX=No of Parties
//                    if ($item_code == 'FCX') {
//                        $total_party = $this->number_of_pages($doc_token_no);
//                    }
                    //It is used to fetch no. of pages as per party count for Jharkhand
                    //Sonam changes FCY=Party shares it is used for count no. of parties for party1 and party2 of Partition Deed for goa
//                    if ($item_code == 'FCY') {
//                        $party1 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$doc_token_no");
//                        //$party2 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where party_type_id=20  and token_no=$doc_token_no");
//                        $party_count_new = $party1[0][0]['count'];
//                        //$party2_count = $party2[0][0]['count'];
//                        //$party_count_new = $party1_count + $party2_count;
//                    }
                    //Sonam Changes FCY=Party shares it is used for count no. of parties for party1 and party2 of Partition Deed for goa
                    
                    //Vishal changes for release deed fee rule here FDF=No. of Properties
//                    if ($item_code == 'FDF') {
//                        $property1 = $this->property_details_entry->query("select count(property_id) from ngdrstab_trn_property_details_entry where token_no=$doc_token_no");
//                        //$party2 = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where party_type_id=20  and token_no=$doc_token_no");
//                        $property1_count_new = $property1[0][0]['count'];
//                        //$party2_count = $party2[0][0]['count'];
//                        //$party_count_new = $party1_count + $party2_count;
//                    }
                    //Vishal changes for release deed fee rule
                }
                $form_name = (isset($this->request->data['form_name'])) ? $this->request->data['form_name'] : 'frm';
                $optional_fees = $this->article_fee_subrule->find('list', array('fields' => array('fee_subrule_id', 'item.fee_item_desc_' . $lang),
                    'conditions' => array('optional_flag' => 'Y', 'fee_rule_id' => $fee_rule_id),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=article_fee_subrule.fee_output_item_id'))
                    )
                ));

                $this->set(compact('property1_count_new', 'party_count_new', 'total_party', 'area', 'gender_new', 'flag', 'gov_body_result', 'party_count', 'itemvalue', 'itemdata', 'items_list', 'form_name', 'lang', 'optional_fees'));
                $this->set('genderList', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang))));
            } else {
                return 'No Data Found';
            }
        } catch (Exception $e) {
            pr($e);
//            return 'error while getting fee rule inputs';
        }
    }

//------------------***------------------------------------------ get article description------------------------------------------------------------------------
    public function get_article_desc() {
        try {
            $this->autoRender = FALSE;
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $ussc = isset($this->request->data['article_id']) ? base64_decode($this->request->data['article_id']) : NULL;
            $ussc = ($ussc) ? (int) $ussc : NULL;
            if (is_integer($ussc)) {
                $result = ClassRegistry::init('article')->find('all', array('fields' => array('article_desc_en', 'article_desc_ll', 'usage_dependancy_flag', 'location_dependancy_flag'), 'conditions' => array('article_id' => $ussc)));
                return json_encode($result[0]['article']);
            } else {
                return 'Invalid Data';
            }
        } catch (Exception $e) {
            return 'error while getting article Description';
        }
    }

//------------------***----------------------------------------Get Max Orderid of Output Item in Subrule-----------------------------------------------------------
    public function get_fee_max_order_id() {
        try {
            $this->autoRender = FALSE;
            if (isset($this->request->data['ruleid'])) {
                $itpid = ClassRegistry::init('article_fee_subrule')->find('first', array('fields' => array('fee_output_item_order'), 'conditions' => array('fee_rule_id' => $this->request->data['ruleid']), 'order' => 'fee_output_item_order DESC'));
                return ($itpid) ? (json_encode( ++$itpid['article_fee_subrule']['fee_output_item_order'])) : (json_encode(1));
            } else {
                return "Rule Not Found";
            }
        } catch (Exception $e) {
            return "! There is some error getting max order id for fee subrule";
        }
    }

//------------------***------------------------------------------Fee Calulation for stamp duty,Registration,Handeling Charges and Others-----------------------------------------------      
    public function calculate_mv($frm = NULL) {
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

//------------------**----------------------------------------------------------------------------------------------------------------------------------
    public function fee_calculation() {
        try {
            array_map([$this, 'loadModel'], ['fees_calculation', 'article', 'article_fee_rule', 'conf_article_feerule_items', 'finyear', 'District', 'Developedlandtype']);
            $actiontypeval = $hfid = $hfupdateflag = NULL;
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $dist_list = $this->District->find('list', array('fields' => array('id', 'district_name_' . $lang), 'order' => 'district_name_' . $lang));
            $landtype = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => 'developed_land_types_desc_' . $lang));
            $finyearList = $this->finyear->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('current_year DESC, finyear_id')));
            $tmp_article = $this->article_fee_rule->find('all', array('fields' => array('DISTINCT article_id')));
            $article_ids = array();
            foreach ($tmp_article as $tmp) {
                array_push($article_ids, $tmp['article_fee_rule']['article_id']);
            }
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $doc_lang = $this->Session->read('doc_lang');
            //--------------Validation TMP Set to NUll ------------for testing purpose-------------------

            $fieldlist = $this->conf_article_feerule_items->validateFields_sdcacl($lang);
            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            //---------------------------------------------------------------------------------------------
            $articlelist = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'order' => 'article_desc_' . $lang, 'conditions' => array('article_id' => $article_ids, 'display_flag' => 'Y')));
            $field = array_keys($this->fees_calculation->getColumnTypes());
            $result = '';
            if ($this->request->is('post')) {
                $feerule_id = NULL;
                if (isset($this->request->data['frm']['fee_rule_id'])) {
                    $feerule_id = $this->request->data['frm']['fee_rule_id'];
                }
                if (isset($this->request->data['frm']['article_id'])) {
                    $article_id = $this->request->data['frm']['article_id'];
                }

                $this->request->data['frm']['FAR'] = (isset($this->request->data['frm']['developed_land_types_id'])) ? $this->request->data['frm']['developed_land_types_id'] : NULL;

                $fieldlist = $this->conf_article_feerule_items->validateFields_sdcacl($lang, $feerule_id, $article_id);

                //$fieldlist = $this->modifyfieldlist($fieldlist, $this->request->data['frm']);

                $errors = $this->validatedata($this->request->data['frm'], $fieldlist);

                if ($this->ValidationError($errors)) {
                    $result = $this->calculate_fees($this->request->data['frm']);
                    $this->autoRender = TRUE;
                } else {
                    
                }
            }
            $this->set(compact('field', 'actiontype', 'finyearList', 'articlelist', 'dist_list', 'landtype', 'result'));
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash('Sorry! There is some error in setting data');
        }
    }

//------------------***---------------------------------------------------------------------------------------------------
    public function get_article_gov_body_flag() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            array_map([$this, 'loadModel'], ['article']);
            $GBodyflag = $this->article->find('first', array('fields' => array('gov_body_applicable'), 'conditions' => array('article_id' => $this->request->data['article_id'], 'display_flag' => 'Y')));
            return ($GBodyflag) ? $GBodyflag['article']['gov_body_applicable'] : 'N';
        } catch (Exception $ex) {
            return 'there is some error while getting gov body flag';
        }
    }

//------------------***---------------------------------------Fee Caculation-------------------------------------------------------------------------------------------
    public function calculate_fees_old($itemValues = NULL, $returnCalc = 'N') {
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
            array_map([$this, 'loadModel'], ['fees_calculation', 'fees_calculation_detail', 'valuation', 'article_fee_rule', 'article_fee_subrule', 'article', 'article_fee_items', 'conf_article_feerule_items', 'property_details_entry', 'VillageMapping']);

            $frm = ($itemValues) ? $itemValues : (isset($this->request->data['frm']) ? $this->request->data['frm'] : $this->request->data);
            //  $this->check_csrf_token_withoutset($frm['csrftoken']);
            $frm['token_no'] = isset($frm['doc_token_no']) ? $frm['doc_token_no'] : (isset($frm['token_no']) ? $frm['token_no'] : NULL);
            $tmp = (isset($frm['village_id'])) ? ($this->VillageMapping->get_ulb_land_type($frm['village_id'])) : NULL;
            // code changes for exemption 
            $vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);
            $pconsamunt = $this->property_details_entry->field('consideration_amount', array('property_id' => @$this->request->data['property_list']));

            if (!empty($pconsamunt)) {
                $frm['cons_amt'] = $pconsamunt;
            }

            $consamount = (isset($frm['property_id'])) ? ($this->fees_calculation->field('cons_amt', array('property_id' => $frm['property_id']))) : (isset($frm['cons_amt']) ? $frm['cons_amt'] : NULL);

            if (is_numeric($vamout) && is_numeric($consamount)) {
                if ($vamout > $consamount) {
                    $frm['FAA'] = $vamout;
                } else {
                    $frm['FAA'] = $consamount;
                }
            } else {
                $frm['FAA'] = $vamout;
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

            $feeRule = $this->get_calc_fee_rule_list($frm);
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


                        if ($subruleData) {
                            foreach ($subruleData as $fsrl) {
                                $fsrl = $fsrl['article_fee_subrule'];
                                $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $fsrl);
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
//                            $ruleData = $this->article_fee_rule->find('all', array('conditions' => array('fee_rule_id' => $rule_id)));
//                            $ruleData = $ruleData[0]['article_fee_rule'];
//                            $ruleData = (isset($ruleData)) ? $ruleData : NULL;
//                            $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $ruleData);
//                            $tmpfeeCalcDetail['fee_subrule_id'] = NULL;
//                            $tmpfeeCalcDetail['fee_item_id'] = $ruleData['fee_output_item_id'];
//                            array_push($feeCalcDetail, $tmpfeeCalcDetail);
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
                        if ($this->save_fee_calculation_detail($fees_cal_id, $frm, $feeCalcDetail, $articleItems)) {
                            if ($frm['article_id'] == 9998 && isset($frm['token_no']) && $frm['token_no']) {
                                $this->update_fee_exemption($frm['token_no']);
                            } else if ($returnCalc == 'Y') {
                                $fees = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) AS fees'), 'conditions' => array('fee_calc_id' => $fees_cal_id, 'item_type_id' => 2)));
                                return $fees[0]['fees'];
                            } else {
                                return $this->view_fee_calculation($fees_cal_id);
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
            return 'error while calculating fees';
        }
    }

    //vishal changs FCR district I FEE
    public function calculate_fees_bihar($itemValues = NULL, $returnCalc = 'N') {
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

            $citizen_token_no = $this->Session->read('Selectedtoken');
            if (!is_null($citizen_token_no)) {
//                $officedistrict = $this->article->query("select office.office_id from ngdrstab_trn_generalinformation info 
//
//                                                            join ngdrstab_mst_office office on office.office_id=info.office_id 
//                                                            where info.token_no=? and office.district_office_flag='Y'", array($citizen_token_no));
                //new jurisdiction_flag
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
//            pr($consamount);
            $article = $frm['article_id'];
            // min amount fee calculation for config 94
            if (is_numeric($vamout) && is_numeric($consamount)) {
                //min considaration amount calculate config table regconfig
                $article_result = $this->article->find("all", array('conditions' => array('article_id' => $article)));

                if (!empty($article_result)) {
                    if ($article_result[0]['article']['min_cons_amt'] == 'Y') {
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

            if (isset($this->request->data['property_list'])) {
                $frm['property_id'] = $this->request->data['property_list'];
            }
            // Dynamic Veriable
            if (isset($frm['token_no'])) {
                $obj = new DynamicVariablesController();
                $frm = $obj->veriables_sd($frm['token_no'], $frm, @$frm['property_id']);
            }
            $feeRule = $this->get_calc_fee_rule_list($frm);
//            pr($feeRule);exit;
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


                        if ($subruleData) {
                            foreach ($subruleData as $fsrl) {
                                $fsrl = $fsrl['article_fee_subrule'];
                                $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $fsrl);
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
//                            $ruleData = $this->article_fee_rule->find('all', array('conditions' => array('fee_rule_id' => $rule_id)));
//                            $ruleData = $ruleData[0]['article_fee_rule'];
//                            $ruleData = (isset($ruleData)) ? $ruleData : NULL;
//                            $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $ruleData);
//                            $tmpfeeCalcDetail['fee_subrule_id'] = NULL;
//                            $tmpfeeCalcDetail['fee_item_id'] = $ruleData['fee_output_item_id'];
//                            array_push($feeCalcDetail, $tmpfeeCalcDetail);
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

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $citizen_token_no), 'order' => 'property_id'));
                            if (!empty($prop_id)) {
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
                    //goa and punjab
                    if (isset($frm['FDE'])) {
                        $oldagrement_reg_no = $frm['FDE'];
                        $frm['oldagreement_reg_no'] = $oldagrement_reg_no;
                        $oldagrement_date = $frm['FDF'];
                        $frm['oldagreement_date'] = $oldagrement_date;
                    }


                    if ($this->fees_calculation->save($frm)) {
                        $fees_cal_id = $this->fees_calculation->getLastInsertID();
                        if (isset($frm['property_id']) && $frm['property_id']) {
                            $this->property_details_entry->updateAll(array('fee_calc_id' => $fees_cal_id), array('property_id' => $frm['property_id'])); // update fees_calc_id   in property entry table                
                        }
                        if ($this->save_fee_calculation_detail($fees_cal_id, $frm, $feeCalcDetail, $articleItems)) {
                            if ($frm['article_id'] == 9998 && isset($frm['token_no']) && $frm['token_no']) {
                                $this->update_fee_exemption($frm['token_no']);
                            } else if ($returnCalc == 'Y') {
                                $fees = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) AS fees'), 'conditions' => array('fee_calc_id' => $fees_cal_id, 'item_type_id' => 2)));
                                return $fees[0]['fees'];
                            } else {

                                return $this->view_fee_calculation($fees_cal_id);
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

    //vishal changs FCR district I FEE
    public function calculate_fees($itemValues = NULL, $returnCalc = 'N') {
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

            $citizen_token_no = $this->Session->read('Selectedtoken');
            if (!is_null($citizen_token_no)) {
//                $officedistrict = $this->article->query("select office.office_id from ngdrstab_trn_generalinformation info 
//
//                                                            join ngdrstab_mst_office office on office.office_id=info.office_id 
//                                                            where info.token_no=? and office.district_office_flag='Y'", array($citizen_token_no));
                //new jurisdiction_flag
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
//            pr($consamount);
            $article = $frm['article_id'];
            // min amount fee calculation for config 94
            //Sonam changes of add new textbox and calculation with max value from that 3 textboxes
            $sramount_flag = (isset($frm['FCZ']) ? $frm['FCZ'] : NULL); //FCZ=Do you agree with calculation from SRO amount.
            $sramount = (isset($frm['FDH']) ? $frm['FDH'] : NULL);      //FDH=SRO Amount
            //Sonam changes of add new textbox and calculation with max value from that 3 textboxes
            //sramount=SRO Amount it is for goa when FCZ=Yes then calculation done max value from vamount, consideration amount, sramount.
            if (is_numeric($vamout) && is_numeric($consamount)) {
                //min considaration amount calculate config table regconfig
                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 94)));

                if (!empty($regconf)) {
                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {


                        if ($vamout > $consamount) {
                            $frm['FAA'] = $consamount;
                        } else {
                            $frm['FAA'] = $vamout;
                        }
                    } else {

//Sonam changes of add new textbox and calculation with max value from that 3 textboxes
                        if ($vamout > $consamount) {
                            if (isset($sramount)) {
                                if ($sramount_flag == 2) {
                                    if ($vamout > $sramount) {
                                        $frm['FAA'] = $vamout;
                                    } else {
                                        $frm['FAA'] = $sramount;
                                    }
                                } else {
                                    $frm['FAA'] = $vamout;
                                }
                            } else {
                                $frm['FAA'] = $vamout;
                            }
                        } else {
                            if (!empty($sramount)) {
                                if ($sramount_flag == 2) {
                                    if ($consamount > $sramount) {
                                        $frm['FAA'] = $consamount;
                                    } else {
                                        $frm['FAA'] = $sramount;
                                    }
                                } else {
                                    $frm['FAA'] = $consamount;
                                }
                            } else {
                                $frm['FAA'] = $consamount;
                            }
                        }
                    }
                }
                //Sonam changes of add new textbox and calculation with max value from that 3 textboxes
            } else {
                $frm['FAA'] = $vamout;
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

            if (isset($this->request->data['property_list'])) {
                $frm['property_id'] = $this->request->data['property_list'];
            }
            // Dynamic Veriable
            if (isset($frm['token_no'])) {
                $obj = new DynamicVariablesController();
                $frm = $obj->veriables_sd($frm['token_no'], $frm, @$frm['property_id']);
            }
            $feeRule = $this->get_calc_fee_rule_list($frm);
//            pr($feeRule);exit;
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


                        if ($subruleData) {
                            foreach ($subruleData as $fsrl) {
                                $fsrl = $fsrl['article_fee_subrule'];
                                $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $fsrl);
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
//                            $ruleData = $this->article_fee_rule->find('all', array('conditions' => array('fee_rule_id' => $rule_id)));
//                            $ruleData = $ruleData[0]['article_fee_rule'];
//                            $ruleData = (isset($ruleData)) ? $ruleData : NULL;
//                            $tmpfeeCalcDetail['calc_detail'] = $this->check_fee_condition($articleItems, $frm, $ruleData);
//                            $tmpfeeCalcDetail['fee_subrule_id'] = NULL;
//                            $tmpfeeCalcDetail['fee_item_id'] = $ruleData['fee_output_item_id'];
//                            array_push($feeCalcDetail, $tmpfeeCalcDetail);
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

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $citizen_token_no), 'order' => 'property_id'));
                            if (!empty($prop_id)) {
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
                    //goa and punjab
                    if (isset($frm['FDE'])) {
                        $oldagrement_reg_no = $frm['FDE'];
                        $frm['oldagreement_reg_no'] = $oldagrement_reg_no;
                        $oldagrement_date = $frm['FDF'];
                        $frm['oldagreement_date'] = $oldagrement_date;
                    }


                    if ($this->fees_calculation->save($frm)) {
                        $fees_cal_id = $this->fees_calculation->getLastInsertID();
                        if (isset($frm['property_id']) && $frm['property_id']) {
                            $this->property_details_entry->updateAll(array('fee_calc_id' => $fees_cal_id), array('property_id' => $frm['property_id'])); // update fees_calc_id   in property entry table                
                        }
                        if ($this->save_fee_calculation_detail($fees_cal_id, $frm, $feeCalcDetail, $articleItems)) {
                            if ($frm['article_id'] == 9998 && isset($frm['token_no']) && $frm['token_no']) {
                                $this->update_fee_exemption($frm['token_no']);
                            } else if ($returnCalc == 'Y') {
                                $fees = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) AS fees'), 'conditions' => array('fee_calc_id' => $fees_cal_id, 'item_type_id' => 2)));
                                return $fees[0]['fees'];
                            } else {

                                return $this->view_fee_calculation($fees_cal_id);
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
    public function get_calc_fee_rule_list($frm) {
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
    public function save_fee_calculation_detail($fees_cal_id, $frm, $feeCalcDetail, $articleItems) {
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
                return $this->view_fee_calculation($frm['fee_calc_id']);
            } else {
                return "Fees Calculation Failed!";
            }
        } catch (Exception $ex) {
            return 'There is some error in saving Calculation Detail';
        }
    }

//-------------------------------------------------------------------Check Fee Rule Conditions---------------------------------------------------------------------------------
    public function check_fee_condition_old($articleItems, $frm, $fsrl) {
        try {
// pr($frm);exit;
            $this->loadModel('property_details_entry');
            $this->loadModel('valuation');
            if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                $total_cons_amt = (isset($frm['property_id']) && is_numeric($frm['property_id'])) ? $this->fees_calculation->get_cons_amt($frm['token_no'], $frm['property_id']) : $this->fees_calculation->get_cons_amt($frm['token_no']);
                $this->loadModel('property_details_entry');
                $this->loadModel('valuation');
                $this->loadModel('valuation_details');
                $this->loadModel('conf_reg_bool_info');
//                if ($total_cons_amt) {
//                    $frm['FAA'] = $total_cons_amt;
//                } else {
//                    $frm['FAA'] = $frm['FAA'];
//                }
                //it is used for get exemption from max value from valuation amount and consideration amount.
//                $property_id = $frm['property_id'];
//                $val_id_data = $this->property_details_entry->query("select val_id from ngdrstab_trn_property_details_entry where property_id=$property_id");
//                $frm['val_id'] = $val_id_data[0][0]['val_id'];
//                $vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);
//
//                if ($total_cons_amt > $vamout) {
//                    $frm['FAA'] = $total_cons_amt;
//                } else {
//                    $frm['FAA'] = $vamout;
//                }
                //this is for same usage category the exemption should be on addition of valuation amount.
                $token = $frm['doc_token_no'];
                $flag = $this->get_prop_same_usage_flag($token);

                if ($flag == 1) {
                    //it is used for get exemption from max value from valuation amount and consideration amount.
                    $property_id = $frm['property_id'];
                    $val_id_data = $this->property_details_entry->query("select val_id from ngdrstab_trn_property_details_entry where property_id=$property_id");
                    $frm['val_id'] = $val_id_data[0][0]['val_id'];
                    $vamout = (isset($frm['val_id'])) ? ($this->valuation->field('rounded_val_amt', array('val_id' => $frm['val_id']))) : (isset($frm['FAA']) ? $frm['FAA'] : NULL);

                    $sramount_flag = (isset($frm['FCZ']) ? $frm['FCZ'] : NULL);
                    $sramount = (isset($frm['FDH']) ? $frm['FDH'] : NULL);


//                    if ($total_cons_amt > $vamout) {
//                        $frm['FAA'] = $total_cons_amt;
//                    } else {
//                        $frm['FAA'] = $vamout;
//                    }

                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 94)));

                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {

                            if ($vamout > $total_cons_amt) {
                                $frm['FAA'] = $total_cons_amt;
                            } else {
                                $frm['FAA'] = $vamout;
                            }
                        } else {

                            //Sonam changes of add new textbox and calculation with max value from that 3 textboxes
                            if ($vamout > $total_cons_amt) {
                                if (isset($sramount)) {
                                    if ($sramount_flag == 2) {
                                        if ($vamout > $sramount) {
                                            $frm['FAA'] = $vamout;
                                        } else {
                                            $frm['FAA'] = $sramount;
                                        }
                                    } else {
                                        $frm['FAA'] = $vamout;
                                    }
                                } else {
                                    $frm['FAA'] = $vamout;
                                }
                            } else {
                                if (!empty($sramount)) {
                                    if ($sramount_flag == 2) {
                                        if ($total_cons_amt > $sramount) {
                                            $frm['FAA'] = $total_cons_amt;
                                        } else {
                                            $frm['FAA'] = $sramount;
                                        }
                                    } else {
                                        $frm['FAA'] = $total_cons_amt;
                                    }
                                } else {
                                    $frm['FAA'] = $total_cons_amt;
                                }
                            }
                        }
                    }
                } else {
//                    this is for same usage category the exemption should be on addition of valuation amount.
                    $frm['FAA'] = 0;
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
                    $vamout = $frm['FAA'];

                    $sramount_flag = (isset($frm['FCZ']) ? $frm['FCZ'] : NULL);
                    $sramount = (isset($frm['FDH']) ? $frm['FDH'] : NULL);


//                    if ($total_cons_amt > $vamout) {
//                        $frm['FAA'] = $total_cons_amt;
//                    } else {
//                        $frm['FAA'] = $vamout;
//                    }

                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 94)));

                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {


                            if ($vamout > $total_cons_amt) {
                                $frm['FAA'] = $total_cons_amt;
                            } else {
                                $frm['FAA'] = $vamout;
                            }
                        } else {

                            //Sonam changes of add new textbox and calculation with max value from that 3 textboxes
                            if ($vamout > $total_cons_amt) {
                                if (isset($sramount)) {
                                    if ($sramount_flag == 2) {
                                        if ($vamout > $sramount) {
                                            $frm['FAA'] = $vamout;
                                        } else {
                                            $frm['FAA'] = $sramount;
                                        }
                                    } else {
                                        $frm['FAA'] = $vamout;
                                    }
                                } else {
                                    $frm['FAA'] = $vamout;
                                }
                            } else {
                                if (!empty($sramount)) {
                                    if ($sramount_flag == 2) {
                                        if ($total_cons_amt > $sramount) {
                                            $frm['FAA'] = $total_cons_amt;
                                        } else {
                                            $frm['FAA'] = $sramount;
                                        }
                                    } else {
                                        $frm['FAA'] = $total_cons_amt;
                                    }
                                } else {
                                    $frm['FAA'] = $total_cons_amt;
                                }
                            }
                        }
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
                    if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond1']) . ");")) {
                        $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula1']);
                    } else if ($fsrl['fee_rule_cond2']) {
                        if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond2']) . ");")) {
                            $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula2']);
                        } else if ($fsrl['fee_rule_cond3']) {
                            if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond3']) . ");")) {
                                $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula3']);
                            } else if ($fsrl['fee_rule_cond4']) {
                                if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond4']) . ");")) {
                                    $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula4']);
                                } else if ($fsrl['fee_rule_cond5']) {
                                    $calculations = (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond5']) . ");")) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                } else {//if condition 5 not available
                                    $calculations = ($fsrl['fee_rule_formula5']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                }
                            } else {//if condition 4  not available
                                $calculations = ($fsrl['fee_rule_formula4']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula4']) : -1;
                            }
                        } else {// if condition 3  not available
                            $calculations = ($fsrl['fee_rule_formula3']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula3']) : -1;
                        }
                    } else {//if condition 2 not available
                        $calculations = ($fsrl['fee_rule_formula2']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula2']) : -1;
                    }
                } else {//condition 1  not Available              
                    $calculations = ($fsrl['fee_rule_formula1']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula1']) : -1;
                }
                //   pr($calculations);exit;
                $result = eval("return(" . $calculations . ");");

                //Bihar Max value null only min value get in rule check condition if($fsrl['max_value']=='')
                if ($fsrl['min_value'] || $fsrl['max_value']) {
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            if ($fsrl['max_value'] == '') {
                                $result = ceil(($fsrl['min_value']) ? ((($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                            } else {
                                $result = ceil(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                            }
                        } else {
                            if ($fsrl['max_value'] == '') {
                                $result = round(($fsrl['min_value']) ? ((($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                            } else {
                                $result = round(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
                            }
                        }
                    }
                } else {
                    //check max and min value for output field
                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
                    if (!empty($regconf)) {
                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                            if ($fsrl['max_value'] == '') {
                                $result = ceil(($output_item['article_fee_items']['min_value']) ? ((($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                            } else {
                                $result = ceil(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                            }
                        } else {
                            if ($fsrl['max_value'] == '') {
                                $result = round(($output_item['article_fee_items']['min_value']) ? ((($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                            } else {
                                $result = round(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
                            }
                        }
                    }
                }


//                if ($fsrl['min_value'] || $fsrl['max_value']) {
//                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
//                    if (!empty($regconf)) {
//                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
//                            $result = ceil(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
//                        } else {
//                            $result = round(($fsrl['min_value'] || $fsrl['max_value']) ? ( ($result > $fsrl['max_value']) ? $fsrl['max_value'] : (($result < $fsrl['min_value'] ) ? $fsrl['min_value'] : $result)) : $result);
//                        }
//                    }
//                } else {
//                    //check max and min value for output field
//                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 92)));
//                    if (!empty($regconf)) {
//                        if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
//                            $result = ceil(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
//                        } else {
//                            $result = round(($output_item['article_fee_items']['max_value'] || $output_item['article_fee_items']['min_value']) ? ( ($result > $output_item['article_fee_items']['max_value']) ? $output_item['article_fee_items']['max_value'] : (($result < $output_item['article_fee_items']['min_value'] ) ? $output_item['article_fee_items']['min_value'] : $result)) : $result);
//                        }
//                    }
//                }
                //perform rounding
                if ($result == -1) {
                    $result = 0;
                }
                if ($result != 0) {
                    if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                         $result = $result;
//                        $value = $output_item['rounding']['next_rounding_value'];
//                        $result = $this->round_tonext($result, $value);
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
    
    public function check_fee_condition($articleItems, $frm, $fsrl) {
        try {
            //pr($frm);

            if ($frm['article_id'] == 9998 && $frm['token_no'] && isset($frm['exm']) && $frm['exm'] == 'Y') {
                $total_cons_amt = (isset($frm['property_id']) && is_numeric($frm['property_id'])) ? $this->fees_calculation->get_cons_amt($frm['token_no'], $frm['property_id']) : $this->fees_calculation->get_cons_amt($frm['token_no']);
                $this->loadModel('property_details_entry');
                $this->loadModel('valuation');
                $this->loadModel('valuation_details');

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
                    if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond1']) . ");")) {
                        $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula1']);
                    } else if ($fsrl['fee_rule_cond2']) {
                        if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond2']) . ");")) {
                            $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula2']);
                        } else if ($fsrl['fee_rule_cond3']) {
                            if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond3']) . ");")) {
                                $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula3']);
                            } else if ($fsrl['fee_rule_cond4']) {
                                if (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond4']) . ");")) {
                                    $calculations = $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula4']);
                                } else if ($fsrl['fee_rule_cond5']) {
                                    $calculations = (eval("return(" . $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_cond5']) . ");")) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                } else {//if condition 5 not available
                                    $calculations = ($fsrl['fee_rule_formula5']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula5']) : -1;
                                }
                            } else {//if condition 4  not available
                                $calculations = ($fsrl['fee_rule_formula4']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula4']) : -1;
                            }
                        } else {// if condition 3  not available
                            $calculations = ($fsrl['fee_rule_formula3']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula3']) : -1;
                        }
                    } else {//if condition 2 not available
                        $calculations = ($fsrl['fee_rule_formula2']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula2']) : -1;
                    }
                } else {//condition 1  not Available              
                    $calculations = ($fsrl['fee_rule_formula1']) ? $this->add_value_to_formula($articleItems, $frm, $fsrl['fee_rule_formula1']) : -1;
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
    public function add_value_to_formula($itemlist, $value, $formula) {// for replacing item code with value in formula
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
                $value['OMV'] = $this->calculate_mv($value);
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
    public function get_certificate_fees() {
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
                return $this->calculate_fees($frm, 'Y');
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
    public function view_fee_calculation($fee_calc_id = NULL, $view_flag = NULL) {
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
                        $vwReport.="<h3 align=center width='80%'> Input Details </h3>"
                                . "<table width='100%' border=1 align=center>"
                                . "<tr style='background-color:#ffffb3;'> <td align=center > <b>Sr.No.</b> </td> <td><b>Particulars</b></td> <td><b>Value</b>  </td></tr>";
//--------------------for Input Details---------------------------------------
                        foreach ($calcDetailData as $calcDetail) {
                            $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                            if ($itemtype != 2 && $itemtype != 6 || $itemtype == 99) { // If Item are Input Only
                                if ($calcDetail['items']['fee_param_type_id'] == 5) {// for Exemption Input
                                    $inputValue = ($calcDetail['items']['fee_item_desc_en'] == 'Gender') ? (str_replace(array(1, 2, 3), array('Male', 'Female', 'Other'), $calcDetail['fees_calculation_detail']['fee_item_value'])) : ($this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')));
                                    $vwReport.="<tr style='background-color:#E8E8E8;'><td align=center>" . $srInput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_en'] . "</b></td> <td>" . $inputValue . "</td> </tr>";
                                } else if ($calcDetail['fees_calculation_detail']['item_type_id'] == 99) { // for consideration amount--- if available
                                    $consideration_amount = "<tr style='background-color:#fdfCCC;'> <td></td> <td  ><b>Consideration Amount </b></td> <td><b>" . $this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')) . "</b></td> </tr>";
                                } else { //for Normal Input Items
                                    $vwReport.="<tr><td align=center>" . $srInput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td>" . $this->valuation->format_money_india(number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '')) . "</td> </tr>";
                                    $InputTotal*=number_format((float) $calcDetail['fees_calculation_detail']['fee_item_value'], 2, '.', '');
                                }

                                if ($calcDetail['items']['fee_item_id'] == 5) {// for Market Value Given
                                    $imv_flag = 'Y';
                                }
                            }
                        }
                        if ($imv_flag == 'N') {
                            $vwReport.="<tr style='background-color:#fdfddd;'> <td></td> <td  ><b>Output Market Value </b></td> <td><b>" . $this->valuation->format_money_india(number_format((float) $InputTotal, 2, '.', '')) . "</b></td> </tr>";
                        }

                        if ($consideration_amount) {
                            $vwReport.="<tr style='background-color:#AAAddd;'><td colspan=4></td></tr>";
                            $vwReport.=$consideration_amount;
                            $vwReport.="<tr style='background-color:#AAAddd;'><td colspan=4></td></tr>";
                        }
                    }
                    $vwReport.="</table>"
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
                            $out_vwReport.="<table width=100% border=1 align=center>"
                                    . "<tr style='background-color:#ffffb3;'> <td align=center><b>Sr.No.</b></td> <td><b>Particulars<b></td> <td width=30%><b>Payment Mode<b></td> <td align=right><b>Total</b></td><td align=right><b>Final Amount</b></td></tr>";

                            $total = 0;
                            foreach ($calcDetailData as $calcDetail) {
                                $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                                if ($itemtype == 2 or $itemtype == 6) {//for output Items
                                    $min_value = ($calcDetail['fees_calculation_detail']['min_value']) ? ($calcDetail['fees_calculation_detail']['min_value']) : (($calcDetail['items']['min_value']) ? $calcDetail['items']['min_value'] : '-NA-');
                                    $max_value = ($calcDetail['fees_calculation_detail']['max_value']) ? ($calcDetail['fees_calculation_detail']['max_value']) : (($calcDetail['items']['max_value']) ? $calcDetail['items']['max_value'] : '-NA-');
                                    if ($calcDetail['items']['fee_param_type_id'] == 6) { // for Exemption  Output
                                        $total-=$calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport.="<tr style='background-color:#E8E8E8;'><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    } else { //for Normal Items
                                        if ($calcDetail['fees_calculation_detail']['fee_calc_desc'] != 0) {
                                            $total+=$calcDetail['fees_calculation_detail']['final_value'];
                                            $out_vwReport.="<tr><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td align=right>" . eval("return(" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . ");") . "</td> <td align=right>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                        }
                                    }
                                }
                            }
                            $out_vwReport.="<tr style='background-color: #b3b300;color:white;'><td align=center colspan=4><b>Total</b></td> <td align=right> <b>" . $this->valuation->format_money_india(number_format((float) abs($total), 2, '.', '')) . "/-</b></td> </tr>";
                            $out_vwReport.="</table>";
                        } else {
                            $out_vwReport.="<table width=100% border=1 align=center>"
                                    . "<tr style='background-color:#ffffb3;'> <td align=center><b>Sr.No.</b></td> <td><b>Particulars<b></td> <td width=30%><b>Payment Mode<b></td> <td align=right><b>Min.</b></td><td align=right><b>Max.</b></td> <td><b>Calculation</b></td> <td align=right><b>Total</b></td><td align=right><b>Final Amount</b></td></tr>";

                            $total = 0;

                            foreach ($calcDetailData as $calcDetail) {
                                $itemtype = $calcDetail['fees_calculation_detail']['item_type_id'];
                                if ($itemtype == 2 or $itemtype == 6) {//for output Items
                                    $min_value = ($calcDetail['fees_calculation_detail']['min_value']) ? ($calcDetail['fees_calculation_detail']['min_value']) : (($calcDetail['items']['min_value']) ? $calcDetail['items']['min_value'] : '-NA-');
                                    $max_value = ($calcDetail['fees_calculation_detail']['max_value']) ? ($calcDetail['fees_calculation_detail']['max_value']) : (($calcDetail['items']['max_value']) ? $calcDetail['items']['max_value'] : '-NA-');
                                    if ($calcDetail['items']['fee_param_type_id'] == 6) { // for Exemption  Output
                                        $total-=$calcDetail['fees_calculation_detail']['final_value'];
                                        $out_vwReport.="<tr style='background-color:#E8E8E8;'><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                    } else { //for Normal Items
                                        if ($calcDetail['fees_calculation_detail']['fee_calc_desc'] != 0) {
                                            $total+=$calcDetail['fees_calculation_detail']['final_value'];
                                            $out_vwReport.="<tr><td align=center>" . $srOutput++ . "</td><td><b>" . $calcDetail['items']['fee_item_desc_' . $lang] . "</b></td> <td><b>" . $calcDetail['paymentdesc']['payment_mode_desc'] . "</b></td>  <td align=right>" . $min_value . " </td> <td align=right>" . $max_value . " </td> <td>" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . "</td> <td align=right>" . eval("return(" . $calcDetail['fees_calculation_detail']['fee_calc_desc'] . ");") . "</td> <td align=right>" . $this->valuation->format_money_india(number_format((float) abs($calcDetail['fees_calculation_detail']['final_value']), 2, '.', '')) . " </td> </tr>";
                                        }
                                    }
                                }
                            }


                            $out_vwReport.="<tr style='background-color: #b3b300;color:white;'><td align=center colspan=7><b>Total</b></td> <td align=right> <b>" . $this->valuation->format_money_india(number_format((float) abs($total), 2, '.', '')) . "/-</b></td> </tr>";
                            $out_vwReport.="</table>";
                        }
                    }
                }
                $style = "<style>table {border-collapse: collapse;} td{padding:3px}</style>";

                if ($view_flag == 'D') {

                    $vwReport.= $out_vwReport;
                    $vwReport = $style . $vwReport;
                    $Reports = new ReportsController;
                    $Reports->create_pdf($vwReport, "Fees_" . $fee_calc_id);
                } else if ($view_flag == 'V') {
                    $vwReport.= $out_vwReport;
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
    public function view_sd_calc_OLD($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
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
                            $html.="<style>td{padding:2px 10px 2px 10px;}</style>"
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
                                        $html.=($calc['fee_rule']['fee_type_id'] == 1) ? "<tr style='background-color: #8C489F; color: white;'>  <td colspan=3>" . $rptlabels[75] . ":" . $calc['fees_calculation']['property_id'] . " </td> </tr>" : '';
                                        $prop_id = $calc['fees_calculation']['property_id'];
                                        $SrRule = 1;
                                    }
                                    if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {

//                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "<button title='Remove' class='danger glyphicon glyphicon-trash' onClick='return removeFees('" . $calc['fees_calculation']['fee_calc_id'] . "')></button>" . "</td></tr>";                                        
                                        $html.="<tr>   <td colspan=3 style='color:red;font-weight:bold;'>" . $rptlabels[74] . ":" . $calc['fee_rule']['fee_rule_desc_' . $lang] . "</td></tr>";
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

                                    $calc_detail = $this->fees_calculation_detail->find('all', array('fields' => array('fee_calc_id', 'fee_rule_id', 'final_value', 'item_type_id', 'fee_calc_desc', 'item.fee_item_desc_' . $lang, 'item.fee_param_type_id', 'item.group_display'),
                                        'conditions' => array('fees_calculation_detail.fee_calc_id' => $calc['fees_calculation']['fee_calc_id'], 'fees_calculation_detail.item_type_id' => 2),
                                        'joins' => array(
                                            array('type' => 'left', 'table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=fees_calculation_detail.fee_item_id'))
                                        ), 'order' => 'item.display_order'
                                    ));

                                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 93)));

                                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {


//                   if($regconf)


                                        if ($calc_detail) {

                                            foreach ($calc_detail as $cd) {
                                                $cds = $cd['fees_calculation_detail'];
                                                if ($cd['item']['fee_param_type_id'] == 2) {
                                                    if ($cd['item']['group_display'] == 'Y') {
                                                        if ($cds['fee_calc_desc'] != 0) {
                                                            $html.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                            $propTotalSD+=round($cds['final_value'], 1);
                                                        }
                                                    }

//                                                 pr($html);
                                                } else {
                                                    if ($cd['item']['group_display'] == 'Y') {
                                                        if ($cds['fee_calc_desc'] != 0) {
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
                                                        if ($cds['fee_calc_desc'] != 0) {
                                                            $newhtml.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                            //   $propTotalSD+=round($cds['final_value'], 1);
                                                        }
                                                    }
//                                                 pr($html);
                                                } else {
                                                    if ($cd['item']['group_display'] == 'N') {
                                                        if ($cds['fee_calc_desc'] != 0) {
                                                            $newhtml.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                            //  $propTotalSD-=round($cds['final_value'], 1);
                                                        }
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
                                                    if ($cds['fee_calc_desc'] != 0) {
                                                        $html.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right>" . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD+=round($cds['final_value'], 1);
                                                    }
                                                } else {
                                                    if ($cds['fee_calc_desc'] != 0) {
                                                        $html.="<tr style='background-color: #F1F0FF;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_' . $lang] . "</td><td align=right> - " . $this->valuation->format_money_india(number_format((float) $cds['final_value'], 0, '.', '')) . "</td></tr>";
                                                        $propTotalSD-=round($cds['final_value'], 1);
                                                    }
                                                }
                                            }
                                        }
                                    }
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

                            if ($calc_detail[0]['fees_calculation_detail']['fee_calc_desc'] != 0) {
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
                            if ($calc_detail[0]['fees_calculation_detail']['fee_calc_desc'] != 0) {
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

    public function view_sd_calc($doc_token_no = NULL, $fee_type_id = 2, $sess_lang = 'en') {
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

//-------------------------------------------------------------------Update Fee Exemption --------------------------------------------------------------------------------
    public function update_fee_exemption($doc_token_no) {
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

    public function delete_fee_exemption() {
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

    public function view_exemption($doc_token_no = NULL, $sess_lang = NULL) {
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
                        $html.="<tr> <td colspan=3>" . $exm['rule']['fee_rule_desc_' . $lang] . " </tr>";
                    }
                    $html.="<tr> "
                            . "<td>" . $srNo++ . ""
                            . "<td>" . $exm['item']['fee_item_desc_' . $lang] . "</td> <td align=right>" . $exm['fees_calculation_detail']['final_value'] . "</td></tr>";
                    $total+=$exm['fees_calculation_detail']['final_value'];
                }
                $html.= "</tbody></table>"
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
    function article_item_link_not_sd() {
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
    public function get_article_dependent_feild() {
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
