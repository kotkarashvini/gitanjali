<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class UsersController extends AppController {

    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Users', 'action' => 'welcome'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'welcomenote'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authorize' => array('Controller')
    ));
//    public $components = array('RequestHandler', 'Security', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('mainlanguage');
        $this->Session->renew();
//        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0,$this->Auth->user('state_id')))));
//        $this->set('langaugelist', $langaugelist);
//        if ($this->Session->check('Auth.User')) {
//            $state_id = $this->Auth->user('state_id');
//            $langaugelist = $this->mainlanguage->query("select A.language_name,A.language_code from ngprtab_mst_language A 
//                inner join ngdrstab_conf_language B on B.language_id=A.id where B.state_id= $state_id");
//            $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('forgotpassword','checkusername', 'activate_biometric_user', 'ngdrsclient', 'checkpasswordauth', 'checkbiometricauth', 'role', 'delete_role', 'scannerclient', 'webcamclient', 'officedisplay', 'roledisplay', 'deactivate', 'login', 'otpsave', 'language', 'checkusercitizen', 'checkemailcitizen', 'checkmobilenocitizen', 'citizenlogin', 'biometriclogin', 'biometricregistration', 'langaugechange', 'empregistration', 'activate', 'checkcaptcha', 'checkemail', 'checkmobileno', 'checkuser', 'welcome', 'new_user', 'userpermission', 'assign_role', 'resetpassword', 'citizenregistration', 'termsandconditions', 'policies', 'accessabilitystmt', 'aboutus', 'contactus', 'feedback', 'help', 'sidemap', 'send_sms', 'secugenclient', 'normalappointment', 'get_available_appointment', 'appointment');
        $this->Auth->allow('langaugechange', 'otpsavecitizen', 'checkidproofcitizen', 'checkuidcitizen', 'forgotpassword', 'getIdentificationlist', 'get_validation_rule', 'checkusername', 'get_otp', 'ngdrsclient', 'role', 'delete_role', 'checkpasswordauth', 'checkbiometricauth', 'scannerclient', 'webcamclient', 'officedisplay', 'roledisplay', 'welcomenote', 'deactivate', 'login', 'otpsave', 'otpsavesro', 'language', 'checkusercitizen', 'checkemailcitizen', 'checkmobilenocitizen', 'citizenlogin', 'biometricregistration', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'activate', 'checkmobileno', 'get_taluka_name', 'get_division_name', 'citizenregistration', 'citizenregistration_mh', 'citizenregistration_ga', 'termsandconditions', 'policies', 'accessabilitystmt', 'aboutus', 'contactus', 'feedback', 'help', 'sidemap', 'send_sms', 'secugenclient', 'normalappointment', 'get_available_appointment', 'appointment', 'statedisplay', 'regoffice', 'feedback_details');
        // }
        $laug = $this->Session->read("sess_langauge");

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

        if (is_null($laug)) {
            $this->Session->write("sess_langauge", 'en');
        }
    }

//done by kalyani 11january 2018
    ////DONE BY KALYANI
    function checkuniqueemail() {
        // echo 'hi';exit;
        try {
            $this->autoRender = false;
            $this->loadModel('CitizenUser');
            $c = Sanitize::html($_POST['email']);
            $user_id = Sanitize::html($_POST['user_id']);


            $a = $this->CitizenUser->query("SELECT * FROM ngdrstab_mst_user_citizen WHERE 
             email_id='$c' and  user_id NOT IN ($user_id)");
            if (count($a) > 0) {
                echo 1;
            } else {
                echo 0;
            }

            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkuniquemobile() {
        try {
            $this->autoRender = false;
            $this->loadModel('CitizenUser');
            $c = Sanitize::html($_POST['mobile']);
            $user_id = Sanitize::html($_POST['user_id']);


            $a = $this->CitizenUser->query("SELECT * FROM ngdrstab_mst_user_citizen WHERE 
             mobile=$c and  user_id NOT IN ($user_id)");
            if (count($a) > 0) {
                echo 1;
            } else {
                echo 0;
            }

            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkuniqueuid() {
        try {
            $this->autoRender = false;
            $this->loadModel('CitizenUser');
            $c = Sanitize::html($_POST['uid']);
            $user_id = Sanitize::html($_POST['user_id']);
            $a = $this->CitizenUser->query("SELECT * FROM ngdrstab_mst_user_citizen WHERE 
             uid=$c and  user_id NOT IN ($user_id)");
            if (count($a) > 0) {
                echo 1;
            } else {
                echo 0;
            }

            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    /////////////////end///////////////////////////
    function checkusername() {
        try {
            $this->loadModel('CitizenUser');
            $c = $_POST['user_name'];
            $this->CitizenUser->findbyusername($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function langaugechange() {
        if (isset($_POST['language']) and isset($_POST['languagetext'])) {

            $language = $_POST['language'];
            $languagetext = $_POST['languagetext'];
            $this->Session->write("sess_langauge", $language);
            $this->Session->write('doc_lang', $language);
            $this->Session->write("sess_langaugetext", $languagetext);
            CakeSession::write('Config.language', $language);
            ClassRegistry::init('Formlabel')->updatepo();

            if ($this->referer() != '/') {
                echo $this->referer();
            }
        }
        //  echo $this->Session->read('sess_langauge');
        exit;
    }

    public function language() {

        $this->loadModel('mainlanguage');
//        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0,$this->Auth->user('state_id')))));
//        $this->set('langaugelist', $langaugelist);
        $this->autoRender = false;
        $state_id = $this->Auth->user('state_id');
        $role_id = $this->Auth->User('role_id');
        //pr($role_id);
        if ($role_id == '999922') {
            $langaugelist = $this->mainlanguage->query("select A.language_name,A.language_code from ngdrstab_mst_language A 
                inner join ngdrstab_conf_language B on B.language_id=A.id ");
            //pr($langaugelist);
        } else {
            $langaugelist = $this->mainlanguage->query("select A.language_name,A.language_code from ngdrstab_mst_language A 
                inner join ngdrstab_conf_language B on B.language_id=A.id ");
        }
        return $langaugelist;
    }

    public function DispalyState() {
        App::import('Vendor', 'Fpdf', array('file' => 'fpdf/fpdf.php'));
        $this->layout = 'pdf'; //this will use the pdf.ctp layout
        $this->response->type('pdf');
        $this->set('fpdf', new FPDF('P', 'mm', 'A4'));
        $this->loadModel('State');
        $schoolAdmDetails = $this->schoolDetails->find('all', array('conditions' => 'state_id'));
        $this->set('schoolAdmDetails', $schoolAdmDetails);
    }

    public function get_role() {
        try {
            if (isset($_GET['module_id'])) {
                $module_id = $_GET['module_id'];
                $lang = $this->Session->read("sess_langauge");
                $rolename = ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'conditions' => array('module_id' => array($module_id))));
                echo json_encode($rolename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    // Start Default footer links
    public function Disclaimer() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function termsandconditions() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function policies() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function accessabilitystmt() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function aboutus() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function help() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function contactus() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function feedback_old() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    public function feedback() {
        try {
            $this->loadModel('Feedback');
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
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
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);
            $fieldlist = array();
            $fieldlist['applicantname']['text'] = 'is_required,is_alphaspace';
            $fieldlist['email_id']['select'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian';
            $fieldlist['message']['text'] = 'is_required';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                //    $this->check_csrf_token($this->request->data['feedback']['csrftoken']);
                $this->request->data['feedback']['ip_address'] = $this->request->clientIp();
                $this->request->data['feedback']['created_date'] = $created_date;
                $this->request->data['feedback']['user_id'] = $user_id;
                if ($this->Feedback->save($this->request->data['feedback'])) {
                    $this->Session->setFlash(__('lblsavemsg'));
                    // $lastid = $this->Project->getLastInsertId();
                } else {
                    $this->Session->setFlash(__('lblnotsavemsg'));
                }
                return $this->redirect(array('controller' => 'Users', 'action' => 'feedback'));
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function sidemap() {
        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }
    }

    // End Default footer links

    public function welcomemodel() {
        $userflag = $this->Session->read("session_usertype");
        //check user is citizen or organization      
        $lang = $this->Session->read("sess_langauge");
        if ($userflag != 'C') {
            $userid = Sanitize::html($this->Session->read("session_user_id"));
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);

            $this->loadModel('getUserRole');
            //role module
//            $usermodules = ClassRegistry::init('getUserRole')->query("select distinct B.module_name_$lang,B.url,A.role_id,C.role_name_$lang from ngdrstab_mst_userroles A 
//             inner join ngdrstab_mst_module B on A.module_id=B.module_id 
//	 inner join ngdrstab_mst_role C on A.role_id=C.role_id	where A.user_id=? order by B.module_name_$lang", array($userid));
//only role
            $usermodules = ClassRegistry::init('getUserRole')->query("select distinct A.role_id,C.role_name_$lang from ngdrstab_mst_userroles A     
	 inner join ngdrstab_mst_role C on A.role_id=C.role_id	where A.user_id=?", array($userid));
//            pr($usermodules);exit;
            $this->set('usermodules', $usermodules);
            $this->Session->write("session_redirect", 'welcomemodel');
        } else {
            //citizenuser home  model
            $userid = Sanitize::html($this->Session->read("session_user_id"));
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $this->loadModel('getUserRolecitizen');
            $usermodules = ClassRegistry::init('getUserRolecitizen')->query("select distinct B.module_name_$lang,B.url,A.role_id,C.role_name_$lang from ngdrstab_mst_userroles_citizen A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id
                                                        inner join ngdrstab_mst_role C on A.role_id=C.role_id where A.user_id=? order by B.module_name_$lang", array($userid));
            $this->set('usermodules', $usermodules);
            $this->Session->write("session_redirect", 'welcomemodel');
        }
        $this->set("lang", $lang);

        $currentrole = $this->Session->read("session_role_id");
        if (is_numeric($currentrole))
            $currentrole = substr($currentrole, 4);
        $currentrole = substr($currentrole, 0, -4);

        $this->set("currentrole", $currentrole);
    }

    public function welcome($master_role_id = NULL) {

        try {

            if ($master_role_id != NULL) {
                $roleid_random1 = rand(1000, 9999);
                $roleid_random2 = rand(1000, 9999);
                $session_roleid = $roleid_random1 . $master_role_id . $roleid_random2;
                $this->Session->write("session_role_id", $session_roleid);
            }
            if ($this->Session->write('doc_lang') != '' && $this->Session->write('sess_langauge') != '') {
                $this->Session->write('doc_lang', 'en');
                $this->Session->write('sess_langauge', 'en');
            }
            //for updating PO File Labels
            ClassRegistry::init('Formlabel')->updatepo();
            $this->Session->write("session_redirect", 'welcome');
        } catch (Exception $e) {
            $this->Session->setFlash($e);
        }
    }

    public function welcomenote() {
        try {
            $this->layout = 'default_new';
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            $curdate = date('Y-m-d');
            $gen_info = ClassRegistry::init('genernalinfoentry')->find('all', array('conditions' => array('last_status_id' => 4, 'DATE(last_status_date)' => date('Y-m-d'))));
            $citizencount = ClassRegistry::init('citizenuserreg')->find('all', array('conditions' => array('DATE(user_creation_date)' => date('Y-m-d'))));
            $this->set('doccount', count($gen_info));
            $this->set('citizencount', count($citizencount));
            // ClassRegistry::init('ge')->
            if ($this->Session->write('doc_lang') != '' && $this->Session->write('sess_langauge') != '') {
                $this->Session->write('doc_lang', 'en');
                $this->Session->write('sess_langauge', 'en');
            }
        } catch (Exception $ex) {
            header('Location:../cterror.html');
            exit;
        }
    }

    public function get_captcha() {
        try {
            $this->autoRender = false;
            App::import('Component', 'Captcha');
            $validCharacters = "ABCDEFGHI123JKLMNPQR456STUVWXYZ789";
            $validCharNumber = strlen($validCharacters);
            $length = 6;
            $result = "";
            for ($i = 0; $i < $length; $i++) {
                $index = mt_rand(0, $validCharNumber - 1);
                $result .= $validCharacters[$index];
            }
            $random = $result;
            $this->Session->write('captcha_code', $random);
            $settings = array('characters' => $random, 'winHeight' => 40, 'winWidth' => 250, 'fontSize' => 20, 'fontPath' => WWW_ROOT . 'tahomabd.ttf', 'noiseColor' => '#ccc', 'bgColor' => '#fff', 'noiseLevel' => '100', 'textColor' => '#000');
            $img = $this->Captcha->ShowImage($settings);
            $this->set('random', $random);
            echo $img;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function change_password() {
        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            $this->loadModel('registration');
            $this->loadModel('User');
            $userid = $this->Session->read("session_user_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            if ($this->request->is('post')) {
                if (isset($this->request->data['change_password']['oldpassword']) && isset($this->request->data['change_password']['newpassword']) && isset($this->request->data['change_password']['cpassword'])) {
                    if ($this->request->data['change_password']['oldpassword'] != NULL && $this->request->data['change_password']['newpassword'] != NULL && $this->request->data['change_password']['cpassword'] != NULL) {
                        $userRecord = $this->User->find('all', array('conditions' => array('User.user_id' => $userid, 'User.password' => $this->request->data['change_password']['oldpassword'])));
                        if ($userRecord != NULL && $userRecord != "") {
                            if (Sanitize::html($this->request->data['change_password']['newpassword']) == Sanitize::html($this->request->data['change_password']['cpassword'])) {
                                $data = array('pwd' => $this->request->data['change_password']['newpassword']);
                                if (Sanitize::check($data)) {
                                    $regex = '/^(?=.*\d)[0-9A-Za-z!@#*]{8,}$/';
                                    if (preg_match($regex, Sanitize::html($this->request->data['change_password']['newpassword']))) {
                                        if ($this->registration->changePassword_model($this->request->data['change_password']['newpassword'], $userid)) {
                                            $this->Session->setFlash(__('Password change successfully'));
                                            $this->redirect(array('action' => 'welcomenote'));
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
                            $this->Session->setFlash(__('Old password does not match'));
                            $this->redirect(array('action' => 'change_password'));
                        }
                    } else {
                        $this->Session->setFlash(__('Please Enter Required Fields'));
                        $this->redirect(array('action' => 'change_password'));
                    }
                } else {
                    header('Location:../cterror.html');
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function checkpassword() {
        try {
            $password = Sanitize::html($_POST['pwd']);
            $userid = $this->Session->read("session_user_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $this->loadModel('registration');
            $this->registration->checkpassword_model($password, $userid);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function otpsavesro_old() {
        $this->LoadModel('otpcitizen');
        $username = $this->Auth->user('username');
        $session_usertype = $this->Session->read("session_usertype");
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->User->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user where username='" . $username . "'");

        if ($checkuser != Null) {
            // $otp = rand(10000000, 99999999);
            $otp = 12345678;
            $userid = $checkuser[0][0]['user_id'];
            $stateid = $checkuser[0][0]['state_id'];
            $createdate = $date;
            $rip = $req_ip;

            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_otp(user_id,username,otp,created,state_id,req_ip,user_type)values(?,?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip, $session_usertype));
        } else {
            $this->Session->setFlash(__('Invalid UserName'));
        }
    }

//        Genrate Otp and save with userid resetpassword.ctp
    public function otpsave() {

        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');
        $username = $this->request->data['username'];
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->CitizenUser->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user where username=?", array($username));

        if ($checkuser != Null) {
            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 8)));
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

            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip));

            if (!empty($checkuser)) {
                if ($checkuser[0][0]['mobile_no']) {


                    if (!empty($event)) {
                        if ($event[0]['smsevent']['send_flag'] == 'Y') {

                            $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 8);
                        }
                    }
                }
            }
        } else {
            $this->Session->setFlash(__('Invalid UserName'));
        }
    }

    //citizenlogin.ctp
    public function otpsavecitizen() {

        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');
        $username = $this->request->data['username'];
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->CitizenUser->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user_citizen where username=?", array($username));

        if ($checkuser != Null) {

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
                        }
                    }
                }
            }
        } else {
            echo 'Invalid UserName';
            exit;
        }
    }

    //resetpassword_citizen.ctp
    public function otpresetpasswordcitizen() {


        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');

        $username = $this->request->data['username'];

        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->CitizenUser->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user_citizen where username=?", array($username));

        if ($checkuser != Null) {
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

            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_citizen_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip));

            if (!empty($checkuser)) {
                if ($checkuser[0][0]['mobile_no']) {


                    if (!empty($event)) {
                        if ($event[0]['smsevent']['send_flag'] == 'Y') {

                            $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 9);
                        }
                    }
                }
            }
        } else {
            $this->Session->setFlash(__('Invalid UserName'));
        }
    }

    //biometric otp
    public function otpsavesro_old1() {

        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');
        $this->Auth->user('username');


        $userid = $this->Session->read("session_user_id");
        $result = substr($userid, 4);
        $userid = substr($result, 0, -4);

        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $checkuser = $this->CitizenUser->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user where user_id=?", array($userid));

        if ($checkuser != Null) {
            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 6)));
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
            $username = $checkuser[0][0]['username'];
            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip));
            // pr($otpsave);exit;
            if (!empty($checkuser)) {
                if ($checkuser[0][0]['mobile_no']) {


                    if (!empty($event)) {
                        if ($event[0]['smsevent']['send_flag'] == 'Y') {

                            $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 4);
                        }
                    }
                }
            }
        } else {
            $this->Session->setFlash(__('Invalid UserName'));
        }
    }

    //biometric otp
    public function otpsavesro() {

        $this->LoadModel('CitizenUser');
        $this->LoadModel('otpcitizen');
        $this->LoadModel('smsevent');
        $userid = $this->Session->read("session_user_id");
        $result = substr($userid, 4);
        $userid = substr($result, 0, -4);
        $sessionuser_id = $this->Auth->User('user_id');
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y/m/d');
        $time = date("H:i");
        $time = explode(":", date("H:i"));
        $req_ip = $_SERVER['REMOTE_ADDR'];

        $userdata = $this->CitizenUser->query("select username,mobile_no,state_id from ngdrstab_mst_user where user_id=?", array($sessionuser_id));
        $username = $userdata[0][0]['username'];

        $checkuser = $this->CitizenUser->query("select user_id,username,mobile_no,state_id from ngdrstab_mst_user where username=?", array($username));

        if ($checkuser != Null) {
            $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 6)));
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

            $otpsave = $this->otpcitizen->query('insert into ngdrstab_trn_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($userid, $username, $otp, $createdate, $stateid, $rip));

            if (!empty($checkuser)) {
                if ($checkuser[0][0]['mobile_no']) {


                    if (!empty($event)) {
                        if ($event[0]['smsevent']['send_flag'] == 'Y') {

                            $this->smssend(1, $checkuser[0][0]['mobile_no'], $otp, $checkuser[0][0]['user_id'], 6);
                        }
                    }
                }
            }
        } else {
            $this->Session->setFlash(__('Invalid UserName'));
        }
    }

    public function biometricotp() {
        $userid = $this->Session->read("session_user_id");
        $result = substr($userid, 4);
        $userid = substr($result, 0, -4);
        $username = $this->Auth->user('username');
        if ($this->request->is('post')) {
            $this->LoadModel('otpcitizen');
            $this->check_csrf_token($this->request->data['biometricotp']['csrftoken']);
            $textotp = $this->request->data['biometricotp']['otp'];

            $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_otp where username=? order by id desc ", array($username));
            if (empty($lastotp)) {
                $this->Session->setFlash(__('Please Genrate OTP!!!'));
                $this->redirect(array('controller' => 'Users', 'action' => 'biometricotp'));
            }
            if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                $this->Session->setFlash(__('OTP Does Not Match...'));
                $this->redirect(array('controller' => 'Users', 'action' => 'biometricotp'));
            }

            $this->set('count', 0);
            $lang = $this->Session->read("sess_langauge");
            $count = ClassRegistry::init('getUserRole')->query("select count(*) from (select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=" . $userid . " order by B.module_name_$lang) as role");




            if ($count[0][0]['count'] > 1) {
                $usermodules = ClassRegistry::init('getUserRole')->query("select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=" . $userid . " order by B.module_name_$lang");
                $this->Session->write("session_redirect", 'welcomemodel');
                $this->redirect(array('action' => 'welcomemodel'));
            } else {
                $this->Session->write("session_redirect", 'welcome');
                $this->redirect(array('action' => 'welcome'));
            }
        }
    }

    public function biometriclogin() {
        try {
//           pr($_SESSION);
//          exit;

            $this->loadModel('biometric');
            $this->loadModel('User');
            $this->loadModel('file_config');

//               $username = $this->Session->read("session_username");
//               pr($username);exit;
            $userid = $this->Session->read("session_user_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $this->set('actiontype', NULL);
            $this->set('cap', NULL);
            $this->set('checkflag', NULL);
            $userdata = $this->User->query("select count(*) from ngdrstab_mst_user_biometric where user_id=?", array($userid));
            $userdata = $userdata[0][0]['count'];
            $this->set('userdata', $userdata);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $bio_list = ClassRegistry::init('biometriclist')->find('list', array('fields' => array('biometric_id', 'biometric_name_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => 'biometric_name_' . $lang));
            $this->set('bio_list', $bio_list);
            $biometcount = $this->Session->read("biometcount");
            $this->set('biometcount', $biometcount);
            $check = $this->User->query("select biometric_registration_flag, biometric_capture_flag,server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            if ($check[0][0]['biometric_registration_flag'] == 'N' && $check[0][0]['biometric_capture_flag'] == 'N') {
                $this->Session->setFlash(__("Please Contact JDR for Biometric Registration...!!!"));
                $checkflag = 1;
                $this->set('checkflag', $checkflag);
            }
            $serverbioflag = $check[0][0]['server_biometric_flag'];

            $this->set('biometserverflag', $serverbioflag);



            if ($this->request->is('post')) {

                if (isset($_POST['cap'])) {
                    $results = $this->User->find('first', array('conditions' => array('User.user_id' => array($userid))));
                    $this->request->data['biometriclogin']['user_id'] = $this->Auth->User("user_id");
                    //  $this->request->data['biometriclogin']['created_date'] = date('Y/m/d');
                    $this->request->data['biometriclogin']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $this->request->data['biometriclogin']['state_id'] = $this->Auth->User("state_id");
                    $decryptcap = $this->decrypt($_POST['cap'], $this->Session->read("salt"));
//                $decryptcap = $this->decrypt($this->request->data['new_user']['password'], $this->Session->read("salt"));
                    $this->request->data['biometriclogin']['biometric_finger'] = $decryptcap;

                    $actiontype = $_POST['actiontype'];
                    $this->set('cap', $decryptcap);
                    $biometcount = $_POST['biometcount'];
                    if ($biometcount == NULL) {
                        $biometcount = 0;
                        $this->set('biometcount', $biometcount);
                    }

                    //for registration login
                    if ($actiontype == '1') {
//                    pr($this->request->data);exit;
                        if ($this->biometric->save($this->request->data['biometriclogin'])) {
                            $udateflag = $this->biometric->query("Update ngdrstab_mst_user set biometric_registration_flag=? where user_id=?", array('Y', $userid));
                            $this->Session->setFlash(__("Biometric Register Successfully"));
                            $this->Session->write("session_redirect", 'welcome');
                            $this->redirect(array('action' => 'welcome'));
                        }
                    }
                    //for login
                    if ($actiontype == '2') {
                        if ($results['User']['activeflag'] == 'N') {
                            $this->Session->setFlash(__('User not activated.'));
                        } else {
                            $biometcount = ++$biometcount;
                            $this->set('biometcount', $biometcount);
//                    pr($biometcount);exit;
                            if ($biometcount < 3) {
                                //pr("amar");exit;
                                $fingerprint = $decryptcap;
                                $employedata = $this->biometric->query("select biometric_finger from ngdrstab_mst_user_biometric where user_id=$userid");
                                $db_finger = $employedata[0][0]['biometric_finger'];
                                $path1 = $this->file_config->find('first', array('fields' => array('filepath')));

                                //pr($serverbioflag);exit;
                                if ($serverbioflag == 'N') {
                                    $path = $path1['file_config']['filepath'] . "Biometric/secugenMatch.jar";
                                    $message = exec('java -jar ' . $path . ' ' . $fingerprint . ' ' . $db_finger, $result);
                                    // pr($message);exit;
                                } else {
                                    $path = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/SecugenMatch_log.jar";
                                    $message = exec('/usr/java/jdk1.8.0_131/bin/java -Djava.library.path=/usr/local/lib -cp ".:FDxSDKPro.jar:commons-codec-1.7.jar" -jar ' . $path . ' ' . $fingerprint . ' ' . $db_finger, $result);
                                }
//                                         pr('java -Djava.library.path=/usr/local/lib -cp ".:FDxSDKPro.jar:commons-codec-1.7.jar" -jar ' . $path . ' ' . $fingerprint . ' ' . $db_finger);
                                //  pr($message);pr($result);exit;
//                                $message = 'Verification Success';
                                if ($message == 'Verification Success') {
                                    $this->Session->setFlash(__("Verification Successfully"));
                                    $this->set('count', 0);
                                    $lang = $this->Session->read("sess_langauge");
                                    $count = ClassRegistry::init('getUserRole')->query("select count(*) from (select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=" . $userid . " order by B.module_name_$lang) as role");
                                    $lang = $this->Session->read("sess_langauge");

                                    if ($count[0][0]['count'] > 1) {
                                        $usermodules = ClassRegistry::init('getUserRole')->query("select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=" . $userid . " order by B.module_name_$lang");

                                        $this->Session->write("session_redirect", 'welcomemodel');
                                        $this->redirect(array('action' => 'welcomemodel'));
                                    } else {
                                        $this->Session->write("session_redirect", 'welcome');
                                        $this->redirect(array('action' => 'welcome'));
                                    }
                                } else {

                                    $this->Session->setFlash(__("Verification Failed...Please rescan your fingure...!!!"));
                                    $this->Session->write("biometcount", $biometcount);
                                    $this->redirect(array('action' => 'biometriclogin'));
                                }
                            } else {
                                //otp
                                $this->redirect(array('action' => 'biometricotp'));


//                            $this->loadModel('User');
//                            $userid = $this->Session->read("session_user_id");
//                            $result = substr($userid, 4);
//                            $userid = substr($result, 0, -4);
//                            $this->User->updateLoginDetails($userid);
//                            $this->Session->destroy();
//                            $this->Session->delete('Controller.sessKey');
//                            $this->redirect($this->Auth->logout());
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash("Please Caputre Finger....!!!");
                }
            }
            $this->Session->write("salt", rand(111111, 999999));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function biometric_registration() {
        $this->loadModel('biometric');
        $this->loadModel('User');
        $userid = $this->Session->read("session_user_id");
        $result = substr($userid, 4);
        $userid = substr($result, 0, -4);
        $this->set('actiontype', NULL);
        $this->set('cap', NULL);
        $this->set('hfid', NULL);
        $office1 = $this->User->query("select office_id from ngdrstab_mst_user where user_id=? ", array($userid));
        $office_id = $office1[0][0]['office_id'];
        $office = $this->User->query("select a.reporting_office_id 
                                           from ngdrstab_mst_office a inner join ngdrstab_mst_office b on a.reporting_office_id=b.office_id");
        $officeid = array();
        foreach ($office as $value) {
            $data = $value[0]['reporting_office_id'];
            array_push($officeid, $data);
        }
        array_push($officeid, $office_id);
        $officeid = implode(',', $officeid);

        $emp = $this->User->query("select emp_id from ngdrstab_mst_emp_office_link where office_id in (?)", array($officeid));

        $officedetails = $this->User->query("select A.user_id,C.id,B.office_name_en,D.district_name_en,E.taluka_name_en from ngdrstab_mst_user A 
inner join ngdrstab_mst_office B on  A.office_id=B.office_id  
inner join ngdrstab_mst_user_biometric C on A.user_id=C.user_id
inner join ngdrstab_conf_admblock3_district D on D.district_id=B.district_id
inner join ngdrstab_conf_admblock5_taluka E on E.taluka_id=B.taluka_id 
left outer join ngdrstab_mst_emp_office_link F on F.office_id=B.office_id 
where capture_flag= ? and authenticat_flag=? and F.office_id in (?)", array('Y', 'Y', $officeid));

        $this->set('officedetails', $officedetails);

        if ($this->request->is('post')) {
            $this->request->data['biometric_registration']['reg_user_id'] = $this->Auth->User("user_id");
            $this->request->data['biometric_registration']['capture_date'] = date('Y/m/d');
            $this->request->data['biometric_registration']['authenticat_date'] = date('Y/m/d');
            // $this->request->data['biometric_registration']['created_date'] = date('Y/m/d');
            $this->request->data['biometric_registration']['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->request->data['biometric_registration']['state_id'] = $this->Auth->User("state_id");
            $this->request->data['biometric_registration']['biometric_finger'] = $_POST['cap'];

            $actiontype = $_POST['actiontype'];
            $this->set('cap', $_POST['cap']);

            if ($actiontype == '1') {

                $this->request->data['biometric_registration']['id'] = $this->request->data['hfid'];
                if ($this->biometric->save($this->request->data['biometric_registration'])) {
                    $this->Session->setFlash(__("Biometric Register Successfully"));
                    $this->Session->write("session_redirect", 'biometric_registration');
                    $this->redirect(array('action' => 'biometric_registration'));
                }
            }
        }
    }

    public function login() {
        try {
            $userid_random1 = rand(1000, 9999);
            $userid_random2 = rand(1000, 9999);
            $roleid_random1 = rand(1000, 9999);
            $roleid_random2 = rand(1000, 9999);
            $this->loadModel('NGDRSErrorCode');
            $captcha = $this->Session->read('captcha_code');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['username']['text'] = 'is_required,is_username';
            $fieldlist['password']['text'] = 'is_required';
//            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            // $fieldlist['csrftoken']['text'] = 'is_integer';
//            $fieldlist['hfSaltedStr']['text'] = 'is_integer';
            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            $this->loadModel('loginusers');

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

                $userid = $this->Session->read("session_user_id");
                $result = substr($userid, 4);
                $userid = substr($result, 0, -4);
                $this->User->updateLoginDetails($userid);
                $this->Session->destroy();
                $this->Session->delete('Controller.sessKey');
                $this->redirect($this->Auth->logout());
            } else {
                $this->loadModel('User');
                $this->User->create();


                if ($this->request->is('post')) {
                    $mysalt = $this->Session->read("mysalt");

                    if (strcmp($this->request->data['User']['hfSaltedStr'], $mysalt) != 0) {
                        $this->Session->setFlash(__('Authentication Failed'));
                        $this->redirect(array('controller' => 'User', 'action' => 'login'));
                    }
                }


                $saltNew = Security::saltNew();
                @$saltstr = Sanitize::html($this->request->data['User']['hfSaltedStr']);
                $this->Session->write("session_salt", @$saltstr);
                $this->Session->write("mysalt", $saltNew);
                $this->set('saltstring', $saltNew);
//                $saltNew = rand(1111, 9999);
//                @$saltstr = $this->request->data['User']['hfSaltedStr'];
//                $this->Session->write("session_salt", @$saltstr);
//                $this->set('saltstring', $saltNew);

                @$logincount = Sanitize::html($this->request->data['User']['hfLoginCount']);
                if ($logincount == NULL) {
                    $logincount = 0;
                    $this->set('logincount1', $logincount);
                }

                if ($this->request->is('post') && isset($this->request->data['User']['csrftoken'])) {

                    //pr($this->request->data);exit;
                    $this->check_csrf_token($this->request->data['User']['csrftoken']);
                    $this->request->data['User'] = $this->istrim($this->request->data['User']);
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

                        @$user_name = Sanitize::html($this->request->data['User']['username']);
                        @$password123 = Sanitize::html($this->request->data['User']['password']);
                        $data2 = array('username' => $user_name, 'password' => $password123);
                        $results = $this->User->find('first', array('conditions' => array('User.username' => array($user_name))));
                        // pr($results);exit;

                        if ($results == NULL) {
                            $this->Session->setFlash(__('Invalid username or password'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        }

                        $rld = $results['User']['role_id'];
                        if ($rld == '100001') {
                            //pr($rld);exit;
                            $formdt = $this->request->data;
                            $this->login_conf($results, $logincount, $formdt);
                            exit;
                        } else {
                            @$login_flag = $results['User']['first_time_pwd'];
                            @$user_id = $results['User']['user_id'];
                            $logincount = ++$logincount;
                            $this->set('logincount1', $logincount);
                            //activeflag flag check
                            if ($results['User']['activeflag'] == 'N') {
                                $this->Session->setFlash(__('User not activated.'));
                            } else {
                                if ($captcha == $this->request->data['User']['captcha']) {
                                    if ($logincount <= 3) {

                                        // SHAJI ADDED CODE FOR THE OFFICE TIMING CHECK [13-APRIL-2020] START
//                                        $CurrentTime = date("H:i:s");
//                                        $OfficeShift_result = $this->loginusers->query("select * from ngdrstab_mst_officeshifttime where user_id=$user_id ");
//                                         if (!empty($OfficeShift_result)) {
//                                         $from_time = $OfficeShift_result[0][0]['from_time'];
//                                         $to_time = $OfficeShift_result[0][0]['to_time'];
//                                         
//                                         if($CurrentTime > $from_time && $CurrentTime <= $to_time)
//                                         {
//                                          pr('on time'); exit;
                                        // testing
//                                         $LocalIPAddress = $this->request->ClientIp();
//                                         pr($LocalIPAddress); exit;
                                        // testing

                                        if ($this->Auth->login()) {
                                            if (isset($new_login_Time)) {
                                                $time = date("H:i:s");
                                                $date = date('Y-m-d');
                                                // pr($time); pr($date); pr($user_id); 
                                                $this->loginusers->query("update ngdrstab_mst_loginusers set login_status=?,logouttime=?, login_status_id=? where user_id=? and logindate=?", array('Logged out successfully', $time, 0, $user_id, $date));
                                                //pr($update);exit;
                                            }
                                            $this->load_alert_msgs();
//                                        $this->Session->write("session_usertype", @$organization);
                                            $organization = 'O';
                                            $this->Session->write("session_usertype", @$organization);

                                            $this->Session->renew();
                                            $_SESSION["token"] = md5(uniqid(mt_rand(), true));
                                            Cache::clear();
                                            clearCache();
                                            $session_userid = $userid_random1 . $user_id . $userid_random2;
                                            $this->Session->write("session_user_id", $session_userid);
                                            $userid = $this->Session->read("session_user_id");
                                            $result = substr($userid, 4);
                                            $userid = substr($result, 0, -4);
                                            $session_id = $this->Auth->User('user_id');
                                            $role_id = $this->Auth->User('role_id');
                                            //user role from database
                                            $this->loadModel('getUserRole');
                                            $UserRole = $this->getUserRole->find('first', array('conditions' => array('getUserRole.role_id' => $role_id)));
                                            @$role_id = Sanitize::html($UserRole['getUserRole']['role_id']);
                                            $session_roleid = $roleid_random1 . @$role_id . $roleid_random2;
                                            $this->Session->write("session_role_id", $session_roleid);
                                            $this->Session->write("session_id", $session_id);
                                            $this->Cookie->write('userid', $userid);
                                            //Module ID
                                            @$module_id = Sanitize::html($results['User']['module_id']);
                                            $this->Session->write("session_module_id", @$module_id);
                                            $this->User->insertLoginDetails($this->Auth->user('user_id'));
                                            $date = date('Y-m-d');
                                            $login_id_result = $this->loginusers->query("select id from ngdrstab_mst_loginusers where user_id=$user_id and logindate='$date' and login_status_id=1 order by id");
                                            $login_id = $login_id_result[0][0]['id'];
                                            $this->Session->write("login_id", $login_id);
                                            $this->Session->write("sess_lan_both", 'N');
                                            $this->loadModel('language');
                                            $this->loadModel('mainlanguage');
                                            $local_langauge = $this->mainlanguage->find('first', array('conditions' => array('state_id' => $this->Auth->user('state_id'))));
                                            // pr($local_langauge);
                                            //$this->Session->write("local_langauge", $local_langauge['mainlanguage']['language_code']);
                                            $this->Session->write("local_langauge", "en");
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
                                                $this->Session->write('doc_lang', $local_langauge['language']['language_code']);
                                            } else
                                            if ($lang_both == 1) {
                                                $this->Session->write("sess_lan_both", 'Y');
                                                $this->Session->write("sess_langauge", 'en');
                                                $this->Session->write('doc_lang', 'en');
                                            }

                                            //check Login AuthenticationType ie Biomatric ,only Password 
                                            $authtype = $this->Auth->user('authetication_type');
//                                    $biometricflag = $this->Auth->user('biometric_registration_flag');
                                            //concat string $authtype.$biometricflag '1N' then check
                                            //
                                            switch ($authtype) {
                                                case 1:
                                                    //pass parameter userid
                                                    $this->Session->write('2n_login_status', 'Y');
                                                    $this->checkpasswordauth($userid);
                                                    //   $this->redirect(array('action' => 'checkpasswordauth', $userid));
                                                    break;
                                                case 2:
                                                    $this->Session->write('2n_login_status', 'N');
                                                    $this->checkbiometricauth();
                                                    //$this->redirect(array('action' => 'checkbiometricauth'));
                                                    break;

                                                case 3:
                                                    $this->Session->write('2n_login_status', 'N');
                                                    $this->Session->write("session_redirect", 'welcomemodel');
                                                    $this->redirect(array('action' => 'biometricotp'));
                                                    break;
                                            }

//                                            switch ($authtype) {
//                                                case ($authtype == '1'):
//                                                    $this->checkpasswordauth($userid);
//                                                    break;
//                                                case ($authtype == '2'):
//                                                    $this->checkbiometricauth();
//                                                    break;
//                                            }
                                        } else {
                                            $this->User->insertUnsuccessfulLogin($user_id);
                                            Cache::clear();
                                            clearCache();

                                            $this->Session->setFlash(__('Invalid username or password'));
                                        }
//                                         }
//                                         else
//                                         {
//                                          $this->Session->setFlash(__('You can not login outside office shift time.'));
//                                        $this->redirect(array('controller' => 'Users', 'action' => 'login'));
//                                         }
//                                         
//                                         }
                                        // SHAJI ADDED CODE FOR THE OFFICE TIMING CHECK [13-APRIL-2020] END
//                                    $date = date('Y-m-d');
//                                    $count_select = $this->loginusers->query("select count(id) from ngdrstab_mst_loginusers where user_id=$user_id and logindate='$date' and login_status_id=1");
//                                    $login_count = $count_select[0][0]['count'];
//                                    $login_result = $this->loginusers->query("select * from ngdrstab_mst_loginusers where user_id=$user_id and logindate='$date' and login_status_id=1 order by id");
//                                    if (!empty($login_result)) {
//                                        $login_time = $login_result[0][0]['logintime'];
//                                        $time = strtotime($login_time);
//                                        $new_login_Time = date("H:i:s", strtotime('+2 minutes', $time));
//                                        $time = date("H:i:s");
//                                        $date = date('Y-m-d');
//                                        $sr_no = $login_result[0][0]['sr_no'];
//                                        //pr($login_time); pr($new_login_Time); pr($time); exit;
//                                    }
//                                    if ($login_count == 1 && $time <= $new_login_Time) {
//                                        Cache::clear();
//                                        clearCache();
//                                        $this->Session->setFlash(__('You can not login multiple times'));
//                                        $this->redirect(array('controller' => 'Users', 'action' => 'login'));
//                                    } else {
                                        //}
                                    } else {
                                        $this->Session->renew();
                                        Cache::clear();
                                        clearCache();
                                    }
                                } else {
                                    $this->User->insertUnsuccessfulLogin($user_id);
                                    if ($logincount <= 3) {
                                        $this->Session->setFlash(__('The captcha code you entered does not match.Please try again.'));
                                    } else {
                                        $this->Session->setFlash(__('You Are Temporarily Blocked,Please Try After Some Time!!!'));
                                    }
                                }
                            }
                            //////////////////////////////////////////
                        }

//                                } else {
//                                    $this->Session->renew();
//                                    Cache::clear();
//                                    clearCache();
//                                }
//                            }
//                        } else {
//                            $this->Session->renew();
//                            Cache::clear();
//                            clearCache();
//                        }
                    }
                }$this->set('logincount1', $logincount);
                $this->set("aftervalidation", 'Y');
            }

            $this->set_csrf_token();
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function login_conf($results, $logincount, $formdt) {
        //echo 'sdfsdf';exit;
        $userid_random1 = rand(1000, 9999);
        $userid_random2 = rand(1000, 9999);
        $roleid_random1 = rand(1000, 9999);
        $roleid_random2 = rand(1000, 9999);
        $captcha = $this->Session->read('captcha_code');
        $this->loadModel('User');
        $this->loadModel('loginusers');
        $this->loadModel('getUserRole');
        //pr($results);

        @$login_flag = $results['User']['first_time_pwd'];
        @$user_id = $results['User']['user_id'];
        $logincount = ++$logincount;
        $this->set('logincount1', $logincount);
        //activeflag flag check
        if ($results['User']['activeflag'] == 'N') {
            $this->Session->setFlash(__('User not activated.'));
        } else {
            if ($captcha == $formdt['User']['captcha']) {
                if ($logincount <= 3) {

                    if ($this->Auth->login()) {
                        if (isset($new_login_Time)) {
                            $time = date("H:i:s");
                            $date = date('Y-m-d');
                            // pr($time); pr($date); pr($user_id); 
                            $this->loginusers->query("update ngdrstab_mst_loginusers set login_status=?,logouttime=?, login_status_id=? where user_id=? and logindate=?", array('Logged out successfully', $time, 0, $user_id, $date));
                            //pr($update);exit;
                        }
                        $this->load_alert_msgs();

                        $organization = 'O';
                        $this->Session->write("session_usertype", @$organization);

                        $this->Session->renew();
                        $_SESSION["token"] = md5(uniqid(mt_rand(), true));
                        Cache::clear();
                        clearCache();
                        $session_userid = $userid_random1 . $user_id . $userid_random2;
                        $this->Session->write("session_user_id", $session_userid);
                        $userid = $this->Session->read("session_user_id");
                        $result = substr($userid, 4);
                        $userid = substr($result, 0, -4);
                        $session_id = $this->Auth->User('user_id');
                        $role_id = $this->Auth->User('role_id');
                        //user role from database
                        $this->loadModel('getUserRole');
                        $UserRole = $this->getUserRole->find('first', array('conditions' => array('getUserRole.role_id' => $role_id)));
                        @$role_id = Sanitize::html($UserRole['getUserRole']['role_id']);
                        $session_roleid = $roleid_random1 . @$role_id . $roleid_random2;
                        $this->Session->write("session_role_id", $session_roleid);
                        $this->Session->write("session_id", $session_id);
                        $this->Cookie->write('userid', $userid);
                        //Module ID
                        @$module_id = Sanitize::html($results['User']['module_id']);
                        $this->Session->write("session_module_id", @$module_id);
                        $this->User->insertLoginDetails($this->Auth->user('user_id'));
                        $date = date('Y-m-d');
                        $login_id_result = $this->loginusers->query("select id from ngdrstab_mst_loginusers where user_id=$user_id and logindate='$date' and login_status_id=1 order by id");
                        $login_id = $login_id_result[0][0]['id'];
                        $this->Session->write("login_id", $login_id);
                        $this->Session->write("sess_lan_both", 'N');
                        $this->loadModel('language');
                        $this->loadModel('mainlanguage');
                        // $local_langauge = $this->mainlanguage->find('first', array('conditions' => array('state_id' => $this->Auth->user('state_id'))));
                        $this->Session->write("local_langauge", "en");
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
                            $this->Session->write('doc_lang', $local_langauge['language']['language_code']);
                        } else
                        if ($lang_both == 1) {
                            $this->Session->write("sess_lan_both", 'Y');
                            $this->Session->write("sess_langauge", 'en');
                            $this->Session->write('doc_lang', 'en');
                        }

                        //check Login AuthenticationType ie Biomatric ,only Password 
                        $authtype = $this->Auth->user('authetication_type');
//                                    $biometricflag = $this->Auth->user('biometric_registration_flag');
                        //concat string $authtype.$biometricflag '1N' then check
                        switch ($authtype) {
                            case ($authtype == '1'):
                                //pass parameter userid
                                $this->checkpasswordauth($userid);

                                break;
                            case ($authtype == '2'):
                                $this->checkbiometricauth();

                                break;
                        }
                    } else {
                        $this->User->insertUnsuccessfulLogin($user_id);
                        Cache::clear();
                        clearCache();

                        $this->Session->setFlash(__('Invalid username or password'));
                    }
                } else {
                    $this->Session->renew();
                    Cache::clear();
                    clearCache();
                }
            } else {
                $this->User->insertUnsuccessfulLogin($user_id);
                if ($logincount <= 3) {
                    $this->Session->setFlash(__('The captcha code you entered does not match.Please try again.'));
                } else {
                    $this->Session->setFlash(__('You Are Temporarily Blocked,Please Try After Some Time!!!'));
                }
            }
        }
    }

    public function logout($checkcsrf) {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if (strcmp($checkcsrf, $_SESSION['csrfoutkey']) !== 0) {
                $_SESSION['csrfoutkey'] = NULL;
                return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
            }

            $login_id = $this->Session->read("login_id");

            $orguser = $this->Session->read("session_orguser");

            if ($orguser != 'O') {

                if (isset($_SESSION["token"])) {
                    $this->Session->delete('appreferer');
                    $this->loadModel('User');
                    $this->User->updateLoginDetails($this->Auth->user('user_id'), $login_id);
                    $flag = 'logout';
                    $this->Session->write('logoutflag', $flag);
                    $this->redirect($this->Auth->logout());
                } else {
                    header('Location:../cterror.html');
                    exit;
                }
            } else {

                if (isset($_SESSION["token"])) {
                    $this->Session->delete('appreferer');
                    $this->loadModel('User');
                    $this->loadModel('CitizenUser');

                    $this->CitizenUser->updateLoginDetails($this->Auth->user('user_id'));
                    $flag = 'logout';
                    $this->Session->write('logoutflag', $flag);
                    $this->redirect($this->Auth->logout());
                } else {
                    header('Location:../cterror.html');
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function csrftrial() {
        
    }

    function checkcaptcha() {
        try {
            if ($this->Session->read('captcha_code') == $_POST['cp']) {
                echo 1;
            } else {
                echo 0;
            }
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function biometricpopup() {
        $this->autoLayout = false;
    }

    public function new_user($id = NULL) {
        try {


            $this->check_role_escalation();
            $this->loadModel('User');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            // $this->set('Empcode', ClassRegistry::init('employee')->find('list', array('fields' => array('emp_code', 'name'),'order' => array('emp_code' => 'ASC'))));
            $this->set('corp_coun', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('id', 'class_description_' . $laug), 'order' => array('class_description_' . $laug => 'ASC'))));
            $this->set('type', ClassRegistry::init('authenticate_type')->find('list', array('fields' => array('user_auth_type_id', 'auth_type_desc_' . $laug), 'order' => array('auth_type_desc_' . $laug => 'ASC'))));
            $this->set('role', ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_' . $laug), 'order' => array('role_name_' . $laug => 'ASC'))));
            // $this->set('user', ClassRegistry::init('User')->find('all'));
            $this->set('officedec', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC'))));
            //$this->set('user', NULL);
            $userrecord = $this->User->query("SELECT u.user_id,u.employee_id,u.username,u.full_name,u.mobile_no,u.email_id,u.authetication_type FROM ngdrstab_mst_employee as e
LEFT JOIN ngdrstab_mst_user as u on e.emp_code=u.employee_id where u.employee_id IS NOT NULL");
            $this->set('userrecord', $userrecord);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $this->loadModel('NGDRSErrorCode');
            if ($id == NULL) {
                $id = '';
                $this->set('id', $id);
            } else {
                $this->set('id', $id);
            }

            //$Empcode='';
            $this->set('Empcode', NULL);
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
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);




            $this->set("fieldlist", $fieldlist = $this->User->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
//                $password = $this->request->data['new_user']['password'];
//                $roldpassword = $this->request->data['new_user']['username'];
////                 
                //$this->request->data['new_user']['password'] = $this->decrypt($this->request->data['new_user']['password'], $this->Session->read("salt"));
                $this->request->data['new_user']['username'] = $this->decrypt($this->request->data['new_user']['username'], $this->Session->read("salt"));
                //$this->request->data['new_user']['r_password'] = $this->decrypt($this->request->data['new_user']['r_password'], $this->Session->read("salt"));
                //  $this->check_csrf_token($this->request->data['new_user']['csrftoken']);
                //$errarr = $this->validatedata($this->request->data['new_user'], $fieldlist);
                $verrors = $this->validatedata($this->request->data['new_user'], $fieldlist);

//                  pr($verrors);exit;
//                $newpassword = sha1($this->request->data['new_user']['password']);
//                $rpassword = sha1($this->request->data['new_user']['r_password']);
                //sha256
                $newpassword = $this->request->data['new_user']['password'];
                $rpassword = $this->request->data['new_user']['r_password'];
//pr($verrors);exit;
                if ($this->validationError($verrors)) {
//                    if ($this->request->data['new_user']['authetication_type'] == 2) {
//                        $biomatricflag = 'Y';
//                    } else {
//                        $biomatricflag = 'N';
//                    }
                    $duplicate = $this->User->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['new_user']);

                    if ($checkd) {
                        if ($newpassword === $rpassword) {

                            if (isset($this->request->data['biometric_capture_flag'])) {
                                $capflag = $this->request->data['biometric_capture_flag'];
                            } else {
                                $capflag = 'N';
                            }

                            $data = array(
                                'username' => $this->request->data['new_user']['username'],
                                'password' => $newpassword,
                                'office_id' => $this->request->data['new_user']['office_id'],
                                // 'corp_coun_id' => $this->request->data['new_user']['corp_coun_id'],
                                'authetication_type' => $this->request->data['new_user']['authetication_type'],
                                'employee_id' => $this->request->data['new_user']['employee_id'],
                                'full_name' => $this->request->data['new_user']['full_name'],
                                'mobile_no' => $this->request->data['new_user']['mobile_no'],
                                'email_id' => $this->request->data['new_user']['email_id'],
                                'role_id' => $this->request->data['new_user']['role_id'],
                                'state_id' => $this->Auth->User("state_id"),
                                'biometric_capture_flag' => $capflag
                            );
                            if (isset($this->request->data['new_user']['user_id'])) {
                                $data['user_id'] = $this->request->data['new_user']['user_id'];
                                $flag = 'updated';
                            } else {
                                $flag = 'created';
                            }
                            $this->request->data['User'] = $this->istrim($this->request->data['new_user']);
                            $data = $this->encode_special_char($data);

                            $employee_idexit = $this->request->data['new_user']['employee_id'];
                            //pr($this->request->data['new_user']['user_id']);exit;
                            if (!isset($this->request->data['new_user']['user_id'])) {
                                $count = $this->User->query("select count(*) from ngdrstab_mst_user where employee_id='$employee_idexit'");
                                if ($count[0][0]['count'] > 0) {
                                    $this->Session->setFlash(__('User Already Exist.'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'new_user'));
                                }
                            }
                            if ($this->User->save($data)) {

                                $this->Session->setFlash(__('User ' . $flag . '  successfully'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'new_user'));
                            } else {
                                $this->Session->setFlash(__('The user could not be created. Please, try again.'));
                            }
                        } else {
                            $this->Session->setFlash(__('Password Did not Match'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                        unset($this->request->data['new_user']['password']);
                        unset($this->request->data['new_user']['r_password']);
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                    unset($this->request->data['new_user']['password']);
                    unset($this->request->data['new_user']['r_password']);
                }
            }

            $this->set("aftervalidation", 'Y');

//            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (is_numeric($id)) {
                $data = $this->User->find('all', array('conditions' => array('user_id' => $id)));
                $this->request->data['new_user'] = $data[0]['User'];

                $office = $this->User->query("select office_id from ngdrstab_mst_user where user_id=$id");
                $office_id = $office[0][0]['office_id'];
                $Empcode = ClassRegistry::init('employee')->find('list', array('fields' => array('emp_code', 'name'), 'conditions' => array('office_id' => array($office_id)), 'order' => array('emp_code' => 'ASC')));
                $this->set('Empcode', $Empcode);




                unset($this->request->data['new_user']['password']);
                unset($this->request->data['new_user']['r_password']);
            }

            $this->set('user', ClassRegistry::init('User')->find('all'));
            $this->set_csrf_token();
            $this->Session->write("salt", rand(111111, 999999));
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_new_user($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('User');

            if (isset($id) && is_numeric($id)) {

                $this->User->user_id = $id;

                if ($this->User->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'new_user'));
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function get_employeedetail() {
        try {
//echo $_GET['office'];
            $this->autorender = false;
            if (isset($_POST['emp_code'])) {
                $emp_code = $_POST['emp_code'];

                $employeedeatails = ClassRegistry::init('employee')->find('first', array('fields' => array('emp_fname', 'emp_mname', 'emp_lname', 'contact_no', 'email_id'), 'conditions' => array('emp_code' => array($emp_code))));
                echo json_encode($employeedeatails['employee'], true);
                exit;
            }
//            else  if (isset($_GET['office'])) {
//                $office_id=$_GET['office'];
//                $employee_list=$this->set('Empcode', ClassRegistry::init('employee')->find('list', array('fields' => array('emp_code', 'name'), 'conditions' => array('office_id' => $office_id), 'order' => array('emp_code' => 'ASC'))));
//            echo $employee_list;
//                 echo json_encode($employee_list);
//                exit;
//            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function get_employeelist() {
        try {
//echo $_POST['office'];
            $this->autorender = false;
            if (isset($_POST['office'])) {
                $office_id = $_POST['office'];
//                  $districtname = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $lang), 'conditions' => array('state_id' => array($state))));
                $Empcode = ClassRegistry::init('employee')->find('list', array('fields' => array('emp_code', 'name'), 'conditions' => array('office_id' => array($office_id)), 'order' => array('emp_code' => 'ASC')));
//            echo  $Empcode;
                echo json_encode($Empcode);
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function new_user21june($id = NULL) {
        try {
            //$this->check_role_escalation();
            $this->loadModel('User');
            $lang = $this->Session->read("sess_langauge");
            $this->set('corp_coun', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('id', 'class_description_en'), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('type', ClassRegistry::init('authenticate_type')->find('list', array('fields' => array('user_auth_type_id', 'auth_type_desc'), 'order' => array('auth_type_desc' => 'ASC'))));
            $this->set('role', ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_name_' . $lang => 'ASC'))));
            $this->set('user', ClassRegistry::init('User')->find('all'));
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $fieldlist = array();
            $fieldlist['corp_coun_id']['select'] = 'is_select_req';
            $fieldlist['authetication_type']['select'] = 'is_select_req';
            $fieldlist['username']['text'] = 'is_required,is_alphanumeric,is_maxlength50';
            $fieldlist['password']['text'] = 'is_required,is_password';
            $fieldlist['r_password']['text'] = 'is_required,is_password';
            $fieldlist['employee_id']['text'] = 'is_required,is_integer,is_maxlength100';
            $fieldlist['full_name']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian';
            $fieldlist['email_id']['text'] = 'is_email,is_maxlength100';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['new_user']['csrftoken']);
                $errarr = $this->validatedata($this->request->data['new_user'], $fieldlist);
                $newpassword = sha1($this->request->data['new_user']['password']);
                $rpassword = sha1($this->request->data['new_user']['r_password']);
                if ($this->validationError($errarr)) {
                    if ($this->request->data['new_user']['authetication_type'] == 2) {
                        $biomatricflag = 'Y';
                    } else {
                        $biomatricflag = 'N';
                    }

                    if ($newpassword === $rpassword) {
                        $data = array(
                            'username' => $this->request->data['new_user']['username'],
                            'password' => $newpassword,
                            'corp_coun_id' => $this->request->data['new_user']['corp_coun_id'],
                            'authetication_type' => $this->request->data['new_user']['authetication_type'],
                            'employee_id' => $this->request->data['new_user']['employee_id'],
                            'full_name' => $this->request->data['new_user']['full_name'],
                            'mobile_no' => $this->request->data['new_user']['mobile_no'],
                            'email_id' => $this->request->data['new_user']['email_id'],
                            'role_id' => $this->request->data['new_user']['role_id'],
                            'state_id' => $this->Auth->User("state_id"),
                            'biometric_registration_flag' => $biomatricflag,
                        );
                        if (isset($this->request->data['new_user']['user_id'])) {
                            $data['user_id'] = $this->request->data['new_user']['user_id'];
                        }
                        $this->request->data['User'] = $this->istrim($this->request->data['new_user']);
                        $data = $this->encode_special_char($data);
                        if ($this->User->save($data)) {

                            $this->Session->setFlash(__('lblsavemsg'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'new_user'));
                        } else {
                            $this->Session->setFlash(__('The user could not be created. Please, try again.'));
                        }
                    } else {
                        $this->Session->setFlash(__('Password Did not Match'));
                    }
                }
            }
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (is_numeric($id)) {
                $data = $this->User->find('all', array('conditions' => array('user_id' => $id)));
                $this->request->data['new_user'] = $data[0]['User'];
                unset($this->request->data['new_user']['password']);
            }

            $this->set('user', ClassRegistry::init('User')->find('all'));
            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function assign_role($userroles_id = null) {

        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('office', 'module', 'User', 'role', 'getUserRole'));

            //$this->set('module', ClassRegistry::init('module')->find('list', array('fields' => array('module_id', 'module_name'), 'order' => array('module_name' => 'ASC'))));
            $this->set('office', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC'))));
            $this->set('role', ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_en'), 'order' => array('role_name_en' => 'ASC'))));

            $id = $this->Auth->user('id');
            @$user_id = $this->request->data['assign_role']['user_id'];
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->set('user_id', null);

//            $userRoledata = $this->circle->query("select * from ngdrstab_mst_userroles");
//            $this->set('userRoledata', $userRoledata);

            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $fieldlist = array();
            $fieldlist['user_id']['select'] = 'is_select_req';
            $fieldlist['role_id']['select'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->check_csrf_token($this->request->data['assign_role']['csrftoken']);

                $errarr = $this->validatedata($this->request->data['assign_role'], $fieldlist);

                if ($this->validationError($errarr)) {
                    $actiontype = $_POST['actiontype'];

                    if ($actiontype != '1') {

                        $role_id = $this->request->data['assign_role']['role_id'];
                        $uname = ClassRegistry::init('User')->find('all', array('fields' => array('username'), 'conditions' => array('user_id' => array($user_id))));

//                        $roledata = array(
//                            'user_id' => $user_id,
//                            'role_id' => $role_id,
//                            'username' => $uname[0]['User']['username'],
//                            //'module_id' => $this->request->data['assign_role']['module_id'],
//                        );

                        $roledata = $this->request->data['assign_role'];
                        //pr($roledata);exit;
                        $userrole = ClassRegistry::init('getUserRole')->find('all', array('conditions' => array('user_id' => $user_id, 'role_id' => $role_id)));

                        if ($userrole == NULL) {
                            if (Sanitize::check($roledata)) {
                                $this->loadModel('getUserRole');
                                $data = $this->encode_special_char($roledata);


                                if ($this->getUserRole->save($data)) {

                                    $this->Session->setFlash(__('lblsavemsg'));
                                } else {
                                    $this->Session->setFlash(__('The role could not be assigned. Please, try again.'));
                                }
                            }
                        } else {
                            $this->Session->setFlash(__('All Ready Existing Record not assigned. Please, try again.'));
                        }
                    }
                }
                $this->set_csrf_token();
//                $this->set('role', NULL);
            }

            $this->loadModel('getUserRole');

            $result = $this->getUserRole->query("select off.office_id, u.user_id, off.office_name_en, u.id,  u.role_id ,r.role_name_en ,e.username,u.userroles_id from ngdrstab_mst_userroles as u ,
            ngdrstab_mst_role r,ngdrstab_mst_user e, ngdrstab_mst_office off where r.role_id=u.role_id and e.user_id=u.user_id and off.office_id = e.office_id order by u.userroles_id");

            $this->set('user', $result);

            if (!is_null($userroles_id) && is_numeric($userroles_id)) {

                $result = $this->getUserRole->query("select off.office_id, u.user_id, off.office_name_en, u.id,  u.role_id ,r.role_name_en ,e.username,u.userroles_id from ngdrstab_mst_userroles as u ,
                    ngdrstab_mst_role r,ngdrstab_mst_user e, 
                    ngdrstab_mst_office off where r.role_id=u.role_id 
                    and e.user_id=u.user_id and off.office_id = e.office_id and u.userroles_id=?", array($userroles_id));


                //$result=$this->getUserRole->find("first",array('conditions'=>array('userroles_id'=>$userroles_id)));
                //  $this->request->data['assign_role']=$result['getUserRole'];
                $this->request->data['assign_role'] = $result[0][0];
                //  pr($result);exit;

                $office = $this->office->find('list', array('fields' => array('office.office_id', 'office_name_en')));
                $this->set('office', $office);

                $user_id = $this->User->find('list', array('fields' => array('user_id', 'username'), 'conditions' => array('user_id' => $result[0][0]['user_id']), 'order' => array('user_id' => 'ASC')));
                $this->set('user_id', $user_id);


                $role_id = $this->role->find('list', array('fields' => array('role_id', 'role_name_en'), 'order' => array('role_id' => 'ASC')));
                $this->set('role', $role_id);
            }
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_assign_role($userroles_id = null) {
        $this->autoRender = false;
        $this->loadModel('getUserRole');
        try {

            if (isset($userroles_id) && is_numeric($userroles_id)) {
                $this->getUserRole->userroles_id = $userroles_id;
                $e = $this->getUserRole->Query("delete from ngdrstab_mst_userroles where userroles_id=$userroles_id");
                if ($e == null) {

                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('action' => 'assign_role'));
                }
            }
        } catch (exception $ex) {
            
        }
    }

    public function get_officeuser() {
        try {
            if (isset($_GET['office_id'])) {
                $office_id = $_GET['office_id'];

                $username = ClassRegistry::init('User')->find('list', array('fields' => array('user_id', 'username'), 'conditions' => array('office_id' => array($office_id))));
                echo json_encode($username);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    //State admin change password to sro user only 
    public function resetpasswordstate() {
        try {

            $this->loadModel('User');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            //$usertype = $this->Session->read("session_usertype");
            $user_id = $this->Auth->user('user_id');
            $user = " ";
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->loadModel('mainlanguage');
            $fieldlist = array();
            $fieldlist['username']['text'] = 'is_username';
            //$fieldlist['password1']['text'] = 'is_password';
            $fieldlist['newpassword']['text'] = 'is_password';
            $fieldlist['rpassword']['text'] = 'is_password';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['resetpasswordstate']['csrftoken']);

                $username = $this->request->data['resetpasswordstate']['username'];
//                pr($username);exit;
                $user = $this->User->query("select user_id,username,password,mobile_no,state_id,role_id from ngdrstab_mst_user where username='" . $username . "'");
                //}
                if (!empty($user)) {
                    $role_changepwd = $this->User->query("select role_id,resetpwd_flag from ngdrstab_mst_role_changepwd where role_id='" . $user[0][0]['role_id'] . "'");

                    if ($role_changepwd[0][0]['resetpwd_flag'] != 'Y') {
                        $this->Session->setFlash(__('You dont have change the password to this role..!!'));
                        $this->redirect(array('controller' => 'Users', 'action' => 'resetpasswordstate'));
                    }
                }

                if (empty($user)) {
                    $this->Session->setFlash(__('User NOt Found..!!'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpasswordstate'));
                }
                $get_username = $user[0][0]['username'];
                // $get_password = $user[0][0]['password'];
                //  pr($get_username);exit;
                //$this->set('user', $get_username);
                //$this->request->data['resetpasswordstate']['password1'] = $this->decrypt($this->request->data['resetpasswordstate']['password1'], $this->Session->read("salt"));
                $this->request->data['resetpasswordstate']['newpassword'] = $this->decrypt($this->request->data['resetpasswordstate']['newpassword'], $this->Session->read("salt"));
                $this->request->data['resetpasswordstate']['rpassword'] = $this->decrypt($this->request->data['resetpasswordstate']['rpassword'], $this->Session->read("salt"));
                $this->request->data['resetpasswordstate'] = $this->istrim($this->request->data['resetpasswordstate']);
                $errarr = $this->validatedata($this->request->data['resetpasswordstate'], $fieldlist);
                if ($this->validationError($errarr)) {
                    // $current_password = $this->request->data['resetpasswordstate']['password1'];
                    //$hashed_current_password = sha1($current_password);
                    $new_password = $this->request->data['resetpasswordstate']['newpassword'];
                    $hashed_new_password = sha1($new_password);
                    $new_confirm_password = $this->request->data['resetpasswordstate']['rpassword'];
                    $hashed_new_confirmed_password = sha1($new_confirm_password);
                    $check_username = $this->User->query("select username from ngdrstab_mst_user where username=?", array($get_username));
//                    $check_username_value = $get_username;
//                    if (($check_username_value) == ($get_username)) {
                    if (($hashed_new_password) == ($hashed_new_confirmed_password)) {


                        $new_changed_password = $this->User->query("UPDATE ngdrstab_mst_user SET password=? where username=?", array($hashed_new_password, $get_username));
                        $new_changed_password1 = $this->User->query("UPDATE ngdrstab_mst_employee SET password=? where username=?", array($hashed_new_password, $get_username));
                        $this->Session->setFlash(__('lbleditmsg'));
                        $this->redirect(array('controller' => 'Users', 'action' => 'resetpasswordstate'));
                    } else {

                        $this->Session->setFlash(__('New password and Confirmed passsword not matched'));
                    }
                } else {
                    $this->Session->setFlash(__('Error in form'));
                }
            }
            $this->set("aftervalidation", 'Y');
        } catch (exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
        $this->Session->write("salt", rand(111111, 999999));
    }

    //end

    public function resetpassword_old() {
        try {

            $this->loadModel('User');
            $this->loadModel('CitizenUser');
            $this->loadModel('otpcitizen');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $usertype = $this->Session->read("session_usertype");
            $user_id = $this->Auth->user('user_id');
            $user = " ";
            if ($usertype == 'C') {
                $user = $this->CitizenUser->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user_citizen where user_id=$user_id");
            } elseif ($usertype == 'O') {
                $user = $this->User->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user where user_id=$user_id");
            }
            if (empty($user)) {
                $this->Session->setFlash(__('User Not Found!!'));
                $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
            }
            $get_username = $user[0][0]['username'];
            $get_password = $user[0][0]['password'];
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
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['resetpassword']['csrftoken']);

                $this->request->data['resetpassword']['password1'] = $this->decrypt($this->request->data['resetpassword']['password1'], $this->Session->read("salt"));
                $this->request->data['resetpassword']['newpassword'] = $this->decrypt($this->request->data['resetpassword']['newpassword'], $this->Session->read("salt"));
                $this->request->data['resetpassword']['rpassword'] = $this->decrypt($this->request->data['resetpassword']['rpassword'], $this->Session->read("salt"));
                $this->request->data['resetpassword'] = $this->istrim($this->request->data['resetpassword']);
                $errarr = $this->validatedata($this->request->data['resetpassword'], $fieldlist);
                if ($this->validationError($errarr)) {

                    $current_password = $this->request->data['resetpassword']['password1'];
                    $hashed_current_password = sha1($current_password);
                    $new_password = $this->request->data['resetpassword']['newpassword'];
                    $hashed_new_password = sha1($new_password);
                    $new_confirm_password = $this->request->data['resetpassword']['rpassword'];
                    $hashed_new_confirmed_password = sha1($new_confirm_password);
                    // $check_username = $this->User->query("select username from ngdrstab_mst_user where username=?", array($get_username));
                    $check_username_value = $get_username;
                    if (($check_username_value) == ($get_username)) {
                        if (($hashed_new_password) == ($hashed_new_confirmed_password)) {
                            //  $get_pass = $this->User->query("select password from ngdrstab_mst_user where username=?", array($get_username));
                            $current_password_db = $get_password;
                            if (($hashed_current_password) == ($current_password_db)) {
                                $this->LoadModel('otpcitizen');
                                $textotp = $this->request->data['resetpassword']['otp'];
                                $textusername = $this->request->data['resetpassword']['username'];
                                $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_otp where username=? order by id desc ", array($textusername));
                                if (empty($lastotp)) {
                                    $this->Session->setFlash(__('Please Generate OTP!!!'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
                                if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                                    $this->Session->setFlash(__('OTP Does Not Match...'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }

                                if ($usertype == 'C') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->CitizenUser->query("UPDATE ngdrstab_mst_user_citizen SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $new_changed_password1 = $this->CitizenUser->query("UPDATE ngdrstab_trn_usercitizen_registartion SET user_pass=?,updated=? where user_name=?", array($hashed_new_password, $today, $get_username));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                } elseif ($usertype == 'O') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->User->query("UPDATE ngdrstab_mst_user SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $new_changed_password1 = $this->User->query("UPDATE ngdrstab_mst_employee SET password=?,updated=? where username=?", array($hashed_new_password, $today, $get_username));
                                    $this->Session->setFlash(__('Password has Successfully changed..!!! Please login with new Password...!!!'));
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
                } else {
                    $this->Session->setFlash(__('Error in form'));
                }
            }
            $this->set("aftervalidation", 'Y');
        } catch (exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
        $this->Session->write("salt", rand(111111, 999999));
    }

    public function resetpassword() {
        try {
            $this->check_role_escalation();
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
            if ($this->request->is('post')) {
//                     pr($this->request->data['resetpassword']);//exit;//username
                $this->check_csrf_token($this->request->data['resetpassword']['csrftoken']);

//                $this->request->data['resetpassword']['password1'] = $this->decrypt($this->request->data['resetpassword']['password1'], $this->Session->read("salt"));
//                $this->request->data['resetpassword']['newpassword'] = $this->decrypt($this->request->data['resetpassword']['newpassword'], $this->Session->read("salt"));
//                $this->request->data['resetpassword']['rpassword'] = $this->decrypt($this->request->data['resetpassword']['rpassword'], $this->Session->read("salt"));
//                $this->request->data['resetpassword'] = $this->istrim($this->request->data['resetpassword']);


                $errarr = $this->validatedata($this->request->data['resetpassword'], $fieldlist);
                if ($this->validationError($errarr)) {


                    $current_password = $this->request->data['resetpassword']['password1'];
                    $new_password = $this->request->data['resetpassword']['newpassword'];
                    $new_confirm_password = $this->request->data['resetpassword']['rpassword'];

                    $check_username_value = $get_username;
                    if ($check_username_value == $get_username) {
                        if ($new_password == $new_confirm_password) {
                            $current_password_db = $get_password;
                            //  pr($current_password);
                            // pr($current_password_db);
                            //  exit;
                            if ($current_password == $current_password_db) {
                                $this->LoadModel('otpcitizen');
                                $textotp = $this->request->data['resetpassword']['otp'];
                                $textusername = $this->request->data['resetpassword']['username'];
//                         pr($textusername);exit;
                                $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_otp where username=? order by id desc ", array($textusername));
//                        pr($textusername);exit;
                                if (empty($lastotp)) {
                                    $this->Session->setFlash(__('Please Generate OTP!!!'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
                                if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                                    $this->Session->setFlash(__('OTP Does Not Match...'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }

                                if ($usertype == 'C') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->CitizenUser->query("UPDATE ngdrstab_mst_user_citizen SET password=?,updated=? where username=?", array($new_password, $today, $get_username));
                                    $new_changed_password1 = $this->CitizenUser->query("UPDATE ngdrstab_trn_usercitizen_registartion SET user_pass=?,updated=? where user_name=?", array($new_password, $today, $get_username));
                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                                } elseif ($usertype == 'O') {
                                    $today = date("Y-m-d");
                                    $new_changed_password = $this->User->query("UPDATE ngdrstab_mst_user SET password=?,updated=? where username=?", array($new_password, $today, $get_username));
                                    $new_changed_password1 = $this->User->query("UPDATE ngdrstab_mst_employee SET password=?,updated=? where username=?", array($new_password, $today, $get_username));
                                    $this->Session->setFlash(__('Password has Successfully changed..!!! Please login with new Password...!!!'));
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
                } else {
                    $this->Session->setFlash(__('Error in form'));
                }
            }


            $saltNew = Security::saltNew();
            $this->Session->write("session_salt", @$saltNew);


            $this->set("aftervalidation", 'Y');
        } catch (exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
        $this->Session->write("salt", rand(111111, 999999));
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
            $user = " ";
            if ($usertype == 'C') {
                $user = $this->CitizenUser->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user_citizen where user_id=$user_id");
            } elseif ($usertype == 'O') {
                $user = $this->User->query("select user_id,username,password,mobile_no,state_id from ngdrstab_mst_user where user_id=$user_id");
            }
            if (empty($user)) {
                $this->Session->setFlash(__('User NOt Found!!'));
                $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
            }
            $get_username = $user[0][0]['username'];
            $get_password = $user[0][0]['password'];
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


            if ($this->request->is('post')) {
                //$this->check_csrf_token($this->request->data['resetpassword_citizen']['csrftoken']);

                $this->request->data['resetpassword_citizen']['password1'] = $this->decrypt($this->request->data['resetpassword_citizen']['password1'], $this->Session->read("salt"));
                $this->request->data['resetpassword_citizen']['newpassword'] = $this->decrypt($this->request->data['resetpassword_citizen']['newpassword'], $this->Session->read("salt"));
                $this->request->data['resetpassword_citizen']['rpassword'] = $this->decrypt($this->request->data['resetpassword_citizen']['rpassword'], $this->Session->read("salt"));
                $this->request->data['resetpassword_citizen'] = $this->istrim($this->request->data['resetpassword_citizen']);
                $errarr = $this->validatedata($this->request->data['resetpassword_citizen'], $fieldlist);
                if ($this->validationError($errarr)) {

                    $current_password = $this->request->data['resetpassword_citizen']['password1'];
                    $hashed_current_password = sha1($current_password);
                    $new_password = $this->request->data['resetpassword_citizen']['newpassword'];
                    $hashed_new_password = sha1($new_password);
                    $new_confirm_password = $this->request->data['resetpassword_citizen']['rpassword'];
                    $hashed_new_confirmed_password = sha1($new_confirm_password);
                    // $check_username = $this->User->query("select username from ngdrstab_mst_user where username=?", array($get_username));
                    $check_username_value = $get_username;
                    if (($check_username_value) == ($get_username)) {
                        if (($hashed_new_password) == ($hashed_new_confirmed_password)) {
                            //  $get_pass = $this->User->query("select password from ngdrstab_mst_user where username=?", array($get_username));
                            $current_password_db = $get_password;
                            if (($hashed_current_password) == ($current_password_db)) {
                                $this->LoadModel('otpcitizen');
                                $textotp = $this->request->data['resetpassword_citizen']['otp'];
                                $textusername = $this->request->data['resetpassword_citizen']['username'];
                                $lastotp = $this->otpcitizen->query("select * from ngdrstab_trn_citizen_otp where username=? order by id desc ", array($textusername));
                                if (empty($lastotp)) {
                                    $this->Session->setFlash(__('Please Generate OTP!!!'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
                                if (strcmp($lastotp[0][0]['otp'], $textotp)) {
                                    $this->Session->setFlash(__('OTP Does Not Match...'));
                                    $this->redirect(array('controller' => 'Users', 'action' => 'resetpassword'));
                                }
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
                } else {
                    $this->Session->setFlash(__('Error in form'));
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

    function checkusername1() {
        try {

            $this->loadModel('User');
            $c = $_POST['user_name'];
            $this->User->findbyusername($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function userpermission_old() {
        try {
            $this->check_role_escalation();
            $this->loadModel('User');
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('ngprforms');
            $lang = $this->Session->read("sess_langauge");
            $this->set('module', ClassRegistry::init('module')->find('list', array('fields' => array('module_id', 'module_name_' . $lang), 'order' => array('module_name_' . $lang => 'ASC'))));
            $this->set('role', $this->role->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_name_' . $lang => 'ASC'))));
            $this->set('formlist', NULL);
            $this->set('actiontypeval', 0);
            $id = $this->Auth->user('id');
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist['module_id']['select'] = 'is_select_req';
            $fieldlist['role_id']['select'] = 'is_select_req';

            //  $fieldlist['']['checkbox']='is_digit';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                //Here there no need of trimming
                $this->check_csrf_token($this->request->data['userpermission']['csrftoken']);
                // $this->request->data['userpermission'] = $this->istrim($this->request->data['userpermission']);
                $errarr = $this->validatedata($this->request->data['userpermission'], $fieldlist);
//                $errarrecncode = $this->encode_special_char($this->request->data['userpermission']);
                if ($this->validationError($errarr)) {
                    $actiontype = $_POST['actiontype'];
                    $moduleid = $this->request->data['userpermission']['module_id'];
                    $roleid = $this->request->data['userpermission']['role_id'];
                    if ($actiontype == '1') {
                        $this->set('actiontypeval', $actiontype);
                        $this->set('role', $this->role->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_name_' . $lang => 'ASC'), 'conditions' => array('module_id' => $moduleid))));
                        $ngprformlist = $this->ngprforms->find('list', array('fields' => array('ngprforms.id', 'ngprforms.name_en'), 'order' => 'ngprforms.name_en'));
                        $this->set('ngprformlist', $ngprformlist);
                        $query = $this->userpermissions->query("delete from ngdrstab_mst_userpermissions where role_id=?", array($roleid));
                        foreach ($this->request->data['userpermission']['formlist'] as $form) {
                            $query1 = $this->userpermissions->query("insert into ngdrstab_mst_userpermissions(role_id,menu_id,submenu_id,subsubmenu_id,user_id) select ?,main_menu_id,id,99,1 from ngdrstab_mst_submenu where id=?", array($roleid, $form));
                            $this->Session->setFlash(__('Save userpermission successfully'));
                        }
                        $permissions = $this->userpermissions->find('all', array('conditions' => array('role_id' => $roleid)));
                        $permissionarray = array();
                        foreach ($permissions as $permission) {
                            array_push($permissionarray, $permission['userpermissions']['submenu_id']);
                        }
                        $this->set('permissionarray', $permissionarray);
                    }
                    if ($actiontype == '2') {
                        $this->set('actiontypeval', $actiontype);
                        $this->set('role', $this->role->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_id' => 'ASC'), 'conditions' => array('module_id' => $moduleid))));
                    }
                    if ($actiontype == '3') {
                        $this->set('actiontypeval', $actiontype);
                        $this->set('role', $this->role->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_id' => 'ASC'), 'conditions' => array('module_id' => $moduleid))));
                        $this->set('ngprformlist', $ngprformlist = $this->ngprforms->find('list', array('fields' => array('ngprforms.id', 'ngprforms.name_en'), 'order' => 'ngprforms.name_en')));
                        $permissions = $this->userpermissions->find('all', array('conditions' => array('role_id' => $roleid)));

                        $permissionarray = array();
                        foreach ($permissions as $permission) {
                            array_push($permissionarray, $permission['userpermissions']['submenu_id']);
                        }
                        $this->set('permissionarray', $permissionarray);
                    }
                }
                $this->set_csrf_token();
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function userpermission() {
        try {
            $this->check_role_escalation();
            $this->loadModel('User');
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('ngprforms');
            $lang = $this->Session->read("sess_langauge");
            $this->set('module', ClassRegistry::init('module')->find('list', array('fields' => array('module_id', 'module_name_' . $lang), 'order' => array('module_name_' . $lang => 'ASC'))));
            $this->set('role', $this->role->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'order' => array('role_name_' . $lang => 'ASC'))));
            $this->set('formlist', NULL);
            $this->set('actiontypeval', 0);
            $id = $this->Auth->user('id');
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);


            $this->set("fieldlist", $fieldlist = $this->userpermissions->fieldlist());
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                //Here there no need of trimming
                $this->check_csrf_token($this->request->data['userpermission']['csrftoken']);
                $errarr = $this->validatedata($this->request->data['userpermission'], $fieldlist);
                if ($this->validationError($errarr)) {


                    // pr($this->request->data);exit;
                    if (isset($this->request->data['btnsearch'])) {
                        $modulefunction = $this->userpermissions->query("select 
COALESCE(mainmenu.id,0) as mainmenu_id,
mainmenu.name_en as mainmenu_name,
COALESCE( submenu.id, 0 ) as submenu_id,
submenu.name_en as submenu_name,
COALESCE(subsubmenu.id,0) as subsubmenu_id,
subsubmenu.name_en as subsubmenu_name
FROM ngdrstab_mst_menu as mainmenu
LEFT JOIN   ngdrstab_mst_submenu as submenu ON submenu.main_menu_id=mainmenu.id
LEFT JOIN   ngdrstab_mst_subsubmenu as subsubmenu ON subsubmenu.sub_menu_id=submenu.id
order by mainmenu_name,submenu_name,subsubmenu_name
");
                        $this->set("modulefunction", $modulefunction);

                        $module_id = $this->request->data['userpermission']['module_id'];
                        $this->set("module_id", $module_id);

                        $rolepermissions = $this->userpermissions->find("all", array('conditions' => array('role_id' => $this->request->data['userpermission']['role_id'], 'module_id' => $this->request->data['userpermission']['module_id'])));
                        //pr($rolepermissions);exit;
//                        $rolepermissions = $this->userpermissions->find("all", array('conditions' => array('role_id' => $this->request->data['userpermission']['role_id'])));

                        $this->set("rolepermissions", $rolepermissions);
                    } else if (isset($this->request->data['btnSave'])) {
                        $this->userpermissions->deleteAll(array('role_id' => $this->request->data['userpermission']['role_id'], 'module_id' => $this->request->data['userpermission']['module_id']));
                        if (isset($this->request->data['menus']) && is_array($this->request->data['menus'])) {

                            foreach ($this->request->data['menus'] as $key => $value) {
                                $data = array();
                                $menu_arr = explode("_", $value);
                                $data['role_id'] = $this->request->data['userpermission']['role_id'];
                                $data['module_id'] = $this->request->data['userpermission']['module_id'];
                                $data['menu_id'] = $menu_arr[0];
                                $data['submenu_id'] = $menu_arr[1];
                                $data['subsubmenu_id'] = $menu_arr[2];
                                $data['modulepermission_id'] = $menu_arr[3];

                                $this->userpermissions->create();
                                $this->userpermissions->save($data);
                                $this->Session->setFlash(__('lblsavemsg'));
                                // pr($data);
                            }
                        } else {
                            $this->Session->setFlash(__('lblsavemsg'));
                        }
                    }
                }
                $this->set_csrf_token();
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_in_modulepermission($module_id, $main_menu_id, $sub_menu_id, $subsub_menu_id) {
        $this->loadModel('ModulePermissions');
        if ($module_id != 0) {
            $result = $this->ModulePermissions->find("first", array('conditions' => array('module_id' => $module_id, 'menu_id' => $main_menu_id, 'submenu_id' => $sub_menu_id, 'subsubmenu_id' => $subsub_menu_id)));
        } else {
            $result = $this->ModulePermissions->find("first", array('conditions' => array('menu_id' => $main_menu_id, 'submenu_id' => $sub_menu_id, 'subsubmenu_id' => $subsub_menu_id)));
        }
        return $result;
    }

    public function empregistration() {
        try {
            if ($this->referer() != '' && $this->referer() != '/') {

                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            $this->loadModel('emptype');
            $emptype = $this->emptype->find('list', array('fields' => array('id', 'emp_type'), 'order' => array('emp_type' => 'ASC')));
            $this->set('emptype', $emptype);
            $this->loadModel('State');
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_en'), 'order' => array('state_name_en' => 'ASC')));
            $this->set('State', $State);
            $this->loadModel('division');
            $division = $this->division->find('list', array('fields' => array('id', 'division_name_en'), 'order' => array('division_name_en' => 'ASC')));
            $this->set('division', $division);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_en'), 'order' => array('district_name_en' => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_en'), 'order' => array('taluka_name_en' => 'ASC')));
            $this->set('taluka', $taluka);
            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('type_id', 'type_desc'), 'order' => array('type_desc' => 'ASC')));
            $this->set('idtype', $idtype);
            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_en'), 'order' => array('questions_en' => 'ASC')));
            // pr($hintquestion);exit;

            $this->set('hintquestion', $hintquestion);
            $captcha = $this->Session->read('captcha_code');
            $this->loadModel('empregistration');
            $this->loadModel('User');

            if ($this->request->is('post')) {
                $email = str_replace("@", "[at]", Sanitize::html($this->request->data['empregistration']['email_id']));
                $newpassword = Sanitize::html($this->request->data['empregistration']['user_pass']);
                if ($newpassword === Sanitize::html($this->request->data['empregistration']['re_user_pass'])) {
                    
                } else {
                    $this->Session->setFlash(__('Password Did not Match'));
                }
                if ($captcha == Sanitize::html($this->request->data['empregistration']['captcha'])) {
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date('Y/m/d');
                    $time = date("H:i");
                    $time = explode(":", date("H:i"));
                    $req_ip = $_SERVER['REMOTE_ADDR'];
                    if ($this->request->data['empregistration']['id_type'] == 'empty') {
                        $this->request->data['empregistration']['id_type'] = '';
                        $this->request->data['empregistration']['pan_no'] = '';
                    }
                    $this->request->data['empregistration']['req_ip'] = $req_ip;
                    $this->request->data['empregistration']['user_creation_date'] = $date;
                    $this->request->data['empregistration']['req_time_hr'] = $time[0];
                    $this->request->data['empregistration']['req_time_min'] = $time[1];
                    $this->request->data['User']['username'] = $this->request->data['empregistration']['user_name'];
                    $this->request->data['User']['password'] = $this->request->data['empregistration']['user_pass'];
                    $this->request->data['User']['full_name'] = $this->request->data['empregistration']['contact_fname'];
                    $this->request->data['User']['mobile_no'] = $this->request->data['empregistration']['mobile_no'];
                    $this->request->data['User']['email_id'] = $this->request->data['empregistration']['email_id'];
                    //$this->request->data['User']['created_date'] = $date;
                    $this->request->data['User']['req_ip'] = $req_ip;
                    if ($this->empregistration->save($this->request->data)) {
                        if ($this->User->save($this->request->data)) {
                            $this->Session->setFlash(__('Registration Successful.'));
                        }
                    } else {
                        $this->Session->setFlash(__('Registration unuccessful.'));
                    }
                } else {
                    $this->Session->setFlash(__('The captcha code you entered does not match. Please try again.'));
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    // Start Citizen Registration Common
    public function citizenregistration_sha1() {

        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../notfound.ctp');
                    exit;
                }
            }

            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            $laug = $this->Session->read("sess_langauge");
            if ($laug == NULL) {
                $this->Session->write("sess_langauge", 'en');
            }
            $laug = $this->Session->read("sess_langauge");

            $this->set('laug', $laug);
            $this->loadModel('emptype');
            $this->loadModel('reg_type');
            $reg_type = $this->reg_type->find('list', array('fields' => array('id', 'type_name_' . $laug), 'conditions' => array('display_flag' => 'Y'), 'order' => array('id' => 'ASC')));
            $this->set('reg_type', $reg_type);
            $emptype = $this->emptype->find('list', array('fields' => array('id', 'emp_type'), 'order' => array('id' => 'ASC'), 'conditions' => array('id' => 2), 'order' => array('id' => 'ASC')));
            $this->set('emptype', $emptype);
            $this->loadModel('State');
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_' . $laug), 'order' => array('state_name_' . $laug => 'ASC')));
            $this->set('State', $State);
            $this->loadModel('division');
            $division = $this->division->find('list', array('fields' => array('id', 'division_name_' . $laug), 'order' => array('division_name_' . $laug => 'ASC')));
            $this->set('division', $division);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $laug), 'order' => array('taluka_name_' . $laug => 'ASC')));
            $this->set('taluka', $taluka);
            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $laug), 'conditions' => array('filter_flag' => 'I', 'reg_display_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $laug => 'ASC')));
            $this->set('idtype', $idtype);
            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_' . $laug), 'order' => array('questions_' . $laug => 'ASC')));
            $this->set('hintquestion', $hintquestion);
            $captcha = $this->Session->read('captcha_code');
            $this->loadModel('citizenuserreg');
            $this->loadModel('CitizenUser');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $allrule = $this->NGDRSErrorCode->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
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
            $fieldlist['citizen_type']['radio'] = 'is_i_n';
            $fieldlist['contact_fname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['contact_mname']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['contact_lname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
//            $fieldlist['buildername']['text'] = 'is_select_req';
//            $fieldlist['builderadd']['text'] = 'is_select_req';
//            $fieldlist['bankname']['text'] = 'is_select_req';
//            $fieldlist['bankadd']['text'] = 'is_select_req';
//            $fieldlist['ifsc']['text'] = 'is_select_req';
            $fieldlist['building']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['street']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['city']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['pincode']['text'] = 'is_pincode_empty';
            $fieldlist['state_id']['text'] = 'is_select_req';
//            $fieldlist['division_id']['text'] = '';
            $fieldlist['district_id']['text'] = 'is_select_req';
            $fieldlist['taluka_id']['text'] = 'is_select_req';
            $fieldlist['office_id']['text'] = 'is_select_req';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['id_type']['select'] = 'is_select_req';
//            $fieldlist['pan_no']['text'] = 'is_required,is_pancard';
//            $fieldlist['firmdetails']['text'] = 'is_required';
//            $fieldlist['authperson']['text'] = 'is_required';
            $fieldlist['uid']['text'] = 'is_required,is_uidnum';
            $fieldlist['user_name']['text'] = 'is_required,is_username,is_maxlength50';
            $fieldlist['user_pass']['text'] = 'is_required,is_password';
            $fieldlist['re_user_pass']['text'] = 'is_required,is_password';
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $fieldlist['hint_question']['select'] = 'is_required,is_select_req';
            $fieldlist['hint_answer']['text'] = 'is_required,is_alphanumspace,is_maxlength255';
            $fieldlist['address']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['licence_no']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['name_of_bar']['text'] = 'is_required,is_alphanumspace';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);

            $this->set("aftervalidation", 'Y');
            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['citizenregistration']['csrftoken']);

                if ($this->request->data['citizenregistration']['reg_type'] == 6) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_company' => 'Y')));
                    $validcount = $this->check_company_reg_count();
                    $is_company = 'Y';
                } else {
                    $is_company = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 5) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_bank' => 'Y')));
                    $validcount = $this->check_bank_reg_count();
                    $is_bank = 'Y';
                } else {
                    $is_bank = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 4) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_builder' => 'Y')));
                    $validcount = $this->check_builder_reg_count();
                    $is_builder = 'Y';
                } else {
                    $is_builder = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'Y')));
                    $validcount = $this->check_advocater_reg_count();
                    $is_advocate = 'Y';
                } else {
                    $is_advocate = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'deed_writer' => 'Y')));
                    $validcount = $this->check_deedwriter_reg_count();
                    $deed_writer = 'Y';
                } else {
                    $deed_writer = 'N';
                }
                if ($this->request->data['citizenregistration']['reg_type'] == 1) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'N', 'deed_writer' => 'N')));
                    $validcount = $this->check_citizen_reg_count();
                }

                if (!empty($totalreg)) {
                    if ($validcount < count($totalreg)) {
                        $this->redirect(array('controller' => 'Users', 'action' => 'welcomenote'));
                    }
                }
                $this->request->data['citizenregistration']['user_pass'] = $this->decrypt($this->request->data['citizenregistration']['user_pass'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['user_name'] = $this->decrypt($this->request->data['citizenregistration']['user_name'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['re_user_pass'] = $this->decrypt($this->request->data['citizenregistration']['re_user_pass'], $this->Session->read("salt"));


//                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlist);
//                $flag = 0;
//                foreach ($errarr as $dd) {
//                    if ($dd != "") {
//                        $flag = 1;
//                    }
//                }
////                pr($this->request->data['citizenregistration']);
////                exit;
//                if ($flag == 1) {
//                    $this->set("errarr", $errarr);
//                } else {
                //------------------------------------------ Server side validation-----------------------------------------------------------
                $this->request->data['citizenregistration'] = $this->istrim($this->request->data['citizenregistration']);
                $fieldlistnew = $this->modifycitizenregfieldlist($fieldlist, $this->request->data['citizenregistration']);

                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlistnew);

//pr($errarr);exit;

                if ($this->ValidationError($errarr)) {
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date('Y/m/d');
                    $time = date("H:i");
                    $time = explode(":", date("H:i"));
                    $req_ip = $_SERVER['REMOTE_ADDR'];
                    if ($this->request->data['citizenregistration']['id_type'] == 'empty') {
                        $this->request->data['citizenregistration']['id_type'] = '';
                        $this->request->data['citizenregistration']['pan_no'] = '';
                    }
                    $this->request->data['citizenregistration']['req_ip'] = $req_ip;
                    $this->request->data['citizenregistration']['user_creation_date'] = $date;
                    $this->request->data['citizenregistration']['req_time_hr'] = $time[0];
                    $this->request->data['citizenregistration']['req_time_min'] = $time[1];
                    $username = $this->request->data['citizenregistration']['user_name'];
                    $userpass = $this->request->data['citizenregistration']['user_pass'];
                    $fname = $this->request->data['citizenregistration']['contact_fname'] . ' ' . $this->request->data['citizenregistration']['contact_mname'] . ' ' . $this->request->data['citizenregistration']['contact_lname'];
                    $mobileno = $this->request->data['citizenregistration']['mobile_no'];
                    $emailid = $this->request->data['citizenregistration']['email_id'];
                    $this->request->data['citizenregistration']['is_company'] = $is_company;
                    $this->request->data['citizenregistration']['is_builder'] = $is_builder;
                    $this->request->data['citizenregistration']['is_bank'] = $is_bank;
                    $this->request->data['citizenregistration']['is_advocate'] = $is_advocate;
                    $this->request->data['citizenregistration']['deed_writer'] = $deed_writer;
                    $this->request->data['citizenregistration']['uid'] = base64_encode($this->request->data['citizenregistration']['uid']);
                    $createdate = $date;
                    $req_ip = $req_ip;
                    $activeflag = 'y';
                    $useractivedate = $date;

                    if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                        $deedwritteracept = 'Y';
                    } else {
                        $deedwritteracept = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                        $advicateacceptflag = 'Y';
                    } else {
                        $advicateacceptflag = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 4) {
                        $builderacceptflag = 'Y';
                    } else {
                        $builderacceptflag = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 5) {
                        $bankacceptflag = 'Y';
                    } else {
                        $bankacceptflag = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 6) {
                        $companyacceptflag = 'Y';
                    } else {
                        $companyacceptflag = 'N';
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['state_id'])) {
                        $sateid = NULL;
                    } else {
                        $sateid = $this->request->data['citizenregistration']['state_id'];
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['office_id'])) {
                        $office = NULL;
                    } else {
                        $office = $this->request->data['citizenregistration']['office_id'];
                    }

                    $newpassword = $this->request->data['citizenregistration']['user_pass'] = sha1($this->request->data['citizenregistration']['user_pass']);
                    $newpassword1 = $this->request->data['citizenregistration']['re_user_pass'] = sha1($this->request->data['citizenregistration']['re_user_pass']);
                    //   $this->request->data['citizenregistration']['user_name'] = sha1($this->request->data['citizenregistration']['user_name']);
                    if ($newpassword == $newpassword1) {
                        if ($captcha == Sanitize::html($this->request->data['citizenregistration']['captcha'])) {
                            $this->request->data['citizenregistration']['user_pass'] = $this->request->data['citizenregistration']['user_pass'];
                            $this->request->data['citizenregistration']['re_user_pass'] = $this->request->data['citizenregistration']['re_user_pass'];


                            if ($this->citizenuserreg->save($this->request->data['citizenregistration'])) {
                                $this->loadModel('CitizenUser');
                                $this->loadModel('getUserRolecitizen');
                                $query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer,is_advocate,deed_write_accept_flag,is_advocate_accept_flag,is_builder,is_bank,is_builder_accept_flag,is_bank_accept_flag,is_company,is_company_accept_flag)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer, $is_advocate, $deedwritteracept, $advicateacceptflag, $is_builder, $is_bank, $builderacceptflag, $bankacceptflag, $is_company, $companyacceptflag));
                                $result = $this->CitizenUser->query("select max(user_id)as muser_id from ngdrstab_mst_user_citizen");
                                $userid = $result[0][0]['muser_id'];
                                $query1 = $this->getUserRolecitizen->query('insert into ngdrstab_mst_userroles_citizen (user_id,module_id,role_id,created,req_ip,state_id)values(?,?,?,?,?,?)', array($userid, 3, 1, $createdate, $req_ip, $sateid));
                                $this->Session->setFlash(__('Registration Successful.'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                $this->Session->setFlash(__('Registration unsuccessful.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'citizenregistration'));
                            }
                        } else {
                            $this->Session->setFlash(__('The captcha code you entered does not match. Please try again.'));
                        }
                    } else {
                        $errarr['re_user_pass_error'] = 'Password Did not Match';
                        $this->Session->setFlash(__('Password Did not Match'));
                        // $this->redirect(array('action' => 'citizenregistration'));
                    }
                }
            }
            $this->Session->write("salt", rand(111111, 999999));
            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function citizenregistration() {

        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../notfound.ctp');
                    exit;
                }
            }

            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            $laug = $this->Session->read("sess_langauge");
            if ($laug == NULL) {
                $this->Session->write("sess_langauge", 'en');
            }

            $laug = $this->Session->read("sess_langauge");

            $this->set('laug', $laug);
            $this->loadModel('emptype');
            $this->loadModel('reg_type');
            $reg_type = $this->reg_type->find('list', array('fields' => array('id', 'type_name_' . $laug), 'conditions' => array('display_flag' => 'Y'), 'order' => array('id' => 'ASC')));
            $this->set('reg_type', $reg_type);

            $emptype = $this->emptype->find('list', array('fields' => array('id', 'emp_type'), 'order' => array('id' => 'ASC'), 'conditions' => array('id' => 2), 'order' => array('id' => 'ASC')));
            $this->set('emptype', $emptype);
            $this->loadModel('State');
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_' . $laug), 'order' => array('state_name_en' => 'ASC')));
            $this->set('State', $State);
            $this->loadModel('division');
            $division = $this->division->find('list', array('fields' => array('id', 'division_name_' . $laug), 'order' => array('division_name_en' => 'ASC')));
            $this->set('division', $division);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_' . $laug), 'order' => array('district_name_en' => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $laug), 'order' => array('taluka_name_en' => 'ASC')));
            $this->set('taluka', $taluka);
            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $laug), 'conditions' => array('filter_flag' => 'I'), 'order' => array('identificationtype_desc_en' => 'ASC')));
            $this->set('idtype', $idtype);
            $this->loadModel('hintquestion');
            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_' . $laug), 'order' => array('questions_' . $laug => 'ASC')));
            $this->set('hintquestion', $hintquestion);
            $captcha = $this->Session->read('captcha_code');
            $this->loadModel('citizenuserreg');
            $this->loadModel('CitizenUser');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $allrule = $this->NGDRSErrorCode->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
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
            $fieldlist['citizen_type']['radio'] = 'is_i_n';
            $fieldlist['contact_fname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['contact_mname']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['contact_lname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['building']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['street']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['city']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['pincode']['text'] = 'is_pincode_empty';
            $fieldlist['state_id']['text'] = 'is_select_req';
//            $fieldlist['division_id']['text'] = '';
            $fieldlist['district_id']['text'] = 'is_select_req';
            $fieldlist['taluka_id']['text'] = 'is_select_req';
            $fieldlist['office_id']['text'] = 'is_select_req';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['id_type']['select'] = 'is_select_req';
            // $fieldlist['pan_no']['text'] = 'is_required,is_pancard';
//            $fieldlist['uid']['text'] = 'is_required,is_uidnum';

            $fieldlist['user_name']['text'] = 'is_required,is_username,is_maxlength50';
//            $fieldlist['user_pass']['text'] = 'is_required,is_password';
//            $fieldlist['re_user_pass']['text'] = 'is_required,is_password';
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $fieldlist['hint_question']['select'] = 'is_required,is_select_req';
            $fieldlist['hint_answer']['text'] = 'is_required,is_alphanumspace,is_maxlength255';
            $fieldlist['address']['text'] = 'is_alphanumspace';
            $fieldlist['licence_no']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['name_of_bar']['text'] = 'is_required,is_alphanumspace';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);

            $this->set("aftervalidation", 'Y');
            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['citizenregistration']['csrftoken']);



                if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'Y')));
                    $validcount = $this->check_advocater_reg_count();
                    $is_advocate = 'Y';
                } else {
                    $is_advocate = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'deed_writer' => 'Y')));
                    $validcount = $this->check_deedwriter_reg_count();
                    $deed_writer = 'Y';
                } else {
                    $deed_writer = 'N';
                }
                if ($this->request->data['citizenregistration']['reg_type'] == 1) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'N', 'deed_writer' => 'N')));
                    $validcount = $this->check_citizen_reg_count();
                }


                if (!empty($totalreg)) {
                    if ($validcount < count($totalreg)) {
                        $this->redirect(array('controller' => 'Users', 'action' => 'welcomenote'));
                    }
                }

//                pr($this->request->data['citizenregistration']['user_name']);
//                $this->request->data['citizenregistration']['user_pass'] = $this->decrypt($this->request->data['citizenregistration']['user_pass'], $this->Session->read("salt"));
//                pr($this->Session->read("salt"));
                $this->request->data['citizenregistration']['user_name'] = $this->decrypt($this->request->data['citizenregistration']['user_name'], $this->Session->read("salt"));
//                echo 'After decrypt';
//                pr($this->request->data['citizenregistration']['user_name']);exit;
//                pr($this->request->data['citizenregistration']['user_name']);exit;
//                $this->request->data['citizenregistration']['re_user_pass'] = $this->decrypt($this->request->data['citizenregistration']['re_user_pass'], $this->Session->read("salt"));
//                echo '12345';exit;
//                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlist);
//                $flag = 0;
//                foreach ($errarr as $dd) {
//                    if ($dd != "") {
//                        $flag = 1;
//                    }
//                }
                //pr($errarr);exit;
////                pr($this->request->data['citizenregistration']);
////                exit;
//                if ($flag == 1) {
//                    $this->set("errarr", $errarr);
//                } else {
                //------------------------------------------ Server side validation-----------------------------------------------------------
//                pr($this->request->data['citizenregistration']);
//                exit;

                $this->request->data['citizenregistration'] = $this->istrim($this->request->data['citizenregistration']);
                $fieldlistnew = $this->modifycitizenregfieldlist($fieldlist, $this->request->data['citizenregistration']);
//                pr($fieldlistnew);
//                exit;
                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlistnew);

//                pr($errarr);exit;


                if ($this->ValidationError($errarr)) {
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date('Y/m/d');
                    $time = date("H:i");
                    $time = explode(":", date("H:i"));
                    $req_ip = $_SERVER['REMOTE_ADDR'];
                    if ($this->request->data['citizenregistration']['id_type'] == 'empty') {
                        $this->request->data['citizenregistration']['id_type'] = '';
                        $this->request->data['citizenregistration']['pan_no'] = '';
                    }
                    $this->request->data['citizenregistration']['pan_no'] = $this->request->data['citizenregistration']['pan_no'];
                    $this->request->data['citizenregistration']['req_ip'] = $req_ip;
                    $this->request->data['citizenregistration']['user_creation_date'] = $date;
                    $this->request->data['citizenregistration']['req_time_hr'] = $time[0];
                    $this->request->data['citizenregistration']['req_time_min'] = $time[1];
                    $username = $this->request->data['citizenregistration']['user_name'];

                    $fname = $this->request->data['citizenregistration']['contact_fname'] . ' ' . $this->request->data['citizenregistration']['contact_mname'] . ' ' . $this->request->data['citizenregistration']['contact_lname'];
                    $mobileno = $this->request->data['citizenregistration']['mobile_no'];
                    $emailid = $this->request->data['citizenregistration']['email_id'];



                    $this->request->data['citizenregistration']['is_advocate'] = $is_advocate;

                    $this->request->data['citizenregistration']['deed_writer'] = $deed_writer;

                    $this->request->data['citizenregistration']['uid'] = $this->request->data['citizenregistration']['uid'];
                    $createdate = $date;
                    $req_ip = $req_ip;
                    $activeflag = 'y';
                    $useractivedate = $date;

                    if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                        $deedwritteracept = 'Y';
                    } else {
                        $deedwritteracept = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                        $advicateacceptflag = 'Y';
                    } else {
                        $advicateacceptflag = 'N';
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['state_id'])) {
                        $sateid = NULL;
                    } else {
                        $sateid = $this->request->data['citizenregistration']['state_id'];
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['office_id'])) {
                        $office = NULL;
                    } else {
                        $office = $this->request->data['citizenregistration']['office_id'];
                    }


//                    $newpassword = $this->request->data['citizenregistration']['user_pass'] = sha1($this->request->data['citizenregistration']['user_pass']);
//                    $newpassword1 = $this->request->data['citizenregistration']['re_user_pass'] = sha1($this->request->data['citizenregistration']['re_user_pass']);
                    $newpassword = $this->request->data['citizenregistration']['user_pass'];
                    $newpassword1 = $this->request->data['citizenregistration']['re_user_pass'];
                    //   $this->request->data['citizenregistration']['user_name'] = sha1($this->request->data['citizenregistration']['user_name']);
//                    pr($newpassword);exit;
                    if ($newpassword == $newpassword1) {

                        if ($captcha == Sanitize::html($this->request->data['citizenregistration']['captcha'])) {

                            $this->request->data['citizenregistration']['user_pass'] = $this->request->data['citizenregistration']['user_pass'];
                            $this->request->data['citizenregistration']['re_user_pass'] = $this->request->data['citizenregistration']['re_user_pass'];
//                            pr($this->request->data['citizenregistration']);
//                            exit;
                            if ($this->citizenuserreg->save($this->request->data['citizenregistration'])) {
                                $this->loadModel('CitizenUser');
                                $this->loadModel('getUserRolecitizen');
//                                $query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer));
                                //$query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer,is_advocate)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer,$is_advocate));
                                $query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer,is_advocate,deed_write_accept_flag,is_advocate_accept_flag)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer, $is_advocate, $deedwritteracept, $advicateacceptflag));
                                $result = $this->CitizenUser->query("select max(user_id)as muser_id from ngdrstab_mst_user_citizen");
                                $userid = $result[0][0]['muser_id'];
                                $query1 = $this->getUserRolecitizen->query('insert into ngdrstab_mst_userroles_citizen (user_id,module_id,role_id,created,req_ip,state_id)values(?,?,?,?,?,?)', array($userid, 3, 1, $createdate, $req_ip, $sateid));
                                $this->Session->setFlash(__('Registration Successful.'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                $this->Session->setFlash(__('Registration unsuccessful.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'citizenregistration'));
                            }
                        } else {
                            $this->Session->setFlash(__('The captcha code you entered does not match. Please try again.'));
                        }
                    } else {
                        $errarr['re_user_pass_error'] = 'Password Did not Match';
                        $this->Session->setFlash(__('Password Did not Match'));
                        // $this->redirect(array('action' => 'citizenregistration'));
                    }
                }
            }
            $this->Session->write("salt", rand(111111, 999999));
            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    // End Citizen Registration Common
    // Start Citizen Registration Common function'sgetIdentificationlist
    public function get_district_name() {
        try {
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            if (isset($_GET['state'])) {
//                $division = $_GET['division'];
                $state = $_GET['state'];
                // echo $state; exit;
                $districtname = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $laug), 'conditions' => array('state_id' => array($state))));
//                pr($districtname);exit;
                echo json_encode($districtname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function get_taluka_name() {
        try {
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            if (isset($_GET['district'])) {
                $district = $_GET['district'];
                $talukaname = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $laug), 'conditions' => array('district_id' => array($district))));
                echo json_encode($talukaname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function regoffice() {

        try {
            if (isset($_GET['taluka_id'])) {
                $taluka = $_GET['taluka_id'];


                $options1['conditions'] = array('ov.taluka_id' => trim($taluka));
                $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                );
                $options1['fields'] = array('office.office_id', 'office.office_name_en');
                $office = ClassRegistry::init('office')->find('list', $options1);
                $result_array = array('office' => $office);
                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function get_validation_rule() {
        try {

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

    function getIdentificationlist() {
        try {

            if (isset($_GET['type'])) {
                $type = $_GET['type'];
                $identifirtype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_en'), 'conditions' => array('filter_flag' => array($type))));
                echo json_encode($identifirtype);
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

    function checkusercitizen() {
        // echo 'hi';exit;
        try {
            $this->loadModel('citizenuserreg');
            $c = Sanitize::html($_POST['username']);

            $this->citizenuserreg->findbyecitiuser($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkemailcitizen() {
        // echo 'hi';exit;
        try {
            $this->loadModel('citizenuserreg');
            $c = Sanitize::html($_POST['email']);

            $this->citizenuserreg->findbyemail($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkmobilenocitizen() {
        try {
            $this->loadModel('citizenuserreg');
            $c = Sanitize::html($_POST['mobile']);
            $this->citizenuserreg->findbymobile($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkuidcitizen() {
        try {
            $this->loadModel('citizenuserreg');
            $c = Sanitize::html($_POST['uid']);

            $this->citizenuserreg->findbyuid($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkidproofcitizen() {
        try {
            $this->loadModel('citizenuserreg');
            $c = Sanitize::html($_POST['pan_no']);

            $this->citizenuserreg->findbyuidproof($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function check_company_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 124)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_bank_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 123)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_builder_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 122)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_advocater_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 61)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_deedwriter_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 57)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_citizen_reg_count() {
        try {
            // $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('regconfig');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 55)));
            if (!empty($regconfig)) {
                return $regconfig['regconfig']['info_value'];
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function modifycitizenregfieldlist($fieldlist, $data) {
        if ($data['reg_type'] == 1) {
            unset($fieldlist['office_id']);
            unset($fieldlist['licence_no']);
            unset($fieldlist['name_of_bar']);
        }
        if ($data['reg_type'] == 2) {
            unset($fieldlist['name_of_bar']);
        }

        if ($data['reg_type'] == 4) {
            unset($fieldlist['office_id']);
            unset($fieldlist['licence_no']);
            unset($fieldlist['name_of_bar']);
            unset($fieldlist['bankname']);
            unset($fieldlist['bankadd']);
            unset($fieldlist['ifsc']);
        }

        if ($data['reg_type'] == 5) {
            unset($fieldlist['office_id']);
            unset($fieldlist['licence_no']);
            unset($fieldlist['name_of_bar']);
            unset($fieldlist['buildername']);
            unset($fieldlist['builderadd']);
        }
        if ($data['reg_type'] == 6) {
            unset($fieldlist['office_id']);
            unset($fieldlist['licence_no']);
            unset($fieldlist['name_of_bar']);
        }

        $statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
        if ($statename[0][0]['state_id'] != 27) {
            if (isset($data['citizen_type']) && $data['citizen_type'] == 'I') {
                unset($fieldlist['address']);
            } elseif (isset($data['citizen_type']) && $data['citizen_type'] == 'N') {
                unset($fieldlist['building']);
                unset($fieldlist['street']);
                unset($fieldlist['city']);
                unset($fieldlist['state_id']);
                unset($fieldlist['office_id']);
                unset($fieldlist['district_id']);
                unset($fieldlist['taluka_id']);
                unset($fieldlist['uid']);
                unset($fieldlist['othstateaddress']);
            }
        }


//        if ($data['reg_type'] == 1) {
//            unset($fieldlist['office_id']);
//        }

        if ($data['id_type'] == 9998) {
            unset($fieldlist['pan_no']);
        } else {
            unset($fieldlist['firmdetails']);
            unset($fieldlist['authperson']);
        }
        return $fieldlist;
    }

    // End Citizen Registration Common function's
    //Start Citizen Registration Maharashtra
    public function citizenregistration_mh() {

        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../notfound.ctp');
                    exit;
                }
            }

            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            $laug = $this->Session->read("sess_langauge");
            if ($laug == NULL) {
                $this->Session->write("sess_langauge", 'en');
            }
            $laug = $this->Session->read("sess_langauge");

            $this->set('laug', $laug);
            $this->loadModel('emptype');
            $this->loadModel('reg_type');
            $reg_type = $this->reg_type->find('list', array('fields' => array('id', 'type_name_' . $laug), 'conditions' => array('display_flag' => 'Y'), 'order' => array('id' => 'ASC')));
            $this->set('reg_type', $reg_type);
            $emptype = $this->emptype->find('list', array('fields' => array('id', 'emp_type'), 'order' => array('id' => 'ASC'), 'conditions' => array('id' => 2), 'order' => array('id' => 'ASC')));
            $this->set('emptype', $emptype);
            $this->loadModel('State');
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_' . $laug), 'order' => array('state_name_' . $laug => 'ASC')));
            $this->set('State', $State);
            $this->loadModel('division');
            $division = $this->division->find('list', array('fields' => array('id', 'division_name_' . $laug), 'order' => array('division_name_' . $laug => 'ASC')));
            $this->set('division', $division);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $laug), 'order' => array('taluka_name_' . $laug => 'ASC')));
            $this->set('taluka', $taluka);
            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $laug), 'conditions' => array('filter_flag' => 'I', 'reg_display_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $laug => 'ASC')));
            $this->set('idtype', $idtype);
            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_' . $laug), 'order' => array('questions_' . $laug => 'ASC')));
            $this->set('hintquestion', $hintquestion);
            $captcha = $this->Session->read('captcha_code');
            $this->loadModel('citizenuserreg');
            $this->loadModel('CitizenUser');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $allrule = $this->NGDRSErrorCode->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
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
//            $fieldlist['citizen_type']['radio'] = 'is_i_n';
            $fieldlist['contact_fname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['contact_mname']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['contact_lname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
//            $fieldlist['buildername']['text'] = 'is_select_req';
//            $fieldlist['builderadd']['text'] = 'is_select_req';
//            $fieldlist['bankname']['text'] = 'is_select_req';
//            $fieldlist['bankadd']['text'] = 'is_select_req';
//            $fieldlist['ifsc']['text'] = 'is_select_req';

            $fieldlist['building']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['street']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['city']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['pincode']['text'] = 'is_pincode_empty';
            $fieldlist['state_id']['text'] = 'is_select_req';
//            $fieldlist['division_id']['text'] = '';
            $fieldlist['district_id']['text'] = 'is_select_req';
            $fieldlist['taluka_id']['text'] = 'is_select_req';
            $fieldlist['office_id']['text'] = 'is_select_req';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['id_type']['select'] = 'is_select_req';
            // $fieldlist['pan_no']['text'] = 'is_required,is_pancard';
            // $fieldlist['firmdetails']['text'] = 'is_required';
//            $fieldlist['authperson']['text'] = 'is_required';
            $fieldlist['uid']['text'] = 'is_required,is_uidnum,is_numeric_nonzero';

            $fieldlist['user_name']['text'] = 'is_required,is_username,is_maxlength50';
            $fieldlist['user_pass']['text'] = 'is_required,is_password';
            $fieldlist['re_user_pass']['text'] = 'is_required,is_password';
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $fieldlist['hint_question']['select'] = 'is_required,is_select_req';
            $fieldlist['hint_answer']['text'] = 'is_required,is_alphanumspace,is_maxlength255';
//            $fieldlist['address']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['licence_no']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['name_of_bar']['text'] = 'is_required,is_alphanumspace';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);

            $this->set("aftervalidation", 'Y');
            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['citizenregistration']['csrftoken']);

                if ($this->request->data['citizenregistration']['reg_type'] == 6) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_company' => 'Y')));
                    $validcount = $this->check_company_reg_count();
                    $is_company = 'Y';
                } else {
                    $is_company = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 5) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_bank' => 'Y')));
                    $validcount = $this->check_bank_reg_count();
                    $is_bank = 'Y';
                } else {
                    $is_bank = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 4) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_builder' => 'Y')));
                    $validcount = $this->check_builder_reg_count();
                    $is_builder = 'Y';
                } else {
                    $is_builder = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'Y')));
                    $validcount = $this->check_advocater_reg_count();
                    $is_advocate = 'Y';
                } else {
                    $is_advocate = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'deed_writer' => 'Y')));
                    $validcount = $this->check_deedwriter_reg_count();
                    $deed_writer = 'Y';
                } else {
                    $deed_writer = 'N';
                }
                if ($this->request->data['citizenregistration']['reg_type'] == 1) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'N', 'deed_writer' => 'N')));
                    $validcount = $this->check_citizen_reg_count();
                }


                if (!empty($totalreg)) {
                    if ($validcount < count($totalreg)) {
                        $this->redirect(array('controller' => 'Users', 'action' => 'welcomenote'));
                    }
                }
                $this->request->data['citizenregistration']['user_pass'] = $this->decrypt($this->request->data['citizenregistration']['user_pass'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['user_name'] = $this->decrypt($this->request->data['citizenregistration']['user_name'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['re_user_pass'] = $this->decrypt($this->request->data['citizenregistration']['re_user_pass'], $this->Session->read("salt"));

//                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlist);
//                $flag = 0;
//                foreach ($errarr as $dd) {
//                    if ($dd != "") {
//                        $flag = 1;
//                    }
//                }
//                if ($flag == 1) {
//                    $this->set("errarr", $errarr);
//                } else {
                //------------------------------------------ Server side validation-----------------------------------------------------------
                $this->request->data['citizenregistration'] = $this->istrim($this->request->data['citizenregistration']);
                $fieldlistnew = $this->modifycitizenregfieldlist($fieldlist, $this->request->data['citizenregistration']);

                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlistnew);

//                pr($errarr);
//                exit;

                if ($this->ValidationError($errarr)) {
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date('Y/m/d');
                    $time = date("H:i");
                    $time = explode(":", date("H:i"));
                    $req_ip = $_SERVER['REMOTE_ADDR'];
                    if ($this->request->data['citizenregistration']['id_type'] == 'empty') {
                        $this->request->data['citizenregistration']['id_type'] = '';
                        $this->request->data['citizenregistration']['pan_no'] = '';
                    }
                    $this->request->data['citizenregistration']['req_ip'] = $req_ip;
                    $this->request->data['citizenregistration']['user_creation_date'] = $date;
                    $this->request->data['citizenregistration']['req_time_hr'] = $time[0];
                    $this->request->data['citizenregistration']['req_time_min'] = $time[1];
                    $username = $this->request->data['citizenregistration']['user_name'];
                    $userpass = $this->request->data['citizenregistration']['user_pass'];

                    $fname = $this->request->data['citizenregistration']['contact_fname'] . ' ' . $this->request->data['citizenregistration']['contact_mname'] . ' ' . $this->request->data['citizenregistration']['contact_lname'];
                    $mobileno = $this->request->data['citizenregistration']['mobile_no'];
                    $emailid = $this->request->data['citizenregistration']['email_id'];
                    $this->request->data['citizenregistration']['is_company'] = $is_company;
                    $this->request->data['citizenregistration']['is_builder'] = $is_builder;
                    $this->request->data['citizenregistration']['is_bank'] = $is_bank;
                    $this->request->data['citizenregistration']['is_advocate'] = $is_advocate;
                    $this->request->data['citizenregistration']['deed_writer'] = $deed_writer;
                    $this->request->data['citizenregistration']['uid'] = base64_encode($this->request->data['citizenregistration']['uid']);
                    $createdate = $date;
                    $req_ip = $req_ip;
                    $activeflag = 'y';
                    $useractivedate = $date;

                    if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                        $deedwritteracept = 'Y';
                    } else {
                        $deedwritteracept = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                        $advicateacceptflag = 'Y';
                    } else {
                        $advicateacceptflag = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 4) {
                        $builderacceptflag = 'Y';
                    } else {
                        $builderacceptflag = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 5) {
                        $bankacceptflag = 'Y';
                    } else {
                        $bankacceptflag = 'N';
                    }
                    if ($this->request->data['citizenregistration']['reg_type'] == 6) {
                        $companyacceptflag = 'Y';
                    } else {
                        $companyacceptflag = 'N';
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['state_id'])) {
                        $sateid = NULL;
                    } else {
                        $sateid = $this->request->data['citizenregistration']['state_id'];
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['office_id'])) {
                        $office = NULL;
                    } else {
                        $office = $this->request->data['citizenregistration']['office_id'];
                    }

                    $newpassword = $this->request->data['citizenregistration']['user_pass'] = sha1($this->request->data['citizenregistration']['user_pass']);
                    $newpassword1 = $this->request->data['citizenregistration']['re_user_pass'] = sha1($this->request->data['citizenregistration']['re_user_pass']);
                    //   $this->request->data['citizenregistration']['user_name'] = sha1($this->request->data['citizenregistration']['user_name']);
                    if ($newpassword == $newpassword1) {
                        if ($captcha == Sanitize::html($this->request->data['citizenregistration']['captcha'])) {
                            $this->request->data['citizenregistration']['user_pass'] = $this->request->data['citizenregistration']['user_pass'];
                            $this->request->data['citizenregistration']['re_user_pass'] = $this->request->data['citizenregistration']['re_user_pass'];

//                            pr($this->request->data['citizenregistration']);exit;
                            if ($this->citizenuserreg->save($this->request->data['citizenregistration'])) {
                                $this->loadModel('CitizenUser');
                                $this->loadModel('getUserRolecitizen');
                                $query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer,is_advocate,deed_write_accept_flag,is_advocate_accept_flag,is_builder,is_bank,is_builder_accept_flag,is_bank_accept_flag,is_company,is_company_accept_flag)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer, $is_advocate, $deedwritteracept, $advicateacceptflag, $is_builder, $is_bank, $builderacceptflag, $bankacceptflag, $is_company, $companyacceptflag));
                                $result = $this->CitizenUser->query("select max(user_id)as muser_id from ngdrstab_mst_user_citizen");
                                $userid = $result[0][0]['muser_id'];
                                $query1 = $this->getUserRolecitizen->query('insert into ngdrstab_mst_userroles_citizen (user_id,module_id,role_id,created,req_ip,state_id)values(?,?,?,?,?,?)', array($userid, 3, 1, $createdate, $req_ip, $sateid));
                                $this->Session->setFlash(__('Registration Successful.'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                $this->Session->setFlash(__('Registration unsuccessful.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'citizenregistration'));
                            }
                        } else {
                            $this->Session->setFlash(__('The captcha code you entered does not match. Please try again.'));
                        }
                    } else {
                        $errarr['re_user_pass_error'] = 'Password Did not Match';
                        $this->Session->setFlash(__('Password Did not Match'));
                        // $this->redirect(array('action' => 'citizenregistration'));
                    }
                }
            }
            $this->Session->write("salt", rand(111111, 999999));
            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    //End Citizen Registration Maharashtra
    //Start Citizen Registration GOA
    public function citizenregistration_ga() {

        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../notfound.ctp');
                    exit;
                }
            }

            if (!isset($_SESSION["token"])) {

                $_SESSION["token"] = md5(uniqid(mt_rand(), true));
            }
            $laug = $this->Session->read("sess_langauge");
            if ($laug == NULL) {
                $this->Session->write("sess_langauge", 'en');
            }
            $laug = $this->Session->read("sess_langauge");

            $this->set('laug', $laug);
            $this->loadModel('emptype');
            $this->loadModel('reg_type');
            $reg_type = $this->reg_type->find('list', array('fields' => array('id', 'type_name_en'), 'conditions' => array('display_flag' => 'Y'), 'order' => array('id' => 'ASC')));
            $this->set('reg_type', $reg_type);
            $emptype = $this->emptype->find('list', array('fields' => array('id', 'emp_type'), 'order' => array('id' => 'ASC'), 'conditions' => array('id' => 2), 'order' => array('id' => 'ASC')));
            $this->set('emptype', $emptype);
            $this->loadModel('State');
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_' . $laug), 'order' => array('state_name_en' => 'ASC')));
//            pr($State);exit;
            $this->set('State', $State);
            $this->loadModel('division');
            $division = $this->division->find('list', array('fields' => array('id', 'division_name_' . $laug), 'order' => array('division_name_en' => 'ASC')));
            $this->set('division', $division);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_' . $laug), 'order' => array('district_name_en' => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $laug), 'order' => array('taluka_name_en' => 'ASC')));
            $this->set('taluka', $taluka);
            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $laug), 'conditions' => array('filter_flag' => 'I'), 'order' => array('identificationtype_desc_en' => 'ASC')));
            $this->set('idtype', $idtype);
            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_en'), 'order' => array('questions_en' => 'ASC')));
            $this->set('hintquestion', $hintquestion);
            $captcha = $this->Session->read('captcha_code');
            $this->loadModel('citizenuserreg');
            $this->loadModel('CitizenUser');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $allrule = $this->NGDRSErrorCode->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
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
            $fieldlist['citizen_type']['radio'] = 'is_i_n';
            $fieldlist['contact_fname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['contact_mname']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['contact_lname']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['building']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['street']['text'] = 'is_alphanumspacecommaroundbrackets,is_minmaxlength100';
            $fieldlist['city']['text'] = 'is_alphaspace,is_minmaxlength100';
            $fieldlist['pincode']['text'] = 'is_pincode_empty';
//            $fieldlist['state_id']['text'] = 'is_select_req';  
//            $fieldlist['division_id']['text'] = '';
//            $fieldlist['district_id']['text'] = 'is_select_req';
//            $fieldlist['taluka_id']['text'] = 'is_select_req';
            $fieldlist['othstateaddress']['text'] = 'is_required';
            $fieldlist['office_id']['text'] = 'is_select_req';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['id_type']['select'] = 'is_select_req';
            // $fieldlist['pan_no']['text'] = 'is_required,is_pancard';
//            $fieldlist['uid']['text'] = 'is_uidnum,is_numeric_nonzero';
            $fieldlist['user_name']['text'] = 'is_required,is_username,is_maxlength50';
            $fieldlist['user_pass']['text'] = 'is_required,is_password';
            $fieldlist['re_user_pass']['text'] = 'is_required,is_password';
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $fieldlist['hint_question']['select'] = 'is_required,is_select_req';
            $fieldlist['hint_answer']['text'] = 'is_required,is_alphanumspace,is_maxlength255';
            $fieldlist['address']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['licence_no']['text'] = 'is_required,is_alphanumspace';
            $fieldlist['name_of_bar']['text'] = 'is_required,is_alphanumspace';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist)); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);

            $this->set("aftervalidation", 'Y');
            if ($this->request->is('post')) {
                // $this->check_csrf_token($this->request->data['citizenregistration']['csrftoken']);

                if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'Y')));
                    $validcount = $this->check_advocater_reg_count();
                    $is_advocate = 'Y';
                } else {
                    $is_advocate = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'deed_writer' => 'Y')));
                    $validcount = $this->check_deedwriter_reg_count();
                    $deed_writer = 'Y';
                } else {
                    $deed_writer = 'N';
                }

                if ($this->request->data['citizenregistration']['reg_type'] == 1) {
                    $totalreg = $this->citizenuserreg->find('all', array('conditions' => array('DATE(created)' => date('Y-m-d'), 'is_advocate' => 'N', 'deed_writer' => 'N')));
                    $validcount = $this->check_citizen_reg_count();
                }

                if (!empty($totalreg)) {
                    if ($validcount < count($totalreg)) {
                        $this->redirect(array('controller' => 'Users', 'action' => 'welcomenote'));
                    }
                }
                $this->request->data['citizenregistration']['user_pass'] = $this->decrypt($this->request->data['citizenregistration']['user_pass'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['user_name'] = $this->decrypt($this->request->data['citizenregistration']['user_name'], $this->Session->read("salt"));
                $this->request->data['citizenregistration']['re_user_pass'] = $this->decrypt($this->request->data['citizenregistration']['re_user_pass'], $this->Session->read("salt"));


//                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlist);
//                $flag = 0;
//                foreach ($errarr as $dd) {
//                    if ($dd != "") {
//                        $flag = 1;
//                    }
//                }
//                if ($flag == 1) {
//                    $this->set("errarr", $errarr);
//                } else {
                //------------------------------------------ Server side validation-----------------------------------------------------------
                $this->request->data['citizenregistration'] = $this->istrim($this->request->data['citizenregistration']);
                $fieldlistnew = $this->modifycitizenregfieldlist($fieldlist, $this->request->data['citizenregistration']);

                $errarr = $this->validatedata($this->request->data['citizenregistration'], $fieldlistnew);

//pr($errarr);exit;

                if ($this->ValidationError($errarr)) {
                    date_default_timezone_set("Asia/Kolkata");
                    $date = date('Y/m/d');
                    $time = date("H:i");
                    $time = explode(":", date("H:i"));
                    $req_ip = $_SERVER['REMOTE_ADDR'];
                    if ($this->request->data['citizenregistration']['id_type'] == 'empty') {
                        $this->request->data['citizenregistration']['id_type'] = '';
                        $this->request->data['citizenregistration']['pan_no'] = '';
                    }
                    $this->request->data['citizenregistration']['req_ip'] = $req_ip;
                    $this->request->data['citizenregistration']['user_creation_date'] = $date;
                    $this->request->data['citizenregistration']['req_time_hr'] = $time[0];
                    $this->request->data['citizenregistration']['req_time_min'] = $time[1];
                    $username = $this->request->data['citizenregistration']['user_name'];
                    $userpass = $this->request->data['citizenregistration']['user_pass'];
                    $fname = $this->request->data['citizenregistration']['contact_fname'] . ' ' . $this->request->data['citizenregistration']['contact_mname'] . ' ' . $this->request->data['citizenregistration']['contact_lname'];
//                    $otherstateaddress = $this->request->data['citizenregistration']['state_id'] . ' ' . $this->request->data['citizenregistration']['district_id'] . ' ' . $this->request->data['citizenregistration']['taluka_id'];
                    $mobileno = $this->request->data['citizenregistration']['mobile_no'];
                    $emailid = $this->request->data['citizenregistration']['email_id'];
                    $this->request->data['citizenregistration']['is_advocate'] = $is_advocate;
                    $this->request->data['citizenregistration']['deed_writer'] = $deed_writer;
//                    $this->request->data['citizenregistration']['uid'] = base64_encode($this->request->data['citizenregistration']['uid']);


                    if (empty($this->request->data['citizenregistration']['state_id']) || $this->request->data['citizenregistration']['state_id'] != 4) {
                        $statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
                        if (isset($statename) && (!empty($statename))) {
                            $this->request->data['citizenregistration']['state_id'] = $statename[0][0]['state_id'];
                            $this->request->data['citizenregistration']['district_id'] = 0;
                            $this->request->data['citizenregistration']['taluka_id'] = 0;
                        }
                    }


                    $createdate = $date;
                    $req_ip = $req_ip;
                    $activeflag = 'y';
                    $useractivedate = $date;

                    if ($this->request->data['citizenregistration']['reg_type'] == 2) {
                        $deedwritteracept = 'Y';
                    } else {
                        $deedwritteracept = 'N';
                    }

                    if ($this->request->data['citizenregistration']['reg_type'] == 3) {
                        $advicateacceptflag = 'Y';
                    } else {
                        $advicateacceptflag = 'N';
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['state_id'])) {
                        $sateid = NULL;
                    } else {
                        $sateid = $this->request->data['citizenregistration']['state_id'];
                    }

                    if (!is_numeric($this->request->data['citizenregistration']['office_id'])) {
                        $office = NULL;
                    } else {
                        $office = $this->request->data['citizenregistration']['office_id'];
                    }


                    $newpassword = $this->request->data['citizenregistration']['user_pass'] = sha1($this->request->data['citizenregistration']['user_pass']);
                    $newpassword1 = $this->request->data['citizenregistration']['re_user_pass'] = sha1($this->request->data['citizenregistration']['re_user_pass']);
                    //   $this->request->data['citizenregistration']['user_name'] = sha1($this->request->data['citizenregistration']['user_name']);
                    if ($newpassword == $newpassword1) {
                        if ($captcha == Sanitize::html($this->request->data['citizenregistration']['captcha'])) {
                            $this->request->data['citizenregistration']['user_pass'] = $this->request->data['citizenregistration']['user_pass'];
                            $this->request->data['citizenregistration']['re_user_pass'] = $this->request->data['citizenregistration']['re_user_pass'];

//                            pr($this->request->data['citizenregistration']);
//                            exit;
                            if ($this->citizenuserreg->save($this->request->data['citizenregistration'])) {
                                $this->loadModel('CitizenUser');
                                $this->loadModel('getUserRolecitizen');
                                $query = $this->CitizenUser->query('insert into ngdrstab_mst_user_citizen (username,password,office_id,role_id,module_id,full_name,mobile_no,email_id,created,req_ip,activeflag,state_id,authetication_type,user_active_date,lang_both,deed_writer,is_advocate,deed_write_accept_flag,is_advocate_accept_flag)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($username, $newpassword, $office, 1, 3, $fname, $mobileno, $emailid, $createdate, $req_ip, $activeflag, $sateid, 1, $useractivedate, 1, $deed_writer, $is_advocate, $deedwritteracept, $advicateacceptflag));
                                $result = $this->CitizenUser->query("select max(user_id)as muser_id from ngdrstab_mst_user_citizen");
                                $userid = $result[0][0]['muser_id'];
                                $query1 = $this->getUserRolecitizen->query('insert into ngdrstab_mst_userroles_citizen (user_id,module_id,role_id,created,req_ip,state_id)values(?,?,?,?,?,?)', array($userid, 3, 1, $createdate, $req_ip, $sateid));
                                $this->Session->setFlash(__('Registration Successful.'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'citizenlogin'));
                            } else {
                                $this->Session->setFlash(__('Registration unsuccessful.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'citizenregistration'));
                            }
                        } else {
                            $this->Session->setFlash(__('The captcha code you entered does not match. Please try again.'));
                        }
                    } else {
                        $errarr['re_user_pass_error'] = 'Password Did not Match';
                        $this->Session->setFlash(__('Password Did not Match'));
                        // $this->redirect(array('action' => 'citizenregistration'));
                    }
                }
            }
            $this->Session->write("salt", rand(111111, 999999));
            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    //End Citizen Registration GOA

    function checkemail() {
        try {
            $this->loadModel('empregistration');
            $c = Sanitize::html($_POST['email']);
            $this->empregistration->findbyemail($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    function checkmobileno() {
        try {
            $this->loadModel('empregistration');
            $c = Sanitize::html($_POST['mobile']);
            $this->empregistration->findbymobile($c);
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function checkuser() {
        try {
            $this->loadModel('empregistration');
            $c = Sanitize::html(strtoupper($_POST['c']));
            $Records1 = $this->empregistration->find('all', array('conditions' => array('UPPER(empregistration.user_name)' => $c)));
            if (!empty($Records1)) {
                echo 1;
            } else {
                echo 0;
            }
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function viewregistereduser() {
        try {
            if ($this->referer() != '') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
//             $this->check_csrf_token($this->request->data['userpermission']['csrftoken']);
            $this->loadModel('empregistration');
            $s = $this->empregistration->test();
            $this->set('usrdata', $s);
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function activate() {
        try {
            $this->loadModel('User');
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
                $id = $_POST['id'];
                $this->User->id = $id;
                if ($this->User->saveField('activeflag', 'Y')) {
                    if ($this->User->saveField('user_active_date', date('Y-m-d'))) {
                        echo 1;
                        exit;
                    } else {
                        echo 0;
                    }
                }
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function deactivate() {
        try {
            $this->loadModel('User');
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
                $id = $_POST['id'];
                $this->User->id = $id;
                if ($this->User->saveField('activeflag', 'N')) {
                    if ($this->User->saveField('user_active_date', date(''))) {
                        echo 1;
                        exit;
                    } else {
                        echo 0;
                    }
                }
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }

            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function biometricregistration() {
        
    }

    // Start Common function display value on Default page
    public function roledisplay() {
        $this->loadModel('User');
        $this->loadModel('role');
        $lang = $this->Session->read("sess_langauge");
        return $this->User->query("select b.role_name_$lang,b.role_id from ngdrstab_mst_user a inner join ngdrstab_mst_role b on b.role_id=a.role_id where a.role_id=?", array($this->Auth->user('role_id')));
    }

    public function officedisplay() {
        $this->loadModel('User');
        $this->loadModel('office');

        return $this->User->query("select B.office_name_en from ngdrstab_mst_user A inner join ngdrstab_mst_office B on A.office_id =B.office_id where A.office_id=?", array($this->Auth->user('office_id')));
    }

    public function statedisplay() {
        $this->loadModel('statedisplay');

        return $this->statedisplay->query("select state_id,state_name,dept_name from ngdrs_current_state");
    }

    // End Common function display value on Default page

    public function send_sms() {
        $smsid = 1;
        $phone = NULL;
        $this->loadModel('registration');
        $this->registration->smssend($smsid, $phone);
        exit;
    }

    public function secugenclient() {
        try {
//            $path = WWW_ROOT . 'files/auth_let/' .'sdfg' . ".pdf";
            $this->autoRender = false;
            $path = APP . 'files/' . 'secugenclient' . '.rar';

            if (file_exists($path)) {

                $this->response->file($path, array('download' => true, 'name' => 'code'));
                return $this->response->download('secugenclient.rar');
            } else {
                echo $path . "<br>";
                echo 'file not find';
                exit;
            }

            return $this->response;
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function scannerclient() {
        try {
//            $path = WWW_ROOT . 'files/auth_let/' .'sdfg' . ".pdf";
            $this->autoRender = false;
            $path = APP . 'files/' . 'Scannerclient' . '.rar';

            if (file_exists($path)) {

                $this->response->file($path, array('download' => true, 'name' => 'code'));
                return $this->response->download('Scannerclient.rar');
            } else {
                echo $path . "<br>";
                echo 'file not find';
                exit;
            }

            return $this->response;
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function webcamclient() {
        try {
//            $path = WWW_ROOT . 'files/auth_let/' .'sdfg' . ".pdf";
            $this->autoRender = false;
            $path = APP . 'files/' . 'webcampclient' . '.rar';

            if (file_exists($path)) {

                $this->response->file($path, array('download' => true, 'name' => 'code'));
                return $this->response->download('webcampclient.rar');
            } else {
                echo $path . "<br>";
                echo 'file not find';
                exit;
            }

            return $this->response;
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function ngdrsclient() {
        
    }

    public function normalappointment() {
        $this->loadModel('office');
        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => array('office_id' => 'ASC')));
        $this->set('office', $office);
        if ($this->request->is('post')) {
            if ($captcha == Sanitize::html($this->request->data['normalappointment']['captcha'])) {
                
            }
        }
    }

    public function get_available_appointment() {
        try {
            if (isset($_POST['office_id'])) {
                $this->loadModel('appointment');
                $this->loadModel('office');
                if ($_POST['from'] != '' && $_POST['to'] != '') {
                    $from = date('Y-m-d', strtotime($_POST['from']));
                    $to = date('Y-m-d', strtotime($_POST['to']));
                } else {
                    $today = strtotime(date("Y-m-d"));
                    $from = date('Y-m-d', $today);
                    $to = date('Y-m-d', strtotime("+10 days", $today));
                }
                $office = $this->office->find('first', array('conditions' => array('office_id' => $_POST['office_id'])));
                $appointment = $this->appointment->get_appointment_details($_POST['office_id'], $from, $to);
                $this->set('appointment', $appointment);
                $this->set('office', $office);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    function appointment($shift_id = NULL, $from = NULL, $quota = NULL) {
        $this->loadModel('officeshift');
        $this->loadModel('office');
        $this->loadModel('appointment');
        $office = $this->office->get_officedetails_for_appointment_dashboard();
        $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_en'), 'order' => array('shift_id' => 'ASC')));
        $quota_list = array('N' => 'Normal Appointment', 'T' => 'Tatkal Appointment');
        $this->set('quota_list', $quota_list);
        if ($shift_id == NULL) {
            $shift_id = 2;
            $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => 2)));
            $this->set('shift', 2);
        } else {
            $shift_id = $shift_id;
            $shift = $this->officeshift->find('all', array('order' => array('shift_id' => 'ASC'), 'conditions' => array('shift_id' => $shift_id)));
            $this->set('shift', $shift_id);
        }
        $today = strtotime(date("Y-m-d"));
        if ($quota == NULL) {
            $q = 'N';
        } else {
            $q = $quota;
        }
        if ($from == NULL) {
            $from = date('Y-m-d', $from);
            $appointment = $this->appointment->find('all', array('conditions' => array(
                    'appointment.appointment_date ' => date('Y-m-d', strtotime($from)), 'shift_id' => $shift_id, 'flag' => $q
            )));
            $this->set('app_date', date('d-m-Y', strtotime(date("d-m-Y"), strtotime($from))));
        } else {
            $appointment = $this->appointment->find('all', array('conditions' => array(
                    'appointment.appointment_date ' => date('Y-m-d', strtotime($from)), 'shift_id' => $shift_id, 'flag' => $q
            )));
            $this->set('app_date', date('d-m-Y', strtotime($from)));
        }
        //  pr($appointment);exit;       
        $this->set('quota', $q);
        $this->set(compact('appointment', 'officeshift', 'office'));
        if (!empty($shift)) {
            //lunch time calculation
            $lunchtime1 = date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']));
            $lunchtime2 = date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time']));
            $lunchtime_diff = $this->get_time_difference($lunchtime1, $lunchtime2);

            if ($q == 'N') {
                $time1 = date('G:i', strtotime($shift[0]['officeshift']['from_time']));
                $time2 = date('G:i', strtotime($shift[0]['officeshift']['to_time']));

                $time_diff = $this->get_time_difference($time1, $time2);

                if (strtotime($shift[0]['officeshift']['tatkal_from_time']) < strtotime($shift[0]['officeshift']['lunch_from_time']) && strtotime($shift[0]['officeshift']['lunch_to_time']) < strtotime($shift[0]['officeshift']['tatkal_to_time'])) {
                    $nettime_diff = $time_diff;
                } else {
                    $nettime_diff = ($time_diff - $lunchtime_diff);
                }

                $totalMinutes = $nettime_diff * 60;
                $hours = intval($totalMinutes / 60);
                $minutes = $totalMinutes - ($hours * 60);
                $a = array();
                $lunch_flag = null;
                $tempdiff = 0;
                $prev_time = $time1;
                $i = 1;
                do {
                    if ($time1 == date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']))) {
                        $time1 = date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time']));
                    }

                    $tempdiff = abs(strtotime($time1) - strtotime($shift[0]['officeshift']['lunch_from_time'])) / (60 * 60);

                    if ($tempdiff < 1 && $lunch_flag != 'Y') {
                        $a[$i++] = ($time1 . '-' . date('G:i', strtotime($time1) + $tempdiff * 60 * 60)); // 
                        $time1 = date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time']));
                        $lunch_flag = 'Y';
                    }
                    if (((strtotime($time1) + 60 * 60 ) <= (strtotime($shift[0]['officeshift']['to_time'])))) {
                        $a[$i] = $time1 . '-' . date('G:i', strtotime($time1) + 60 * 60);
                        $time1 = date('G:i', strtotime($time1) + 60 * 60);
                    }
                    $i++;
                } while ((strtotime($time1) + 60 * 60 ) <= (strtotime($shift[0]['officeshift']['to_time'])));
                $from_time = strtotime("2008-12-13 10:21:00");
                $minutes = round(abs(strtotime($time1) - strtotime($shift[0]['officeshift']['to_time'])) / 60, 2);
                $endTime = strtotime("+$minutes minutes", strtotime($time1));
                if ($minutes > 0)
                    $a[$hours + 1] = $time1 . '-' . date('G:i', $endTime);
                $this->set('a', $a);
            }
            else if ($q == 'T') {
                $time1 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_from_time']));
                $time2 = date('G:i', strtotime($shift[0]['officeshift']['tatkal_to_time']));

                $time_diff = $this->get_time_difference($time1, $time2);

                if (strtotime($shift[0]['officeshift']['tatkal_from_time']) < strtotime($shift[0]['officeshift']['lunch_from_time']) && strtotime($shift[0]['officeshift']['lunch_to_time']) < strtotime($shift[0]['officeshift']['tatkal_to_time'])) {
                    $nettime_diff = $time_diff;
                } else {
                    $nettime_diff = ($time_diff - $lunchtime_diff);
                }

                $totalMinutes = $nettime_diff * 60;
                $hours = intval($totalMinutes / 60);
                $minutes = $totalMinutes - ($hours * 60);
                $a = array();
                $lunch_flag = null;
                $tempdiff = 0;
                $prev_time = $time1;
                $i = 1;
                do {
                    if ($time1 == date('G:i', strtotime($shift[0]['officeshift']['lunch_from_time']))) {
                        $time1 = date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time']));
                    }

                    $tempdiff = abs(strtotime($time1) - strtotime($shift[0]['officeshift']['lunch_from_time'])) / (60 * 60);

                    if ($tempdiff < 1 && $lunch_flag != 'Y') {
                        $a[$i++] = ($time1 . '-' . date('G:i', strtotime($time1) + $tempdiff * 60 * 60)); // 
                        $time1 = date('G:i', strtotime($shift[0]['officeshift']['lunch_to_time']));
                        $lunch_flag = 'Y';
                    }
                    if (((strtotime($time1) + 60 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])))) {
                        $a[$i] = $time1 . '-' . date('G:i', strtotime($time1) + 60 * 60);
                        $time1 = date('G:i', strtotime($time1) + 60 * 60);
                    }
                    $i++;
                } while ((strtotime($time1) + 60 * 60 ) <= (strtotime($shift[0]['officeshift']['tatkal_to_time'])));
                $from_time = strtotime("2008-12-13 10:21:00");
                $minutes = round(abs(strtotime($time1) - strtotime($shift[0]['officeshift']['tatkal_to_time'])) / 60, 2);
                $endTime = strtotime("+$minutes minutes", strtotime($time1));
                if ($minutes > 0)
                    $a[$hours + 1] = $time1 . '-' . date('G:i', $endTime);
                $this->set('a', $a);
            }
        }
    }

    function get_time_difference($time1, $time2) {

        $time1 = strtotime("1/1/1980 $time1");
        $time2 = strtotime("1/1/1980 $time2");

        if ($time2 < $time1) {
            $time2 = $time2 + 86400;
        }

        return ($time2 - $time1) / 3600;
    }

    public function role() {
        try {
            $this->check_role_escalation();
            $this->loadModel('role');
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->request->data['role']['req_ip'] = $req_ip;
            $this->request->data['role']['user_id'] = $user_id;
            $this->request->data['role']['state_id'] = $stateid;
            $this->set('module_id', ClassRegistry::init('module')->find('list', array('fields' => array('module_id', 'module_name_' . $laug))));
            $rolerecord = $this->role->query("select a.id,a.module_id,a.role_id,a.role_name_$laug,b.module_name_$laug,a.valid_for_months from ngdrstab_mst_role a
                                              inner join ngdrstab_mst_module b on b.module_id=a.module_id where a.permanent_role_flag='N'");
            $this->set('rolerecord', $rolerecord);
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist['role_id']['text'] = 'is_required,is_integer,is_integer_length8';
            $fieldlist['role_name']['text'] = 'is_required,is_alphaspace,is_maxlength255';
            $fieldlist['module_id']['select'] = 'is_select_req';
            $fieldlist['valid_for_months']['text'] = 'is_required,is_integer,is_minmaxlength20';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['role']['csrftoken']);
                $this->request->data['role'] = $this->istrim($this->request->data['role']);
                $errarr = $this->validatedata($this->request->data['role'], $fieldlist);

                if ($this->ValidationError($errarr)) {
                    $this->set('actiontypeval', $_POST['actiontype']);
                    $this->set('hfid', $_POST['hfid']);
                    if ($_POST['actiontype'] == '1') {
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['role']['id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data = $this->encode_special_char($this->request->data);
                        $roleidexit = $this->request->data['role']['role_id'];
//                        pr($roleidexit);exit;
                        $count = $this->role->query("select count(*)  from ngdrstab_mst_role where role_id=$roleidexit");
//                        pr($roleid);exit;
                        if ($count[0][0]['count'] > 0) {
                            $this->Session->setFlash(__('Role ID Allready Exists.'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'role'));
                        }

                        if ($this->role->save($this->request->data)) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Users', 'action' => 'role'));
                        } else {
                            $this->Session->setFlash(__("Record Not $actionvalue "));
                        }
                    }
                } $this->set_csrf_token();
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_role($id = null) {
        $this->autoRender = false;
        $this->loadModel('role');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'document') {
                $this->role->id = $id;
                if ($this->role->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'role'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    //check valid days role deactivated
    public function roledeactive() {
        $currentdate = date('Y/m/d');
        $roleid = $this->role->query("select role_id from ngdrstab_mst_user where user_id=$user_id");
        $roleid1 = $roleid[0][0]['role_id'];

        $validmonth = $this->role->Query("select created,valid_for_months from ngdrstab_mst_role where role_id=$roleid1");
        $validmonth1 = $validmonth[0][0]['valid_for_months'];
        $created = $validmonth[0][0]['created'];
        $stringday = $validmonth1 . 'days';

        $date = date_create($created);

        date_add($date, date_interval_create_from_date_string($stringday));
        $newvaliddate = date_format($date, "Y/m/d");
        if (strtotime($currentdate) > strtotime($newvaliddate)) {
            echo 'hi';
            exit;
            $updateactiveflace = $this->role->query("update ngdrstab_mst_user set activeflag='N' where user_id=$user_id");
            pr($updateactiveflace);
            exit;
        } else {
            echo 'end';
            exit;
        }
    }

    public function checkpasswordauth($userid) {
        $this->set('count', 0);
        $lang = $this->Session->read("sess_langauge");
//        $count = ClassRegistry::init('getUserRole')->query("select count(*) from (select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
//                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=? order by B.module_name_$lang) as role", array($userid));
        $count = ClassRegistry::init('getUserRole')->query("select count(*) from (select distinct role_id from ngdrstab_mst_userroles where user_id=? ) as role", array($userid));
        //check multiple Model show
        if ($count[0][0]['count'] > 1) {
            $usermodules = ClassRegistry::init('getUserRole')->query("select distinct B.module_name_$lang,B.url,A.role_id from ngdrstab_mst_userroles A
                                                        inner join ngdrstab_mst_module B on A.module_id=B.module_id where A.user_id=? order by B.module_name_$lang", array($userid));
            $this->set('usermodules', $usermodules);
            $this->Session->write("session_redirect", 'welcomemodel');
            $this->redirect(array('action' => 'welcomemodel'));
        } else {
            $this->Session->write("session_redirect", 'welcome');
            $this->redirect(array('action' => 'welcome'));
        }
    }

    public function checkbiometricauth() {

        $this->Session->write("session_redirect", 'welcomemodel');
        $this->Session->write("biometcount", 0);
        $this->redirect(array('action' => 'biometriclogin'));
    }

    public function activate_biometric_user() {
        $this->loadModel("User");
        $this->loadModel("biometric");
        $this->set('hfid', NULL);
        $this->set('actiontype', NULL);
        $this->set('cap', NULL);
        $user_id = $this->Auth->User("user_id");
        $this->set("result", $result = $this->User->query("select u.user_id,u.full_name,u.activeflag,u.biometric_registration_flag,b.created,b.biometric_finger,f.office_name_en
                                                            from ngdrstab_mst_user u
                                                            left outer join ngdrstab_mst_user_biometric b on b.user_id=u.user_id
                                                            left outer join ngdrstab_mst_office f on f.office_id=u.office_id
                                                            where u.authetication_type IN (?,?)", array('2', '3')));
        $check = $this->User->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($user_id));
        $serverbioflag = $check[0][0]['server_biometric_flag'];
        $this->set('biometserverflag', $serverbioflag);

        if ($this->request->is('post')) {
            $this->set('actiontype', $_POST['actiontype']);
            if ($_POST['actiontype'] == '1') {
                $updateuser = $this->User->query("Update ngdrstab_mst_user set activeflag=? where user_id=?", array('Y', $_POST['hfid']));
                if ($updateuser == NULL) {
                    $this->Session->setFlash(__("Activate Biometric User Successfully"));
                    $this->redirect(array('action' => 'activate_biometric_user'));
                }
            }
            if ($_POST['actiontype'] == '2') {
                $resetuser = $resetbiometric = NULL;
                $check = $this->User->query("select biometric_registration_flag, activeflag from ngdrstab_mst_user where user_id=?", array($_POST['hfid']));
                if ($check[0][0]['activeflag'] == 'Y') {
                    $resetuser = $this->User->query("Update ngdrstab_mst_user set activeflag=?, biometric_capture_flag=? where user_id=?", array('N', 'Y', $_POST['hfid']));
                }
                if ($check[0][0]['biometric_registration_flag'] == 'Y') {
                    $resetuser = $this->User->query("Update ngdrstab_mst_user set biometric_registration_flag=? where user_id=?", array('N', $_POST['hfid']));
                    $resetbiometric = $this->User->query("delete from ngdrstab_mst_user_biometric where user_id=?", array($_POST['hfid']));
                }
                if ($resetuser == NULL && $resetbiometric == NULL) {
                    $this->Session->setFlash(__("Reset Biometric User Successfully"));
                    $this->redirect(array('action' => 'activate_biometric_user'));
                }
            }
            if ($_POST['actiontype'] == '3') {
                if ($_POST['cap'] != NULL || $_POST['cap'] != '') {
                    $data['user_id'] = $_POST['hfid'];
                    //  $this->request->data['biometriclogin']['created_date'] = date('Y/m/d');
                    $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $data['state_id'] = $this->Auth->User("state_id");
                    $data['biometric_finger'] = $_POST['cap'];
                    if ($this->biometric->save($data)) {
                        $updaterecord = $this->biometric->query("Update ngdrstab_mst_user set biometric_registration_flag=? where user_id=?", array('Y', $_POST['hfid']));
                        $this->Session->setFlash(__("Biometric Register Successfully"));
                        $this->redirect(array('action' => 'activate_biometric_user'));
                    }
                } else {
                    $this->Session->setFlash(__("Please Scan Your Fingure...!!!"));
                    $this->redirect(array('action' => 'activate_biometric_user'));
                }
            }
        }
    }

    public function forgotpassword_old() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('employee');
                $this->loadModel('User');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        $userdetailsrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('employee.username' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('employee.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->employee->find('all', $options);
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->employee->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));

                        if ($hintanswer != '' && $hintanswer != NULL) {
                            $otp = rand(10000000, 99999999);
                            $smsid = 1;
                            $dbrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['User']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['User']['user_id'];
                            $state_id = $dbrecord[0]['User']['state_id'];
                            $ip = $this->request->clientIp();

                            $this->loadModel('otpcitizen');
                            $data = array('username' => $this->request->data['forgotpassword']['username'],
                                'otp' => $otp,
                                'user_id' => $user_id,
                                'state_id' => $state_id,
                                'req_ip' => $ip,
                                'user_type' => 'O',
                                'created_date' => $date);

                            if ($this->otpcitizen->save($data)) {
                                //$this->set('actiontype1', 3);
//                                //**************************By pravin******************
//                                $this->set('recorddata', 2);
//                                $newphone = substr($phone, -4);
//                                $this->set('otp_mobileno', $phone);
//                                $this->set('newmobileno', $newphone);
//                                //************************** end By pravin******************

                                $this->loadModel('employee');
//                                if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
//                                } else {
//                                    $this->Session->setFlash(__('OTP send failed...'));
//                                }
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

                                            if ($this->employee->updateforgotpassword($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'login'));
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

    //sha256 forgotpassword with out hintqestion
    public function forgotpassword_256() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('employee');
                $this->loadModel('User');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        //employee
                        $userdetailsrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('employee.username' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('employee.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->employee->find('all', $options);
                            // pr($hintquestion );exit;
                            if (!empty($hintquestion)) {
                                $this->set('hintquestion', $hintquestion);
                            } else {

                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
//                        $hintanswer = $this->employee->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));
//
//                        if ($hintanswer != '' && $hintanswer != NULL) {
                        $userdetailsrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $otp = rand(10000000, 99999999);
//                            $smsid = 1;
                            $dbrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['User']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['User']['user_id'];
                            $state_id = $dbrecord[0]['User']['state_id'];
                            $ip = $this->request->clientIp();

                            $this->loadModel('smsevent');
                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 5)));
                                //pr($event);
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }



                                $this->loadModel('otpcitizen');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'O',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['User']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['User']['mobile_no'], $otp, $dbrecord[0]['User']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
                            if ($this->otpcitizen->save($data)) {


                                $this->loadModel('employee');
//                                if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
//                                } else {
//                                    $this->Session->setFlash(__('OTP send failed...'));
//                                }
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

                                            if ($this->employee->updateforgotpassword($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'login'));
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

    //sha256 forgotpassword with out hintqestion
    public function forgotpassword_hintqestion() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('employee');
                $this->loadModel('User');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        //employee
                        $userdetailsrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('employee.username' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('employee.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->employee->find('all', $options);
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->employee->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));

                        if ($hintanswer != '' && $hintanswer != NULL) {
//                            $otp = rand(10000000, 99999999);
//                            $smsid = 1;
                            $dbrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['User']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['User']['user_id'];
                            $state_id = $dbrecord[0]['User']['state_id'];
                            $ip = $this->request->clientIp();

                            $this->loadModel('smsevent');
                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 5)));
                                //pr($event);
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }



                                $this->loadModel('otpcitizen');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'O',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['User']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['User']['mobile_no'], $otp, $dbrecord[0]['User']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
                            if ($this->otpcitizen->save($data)) {


                                $this->loadModel('employee');
//                                if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
//                                } else {
//                                    $this->Session->setFlash(__('OTP send failed...'));
//                                }
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

                                            if ($this->employee->updateforgotpassword($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'login'));
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

    //sha1 forgotpassword
    public function forgotpassword() {
        try {
            $this->set('userdetailsrecord', NULL);
            $this->set('recorddata', 0);
            $this->set('actiontype1', 0);
            if ($this->request->is('post')) {

                $this->loadModel('employee');
                $this->loadModel('User');
                if (isset($this->request->data['forgotpassword'])) {

                    $actiontype = $_POST['actiontype'];
                    if ($actiontype == '1') {
                        //employee
                        $userdetailsrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                        if ($userdetailsrecord != '' && $userdetailsrecord != NULL) {
//                            $db_email = $userdetailsrecord[0]['User']['email_id'];
//                            $db_mobileno1 = $userdetailsrecord[0]['User']['mobile_no'];
//                            $db_mobileno = substr($db_mobileno1, -10);
//                            $emailid = $db_email;
//                            $mobileno = $db_mobileno;
//                            if ($emailid == $this->request->data['forgotpassword']['email_id'] && $mobileno == $this->request->data['forgotpassword']['mobile_no']) {
                            $rec = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $this->set('userdetailsrecord', $rec);
                            $this->set('recorddata', 1);

                            $options['conditions'] = array('employee.username' => $this->request->data['forgotpassword']['username']);
                            $options['joins'] = array(array('table' => 'ngdrstab_mst_hint_questions', 'alias' => 'hint', 'type' => 'INNER', 'conditions' => array('employee.hint_question = hint.id')));
                            $options['fields'] = array('hint.questions_en');

                            $hintquestion = $this->employee->find('all', $options);
                            $this->set('hintquestion', $hintquestion);
//                            } else {
//
//                                $this->Session->setFlash(__('** WRONG INFORMATION **'));
//                            }
                        } else {
                            $this->Session->setFlash(__('** WRONG INFORMATION **'));
                        }
                    }
                    if ($actiontype == '2') {
                        $hintanswer = $this->employee->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'], 'hint_answer' => $this->request->data['forgotpassword']['hint_answer'])));

                        if ($hintanswer != '' && $hintanswer != NULL) {
//                            $otp = rand(10000000, 99999999);
//                            $smsid = 1;
                            $dbrecord = $this->User->find('all', array('conditions' => array('username' => $this->request->data['forgotpassword']['username'])));
                            $phone = Sanitize::html($dbrecord[0]['User']['mobile_no']);
                            $date = date('Y/m/d H:i:s');
                            $user_id = $dbrecord[0]['User']['user_id'];
                            $state_id = $dbrecord[0]['User']['state_id'];
                            $ip = $this->request->clientIp();

                            $this->loadModel('smsevent');
                            if ($dbrecord != Null) {
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 5)));
                                //pr($event);
                                if (!empty($event)) {
                                    if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                        $otp = rand(10000000, 99999999);
                                    } else {
                                        $otp = 12345678;
                                    }
                                }



                                $this->loadModel('otpcitizen');
                                $data = array('username' => $this->request->data['forgotpassword']['username'],
                                    'otp' => $otp,
                                    'user_id' => $user_id,
                                    'state_id' => $state_id,
                                    'req_ip' => $ip,
                                    'user_type' => 'O',
                                    'created_date' => $date);

                                if (!empty($dbrecord)) {
                                    if ($dbrecord[0]['User']['mobile_no']) {


                                        if (!empty($event)) {
                                            if ($event[0]['smsevent']['send_flag'] == 'Y') {

                                                $this->smssend(1, $dbrecord[0]['User']['mobile_no'], $otp, $dbrecord[0]['User']['user_id'], 4);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__('Invalid UserName'));
                            }
                            if ($this->otpcitizen->save($data)) {


                                $this->loadModel('employee');
//                                if ($this->employee->smssend($smsid, $phone, $otp)) {
                                $this->set('recorddata', 2);
                                $newphone = substr($phone, -4);
                                $this->set('otp_mobileno', $phone);
                                $this->set('newmobileno', $newphone);
//                                } else {
//                                    $this->Session->setFlash(__('OTP send failed...'));
//                                }
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

                                            if ($this->employee->updateforgotpassword($this->request->data['forgotpassword']['newpassword'], $this->request->data['forgotpassword']['username'])) {
                                                $this->Session->setFlash(__('Password reset successfully'));
                                                $this->redirect(array('action' => 'login'));
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

    function check_login_status($user_id) {
        $this->loadModel("loginusers");
        $login_id = $this->Session->read("login_id");
        $current_login_result = $this->loginusers->query("select * from ngdrstab_mst_loginusers where user_id=$user_id and id=$login_id");
        // pr($current_login_result); exit;
        $login_status_id = $current_login_result[0][0]['login_status_id'];
        if ($login_status_id == 0) {
            if (isset($_SESSION["csrfoutkey"]) and ! is_null($_SESSION["csrfoutkey"])) {
                $csrfoutkey = $_SESSION["csrfoutkey"];
            } else {
                $_SESSION["csrfoutkey"] = $csrfoutkey = rand(1111, 9999);
            }
            $this->requestAction(array('controller' => 'Users', 'action' => 'logout', $csrfoutkey));
        }
    }

    public function authenticationtype1($user_auth_type_id = NULL) {
        try {
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('OfficeCategory');
            $this->loadModel('authenticate_type');
            $this->loadModel('User');
            //  $this->loadModel('taluka');
            $this->loadModel('officehierarchy');
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
            // $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            //  $this->set('officehierarchy', $officehierarchy);
            //  $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            //  $this->set('OfficeCategory', $OfficeCategory);
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

            $authenticationtype = $this->authenticate_type->query("select * from ngdrstab_mst_user_authenticationtype");
            $this->set('authenticationtype', $authenticationtype);


            $this->set("fieldlist", $fieldlist = $this->authenticate_type->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($user_auth_type_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->request->data['authenticationtype']['ip_address'] = $this->request->clientIp();
                $this->request->data['authenticationtype']['created_date'] = $created_date;
                $this->request->data['authenticationtype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['authenticationtype'], $fieldlist);
                //pr($verrors);
                // exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->authenticate_type->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['authenticationtype']);
                    if ($checkd) {
                        if ($this->authenticate_type->save($this->request->data['authenticationtype'])) {

                            $this->Session->setFlash(__($actionvalue));
                            // $this->Session->setFlash(__('Record  saved Successful.'));
                            return $this->redirect(array('action' => 'authenticationtype'));
                            $lastid = $this->authenticate_type->getLastInsertId();
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
            if (!is_null($user_auth_type_id) && is_numeric($user_auth_type_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('user_auth_type_id', $user_auth_type_id);
                $result = $this->authenticate_type->find("first", array('conditions' => array('user_auth_type_id' => $user_auth_type_id)));
                if (!empty($result)) {
                    $this->request->data['authenticationtype'] = $result['authenticate_type'];
                } else {
                    $this->Session->setFlash(__('Record Not Found'));
                }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_authenticationtype1($user_auth_type_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('authenticate_type');
        try {

            if (isset($user_auth_type_id) && is_numeric($user_auth_type_id)) {
                //  if ($type = 'subdivision') {
                $this->authenticate_type->user_auth_type_id = $user_auth_type_id;
                if ($this->authenticate_type->delete($user_auth_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'authenticationtype'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function authenticationtype($id = NULL) {
        try {
            $this->check_role_escalation();
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('OfficeCategory');
            $this->loadModel('authenticate_type');
            $this->loadModel('User');
            //  $this->loadModel('taluka');
            $this->loadModel('officehierarchy');
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
            $this->set('hfid', NULL);

            // $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            //  $this->set('officehierarchy', $officehierarchy);
            //  $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            //  $this->set('OfficeCategory', $OfficeCategory);
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

            $authenticationtype = $this->authenticate_type->query("select * from ngdrstab_mst_user_authenticationtype");
            $this->set('authenticationtype', $authenticationtype);


            $this->set("fieldlist", $fieldlist = $this->authenticate_type->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $this->set('hfid', $id);
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {


                $this->request->data['authenticationtype']['id'] = $id;
                $this->request->data['authenticationtype']['ip_address'] = $this->request->clientIp();
                $this->request->data['authenticationtype']['created_date'] = $created_date;
                $this->request->data['authenticationtype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['authenticationtype'], $fieldlist);
                //pr($verrors);
                // exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->authenticate_type->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['authenticationtype']);
                    if ($checkd) {
                        if ($this->authenticate_type->save($this->request->data['authenticationtype'])) {

                            $this->Session->setFlash(__($actionvalue));
                            // $this->Session->setFlash(__('Record  saved Successful.'));
                            return $this->redirect(array('action' => 'authenticationtype'));
                            $lastid = $this->authenticate_type->getLastInsertId();
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
            if (!is_null($id) && is_numeric($id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('id', $id);
                $result = $this->authenticate_type->find("first", array('conditions' => array('id' => $id)));
                if (!empty($result)) {
                    //pr($result);exit;
                    $this->request->data['authenticationtype'] = $result['authenticate_type'];
                    //$this->request->data['authenticationtype']['user_auth_type_id']
                } else {
                    $this->Session->setFlash(__('Record Not Found'));
                }
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_authenticationtype($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('authenticate_type');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'subdivision') {
                $this->authenticate_type->id = $id;
                if ($this->authenticate_type->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'authenticationtype'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function role_creation($role_id = NULL) {
        try {
            $this->check_role_escalation();
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            // $this->loadModel('State');
            // $this->loadModel('OfficeCategory');
            $this->loadModel('module');
            $this->loadModel('User');
            $this->loadModel('role');
            // $this->loadModel('officehierarchy');
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
            // $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            //  $this->set('officehierarchy', $officehierarchy);
            //  $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            //  $this->set('OfficeCategory', $OfficeCategory);
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

            $role_creation = $this->role->query("select * from ngdrstab_mst_role");
            $this->set('role_creation', $role_creation);


            $this->set("fieldlist", $fieldlist = $this->role->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $module = $this->module->find('list', array('fields' => array('module.module_id', 'module.module_name_' . $laug), 'order' => array('module_name_en' => 'ASC')));
            $this->set('module', $module);

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->request->data['role_creation']['ip_address'] = $this->request->clientIp();
                $this->request->data['role_creation']['created_date'] = $created_date;
                $this->request->data['role_creation']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['role_creation'], $fieldlist);
                //pr($this->request->data['role_creation']);
                // pr($verrors);
                // exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->role->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['role_creation']);
                    if ($checkd) {
                        if ($this->role->save($this->request->data['role_creation'])) {
                            $this->Session->setFlash(__('lblsavemsg'));
                            return $this->redirect(array('action' => 'role_creation'));
                            $lastid = $this->role->getLastInsertId();
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
            if (!is_null($role_id) && is_numeric($role_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('role_id', $role_id);
                $result = $this->role->find("first", array('conditions' => array('role_id' => $role_id)));
//                pr($result);exit;
                //$this->set('result', $result);
                $this->request->data['role_creation'] = $result['role'];
            }
        } catch (exception $ex) {

            //  pr($ex);
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_role_creation($role_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('role');
        try {

            if (isset($role_id) && is_numeric($role_id)) {
                //  if ($type = 'subdivision') {
                $this->role->role_id = $role_id;
                if ($this->role->delete($role_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'role_creation'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function module($module_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('module');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('module', $this->module->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->module->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if (!empty($module_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {



                $this->request->data['module']['ip_address'] = $this->request->clientIp();
                $this->request->data['module']['created_date'] = $created_date;
                $this->request->data['module']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['module'], $fieldlist);
//                 

                if ($this->ValidationError($verrors)) {
//                   pr($this->request->data);exit;
                    $duplicate = $this->module->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['module']);
                    if ($checkd) {
                        if ($this->module->save($this->request->data['module'])) {
                            $this->Session->setFlash(__($actionvalue));
                            return $this->redirect(array('action' => 'module'));
                            $lastid = $this->module->getLastInsertId();
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
            if (!is_null($module_id) && is_numeric($module_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('module_id', $module_id);
                $result = $this->module->find("first", array('conditions' => array('module_id' => $module_id)));
                $this->request->data['module'] = $result['module'];
            }
        } catch (exception $ex) {

            //pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_module($module_id = null) {
        $this->autoRender = false;
        $this->loadModel('module');
        try {

            if (isset($module_id) && is_numeric($module_id)) {
                $this->module->module_id = $module_id;
                if ($this->module->delete($module_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'module'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function modulepermission($modulepermission_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('User');
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('ngprforms');
            $this->loadModel('module');
            $this->loadModel('ModulePermissions');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');


            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $this->set('module', ClassRegistry::init('module')->find('list', array('fields' => array('module_id', 'module_name_' . $lang), 'order' => array('module_name_' . $lang => 'ASC'))));



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
            $this->set("fieldlist", $fieldlist = $this->ModulePermissions->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['modulepermission']['ip_address'] = $this->request->clientIp();
                $this->request->data['modulepermission']['created_date'] = $created_date;
                $this->request->data['modulepermission']['user_id'] = $user_id;

                $verrors = $this->validatedata($this->request->data['modulepermission'], $fieldlist);
                if ($this->ValidationError($verrors)) {

                    if (isset($this->request->data['btnsearch'])) {
                        $menulist = $this->ModulePermissions->query("select
COALESCE(mainmenu.id,0) as mainmenu_id,
mainmenu.name_en as mainmenu_name,
COALESCE( submenu.id, 0 ) as submenu_id,
submenu.name_en as submenu_name,
COALESCE(subsubmenu.id,0) as subsubmenu_id,
subsubmenu.name_en as subsubmenu_name,
submenu.display_flag as submenu_display_flag ,
subsubmenu.display_flag as subsubmenu_display_flag
FROM ngdrstab_mst_menu as mainmenu
LEFT JOIN   ngdrstab_mst_submenu as submenu ON submenu.main_menu_id=mainmenu.id
LEFT JOIN   ngdrstab_mst_subsubmenu as subsubmenu ON subsubmenu.sub_menu_id=submenu.id
where mainmenu.display_flag='Y'  
order by  mainmenu.display_order,submenu.display_order,subsubmenu.display_order, mainmenu_name,submenu_name,subsubmenu_name
");
                        $this->set('menulist', $menulist);

                        $ModulePermissions = $this->ModulePermissions->find("all", array('conditions' => array('module_id' => $this->request->data['modulepermission']['module_id'])));
                        //pr($ModulePermissions);
                        $this->set('ModulePermissions', $ModulePermissions);
                    } else if (isset($this->request->data['btnSave'])) {
                        $this->ModulePermissions->deleteAll(array('module_id' => $this->request->data['modulepermission']['module_id']));
                        if (isset($this->request->data['menus']) && is_array($this->request->data['menus'])) {
                            foreach ($this->request->data['menus'] as $d) {
                                $data1 = explode('_', $d);
                                $this->request->data['modulepermission']['menu_id'] = $data1[0];
                                $this->request->data['modulepermission']['submenu_id'] = $data1[1];
                                $this->request->data['modulepermission']['subsubmenu_id'] = $data1[2];
                                $this->ModulePermissions->save($this->request->data['modulepermission']);
                                $this->Session->setFlash(__('lblsavemsg'));
                            }
                        } else {
                            $this->Session->setFlash(__('lblsavemsg'));
                        }
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($modulepermission_id) && is_numeric($modulepermission_id)) {

                $this->Session->write('modulepermission_id', $modulepermission_id);
                $result = $this->ModulePermissions->find("first", array('conditions' => array('modulepermission_id' => $modulepermission_id)));
                $this->request->data['modulepermission'] = $result['ModulePermissions'];
            }
        } catch (exception $ex) {

            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function feedback_details() {
        try {
            $this->loadModel('Feedback');
            $feedbackDetails = $this->Feedback->getAllFeedback();
            $lang = $this->Session->read('sess_language');
            if ($this->request->is('post')) {
                $formData = $this->request->data['feedback_detail'];
                $feedbackDetails = $this->Feedback->getFeedbackByDateRange($formData['from'], $formData['to']);
            }
            $this->set(compact('feedbackDetails', 'lang'));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

}
