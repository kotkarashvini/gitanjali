<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'Fees'); // mention at top
App::import('Controller', 'Property'); // mention at top

class HomeVisitController extends FeesController {

    //put your code here
    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha', 'Cookie',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Users', 'action' => 'welcome'),
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
        $this->Auth->allow('forgotpassword_citizen', 'welcomenote', 'citizenlogin', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'district_change_event');
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

       public function genernal_info() {
        try {
            array_map(array($this, 'loadModel'), array('genernal_info','regconfig'));
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("user_id", $this->Auth->user('user_id'));
            $user_id = $this->Session->read("user_id");
            $laug = $this->Session->read("sess_langauge");
           
            $office_id = $this->Auth->user('office_id');
           
            $statusrecord = $this->genernal_info->get_alldocument_homevisit($user_id, $laug, $this->Session->read("session_usertype"),$office_id);  
          
            $this->set('statusrecord', $statusrecord);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function party($lock_party_id = NULL, $csrftoken = NULL) {

        try {
            $this->loadModel("party_entry");
            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("trnhomevisit");

            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('cap', NULL);
            $this->set('pic', NULL);

            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $userid = $this->Auth->User('user_id');
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $language = $this->Session->read('sess_langauge');
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $citizen_user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read("doc_lang");
            $party = $this->party_entry->get_partyrecord_homevisit($tokenno, $doc_lang, $language);
          

            $this->set("partylist", $party = $this->party_entry->get_partyrecord_homevisit($tokenno, $doc_lang, $language));
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);

            if ($this->request->is('post')) {


                //$this->check_csrf_token($this->request->data['party']['csrftoken']);
                $uploadeddate = date('Y-m-d H:i:s');
                $this->request->data['party']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['party']['user_id'] = $this->Auth->User('user_id');

                $this->request->data['party']['req_ip'] = $_SERVER['REMOTE_ADDR'];

                if (isset($this->request->data['btnaccept'])) {
// should validate before update
                    //if ($this->party_entry->validate($party, $path)) {
                    $trnrec = $this->trnhomevisit->find('all', array('conditions' => array('token_no' => $tokenno)));


                    $homvisit = array('token_no' => $tokenno,
                        'remark' => "'".$this->request->data['party']['remark']."'",
                        'visit_date' => "'".date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['party']['visit_date'])))."'",
                        'user_id' => $this->Auth->User('user_id'),
                        'state_id' => $this->Auth->User('state_id'),
                        'req_ip' => "'".$_SERVER['REMOTE_ADDR']."'"
                    );
                    if (!empty($trnrec)) {
                        $this->trnhomevisit->updateAll(
                                $homvisit, array('token_no' => $tokenno)
                        );
                    } else {
                        $this->trnhomevisit->save($homvisit);
                    }
                    $this->party_entry->updateAll(
                            array('record_lock' => "'Y'", 'home_visit_flag' => "'Y'"), array('token_no' => $tokenno)
                    );

                    $this->request->data['party']['remark'] = $this->request->data['party']['remark'];
                    $this->Session->setFlash(__("Party Details Completed Sucessfully"));
//                    } else {
//                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
//                    }
                    $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                }

                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];

//               pr($path);exit;

                if ($_POST['actiontype'] == '1') {
                    $folder = "Biometric_Party";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
// to check directory exist or not 
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '.png';
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '.png';
                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?  WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Record Not Found"));
                        $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                    }
                }
                if ($_POST['actiontype'] == '2') {
                    $loc = $this->party_entry->query("select biometric_img,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                    if (!empty($loc)) {
                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, photo_upload=?, photo_img=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, $id, $tokenno, 'N'));
                        if ($check == NULL) {
                            $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
                            $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                            if (is_file($loc1)) {
                                unlink($loc1);
                            }
                            if (is_file($loc2)) {
                                unlink($loc2);
                            }

                            $this->Session->setFlash(__("Biometric Reset Successfully"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        } else {
                            $this->Session->setFlash(__("Biometric Reset Failed"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Record Not Found"));
                        $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                    }
                }
                if ($_POST['actiontype'] == '3') {
                    $folder = "Photo_Party";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['pic'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '.jpg';
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '.jpg';
                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $this->Session->setFlash(__("Record Not Found"));
                        $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                    }
                }
            }
            if (is_numeric($lock_party_id)) {
                // $this->check_csrf_token($csrftoken);
                if ($this->party_entry->validate($party, $path, $lock_party_id)) {
                    $this->party_entry->updateAll(
                            array('record_lock' => "'Y'", 'home_visit_flag' => "'Y'"), array('token_no' => $tokenno, 'party_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                    $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
                }
            }



            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            
            }

            $this->set_csrf_token();
        } catch (Exception $exc) {
            pr($exc);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function set_token_session($token, $csrftoken = NULL) {
        try {
            // $this->check_csrf_token($csrftoken);
            $this->loadModel('language');


            $this->Session->write('reg_token', $token);

            $this->redirect(array('action' => 'party', 'N', $this->Session->read('csrftoken')));
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
