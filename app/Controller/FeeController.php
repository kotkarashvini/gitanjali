<?php

class FeeController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

        //$this->Security->unlockedActions = array('conf_feetype_payment_mode', 'feetype', 'payment_mode', 'fees_items', 'fee_dependancy_attribute');
//        $this->Auth->allow('welcomenote', 'usagecategory', 'login', 'add', 'Constructiontype', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'district_new', 'divisionnew', 'taluka', 'itemlist', 'subsubcategory', 'get_taluka_name', 'get_village_name', 'get_data');
    }

    public function conf_feetype_payment_mode() {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('conf_feetype_payment_mode');

            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);


            $language = $this->Session->read('sess_langauge');
            $this->set('language', $language);
            $stateid = $this->Auth->User('state_id');
            $local_langauge = $this->language->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $this->set('language2', $local_langauge);

            $this->set('feetype', ClassRegistry::init('fee_type')->find('list', array('fields' => array('fee_type_id', 'fee_type_desc_' . $language), 'order' => array('fee_type_desc_' . $language => 'ASC'))));
            $this->set('payment_mode', ClassRegistry::init('payment_mode')->find('list', array('fields' => array('payment_mode_id', 'payment_mode_desc_' . $language), 'order' => array('payment_mode_desc_' . $language => 'ASC'))));
            $grid = $this->conf_feetype_payment_mode->query("select distinct b.fee_type_id,b.id,b.fee_type_desc_" . $language . "
                                                 from ngdrstab_conf_feetype_payment_mode a
                                                 inner join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_type_id");
            $grid1 = $this->conf_feetype_payment_mode->query("select a.*,c.payment_mode_desc_" . $language . "
                                                 from ngdrstab_conf_feetype_payment_mode a
                                                 inner join ngdrstab_mst_payment_mode c on c.payment_mode_id=a.payment_mode_id");

            $this->set('grid', $grid);
            $this->set('grid1', $grid1);
            $fieldlist = array();
            $fieldlist['fee_type_id']['select'] = 'is_select_req';
            $fieldlist['payment_mode_id']['checkbox'] = 'is_required';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['conf_feetype_payment_mode']['csrftoken']);
                $data['state_id'] = $this->Auth->User('state_id');
                $data['user_id'] = $this->Auth->User('user_id');
                // $data['created_date'] = date('Y/m/d H:i:s');
                $data['req_ip'] = $_SERVER['REMOTE_ADDR'];

                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);

                if ($_POST['actiontype'] == '1') {
                    $check = $this->conf_feetype_payment_mode->query("SELECT MAX(feetype_payment_mode_id) FROM ngdrstab_conf_feetype_payment_mode");
                    $feetype_payment_mode_id = $check[0][0]['max'];
                    if ($feetype_payment_mode_id != Null) {
                        $feetype_payment_mode_id = $feetype_payment_mode_id + 1;
                    } else {
                        $feetype_payment_mode_id = 1;
                    }
                    $data['feetype_payment_mode_id'] = $feetype_payment_mode_id;

                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $feetype = $_POST['hfid'];
                        $id = $this->conf_feetype_payment_mode->query("select a.id from ngdrstab_conf_feetype_payment_mode a
                                                                        inner join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_type_id
                                                                        where a.fee_type_id=?", array($feetype));
                        foreach ($id as $id1) {
                            $temp = $id1[0]['id'];
                            $delete = $this->conf_feetype_payment_mode->query("Delete from ngdrstab_conf_feetype_payment_mode where id=?", array($temp));
                        }
                        $actionvalue = "lbleditmsg";
                    } else {
                        $feetype = $this->request->data['conf_feetype_payment_mode']['fee_type_id'];
                        $id = $this->conf_feetype_payment_mode->query("select a.id from ngdrstab_conf_feetype_payment_mode a
                                                                        inner join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_type_id
                                                                        where a.fee_type_id=?", array($feetype));
                        if ($id == NULL) {
                            $actionvalue = "lblsavemsg";
                        } else {
                            $this->Session->setFlash(__("You have already Configue this record.. "));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'conf_feetype_payment_mode'));
                        }
                    }
                    $payment_mode_id = $this->request->data['conf_feetype_payment_mode']['payment_mode_id'];
                    if (!empty($payment_mode_id)) {
                        $count = 0;
                        $tempdata = array();
                        foreach ($payment_mode_id as $selected) {
                            $data['fee_type_id'] = $this->request->data['conf_feetype_payment_mode']['fee_type_id'];
                            $data['payment_mode_id'] = $selected;
                            array_push($tempdata, $data);
                        }
                        if ($this->conf_feetype_payment_mode->saveAll($tempdata)) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'conf_feetype_payment_mode'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Fee', 'action' => 'conf_feetype_payment_mode'));
                }
                if ($_POST['actiontype'] == '3') {
                    if ($_POST['hfid'] != NULL) {
                        $feetype = $_POST['hfid'];
                        $id = $this->conf_feetype_payment_mode->query("select a.id from ngdrstab_conf_feetype_payment_mode a
                                                                        inner join ngdrstab_mst_fee_type b on b.fee_type_id = a.fee_type_id
                                                                        where a.fee_type_id=?", array($feetype));
                        foreach ($id as $id1) {
                            $temp = $id1[0]['id'];
                            $delete = $this->conf_feetype_payment_mode->query("Delete from ngdrstab_conf_feetype_payment_mode where id=?", array($temp));
                        }
                        if ($delete == NULL) {
                            $this->Session->setFlash(__('lbldeletemsg'));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'conf_feetype_payment_mode'));
                        } else {
                            $this->Session->setFlash(__('lblnotdeletemsg'));
                        }
                    }
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

    public function fee_dependancy_attribute() {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('fee_dependancy_attribute');
            $this->loadModel('mainlanguage');

            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);

            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User('state_id');
            $local_langauge = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $this->set('language2', $local_langauge);

            $fee_dependancy_attribute = $this->fee_dependancy_attribute->query("select * from ngdrstab_mst_fee_dependancy_attribute");
            $this->set('fee_dependancy_attribute', $fee_dependancy_attribute);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);

            $fieldlist = array();
            $fielderrorarray = array();

            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_dependancy_attribute_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    //list for all unicode fields
                    $fieldlist['fee_dependancy_attribute_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['fee_dependancy_attribute']['csrftoken']);
                $this->request->data['fee_dependancy_attribute']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['fee_dependancy_attribute']['user_id'] = $this->Auth->User('user_id');
                //   $this->request->data['fee_dependancy_attribute']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['fee_dependancy_attribute']['req_ip'] = $_SERVER['REMOTE_ADDR'];

                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);

                if ($_POST['actiontype'] == '1') {
                    $check = $this->fee_dependancy_attribute->query("SELECT MAX(fee_dependancy_attribute_id) FROM ngdrstab_mst_fee_dependancy_attribute");
                    $fee_dependancy_attribute_id = $check[0][0]['max'];
                    if ($fee_dependancy_attribute_id != Null) {
                        $fee_dependancy_attribute_id = $fee_dependancy_attribute_id + 1;
                    } else {
                        $fee_dependancy_attribute_id = 1;
                    }

                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['fee_dependancy_attribute']['id'] = $this->request->data['hfid'];
                        $actionvalue = "lbleditmsg";
                    } else {
                        $this->request->data['fee_dependancy_attribute']['fee_dependancy_attribute_id'] = $fee_dependancy_attribute_id;
                        $actionvalue = "lblsavemsg";
                    }
                    $this->request->data['fee_dependancy_attribute'] = $this->istrim($this->request->data['fee_dependancy_attribute']);
                    $errarr = $this->validatedata($this->request->data['fee_dependancy_attribute'], $fieldlist);
                    if ($this->ValidationError($errarr)) {

                        if ($this->fee_dependancy_attribute->save($this->request->data['fee_dependancy_attribute'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'fee_dependancy_attribute'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Fee', 'action' => 'fee_dependancy_attribute'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_fee_dependancy_attribute($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('fee_dependancy_attribute');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->fee_dependancy_attribute->id = $id;
                if ($this->fee_dependancy_attribute->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'fee_dependancy_attribute'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function payment_mode($id = NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('payment_mode');
            $this->loadModel('mainlanguage');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);

            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User('state_id');
            $local_langauge = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $this->set('language2', $local_langauge);

            $payment_mode = $this->payment_mode->query("select * from ngdrstab_mst_payment_mode");
            $this->set('payment_mode', $payment_mode);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))
                , 'order' => 'conf.language_id ASC'
                ));
            
      
            $this->set('languagelist', $languagelist);
            $paymentActivation = array('Y' => "Yes", 'N' => "No");
            $this->set('paymentActivation', $paymentActivation);

            $paymentverification = array('Y' => "Yes", 'N' => "No");
            $this->set('paymentverification', $paymentverification);


            $this->set("fieldlist", $fieldlist = $this->payment_mode->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
                $this->set('hfid', $id);
            } else {
                $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                // pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['payment_mode']['csrftoken']);
                $this->request->data['payment_mode']['id'] = $this->request->data['hfid'];
                $this->request->data['payment_mode']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['payment_mode']['user_id'] = $this->Auth->User('user_id');
                // $this->request->data['payment_mode']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['payment_mode']['req_ip'] = $_SERVER['REMOTE_ADDR'];

                $verrors = $this->validatedata($this->request->data['payment_mode'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->payment_mode->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['payment_mode']);
                    if ($checkd) {
                        if ($this->payment_mode->save($this->request->data['payment_mode'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'payment_mode'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }

            if (!is_null($id) && is_numeric($id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('id', $id);
                $result = $this->payment_mode->find("first", array('conditions' => array('id' => $id)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['payment_mode'] = $result['payment_mode'];
                     $paymentActivation = array('Y' => "Yes", 'N' => "No");
            $this->set('paymentActivation', $paymentActivation);
            
            $paymentverification = array('Y' => "Yes", 'N' => "No");
            $this->set('paymentverification', $paymentverification);
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }


            //}
            //}
        } catch (Exception $ex) {
            pr($ex);
            pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_payment_mode($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('payment_mode');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->payment_mode->id = $id;
                if ($this->payment_mode->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'payment_mode'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function feetype_old() {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('fee_type');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);
            $this->set('feetyperecord', $this->fee_type->find('all'));
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['fee_type']['req_ip'] = $req_ip;
            $this->request->data['fee_type']['user_id'] = $user_id;
            // $this->request->data['fee_type']['created_date'] = $created_date;
            $this->request->data['fee_type']['state_id'] = $stateid;
            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['fee_type_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    //list for all unicode fields
                    $fieldlist['fee_type_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //this array of error is set here to display those correspondent fields error  in the ctp.
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['fee_type']['csrftoken']);


                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
//                pr($this->request->data);
//                exit;
                if ($actiontype == '1') {

                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);

                    if ($hfactionval == 'S') {
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['fee_type']['id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
//                        pr($this->request->data);exit;
                        $this->request->data['fee_type'] = $this->istrim($this->request->data['fee_type']);
                        $errarr = $this->validatedata($this->request->data['fee_type'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            //pr($this->request->data['fee_type']);  exit;
                            if ($this->fee_type->save($this->request->data['fee_type'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Fee', 'action' => 'feetype'));
                                $this->set('feetyperecord', $this->feetype->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
                if ($actiontype == 2) {
                    $this->set('hfupdateflag', 'Y');
                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_feetype_old($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('fee_type');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->fee_type->id = $id;
                if ($this->fee_type->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'feetype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function feetype($feetypeid = NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('fee_type');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);
            $this->set('feetyperecord', $this->fee_type->find('all'));
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['fee_type']['req_ip'] = $req_ip;
            $this->request->data['fee_type']['user_id'] = $user_id;
            // $this->request->data['fee_type']['created_date'] = $created_date;
            $this->request->data['fee_type']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->fee_type->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($feetypeid)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                //pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['fee_type']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['fee_type'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->fee_type->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['fee_type']);
                    if ($checkd) {

                        if ($this->fee_type->save($this->request->data['fee_type'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'feetype'));
                            $this->set('feetyperecord', $this->feetype->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
//                    }
//                }
//                if ($actiontype == 2) {
//                    $this->set('hfupdateflag', 'Y');
//                }
            }

            if (!is_null($feetypeid) && is_numeric($feetypeid)) {
                $this->set('editflag', 'Y');
                $this->Session->write('fee_type_id', $feetypeid);
                $result = $this->fee_type->find("first", array('conditions' => array('fee_type_id' => $feetypeid)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['fee_type'] = $result['fee_type'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_feetype($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('fee_type');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->fee_type->fee_type_id = $id;
                if ($this->fee_type->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'feetype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }
      public function fee_item_type($id=NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('fee_itemstype');
            $this->loadModel('mainlanguage');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User('state_id');
            $local_langauge = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $this->set('language2', $local_langauge);

            $feeitem = $this->fee_itemstype->query("select * from ngdrstab_mst_items_types order by usage_param_type_id");
            $this->set('feeitem', $feeitem);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
             $this->set('languagelist', $languagelist);
            

             $this->set("fieldlist", $fieldlist = $this->fee_itemstype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            
            if (!empty($id)) {
                 $actionvalue = 'lbleditmsg';
                 $this->set('hfid', $id);
            } else {
                 $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post')|| $this->request->is('put')) {
                  // pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['fee_item_type']['csrftoken']);
                $this->request->data['fee_item_type']['id'] =  $this->request->data['hfid'];
                $this->request->data['fee_item_type']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['fee_item_type']['user_id'] = $this->Auth->User('user_id');
                // $this->request->data['payment_mode']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['fee_item_type']['req_ip'] = $_SERVER['REMOTE_ADDR'];

 $verrors = $this->validatedata($this->request->data['fee_item_type'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->fee_itemstype->get_duplicate($languagelist);
                     $checkd = $this->check_duplicate($duplicate, $this->request->data['fee_item_type']);
                     if ($checkd) {
                        if ($this->fee_itemstype->save($this->request->data['fee_item_type'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'fee_item_type'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        } 
                        } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                        }else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            
                    if (!is_null($id) && is_numeric($id)) {
 $this->set('editflag', 'Y');
                $this->Session->write('id', $id);
                $result = $this->fee_itemstype->find("first", array('conditions' => array('id' => $id)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['fee_item_type'] = $result['fee_itemstype'];
                    

                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }      
                        
                        
                //}
            //}
        } catch (Exception $ex) {
            pr($ex);
            pr($exc);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }
    public function delete_fee_item_type($id = null) {
         
        $this->autoRender = false;
        $this->loadModel('fee_itemstype');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->fee_itemstype->id = $id;
                if ($this->fee_itemstype->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'fee_item_type'));
                }
                // }
            }
        } catch (exception $ex) {
             pr($ex);exit;
        }
    }
    
    
     public function fee_round_value($id=NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('fee_round_value');
            $this->loadModel('mainlanguage');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User('state_id');
            $local_langauge = $this->mainlanguage->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
            $this->set('language2', $local_langauge);

            $feeitem = $this->fee_round_value->query("select * from ngdrstab_mst_rounding_value order by rounding_id");
            $this->set('feeitem', $feeitem);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
             $this->set('languagelist', $languagelist);
            

             $this->set("fieldlist", $fieldlist = $this->fee_round_value->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            
            if (!empty($id)) {
                 $actionvalue = 'lbleditmsg';
                 $this->set('hfid', $id);
            } else {
                 $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post')|| $this->request->is('put')) {
                  // pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['fee_round_value']['csrftoken']);
                $this->request->data['fee_round_value']['rounding_id'] =  $this->request->data['hfid'];
                $this->request->data['fee_round_value']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['fee_round_value']['user_id'] = $this->Auth->User('user_id');
                // $this->request->data['payment_mode']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['fee_round_value']['req_ip'] = $_SERVER['REMOTE_ADDR'];

 $verrors = $this->validatedata($this->request->data['fee_round_value'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->fee_round_value->get_duplicate($languagelist);
                     $checkd = $this->check_duplicate($duplicate, $this->request->data['fee_round_value']);
                     if ($checkd) {
                        if ($this->fee_round_value->save($this->request->data['fee_round_value'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Fee', 'action' => 'fee_round_value'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        } 
                        } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                        }else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            
                    if (!is_null($id) && is_numeric($id)) {
 $this->set('editflag', 'Y');
                $this->Session->write('id', $id);
                $result = $this->fee_round_value->find("first", array('conditions' => array('rounding_id' => $id)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['fee_round_value'] = $result['fee_round_value'];
                    

                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }      
                        
                        
                //}
            //}
        } catch (Exception $ex) {
            pr($ex);
            pr($exc);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }
    public function delete_fee_round_value($id = null) {
         
        $this->autoRender = false;
        $this->loadModel('fee_round_value');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->fee_round_value->rounding_id = $id;
                if ($this->fee_round_value->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'fee_round_value'));
                }
                // }
            }
        } catch (exception $ex) {
             pr($ex);exit;
        }
    }

}
