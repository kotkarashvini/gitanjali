<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'Fees'); // mention at top
App::import('Controller', 'Property'); // mention at top
App::import('Controller', 'DynamicVariables'); // mention at top
App::import('Controller', 'WebService');
App::import('Controller', 'PBWebService'); // mention at top

class CitizenentryController extends FeesController {

    //put your code here
    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha', 'Cookie',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Citizenentry', 'action' => 'welcome'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'welcomenote'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authorize' => array('Controller')
    ));
//    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {

        $this->loadModel('language');
        $this->Session->renew();
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
//        $this->Security->unlockedActions = array('get_adj_doc_exess_amt', 'setdoc_lang', 'update_sd', 'leaseandlicense', 'appointment', 'getarticlefield', 'genernal_info', 'genernalinfoentry', 'usagecategory_change_event', 'property_details', 'getattributeupdate', 'valuation_entry', 'party_entry', 'taluka_change_event', 'getusagevisibilitynew', 'rulechangeevent', 'getattributeparameter', 'add_property_attribute', 'witness', 'behavioral_patterns', 'article_change_event', 'check_appointmentdate', 'slot_alocation', 'get_time_difference', 'tatkalappoinment', 'tatkal_slot_alocation', 'check_maxappointmentday', 'getdependent_article', 'get_7_12_record', 'check_filevalidation', 'upload_document', 'article_mapping_screen', 'get_valuation_amt', 'getarticledepfeild', 'check_prohibited_prop', 'stamp_duty', 'check_config_prohibition', 'check_execution_date', 'party_address', 'get_instrument', 'get_fees_falc_ids', 'payment', 'get_payment_details', 'final_submit', 'check_land_record_fetching', 'check_7_12_compulsary', 'get_adj_doc_exess_amt_detail', 'is_uid_compulsary', 'is_identity_compulsary', 'identification', 'multiple_property_allowed', 'check_property_count');
//        $this->Auth->allow('welcomenote', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration');
        //$this->Security->unlockedActions = array('get_valuation_id', 'get_party_flag', 'get_adj_doc_exess_amt', 'citizenlogin', 'logout', 'setdoc_lang', 'update_sd', 'leaseandlicense', 'appointment', 'getarticlefield', 'genernal_info', 'genernalinfoentry', 'usagecategory_change_event', 'property_details', 'getattributeupdate', 'valuation_entry', 'party_entry', 'taluka_change_event', 'getusagevisibilitynew', 'rulechangeevent', 'getattributeparameter', 'add_property_attribute', 'witness', 'behavioral_patterns', 'article_change_event', 'check_appointmentdate', 'slot_alocation', 'get_time_difference', 'tatkalappoinment', 'tatkal_slot_alocation', 'check_maxappointmentday', 'getdependent_article', 'get_7_12_record', 'check_filevalidation', 'upload_document', 'article_mapping_screen', 'get_valuation_amt', 'getarticledepfeild', 'check_prohibited_prop', 'stamp_duty', 'check_config_prohibition', 'check_execution_date', 'party_address', 'get_instrument', 'get_fees_falc_ids', 'payment', 'get_payment_details', 'final_submit', 'check_land_record_fetching', 'check_7_12_compulsary', 'get_adj_doc_exess_amt_detail', 'is_uid_compulsary', 'is_identity_compulsary', 'identification', 'multiple_property_allowed', 'check_property_count', 'check_presenter', 'set_presenter', 'is_party_ekyc_auth_compusory', 'party_ekyc_authentication', 'is_party_ekyc_done', 'cancel_appointment', 'set_common_fields', 'validatesurveynumbers', 'taluka_change_event', 'district_change_event', 'get_title', 'set_token_session', 'delete_session', 'document_search', 'get_party_byname', 'payment_webservice', 'search_partyname', 'check_attribute_subpart', 'certificatesissuedetails', 'get_validation_rule', 'delete_party', 'get_party_feilds', 'get_record_old_party', 'advoate_feild_require_flag', 'delete_identifire', 'delete_witness');
        $this->Auth->allow('check_female_exemption', 'forgotpassword_citizen', 'welcomenote', 'citizenlogin', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'district_change_event','otpsavecitizen');
        $laug = $this->Session->read("sess_langauge");

        if (is_null($laug)) {
            $this->Session->write("sess_langauge", 'en');
        }

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function way_to_skipvalution($csrftoken = NULL, $prop_id = NULL, $token_no = NULL) {
        $this->Session->write('token_no', $token_no);
        return $this->redirect(array('action' => 'skip_property_details', $this->Session->read('csrftoken'), $prop_id));
    }

    public function skip_property_details($csrftoken = NULL, $prop_id = NULL) {
        try {

            $last_status_id = $this->Session->read('last_status_id');

//            if (!is_numeric($this->Session->read('Selectedtoken'))) {
//                $this->Session->setFlash("Kindly complete general info tab then proceed further");
//                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
//            }
// Load Model
            array_map(array($this, 'loadModel'), array('mainlanguage', 'property_details_entry', 'stamp_duty', 'regconfig', 'attribute_parameter', 'articaledepfields', 'articletrnfields', 'parameter', 'items_parameter', 'TrnBehavioralPatterns', 'article_screen_mapping'));
// Declere Variable
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $Selectedtoken = $token = $this->Session->read("token_no");
            $generalinfodata = $this->article_screen_mapping->query("select * from ngdrstab_trn_generalinformation where token_no=$Selectedtoken");
            $this->Session->write('citizen_user_id', $generalinfodata[0][0]['user_id']);
            $this->Session->write('article_id', $generalinfodata[0][0]['article_id']);
            $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $generalinfodata[0][0]['local_language_id'])));
            if ($language) {
                if ($language['0']['mainlanguage']['language_code'] == 'en') {
                    $this->Session->write('doc_lang', 'en');
                } else {
                    $this->Session->write('doc_lang', 'll');
                }
            }

            $doc_lang = $this->Session->read('doc_lang');


            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 33)));
            //  $prop_boundries=$this->property_boundries_flag();
            $regval = $regconfig['regconfig']['conf_bool_value'];
            $this->set(compact('lang', 'doc_lang', 'Selectedtoken', 'regval'));
            $result = $this->article_screen_mapping->find("all", array('conditions' => array('article_id' => $generalinfodata[0][0]['article_id'], 'minorfun_id' => 2)));
            if (empty($result)) {
                return $this->redirect(array('action' => 'party_entry', $this->Session->read('csrftoken'))); // screen no avalable to article
            }

// Load data to json file and set variable for ctp
            $json2array = $this->load_json_file();
//---------------------------------EDIT EDIT---------------------------------------------------------------------------------------------  
            if (!$this->request->is('post') and is_numeric($prop_id)) {
                $json2array1 = $this->edit_propertydetails($prop_id);
                if (isset($totaldependency['hfboundaryflag']) && $totaldependency['hfboundaryflag'] == 'Y') {
                    $this->set('hfboundaryflag', 'Y');
                }
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array1));
                $file->close();
            }
//----------------------------------END EDIT-----------------------------------------------------------------------------------------------------    


            $property_list = $this->property_details_entry->get_property_detail_list_edit($lang, $token, $user_id);
            $this->set('property_list', $property_list);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $token, $user_id);
            $this->set('property_pattern', $property_pattern);


            if ($this->request->is('post') || $this->request->is('put')) {
                // $this->check_csrf_token($this->request->data['propertyscreennew']['csrftoken'], 'P');
                $rulelist = NULL;
                if (isset($this->request->data['propertyscreennew']['usage_cat_id'])) {
                    $rulelist = $this->request->data['propertyscreennew']['usage_cat_id'];
                }
                // pr($this->request->data);exit;
                // pr($rulelist);
                // pr($this->request->data['propertyscreennew']['village_id']);
                // exit;
                //   $fielslistaddress = $this->BehavioralPattens->fieldlist(1,$doc_lang,$rulelist,@$this->request->data['propertyscreennew']['village_id']);
                //   $fieldlist_citizen = $this->valuation->fieldlist_citizen($doc_lang,$lang,$rulelist,$regval);
                //   $fielslistaddress = array_merge($fieldlist_citizen, $fielslistaddress); 

                $fieldlist = $this->valuation->fieldlist($rulelist);
                //   $fieldlist = array_merge($fieldlist, $fielslistaddress); 
                $fieldlist = $this->modifyfieldlist($fieldlist, $this->request->data['propertyscreennew']);
                $requestdata = $this->request->data['property_details'];
                if (isset($requestdata['pattern_id'])) {
                    foreach ($requestdata['pattern_id'] as $key => $singlefield) {
                        $this->request->data['propertyscreennew']['field_en' . $singlefield] = $requestdata['pattern_value_en'][$key];
                        $this->request->data['propertyscreennew']['field_ll' . $singlefield] = @$requestdata['pattern_value_ll'][$key];
                    }
                }

                $errors = $this->validatedata($this->request->data['propertyscreennew'], $fieldlist);
//pr($errors);exit;
                if ($this->ValidationError($errors)) {
                    if (isset($this->request->data['propertyscreennew']['corp_id']) && !is_numeric($this->request->data['propertyscreennew']['corp_id'])) {
                        $this->request->data['propertyscreennew']['corp_id'] = '';
                    }

                    if (!is_numeric($this->request->data['propertyscreennew']['taluka_id'])) {
                        $this->request->data['propertyscreennew']['taluka_id'] = '';
                    }
                    if (isset($this->request->data['propertyscreennew']['level1_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                        $this->request->data['propertyscreennew']['level1_id'] = NULL;
                    }
                    if (isset($this->request->data['propertyscreennew']['level1_list_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_list_id'])) {
                        $this->request->data['propertyscreennew']['level1_list_id'] = NULL;
                    }

                    $this->post_back_valuation_data($json2array);
                    $unique_record_id = '';
                    if (isset($this->request->data['propertyscreennew']['property_id']) and $this->request->data['propertyscreennew']['property_id'] == $this->Session->read('prop_edit_id')) {
                        $unique_record_id = $this->Session->read('prop_edit_id');
                    }
                    $this->request->data['propertyscreennew']['token_no'] = $token;
                    $this->request->data['propertyscreennew']['user_id'] = $user_id;
                    $this->request->data['propertyscreennew']['token_process_by_tah_flag'] = "Y";
                    $this->request->data['propertyscreennew']['token_process_by_tah_date'] = date('Y/m/d H:i:s');
                    $this->property_details_entry->create();
                    $this->request->data['propertyscreennew']['property_id'] = $unique_record_id;

                    $last_prop_id = $unique_record_id;
                    $this->request->data['propertyscreennew']['user_type'] = $this->Session->read("session_usertype");

                    // pr($this->request->data['propertyscreennew']);exit;
                    if ($this->property_details_entry->save($this->request->data['propertyscreennew'])) {
                        $last_prop_id = $this->property_details_entry->getLastInsertID();

                        if (empty($last_prop_id)) {  // ON update Null
                            $last_prop_id = $unique_record_id;
                        }

                        if (isset($this->request->data['propertyscreennew']['usage_cat_id']) and ! empty($this->request->data['propertyscreennew']['usage_cat_id'])) {
// SET DELETE FLAG FOR VALUATION                  
                            $this->valuation->query("UPDATE ngdrstab_trn_valuation SET delete_flag='Y' WHERE property_id=$last_prop_id AND token_no=$token AND user_id= $user_id");
                            $this->request->data['propertyscreennew']['property_id'] = $last_prop_id;
                            $this->request->data['propertyscreennew']['token_no'] = $token;
                            unset($this->request->data['propertyscreennew']['property_id']); // other wise updates valuation Records
                            $result = $this->property_valuation();
                            $val_id = is_numeric($result) ? $this->request->data['propertyscreennew']['val_id'] : 0;
                            $this->property_details_entry->query("UPDATE ngdrstab_trn_property_details_entry  SET val_id=$val_id WHERE property_id=$last_prop_id AND token_no=$token AND user_id= $user_id ");
                        }

// Usage Items Entry (for Multiple usage and multiple item)
                        foreach ($json2array['usageitemlist'] as $usageitem) {
                            $this->items_parameter->save_item_prameter($this->request->data['propertyscreennew'], $usageitem, $last_prop_id, $token, $user_id, $this->Session->read("session_usertype"));
                        }
//  For Attribute Entry
// ( Delete   Existing Entry For Edit)
                        $this->parameter->deleteAll(['property_id' => $last_prop_id, 'token_id' => $token]);

                        if (isset($json2array['prop_attributes_seller'])) {

                            $this->parameter->save_parameter($json2array['prop_attributes_seller'], $last_prop_id, $token, $user_id, 'S', $this->Session->read("session_usertype"));
                        }
                        if ($regval == 'Y') {
                            if (isset($json2array['prop_attributes_pur'])) {
                                $this->parameter->save_parameter($json2array['prop_attributes_pur'], $last_prop_id, $token, $user_id, 'P', $this->Session->read("session_usertype"));
                            }
                        }

//  For Behavioral Pattens Entry //( Delete   Existing Entry For Edit)
                        $this->TrnBehavioralPatterns->deletepattern($token, $user_id, $last_prop_id, 1);
                        if (isset($this->request->data['property_details']['pattern_id'])) {
                            $this->TrnBehavioralPatterns->savepattern($token, $user_id, $last_prop_id, $this->request->data['property_details'], 1, $this->Session->read("session_usertype"));
                        }
                        $this->stamp_duty->updateAll(array('stamp_duty.recalculate_flag' => "'Y'"), //fields to update
                                array('stamp_duty.token_no' => $Selectedtoken)  //condition
                        );
                        $this->Session->setFlash(("Record Saved Succefully"));
                    } else {
                        $this->Session->setFlash(("Record Not Saved"));
                    }
                } else {
                    $this->Session->setFlash('Check Validation  Error!');
                }
//$this->Session->setFlash(($this->Session->read('csrftoken')));
                $this->set_csrf_token();
                $this->redirect(array('action' => 'skip_property_details', $this->Session->read('csrftoken')));
            } else {
                $fieldlistmultiform['propertyscreennew'] = $this->valuation->fieldlist();
                $fielslistaddress = $this->BehavioralPattens->fieldlist(1, $doc_lang);
                $fieldlist_citizen = $this->valuation->fieldlist_citizen($doc_lang, $lang, NULL, $regval);
                $fielslistaddress = array_merge($fieldlist_citizen, $fielslistaddress);
                $fieldlistmultiform['propertyscreennew'] = array_merge($fieldlistmultiform['propertyscreennew'], $fielslistaddress);
                $fieldlistmultiform['copyvaluation']['valuation_id']['text'] = 'is_required,is_numeric';
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function tehsiloperation() {
        try {
            $this->loadModel('office');
            $this->set('hfid', NULL);
            $this->set('actiontype', NULL);
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $alldocuments = $this->office->query("select distinct a.token_no,e.property_id
                                                    from ngdrstab_trn_generalinformation a
                                                    inner join ngdrstab_trn_valuation b on a.token_no = b.token_no
                                                    inner join ngdrstab_trn_valuation_details c on b.val_id = c.val_id
                                                    inner join ngdrstab_mst_evalrule_new d on c.rule_id = d.evalrule_id
                                                    inner join ngdrstab_trn_property_details_entry e on e.token_no = a.token_no
                                                    where d.tah_process_flag = 'Y' and e.token_process_by_tah_flag = 'N'");
            $this->set('alldocuments', $alldocuments);
        } catch (Exception $ex) {
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function citizenlogin() {
        try {

            $userid_random1 = rand(1000, 9999);
            $userid_random2 = rand(1000, 9999);
            $roleid_random1 = rand(1000, 9999);
            $roleid_random2 = rand(1000, 9999);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('mainlanguage');
            $this->loadModel('loginstatus_citizen');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['username']['text'] = 'is_required,is_username';
            $fieldlist['password']['text'] = 'is_required';
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $fieldlist['csrftoken']['text'] = 'is_integer';
//            $fieldlist['hfSaltedStr']['text'] = 'is_integer';

            $this->loadModel('smsevent');


            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 4)));
            if (!empty($event)) {
                if ($event[0]['smsevent']['send_flag'] == 'Y') {

                    $otp = NULL;
                } else {
                    $otp = 12345678;
                }
            }
            $this->set('otp', $otp);

            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false && strpos($this->referer(), 'temp_db') == false) {
                    Cache::clear();
                    clearCache();
                    header('Location:../cterror.html');
                    exit;
                }
            }
            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            if ($this->Session->read("session_user_id") != '') {
                $this->loadModel('User');
                $this->User->useTable = 'ngdrstab_mst_user_citizen';
                $userid = $this->Session->read("session_user_id");
                $result = substr($userid, 4);
                $userid = substr($result, 0, -4);
                $this->User->updateLoginDetails($userid);
                $this->Session->destroy();
                $this->Session->delete('Controller.sessKey');
                $this->redirect($this->Auth->logout());
            } else {
                $this->loadModel('User');
                $this->User->useTable = 'ngdrstab_mst_user_citizen';
                $this->User->create();

                if ($this->request->is('post')) {
                    $mysalt = $this->Session->read("mysalt");

                    if (strcmp($this->request->data['User']['hfSaltedStr'], $mysalt) != 0) {
                        $this->Session->setFlash(__('Authentication Failed1'));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                    }
                }


                $saltNew = Security::saltNew();
                @$saltstr = Sanitize::html($this->request->data['User']['hfSaltedStr']);
                $this->Session->write("session_salt", @$saltstr);
                $this->Session->write("mysalt", $saltNew);
                $this->set('saltstring', $saltNew);


                $captcha = $this->Session->read('captcha_code');
                @$logincount = Sanitize::html($this->request->data['User']['hfLoginCount']);
                if ($logincount == NULL) {
                    $logincount = 0;
                    $this->set('logincount1', $logincount);
                }
                if ($this->request->is('post') && isset($this->request->data['User']['csrftoken'])) {
                    $this->check_csrf_token($this->request->data['User']['csrftoken']);

//                  
                    //vishal login restrict
                    $user12 = $this->User->find('first', array('conditions' => array('User.username' => array($this->request->data['User']['username']))));
                    if (!empty($user12)) {
//                        if ($user12['User']['deed_writer'] == 'Y') {
//
//                            if ($this->checkdeedwriterlogin() > $this->get_deedwriter_logincount()) {
//                                $this->Session->setFlash(__('Please Try Later...!!!'));
//                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
//                            }
//                        } if ($user12['User']['is_advocate'] == 'Y') {
//                            //new function
//                            if ($this->checkadvocatelogin() > $this->get_advocate_logincount()) {
//                                $this->Session->setFlash(__('Please Try Later...!!!'));
//                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
//                            }
//                            
//                        }else {
//                            if ($this->checkcitizenlogin() > $this->get_citizen_logincount()) {
//                                $this->Session->setFlash(__('Please Try Later...!!!'));
//                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
//                            }
//                        }
//                    }

                        if ($user12['User']['deed_writer'] == 'Y') {

                            $this->loadModel('regconfig');
                            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 56)));
//                               pr($regconfig['regconfig']['conf_bool_value']);exit;
                            if ($regconfig['regconfig']['conf_bool_value'] == 'N') {
                                $this->Session->setFlash(__('Please Try Later...!!!'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                if ($this->get_deedwriter_logincount() != NULL) {
                                    if ($this->checkdeedwriterlogin() >= $this->get_deedwriter_logincount()) {
                                        $this->Session->setFlash(__('Please Try Later...!!!'));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                    }
                                }
                            }
                        } else if ($user12['User']['is_advocate'] == 'Y') {
                            $this->loadModel('regconfig');
                            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 60)));
                            //new function
                            if ($regconfig['regconfig']['conf_bool_value'] == 'N') {
                                $this->Session->setFlash(__('Please Try Later...!!!'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                if ($this->get_advocate_logincount() != NULL) {
                                    if ($this->checkadvocatelogin() >= $this->get_advocate_logincount()) {
                                        $this->Session->setFlash(__('Please Try Later...!!!'));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                    }
                                }
                            }
                        } else {
                            $this->loadModel('regconfig');
                            $this->loadModel('CitizenUser');

                            $role_result = $this->CitizenUser->query("select role_id from ngdrstab_mst_user_citizen where username=?", array($this->request->data['User']['username']));
                            $role_id = $role_result[0][0]['role_id'];
                            if ($role_id != 101) {

                                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 54)));
                                if ($regconfig['regconfig']['conf_bool_value'] == 'N') {

                                    $this->Session->setFlash(__('Please Try Later...!!!'));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                } else {
                                    if ($this->get_citizen_logincount() != NULL) {
                                        if ($this->checkcitizenlogin() >= $this->get_citizen_logincount()) {
                                            $this->Session->setFlash(__('Please Try Later...!!!'));
                                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //end
                    $this->request->data['User'] = $this->istrim($this->request->data['User']);
                    //  $fieldlist['password']['text'] = 'is_required,is_pass';
                    $errarr = $this->validatedata($this->request->data['User'], $fieldlist);
                    $flag = 0;
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        $this->set("errarr", $errarr);
                    } else {
                        $this->LoadModel('otpcitizen');
                        $textotp = $this->request->data['User']['otp'];
                        $textusername = $this->request->data['User']['username'];

                        $user_name = $this->request->data['User']['username'];
                        $password123 = $this->request->data['User']['password'];
                        $data2 = array('username' => $user_name, 'password' => $password123);
                        $results = $this->User->find('first', array('conditions' => array('User.username' => array($user_name))));

                        if ($results != NULL) {

                            $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));

                            if (empty($lastotp)) {
                                $this->Session->setFlash(__('Please Genrate OTP!!!'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            }
                            if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                                $this->Session->setFlash(__('OTP Does Not Match...'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            }
                        } else {
                            $this->Session->setFlash(__('Authentication Failed'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                        }

//                        $user_name = $this->request->data['User']['username'];
//                        $password123 = $this->request->data['User']['password'];
//                        $data2 = array('username' => $user_name, 'password' => $password123);
//                        $results = $this->User->find('first', array('conditions' => array('User.username' => array($user_name))));

                        if ($results != NULL) {
                            if ($results['User']['deed_writer'] == 'Y') {
                                if ($results['User']['deed_write_accept_flag'] == 'N') {
                                    $this->Session->setFlash(__('Wait for SRO Aprroval'));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                }
                            }
                            if ($results['User']['is_advocate'] == 'Y') {
                                if ($results['User']['is_advocate_accept_flag'] == 'N') {
                                    $this->Session->setFlash(__('Wait for SRO Aprroval'));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                }
                            }
                        } else {
                            $this->Session->setFlash(__('Authentication Failed'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                        }

                        //vishal login ristrict
                        if ($results['User']['deed_writer'] == 'Y') {
                            $this->Session->write("deed_writer", "Y");
                            $logintype = 'D';
                        } else if ($results['User']['is_advocate'] == 'Y') {
                            $logintype = 'A';
                            $this->Session->write("is_advocate", "Y");
                        } else {
                            $logintype = 'C';
                        }
                        //vishal login ristrict
//                        if ($results == NULL) {
//                            $this->Session->setFlash(__('Authentication Failed'));
//                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
//                        }
                        @$login_flag = $results['User']['first_time_pwd'];
                        @$user_id = $results['User']['user_id'];
                        $logincount = ++$logincount;
                        if ($results['User']['activeflag'] == 'N') {
                            $this->Session->setFlash(__('User Deactivate'));
                        } else {
                            if ($captcha == $this->request->data['User']['captcha']) {
                                if ($logincount <= 3) { // echo 11;exit;
                                    if ($this->Auth->login()) {
                                        //  echo 'hi';exit;
                                        // pr($results);exit;
                                        $this->load_alert_msgs();
                                        $citizen = 'C';
                                        $this->Session->write("session_salt", NULL);
                                        $this->Session->write("session_usertype", @$citizen);
//                              $a = $this->Session->read("session_citizen");
                                        // echo $a;
                                        //  exit;
//                                                echo 11;exit;
                                        $this->Session->renew();
                                        $_SESSION["token"] = md5(uniqid(mt_rand(), true));
                                        Cache::clear();
                                        clearCache();
                                        $session_userid = $userid_random1 . $user_id . $userid_random2;
                                        $this->Session->write("session_user_id", $session_userid);
                                        $userid = $this->Session->read("session_user_id");

                                        $result = substr($userid, 4);
                                        $userid = substr($result, 0, -4);
                                        $session_id = $this->Auth->User('id');
                                        $this->loadModel('getUserRolecitizen');
                                        $UserRole = $this->getUserRolecitizen->find('first', array('conditions' => array('getUserRolecitizen.user_id' => $userid)));
                                        @$role_id = $UserRole['getUserRolecitizen']['role_id'];
                                        $session_roleid = $roleid_random1 . @$role_id . $roleid_random2;
                                        $this->Session->write("session_role_id", $session_roleid);
                                        $this->Session->write("session_id", $session_id);
                                        $this->Cookie->write('userid', $userid);
                                        $this->Session->write('doc_lang', 'en');
                                        $this->Session->write('sess_langauge', 'en');
                                        //Module ID
                                        @$module_id = $results['User']['module_id'];
                                        $this->Session->write("session_module_id", @$module_id);
                                        if ($results['User']['deed_writer'] == 'Y') {
                                            $this->Session->write("deed_writer", "Y");
                                            $role_id = 2;
                                        } else if ($results['User']['deed_writer'] == 'N') {
                                            $role_id = 1;
                                            $this->Session->write("deed_writer", "N");
                                        }

                                        if ($results['User']['is_advocate'] == 'Y') {
                                            $this->Session->write("is_advocate", "Y");
                                            $role_id = 2;
                                        } else if ($results['User']['is_advocate'] == 'N') {
                                            $role_id = 1;
                                            $this->Session->write("is_advocate", "N");
                                        }




                                        //pr($logintype);
                                        //vishal login restrict
                                        $this->loadModel('CitizenUser');
                                        $this->CitizenUser->insertLoginDetails($this->Auth->user('user_id'), $role_id, $logintype);
                                        //login restrict  end
                                        $this->Session->write("sess_lan_both", 'N');
                                        $this->loadModel('language');
                                        $this->loadModel('mainlanguage');
                                        $local_langauge = $this->mainlanguage->find('first', array('conditions' => array('state_id' => $this->Auth->user('state_id'))));
                                        if (!empty($local_langauge)) {
                                            $this->Session->write("local_langauge", $local_langauge['mainlanguage']['language_code']);
                                        }
                                        $username = $this->User->find('all', array('conditions' => array('user_id' => $userid)));
                                        $this->set('userlanguage', $username);
                                        $lang_english = $username[0]['User']['lang_english'];
                                        $lang_local = $username[0]['User']['lang_local'];
                                        $lang_both = $username[0]['User']['lang_both'];
                                        if ($lang_english == 1) {
                                            $this->Session->write("sess_langauge", 'en');
                                            $this->Session->write('doc_lang', 'en');
                                        } else
                                        if ($lang_local == 1) {
                                            $this->Session->write("sess_langauge", $local_langauge['language']['language_code']);
                                            if ($local_langauge['language']['language_code'] == 'en') {
                                                $this->Session->write('doc_lang', 'en');
                                            } else {
                                                $this->Session->write('doc_lang', 'll');
                                            }
                                            // $this->Session->write('doc_lang', $local_langauge['language']['language_code']);
                                        } else
                                        if ($lang_both == 1) {
                                            $this->Session->write("sess_lan_both", 'Y');
                                            $this->Session->write("sess_langauge", 'en');
                                            $this->Session->write('doc_lang', 'en');
                                        }
                                        if ($this->Auth->user('authetication_type') == '1') {
                                            $this->set('count', 0);
                                            $lang = $this->Session->read("sess_langauge");
                                            $count = ClassRegistry::init('getUserRolecitizen')->query("select count(*) from (select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles_citizen A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=? order by B.module_name_$lang) as role", array($userid));
                                            //check multiple 
                                            //pr($count);exit;
                                            if ($count[0][0]['count'] > 1) {
                                                $lang = $this->Session->read("sess_langauge");
                                                $usermodules = ClassRegistry::init('getUserRolecitizen')->query("select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles_citizen A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=? order by B.module_name_$lang", array($userid));
                                                $this->set('usermodules', $usermodules);
                                                $this->Session->write("session_redirect", 'welcomemodel');
                                                $this->redirect(array('action' => 'welcomemodel'));
                                            } else {
                                                // echo 1;exit;
                                                $this->Session->write("session_redirect", 'welcome');
                                                $this->redirect(array('action' => 'welcome'));
                                            }
                                        } else if ($this->Auth->user('authetication_type') == '2' && $this->Auth->user('biometric_registration_flag') == 'Y') {
                                            $this->Session->write("session_redirect", 'welcomemodel');
                                            $this->redirect(array('action' => 'biometriclogin'));
                                        } else if ($this->Auth->user('authetication_type') == '2' && $this->Auth->user('biometric_registration_flag') == 'N') {
                                            $this->Session->write("session_redirect", 'welcomemodel');
                                            $this->redirect(array('action' => 'biometricregistration'));
                                        }
                                    } else {
                                        //login restrict $logintype
                                        $this->loadModel('CitizenUser');
                                        $this->CitizenUser->insertUnsuccessfulLogin($user_id, $logintype);
                                        //end
                                        Cache::clear();
                                        clearCache();
                                        $this->Session->setFlash(__('Authentication Failed'));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                    }
                                } else {
                                    $this->Session->renew();
                                    Cache::clear();
                                    clearCache();
                                }
                            } else {
                                //login restrict $logintype
                                $this->User->insertUnsuccessfulLogin($user_id, $logintype);
                                //end
                                if ($logincount <= 3) {
                                    $this->Session->setFlash(__('Captcha code does not match. Verification unsuccessful'));
                                } else {
                                    $this->Session->setFlash(__('You Are Temporarily Blocked,Please Try After Some Time!!!'));
                                }
                            }
                        }
                    }$this->set('logincount1', $logincount);
                }





                $this->set("aftervalidation", 'Y');

                $this->set_csrf_token();
            }
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function otpsavecitizen() {

        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');
        $username = $this->request->data['username'];
        $password = $this->request->data['password'];
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->CitizenUser->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user_citizen where username=?", array($username));
        //pr($password);exit;

        if ($checkuser != Null) {
            //if($checkuser[0][0]['password']==)
            $get_password = $checkuser[0][0]['password'];

            $hashed_current_password = $password;
            $current_password_db = $get_password;
            //pr($hashed_current_password);exit;
            if (($hashed_current_password) == ($current_password_db)) {

                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 4)));
                if (!empty($event)) {
                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                        $otp = rand(10000000, 99999999);
                    } else {
                        $otp = 12345678;
                    }
                }

                $userid = $checkuser[0][0]['user_id'];
                $stateid = $checkuser[0][0]['state_id'];
                $createdate = $date;
                $rip = $req_ip;

                $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_citizen_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip));

                if (!empty($checkuser)) {
                    if ($checkuser[0][0]['mobile_no']) {


                        if (!empty($event)) {
                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 4);
                            } else {
                                echo 1;
                                exit;
                            }
                        }
                    }
                }
            } else {
                echo 'Authentication failed';
                exit;
            }
        } else {
            echo 'Authentication failed';
            exit;
        }
    }

    public function checkcitizenlogin() {
        $this->loadModel('loginstatus_citizen');
        // $citizenlogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'role_id' => 1)));
        $citizenlogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'login_type' => 'C')));

        return count($citizenlogincount);
    }

    public function checkdeedwriterlogin() {
        $this->loadModel('loginstatus_citizen');
        //$deedwriterlogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'role_id' => 2)));
        $deedwriterlogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'login_type' => 'D')));
//pr($deedwriterlogincount);exit;
        return count($deedwriterlogincount);
    }

    public function checkadvocatelogin() {
        $this->loadModel('loginstatus_citizen');
        //$deedwriterlogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'role_id' => 2)));
        $advocatelogincount = $this->loginstatus_citizen->find('all', array('conditions' => array('login_status' => 'LoggedIn', 'DATE(logindate)' => date('Y-m-d'), 'login_type' => 'A')));
//pr($deedwriterlogincount);exit;
        return count($advocatelogincount);
    }

    function get_citizen_logincount() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 54)));
            return $regconfig['regconfig']['info_value'];
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_deedwriter_logincount() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 56)));
            return $regconfig['regconfig']['info_value'];
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_advocate_logincount() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 60)));
            return $regconfig['regconfig']['info_value'];
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function forgotpassword_citizen_old() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('citizenuserreg');
                $this->loadModel('CitizenUser');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        $userdetailsrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('citizenuserreg.user_name' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('citizenuserreg.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->citizenuserreg->find('all', $options);
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));
                        if ($hintanswer != '' && $hintanswer != NULL) {

                            $otp = rand(10000000, 99999999);
                            $smsid = 1;
                            $dbrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['CitizenUser']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['CitizenUser']['user_id'];
                            $state_id = $dbrecord[0]['CitizenUser']['state_id'];
                            $ip = $this->request->clientIp();

                            $this->loadModel('otpcitizen');
                            $data = array('username' => $this->request->data['forgotpassword']['username'],
                                'otp' => $otp,
                                'user_id' => $user_id,
                                'state_id' => $state_id,
                                'req_ip' => $ip,
                                'user_type' => 'C',
                                'created_date' => $date);

                            if ($this->otpcitizen->save($data)) {
                                //$this->set('actiontype1', 3);
//                                //**************************By pravin******************
//                                $this->set('recorddata', 2);
//                                $newphone = substr($phone, -4);
//                                $this->set('otp_mobileno', $phone);
//                                $this->set('newmobileno', $newphone);
//                                //************************** end By pravin******************

                                $this->loadModel('citizenuserreg');
                                // if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
                                // } else {
                                //    $this->Session->setFlash(__('OTP send failed...'));
                                //}
                            } else {
                                $this->Session->setFlash(__('OTP send failed...Please try later'));
                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '3') {
                        $this->loadModel('otpcitizen');
                        $otpdata = $this->otpcitizen->find('first', array('conditions' => array('username' => $this->request->data['forgotpassword']['username']), 'order' => array('created' => 'DESC')));
                        $db_otp = $otpdata['otpcitizen']['otp'];
                        if ($db_otp == $this->request->data['forgotpassword']['txtotp']) {
                            $this->set('recorddata', 3);
                        } else {
                            $this->Session->setFlash(__('OTP does not match...'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        }
                    }
                    if ($actiontype == '4') {
                        if (isset($this->request->data['forgotpassword']['newpassword']) && isset($this->request->data['forgotpassword']['cpassword'])) {
                            if ($this->request->data['forgotpassword']['newpassword'] != NULL && $this->request->data['forgotpassword']['cpassword'] != NULL) {
                                if (Sanitize::html($this->request->data['forgotpassword']['newpassword']) == Sanitize::html($this->request->data['forgotpassword']['cpassword'])) {
                                    $data = array('pwd' => $this->request->data['forgotpassword']['newpassword']);
                                    if (Sanitize::check($data)) {
                                        $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                        if (preg_match($regex, Sanitize::html($this->request->data['forgotpassword']['newpassword']))) {
                                            if ($this->citizenuserreg->updateforgotpassword_citizen($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'citizenlogin'));
                                            } else {
                                                $this->Session->setFlash(__('Password did not change '));
                                            }
                                        } else {
                                            $this->Session->setFlash(__('Enter Proper String'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('Enter Proper String'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('Password did not match'));
                                }
                            } else {
                                $this->Session->setFlash(__('Please Enter Required Fields'));
                                $this->redirect(array('action' => 'change_password'));
                            }
                        } else {
                            //header('Location:../cterror.html');
                            exit;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            pr($e);
            $this->redirect(array('action' => 'error404'));
        }
    }

    //Sha256 without hintquestion
    public function forgotpassword_citizen_sha256() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('citizenuserreg');
                $this->loadModel('CitizenUser');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        $userdetailsrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('citizenuserreg.user_name' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('citizenuserreg.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->citizenuserreg->find('all', $options);

                            $this->set('hintquestion', $hintquestion);
//                            } else {
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
//                        $hintanswer = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));
//                        if ($hintanswer != '' && $hintanswer != NULL) {

                        $userdetailsrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {

                            $this->loadModel('smsevent');


                            $dbrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['CitizenUser']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['CitizenUser']['user_id'];
                            $state_id = $dbrecord[0]['CitizenUser']['state_id'];
                            $ip = $this->request->clientIp();



                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 7)));
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }

                                $this->loadModel('otpcitizen');
                                $this->loadModel('otpcitizen_citizenuser');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'C',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['CitizenUser']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['CitizenUser']['mobile_no'], $otp, $dbrecord[0]['CitizenUser']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
//                          
//       
                            //comment vishal

                            if ($this->otpcitizen_citizenuser->save($data)) {

                                $this->loadModel('citizenuserreg');
                                // if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
                                // } else {
                                //    $this->Session->setFlash(__('OTP send failed...'));
                                //}
                            } else {
                                $this->Session->setFlash(__('OTP send failed...Please try later'));
                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                        //comment vishal
                    }
                    if ($actiontype == '3') {
                        $this->loadModel('otpcitizen');
                        $textotp = $this->request->data['forgotpassword']['txtotp'];
                        $textusername = $this->request->data['forgotpassword']['username'];
                        $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));
//                               pr($textotp);
//                               pr($lastotp);exit;
                        if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                            $this->Session->setFlash(__('OTP Does Not Match...'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        } else {
                            $this->set('recorddata', 3);
                        }
                    }
                    if ($actiontype == '4') {

                        // pr($this->request->data['forgotpassword']['newpassword']);exit;
                        if (isset($this->request->data['forgotpassword']['newpassword']) && isset($this->request->data['forgotpassword']['cpassword'])) {
                            if ($this->request->data['forgotpassword']['newpassword'] != NULL && $this->request->data['forgotpassword']['cpassword'] != NULL) {
                                if (Sanitize::html($this->request->data['forgotpassword']['newpassword']) == Sanitize::html($this->request->data['forgotpassword']['cpassword'])) {
                                    $data = array('pwd' => $this->request->data['forgotpassword']['newpassword']);

                                    if (Sanitize::check($data)) {
                                        $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                        if (preg_match($regex, Sanitize::html($this->request->data['forgotpassword']['newpassword']))) {

                                            if ($this->citizenuserreg->updateforgotpassword_citizen($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'citizenlogin'));
                                            } else {
                                                $this->Session->setFlash(__('Password did not change '));
                                            }
                                        } else {
                                            $this->Session->setFlash(__('Enter Proper String'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('Enter Proper String'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('Password did not match'));
                                }
                            } else {
                                $this->Session->setFlash(__('Please Enter Required Fields'));
                                $this->redirect(array('action' => 'change_password'));
                            }
                        } else {
                            //header('Location:../cterror.html');
                            exit;
                        }
                    }
                }
            }
        } catch (Exception $ex) {

//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //SHA256 withhinquestion
    public function forgotpassword_citizen_hintquestion() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('citizenuserreg');
                $this->loadModel('CitizenUser');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        $userdetailsrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('citizenuserreg.user_name' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('citizenuserreg.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->citizenuserreg->find('all', $options);
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));
                        if ($hintanswer != '' && $hintanswer != NULL) {

                            $this->loadModel('smsevent');


                            $dbrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['CitizenUser']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['CitizenUser']['user_id'];
                            $state_id = $dbrecord[0]['CitizenUser']['state_id'];
                            $ip = $this->request->clientIp();



                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 7)));
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }

                                $this->loadModel('otpcitizen');
                                $this->loadModel('otpcitizen_citizenuser');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'C',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['CitizenUser']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['CitizenUser']['mobile_no'], $otp, $dbrecord[0]['CitizenUser']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
//                          
//       
                            //comment vishal

                            if ($this->otpcitizen_citizenuser->save($data)) {

                                $this->loadModel('citizenuserreg');
                                // if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
                                // } else {
                                //    $this->Session->setFlash(__('OTP send failed...'));
                                //}
                            } else {
                                $this->Session->setFlash(__('OTP send failed...Please try later'));
                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                        //comment vishal
                    }
                    if ($actiontype == '3') {
                        $this->loadModel('otpcitizen');
                        $textotp = $this->request->data['forgotpassword']['txtotp'];
                        $textusername = $this->request->data['forgotpassword']['username'];
                        $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));
//                               pr($textotp);
//                               pr($lastotp);exit;
                        if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                            $this->Session->setFlash(__('OTP Does Not Match...'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        } else {
                            $this->set('recorddata', 3);
                        }
                    }
                    if ($actiontype == '4') {

                        // pr($this->request->data['forgotpassword']['newpassword']);exit;
                        if (isset($this->request->data['forgotpassword']['newpassword']) && isset($this->request->data['forgotpassword']['cpassword'])) {
                            if ($this->request->data['forgotpassword']['newpassword'] != NULL && $this->request->data['forgotpassword']['cpassword'] != NULL) {
                                if (Sanitize::html($this->request->data['forgotpassword']['newpassword']) == Sanitize::html($this->request->data['forgotpassword']['cpassword'])) {
                                    $data = array('pwd' => $this->request->data['forgotpassword']['newpassword']);
                                    if (Sanitize::check($data)) {
                                        $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                        if (preg_match($regex, Sanitize::html($this->request->data['forgotpassword']['newpassword']))) {
                                            if ($this->citizenuserreg->updateforgotpassword_citizen($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'citizenlogin'));
                                            } else {
                                                $this->Session->setFlash(__('Password did not change '));
                                            }
                                        } else {
                                            $this->Session->setFlash(__('Enter Proper String'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('Enter Proper String'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('Password did not match'));
                                }
                            } else {
                                $this->Session->setFlash(__('Please Enter Required Fields'));
                                $this->redirect(array('action' => 'change_password'));
                            }
                        } else {
                            //header('Location:../cterror.html');
                            exit;
                        }
                    }
                }
            }
        } catch (Exception $ex) {

//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //sha11
    public function forgotpassword_citizen() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('citizenuserreg');
                $this->loadModel('CitizenUser');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        $userdetailsrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        // pr($userdetailsrecord);exit;
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);
                            //code updated by PARESH//
//                            $option['conditions'] = array('CitizenUser.username' => $this->request->data['forgotpassword']['username']);
//                            $option['joins'] = array(array('table' => 'ngdrstab_mst_userroles_citizen', 'alias' => 'Role', 'type' => 'INNER', 'conditions' => array('CitizenUser.user_id = Role.user_id')));
//                            $option['fields'] = array('Role.role_id', 'CitizenUser.username', 'Role.user_id');
//                           
//                            $role = $this->CitizenUser->find('all', $option);
//                            $this->set('role', $role);
//                            
//                            if( $role == NULL )
//                            {
//                                $this->Session->setFlash(__('** USER DOES NOT EXISTS **')); 
//                            }
//                            else if( $role[0]['Role']['role_id'] != 999906 ) {
//                                        $this->set('recorddata', 1);
//                                        $this->loadModel('citizenuserreg');
//                                        $dbrecord = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'])));
//                                        $phone = Sanitize::html($dbrecord[0]['citizenuserreg']['mobile_no']);
//                                        $newphone = substr($phone, -4);
//                                        $this->set('otp_mobileno', $phone);
//                                        $this->set('newmobileno', $newphone);
//                            }
//                            else { 
//                                        $this->set('recorddata', 2);
//                                        $this->loadModel('citizenuserreg');
//                                        $dbrecord = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'])));
//                                        $phone = Sanitize::html($dbrecord[0]['citizenuserreg']['mobile_no']);
//                                        $newphone = substr($phone, -4);
//                                        $this->set('otp_mobileno', $phone);
//                                        $this->set('newmobileno', $newphone);
//                            }
//                           
//                            $options['conditions'] = array('citizenuserreg.user_name' => $this->request->data['forgotpassword']['username']);
//                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('citizenuserreg.hint_question = hint.id')));
//                            $options['fields'] = array('hint.questions_en');
//                             
//                            $hintquestion = $this->citizenuserreg->find('all', $options);
//                            
//                            if( $hintquestion != NULL )
//                            {
//                                        $this->set('hintquestion', $hintquestion);
//                            } else {
//                                     $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }

                            $options['conditions'] = array('citizenuserreg.user_name' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('citizenuserreg.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->citizenuserreg->find('all', $options);
                            // pr($hintquestion);exit;
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->citizenuserreg->find('all', array('conditions' => array('user_name' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));
                        if ($hintanswer != '' && $hintanswer != NULL) {

                            $this->loadModel('smsevent');


                            $dbrecord = $this->CitizenUser->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['CitizenUser']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['CitizenUser']['user_id'];
                            $state_id = $dbrecord[0]['CitizenUser']['state_id'];
                            $ip = $this->request->clientIp();



                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 7)));
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }

                                $this->loadModel('otpcitizen');
                                $this->loadModel('otpcitizen_citizenuser');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'C',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['CitizenUser']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['CitizenUser']['mobile_no'], $otp, $dbrecord[0]['CitizenUser']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
//                          
//       
                            //comment vishal

                            if ($this->otpcitizen_citizenuser->save($data)) {

                                $this->loadModel('citizenuserreg');
                                // if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
                                // } else {
                                //    $this->Session->setFlash(__('OTP send failed...'));
                                //}
                            } else {
                                $this->Session->setFlash(__('OTP send failed...Please try later'));
                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                        //comment vishal
                    }
                    if ($actiontype == '3') {
                        $this->loadModel('otpcitizen');
                        $textotp = $this->request->data['forgotpassword']['txtotp'];
                        $textusername = $this->request->data['forgotpassword']['username'];
                        $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));
//                               pr($textotp);
//                               pr($lastotp);exit;
                        if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                            $this->Session->setFlash(__('OTP Does Not Match...'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        } else {
                            $this->set('recorddata', 3);
                        }
                    }
                    if ($actiontype == '4') {

                        // pr($this->request->data['forgotpassword']['newpassword']);exit;
                        if (isset($this->request->data['forgotpassword']['newpassword']) && isset($this->request->data['forgotpassword']['cpassword'])) {
                            if ($this->request->data['forgotpassword']['newpassword'] != NULL && $this->request->data['forgotpassword']['cpassword'] != NULL) {
                                if (Sanitize::html($this->request->data['forgotpassword']['newpassword']) == Sanitize::html($this->request->data['forgotpassword']['cpassword'])) {
                                    $data = array('pwd' => $this->request->data['forgotpassword']['newpassword']);
                                    if (Sanitize::check($data)) {
                                        $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                        if (preg_match($regex, Sanitize::html($this->request->data['forgotpassword']['newpassword']))) {
                                            if ($this->citizenuserreg->updateforgotpassword_citizen($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'citizenlogin'));
                                            } else {
                                                $this->Session->setFlash(__('Password did not change '));
                                            }
                                        } else {
                                            $this->Session->setFlash(__('Enter Proper String'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('Enter Proper String'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('Password did not match'));
                                }
                            } else {
                                $this->Session->setFlash(__('Please Enter Required Fields'));
                                $this->redirect(array('action' => 'change_password'));
                            }
                        } else {
                            //header('Location:../cterror.html');
                            exit;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            pr($e);
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function welcome() {
        
    }

    public function citizenlogout() {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            // $this->autoRender = FALSE;
            $citizenflag = $this->Session->read("session_usertype");
//            pr($citizenflag);exit;
            if ($citizenflag == 'C') {
                if (isset($_SESSION["token"])) {
                    $this->Session->delete('appreferer');
                    $this->loadModel('User');
                    $this->loadModel('CitizenUser');

                    $this->CitizenUser->updateLoginDetails($this->Auth->user('user_id'));
                    $flag = 'logout';
                    $this->Session->write('logoutflag', $flag);
                    $this->redirect($this->Auth->logout());
                } else {

                    return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_common_fields() {
        $data['stateid'] = $this->Auth->User("state_id");
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Session->read("citizen_user_id");
        return $data;
    }

    //function for your documents
    public function genernal_info() {
        try {

            array_map(array($this, 'loadModel'), array('genernal_info', 'conf_reg_bool_info'));
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("citizen_user_id", $this->Auth->user('user_id'));
            $user_id = $this->Session->read("citizen_user_id");
            $session_tokenval = $this->Session->read("Selectedtoken");
            $laug = $this->Session->read("sess_langauge");
            $this->Session->write("sroidetifier", 'N');
            $this->Session->write("sroparty", 'N');
            $this->Session->write("ereg_flag", 'N');
            $statusrecord = $this->genernal_info->get_alldocument($user_id, $laug, $this->Session->read("session_usertype"));
            if ($this->Session->read("session_usertype") == 'C') {
                $this->Session->write("manual_flag", 'N');
            }
            $this->set('statusrecord', $statusrecord);
            $sro_appr_flag = $this->conf_reg_bool_info->field('conf_bool_value', array('reginfo_id' => 125));
            $this->set('sro_appr_flag', $sro_appr_flag);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //function for general infoentry start

    public function genernalinfoentry($csrftoken = Null, $flag = '') {

        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../error.html');
                    exit;
                }
            }
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            array_map(array($this, 'loadModel'), array('User', 'foldertobedeleted', 'finyear', 'counter', 'ApplicationSubmitted', 'proceduretype', 'party_entry', 'regconfig', 'uploaded_file_trn', 'file_config', 'District', 'taluka', 'conf_article_feerule_items', 'language', 'mainlanguage', 'articaledepfields', 'genernalinfoentry', 'doc_levels', 'doc_title', 'State', 'User', 'articletrnfields', 'article', 'documenttitle', 'document_execution_type', 'article_screen_mapping', 'article_fee_rule'));
            $fields = $this->set_common_fields();

            if ($this->Session->read("deed_writer") == 'Y') {
                $doc_count = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 50)));
            } else if ($this->Session->read("is_advocate") == 'Y') {
                $doc_count = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 62)));
            } else {
                $doc_count = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 47)));
            }
            $village_flag = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 81)));
            if (!empty($village_flag)) {
                $this->set('village_flag', $village_flag['regconfig']['conf_bool_value']);
            } else {
                $this->set('village_flag', 'Y');
            }
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                if (!empty($doc_count)) {
                    $check_count = $this->genernalinfoentry->find('all', array('conditions' => array('user_id' => $fields['user_id'], 'last_status_id !=' => 4)));
                    if (count($check_count) >= $doc_count['regconfig']['info_value']) {
                        $this->Session->setFlash(
                                __('Document count greater than ' . $doc_count['regconfig']['info_value'] . ' Not alowed for new document Entry')
                        );

                        return $this->redirect(array('action' => 'genernal_info'));
                    }
                }
            }

            $upload_doc_title_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 143));
            $this->set('upload_doc_title_flag', $upload_doc_title_flag);


            $sro_appr_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 125));
            if ($sro_appr_flag == 'Y') {
                if (is_numeric($this->Session->read('Selectedtoken'))) {
                    $sro_approval = ClassRegistry::init('genernalinfoentry')->field('sro_approve_flag', array('token_no' => $this->Session->read("Selectedtoken")));
                    if ($sro_approval == 'N') {
                        $this->Session->setFlash("Wait For SRO Approval");
                    }
                }
            }

            $last_status_id = $this->Session->read('last_status_id');

            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array('ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));
//          pr($submission);exit;
            if (count($submission) > 0) {
                $this->set('submission_flag', 'Y');
            } else {
                $this->set('submission_flag', 'N');
            }


            $documenttitle = $this->doc_title->get_title();
            $doc_lang = $this->Session->read("sess_langauge");
            $laug = $this->Session->read("sess_langauge");

            $language = $this->mainlanguage->get_main_lag();
            $language2 = $this->mainlanguage->get_state_lang($fields['stateid']);
            $document_execution_type = $this->document_execution_type->get_doc_execution_type($laug);

            //$this->set('username', $this->Auth->User('full_name'));

            $deed_writer = $this->Auth->User('deed_writer');
            $is_advocate = $this->Auth->User('is_advocate');
            $full_name = $this->Auth->User('full_name');

            if ($deed_writer == 'Y') {
                $username = $full_name . " - Deed Writer";
            } else if ($is_advocate == 'Y') {
                $username = $full_name . " - Advocate";
            } else {
                $username = $full_name . " - Citizen";
            }

            $this->set("username", $username);

            $this->set(compact('language', 'language2', 'document_execution_type', 'article', 'documenttitle'));

            $this->set('laug', $laug);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $stateid = $this->Auth->User("state_id");
            //pr($doc_lang);
            if ($stateid == 3) {
                $article_o = $this->article->get_article_hp($doc_lang);
            } else {
                $article_o = $this->article->get_article($doc_lang);
            }
            // pr($article_o);
            // pr($doc_lang);
            $article = array();
            for ($r = 0; $r < sizeof($article_o); $r++) {
                $flg = $article_o[$r]['article']['article_ll_activation_flag'];
                $eng_name = $article_o[$r]['article']['article_desc_en'];
                $mar_name = $article_o[$r]['article']['article_desc_ll'];
                $art_id = $article_o[$r]['article']['article_id'];

                if ($doc_lang == 'll' && $flg == 'Y') {
                    $article[$art_id] = $mar_name;
                } else {
                    $article[$art_id] = $eng_name;
                }
            }
            // pr($article);
            $languagecode = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'conditions' => array('language_code' => $this->Session->read('sess_langauge'))));
            // echo $languagecode[0]['mainlanguage']['id'];
            //exit;
            $this->set('lang_id', $languagecode[0]['mainlanguage']['id']);

            $this->set('article', $article);
            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC')));
            $this->set('office', $office);
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
            $advocate_name_flag = $this->advoate_feild_require_flag();
            $this->set('advocate_name_flag', $advocate_name_flag);

            //Procedure type
            $proceduretype_flag = $this->proceduretype_feild_require_flag();
            $this->set('proceduretype_flag', $proceduretype_flag);

            $fieldlist = array();
            $fieldlist = $this->conf_article_feerule_items->fieldlist($doc_lang, $advocate_name_flag);

            $circle = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 100));
            $tal_compulsary = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 102));
            if (isset($circle)) {
                if ($circle == 'N') {
                    unset($fieldlist['taluka_id']);
                }
                $this->set('circle', $circle);
            } else {
                if (isset($tal_compulsary)) {
                    if ($tal_compulsary == 'N') {
                        unset($fieldlist['taluka_id']);
                    }
                }

                $this->set('circle', 'Y');
            }
            $this->set('tal_compulsary', $tal_compulsary);

            if ($this->Session->read("session_usertype") == 'O') {
                //unset($fieldlist['district_id']);
                //unset($fieldlist['taluka_id']);
                unset($fieldlist['office_id']);
            }

            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }

            $this->set("errarr", $errarr);
            $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($errarr));

            if ($this->Session->read("session_usertype") == 'O') {
                $distid = ClassRegistry::init('office')->field('district_id', array('office_id' => $this->Session->read('office_id')));

                $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $laug), 'conditions' => array('state_id' => $fields['stateid'], 'District.id' => $distid), 'order' => 'district_name_' . $laug));
            } else {
                $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $laug), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $laug));
            }
            $taluka = $villagelist = $office = NULL;


            $proceduretype = $this->proceduretype->find('list', array('fields' => array('proceduretype.procedure_id', 'proceduretype.procedure_desc_en'), 'order' => array('proceduretype.procedure_desc_en' => 'ASC')));
            $this->set('proceduretype', $proceduretype);

            $this->set('districtdata', $districtdata);
            $this->set('taluka', $taluka);
            $this->set('villagelist', $villagelist);
            $this->set('office', $office);
            //number of pages
            $no_of_pages = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 90)));

            if (!empty($no_of_pages)) {
                $this->set('display_no_of_pages', $no_of_pages['regconfig']['conf_bool_value']);
            }
            if ($this->request->is('post') || $this->request->is('put')) {

                $this->check_csrf_token($this->request->data['genernalinfoentry']['csrftoken']);

                //delete folder file by changing office

                if (is_numeric($this->Session->read('Selectedtoken'))) {
                    $lastoffice = $this->genernalinfoentry->field('office_id', array('token_no' => $this->Session->read('Selectedtoken')));
                    $office_id = $this->request->data['genernalinfoentry']['office_id'];
                    if ($lastoffice != $office_id) {
                        $path = $this->file_config->find('first', array('fields' => array('filepath')));
                        $general = $this->genernalinfoentry->find('first', array('fields' => array('dist.district_name_en', 'genernalinfoentry.taluka_id', 'genernalinfoentry.office_id'), 'conditions' => array(
                                'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=genernalinfoentry.district_id')),
                        )));

                        $path = $path['file_config']['filepath'] . 'Documents' . '/' . $general['dist']['district_name_en'] . '/' . $general['genernalinfoentry']['taluka_id'] . '/' . $general['genernalinfoentry']['office_id'] . '/' . $this->Session->read("Selectedtoken");
                        $upload_fileinfo = $this->uploaded_file_trn->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));

                        $data = array();
                        $data['folderpath'] = $path;
                        $data['token_no'] = $this->Session->read("Selectedtoken");
                        $data['user_id'] = $fields['user_id'];
                        $data['req_ip'] = $this->RequestHandler->getClientIp();
                        $data['state_id'] = $fields['stateid'];
                        $data['user_type'] = $this->Session->read("session_usertype");
         
                        $this->foldertobedeleted->save($data);
                        if (!empty($upload_fileinfo)) {

                            $this->party_entry->updateAll(array('party_entry.uploaded_file' => NULL), array('party_entry.token_no' => $this->Session->read("Selectedtoken"))
                            );

                            $this->uploaded_file_trn->deleteAll(['token_no' => $this->Session->read("Selectedtoken")]);
                        }
                    }
                }


                $rule = $this->article_fee_rule->find('all', array('conditions' => array('article_id' => $this->request->data['genernalinfoentry']['article_id'])));
                if (count($rule) <= 0) {
                    $this->Session->setFlash("Fee rule not found for this article ,Document not saved !");
                    $this->set_csrf_token();
                   // return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
                }
                $fieldlist = $this->conf_article_feerule_items->fieldlist($doc_lang, $advocate_name_flag, $this->request->data['genernalinfoentry']['article_id']);

                if (isset($circle)) {
                    if ($circle == 'N') {
                        unset($fieldlist['taluka_id']);
                    }
                } else {
                    if (isset($tal_compulsary)) {
                        if ($tal_compulsary == 'N') {
                            unset($fieldlist['taluka_id']);
                        }
                    }
                }
               
                $languagecode = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'conditions' => array('language_code' => $this->Session->read('sess_langauge'))));
                if ($languagecode) {
                    $this->request->data['genernalinfoentry']['local_language_id'] = $languagecode[0]['mainlanguage']['id'];
                }
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                if ($this->Session->read("manual_flag") == 'N') {
                    unset($fieldlist['manual_reg_no']);
                }
                if ($this->Session->read("session_usertype") == 'O') {
                    // unset($fieldlist['district_id']);
                    //unset($fieldlist['taluka_id']);
                    unset($fieldlist['office_id']);
                }
                $errarr = $this->validatedata($this->request->data['genernalinfoentry'], $fieldlist);

                $flag = 0;
                foreach ($errarr as $dd) {
                    // PR($dd);
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                    $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($errarr));
                } else {
                    $this->request->data['genernalinfoentry']['user_type'] = $this->Session->read("session_usertype");





                    $setdata = $this->set_value_tosave_generalinfo($this->request->data['genernalinfoentry'], $fields['user_id'], $fields['stateid'], $this->Session->read("session_usertype"));
                    $this->request->data['genernalinfoentry']['local_language_id'] = $languagecode[0]['mainlanguage']['id'];
                    $this->request->data['genernalinfoentry'] = $setdata;
                    $frmData = $this->request->data['genernalinfoentry'];

                    // for token sequence
                    if (!isset($this->request->data['genernalinfoentry']['general_info_id'])) {

                        $finyear = $this->finyear->field('finyear_id', array('current_year' => 'Y'));
                        $year = $this->finyear->field('year_for_token', array('current_year' => 'Y'));
                        $counter = $this->counter->find('all');
                        if (!empty($counter)) {
                            $count = $counter[0]['counter']['token_no_count'];
                            $token_no = ($year . $count) + 1;
                        } else {
                            $count = '0000000001';
                            $this->counter->save(array('fin_year_id' => $finyear, 'token_no_count' => $count));
                            $token_no = ($year . $count);
                        }

                        $this->request->data['genernalinfoentry']['token_no'] = $token_no;
                        $rem = "'" . substr($token_no, 4) . "'";

                        $this->counter->updateAll(
                                array('fin_year_id' => $finyear, 'token_no_count' => $rem)); //fields to update
                    }
                    //server side validations
                    $this->request->data['genernalinfoentry'] = $this->istrim($this->request->data['genernalinfoentry']);
                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['genernalinfoentry']['user_id']);

                        if ($this->Auth->user('office_id')) {
                            $this->request->data['genernalinfoentry']['office_id'] = $this->Auth->user('office_id');
                            $this->request->data['genernalinfoentry']['org_user_id'] = $this->Auth->User('user_id');
                            if (isset($this->request->data['genernalinfoentry']['general_info_id']) && is_numeric($this->request->data['genernalinfoentry']['general_info_id'])) {

                                $this->request->data['genernalinfoentry']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['genernalinfoentry']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                    }


                    $this->loadModel('User');
                    $userarr = $this->User->find('list', array('fields' => array('user_id', 'office_id'), 'conditions' => array('office_id' => $this->request->data['genernalinfoentry']['office_id'], 'role_id' => array('999901'), 'is_demo_user' => 'N')));
                    if (empty($userarr)) {
                        $this->Session->setFlash("SRO User Not Found For This Office");
                        $this->set_csrf_token();
                        return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
                    }

                    if ($this->genernalinfoentry->save($this->request->data['genernalinfoentry'])) {

                        $last_id = $this->genernalinfoentry->getLastInsertID();
                        if (!is_numeric($last_id)) {
                            $last_id = $this->request->data['genernalinfoentry']['general_info_id'];
                        }
                        $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('general_info_id' => $last_id)));
                        // $this->set_token_session($gen_info['0']['genernalinfoentry']['token_no']);
                        if ($gen_info) {
                            if (is_null($gen_info[0]['genernalinfoentry']['sro_user_id']) || $gen_info[0]['genernalinfoentry']['sro_approve_flag'] == 'N') {
                                $rand = array_rand($userarr);
                                $this->genernalinfoentry->updateAll(array('sro_user_id' => $rand), array('general_info_id' => $last_id));
                            }

                            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                                $this->save_documentstatus(1, $gen_info[0]['genernalinfoentry']['token_no'], $gen_info[0]['genernalinfoentry']['office_id']);
                            }
                            $this->Session->write('Selectedtoken', $gen_info[0]['genernalinfoentry']['token_no']);
                            $this->Session->write('article_id', $gen_info[0]['genernalinfoentry']['article_id']); //madhuri
                            $this->set('delay_flag', $gen_info[0]['genernalinfoentry']['delay_flag']); //madhuri



                            $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $gen_info[0]['genernalinfoentry']['local_language_id'])));
                            if ($language) {
                                if ($language['0']['mainlanguage']['language_code'] == 'en') {
                                    $this->Session->write('doc_lang', 'en');
                                } else {
                                    $this->Session->write('doc_lang', 'll');
                                }
                            }

                            $property_exists = $this->article_screen_mapping->find('count', array('conditions' => array('minorfun_id' => 2, 'article_id' => $this->request->data['genernalinfoentry']['article_id']))); //2 for Property        
                            if (!$property_exists) {

                                $ids = ClassRegistry::init('property_details_entry')->find('all', array('fields' => array('property_id'), 'conditions' => array('token_no' => $gen_info[0]['genernalinfoentry']['token_no'])));
//                               pr($ids);
//                               exit;
                                if (count($ids) > 0) {
                                    foreach ($ids as $id) {
                                        $this->property_remove($id['property_details_entry']['property_id'], 'G');
                                    }
                                }
//                              
                            }
                            ClassRegistry::init('stamp_duty')->deleteAll(['token_no' => $gen_info[0]['genernalinfoentry']['token_no']]);
                            ClassRegistry::init('stamp_duty_adjustment')->deleteAll(['token_no' => $gen_info[0]['genernalinfoentry']['token_no']]);
                        }

                        //save article dependent feilds
//                        pr($this->request->data);exit;
                        $this->articletrnfields->query('delete from ngdrstab_trn_articledepfields where token_no=' . $this->Session->read('Selectedtoken'));

                        $this->articletrnfields->savedependent_field($laug, $this->request->data['genernalinfoentry'], $this->Session->read('Selectedtoken'), $fields, $this->Session->read("session_usertype"));
                        //----------------------------by Shridhar SD Calculation-------------------------------------------------
                        if (isset($frmData['no_of_pages'])) {
                            $pages = $frmData['no_of_pages'];
                        } else {
                            $pages = NULL;
                        }

                        $tmp_doc_token_no = $gen_info['0']['genernalinfoentry']['token_no'];


                        $result = $this->article_fee_rule->query("select * from ngdrstab_mst_article_fee_rule where common_rule_flag='Y'");
                        if (!empty($result)) {
                            foreach ($result as $result1):
                                $sd_values = array(
                                    'token_no' => $tmp_doc_token_no,
                                    'article_id' => 9999,
                                    'fee_rule_id' => $result1[0]['fee_rule_id'],
                                    'FAJ' => $frmData['no_of_pages'], //valuation Amount
                                );

                                $handling_charge = $this->is_handling_charges_required();
                                if ($handling_charge == 'Y') {
                                    $this->calculate_fees($sd_values);
                                }
                            endforeach;
                        }

//                        $sd_values = array(
//                            'token_no' => $tmp_doc_token_no,
//                            'article_id' => 9999,
//                            'fee_rule_id' => 114,
//                            'FAJ' => $pages, //valuation Amount
//                        );
                        $fieldlist = array();
                        $fieldlist['token_no']['text'] = 'is_required';
                        $fieldlist['article_id']['text'] = 'is_required';
                        $fieldlist['FAJ']['text'] = 'is_required';
//                        $this->set('fieldlist', $fieldlist);
                        $json2array['fieldlist'] = $fieldlist;
                        $file = new File(WWW_ROOT . 'files/vjsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                        // calculate Common Stamp Duty (Scanning & Handeing Charges)
//                        $handling_charge = $this->is_handling_charges_required();
//                        if ($handling_charge == 'Y') {
//                            $this->calculate_fees($sd_values);
//                        }
                        array_map([$this, 'loadModel'], ['fees_calculation', 'fees_calculation_detail', 'stamp_duty']);
                        $fee_calc_id = $this->fees_calculation->find('first', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $tmp_doc_token_no, 'article_id' => 9999, 'delete_flag' => 'N')));
                        if ($fee_calc_id) {
                            $total_sd = $this->fees_calculation_detail->find('first', array('fields' => array('fees_total'), 'conditions' => array('fee_calc_id' => $fee_calc_id['fees_calculation']['fee_calc_id'])));

                            $tmp_total_sd = $total_sd['fees_calculation_detail']['fees_total'];
                            $sdData = array(
                                'token_no' => $tmp_doc_token_no,
                                'article_id' => $gen_info['0']['genernalinfoentry']['article_id'],
                                'counter_sd_amt' => $tmp_total_sd,
                            );
                            unset($tmp_total_sd);
                            if ($this->stamp_duty->save($sdData)) {
                                $this->stamp_duty->update_sd_amt();
                            }
                        }
//                        echo 1;
//                        exit;
                        //-----------------------------------------------------------------------------------------------------
                        $this->Session->setFlash("Saved Successfully");
                        $this->set_csrf_token();
                        $sro_appr_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 125));

                        if ($sro_appr_flag == 'Y') {
                            if (is_numeric($this->Session->read('Selectedtoken'))) {
                                $sro_approval = ClassRegistry::init('genernalinfoentry')->field('sro_approve_flag', array('token_no' => $this->Session->read("Selectedtoken")));
                                if ($sro_approval == 'Y') {
                                    return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
                                } else {
                                    return $this->redirect(array('action' => 'upload_document', $this->Session->read('csrftoken')));
                                }
                            }
                        } else {
                            return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
                        }
                    }
                }
                // $this->Session->setFlash("Saved Not Saved");
            } else {
                $laug = $this->Session->read("sess_langauge");
                $this->check_csrf_token_withoutset($csrftoken);
                // $this->Session->write('Selectedtoken', $tokenval);
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('token_no' => $tokenval)));
                if ($gen_info != NULL) { // set data  to form
                    $this->Session->write('no_of_pages', $gen_info[0]['genernalinfoentry']['no_of_pages']);
                    $this->request->data['genernalinfoentry'] = $gen_info[0]['genernalinfoentry'];
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => $gen_info[0]['genernalinfoentry']['district_id'])));
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $laug), 'conditions' => array('taluka_id' => $gen_info[0]['genernalinfoentry']['taluka_id'])));
                    $options1['conditions'] = array('ov.taluka_id' => $gen_info[0]['genernalinfoentry']['taluka_id']);
                    $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                    );
                    $options1['fields'] = array('office.office_id', 'office.office_name_' . $laug);
                    $office = ClassRegistry::init('office')->find('list', $options1);
                    if (empty($office)) {
                        $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug)));
                    }
                    $this->set('taluka', $talukalist);
                    $this->set('villagelist', $villagelist);
                    $this->set('office', $office);
                    if (is_numeric($this->Session->read('doc_lang_id'))) {
                        $this->request->data['genernalinfoentry']['local_language_id'] = $this->Session->read('doc_lang_id');
                    }
                    if ($gen_info[0]['genernalinfoentry']['exec_date'] != '' || $gen_info[0]['genernalinfoentry']['exec_date'] != NULL) {
                        $this->request->data['genernalinfoentry']['exec_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['exec_date']));
                    }
                    if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                        $this->request->data['genernalinfoentry']['presentation_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['presentation_date']));
                    }
                    $this->request->data['genernalinfoentry']['ref_doc_date'] = ($gen_info[0]['genernalinfoentry']['ref_doc_date']) ? date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['ref_doc_date'])) : NULL;

                    $this->request->data['genernalinfoentry']['link_doc_date'] = ($gen_info[0]['genernalinfoentry']['link_doc_date']) ? date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['link_doc_date'])) : NULL;

                    if ($gen_info[0]['genernalinfoentry']['court_order_date']) {
                        $this->request->data['genernalinfoentry']['court_order_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['court_order_date']));
                    }
                    $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $gen_info[0]['genernalinfoentry']['local_language_id'])));
                    if (!is_numeric($this->Session->read('doc_lang_id'))) {
                        if ($language['0']['mainlanguage']['language_code'] == 'en') {
                            $this->Session->write('doc_lang', 'en');
                        } else {
                            $this->Session->write('doc_lang', 'll');
                        }
                    }
                    $this->Session->write('article_id', $gen_info[0]['genernalinfoentry']['article_id']);
                    $this->set('delay_flag', $gen_info[0]['genernalinfoentry']['delay_flag']);
                } else {
                    $this->set('exe_date', date('d-m-Y'));
                }
            }
        } catch (Exception $ex) {

            pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_value_tosave_generalinfo($data, $user_id, $stateid, $user_type) {
        try {
            $this->loadModel('genernalinfoentry');
            $data['user_id'] = $user_id;
            $data['req_ip'] = $this->RequestHandler->getClientIp();
            $data['state_id'] = $stateid;
            $data['user_type'] = $user_type;


            $data['ref_doc_date'] = ($data['ref_doc_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['ref_doc_date']))) : NULL;

            if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                if ($this->Session->read("manual_flag") != 'Y') {
                    $data['presentation_date'] = date('Y-m-d');
                }
            }
            $data['exec_date'] = ($data['exec_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['exec_date']))) : NULL;
            if (isset($data['entry_date_india'])) {
                $data['entry_date_india'] = ($data['entry_date_india']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['entry_date_india']))) : NULL;
            }
            $data['link_doc_date'] = ($data['link_doc_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['link_doc_date']))) : NULL;
            $data['court_order_date'] = ($data['doc_execution_type_id'] == 3) ? (($data['court_order_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['court_order_date']))) : NULL) : NULL;
            //$data['last_status_id'] = 1;
            $status_result = $this->genernalinfoentry->find('all', array('fields' => array('last_status_id'), 'conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));
            if (!empty($status_result)) {
                $last_status_id = $status_result[0]['genernalinfoentry']['last_status_id'];
                $data['last_status_id'] = $last_status_id;
            } else {
                $data['last_status_id'] = 1;
            }

            $data['last_status_date'] = date('Y-m-d');

            return $data;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function getdependent_article($errarr = NULL) {
        try {

            if ($_POST['csrftoken'] == $this->Session->read('csrftoken') and isset($_POST['article_id']) and is_numeric($_POST['article_id'])) {


                $user_id = $this->Session->read("citizen_user_id");
                $lang = $this->Session->read('sess_langauge');


                $field = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $lang, 'item.display_order', 'item.is_date'), 'conditions' => array('conf_article_feerule_items.article_id' => $_POST['article_id'], 'item.gen_dis_flag' => 'Y', 'conf_article_feerule_items.level1_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $lang, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id'))), 'order' => ('item.display_order ASC')));

                $this->set('field', $field);
                $this->loadModel('articletrnfields');
                $this->loadModel('gender');
                $this->loadModel('article_fee_item_list');
                $genderlist = $this->gender->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang)));
                if ($this->Session->read('Selectedtoken') != '') {
//                 
                    $result = $this->articletrnfields->get_articledependent_feild($lang, $_POST['article_id'], $this->Session->read('Selectedtoken'));
                } else {
                    $result = $this->articletrnfields->get_articledependent_feild($lang, $_POST['article_id']);
                }
                $items_list = array();

                if (isset($result)) {
                    foreach ($result as $FeeItem) {
                        if ($FeeItem[0]['list_flag'] == 'Y') {
                            $items_list[$FeeItem[0]['fee_param_code']] = $this->article_fee_item_list->find('list', array('fields' => array('fee_item_list_id', 'fee_item_list_desc_' . $lang), 'conditions' => array('fee_item_id' => $FeeItem[0]['fee_item_id'])));
                        }
                    }
                    $this->set(compact('result', 'items_list'));
                }
                $this->set(compact('genderlist'));
                //validations kk
                $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
                $error_arr = $json = $file->read(true, 'r');
                $this->set("errarr", json_decode($error_arr, TRUE));
            } else {

                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_execution_date() {
        try {
            if (isset($_POST['presentation_date']) && isset($_POST['exec_date'])) {
                $present = date('Y-m-d', strtotime($_POST['presentation_date']));
                $exec = date('Y-m-d', strtotime($_POST['exec_date']));

                $this->loadModel('regconfig');
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 6)));

                if (!empty($regconfig)) {
                    $a = explode(' ', $regconfig['regconfig']['info_value']);
                    if (!empty($a)) {
                        if ($a[1] == 'M') {
                            $ts1 = strtotime($exec);
                            $ts2 = strtotime($present);

                            $year1 = date('Y', $ts1);
                            $year2 = date('Y', $ts2);

                            $month1 = date('m', $ts1);
                            $month2 = date('m', $ts2);

                            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                        } else {
                            $datediff = strtotime($present) - strtotime($exec);
                            $diff = floor($datediff / (60 * 60 * 24));
                        }

                        if ($regconfig['regconfig']['info_value'] <= $diff) {
                            if ($regconfig['regconfig']['is_boolean'] == 'Y') {
                                if ($regconfig['regconfig']['conf_bool_value'] == 'Y') {
                                    echo 'Y';
                                } else {
                                    echo 'N';
                                }
                            }
                        } else {
                            echo 'R';
                        }
                    } else {
                        echo 'R';
                    }
                } else {
                    echo 'R';
                }
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function getarticledepfeild() {
        try {
            $user_id = $this->Session->read("citizen_user_id");

            $result = ClassRegistry::init('articletrnfields')->find('list', array('fields' => array('articletrnfields.articledepfield_id', 'articletrnfields.articledepfield_value'),
                'conditions' => array('articletrnfields.article_id' => $this->Session->read('article_id'),
                    'articletrnfields.token_no' => $this->Session->read('Selectedtoken'), 'articletrnfields.user_id' => $user_id),
            ));
            echo json_encode($result);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //general infoentry end
    //Property Entry Start

    public function property_details($csrftoken = NULL, $prop_id = NULL) {
        try {
            
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }
// Load Model
            array_map(array($this, 'loadModel'), array('property_details_entry', 'stamp_duty', 'regconfig', 'attribute_parameter', 'articaledepfields', 'articletrnfields', 'parameter', 'items_parameter', 'TrnBehavioralPatterns', 'article_screen_mapping', 'TrnPropertyFields', 'PropertyFields'));
// Declere Variable
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            $doc_lang = $this->Session->read('doc_lang');

            $Selectedtoken = $token = $this->Session->read('Selectedtoken');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 33)));

            $landrecordbutton = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 112));

            $this->set('landrecordbutton', $landrecordbutton);
            $holdingrecordbutton = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 113));
            $remarkconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 132, 'conf_bool_value' => 'Y', 'is_boolean' => 'Y')));
            $this->set('holdingrecordbutton', $holdingrecordbutton);
            //  $prop_boundries=$this->property_boundries_flag();
            $regval = $regconfig['regconfig']['conf_bool_value'];
            $this->set(compact('lang', 'doc_lang', 'Selectedtoken', 'regval', 'remarkconfig'));
            $result = $this->article_screen_mapping->find("all", array('conditions' => array('article_id' => $this->Session->read('article_id'), 'minorfun_id' => 2)));
            if (empty($result)) {
                return $this->redirect(array('action' => 'party_entry', $this->Session->read('csrftoken'))); // screen no avalable to article
            }

// Load data to json file and set variable for ctp
            $json2array = $this->load_json_file();
//---------------------------------EDIT EDIT---------------------------------------------------------------------------------------------  
            if (!$this->request->is('post') and is_numeric($prop_id)) {
                $json2array1 = $this->edit_propertydetails($prop_id);
                if (isset($totaldependency['hfboundaryflag']) && $totaldependency['hfboundaryflag'] == 'Y') {
                    $this->set('hfboundaryflag', 'Y');
                }
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array1));
                $file->close();
            }
//----------------------------------END EDIT-----------------------------------------------------------------------------------------------------    


            $property_list = $this->property_details_entry->get_property_detail_list_edit($lang, $token, $user_id);
            $this->set('property_list', $property_list);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $token, $user_id);
            $this->set('property_pattern', $property_pattern);


            if ($this->request->is('post') || $this->request->is('put')) {
                // $this->check_csrf_token($this->request->data['propertyscreennew']['csrftoken'], 'P');
                $rulelist = NULL;
                if (isset($this->request->data['propertyscreennew']['usage_cat_id'])) {
                    $rulelist = $this->request->data['propertyscreennew']['usage_cat_id'];
                }
                // pr($this->request->data);exit;
                // pr($rulelist);
                // pr($this->request->data['propertyscreennew']['village_id']);
                // exit;
                //   $fielslistaddress = $this->BehavioralPattens->fieldlist(1,$doc_lang,$rulelist,@$this->request->data['propertyscreennew']['village_id']);
                //   $fieldlist_citizen = $this->valuation->fieldlist_citizen($doc_lang,$lang,$rulelist,$regval);
                //   $fielslistaddress = array_merge($fieldlist_citizen, $fielslistaddress); 

                $fieldlist = $this->valuation->fieldlist($rulelist);
                $PropertyFields_list = $this->PropertyFields->fieldlist($rulelist, $doc_lang, @$this->request->data['propertyscreennew']['developed_land_types_id']);

                $fieldlist = array_merge($fieldlist, $PropertyFields_list);

                $fieldlist_citizen = $this->valuation->fieldlist_citizen($doc_lang, $lang, $rulelist, $regval);

                $fielslistaddress = array_merge($fieldlist, $fieldlist_citizen);
                $fieldlist = $this->modifyfieldlist($fieldlist, $this->request->data['propertyscreennew']);
                $requestdata = @$this->request->data['property_details'];
                if (isset($requestdata['pattern_id'])) {
                    foreach ($requestdata['pattern_id'] as $key => $singlefield) {
                        $this->request->data['propertyscreennew']['field_en' . $singlefield] = $requestdata['pattern_value_en'][$key];
                        $this->request->data['propertyscreennew']['field_ll' . $singlefield] = @$requestdata['pattern_value_ll'][$key];
                    }
                }

                $errors = $this->validatedata($this->request->data['propertyscreennew'], $fieldlist);
//pr($errors);exit;
                if ($this->ValidationError($errors)) {
                    if (isset($this->request->data['propertyscreennew']['corp_id']) && !is_numeric($this->request->data['propertyscreennew']['corp_id'])) {
                        $this->request->data['propertyscreennew']['corp_id'] = '';
                    }

                    if (!is_numeric($this->request->data['propertyscreennew']['taluka_id'])) {
                        $this->request->data['propertyscreennew']['taluka_id'] = '';
                    }
                    if (isset($this->request->data['propertyscreennew']['level1_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                        $this->request->data['propertyscreennew']['level1_id'] = NULL;
                    }
                    if (isset($this->request->data['propertyscreennew']['level1_list_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_list_id'])) {
                        $this->request->data['propertyscreennew']['level1_list_id'] = NULL;
                    }

                    $this->post_back_valuation_data($json2array);
                    $unique_record_id = '';
                    if (isset($this->request->data['propertyscreennew']['property_id']) and $this->request->data['propertyscreennew']['property_id'] == $this->Session->read('prop_edit_id')) {
                        $unique_record_id = $this->Session->read('prop_edit_id');
                    }
                    $this->request->data['propertyscreennew']['token_no'] = $token;
                    $this->request->data['propertyscreennew']['user_id'] = $user_id;
                    $this->property_details_entry->create();
                    $this->request->data['propertyscreennew']['property_id'] = $unique_record_id;

                    $last_prop_id = $unique_record_id;
                    $this->request->data['propertyscreennew']['user_type'] = $this->Session->read("session_usertype");

                    //sro userid
                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['propertyscreennew']['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $this->request->data['propertyscreennew']['org_user_id'] = $this->Auth->User('user_id');
                            if (is_numeric($this->request->data['propertyscreennew']['property_id'])) {

                                $this->request->data['propertyscreennew']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['propertyscreennew']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                    }
                    // pr($this->request->data['propertyscreennew']);exit;
                    if ($this->property_details_entry->save($this->request->data['propertyscreennew'])) {
                        $last_prop_id = $this->property_details_entry->getLastInsertID();

                        if (empty($last_prop_id)) {  // ON update Null
                            $last_prop_id = $unique_record_id;
                        }

                        if (isset($this->request->data['propertyscreennew']['usage_cat_id']) and ! empty($this->request->data['propertyscreennew']['usage_cat_id'])) {
// SET DELETE FLAG FOR VALUATION                  
                            $this->valuation->query("UPDATE ngdrstab_trn_valuation SET delete_flag='Y' WHERE property_id=$last_prop_id AND token_no=$token ");
                            $this->request->data['propertyscreennew']['property_id'] = $last_prop_id;
                            $this->request->data['propertyscreennew']['token_no'] = $token;
                            unset($this->request->data['propertyscreennew']['property_id']); // other wise updates valuation Records
                            $result = $this->property_valuation();
                            $val_id = is_numeric($result) ? $this->request->data['propertyscreennew']['val_id'] : 0;
                            $this->property_details_entry->query("UPDATE ngdrstab_trn_property_details_entry  SET val_id=$val_id WHERE property_id=$last_prop_id AND token_no=$token  ");
                        }

// Usage Items Entry (for Multiple usage and multiple item)
                        foreach ($json2array['usageitemlist'] as $usageitem) {
                            $this->items_parameter->save_item_prameter($this->request->data['propertyscreennew'], $usageitem, $last_prop_id, $token, $user_id, $this->Session->read("session_usertype"));
                        }
//  For Attribute Entry
// ( Delete   Existing Entry For Edit)
                        $this->parameter->deleteAll(['property_id' => $last_prop_id, 'token_id' => $token]);

                        if (isset($json2array['prop_attributes_seller'])) {

                            $this->parameter->save_parameter($json2array['prop_attributes_seller'], $last_prop_id, $token, $user_id, 'S', $this->Session->read("session_usertype"));
                        }
                        if ($regval == 'Y') {
                            if (isset($json2array['prop_attributes_pur'])) {
                                $this->parameter->save_parameter($json2array['prop_attributes_pur'], $last_prop_id, $token, $user_id, 'P', $this->Session->read("session_usertype"));
                            }
                        }

//  For Behavioral Pattens Entry //( Delete   Existing Entry For Edit)
                        $this->TrnBehavioralPatterns->deletepattern($token, $user_id, $last_prop_id, 1);
                        if (isset($this->request->data['property_details']['pattern_id'])) {
                            $this->TrnBehavioralPatterns->savepattern($token, $user_id, $last_prop_id, $this->request->data['property_details'], 1, $this->Session->read("session_usertype"));
                        }
                        $this->TrnPropertyFields->deletepropertyfields($token, $last_prop_id);
                        if (isset($this->request->data['property_fields']['field_id'])) {
                            $this->TrnPropertyFields->savepropertyfields($token, $user_id, $last_prop_id, $this->request->data['property_fields'], $this->Session->read("session_usertype"));
                            // pr($this->request->data['property_fields']);exit;
                        }
                        $this->stamp_duty->updateAll(array('stamp_duty.recalculate_flag' => "'Y'"), //fields to update
                                array('stamp_duty.token_no' => $this->Session->read("Selectedtoken"))  //condition
                        );
                        $this->Session->setFlash(("Record Saved Succefully"));
                    } else {
                        $this->Session->setFlash(("Record Not Saved"));
                    }
                } else {
                    $this->Session->setFlash('Check Validation  Error!');
                }
//$this->Session->setFlash(($this->Session->read('csrftoken')));
                $this->set_csrf_token();
                $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
            } else {

                $propgroup = 0;
                $propgroupconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 160, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($propgroupconf)) {
                    if (is_numeric($token)) {
                        $info = $this->genernalinfoentry->query("select info.token_no from ngdrstab_trn_generalinformation as info 
JOIN ngdrstab_mst_article as article ON  article.article_id=info.article_id and article.exchange_property_flag='Y'
where info.token_no=?", array($token));

                        if (!empty($info)) {
                            $propgroup = 1;
                        }
                    }
                }
                $fieldlistmultiform['propertyscreennew'] = $this->valuation->fieldlist();

                $fielslistaddress = $this->BehavioralPattens->fieldlist(1, $doc_lang);

                $fieldlist_citizen = $this->valuation->fieldlist_citizen($doc_lang, $lang, NULL, $regval);
                if ($propgroup) {
                    $fieldlist_citizen['property_group_flag']['select'] = 'is_alpha_select';
                }
                $fielslistaddress = array_merge($fieldlist_citizen, $fielslistaddress);
                $fieldlistmultiform['propertyscreennew'] = array_merge($fieldlistmultiform['propertyscreennew'], $fielslistaddress);

                $fieldlistmultiform['propertyscreennew'] = array_merge($fieldlistmultiform['propertyscreennew'], $this->PropertyFields->fieldlist());
                //exit;
                $fieldlistmultiform['copyvaluation']['valuation_id']['text'] = 'is_required,is_numeric';
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }
        } catch (Exception $ex) {
            pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

// Property Details modify field list
    public function modifyfieldlist($fieldlist, $data) {
        try {

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $errors = array();
            unset($fieldlist['corp_id']);

            if (!isset($json2array['level1']) || empty($json2array['level1']) || $json2array['level1'] == NULL) {
                unset($fieldlist['level1_id']);
                if (isset($this->request->data['propertyscreennew']['level1_id'])) {
                    unset($this->request->data['propertyscreennew']['level1_id']);
                }
            }
            if (!isset($json2array['level1list']) || empty($json2array['level1list']) || $json2array['level1list'] == NULL || !isset($fieldlist['level1_id'])) {
               if (!is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                unset($fieldlist['level1_list_id']);
                if (isset($this->request->data['propertyscreennew']['level1_list_id'])) {
                    unset($this->request->data['propertyscreennew']['level1_list_id']);
                }
               }
            }

            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfconstructionflag'] == 'N') {
                unset($fieldlist['construction_type_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfdepreciationflag'] == 'N') {
                unset($fieldlist['depreciation_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfroadvicinityflag'] == 'N') {
                unset($fieldlist['road_vicinity_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfuserdependency1flag'] == 'N') {
                unset($fieldlist['user_defined_dependency1_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfuserdependency2flag'] == 'N') {
                unset($fieldlist['user_defined_dependency2_id']);
            }

            return $fieldlist;
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function copyvaluation() {
        $this->loadModel('valuation');
        $this->loadModel('property_details_entry');
        $token = $this->Session->read('Selectedtoken');
        $user_id = $this->Session->read('citizen_user_id');
        $val_id = @$this->request->data['copyvaluation']['valuation_id'];
        if (is_numeric($val_id)) {
            $result = $this->valuation->find("first", array('conditions' => array('val_id' => $val_id, 'user_id' => $user_id)));
        }
        if (!empty($result)) {
            $result['valuation']['token_no'] = $token;
            $result['valuation']['user_id'] = $this->Session->read('citizen_user_id');
            if ($this->property_details_entry->save($result['valuation'])) {
                $this->Session->setFlash(__('Record Added Successfully!'));
            } else {
                $this->Session->setFlash(__('Record Added Successfully!'));
            }
        } else {
            $this->Session->setFlash(__('No Record Found'));
        }
        $this->set_csrf_token();
        return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken')));
    }

    public function edit_propertydetails($prop_id = NULL) {
        try {
            //  $this->check_csrf_token($csrftoken);
            if (is_numeric($prop_id)) {
                $prop_result = $this->property_details_entry->find("all", array('conditions' => array('property_id' => $prop_id)));
                $this->set("prop_result", $prop_result);
                if (empty($prop_result)) {
                    $this->Session->setFlash("Property Not Found");
                    return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
                }
                $this->Session->write('prop_edit_id', $prop_id);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $vobj = new PropertyController();
                $vobj->constructClasses();
                $this->request->data["propertyscreennew"] = $prop_result[0]['property_details_entry'];
                if (is_numeric($prop_result['0']['property_details_entry']['val_id'])) {
                    $vresult = $vobj->editvaluation($prop_result['0']['property_details_entry']['val_id']);
                    $this->set('district_id', $prop_result['0']['property_details_entry']['district_id']);
                    $this->set('defaultval_flag', 'Y');

                    if (empty($vresult['Error'])) {
                        $this->request->data["propertyscreennew"] = array_merge($this->request->data["propertyscreennew"], $vresult['VData']);
                        $json2array = array_merge($json2array, $vresult['Datalist']);
                        if (isset($vresult['Datalist']['PropertyFields'])) {
                            $this->set('PropertyFields', $vresult['Datalist']['PropertyFields']);
                        }
                    }
                }
                $TrnPropertyFields = $this->TrnPropertyFields->find("all", array('conditions' => array('property_id' => $prop_id)));
                $attributes_result = $this->parameter->find("all", array('conditions' => array('property_id' => $prop_id)));

                $prop_attributes_seller = array();
                $prop_attributes_pur = array();
                foreach ($attributes_result as $key => $attributes) {

                    if ($attributes['parameter']['parameter_type'] == 'S') {
                        array_push($prop_attributes_seller, array('attribute_id' => $attributes['parameter']['paramter_id'], 'attribute_value' => $attributes['parameter']['paramter_value'], 'attribute_value1' => $attributes['parameter']['paramter_value1'], 'attribute_value2' => $attributes['parameter']['paramter_value2']));
                    } else if ($attributes['parameter']['parameter_type'] == 'P') {
                        array_push($prop_attributes_pur, array('attribute_id' => $attributes['parameter']['paramter_id'], 'attribute_value' => $attributes['parameter']['paramter_value'], 'attribute_value1' => $attributes['parameter']['paramter_value1'], 'attribute_value2' => $attributes['parameter']['paramter_value2']));
                    }
                }
                if (isset($prop_attributes_seller)) {
                    $json2array['prop_attributes_seller'] = $prop_attributes_seller;
                    $this->set('prop_attributes_seller', $prop_attributes_seller);
                }

                if (isset($prop_attributes_pur)) {
                    $json2array['prop_attributes_pur'] = $prop_attributes_pur;
                    $this->set('prop_attributes_pur', $prop_attributes_pur);
                }


                if (isset($prop_id) and is_numeric($prop_id)) {
                    $trnbehavioral = $this->TrnBehavioralPatterns->find("all", array('conditions' => array('mapping_ref_id' => 1, 'mapping_ref_val' => $prop_id)));
                    $this->set('trnbehavioral', $trnbehavioral);
                }

                $this->post_back_valuation_data($json2array, $prop_result[0]['property_details_entry']);
                $this->set('attributes', $json2array['attributes']);
                return $json2array;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function add_property_attribute() {
        try {
            $this->loadModel("regconfig");
            $regconf_attr = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 151, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            //  pr($json2array['prop_attributes_seller']);
            if (@$this->request->data['action'] == 'remove') {
                if ($this->request->data['type'] == 'S') {
                    if (isset($json2array['prop_attributes_seller'][$this->request->data['attribute_index_id']])) {
                        unset($json2array['prop_attributes_seller'][$this->request->data['attribute_index_id']]);
                    }
                    $prop_attributes = $json2array['prop_attributes_seller'];
                }if ($this->request->data['type'] == 'P') {
                    if (isset($json2array['prop_attributes_pur'][$this->request->data['attribute_index_id']])) {
                        unset($json2array['prop_attributes_pur'][$this->request->data['attribute_index_id']]);
                    }
                    $prop_attributes = $json2array['prop_attributes_pur'];
                }
            } else {

                if ($this->request->data['type'] == 'S') {
                    if (isset($json2array['prop_attributes_seller'])) {
                        $prop_attributes = $json2array['prop_attributes_seller'];
                    } else {
                        $prop_attributes = array();
                    }
                    if (!empty($regconf_attr)) {
                        foreach ($prop_attributes as $key => $value) {
                            if ($this->request->data['attribute_id'] == $value['attribute_id']) {
                                unset($prop_attributes[$key]);
                            }
                        }
                    }

                    array_push($prop_attributes, array('attribute_id' => $this->request->data['attribute_id'], 'attribute_value' => $this->request->data['attribute_value'], 'attribute_value1' => $this->request->data['attribute_value1'], 'attribute_value2' => $this->request->data['attribute_value2']));
                    $json2array['prop_attributes_seller'] = $prop_attributes;
                } else if ($this->request->data['type'] == 'P') {
                    if (isset($json2array['prop_attributes_pur'])) {
                        $prop_attributes = $json2array['prop_attributes_pur'];
                    } else {
                        $prop_attributes = array();
                    }
                    if (!empty($regconf_attr)) {
                        foreach ($prop_attributes as $key => $value) {
                            if ($this->request->data['attribute_id'] == $value['attribute_id']) {
                                unset($prop_attributes[$key]);
                            }
                        }
                    }
                    array_push($prop_attributes, array('attribute_id' => $this->request->data['attribute_id'], 'attribute_value' => $this->request->data['attribute_value'], 'attribute_value1' => $this->request->data['attribute_value1'], 'attribute_value2' => $this->request->data['attribute_value2']));
                    $json2array['prop_attributes_pur'] = $prop_attributes;
                }
            }

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
            $this->set('prop_attributes', $prop_attributes);
            $this->set('attributes', $json2array['attributes']);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function property_remove($id = NULL, $flag = NULL) {
        try {

            if (is_numeric($id)) {
                array_map(array($this, 'loadModel'), array('property_details_entry', 'parameter', 'TrnBehavioralPatterns', 'fees_calculation', 'fees_calculation_detail', 'party_entry', 'TrnPropertyFields'));
                $user_id = $this->Session->read("citizen_user_id");
                $token = $this->Session->read('Selectedtoken');




                $this->parameter->deleteAll(['property_id' => $id, 'token_id' => $token]);
                $this->TrnBehavioralPatterns->deleteAll(['mapping_ref_val' => 1, 'mapping_ref_val' => $id, 'token_no' => $token]);
                $this->TrnPropertyFields->deletepropertyfields($token, $id);
                if ($this->property_details_entry->deleteAll(['property_details_entry.property_id' => $id, 'token_no' => $token])) {


                    $fees_cal = $this->fees_calculation->find('all', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $token, 'property_id' => $id, 'article_id' => array($this->Session->read('article_id'), 9998)
                    )));
                    if (!empty($fees_cal)) {

                        foreach ($fees_cal as $fee) {

                            $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $fee['fees_calculation']['fee_calc_id']));
                        }
                        $this->fees_calculation->deleteAll(array('token_no' => $token, 'property_id' => $id, 'article_id' => array($this->Session->read('article_id'), 9998)));
                    }
                    $party = $this->party_entry->find('all', array('conditions' => array('token_no' => $token, 'property_id' => $id)));
                    if (!empty($party)) {
                        $this->party_entry->deleteAll(array('token_no' => $token, 'property_id' => $id));
                    }

                    if ($flag == 'G') {
                        return true;
                    } else {
                        $this->Session->setFlash(("Record Deleted Successfully"));
                        $this->redirect(array('controller' => 'citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken')));
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function witness($csrftoken = NULL) {
        try {
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }
//load Model
            $stateid = $this->Auth->User("state_id");
            $this->set('stateid', $stateid);


            array_map(array($this, 'loadModel'), array('witness', 'witness_fields', 'identificatontype', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'witness_type'));
            $popupstatus = $actiontypeval = $hfid = $hfupdateflag = $hfactionval = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $witness = $this->witness->get_allwitness($doc_lang, $tokenval, $user_id); //            pr($witness);exit;
            $this->set('witness', $witness);
            $alllevel = $this->doc_levels->get_alllevel();

//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Selectedtoken', 'language', 'witness', 'doclevels'));

//Status check box code            
            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $this->set(compact('lang', 'Selectedtoken', 'popupstatus', 'exemption', 'actiontypeval', 'hfid', 'hfupdateflag', 'hfactionval', 'districtdata', 'taluka', 'party_category', 'occupation', 'gender', 'salutation', 'property', 'property_list', 'property_pattern', 'party_record'));
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            // validations kalyani
            $doc_lang = $this->Session->read('doc_lang');
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $language . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist = $this->witness_fields->fieldlist($doc_lang);


            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {


                $hfid = $_POST['hfid'];
                $this->check_csrf_token($this->request->data['witness']['csrftoken']);


                if (!isset($this->request->data['witness']['village_id'])) {
                    $village_id = NULL;
                } else {
                    $village_id = $this->request->data['witness']['village_id'];
                }
                $fieldlist = $this->witness_fields->fieldlist($doc_lang, $village_id);

                $this->set('hfid', $_POST['hfid']);
                $name_format = $this->get_name_format();

                if (isset($this->request->data['witness']['witness_full_name_en'])) {
                    if ($this->request->data['witness']['witness_full_name_en'] == '') {
                        $this->request->data['witness']['witness_full_name_en'] = isset($this->request->data['witness']['fname_en']) ? $this->request->data['witness']['fname_en'] . ' ' . $this->request->data['witness']['mname_en'] . ' ' . $this->request->data['witness']['lname_en'] : '';
                    } else {
                        $this->request->data['witness']['witness_full_name_en'] = $this->request->data['witness']['witness_full_name_en'];
                    }
                } else {
                    $this->request->data['witness']['witness_full_name_en'] = isset($this->request->data['witness']['fname_en']) ? $this->request->data['witness']['fname_en'] . ' ' . $this->request->data['witness']['mname_en'] . ' ' . $this->request->data['witness']['lname_en'] : '';
                }
                if (isset($this->request->data['witness']['witness_full_name_ll'])) {
                    if ($this->request->data['witness']['witness_full_name_ll'] == '') {
                        $this->request->data['witness']['witness_full_name_ll'] = isset($this->request->data['witness']['fname_ll']) ? $this->request->data['witness']['fname_ll'] . ' ' . $this->request->data['witness']['mname_ll'] . ' ' . $this->request->data['witness']['lname_ll'] : '';
                    } else {
                        $this->request->data['witness']['witness_full_name_ll'] = $this->request->data['witness']['witness_full_name_ll'];
                    }
                } else {
                    $this->request->data['witness']['witness_full_name_ll'] = isset($this->request->data['witness']['fname_ll']) ? $this->request->data['witness']['fname_ll'] . ' ' . $this->request->data['witness']['mname_ll'] . ' ' . $this->request->data['witness']['lname_ll'] : '';
                }
                $this->request->data['witness']['witness_full_name_en'] = ucwords($this->request->data['witness']['witness_full_name_en']);



                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
//                    pr($this->request->data);

                    foreach ($bdata as $datafield) {
                        foreach ($datafield as $key => $fieldid) {
                            $this->request->data['witness']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                            //  pr($field);
                            if (isset($bdata['pattern_value_ll'][$key])) {
                                $this->request->data['witness']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                            }
                        }
                    }
                }
                $this->set('hfactionval', $hfactionval);



//validations kalyani
                $this->request->data['witness'] = $this->istrim($this->request->data['witness']);
                if (isset($this->request->data['witness']['identificationtype_id']) && $this->request->data['witness']['identificationtype_id']) {
                    $rule = $this->witness->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['witness']['identificationtype_id']);
                    if ($rule) {
                        $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                    }
                }

                $errarr = $this->validatedata($this->request->data['witness'], $fieldlist);

                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                } else {

                    if (isset($this->request->data['witness']['identificationtype_id']) && $this->request->data['witness']['identificationtype_id'] == 9999) {
                        $this->request->data['witness']['identificationtype_desc_en'] = $this->enc($this->request->data['witness']['identificationtype_desc_en']);
                    }
                    if (isset($this->request->data['witness']['uid_no']) && is_numeric($this->request->data['witness']['uid_no'])) {
                        $this->request->data['witness']['uid_no'] = $this->enc($this->request->data['witness']['uid_no']);
                    }
                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['witness']['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $this->request->data['witness']['org_user_id'] = $this->Auth->User('user_id');
                            if (is_numeric($hfid)) {

                                $this->request->data['witness']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['witness']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                    }
                    if ($this->save_witness($this->request->data['witness'], $tokenval, $stateid, $user_id, $hfid, $this->Session->read("session_usertype"))) {
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $witnessid = $this->request->data['witness']['id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                        } else {
                            $witnessid = $this->witness->getLastInsertID();
                            $actionvalue = "Saved";
                        }
                        if (isset($this->request->data['property_details']['pattern_id'])) {
                            $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $witnessid, 3);

                            $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $witnessid, $this->request->data['property_details'], 3, $this->Session->read("session_usertype"));
                        }
                        $this->Session->setFlash(__("Record $actionvalue Successfully"));
                    } else {
                        $this->Session->setFlash(__("Record Not save"));
                    }
                }

                $this->set_csrf_token();
                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $this->Session->read('csrftoken')));
            } else {
                // $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function save_witness($data, $tokenval, $stateid, $user_id, $hfid, $user_type) {
        try {

            array_map([$this, 'loadModel'], ['witness']);
            $pan = isset($data['pan_no']) ? ($data['pan_no']) : ('');
            $mobile = isset($data['mobile_no']) ? ($data['mobile_no']) : ('');
            $uid = isset($data['uid_no']) ? ($data['uid_no']) : ('');
            $email = isset($data['email_id']) ? ($data['email_id']) : ('');

            $data['token_no'] = $tokenval;
            $data['state_id'] = $stateid;
            $data['user_id'] = $user_id;
            $data['user_type'] = $user_type;

            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            if (isset($data['dob']) && $data['dob'] != NULL) {
                $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
            }
            if ($hfid) {
                $action = 'U';

                //if ($this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
                $this->witness->id = $hfid;
                $this->witness->save($data);
                return true;
                //} else {
                //return false;
                // }
            } else {

                $action = 'S';
                //  if ($this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
                $this->witness->save($data);
                return true;
                //} else {
                //   return false;
                //}
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function behavioral_patterns() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('Behavioural');
            $this->loadModel('BehaviouralDetails');
            $this->loadModel('BehavioralPattens');
            $this->loadModel('VillageMapping');
            $this->loadModel('TrnBehavioralPatterns');
            $doc_lang = $this->Session->read('doc_lang');
            $trnbehavioral = array();
            //ref_id=screen_id
            if ($_POST['ref_id'] == 1 || $_POST['ref_id'] == 2 || $_POST['ref_id'] == 3 || $_POST['ref_id'] == 4 || $_POST['ref_id'] == 5 || $_POST['ref_id'] == 9999 && is_numeric($_POST['ref_id']) && is_numeric($_POST['behavioral_id'])) {

                if (isset($this->request->data['village_id'])) {
                    $village_id = $this->request->data['village_id'];
                } else {
                    $village_id = NULL;
                }
                $behavioral_id = $this->request->data['behavioral_id'];
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang, 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id'), 'conditions' => array('village_id' => $village_id)));
                if (!empty($villagedetails)) {
                    $land_type = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                } else {
                    $land_type = 'U';
                }

                if ($land_type == '1') {
                    $land_flag = "U";
                } else if ($land_type == '2') {
                    $land_flag = "R";
                } else if ($land_type == '3') {
                    $land_flag = "I";
                } else {
                    $land_flag = "U";
                }
                //usage_id
                $BehavioralPatterns = array();
                if (isset($_POST['usage_id']) && is_numeric($_POST['usage_id'])) {
                    $main_cat_id = ClassRegistry::init('usage_category')->field('usage_main_catg_id', array('evalrule_id' => $_POST['usage_id']));
                    $BehavioralPatterns = $this->BehavioralPattens->query("select * from   ngdrstab_conf_behavioral behavioral,   ngdrstab_conf_behavioral_details details, ngdrstab_conf_behavioral_patterns patterns where behavioral.behavioral_id=details.behavioral_id AND details.behavioral_details_id=patterns.behavioral_details_id AND patterns.behavioral_id=1 AND details.developed_land_types_flag=?  AND details.main_usage_id=?", array($land_flag, $main_cat_id));
                } else {

                    $BehavioralPatterns = $this->BehavioralPattens->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=$behavioral_id AND behavioral.behavioral_id=details.behavioral_id  AND details.developed_land_types_flag='$land_flag' ");
                }
                $this->set("BehavioralPatterns", $BehavioralPatterns);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['BehavioralPatterns'] = $BehavioralPatterns;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
            }
            if (isset($_POST['ref_val']) and is_numeric($_POST['ref_val'])) {
                $trnbehavioral = $this->TrnBehavioralPatterns->find("all", array('conditions' => array('mapping_ref_id' => $_POST['ref_id'], 'mapping_ref_val' => $_POST['ref_val'])));
            }
            $this->set("trnbehavioral", $trnbehavioral);
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function mst_behavioral_patterns() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('Behavioural');
            $this->loadModel('BehaviouralDetails');
            $this->loadModel('BehavioralPattens');
            $this->loadModel('VillageMapping');
            $this->loadModel('TrnBehavioralPatterns');
            $this->loadModel('mainlanguage');

            $lang = $this->Session->read('sess_langauge');
            $trnbehavioral = array();
//ref_id=screen_id
            if ($_POST['ref_id'] == 1 || $_POST['ref_id'] == 9999 || $_POST['ref_id'] == 2 || $_POST['ref_id'] == 3 || $_POST['ref_id'] == 4 || $_POST['ref_id'] == 5 && is_numeric($_POST['ref_id']) && is_numeric($_POST['village_id']) && is_numeric($_POST['behavioral_id'])) {

                $village_id = $this->request->data['village_id'];
                $behavioral_id = $this->request->data['behavioral_id'];
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_' . $lang, 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id'), 'conditions' => array('village_id' => $village_id)));
                $land_type = $villagedetails[0]['VillageMapping']['developed_land_types_id'];

                if ($land_type == '1') {
                    $land_flag = "U";
                } else if ($land_type == '2') {
                    $land_flag = "R";
                } else if ($land_type == '3') {
                    $land_flag = "I";
                } else {
                    $land_flag = "U";
                }
//usage_id
                $BehavioralPatterns = array();
                if (isset($_POST['usage_id']) && is_numeric($_POST['usage_id'])) {
                    $main_cat_id = ClassRegistry::init('usage_category')->field('usage_main_catg_id', array('evalrule_id' => $_POST['usage_id']));
                    $BehavioralPatterns = $this->BehavioralPattens->query("select * from   ngdrstab_conf_behavioral behavioral,   ngdrstab_conf_behavioral_details details, ngdrstab_conf_behavioral_patterns patterns where behavioral.behavioral_id=details.behavioral_id AND details.behavioral_details_id=patterns.behavioral_details_id AND patterns.behavioral_id=1 AND details.developed_land_types_flag=?  AND details.main_usage_id=?", array($land_flag, $main_cat_id));
                } else {

                    $BehavioralPatterns = $this->BehavioralPattens->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=$behavioral_id AND behavioral.behavioral_id=details.behavioral_id  AND details.developed_land_types_flag='$land_flag' ");
                }
                $this->set("BehavioralPatterns", $BehavioralPatterns);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['BehavioralPatterns'] = $BehavioralPatterns;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
            }
            if (isset($_POST['ref_val']) and is_numeric($_POST['ref_val'])) {
                $trnbehavioral = $this->TrnBehavioralPatterns->find("all", array('conditions' => array('mapping_ref_id' => $_POST['ref_id'], 'mapping_ref_val' => $_POST['ref_val'])));
            }
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);
            $this->set("trnbehavioral", $trnbehavioral);
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function major_functions() {
        try {
            $this->loadModel('majorfunction');
            $result = $this->majorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'N')));
            return $result;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function minor_functions() {
        try {


            $this->loadModel('minorfunction');
            $this->loadModel('conf_reg_bool_info');
            $sro_appr_flag = $this->conf_reg_bool_info->field('conf_bool_value', array('reginfo_id' => 125));
            if ($sro_appr_flag == 'Y') {
                if (is_numeric($this->Session->read('Selectedtoken'))) {
                    $sro_approval = ClassRegistry::init('genernalinfoentry')->field('sro_approve_flag', array('token_no' => $this->Session->read("Selectedtoken")));

                    if ($sro_approval == 'Y') {


                        if ($this->Session->read("manual_flag") == 'Y') {
                            $result = $this->minorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                        } else {
                            $result = $this->minorfunction->find("all", array('conditions' => array('citizen_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                        }
                    } else {
                        $result = $this->minorfunction->find('all', array('conditions' => array('citizen_flag' => 'Y', 'delete_flag' => 'N', 'id' => array(1, 9)), 'order' => 'mf_serial ASC'));
                    }
                } else {
                    $result = $this->minorfunction->find('all', array('conditions' => array('citizen_flag' => 'Y', 'delete_flag' => 'N', 'id' => array(1, 9)), 'order' => 'mf_serial ASC'));
                }
            }else  if ($this->Session->read("authinfo") == 'Y') {
                   // $result = $this->minorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                    $result = $this->minorfunction->find('all', array('conditions' => array('auth_flag' => 'Y', 'delete_flag' => 'N'), 'order' => 'mf_serial ASC'));
                } else if($this->Session->read('legacyinfo') == 'Y'){
                    $result = $this->minorfunction->find("all", array('conditions' => array('legacy_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                }

            else {


                if ($this->Session->read("manual_flag") == 'Y') {
                    $result = $this->minorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                } else {
                    $result = $this->minorfunction->find("all", array('conditions' => array('citizen_flag' => 'Y', 'delete_flag' => 'N'), 'order' => array('mf_serial ASC')));
                }
            }

            return $result;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function article_mapping_screen() {
        try {
            $this->loadModel('article_screen_mapping');
            $result = $this->article_screen_mapping->find("all", array('conditions' => array('article_id' => $this->Session->read('article_id'))));
            return $result;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function article_change_event() {
        try {
            if (isset($this->request->data['article_id']) and is_numeric($this->request->data['article_id'])) {
                $this->loadModel('articaledepfields');
                $article_id = $this->request->data['article_id'];
                $result = $this->articaledepfields->find("all", array('conditions' => array('article_id' => $article_id, 'party_or_property_flag' => 'Article')));
                $this->set('result', $result);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function data_entry_status() {
        try {
            array_map(array($this, 'loadModel'), array('genernalinfoentry', 'property_details_entry', 'party_entry', 'conf_reg_bool_info', 'witness', 'appointment', 'ApplicationSubmitted', 'leaseandlicense', 'stamp_duty', 'CitizenPaymentEntry', 'uploaded_file_trn', 'identification', 'article_screen_mapping', 'BankPayment', 'valuation_details'));

            $token = $this->Session->read('Selectedtoken');
            $article_id = $this->Session->read('article_id');

            $status = array();
            $result = $this->genernalinfoentry->find("all", array('conditions' => array('token_no' => $token)));
            $status['1'] = count($result);
            $result = $this->property_details_entry->find("all", array('conditions' => array('token_no' => $token, 'val_id !=' => 0)));
            $status['2'] = count($result);
            $result = $this->leaseandlicense->find("all", array('conditions' => array('token_no' => $token)));
            $status['3'] = count($result);



            //--------------------------------stamp_duty Calculation Flag Checking updated by sonam- 10-1-2019 ----------------------------------------------------------
            $result = $this->stamp_duty->find("all", array('conditions' => array('token_no' => $token)));
            $property_exists = $this->article_screen_mapping->find('count', array('conditions' => array('minorfun_id' => 2, 'article_id' => $article_id))); //2 for Property 

            $flag = $this->get_prop_same_usage_flag($token);

            if ($article_id != 32 && $flag == 1) {
                $status['4'] = ($property_exists) ? (($this->property_details_entry->find('count', array('conditions' => array('fee_calc_id IS NULL', 'token_no' => $token, 'val_id !=' => 0))) ? 0 : 1)) : count($result);
            } else {
                $status['4'] = $this->stamp_duty->find("all", array('conditions' => array('token_no' => $token)));
            }
            if (empty($result)) {
                $status['4'] = 0;
            }
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------stamp_duty Calculation Flag Checking updated by Shridhar- 23-May-2017 ----------------------------------------------------------
//            $result = $this->stamp_duty->find("all", array('conditions' => array('token_no' => $token)));
//            $property_exists = $this->article_screen_mapping->find('count', array('conditions' => array('minorfun_id' => 2, 'article_id' => $article_id))); //2 for Property 
//            if ($article_id != 32) {
//                $status['4'] = ($property_exists) ? (($this->property_details_entry->find('count', array('conditions' => array('fee_calc_id IS NULL', 'token_no' => $token, 'val_id !=' => 0))) ? 0 : 1)) : count($result);
//            } else {
//                $status['4'] = $this->stamp_duty->find("all", array('conditions' => array('token_no' => $token)));
//            }
//            if (empty($result)) {
//                $status['4'] = 0;
//            }
//--------------------------------------------------------------------------------------------------------------------------
            $result = $this->BankPayment->find("all", array('conditions' => array('token_no' => $token, 'payment_status' => 'SUCCESS')));
            $status['5'] = count($result);

            $partytype_name = $this->party_entry->get_partyname_forstatus($token);


            $partytype_count = $this->party_entry->get_applicable_party_count($article_id);

            if (isset($token)) {
                $poa_individual_exist = $this->party_entry->query("select party_id from ngdrstab_trn_party_entry_new where token_no=$token and party_catg_id = 24");
            }
            if (!empty($poa_individual_exist)) {
                $poa_individual_exist_count = count($poa_individual_exist);
            }

            if ($partytype_count == 'N') {
             //   pr($partytype_name);exit;
                if (!empty($partytype_name)) {
                    if (count($partytype_name) == 2) {
                        if ($partytype_name[0]['pt']['party_type_flag'] == 0 && $partytype_name[1]['pt']['party_type_flag'] == 1) {
                            if (!empty($poa_individual_exist)) {
                                $count = 0;
                                foreach ($poa_individual_exist as $poa_individual_exist1) {
                                    $party_id_poa = $poa_individual_exist1[0]['party_id'];
                                    $poa_entry = $this->party_entry->query("select power_attoney_party_id from ngdrstab_trn_party_entry_new where token_no=$token and party_catg_id = 4 and power_attoney_party_id=$party_id_poa");
                                    if (!empty($poa_entry)) {
                                        $count++;
                                    }
                                }
                                if ($poa_individual_exist_count == $count) {
                                    $is_presenter = $this->party_entry->find("all", array('fields' => array('is_presenter'), 'conditions' => array('is_presenter' => 'Y', 'token_no' => $token)));
                                    if (count($is_presenter) > 0) {
                                        $trible = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 82)));
                                        if ($trible['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                            if ($this->check_party_cast($token)) {
                                                $status['6'] = 1;
                                            } else {
                                                $status['6'] = 0;
                                            }
                                        } else {
                                            $status['6'] = 1;
                                        }
                                    } else {
                                        $status['6'] = 0;
                                    }
                                }
                            } else {
                                $is_presenter = $this->party_entry->find("all", array('fields' => array('is_presenter'), 'conditions' => array('is_presenter' => 'Y', 'token_no' => $token)));
                                if (count($is_presenter) > 0) {
                                    $trible = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 82)));
                                    if ($trible['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                        if ($this->check_party_cast($token)) {
                                            $status['6'] = 1;
                                        } else {
                                            $status['6'] = 0;
                                        }
                                    } else {
                                        $status['6'] = 1;
                                    }
                                } else {
                                    $status['6'] = 0;
                                }
                            }
                        }
                    }
                }
            } elseif ($partytype_count == 'Y') {
                if (!empty($partytype_name)) {

                    if (!empty($poa_individual_exist)) {
                        //  if ($partytype_name[0]['pt']['party_type_flag'] == 0) {
                        $count = 0;
                        foreach ($poa_individual_exist as $poa_individual_exist1) {
                            $party_id_poa = $poa_individual_exist1[0]['party_id'];
                            $poa_entry = $this->party_entry->query("select power_attoney_party_id from ngdrstab_trn_party_entry_new where token_no=$token and party_catg_id = 4 and power_attoney_party_id=$party_id_poa");
                            if (!empty($poa_entry)) {
                                $count++;
                            }
                        }

                        //  if ($partytype_name[0]['pt']['party_type_flag'] == 0) {
                        if ($poa_individual_exist_count == $count) {
                            $is_presenter = $this->party_entry->find("all", array('fields' => array('is_presenter'), 'conditions' => array('is_presenter' => 'Y', 'token_no' => $token)));
                            if (count($is_presenter) > 0) {
                                $trible = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 82)));
                                if ($trible['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if ($this->check_party_cast($token)) {
                                        $status['6'] = 1;
                                    } else {
                                        $status['6'] = 0;
                                    }
                                } else {
                                    $status['6'] = 1;
                                }
                            } else {
                                $status['6'] = 0;
                            }
                            // }
                        }
                    } else {
                        $is_presenter = $this->party_entry->find("all", array('fields' => array('is_presenter'), 'conditions' => array('is_presenter' => 'Y', 'token_no' => $token)));
                        if (count($is_presenter) > 0) {
                            $trible = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 82)));
                            if ($trible['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                if ($this->check_party_cast($token)) {
                                    $status['6'] = 1;
                                } else {
                                    $status['6'] = 0;
                                }
                            } else {
                                $status['6'] = 1;
                            }
                        } else {
                            $status['6'] = 0;
                        }
                    }
                }
            }

            $result = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token)));
            $status['12'] = 1;

            $result = $this->witness->find("all", array('conditions' => array('token_no' => $token)));
            $status['7'] = count($result);
            $result = $this->appointment->find("all", array('conditions' => array('token_no' => $token)));
            $status['8'] = 1;

            $result = $this->uploaded_file_trn->find("all", array('conditions' => array('token_no' => $token)));
            $status['9'] = count($result);

            $identification_optional_result = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 161)));
            if ($identification_optional_result)
                $ident_conf_bool_value = $identification_optional_result['conf_reg_bool_info']['conf_bool_value'];
            else
                $ident_conf_bool_value = 'N';

            if ($ident_conf_bool_value == 'Y') {
                $status['13'] = 1;
            } else {
                if ($this->is_party_ekyc_done() == 1) {
                    $status['13'] = 1;
                } else {

                    $idenfire_count = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 68)));

                    if ($idenfire_count['conf_reg_bool_info']['conf_bool_value'] == 'Y') {

                        $identifier = $this->identification->find("all", array('conditions' => array('token_no' => $token)));
                        $c = 0;
                        $one_identifier = 0;
                        foreach ($identifier as $iden) {
                            if ($iden['identification']['identifire_type'] == 3) {
                                $c++;
                            }
                            if ($iden['identification']['identifire_type'] == 4 || $iden['identification']['identifire_type'] == 2) {
                                $one_identifier = 1;
                            }
                        }
                        if ($c > 0) {
                            $allparty = $this->party_entry->find('all', array('fields' => array('party_entry.party_id'), 'conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
                            $flag = 0;
                            foreach ($allparty as $single) {
                                $iden = $this->identification->find("all", array('conditions' => array('token_no' => $token, 'party_id' => $single['party_entry']['party_id'])));

                                if (count($iden) != $idenfire_count['conf_reg_bool_info']['info_value']) {
                                    $flag = 0;
                                    break;
                                } else {
                                    $flag = 1;
                                }
                            }
                            if ($flag == 1) {
                                $status['13'] = 1;
                            } else {
                                $status['13'] = 0;
                            }
                        } else {
                            if (count($identifier) != $idenfire_count['conf_reg_bool_info']['info_value']) {
                                $status['13'] = 0;
                            } else {
                                $status['13'] = 1;
                            }
                        }
                        if ($one_identifier == 1) {
                            $status['13'] = 1;
                        }
                    } else {
                        $identification = $this->identification->find("all", array('conditions' => array('token_no' => $token)));

                        $status['13'] = count($identification);
                    }
                }
            }

            $status['15'] = 1;
            if ($this->Session->read("manual_flag") == 'Y') {
                $status['16'] = 1;
            }
            
            ////Added by Prasmita
            ///////////////////////////////////////////////////////
             array_map(array($this, 'loadModel'), array('Leg_uploaded_file_trn','Leg_generalinformation','Leg_fee_calculation', 'Leg_property_details_entry', 'Leg_party_entry','Leg_witness','Leg_identification','genernalinfoentry', 'property_details_entry', 'party_entry', 'conf_reg_bool_info', 'witness', 'appointment', 'ApplicationSubmitted', 'leaseandlicense', 'stamp_duty', 'CitizenPaymentEntry', 'uploaded_file_trn', 'identification', 'article_screen_mapping'));
            $Leg_token = $this->Session->read('Leg_Selectedtoken');
          // pr($Leg_token);exit;
              $gen_info = $this->Leg_generalinformation->find("all", array('conditions' => array('token_no' => $Leg_token)));
          //pr($gen_info);exit;
            if($gen_info!=null)
            {
                $status['16'] = 1;
            }
           if($gen_info==NULL)
           {
               $status['16'] = 0;
           }
           else if($gen_info[0]['Leg_generalinformation']['authorized_flag']=='Y')
            {
                 $status['25'] = 1;
            }
           else if($gen_info[0]['Leg_generalinformation']['last_status_id']=='2')
            {
                $status['24'] = 1;
               
            }
            else
            {
              //  $status['8'] = 0;
            }
             $property_info = $this->Leg_property_details_entry->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($property_info!=null)
            {
                $status['17'] = 1;
            }
             $party_info = $this->Leg_party_entry->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($party_info!=null)
            {
                $status['18'] = 1;
            } 
            $witness_info = $this->Leg_witness->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($witness_info!=null)
            {
                $status['19'] = 1;
            }
             $identifier_info = $this->Leg_identification->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($identifier_info!=null)
            {
                $status['20'] = 1;
            }
            $fee_info = $this->Leg_fee_calculation->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($fee_info!=null)
            {
                $status['21'] = 1;
            }
            $doc_upload_info = $this->Leg_uploaded_file_trn->find("all", array('conditions' => array('token_no' => $Leg_token)));
            if($doc_upload_info!=null)
            {
                $status['22'] = 1;
            }
            ////////////////////////////////////////////
            
            
            
            
            
            $optional = $this->article_mapping_screen();
            $minor = $this->minor_functions();
            $tab = array();
            foreach ($minor as $menu) {
                if ($menu['minorfunction']['delete_flag'] == 'N') {
                    if ($menu['minorfunction']['dispaly_flag'] == 'C') {
                        $tab[$menu['minorfunction']['id']] = 0;
                    } else {
                        foreach ($optional as $menu1) {
                            if ($menu['minorfunction']['id'] == $menu1['article_screen_mapping']['minorfun_id']) {

                                $tab[$menu['minorfunction']['id']] = 0;
                            }
                        }
                    }
                }
            }
            foreach ($status as $key => $value) {
                foreach ($tab as $k => $v) {
                    if ($key == $k) {
                        $tab[$k] = $value;
                    }
                }
            }
            if (isset($tab[33])) {
                $tab[33] = 1;
            }
      
            ksort($tab);


            return ($tab);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function consideration_amount() {
        try {

            $this->loadModel('property_details_entry');
            $this->loadModel('fees_calculation');
            $this->loadModel('fees_calculation_detail');

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $user_id = $this->Session->read("citizen_user_id");
            $citizen_token_no = $this->Session->read('Selectedtoken');
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            $article_id = $this->Session->read('article_id');
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }
            $conditions = array('property_details_entry.token_no' => $citizen_token_no);
            $conditions['property_details_entry.user_id'] = $user_id;
            $property_list = $this->property_details_entry->find('all', array(
                'fields' => array(' DISTINCT ON ("property_details_entry"."property_id") "property_details_entry"."property_id" ', 'property_details_entry.*', 'village.village_name_' . $lang, 'district.district_name_' . $lang, 'taluka.taluka_name_' . $lang, 'level1.level_1_desc_' . $lang, 'level1_list.list_1_desc_' . $lang, 'evalrule.evalrule_desc_' . $lang, 'valuation.rounded_val_amt'),
                'joins' => array(
                    array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'alias' => 'village', 'conditions' => array('village.village_id=property_details_entry.village_id')),
                    array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=property_details_entry.district_id')),
                    array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=property_details_entry.taluka_id')),
                    array('table' => 'ngdrstab_mst_location_levels_1_property', 'type' => 'left', 'alias' => 'level1', 'conditions' => array('level1.level_1_id=property_details_entry.level1_id')),
                    array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'type' => 'left', 'alias' => 'level1_list', 'conditions' => array('level1_list.prop_level1_list_id=property_details_entry.level1_list_id')),
                    array('table' => 'ngdrstab_trn_valuation', 'type' => 'left', 'alias' => 'valuation', 'conditions' => array('valuation.val_id=property_details_entry.val_id')),
                    array('table' => 'ngdrstab_trn_valuation_details', 'type' => 'left', 'alias' => 'valuationd', 'conditions' => array('valuation.val_id=valuation.val_id')),
                    array('table' => 'ngdrstab_mst_evalrule_new', 'type' => 'left', 'alias' => 'evalrule', 'conditions' => array('evalrule.evalrule_id=valuationd.rule_id'))
                ),
                'conditions' => $conditions, 'order' => 'property_details_entry.property_id'
            ));
            $fee = $this->fees_calculation->find("all", array('conditions' => array('article_id' => $article_id, 'token_no' => $citizen_token_no, 'delete_flag' => 'N')));
            $this->set('fee', $fee);
            $this->set('property_list', $property_list);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $citizen_token_no, $user_id);
            $this->set('property_pattern', $property_pattern);
            $this->set('lang', $lang);


            foreach ($property_list as $key => $property) {
                $fieldlist['consideration_amount_' . $property['property_details_entry']['property_id']]['text'] = 'is_numeric';
            }
//pr($fieldlist);exit;
            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $data = $this->request->data['consideration_amount'];
                $this->check_csrf_token($data['csrftoken']);
                if ($this->ValidationError($this->validatedata($data, $fieldlist))) {
                    $this->property_details_entry->updateAll(
                            array('exchange_property_flag' => "'N'"), array('token_no' => $citizen_token_no)
                    );
                    $prop_arr = array();
                    foreach ($data as $key => $amount) {
                        $consarr = explode("_", $key);
                        if (count($consarr) == 3 and is_numeric($consarr[2])) {
                            $this->property_details_entry->updateAll(
                                    array('consideration_amount' => $amount), array('property_id' => $consarr[2], 'token_no' => $citizen_token_no)
                            );
                            foreach ($property_list as $key => $property) {
                                if ($property['property_details_entry']['property_id'] == $consarr[2]) {
                                    if ($property['valuation']['rounded_val_amt'] > $amount) {
                                        $prop_arr[$consarr[2]] = $property['valuation']['rounded_val_amt'];
                                    } else {
                                        $prop_arr[$consarr[2]] = $amount;
                                    }
                                }
                            }
                        }
                    }
// flag update
                    $cmpamt = 0;
                    $cmppropid = NULL;
                    foreach ($prop_arr as $pid => $amt) {
                        if ($amt > $cmpamt) {
                            $cmpamt = $amt;
                            $cmppropid = $pid;
                        }
                    }
                    if (!is_null($cmppropid)) {
                        $this->property_details_entry->updateAll(
                                array('exchange_property_flag' => "'Y'"), array('property_id' => $cmppropid, 'token_no' => $citizen_token_no)
                        );
                    }
                    $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $this->fees_calculation->find("list", array('fields' => 'fee_calc_id,fee_calc_id', 'conditions' => array('article_id' => array($article_id, 9998), 'token_no' => $citizen_token_no, 'delete_flag' => 'N')))));

                    $this->fees_calculation->deleteAll(array('article_id' => array($article_id, 9998), 'token_no' => $citizen_token_no, 'delete_flag' => 'N'));

                    $this->Session->setFlash("Record Saved Successfully!");
                    $this->redirect('consideration_amount');
                }
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

///-------*--------*-*-------------------*-----------------------*----------Stamp Duty Related----------*--------------------*---------*---------------------
    public function stamp_duty($csrftoken = NULL) {
        // load Model
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $user_id = $this->Session->read("citizen_user_id");
            $citizen_token_no = $this->Session->read('Selectedtoken');
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            //load Model
            array_map(array($this, 'loadModel'), array('genernalinfoentry', 'valuation_details', 'party_entry', 'investment_details', 'articledescdetails', 'article', 'article_screen_mapping', 'exemption_article_rules', 'property_details_entry', 'article_fee_rule', 'stamp_duty', 'stamp_duty_adjustment', 'conf_reg_bool_info', 'office', 'conf_article_feerule_items'));

            //-----------------checking Property Flag and property List---------------------------------            
            $property = $this->article_screen_mapping->query('select minorfun_id from ngdrstab_mst_article_screen_mapping where article_id=' . $this->Session->read("article_id") . ' and minorfun_id =2');
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $citizen_token_no, $user_id);
            if (count($property) > 0 && count($property_list) < 1) {
                $this->Session->setFlash("Kindly add Property ");
                return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
            }

            $flag = $this->get_prop_same_usage_flag($citizen_token_no);

            $this->set('prop_flag', $flag);

            $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 103)));
            if (!empty($regconf)) {
                $this->set('gov_body_flag', $regconf['conf_reg_bool_info']['conf_bool_value']);
            }

            $regconfig = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 77)));
            if (!empty($regconfig)) {
                $this->set('area_flag', $regconfig['conf_reg_bool_info']['conf_bool_value']);
            }
            $echallan = $this->conf_reg_bool_info->field('conf_bool_value', array('reginfo_id' => 105));

            $regconfig = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 131)));
            if (!empty($regconfig)) {
                $this->set('mutation_fee_flag', $regconfig['conf_reg_bool_info']['info_value']);
            }

            $state_id = $this->Auth->User("state_id");

            $result = $this->article_fee_rule->query("select * from ngdrstab_mst_article_fee_rule where common_rule_flag='Y'");
            $pageno = $this->genernalinfoentry->query("select no_of_pages from ngdrstab_trn_generalinformation where token_no=$citizen_token_no");
            if (!empty($result)) {
                foreach ($result as $result1):
                    $sd_values = array(
                        'token_no' => $citizen_token_no,
                        'article_id' => 9999,
                        'fee_rule_id' => $result1[0]['fee_rule_id'],
                        'FAJ' => $pageno[0][0]['no_of_pages'], //valuation Amount
                        'FCX' => $this->number_of_pages($citizen_token_no),
                    );
                    $this->calculate_fees($sd_values);
                endforeach;
                $this->autoRender = True;
            }


//$tehprocflag =$this->property_details_entry->query("select DISTINCT(a.token_no),a.token_process_by_tah_flag
//                                                    from ngdrstab_trn_property_details_entry a
//                                                    inner join ngdrstab_trn_valuation b on a.token_no = b.token_no
//                                                    inner join ngdrstab_trn_valuation_details c on b.val_id = c.val_id
//                                                    inner join ngdrstab_mst_evalrule_new d on c.rule_id = d.evalrule_id
//                                                   
//                                                    where d.tah_process_flag = 'Y' and a.token_no=?",array($citizen_token_no));
//             if(!empty($tehprocflag)){
//          if($tehprocflag[0][0]['token_process_by_tah_flag']=='N'){
//               $this->Session->setFlash("Wait for Tehsildar approval ");
//                return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
//          }
//             }
//----------------------end of checking property list and property applicable-------------------------------------------------------
            //$user_id = $this->Auth->User("user_id");

            $total_buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $citizen_token_no), 'joins' => array(
                    array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
            )));
            $this->set('total_buyer', count($total_buyer));
            $total_female_buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $citizen_token_no, 'gender_id' => 2), 'joins' => array(
                    array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
            )));

            $this->set('total_female_buyer', count($total_female_buyer));

            $state_id = $this->Auth->User("state_id");
            $article_id = $this->Session->read('article_id');

            $formdata = $this->stamp_duty->find('first', array('conditions' => array('state_id' => $state_id, 'token_no' => $citizen_token_no)));
            $feeRuleList = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $lang), 'conditions' => array('article_id' => $article_id), 'order' => array('fee_rule_id' => 'ASC')));
            if ($this->Session->read("article_id") == 32) {
                $property_list = $this->property_details_entry->get_property_list_32($doc_lang, $citizen_token_no, $user_id);
                if (empty($property_list)) {
                    $this->Session->setFlash("Kindly fill consideration amount");
                    return $this->redirect(array('action' => 'consideration_amount', $this->Session->read('csrftoken')));
                }
            } else {
                $property_list = $this->property_details_entry->get_property_list($doc_lang, $citizen_token_no, $user_id);
            }
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $citizen_token_no, $user_id);
            $exemption_flag = $this->article->field('exemption_applicable', array('article_id' => $article_id));
            $investor_flag = $this->article->field('investor_clouse', array('article_id' => $article_id));
            $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));

            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('office_id' => $office_id1), 'order' => array('office_name_' . $lang => 'ASC')));
            $sd_flags = $this->conf_reg_bool_info->find('list', array('fields' => array('reginfo_id', 'conf_bool_value'), 'conditions' => array('state_id' => $state_id)));

            //check for document title adjustmentdocument_title_flags
            $adjustmentdocument_title_flags = $this->articledescdetails->query('select articledescription_id ,articledescription_en,adjustment_flag from ngdrstab_mst_articledescriptiondetail where article_id=? and adjustment_flag=?', array($article_id, 'Y'));
            if (!empty($adjustmentdocument_title_flags)) {
                $adjustmentdocument_flags = $adjustmentdocument_title_flags[0][0]['adjustment_flag'];
            } else {
                $adjustmentdocument_flags = NULL;
            }


            $delay_flag = $sd_flags[6];
            $sd_adj_flag = $sd_flags[8];
            $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id)))))); //get only Exemption Rules(9998)

            $this->set(compact('lang', 'article_id', 'investor_flag', 'feeRuleList', 'exemption_flag', 'exemption_rule', 'citizen_token_no', 'article_rule', 'property_pattern', 'property_list', 'sd_adj_flag', 'adjustmentdocument_flags', 'delay_flag', 'office'));

            //validations kalyani
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $fieldlist = array();

            $fieldlist = $this->conf_article_feerule_items->stampdutyfields($laug);
            $fieldlist['cons_amt']['text'] = 'is_required,is_numeric';
            $fieldlist['sr_amt']['text'] = 'is_required,is_numeric';
            // pr($fieldlist);
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $a = $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //  $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                $data = array('online_adj_doc_no' => $this->request->data['frm']['online_adj_doc_no'],
                    'online_adj_doc_date' => $this->request->data['frm']['online_adj_doc_date'],
                    'online_adj_amt' => $this->request->data['frm']['online_adj_amt']);

                //-------------------------------Date 01-March-2017 by shridhar for Stamp Duty Adjustment---------------------------------------------------------
                $frm = $this->request->data['frm'];


                //////////  web service reg fee and mutation fee pdf - devashree  ///////
                if (!isset($echallan)) {
                    if ($echallan == 'Y') {
                        $partydetail = $this->party_entry->find("all", array('conditions' => array('token_no' => $citizen_token_no, 'is_presenter' => 'Y')));

                        $pname = $partydetail[0]['party_entry']['party_fname_en'] . ' ' . $partydetail[0]['party_entry']['party_mname_en'] . ' ' . $partydetail[0]['party_entry']['party_lname_en'];
                        $pmobile = $partydetail[0]['party_entry']['mobile_no'];
                        $distid = $partydetail[0]['party_entry']['district_id'];
                        $talukaid = $partydetail[0]['party_entry']['taluka_id'];

                        $address_en = $partydetail[0]['party_entry']['address_en'];
                        $email_id = $partydetail[0]['party_entry']['email_id'];

                        $talukaf = $this->taluka->find("all", array('conditions' => array('district_id' => $distid, 'taluka_id' => $talukaid)));

                        $talukanm = $talukaf[0]['taluka']['taluka_name_en'];
                        $feetot = $this->request->data['frm']['total'];


                        if ($address_en != '' && $email_id != '') {
                            $this->getregfee_pdf($feetot, $pmobile, $pname, $talukanm);
                            $this->getmutationfee_pdf($feetot, $pmobile, $pname, $talukanm);
                        }
                    }
                }
                /////////////////////////////////////////////////////////
                //print_r($exemption_id); exit;
                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 88)));
                if (!empty($regconf)) {
                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                        if (isset($frm['exemption_fee_rule_id'])) {
                            $exemption_id = $frm['exemption_fee_rule_id'];

                            if ($exemption_id == 197) {
                                $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                                $this->check_female_exemption($frm);
                                if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                    $adjustable_amount = $this->get_adj_doc_exess_amt($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                    if ($frm['online_adj_amt'] < $adjustable_amount) {
                                        $sd_update_result = $this->check_female_exemption($frm);
                                    } else {
                                        $sd_update_result = 0;
                                        $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                    }
                                } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                    $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                    $this->stamp_duty_adjustment->updateAll(
                                            array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                    );
                                    $sd_update_result = $this->check_female_exemption($frm);
                                }
                            } else {
                                $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                                $sd_update_result = 1;

                                $this->update_sd($frm);
                                if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                    $adjustable_amount = $this->get_adj_doc_exess_amt($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                    if ($frm['online_adj_amt'] < $adjustable_amount) {
                                        $sd_update_result = $this->update_sd($frm);
                                    } else {
                                        $sd_update_result = 0;
                                        $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                    }
                                } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                    $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                    $this->stamp_duty_adjustment->updateAll(
                                            array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                    );
                                    $sd_update_result = $this->update_sd($frm);
                                }
                            }
                        } else {
                            $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                            $sd_update_result = 1;

                            $this->update_sd($frm);
                            if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                $adjustable_amount = $this->get_adj_doc_exess_amt($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                if ($frm['online_adj_amt'] < $adjustable_amount) {
                                    $sd_update_result = $this->update_sd($frm);
                                } else {
                                    $sd_update_result = 0;
                                    $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                }
                            } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                $this->stamp_duty_adjustment->updateAll(
                                        array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                );
                                $sd_update_result = $this->update_sd($frm);
                            }
                        }
                    } else {
                        $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                        $sd_update_result = 1;

                        $this->update_sd($frm);
                        if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                            $adjustable_amount = $this->get_adj_doc_exess_amt($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                            if ($frm['online_adj_amt'] < $adjustable_amount) {
                                $sd_update_result = $this->update_sd($frm);
                            } else {
                                $sd_update_result = 0;
                                $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                            }
                        } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                            $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                            $this->stamp_duty_adjustment->updateAll(
                                    array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                            );
                            $sd_update_result = $this->update_sd($frm);
                        }
                    }
                }
//investment details

                if ($investor_flag == 'Y') {
                    $frm['online_invest_doc_date'] = ($frm['online_invest_doc_date']) ? date('Y-m-d', strtotime($frm['online_invest_doc_date'])) : NULL;
                    $this->request->data['frm']['invest_stamp_amount'] = ($this->request->data['frm']['invest_stamp_amount']) ? $this->request->data['frm']['invest_stamp_amount'] : 0;
                    $this->investment_details->deleteAll(array('token_no' => $this->Session->read('Selectedtoken')));

                    $invest = array('online_invest_doc_no' => $frm['online_invest_doc_no'],
                        'online_invest_doc_date' => $frm['online_invest_doc_date'],
                        'invest_stamp_amount' => $this->request->data['frm']['invest_stamp_amount'],
                        'token_no' => $this->Session->read('Selectedtoken'),
                        'state_id' => $this->Auth->User("state_id"),
                        'req_ip' => $_SERVER['REMOTE_ADDR'],
                        'user_type' => $this->Session->read("session_usertype")
                    );

                    $this->investment_details->save($invest);
                }
                $this->set_csrf_token();
//------------------------------------------------------------------------------------------------------------------------------------------------
                if ($sd_update_result == 1) {
                    $this->Session->setFlash('Record Saved Successfully ');

                    $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                } else if ($sd_update_result == 0) {
                    $this->Session->setFlash('!SD update Failed');
                    $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                } else {
                    $this->Session->setFlash('!No proper Input');
                    $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                }
//                }
            } else {

                $this->check_csrf_token_withoutset($csrftoken);
                if ($formdata) {
                    $this->stamp_duty->id = $formdata['stamp_duty']['token_no'];
                    $dataarray = $this->stamp_duty->read();
                    $this->request->data['frm'] = $dataarray['stamp_duty'];

                    $invstdata = $this->investment_details->find('first', array('conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));

                    if (!empty($invstdata)) {
                        $this->request->data['frm']['online_invest_doc_date'] = date('d-m-Y', strtotime($invstdata['investment_details']['online_invest_doc_date']));
                        $this->request->data['frm']['invest_stamp_amount'] = $invstdata['investment_details']['invest_stamp_amount'];
                        $this->request->data['frm']['online_invest_doc_no'] = $invstdata['investment_details']['online_invest_doc_no'];
                    }
                }
            }
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_female_exemption_old($formData = NULL) {
        try {
            $this->autoRender = FALSE;

            $formData = (isset($this->request->data['frm'])) ? $this->request->data['frm'] : $formData;
            array_map(array($this, 'loadModel'), array('article_fee_rule', 'female_exemption', 'stamp_duty', 'stamp_duty_adjustment', 'party_entry'));
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $token = $formData['token_no'];
            $exemption_amt = $formData['exemption_amt'];
            $user_id = $formData['user_id'];
            $exemption_rule_id = $formData['exemption_fee_rule_id'];
            // print_r($formData); exit;

            $formData['state_id'] = $this->Auth->User("state_id");
            $formData['req_ip'] = $_SERVER['REMOTE_ADDR'];
            //$formData['user_id'] = $_SERVER['REMOTE_ADDR'];
            //  $formData['created_date'] = date('Y-m-d H:i:s');
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $formData['online_final_amt'] = ($formData['online_adj_amt']) ? ($formData['online_sd_amt'] - $formData['online_adj_amt']) : $formData['online_sd_amt'];
            $formData['online_final_amt'] += ($formData['late_fee']) ? $formData['late_fee'] : 0; // add alte fee if applicable
            $formData['final_amt'] = $formData['online_final_amt'] + $formData['counter_sd_amt'];

            $formData['online_adj_doc_date'] = ($formData['online_adj_doc_date']) ? date('Y-m-d', strtotime($formData['online_adj_doc_date'])) : NULL;
            $formData['recalculate_flag'] = 'N';


            if ($formData['exemption_fee_rule_id'] == 197) {
                $party_uid = $this->party_entry->query("select uid from ngdrstab_trn_party_entry_new p,
                ngdrstab_mst_party_type pt 
                where p.gender_id=2 and pt.party_type_flag='0' and p.party_type_id=pt.party_type_id and token_no=$token");

                if (!empty($party_uid)) {
                    $uid = $party_uid[0][0]['uid'];
                    //$uid_decrypt=$this->enc($uid); $uid_decrypt=$this->dec($uid); 
                    $this->set_csrf_token();
                    $uid_check = $this->female_exemption->query("select uid from ngdrstab_trn_female_exemption where uid='$uid'");

                    if (empty($uid_check)) {
                        $uid_insert = $this->female_exemption->query("insert into ngdrstab_trn_female_exemption(token_no,uid,exemption_type,exemption_amt,date,user_id,created_date) values($token,'$uid','Female Exemption',$exemption_amt,now(),$user_id,now())");

                        if ($this->stamp_duty->save($formData)) {
                            if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                if ($this->stamp_duty_adjustment->save($formData)) {
                                    $this->Session->setFlash('Record Saved Successfully ');

                                    $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                } else {
                                    $this->Session->setFlash('!SD update Failed');
                                    $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                                }
                            }
                            $this->Session->setFlash('Record Saved Successfully ');

                            $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash('Female Exemption can be calculated only once');
                        $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                    }
                } else {
                    if ($this->stamp_duty->save($formData)) {
                        if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                            if ($this->stamp_duty_adjustment->save($formData)) {
                                $this->Session->setFlash('Record Saved Successfully ');

                                $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                            } else {
                                $this->Session->setFlash('!SD update Failed');
                                $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                            }
                        }
                        $this->Session->setFlash('Record Saved Successfully ');

                        $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        exit;
    }

    function check_female_exemption($formData = NULL) {
        try {
            $this->autoRender = FALSE;

            $formData = (isset($this->request->data['frm'])) ? $this->request->data['frm'] : $formData;
            array_map(array($this, 'loadModel'), array('fee_exemption', 'ApplicationSubmitted', 'article_fee_rule', 'female_exemption', 'stamp_duty', 'stamp_duty_adjustment', 'party_entry'));
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $token = $formData['token_no'];
            $exemption_amt = $formData['exemption_amt'];
            $user_id = $formData['user_id'];
            $exemption_rule_id = $formData['exemption_fee_rule_id'];
            // print_r($formData); exit;
            $formData['state_id'] = $this->Auth->User("state_id");
            $formData['req_ip'] = $_SERVER['REMOTE_ADDR'];
            //$formData['user_id'] = $_SERVER['REMOTE_ADDR'];
            //  $formData['created_date'] = date('Y-m-d H:i:s');
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $formData['online_final_amt'] = ($formData['online_adj_amt']) ? ($formData['online_sd_amt'] - $formData['online_adj_amt']) : $formData['online_sd_amt'];
            $formData['online_final_amt'] += ($formData['late_fee']) ? $formData['late_fee'] : 0; // add alte fee if applicable
            $formData['final_amt'] = $formData['online_final_amt'] + $formData['counter_sd_amt'];

            $formData['online_adj_doc_date'] = ($formData['online_adj_doc_date']) ? date('Y-m-d', strtotime($formData['online_adj_doc_date'])) : NULL;
            $formData['recalculate_flag'] = 'N';

            if ($formData['exemption_fee_rule_id'] == 197) {
                $party_uid = $this->party_entry->query("select uid from ngdrstab_trn_party_entry_new p,
                ngdrstab_mst_party_type pt 
                where p.gender_id=2 and pt.party_type_flag='0' and p.party_type_id=pt.party_type_id and token_no=$token");

                if (!empty($party_uid)) {
                    $uid = $party_uid[0][0]['uid'];
                    //$uid_decrypt=$this->enc($uid); $uid_decrypt=$this->dec($uid); 
                    $this->set_csrf_token();

                    $uid_check = $this->female_exemption->query("select uid,old_doc_flag,token_no,office_name from ngdrstab_trn_female_exemption where uid='$uid'");

                    if (empty($uid_check)) {
                        $uid_insert = $this->female_exemption->query("insert into ngdrstab_trn_female_exemption(token_no,uid,exemption_type,exemption_amt,date,user_id,created_date) values($token,'$uid','Female Exemption',$exemption_amt,now(),$user_id,now())");

                        if ($this->stamp_duty->save($formData)) {
                            if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                if ($this->stamp_duty_adjustment->save($formData)) {
                                    $this->Session->setFlash('Record Saved Successfully ');

                                    $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                } else {
                                    $this->Session->setFlash('!SD update Failed');
                                    $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                                }
                            }
                            $this->Session->setFlash('Record Saved Successfully ');

                            $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $old_doc_flag = $uid_check[0][0]['old_doc_flag'];
                        $old_office_name = $uid_check[0][0]['office_name'];
                        $existing_token_no = $uid_check[0][0]['token_no'];
                        $token_no = $this->Session->read('Selectedtoken');

                        if ($old_doc_flag == 'Y') {
                            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                            $this->Session->setFlash('Female Exemption Already taken in Old Document for ' . $old_office_name . ' office');
                            $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                        } else {
                            if ($existing_token_no == $token_no) {
                                $final_stamp_result = $this->ApplicationSubmitted->query("select final_stamp_flag,stamp2_flag from ngdrstab_trn_application_submitted where token_no='$token_no'");
                                if (empty($final_stamp_result)) {
                                    if ($this->stamp_duty->save($formData)) {
                                        if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                            if ($this->stamp_duty_adjustment->save($formData)) {
                                                $this->Session->setFlash('Record Saved Successfully ');

                                                $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                            } else {
                                                $this->Session->setFlash('!SD update Failed4');
                                                $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                                            }
                                        }
                                        $this->Session->setFlash('Record Saved Successfully ');

                                        $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                    }
                                } else {
                                    $final_stamp_flag = $final_stamp_result[0][0]['final_stamp_flag'];
                                    $stamp2_flag = $final_stamp_result[0][0]['stamp2_flag'];
                                    if ($final_stamp_flag == 'N' && $stamp2_flag == 'N') {
                                        $update_female_exm = $this->female_exemption->query("update ngdrstab_trn_female_exemption set exemption_amt=$exemption_amt where token_no=$token_no");
                                        if ($this->stamp_duty->save($formData)) {
                                            if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                                if ($this->stamp_duty_adjustment->save($formData)) {
                                                    $this->Session->setFlash('Record Saved Successfully ');

                                                    $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                                } else {
                                                    $this->Session->setFlash('!SD update Failed4');
                                                    $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                                                }
                                            }
                                            $this->Session->setFlash('Record Saved Successfully ');

                                            $this->redirect(array('action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                        }
                                    } else {
                                        $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                                        $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                                        $this->Session->setFlash('Female Exemption Already taken for ' . $existing_token_no . ' this Token No.');
                                        $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                                    }
                                }
                            } else {
                                $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                                $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                                $this->Session->setFlash('Female Exemption Already taken for ' . $existing_token_no . ' this Token No.');
                                $this->redirect(array('action' => 'stamp_duty', $this->Session->read('csrftoken')));
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //-------*---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update_sd($formData = NULL) {
        try {
            $this->autoRender = FALSE;

            $formData = (isset($this->request->data['frm'])) ? $this->request->data['frm'] : $formData;
            array_map(array($this, 'loadModel'), array('stamp_duty', 'stamp_duty_adjustment'));
            $formData['state_id'] = $this->Auth->User("state_id");
            $formData['req_ip'] = $_SERVER['REMOTE_ADDR'];
            //$formData['user_id'] = $_SERVER['REMOTE_ADDR'];
            //  $formData['created_date'] = date('Y-m-d H:i:s');
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $formData['online_final_amt'] = ($formData['online_adj_amt']) ? ($formData['online_sd_amt'] - $formData['online_adj_amt']) : $formData['online_sd_amt'];
            $formData['online_final_amt'] += ($formData['late_fee']) ? $formData['late_fee'] : 0; // add alte fee if applicable
            $formData['final_amt'] = $formData['online_final_amt'] + $formData['counter_sd_amt'];

            $formData['online_adj_doc_date'] = ($formData['online_adj_doc_date']) ? date('Y-m-d', strtotime($formData['online_adj_doc_date'])) : NULL;
            $formData['recalculate_flag'] = 'N';
            if ($this->Session->read("session_usertype") == 'O') {
                unset($formData['user_id']);

                if ($this->Auth->user('office_id')) {

                    $formData['org_user_id'] = $this->Auth->User('user_id');


                    $formData['org_updated'] = date('Y-m-d H:i:s');

                    $formData['org_created'] = date('Y-m-d H:i:s');
                }
            }

            if ($this->stamp_duty->save($formData)) {
                if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                    if ($this->stamp_duty_adjustment->save($formData)) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        exit;
    }

    //-----------------------------1 March 2017 Code Update for get---get_adj_doc_exess_amt(),get_adj_doc_exess_amt_detail(),get_fees_falc_ids()  by Shridhar-------------------------------------------------------------------------------------------------
    function get_adj_doc_exess_amt($adj_doc_no = NULL, $adj_doc_date = NULL) {
        try {

            $this->autoRender = FALSE;
            $adj_doc_no = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_no'] : $adj_doc_no;
            $adj_doc_date = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_date'] : $adj_doc_date;
            $doc_token_no = $this->Session->read('Selectedtoken');
            if ($adj_doc_no && $adj_doc_date) {
                array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'stamp_duty_adjustment'));
                $adj_doc_date = date('Y-m-d', strtotime(str_replace('/', '-', $adj_doc_date)));
                $exsAmount = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $adj_doc_no, 'DATE(doc_reg_date)' => $adj_doc_date)));
                $onlineAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(online_adj_amt) as online_adj_amt'), 'conditions' => array('online_adj_doc_no' => $adj_doc_no, 'DATE(online_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'online_adj_amt IS NOT NULL')));
                $counterAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(counter_adj_amt) as counter_adj_amt'), 'conditions' => array('counter_adj_doc_no' => $adj_doc_no, 'DATE(counter_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'counter_adj_amt IS NOT NULL')));
                $adjAmount = $onlineAdjAmount['0']['online_adj_amt'] + $counterAdjAmount['0']['counter_adj_amt'];
                return ($exsAmount) ? ($adjAmount ? ($exsAmount[0]['diff_amt'] - $adjAmount) : $exsAmount[0]['diff_amt']) : 0;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_adj_doc_exess_amt_detail($adj_doc_no = NULL, $adj_doc_date = NULL) {
        try {
//            $this->autoRender = TRUE;
            $adj_doc_no = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_no'] : $adj_doc_no;
            $adj_doc_date = (isset($_POST['adj_doc_date'])) ? $_POST['adj_doc_date'] : $adj_doc_date;
            $doc_token_no = $this->Session->read('Selectedtoken');
            if ($adj_doc_no && $adj_doc_date) {
                array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'stamp_duty_adjustment'));
                $adj_doc_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['adj_doc_date'])));
                $doc_payment = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_to_be_paid', 'amt_paid', 'amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $adj_doc_no, 'DATE(doc_reg_date)' => $adj_doc_date)));
                $exsAmount = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $_POST['adj_doc_no'], 'DATE(doc_reg_date)' => $adj_doc_date)));
                $onlineAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(online_adj_amt) as online_adj_amt'), 'conditions' => array('online_adj_doc_no' => $adj_doc_no, 'DATE(online_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'online_adj_amt IS NOT NULL')));
                $counterAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(counter_adj_amt) as counter_adj_amt'), 'conditions' => array('counter_adj_doc_no' => $adj_doc_no, 'DATE(counter_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'counter_adj_amt IS NOT NULL')));
                $adjustedAmount = $onlineAdjAmount['0']['online_adj_amt'] + $counterAdjAmount['0']['counter_adj_amt'];
                $adj_detail = $this->stamp_duty_adjustment->find('all', array('fields' => array('token_no', 'online_adj_amt', 'counter_adj_amt'),
                    'conditions' => array(
                        'AND' => array(
                            'OR' => array('online_adj_doc_no' => $adj_doc_no, 'counter_adj_doc_no' => $adj_doc_no),
                            'OR' => array('DATE(counter_adj_doc_date)' => $adj_doc_date, 'DATE(online_adj_doc_date)' => $adj_doc_date)
                        ),
                        'token_no !=' => $doc_token_no
                    )
                ));
            } else {
                $doc_payment = $adj_detail = NULL;
            }
            $this->set(compact('doc_payment', 'adj_detail', 'adjustedAmount'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //-------------------------------------------------Code by Shridhar for Getting property_id which SD is not calculated------------------
    function get_fees_falc_ids() {
        try {
            $this->autoRender = FALSE;
            if ($this->request->data['doc_token_no']) {
                $doc_token_no = $this->request->data['doc_token_no'];
                $user_id = $this->Session->read("citizen_user_id");
                $article_id = $this->Session->read("article_id");
                if ($article_id == 32) {
                    $property_ids = ClassRegistry::init('property_details_entry')->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'fee_calc_id is NULL', 'val_id !=' => 0, 'exchange_property_flag' => 'Y')));
                    return json_encode($property_ids);
                } else if (is_numeric($user_id) && is_numeric($doc_token_no)) {
                    $property_ids = ClassRegistry::init('property_details_entry')->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'fee_calc_id is NULL', 'val_id !=' => 0)));
                    return json_encode($property_ids);
                } else {
                    return 'return wrong input';
                }
            } else {
                return 'wrong input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//-------------*----------------*--------------*---------------------*-------------*---------------*------------*----------------*---------------------------------------------------------
    public function leaseandlicense() {
        try {
            $last_status_id = $this->Session->read('last_status_id');

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please Select Token");
                return $this->redirect('genernal_info');
            }
            array_map(array($this, 'loadModel'), array('identificatontype', 'leaseandlicense', 'doc_levels', 'State', 'User', 'article_screen_mapping', 'partytype', 'TrnBehavioralPatterns'));
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('popupstatus', NULL);
            $tokenval = $this->Session->read("Selectedtoken");
            $this->set('Selectedtoken', $tokenval);
            $stateid = $this->Auth->User("state_id");
            $language = $this->Session->read("sess_langauge");
            $this->set('language', $language);
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $result = $this->article_screen_mapping->find("all", array('conditions' => array('article_id' => $this->Session->read('article_id'), 'minorfun_id' => 3)));
            if (empty($result)) {
                return $this->redirect('stamp_duty'); // screen no avalable to article
            }
            $this->set('salutation', ClassRegistry::init('salutation')->find('list', array('fields' => array('salutation_id', 'salutation_desc_' . $doc_lang), 'order' => array('salutation_desc_' . $doc_lang => 'ASC'))));
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $this->set('gender', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $doc_lang))));
            $this->set('occupation', ClassRegistry::init('occupation')->find('list', array('fields' => array('occupation_id', 'occupation_name_' . $doc_lang))));
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype', $partytype_name);
            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $doc_lang))));
            $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang))));
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $leaseandlicense = $this->leaseandlicense->get_leaserecord($doc_lang, $tokenval, $user_id);
            $this->set('leaseandlicense', $leaseandlicense);
            //Status check box code
            $alllevel = $this->doc_levels->get_alllevel();
            $this->set('doclevels', $alllevel);

            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $doc_lang = $this->Session->read('doc_lang');
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            $fieldlist = array();
            if ($name_format == 'Y') {
                $fieldlist['fname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['mname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['lname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';

                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';

                if ($doc_lang != 'en') {
                    //list for all unicode fields
                    $fieldlist['fname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['mname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['lname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;


                    $fieldlist['idetification_mark1_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicode_rule_' . $doc_lang;
                }
            } else {
                $fieldlist['party_full_name_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';

                $fieldlist['idetification_mark1_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';
                if ($doc_lang != 'en') {
                    $fieldlist['party_full_name_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;

                    $fieldlist['idetification_mark1_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                }
            }
            $fieldlist['party_type_id']['select'] = 'is_select_req';
            $fieldlist['salutation']['select'] = 'is_select_req';
            $fieldlist['identificationtype_id']['select'] = 'is_select_req';
            $fieldlist['dob']['text'] = '';
            $fieldlist['gender_id']['select'] = 'is_select_req';
            $fieldlist['occupation_id']['select'] = 'is_select_req';
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
            $fieldlist['identificationtype_desc_en']['text'] = 'is_alphanumspace'; //please change this field name  dob,
            $fieldlist['uid']['text'] = 'is_uidnum';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian'; //7,8,9 start
            $fieldlist['pan_no']['text'] = 'is_pancard';
            $fieldlist['age']['text'] = 'is_digit';
            $fieldlist['ll_fdate']['text'] = '';
            $fieldlist['ll_tdate']['text'] = '';
            $fieldlist['ll_month']['text'] = 'is_digit';
            $fieldlist['rent_permonth']['text'] = 'is_digit';
            $fieldlist['deposite_amount']['text'] = 'is_digit';
            $fieldlist['deposite_refundable']['text'] = 'is_digit';
            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $errarr['identificationtype_desc_en_error'] = '';
            $this->set("errarr", $errarr);
            // /^[[A-PR-WYa-pr-wy][0-9]{7}]*$/
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['leaseandlicense']['csrftoken']);
//set extra data to save in table

                $this->request->data['leaseandlicense']['user_type'] = $this->Session->read("session_usertype");
                $data = $this->set_value_for_save_lease_licence($this->request->data['leaseandlicense'], $stateid, $tokenval);

                $this->request->data['leaseandlicense'] = $data;
                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);
                if ($_POST['actiontype'] == '1') {
                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['leaseandlicense']['id'] = $this->request->data['hfid'];
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }

                    //validations kalyani
                    $this->request->data['leaseandlicense'] = $this->istrim($this->request->data['leaseandlicense']);
                    if ($this->request->data['leaseandlicense']['identificationtype_id']) {
                        $rule = $this->leaseandlicense->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['leaseandlicense']['identificationtype_id']);
                        if ($rule) {
                            $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                        }
                    }

                    $errarr = $this->validatedata($this->request->data['leaseandlicense'], $fieldlist);
                    $flag = 0;
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        $this->set("errarr", $errarr);
                    } else {
                        $this->request->data['leaseandlicense']['user_type'] = $this->Session->read("session_usertype");
                        if ($this->leaseandlicense->save($this->request->data['leaseandlicense'])) {
                            if ($actionvalue == 'Updated') {
                                $lease_id = $this->request->data['leaseandlicense']['id'];
                                $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $lease_id, 4);
                            } else {
                                $lease_id = $this->leaseandlicense->getLastInsertID();
                            }
                            if (isset($this->request->data['property_details']['pattern_id'])) {
                                $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $lease_id, $this->request->data['property_details'], 4, $this->Session->read("session_usertype"));
                            }
                            $this->Session->setFlash(__("Record $actionvalue Successfully"));

                            if ($actionvalue == 'Updated') {
                                $this->redirect(array('action' => 'leaseandlicense', $tokenval));
                            } else {
                                $leaseandlicense1 = $this->leaseandlicense->find('all', array('conditions' => array('id' => $this->leaseandlicense->getLastInsertId())));
                                $this->redirect(array('action' => 'leaseandlicense', $leaseandlicense1[0]['leaseandlicense']['token_no']));
                            }
                        } else {
                            $this->Session->setFlash(__("Record Not $actionvalue "));
                        }
                    }
                    if ($_POST['actiontype'] == '2') {
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'leaseandlicense', $tokenval));
                    }
                    if ($_POST['actiontype'] == '3') {
                        $this->delete_lease($_POST['hfid'], $tokenval);
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    function set_value_for_save_lease_licence($data, $stateid, $tokenval) {
//        $language = $this->Session->read("sess_langauge");
//        $doc_lang = $this->Session->read('doc_lang');
//        $uid_compulsary = $this->is_party_ekyc_auth_compusory();
//        $identity = $this->is_Pan_verification_compulsary();
//        if ($uid_compulsary == 'Y') {
//            if (!$data['uid']) {
//
//                $this->Session->setFlash(__('Please Enter UID'));
//                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'leaseandlicense', $tokenval));
//            }
//        }
//        if ($identity == 'Y') {
//            if (!$data['
//            '] || $data['identificationtype_desc_' . $doc_lang] == '') {
//                $this->Session->setFlash(__('Please Select Identity'));
//                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'leaseandlicense', $tokenval));
//            }
//        }
//        $data['user_id'] = $this->Auth->user('user_id');
//        // $data['created_date'] = date('Y/m/d');
//        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
//        $data['state_id'] = $stateid;
//        $data['deposite_refundable'] = $_POST['deposite_refundable'];
//        if (isset($data['dob']) && $data['dob'] != NULL) {
//            $data['dob'] = date('Y-m-d', strtotime($data['dob']));
//        }
//        $data['ll_fdate'] = date('Y-m-d', strtotime($data['ll_fdate']));
//        $data['ll_tdate'] = date('Y-m-d', strtotime($data['ll_tdate']));
//        $name_format = $this->get_name_format();
//        if ($name_format == 'Y') {
//            $data['party_full_name_en'] = isset($data['fname_en']) ? $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'] : '';
//            $data['party_full_name_ll'] = isset($data['fname_ll']) ? $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'] : '';
//        }
//        return $data;
//    }

    function delete_lease($id, $tokenval) {
        if ($id != NULL) {
            $this->leaseandlicense->id = $id;
            if ($this->leaseandlicense->delete()) {
                $this->Session->setFlash(__('Record Deleted Successfully'));
                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'leaseandlicense', $tokenval));
            } else {
                $this->Session->setFlash(__('Record Not Deleted'));
            }
        }
    }

    /*     * **************************************Function for citizen appointment ********************** */

    public function appointment_OLD($csrftoken = NULL) {
        try {
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect('genernalinfoentry');
            }
            //load Model
            array_map(array($this, 'loadModel'), array('officeshift', 'party_entry', 'smsevent', 'office', 'appointment', 'ApplicationSubmitted', 'regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 44)));
            $tatkal = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 53)));
            $startapp = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 76)));
            $reschedule = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 78)));

            if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {

                if ($this->insert_apptmt_temp_to_original()) {
                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->set_csrf_token();
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            }
            $this->set('reschedule', $reschedule['regconfig']['conf_bool_value']);

            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');

            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array('ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));

            if (count($submission) > 0) {

                $this->set('submission_flag', 'Y');
                $now = time(); // or your date as well
                $subdate = strtotime($submission[0]['ApplicationSubmitted']['token_submit_date']);
                $datediff = $now - $subdate;

                $startdays = round($datediff / (60 * 60 * 24));
                if (isset($startapp['regconfig']['info_value'])) {
                    $startdiff = $startapp['regconfig']['info_value'] - $startdays;
                    if ($startdiff <= 0) {
                        $startday = 0;
                    } else {
                        $startday = $startdiff;
                    }

                    $this->set('startday', '+' . $startday . 'd');
                }
            } else {
                $this->set('startday', '+0d');
                $this->set('submission_flag', 'N');
            }





            $this->set('tatkal', $tatkal['regconfig']['conf_bool_value']);
            if (isset($regconfig['regconfig']['info_value'])) {
                $this->set('normal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
            }

            if (!$this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                $this->set('tatkal_paid', 'N');
            } else {
                $this->set('tatkal_paid', 'Y');
            }


            $office_id = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
            $officename = ClassRegistry::init('office')->field('office.office_name_' . $this->Session->read('doc_lang'), array('office_id' => $office_id));
            $doc_lang = $this->Session->read('doc_lang');
            $office = $this->office->get_officedetails_for_appointment($office_id);

            $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $doc_lang), 'order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $office[0]['office']['shift_id'])));

            $this->set('office_id', $office_id);
            $this->set('officeshift', $officeshift);
            $appointment = $this->appointment->find('all', array('conditions' => array('appointment.token_no ' => $this->Session->read("Selectedtoken"), 'appointment.user_id' => $user_id)));

            $this->set('officename', $officename);
            $this->set('appointment', $appointment);


            $tatkalslot = $this->office->get_officedetails_for_tatkalappointment($office_id);
            if (empty($tatkalslot)) {
                $this->set('tatkal_availbility', 'N');
            } else {
                $this->set('tatkal_availbility', 'Y');
            }

//          pr($submission);exit;


            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist['appointment_date']['text'] = 'is_required';
            $fieldlist['shift_id']['select'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['appointment']['csrftoken']);
                if (isset($this->request->data['appointment']['reschedule_flag']) && $this->request->data['appointment']['reschedule_flag'] == 'Y') {
                    $this->appointment->deleteAll(['token_no' => $this->Session->read("Selectedtoken")]);
                }
                $this->request->data['appointment'] = $this->istrim($this->request->data['appointment']);

                if (isset($this->request->data['appointment']['appointment_date'])) {
                    $app_date = date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date']));
                    $today = date('Y-m-d');

                    if ($app_date < $today) {
                        $this->Session->setFlash(__("Please Check System Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }

                    $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));




                    if ($app_date > $limit) {
                        $this->Session->setFlash(__("Please Check  Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }
                }
                $errarr = $this->validatedata($this->request->data['appointment'], $fieldlist);
                if ($this->ValidationError($errarr)) {
                    $this->request->data['appointment']['user_type'] = $this->Session->read("session_usertype");
                    if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                        $this->Session->setFlash(__("Please Select slot"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }

                    list($interval, $slot) = explode('_', $_POST['slot']);
                    $data = array('office_id' => $this->request->data['appointment']['office_id'],
                        'interval_id' => $interval,
                        'slot_no' => $slot,
                        'appointment_date' => date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])),
                        'user_id' => $user_id,
                        'user_type' => $this->request->data['appointment']['user_type'],
                        'state_id' => $stateid,
                        // 'created_date' => $created_date,
                        'totalslot' => $_POST['totalslot'],
                        'req_ip' => $ip,
                        'token_no' => $this->Session->read("Selectedtoken"),
                        'sheduled_time' => $_POST['time'],
                        'shift_id' => $this->request->data['appointment']['shift_id'],
                        'flag' => 'N');



                    $check = $this->appointment->find('first', array('conditions' => array('interval_id' => $interval, 'slot_no' => $slot, 'office_id' => $this->request->data['appointment']['office_id'], 'appointment_date' => date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])), 'flag' => 'N')));

                    if (empty($check)) {

                        $usertype = $this->Session->read("session_usertype");
                        if ($usertype == 'O') {
                            $data['org_user_id'] = $this->Auth->User("user_id");
                            $data['org_updated'] = date('Y-m-d H:i:s');
                        }
                        if ($this->appointment->save($data)) {
                            //sms code

                            $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));


                            $officename = ClassRegistry::init('office')->field('office.office_name_' . $this->Session->read('doc_lang'), array('office_id' => $office_id1));
                            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 2)));

                            $message = 'Office name:-' . $officename . '  Date:- ' . ' ' . $data['appointment_date'] . '  Time:-' . ' ' . $data['sheduled_time'] . 'Your Pre-registration number is ' . $this->Session->read("Selectedtoken");

                            if (!empty($event)) {
                                if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                    $seller = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                            array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='1'"))
                                    )));
                                    if (!empty($seller)) {
                                        $mobno = $seller[0]['party_entry']['mobile_no'];
                                        $this->smssend(3, $mobno, $message, $this->Session->read("citizen_user_id"), 2);
                                    }

                                    $buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                            array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
                                    )));
                                    if (!empty($buyer)) {
                                        $mobno1 = $buyer[0]['party_entry']['mobile_no'];
                                        $this->smssend(3, $mobno1, $message, $this->Session->read("citizen_user_id"), 2);
                                    }
                                }
                            }
                            $this->Session->setFlash(__("Slot allocated Successfully"));
                            $this->set_csrf_token();
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Please Check Slot Again"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }
                } else {
                    $this->check_csrf_token_withoutset($csrftoken);
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function appointment($csrftoken = NULL) {
        try {
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect('genernalinfoentry');
            }
            //load Model
            array_map(array($this, 'loadModel'), array('officeshift', 'party_entry', 'smsevent', 'office', 'appointment', 'ApplicationSubmitted', 'regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 44)));
            $tatkal = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 53)));
            $startapp = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 76)));
            $reschedule = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 78)));

            if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {

                if ($this->insert_apptmt_temp_to_original()) {
                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->set_csrf_token();
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            }
            $this->set('reschedule', $reschedule['regconfig']['conf_bool_value']);

            $gov_apt = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 155)));
            $this->set('gov_apt', $gov_apt['regconfig']['conf_bool_value']);

            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');

            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array('ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));

            if (count($submission) > 0) {

                $this->set('submission_flag', 'Y');
                $now = time(); // or your date as well
                $subdate = strtotime($submission[0]['ApplicationSubmitted']['token_submit_date']);
                $datediff = $now - $subdate;

                $startdays = round($datediff / (60 * 60 * 24));
                if (isset($startapp['regconfig']['info_value'])) {
                    $startdiff = $startapp['regconfig']['info_value'] - $startdays;
                    if ($startdiff <= 0) {
                        $startday = 0;
                    } else {
                        $startday = $startdiff;
                    }

                    $this->set('startday', '+' . $startday . 'd');
                }
            } else {
                $this->set('startday', '+0d');
                $this->set('submission_flag', 'N');
            }





            $this->set('tatkal', $tatkal['regconfig']['conf_bool_value']);
            // if (isset($regconfig['regconfig']['info_value'])) {
            // $this->set('normal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
            //}
            // max date code start
            $max_app_date = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 154)));
            if (isset($regconfig['regconfig']['info_value'])) {

                if ($max_app_date['regconfig']['conf_bool_value'] == 'Y' && $max_app_date['regconfig']['info_value'] != null) {
                    $max_date = strtotime($max_app_date['regconfig']['info_value']);
                    $maxdatediff = $max_date - $now;

                    $max_normal_days = round($maxdatediff / (60 * 60 * 24));

                    if ($max_normal_days < $regconfig['regconfig']['info_value']) {
                        $this->set('normal_days', '+' . $max_normal_days . 'd');
                    } else {
                        $this->set('normal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
                    }
                } else {

                    $this->set('normal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
                }
            }

            //max date code end

            if (!$this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                $this->set('tatkal_paid', 'N');
            } else {
                $this->set('tatkal_paid', 'Y');
            }


            $office_id = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
            $officename = ClassRegistry::init('office')->field('office.office_name_' . $this->Session->read('doc_lang'), array('office_id' => $office_id));
            $doc_lang = $this->Session->read('doc_lang');
            $office = $this->office->get_officedetails_for_appointment($office_id);

            $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $doc_lang), 'order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $office[0]['office']['shift_id'])));

            $this->set('office_id', $office_id);
            $this->set('officeshift', $officeshift);
            $appointment = $this->appointment->find('all', array('conditions' => array('appointment.token_no ' => $this->Session->read("Selectedtoken"), 'appointment.user_id' => $user_id)));

            $this->set('officename', $officename);
            $this->set('appointment', $appointment);


            $tatkalslot = $this->office->get_officedetails_for_tatkalappointment($office_id);
            if (empty($tatkalslot)) {
                $this->set('tatkal_availbility', 'N');
            } else {
                $this->set('tatkal_availbility', 'Y');
            }

//          pr($submission);exit;

            $gov_apt_slot = $this->office->get_officedetails_for_govappointment($office_id);
            if (empty($gov_apt_slot)) {
                $this->set('gov_apt_availbility', 'N');
            } else {
                $this->set('gov_apt_availbility', 'Y');
            }

            $check_gov_party = $this->party_entry->query("select party_catg_id from ngdrstab_trn_party_entry_new where token_no=? group by party_catg_id
", array($this->Session->read('Selectedtoken')));
            if (!empty($check_gov_party)) {
                $gov_party = 0;
                foreach ($check_gov_party as $check_gov_party1) {
                    $party_catg_id = $check_gov_party1[0]['party_catg_id'];
                    if ($party_catg_id == 8 || $party_catg_id == 24 || $party_catg_id == 25) {
                        $gov_party = 1;
                    }
                }
            }
            if (!empty($gov_party)) {
                if ($gov_party == 1) {
                    $this->set('gov_apt_show', 'Y');
                } else {
                    $this->set('gov_apt_show', 'N');
                }
            } else {
                $this->set('gov_apt_show', 'N');
            }


            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist['appointment_date']['text'] = 'is_required';
            $fieldlist['shift_id']['select'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['appointment']['csrftoken']);
                if (isset($this->request->data['appointment']['reschedule_flag']) && $this->request->data['appointment']['reschedule_flag'] == 'Y') {
                    $this->appointment->deleteAll(['token_no' => $this->Session->read("Selectedtoken")]);
                }
                $this->request->data['appointment'] = $this->istrim($this->request->data['appointment']);

                if (isset($this->request->data['appointment']['appointment_date'])) {
                    $app_date = date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date']));
                    $today = date('Y-m-d');


                    //check max date
                    if ($max_app_date['regconfig']['conf_bool_value'] == 'Y' && $max_app_date['regconfig']['info_value'] != null) {
                        if ($max_normal_days < $regconfig['regconfig']['info_value']) {
                            $limit = Date('Y-m-d', strtotime("+" . $max_normal_days . " days"));
                        } else {
                            $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));
                        }
                    } else {
                        $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));
                    }

                    //max date code end
                    // $limit=Date('Y-m-d', strtotime("+".$regconfig['regconfig']['info_value']." days"));

                    if ($app_date < $today) {
                        $this->Session->setFlash(__("Please Check System Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }

                    if ($app_date > $limit) {
                        $this->Session->setFlash(__("Please Check  Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }
                }
                $errarr = $this->validatedata($this->request->data['appointment'], $fieldlist);
                if ($this->ValidationError($errarr)) {
                    $this->request->data['appointment']['user_type'] = $this->Session->read("session_usertype");
                    if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                        $this->Session->setFlash(__("Please Select slot"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }

                    list($interval, $slot) = explode('_', $_POST['slot']);
                    $data = array('office_id' => $this->request->data['appointment']['office_id'],
                        'interval_id' => $interval,
                        'slot_no' => $slot,
                        'appointment_date' => date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])),
                        'user_id' => $user_id,
                        'user_type' => $this->request->data['appointment']['user_type'],
                        'state_id' => $stateid,
                        // 'created_date' => $created_date,
                        'totalslot' => $_POST['totalslot'],
                        'req_ip' => $ip,
                        'token_no' => $this->Session->read("Selectedtoken"),
                        'sheduled_time' => $_POST['time'],
                        'shift_id' => $this->request->data['appointment']['shift_id'],
                        'flag' => 'N');



                    $check = $this->appointment->find('first', array('conditions' => array('interval_id' => $interval, 'slot_no' => $slot, 'office_id' => $this->request->data['appointment']['office_id'], 'appointment_date' => date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])), 'flag' => 'N')));

                    if (empty($check)) {

                        $usertype = $this->Session->read("session_usertype");
                        if ($usertype == 'O') {
                            $data['org_user_id'] = $this->Auth->User("user_id");
                            $data['org_updated'] = date('Y-m-d H:i:s');
                        }
                        if ($this->appointment->save($data)) {
                            //sms code

                            $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));


                            $officename = ClassRegistry::init('office')->field('office.office_name_' . $this->Session->read('doc_lang'), array('office_id' => $office_id1));
                            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 2)));

                            $message = 'Office name:-' . $officename . '  Date:- ' . ' ' . $data['appointment_date'] . '  Time:-' . ' ' . $data['sheduled_time'] . 'Your Pre-registration number is ' . $this->Session->read("Selectedtoken");

                            if (!empty($event)) {
                                if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                    $seller = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                            array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='1'"))
                                    )));
                                    if (!empty($seller)) {
                                        $mobno = $seller[0]['party_entry']['mobile_no'];
                                        $this->smssend(3, $mobno, $message, $this->Session->read("citizen_user_id"), 2);
                                    }

                                    $buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                            array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
                                    )));
                                    if (!empty($buyer)) {
                                        $mobno1 = $buyer[0]['party_entry']['mobile_no'];
                                        $this->smssend(3, $mobno1, $message, $this->Session->read("citizen_user_id"), 2);
                                    }
                                }
                            }
                            $this->Session->setFlash(__("Slot allocated Successfully"));
                            $this->set_csrf_token();
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Please Check Slot Again"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }
                } else {
                    $this->check_csrf_token_withoutset($csrftoken);
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    /*     * **************************************Function for checking appoinment date is holiday or not ********************** */

    public function check_appointmentdate() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['app_date'])) {
                $this->loadModel('holiday');
                $date = date('Y-m-d', strtotime($_POST['app_date']));
                $holidaylist = $this->holiday->find('all', array('conditions' => array(
                        'and' => array(
                            array('holiday.holiday_fdate <= ' => $date,
                                'holiday.holiday_tdate >= ' => $date
                )))));

                if (empty($holidaylist)) {

                    echo 'a';
                } else {
                    echo 'b';
                }
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function is_weekend($your_date) {
        $week_day = date('w', strtotime($your_date));
        //returns true if Sunday or Saturday else returns false
        return ($week_day == 0 || $week_day == 6);
    }

    /*     * **************************************Function for allocating slot ********************** */

    public function slot_alocation_old() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['office_id']) and is_numeric($_POST['office_id']) and isset($_POST['shift_id']) and is_numeric($_POST['shift_id'])) {
                array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment', 'regconfig'));

                $stateid = $this->Auth->User("state_id");
                $tatkal = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 53)));
                $office = $this->office->get_officedetails_for_appointment($_POST['office_id']);

                $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $_POST['shift_id'])));

                $appointment = $this->appointment->find('all', array('conditions' => array(
                        'appointment.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment.office_id' => trim($_POST['office_id']), 'appointment.shift_id' => trim($_POST['shift_id']), 'appointment.state_id' => $stateid, 'appointment.flag' => 'N'
                )));

                if ($office[0]['office']['tatkal_slot_id']) {
                    $this->set('tatkal', 'Y');
                } else {
                    $this->set('tatkal', 'N');
                }

                $this->set('appointment', $appointment);

                if (!empty($office)) {

                    $this->set('slot', $office[0]['slot']['slot_time_minute']);


                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['appnt_from_time']));
                    $time2 = date('G:i', strtotime($shift[0]['officeshift']['appnt_to_time']));
                    $time_diff = $this->get_time_difference($time1, $time2);

                    //lunch array
                    $lunch_time_array = array();
                    $i = 0;
                    $time11 = date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']));
                    do {
                        if (((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])))) {
                            $lunch_time_array[$i] = $time11 . '-' . date('G:i', strtotime($time11) + 30 * 60);
                            $time11 = date('G:i', strtotime($time11) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    //tatkal array

                    $tatkal_array = array();
                    $i = 0;
                    $time111 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time']));
                    do {
                        if (((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])))) {
                            $tatkal_array[$i] = $time111 . '-' . date('G:i', strtotime($time111) + 30 * 60);
                            $time111 = date('G:i', strtotime($time111) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])));

                    //appointment array
                    $appt_array = array();
                    $i = 0;
                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['appnt_from_time']));
                    do {
                        if (((strtotime($time1) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['appnt_to_time'])))) {
                            $appt_array[$i] = $time1 . '-' . date('G:i', strtotime($time1) + 30 * 60);
                            $time1 = date('G:i', strtotime($time1) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time1) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['appnt_to_time'])));

                    $tatkal_slot_config = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 99)));
                    if ($office[0]['office']['tatkal_slot_id']) {
                        if (isset($tatkal_slot_config['regconfig']['conf_bool_value']) && $tatkal_slot_config['regconfig']['conf_bool_value'] == 'Y') {
                            $fin_array = array_diff(array_diff($appt_array, $lunch_time_array), $tatkal_array);
                        } else {
                            $fin_array = array_diff($appt_array, $lunch_time_array);
                        }
                    } else {
                        $fin_array = array_diff($appt_array, $lunch_time_array);
                    }
//                    pr($fin_array);
//                    exit;
                    $a = $this->cal_appt($fin_array);


                    $curr_date = date('d-m-Y');


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);
                    $this->set('lunch_from', date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time'])));
                    $this->set('lunch_to', date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    $this->set('tatkal_from', date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time'])));
                    $this->set('tatkal_to', date('G:i', strtotime($shift[0]['officeshift']['tatkal_to_time'])));
                    $this->set('app_dt', $_POST['app_date']);
                    $this->set(compact('extraslot', 'minutes', 'hours', 'a'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function slot_alocation() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['office_id']) and is_numeric($_POST['office_id']) and isset($_POST['shift_id']) and is_numeric($_POST['shift_id'])) {
                array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment', 'regconfig', 'User'));

                $stateid = $this->Auth->User("state_id");
                $tatkal = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 53)));

                $office = $this->office->get_officedetails_for_appointment($_POST['office_id']);

                $virtual_office_flag = $office[0]['office']['is_virtual_office'];
                $this->set('virtual_office_flag', $virtual_office_flag);

                $sro_user_result = $this->User->query("select count(user_id) from ngdrstab_mst_user where role_id=? and office_id=?", array(999901, $_POST['office_id']));
                $sro_user_count = $sro_user_result[0][0]['count'];
                $this->set('sro_user_count', $sro_user_count);

                $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $_POST['shift_id'])));

                if ($virtual_office_flag == 'Y') {
                    $sro_user_id = ClassRegistry::init('genernalinfoentry')->field('sro_user_id', array('token_no' => $this->Session->read("Selectedtoken")));
                    $appointment = $this->appointment->find('all', array('conditions' => array(
                            'appointment.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment.office_id' => trim($_POST['office_id']), 'appointment.shift_id' => trim($_POST['shift_id']), 'appointment.state_id' => $stateid, 'appointment.flag' => 'N'
                        ), 'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'generalinfo', 'conditions' => array("appointment.token_no=generalinfo.token_no and generalinfo.sro_user_id=$sro_user_id"))
                    )));
                } else {
                    $appointment = $this->appointment->find('all', array('conditions' => array(
                            'appointment.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment.office_id' => trim($_POST['office_id']), 'appointment.shift_id' => trim($_POST['shift_id']), 'appointment.state_id' => $stateid, 'appointment.flag' => 'N'
                    )));
                }
                if ($office[0]['office']['tatkal_slot_id']) {
                    $this->set('tatkal', 'Y');
                } else {
                    $this->set('tatkal', 'N');
                }

                $this->set('appointment', $appointment);

                if (!empty($office)) {

                    $this->set('slot', $office[0]['slot']['slot_time_minute']);
                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['appnt_from_time']));
                    $time2 = date('G:i', strtotime($shift[0]['officeshift']['appnt_to_time']));
                    $time_diff = $this->get_time_difference($time1, $time2);

                    //lunch array
                    $lunch_time_array = array();
                    $i = 0;
                    $time11 = date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']));
                    do {
                        if (((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])))) {
                            $lunch_time_array[$i] = $time11 . '-' . date('G:i', strtotime($time11) + 30 * 60);
                            $time11 = date('G:i', strtotime($time11) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    //tatkal array

                    $tatkal_array = array();
                    $i = 0;
                    $time111 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time']));
                    do {
                        if (((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])))) {
                            $tatkal_array[$i] = $time111 . '-' . date('G:i', strtotime($time111) + 30 * 60);
                            $time111 = date('G:i', strtotime($time111) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])));

                    //appointment array
                    $appt_array = array();
                    $i = 0;
                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['appnt_from_time']));
                    do {
                        if (((strtotime($time1) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['appnt_to_time'])))) {
                            $appt_array[$i] = $time1 . '-' . date('G:i', strtotime($time1) + 30 * 60);
                            $time1 = date('G:i', strtotime($time1) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time1) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['appnt_to_time'])));

                    $tatkal_slot_config = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 99)));
                    if ($office[0]['office']['tatkal_slot_id']) {
                        if (isset($tatkal_slot_config['regconfig']['conf_bool_value']) && $tatkal_slot_config['regconfig']['conf_bool_value'] == 'Y') {
                            $fin_array = array_diff(array_diff($appt_array, $lunch_time_array), $tatkal_array);
                        } else {
                            $fin_array = array_diff($appt_array, $lunch_time_array);
                        }
                    } else {
                        $fin_array = array_diff($appt_array, $lunch_time_array);
                    }
//                    pr($fin_array);
//                    exit;
                    $a = $this->cal_appt($fin_array);


                    $curr_date = date('d-m-Y');


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);
                    $this->set('lunch_from', date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time'])));
                    $this->set('lunch_to', date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    $this->set('tatkal_from', date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time'])));
                    $this->set('tatkal_to', date('G:i', strtotime($shift[0]['officeshift']['tatkal_to_time'])));
                    $this->set('app_dt', $_POST['app_date']);
                    $this->set(compact('extraslot', 'minutes', 'hours', 'a'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function cal_appt($fin_array) {

        $a = array();
        $j = 0;
        $fin_array = array_values($fin_array);
        $app = $fin_array;

        for ($i = 0; $i < count($fin_array); $i++) {

            if (count($app) >= 2) {
                $c = explode('-', $app[0]);
                $d = explode('-', $app[1]);
                $diff = $this->get_time_difference($c[0], $d[1]);
                if ($diff == 1) {
                    $a[$j] = $c[0] . '-' . $d[1];
                    unset($app[0]);
                    unset($app[1]);
                } else {
                    $a[$j] = $app[0];
                    unset($app[0]);
                }

                $app = array_values($app);
            } else if (!empty($app)) {

                $a[$j] = $app[0];
                break;
            }
            $j++;
        }
        $a = array_combine(range(1, count($a)), $a);
        return $a;
    }

    function get_time_difference($time1, $time2) {

        $time1 = strtotime("1/1/1980 $time1");
        $time2 = strtotime("1/1/1980 $time2");

        if ($time2 < $time1) {
            $time2 = $time2 + 86400;
        }

        return ($time2 - $time1) / 3600;
    }

    //function for tatkal appoinment

    public function tatkalappoinment_OLD($csrftoken = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment_temp', 'appointment', 'ApplicationSubmitted', 'fees_calculation', 'fees_calculation_detail', 'regconfig', 'tatkal_app_config'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 43)));

            // insert temp to original table

            if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                if ($this->insert_apptmt_temp_to_original()) {
                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->set_csrf_token();
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            }


            $this->set('tatkal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $doc_lang = $this->Session->read('doc_lang');
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $office_id = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
            $office = $this->office->get_officedetails_for_appointment($office_id);

            $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $doc_lang), 'order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $office[0]['office']['shift_id'])));
            $this->set('office_id', $office_id);
            $this->set('officeshift', $officeshift);
            $appointment = $this->appointment->find('all', array('conditions' => array(
                    'appointment.token_no ' => $this->Session->read("Selectedtoken"), 'appointment.user_id' => $user_id)));
            $this->set('appointment', $appointment);
            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array(
                    'ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));
            if (count($submission) > 0) {
                $this->set('submission_flag', 'Y');
            } else {
                $this->set('submission_flag', 'N');
            }
            $tatkal_amt = $this->tatkal_app_config->find('first');
            if (!empty($tatkal_amt)) {
                $amount = $tatkal_amt['tatkal_app_config']['amount'];
            } else {
                $amount = 0;
            }
            $this->set('amount', $amount);
            if (!$this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                $this->set('tatkal_paid', 'N');
            } else {
                $this->set('tatkal_paid', 'Y');
            }
            if ($this->request->is('post')) {

                // $this->check_csrf_token($this->request->data['tatkalappoinment']['csrftoken']);
//               if(!$this->checkpayment_fortatkal()){
//                     $this->Session->setFlash(__("Please Pay Tatkal Fee Using Make Payment Button"));
//                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
//                   
//               }


                if (isset($this->request->data['tatkalappoinment']['appointment_date'])) {
                    $app_date = date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date']));
                    $today = date('Y-m-d');
                    $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));


                    if ($app_date < $today) {
                        $this->Session->setFlash(__("Please Check System Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                    }

                    if ($app_date > $limit) {
                        $this->Session->setFlash(__("Please Check  Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                    }
                }

                $this->request->data['tatkalappoinment']['user_type'] = $this->Session->read("session_usertype");
                if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                    $this->Session->setFlash(__("Please Select slot"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                }

                list($interval, $slot) = explode('_', $_POST['slot']);
                $data = array('office_id' => $this->request->data['tatkalappoinment']['office_id'],
                    'interval_id' => $interval,
                    'slot_no' => $slot,
                    'appointment_date' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])),
                    'user_id' => $user_id,
                    'user_type' => $this->request->data['tatkalappoinment']['user_type'],
                    'state_id' => $stateid,
                    'tatkal_totalslot' => $_POST['totalslot'],
                    'req_ip' => $ip,
                    'token_no' => $this->Session->read("Selectedtoken"),
                    'sheduled_time' => $_POST['time'],
                    'shift_id' => $this->request->data['tatkalappoinment']['shift_id'],
                    'flag' => 'T');
                $this->set_csrf_token();


                $rec = $this->appointment_temp->find('first', array('conditions' => array(
                        'appointment_temp.token_no ' => $this->Session->read("Selectedtoken"))));
                // pr($rec);exit;
                if (!empty($rec)) {

                    $this->appointment_temp->deleteAll(array('appointment_temp.token_no' => $rec['appointment_temp']['token_no']));
                }

                $appointment_temp = $this->appointment_temp->find('all', array('conditions' => array(
                        'appointment_temp.appointment_date ' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])), 'appointment_temp.office_id' => trim($this->request->data['tatkalappoinment']['office_id']), 'appointment_temp.shift_id' => trim($this->request->data['tatkalappoinment']['shift_id']), 'appointment_temp.slot_no' => trim($slot), 'appointment_temp.interval_id' => trim($interval), 'appointment_temp.state_id' => $stateid, 'appointment_temp.flag' => 'T'
                )));

                $appt = $this->appointment->find('first', array('conditions' => array('office_id' => trim($this->request->data['tatkalappoinment']['office_id']), 'interval_id' => trim($interval), 'slot_no' => trim($slot), 'appointment_date' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])), 'flag' => 'T')));

                if (empty($appointment_temp) && empty($appt)) {
                    if ($this->appointment_temp->save($data)) {
                        if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                            if ($this->insert_apptmt_temp_to_original()) {
                                $this->Session->setFlash(__("Slot allocated Successfully"));
                                $this->set_csrf_token();
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                            }
                        } else {
                            $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                        }
                    }
                } else {
                    $this->Session->setFlash(__("Slots Not Available"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
//            echo '<pre>';
//            print_r($ex);
//            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function tatkalappoinment($csrftoken = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment_temp', 'appointment', 'ApplicationSubmitted', 'fees_calculation', 'fees_calculation_detail', 'regconfig', 'tatkal_app_config'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 43)));

            // insert temp to original table

            if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                if ($this->insert_apptmt_temp_to_original()) {
                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->set_csrf_token();
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            }
            $now = time(); // or your date as well
            $max_app_date = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 154)));
            if (isset($regconfig['regconfig']['info_value'])) {

                if ($max_app_date['regconfig']['conf_bool_value'] == 'Y' && $max_app_date['regconfig']['info_value'] != null) {
                    $max_date = strtotime($max_app_date['regconfig']['info_value']);
                    $maxdatediff = $max_date - $now;

                    $max_normal_days = round($maxdatediff / (60 * 60 * 24));

                    if ($max_normal_days < $regconfig['regconfig']['info_value']) {
                        $this->set('tatkal_days', '+' . $max_normal_days . 'd');
                    } else {
                        $this->set('tatkal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
                    }
                } else {

                    $this->set('tatkal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
                }
            }

            //$this->set('tatkal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $doc_lang = $this->Session->read('doc_lang');
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $office_id = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
            $office = $this->office->get_officedetails_for_appointment($office_id);

            $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $doc_lang), 'order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $office[0]['office']['shift_id'])));
            $this->set('office_id', $office_id);
            $this->set('officeshift', $officeshift);
            $appointment = $this->appointment->find('all', array('conditions' => array(
                    'appointment.token_no ' => $this->Session->read("Selectedtoken"), 'appointment.user_id' => $user_id)));
            $this->set('appointment', $appointment);
            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array(
                    'ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));
            if (count($submission) > 0) {
                $this->set('submission_flag', 'Y');
            } else {
                $this->set('submission_flag', 'N');
            }
            $tatkal_amt = $this->tatkal_app_config->find('first');
            if (!empty($tatkal_amt)) {
                $amount = $tatkal_amt['tatkal_app_config']['amount'];
            } else {
                $amount = 0;
            }
            $this->set('amount', $amount);
            if (!$this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                $this->set('tatkal_paid', 'N');
            } else {
                $this->set('tatkal_paid', 'Y');
            }
            if ($this->request->is('post')) {

                // $this->check_csrf_token($this->request->data['tatkalappoinment']['csrftoken']);
//               if(!$this->checkpayment_fortatkal()){
//                     $this->Session->setFlash(__("Please Pay Tatkal Fee Using Make Payment Button"));
//                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
//                   
//               }


                if (isset($this->request->data['tatkalappoinment']['appointment_date'])) {
                    $app_date = date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date']));
                    $today = date('Y-m-d');
                    //  $limit=Date('Y-m-d', strtotime("+".$regconfig['regconfig']['info_value']." days"));

                    if ($max_app_date['regconfig']['conf_bool_value'] == 'Y' && $max_app_date['regconfig']['info_value'] != null) {
                        if ($max_normal_days < $regconfig['regconfig']['info_value']) {
                            $limit = Date('Y-m-d', strtotime("+" . $max_normal_days . " days"));
                        } else {
                            $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));
                        }
                    } else {
                        $limit = Date('Y-m-d', strtotime("+" . $regconfig['regconfig']['info_value'] . " days"));
                    }


                    if ($app_date < $today) {
                        $this->Session->setFlash(__("Please Check System Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                    }

                    if ($app_date > $limit) {
                        $this->Session->setFlash(__("Please Check  Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                    }
                }

                $this->request->data['tatkalappoinment']['user_type'] = $this->Session->read("session_usertype");
                if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                    $this->Session->setFlash(__("Please Select slot"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                }

                list($interval, $slot) = explode('_', $_POST['slot']);
                $data = array('office_id' => $this->request->data['tatkalappoinment']['office_id'],
                    'interval_id' => $interval,
                    'slot_no' => $slot,
                    'appointment_date' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])),
                    'user_id' => $user_id,
                    'user_type' => $this->request->data['tatkalappoinment']['user_type'],
                    'state_id' => $stateid,
                    'tatkal_totalslot' => $_POST['totalslot'],
                    'req_ip' => $ip,
                    'token_no' => $this->Session->read("Selectedtoken"),
                    'sheduled_time' => $_POST['time'],
                    'shift_id' => $this->request->data['tatkalappoinment']['shift_id'],
                    'flag' => 'T');
                $this->set_csrf_token();


                $rec = $this->appointment_temp->find('first', array('conditions' => array(
                        'appointment_temp.token_no ' => $this->Session->read("Selectedtoken"))));
                // pr($rec);exit;
                if (!empty($rec)) {

                    $this->appointment_temp->deleteAll(array('appointment_temp.token_no' => $rec['appointment_temp']['token_no']));
                }

                $appointment_temp = $this->appointment_temp->find('all', array('conditions' => array(
                        'appointment_temp.appointment_date ' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])), 'appointment_temp.office_id' => trim($this->request->data['tatkalappoinment']['office_id']), 'appointment_temp.shift_id' => trim($this->request->data['tatkalappoinment']['shift_id']), 'appointment_temp.slot_no' => trim($slot), 'appointment_temp.interval_id' => trim($interval), 'appointment_temp.state_id' => $stateid, 'appointment_temp.flag' => 'T'
                )));

                $appt = $this->appointment->find('first', array('conditions' => array('office_id' => trim($this->request->data['tatkalappoinment']['office_id']), 'interval_id' => trim($interval), 'slot_no' => trim($slot), 'appointment_date' => date('Y-m-d', strtotime($this->request->data['tatkalappoinment']['appointment_date'])), 'flag' => 'T')));

                if (empty($appointment_temp) && empty($appt)) {
                    if ($this->appointment_temp->save($data)) {
                        if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                            if ($this->insert_apptmt_temp_to_original()) {
                                $this->Session->setFlash(__("Slot allocated Successfully"));
                                $this->set_csrf_token();
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                            }
                        } else {
                            $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                        }
                    }
                } else {
                    $this->Session->setFlash(__("Slots Not Available"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
//            echo '<pre>';
//            print_r($ex);
//            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    /*     * **************************************Function for allocating slot ********************** */

    public function tatkal_slot_alocation() {

        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['office_id']) and is_numeric($_POST['office_id']) and isset($_POST['shift_id']) and is_numeric($_POST['shift_id'])) {
                array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment', 'appointment_temp'));

                $stateid = $this->Auth->User("state_id");

                $office = $this->office->get_officedetails_for_tatkalappointment($_POST['office_id']);

                $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $_POST['shift_id'])));

//                pr($this->appointment_temp->query('select * from ngdrstab_trn_test'));
//                exit;

                $appt_temp = $this->appointment_temp->find('all');
                // pr($appt_temp);exit;
                foreach ($appt_temp as $appt) {
                    $datetime1 = strtotime($appt['appointment_temp']['created']);

                    $curr_date = strtotime(date('Y-m-d H:i:s'));

                    $interval = abs($curr_date - $datetime1);

                    $minutes = round($interval / 60);

                    if ($minutes > 45) {
                        if (!$this->checkpayment_fortatkal($appt['appointment_temp']['token_no'])) {
                            $this->appointment_temp->deleteAll(array('appointment_temp.token_no' => $appt['appointment_temp']['token_no']));
                        }
                    }
                }

                $appointment = $this->appointment->find('all', array('conditions' => array(
                        'appointment.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment.office_id' => trim($_POST['office_id']), 'appointment.shift_id' => trim($_POST['shift_id']), 'appointment.state_id' => $stateid, 'appointment.flag' => 'T'
                )));

                $appointment_temp = $this->appointment_temp->find('all', array('conditions' => array(
                        'appointment_temp.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment_temp.office_id' => trim($_POST['office_id']), 'appointment_temp.shift_id' => trim($_POST['shift_id']), 'appointment_temp.state_id' => $stateid, 'appointment_temp.flag' => 'T'
                )));
                $temp = array();
                $i = 0;
                foreach ($appointment_temp as $app) {

                    $temp[$i]['appointment'] = $app['appointment_temp'];
                    $i++;
                }

                $appointment = array_merge($appointment, $temp);


                $this->set('appointment', $appointment);

                if (!empty($office)) {


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);


                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_to_time']));
                    $time2 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time']));
                    $time_diff = $this->get_time_difference($time1, $time2);

                    //lunch array
                    $lunch_time_array = array();
                    $i = 0;
                    $time11 = date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']));
                    do {
                        if (((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])))) {
                            $lunch_time_array[$i] = $time11 . '-' . date('G:i', strtotime($time11) + 30 * 60);
                            $time11 = date('G:i', strtotime($time11) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    //tatkal array

                    $tatkal_array = array();
                    $i = 0;
                    $time111 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time']));
                    do {
                        if (((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])))) {
                            $tatkal_array[$i] = $time111 . '-' . date('G:i', strtotime($time111) + 30 * 60);
                            $time111 = date('G:i', strtotime($time111) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])));


                    $fin_array = array_diff($tatkal_array, $lunch_time_array);
                    $a = $this->cal_appt($fin_array);


                    $curr_date = date('d-m-Y');


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);
                    $this->set('lunch_from', date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time'])));
                    $this->set('lunch_to', date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    $this->set('tatkal_from', date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time'])));
                    $this->set('tatkal_to', date('G:i', strtotime($shift[0]['officeshift']['tatkal_to_time'])));
                    $this->set('app_dt', $_POST['app_date']);
                    $this->set(compact('extraslot', 'minutes', 'hours', 'a'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_maxappointmentday() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['app_date'])) {
                $this->loadModel('office');
                $this->loadModel('officeshift');
                $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $_POST['shift_id'])));
                if (strtotime($_POST['app_date']) > strtotime(date('Y-m-d', strtotime("+" . $shift[0]['officeshift']['tatkal_days'] . "days")))) {
                    echo $shift[0]['officeshift']['tatkal_days'];
                } else {
                    echo 'b';
                }

                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_land_record_fetching() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('regconfig'));
            if ($_POST['party'] == 1) {
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 15)));
                echo $regconfig['regconfig']['conf_bool_value'];
            } else if ($_POST['party'] == 2) {
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 33)));
                echo $regconfig['regconfig']['conf_bool_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_7_12_compulsary() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('regconfig'));
            if ($_POST['party'] == 1) {
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 27)));
                echo $regconfig['regconfig']['conf_bool_value'];
            } else if ($_POST['party'] == 2) {
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 34)));
                echo $regconfig['regconfig']['conf_bool_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_7_12_record() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['id']) && isset($_POST['party'])) {
                $ref_record = array();
                array_map(array($this, 'loadModel'), array('parameter', 'party_entry', 'property_details_entry', 'areaconversion', 'regconfig', 'interface_trn', 'genernalinfoentry', 'old_document_trn', 'lr_taluka_mapping', 'external_interface'));
                $user_id = $this->Session->read("citizen_user_id");
                $stateid = $this->Auth->User("state_id");
                $doc_lang = $this->Session->read('doc_lang');
                $ip = $_SERVER['REMOTE_ADDR'];
                $created_date = date('Y-m-d H:i:s');
                $land_type = $this->parameter->get_land_type($_POST['id']);
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 15)));
                $property = $this->property_details_entry->find('first', array('conditions' => array('property_id' => $_POST['id'])));
                $inpt_3format = $this->get_name_format();
                $this->set('inpt_3format', $inpt_3format);
                if (!empty($land_type)) {
                    $census_code = $land_type[0][0]['census_code'];
                    if ($land_type[0][0]['developed_land_types_id'] == 2) {
                        $this->loadModel('extinterfacefielddetails');
                        //function for making input array

                        $input = $this->set_input_array_for_7_12($_POST['id'], $census_code, $_POST['party']);

                        $interface = $this->external_interface->find('all', array('conditions' => array(
                                'external_interface.interface_id ' => 1)));
                        // $arr = array('ccode' => '272500130317380000', 'serveyno' => '380', 'khatano' => '1216', 'flag' => 'R', 'TranscationID' => '100', 'districtcode' => '25', 'talukacode' => '13', 'TranscationIDnew' => '100');
                        $data_string = http_build_query($input);

                        $ch = curl_init($interface[0]['external_interface']['interface_url']);

                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/x-www-form-urlencoded',
                            'Content-Length: ' . strlen($data_string))
                        );
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
                        $result1 = curl_exec($ch);


                        if (!curl_errno($ch)) {

                            $xml = new SimpleXMLElement($result1);

                            $xml1 = simplexml_load_string($xml);
                            $json = json_encode($xml1); // convert the XML string to JSON
                            $array = json_decode($json, TRUE);
                            $inarray = $this->property_details_entry->query("select v.village_name_$doc_lang,t.taluka_name_$doc_lang,d.district_name_$doc_lang from ngdrstab_conf_admblock7_village_mapping v,ngdrstab_conf_admblock5_taluka t,ngdrstab_conf_admblock3_district d
                                      where v.taluka_id=t.taluka_id and t.district_id=d.district_id and v.census_code='" . $census_code . "'");
                            if (!empty($array)) {
                                $attributeout = $this->extinterfacefielddetails->get_output_attr($this->Auth->User("state_id"), 1);

                                $output = array();
                                foreach ($array as $k => $v) {
                                    foreach ($attributeout as $out) {

                                        if ($k == $out['extinterfacefielddetails']['column_name']) {
                                            $output[$k] = $v;
                                        }
                                    }
                                }
                                $lable = array();
                                foreach ($attributeout as $out) {
                                    $lable[$out['extinterfacefielddetails']['id']] = $out['extinterfacefielddetails']['mapping_name'];
                                }
                                $attributein = $this->extinterfacefielddetails->find('all', array('conditions' => array(
                                        'extinterfacefielddetails.interface_id ' => 1, 'extinterfacefielddetails.ext_interface_param_inout_type' => 'I', 'extinterfacefielddetails.display_flag' => 'Y', 'extinterfacefielddetails.state_id' => $this->Auth->User("state_id"), 'extinterfacefielddetails.send_flag' => 'Y'), 'order' => array('send_order' => 'ASC')));
                                $this->set(compact('lable', 'output', 'inarray', 'attributein', 'attributeout')); //close connection
                                curl_close($ch);
                            } else {
                                echo 1;
                                exit;
                            }
                        } else {
                            echo 1;
                            exit;
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_7_12_record_php() {
        try {
            if (isset($_POST['id']) && isset($_POST['party'])) {
                $ref_record = array();
                array_map(array($this, 'loadModel'), array('parameter', 'party_entry', 'property_details_entry', 'areaconversion', 'regconfig', 'interface_trn', 'genernalinfoentry', 'old_document_trn', 'lr_taluka_mapping', 'external_interface', 'extinterfacefielddetails'));
                $user_id = $this->Session->read("citizen_user_id");
                $stateid = $this->Auth->User("state_id");
                $doc_lang = $this->Session->read('doc_lang');
                $ip = $_SERVER['REMOTE_ADDR'];
                $created_date = date('Y-m-d H:i:s');
                $land_type = $this->parameter->get_land_type($_POST['id']);
                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 15)));
                $property = $this->property_details_entry->find('first', array('conditions' => array('property_id' => $_POST['id'])));

                if (!empty($land_type)) {
                    $census_code = $land_type[0][0]['census_code'];
                    if ($land_type[0][0]['developed_land_types_id'] == 2) {
                        if ($property) {
                            $dist_id = $property['property_details_entry']['district_id'];
                            $tal_id = $property['property_details_entry']['taluka_id'];
                            $village_id = $property['property_details_entry']['village_id'];
                            $input = array('district_id' => $dist_id,
                                'taluka_id' => $tal_id,
                                'village_id' => $village_id);
                            $servey = $this->parameter->get_property_parameter($property['property_details_entry']['property_id'], $this->Auth->User("state_id"), $_POST['party']);
                            if (!empty($servey)) {
                                foreach ($servey as $result1) {
                                    if (preg_match("~\battr2\b~", $result1[0]['mapping_name'])) {
                                        $input['serveyno'] = $result1[0]['paramter_value'];
                                    }
                                    if (preg_match("~\battr3\b~", $result1[0]['mapping_name'])) {
                                        $input['khatano'] = $result1[0]['paramter_value'];
                                    }
                                }
                            }

                            $data_string = http_build_query($input);
                            $interface = $this->external_interface->find('all', array('conditions' => array(
                                    'external_interface.interface_id ' => 5)));
                            $ch = curl_init($interface[0]['external_interface']['interface_url']);

                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                'Content-Type: application/x-www-form-urlencoded',
                                'Content-Length: ' . strlen($data_string))
                            );
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//execute post

                            $result1 = curl_exec($ch);

                            $array = json_decode($result1, True);




                            $inarray = $this->property_details_entry->query("select v.village_name_$doc_lang,t.taluka_name_$doc_lang,d.district_name_$doc_lang from ngdrstab_conf_admblock7_village_mapping v,ngdrstab_conf_admblock5_taluka t,ngdrstab_conf_admblock3_district d
                                      where v.taluka_id=t.taluka_id and t.district_id=d.district_id and v.taluka_id=$tal_id and v.district_id=$dist_id and v.village_id=$village_id");
                            if (!empty($array)) {

                                $attributeout = $this->extinterfacefielddetails->get_output_attr($this->Auth->User("state_id"), 5);

                                $output = array();

                                foreach ($array as $k => $v) {

                                    foreach ($attributeout as $out) {

                                        if ($k == $out['extinterfacefielddetails']['column_name']) {
                                            $output[$k] = $v;
                                        }
                                    }
                                }


                                $lable = array();
                                foreach ($attributeout as $out) {
                                    $lable[$out['extinterfacefielddetails']['id']] = $out['extinterfacefielddetails']['mapping_name'];
                                }
                                $attributein = $this->extinterfacefielddetails->find('all', array('conditions' => array(
                                        'extinterfacefielddetails.interface_id ' => 5, 'extinterfacefielddetails.ext_interface_param_inout_type' => 'I', 'extinterfacefielddetails.display_flag' => 'Y', 'extinterfacefielddetails.state_id' => $this->Auth->User("state_id"), 'extinterfacefielddetails.send_flag' => 'Y'), 'order' => array('send_order' => 'ASC')));
                                $this->set(compact('lable', 'output', 'inarray', 'attributein', 'attributeout', 'array')); //close connection

                                curl_close($ch);
                            }
                        }
                    } else if ($land_type[0][0]['developed_land_types_id'] == 1) {

                        $ref_doc_no = $this->genernalinfoentry->find('first', array('fields' => array('ref_doc_no'), 'conditions' => array(
                                'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken"))));

                        $type = '';
                        if ($ref_doc_no) {

                            if ($ref_doc_no['genernalinfoentry']['ref_doc_no'] != '') {
                                $type = 'T';
                                $ref_record = $this->party_entry->query('select p.* from ngdrstab_trn_party_entry_new p ,ngdrstab_trn_application_submitted a where a.token_no=p.token_no and p.party_catg_id=1 and a.doc_reg_no=?', array($ref_doc_no['genernalinfoentry']['ref_doc_no']));
                            }

                            $this->set("doc_lang", $this->Session->read('doc_lang'));
                            $this->set("type", $type);
                            $this->set("ref_record", $ref_record);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function save_7_12_transaction() {

        /* echo $result;
          $data = array('interface_id' => 1,
          'trn_date' => $created_date,
          'user_id' => $user_id,
          'state_id' => $stateid,
          //'created_date' => $created_date,
          'req_ip' => $ip,
          'iattr1' => $attr1_val,
          'iattr2' => $attr2_val,
          'iattr3' => $attr3_val,
          'iattr4' => $attr4_val,
          'iattr5' => $attr5_val,
          // 'oattr1'
          );

          if (!empty($response)) {

          $this->Session->write('flag', 1);

          $temp = array();



          foreach ($response as $response1) {

          $outarray = array();
          $p = 1;
          foreach ($response1 as $key => $val) {
          $outarray['oattr' . $p] = $val;
          $p++;
          }

          $merge = array_merge($data, $outarray);

          $this->interface_trn->create(false);
          $this->interface_trn->save($merge);
          }
          } else {
          $this->Session->write('flag', '');
          }


         */
    }

    function check_area_for_7_12() {
        // $prop = $this->property_details_entry->query("select DISTINCT v.item_value,v.area_unit  from ngdrstab_trn_valuation_details v,ngdrstab_trn_property_details_entry p,ngdrstab_mst_usage_items_list l
//                   //    where v.val_id = p.val_id  and p.property_id=" . $_POST['id'] . " and v.item_id = l.usage_param_id and l.area_field_flag='Y' ");
//
//                        $totalarea = 0;
//                        for ($m = 0; $m < count($prop); $m++) {
//                            $totalarea = $totalarea + $this->areaconversion->standardareaconversion($prop[$m][0]['item_value'], $prop[$m][0]['area_unit']);
//                        }
//                        if (!empty($response)) {
//                            if ($response[0]['khand_no'] == 1) {
//                                $unit = 2;
//                            } else if ($response[0]['khand_no'] == 2) {
//                                $unit = 1;
//                            }
//                            $outputarea = $this->areaconversion->standardareaconversion($response[0]['sum'], $unit);
//
//                            if ($totalarea > $outputarea) {
//
//                                echo 'o';
//                                exit;
//                            }
//                        } else {
//                            $this->Session->write('flag', '');
//                        }                        
    }

    function set_input_array_for_7_12($property_id, $census_code, $type) {
        array_map(array($this, 'loadModel'), array('extinterfacefielddetails', 'property_details_entry', 'parameter', 'interface_trn', 'lr_taluka_mapping'));
        $attributein1 = $this->extinterfacefielddetails->get_input_attr($this->Auth->User("state_id"));


        $property = $this->property_details_entry->find('first', array('conditions' => array('property_id' => $property_id)));
        $input = array();
        foreach ($attributein1 as $attr) {
            $input[$attr['extinterfacefielddetails']['column_name']] = '';
        }

        $result = $this->parameter->get_property_parameter($property_id, $this->Auth->User("state_id"), $type);
//        pr($result);
//        exit;
        if (!empty($result)) {
            foreach ($result as $result1) {
                if (preg_match("~\battr2\b~", $result1[0]['mapping_name'])) {
                    if (isset($input['serveyno'])) {
                        $input['serveyno'] = $result1[0]['paramter_value'];
                    }
                }
                if (preg_match("~\battr3\b~", $result1[0]['mapping_name'])) {
                    if (isset($input['khatano'])) {
                        $input['khatano'] = $result1[0]['paramter_value'];
                    }
                }
            }
        }
        $max = $this->interface_trn->query('SELECT MAX(trn_id) FROM ngdrstab_trn_interface_details');
        $taluka = $this->lr_taluka_mapping->find('first', array('conditions' => array('lr_taluka_mapping.ngdrs_taluka_id' => $property['property_details_entry']['taluka_id'], 'district_id' => $property['property_details_entry']['district_id'])));


        if (isset($input['ccode'])) {
            $input['ccode'] = $census_code;
        }
        if (isset($input['flag'])) {
            $input['flag'] = 'R';
        }
        if (isset($input['TranscationID'])) {
            $input['TranscationID'] = $max[0][0]['max'] + 1;
        }
        if (isset($input['TranscationIDnew'])) {
            $input['TranscationIDnew'] = $max[0][0]['max'] + 1;
        }

        if (!empty($taluka)) {
            if (isset($input['districtcode'])) {
                $input['districtcode'] = $taluka['lr_taluka_mapping']['igr_district_id'];
            }
            if (isset($input['talukacode'])) {
                $input['talukacode'] = $taluka['lr_taluka_mapping']['igr_taluka_id'];
            }
        }


        return $input;
    }

    function check_filevalidation() {
        try {

            if (isset($_POST['file'])) {
                $this->loadModel('upload_file_format');
                $this->loadModel('upload_document');
                $extension = pathinfo($_POST['file'], PATHINFO_EXTENSION);
                $record = $this->upload_file_format->find('first', array('conditions' => array(
                        'upload_file_format.field_type ' => $extension)));
                if (!empty($record)) {


                    $size = $this->upload_document->field('file_size', array('document_id' => $_POST['doc_id']));
                    echo $size;
                } else {
                    echo 'false';
                }
                exit;
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt($val_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {
                $this->loadModel('valuation_details');
                $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                if (!empty($record)) {
                    return $record['valuation_details']['rounded_val_amt'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_sd_old_old($val_id = NULL, $property_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $this->loadModel('valuation_details');
            $this->loadModel('property_details_entry');
            $token = $this->Session->read('Selectedtoken');

            //Sonam code for add valuation amount of same rule_id-------------------------------
            $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
            $data['val'] = 0;
            $data['cons'] = 0;
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
            if ($count == 1 || $this->Session->read("article_id") == 32) {
                $flag = 1;
            } else {
                foreach ($rule_new as $ruleid_new) {
                    if ($ruleid_new != $rule_new[0]) {
                        $flag = 1;
                    }
                }
            }
            //----------------------------------------------------------------------------------------------------------------------------

            if ($flag == 0) {
                $token = $this->Session->read('Selectedtoken');
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));

                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];

                    $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    foreach ($rule_id as $rule) {
                        $ruleid = $rule['rule_id'];
                        array_push($rule_new, $ruleid);
                    }

                    if ($valuation_id) {

                        $this->loadModel('party_entry');

                        $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                        $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                        if (!empty($record)) {
                            $data['val'] += $record['valuation_details']['rounded_val_amt'];

                            $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                            if (!empty($property)) {
                                $data['cons'] = $property['property_details_entry']['consideration_amount'];
                            }

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $token)));
                            $property_id = $prop_id['property_details_entry']['property_id'];
                            $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                            if (!empty($party)) {
                                $data['party'] = $party[0][0]['count'];
                            }
                        }
                    }
                }
            } else {
                $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
                $data['val'] = 0;
                $data['cons'] = 0;
                $data['party'] = 0;
                $property_id = $_POST['property_id'];
                if ($valuation_id) {

                    $this->loadModel('party_entry');

                    $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                    $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    if (!empty($record)) {
                        $data['val'] = $record['valuation_details']['rounded_val_amt'];

                        $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                        if (!empty($property)) {
                            $data['cons'] = $property['property_details_entry']['consideration_amount'];
                        }

                        $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                        if (!empty($party)) {
                            $data['party'] = $party[0][0]['count'];
                        }

                        return json_encode($data);
                        exit;
                    } else {
                        return '0';
                    }
                }
            }
            return json_encode($data);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_sd_old($val_id = NULL, $property_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $this->loadModel('valuation_details');
            $this->loadModel('property_details_entry');
            $token = $this->Session->read('Selectedtoken');
            $this->loadModel('regconfig');
            $article_id = $this->Session->read('article_id');

            $data['val'] = 0;
            $data['cons'] = 0;
            $rule_new = array();
            $flag = $this->get_prop_same_usage_flag($token);
            //----------------------------------------------------------------------------------------------------------------------------

            if ($flag == 0) {
                $token = $this->Session->read('Selectedtoken');
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));

                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];

                    $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    foreach ($rule_id as $rule) {
                        $ruleid = $rule['rule_id'];
                        array_push($rule_new, $ruleid);
                    }

                    if ($valuation_id) {

                        $this->loadModel('party_entry');

                        $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                        $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                        if (!empty($record)) {
                            //$data['val'] += $record['valuation_details']['rounded_val_amt'];

                            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 134)));
                            if (!empty($regconfig)) {
                                if ($regconfig['regconfig']['info_value'] == 'Y') {
                                    if ($article_id == 46) {
                                        @$val_amt += $record['valuation_details']['rounded_val_amt'];
                                        $party_share = $this->party_entry->query("select MAX(share_percentage) from ngdrstab_trn_party_entry_new where token_no=$token");
                                        $max_party_share = $party_share[0][0]['max'];
                                        $val_amt_share = eval("return(" . $val_amt * $max_party_share / 100 . ");");
                                        $valuation_amt = $val_amt - $val_amt_share;
                                        $data['val'] = $valuation_amt;
                                    } else {
                                        $data['val'] += $record['valuation_details']['rounded_val_amt'];
                                    }
                                } else {
                                    $data['val'] += $record['valuation_details']['rounded_val_amt'];
                                }
                            } else {
                                $data['val'] += $record['valuation_details']['rounded_val_amt'];
                            }


                            $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                            if (!empty($property)) {
                                $data['cons'] = $property['property_details_entry']['consideration_amount'];
                            }

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $token)));
                            $property_id = $prop_id['property_details_entry']['property_id'];
                            $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                            if (!empty($party)) {
                                $data['party'] = $party[0][0]['count'];
                            }
                        }
                    }
                }
            } else {
                $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
                $data['val'] = 0;
                $data['cons'] = 0;
                $data['party'] = 0;
                $property_id = $_POST['property_id'];
                if ($valuation_id) {

                    $this->loadModel('party_entry');

                    $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                    $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    if (!empty($record)) {
                        //$data['val'] = $record['valuation_details']['rounded_val_amt'];

                        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 134)));
                        if (!empty($regconfig)) {
                            if ($regconfig['regconfig']['info_value'] == 'Y') {
                                if ($article_id == 46) {
                                    $val_amt = $record['valuation_details']['rounded_val_amt'];
                                    $party_share = $this->party_entry->query("select MAX(share_percentage) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                                    $max_party_share = $party_share[0][0]['max'];
                                    $val_amt_share = eval("return(" . $val_amt * $max_party_share / 100 . ");");
                                    $valuation_amt = $val_amt - $val_amt_share;
                                    $data['val'] = $valuation_amt;
                                } else {
                                    $data['val'] = $record['valuation_details']['rounded_val_amt'];
                                }
                            } else {
                                $data['val'] = $record['valuation_details']['rounded_val_amt'];
                            }
                        } else {
                            $data['val'] = $record['valuation_details']['rounded_val_amt'];
                        }

                        $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                        if (!empty($property)) {
                            $data['cons'] = $property['property_details_entry']['consideration_amount'];
                        }



                        return json_encode($data);
                        exit;
                    } else {
                        return '0';
                    }
                }
            }
            return json_encode($data);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_sd($val_id = NULL, $property_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $this->loadModel('valuation_details');
            $this->loadModel('property_details_entry');
            $this->loadModel('party_entry');
            $this->loadModel('conf_reg_bool_info');
            $token = $this->Session->read('Selectedtoken');

            //Sonam code for add valuation amount of same rule_id-------------------------------

            $flag = $this->get_prop_same_usage_flag($token);

            if ($flag == 0) {
                $token = $this->Session->read('Selectedtoken');
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                $data['val'] = 0;
                $data['cons'] = 0;

                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];

                    if ($valuation_id) {

                        $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                        $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                        if (!empty($record)) {
                            foreach ($record as $record_result) {
                                $data['val'] += $record_result['valuation_details']['final_value'];
                            }
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                            $roundto = '';
                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                        $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                }
                            }
                            $data['val'] = $this->round_tonext($data['val'], $roundto);

                            $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                            if (!empty($property)) {
                                $data['cons'] = $property['property_details_entry']['consideration_amount'];
                            }

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $token)));
                            $property_id = $prop_id['property_details_entry']['property_id'];
                        }
                    }
                }
            } else {
                $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
                $data['val'] = 0;
                $data['cons'] = 0;
                $data['party'] = 0;
                $property_id = $_POST['property_id'];
                if ($valuation_id) {

                    $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                    $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    if (!empty($record)) {
                        foreach ($record as $record_result) {
                            $data['val'] += $record_result['valuation_details']['final_value'];
                        }
                        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                        $roundto = '';
                        if (!empty($regconf)) {
                            if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                    $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                            }
                        }
                        $data['val'] = $this->round_tonext($data['val'], $roundto);

                        $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                        if (!empty($property)) {
                            $data['cons'] = $property['property_details_entry']['consideration_amount'];
                        }


                        $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                        if (!empty($party)) {
                            $data['party'] = $party[0][0]['count'];
                        }


                        return json_encode($data);
                        exit;
                    } else {
                        return '0';
                    }
                }
            }
            return json_encode($data);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
   

    function check_prohibited_prop_old() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->autoRender = FALSE;
            $this->loadModel('proprodtsattr');
            $this->loadModel('proprodts');
            if (is_numeric($_POST['village_id'])) {
                $record = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));

                if (!empty($record)) {
                    $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value'), 'conditions' => array('prohibited_id' => $record['proprodts']['prohibited_id'])));
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $i = 0;


                    if (isset($json2array['prop_attributes_seller'])) {
                        $c = count($json2array['prop_attributes_seller']);
                        foreach ($json2array['prop_attributes_seller'] as $key => $value) {
                            foreach ($attribute as $attr) {

                                if ($attr['proprodtsattr']['paramter_id'] == $key && $attr['proprodtsattr']['paramter_value'] == $value['attribute_value']) {
                                    $i++;
                                }
                            }
                        }

                        if ($i > 0 && $i == $c) {
                            echo $record['proprodts']['prohibited_id'];
                        } else {
                            echo 'N';
                        }
                    } else {
                        echo 'N';
                    }
                } else {
                    echo 'N';
                }
            } else {
                echo 'E';
            }
            exit;
        } catch (Exception $e) {

            $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
        }
    }
    
    

    function check_prohibited_prop_OLD_OLD() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->autoRender = FALSE;
            $this->loadModel('proprodtsattr');
            $this->loadModel('proprodts');
            $this->loadModel('attribute_parameter');

            if (is_numeric($_POST['village_id'])) {
                $record = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));
//prohibition_flag
                if (!empty($record)) {
                    $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value', 'survey_no'), 'conditions' => array('prohibited_id' => $record['proprodts']['prohibited_id'])));
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $i = 0;

                    ksort($json2array['prop_attributes_seller']);

                    if (isset($json2array['prop_attributes_seller'])) {
                        $input = '';
                        $flag = 0;
                        foreach ($json2array['prop_attributes_seller'] as $key => $value) {
                            $attribute_parameter = $this->attribute_parameter->find("first", array('conditions' => array('attribute_id' => $key, 'prohibition_flag' => 'Y')));
                            if (!empty($attribute_parameter)) {
                                $input.='|' . $value['attribute_value'];
                            }
                        }
                        if (!empty($input)) {
                            $input = substr($input, 1);
                        }
                        foreach ($attribute as $attr) {
                            if ($input == $attr['proprodtsattr']['survey_no']) {
                                echo $record['proprodts']['prohibited_id'];
                                $flag = 1;
                            }
                        }
                        if (!$flag) {
                            echo 'N';
                        }
                    } else {
                        echo 'N';
                    }
                } else {
                    echo 'N';
                }
            } else {
                echo 'E';
            }
            exit;
        } catch (Exception $e) {

            $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
        }
    }

    /*
     * status  1 - NOT Prohibited  
     * status  2 - Prohibited but Proceed 
     * status  3 - Prohibited and Not Proceed 
     * status  4 - Error     
     *     */

    function check_prohibited_prop() {
        $this->autoRender = FALSE;
        $state_id = $this->Auth->user('state_id');
        $data = $this->request->data;
        switch ($state_id) {
            case 20:
                return $this->check_prohibited_prop_jh($data);
                break;
            default :
                return $this->check_prohibited_prop_default($data);
        }
    }

    /*
     * status  1 - NOT Prohibited  
     * status  2 - Prohibited but Proceed 
     * status  3 - Prohibited and Not Proceed 
     * status  4 - Error     
     *     */

    function check_prohibited_prop_default($data) {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->autoRender = FALSE;
            $this->loadModel('proprodtsattr');
            $this->loadModel('proprodts');
            $this->loadModel('regconfig');

            if (is_numeric($data['village_id'])) {
                $record = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));

                if (!empty($record)) {
                    $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value'), 'conditions' => array('prohibited_id' => $record['proprodts']['prohibited_id'])));
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                    $i = 0;
                    if (isset($json2array['prop_attributes_seller'])) {
                        $c = count($json2array['prop_attributes_seller']);
                        foreach ($json2array['prop_attributes_seller'] as $key => $value) {
                            $response['data'] = $value;
                            foreach ($attribute as $attr) {

                                if ($attr['proprodtsattr']['paramter_id'] == $value['attribute_id'] && $attr['proprodtsattr']['paramter_value'] == $value['attribute_value']) {
                                    $i++;
                                }
                            }
                        }

                        if ($i > 0 && $i == $c) {

                            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 22, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                            if (!empty($regconfig)) {
                                $response['status'] = 3;
                                $response['msg'] = 'This Property is Prohibited . you are not allowed to registar.';
                                $response['prohibited_id'] = $record['proprodts']['prohibited_id'];
                            } else {
                                $response['status'] = 2;
                                $response['msg'] = 'This Property is Prohibited';
                                $response['prohibited_id'] = $record['proprodts']['prohibited_id'];
                            }
                        } else {
                            $response['status'] = 1;
                            $response['msg'] = 'Not Prohibited';
                        }
                    } else {
                        $response['status'] = 1;
                        $response['msg'] = 'NO Parameter Added';
                    }
                } else {
                    $response['status'] = 1;
                    $response['msg'] = 'Not Prohibited';
                }
            } else {
                $response['status'] = 4;
                $response['msg'] = 'Select Village ';
            }
            return json_encode($response);
        } catch (Exception $e) {
            
        }
    }

    /*
     * status  1 - NOT Prohibited  
     * status  2 - Prohibited but Proceed 
     * status  3 - Prohibited and Not Proceed 
     * status  4 - Error     
     *     */

    function check_prohibited_prop_jh($data) {

        try {

            $this->loadModel('VillageMapping');
            $this->loadModel('regconfig');
            $this->loadModel('proprodts');
            $this->loadModel('proprodtsattr');
            $record = $this->VillageMapping->find('first', array('fields' => array('village_id', 'prohibition_attribute1_flag', 'prohibition_attribute2_flag'), 'conditions' => array('village_id' => $data['village_id'])));



            $response['status'] = 1;
            $response['msg'] = 'NA';

            if (!empty($record)) {
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $attr1 = '';
                $attr2 = '';
                if (isset($json2array['prop_attributes_seller'])) {
                    if (is_array($json2array['prop_attributes_seller'])) {
                        foreach ($json2array['prop_attributes_seller'] as $attributes_seller) {
                            if ($attributes_seller['attribute_id'] == 205) {
                                $attr1 = $attributes_seller['attribute_value'];
                            } else if ($attributes_seller['attribute_id'] == 206) {
                                $attr2 = $attributes_seller['attribute_value'];
                            }
                        }
                    }
                }

                if ($record['VillageMapping']['prohibition_attribute1_flag'] == 'Y' && $record['VillageMapping']['prohibition_attribute2_flag'] == 'Y') {
                    if (!empty($attr1) && !empty($attr2)) {
                        $prohibitedrecord = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));
                        if (!empty($prohibitedrecord)) {
                            $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value'), 'conditions' => array('paramter_id' => 206, 'prohibited_id' => $prohibitedrecord['proprodts']['prohibited_id'], 'survey_no' => $attr1 . '|' . $attr2)));
                            if (!empty($attribute)) {
                                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 22, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                if (!empty($regconfig)) {
                                    $response['status'] = 3;
                                    $response['msg'] = 'This Property is Prohibited . you are not allowed to registar.';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                } else {
                                    $response['status'] = 2;
                                    $response['msg'] = 'This Property is Prohibited';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                }
                            } else {
                                $response['status'] = 1;
                                $response['msg'] = 'Not Prohibited';
                            }
                        } else {
                            $response['status'] = 1;
                            $response['msg'] = 'Not Prohibited';
                        }
                    } else {
                        $response['status'] = 4;
                        $response['msg'] = 'Enter Khata Number and Plot Number';
                    }
                } else if ($record['VillageMapping']['prohibition_attribute1_flag'] == 'Y') {
                    if (!empty($attr1)) {
                        $prohibitedrecord = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));
                        if (!empty($prohibitedrecord)) {
                            $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value'), 'conditions' => array('paramter_id' => 205, 'prohibited_id' => $prohibitedrecord['proprodts']['prohibited_id'], 'survey_no' => $attr1)));
                            if (!empty($attribute)) {
                                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 22, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                if (!empty($regconfig)) {
                                    $response['status'] = 3;
                                    $response['msg'] = 'This Property is Prohibited . you are not allowed to registar.';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                } else {
                                    $response['status'] = 2;
                                    $response['msg'] = 'This Property is Prohibited';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                }
                            } else {
                                $response['status'] = 1;
                                $response['msg'] = 'Not Prohibited';
                            }
                        } else {
                            $response['status'] = 1;
                            $response['msg'] = 'Not Prohibited';
                        }
                    } else {
                        $response['status'] = 4;
                        $response['msg'] = 'Enter Khata Number';
                    }
                } else if ($record['VillageMapping']['prohibition_attribute2_flag'] == 'Y') {
                    if (!empty($attr2)) {
                        $prohibitedrecord = $this->proprodts->find('first', array('fields' => array('prohibited_id'), 'conditions' => array('village_id' => $_POST['village_id'])));
                        if (!empty($prohibitedrecord)) {
                            $attribute = $this->proprodtsattr->find('all', array('fields' => array('paramter_id', 'paramter_value'), 'conditions' => array('paramter_id' => 206, 'prohibited_id' => $prohibitedrecord['proprodts']['prohibited_id'], 'survey_no' => $attr1)));
                            if (!empty($attribute)) {
                                $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 22, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                if (!empty($regconfig)) {
                                    $response['status'] = 3;
                                    $response['msg'] = 'This Property is Prohibited . you are not allowed to registar.';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                } else {
                                    $response['status'] = 2;
                                    $response['msg'] = 'This Property is Prohibited';
                                    $response['prohibited_id'] = $prohibitedrecord['proprodts']['prohibited_id'];
                                }
                            } else {
                                $response['status'] = 1;
                                $response['msg'] = 'Not Prohibited';
                            }
                        } else {
                            $response['status'] = 1;
                            $response['msg'] = 'Not Prohibited';
                        }
                    } else {
                        $response['status'] = 4;
                        $response['msg'] = 'Enter Plot Number';
                    }
                } else {
                    $response['status'] = 1;
                    $response['msg'] = 'Prohibition not configured';
                }
            } else {
                $response['status'] = 4;
                $response['msg'] = 'Village Not Found';
            }
            return json_encode($response);
        } catch (Exception $ex) {
            
        }
    }

     function check_property_attribute() {

        try {
            $this->autoRender = FALSE;
            $state_id = $this->Auth->user('state_id');
            $data = $this->request->data;
            switch ($state_id) {
                case 4:
                    return $this->check_property_attribute_pb($data);
                    break;
                default :
                    return $this->check_property_attribute_default($data);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    function check_property_attribute_default() {
        try {
//            pr('hello');exit;
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->autoRender = FALSE;

            $this->loadModel('regconfig');

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            $regconf = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 174, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            if (!empty($regconf)) {
                if (isset($json2array['prop_attributes_seller'])) {
                    echo 'Y';
                } else {
                    echo 'N';
                }
            } else {
                echo 'Y';
            }
        } catch (Exception $e) {

            $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
        }
    }
    
    function check_config_prohibition() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 22)));
            echo $regconfig['regconfig']['conf_bool_value'];
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function payment($csrftoken = NULL, $id = NULL) {

        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            $last_status_id = $this->Session->read('last_status_id');

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect('genernalinfoentry');
            }//load Model

            array_map(array($this, 'loadModel'), array('stamp_duty', 'ReceiptCounter', 'conf_reg_bool_info', 'exemption_article_rules', 'article', 'fees_calculation', 'fees_calculation_detail', 'external_links', 'payment_mode', 'bank_master', 'PaymentPreference', 'CitizenPaymentEntry', 'article_fee_items', 'PaymentFields', 'payment', 'genernalinfoentry'));
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);


            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $lang = $this->Session->read("sess_langauge");
            $this->set("doc_lang", $this->Session->read('doc_lang'));
            $token = $this->Session->read('Selectedtoken');
            $article_id = $this->Session->read('article_id');
            $exemption_flag = $this->article->field('exemption_applicable', array('article_id' => $article_id));
            $exemption_amount = $this->fees_calculation_detail->find('first', array('fields' => array('SUM(final_value) as final_value'), 'conditions' => array('fee_calc_id' => $this->fees_calculation->find('list', array('fields' => array('fee_calc_id'), 'conditions' => array('delete_flag' => 'N', 'token_no' => $token, 'fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id)))))))));
            $exemption_amount = ($exemption_amount[0]['final_value']) ? $exemption_amount[0]['final_value'] : 0;

            if (is_numeric($id)) {
                if ($this->CitizenPaymentEntry->deleteAll(array('id' => $id, 'token_no' => $token))) {
                    $this->Session->setFlash(__("Record Deleted Successfully"));
                }

                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment', $this->Session->read('csrftoken')));
            }

            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => 2), 'fields' => array('account_head_code', 'fee_item_desc_en')));

            if ($this->Session->read("manual_flag") == 'Y') {
                $payment_mode = $this->payment_mode->get_payment_mode_counter($lang);
            } else {
                $payment_mode = $this->payment_mode->get_payment_mode_online($lang);
            }

            $payment = $this->CitizenPaymentEntry->get_all_payment($token, $lang);


            $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('is_transaction_flag' => 'Y'), 'order' => 'srno ASC'));
            $stamp_duty_details = $this->stamp_duty->get_stamp_duty_payment($token, $user_id, $lang, 1, $article_id); //1 fee Type Id for Online Payment Only
            $payment_url = $this->external_links->find('all', array('conditions' => array('link_id' => 1))); // one for Payment Gateway
            $this->set(compact('payment_mode', 'exemption_flag', 'exemption_amount', 'payment_url', 'lang', 'stamp_duty_details', 'payment', 'accounthead', 'paymentfields'));
            $this->set("fieldlist", $fieldlist = $this->PaymentFields->fieldlist());
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['payment']['csrftoken']);

                /*                 * ********************************end man payment ************************************************************************ */
                $this->request->data['payment']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['payment']['user_id'] = $user_id;

                $this->request->data['payment']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['payment']['token_no'] = $token;
                if (isset($this->request->data['payment']['pdate'])) {
                    $this->request->data['payment']['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['pdate'])));
                }
                if (isset($this->request->data['payment']['estamp_issue_date'])) {
                    $this->request->data['payment']['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['estamp_issue_date'])));
                }

                $request_data = $this->request->data['payment'];
                $fieldlist_new = $this->PaymentFields->fieldlist($request_data['payment_mode_id']);
                $errors = $this->validatedata($request_data, $fieldlist_new);

                if ($this->ValidationError($errors)) {

                    $request_data['token_no'] = $token;
                    // check date validations
                    $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
                    $verification = ClassRegistry::init('office')->field('citizen_payment_verification', array('office_id' => $office_id1));


                    if (isset($request_data['pdate'])) {
                        $request_data['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $request_data['pdate'])));
                    } else {
                        $request_data['pdate'] = date('Y-m-d');
                    }
                    if (isset($request_data['estamp_issue_date'])) {
                        $request_data['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $request_data['estamp_issue_date'])));
                    }


                    $datecheckflag = 1;
                    $payment_mode = $this->payment_mode->find("first", array('conditions' => array('payment_mode_id' => $request_data['payment_mode_id'])));
                    if (!empty($payment_mode)) {
                        $payment_mode = $payment_mode['payment_mode'];
                        $datecheckflag = $this->date_compaire($payment_mode['start_date'], $payment_mode['end_date'], $request_data['pdate']);
                        if (isset($request_data['estamp_issue_date'])) {
                            $datecheckflag = $this->date_compaire($payment_mode['start_date'], $payment_mode['end_date'], $request_data['estamp_issue_date']);
                        }
                    }

                    if ($datecheckflag) {

                        if (isset($payment_mode['payment_mode_id'])) {
                            $usertype = $this->Session->read("session_usertype");
                            $extrafields['token_no'] = $token;
                            if ($usertype == 'C') {
                                $extrafields['user_id'] = $user_id;
                            } else {
                                $extrafields['org_user_id'] = $user_id;
                                $extrafields['org_created'] = date('Y-m-d H:i:s');
                            }
                            $extrafields['user_type'] = $this->Session->read("session_usertype");
                            $extrafields['req_ip'] = $this->request->clientIp();
                            $extrafields['state_id'] = $stateid;
                            $extrafields['article_id'] = $this->Session->read("article_id");
                            $extrafields['lang'] = $lang;
                            if ($verification == 'Y') {
                                $webserviceobj = new WebServiceController();
                                $webserviceobj->constructClasses();
                                $ServiceResponse['Error'] = '';

                                if ($request_data['payment_mode_id'] == 1) {  // 1. If GRAS Payment
                                    $ServiceResponse = $webserviceobj->GrasVerification($request_data, $extrafields);
                                } else if ($request_data['payment_mode_id'] == 6) {  // 6. Estamp
                                    $ServiceResponse = $webserviceobj->EstampVerification($request_data, $extrafields);
                                } else if ($request_data['payment_mode_id'] == 5) {  // 6. ERegistration
                                    $ServiceResponse = $webserviceobj->ERegistrationVerification($request_data, $extrafields);
                                } else if ($request_data['payment_mode_id'] == 10) {  // 10. Pay U By HDFC
                                    $ServiceResponse = $webserviceobj->PayuPayment($request_data, $extrafields);
                                } else {
                                    $ServiceResponse['Error'] = 'Payment Mode Not FOund!';
                                }

                                if (empty($ServiceResponse['Error'])) {
                                    $ServiceResponse['verified_by'] = $this->Session->read("session_usertype");
                                    $ServiceResponse['verified_user_id'] = $user_id;
                                    $ServiceResponse['verification_date'] = date('Y-m-d H:i:s');

                                    $this->CitizenPaymentEntry->Save($request_data);

                                    $this->OnlinePayment->Save($ServiceResponse['OnlinePaymentData']);
                                    $this->payment->SaveAll($ServiceResponse['PaymentData']);
                                    $this->Session->setFlash(__("Record Verified Successfully"));
                                } else {
                                    $this->Session->setFlash(__("Error:" . $ServiceResponse['Error']));
                                }
                            } else {
                                $this->CitizenPaymentEntry->Save($request_data);
                                $this->payment->Save($request_data);
                                $this->Session->setFlash(__("Payment Save Successfully But Not Verified"));
                            }
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Please Select Correct date"));
                        $this->set('RequestData', $this->request->data['payment']);
                    }
                } else {
                    $this->set('RequestData', $this->request->data['payment']);
                }



                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment', $this->Session->read('csrftoken')));
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gras_payment_verification($data = NULL) {
        try {
            $this->loadModel('GrasVerification');
            $this->loadModel('OnlinePayment');
            $this->loadModel('CitizenPaymentEntry');
            $this->loadModel('external_interface');

            if ($data != NULL) {
                $userid = $this->Session->read("citizen_user_id");
                $token = $this->Session->read('Selectedtoken');

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 2)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(__('Bank Api Not Found'));
                    return 0; // GRN Not Found
                }
                $bankapi = $bankapi['external_interface'];
                $url = $bankapi['interface_url'];
                // $url = 'http://10.153.16.145/challan/models/frmgrnverificationoutsidebe.php';

                $fields = array(
                    'GRN' => urlencode($data['grn_no']),
                    'AMOUNT' => urlencode($data['pamount']),
                    'OFFICECODE' => 'IGR039',
                    'VIEWCHALLAN' => NULL,
                    'USERID' => urlencode($userid),
                );
                $fields_string = '';

                //build Query String
                $i = 1;
                foreach ($fields as $key => $value) {
                    if (count($fields) > $i) {
                        $fields_string .= $key . '=' . $value . '&';
                    } else {
                        $fields_string .= $key . '=' . $value;
                    }
                    $i++;
                }

//pr($fields_string);exit;
                $ch1 = curl_init($url);
                curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($fields_string))
                );
                curl_setopt($ch1, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
                $service_result = curl_exec($ch1);
                // pr($service_result);exit;
                curl_close($ch1);
                // with error string  
                // $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$IP MAPPING WITH GRAS NOT PRESENT 10.153.8.105 FOR OFFICE IGR039$$';
                // verified  
                // $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$0000008585201617$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$-$';
                // build array of responce   
                if (!empty($service_result)) {
                    $service_result_array = (explode("$", $service_result));
                    // pr($service_result_array);
                    //exit;
                    $response_array = array();
                    $reskey = NULL;
                    $errorflag = 0;
                    foreach ($service_result_array as $key => $array_val) {

                        if ($array_val == 'ERROR') {
                            $errorflag = 1;
                            $response_array['ERROR'] = '';
                        } elseif ($errorflag == 1) {
                            $response_array['ERROR'] = $response_array['ERROR'] . " | " . $array_val;
                        } elseif ($key != 0 && !empty($array_val)) {
                            if ($key % 2 != 0) {
                                $reskey = $array_val;
                            } else {
                                $response_array[$reskey] = $array_val;
                            }
                        }
                    }
                    // pr($response_array);exit;
                    if (empty($response_array)) {
                        $this->Session->setFlash(__('ERROR : Wrong Service Responce'));
                        return 0;
                    }
                    $check_error = $response_array['ERROR'];
                    $check_error = trim($check_error);

                    if (strlen($check_error) > 5) {  // error occured   
                        // echo 'Error';
                        $this->Session->setFlash(__('ERROR : ' . $check_error));
                        return 0;
                    }
                    //Seperation of payment Details 

                    $account_result_array = (explode("#", $response_array['ACCOUNTDETAILS']));
                    $account_array = array();


                    $counter = 0;

                    foreach ($account_result_array as $key => $array_val) {
                        if ($key != 0 && $counter == 1) {
                            $reskey = $array_val;
                        } else if ($key != 0 && $counter == 5) {
                            $account_array[$reskey] = $array_val;
                            $counter = 0;
                        }
                        $counter++;
                    }
                    // Update Online Payment Received
                    $online_data = $this->add_default_fields();
                    $online_data['payment_mode_id'] = $data['payment_mode_id'];
                    $online_data['payee_fname_en'] = $response_array['PARTYNAME'];
                    $online_data['grn_no'] = $response_array['GRN'];
                    $online_data['cin_no'] = $response_array['CIN'];
                    $online_data['verification_number'] = $response_array['VERIFICATIONNUMBER'];
                    $online_data['gras_account_details'] = $response_array['ACCOUNTDETAILS'];
                    $online_data['pamount'] = $response_array['AMOUNT'];
                    $entrydate = $response_array['ENTRYDATE'];
                    $entrydate_arr = explode("/", $entrydate);  //   06/03/2017
                    $online_data['pdate'] = $entrydate_arr[2] . "-" . $entrydate_arr[1] . "-" . $entrydate_arr[0];
                    // Update Payment Entry Table
                    $payment_entry = $online_data;
                    $payment_entry_all = array();
                    foreach ($account_array as $keyval => $array_val) {
                        $payment_entry['account_head_code'] = $keyval;
                        $payment_entry['pamount'] = $array_val;
                        $payment_entry['token_no'] = $token;
                        array_push($payment_entry_all, $payment_entry);
                    }
                    // pr($response_array['VERIFICATIONNUMBER']);
                    $checkexist = $this->CitizenPaymentEntry->find("all", array('conditions' => array('grn_no' => $online_data['grn_no'])));

                    if (empty($checkexist)) {
                        $this->OnlinePayment->save($online_data);
                        $this->CitizenPaymentEntry->saveAll($payment_entry_all);
                        $this->Session->setFlash(__('Verified And Saved Succeesfully!'));
                    } else {
                        $this->Session->setFlash(__('Already Verified Record Exist!'));
                    }
                    return 1;
                } else {
                    // empty Responce 
                    $this->Session->setFlash(__('Empty Service response'));
                    return 0;
                }

//close connection
            }
            $this->set_csrf_token();
            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment', $this->Session->read('csrftoken')));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function add_default_fields($request_data = NULL) {
        try {
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");


            $request_data['user_id'] = $user_id;
            $request_data['state_id'] = $stateid;

            // $request_data['req_ip'] = "'".$_SERVER['REMOTE_ADDR']."'";
            $request_data['req_ip'] = $this->request->clientIp();
            $request_data['user_type'] = $this->Session->read("session_usertype");

            return $request_data;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_payment_details() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('CitizenPaymentEntry', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read('doc_lang');
            $paymentfields = array();
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->CitizenPaymentEntry->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'], 'token_no' => $token)));
                $this->set("payment", $payment);
                //pr($payment);
            }
            foreach ($paymentfields as $field) {
                if ($field['PaymentFields']['field_name'] == 'bank_id') {
                    $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                    $this->set("bank_master", $bank_master);
                    if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                        $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch'), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                    } else {
                        $branch_master = array();
                    }
                    $this->set("branch_master", $branch_master);
                }
                if ($field['PaymentFields']['field_name'] == 'cos_id') {
                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $doc_lang), 'conditions' => array('hierarchy_id' => 45)));
                    $this->set("office", $office);
                }
            }
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => array(2)), 'fields' => array('fee_item_id', 'fee_item_desc_' . $doc_lang)));
            $this->set("accounthead", $accounthead);
            $this->set("lang", $lang);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_bank_branch() {
        try {
            if (isset($this->request->data['bank']) and is_numeric($this->request->data['bank'])) {
                $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
                $this->autoRender = FALSE;
                $this->loadModel('BankBranch');
                $lang = $this->Session->read("sess_langauge");
                $branch = $this->BankBranch->find("list", array('fields' => array('branch_id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $this->request->data['bank'])));
                return json_encode($branch);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_bank_branch_code() {
        try {
            if (isset($this->request->data['branch']) and is_numeric($this->request->data['branch'])) {
                $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
                $this->autoRender = FALSE;
                $this->loadModel('BankBranch');
                $branch = $this->BankBranch->find("list", array('fields' => array('branch_id', 'ifsc'), 'conditions' => array('branch_id' => $this->request->data['branch'])));
                return json_encode($branch);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function final_submit($csrftoken = NULL) {
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            $last_status_id = $this->Session->read('last_status_id');

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect('genernalinfoentry');
            }
            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            array_map(array($this, 'loadModel'), array('regconfig', 'CitizenUser', 'stamp_duty', 'uploaded_file_trn', 'upload_document', 'smsevent', 'property_details_entry', 'party_category_fields', 'CitizenPaymentEntry', 'article_fee_items', 'article_fee_rule', 'article_fee_subrule', 'party_entry', 'office', 'office_village_map', 'ApplicationSubmitted', 'genernalinfoentry', 'BankPayment', 'conf_reg_bool_info', 'fee_exemption'));

            //checking mandatory document

            $uploaded_file = $this->uploaded_file_trn->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));


            $u1 = array();

            if (!empty($uploaded_file)) {
                for ($j = 0; $j < count($uploaded_file); $j++) {
                    $u1[$j] = $uploaded_file[$j]['uploaded_file_trn']['document_id'];
                }
            }

            /* $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id'), 'joins' => array(
              array(
              'table' => 'ngdrstab_mst_article_document_mapping',
              'alias' => 'ad',
              'type' => 'inner',
              'foreignKey' => false,
              'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.is_required='Y' and ad.article_id=" . $this->Session->read("article_id"))
              )), 'order' => array('upload_document.document_id' => 'ASC')));
             */

            $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
            //pr($gen_info);
            $doctitleid = $gen_info[0]['genernalinfoentry']['title_id'];
            // pr($doctitleid);
            $upload_doc_title_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 143));

            if ($upload_doc_title_flag == 'Y') {
                $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id'), 'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_article_document_mapping',
                            'alias' => 'ad',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.is_required='Y'  and ad.articledescription_id=" . $doctitleid . " and ad.article_id=" . $this->Session->read("article_id"))
                        )), 'order' => array('upload_document.document_id' => 'ASC')));
            } else {
                $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id'), 'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_article_document_mapping',
                            'alias' => 'ad',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.is_required='Y' and ad.article_id=" . $this->Session->read("article_id"))
                        )), 'order' => array('upload_document.document_id' => 'ASC')));
            }


            $u = array();
            if (!empty($upload_file1)) {
                for ($i = 0; $i < count($upload_file1); $i++) {
                    $u[$i] = $upload_file1[$i]['upload_document']['document_id'];
                }
            }
            $containsSearch = count(array_intersect($u1, $u)) == count($u);

            if (count(array_intersect($u1, $u)) != count($u)) {
                $this->Session->setFlash("Please Upload All Mandatory Documents");
                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
            }

            //cheking recalculation flag
//            $stampduty_flag = $this->stamp_duty->field('recalculate_flag', array('token_no' => $this->Session->read("Selectedtoken")));
//            if ($stampduty_flag == NULL || $stampduty_flag == 'Y') {
//                $this->Session->setFlash("Please Recalculate  and Save Fee Details");
//                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'stamp_duty', $this->Session->read('csrftoken')));
//            }
            //sonam changes for separate controller
            $stampduty_flag = $this->stamp_duty->field('recalculate_flag', array('token_no' => $this->Session->read("Selectedtoken")));
            if ($stampduty_flag == NULL || $stampduty_flag == 'Y') {
                $conf_result = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 152)));
                if (!empty($conf_result)) {
                    $conf_value = $conf_result['conf_reg_bool_info']['conf_bool_value'];
                    if ($conf_value == 'Y') {
                        $info_format = $conf_result['conf_reg_bool_info']['info_format'];
                        $info_value = $conf_result['conf_reg_bool_info']['info_value'];
                        $this->Session->setFlash("Please Recalculate  and Save Fee Details");
                        $this->redirect(array('controller' => 'Citizenentry' . $info_format, 'action' => 'stamp_duty' . '_' . $info_value, $this->Session->read('csrftoken')));
                    } else {
                        $this->Session->setFlash("Please Recalculate  and Save Fee Details");
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'stamp_duty', $this->Session->read('csrftoken')));
                    }
                } else {
                    $this->Session->setFlash("Please Recalculate  and Save Fee Details");
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'stamp_duty', $this->Session->read('csrftoken')));
                }
            }

            $token_no = $this->Session->read("Selectedtoken");
            $exm = $this->fee_exemption->query("select * from ngdrstab_trn_fee_exemption where fee_rule_id=197 and token_no=$token_no");
            if (!empty($exm)) {
                $party_uid = $this->party_entry->query("select uid from ngdrstab_trn_party_entry_new p,
                ngdrstab_mst_party_type pt 
                where p.gender_id=2 and pt.party_type_flag='0' and p.party_type_id=pt.party_type_id and token_no=$token_no");
                if (empty($party_uid)) {
                    $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                    $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                    $this->Session->setFlash("Female Exemption can be applicable only for Female Party");
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'stamp_duty', $this->Session->read('csrftoken')));
                }
            }


            $rec = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));


            if (count($rec) > 0) {
                $officename = $this->ApplicationSubmitted->query("select a.office_name_$doc_lang from ngdrstab_mst_office a,ngdrstab_trn_application_submitted b where a.office_id=b.office_id and b.token_no=?", array($this->Session->read("Selectedtoken")));
                if (!empty($officename)) {
                    $this->set('office_name', $officename[0][0]['office_name_' . $doc_lang]);
                }
                $this->set('submitted', 'Y');
            } else {
                $this->set('submitted', 'N');
            }

            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $status = $this->data_entry_status();
            $optional = $this->article_mapping_screen();
            $minor = $this->minor_functions();
            $tab = array();
            foreach ($minor as $menu) {
                if ($menu['minorfunction']['delete_flag'] == 'N') {
                    if ($menu['minorfunction']['dispaly_flag'] == 'C') {
                        $tab[$menu['minorfunction']['id']] = 0;
                    } else {
                        foreach ($optional as $menu1) {
                            if ($menu['minorfunction']['id'] == $menu1['article_screen_mapping']['minorfun_id']) {

                                $tab[$menu['minorfunction']['id']] = 0;
                            }
                        }
                    }
                }
            }
            foreach ($status as $key => $value) {
                foreach ($tab as $k => $v) {
                    if ($key == $k) {
                        $tab[$k] = $value;
                    }
                }
            }

            //-----------for payment varification of compulsory flag  by Madhuri---------29-June-2017-10-50am---------------------------
            $article_id = $this->Session->read('article_id');
//            $compulsory_payment_fees = $this->article_fee_items->query("select DISTINCT fi.account_head_code from ngdrstab_mst_article_fee_items fi,ngdrstab_mst_article_fee_rule fr,ngdrstab_mst_article_fee_subrule fs
//                                               where fi.fee_item_id=fs.fee_output_item_id and fr.fee_rule_id=fs.fee_rule_id and online_compultion_flag='Y'and fr.article_id=?", array($article_id));
//            $c = 0;
//            foreach ($compulsory_payment_fees as $head) {
//
//                $check = $this->CitizenPaymentEntry->find("first", array('conditions' => array('account_head_code' => $head[0]['account_head_code'], 'token_no' => $this->Session->read('Selectedtoken'))));
//                if (!empty($check)) {
//                    $c++;
//                }
//            }
//            if (count($compulsory_payment_fees) == $c) {
//                $tab[5] = 1;
//            } else {
//                $tab[5] = 0;
//            }
            if ($stateid == 31) {
                $token = $this->Session->read('Selectedtoken');
                if (isset($token)) {
                    $result = $this->BankPayment->find("all", array('conditions' => array('token_no' => $token, 'payment_status' => 'SUCCESS')));
                }
                if (!empty($result)) {
                    $tab[5] = 1;
                } else {
                    $tab[5] = 0;
                }
            }

            $tab[9] = 1;

            if (in_array(0, $tab)) {
                $flag = 0;
            } else {
                $flag = 1;
            }
            $this->set('flag', $flag);

            if ($this->Session->read("manual_flag") == 'Y') {
                $office_id = $this->Auth->User("office_id");

                $office = $this->office->find('list', array('fields' => array('office.office_id', 'office.office_name_' . $this->Session->read('doc_lang')), 'conditions' => array('office.office_id' => $office_id)));
                $this->set('office', $office);
            }
            //================== saddam change==========Edited by madhuri===============================

            $token_no = $this->Session->read("Selectedtoken");
            $val_amt = $this->party_entry->query("select sum(rounded_val_amt) ,token_no from ngdrstab_trn_valuation where token_no=$token_no group by token_no");
            $partyfields1 = $this->party_category_fields->find('all', array('conditions' => array('display_flag' => 'Y', 'article_id' => array(9999, $this->Session->read('article_id'))), 'order' => 'order ASC'));
            $article = $this->party_entry->query("select pan_applicable  from ngdrstab_mst_article where article_id=?", array($article_id));
            $party_upload_flag = $this->party_doc_upload_flag();
            if ($party_upload_flag == 'Y') {
                foreach ($partyfields1 as $f) {
                    $f = $f['party_category_fields'];

                    if ($f['condition_flag'] == 'Y') {
                        if (count($val_amt) > 0) {
                            if ($f['field_id_name_en'] == 'pan_no') {
                                if (count($article) > 0) {
                                    if ($article[0][0]['pan_applicable'] == 'Y') {

                                        if ($val_amt[0][0]['sum'] >= $f['condition_value']) {

                                            $party = $this->party_entry->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'party_catg_id' => 1)));


                                            foreach ($party as $single) {
                                                $pan_check = ClassRegistry::init('party_category')->field('pan_applicable', array('category_id' => $single['party_entry']['party_catg_id']));
                                                if ($pan_check == 'Y') {
                                                    $pan_flag = 0;
                                                    if (!empty($single['party_entry']['pan_no']) || !empty($single['party_entry']['uploaded_file'])) {
                                                        $pan_flag = 1;
                                                    }
                                                    if ($pan_flag == 0) {
                                                        $this->Session->setFlash(__("Property Value Exceed limit please enter Pan or upload form 60/61 for each party"));
                                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $permission_applicable = ClassRegistry::init('article')->field('permission_case_no_applicable', array('article_id' => $this->Session->read('article_id')));
            if ($permission_applicable == 'Y') {
                //for cast category
                //$partyrec = $this->party_entry->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
                $partyrec = $this->party_entry->query("select a.*, b.party_type_desc_en
                from ngdrstab_trn_party_entry_new a
                join ngdrstab_mst_party_type b ON a.party_type_id=b.party_type_id and b.party_type_flag='1'
                where token_no=?
                ", array($this->Session->read("Selectedtoken")));
                $p = 0;
                foreach ($partyrec as $single) {
                    if ($single[0]['maincast_id']) {
                        $permission = ClassRegistry::init('maincast')->field('permission_case_no_require', array('maincast_id' => $single[0]['maincast_id']));
                        if ($permission == 'Y') {
                            $p++;
                        }
                    }
                }
                if ($p > 0) {
                    $casefile = $this->uploaded_file_trn->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'document_id' => 7777)));
                    if (empty($casefile)) {
                        $this->Session->setFlash("Please Upload Permission Case Document");
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                    }
                }
            }

            //================== saddam change=======edited by madhuri====================================


            $minor_seller = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 147)));
            if (!empty($minor_seller)) {
                $reginfo_value = $minor_seller['conf_reg_bool_info']['info_value'];
                if ($reginfo_value == 'Y') {
                    $party_age = $this->party_entry->query("select a.age, b.party_type_desc_en
                from ngdrstab_trn_party_entry_new a
                join ngdrstab_mst_party_type b ON a.party_type_id=b.party_type_id and b.party_type_flag='1'
                where token_no=?
                ", array($this->Session->read("Selectedtoken")));
                    $a = 0;
                    foreach ($party_age as $party_age1) {
                        $seller_age = $party_age1[0]['age'];
                        if ($seller_age <= 18) {
                            $a++;
                        }
                    }
                    if ($a > 0) {
                        $upload_file = $this->uploaded_file_trn->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'document_id' => 8888)));
                        if (empty($upload_file)) {
                            $this->Session->setFlash("Please Upload Document for Minor Seller");
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                        }
                    }
                }
            }


            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['final_submit']['csrftoken']);
                $this->request->data['final_submit']['user_type'] = $this->Session->read("session_usertype");
                $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
                $officename = ClassRegistry::init('office')->field('office.office_name_' . $this->Session->read('doc_lang'), array('office_id' => $office_id1));
                $check = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
                if (count($check) <= 0) {

                    $data = array(
                        'token_submit_date' => $created_date,
                        'office_id' => $office_id1,
                        'user_id' => $user_id,
                        'state_id' => $stateid,
                        'req_ip' => $ip,
                        'token_no' => $this->Session->read("Selectedtoken")
                    );
                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($data['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $data['org_user_id'] = $this->Auth->User('user_id');

                            $data['org_created'] = date('Y-m-d H:i:s');
                        }
                    }
                    if ($this->ApplicationSubmitted->save($data)) {

                        $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 1)));
                        $message = $officename . '   Your Pre-registration number is  ' . $this->Session->read("Selectedtoken");

                        if (!empty($event)) {
                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                $seller = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                        array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='1'"))
                                )));
                                if (!empty($seller)) {
                                    $mobno = $seller[0]['party_entry']['mobile_no'];
                                    $this->smssend(2, $mobno, $message, $this->Session->read("citizen_user_id"), 1);
                                }

                                $buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                                        array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
                                )));
                                if (!empty($buyer)) {
                                    $mobno1 = $buyer[0]['party_entry']['mobile_no'];
                                    $this->smssend(2, $mobno1, $message, $this->Session->read("citizen_user_id"), 1);
                                }

                                //send sms
                            }
                        }
                        $this->genernalinfoentry->updateAll(
                                array('genernalinfoentry.submitted_flag' => "'Y'", 'genernalinfoentry.last_status_id' => 2, 'genernalinfoentry.last_status_date' => "'$created_date'", 'genernalinfoentry.submitted_userid' => $this->Auth->User("user_id"), 'genernalinfoentry.submitted_date' => "'$created_date'"), //fields to update
                                array('genernalinfoentry.token_no' => $this->Session->read("Selectedtoken"))  //condition
                        );
                        $this->save_documentstatus(2, $this->Session->read("Selectedtoken"), $office_id1);

                        $this->set_csrf_token();
                        $this->Session->setFlash(__("Application Submitted Successfully"));
                        if ($this->Session->read("manual_flag") == 'Y') {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info'));
                        } else {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                        }
                    }
                } else {
                    $this->Session->setFlash("Token no. already Exit;");
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'final_submit', $this->Session->read('csrftoken')));
                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }

            $this->autoRender = TRUE;
        } catch (Exception $ex) {
//            pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function setdoc_lang() {
        try {
            if ($_POST['csrftoken'] == $this->Session->read('csrftoken') and isset($_POST['lang']) and is_numeric($_POST['lang'])) {
                $this->loadModel('mainlanguage');
                $this->autoRender = FALSE;
                $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $_POST['lang'])));

                if ($language['0']['mainlanguage']['language_code'] == 'en') {
                    $this->Session->write('doc_lang', 'en');
                } else {
                    $this->Session->write('doc_lang', 'll');
                }

                $this->Session->write('doc_lang_id', $language['0']['mainlanguage']['id']);
            } else {
                echo 'csrf';
                return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //upload Document
    function upload_document($csrftoken = NULL) {
        try {
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }
            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            $this->set('formid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $token = $this->Session->read('Selectedtoken');
            array_map(array($this, 'loadModel'), array('upload_document', 'genernalinfoentry', 'article_doc_map', 'office', 'file_config', 'uploaded_file_trn', 'upload_file_format', 'regconfig'));

            /* $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required'), 'joins' => array(
              array(
              'table' => 'ngdrstab_mst_article_document_mapping',
              'alias' => 'ad',
              'type' => 'inner',
              'foreignKey' => false,
              'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.article_id=" . $this->Session->read("article_id"))
              )), 'order' => array('upload_document.document_id' => 'ASC')));
             */

            $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('token_no' => $token)));
            //pr($gen_info);
            $doctitleid = $gen_info[0]['genernalinfoentry']['title_id'];
            //pr($doctitleid);
            $upload_doc_title_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 143));
            //pr($upload_doc_title_flag);
            if ($upload_doc_title_flag == 'Y') {
                $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required', 'upload_document.file_size'), 'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_article_document_mapping',
                            'alias' => 'ad',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.articledescription_id=" . $doctitleid . " and ad.article_id=" . $this->Session->read("article_id"))
                        )), 'order' => array('upload_document.document_id' => 'ASC')));
            } else {
                $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required', 'upload_document.file_size'), 'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_article_document_mapping',
                            'alias' => 'ad',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.article_id=" . $this->Session->read("article_id"))
                        )), 'order' => array('upload_document.document_id' => 'ASC')));
            }


            $this->set('upload_file1', $upload_file1);


            $upload_fileinfo = $this->uploaded_file_trn->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
            $this->set('upload_fileinfo', $upload_fileinfo);

            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['upload']['csrftoken']);
                // $this->set_csrf_token();

                if ($this->request->data['upload']['upload_file']['error'] == 0) {

                    $formid = $_POST['formid'];
                    $fid = $_POST['file_id' . $formid];
                    if ($this->uploadvalidfile($this->request->data['upload']['upload_file'], $fid)) {
                        $file_ext = pathinfo($this->request->data['upload']['upload_file']['name'], PATHINFO_EXTENSION);
                        $filename = str_replace(' ', '_', $this->request->data['upload']['upload_file']['name']);

                        $general = $this->genernalinfoentry->find('first', array('fields' => array('genernalinfoentry.office_id'), 'conditions' => array(
                                'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken"))));

                        $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                                'office.office_id' => $general['genernalinfoentry']['office_id']), 'joins' => array(
                                array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                        )));


                        $path = $this->file_config->find('first', array('fields' => array('filepath')));

                        $createFolder1 = $this->create_folder($path['file_config']['filepath'], 'Documents/');
                        if (!empty($general)) {
                            $dist = $this->create_folder($createFolder1, $office['dist']['district_name_en'] . '/');
                            $taluka = $this->create_folder($dist, $office['office']['taluka_id'] . '/');
                            $office = $this->create_folder($taluka, $general['genernalinfoentry']['office_id'] . '/');

                            $final_folder1 = $this->create_folder($office, $this->Session->read("Selectedtoken") . '/');

                            $final_folder = $this->create_folder($final_folder1, 'Uploads/');

                            $new_name = $this->Session->read("Selectedtoken") . '_' . $fid;

                            if (file_exists($final_folder . '/' . $new_name)) {
                                unlink($final_folder . '/' . $new_name);
                            }

                            $success = move_uploaded_file($this->request->data['upload']['upload_file']['tmp_name'], $final_folder . '/' . $new_name . '.' . $file_ext);

                            if ($success == 1) {
                                // uploaded_file_trn
                                $upload_info = $this->uploaded_file_trn->find('first', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'document_id' => $fid)));
                                if (!empty($upload_info)) {
                                    $this->uploaded_file_trn->deleteAll(array('token_no' => $this->Session->read("Selectedtoken"), 'document_id' => $fid));
                                }
                                $data = array(
                                    'document_id' => $fid,
                                    'input_fname' => $this->request->data['upload']['upload_file']['name'],
                                    'out_fname' => $new_name . '.' . $file_ext,
                                    'user_id' => $user_id,
                                    'user_type' => $this->Session->read("session_usertype"),
                                    'state_id' => $stateid,
                                    //'created_date' => $created_date,
                                    'req_ip' => $ip,
                                    'token_no' => $this->Session->read("Selectedtoken")
                                );
                                if ($this->Session->read("session_usertype") == 'O') {
                                    unset($data['user_id']);

                                    if ($this->Auth->user('office_id')) {

                                        $data['org_user_id'] = $this->Auth->User('user_id');
                                        if (count($upload_info) > 0) {

                                            $data['org_updated'] = date('Y-m-d H:i:s');
                                        } else {
                                            $data['org_created'] = date('Y-m-d H:i:s');
                                        }
                                    }
                                }

                                if (count($upload_info) > 0) {

                                    $this->uploaded_file_trn->id = $upload_info['uploaded_file_trn']['id'];

                                    if ($this->uploaded_file_trn->save($data)) {

                                        $this->Session->setFlash(__("File Updated  Successfully"));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                                    }
                                } else {
                                    $data['org_updated'] = date('Y-m-d H:i:s');
                                    if ($this->uploaded_file_trn->save($data)) {
                                        $this->Session->setFlash(__("File Uploaded Successfully"));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                                    }
                                }
                            }
                        } else {
                            $this->Session->setFlash(__("File format not suported"));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Error in File"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'upload_document', $this->Session->read('csrftoken')));
                    }
                } else {
                    $this->check_csrf_token_withoutset($csrftoken);
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_name_format() {
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 29)));
        if (!empty($regconfig)) {
            return $regconfig['regconfig']['conf_bool_value'];
        }
    }

    public function is_uid_compulsary() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 30)));
            if (!empty($regconfig)) {
                if (isset($_POST['id'])) {
                    echo $regconfig['regconfig']['conf_bool_value'];
                    exit;
                } else {

                    return $regconfig['regconfig']['conf_bool_value'];
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function is_Pan_verification_compulsary() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 31)));
            if (!empty($regconfig)) {
                if (isset($_POST['id'])) {
                    echo $regconfig['regconfig']['conf_bool_value'];
                    exit;
                } else {

                    return $regconfig['regconfig']['conf_bool_value'];
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function identification($csrftoken = NULL) {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }
//load Model
            array_map(array($this, 'loadModel'), array('identificatontype', 'MstIdentification', 'conf_reg_bool_info', 'identification_fields', 'identification', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'identifire_type', 'party_entry', 'partytype'));
            $actiontypeval = $hfid = $hfupdateflag = $popupstatus = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $identification = $this->identification->get_identification_details($doc_lang, $tokenval, $user_id);
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $sro = $this->User->query('select a.*,b.* from ngdrstab_mst_employee a,ngdrstab_mst_user b where a.emp_code=b.employee_id and b.user_id=?', array($this->Auth->User('user_id')));

            if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang)));
            } else {
                $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang), 'conditions' => array('citizen_flag' => 'Y')));
            }
            $idenfire_disply = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 68)));
            $this->set('sro', $sro);
            $this->set('idenfire_disply', $idenfire_disply);
            $this->set('identification', $identification);
            $this->set('identifire_type', $identifire_type);
            $alllevel = $this->doc_levels->get_alllevel();
//set Values
            // master identifire
            $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));

            $masterrecord = $this->MstIdentification->Identifirelist($language, $office_id1);

//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Selectedtoken', 'masterrecord', 'language', 'identification', 'doclevels', 'sro'));

            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype', $partytype_name);

            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype_name', $partytype_name);
//Status check box code   
            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            //validation
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $doc_lang = $this->Session->read('doc_lang');
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            //  PR($languagelist);EXIT;
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist = $this->identification_fields->fieldlist($doc_lang);

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            if ($this->request->is('post')) {

                //$this->check_csrf_token($this->request->data['identification']['csrftoken']);


                if (isset($this->request->data['identification']['identifire_type'])) {
                    if ($this->request->data['identification']['identifire_type'] == 2) {
                        $user = $this->Auth->user();
                        $identification_data = array('token_no' => $this->Session->read('Selectedtoken'),
                            'identifire_type' => $this->request->data['identification']['identifire_type'],
                            'identification_full_name_en' => $user['full_name'],
                            'mobile_no' => $user['mobile_no'],
                            'email_id' => $user['email_id'],
                            'photo_require' => 'N',
                            // 'party_type_id' => $this->request->data['identification']['party_type_id'],
                            'state_id' => $stateid,
                            'org_user_id' => $this->Auth->User('user_id'),
                            'req_ip' => $_SERVER['REMOTE_ADDR']
                        );
                        if ($this->identification->save($identification_data)) {
                            $this->Session->setFlash(__("Record Saved Successfully"));


                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                        }
                    }
                }

                $this->set_csrf_token();
                $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");
                if (!isset($this->request->data['identification']['village_id'])) {
                    $village_id = NULL;
                } else {
                    $village_id = $this->request->data['identification']['village_id'];
                }
                $fieldlist = $this->identification_fields->fieldlist($doc_lang, $village_id);

                $data = $this->set_value_for_save_identification($this->request->data['identification'], $stateid, $tokenval, $user_id);

                $this->request->data['identification'] = $data;
                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
//                    pr($this->request->data);

                    foreach ($bdata as $datafield) {
                        foreach ($datafield as $key => $fieldid) {
                            $this->request->data['identification']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                            //  pr($field);
                            if (isset($bdata['pattern_value_ll'][$key])) {
                                $this->request->data['identification']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                            }
                        }
                    }
                }
                if (is_numeric($this->request->data['identification']['master_id']) && is_numeric($this->request->data['identification']['master_office_id'])) {

                    if (!$this->check_sameidentifire($this->request->data['identification']['master_id'], $this->request->data['identification']['master_office_id'])) {
                        $this->Session->setFlash(__("Record Not Saved,Not a valid Identifire "));
                        if ($this->Session->read('sroidetifier') == 'N') {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                        } else if ($this->Session->read('sroidetifier') == 'Y') {
                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
                        }
                    }
                }
                $pan = isset($this->request->data['identification']['pan_no']) ? ($this->request->data['identification']['pan_no']) : ('');
                $mobile = isset($this->request->data['identification']['mobile_no']) ? ($this->request->data['identification']['mobile_no']) : ('');
                $uid = isset($this->request->data['identification']['uid_no']) ? ($this->enc($this->request->data['identification']['uid_no'])) : ('');
                $email = isset($this->request->data['identification']['email_id']) ? ($this->request->data['identification']['email_id']) : ('');
                if ($this->request->data['hfupdateflag'] == 'Y') {
                    $action = 'U';
//                    if (!$this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
//                        $this->Session->setFlash(__("Record Not Saved,Dupliacate entry not allowed"));
//                        if ($this->Session->read('sroidetifier') == 'N') {
//                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
//                        } else if ($this->Session->read('sroidetifier') == 'Y') {
//                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
//                        }
//                    }
                    $this->request->data['identification']['id'] = $this->request->data['hfid'];
                    $actionvalue = "Updated";
                } else {
                    $action = 'S';
//                    if (!$this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
//                        $this->Session->setFlash(__("Record Not Saved,Dupliacate entry not allowed "));
//                        if ($this->Session->read('sroidetifier') == 'N') {
//                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
//                        } else if ($this->Session->read('sroidetifier') == 'Y') {
//                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
//                        }
//                    }

                    $actionvalue = "Saved";
                }


                $this->request->data['identification'] = $this->istrim($this->request->data['identification']);

                if (isset($this->request->data['identification']['identificationtype_id']) && $this->request->data['identification']['identificationtype_id']) {
                    $rule = $this->identification->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['identification']['identificationtype_id']);
                    if ($rule) {
                        $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                    }
                }
                $errarr = $this->validatedata($this->request->data['identification'], $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }

                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                } else {

                    if (isset($this->request->data['identification']['identificationtype_id']) && $this->request->data['identification']['identificationtype_id'] == 9999) {
                        $this->request->data['identification']['identificationtype_desc_en'] = $this->enc($this->request->data['identification']['identificationtype_desc_en']);
                    }
                    if (isset($this->request->data['identification']['uid_no']) && is_numeric($this->request->data['identification']['uid_no'])) {
                        $this->request->data['identification']['uid_no'] = $this->enc($this->request->data['identification']['uid_no']);
                    }
                    $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");
                    $this->request->data['identification']['token_no'] = $this->Session->read("Selectedtoken");

                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['identification']['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $this->request->data['identification']['org_user_id'] = $this->Auth->User('user_id');
                            if (isset($this->request->data['identification']['id']) && is_numeric($this->request->data['identification']['id'])) {

                                $this->request->data['identification']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['identification']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                    }

                    if ($this->identification->save($this->request->data['identification'])) {
                        if ($actionvalue == 'Updated') {
                            $patterndata['mapping_ref_val'] = $this->request->data['identification']['id'];
                            $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $patterndata['mapping_ref_val'], 5);
                        } else {
                            $patterndata['mapping_ref_val'] = $this->identification->getLastInsertID();
                        }

                        if (isset($this->request->data['property_details']['pattern_id'])) {
                            $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $patterndata['mapping_ref_val'], $this->request->data['property_details'], 5, $this->Session->read("session_usertype"));
                        }

                        $this->Session->setFlash(__("Record $actionvalue Successfully"));
                        if ($actionvalue == 'Updated') {
                            //  $this->redirect(array('action' => 'identification', $tokenval));
                        } else {
                            $identification1 = $this->identification->find('all', array('conditions' => array('id' => $this->identification->getLastInsertId())));
                            //  $this->redirect(array('action' => 'identification', $identification1[0]['identification']['token_no']));
                        }
                    } else {
                        $this->Session->setFlash(__("Record Not $actionvalue "));
                    }
                }

                if ($this->Session->read('sroidetifier') == 'N') {
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                } else if ($this->Session->read('sroidetifier') == 'Y') {
                    $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
            $checkuid_entry = $this->party_entry->find("all", array('conditions' => array('token_no' => $tokenval)));
            $this->set("checkuid_entry", $checkuid_entry);
        } catch (Exception $ex) {
            pr($ex);
            exit;

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_sameidentifire($master_id, $office_id) {
        try {
            $this->autoRender = false;
            array_map(array($this, 'loadModel'), array('regconfig', 'identification'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 52)));
            $date = date('Y-m-d');
            if (!empty($regconfig)) {
                $identification1 = $this->identification->find('all', array('conditions' => array('master_id' => $master_id, 'master_office_id' => $office_id, 'DATE(created)' => date('Y-m-d'))));
                if (count($identification1) == $regconfig['regconfig']['info_value']) {
                    return false;
                } else {
                    return true;
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_identifire($csrf, $id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->check_csrf_token_withoutset($csrf);
        $this->loadModel('identification');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'identifire_type') {
                $this->identification->id = $id;
                if ($this->identification->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken')));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function delete_witness($csrf, $id = null) {
        // pr($id);exit;
        $this->autoRender = false;
//        echo $csrf;
//        echo '<br>';
//        echo $this->Session->read('csrftoken');
//        exit;
        $this->check_csrf_token_withoutset($csrf);

        $this->loadModel('witness');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'identifire_type') {
                $this->witness()->id = $id;
                if ($this->witness->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $this->Session->read('csrftoken')));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    function get_party_flag() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('partytype');
            $partyflag = $this->partytype->find('first', array('conditions' => array('party_type_id' => $_POST['party_type_id'])));
//      pr($partyflag);exit; 
            if (!empty($partyflag)) {
                if (isset($_POST['party_type_id'])) {
                    echo $partyflag['partytype']['party_type_flag'];
                    exit;
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_value_for_save_identification($data, $stateid, $tokenval, $user_id) {
        try {
            $language = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');

            $data['user_id'] = $user_id;
            // $data['created_date'] = date('Y/m/d');
            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['state_id'] = $stateid;
            if (isset($data['dob']) && $data['dob'] != NULL) {
                $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
            }

            $this->set('actiontypeval', $_POST['actiontype']);
            $this->set('hfid', $_POST['hfid']);
            if (isset($data['identification_full_name_en'])) {
                if ($data['identification_full_name_en'] == '') {
                    $data['identification_full_name_en'] = isset($data['fname_en']) ? $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'] : '';
                } else {
                    $data['identification_full_name_en'] = $data['identification_full_name_en'];
                }
            } else {
                $data['identification_full_name_en'] = isset($data['fname_en']) ? $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'] : '';
            }
            if (isset($data['identification_full_name_ll'])) {
                if ($data['identification_full_name_ll'] == '') {
                    $data['identification_full_name_ll'] = isset($data['fname_ll']) ? $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'] : '';
                } else {
                    $data['identification_full_name_ll'] = $data['identification_full_name_ll'];
                }
            } else {
                $data['identification_full_name_ll'] = isset($data['fname_ll']) ? $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'] : '';
            }

            $data['identification_full_name_en'] = ucwords($data['identification_full_name_en']);



            return $data;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function multiple_property_allowed() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);

            $this->autoRender = false;
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 32)));
            if (!empty($regconfig)) {

                echo $regconfig['regconfig']['conf_bool_value'];
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_property_count() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);

            array_map(array($this, 'loadModel'), array('property_details_entry'));
            $result = $this->property_details_entry->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
            echo count($result);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_presenter() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['id'])) {
                array_map(array($this, 'loadModel'), array('party_entry'));
                $result = $this->party_entry->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'is_presenter' => 'Y')));
                echo(count($result));
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_presenter() {
        try {
            //$this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['id'])) {
                array_map(array($this, 'loadModel'), array('party_entry'));
                //check presenter
                $is_pre = $this->party_entry->find("first", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'is_presenter' => 'Y')));
                if (count($is_pre) > 0) {
                    $this->party_entry->id = $is_pre['party_entry']['id'];
                    $this->party_entry->saveField('is_presenter', 'N');
                }
                //set presenter
                $result = $this->party_entry->find("first", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'id' => $_POST['id'])));
                $this->party_entry->id = $result['party_entry']['id'];
                if ($this->party_entry->saveField('is_presenter', 'Y')) {
                    echo 1;
                    exit;
                }
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function is_party_ekyc_auth_compusory() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 30)));
            return $regconfig['regconfig']['conf_bool_value'];
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function party_ekyc_authentication() {
        try {
            echo 'Y';
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function is_party_ekyc_done() {
        try {
            array_map(array($this, 'loadModel'), array('party_entry', 'regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 30)));
            if ($regconfig['regconfig']['conf_bool_value'] == 'Y') {
                $rec = $this->party_entry->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'ekyc_authentication' => 'Y')));

                if (count($rec) > 0) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function pre_registration_docket($csrftoken = NULL, $doc_token_no = NULL, $flag = NULL) {
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            if ($this->checkpayment_fortatkal($this->Session->read('Selectedtoken'))) {
                if ($this->insert_apptmt_temp_to_original()) {
                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->set_csrf_token();
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            }
            $this->check_csrf_token_withoutset($csrftoken);
            $this->set('flag', $flag);
            $doc_token_no = $this->Session->read('Selectedtoken');
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $this->set(compact('doc_token_no', 'lang', 'stateid', 'user_id', 'doc_lang'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function cancel_appointment() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('appointment');
            $token_no = $this->Session->read('Selectedtoken');
            if ($this->appointment->deleteAll(['token_no' => $token_no])) {
                echo 1;
            } else {
                echo 0;
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function validatesurveynumbers() {

        $this->loadModel('Surveyno');
        $this->loadModel('regconfig');
        $this->autoRender = false;
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $data = $this->request->data;

            $stateid = $this->Auth->User("state_id");
            $district = $data['district'];
            $taluka = $data['taluka'];
            $council = $data['council'];
            $village = $data['village'];
            $lavel1 = $data['lavel1'];
            $lavel1_list = $data['lavel1_list'];
            $lavel2 = $data['lavel2'];
            $lavel2_list = $data['lavel2_list'];
            $lavel3 = $data['lavel3'];
            $lavel3_list = $data['lavel3_list'];
            $lavel4 = $data['lavel4'];
            $lavel4_list = $data['lavel4_list'];

            $conditions['state_id'] = $stateid;
            if (is_numeric($district)) {
                $conditions['district_id'] = $district;
            }
            if (is_numeric($taluka)) {
                $conditions['taluka_id'] = $taluka;
            }
            if (is_numeric($council)) {
                $conditions['corp_id'] = $council;
            }
            if (is_numeric($village)) {
                $conditions['village_id'] = $village;
            } else {
                echo "Fail";
            }
            if (is_numeric($lavel1)) {
                $conditions['level1_id'] = $lavel1;
            }
            if (is_numeric($lavel1_list)) {
                $conditions['level1_list_id'] = $lavel1_list;
            }
            if (is_numeric($lavel2)) {
                $conditions['level2_id'] = $lavel2;
            }
            if (is_numeric($lavel2_list)) {
                $conditions['level2_list_id'] = $lavel2_list;
            }
            if (is_numeric($lavel3)) {
                $conditions['level3_id'] = $lavel3;
            }
            if (is_numeric($lavel3_list)) {
                $conditions['level3_list_id'] = $lavel3_list;
            }
            if (is_numeric($lavel4)) {
                $conditions['level4_id'] = $lavel4;
            }
            if (is_numeric($lavel4_list)) {
                $conditions['level4_list_id'] = $lavel4_list;
            } // pr($conditions);
            $conditions['survey_no'] = $data['attribute_value'];
            if (is_numeric($data['attribute_id'])) {
                $conditions['ri_attribute'] = $data['attribute_id'];
            } else if ($data['attribute_id'] == 'NA') {
                
            } else {
                echo "fail";
            }

            $validate = 0;
            $result = $this->Surveyno->find('list', array('fields' => array('survey_no_id', 'survey_no'), 'conditions' => $conditions));
//            pr($conditions);
            foreach ($result as $serveyno) {
                if ($serveyno == $data['attribute_value']) {
                    $validate = 1;
                }
            }
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 101, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            if (empty($regconf)) {
                $validate = 1;
            }



            if ($validate == 1) {
                echo "success";
            } else {
                echo "fail";
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function state_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['state']) and is_numeric($this->request->data['state'])) {
                $party_state = $this->request->data['state'];

                $distlist = ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $party_state)));
                $result_array = array('dist' => $distlist);


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function district_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($this->request->data['dist'])) {
                $laug = $this->Session->read("sess_langauge");
                $doc_lang = $this->Session->read('doc_lang');
                $dist = $this->request->data['dist'];
                $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => $dist)));

                $result_array = array('subdiv' => NULL, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => NULL, 'village' => NULL);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function taluka_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {
                $tal = $this->request->data['tal'];

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                $result_array = array('village' => $villagelist);


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_title() {
        try {

            if ($this->request->data['csrftoken'] == $this->Session->read('csrftoken') and isset($this->request->data['article_id']) and is_numeric($this->request->data['article_id'])) {

                $this->autoRender = FALSE;
                $this->loadModel('doc_title');
                //$doc_lang = $this->Session->read('doc_lang');
                $doc_lang = $this->Session->read("sess_langauge");
                $title = $this->doc_title->find("list", array('fields' => array('articledescription_id', 'articledescription_' . $doc_lang), 'conditions' => array('article_id' => $this->request->data['article_id'])));
                return json_encode($title);
            } else {
                echo json_encode('csrf');
                return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_token_session($token, $csrftoken = NULL) {
        try {
            $this->check_csrf_token($csrftoken);
            $this->loadModel('language');

            $lang = $this->language->query('select DISTINCT l.language_code ,g.local_language_id from ngdrstab_trn_generalinformation g,ngdrstab_mst_language l ,ngdrstab_conf_language cl where g.local_language_id=l.id and g.token_no=?', array($token));
            if (!empty($lang)) {
                if ($lang[0][0]['language_code'] == 'en') {
                    $this->Session->write('doc_lang', 'en');
                } else {
                    $this->Session->write('doc_lang', 'll');
                }
                $this->Session->write('sess_langauge', $lang[0][0]['language_code']);
                CakeSession::write('Config.language', $lang[0][0]['language_code']);
                ClassRegistry::init('Formlabel')->updatepo();
            }
            $this->Session->write('Selectedtoken', $token);
            $this->Session->write('reschedule_flag', 'N');
            $last_status_id = ClassRegistry::init('genernalinfoentry')->field('last_status_id', array('token_no' => $token));
            $this->Session->write('last_status_id', $last_status_id);
            $article_id = ClassRegistry::init('genernalinfoentry')->field('article_id', array('token_no' => $token));
            $this->Session->write('article_id', $article_id);
            if ($last_status_id == 1) {
                $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            } else if ($last_status_id == 2) {
                $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function delete_session() {
        $this->Session->write('Selectedtoken', NULL);
        $this->Session->write('doc_lang', NULL);
        $this->Session->write('article_id', NULL);
        $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
    }

    // 
    // search document
//    function document_search() {
//        try {
//            // $this->set('documentrecord', Null);
//            $this->set('actiontypeval', NULL);
//            $this->set('hfsetradio', NULL);
//            $this->loadModel('ApplicationSubmitted');
//            $lang = $this->Session->read("sess_langauge");
//            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['doc_search']['csrftoken']);
//                
////         pr($this->request->data['actiontype']);
////         exit;
//                $this->loadModel('ApplicationSubmitted');
//                $this->set('hfsetradio', $_POST['hfsetradio']);
//                $this->set('actiontypeval', $_POST['actiontype']);
//                if ($this->request->data['actiontype'] == 'T') {
//                    $docno = $this->request->data['doc_search']['reg_no'];
//                    $documentrecord = $this->ApplicationSubmitted->query("select a.doc_reg_no, c.article_desc_$lang ,a.token_no from ngdrstab_trn_application_submitted a,ngdrstab_trn_generalinformation b,ngdrstab_mst_article c
//                     where c.article_id=b.article_id and a.token_no=b.token_no and doc_reg_no='$docno' ");
//                }
//                if ($this->request->data['actiontype'] == 'D') {
//
//
//                    $from = date('Y-m-d', strtotime($this->request->data['doc_search']['from']));
//                    $to = date('Y-m-d', strtotime($this->request->data['doc_search']['to']));
//
//                    $documentrecord = $this->ApplicationSubmitted->query("select a.doc_reg_no, c.article_desc_$lang ,a.token_no from ngdrstab_trn_application_submitted a,ngdrstab_trn_generalinformation b,ngdrstab_mst_article c
//                     where c.article_id=b.article_id and a.token_no=b.token_no and '" . $from . "' <= doc_reg_date and doc_reg_date <= '" . $to . "'");
//                }
//
//                $this->set('documentrecord', $documentrecord);
//            }
//            $this->set_csrf_token();
//        } catch (Exception $ex) {
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
///
    //validations done by kalyani 10august2017

    function document_search() {
        try {
            // $this->set('documentrecord', Null);
            $this->set('actiontypeval', NULL);
            $this->set('hfsetradio', NULL);
            $this->loadModel('ApplicationSubmitted');
            $lang = $this->Session->read("sess_langauge");
            $fieldlist = array();
            $fieldlist['selectdocno']['radio'] = 'is_required'; // must require
            $fieldlist['reg_no']['text'] = 'is_alphanumspacedashdotslash';
            $fieldlist['from']['text'] = 'is_required'; //dependent
            $fieldlist['to']['text'] = 'is_required'; // dependent
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->request->data['doc_search'] = $this->istrim($this->request->data['doc_search']);
                $fieldlistnew = $this->modifyfieldlistdependentdocsearch($fieldlist, $this->request->data['doc_search']);
                $errarr = $this->validatedata($this->request->data['doc_search'], $fieldlistnew);
                // pr($errarr);
                //pr($this->request->data['doc_search']);exit;
                if ($this->ValidationError($errarr)) {
                    $this->loadModel('ApplicationSubmitted');
                    $this->set('hfsetradio', $_POST['hfsetradio']);
                    $this->set('actiontypeval', $_POST['actiontype']);
                    $documentrecord = array();
                    if ($this->request->data['actiontype'] == 'T') {

                        $docno = $this->request->data['doc_search']['reg_no'];
                        $documentrecord = $this->ApplicationSubmitted->query("select a.doc_reg_no, c.article_desc_$lang ,a.token_no from ngdrstab_trn_application_submitted a,ngdrstab_trn_generalinformation b,ngdrstab_mst_article c
                     where c.article_id=b.article_id and a.token_no=b.token_no and doc_reg_no='$docno' ");
                    }
                    if ($this->request->data['actiontype'] == 'D') {


                        $from = date('Y-m-d', strtotime($this->request->data['doc_search']['from']));
                        $to = date('Y-m-d', strtotime($this->request->data['doc_search']['to']));

                        $documentrecord = $this->ApplicationSubmitted->query("select a.doc_reg_no, c.article_desc_$lang ,a.token_no from ngdrstab_trn_application_submitted a,ngdrstab_trn_generalinformation b,ngdrstab_mst_article c
                     where c.article_id=b.article_id and a.token_no=b.token_no and '" . $from . "' <= doc_reg_date and doc_reg_date <= '" . $to . "'");
                    }
                    $this->set('documentrecord', $documentrecord);
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //validations for document search kalyani
    public function modifyfieldlistdependentdocsearch($fieldlist, $data) {
        if (isset($data['selectdocno']) && $data['selectdocno'] == 'T') {
            unset($fieldlist['from']);
            unset($fieldlist['to']);
        }
        if (isset($data['selectdocno']) && $data['selectdocno'] == 'D') {
            unset($fieldlist['reg_no']);
        }
        return $fieldlist;
    }

    function search_partyname() {
        try {
            $this->loadModel('party_entry');
            $this->loadModel('TrnBehavioralPatterns');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hftoken', NULL);
            $lang = $this->Session->read("sess_langauge");
            if ($this->request->is('post')) {
                $this->set('hftoken', $_POST['hftoken']);
                $this->set('lang', $lang);
                $this->set('actiontypeval', $_POST['actiontype']);
                if ($_POST['actiontype'] == '1') {
                    $party_record = $this->party_entry->query("select  a.*,a.property_id, a.party_fname_$lang,b.party_type_desc_$lang,c.category_name_$lang ,
                                d.salutation_desc_$lang,e.desc_$lang,f.identificationtype_desc_$lang as idntity,h.gender_desc_$lang,i.occupation_name_$lang,
                                j.district_name_$lang,k.taluka_name_$lang,l.village_name_$lang
                                from ngdrstab_trn_party_entry_new a
                                left outer join ngdrstab_mst_party_type b on b.party_type_id = a.party_type_id
                                left outer join ngdrstab_mst_party_category c on c.category_id = a.party_catg_id
                                left outer join ngdrstab_mst_salutation d on d.id = a.salutation_id
                                left outer join ngdrstab_mst_presentation_exemption e on e.exemption_id = a.exemption_id
                                left outer join ngdrstab_mst_identificationtype f on f.identificationtype_id = a.identificationtype_id
                                left outer join ngdrstab_mst_gender h on h.id = CAST(a.sex AS INT)
                                left outer join ngdrstab_mst_occupation i on i.id = a.occupation_id
                                left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                                left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                                left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                                where a.token_no = ? and a.id = ?", array($_POST['hftoken'], $_POST['hfid']));
                    $this->set('party_record', $party_record);
                    $pattern = $this->TrnBehavioralPatterns->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_' . $lang, 'pattern.pattern_desc_ll', 'pattern.field_id', 'TrnBehavioralPatterns.field_value_' . $lang, 'TrnBehavioralPatterns.field_value_ll'),
                        'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $_POST['hfid'], 'TrnBehavioralPatterns.token_no' => $_POST['hftoken'], 'TrnBehavioralPatterns.mapping_ref_id' => 2), // for property:mapping_ref_id => 1
                        'joins' => array(
//                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id AND pattern.behavioral_id=TrnBehavioralPatterns.mapping_ref_id')),
                            array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                        ),
                        'order' => 'pattern.field_id DESC'
                    ));
                    $this->set('pattern_data', $pattern);
//                    pr($party_record);exit;
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_party_byname() {
        try {
            if (isset($_POST['last_name'])) {
                $this->loadModel('party_entry');

                $from = date('Y-m-d', strtotime($_POST['from']));
                $to = date('Y-m-d', strtotime($_POST['to']));
                $partyname = strtoupper($_POST['last_name']);
                $lang = $this->Session->read("sess_langauge");

//            echo $partyname;exit;
                $record = $this->party_entry->query("select  a.*,a.property_id, a.party_fname_$lang,b.party_type_desc_$lang,c.category_name_$lang ,gender.gender_desc_$lang
                                                        from ngdrstab_trn_party_entry_new a
                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
                                                        left outer join ngdrstab_mst_gender gender on gender.gender_id=CAST(a.sex AS INT)
                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where '" . $from . "' <= a.created and a.created <= '" . $to . "' and upper(a.party_full_name_$lang) LIKE '%$partyname%'");
                $this->set('partyrecord', $record);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_attribute_subpart() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel("attribute_parameter");
            $this->autoRender = False;
            $status = 'NA';
            if (is_numeric($this->request->data('attribute_id'))) {
                $result = $this->attribute_parameter->find('all', array('conditions' => array('attribute_id' => $this->request->data('attribute_id'), 'is_subpart_flag' => 'Y')));
                if (!empty($result)) {
                    $status = 'A';
                }
            }
            echo $status;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    //----------------------------------------------- code updated on 30-June-2017 by shridhar --------------------------------------
//    public function certificatesissuedetails() {
//        try {
//            array_map([$this, 'loadModel'], ['payment_mode', 'office', 'certificatesissuedetails', 'article_fee_items', 'article_fee_rule']);
//            $actiontypeval = $actiontypeval = 0;
//            $hfactionval = $hfaction = $hfid = $workrecord = NULL;
//            $officedata = $this->office->find('list', array('fields' => array('office_id', 'office_name_en')));
//            $this->set('hfactionval', 'S');
//            $userid = $this->Session->read("session_user_id");
//
//            $stateid = $this->Auth->User("state_id");
//
//            $result = substr($userid, 4);
//            $userid = substr($result, 0, -4);
//            $this->request->data['certificatesissuedetails']['req_ip'] = $this->request->clientIp();
//            $date = date('Y/m/d H:i:s');
//            $currentdate = date('Y/m/d');
//            $lang = $this->Session->read('sess_langauge');
//            $fee_rules = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_en'), 'conditions' => array('fee_rule_id' => array(131, 132))));
//            $payment_mode = $this->payment_mode->get_payment_mode_online($lang);
//
//            $language = $this->Session->read('sess_langauge');
//            $configure1 = ClassRegistry::init('levelconfig')->find('all', array('conditions' => array('state_id' => $stateid)));
//            $this->set('configure1', $configure1);
//            $this->set('attributes', ClassRegistry::init('attribute_parameter')->find('list', array('fields' => array('attribute_id', 'eri_attribute_name'), 'conditions' => array('state_id' => $stateid))));
//            $this->set('configure', ClassRegistry::init('damblkdpnd')->query("select * from ngdrstab_conf_state_district_div_level where state_id=$stateid"));
//            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $language), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $language => 'ASC'))));
//
//
//            $this->set(compact('actiontypeval', 'actiontypeval', 'officedata', 'hfactionval', 'hfaction', 'hfid', 'fee_rules', 'payment_mode'));
//
//            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['certificatesissuedetails']['csrftoken']);
//                
//                $actiontype = $this->request->data['actiontype'];
//                $this->set('actiontypeval', $actiontype);
//                $hfaction = $this->request->data['hfaction'];
//                $this->set('hfactionval', $hfaction);
//                if ($actiontype == '1') {
//                    if ($hfaction == 'S') {
//                        $this->request->data['certificatesissuedetails']['account_head_code'] = $this->article_fee_items->get_certificate_accheadcode();
//                        $this->request->data['certificatesissuedetails']['user_id'] = $userid;
//                        $this->request->data['certificatesissuedetails']['state_id'] = $stateid;
//                        $this->request->data['certificatesissuedetails']['user_type'] = $this->Session->read("session_citizen");
//                        //--------------------------------30-June to 01-July-2017 by Shridhar for Payment Varification--------------------------------------------
//                        $data = $this->request->data;
//                        $data = array_merge($data['certificatesissuedetails'], $data['simple_reciept']);
//
//                        $varify = $this->varify_payment($data);
//                        $data['grn_no'] = isset($varify['grn_no']) ? $varify['grn_no'] : NULL;
//                        $data['cin_no'] = isset($varify['cin_no']) ? $varify['cin_no'] : NULL;
//                        $data['verification_no'] = isset($varify['verification_no']) ? $varify['verification_no'] : NULL;
//                        $data['account_detail'] = isset($varify['account_detail']) ? $varify['account_detail'] : NULL;
//                        $data['grn_amount'] = isset($varify['pamount']) ? $varify['pamount'] : NULL;
//                        $data['entry_date'] = isset($varify['pdate']) ? $varify['pdate'] : NULL;
//                        $data['grn_party_name'] = isset($varify['grn_party_name']) ? $varify['grn_party_name'] : NULL;
//                        $data['payment_mode_id'] = isset($varify['payment_mode_id']) ? $varify['payment_mode_id'] : NULL;
//                        if ($varify['verification_no']) {
//                            if ($this->certificatesissuedetails->save($data)) {
//                                $this->Session->setFlash(__('Request Send Successfully'));
//                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'certificatesissuedetails'));
//                            } else {
//                                $this->Session->setFlash(__('Request Not Send '));
//                            }
//                        } else {// if varification not done
//                            $this->Session->setFlash(__('!Payment Not Varified, please enter correct Payment Details'));
//                        }
//                        //---------------------------------------------------------------------------------------------------------------
//                    }
//                }
//            }
//            $this->set_csrf_token();
//        } catch (Exception $ex) {
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
    //validations done by kalyani on 10august2017
    public function certificatesissuedetails() {
        try {
            array_map([$this, 'loadModel'], ['payment_mode', 'payment', 'OnlinePayment', 'office', 'certificatesissuedetails', 'article_fee_items', 'article_fee_rule', 'PaymentFields']);
            $actiontypeval = $actiontypeval = 0;
            $hfactionval = $hfaction = $hfid = $workrecord = NULL;
            $officedata = $this->office->find('list', array('fields' => array('office_id', 'office_name_en')));
            $this->set('hfactionval', 'S');
            $userid = $this->Session->read("session_user_id");
            $stateid = $this->Auth->User("state_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $this->request->data['certificatesissuedetails']['req_ip'] = $this->request->clientIp();
            $date = date('Y/m/d H:i:s');
            $currentdate = date('Y/m/d');
            $lang = $this->Session->read('sess_langauge');
            $this->set('lang', $lang);
            $fee_rules = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_en'), 'conditions' => array('fee_rule_id' => array(131, 132))));
            $payment_mode = $this->payment_mode->get_payment_mode_online($lang);
            $language = $this->Session->read('sess_langauge');
            $configure1 = ClassRegistry::init('levelconfig')->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('configure1', $configure1);
            $this->set('attributes', ClassRegistry::init('attribute_parameter')->find('list', array('fields' => array('attribute_id', 'eri_attribute_name'), 'conditions' => array('state_id' => $stateid))));
            $this->set('configure', ClassRegistry::init('damblkdpnd')->query("select * from ngdrstab_conf_state_district_div_level where state_id=$stateid"));
            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $language), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $language => 'ASC'))));
            $this->set(compact('actiontypeval', 'actiontypeval', 'officedata', 'hfactionval', 'hfaction', 'hfid', 'fee_rules', 'payment_mode'));

            $fieldlist = array();
            $fieldlist['ctype']['radio'] = 'is_ctype';
            $fieldlist['applicant_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['office_id']['select'] = 'is_select_req';
            $fieldlist['doc_reg_no']['text'] = 'is_required,is_integer';
            $fieldlist['doc_reg_date']['text'] = 'is_required';
            $fieldlist['paymentmode_id']['select'] = 'is_select_req';
            $fieldlist['uniq_prop_id']['text'] = 'is_required,is_integer';
            $fieldlist['survey_no']['text'] = 'is_required,is_integer';
            $fieldlist['fee_amount']['text'] = 'is_digit';
            $paymentfields = $this->PaymentFields->fieldlist();
            $fieldlist1 = array_merge($fieldlist, $paymentfields);

            $this->set('fieldlist', $fieldlist1); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED

            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['certificatesissuedetails']['csrftoken']);
                //pr($this->request->data);exit;
                $this->request->data['certificatesissuedetails'] = $this->istrim($this->request->data['certificatesissuedetails']);
                $request_data = array_merge($this->request->data['certificatesissuedetails'], $this->request->data['simple_reciept']);
                $paymentfields = $this->PaymentFields->fieldlist($request_data['paymentmode_id']);
                $fieldlist1 = array_merge($fieldlist, $paymentfields);

                $fieldlistnew = $this->modifyfieldlistdependent($fieldlist1, $request_data); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
                $errarr = $this->validatedata($request_data, $fieldlistnew); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
                // pr($errarr);exit;
                if ($this->ValidationError($errarr)) {//UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
                    $actiontype = $this->request->data['actiontype'];
                    $this->set('actiontypeval', $actiontype);
                    $hfaction = $this->request->data['hfaction'];
                    $this->set('hfactionval', $hfaction);
                    if ($this->request->data['certificatesissuedetails']['ctype'] == 'C') {
                        $flag = 'C';
                    } else {
                        $flag = 'E';
                    }
                    if ($actiontype == '1') {
                        if ($hfaction == 'S') {
                            $this->request->data['certificatesissuedetails']['account_head_code'] = $this->article_fee_items->get_certificate_accheadcode($flag);
                            $this->request->data['certificatesissuedetails']['user_id'] = $userid;
                            $this->request->data['certificatesissuedetails']['state_id'] = $stateid;
                            $this->request->data['certificatesissuedetails']['user_type'] = $this->Session->read("session_usertype");


                            //--------------------------------30-June to 01-July-2017 by Shridhar for Payment Varification--------------------------------------------
                            $data = $this->request->data;
                            $data = array_merge($data['certificatesissuedetails'], $data['simple_reciept']);

                            $varify = $this->varify_payment($data);

                            $paymentdata['grn_no'] = isset($varify['grn_no']) ? $varify['grn_no'] : NULL;
                            $paymentdata['cin_no'] = isset($varify['cin_no']) ? $varify['cin_no'] : NULL;
                            $paymentdata['verification_number'] = isset($varify['verification_no']) ? $varify['verification_no'] : NULL;
                            $paymentdata['gras_account_details'] = isset($varify['account_detail']) ? $varify['account_detail'] : NULL;
                            $paymentdata['pamount'] = isset($varify['pamount']) ? $varify['pamount'] : NULL;
                            $paymentdata['pdate'] = isset($varify['pdate']) ? $varify['pdate'] : NULL;
                            $paymentdata['payee_full_name_en'] = isset($varify['grn_party_name']) ? $varify['grn_party_name'] : NULL;
                            $paymentdata['payment_mode_id'] = isset($varify['payment_mode_id']) ? $varify['payment_mode_id'] : NULL;
                            $paymentdata['defacement_flag'] = 'N';
                            $paymentdata['account_head_code'] = $this->article_fee_items->get_certificate_accheadcode($flag);
                            $paymentdata['user_id'] = $userid;
                            $paymentdata['state_id'] = $stateid;

                            $paymentdata['user_type'] = $this->Session->read("session_usertype");

                            //  pr($paymentdata);exit;
                            // $this->redirect(array('controller' => 'Citizenentry', 'action' => 'certificatesissuedetails'));
                            if ($varify['verification_no']) {
                                if ($this->payment->save($paymentdata)) {


                                    $payment_id = $this->payment->getLastInsertID();
                                    $this->OnlinePayment->save($paymentdata);
                                    $online_payment_id = $this->OnlinePayment->getLastInsertID();
                                    $data['payment_id'] = $payment_id;
                                    $data['online_payment_id'] = $online_payment_id;

                                    if ($this->certificatesissuedetails->save($data)) {

                                        $this->Session->setFlash(__('Request Send Successfully'));
                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'certificatesissuedetails'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('Request Not Send '));
                                }
                            } else {// if varification not done
                                $this->Session->setFlash(__('!Payment Not Varified, please enter correct Payment Details'));
                            }
                            //---------------------------------------------------------------------------------------------------------------
                        }
                    }
                }
                //pr($errarr);
                // $this->Session->setFlash(__('Something went wrong'));
            }
            $this->set_csrf_token();
//UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //dependent field validation FOR certified copy kalyani
    public function modifyfieldlistdependent($fieldlist, $data) {
//pr($data);
        //   pr($fieldlist);
        if (isset($data['ctype']) && $data['ctype'] == 'C') {
            unset($fieldlist['uniq_prop_id']);
            unset($fieldlist['survey_no']);
        }

        return $fieldlist;
    }

    //---------------------------------------------- Verify Payment --------------------------------------------------------------------------------------------------
    public function varify_payment($data = NULL) {
        /*
         * compulsory Fields:grn_no,pamount
         * 
         */
        try {
            array_map([$this, 'loadModel'], ['GrasVerification', 'OnlinePayment', 'payment', 'external_interface']);
            if ($data != NULL) {
                $userid = $this->Session->read("session_user_id");
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 2)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(__('Bank Api Not Found'));
                    return 0; // GRN Not Found
                }

                $bankapi = $bankapi['external_interface'];
                $url = $bankapi['interface_url'];

                $fields = array(
                    'GRN' => urlencode($data['grn_no']),
                    'AMOUNT' => urlencode($data['pamount']),
                    'OFFICECODE' => 'IGR039',
                    'VIEWCHALLAN' => NULL,
                    'USERID' => urlencode($userid),
                );

                $fields_string = '';

                //build Query String
                $i = 1;
                foreach ($fields as $key => $value) {
                    if (count($fields) > $i) {
                        $fields_string .= $key . '=' . $value . '&';
                    } else {
                        $fields_string .= $key . '=' . $value;
                    }
                    $i++;
                }

                $ch1 = curl_init($url);
                curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($fields_string))
                );

                curl_setopt($ch1, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);
                //execute post

                $service_result = curl_exec($ch1);
                curl_close($ch1);

                // with error string  
                // $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$IP MAPPING WITH GRAS NOT PRESENT 10.153.8.105 FOR OFFICE IGR039$$';
                // verified  
                // $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$0000008585201617$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$-$';
                // build array of responce                 
                if (!empty($service_result)) {
                    $service_result_array = (explode("$", $service_result));
                    $response_array = array();
                    $reskey = NULL;
                    $errorflag = 0;
                    foreach ($service_result_array as $key => $array_val) {

                        if ($array_val == 'ERROR') {
                            $errorflag = 1;
                            $response_array['ERROR'] = '';
                        } elseif ($errorflag == 1) {
                            $response_array['ERROR'] = $response_array['ERROR'] . " | " . $array_val;
                        } elseif ($key != 0 && !empty($array_val)) {
                            if ($key % 2 != 0) {
                                $reskey = $array_val;
                            } else {
                                $response_array[$reskey] = $array_val;
                            }
                        }
                    }

                    if (empty($response_array)) {
                        pr($response_array);
                        $this->Session->setFlash(__('ERROR : Wrong Service Responce'));
                        return 0;
                    }

                    $check_error = $response_array['ERROR'];

                    $check_error = trim($check_error);
                    if (strlen($check_error) > 5) {  // error occured   
                        $this->Session->setFlash(__('ERROR : ' . $check_error));
                        return 0;
                    }
                    //Seperation of payment Details 
                    $account_result_array = (explode("#", $response_array['ACCOUNTDETAILS']));
                    $account_array = array();
                    $counter = 0;

                    foreach ($account_result_array as $key => $array_val) {
                        if ($key != 0 && $counter == 1) {
                            $reskey = $array_val;
                        } else if ($key != 0 && $counter == 5) {
                            $account_array[$reskey] = $array_val;
                            $counter = 0;
                        }
                        $counter++;
                    }

                    // Update Online Payment Received                
                    $online_data = $this->add_default_fields();
                    $online_data['payment_mode_id'] = $data['payment_mode_id'];
                    $online_data['grn_party_name'] = $response_array['PARTYNAME'];
                    $online_data['grn_no'] = $response_array['GRN'];
                    $online_data['cin_no'] = $response_array['CIN'];
                    $online_data['verification_no'] = $response_array['VERIFICATIONNUMBER'];
                    $online_data['account_detail'] = $response_array['ACCOUNTDETAILS'];
                    $online_data['pamount'] = $response_array['AMOUNT'];
                    $online_data['pdate'] = date('Y-m-d', strtotime($response_array['ENTRYDATE']));
                    // Update Payment Entry Table
                    return $online_data;
//                $payment_entry = $online_data;
//                $payment_entry_all = array();
//                foreach ($account_array as $keyval => $array_val) {
//                    $payment_entry['account_head_code'] = $keyval;
//                    $payment_entry['pamount'] = $array_val;
//                    $payment_entry['token_no'] = $token;
//                    array_push($payment_entry_all, $payment_entry);
//                }               
                    return 1;
                } else {
                    // empty Responce 
                    $this->Session->setFlash(__('Empty Service response'));
                    return 0;
                }
//close connection
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//-----------------------------------------------------end--Varify Payment---------------------------------------------------------------------------
    function get_validation_rule() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            if (isset($this->request->data['type']) && $this->request->data['type'] != '') {
                $data = array();
                $lang = $this->Session->read("sess_langauge");
                $this->loadModel('identificatontype');
                $rule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $lang . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['type']);
                if ($rule) {
                    $data['message'] = $rule[0][0]['error_messages_' . $lang];
                    $data['pattern'] = trim($rule[0][0]['pattern_rule_client']);
                    $data['error_code'] = trim($rule[0][0]['error_code']);
                    echo json_encode($data);
                    exit;
                }
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function delete_party() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->autoRender = FALSE;
            /*
              if (isset($_POST['id'])) {

              $this->loadModel('party_entry');
              $this->loadModel('fee_item_val');
              $this->loadModel('female_exemption');


              $this->party_entry->id = trim($_POST['id']);
              $party_id = $this->party_entry->field('party_id', array(array('id' => trim($_POST['id']))));
              $uid = $this->party_entry->field('uid', array(array('id' => trim($_POST['id']))));

              if ($this->party_entry->delete()) {
              $this->party_entry->query('delete from ngdrstab_trn_behavioral_patterns where  mapping_ref_id=2 and mapping_ref_val=?', array($_POST['id']));
              $this->fee_item_val->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'mapping_ref_id' => $_POST['id'], 'fee_param_code' => 'SAA']);
              $this->female_exemption->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'uid' => $uid]);
              echo 1;
              } else {
              echo 0;
              }
              }
             */
            if (isset($_POST['id'])) {
                $this->loadModel('party_entry');
                $this->loadModel('fee_item_val');
                $this->loadModel('female_exemption');

                $this->party_entry->id = trim($_POST['id']);
                $party_id = $this->party_entry->field('party_id', array(array('id' => trim($_POST['id']))));
                $uid = $this->party_entry->field('uid', array(array('id' => trim($_POST['id']))));


                $this->party_entry->repeat_party_id = trim($_POST['repeat_party_id']);

                $party_id = $this->party_entry->field('repeat_party_id', array(array('repeat_party_id' => trim($_POST['repeat_party_id']))));



                if ($_POST['repeat_party_id'] == "" || $_POST['repeat_party_id'] == NULL) {

                    $this->party_entry->query('delete from ngdrstab_trn_party_entry_new where id=?', array($_POST['id']));
                    /* $this->party_entry->query('delete from ngdrstab_trn_behavioral_patterns where  mapping_ref_id=2 and mapping_ref_val=?', array($_POST['id'])); */
                    $this->fee_item_val->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'mapping_ref_id' => $_POST['id'], 'fee_param_code' => 'SAA']);
                    $this->female_exemption->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'uid' => $uid]);
                    echo 1;
                } elseif ($_POST['repeat_party_id'] != "" && $_POST['repeat_party_id'] != NULL) {

                    $this->party_entry->query('delete from ngdrstab_trn_party_entry_new where   repeat_party_id=?', array($_POST['repeat_party_id']));
                    $this->fee_item_val->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'mapping_ref_id' => $_POST['repeat_party_id'], 'fee_param_code' => 'SAA']);
                    $this->female_exemption->deleteAll(['token_no' => $this->Session->read('Selectedtoken'), 'uid' => $uid]);
                    echo 1;
                } else {
                    echo 0;
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    function get_party_feilds() {
//        try {
//            array_map(array($this, 'loadModel'), array('party_entry', 'District', 'taluka', 'identificatontype', 'party_category_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category'));
//
//            $laug = $this->Session->read("sess_langauge");
//            $lang = $this->Session->read("sess_langauge");
//            $user_id = $this->Session->read("citizen_user_id");
//            $token = $this->Session->read('Selectedtoken');
//            $data = $this->request->data;
//            $doc_lang = $this->Session->read('doc_lang');
//            $fields = $this->set_common_fields();
//            $this->set('laug', $laug);
//            // set array for selection or list fields
//            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
//            $executer = array('Y' => 'Yes', 'N' => 'NO');
//            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang)));
//            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
//            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name')));
//            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
//            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
//            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
//            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
//            $name_format = $this->get_name_format();
//            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
//            $fieldlist = array();
//            $fieldlist['party_type_id']['select'] = 'is_select_req';
//            $fieldlist['party_catg_id']['select'] = 'is_select_req';
//
//            $fieldlist['district_id']['select'] = 'is_select_req';
//            $fieldlist['taluka_id']['select'] = 'is_select_req';
//            $fieldlist['village_id']['select'] = 'is_select_req';
//
//            $this->set('fieldlist', $fieldlist);
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $errarr['identificationtype_desc_en_error'] = '';
//
//            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
//            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
//
//            $partyfields = array();
//            if (isset($data['category']) && is_numeric($data['category'])) {
//                $partyfields = $this->party_category_fields->find('all', array('conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y'), 'order' => 'order ASC'));
//                $auth_sign = $this->party_category->field('authorised_signatory', array(array('category_id' => $data['category'])));
//                if ($auth_sign == 'Y') {
//                    $signatory = $this->party_category_fields->find('all', array('conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'Y', 'display_flag' => 'Y'), 'order' => 'order ASC'));
//
//                    $this->set("signatory", $signatory);
//                }
//            }
//            if (isset($data['id']) && is_numeric($data['id'])) {
//                $party = $this->party_entry->find('all', array('conditions' => array('party_catg_id' => $data['category'], 'party_id' => $data['id'], 'token_no' => $token)));
//                $this->set("party", $party);
//                //pr($payment);
//            }
//
//            // to set data for edit;
//            foreach ($partyfields as $field) {
//                if ($field['party_category_fields']['field_id_name_en'] == 'district_id') {
//                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
//
//                    if (isset($party) and is_numeric($party[0]['party_entry']['district_id'])) {
//                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $party[0]['party_entry']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
//                    } else {
//                        $taluka = array();
//                    }
//                }
//                if (isset($party) and is_numeric($party[0]['party_entry']['taluka_id'])) {
//                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $party[0]['party_entry']['taluka_id'])));
//                } else {
//                    $villagelist = array();
//                }
//            }
//            if (isset($signatory) && !(empty($signatory))) {
//                foreach ($signatory as $field1) {
//                    if ($field1['party_category_fields']['field_id_name_en'] == 'district_id') {
//                        $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
//
//                        if (isset($party) and is_numeric($party[0]['party_entry']['district_id'])) {
//                            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $party[0]['party_entry']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
//                        } else {
//                            $taluka = array();
//                        }
//                    }
//                    if (isset($party) and is_numeric($party[0]['party_entry']['taluka_id'])) {
//                        $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $party[0]['party_entry']['taluka_id'])));
//                    } else {
//                        $villagelist = array();
//                    }
//                }
//            }
//            $this->set(compact('districtdata', 'taluka', 'villagelist', 'partyfields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka', 'auth_sign'));
//        } catch (Exception $e) {
//            pr($e);
//            exit;
//        }
//    }

    function get_party_feilds() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('conf_reg_bool_info', 'party_entry', 'State', 'maincast', 'marital_status', 'partytype', 'DataEntryReject', 'nationality', 'cast_category', 'gov_partytype', 'District', 'taluka', 'identificatontype', 'party_category_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read('doc_lang');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
            $marathi_template = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 159)));
            $this->set("marathi_template", $marathi_template);
            // set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $home_visit = array('Y' => 'Yes', 'N' => 'NO');
            $stamp_purchaser = array('Y' => 'Yes', 'N' => 'NO');
            $industrial = array('Y' => 'Yes', 'N' => 'NO');
            $permission_applicable = ClassRegistry::init('article')->field('permission_case_no_applicable', array('article_id' => $this->Session->read('article_id')));
            $maincast = $this->maincast->find('list', array('fields' => array('maincast.maincast_id', 'maincast.cast_' . $doc_lang)));
            $marital_status = $this->marital_status->find('list', array('fields' => array('status_id', 'status_desc_' . $doc_lang)));
            $nationality = $this->nationality->find('list', array('fields' => array('nationality.nationality_id', 'nationality.nationality_name_' . $doc_lang), 'order' => 'nationality_id ASC'));
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang), 'order' => 'salutation_id ASC'));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $doc_lang), 'order' => 'occupation.occupation_name_' . $doc_lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('separate_list' => 'Y', 'party_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $panlist = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('separate_list' => 'N', 'party_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $doc_lang), 'order' => array('category_name_' . $doc_lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $doc_lang), 'order' => array('government_type_' . $doc_lang => 'ASC')));
            $allparty = $this->party_entry->find('list', array('fields' => array('party_entry.party_id', 'party_entry.party_full_name_' . $doc_lang), 'conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_' . $laug), 'order' => array('state_name_en' => 'ASC')));
            $guardian_name = ClassRegistry::init('regconfig')->field('conf_bool_value', array('reginfo_id' => 120));
//validations
            //category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();

            $partyfields = array();
            if (isset($data['category']) && is_numeric($data['category'])) {
//                $partyfields = $this->party_category_fields->find('all', array('fields'=>array(''),'conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y'), 'order' => 'order ASC'));
                $partyfields = $this->party_category_fields->find('all', array('conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y', 'article_id' => array(9999, $this->Session->read('article_id'))), 'order' => 'order ASC'));
                $auth_sign = $this->party_category->field('authorised_signatory', array(array('category_id' => $data['category'])));
                if ($auth_sign == 'Y') {
                    $signatory = $this->party_category_fields->find('all', array('conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'Y', 'display_flag' => 'Y', 'article_id' => array(9999, $this->Session->read('article_id'))), 'order' => 'order ASC'));
                    $this->set("signatory", $signatory);
                }
            }


            if (isset($data['id']) && is_numeric($data['id'])) {

                if (isset($token) && is_numeric($token)) {

                    $party = $this->party_entry->find('all', array('conditions' => array('party_catg_id' => $data['category'], 'id' => $data['id'], 'token_no' => $token)));
                } else {
                    $party = $this->party_entry->find('all', array('conditions' => array('party_catg_id' => $data['category'], 'id' => $data['id'], 'token_no' => $token)));
                }

                $party[0]['party_entry']['uid'] = $this->dec($party[0]['party_entry']['uid']);
                $this->set("party", $party);

                $type_flag = $this->partytype->field('party_type_flag', array('party_type_id' => $party[0]['party_entry']['party_type_id']));
                $this->set('party_type', $type_flag);
            }
            if (isset($data['party_id']) && is_numeric($data['party_id']) && isset($data['power_att']) && $data['power_att'] == 'Y') {
                $party = $this->party_entry->find('all', array('conditions' => array('party_catg_id' => $data['category'], 'id' => $data['party_id'])));
                $party[0]['party_entry']['uid'] = $this->dec($party[0]['party_entry']['uid']);
                $this->set('auth_sign', 'N');
                $this->set("party", $party);
            }

            // to set data for edit;
            foreach ($partyfields as $field) {
                if ($field['party_category_fields']['field_id_name_en'] == 'district_id') {
                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                    if (isset($party) and is_numeric($party[0]['party_entry']['district_id'])) {
                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $party[0]['party_entry']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                    } else {
                        $taluka = array();
                    }
                }
                if (isset($party) and is_numeric($party[0]['party_entry']['taluka_id'])) {
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $party[0]['party_entry']['taluka_id'])));
                } else {
                    $villagelist = array();
                }
            }
            if (isset($signatory) && !(empty($signatory))) {
                foreach ($signatory as $field1) {
                    if ($field1['party_category_fields']['field_id_name_en'] == 'district_id') {
                        $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                        if (isset($party) and is_numeric($party[0]['party_entry']['district_id'])) {
                            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $party[0]['party_entry']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                        } else {
                            $taluka = array();
                        }
                    }
                    if (isset($party) and is_numeric($party[0]['party_entry']['taluka_id'])) {
                        $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $party[0]['party_entry']['taluka_id'])));
                    } else {
                        $villagelist = array();
                    }
                }
            }
            $rejecttoken = $this->DataEntryReject->find('all', array('conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));

            if (!empty($rejecttoken)) {
                $this->set('rejected', 'Y');
            } else {
                $this->set('rejected', 'N');
            }

            $this->set(compact('districtdata', 'allparty', 'home_visit', 'industrial', 'guardian_name', 'permission_applicable', 'stamp_purchaser', 'State', 'maincast', 'category', 'nationality', 'marital_status', 'cast_cat_flag', 'gov_partytype', 'taluka', 'villagelist', 'partyfields', 'identificatontype', 'panlist', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka', 'auth_sign'));
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_id() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['property_id']) && is_numeric($_POST['property_id'])) {
                $val_id = ClassRegistry::init('property_details_entry')->field('val_id', array('property_id' => $_POST['property_id']));
                echo $val_id;
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function party_entry($csrftoken = NULL, $tokenval = NULL) {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if ($this->Session->read('reschedule_flag') == 'Y') {

                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));

            $stateid = $this->Auth->User("state_id");
            $this->set('stateid', $stateid);

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }//load Model
            array_map([$this, 'loadModel'], ['identificatontype', 'stamp_duty', 'bank_master', 'party_entry', 'party_category', 'property_details_entry', 'salutation', 'gender', 'occupation', 'party_category', 'partytype', 'article_screen_mapping',
                'articaledepfields', 'articletrnfields', 'regconfig', 'fee_item_val', 'property_details_entry', 'TrnBehavioralPatterns', 'extinterfacefielddetails', 'article_partymapping', 'District', 'taluka', 'presentation_exmp', 'party_category_fields', 'BehavioralPattens']);
            //declare & assign variable

            $trible = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 82)));

            if (isset($trible['regconfig']['conf_bool_value'])) {
                $this->set('trible', $trible['regconfig']['conf_bool_value']);
            } else {
                $this->set('trible', 'N');
            }
            $uid_compulsary = $this->is_party_ekyc_auth_compusory();
            $pan_verify = $this->is_Pan_verification_compulsary();
            $party_upload_flag = $this->party_doc_upload_flag();
            $popupstatus = $actiontypeval = $hfid = $hfidnew = $updateid = $hfupdateflag = $hfactionval = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $lang = $this->Session->read("sess_langauge");
            $laug = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $this->set('laug', $laug);
            $fields = $this->set_common_fields();
            //-----------------checking Property Flag and property List---------------------------------            
            $property = $this->article_screen_mapping->query('select minorfun_id from ngdrstab_mst_article_screen_mapping where article_id=' . $this->Session->read("article_id") . ' and minorfun_id =2');
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $tokenval, $fields['user_id']);
            if (count($property) > 0 && count($property_list) < 1) {
                $this->Session->setFlash("Kindly add Property ");
                return $this->redirect(array('action' => 'property_details', $this->Session->read('csrftoken')));
            } else if (count($property) > 0 && count($property_list) > 1) {
                $this->set('same_prop_flag', 'Y');
            } else {
                $this->set('same_prop_flag', 'N');
            }
            if ($this->Session->read('sroparty') == 'Y') {
                $party_category = $this->party_category->find('list', array('fields' => array('party_category.category_id', 'party_category.category_name_' . $doc_lang), 'conditions' => array('power_attorney_flag' => 'Y'), 'order' => array('category_id' => 'ASC')));
            } else {
                $party_category = $this->party_category->find('list', array('fields' => array('party_category.category_id', 'party_category.category_name_' . $doc_lang), 'order' => array('category_id' => 'ASC')));
            }
            $property = $this->article_screen_mapping->query('select minorfun_id from ngdrstab_mst_article_screen_mapping where article_id=' . $this->Session->read("article_id") . ' and minorfun_id =2');
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $tokenval, $fields['user_id']);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $tokenval, $fields['user_id']);
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $party_record = $this->party_entry->get_partyrecord($tokenval, $fields['user_id'], $doc_lang, $lang, $this->Session->read('sroparty'));
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $this->set('partytype', $partytype_name);
            $this->set(compact('hfactionval', 'bank_master', 'lang', 'party_upload_flag', 'Selectedtoken', 'popupstatus', 'actiontypeval', 'hfid', 'hfidnew', 'hfupdateflag', 'hfactionval', 'districtdata', 'taluka', 'party_category', 'property', 'property_list', 'property_pattern', 'party_record', 'condition'));

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            $doc_lang = $this->Session->read('doc_lang');

            $this->set('updateid', $updateid);
            $fieldlist = array();
            $fielderrorarray = array();
            $article_id = $this->Session->read('article_id');
            $fieldlist = $this->party_category_fields->fieldlist($doc_lang, $tokenval, $article_id, $party_upload_flag);
//pr($fieldlist);exit;
            $this->set('fieldlistmultiform', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist, True));
            $this->set('formData', NULL);
            $this->set('uid_compulsary', $uid_compulsary);
            $this->set('pan_verify', $pan_verify);

            $permission_applicable = ClassRegistry::init('article')->field('permission_case_no_applicable', array('article_id' => $this->Session->read('article_id')));
            $this->set('permission_applicable', $permission_applicable);

            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 87)));
            $this->set("regconfig", $regconfig);
            //pr($regconfig);
//            $json2array['fieldlist'] = $fieldlist;
//            $file = new File(WWW_ROOT . 'files/vjsonfile1_' . $this->Auth->user('user_id') . '.json', true);
//            $file->write(json_encode($json2array));
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);
//            $file->write(json_encode($errarr));


            $form60_61 = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 121));

            $this->set('form60_61', $form60_61);

            if ($this->request->is('post')) {
//                echo $this->request->data['party_entry']['csrftoken'];
//                exit;
//                $this->check_csrf_token($this->request->data['party_entry']['csrftoken']);
//                $this->set_csrf_token();
                if (isset($this->request->data['party_entry']['village_id'])) {
                    $village_id = $this->request->data['party_entry']['village_id'];
                } else {
                    $village_id = NULL;
                }
                $article_id = $this->Session->read('article_id');
                $fieldlist = $this->party_category_fields->fieldlist($doc_lang, $tokenval, $article_id, $party_upload_flag, $this->request->data['party_entry']['party_catg_id'], $village_id);
                $fromdata = $this->request->data['party_entry'];
                if (!isset($this->request->data['party_entry']['poa_id'])) {
                    $this->request->data['party_entry'] = $this->istrim($this->request->data['party_entry']);
                }
                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
                    foreach ($bdata['pattern_id'] as $key => $fieldid) {

                        $this->request->data['party_entry']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                        if (isset($bdata['pattern_value_ll'][$key])) {
                            $this->request->data['party_entry']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                        }
                    }
                }

                $presenty_require = $this->party_category->field('presenty_require_photo', array('category_id' => $this->request->data['party_entry']['party_catg_id']));
                $this->request->data['party_entry']['presenty_require'] = $presenty_require;

                if (isset($this->request->data['party_entry']['pan_form_list']) && $this->request->data['party_entry']['pan_form_list'] == 9) {
                    unset($fieldlist['party_entry']['pan_no']);
                }

                $errarr = $this->validatedata($this->request->data['party_entry'], $fieldlist['party_entry']);

                if ($this->ValidationError($errarr)) {
//                    $val_amt = $this->get_valuation_amt($_POST['val_id']);
//                    if (is_numeric($val_amt) && $val_amt > 1000000) {
//                        if (!$this->request->data['party_entry']['pan_no']) {
//                            // $this->Session->setFlash(__('Please Enter PAN'));
//                            //$this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $tokenval));
//                        }
//                    }
                    $actiontype = $_POST['actiontype'];
                    $hfactionval = $_POST['hfaction'];
                    $property_id = $_POST['propertyid'];
                    $hfid = $_POST['hfid'];
                    $hfidnew = $_POST['hfidnew'];
                    $updateid = $_POST['updateid'];
                    $this->set('hfid', $hfid);
                    $this->set('hfidnew', $hfidnew);
                    if (isset($this->request->data['party_entry']['uid']) && is_numeric($this->request->data['party_entry']['uid'])) {
                        $this->request->data['party_entry']['uid'] = $this->enc($this->request->data['party_entry']['uid']);
                    }
                    if (isset($this->request->data['party_entry']['party_full_name_en'])) {
                        if ($this->request->data['party_entry']['party_full_name_en'] == '') {
                            $this->request->data['party_entry']['party_full_name_en'] = isset($this->request->data['party_entry']['party_fname_en']) ? $this->request->data['party_entry']['party_fname_en'] . ' ' . $this->request->data['party_entry']['party_mname_en'] . ' ' . $this->request->data['party_entry']['party_lname_en'] : '';
                            $this->request->data['party_entry']['party_full_name_en'] = ucwords($this->request->data['party_entry']['party_full_name_en']);
                        } else {
                            $full_name = $this->request->data['party_entry']['party_full_name_en'];

                            $this->request->data['party_entry']['party_full_name_en'] = ucwords($full_name);
                        }
                    } else {
                        $this->request->data['party_entry']['party_full_name_en'] = isset($this->request->data['party_entry']['party_fname_en']) ? $this->request->data['party_entry']['party_fname_en'] . ' ' . $this->request->data['party_entry']['party_mname_en'] . ' ' . $this->request->data['party_entry']['party_lname_en'] : '';
                        $this->request->data['party_entry']['party_full_name_en'] = ucwords($this->request->data['party_entry']['party_full_name_en']);
                    }
                    if (isset($this->request->data['party_entry']['party_full_name_ll'])) {
                        if ($this->request->data['party_entry']['party_full_name_ll'] == '') {
                            $this->request->data['party_entry']['party_full_name_ll'] = isset($this->request->data['party_entry']['party_fname_ll']) ? $this->request->data['party_entry']['party_fname_ll'] . ' ' . $this->request->data['party_entry']['party_mname_ll'] . ' ' . $this->request->data['party_entry']['party_lname_ll'] : '';
                        } else {
                            $this->request->data['party_entry']['party_full_name_ll'] = $this->request->data['party_entry']['party_full_name_ll'];
                        }
                    } else {
                        $this->request->data['party_entry']['party_full_name_ll'] = isset($this->request->data['party_entry']['party_fname_ll']) ? $this->request->data['party_entry']['party_fname_ll'] . ' ' . $this->request->data['party_entry']['party_mname_ll'] . ' ' . $this->request->data['party_entry']['party_lname_ll'] : '';
                    }

                    if (isset($this->request->data['party_entry']['father_full_name_en'])) {
                        $father_name = $this->request->data['party_entry']['father_full_name_en'];

                        $this->request->data['party_entry']['father_full_name_en'] = ucwords($father_name);
                    }

                    if (1) {

                        if (isset($this->request->data['party_entry']['partycheck'])) {
                            $partycheck = $this->request->data['party_entry']['partycheck'];
                        } else {
                            $partycheck = 0;
                        }
                        $this->set('actiontypeval', $actiontype);
                        $this->set('hfactionval', $hfactionval);
                        if ($hfactionval == 'S') {
                            $this->request->data['party_entry']['user_type'] = $this->Session->read("session_usertype");

                            if ($this->Session->read("session_usertype") == 'O') {
                                unset($this->request->data['party_entry']['user_id']);

                                if ($this->Auth->user('office_id')) {

                                    $this->request->data['party_entry']['org_user_id'] = $this->Auth->User('user_id');
                                    if (is_numeric($hfid)) {

                                        $this->request->data['party_entry']['org_updated'] = date('Y-m-d H:i:s');
                                    } else {
                                        $this->request->data['party_entry']['org_created'] = date('Y-m-d H:i:s');
                                    }
                                }
                            }

//                            spr($this->request->data);exit;

                            if (!isset($this->request->data['party_entry']['partycheck']) || $this->request->data['party_entry']['partycheck'] == 0) {

//                            pr($repeat_party_id);exit;
                                if ($this->save_party($this->request->data['party_entry'], $tokenval, $fields['stateid'], $fields['user_id'], $property_id, $updateid, $repeat_party_id = NULL, $partycheck)) {

                                    if ($this->request->data['hfupdateflag'] == 'Y') {
                                        $party_id = $updateid;
                                        $actionvalue = "Updated";
                                    } else {
                                        //   $id = $this->party_entry->getLastInsertID();
                                        $party_id = $this->party_entry->getLastInsertID();
                                        $actionvalue = "Saved";
                                    }

                                    //save geneder in trn table
                                    if (isset($this->request->data['party_entry']['gender_id'])) {

                                        $item_record = array('fee_param_code' => 'SAA',
                                            'token_no' => $tokenval,
                                            'mapping_ref_typeid' => $this->request->data['party_entry']['party_type_id'],
                                            'mapping_ref_id' => $party_id,
                                            'mapping_ref_val' => $this->request->data['party_entry']['gender_id'],
                                            'state_id' => $fields['stateid'],
                                            'user_id' => $fields['user_id'],
                                            'req_ip' => $_SERVER['REMOTE_ADDR']
                                        );
                                        $this->fee_item_val->deleteAll(['token_no' => $tokenval, 'mapping_ref_id' => $party_id, 'fee_param_code' => 'SAA']);
                                        $this->fee_item_val->save($item_record);
                                    }
                                    if (isset($this->request->data['property_details']['pattern_id'])) {
                                        $this->TrnBehavioralPatterns->deletepattern($tokenval, $fields['user_id'], $party_id, 2);
                                        $this->TrnBehavioralPatterns->savepattern($tokenval, $fields['user_id'], $party_id, $this->request->data['property_details'], 2, $this->Session->read("session_usertype"));
                                    }

                                    $this->stamp_duty->updateAll(array('stamp_duty.recalculate_flag' => "'Y'"), //fields to update
                                            array('stamp_duty.token_no' => $this->Session->read("Selectedtoken"))  //condition
                                    );

                                    $this->Session->setFlash(__("Record $actionvalue Successfully"));

                                    // $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken'),));
                                } else {
                                    $this->Session->setFlash(__('Record Not Saved,Do not enter duplicate record '));
                                }
                            } else {

                                $repeat_party_id = NULL;

                                if ($this->request->data['hfupdateflag'] == 'Y') {
                                    $repeat_party_id = $hfid;
                                } else {

                                    $repeat_party_id = $this->party_entry->query("select nextval('ngdrstab_trn_party_entry_new_id_seq')");

                                    $repeat_party_id = $repeat_party_id[0][0]['nextval'];
                                    if ($repeat_party_id == null) {
                                        $repeat_party_id = 1;
                                    } else {
                                        $repeat_party_id = $repeat_party_id + 1;
                                    }
                                }
                                foreach ($property_list as $chkprop) {

                                    if ($this->save_party($this->request->data['party_entry'], $tokenval, $fields['stateid'], $fields['user_id'], $chkprop[0]['property_id'], $hfid, $repeat_party_id, $partycheck)) {
                                        if ($this->request->data['hfupdateflag'] == 'Y') {
                                            $party_id = $hfid;
                                            $actionvalue = "Updated";
                                        } else {
                                            $id = $this->party_entry->getLastInsertID();
                                            $party_id = $this->party_entry->field('party_id', array('id' => $id));
                                            $actionvalue = "Saved";
                                        }

                                        //save geneder in trn table

                                        $item_record = array('fee_param_code' => 'SAA',
                                            'token_no' => $tokenval,
                                            'mapping_ref_typeid' => $this->request->data['party_entry']['party_type_id'],
                                            'mapping_ref_id' => $party_id,
                                            'mapping_ref_val' => $this->request->data['party_entry']['gender_id'],
                                            'state_id' => $fields['stateid'],
                                            'user_id' => $fields['user_id'],
                                            'req_ip' => $_SERVER['REMOTE_ADDR']
                                        );
                                        $this->fee_item_val->deleteAll(['token_no' => $tokenval, 'mapping_ref_id' => $party_id, 'fee_param_code' => 'SAA']);
                                        $this->fee_item_val->save($item_record);


                                        $this->stamp_duty->updateAll(array('stamp_duty.recalculate_flag' => "'Y'"), //fields to update
                                                array('stamp_duty.token_no' => $this->Session->read("Selectedtoken"))  //condition
                                        );



                                        // $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken'),));
                                    } //if closing 
                                }// foreach bracket
                                if (isset($this->request->data['property_details']['pattern_id'])) {
                                    $id = $this->party_entry->query('select id from ngdrstab_trn_party_entry_new  where repeat_party_id=?', array($repeat_party_id));

                                    for ($i = 0; $i < count($id); $i++) {
                                        $this->TrnBehavioralPatterns->deletepattern($tokenval, $fields['user_id'], $id[$i][0]['id'], 2);
                                        $this->TrnBehavioralPatterns->savepattern($tokenval, $fields['user_id'], $id[$i][0]['id'], $this->request->data['property_details'], 2, $this->Session->read("session_usertype"));
                                    }
                                }
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                            }
                        }
                    }
                }
                if ($this->Session->read('sroparty') == 'Y') {
                    $this->redirect(array('controller' => 'Registration', 'action' => 'party'));
                } else {

                    $this->redirect(array('action' => 'party_entry', $this->Session->read('csrftoken')));
                }
            } else {

                $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
            pr($ex);
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function party_doc_upload_flag() {
        try {
            //this->autoRender = false;
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 45)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function save_party($data, $tokenval, $stateid, $user_id, $property_id, $hfid, $repeat_party_id, $partycheck) {
        array_map([$this, 'loadModel'], ['party_entry']);
        $pan = isset($data['pan_no']) ? ($data['pan_no']) : ('');
        $mobile = isset($data['mobile_no']) ? ($data['mobile_no']) : ('');
        $uid = isset($data['uid']) ? ($data['uid']) : ('');
        $email = isset($data['email_id']) ? ($data['email_id']) : ('');
        $poa1 = isset($data['poa_id']) ? ($data['poa_id']) : ('');


        $data['token_no'] = $tokenval;
        $data['repeat_party_id'] = $repeat_party_id;
        $data['state_id'] = $stateid;
        $data['user_id'] = $user_id;
        //$data['property_id'] = $property_id;
        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
        if (isset($data['embossing_doc_date']) && $data['embossing_doc_date'] != NULL) {
            $data['embossing_doc_date'] = date('Y-m-d', strtotime($data['embossing_doc_date']));
        }
        if (isset($data['reg_date']) && $data['reg_date'] != NULL) {
            $data['reg_date'] = date('Y-m-d', strtotime($data['reg_date']));
        }

        if (isset($data['dob']) && $data['dob'] != NULL) {

            $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
        }

        if (isset($data['poa_id'])) {
            $i = 0;
            foreach ($data['poa_id'] as $poa) {
                $i++;
                if ($i == 1) {
                    $data['display_flag'] = 'Y';
                } else {
                    $data['presenty_require'] = 'N';
                    $data['is_executer'] = 'N';
                    $data['display_flag'] = 'N';
                }
                $data['property_id'] = $property_id;
                $data['poa_id'] = $poa;
                $data['power_attoney_party_id'] = $data['poa_id'];
                //$this->party_entry->id = $hfid;
                if ($hfid) {
                    $action = 'U';
                    $this->party_entry->id = $hfid;
                    $this->party_entry->save($data);
                } else {
                    $action = 'S';
                    $this->party_entry->create();
                    $this->party_entry->save($data);
                }
            }
            return true;
        } else {
            if ($hfid) {
                if ($partycheck == 0) {
                    $data['property_id'] = $property_id;
                }
                $this->party_entry->id = $hfid;
                $this->party_entry->updateAll(array('party_fname_en' => null, 'party_fname_ll' => null, 'party_mname_en' => null, 'party_mname_ll' => null
                    , 'party_lname_en' => null, 'party_lname_ll' => null, 'alias_name_en' => null, 'alias_name_ll' => null, 'father_fname_en' => null, 'father_fname_ll' => null
                    , 'father_mname_en' => null, 'father_mname_ll' => null, 'father_lname_en' => null, 'father_lname_ll' => null, 'mother_fname_en' => null
                    , 'mother_fname_ll' => null, 'mother_mname_en' => null, 'mother_mname_ll' => null, 'mother_lname_en' => null, 'mother_lname_ll' => null
                    , 'dob' => null, 'age' => null, 'occupation_id' => null, 'address_en' => null, 'address_ll' => null, 'pan_no' => null
                    , 'uid' => null, 'mobile_no' => null, 'email_id' => null, 'district_id' => null, 'taluka_id' => null, 'village_id' => null, 'party_full_name_en' => null
                    , 'party_full_name_ll' => null, 'party_full_name_en' => null, 'father_full_name_en' => null, 'father_full_name_ll' => null, 'mother_full_name_en' => null, 'mother_full_name_ll' => null, 'exemption_id' => null, 'idetification_mark1_en' => null
                    , 'idetification_mark1_ll' => null, 'idetification_mark2_en' => null, 'idetification_mark2_ll' => null, 'bank_id' => null, 'org_name' => null
                    , 'company_name' => null, 'tan' => null, 'gender_id' => null, 'org_name_en' => null, 'org_name_ll' => null, 'cast_id' => null, 'grand_father_fullname_ll' => null
                    , 'id_type_2' => null, 'pan_form_list' => null, 'pin_code' => null, 'branch_name_en' => null
                    , 'branch_name_ll' => null, 'repete_add' => null, 'embossing_off_fullname' => null, 'embossing_doc_no' => null
                    , 'embossing_doc_date' => null, 'representive_full_name_en' => null, 'representive_full_name_ll' => null
                        ), array('id' => $hfid));

                if ($partycheck == 0) {

                    $action = 'U';
                    $this->party_entry->id = $hfid;
                    $this->party_entry->save($data);
                } else {
                    $action = 'U';

                    $id = $this->party_entry->query('select id from ngdrstab_trn_party_entry_new  where repeat_party_id=?', array($hfid));
                    for ($i = 0; $i < count($id); $i++) {
                        $this->party_entry->id = $id[$i][0]['id'];
                        $this->party_entry->save($data);
                    }
                }
                return true;
            } else {
                $data['property_id'] = $property_id;
                $action = 'S';
                $this->party_entry->saveAll($data);
                return true;
            }
        }
    }

    function get_record_old_party() {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map([$this, 'loadModel'], ['genernalinfoentry', 'old_document_trn', 'party_entry']);
            $ref_doc_no = $this->genernalinfoentry->find('first', array('fields' => array('ref_doc_no'), 'conditions' => array(
                    'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken"))));
            $type = '';

            if ($ref_doc_no) {
                $type = 'T';
                $ref_record = $this->party_entry->query('select p.* from ngdrstab_trn_party_entry_new p ,ngdrstab_trn_application_submitted a where a.token_no=p.token_no and a.doc_reg_no=?', array($ref_doc_no['genernalinfoentry']['ref_doc_no']));
            }

            $this->set("doc_lang", $this->Session->read('doc_lang'));
            $this->set("type", $type);
            $this->set("ref_record", $ref_record);
//                if (isset($ref_record) && !empty($ref_record)) {
//                    $this->set("ref_record", $ref_record);
//                }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function party_upload() {
        if ($this->request->is('post')) {
            //  $this->check_csrf_token($this->request->data['party_upload']['csrftoken']);
            // $this->set_csrf_token();

            array_map(array($this, 'loadModel'), array('file_config', 'party_entry', 'genernalinfoentry'));
            if ($this->request->data['party_upload']['upload_file']['error'] == 0) {

                if ($this->validfile($this->request->data['party_upload']['upload_file'])) {
                    $file_ext = pathinfo($this->request->data['party_upload']['upload_file']['name'], PATHINFO_EXTENSION);
                    $filename = str_replace(' ', '_', $this->request->data['party_upload']['upload_file']['name']);

                    $party_id = $_POST['party_id'];

                    $general = $this->genernalinfoentry->find('first', array('fields' => array('dist.district_name_en', 'genernalinfoentry.taluka_id', 'genernalinfoentry.office_id'), 'conditions' => array(
                            'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                            array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=genernalinfoentry.district_id')),
                    )));


                    $path = $this->file_config->find('first', array('fields' => array('filepath')));

                    $createFolder1 = $this->create_folder($path['file_config']['filepath'], 'Documents/');
                    if (!empty($general)) {
                        $dist = $this->create_folder($createFolder1, $general['dist']['district_name_en'] . '/');
                        $taluka = $this->create_folder($dist, $general['genernalinfoentry']['taluka_id'] . '/');
                        $office = $this->create_folder($taluka, $general['genernalinfoentry']['office_id'] . '/');
                        $final_folder1 = $this->create_folder($office, $this->Session->read("Selectedtoken") . '/');

                        $final_folder = $this->create_folder($final_folder1, 'Uploads/');
                        $final_folder2 = $this->create_folder($final_folder, $party_id . '/');


                        $new_name = $this->Session->read("Selectedtoken") . '_' . $party_id;

                        if (file_exists($final_folder2 . $new_name)) {
                            unlink($final_folder2 . $new_name);
                        }
                        if (!is_writable($final_folder2 . $new_name)) {
                            $mode = 0777;
                            //chmod($final_folder2 . $new_name, 7777);
                            chmod($final_folder2, $mode);
                        }
                        $file_name = $final_folder2 . $new_name . '.' . $file_ext;


                        $success = move_uploaded_file($this->request->data['party_upload']['upload_file']['tmp_name'], $file_name);

                        if ($success == 1) {


                            $data = array(
                                'uploaded_file' => $new_name . '.' . $file_ext,
                            );
                            $id = $this->party_entry->field('id', array('party_id' => $_POST['party_id']));
                            $this->party_entry->id = $id;

                            if ($this->party_entry->save($data)) {

                                $this->Session->setFlash(__("File Updated  Successfully"));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
                            }
//                            
                        }
                    } else {
                        $this->Session->setFlash(__("File format not suported"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
                    }
                } else {
                    $this->Session->setFlash(__("Error in File"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
                }
            }
        }
    }

    function downloadpartyfile($file = NULL, $party_id) {
        try {
            if (isset($file) and $file != '') {
                $this->autoRender = FALSE;
                array_map(array($this, 'loadModel'), array('file_config', 'genernalinfoentry'));
                $path = $this->file_config->find('first', array('fields' => array('filepath')));

                $general = $this->genernalinfoentry->find('first', array('fields' => array('dist.district_name_en', 'genernalinfoentry.taluka_id', 'genernalinfoentry.office_id'), 'conditions' => array(
                        'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=genernalinfoentry.district_id')),
                )));

                if (!empty($general)) {

                    $path = $path['file_config']['filepath'] . 'Documents' . '/' . $general['dist']['district_name_en'] . '/' . $general['genernalinfoentry']['taluka_id'] . '/' . $general['genernalinfoentry']['office_id'] . '/' . $this->Session->read("Selectedtoken") . '/' . 'Uploads' . '/' . $party_id . '/' . $file;




                    if (file_exists($path)) {

                        $this->response->file($path, array('download' => true, 'name' => $file));
                        return $this->response->download($file);
                    } else {
                        return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                    }
                } else {
                    return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function delete_partyfile($csrf, $id = null, $file = null) {
        $this->autoRender = false;

        // $this->check_csrf_token_withoutset($csrf);

        $this->loadModel('party_entry');
        $this->loadModel('file_config');
        $this->loadModel('genernalinfoentry');

        try {

            if (isset($id) && is_numeric($id)) {


                //
                $general = $this->genernalinfoentry->find('first', array('fields' => array('dist.district_name_en', 'genernalinfoentry.taluka_id', 'genernalinfoentry.office_id'), 'conditions' => array(
                        'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken")), 'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=genernalinfoentry.district_id')),
                )));



                $this->party_entry->query('update ngdrstab_trn_party_entry_new set uploaded_file=NULL where party_id=? and token_no=?', array($id, $this->Session->read("Selectedtoken")));
                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                $path = $path['file_config']['filepath'] . 'Documents' . '/' . $general['dist']['district_name_en'] . '/' . $general['genernalinfoentry']['taluka_id'] . '/' . $general['genernalinfoentry']['office_id'] . '/' . $this->Session->read("Selectedtoken") . '/' . 'Uploads' . '/' . $id . '/' . $file;

                if (file_exists($path)) {
                    unlink($path);
                } else {
                    $this->Session->setFlash(
                            __('File not found')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
                }
                $this->Session->setFlash(
                        __('The File  has been deleted')
                );

                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $this->Session->read('csrftoken')));
            }
            // }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    function proceduretype_feild_require_flag() {
        try {
            //this->autoRender = false;
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 66)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function advoate_feild_require_flag() {
        try {
            //this->autoRender = false;
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 39)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function cast_category_applicable_flag() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 40)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function witness_only_for_will() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 40)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function downloadfile($file = NULL) {
        try {
            if (isset($file) and $file != '') {
                $this->autoRender = FALSE;
                array_map(array($this, 'loadModel'), array('file_config', 'genernalinfoentry', 'office'));
                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                $general = $this->genernalinfoentry->find('first', array('fields' => array('genernalinfoentry.office_id'), 'conditions' => array(
                        'genernalinfoentry.token_no' => $this->Session->read("Selectedtoken"))));


                $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                        'office.office_id' => $general['genernalinfoentry']['office_id']), 'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                )));

                if (!empty($general)) {

                    $path = $path['file_config']['filepath'] . 'Documents' . '/' . $office['dist']['district_name_en'] . '/' . $office['office']['taluka_id'] . '/' . $general['genernalinfoentry']['office_id'] . '/' . $this->Session->read("Selectedtoken") . '/' . 'Uploads' . '/' . $file;

                    if (file_exists($path)) {

                        $this->response->file($path, array('download' => true, 'name' => $file));
                        return $this->response->download($file);
                    } else {
                        return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                    }
                } else {
                    return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_identification_feilds() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('identification', 'party_entry', 'nationality', 'partytype', 'MstIdentification', 'cast_category', 'gov_partytype', 'District', 'taluka', 'identificatontype', 'identification_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read('doc_lang');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
            // set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $nationality = $this->nationality->find('list', array('fields' => array('nationality.nationality_id', 'nationality.nationality_name_' . $doc_lang), 'order' => 'nationality_id ASC'));
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang), 'order' => 'salutation_id ASC'));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $doc_lang), 'conditions' => array('identifier_flag' => 'Y'), 'order' => 'occupation_name_' . $doc_lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('identification_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $doc_lang), 'order' => array('category_name_' . $doc_lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $doc_lang), 'order' => array('government_type_' . $doc_lang => 'ASC')));
            $allparty = $this->party_entry->find('list', array('fields' => array('party_entry.party_id', 'party_entry.party_full_name_' . $doc_lang), 'conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));

            if (isset($data['type'])) {
                if ($data['type'] == 3) {
                    $this->identification->query("update ngdrstab_mst_identifire_fields set display_flag='Y' where field_id_name_en='party_id'");
                } else {
                    $this->identification->query("update ngdrstab_mst_identifire_fields set display_flag='N' where field_id_name_en='party_id'");
                }
            } else {
                $this->identification->query("update ngdrstab_mst_identifire_fields set display_flag='N' where field_id_name_en='party_id'");
            }

//validations
            //category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();

            $identifirefields = array();

//                $partyfields = $this->party_category_fields->find('all', array('fields'=>array(''),'conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y'), 'order' => 'order ASC'));
            $identifirefields = $this->identification_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));


            if (isset($data['id']) && is_numeric($data['id'])) {
                $rec = $this->identification->find('all', array('conditions' => array('id' => $data['id'], 'token_no' => $token)));
                if (isset($rec[0]['identification']['identificationtype_id']) && $rec[0]['identification']['identificationtype_id'] == 9999) {
                    $rec[0]['identification']['identificationtype_desc_en'] = $this->dec($rec[0]['identification']['identificationtype_desc_en']);
                }
                if ($rec[0]['identification']['uid_no'] != NULL || $rec[0]['identification']['uid_no'] != '') {
                    $rec[0]['identification']['uid_no'] = $this->dec($rec[0]['identification']['uid_no']);
                }
                $this->set("rec", $rec);
                //pr($payment);
            }

            if (isset($data['master_id']) && is_numeric($data['master_id'])) {
                $rec = $this->MstIdentification->find('all', array('conditions' => array('identification_id' => $data['master_id'])));
                $rec[0]['identification'] = $rec[0]['MstIdentification'];
                $this->set("rec", $rec);
            }
            // to set data for edit;
            foreach ($identifirefields as $field) {
                if ($field['identification_fields']['field_id_name_en'] == 'district_id') {
                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                    if (isset($rec) and is_numeric($rec[0]['identification']['district_id'])) {
                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $rec[0]['identification']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                    } else {
                        $taluka = array();
                    }
                }
                if (isset($rec) and is_numeric($rec[0]['identification']['taluka_id'])) {
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $rec[0]['identification']['taluka_id'])));
                } else {
                    $villagelist = array();
                }
            }

            $this->set(compact('districtdata', 'partytype_name', 'category', 'allparty', 'cast_cat_flag', 'gov_partytype', 'taluka', 'villagelist', 'identifirefields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka'));
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_witness_feilds() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('witness', 'cast_category', 'gov_partytype', 'nationality', 'District', 'taluka', 'identificatontype', 'witness_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read('doc_lang');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
            // set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $marital_status = array('M' => 'Married', 'U' => 'Unmarried');
            $nationality = $this->nationality->find('list', array('fields' => array('nationality.nationality_id', 'nationality.nationality_name_' . $doc_lang), 'order' => 'nationality_id ASC'));
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang), 'order' => 'salutation_id ASC'));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $doc_lang), 'order' => 'occupation.occupation_name_' . $doc_lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('witness_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $doc_lang), 'order' => array('category_name_' . $doc_lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $doc_lang), 'order' => array('government_type_' . $doc_lang => 'ASC')));
            //validations
            //category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();

            $witnessfields = array();

//                $partyfields = $this->party_category_fields->find('all', array('fields'=>array(''),'conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y'), 'order' => 'order ASC'));
            $witnessfields = $this->witness_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));


            if (isset($data['id']) && is_numeric($data['id'])) {
                $rec = $this->witness->find('all', array('conditions' => array('id' => $data['id'], 'token_no' => $token)));
                if (isset($rec[0]['witness']['identificationtype_id']) && $rec[0]['witness']['identificationtype_id'] == 9999) {
                    $rec[0]['witness']['identificationtype_desc_en'] = $this->dec($rec[0]['witness']['identificationtype_desc_en']);
                }

                if ($rec[0]['witness']['uid_no'] != NULL || $rec[0]['witness']['uid_no'] != '') {
                    $rec[0]['witness']['uid_no'] = $this->dec($rec[0]['witness']['uid_no']);
                }


                $this->set("rec", $rec);
                //pr($payment);
            }
            // to set data for edit;
            foreach ($witnessfields as $field) {
                if ($field['witness_fields']['field_id_name_en'] == 'district_id') {
                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                    if (isset($rec) and is_numeric($rec[0]['witness']['district_id'])) {
                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $rec[0]['witness']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                    } else {
                        $taluka = array();
                    }
                }
                if (isset($rec) and is_numeric($rec[0]['witness']['taluka_id'])) {
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $rec[0]['witness']['taluka_id'])));
                } else {
                    $villagelist = array();
                }
            }

            $this->set(compact('districtdata', 'category', 'cast_cat_flag', 'nationality', 'marital_status', 'gov_partytype', 'taluka', 'villagelist', 'witnessfields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function verify_uid() {
        try {
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                echo 1;
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function verifypan() {
        try {
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                echo 1;
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_village_bypin_old() {
        try {
            if (isset($_POST['pin']) && is_numeric($_POST['pin'])) {
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('district_id', 'taluka_id', 'village_id', 'state_id'), 'conditions' => array('pin_code' => $_POST['pin'])));
                if (!empty($villagedetails)) {
                    echo implode(', ', $villagedetails[0]['VillageMapping']);
                } else {
                    echo 0;
                }

                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_district_bypin() {
        try {
            if (isset($_POST['pin']) && is_numeric($_POST['pin'])) {
                $pindetails = ClassRegistry::init('pincode')->find('all', array('fields' => array('state', 'district', 'post_office_name'), 'conditions' => array('pincode' => $_POST['pin'])));
                if (!empty($pindetails)) {
                    echo implode(', ', $pindetails[0]['pincode']);
                } else {
                    echo 0;
                }
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_address() {
        try {
            if (isset($_POST['repete_add']) && is_numeric($_POST['repete_add'])) {
                $addressdetails = ClassRegistry::init('party_entry')->find('first', array('fields' => array('district_id', 'taluka_id', 'village_id', 'party_state_id'), 'conditions' => array('party_id' => $_POST['repete_add'])));

                if (!empty($addressdetails)) {

                    echo implode(',', $addressdetails['party_entry']);
                } else {
                    echo 0;
                }
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_office_list() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('office');
            $doc_lang = $this->Session->read('doc_lang');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {

                $tal = $this->request->data['tal'];

                if (isset($this->request->data['village']) && $this->request->data['village'] != '') {
                    $village = $this->request->data['village'];


                    $options1['conditions'] = array('ov.village_id' => trim($village));
                    $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                    );
                    $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                    $office = $this->office->find('list', $options1);
                    if (empty($office)) {
                        $options1['conditions'] = array('ov.taluka_id' => trim($tal));
                        $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                        );
                        $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                        $office = $this->office->find('list', $options1);
                    }
                } elseif (isset($tal) && $tal != '') {

                    $options1['conditions'] = array('ov.taluka_id' => trim($tal));
                    $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                    );
                    $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                    $office = $this->office->find('list', $options1);
                }

//                if (empty($office)) {
//
//                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang)));
//                }

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);


                $result_array = array('office' => $office);
                $json2array['office'] = $office;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {

                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_office_list_dist() {
        try {
//            echo'hi';exit;
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('office');
            $doc_lang = $this->Session->read('doc_lang');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
//            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {
//
//                $tal = $this->request->data['tal'];
            if (isset($this->request->data['dist']) && $this->request->data['dist'] != '') {
                $dist = $this->request->data['dist'];
                $options1['conditions'] = array('ov.district_id' => trim($dist));
                $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                );
                $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                $office = $this->office->find('list', $options1);


                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $result_array = array('office' => $office);
                $json2array['office'] = $office;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function is_handling_charges_required() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 51)));


            return $regconfig['regconfig']['conf_bool_value'];
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function encrpt_witnessuid() {
        try {
            array_map(array($this, 'loadModel'), array('witness'));

            $record = $this->witness->find('all');
            foreach ($record as $rec) {
                if (is_numeric($rec['witness']['uid_no'])) {
                    $uid = "'" . $this->enc($rec['witness']['uid_no']) . "'";
                } else {
                    $uid = "'" . $rec['witness']['uid_no'] . "'";
                }
                if (isset($rec['witness']['identificationtype_id']) && $rec['witness']['identificationtype_id'] == 9999) {
                    $desc = "'" . $this->enc($rec['witness']['identificationtype_desc_en']) . "'";
                } else {
                    $desc = "'" . $rec['witness']['identificationtype_desc_en'] . "'";
                }

                $this->witness->updateAll(array('uid_no' => $uid, 'identificationtype_desc_en' => $desc), array('id' => $rec['witness']['id']));
            }


            pr($record);
            exit;
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    function getdependent_article_level1field() {
        try {
            if (isset($_POST['article_id']) and is_numeric($_POST['article_id']) and isset($_POST['code'])) {
                $lang = $this->Session->read('sess_langauge');
                $code = $_POST['code'];
                $type = $_POST['type_val'];


                $field = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $lang, 'item.display_order', 'item.is_date'), 'conditions' => array('conf_article_feerule_items.article_id' => $_POST['article_id'], 'item.gen_dis_flag' => 'Y', 'conf_article_feerule_items.level2_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $lang, 'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item', 'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id'))), 'order' => ('item.display_order ASC')));

                $this->set('field', $field);
                $this->loadModel('articletrnfields');
                $this->loadModel('gender');
                $this->loadModel('article_fee_item_list');
                $genderlist = $this->gender->find('list', array('fields' => array('gender_id', 'gender_desc_' . $lang)));
                if ($this->Session->read('Selectedtoken') != '') {
//                 
                    $result = $this->articletrnfields->get_articledependent_feild_level1($lang, $_POST['article_id'], $this->Session->read('Selectedtoken'), $code, $type);
                } else {
                    $result = $this->articletrnfields->get_articledependent_feild_level1($lang, $_POST['article_id'], NULL, $code, $type);
                }

                $items_list = array();

                if (isset($result)) {
                    foreach ($result as $FeeItem) {
                        if ($FeeItem[0]['list_flag'] == 'Y') {
                            $items_list[$FeeItem[0]['fee_param_code']] = $this->article_fee_item_list->find('list', array('fields' => array('fee_item_list_id', 'fee_item_list_desc_' . $lang), 'conditions' => array('fee_item_id' => $FeeItem[0]['fee_item_id'])));
                        }
                    }
                    $this->set(compact('result', 'items_list'));
                }
                $this->set(compact('genderlist'));
                //validations kk
                $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
                $error_arr = $json = $file->read(true, 'r');
                $this->set("errarr", json_decode($error_arr, TRUE));
            } else {

                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_share_percentage() {
        $this->autoRender = FALSE;
        try {
            if (isset($_POST['type']) && is_numeric($_POST['type'])) {

                $this->loadModel("party_entry");
                $sum = $this->party_entry->query("SELECT COALESCE(SUM(share_percentage),0) as total FROM ngdrstab_trn_party_entry_new where token_no=? and party_type_id=? group by token_no", array($this->Session->read('Selectedtoken'), $_POST['type']));

                if (!empty($sum)) {
                    echo $sum[0][0]['total'];
                    exit;
                } else {
                    echo 'Y';
                    exit;
                }
            } else {
                echo 11;
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function check_view_rate_config() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 75)));
            if (!empty($regconfig)) {


                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_area($val_id = NULL) {
        try {

            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {
                $this->loadModel('valuation_details');
                $record = $this->valuation_details->find('first', array('fields' => array('item_id', 'item_value'), 'conditions' => array('val_id' => $valuation_id), 'joins' => array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('valuation_details.item_id=item.usage_param_id', 'item.usage_param_code' => 'AAA')))));


                if (!empty($record)) {
                    return $record['valuation_details']['item_value'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_exmption_rule() {
        try {

            $this->autoRender = FALSE;
            if (isset($_POST['fee_rule_id']) && is_numeric($_POST['fee_rule_id'])) {
                $this->loadModel('article_fee_rule');
                $this->loadModel('exemption_article_rules');
                $article_id = $this->Session->read('article_id');
                $doc_lang = $this->Session->read('doc_lang');
                $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id))), 'main_rule_id' => $_POST['fee_rule_id']))); //get only Exemption Rules(9998)

                if (empty($exemption_rule)) {
                    $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id))), 'main_rule_id' => NULL))); //get only Exemption Rules(9998) 
                }
                echo json_encode($exemption_rule);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_party_cast($token) {
        $this->autoRender = FALSE;
        $this->loadModel('party_entry');
        $party = $this->party_entry->find("all", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
        $flag = 0;
        $exmption = 0;
        foreach ($party as $single) {

            if ($single['party_entry']['maincast_id'] == 1) {
                $flag++;
            }
            if ($single['party_entry']['exmption_trible'] == 'Y') {
                $exmption = 1;
            }
        }
        if ($exmption == 1) {
            return 1;
        }
        if ($flag == 0) {
            return 1;
        } else if ($flag == count($party)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_sub_cast() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['maincast_id']) and is_numeric($this->request->data['maincast_id'])) {
                $maincast_id = $this->request->data['maincast_id'];

                $castlist = ClassRegistry::init('cast_category')->find('list', array('fields' => array('cast_category.category_id', 'cast_category.category_name_' . $lang), 'conditions' => array('maincast_id' => $maincast_id)));
                $result_array = array('cast' => $castlist);


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function rpt_feerule_desc() {
        try {

            array_map([$this, 'loadModel'], ['ApplicationSubmitted', 'party_entry']);
            $stateid = $this->Auth->User('state_id');
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $this->set('articledata', ClassRegistry::init('article')->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'order' => array('article_desc_' . $lang => 'ASC'))));

            if ($this->request->is('post')) {


                $result = $this->ApplicationSubmitted->query("select art.article_desc_en,fee.fee_rule_desc_en,feerule.fee_rule_desc_en ,feerule.fee_rule_id,feeitem.fee_item_id,feesub.fee_output_item_id,artfee.fee_item_desc_en,
                                        feesub.fee_rule_cond1,feesub.fee_rule_formula1,feesub.fee_rule_cond2,feesub.fee_rule_formula2
                                         from ngdrstab_mst_article_fee_rule fee
                                        join ngdrstab_mst_article art on art.article_id=fee.article_id
                                        join ngdrstab_mst_article_fee_rule feerule on feerule.article_id=fee.article_id
                                        join ngdrstab_conf_article_feerule_items feeitem on feeitem.fee_rule_id=fee.fee_rule_id  
                                        join ngdrstab_mst_article_fee_subrule feesub on feesub.fee_rule_id=fee.fee_rule_id
                                        join ngdrstab_mst_article_fee_items artfee on artfee.fee_item_id=feesub.fee_output_item_id
                                        group by art.article_desc_en,fee.fee_rule_desc_en,feerule.fee_rule_desc_en,feerule.fee_rule_id,feeitem.fee_item_id,feesub.fee_output_item_id,artfee.fee_item_desc_en,
                                        feesub.fee_rule_cond1,feesub.fee_rule_formula1,feesub.fee_rule_cond2,feesub.fee_rule_formula2 order by feerule.fee_rule_desc_en,artfee.fee_item_desc_en");


                if ($result) {

                    $html_design = "<style>td{padding:5px;} </style>"
                            . "<tr><td ><h2 align=center>Fee Rule Details</h2></td></tr>"
                            . "<hr style='color:red;'>"
                            . "<div class='table-responsive'>"
                            . "<br/>"
                            . "<table border=1 style='border-collapse:collapse;' width=100%>"
                            . "<tr>"
                            . "<th style='text-align:center;' >Sr No</th>"
                            . "<th style='text-align:center;' >Article Name</th>"
                            . "<th style='text-align:center;' >Fee Rule Description</th>"
                            . "<th style='text-align:center;' >Fee item Description</th>"
                            . "<th style='text-align:center;' >Fee Rule Formula 1</th>"
                            . "<th style='text-align:center;' >Fee Rule Cond 1</th>"
                            . "<th style='text-align:center;' >Fee Rule Formula 2</th>"
                            . "<th style='text-align:center;' >Fee Rule Cond 2</th>"
                            . "</tr>";
                    $SrNo = 1;

                    foreach ($result as $result1) {

                        $html_design .="<tr>"
                                . "<td align=center>" . $SrNo++ . "</td>"
                                . "<td align=center>" . $result1[0]['article_desc_en'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_rule_desc_en'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_item_desc_en'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_rule_formula1'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_rule_cond1'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_rule_formula2'] . "</td>"
                                . "<td style='text-align:center;'>" . $result1[0]['fee_rule_cond2'] . "</td>"
                                . "</tr>";
                    }

                    $html_design .= "</table></div>";
                    $this->create_pdf($html_design, 'fee description', 'A4-L', 'NGDRS');
                } else {
                    $this->Session->setFlash(__('Record not found'));
                    $this->redirect(array('controller' => 'Reports', 'action' => 'rpt_transaction'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    function checkpayment_fortatkal($token = NULL) {
        try {

            //$this->autoRender = FALSE;
//return 1;

            $this->loadModel('BankPayment');
            $this->loadModel('tatkal_app_config');


            $payment = $this->BankPayment->find('all', array('conditions' => array('token_no' => $token, 'payment_status' => 'SUCCESS')));
            $paid_amt = 0;
            if (!empty($payment)) {
                foreach ($payment as $pay) {

                    $data['bank_trn_id'] = $pay['BankPayment']['transaction_id'];
                    $data['payment_mode_id'] = $pay['BankPayment']['payment_mode_id'];
                    $extrafields['token_no'] = $pay['BankPayment']['token_no'];
                    $extrafields['article_id'] = $this->Session->read("article_id");
                    $extrafields['lang'] = 'en';
                    $serviceobj = new WebServiceController();
                    $serviceobj->constructClasses();
                    $responce = $serviceobj->PayuPayment($data, $extrafields);


                    if (!empty($responce['OnlinePaymentData'])) {


                        $paid_amt = $paid_amt + $responce['OnlinePaymentData']['pamount'];
                    }
                }
            }

            $tatkal_amt = $this->tatkal_app_config->find('first');
            if (!empty($tatkal_amt)) {
                $amount = $tatkal_amt['tatkal_app_config']['amount'];
            } else {
                $amount = 0;
            }
            if ($paid_amt >= $amount) {
                return 1;
            } else {
                return 0;
//                    
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    function check_permission_case() {
        try {

            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['maincast_id']) and is_numeric($this->request->data['maincast_id'])) {

                $maincast_id = $this->request->data['maincast_id'];

                $permission = ClassRegistry::init('maincast')->field('permission_case_no_require', array('maincast_id' => $maincast_id));
                echo $permission;
                exit;
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    updations while validations
//    ngdrstab_mst_party_category_fields(vrule_ll,vrule_en added)
//ngdrstab_conf_behavioral_patterns(vrule_ll,vrule_en added)
//changes done in party_entry() and get_party_feilds() function 



    public function property_fields() {
        try {

            $this->loadModel('PropertyFields');
            $this->loadModel('usage_category');
            $doc_lang = $this->Session->read('doc_lang');
            $data = $this->request->data;
            $PropertyFields = array();
            if (isset($data['village_id']) && is_numeric($data['village_id'])) {
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_en', 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id'), 'conditions' => array('village_id' => $data['village_id'])));
                $land_type = 0;
                if (!empty($villagedetails)) {
                    $land_type = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                }
                if (!empty($data['usage_id'])) {
                    $rulearr = explode(",", substr($data['usage_id'], 1));
                    //pr($rulearr);
                    $usage_category = $this->usage_category->find("all", array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id'),
                        'conditions' => array('evalrule_id' => $rulearr)
                    ));
                    $sql = "select propfield.field_id,propfield.field_desc_en,propfield.field_desc_$doc_lang ,is_required,is_date,date_format,start_date,end_date

from ngdrstab_conf_property_dependent_fields_mapping  as  propfieldmap
JOIN ngdrstab_conf_property_dependent_fields  as propfield ON propfield.field_id=propfieldmap.field_id

where  1=0 ";
                    $flag = 0;
                    if (!empty($usage_category)) {
                        foreach ($usage_category as $usage) {
                            $usage = $usage['usage_category'];
                            if (is_numeric($land_type) && is_numeric($usage['usage_main_catg_id']) && is_numeric($usage['usage_sub_catg_id'])) {
                                $flag = 1;
                                $sql = $sql . " OR  (developed_land_types_id=" . $land_type . " and usage_main_catg_id=" . $usage['usage_main_catg_id'] . " and usage_sub_catg_id=" . $usage['usage_sub_catg_id'] . ")";
                            }
                        }
                    }
                    if ($flag) {
                        //pr($sql);
                        $PropertyFields = $this->PropertyFields->query($sql);
                        // pr($PropertyFields);
                    }
                }
            }

            $this->set(compact('PropertyFields', 'doc_lang'));
        } catch (Exception $ex) {
            //   pr($ex);
        }
    }

    function all_state_reschedule() {


        try {
            $this->loadModel('appointment');
            $reschedule = $this->appointment->find('all', array('fields' => array('DISTINCT appointment.original_date', 'appointment_date'), 'conditions' => array(
                    'original_date !=' => NULL)));
            $this->set('reschedule', $reschedule);
//              pr($reschedule);
//              exit;

            if ($this->request->is('post')) {

                if ($this->request->data['appointment']['appointment_date'] != NULL && $this->request->data['appointment']['reschedule_date'] != NULL) {

                    $apt_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])) . "'";
                    $reschedule_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['reschedule_date'])) . "'";


                    $this->appointment->updateAll(
                            array('appointment.appointment_date' => $reschedule_date, 'appointment.original_date' => $apt_date),
                            // conditions
                            array('appointment.appointment_date' => $apt_date)
                    );
                    $this->Session->setFlash(
                            __('Appointments reschedule successfully')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'all_state_reschedule'));
                } else {
                    $this->Session->setFlash(
                            __('Please Select Both Dates')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'all_state_reschedule'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function office_wise_reschedule() {
        try {
            $this->loadModel('office');
            $this->loadModel('appointment');
            $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => 'office_name_en'));
            $this->set('office', $office);
            $reschedule = $this->appointment->find('all', array('fields' => array('DISTINCT appointment.original_date', 'appointment_date', 'o.office_name_en'), 'conditions' => array(
                    'original_date !=' => NULL), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_office',
                        'alias' => 'o',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('o.office_id = appointment.office_id')))));

            $this->set('reschedule', $reschedule);
            if ($this->request->is('post')) {

                if ($this->request->data['appointment']['appointment_date'] != NULL && $this->request->data['appointment']['reschedule_date'] != NULL && is_numeric($this->request->data['appointment']['office_id'])) {

                    $apt_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])) . "'";
                    $reschedule_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['reschedule_date'])) . "'";
                    $office_id = $this->request->data['appointment']['office_id'];
                    $this->loadModel('appointment');

                    $this->appointment->updateAll(
                            array('appointment.appointment_date' => $reschedule_date, 'appointment.original_date' => $apt_date),
                            // conditions
                            array('appointment.appointment_date' => $apt_date, 'appointment.office_id' => $office_id)
                    );
                    $this->Session->setFlash(
                            __('Appointments reschedule successfully')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'office_wise_reschedule'));
                } else {
                    $this->Session->setFlash(
                            __('Please Select Both Dates')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'all_state_reschedule'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function appointment_reschedule() {
        try {
            $this->loadModel('office');
            $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => 'office_name_en'));
            $this->set('office', $office);
            $this->loadModel('appointment');
            $reschedule = $this->appointment->find('all', array('fields' => array('DISTINCT appointment.original_date', 'appointment_date'), 'conditions' => array(
                    'original_date !=' => NULL, 'office_id' => $this->Session->read("office_id"))));
            $this->set('reschedule', $reschedule);
            if ($this->request->is('post')) {

                if ($this->request->data['appointment']['appointment_date'] != NULL && $this->request->data['appointment']['reschedule_date'] != NULL) {

                    $apt_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])) . "'";
                    $reschedule_date = "'" . date('Y-m-d', strtotime($this->request->data['appointment']['reschedule_date'])) . "'";
                    $office_id = $this->Session->read("office_id");


                    $this->appointment->updateAll(
                            array('appointment.appointment_date' => $reschedule_date, 'appointment.original_date' => $apt_date),
                            // conditions
                            array('appointment.appointment_date' => $apt_date, 'appointment.office_id' => $office_id)
                    );
                    $this->Session->setFlash(
                            __('Appointments reschedule successfully')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'office_wise_reschedule'));
                } else {
                    $this->Session->setFlash(
                            __('Please Select Both Dates')
                    );

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'all_state_reschedule'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function date_compaire($start_date, $end_date, $entrydate) {
        $returnflag = 1;
        if (!empty($start_date) && !empty($end_date)) {
            if (strpos($start_date, 'd') !== false) {
                $start_date = str_replace("d", " days", $start_date);
            } else if (strpos($start_date, 'm') !== false) {
                $start_date = str_replace("m", " months", $start_date);
            } else if (strpos($start_date, 'y') !== false) {
                $start_date = str_replace("y", " years", $start_date);
            }

            $cmpdt = date("Y-m-d", strtotime(date('Y-m-d') . $start_date));
            $datestartobj = new DateTime($cmpdt);

            if (strpos($end_date, 'd') !== false) {
                $end_date = str_replace("d", " days", $end_date);
            } else if (strpos($end_date, 'm') !== false) {
                $end_date = str_replace("m", " months", $end_date);
            } else if (strpos($end_date, 'y') !== false) {
                $end_date = str_replace("y", " years", $end_date);
            }

            $cmpdt = date("Y-m-d", strtotime(date('Y-m-d') . $end_date));
            $dateendobj = new DateTime($cmpdt);

            $entrydateobj = new DateTime($entrydate);

            if ($entrydateobj >= $datestartobj && $entrydateobj <= $dateendobj) {
                $returnflag = 1;
            } else {
                $returnflag = 0;
            }
        }
        return $returnflag;
    }

    function add_month_in_date() {
        try {

            if (isset($this->request->data['date'])) {
                $date = $this->request->data['date'];
                $period = $this->request->data['period'];

                echo date('Y-m-d', strtotime($date . '+' . $period . ' months'));


                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function getregfee_pdf($feetot, $pmobile, $pname, $talukanm) {
        $requestdata_reg['webUser'] = 'NGDRSAPPLN';
        $requestdata_reg['webPass'] = 'q]%6(;CcVyPJaYU3';
        $requestdata_reg['No_of_fee_items'] = '1';
        //$requestdata_reg['Feeamt']='1400';
        $requestdata_reg['Feeamt'] = $feetot;
        //$requestdata_reg['mobile']='9665366856';
        $requestdata_reg['mobile'] = $pmobile;
        //$requestdata_reg['Partyname']='Priya H Kamat';
        $requestdata_reg['Partyname'] = $pname;
        $requestdata_reg['Party_Address'] = 'Tiswadi,Goa';
        $requestdata_reg['Party_PIN'] = '403521';
        $requestdata_reg['email'] = 'priya@yahoo.com';
        $requestdata_reg['Party_taluka'] = $talukanm;
        $requestdata_reg['IPAddress'] = '10.155.4.10';
        //$requestdata_reg['IPAddress'] = $_SERVER['REMOTE_ADDR'];
        $requestdata_reg['Reason'] = 'Registration Fee for Reg No. BRZ_2018-8956';
        $requestdata_reg['OtherDetails'] = 'RegNo BRZ_2018-8923';

        $client_reg = new SoapClient('http://10.155.4.10/wsNGDRS/echallan.asmx?wsdl', array('trace' => 1));
        //pr($requestdata_reg);
        $servicedata_reg = $client_reg->Generate_eChallan_RegFee($requestdata_reg);

        if (is_object($servicedata_reg)) {
            $bb = get_object_vars($servicedata_reg);
        }
        $sto_reg = $bb['Generate_eChallan_RegFeeResult'];
        //echo $sto_reg;
        $arr_reg = explode(" ", $sto_reg);
        $str_reg = $arr_reg['19'];
        $str_reg = str_replace('<filebytes>', "", $str_reg);
        $str_reg = str_replace('</filebytes>', "", $str_reg);
        $str_reg_final = $str_reg;
        $this->Session->write('str_reg_final', $str_reg_final);
    }

    function getmutationfee_pdf($feetot, $pmobile, $pname, $talukanm) {
        //////////  web service mutation fee - devashree  ///////
        $requestdata['webUser'] = 'NGDRSAPPLN';
        $requestdata['webPass'] = 'q]%6(;CcVyPJaYU3';
        $requestdata['No_of_fee_items'] = '2';
        $requestdata['Feeamt'] = $feetot;
        $requestdata['mobile'] = $pmobile;
        $requestdata['Partyname'] = $pname;
        $requestdata['Party_Address'] = 'Tiswadi,Goa';
        $requestdata['Party_PIN'] = '403521';
        $requestdata['email'] = 'priya@yahoo.com';
        $requestdata['Party_taluka'] = $talukanm;
        $requestdata['IPAddress'] = '10.155.4.10';
        //$requestdata['IPAddress'] = $_SERVER['REMOTE_ADDR'];
        $requestdata['Reason'] = 'Registration Fee for Reg No. BRZ_2018-8956';
        $requestdata['OtherDetails'] = 'RegNo BRZ_2018-8923';
        $requestdata['RuralUrbanFlag'] = 'RURALNORTH';

        $client = new SoapClient('http://10.155.4.10/wsNGDRS/echallan.asmx?wsdl', array('trace' => 1));

        $servicedata = $client->Generate_eChallan_MutationFee($requestdata);
        //pr($servicedata);


        if (is_object($servicedata)) {
            $aa = get_object_vars($servicedata);
        }
        $sto = $aa['Generate_eChallan_MutationFeeResult'];
        $arr = explode(" ", $sto);
        $str = $arr['19'];
        $str = str_replace('<filebytes>', "", $str);
        $str = str_replace('</filebytes>', "", $str);
        $str_final = $str;
        $this->Session->write('str_final', $str_final);
    }

    function get_gov_body($val_id = NULL) {
        try {
            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {
                $this->loadModel('property_details_entry');
                $record = $this->property_details_entry->find('first', array('fields' => array('developed_land_types_id'),
                    'conditions' => array('val_id' => $valuation_id),
                    'joins' => array(array('table' => 'ngdrstab_mst_developed_land_types', 'alias' => 'land', 'type' => 'inner', 'foreignKey' => false,
                            'conditions' => array('property_details_entry.developed_land_types_id=land.developed_land_types_id')))));
                if (!empty($record)) {
                    return $record['property_details_entry']['developed_land_types_id'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function insert_apptmt_temp_to_original() {
        try {

            //$this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['tatkal_app_config', 'appointment_temp', 'appointment', 'fees_calculation', 'fees_calculation_detail']);
            $tatkal_amt = $this->tatkal_app_config->find('first');
            if (!empty($tatkal_amt)) {
                $amount = $tatkal_amt['tatkal_app_config']['amount'];
            } else {
                $amount = 0;
            }

            $rec = $this->appointment_temp->find('first', array('conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));


            if (!empty($rec)) {
                $data = array('office_id' => $rec['appointment_temp']['office_id'],
                    'interval_id' => $rec['appointment_temp']['interval_id'],
                    'slot_no' => $rec['appointment_temp']['slot_no'],
                    'appointment_date' => $rec['appointment_temp']['appointment_date'],
                    'user_id' => $rec['appointment_temp']['user_id'],
                    'user_type' => $rec['appointment_temp']['user_type'],
                    'state_id' => $rec['appointment_temp']['state_id'],
                    'tatkal_totalslot' => $rec['appointment_temp']['tatkal_totalslot'],
                    'req_ip' => $rec['appointment_temp']['req_ip'],
                    'token_no' => $rec['appointment_temp']['token_no'],
                    'sheduled_time' => $rec['appointment_temp']['sheduled_time'],
                    'shift_id' => $rec['appointment_temp']['shift_id'],
                    'flag' => $rec['appointment_temp']['flag']);


                $appt = $this->appointment->find('first', array('conditions' => array('office_id' => $rec['appointment_temp']['office_id'], 'interval_id' => $rec['appointment_temp']['interval_id'], 'slot_no' => $rec['appointment_temp']['slot_no'], 'appointment_date' => $rec['appointment_temp']['appointment_date'], 'flag' => 'T')));
//            echo '<pre>'; print_r($appt);exit;
                if (empty($appt)) {

                    $chkforappt = $this->appointment->find('first', array('conditions' => array('token_no' => $rec['appointment_temp']['token_no'])));
                    if (empty($chkforappt)) {
                        if ($this->appointment->save($data)) {


                            $fee = array(
                                'state_id' => $rec['appointment_temp']['state_id'],
                                'req_ip' => $rec['appointment_temp']['req_ip'],
                                'user_id' => $rec['appointment_temp']['user_id'],
                                'token_no' => $rec['appointment_temp']['token_no'],
                                'article_id' => $this->Session->read("article_id"),
                                'final_amt' => $amount,
                                'user_type' => $rec['appointment_temp']['user_type']);
                            $this->fees_calculation->save($fee);
                            $fee_cal_id = $this->fees_calculation->getInsertID();
                            $fee_detail = array(
                                'fee_calc_id' => $fee_cal_id,
                                'state_id' => $rec['appointment_temp']['state_id'],
                                'req_ip' => $rec['appointment_temp']['req_ip'],
                                'user_id' => $rec['appointment_temp']['user_id'],
                                'fee_item_id' => 18,
                                'item_type_id' => 2,
                                'final_value' => $amount
                            );
                            $this->fees_calculation_detail->save($fee_detail);
                            $this->appointment_temp->deleteAll(array('appointment_temp.token_no' => $rec['appointment_temp']['token_no']));
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            
        }
    }

    function reschedule($token, $csrftoken = NULL) {
        try {
            $this->check_csrf_token($csrftoken);
            $this->loadModel('language');

            $lang = $this->language->query('select DISTINCT l.language_code ,g.local_language_id from ngdrstab_trn_generalinformation g,ngdrstab_mst_language l ,ngdrstab_conf_language cl where g.local_language_id=l.id and g.token_no=?', array($token));
            if (!empty($lang)) {
                if ($lang[0][0]['language_code'] == 'en') {
                    $this->Session->write('doc_lang', 'en');
                } else {
                    $this->Session->write('doc_lang', 'll');
                }
                $this->Session->write('sess_langauge', $lang[0][0]['language_code']);
                CakeSession::write('Config.language', $lang[0][0]['language_code']);
                ClassRegistry::init('Formlabel')->updatepo();
            }
            $this->Session->write('Selectedtoken', $token);
            $this->Session->write('reschedule_flag', 'Y');

            $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function payu_payment_entry($transid = NULL) {
        try {
            $this->response->header(array(
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => 'Content-Type'
                    )
            );
            $this->loadModel('BankPayment');
            $this->loadModel('external_interface');
            $this->loadModel('genernalinfoentry');


            if (!is_null($transid)) {

                $result = $this->BankPayment->find("all", array(
                    'fields' => array('info.token_no', 'info.article_id', 'BankPayment.payment_mode_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'conditions' => array('info.token_no=BankPayment.token_no')),
                    ),
                    'conditions' => array('BankPayment.transaction_id' => $transid)
                ));
                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Number Not Available!')
                    );
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payu_payment_entry'));
                }
                //  pr($result);exit;
                $data['bank_trn_id'] = $transid;
                $data['payment_mode_id'] = $result[0]['BankPayment']['payment_mode_id'];

                $extrafields['token_no'] = $result[0]['info']['token_no'];
                $extrafields['article_id'] = $result[0]['info']['article_id'];
                $extrafields['lang'] = 'en';
                $serviceobj = new WebServiceController();
                $serviceobj->constructClasses();
                $responce = $serviceobj->PayuPayment($data, $extrafields);
                if (isset($responce['Error']) && !empty($responce['Error'])) {
                    $this->Session->setFlash(
                            __('' . $responce['Error'])
                    );
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payu_payment_entry'));
                } else {
                    $resdata = $responce['OnlinePaymentData'];
                    $usertype = $this->Session->read("session_usertype");
                    $now = date('Y-m-d H:i:s');
                    if ($usertype == 'C') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,user_type=?,gateway_trans_id=? where token_no=?  and transaction_id=?", array($resdata['verification_number'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['certificate_no'], $resdata['token_no'], $resdata['bank_trn_id']));
                    } elseif ($usertype == 'O') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=? where token_no=? and transaction_id=?", array($resdata['verification_number'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['certificate_no'], $resdata['token_no'], $resdata['bank_trn_id']));
                    }
                    $this->Session->setFlash(
                            __('Payment Status Updated')
                    );
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payu_payment_entry'));
                }
            }

            $mapping = $this->BankPayment->mapping_account_heads(10);

            $hash = '';
            $hash_string = '';
            $action = '';
            $txnid = '';
            // Merchant key here as provided by Payu
            $MERCHANT_KEY = ""; //Please change this value with live key for production        
            // Merchant Salt as provided by Payu
            $SALT = ""; //Please change this value with live salt for production
            // End point - change to https://secure.payu.in for LIVE mode
            $PAYU_BASE_URL = "";

            $usertype = $this->Session->read("session_usertype");
            $result = array();
            if ($usertype == 'C') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('user_id' => $this->Auth->user('user_id')),
                    'order' => array('trn_id DESC'),
                        )
                );
            }
            if ($usertype == 'O') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('org_user_id' => $this->Auth->user('user_id')),
                    'order' => array('trn_id DESC'),
                ));
            }



            $this->set(compact('action', 'hash', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function generate_form60($id = null) {

        $this->loadModel('party_entry');
        $this->loadModel('TrnBehavioralPatterns');

        $party = $this->party_entry->query('select party_full_name_en,father_full_name_en,uid,mobile_no,pan_no,dob,p.identificationtype_id,p.identificationtype_desc_en as value,iden.identificationtype_desc_en,dist.district_name_en,
tal.taluka_name_en,village.village_name_en
 from ngdrstab_trn_party_entry_new p

left join ngdrstab_mst_identificationtype iden on iden.identificationtype_id=p.identificationtype_id
left join ngdrstab_conf_admblock3_district dist on dist.district_id=p.district_id
left join ngdrstab_conf_admblock5_taluka tal on tal.taluka_id=p.taluka_id
left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=p.village_id
where p.id=' . $id
        );

        $tmp_party_address = $this->TrnBehavioralPatterns->get_pattern_detail('en', $id, $this->Session->read("Selectedtoken"), '2', 'en');

        $party_address = array();
        foreach ($tmp_party_address as $tmp_party_address) {
            array_push($party_address, $tmp_party_address['pattern']['pattern_desc_en'] . ' - ' . $tmp_party_address['TrnBehavioralPatterns']['field_value_en']);
        }


        $html_design = "<h1 align=center> Form 60/61 <br/></h1> "
                . "<table width=100% border=1>"
                . "<tr> <td width=50%> <b><h3> Name Of Person : </b> </td> <td align=center>  " . $party[0][0]['party_full_name_en'] . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> Father Name : </b> </td> <td align=center>  " . $party[0][0]['father_full_name_en'] . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> UID : </b> </td> <td align=center>  " . $this->dec($party[0][0]['uid']) . "</td> </tr>"
                . "<tr> <td width=50%> <b> <h3>PAN : </b> </td> <td align=center>  " . ($party[0][0]['pan_no']) . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> Date of Birth : </b> </td> <td align=center>  " . date('d-m-Y', strtotime($party[0][0]['dob'])) . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> Mobile Number : </b> </td> <td align=center> " . ($party[0][0]['mobile_no']) . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> District Name : </b> </td> <td align=center> " . ($party[0][0]['district_name_en']) . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> Taluka Name : </b> </td> <td align=center>  " . ($party[0][0]['taluka_name_en']) . "</td> </tr>"
                . "<tr> <td width=50%> <b><h3> Village Name : </b> </td> <td align=center>  " . ($party[0][0]['village_name_en']) . "</td> </tr>"
                . "<tr> <td width=50%> <b> <h3>Address : </b> </td> <td align=center>  " . implode(', ', $party_address) . "</td> </tr>"
                . "</table><br/>";


        $html_design.="<br><br><h3 align=right>Signature</h3>";

        $this->create_pdf($html_design, 'Form 60/61', 'A4', 'Form 60/61');
    }

    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = '', $display_flag = 'D') {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mpdf');
            $mpdf = new mPDF('utf-8', $page_size, 10, 'dejavusans');
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->setFooter('{PAGENO} / {nb}');
            if ($waterMark) {
                $mpdf->SetWatermarkText($waterMark);
                $mpdf->watermarkTextAlpha = 0.2;
                $mpdf->showWatermarkText = true;
            }

            $mpdf->WriteHTML($html_design);
            $mpdf->Output($file_name . ".pdf", $display_flag); // 'I' for Display PDF in Next Tab
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! error in creating PDF');
        }
    }

    function get_party_share($property_id = NULL) {
        try {
            $this->autoRender = FALSE;
            $property_id = (isset($_POST['property_id'])) ? $_POST['property_id'] : $property_id;
            if ($property_id) {
                $this->loadModel('party_entry');
                $party_share = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where property_id=$property_id");

                if (!empty($party_share)) {
                    return $party_share[0][0]['count'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getdocs() {
        $this->loadModel('upload_document');
        $this->loadModel('regconfig');
        $data = $this->request->data;
        //pr($data);
        $article_sel = $data['article_sel'];
        $article_title_sel = $data['article_title_sel'];
        $this->check_csrf_token_withoutset($data['csrftoken']);
        $language = $this->Session->read("sess_langauge");
        $this->set('lang', $language);
        $upload_doc_title_flag = $this->regconfig->field('conf_bool_value', array('reginfo_id' => 143));
        //pr($upload_doc_title_flag);
        if ($upload_doc_title_flag == 'Y') {
            $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_article_document_mapping',
                        'alias' => 'ad',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.articledescription_id=" . $article_title_sel . " and ad.article_id=" . $article_sel)
                    )), 'order' => array('upload_document.document_id' => 'ASC')));
        } else {
            $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_article_document_mapping',
                        'alias' => 'ad',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.article_id=" . $article_sel)
                    )), 'order' => array('upload_document.document_id' => 'ASC')));
        }

        $this->set('upload_file1', $upload_file1);
    }

    public function govappoinment($csrftoken = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment', 'ApplicationSubmitted', 'fees_calculation', 'fees_calculation_detail', 'regconfig', 'tatkal_app_config'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 158)));

            $this->set('gov_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $doc_lang = $this->Session->read('doc_lang');
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $office_id = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));
            $office = $this->office->get_officedetails_for_appointment($office_id);

            $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $doc_lang), 'order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $office[0]['office']['shift_id'])));
            $this->set('office_id', $office_id);
            $this->set('officeshift', $officeshift);
            $appointment = $this->appointment->find('all', array('conditions' => array(
                    'appointment.token_no ' => $this->Session->read("Selectedtoken"), 'appointment.user_id' => $user_id)));
            $this->set('appointment', $appointment);
            $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array(
                    'ApplicationSubmitted.token_no ' => $this->Session->read("Selectedtoken"))));
            if (count($submission) > 0) {
                $this->set('submission_flag', 'Y');
            } else {
                $this->set('submission_flag', 'N');
            }
//            $tatkal_amt = $this->tatkal_app_config->find('first');
//            if (!empty($tatkal_amt)) {
//                $amount = $tatkal_amt['tatkal_app_config']['amount'];
//            } else {
//                $amount = 0;
//            }
//            $this->set('amount', $amount);
            if ($this->request->is('post')) {

                // $this->check_csrf_token($this->request->data['tatkalappoinment']['csrftoken']);
//                if (!$this->checkpayment_fortatkal()) {
//                    $this->Session->setFlash(__("Please Pay Tatkal Fee Using Make Payment Button"));
//                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'tatkalappoinment', $this->Session->read('csrftoken')));
//                }

                if (isset($this->request->data['govappoinment']['appointment_date'])) {
                    $app_date = date('Y-m-d', strtotime($this->request->data['govappoinment']['appointment_date']));
                    $today = date('Y-m-d');

                    if ($app_date < $today) {
                        $this->Session->setFlash(__("Please Check System Date"));
                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'govappoinment', $this->Session->read('csrftoken')));
                    }
                }

                $this->request->data['govappoinment']['user_type'] = $this->Session->read("session_usertype");
                if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                    $this->Session->setFlash(__("Please Select slot"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'govappoinment', $this->Session->read('csrftoken')));
                }

                list($interval, $slot) = explode('_', $_POST['slot']);
                $data = array('office_id' => $this->request->data['govappoinment']['office_id'],
                    'interval_id' => $interval,
                    'slot_no' => $slot,
                    'appointment_date' => date('Y-m-d', strtotime($this->request->data['govappoinment']['appointment_date'])),
                    'user_id' => $user_id,
                    'user_type' => $this->request->data['govappoinment']['user_type'],
                    'state_id' => $stateid,
                    'gov_totalslot' => $_POST['totalslot'],
                    'req_ip' => $ip,
                    'token_no' => $this->Session->read("Selectedtoken"),
                    'sheduled_time' => $_POST['time'],
                    'shift_id' => $this->request->data['govappoinment']['shift_id'],
                    'flag' => 'G');
                $this->set_csrf_token();
//                $tatkal_amt = $this->tatkal_app_config->find('first');
//                if (!empty($tatkal_amt)) {
//                    $amount = $tatkal_amt['tatkal_app_config']['amount'];
//                } else {
//                    $amount = 0;
//                }

                if ($this->appointment->save($data)) {


                    $fee = array(
                        'state_id' => $stateid,
                        'req_ip' => $ip,
                        'user_id' => $user_id,
                        'token_no' => $this->Session->read("Selectedtoken"),
                        'article_id' => $this->Session->read("article_id"),
                        'user_type' => $this->request->data['govappoinment']['user_type']);


                    $this->Session->setFlash(__("Slot allocated Successfully"));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info', $this->Session->read('csrftoken')));
                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gov_slot_alocation() {

        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['office_id']) and is_numeric($_POST['office_id']) and isset($_POST['shift_id']) and is_numeric($_POST['shift_id'])) {
                array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment'));

                $stateid = $this->Auth->User("state_id");

                $office = $this->office->get_officedetails_for_govappointment($_POST['office_id']);
//               pr($office);
//               exit;
                $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $_POST['shift_id'])));

                $appointment = $this->appointment->find('all', array('conditions' => array(
                        'appointment.appointment_date ' => date('Y-m-d', strtotime($_POST['app_date'])), 'appointment.office_id' => trim($_POST['office_id']), 'appointment.shift_id' => trim($_POST['shift_id']), 'appointment.state_id' => $stateid, 'appointment.flag' => 'G'
                )));

                $this->set('appointment', $appointment);

                if (!empty($office)) {


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);


                    $time1 = date('G:i', strtotime($shift[0]['officeshift']['gov_apt_to_time']));
                    $time2 = date('G:i', strtotime($shift[0]['officeshift']['gov_apt_from_time']));
                    $time_diff = $this->get_time_difference($time1, $time2);

                    //lunch array
                    $lunch_time_array = array();
                    $i = 0;
                    $time11 = date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']));
                    do {
                        if (((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])))) {
                            $lunch_time_array[$i] = $time11 . '-' . date('G:i', strtotime($time11) + 30 * 60);
                            $time11 = date('G:i', strtotime($time11) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time11) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    //gov array

                    $gov_array = array();
                    $i = 0;
                    $time111 = date('G:i', strtotime($shift[0]['officeshift']['gov_apt_from_time']));
                    do {
                        if (((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['gov_apt_to_time'])))) {
                            $gov_array[$i] = $time111 . '-' . date('G:i', strtotime($time111) + 30 * 60);
                            $time111 = date('G:i', strtotime($time111) + 30 * 60);
                        }
                        $i++;
                    } while ((strtotime($time111) + 30 * 60 ) <= (strtotime($shift[0]['officeshift']['gov_apt_to_time'])));


                    $fin_array = array_diff($gov_array, $lunch_time_array);
                    $a = $this->cal_appt($fin_array);


                    $curr_date = date('d-m-Y');


                    $this->set('slot', $office[0]['slot']['slot_time_minute']);
                    $this->set('lunch_from', date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time'])));
                    $this->set('lunch_to', date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time'])));
                    $this->set('gov_from', date('G:i', strtotime($shift[0]['officeshift']['gov_apt_from_time'])));
                    $this->set('gov_to', date('G:i', strtotime($shift[0]['officeshift']['gov_apt_to_time'])));
                    $this->set('app_dt', $_POST['app_date']);
                    $this->set(compact('extraslot', 'minutes', 'hours', 'a'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function getpow_attorny_list() {

        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $fields = $this->set_common_fields();
            $doc_lang = $this->Session->read('doc_lang');
            $lang = $this->Session->read("sess_langauge");
            array_map(array($this, 'loadModel'), array('party_entry'));
            if (isset($_POST['doc_reg_no']) && ($_POST['doc_reg_no']) != NULL) {

                $reg_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['doc_reg_date'])));
                $token = ClassRegistry::init('ApplicationSubmitted')->field('token_no', array('doc_reg_no' => $_POST['doc_reg_no'], 'DATE(doc_reg_date)' => $reg_date));
                if (is_numeric($token)) {
                    $party_record = $this->party_entry->get_partyrecord($token, $fields['user_id'], $doc_lang, $lang, $this->Session->read('sroparty'));


                    $this->set('party_record', $party_record);
                } else {
                    $this->set('party_record', array());
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;

//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
