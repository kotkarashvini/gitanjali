<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class BlockLevelsController extends AppController {

    //put your code here
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
    }

    public function admin_block_level_config() {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('adminLevelConfig');
            $this->set('actontype', NULL);
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Formlabel');

            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->loadModel('currentstate');

            
            //languages are loaded firstly from config (from table)


            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));

            //pr($languagelist);exit;




            $this->set('languagelist', $languagelist);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['statename_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                    $fieldlist['divisionname_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_alphaspace';
                    $fieldlist['districtname_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                    $fieldlist['subdivname_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_alphaspace';
                    $fieldlist['talukaname_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                    $fieldlist['circlename_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_alphaspace';
                    $fieldlist['villagename_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
                } else {
                    //list for all unicode fields
                    $fieldlist['statename_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['divisionname_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['districtname_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['subdivname_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['talukaname_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['circlename_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                    $fieldlist['villagename_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to100";
                }
            }
            $fieldlist['is_state']['radio'] = 'is_yes_no';
            $fieldlist['is_div']['radio'] = 'is_yes_no';
            $fieldlist['is_dist']['radio'] = 'is_yes_no';
            $fieldlist['is_subdiv']['radio'] = 'is_yes_no';
            $fieldlist['is_taluka']['radio'] = 'is_yes_no';
            $fieldlist['is_circle']['radio'] = 'is_yes_no';
            $fieldlist['is_village']['radio'] = 'is_yes_no';
            $this->set('fieldlist', $fieldlist);



            if ($this->request->is('post')) {
                //pr($this->request->data);
                //$this->check_csrf_token($this->request->data['admin_block_level_config']['csrftoken']);
                $this->request->data['admin_block_level_config']['req_ip'] = $this->request->clientIp();
                $this->request->data['admin_block_level_config']['user_id'] = $this->Auth->User('user_id');
                //  $this->request->data['admin_block_level_config']['created_date'] = date('Y-m-d');

                $this->request->data['admin_block_level_config']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['admin_block_level_config'] = $this->istrim($this->request->data['admin_block_level_config']);



                // pr($this->request->data['admin_block_level_config']);
                // exit;

                foreach ($languagelist as $languagecode) {

                    if (isset($this->request->data['admin_block_level_config']['is_div']) && $this->request->data['admin_block_level_config']['is_div'] == 'N') {
                        unset($fieldlist['divisionname_' . $languagecode['mainlanguage']['language_code']]);
                    }
                    if (isset($this->request->data['admin_block_level_config']['is_subdiv']) && $this->request->data['admin_block_level_config']['is_subdiv'] == 'N') {
                        unset($fieldlist['subdivname_' . $languagecode['mainlanguage']['language_code']]);
                    }
                    if (isset($this->request->data['admin_block_level_config']['is_circle']) && $this->request->data['admin_block_level_config']['is_circle'] == 'N') {
                        unset($fieldlist['circlename_' . $languagecode['mainlanguage']['language_code']]);
                    }
                }
                // pr($fieldlist);
                // exit;
                $errors = $this->validatedata($this->request->data['admin_block_level_config'], $fieldlist);
                if ($this->ValidationError($errors)) {
                    if ($this->adminLevelConfig->save($this->request->data['admin_block_level_config'])) {
                        $data = $this->request->data['admin_block_level_config'];

                        $label_state['labelname'] = 'lbladmstate';
                        $label_div['labelname'] = 'lbladmdivision';
                        $label_dist['labelname'] = 'lbladmdistrict';
                        $label_subdiv['labelname'] = 'lbladmsubdiv';
                        $label_taluka['labelname'] = 'lbladmtaluka';
                        $label_circle['labelname'] = 'lbladmcircle';
                        $label_village['labelname'] = 'lbladmvillage';

                        foreach ($languagelist as $language) {
                            $label_state['label_desc_' . $language['mainlanguage']['language_code']] = $data['statename_' . $language['mainlanguage']['language_code']];
                            $label_state[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_div['label_desc_' . $language['mainlanguage']['language_code']] = $data['divisionname_' . $language['mainlanguage']['language_code']];
                            $label_div[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_dist['label_desc_' . $language['mainlanguage']['language_code']] = $data['districtname_' . $language['mainlanguage']['language_code']];
                            $label_dist[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_subdiv['label_desc_' . $language['mainlanguage']['language_code']] = $data['subdivname_' . $language['mainlanguage']['language_code']];
                            $label_subdiv[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_taluka['label_desc_' . $language['mainlanguage']['language_code']] = $data['talukaname_' . $language['mainlanguage']['language_code']];
                            $label_taluka[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_circle['label_desc_' . $language['mainlanguage']['language_code']] = $data['circlename_' . $language['mainlanguage']['language_code']];
                            $label_circle[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';

                            $label_village['label_desc_' . $language['mainlanguage']['language_code']] = $data['villagename_' . $language['mainlanguage']['language_code']];
                            $label_village[$language['mainlanguage']['language_code'] . '_activation_flag'] = 'Y';
                        }
                        //pr($label_state);exit;
                        $this->Formlabel->save($label_state);
                        $this->Formlabel->save($label_div);
                        $this->Formlabel->save($label_dist);
                        $this->Formlabel->save($label_subdiv);
                        $this->Formlabel->save($label_taluka);
                        $this->Formlabel->save($label_circle);
                        $this->Formlabel->save($label_village);

                        $this->Session->setFlash(__("lblsavemsg"));

                        return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'admin_block_level_config'));
                    } else {
                        $this->Session->setFlash("There is some error");
                    }
                } else {
                    $this->set("errarr", $errors);
                    $this->Session->setFlash(
                            __('Please Find Validation Errors!')
                    );
                }
            } else {
                $first = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $this->Auth->User('state_id'))));
                if (!empty($first)) {
                    $this->request->data['admin_block_level_config'] = $first['adminLevelConfig'];
                } else {
                    //$first = $this->adminLevelConfig->find('first');
                    $data['statename_en'] = 'State';
                    $data['divisionname_en'] = 'Division';
                    $data['districtname_en'] = 'District';
                    $data['subdivname_en'] = 'Sub Division';
                    $data['talukaname_en'] = 'Tehsil';
                    $data['circlename_en'] = 'Circle';
                    $data['villagename_en'] = 'Village';


                    $this->request->data['admin_block_level_config'] = $data;
                }
            }
            $currentstate = $this->currentstate->find("first", array('conditions' => array('state_id' => $this->Auth->User('state_id'))));

            $this->set('currentstate', $currentstate);
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function block_levels_main_menu() {
        $this->loadModel('adminLevelConfig');
        $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $this->Auth->User('state_id'))));
        return $adminLevelConfig;
    }

    public function division_new() {
        try {
            $this->check_role_escalation_tab();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'divisionnew', 'mainlanguage', 'language', 'adminLevelConfig'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");

            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('districtrecord', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            //languages are loaded firstly from config (from table)

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));

            $this->set('languagelist', $languagelist);

            $this->set('districtrecord', $this->divisionnew->find('all',array('order'=>'division_id DESC')));

            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('adminLevelConfig', $adminLevelConfig);

            $this->set("fieldlist", $fieldlist = $this->divisionnew->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
//pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['division_new']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');


                $stateid = $this->Auth->User("state_id");


                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);

                    if ($hfactionval == 'S') {
                        $duplicateflag = 'S';
                        $this->request->data['division_new']['req_ip'] = $this->request->clientIp();
                        $this->request->data['division_new']['user_id'] = $user_id;
                        $this->request->data['division_new']['actiontype'] = $actiontype;
                        $this->request->data['division_new']['hfaction'] = $hfactionval;
                        $this->request->data['division_new']['state_id'] = $stateid;
                        $this->request->data['division_new']['hfid'] = $hfid;


                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['division_new']['division_id'] = $this->request->data['hfid'];
                            $duplicateflag = 'U';
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                            
                        }
                            


                        $verrors = $this->validatedata($this->request->data['division_new'], $fieldlist);
                        if ($this->ValidationError($verrors)) {
                            $duplicate = $this->divisionnew->get_duplicate($languagelist);
                            $checkd = $this->check_duplicate($duplicate, $this->request->data['division_new']);
                            if ($checkd) {

                                if ($this->divisionnew->save($this->request->data['division_new'])) {

                                    $this->Session->setFlash(__($actionvalue));

                                    $this->redirect(array('controller' => 'BlockLevels', 'action' => 'division_new'));
                                    $this->set('districtrecord', $this->divisionnew->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }
                        } else {
                            $this->Session->setFlash(__('Find validations '));
                        }
                    } else {
                        $this->Session->setFlash(__('Find Validation Errors'));
                    }
                }
            }


            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {


            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function division_new_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('divisionnew');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->divisionnew->division_id = $id;
                if ($this->divisionnew->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'division_new'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function subdivision($subdivision_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('Subdivision');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('office');
            $this->loadModel('division');
            $this->set('subdivisionrecord', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('districtdata', NULL);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));





            $this->set('languagelist', $languagelist);

            $language = $this->Session->read("sess_langauge");

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            // $stateid=20;

            $configure = $this->office->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
            $this->set('configure', $configure);


            $divisionname = $this->division->find('list', array('fields' => array('division.division_id', 'division_name_' . $language), 'conditions' => array('state_id' => $stateid), 'order' => array('division_name_' . $language => 'ASC')));
            $this->set('divisiondata', $divisionname);


            if ($configure[0][0]['is_div'] == 'N') {
                $districtnname = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $language => 'ASC')));
                $this->set('districtdata', $districtnname);
            }

            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('subdivisionrecord', $this->Subdivision->find('all'));
            $fieldlist = array();
            $fielderrorarray = array();

            if ($configure[0][0]['is_div'] == 'Y') {
                $fieldlist['division_id']['select'] = 'is_required';
            }
            $fieldlist['district_id']['select'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['subdivision_name_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength50';
                } else {
                    //list for all unicode fields
                    $fieldlist['subdivision_name_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicoderequired_rule_" . $languagecode['mainlanguage']['language_code'] . ',maxlength_unicode_0to50';
                }
            }


            $fieldlist['dsro_code']['text'] = 'is_required,is_positiveinteger';


            $this->set('fieldlist', $fieldlist);

            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {


                $this->check_csrf_token($this->request->data['subdivision']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $subdivision_id;

                $this->set('hfid', $hfid);

                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);

                    $this->request->data['subdivision']['state_id'] = $stateid;
                    if (isset($hfid)) {
                        $this->request->data['subdivision']['subdivision_id'] = $hfid;
                        $actionvalue = "lbleditmsg";
                        $duplicateflag = 'U';
                    }


                    if ($hfactionval == 'S') {
                        $duplicateflag = 'S';
                        $this->request->data['subdivision']['req_ip'] = $this->request->clientIp();
                        $this->request->data['subdivision']['user_id'] = $user_id;
                        // $this->request->data['subdivision']['created_date'] = $created_date;
//                        if ($this->request->data['hfupdateflag'] == 'Y') {
//                            $this->request->data['subdivision']['subdivision_id'] = $this->request->data['hfid'];
//                            $actionvalue = "lbleditmsg";
//                             $duplicateflag = 'U';
//                        } else {
                        $actionvalue = "lblsavemsg";
//                        }
                        $errarr = $this->validatedata($this->request->data['subdivision'], $fieldlist);


                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {

                            if ($this->ValidationError($errarr)) {
//                            $duplicate['Table'] = 'ngdrstab_conf_admblock4_subdivision';
//                            $duplicate['Fields'] = array('subdivision_name_en', 'subdivision_name_ll');
//                            $duplicate['Action'] = $duplicateflag; //U   
//                            $duplicate['PrimaryKey'] = 'subdivision_id';


                                $duplicate = $this->Subdivision->get_duplicate($languagelist);
                                $checkd = $this->check_duplicate($duplicate, $this->request->data['subdivision']);

                                if ($checkd) {

                                    if ($this->Subdivision->save($this->request->data['subdivision'])) {

                                        $lastid = $this->Subdivision->getLastInsertId();
                                        if (is_numeric($lastid)) {
                                            $actionvalue = 'lblsavemsg';
                                        } else {
                                            $actionvalue = 'lbleditmsg';
                                        }
                                        $this->Session->setFlash(__($actionvalue));

                                        $this->redirect(array('controller' => 'BlockLevels', 'action' => 'subdivision'));
                                        $this->set('subdivisionrecord', $this->subdivision->find('all'));
                                    } else {
                                        $this->Session->setFlash(__('lblnotsavemsg'));
                                    }
                                } else {
                                    if ($configure[0][0]['is_div'] == 'Y') {
                                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('division_id' => @$this->request->data['subdivision']['division_id']), 'order' => array('district_name_' . $language => 'ASC')));
                                        $this->set('districtdata', $distdata);
                                    } else {
                                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC')));
                                        $this->set('districtdata', $distdata);
                                    }
                                    $this->Session->setFlash(__('lblduplicatemsg'));
                                }
                            }
                        }
//                    if ($actiontype == 2) {
//                        $this->set('hfupdateflag', 'Y');
//                    }
                    }
                }
            }
            if (!is_null($subdivision_id) && is_numeric($subdivision_id)) {

                $this->Session->write('subdivision_id', $subdivision_id);
                $result = $this->Subdivision->find("first", array('conditions' => array('subdivision_id' => $subdivision_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['subdivision'] = $result['Subdivision'];
                    $division_id = $this->District->query("SELECT division_id FROM ngdrstab_conf_admblock3_district where district_id=?", array($result['Subdivision']['district_id']));
                    $divid = $division_id[0][0]['division_id'];
                    $this->request->data['subdivision']['division_id'] = $divid;
                    if ($configure[0][0]['is_div'] == 'Y') {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('division_id' => $divid), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('districtdata', $distdata);
                    } else {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('districtdata', $distdata);
                    }
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function subdivision_delete($subdivision_id = null) {
        $this->autoRender = false;
        $this->loadModel('Subdivision');
        try {
            $id = $this->decrypt($subdivision_id, $this->Session->read("randamkey"));

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'subdivision') {
                $this->Subdivision->subdivision_id = $id;
                if ($this->Subdivision->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'subdivision'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function getdistsubdiv() {
        try {

            $this->autoRender = FALSE;
            $this->loadModel('District');
            $lang = $this->Session->read("sess_langauge");
            $data = $_GET['division_id'];


            if (isset($data) && is_numeric($data)) {

                $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $data)));
                echo json_encode($distdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_block_status($data = NULL) {
        $this->loadModel('adminLevelConfig');
        $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $this->Auth->User('state_id'))));
        if (empty($adminLevelConfig)) {
            $this->Session->setFlash(
                    __('Please Check Configuration')
            );
            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'admin_block_level_config'));
        }
    }

    public function district_new() {
        try {
            $this->check_role_escalation_tab();

            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'District', 'mainlanguage', 'language', 'division', 'adminLevelConfig'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('districtrecord', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            //languages are loaded firstly from config (from table)
            $divisiondata = $this->division->find('list', array('fields' => array('division_id', 'division_name_' . $laug), 'order' => array('division_id' => 'ASC')));
            $this->set('divisiondata', $divisiondata);
            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('adminLevelConfig', $adminLevelConfig);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('districtrecord', $this->District->find('all'));

            $this->set("fieldlist", $fieldlist = $this->District->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['district_new']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');


                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);

                    if ($hfactionval == 'S') {
                        $this->request->data['district_new']['req_ip'] = $this->request->clientIp();
                        $this->request->data['district_new']['user_id'] = $user_id;
                        $this->request->data['district_new']['actiontype'] = $actiontype;
                        $this->request->data['district_new']['hfaction'] = $hfactionval;
                        $this->request->data['district_new']['state_id'] = $stateid;
                        $this->request->data['district_new']['hfid'] = $hfid;

                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['district_new']['district_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }


                        $verrors = $this->validatedata($this->request->data['district_new'], $fieldlist);
                        if ($this->ValidationError($verrors)) {
                            $duplicate = $this->District->get_duplicate($languagelist);
                            $checkd = $this->check_duplicate($duplicate, $this->request->data['district_new']);
                            if ($checkd) {
                                if ($this->District->save($this->request->data['district_new'])) {
                                    $this->Session->setFlash(__($actionvalue));
                                    $this->redirect(array('controller' => 'BlockLevels', 'action' => 'district_new'));
                                    $this->set('districtrecord', $this->District->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }
                        } else {
                            $this->Session->setFlash(__('Find validations '));
                        }
                    }
                }
            }

            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {

            pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function district_new_delete($id = null) {
        //pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('District');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'constructiontype') {
                $this->District->district_id = $id;
                if ($this->District->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'district_new'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function division_change_event() {

        try {
            $this->loadModel('District');
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['division_id'])) {
                $division_id = $data['division_id'];

                $Districtlist = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $division_id)));

                $this->set('Districtlist', $Districtlist);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function taluka($taluka_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $laug), 'order' => array('division_id' => 'ASC')));
            $this->set('divisiondata', $divisiondata);

            $subdivisiondata = '';
            //  $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $this->set('subdivisiondata', $subdivisiondata);

            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'N') {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $laug), 'order' => array('district_id' => 'ASC')));
            } else {
                $distdata = '';
            }
            $this->set('distdata', $distdata);

            $this->set('adminLevelConfig', $adminLevelConfig);

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);




            $this->set("fieldlist", $fieldlist = $this->taluka->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            $this->set('talukarecord', NULL);


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['taluka']['ip_address'] = $this->request->clientIp();
                $this->request->data['taluka']['created_date'] = $created_date;
                $this->request->data['taluka']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['taluka'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->taluka->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['taluka']);
                    if ($checkd) {
                        if ($this->taluka->save($this->request->data['taluka'])) {


                            $lastid = $this->taluka->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $actionvalue = 'lblsavemsg';
                            } else {
                                $actionvalue = 'lbleditmsg';
                            }
                            $this->Session->setFlash(__($actionvalue));
                            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'taluka'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['taluka']['division_id'])));
                            $this->set('distdata', $distdata);
                        }
                        $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['taluka']['district_id'])));
                        $this->set('subdivisiondata', $subdivisiondata);

                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    //  pr($verrors);
                    //  exit;
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($taluka_id) && is_numeric($taluka_id)) {


                $this->Session->write('taluka_id', $taluka_id);

                $result = $this->taluka->find("first", array('conditions' => array('taluka_id' => $taluka_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['taluka'] = $result['taluka'];
                    if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => $result['taluka']['division_id'])));
                    }
                    $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => $result['taluka']['district_id'])));
                    $this->set('distdata', $distdata);
                    $this->set('subdivisiondata', $subdivisiondata);
                    $this->set('result', $result);
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
            $talukadata = $this->taluka->query("select * from ngdrstab_conf_admblock5_taluka");
            $this->set('talukadata', $talukadata);
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_taluka($id = null) {
        $this->autoRender = false;
        $this->loadModel('taluka');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->taluka->taluka_id = $id;
                if ($this->taluka->delete($id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'taluka'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //---------------Division->District filteration
    public function getdist() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('District');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['division_id']) && is_numeric($data['division_id'])) {
                $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $data['division_id'])));
                echo json_encode($distdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettaluka() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('taluka');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;


            if (isset($data['subdivision_id']) && is_numeric($data['subdivision_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $data['subdivision_id'])));
                echo json_encode($talukadata1);
                exit;
            } else if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($talukadata1);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //---------------District->Subdivision filteration
    public function getsubdiv() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('Subdivision');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($subdivisiondata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

   
    public function circle($circle_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('circle');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $configure = $this->taluka->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
            $this->set('configure', $configure);

            $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $laug), 'order' => array('division_id' => 'ASC')));
            $this->set('divisiondata', $divisiondata);

            $distdata = '';
            $subdivisiondata = '';

//            $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $subdivisiondata = '';
            $this->set('subdivisiondata', $subdivisiondata);

            //$talukadata1 = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'order' => array('taluka_id' => 'ASC')));
            $talukadata1 = '';
            $this->set('talukadata1', $talukadata1);
            $talukadata1 = '';
            if ($configure[0][0]['is_div'] == 'N') {
                //$distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', null);
                $this->set('divisiondata', null);
                $this->set('subdivisiondata', null);
                $this->set('talukadata1', null);
            } else {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $laug), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', $distdata);
            }

            $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('is_div_flag', $is_div_flag);
// taluka

            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'N') {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_id' => 'ASC')));
            } else {
                $distdata = '';
            }
            $this->set('distdata', $distdata);





            $this->set('adminLevelConfig', $adminLevelConfig);

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set("fieldlist", $fieldlist = $this->circle->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $this->set('talukarecord', NULL);

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $circledata = $this->circle->query("select * from ngdrstab_conf_admblock6_circle");
            $this->set('circledata', $circledata);

            if ($this->request->is('post') || $this->request->is('put')) {

                $verrors = $this->validatedata($this->request->data['circle'], $fieldlist);
                //pr($verrors);exit;
                if ($this->ValidationError($verrors)) {

                    $duplicate = $this->circle->get_duplicate($languagelist);

                    $checkd = $this->check_duplicate($duplicate, $this->request->data['circle']);

                    if ($checkd) {
                        if ($this->circle->save($this->request->data['circle'])) {


                            $lastid = $this->circle->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $actionvalue = 'lblsavemsg';
                            } else {
                                $actionvalue = 'lbleditmsg';
                            }
                            $this->Session->setFlash(__($actionvalue));

                            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'circle'));
                        }
                    } else {

                        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['circle']['division_id'])));
                            $this->set('distdata', $distdata);
                        } else {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug)));
                            $this->set('distdata', $distdata);
                        }
                        $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['circle']['district_id'])));
                        $this->set('subdivisiondata', $subdivisiondata);

                        if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('subdivision_id' => @$this->request->data['circle']['subdivision_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));

                            $this->set('talukadata1', $talukadata);
                        } else {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['circle']['district_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));
                            $this->set('talukadata1', $talukadata);
                        }


                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }



            if (!is_null($circle_id) && is_numeric($circle_id)) {

                $this->Session->write('circle_id', $circle_id);
                $result = $this->circle->find("first", array('conditions' => array('circle_id' => $circle_id)));

                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['circle'] = $result['circle'];

                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        $divisiondata = ClassRegistry::init('divisionnew')->find('list', array('fields' => array('divisionnew.division_id', 'divisionnew.division_name_' . $laug)));
                    }
                    $this->set('divisiondata', $divisiondata);
                    // getting drop down

                    $data = $this->circle->query("select circle.circle_id,tal.taluka_id,subd.subdivision_id,dist.district_id,div.division_id
                from ngdrstab_conf_admblock6_circle circle
                join ngdrstab_conf_admblock5_taluka as tal on tal.taluka_id=circle.taluka_id
                left join ngdrstab_conf_admblock4_subdivision as subd on subd.subdivision_id=tal.subdivision_id
                join ngdrstab_conf_admblock3_district as dist on dist.district_id=subd.district_id
                left join ngdrstab_conf_admblock2_division as div on div.division_id=dist.division_id
                where circle.circle_id = ?", array($circle_id));

                    $this->request->data['circle']['division_id'] = $data[0][0]['division_id'];

                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'conditions' => array('division_id' => $data[0][0]['division_id']), 'order' => array('district_id' => 'ASC')));
                        $this->set('distdata', $distdata);
                    } else {
                        $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_id' => 'ASC')));
                        $this->set('distdata', $distdata);
                    }
                    $this->request->data['circle']['district_id'] = $data[0][0]['district_id'];


                    if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                        $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'conditions' => array('district_id' => $data[0][0]['district_id']), 'order' => array('subdivision_id' => 'ASC')));
                        $this->set('subdivisiondata', $subdivisiondata);
                    } else {
                        $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
                        $this->set('subdivisiondata', $subdivisiondata);
                    }
                    $this->request->data['circle']['subdivision_id'] = $data[0][0]['subdivision_id'];


                    if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                        $talukadata1 = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('subdivision_id' => $data[0][0]['subdivision_id']), 'order' => array('taluka_id' => 'ASC')));
                        $this->set('talukadata1', $talukadata1);
                    } else {
                        $talukadata1 = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $data[0][0]['district_id']), 'order' => array('taluka_id' => 'ASC')));
                        $this->set('talukadata1', $talukadata1);
                    }
                    $this->request->data['circle']['taluka_id'] = $data[0][0]['taluka_id'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            // pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function delete_circle($id = null) {
        $this->autoRender = false;
        $this->loadModel('circle');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->circle->circle_id = $id;
                if ($this->circle->delete($id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'circle'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function village($village_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $this->loadModel('circle');
            $this->loadModel('VillageMapping');
            $this->loadModel('corporationclasslist');
            $this->loadModel('Developedlandtype');





            $user_id = $this->Auth->User("user_id");
            $state_id = $this->Auth->User("state_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->set('hfid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $subdivisiondata = '';
            //  $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $this->set('subdivisiondata', $subdivisiondata);
            $talukadata = '';
            $this->set('talukadata', $talukadata);
            $circledata = '';
            $this->set('circledata', $circledata);
            $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $state_id)));
            $this->set('is_div_flag', $is_div_flag);

            if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_id' => 'ASC')));
                $this->set('divisiondata', $divisiondata);
                $distdata = '';
                $this->set('distdata', $distdata);
            } else {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', $distdata);
            }
            $corp_id = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('corp_id', $corp_id);


            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $language = $this->Session->read("sess_langauge");

            $this->set("fieldlist", $fieldlist = $this->VillageMapping->fieldlist($languagelist, $is_div_flag));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            $this->set('talukarecord', NULL);
            $this->set('govbody', NULL);

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $villagedata1 = $this->VillageMapping->query("select * from ngdrstab_conf_admblock7_village_mapping");
            $this->set('villagedata1', $villagedata1);





            if ($this->request->is('post') || $this->request->is('put')) {
                //   pr($this->request->data);
                // exit;
//                if (isset($this->request->data['village']['village_id'])) {
//                    $editact = $this->Session->read('village_id');
//                    if ($this->request->data['village']['village_id'] != $editact) {
//                        return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//                    }
//                }
                $this->request->data['village']['ip_address'] = $this->request->clientIp();
                $this->request->data['village']['created_date'] = $created_date;
                $this->request->data['village']['user_id'] = $user_id;
                //$this->request->data['project']['req_ip'] = $this->request->clientIp();
//pr($this->request->data);exit;
                $verrors = $this->validatedata($this->request->data['village'], $fieldlist);
                // pr($fieldlist);
                // pr($this->request->data);
                // pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->VillageMapping->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['village']);
                    if ($checkd) {
                        if ($this->VillageMapping->save($this->request->data['village'])) {
                            $lastid = $this->VillageMapping->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $actionvalue = 'lblsavemsg';
                            } else {
                                $actionvalue = 'lbleditmsg';
                            }
                            $this->Session->setFlash(__($actionvalue));

                            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'village'));
                        } else {
                            $this->Session->setFlash(__('Project not saved.'));
                        }
                    } else {

                        if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['village']['division_id'])));
                            $this->set('distdata', $distdata);
                        } else {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug)));
                            $this->set('distdata', $distdata);
                        }
                        $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['village']['district_id'])));
                        $this->set('subdivisiondata', $subdivisiondata);

                        if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('subdivision_id' => @$this->request->data['village']['subdivision_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));

                            $this->set('talukadata', $talukadata);
                        } else {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['village']['district_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));
                            $this->set('talukadata', $talukadata);
                        }

                        $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => @$this->request->data['village']['district_id'])));
                        $this->set('govbody', $corp_id);

                        if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                            $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['village']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                            $this->set('circledata', $circledata);
                        }

                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($village_id) && is_numeric($village_id)) {

                $this->Session->write('village_id', $village_id);
                $result = $this->VillageMapping->find("first", array('conditions' => array('village_id' => $village_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['village'] = $result['VillageMapping'];
                    $disitid = $this->request->data['village']['district_id'];
                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('division_id' => $result['VillageMapping']['division_id']), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    } else {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    }
                    $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $language), 'conditions' => array('district_id' => $disitid), 'order' => array('subdivision_name_' . $language => 'ASC')));
                    $this->set('subdivisiondata', $subdivisiondata);
                    if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('subdivision_id' => $result['VillageMapping']['subdivision_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    } else {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('district_id' => $result['VillageMapping']['district_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    }
                    $this->request->data['village']['subdivision_id'] = $result['VillageMapping']['subdivision_id'];
                    $circleid = $this->request->data['village']['circle_id'];
                    $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['village']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                    $this->set('circledata', $circledata);
                    $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $disitid)));
                    $this->set('govbody', $corp_id);
                    $this->request->data['village']['district_id'] = $disitid;
                    if (!empty($districtnname)) {
                        $division_id = $this->District->query("SELECT division_id FROM ngdrstab_conf_admblock3_district where district_id=$disitid");
                        $divid = $division_id[0][0]['division_id'];
                        $this->request->data['village']['division_id'] = $divid;
                    }
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function village_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('VillageMapping');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->VillageMapping->village_id = $id;
                if ($this->VillageMapping->delete($id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'village'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function gettalukadist() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('taluka');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));

                echo json_encode($talukadata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getcircle() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('circle');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                echo json_encode($circledata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgovtbody() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('corporationclasslist');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $corporation = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($corporation);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function developltype($developed_land_types_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $Developedland = $this->Developedlandtype->find('list', array('fields' => array('Developedlandtype.developed_land_types_id', 'Developedlandtype.developed_land_types_desc_' . $laug), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('Developedland', $Developedland);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $landtdata = $this->Developedlandtype->query("select * from ngdrstab_mst_developed_land_types");
            $this->set('landtdata', $landtdata);


            $this->set("fieldlist", $fieldlist = $this->Developedlandtype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['developltype']['ip_address'] = $this->request->clientIp();
                $this->request->data['developltype']['created_date'] = $created_date;
                $this->request->data['developltype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['developltype'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->Developedlandtype->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['developltype']);
                    if ($checkd) {
                        if ($this->Developedlandtype->save($this->request->data['developltype'])) {

                            $lastid = $this->Developedlandtype->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $actionvalue = 'lblsavemsg';
                            } else {
                                $actionvalue = 'lbleditmsg';
                            }
                            $this->Session->setFlash(__($actionvalue));

                            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'developltype'));
                        } else {
                            $this->Session->setFlash(__('Area/Land not saved.'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($developed_land_types_id) && is_numeric($developed_land_types_id)) {

                $this->Session->write('developed_land_types_id', $developed_land_types_id);
                $result = $this->Developedlandtype->find("first", array('conditions' => array('developed_land_types_id' => $developed_land_types_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['developltype'] = $result['Developedlandtype'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //  pr($ex);
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_developltype($developed_land_types_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('Developedlandtype');
        try {

            if (isset($developed_land_types_id) && is_numeric($developed_land_types_id)) {
                //  if ($type = 'subdivision') {
                $this->Developedlandtype->developed_land_types_id = $developed_land_types_id;
                if ($this->Developedlandtype->delete($developed_land_types_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'developltype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function local_governing_body($ulb_type_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('local_governing_body');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('governingbody', $this->local_governing_body->find('all'));

            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['local_governing_body']['req_ip'] = $req_ip;
            $this->request->data['local_governing_body']['user_id'] = $user_id;
            // $this->request->data['local_governing_body']['created_date'] = $created_date;
            $this->request->data['local_governing_body']['state_id'] = $stateid;

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set("fieldlist", $fieldlist = $this->local_governing_body->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['local_governing_body']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $duplicateflag = 'S';
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['local_governing_body']['id'] = $this->request->data['hfid'];
                            $duplicateflag = 'U';
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['local_governing_body'] = $this->istrim($this->request->data['local_governing_body']);
                        $verrors = $this->validatedata($this->request->data['local_governing_body'], $fieldlist);
                        if ($this->ValidationError($verrors)) {
                            $duplicate = $this->local_governing_body->get_duplicate($languagelist);
                            $checkd = $this->check_duplicate($duplicate, $this->request->data['local_governing_body']);
                            if ($checkd) {
                                if ($this->local_governing_body->save($this->request->data['local_governing_body'])) {

                                    $lastid = $this->local_governing_body->getLastInsertId();
                                    if (is_numeric($lastid)) {
                                        $actionvalue = 'lblsavemsg';
                                    } else {
                                        $actionvalue = 'lbleditmsg';
                                    }
                                    $this->Session->setFlash(__($actionvalue));

                                    $this->redirect(array('controller' => 'BlockLevels', 'action' => 'local_governing_body'));
                                    $this->set('unitrecord', $this->local_governing_body->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }
                        } else {
                            $this->Session->setFlash(__('Find validations '));
                        }
                    }
                }
            }



            if (!is_null($ulb_type_id) && is_numeric($ulb_type_id)) {
                $this->Session->write('ulb_type_id', $ulb_type_id);
                $result = $this->local_governing_body->find("first", array('conditions' => array('ulb_type_id' => $ulb_type_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['local_governing_body'] = $result['local_governing_body'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_local_governing_body($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('local_governing_body');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'local_governing_body') {
                $this->local_governing_body->ulb_type_id = $id;
                if ($this->local_governing_body->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'local_governing_body'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function locgovbodylist($corp_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('corporationclasslist');
            $this->loadModel('mainlanguage');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('division');
            $this->loadModel('District');


            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User('state_id');
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $laug), 'order' => array('class_description_en' => 'ASC'))));
            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('adminLevelConfig', $adminLevelConfig);
            $locgovbodylist = $this->corporationclasslist->query("select a.corp_id,a.id,a.ulb_type_id,a.class_type,a.governingbody_name_en,
                            a.governingbody_name_ll,governingbody_name_ll1,governingbody_name_ll2,governingbody_name_ll3,
                            governingbody_name_ll4,b.class_description_$laug 
                            from ngdrstab_conf_admblock_local_governingbody_list a
                            left outer join ngdrstab_conf_admblock_local_governingbody b on b.ulb_type_id=a.ulb_type_id ");
            $this->set('locgovbodylist', $locgovbodylist);

            $this->set("fieldlist", $fieldlist = $this->corporationclasslist->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                $divisiondata = $this->division->find('list', array('fields' => array('division_id', 'division_name_' . $laug), 'order' => array('division_id' => 'ASC')));
                $this->set('divisiondata', $divisiondata);
                $this->set('districtdata', NULL);
            } else {
                $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug)));
                $this->set('districtdata', $distdata);
            }



            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['locgovbodylist']['csrftoken']);
                $this->request->data['locgovbodylist']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['locgovbodylist']['user_id'] = $this->Auth->User('user_id');
                // $this->request->data['locgovbodylist']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['locgovbodylist']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);
//               pr($this->request->data);exit;
                if ($_POST['actiontype'] == '1') {
                    $duplicateflag = 'S';
                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['locgovbodylist']['id'] = $this->request->data['hfid'];
                        $duplicateflag = 'U';
                        $actionvalue = "lbleditmsg";
                    } else {
                        $actionvalue = "lblsavemsg";
                    }
                    $this->request->data['locgovbodylist'] = $this->istrim($this->request->data['locgovbodylist']);
                    $verrors = $this->validatedata($this->request->data['locgovbodylist'], $fieldlist);
                    if ($this->ValidationError($verrors)) {
                        $duplicate = $this->corporationclasslist->get_duplicate($languagelist);
                        $checkd = $this->check_duplicate($duplicate, $this->request->data['locgovbodylist']);
                        if ($checkd) {
                            if ($this->corporationclasslist->save($this->request->data['locgovbodylist'])) {
                                $lastid = $this->corporationclasslist->getLastInsertId();
                                if (is_numeric($lastid)) {
                                    $actionvalue = 'lblsavemsg';
                                } else {
                                    $actionvalue = 'lbleditmsg';
                                }
                                $this->Session->setFlash(__($actionvalue));

                                $this->redirect(array('controller' => 'BlockLevels', 'action' => 'locgovbodylist'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        } else {
                            if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                                $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['locgovbodylist']['division_id']), 'order' => array('district_name_' . $laug => 'ASC')));
                                $this->set('districtdata', $distdata);
                            } else {
                                $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC')));
                                $this->set('districtdata', $distdata);
                            }

                            $this->Session->setFlash(__('lblduplicatemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('Find validations '));
                    }
                }
            }
            if (!is_null($corp_id) && is_numeric($corp_id)) {

                $this->Session->write('corp_id', $corp_id);
                $result = $this->corporationclasslist->find("first", array('conditions' => array('corp_id' => $corp_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');

                    $this->request->data['locgovbodylist'] = $result['corporationclasslist'];
                    if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug),
                            'conditions' => array('division_id' => $result['corporationclasslist']['division_id'])));
                        $this->set('districtdata', $distdata);
                    }
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }

        $this->set_csrf_token();
    }

    public function locgovbodylist_delete($id = null) {
//         pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('corporationclasslist');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'constructiontype') {
                $this->corporationclasslist->corp_id = $id;
                if ($this->corporationclasslist->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'locgovbodylist'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function location_level1($level_1_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $this->loadModel('circle');
            $this->loadModel('VillageMapping');
            $this->loadModel('corporationclasslist');
            $this->loadModel('Developedlandtype');
            $this->loadModel('Level1');
            $user_id = $this->Auth->User("user_id");
            $state_id = $this->Auth->User("state_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->set('hfid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $subdivisiondata = '';
            //  $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $this->set('subdivisiondata', $subdivisiondata);
            $talukadata = '';
            $this->set('talukadata', $talukadata);
            $circledata = '';
            $this->set('circledata', $circledata);
            $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $state_id)));
            $this->set('is_div_flag', $is_div_flag);

            if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_id' => 'ASC')));
                $this->set('divisiondata', $divisiondata);
                $distdata = '';
                $this->set('distdata', $distdata);
            } else {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', $distdata);
            }
            $corp_id = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('corp_id', $corp_id);


            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $language = $this->Session->read("sess_langauge");

            $this->set("fieldlist", $fieldlist = $this->Level1->fieldlist($languagelist, $is_div_flag));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $this->set('talukarecord', NULL);
            $this->set('govbody', NULL);

            $villagedata = null;
            $this->set('villagedata', $villagedata);

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $villagedata1 = $this->Level1->query("select * from ngdrstab_mst_location_levels_1_property");
            $this->set('level1data1', $villagedata1);


            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data['location_level1']['ip_address'] = $this->request->clientIp();
                $this->request->data['location_level1']['created_date'] = $created_date;
                $this->request->data['location_level1']['user_id'] = $user_id;

                $verrors = $this->validatedata($this->request->data['location_level1'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->Level1->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['location_level1']);
                    if ($checkd) {
                        if ($this->Level1->save($this->request->data['location_level1'])) {
                            $this->Session->setFlash(__('lblsavemsg'));
                            return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'location_level1'));
                            $lastid = $this->Level1->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('Project not saved.'));
                        }
                    } else {

                        if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['location_level1']['division_id'])));
                            $this->set('distdata', $distdata);
                        } else {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug)));
                            $this->set('distdata', $distdata);
                        }
                        $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['location_level1']['district_id'])));
                        $this->set('subdivisiondata', $subdivisiondata);

                        if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('subdivision_id' => @$this->request->data['location_level1']['subdivision_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));

                            $this->set('talukadata', $talukadata);
                        } else {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['location_level1']['district_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));
                            $this->set('talukadata', $talukadata);
                        }
                        //  _____________village____________________

                        if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                            $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $laug), 'conditions' => array('taluka_id' => @$this->request->data['location_level1']['taluka_id']), 'order' => array('village_name_' . $laug => 'ASC')));

                            $this->set('villagedata', $villagedata);
                        } else {
                            $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $laug), 'conditions' => array('circle_id' => @$this->request->data['location_level1']['corcle_id']), 'order' => array('village_name_' . $laug => 'ASC')));
                            $this->set('villagedata', $villagedata);
                        }

                        //________________end_________________

                        $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => @$this->request->data['location_level1']['district_id'])));
                        $this->set('govbody', $corp_id);

                        if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                            $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['location_level1']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                            $this->set('circledata', $circledata);
                        }

                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($level_1_id) && is_numeric($level_1_id)) {

                $this->Session->write('level_1_id', $level_1_id);
                $result = $this->Level1->find("first", array('conditions' => array('level_1_id' => $level_1_id)));
//                pr($result);exit;
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['location_level1'] = $result['Level1'];
                    $disitid = $this->request->data['location_level1']['district_id'];

                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('division_id' => $result['Level1']['division_id']), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    } else {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    }

                    $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $language), 'conditions' => array('district_id' => $disitid), 'order' => array('subdivision_name_' . $language => 'ASC')));
                    $this->set('subdivisiondata', $subdivisiondata);


                    if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('subdivision_id' => $result['Level1']['subdivision_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    } else {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('district_id' => $result['Level1']['district_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    }
//                     pr($talukadata);exit;

                    $circleid = $this->request->data['location_level1']['circle_id'];
                    $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['location_level1']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                    $this->set('circledata', $circledata);


                    if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                        $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $language), 'conditions' => array('circle_id' => $result['Level1']['circle_id']), 'order' => array('village_name_' . $language => 'ASC')));
                        $this->set('villagedata', $villagedata);
                    } else {
                        $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $language), 'conditions' => array('taluka_id' => $result['Level1']['taluka_id']), 'order' => array('village_name_' . $language => 'ASC')));
                        $this->set('villagedata', $villagedata);
                    }

                    $this->request->data['location_level1']['subdivision_id'] = $result['Level1']['subdivision_id'];


                    $this->request->data['location_level1']['district_id'] = $disitid;
                    if (!empty($districtnname)) {
                        $division_id = $this->District->query("SELECT division_id FROM ngdrstab_conf_admblock3_district where district_id=$disitid");
                        $divid = $division_id[0][0]['division_id'];
                        $this->request->data['location_level1']['division_id'] = $divid;
                    }
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function location_level1_delete($level_1_id = null) {
        $this->autoRender = false;
        $this->loadModel('Level1');
        try {
            if (isset($level_1_id) && is_numeric($level_1_id)) {
                $this->Level1->level_1_id = $level_1_id;
                if ($this->Level1->delete($level_1_id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('controller' => 'BlockLevels', 'action' => 'location_level1'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function getvillage() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('VillageMapping');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            // pr($data);exit;

            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                echo json_encode($villagedata);
                exit;
            } else {
                if (isset($data['circle_id']) && is_numeric($data['circle_id'])) {
                    $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $data['circle_id'])));
                    echo json_encode($villagedata);
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function loclevellist1($levellist_1_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $this->loadModel('circle');
            $this->loadModel('VillageMapping');
            $this->loadModel('corporationclasslist');
            $this->loadModel('Developedlandtype');
            $this->loadModel('Level1');
            $this->loadModel('loc_level_1_prop_list');
            $user_id = $this->Auth->User("user_id");
            $state_id = $this->Auth->User("state_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->set('hfid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $subdivisiondata = '';
            //  $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $this->set('subdivisiondata', $subdivisiondata);
            $talukadata = '';
            $this->set('talukadata', $talukadata);
            $circledata = '';
            $this->set('circledata', $circledata);
            $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $state_id)));
            $this->set('is_div_flag', $is_div_flag);
            $level1data = $this->Level1->find('list', array('fields' => array('level_1_id', 'level_1_desc_' . $lang), 'order' => array('level_1_id' => 'ASC')));
            $this->set('level1data', $level1data);


            if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_id' => 'ASC')));
                $this->set('divisiondata', $divisiondata);
                $distdata = '';
                $this->set('distdata', $distdata);
            } else {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', $distdata);
            }
            $corp_id = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('corp_id', $corp_id);


            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $language = $this->Session->read("sess_langauge");

            $this->set("fieldlist", $fieldlist = $this->loc_level_1_prop_list->fieldlist($languagelist, $is_div_flag));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $this->set('talukarecord', NULL);
            $this->set('govbody', NULL);

            $villagedata = null;
            $this->set('villagedata', $villagedata);

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $loclevellist1data = $this->loc_level_1_prop_list->query("select levellist1.prop_level1_list_id,village.village_name_en,level1.level_1_desc_en,levellist1.list_1_desc_en,levellist1.list_1_desc_ll from ngdrstab_mst_loc_level_1_prop_list as levellist1 
                                                                        inner join ngdrstab_mst_location_levels_1_property as level1 on levellist1.level_1_id=level1.level_1_id
                                                                        inner join ngdrstab_conf_admblock7_village_mapping as village on village.village_id = levellist1.village_id ");
            $this->set('loclevellist1data', $loclevellist1data);

              if (!empty($levellist_1_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data['loclevellist1']['ip_address'] = $this->request->clientIp();
                $this->request->data['loclevellist1']['created_date'] = $created_date;
                $this->request->data['loclevellist1']['user_id'] = $user_id;

                $verrors = $this->validatedata($this->request->data['loclevellist1'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->loc_level_1_prop_list->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['loclevellist1']);
                    if ($checkd) {
                        if ($this->loc_level_1_prop_list->save($this->request->data['loclevellist1'])) {
                            $this->Session->setFlash(__($actionvalue));
                            return $this->redirect(array('action' => 'loclevellist1'));
                            $lastid = $this->loc_level_1_prop_list->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {

                        if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'conditions' => array('division_id' => @$this->request->data['loclevellist1']['division_id'])));
                            $this->set('distdata', $distdata);
                        } else {
                            $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug)));
                            $this->set('distdata', $distdata);
                        }
                        $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['loclevellist1']['district_id'])));
                        $this->set('subdivisiondata', $subdivisiondata);

                        if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('subdivision_id' => @$this->request->data['loclevellist1']['subdivision_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));

                            $this->set('talukadata', $talukadata);
                        } else {
                            $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => @$this->request->data['loclevellist1']['district_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));
                            $this->set('talukadata', $talukadata);
                        }
                        //  _____________village____________________

                        if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {

                            $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $laug), 'conditions' => array('circle_id' => @$this->request->data['loclevellist1']['corcle_id']), 'order' => array('village_name_' . $laug => 'ASC')));
                            $this->set('villagedata', $villagedata);
                        } else {
                            $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $laug), 'conditions' => array('taluka_id' => @$this->request->data['loclevellist1']['taluka_id']), 'order' => array('village_name_' . $laug => 'ASC')));

                            $this->set('villagedata', $villagedata);
                        }

                        //________________end_________________

                        $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => @$this->request->data['loclevellist1']['district_id'])));
                        $this->set('govbody', $corp_id);

                        if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                            $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['loclevellist1']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                            $this->set('circledata', $circledata);
                        }

                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($levellist_1_id) && is_numeric($levellist_1_id)) {

                $this->Session->write('prop_level1_list_id', $levellist_1_id);
                $result = $this->loc_level_1_prop_list->find("first", array('conditions' => array('prop_level1_list_id' => $levellist_1_id)));
                $this->set('result', $result);
                //pr($result);
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['loclevellist1'] = $result['loc_level_1_prop_list'];
                    $disitid = $this->request->data['loclevellist1']['district_id'];

                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('division_id' => $result['loc_level_1_prop_list']['division_id']), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    } else {
                        $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC')));
                        $this->set('distdata', $distdata);
                    }

                    $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $language), 'conditions' => array('district_id' => $disitid), 'order' => array('subdivision_name_' . $language => 'ASC')));
                    $this->set('subdivisiondata', $subdivisiondata);


                    if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('subdivision_id' => $result['loc_level_1_prop_list']['subdivision_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    } else {
                        $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $language), 'conditions' => array('district_id' => $result['loc_level_1_prop_list']['district_id']), 'order' => array('taluka_name_' . $language => 'ASC')));
                        $this->set('talukadata', $talukadata);
                    }
//                     pr($talukadata);exit;

                    $circleid = $this->request->data['loclevellist1']['circle_id'];
                    $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $language), 'conditions' => array('taluka_id' => $this->request->data['loclevellist1']['taluka_id']), 'order' => array('circle_name_' . $language => 'ASC')));
                    $this->set('circledata', $circledata);


                    if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                        $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $language), 'conditions' => array('circle_id' => $result['loc_level_1_prop_list']['circle_id']), 'order' => array('village_name_' . $language => 'ASC')));
                        $this->set('villagedata', $villagedata);
                    } else {
                        $villagedata = $this->VillageMapping->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $language), 'conditions' => array('taluak_id' => $result['loc_level_1_prop_list']['taluak_id']), 'order' => array('village_name_' . $language => 'ASC')));
                        $this->set('villagedata', $villagedata);
                    }

                    $this->request->data['loclevellist1']['subdivision_id'] = $result['loc_level_1_prop_list']['subdivision_id'];



//                    $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $disitid)));
//                    $this->set('govbody', $corp_id);

                    $this->request->data['loclevellist1']['district_id'] = $disitid;
                    if (!empty($districtnname)) {
                        $division_id = $this->District->query("SELECT division_id FROM ngdrstab_conf_admblock3_district where district_id=$disitid");
                        $divid = $division_id[0][0]['division_id'];
                        $this->request->data['loclevellist1']['division_id'] = $divid;
                        $this->Session->setFlash(
                            __($actionvalue)
                    );
                        
                    }
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function loclevellist1_delete($levellist_1_id = null) {
        $this->autoRender = false;
        $this->loadModel('loc_level_1_prop_list');
        try {
            if (isset($levellist_1_id) && is_numeric($levellist_1_id)) {
                $this->loc_level_1_prop_list->prop_level1_list_id = $levellist_1_id;
                if ($this->loc_level_1_prop_list->delete($levellist_1_id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('action' => 'loclevellist1'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function getvillagelist1() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('VillageMapping');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;



            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                echo json_encode($villagedata);
                exit;
            } else {
                if (isset($data['circle_id']) && is_numeric($data['circle_id'])) {
                    $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $data['circle_id'])));
                    echo json_encode($villagedata);
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
