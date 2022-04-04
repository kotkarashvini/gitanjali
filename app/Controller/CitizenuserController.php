<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'Fees'); // mention at top
App::import('Controller', 'Property'); // mention at top
App::import('Controller', 'DynamicVariables'); // mention at top
App::import('Controller', 'WebService');
App::import('Controller', 'PBWebService'); // mention at top

class CitizenuserController extends AppController {

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
        $this->Auth->allow('forgotpassword_citizen','otpsavecitizen');
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
                        }else{
                            echo 1;
                            exit;
                        }
                    }
                }
            }
        }else{
             echo 'Authentication failed';
            exit;
        }
        } else {
            echo 'Authentication failed';
            exit;
        }
    }
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
                           $this->redirect(array('controller' => 'Citizenentry','action' => 'citizenlogin'));
                        } else {
                            $this->set('recorddata', 3);
                        }
                    }
                    if ($actiontype == '4') {

//                         pr($this->request->data['forgotpassword']['newpassword']);
                         
                        if (isset($this->request->data['forgotpassword']['newpassword']) && isset($this->request->data['forgotpassword']['cpassword'])) {
                            if ($this->request->data['forgotpassword']['newpassword'] != NULL && $this->request->data['forgotpassword']['cpassword'] != NULL) {
                                if (Sanitize::html($this->request->data['forgotpassword']['newpassword']) == Sanitize::html($this->request->data['forgotpassword']['cpassword'])) {
                                    $data = array('pwd' => $this->request->data['forgotpassword']['newpassword']);
                                 
                                    if (Sanitize::check($data)) {
                                        $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                        if (preg_match($regex, Sanitize::html($this->request->data['forgotpassword']['newpassword']))) {
                                           
                                            if ($this->citizenuserreg->updateforgotpassword_citizen($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('controller' => 'Citizenentry','action' => 'citizenlogin'));
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
    public function resetpassword_citizen() {
        try {

            $this->loadModel('User');
            $this->loadModel('CitizenUser');
            $this->loadModel('otpcitizen');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $usertype = $this->Session->read("session_usertype");
            $user_id = $this->Auth->user('user_id');
            //pr($this->Session->read("session_usertype"));exit;
            $user = " ";
            if ($usertype == 'C') {
                $user = $this->CitizenUser->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user_citizen where user_id=$user_id");
                //pr($user);exit;
            } elseif ($usertype == 'O') {
                $user = $this->User->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user where user_id=$user_id");
            }
            if (empty($user)) {
                $this->Session->setFlash(__('User NOt Found!!'));
                $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
            }
            $get_username = $user[0][0]['username'];
            $get_password = $user[0][0]['password'];
            //  pr($get_username);exit;
            $this->set('user', $get_username);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->loadModel('mainlanguage');
            $fieldlist = array();
            $fieldlist['username']['text'] = 'is_username';
            $fieldlist['password1']['text'] = 'is_password';
            $fieldlist['newpassword']['text'] = 'is_password';
            $fieldlist['rpassword']['text'] = 'is_password';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
   $this->loadModel('smsevent');


            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 9)));
            if (!empty($event)) {
                if ($event[0]['smsevent']['send_flag'] == 'Y') {

                    $otp = NULL;
                } else {
                    $otp = 12345678;
                }
            }
            $this->set('otp', $otp);

            if ($this->request->is('post')) {
                //pr($this->request->data['resetpassword_citizen']);exit;//username
                //$this->check_csrf_token($this->request->data['resetpassword_citizen']['csrftoken']);

//                $this->request->data['resetpassword_citizen']['password1'] = $this->decrypt($this->request->data['resetpassword_citizen']['password1'], $this->Session->read("salt"));
//                $this->request->data['resetpassword_citizen']['newpassword'] = $this->decrypt($this->request->data['resetpassword_citizen']['newpassword'], $this->Session->read("salt"));
//                $this->request->data['resetpassword_citizen']['rpassword'] = $this->decrypt($this->request->data['resetpassword_citizen']['rpassword'], $this->Session->read("salt"));
//                $this->request->data['resetpassword_citizen'] = $this->istrim($this->request->data['resetpassword_citizen']);
                $errarr = $this->validatedata($this->request->data['resetpassword_citizen'], $fieldlist);
                if ($this->validationError($errarr)) {
                    //pr($this->request->data);

                    $current_password = $this->request->data['resetpassword_citizen']['password1'];
//                     pr($current_password);EXIT;
                    $hashed_current_password = $current_password;
                    //pr($hashed_current_password);exit;
                    $new_password = $this->request->data['resetpassword_citizen']['newpassword'];
                    $hashed_new_password = $new_password;
                    $new_confirm_password = $this->request->data['resetpassword_citizen']['rpassword'];
                    $hashed_new_confirmed_password = $new_confirm_password;
                     $check_username = $this->User->query("select username from ngdrstab_mst_user where username=?", array($get_username));
                    $check_username_value = $get_username;
                    if (($check_username_value) == ($get_username)) {
                        if (($hashed_new_password) == ($hashed_new_confirmed_password)) {
                            //  echo "hii";
                            //  $get_pass = $this->User->query("select password from ngdrstab_mst_user where username=?", array($get_username));
                            $current_password_db = $get_password;
                           
                            if (($hashed_current_password) == ($current_password_db)) {
                                $this->LoadModel('otpcitizen');
                                $textotp = $this->request->data['resetpassword_citizen']['otp'];
                                $textusername = $this->request->data['resetpassword_citizen']['username'];
                                //  pr($textusername);
                                $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));
                                // pr($lastotp);exit;
                                if (empty($lastotp)) {
                                    $this->Session->setFlash(__('Please Generate OTP!!!'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
                                if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                                    $this->Session->setFlash(__('OTP Does Not Match...'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
//pr($usertype);exit;
                                if ($usertype == 'C') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->CitizenUser->query("UPDATE ngdrstab_mst_user_citizen SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $new_changed_password1 = $this->CitizenUser->query("UPDATE ngdrstab_trn_usercitizen_registartion SET user_pass=?,updated=? where user_name=?", array($hashed_new_password, $today, $get_username));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
// $this->Session->setFlash(__('Password has Successfully changed..!!! Please login with new Password...!!!'));
                                    //$this->redirect(array('controller' => 'Users', 'action' => 'welcomenote'));
                                } elseif ($usertype == 'O') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->User->query("UPDATE ngdrstab_mst_user SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $new_changed_password1 = $this->User->query("UPDATE ngdrstab_mst_employee SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                                }


                                $this->Session->setFlash(__('Password updated sucessfully'));
                            } else {

                                $this->Session->setFlash(__('Current password not Matched'));
                            }
                        } else {

                            $this->Session->setFlash(__('new password and Confirmed passsword not matched'));
                        }
                    } else {
                        $this->Session->setFlash(__('username Not  Available'));
                    }
//                } else {
//                    $this->Session->setFlash(__('Error in form'));
                }
            }
            $this->set("aftervalidation", 'Y');
        } catch (exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
        $this->Session->write("salt", rand(111111, 999999));
    } 
    public function otpresetpasswordcitizen() {

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

            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 9)));
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

            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_citizen_otp(user_id,username,otp,created,state_id,req_ip,event_id)values(?,?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip,9));

            if (!empty($checkuser)) {
                if ($checkuser[0][0]['mobile_no']) {


                    if (!empty($event)) {
                        if ($event[0]['smsevent']['send_flag'] == 'Y') {

                            $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 9); echo 1;
                            exit;
                        }else{
                            echo 1;
                            exit;
                        }
                    }
                }
            }
        }else{
             echo 'Authentication failed';
            exit;
        }
        } else {
            echo 'Authentication failed';
            exit;
        }
    }
   




}
