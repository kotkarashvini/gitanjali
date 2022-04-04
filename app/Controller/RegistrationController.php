<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::import('Controller', 'Reports'); // mention at top
App::import('Controller', 'Utility'); // mention at top
App::import('Controller', 'WebService'); // mention at top
App::import('Controller', 'Fine');

class RegistrationController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');

        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Auth->allow('inspection_search','get_payment_details2','simple_reciept','get_payment_details1', 'scan', 'checkscan', 'loadfile', 'upload');
        $this->Auth->allow('exception_occurred', 'upload', 'index_scan');

        //$this->Security->unlockedActions = array('get_payment_details2', 'inspection_search', 'get_payment_details1', 'document_number', 'documentindex2', 'reg_main_menu', 'reg_sub_sub_menu', 'reg_sub_menu', 'scan', 'checkscan', 'loadfile', 'upload', 'documentindex', 'main_menu', 'sub_menu', 'subsub_menu', 'rulechangeevent', 'document_payment', 'calculatefees', 'document_witness', 'party', 'get_payment_details', 'document_identification', 'stamp_and_functions_config', 'get_available_appointment', 'document_final', 'payment', 'search_registration_summary', 'gras_payment_verification', 'payment_defacement', 'add_default_fields', 'payment_verification', 'lr_mutation');

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

//=================================== Scanner Start=================================================================
    public function scan($tokennumber = NULL, $view_flag = NULL) {
        if ($view_flag == NULL) {
            $this->check_role_escalation_tab();
        }
        try {
            if (is_null($tokennumber)) {
                $token = $this->Session->read("reg_token");
            } else {
                $token = $tokennumber;
            }
            if ($view_flag == NULL) {
                $this->check_function_hierarchy($this->request->params['action'], $token);
            }
            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');
            $this->set('rval', 'SC');
            $office_id = $this->Session->read("office_id");

            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $createFolder = $path['file_config']['filepath'] . "Scanned";
            if (!file_exists($createFolder)) {
                mkdir($createFolder, 0744, true); // creates folder if  not found
            }
            $this->set('path', $createFolder);
            $this->set('token', $token);
            $this->set('view_flag', $view_flag);

            $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $token));
            $application = $application[0][0];

            if ($application['document_scan_flag'] == 'Y') {
//                $docid = $this->request->data['scan']['filename'];
                $filepath = $createFolder . "/" . base64_decode($application['doc_reg_no']) . ".pdf";

                if (file_exists($filepath)) {
                    $this->set('filename', $this->request->data['scan']['filename']);
                } else {
                    $this->Session->setFlash(__("This Document is NOT Exist...!!!"));
                }
            }
            if ($this->request->is('post')) {
                $check = $this->ApplicationSubmitted->query("select count(token_no) from ngdrstab_trn_scanuploadinfo WHERE token_no=? ", array($token));
                if ($check[0][0]['count'] != 0) {
                    $updatecheck = $this->ApplicationSubmitted->query("update ngdrstab_trn_scanuploadinfo set conf_flag='Y' WHERE token_no=? ", array($token));
                    $this->Session->setFlash(__("Document Uploaded Permenently"));
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'scan'));
                } else {
                    $this->Session->setFlash(__("Document not Scanned Properly"));
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'scan'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function index_scan() {
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("article");
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read('sess_langauge');

            if (isset($office_id) && is_numeric($office_id)) {
                $stamp_conf = $this->stamp_and_functions_config();
                foreach ($stamp_conf as $stamp) {
                    if ($stamp['is_last'] == 'Y') {
                        $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
                    }
                }
                $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$lang,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            where app.office_id=? and check_in_flag=? and document_scan_flag=? or app.office_id=? and check_in_flag=? and  $check_stamp_flag=?", array($office_id, 'Y', 'N', $office_id, 'Y', 'N')));
            }
            $this->set(compact('lang', 'stamp_conf'));
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function checkscan() {
        try {
            $this->autoRender = false;
            $this->loadModel('scan_upload');
            if (isset($_POST['path']) && isset($_POST['docid']) && isset($_POST['token'])) {
                $filename = $_POST['path'] . "/" . base64_encode($_POST['docid']) . ".pdf";
                $file = $this->scan_upload->query("SELECT count(scan_name) FROM ngdrstab_trn_scanuploadinfo WHERE scan_name=? and token_no=?", array($filename, $_POST['token']));
                if ($file[0][0]['count'] != 0 && file_exists($filename)) {
                    echo json_encode(1);   //file exist 
                    exit;
                } else {
                    echo json_encode(2);
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function loadfile($fname) {
        $this->autoRender = false;
        $this->loadModel('file_config');
        $path = $this->file_config->find('first', array('fields' => array('filepath')));
        $path = $path['file_config']['filepath'] . "Scanned/" . base64_encode($fname) . ".pdf";
        return $this->response->file($path, array('download' => false, 'name' => 'samplefile'));
    }

    public function upload() {
        try {
//            $this->response->header(array(
//            'Access-Control-Allow-Origin' => '*',
//            'Access-Control-Allow-Headers' => 'Content-Type'
//        )
//    );
//      $this->autoRender=FALSE;  
//            pr($this->request->data);exit;
            $this->autoRender = false;
            $this->loadModel('scan_upload');
            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');
            $token = $_POST['token'];
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $createFolder = $path['file_config']['filepath'] . "Documents/" . $token . "/Scanning/";
            if (!file_exists($createFolder)) {
                mkdir($createFolder, 0744, true); // creates folder if  not found
            }

            $pages = $this->ApplicationSubmitted->query("SELECT no_of_pages FROM ngdrstab_trn_generalinformation WHERE token_no=? ", array($token));
            $pages = $pages[0][0]['no_of_pages'];
            $pdfname = $_FILES['asprise_scans']['tmp_name'];
            $pdftext = file_get_contents($pdfname);
            $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
            if ($num != $pages) {
                if ($num < $pages) {
                    $misspages = $pages - $num;
                    echo "Document required $pages pages. You have Scanned $num pages. $misspages pages are Missing in this Document.";
                    return;
                } else if ($num > $pages) {
                    $misspages = $num - $pages;
                    echo "Document required $pages pages. You have Scanned $num pages. $misspages pages are more added in this Document.";
                    return;
                }
            }
            $docid = $_POST['docid'];
            $img = $path['file_config']['filepath'] . "Documents/" . $token . "/QRBarcode/" . $token . "_qrbarcode.png";
            if (!file_exists($img)) {
                mkdir($img, 0744, true); // creates folder if  not found
            }
            $src = $pdfname;
            $dest = $createFolder . base64_encode($docid) . ".pdf";
            $path1 = $path['file_config']['filepath'] . "jar_files/qrcodeattach.jar";
//            pr($path1); pr($src); pr($img); pr($dest);exit;
            $message = exec('java -jar ' . $path1 . ' ' . $src . ' ' . $dest . ' ' . $img, $result);
//            $udir = $createFolder . "/" . base64_encode($docid) . ".pdf";
//            $newname = base64_encode($docid) . ".pdf";
//            $save1 = move_uploaded_file($_FILES['asprise_scans']['tmp_name'], $udir);
            $stateid = $this->Auth->User('state_id');
            $userid = $this->Auth->User('user_id');
            $data['doc_reg_no'] = $_POST['docid'];
            $data['token_no'] = $token;
            $data['scan_name'] = $dest;
            $data['state_id'] = $stateid;
            $data['user_id'] = $this->Auth->User('user_id');
            $data['created_date'] = date('Y/m/d H:i:s');
            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            if ($_POST['saveflag'] == 'U') {
                $check = $this->scan_upload->query("SELECT id FROM ngdrstab_trn_scanuploadinfo where doc_reg_no=?", array($_POST['docid']));
                $data['id'] = $check[0][0]['id'];
            }
            $save2 = $this->scan_upload->save($data);

            if ($message && $save2 && $_POST['saveflag'] == 'S') {
                $this->ApplicationSubmitted->query("UPDATE ngdrstab_trn_application_submitted SET document_scan_flag=? , document_scan_date=? WHERE doc_reg_no=?", array('Y', date('Y-m-d H:i:s'), $docid));
                echo "lblsavemsg";
            } else if ($message && $save2 && $_POST['saveflag'] == 'U') {
                echo "lbleditmsg";
            } else {
                echo "Document Failed...Please try again...!!!";
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//=================================== Scanner End=================================================================
//=================================== Scanner Start=================================================================
//    public function scan($tokennumber = NULL, $view_flag = NULL) {
//        try {
//            if (is_null($tokennumber)) {
//                $token = $this->Session->read("reg_token");
//            } else {
//                $token = $tokennumber;
//            }
//            $this->check_function_hierarchy($this->request->params['action'], $token);
//            $this->loadModel('file_config');
//            $this->loadModel('ApplicationSubmitted');
//            $this->set('rval', 'SC');
//            $office_id = $this->Session->read("office_id");
//
//            $path = $this->file_config->find('first', array('fields' => array('filepath')));
//            $createFolder = $path['file_config']['filepath'] . "Scanned";
//            if (!file_exists($createFolder)) {
//                mkdir($createFolder, 0744, true); // creates folder if  not found
//            }
//            $this->set('path', $createFolder);
//            $this->set('token', $token);
//            $this->set('view_flag', $view_flag);
//
//            $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $token));
//            $application = $application[0][0];
//
//            if ($application['document_scan_flag'] == 'Y') {
////                $docid = $this->request->data['scan']['filename'];
//                $filepath = $createFolder . "/" . base64_decode($application['doc_reg_no']) . ".pdf";
//
//                if (file_exists($filepath)) {
//                    $this->set('filename', $this->request->data['scan']['filename']);
//                } else {
//                    $this->Session->setFlash(__("This Document is NOT Exist...!!!"));
//                }
//            }
//            if ($this->request->is('post')) {
//                $check = $this->ApplicationSubmitted->query("select count(token_no) from ngdrstab_trn_scanuploadinfo WHERE token_no=? ", array($token));
//                if ($check[0][0]['count'] != 0) {
//                    $updatecheck = $this->ApplicationSubmitted->query("update ngdrstab_trn_scanuploadinfo set conf_flag='Y' WHERE token_no=? ", array($token));
//                    $this->Session->setFlash(__("Document Uploaded Permenently"));
//                } else {
//                    $this->Session->setFlash(__("Document not Scanned Properly"));
//                }
//            }
//        } catch (Exception $ex) {
//            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
//
//    public function index_scan() {
//        try {
//            $this->loadModel("ApplicationSubmitted");
//            $this->loadModel("article");
//            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
//            $this->Session->write("office_id", $this->Auth->user('office_id'));
//            $office_id = $this->Session->read("office_id");
//            $lang = $this->Session->read('sess_langauge');
//
//            if (isset($office_id) && is_numeric($office_id)) {
//                $stamp_conf = $this->stamp_and_functions_config();
//                foreach ($stamp_conf as $stamp) {
//                    if ($stamp['is_last'] == 'Y') {
//                        $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
//                    }
//                }
//                $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$lang,
//                            appoint.appointment_date,appoint.sheduled_time
//                            FROM ngdrstab_trn_application_submitted app
//                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
//                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
//                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
//                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
//                            where app.office_id=? and check_in_flag=? and document_scan_flag=? or app.office_id=? and check_in_flag=? and  $check_stamp_flag=?", array($office_id, 'Y', 'N', $office_id, 'Y', 'N')));
//            }
//            $this->set(compact('lang', 'stamp_conf'));
//        } catch (Exception $exc) {
//            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
//
//    public function checkscan() {
//        try {
//            $this->autoRender = false;
//            $this->loadModel('scan_upload');
//            if (isset($_POST['path']) && isset($_POST['docid'])) {
//                $filename = $_POST['path'] . "/" . base64_encode($_POST['docid']) . ".pdf";
//                $file = $this->scan_upload->query("SELECT count(scan_name) FROM ngdrstab_trn_scanuploadinfo WHERE scan_name=? and conf_flag=?", array($filename, 'N'));
//                if ($file[0][0]['count'] != 0) {
//                    echo json_encode(1);   //file exist 
//                    exit;
//                } else {
//                    echo json_encode(2);
//                    exit;
//                }
//            }
//        } catch (Exception $e) {
//            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
//
//    public function loadfile($fname) {
//        $this->autoRender = false;
//        $this->loadModel('file_config');
//        $path = $this->file_config->find('first', array('fields' => array('filepath')));
//        $path = $path['file_config']['filepath'] . "Scanned/" . base64_encode($fname) . ".pdf";
//        return $this->response->file($path, array('download' => false, 'name' => 'samplefile'));
//    }
//
//    public function upload() {
//        try {
////            $this->response->header(array(
////            'Access-Control-Allow-Origin' => '*',
////            'Access-Control-Allow-Headers' => 'Content-Type'
////        )
////    );
////      $this->autoRender=FALSE;  
////            pr($this->request->data);;exit;
//            $this->autoRender = false;
//            $this->loadModel('scan_upload');
//            $this->loadModel('file_config');
//            $this->loadModel('ApplicationSubmitted');
//            $path = $this->file_config->find('first', array('fields' => array('filepath')));
//            $createFolder = $path['file_config']['filepath'] . "Scanned";
//            if (!file_exists($createFolder)) {
//                mkdir($createFolder, 0744, true); // creates folder if  not found
//            }
//            $token = $_POST['token'];
//            $pages = $this->ApplicationSubmitted->query("SELECT no_of_pages FROM ngdrstab_trn_generalinformation WHERE token_no=? ", array($token));
//            $pages = $pages[0][0]['no_of_pages'];
//            $pdfname = $_FILES['asprise_scans']['tmp_name'];
//            $pdftext = file_get_contents($pdfname);
//            $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
//            if ($num != $pages) {
//                if ($num < $pages) {
//                    $misspages = $pages - $num;
//                    echo "Document required $pages pages. You have Scanned $num pages. $misspages pages are Missing in this Document.";
//                    return;
//                } else if ($num > $pages) {
//                    $misspages = $num - $pages;
//                    echo "Document required $pages pages. You have Scanned $num pages. $misspages pages are more added in this Document.";
//                    return;
//                }
//            }
//
//            $docid = $_POST['docid'];
//            $udir = $createFolder . "/" . base64_encode($docid) . ".pdf";
//            $newname = base64_encode($docid) . ".pdf";
//            $save1 = move_uploaded_file($_FILES['asprise_scans']['tmp_name'], $udir);
//            $stateid = $this->Auth->User('state_id');
//            $userid = $this->Auth->User('user_id');
//            $data['doc_reg_no'] = $_POST['docid'];
//            $data['token_no'] = $token;
//            $data['scan_name'] = $udir;
//            $data['state_id'] = $stateid;
//            $data['user_id'] = $userid;
//            $data['created_date'] = date('Y/m/d H:i:s');
//            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
//            if ($_POST['saveflag'] == 'U') {
//                $check = $this->scan_upload->query("SELECT id FROM ngdrstab_trn_scanuploadinfo where doc_reg_no=?", array($_POST['docid']));
//                $data['id'] = $check[0][0]['id'];
//            }
//            $save2 = $this->scan_upload->save($data);
//
//            if ($save1 && $save1 && $_POST['saveflag'] == 'S') {
//                $this->ApplicationSubmitted->query("UPDATE ngdrstab_trn_application_submitted SET document_scan_flag=? , document_scan_date=? WHERE doc_reg_no=?", array('Y', date('Y-m-d H:i:s'), $docid));
//                echo "Document Saved Successfully...!!!";
//            } else if ($save1 && $save1 && $_POST['saveflag'] == 'U') {
//                echo "Document Updated Successfully...!!!";
//            } else {
//                echo "Document Failed...Please try again...!!!";
//            }
//        } catch (Exception $exc) {
//            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage()));
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }
//=================================== Scanner End=================================================================


    public function document_index() {
        $this->check_role_escalation_tab();
        if ($this->request->is('post')) {
            $from = date('Y-m-d', strtotime($this->request->data['doc_index']['from']));
            return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex', $from));
        }
    }

    public function documentindex() {
        $this->check_role_escalation_tab();
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("article");
            $this->loadModel("conf_reg_bool_info");
            $this->loadModel("regconfig");
            $this->loadModel("office");

            // reset sessions 
            $this->Session->write("reg_token", NULL);
            $this->Session->write("reg_record_no", NULL);
            $this->Session->write("citizen_user_id", NULL);
            $this->Session->write("Selectedtoken", NULL);
            $this->Session->write("selectedarticle_id", NULL);

            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read('sess_langauge');
            $user_id = $this->Auth->user("user_id");
            if ($lang == 'en') {
                $this->Session->write("doc_lang", 'en');
            } else {
                $this->Session->write("doc_lang", 'll');
            }
            $doc_lang = $this->Session->read("doc_lang");

            $inspectionflag = 0;
            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 40)));
            if (!empty($regconf)) {
                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                    $inspectionflag = 1;
                }
            }
            $this->set("inspectionflag", $inspectionflag);
            $regconf_appointment = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 85, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $fieldlist = array();
            if (!empty($regconf_appointment)) {
                $fieldlist['from']['text'] = 'is_required';
                $this->set("fieldlist", $fieldlist);
                $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            }

            $regconf_search_date = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 149, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $search_date_minus = '-1y';
            $search_date_plus = '+1y';
            if (!empty($regconf_search_date)) {
                if (!empty($regconf_search_date['regconfig']['info_value'])) {
                    $regconf_search_date_arr = explode("|", $regconf_search_date['regconfig']['info_value']);
                    if (is_array($regconf_search_date_arr) && count($regconf_search_date_arr) == 2) {
                        $search_date_minus = $regconf_search_date_arr[0];
                        $search_date_plus = $regconf_search_date_arr[1];
                    }
                }
            }


            if ($this->request->is('post')) {
                $errarr = $this->validatedata($this->request->data['documentindex'], $fieldlist);
                if ($this->validationError($errarr)) {
                    $regconf_user_id = $this->office->find("first", array('conditions' => array('office_id' => $this->Auth->user("office_id"), 'is_virtual_office' => 'Y')));
                    $cond_user = '';
                    if (!empty($regconf_user_id)) {
                        $cond_user = ' and info.sro_user_id= ' . $user_id . ' ';
                    }

                    if (!empty($this->request->data['documentindex']['from'])) {
                        $from = date('Y-m-d', strtotime($this->request->data['documentindex']['from']));
                        $datecheckflag = $this->date_compaire($search_date_minus, $search_date_plus, $this->request->data['documentindex']['from']);

                        if ($datecheckflag) {

                            if (isset($office_id) && is_numeric($office_id)) {
                                $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.appointment_date,appoint.sheduled_time,appoint.slot_no
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no  " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no and app.office_id=appoint.office_id
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                           where app.office_id=? and check_in_flag=? and Date(appoint.appointment_date)=? order by  appoint.interval_id, appoint.slot_no ASC", array($office_id, 'N', date($from))));
                            }
                        } else {
                            $this->Session->setFlash(__('Checkin not allowed for this date')
                            );
                        }
                    } else {
                        if (isset($office_id) && is_numeric($office_id)) {
                            $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time,appoint.slot_no
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no  " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no 
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                           where app.office_id=? and check_in_flag=? and appoint.appointment_date IS NULL ", array($office_id, 'N')));
                        }
                    }
                }
            }



            $stamp_conf = $this->stamp_and_functions_config();

            $this->set(compact('doc_lang', 'stamp_conf', 'search_date_minus', 'search_date_plus'));
        } catch (Exception $exc) {

            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function inspection_status($token) {
        $this->loadModel("property_details_entry");
        $this->loadModel("inspection");
        $lang = $this->Session->read('sess_langauge');
        $checkproplist = $this->property_details_entry->find("all", array('conditions' => array('token_no' => $token, 'val_id !=' => 0)));
        // pr($checkproplist);exit;
        if (!empty($checkproplist)) {
            $eachflag = 1;
            foreach ($checkproplist as $property) {
                $property_id = $property['property_details_entry']['id'];
                $property_token = $property['property_details_entry']['token_no'];
                $inspection = $this->inspection->find("all", array('conditions' => array('property_no' => $property_id, 'token_no' => $property_token, 'verified_flag' => 'Y')));
                if (empty($inspection)) {
                    $eachflag = 0;
                }
            }
            if ($eachflag == 0) {
                return 1; // Inspection pending
            } else {
                return 2; // Inspection Done  
            }
        } else {
            return 0; // NA
        }
    }

    public function document_index2() {
        $this->check_role_escalation_tab();
        if ($this->request->is('post')) {
            $from = date('Y-m-d', strtotime($this->request->data['doc_index']['from']));
            return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex2', $from));
        }
    }

    public function documentindex2() {
        $this->check_role_escalation_tab();
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("article");
            $this->loadModel("regconfig");
            $this->loadModel("office");
            // reset sessions 
            $this->Session->write("reg_token", NULL);
            $this->Session->write("reg_record_no", NULL);
            $this->Session->write("citizen_user_id", NULL);
            $this->Session->write("Selectedtoken", NULL);
            $this->Session->write("selectedarticle_id", NULL);
            $this->Session->write("article_id", NULL);

            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $office_id = $this->Session->read("office_id");
            $user_id = $this->Auth->user("user_id");
            $lang = $this->Session->read('sess_langauge');
            if ($lang == 'en') {
                $this->Session->write("doc_lang", 'en');
            } else {
                $this->Session->write("doc_lang", 'll');
            }
            $doc_lang = $this->Session->read("doc_lang");
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 41, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));

            $regconf_appointment = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 85, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $fieldlist = array();
            if (!empty($regconf_appointment)) {
                $fieldlist['from']['text'] = 'is_required';
                $this->set("fieldlist", $fieldlist);
                $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            }
            $regconf_search_date = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 150, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $search_date_minus = '-1y';
            $search_date_plus = '+1y';
            if (!empty($regconf_search_date)) {
                if (!empty($regconf_search_date['regconfig']['info_value'])) {
                    $regconf_search_date_arr = explode("|", $regconf_search_date['regconfig']['info_value']);
                    if (is_array($regconf_search_date_arr) && count($regconf_search_date_arr) == 2) {
                        $search_date_minus = $regconf_search_date_arr[0];
                        $search_date_plus = $regconf_search_date_arr[1];
                    }
                }
            }

            if ($this->request->is('post')) {
                $errarr = $this->validatedata($this->request->data['documentindex2'], $fieldlist);
                if ($this->validationError($errarr)) {

                    $regconf_user_id = $this->office->find("first", array('conditions' => array('office_id' => $this->Auth->user("office_id"), 'is_virtual_office' => 'Y')));
                    $cond_user = '';
                    if (!empty($regconf_user_id)) {
                        $cond_user = ' and info.sro_user_id= ' . $user_id . ' ';
                    }
                    if (!empty($this->request->data['documentindex2']['from'])) {
                        $from = date('Y-m-d', strtotime($this->request->data['documentindex2']['from']));
                        $datecheckflag = $this->date_compaire($search_date_minus, $search_date_plus, $this->request->data['documentindex2']['from']);
                        if ($datecheckflag) {
                            if (isset($office_id) && is_numeric($office_id)) {
                                if (!empty($regconf)) {
                                    $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time,mststatus.document_status_desc_$lang,appoint.slot_no
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no  " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_trn_document_status trnstatus ON trnstatus.token_no=app.token_no  and trnstatus.id=(SELECT MAX(ID) FROM ngdrstab_trn_document_status WHERE token_no=app.token_no)
                            join ngdrstab_mst_document_status_description mststatus ON mststatus.id=trnstatus.status_id   
                            where app.office_id=? and check_in_flag=? and document_scan_flag=? and Date(appoint.appointment_date)=? or app.office_id=? and check_in_flag=? and  final_stamp_flag=? and Date(appoint.appointment_date)=? or app.office_id=? and check_in_flag=? and  esign_flag=? and Date(appoint.appointment_date)=? order by  appoint.interval_id, appoint.slot_no ASC", array($office_id, 'Y', 'N', Date($from), $office_id, 'Y', 'N', Date($from), $office_id, 'Y', 'N', Date($from))));
                                } else {
                                    $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time,mststatus.document_status_desc_$lang,appoint.slot_no
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no  " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_trn_document_status trnstatus ON trnstatus.token_no=app.token_no  and trnstatus.id=(SELECT MAX(ID) FROM ngdrstab_trn_document_status WHERE token_no=app.token_no)
                            join ngdrstab_mst_document_status_description mststatus ON mststatus.id=trnstatus.status_id   
                            
                            where app.office_id=? and check_in_flag=? and document_scan_flag=? and Date(appoint.appointment_date)=? or app.office_id=? and check_in_flag=? and  final_stamp_flag=? and Date(appoint.appointment_date)=? order by  appoint.interval_id, appoint.slot_no ASC", array($office_id, 'Y', 'N', Date($from), $office_id, 'Y', 'N', Date($from))));
                                }
                            }
                        } else {
                            $this->Session->setFlash(__('Registration not allowed for this date')
                            );
                        }
                    } else {
                        if (isset($office_id) && is_numeric($office_id)) {
                            if (!empty($regconf)) {
                                $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time,mststatus.document_status_desc_$lang
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no   " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_trn_document_status trnstatus ON trnstatus.token_no=app.token_no  and trnstatus.id=(SELECT MAX(ID) FROM ngdrstab_trn_document_status WHERE token_no=app.token_no)
                            join ngdrstab_mst_document_status_description mststatus ON mststatus.id=trnstatus.status_id   
                            where app.office_id=? and check_in_flag=? and document_scan_flag=? and appoint.appointment_date IS NULL or app.office_id=? and check_in_flag=? and  final_stamp_flag=? and  appoint.appointment_date IS NULL or app.office_id=? and check_in_flag=? and  esign_flag=? and  appoint.appointment_date IS NULL ", array($office_id, 'Y', 'N', $office_id, 'Y', 'N', $office_id, 'Y', 'N')));
                            } else {
                                $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time,mststatus.document_status_desc_$lang
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no  " . $cond_user . "
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_trn_document_status trnstatus ON trnstatus.token_no=app.token_no  and trnstatus.id=(SELECT MAX(ID) FROM ngdrstab_trn_document_status WHERE token_no=app.token_no)
                            join ngdrstab_mst_document_status_description mststatus ON mststatus.id=trnstatus.status_id   
                            
                            where app.office_id=? and check_in_flag=? and document_scan_flag=? and  appoint.appointment_date IS NULL or app.office_id=? and check_in_flag=? and  final_stamp_flag=? and  appoint.appointment_date IS NULL ", array($office_id, 'Y', 'N', $office_id, 'Y', 'N')));
                            }
                        }
                    }
                }
            }

            $stamp_conf = $this->stamp_and_functions_config();
            // pr($stamp_conf);exit;
            $this->set(compact('doc_lang', 'stamp_conf', 'regconf', 'lang', 'search_date_minus', 'search_date_plus'));
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function stamp_and_functions_config() {
        $this->loadModel('role');
        $this->loadModel('RegistrationSubmenu');
        $this->loadModel('RegistrationSubsubmenu');
        $this->loadModel('RegistrationScreenMapping');
        $lang = $this->Session->read("sess_langauge");
        $roles = $this->role->find("list", array('fields' => array('role_id', 'role_name_' . $lang)));
        $stamp_conf = array();
        $stamps = $this->RegistrationSubmenu->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id')), 'order' => 'sm_serial ASC'));
        foreach ($stamps as $key => $values) {
            if ($values['RegistrationSubmenu']['is_stamp'] == 'Y') {
                $stamp_conf[$key]['submenu_id'] = $values['RegistrationSubmenu']['submenu_id'];
                $stamp_conf[$key]['sm_serial'] = $values['RegistrationSubmenu']['sm_serial'];
                $stamp_conf[$key]['stamp_title'] = 'stamp' . $values['RegistrationSubmenu']['stamp_id'];
                $stamp_conf[$key]['stamp_desc'] = $values['RegistrationSubmenu']['submenu_desc_en'];
                $stamp_conf[$key]['stamp_flag'] = 'stamp' . $values['RegistrationSubmenu']['stamp_id'] . '_flag';
                $stamp_conf[$key]['submenu_desc_en'] = $values['RegistrationSubmenu']['submenu_desc_en'];
            }
        }

        if (!empty($stamp_conf)) {
            $firststamp = reset($stamp_conf);
            $laststamp = end($stamp_conf);
        }
        foreach ($stamp_conf as $stampkey2 => $stampval) {
            if ($stampval['submenu_id'] == $laststamp['submenu_id']) {
                $stamp_conf[$stampkey2]['is_last'] = 'Y';
            } else {
                $stamp_conf[$stampkey2]['is_last'] = 'N';
            }

            if ($stampval['submenu_id'] == $firststamp['submenu_id']) {
                $stamp_conf[$stampkey2]['is_first'] = 'Y';
            } else {
                $stamp_conf[$stampkey2]['is_first'] = 'N';
            }
        }

        $article = $this->Session->read('selectedarticle_id');
        if (!is_null($article)) {
            $menusc = $this->RegistrationSubsubmenu->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('is_optional' => 'N', 'state_id' => $this->Auth->user('state_id')), 'order' => 'ssm_serial ASC'));
            $menuso = $this->RegistrationScreenMapping->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('article_id' => $article)));
            $ids = array_merge($menusc, $menuso);
            $functions = $this->RegistrationSubsubmenu->find('all', array(
                'conditions' => array('subsubmenu_id' => $ids)
            ));
        } else {
            $functions = $this->RegistrationSubsubmenu->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id')), 'order' => 'ssm_serial ASC'));
        }

        foreach ($stamp_conf as $stampkey => $singlestamp) {

            foreach ($functions as $funkey => $function) {
                if ($singlestamp['submenu_id'] == $function['RegistrationSubsubmenu']['submenu_id']) {
                    $stamp_conf[$stampkey]['functions'][$funkey]['submenu_id'] = $function['RegistrationSubsubmenu']['submenu_id'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['subsubmenu_id'] = $function['RegistrationSubsubmenu']['subsubmenu_id'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['ssm_serial'] = $function['RegistrationSubsubmenu']['ssm_serial'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['function_sr_no'] = $function['RegistrationSubsubmenu']['function_sr_no'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['function_flag'] = 'fun' . $function['RegistrationSubsubmenu']['function_sr_no'] . '_flag';
                    $stamp_conf[$stampkey]['functions'][$funkey]['function_title'] = 'fun' . $function['RegistrationSubsubmenu']['function_sr_no'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['function_desc'] = $function['RegistrationSubsubmenu']['subsubmenu_desc_en'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['action'] = $function['RegistrationSubsubmenu']['action'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['work_flow'] = $function['RegistrationSubsubmenu']['function_hierarchy'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['role_id'] = $function['RegistrationSubsubmenu']['role_id'];
                    $stamp_conf[$stampkey]['functions'][$funkey]['role'] = $roles[$function['RegistrationSubsubmenu']['role_id']];
                    $stamp_conf[$stampkey]['functions'][$funkey]['btnaccept'] = 'lblfunaccept' . $function['RegistrationSubsubmenu']['function_sr_no'];
                }
            }
            if (isset($stamp_conf[$stampkey]['functions'])) {
                $lastfun = end($stamp_conf[$stampkey]['functions']);
                $firstfun = reset($stamp_conf[$stampkey]['functions']);
                foreach ($stamp_conf[$stampkey]['functions'] as $funkey2 => $funval) {
                    if ($funval['subsubmenu_id'] == $lastfun['subsubmenu_id']) {
                        $stamp_conf[$stampkey]['functions'][$funkey2]['is_last'] = 'Y';
                    } else {
                        $stamp_conf[$stampkey]['functions'][$funkey2]['is_last'] = 'N';
                    }

                    if ($funval['subsubmenu_id'] == $firstfun['subsubmenu_id']) {
                        $stamp_conf[$stampkey]['functions'][$funkey2]['is_first'] = 'Y';
                    } else {
                        $stamp_conf[$stampkey]['functions'][$funkey2]['is_first'] = 'N';
                    }
                }
            }
        }

//        pr($stamp_conf);exit;
        return $stamp_conf;
    }

    public function update_stamp_function_flags($action = NULL, $extrafields = NULL) {
        $this->loadModel('ApplicationSubmitted');
        $office_id = $this->Session->read("office_id");
        $token = $this->Session->read("reg_token");
        $now = date('Y-m-d H:i:s');
        $data['app_id'] = $this->Session->read("reg_record_no");

        if (!is_null($extrafields)) {
            $data = array_merge($data, $extrafields);
        }
        $currentstamp = array();
        $stampconfig = $this->stamp_and_functions_config();

        foreach ($stampconfig as $stamprec) {
            if (isset($stamprec['functions'])) {
                foreach ($stamprec['functions'] as $funrec) {
                    if ($funrec['action'] == $action) {
                        $currentstamp = $stamprec;
                        $data[$funrec['function_flag']] = "'Y'";
                        $data[$funrec['function_title'] . '_date'] = "'" . $now . "'";
                        $data = $this->add_default_fields_updateAll($data);
                        $this->ApplicationSubmitted->updateAll($data, array('token_no' => $token, 'office_id' => $office_id, $funrec['function_flag'] => 'N'));
                        $this->save_step_ips($token, $funrec['function_desc']);
                    }
                }
            }
        }
        $stampoptios = array('token_no' => $token, 'office_id' => $office_id, 'app_id' => $this->Session->read("reg_record_no"));
        foreach ($currentstamp['functions'] as $funrec) {
            $stampoptios[$funrec['function_flag']] = 'Y';
        }

        $stampstatus = $this->ApplicationSubmitted->find("first", array('conditions' => $stampoptios));

        if (!empty($stampstatus)) {
            $stampupdate['app_id'] = $this->Session->read("reg_record_no");
            $stampupdate[$currentstamp['stamp_flag']] = "'Y'";
            $stampupdate[$currentstamp['stamp_title'] . '_date'] = "'" . $now . "'";
            $stampupdate = $this->add_default_fields_updateAll($stampupdate);
            $stampoptios[$currentstamp['stamp_flag']] = 'N';
            $this->ApplicationSubmitted->updateAll(
                    $stampupdate, $stampoptios
            );
        }
    }

    public function save_step_ips($token = NULL, $step_no = NULL) {
        if (isset($token) && is_numeric($token) && !is_null($step_no)) {
            $this->loadModel('ApplicationSubmitted');
            $stateid = $this->Auth->User('state_id');
            $userid = $this->Auth->User('user_id');
            $createddate = date('Y-m-d H:i:s');
            $ip_add = $_SERVER['REMOTE_ADDR'];
            $this->ApplicationSubmitted->query("insert into ngdrstab_trn_registration_step_ips (token_no, step_no, user_id, created, state_id, req_ip)"
                    . "values(?,?,?,?,?,?)", array($token, $step_no, $userid, $createddate, $stateid, $ip_add));
        }
    }

    public function reset_stamp_function_flags($action = NULL, $extrafields = NULL) {
        $this->loadModel('ApplicationSubmitted');
        $office_id = $this->Session->read("office_id");
        $token = $this->Session->read("reg_token");
        $now = date('Y-m-d H:i:s');
        $data['app_id'] = $this->Session->read("reg_record_no");
        if (!is_null($extrafields)) {
            foreach ($extrafields as $keyfield => $fieldval) {
                $data[$keyfield] = $fieldval;
            }
        }
        $currentstamp = array();
        $stampconfig = $this->stamp_and_functions_config();
        foreach ($stampconfig as $stamprec) {
            foreach ($stamprec['functions'] as $funrec) {
                if ($funrec['action'] == $action) {
                    $currentstamp = $stamprec;
                    $data[$funrec['function_flag']] = 'N';
                    $data[$funrec['function_title'] . '_date'] = "'" . $now . "'";
                    $data = $this->add_default_fields_updateAll($data);
                    $this->ApplicationSubmitted->saveAssociated($data, array('token_no' => $token, 'office_id' => $office_id));
                }
            }
        }
        $stampoptios = array('token_no' => $token, 'office_id' => $office_id, 'app_id' => $this->Session->read("reg_record_no"));
        foreach ($currentstamp['functions'] as $funrec) {
            $stampoptios[$funrec['function_flag']] = 'Y';
        }

        $stampstatus = $this->ApplicationSubmitted->find("first", array('conditions' => $stampoptios));

        if (empty($stampstatus)) {
            $stampupdate['app_id'] = $this->Session->read("reg_record_no");
            $stampupdate[$currentstamp['stamp_flag']] = 'N';
            $stampupdate[$currentstamp['stamp_title'] . '_date'] = "'" . $now . "'";
            $stampupdate = $this->add_default_fields_updateAll($stampupdate);
            $this->ApplicationSubmitted->saveAssociated($stampupdate, $stampoptios);
        }
    }

    public function document_select($token = NULL) {

        try {
            $this->loadModel('RegistrationSubsubmenu');
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("mainlanguage");
            $this->loadModel('RegistrationScreenMapping');
            $this->Session->write('biomatch', NULL);
            $office_id = $this->Session->read("office_id");
            if (isset($token) && is_numeric($token) && isset($office_id) && is_numeric($office_id)) {
                $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id ,info.article_id ,presentation_date,exec_date FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $token));

                if (!empty($application)) {
                    $returnflag = $this->validate_application_document($application);
                    if ($returnflag == 1) {
                        $Fine = new FineController();
                        $Fine->constructClasses();
                        $checkfine = $Fine->calculate_fine($token);
                        if (!is_numeric($checkfine)) {
                            $this->Session->setFlash(__('Error In Fine Calculation : ' . $checkfine));
                            return $this->redirect('documentindex2');
                        }

                        $application = $application['0']['0'];
                        $this->Session->write("reg_token", $application['token_no']);
                        $this->Session->write("reg_record_no", $application['app_id']);
                        $this->Session->write("citizen_user_id", $application['citizen_user_id']);
                        $this->Session->write("Selectedtoken", $application['token_no']);
                        $this->Session->write("selectedarticle_id", $application['article_id']);
                        $this->Session->write("article_id", $application['article_id']);

                        $doc_language = $this->mainlanguage->find("all", array('conditions' => array('id' => $application['local_language_id'])));
                        if (!empty($doc_language)) {
                            $doc_language = $doc_language[0]['mainlanguage'];
                            if ($doc_language['language_code'] == 'en') {
                                $this->Session->write("doc_lang", $doc_language['language_code']);
                            } else {
                                $this->Session->write("doc_lang", 'll');
                            }
                            $this->Session->write('sess_langauge', $doc_language['language_code']);
                            CakeSession::write('Config.language', $doc_language['language_code']);
                        } else {
                            $this->Session->write("doc_lang", NULL);
                        }

                        $menusc = $this->RegistrationSubsubmenu->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('is_optional' => 'N', 'state_id' => $this->Auth->user('state_id')), 'order' => 'ssm_serial ASC'));
                        $menuso = $this->RegistrationScreenMapping->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('article_id' => $application['article_id'])));
                        $ids = array_merge($menusc, $menuso);
                        $funresult = $this->RegistrationSubsubmenu->find('all', array(
                            'conditions' => array('subsubmenu_id' => $ids, 'role_id' => $this->Session->read("user_role_id")),
                            'order' => 'function_order ASC'
                        ));
                        //   $funresult = $this->RegistrationSubsubmenu->query("SELECT function_sr_no,controller,action FROM ngdrstab_mst_registration_subsubmenu where role_id=? and subsubmenu_id IN (? ORDER BY function_sr_no ASC ", array($this->Session->read("user_role_id"),$ids));
                        // To Check   function   Completed      
//                    pr($application);
//                    pr($funresult);exit;
                        foreach ($funresult as $function) {
                            // pr($function);exit;
                            $function = $function['RegistrationSubsubmenu'];
                            if ($application['fun' . $function['function_sr_no'] . '_flag'] == 'N') {
                                $this->redirect($function['action']);
                            }
                        }
                        if (isset($funresult[0]['RegistrationSubsubmenu']['function_sr_no'])) {
                            $this->redirect($funresult[0]['RegistrationSubsubmenu']['action']);  //if all allocated functions flag=y them rediret to first function
                        }

                        $this->Session->setFlash(__('Functions Not Configured'));
                        return $this->redirect('documentindex2');
                    } else {
                        $this->Session->setFlash(__('Not able to Process.Please Check Document Status!'));
                        return $this->redirect('documentindex2');
                    }
                }
            }
            $this->Session->setFlash(__('Check In Token Not Found!'));
            return $this->redirect('documentindex2');
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    /*
     * IGR Maharshtra Requirement
     * DEMO 05-01-2018
     *     */

    function validate_application_document($application = NULL) {
        $this->loadModel('regconfig');
        $this->loadModel('trndocumentstatus');
        $returnflag = 1;
        $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 65, 'is_boolean' => 'N', 'conf_bool_value' => 'Y')));
        $monthsconf = $regconf[0]['regconfig']['info_value'] ? $regconf[0]['regconfig']['info_value'] : -1;
        if (!empty($application) && $application[0][0]['final_stamp_flag'] == 'N') {
            if (!empty($application[0][0]['presentation_date']) && empty($application[0][0]['final_stamp_date'])) {
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($application[0][0]['presentation_date']);
                $interval = date_diff($datetime1, $datetime2);
                $months = $interval->y * 12 + $interval->m;
                if ($interval->d > 0) {
                    $months++;
                }
                // pr($months);exit;
                if ($months > $monthsconf) {
                    $check = $this->trndocumentstatus->find('first', array('conditions' => array('token_no' => $application[0][0]['token_no']), 'order' => 'id DESC'));
                    if (!empty($check)) {
                        $this->save_documentstatus(7, $application[0][0]['token_no'], $application[0][0]['office_id']);
                    } else if ($check['trndocumentstatus']['status_id'] != 7) {
                        $this->save_documentstatus(7, $application[0][0]['token_no'], $application[0][0]['office_id']);
                    }
                    $returnflag = 0;
                }
            }
        }
        return $returnflag;
    }

    public function document_edit($token = NULL) {
        try {
            $this->loadModel('genernalinfoentry');
            $this->loadModel('ApplicationSubmitted');
            $this->Session->write("manual_flag", 'N');

            $office_id = $this->Session->read("office_id");
            if (isset($token) && is_numeric($token) && isset($office_id) && is_numeric($office_id)) {
                $application = $this->ApplicationSubmitted->query("SELECT app.*,article.*,info.user_id As citizen_user_id FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $token));
                $application = $application['0']['0'];
                if (!empty($application)) {
                    $this->Session->write("citizen_user_id", $application['citizen_user_id']);
                }
                $this->set_csrf_token();
                return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            $this->Session->setFlash(__('Check In Token Not Found!'));
            return $this->redirect('documentindex');
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_function_hierarchy($action, $tokennumber = NULL) {
        $this->loadModel('RegistrationSubsubmenu');
        $this->loadModel("ApplicationSubmitted");
        $office_id = $this->Session->read("office_id");
        if (is_null($tokennumber)) {
            $token = $this->Session->read("reg_token");
        } else {
            $token = $tokennumber;
        }
        $lang = $this->Session->read("sess_langauge");

        $application = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'office_id' => $office_id)));
        $funresult = $this->RegistrationSubsubmenu->query("SELECT subsubmenu_desc_$lang,function_hierarchy,controller,action FROM ngdrstab_mst_registration_subsubmenu where action=? and role_id=?", array($action, $this->Session->read("user_role_id")));

        if (!empty($application)) {
            $application = $application[0]['ApplicationSubmitted'];

            if (!empty($funresult)) {// To Check all function are Completed  
                $hierarchy = explode("-", $funresult[0][0]['function_hierarchy']);
                foreach ($hierarchy as $function) {
                    if (is_numeric($function) && $application['fun' . $function . '_flag'] == 'N') {
                        $redirect_result = $this->RegistrationSubsubmenu->query("SELECT *  FROM ngdrstab_mst_registration_subsubmenu where function_sr_no=? ", array($function));
                        $this->Session->setFlash(__($redirect_result[0][0]['subsubmenu_desc_' . $lang] . " Should be Completed"));
                        return $this->redirect($redirect_result[0][0]['action']); // redirect to uncompleted page
                    }
                }
            } else {
                $this->Session->setFlash(__('Function not available role')); // not exist in role redirect to referer page
                return $this->redirect($this->referer());
            }
        } else {
            $this->Session->setFlash(__('Token Not Found'));
            return $this->redirect($this->referer());
        }
    }

    public function check_function_hierarchy_new($action, $tokennumber = NULL) {
        $this->loadModel('RegistrationSubsubmenu');
        $this->loadModel("ApplicationSubmitted");
        $office_id = $this->Session->read("office_id");
        $article_id = $this->Session->read("selectedarticle_id");
        if (is_null($tokennumber)) {
            $token = $this->Session->read("reg_token");
        } else {
            $token = $tokennumber;
        }
        $lang = $this->Session->read("sess_langauge");

        $application = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'office_id' => $office_id)));
        $funresult = $this->RegistrationSubsubmenu->query("SELECT subsubmenu_desc_$lang,function_hierarchy,controller,action FROM ngdrstab_mst_registration_subsubmenu where action=? and role_id=?", array($action, $this->Session->read("user_role_id")));

        if (!empty($application)) {
            $application = $application[0]['ApplicationSubmitted'];

            if (!empty($funresult)) {// To Check all function are Completed  
                $hierarchy = explode("-", $funresult[0][0]['function_hierarchy']);
                foreach ($hierarchy as $function) {
                    if (is_numeric($function)) {
                        $optional = $this->RegistrationSubsubmenu->find('first', array('conditions' => array('function_sr_no' => $function, 'is_optional' => 'Y')));
                        if (!empty($optional)) {
                            $optional_check = $this->RegistrationSubsubmenu->query("select * from ngdrstab_mst_registration_screen_mapping where article_id=? and subsubmenu_id=?   ", array($article_id, $optional['RegistrationSubsubmenu']['subsubmenu_id']));
                            if (!empty($optional_check) && $application['fun' . $function . '_flag'] == 'N') {
                                $redirect_result = $this->RegistrationSubsubmenu->query("SELECT *  FROM ngdrstab_mst_registration_subsubmenu where function_sr_no=? ", array($function));
                                $this->Session->setFlash(__($redirect_result[0][0]['subsubmenu_desc_' . $lang] . " Should be Completed"));
                                return $this->redirect($redirect_result[0][0]['action']); // redirect to uncompleted page
                            }
                        } else if (is_numeric($function) && $application['fun' . $function . '_flag'] == 'N') {
                            $redirect_result = $this->RegistrationSubsubmenu->query("SELECT *  FROM ngdrstab_mst_registration_subsubmenu where function_sr_no=? ", array($function));
                            $this->Session->setFlash(__($redirect_result[0][0]['subsubmenu_desc_' . $lang] . " Should be Completed"));
                            return $this->redirect($redirect_result[0][0]['action']); // redirect to uncompleted page
                        } else {
                            $this->Session->setFlash(__('Function not available role')); // not exist in role redirect to referer page
                            return $this->redirect($this->referer());
                        }
                    }
                }
            } else {
                $this->Session->setFlash(__('Function not available role')); // not exist in role redirect to referer page
                return $this->redirect($this->referer());
            }
        } else {
            $this->Session->setFlash(__('Token Not Found'));
            return $this->redirect($this->referer());
        }
    }

    public function document_functions_check() {
        try {
            $this->loadModel('RegistrationSubsubmenu');
            $this->loadModel("ApplicationSubmitted");
            $office_id = $this->Session->read("office_id");
            $token = $this->Session->read("reg_token");
            $application = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'office_id' => $office_id)));
            $funresult = $this->RegistrationSubsubmenu->query("SELECT function_sr_no,controller,action FROM ngdrstab_mst_registration_subsubmenu where function_sr_no NOT IN( SELECT MAX(function_sr_no) FROM  ngdrstab_mst_registration_subsubmenu ) ORDER BY function_sr_no ASC ");

            if (!empty($application)) {
                $application = $application[0]['ApplicationSubmitted'];
                // To Check all function are Completed Before Final Registration   
                foreach ($funresult as $function) {
                    $function = $function[0];
                    if ($application['fun' . $function['function_sr_no'] . '_flag'] == 'N') {
                        $this->redirect($function['action']);
                    }
                }
            }
            $this->Session->setFlash(__('Check In Token Not Found!'));
            return $this->redirect('documentindex');
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function profile() {
        
    }

    public function change_password() {
        
    }

    public function document_checkin($token = NULL) {
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("article");
            $office_id = $this->Session->read("office_id");
            $user_id = $this->Auth->user('user_id');
            if (is_numeric($token)) {
                $now = date('Y-m-d H:i:s');
                // match office id from Session  
                $this->ApplicationSubmitted->query("UPDATE ngdrstab_trn_application_submitted SET check_in_flag='Y',check_in_date=? ,org_user_id=?,org_updated=? WHERE token_no=?  AND office_id=? ", array($now, $user_id, $now, $token, $office_id));
            }
            $this->Session->setFlash(__('Check In Successfull'));
            return $this->redirect("documentindex2");
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function document_presentation() {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'article', 'regconfig', 'genernalinfoentry', 'valuation', 'uploaded_file_trn', 'upload_document', 'file_config', 'TrnCondonationOrder'));

            $office_id = $this->Session->read("office_id");
            $token = $this->Session->read("reg_token");
            $doc_lang = $this->Session->read("doc_lang");
            $lang = $this->Session->read("sess_langauge");
            $state_id = $this->Auth->user("state_id");
            $user_id = $this->Auth->user("user_id");
            $this->Session->write("sroidetifier", 'N');
            $this->Session->write("sroparty", 'N');
            $article_id = $this->Session->read("selectedarticle_id");

            $fieldlist['dataentryaccept']['document_entry_remark']['text'] = 'is_required,is_alphaspace';
            $fieldlist['dataentryaccept']['csrftoken']['text'] = 'is_required,is_alphanumeric';

            $fieldlist['condonation_order']['order_number']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['condonation_order']['order_issue_date']['text'] = 'is_required';
            $fieldlist['condonation_order']['order_remark']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';

            $this->set("fieldlistmultiform", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));

            $regconf_order = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 67, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));

            $info = $this->genernalinfoentry->find("all", array('conditions' => array('token_no' => $token)));
            $condonation_order_flag = 0;
            if (!empty($info) && !empty($regconf_order)) {
                $dateexe = $info[0]['genernalinfoentry']['exec_date'];
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($dateexe);
                $interval = date_diff($datetime1, $datetime2);
                $months = $interval->y * 12 + $interval->m;
                if ($interval->d > 0) {
                    $months++;
                }
                $conf_order_arr = explode(" ", $regconf_order[0]['regconfig']['info_value']);
                if ($conf_order_arr[1] == 'M') {
                    if ($months > $conf_order_arr[0]) {
                        $check_order = $this->TrnCondonationOrder->find("all", array('conditions' => array('token_no' => $token)));
                        if (empty($check_order)) {
                            $condonation_order_flag = 1;
                        }
                    }
                }
            }


            if ($this->request->is('post') && isset($this->request->data['presentation'])) {
                // Check condonation order is Given
                if ($condonation_order_flag == 1) {
                    $this->Session->setFlash(__('Please provide condonation order details!'));
                    return $this->redirect(array('action' => 'document_presentation'));
                }
                $errors = $this->validatedata($this->request->data['presentation'], $fieldlist['dataentryaccept']);
                //pr($errors);exit;
                if ($this->ValidationError($errors)) {
                    $this->check_csrf_token($this->request->data['presentation']['csrftoken']);


                    //Generate Document Number
                    $regconf_docno = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 111, 'is_boolean' => 'Y', 'conf_bool_value' => 'N')));
                    if (!empty($regconf_docno)) {
                        $docnumber = $this->generate_document_number();
                        if (strcmp($docnumber, '0') == 0) {
                            $this->Session->setFlash(__('Not able to generate document number'));
                            return $this->redirect('document_presentation');
                        }
                        $extrafields['doc_reg_no'] = "'" . $docnumber . "'";
                        $extrafields['doc_reg_date'] = "'" . date('Y-m-d H:i:s') . "'";
                        $this->valuation->updateAll(
                                array('doc_reg_no' => $extrafields['doc_reg_no']), array('token_no' => $token)
                        );
                    }


                    $extrafields['document_entry_remark'] = "'" . $this->request->data['presentation']['document_entry_remark'] . "'";
                    $this->update_stamp_function_flags($this->request->params['action'], $extrafields);


                    $cur_date = "'" . date('Y-m-d H:i:s') . "'";
                    $changestatus['last_status_id'] = 3;
                    $changestatus['last_status_date'] = $cur_date;
                    $changestatus['presentation_date'] = $cur_date;
                    $changestatus = $this->add_default_fields_updateAll($changestatus);
                    $this->genernalinfoentry->updateAll(
                            $changestatus, array('token_no' => $token)
                    );

                    // by madhuri
                    $this->save_documentstatus(5, $token, $office_id);

                    $this->Session->setFlash(
                            __('Presentation Completed !')
                    );
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'document_presentation'));
                } else {
                    $this->set("errarr", $errors);
                    $this->Session->setFlash(
                            __('Please Find Validation Errors!')
                    );
                }
            }
            if ($this->request->is('post') && isset($this->request->data['fileupload'])) {
                $this->check_csrf_token($this->request->data['fileupload']['csrftoken']);
                if (!isset($this->request->data['fileupload']['document_id']) || !is_numeric($this->request->data['fileupload']['document_id'])) {
                    $this->Session->setFlash(__('Please Select Document!'));
                    return $this->redirect(array('action' => 'document_presentation'));
                }
                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(__('Database Base Path Not Found!'));
                    return $this->redirect(array('action' => 'document_presentation'));
                }
                $basepath = $path['file_config']['filepath'];
                $check = $this->check_document_folder_structure($basepath, $token, $office_id);
                if ($check == 1) {
                    //$responce['ERROR']='';
                    $responce = $this->validatepdffile($this->request->data['fileupload']['upload_file']);
                    //pr($responce);exit;
                    if (empty($responce['ERROR'])) {
                        //if (1) {    
                        $office = $this->office->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                            ),
                            'conditions' => array('office.office_id' => $office_id)
                        ));
//pr($office);exit;
                        if (!empty($office) || is_null($office['district']['district_name_en']) || is_null($office['office']['taluka_id'])) {
                            $reqdata = $this->request->data['fileupload'];
                            $file_ext = pathinfo($reqdata['upload_file']['name'], PATHINFO_EXTENSION);
                            $destination = $basepath . "Documents/" . $office['district']['district_name_en'] . "/" . $office['office']['taluka_id'] . "/" . $office_id . "/" . $token . "/Uploads/" . $token . "_" . $reqdata['document_id'] . "." . $file_ext;
                            //pr($destination);exit;
                            $success = move_uploaded_file($reqdata['upload_file']['tmp_name'], $destination);

                            if ($success == 1) {

                                $upload_info = $this->uploaded_file_trn->find('first', array('conditions' => array('token_no' => $token, 'document_id' => $reqdata['document_id'])));
                                $data = array(
                                    'document_id' => $reqdata['document_id'],
                                    'input_fname' => $reqdata['upload_file']['name'],
                                    'out_fname' => $token . "_" . $reqdata['document_id'] . "." . $file_ext,
                                    'org_user_id' => $user_id,
                                    'user_type' => $this->Session->read("session_usertype"),
                                    'state_id' => $state_id,
                                    'req_ip' => $this->request->clientIp(),
                                    'token_no' => $this->Session->read("Selectedtoken")
                                );

                                if (count($upload_info) > 0) {
                                    $data['up_id'] = $upload_info['uploaded_file_trn']['up_id'];
                                    $data['org_updated'] = date('Y-m-d H:i:s');
                                    if ($this->uploaded_file_trn->save($data)) {
                                        $this->Session->setFlash(__("lbleditmsg"));
                                        $this->redirect(array('controller' => 'Registration', 'action' => 'document_presentation'));
                                    }
                                } else {
                                    $data['org_created'] = date('Y-m-d H:i:s');
                                    if ($this->uploaded_file_trn->save($data)) {
                                        $this->Session->setFlash(__("File Uploaded Successfully"));
                                        $this->redirect(array('controller' => 'Registration', 'action' => 'document_presentation'));
                                    }
                                }
                            }
                        } else {
                            $this->Session->setFlash(__("Office Not Found"));
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Error : ' . $responce['ERROR'])
                        );
                    }
                } else {
                    $this->Session->setFlash(
                            __('Upload Folder canot be created!')
                    );
                }
                $this->redirect(array('controller' => 'Registration', 'action' => 'document_presentation'));
            }

            if ($this->request->is('post') && isset($this->request->data['condonation_order'])) {
                $this->check_csrf_token($this->request->data['condonation_order']['csrftoken']);
                $rdata = $this->request->data['condonation_order'];
                $errors = $this->validatedata($this->request->data['condonation_order'], $fieldlist['condonation_order']);
                if ($this->ValidationError($errors)) {
                    $rdata = $this->add_default_fields($rdata);
                    $rdata['token_no'] = $token;
                    $rdata['order_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $rdata['order_issue_date'])));
                    if ($this->TrnCondonationOrder->save($rdata)) {
                        $this->Session->setFlash(__('lblsavemsg'));
                    } else {
                        $this->Session->setFlash(__('lblnotsavemsg'));
                    }
                    return $this->redirect(array('action' => 'document_presentation'));
                }
            }

            $document_list = $this->uploaded_file_trn->find('all', array('fields' => array('document.document_id', 'document.document_name_' . $lang, 'uploaded_file_trn.document_id', 'uploaded_file_trn.up_id', 'uploaded_file_trn.out_fname'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_upload_document', 'alias' => 'document', 'conditions' => array('document.document_id=uploaded_file_trn.document_id'))
                ),
                'conditions' => array('token_no' => $this->Session->read('Selectedtoken')),
                'order' => 'document.document_name_en,uploaded_file_trn.up_id'
            ));

            $upload_doc_list = $this->upload_document->find('list', array('fields' => array('upload_document.document_id', 'upload_document.document_name_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_document_mapping', 'alias' => 'mapping', 'conditions' => array('mapping.document_id=upload_document.document_id', 'mapping.article_id=' . $article_id)),
                ),
                'conditions' => array('partywise_flag' => 'N', 'upload_document.state_id' => $state_id)
            ));
            $mandatory_doc_list = $this->upload_document->find('list', array('fields' => array('upload_document.document_id', 'upload_document.document_name_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_article_document_mapping', 'alias' => 'mapping', 'conditions' => array('mapping.document_id=upload_document.document_id', 'mapping.article_id=' . $article_id)),
                ),
                'conditions' => array('mandatory_flag' => 'Y', 'upload_document.state_id' => $state_id)
            ));

            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->application_document($token, $doc_lang));
            }
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 37)));
            $report = new ReportsController();
            $report->constructClasses();
            $presummary = $report->pre_registration_docket(base64_encode($token), 'V');
            $stampconfig = $this->stamp_and_functions_config();
            $regconf_doc_edit = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 86, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $regconf_doc_upload = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 162, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $this->set(compact('regconf', 'presummary', 'stampconfig', 'doc_lang', 'document_list', 'upload_doc_list', 'lang', 'token', 'mandatory_doc_list', 'condonation_order_flag', 'regconf_doc_edit', 'regconf_doc_upload'));
        } catch (Exception $exc) {
            //  pr($exc);
            //   exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function calculate_fine($article, $tokenno) {
        try {
//            echo $article;exit;
            $this->loadModel('finefee');
            $this->loadModel('fees_calculation_detail');
            $this->loadModel('fees_calculation');
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('regconfig');
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $office_id = $this->Auth->User("office_id");
            $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id ,info.article_id ,presentation_date,exec_date FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $tokenno));
            if (!empty($application)) { // Document found
                $fineflag = 0;
                /*
                 *   Fine Case 1 Start
                 */
                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 6, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                $confstr = $regconf[0]['regconfig']['info_value'] ? $regconf[0]['regconfig']['info_value'] : '0 M';
                $confarr = explode(" ", $confstr);
                // pr($confarr);
                if (!empty($application[0][0]['presentation_date']) && !empty($application[0][0]['exec_date'])) {
                    $datetime1 = date_create($application[0][0]['exec_date']);
                    $datetime2 = date_create($application[0][0]['presentation_date']);
                    $interval = date_diff($datetime1, $datetime2);
                    $months = $interval->y * 12 + $interval->m;
                    if ($interval->d > 0) {
                        $months++;
                    }
                    if ($confarr['1'] == 'M' && $months > $confarr[0]) {
                        $fineflag = 1;
                    } else if ($confarr['1'] == 'D' && $interval->days > $confarr[0]) {
                        $fineflag = 1;
                    }
                }
                /*
                 *   Fine Case 1 End
                 */
//pr($fineflag);exit;
                if ($fineflag) { // Fine True
                    $feedetails = $this->fees_calculation->find("first", array('conditions' => array('token_no' => $tokenno, 'article_id' => $article, 'delete_flag' => 'N')));
                    if (!empty($feedetails)) {
                        $consamount = $feedetails['fees_calculation']['cons_amt'] ? $feedetails['fees_calculation']['cons_amt'] : 0;
                    } else {
                        $consamount = 0;
                    }
                    $amount = $consamount;

                    $getarticle = $this->finefee->query("Select article_id from ngdrstab_mst_finefee where article_id=?", array($article));
                    // $exitarticle = $getarticle[0][0]['article_id'];
                    if ($getarticle != NULL) {

                        $getrecord = $this->finefee->query("select a.finefee_id,a.article_id,a.fixed_amt_flag,a.fix_fee_amt,a.fee_formula_format,a.fine_description,a.state_id,a.user_id,a.fee_item_id from ngdrstab_mst_finefee a 
                                                inner join ngdrstab_mst_article_fee_items b on a.fee_item_id=b.fee_item_id 
                                                where a.article_id=?", array($article));

                        $finefeeid = $getrecord[0][0]['finefee_id'];
                        $articleid = $getrecord[0][0]['article_id'];
                        $finedescription = $getrecord[0][0]['fine_description'];
                        $feeitemid = $getrecord[0][0]['fee_item_id'];
                        $fee_formula_format = $getrecord[0][0]['fee_formula_format'];
                        $fixed_amt_flag = $getrecord[0][0]['fixed_amt_flag'];

                        $fixedamount = $getrecord[0][0]['fix_fee_amt'];
                        if ($fixed_amt_flag == 'Y') {
                            $finefeecalculate = $fixedamount;
                        } else {
                            $formula = str_replace("FAA", $amount, $fee_formula_format);
                            $finefeecalculate = eval("return (" . $formula . ");");
                        }
                    } else {
                        $getrecord = $this->finefee->query("select a.finefee_id,a.article_id,a.fixed_amt_flag,a.fix_fee_amt,a.fee_formula_format,a.fine_description,a.state_id,a.user_id from ngdrstab_mst_finefee a         
                                                inner join ngdrstab_mst_article_fee_items b on a.fee_item_id=b.fee_item_id 
                                                where a.article_id=?", array(99999));
                        if (!empty($getrecord)) {
                            $finefeeid = $getrecord[0][0]['finefee_id'];
                            $articleid = $getrecord[0][0]['article_id'];
                            $finedescription = $getrecord[0][0]['fine_description'];
                            $fee_formula_format = $getrecord[0][0]['fee_formula_format'];
                            $fixed_amt_flag = $getrecord[0][0]['fixed_amt_flag'];
                            $fixedamount = $getrecord[0][0]['fix_fee_amt'];
                            $formula = '';
                            if ($fixed_amt_flag == 'Y') {
                                $finefeecalculate = $fixedamount;
                            } else {
                                $formula = str_replace("FAA", $amount, $fee_formula_format);
                                $finefeecalculate = eval("return (" . $formula . ");");
                            }
                        }
                    }

                    //   pr($finefeecalculate);exit;
                    //insertret
                    //fee calculate
                    if (isset($finefeecalculate) && is_numeric($finefeecalculate)) {
                        $calculate_savequery = $this->fees_calculation->query("insert into ngdrstab_trn_fee_calculation (token_no,article_id,market_value,cons_amt,final_amt,state_id,user_id) values(?,?,?,?,?,?,?)", array($tokenno, $articleid, $amount, $amount, $finefeecalculate, $stateid, $user_id));
                        //  pr($calculate_savequery)

                        if ($calculate_savequery == NULL) {
                            $calculaterecord = $this->fees_calculation->query("select fee_calc_id from ngdrstab_trn_fee_calculation where token_no= ? order by fee_rule_id DESC LIMIT 1  ", array($tokenno));
                            //pr($calculaterecord);exit;
                            foreach ($calculaterecord as $calculaterecord1) {
                                $feecalcid = $calculaterecord1[0]['fee_calc_id'];
                                $calculate_details_savequery = $this->fees_calculation_detail->query("insert into ngdrstab_trn_fee_calculation_detail (fee_calc_id,fee_calc_desc,fee_item_id,final_value,state_id,user_id) values(?,?,?,?,?,?)", array($feecalcid, $formula, $feeitemid, $finefeecalculate, $stateid, $user_id));
                            }
                        }
                    }
                }// Fine True End
            }// Document found End
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Error :' . $ex)
            );
        }
    }

    public function document_upload_check() {
        $this->loadModel('uploaded_file_trn');
        $this->loadModel('upload_document');
        $lang = $this->Session->read("sess_langauge");
        $state_id = $this->Session->read("state_id");

        $document_list = $this->uploaded_file_trn->find('all', array('fields' => array('document.document_id', 'document.document_name_' . $lang, 'uploaded_file_trn.document_id', 'uploaded_file_trn.up_id', 'uploaded_file_trn.out_fname'),
            'joins' => array(
                array('table' => 'ngdrstab_mst_upload_document', 'alias' => 'document', 'conditions' => array('document.document_id=uploaded_file_trn.document_id'))
            ),
            'conditions' => array('token_no' => $this->Session->read('Selectedtoken')),
            'order' => 'document.document_name_en,uploaded_file_trn.up_id'
        ));


        $mandatory_doc_list = $this->upload_document->find('list', array('fields' => array('upload_document.document_id', 'upload_document.document_name_' . $lang),
            'joins' => array(
                array('table' => 'ngdrstab_mst_article_document_mapping', 'alias' => 'mapping', 'conditions' => array('mapping.document_id=upload_document.document_id', 'mapping.article_id=' . $this->Session->read("selectedarticle_id"))),
            ),
            'conditions' => array('mandatory_flag' => 'Y', 'upload_document.state_id' => $state_id)
        ));
        $missingflag = 'N';
        if (!empty($mandatory_doc_list)) {
            foreach ($mandatory_doc_list as $document_id => $single) {
                $uploadedflag = 'N';
                foreach ($document_list as $uploaded) {
                    if ($uploaded['document']['document_id'] == $document_id && !empty($uploaded['uploaded_file_trn']['out_fname'])) {
                        $uploadedflag = 'Y';
                    }
                }
                if ($uploadedflag == 'N') {
                    $missingflag = "Y";
                }
            }
        }
        return $missingflag;
    }

    function downloadfile($file, $folder = NULL, $token = NULL) {
        try {
            $this->loadModel('office');
            $this->loadModel('ApplicationSubmitted');
            if (is_null($token)) {
                $token = $this->Session->read("reg_token");
            }
            if (!is_null($token)) {
                $office = $this->ApplicationSubmitted->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id', 'office.office_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'conditions' => array('office.office_id=ApplicationSubmitted.office_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                    ),
                    'conditions' => array('ApplicationSubmitted.token_no' => $token)
                ));

                if (!empty($office)) {
                    if (isset($office['district']['district_name_en']) || !is_null($office['district']['district_name_en'])) {
                        if (isset($office['office']['taluka_id']) || !is_null($office['office']['taluka_id'])) {
                            $folder = 'Documents/' . $office['district']['district_name_en'] . "/" . $office['office']['taluka_id'] . "/" . $office['office']['office_id'] . "/" . $token . '/' . $folder . '/';
                            $this->any_download_file($folder, $file);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Errors', 'action' => 'error404'));
        }
    }

    function any_download_file($folder, $file) {
        try {
            $this->autoRender = FALSE;
            array_map(array($this, 'loadModel'), array('file_config'));
            $token = $this->Session->read("reg_token");
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $path = $path['file_config']['filepath'] . $folder . $file;
            if (file_exists($path)) {
                $this->response->file($path, array('download' => true, 'name' => $file));
                return $this->response->download($file);
            } else {
                echo "file not found";
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Errors', 'action' => 'error404'));
        }
    }

    public function lock_document($token, $office_id) {
        $this->loadModel('genernalinfoentry');
        $this->loadModel('ApplicationSubmitted');
        //$this->update_stamp_function_flags($this->request->params['action'], $extrafields);
        // Update On Genneral Information For Document Lock
        $extrafields['document_entry_flag'] = "'Y'";
        $extrafields = $this->add_default_fields_updateAll($extrafields);
        $this->ApplicationSubmitted->updateAll($extrafields, array('token_no' => $token));
        // exit;
        $lockdata['last_status_id'] = 3;
        $lockdata['last_status_date'] = "'" . date('Y-m-d H:i:s') . "'";
        $lockdata = $this->add_default_fields_updateAll($lockdata);
        $this->genernalinfoentry->updateAll($lockdata, array('token_no' => $token));
    }

    public function form14() {
        
    }

    public function form15() {
        
    }

    public function form16() {
        $this->check_function_hierarchy($this->request->params['action']);
        array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'property_details_entry', 'payment_mode'));

        $office_id = $this->Session->read("office_id");
        $token = $this->Session->read("reg_token");
        $state_id = $this->Auth->User("state_id");
        $citizen_user_id = $this->Session->read("citizen_user_id");
        $lang = $this->Session->read("sess_langauge");
        $doc_lang = $this->Session->read("doc_lang");

        $documents = $this->ApplicationSubmitted->general_information($lang, $office_id, $token);
        $propertylist = $this->ApplicationSubmitted->get_property_list($lang, $token, $citizen_user_id);
        $pattens = $this->ApplicationSubmitted->get_property_pattern($doc_lang, $token, $citizen_user_id);
        $partylist = $this->ApplicationSubmitted->party_information($lang, $doc_lang, $office_id, $token);
        $witnesslist = $this->ApplicationSubmitted->witness_information($lang, $doc_lang, $office_id, $token);
        $paymentlist = $this->ApplicationSubmitted->payment_information($lang, $doc_lang, $office_id, $token);
        $payment_mode = $this->payment_mode->find('list', array('fields' => array('payment_mode.payment_mode_id', 'payment_mode.payment_mode_desc_' . $lang), 'conditions' => array('payment_mode_id NOT IN' => array('3', '8')), 'order' => 'payment_mode_id ASC'));

//        pr($party);
        $this->set(compact('documents', 'lang', 'token', 'propertylist', 'pattens', 'partylist', 'witnesslist', 'doc_lang', 'paymentlist', 'payment_mode'));
    }

    public function document_final() {
        $this->check_role_escalation_tab();
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('SroAcceptance');
        $this->loadModel('SroChecklistDetails');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('NGDRSErrorCode');
        $this->loadModel('DocumentDisposal');
        $this->loadModel('DocumentDisposalEntry');
        $this->loadModel('User');
        $this->loadModel('regconfig');
        $this->loadModel('article');
        $this->loadModel('smsevent');
        $this->loadModel('party_entry');
        $this->loadModel('DocumentDisposalReasons');
        $this->loadModel('payment');

        try {

            $this->set('cap', null);
            $userid = $this->Session->read("session_user_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);

            $this->check_function_hierarchy($this->request->params['action']);
            $token = $this->Session->read("reg_token");
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read("sess_langauge");
            $state_id = $this->Auth->user('state_id');
            $article_id = $this->Session->read("selectedarticle_id");
            $this->update_sro_acceptance($token);

            $Srochecklist = $this->SroChecklistDetails->query("SELECT checklist.checklist_id, checklist.checklist_desc_$lang, details.checklist_flag FROM ngdrstab_mst_sro_checklist AS checklist LEFT JOIN ngdrstab_trn_sro_checklist_details  AS details    ON details.checklist_id = checklist.checklist_id   AND    details.token_no=?", array($token));
            $SroAcceptance = $this->SroAcceptance->query("SELECT A.*,B.* FROM ngdrstab_mst_sro_acceptance AS A, ngdrstab_trn_sro_acceptance_details AS B WHERE  A.acceptance_id=B.acceptance_id AND B.token_no=?", array($token));
            $fieldlist['acceptance_remark']['text'] = 'is_required';
// $this->set("fieldlist", $fieldlist);
            $btnhide = $this->ApplicationSubmitted->query("select final_stamp_flag,final_stamp_pending from ngdrstab_trn_application_submitted where token_no=?", array($token));
            $this->set('btnhide', $btnhide);

            $party_home_visit = $this->party_entry->query("SELECT p.home_visit_flag,p.party_full_name_$lang FROM ngdrstab_trn_party_entry_new p"
                    . " left outer join ngdrstab_mst_party_type pt on pt.party_type_id=p.party_type_id "
                    . " where p.home_visit_flag='Y' and p.token_no=?", array($token));
            $i = 0;
            foreach ($party_home_visit as $home) {
                if ($home[0]['home_visit_flag'] == 'Y') {
                    $i++;
                }
            }
            $this->set('i', $i);

            /* creating field list for dynamic forms(client side validation) */
            $lc = 0;
            if (!empty($SroAcceptance)) {
                foreach ($SroAcceptance as $single) {
                    $lc = $single[0]['acceptance_id'];
                    if ($single[0]['remark_flag'] == 'Y') {
                        if ($single[0]['old_data_flag'] == 'N' && $single[0]['acceptance_flag'] == 'A' && $single[0]['second_remark_flag'] == 'N') {
                            $fieldlistmultiform['formac' . $lc]['formac' . $lc . '_acceptance_remark2']['text'] = 'is_required,is_maxlength200';
                            $fieldlistmultiform['formrj' . $lc]['formrj' . $lc . '_acceptance_remark2']['text'] = 'is_required,is_maxlength200';
                        }
                    } else {
                        $fieldlistmultiform['formac' . $lc]['formac' . $lc . '_acceptance_remark']['text'] = 'is_required,is_maxlength200';
                        $fieldlistmultiform['formrj' . $lc]['formrj' . $lc . '_acceptance_remark']['text'] = 'is_required,is_maxlength200';
                    }
                }
            }

            $fieldlistmultiform['disposal']['disposal_id']['select'] = 'is_select_req';
            $fieldlistmultiform['disposal']['reason_id']['select'] = 'is_select_req';
            $fieldlistmultiform['disposal']['disposal_remark']['text'] = 'is_required,is_alphanumericspace,is_maxlength200';
            $fieldlistmultiform['disposal']['forward_user_id']['select'] = 'is_select_req';
            $fieldlistmultiform['final_stamp']['final_stamp_pending_remark']['text'] = 'is_required,is_alphanumericspace,is_maxlength200';
            $fieldlistmultiform['final_stamp']['otp']['text'] = 'is_required,is_numeric';


            if (!empty($fieldlistmultiform)) {
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }


            /* Creationg Field set END */
            $check = $this->User->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);
            $regconfbiometric = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 64, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            // biomatric failed 3 time then goto otp base 
            $biomatch = $this->Session->read('biomatch');
            if (!empty($regconfbiometric) && is_numeric($biomatch) && $biomatch > 2) {
                $regconfbiometric[0]['regconfig']['info_value'] = 2;
            }
            if ($this->request->is('post')) {
//pr($this->request->data);exit;
                $data = $this->request->data;
                if (isset($data['final_stamp'])) {
                    $csrftoken = $data['final_stamp']['csrftoken'];
                } else {
                    $csrftoken = NULL;
                }
                $this->check_csrf_token($csrftoken);


                if (isset($data['dispose'])) {
                    $verrors = $this->validatedata($data['final_stamp'], $fieldlistmultiform['disposal']);
                    if ($this->ValidationError($verrors)) {
                        $updatedata['disposal_flag'] = "'Y'";
                        $updatedata = $this->add_default_fields_updateAll($updatedata);
                        $this->ApplicationSubmitted->updateAll($updatedata, array('token_no' => $token));
                        $data['final_stamp'] = $this->add_default_fields($data['final_stamp']);
                        $data['final_stamp']['disposal_date'] = "'" . date('Y-m-d H:i:s') . "'";
                        $data['final_stamp']['token_no'] = $token;
                        $this->DocumentDisposalEntry->Save($data['final_stamp']);
                        $this->save_documentstatus(8, $token, $office_id);
                        $this->Session->setFlash(__("lblsavemsg"));
                        return $this->redirect('document_final');
                    } else {
                        $this->Session->setFlash(__("Please Find Validation Errors!"));
                        $this->set("errarr", $verrors);
                    }
                } else if (isset($data['sroaccept']) || isset($data['sroreject'])) {
                    $fieldlistnew['acceptance_remark']['text'] = 'is_required';
                    $fieldlistnew['acceptance_id']['text'] = 'is_required,is_integer';
                    $fieldlistnew['acceptance_flag']['text'] = 'is_required,is_alpha';

                    $verrors = $this->validatedata($data, $fieldlistnew);
                    if ($this->ValidationError($verrors)) {
                        $extrafields['acceptance_id'] = $data['acceptance_id'];
                        $extrafields['acceptance_remark'] = "'" . $data['acceptance_remark'] . "'";
                        $extrafields['acceptance_flag'] = "'" . $data['acceptance_flag'] . "'";
                        $extrafields['acceptance_remark_date'] = "'" . date('Y-m-d H:i:s') . "'";

                        $this->update_sro_acceptance($token, $extrafields);
                        $this->Session->setFlash(__("lblsavemsg"));
                        return $this->redirect('document_final');
                    } else {
                        if (isset($data['sroaccept'])) {
                            $error['formac' . $data['acceptance_id'] . '_acceptance_remark_error'] = $verrors['acceptance_remark_error'];
                        } else if (isset($data['sroreject'])) {
                            $error['formrj' . $data['acceptance_id'] . '_acceptance_remark_error'] = $verrors['acceptance_remark_error'];
                        }
                        $this->set("errarr", $error);
                    }
                } else if (isset($data['sroaccept1']) || isset($data['sroreject1'])) {
                    $fieldlistnew['acceptance_remark2']['text'] = 'is_required';
                    $fieldlistnew['acceptance_id']['text'] = 'is_required,is_integer';
                    $fieldlistnew['acceptance_flag']['text'] = 'is_required,is_alpha';
                    $verrors = $this->validatedata($data, $fieldlistnew);
                    if ($this->ValidationError($verrors)) {
                        $extrafields['acceptance_id'] = $data['acceptance_id'];
                        $extrafields['acceptance_remark2'] = "'" . $data['acceptance_remark2'] . "'";
                        $extrafields['acceptance_flag'] = "'" . $data['acceptance_flag'] . "'";
                        $extrafields['second_remark_flag'] = "'Y'";
                        $extrafields['acceptance_remark2_date'] = "'" . date('Y-m-d H:i:s') . "'";
                        $this->update_sro_acceptance($token, $extrafields);
                        $this->Session->setFlash(__("lblsavemsg"));
                        return $this->redirect('document_final');
                    } else {
                        if (isset($data['sroaccept1'])) {
                            $error['formac' . $data['acceptance_id'] . '_acceptance_remark2_error'] = $verrors['acceptance_remark2_error'];
                        } else if (isset($data['sroreject1'])) {
                            $error['formrj' . $data['acceptance_id'] . '_acceptance_remark2_error'] = $verrors['acceptance_remark2_error'];
                        }
                        $this->set("errarr", $error);
                    }
                } else {
                    $continueflag = 1;
                    foreach ($SroAcceptance as $single) {
                        if ($single[0]['remark_flag'] != 'Y') {
                            $continueflag = 0;
                        }
                        if ($single[0]['old_data_flag'] == 'N' && $single[0]['second_remark_flag'] == 'N') {
                            $continueflag = 0;
                        }
                    }
                    if ($continueflag == 0) {
                        $this->Session->setFlash(__("Please Fill Remark for All Records"));
                        return $this->redirect('document_final');
                    }
// Server Side Remark Checking End

                    $checkrefuse = $this->ApplicationSubmitted->find("all", array('conditions' => array('doc_refuse_flag' => 'Y', 'token_no' => $token)));
                    // pr($checkrefuse);exit;
                    if (!empty($checkrefuse)) {
                        $this->Session->setFlash(__("Document is refused. can not be completed!"));
                        return $this->redirect('document_final');
                    }


                    /*
                     * Recheck Payment   START
                     *              */
                    $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_$lang,mode.verification_flag  FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=?  ", array($token));
                    $regconf_amount_tally = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 48, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                    $feedetails = $this->payment->stampduty_fee_details($token, $lang, $article_id);
                    $paycheckflag = $this->payment->validate_payment($feedetails, $payment, $regconf_amount_tally);
                    if ($paycheckflag == 0) {
                        $this->Session->setFlash(__("Please check Payment Details!"));
                        return $this->redirect('payment_verification');
                    }
                    /*
                     * Recheck Payment   END 
                     *              */


                    $message = '';
                    $biometric_match = 0;
                    if (!empty($regconfbiometric)) {
                        if (isset($this->request->data['device_not_working'])) {
                            $message = 'Verification Success';
                        } else {
                            if ($regconfbiometric[0]['regconfig']['info_value'] == 1) {
                                $fingerprint = $_POST['cap'];
                                $employedata = $this->ApplicationSubmitted->query("select biometric_finger from ngdrstab_mst_user_biometric where user_id=$userid");
                                if (empty($employedata)) {
                                    $this->Session->setFlash(__("Please Register Your Biometric!"));
                                    return $this->redirect('document_final');
                                }
                                $db_finger = $employedata[0][0]['biometric_finger'];
                                if ($serverbioflag == 'N') {
                                    $path = WWW_ROOT . 'Biometric\secugenMatch.jar';
                                    $message = exec('java -jar ' . $path . ' ' . $fingerprint . ' ' . $db_finger, $result);
                                    $biometric_match = 1;
                                } else {
                                    $path = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/SecugenMatch_log.jar";
                                    $message = exec('java -Djava.library.path=/usr/local/lib -cp ".:FDxSDKPro.jar:commons-codec-1.7.jar" -jar ' . $path . ' ' . $fingerprint . ' ' . $db_finger, $result);
                                    $biometric_match = 1;
                                }
                                if ($message != 'Verification Success') {
                                    $biomatch = $this->Session->read('biomatch');
                                    if (is_numeric($biomatch)) {
                                        $biomatch++;
                                        $this->Session->write('biomatch', $biomatch);
                                    } else {
                                        $this->Session->write('biomatch', 1);
                                    }
                                }
                            } else if ($regconfbiometric[0]['regconfig']['info_value'] == 2 && isset($this->request->data['final_stamp']['otp'])) {
                                if (is_numeric($this->request->data['final_stamp']['otp']) && $this->request->data['final_stamp']['otp'] == $this->Session->read('userotp')) {
                                    $message = 'Verification Success';
                                }
                            }
                        }
                    } else {
                        $message = 'Verification Success';
                    }


                    if ($message == 'Verification Success') {

                        if ($this->final_check_all_flags()) {
                            if (isset($this->request->data['final_stamp_pending'])) {
                                $fieldsetnew['final_stamp_pending_remark']['text'] = 'is_required,is_alphanumericspace,is_maxlength200';
                                $errors = $this->validatedata($data['final_stamp'], $fieldsetnew);
                                if ($this->ValidationError($errors)) {

                                    $pendingdata['final_stamp_pending'] = "'Y'";
                                    $pendingdata['final_stamp_pending_date'] = "'" . date('Y-m-d H:i:s') . "'";
                                    $pendingdata['final_stamp_pending_remark'] = "'" . $data['final_stamp']['final_stamp_pending_remark'] . "'";

                                    $pendingdata = $this->add_default_fields_updateAll($pendingdata);
                                    $this->ApplicationSubmitted->updateAll(
                                            $pendingdata, array('token_no' => $token)
                                    );
                                    $this->save_documentstatus(9, $token, $office_id);
                                    $this->Session->setFlash(__("lbleditmsg"));
                                    return $this->redirect('document_final');
                                } else {
                                    $this->Session->setFlash(__("Please check validation"));
                                    return $this->redirect('document_final');
                                }
                            }

                            $regconf_docnofinal = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 139, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                            if (!empty($regconf_docnofinal)) {
                                $docnumbercheck = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'final_doc_reg_no' => NULL)));
                                if (!empty($docnumbercheck)) {
                                    if ($regconf_docnofinal['regconfig']['info_value'] == 'AUTO') {
                                        $docnumberfinal = $this->generate_document_number_final_auto();
                                    } else {
                                        $docnumberfinal = $this->generate_document_number_final();
                                    }
                                    if (strcmp($docnumberfinal, '0') == 0) {
                                       // $this->Session->setFlash(__("Not able to generate final document number"));
                                        return $this->redirect('document_final');
                                    }
                                    //$extrafields['final_doc_reg_no'] = "'" . $docnumberfinal . "'";
                                    $this->ApplicationSubmitted->updateAll(array('final_doc_reg_no' => "'" . $docnumberfinal . "'"), array('token_no' => $token));
                                }
                            }

                            $regconf_docno = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 111, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                            if (!empty($regconf_docno)) {
                                $docnumbercheck = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'doc_reg_no' => NULL)));
                                if (!empty($docnumbercheck)) {
                                    $docnumber = $this->generate_document_number();
                                    if (strcmp($docnumber, '0') == 0) {
                                        $this->Session->setFlash(__('Not able to generate document number'));
                                        return $this->redirect('document_final');
                                    }
//                                        $extrafields['doc_reg_no'] = "'" . $docnumber . "'";
//                                        $extrafields['doc_reg_date'] = "'" . date('Y-m-d H:i:s') . "'";
                                    $this->ApplicationSubmitted->updateAll(array('doc_reg_no' => "'" . $docnumber . "'", 'doc_reg_date' => "'" . date('Y-m-d H:i:s') . "'"), array('token_no' => $token));
                                }
                            }

                            $app = $this->ApplicationSubmitted->find('list', array('fields' => array('token_no', 'doc_reg_no'), 'conditions' => array('token_no' => $token)));

                            $regconf_docnofinal1 = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 140, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                            if (!empty($regconf_docnofinal1)) {
                                $docresponce = $this->create_final_document($token, $app[$token], $office_id);
                            } else {
                                $docresponce['ERROR'] = '';
                            }

                            if (empty($docresponce['ERROR'])) {
                                $extrafields['final_stamp_date'] = "'" . date('Y-m-d H:i:s') . "'";
                                $extrafields['final_stamp_flag'] = "'Y'";
                                if ($biometric_match == 1) {
                                    $extrafields['final_biometric_match'] = "'Y'";
                                }

                                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 109, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                if (!empty($regconf)) {
                                    $volume_res = $this->volume_number_page_number_entry($token);
                                    if (!empty($volume_res['ERROR'])) {
                                        $this->Session->setFlash(__("Not able to enter volume number and page number"));
                                        return $this->redirect('document_final');
                                    }
                                }
                                // Mutation 
                                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 137, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                if (!empty($regconf)) {
                                    $article = $this->article->find("first", array('conditions' => array('article_id' => $article_id, 'property_applicable' => 'Y')));
                                    if (!empty($article)) {
                                        $serviceobj = new WebServiceController();
                                        $serviceobj->constructClasses();
                                        $mutation = $serviceobj->Mutation($token);
                                        //pr($mutation);exit;
                                        if (!empty($mutation['Error'])) {
                                            $this->Session->setFlash(__("Error While Mutation :" . $mutation['Error']));
                                            return $this->redirect('document_final');
                                        } elseif ($mutation['STATUS'] == 1) { //STATUS
                                            $this->ApplicationSubmitted->query("update ngdrstab_trn_application_submitted set mutation_flag=? where token_no=? ", array('Y', $token));
                                        }
                                    }
                                }


                                $this->update_stamp_function_flags($this->request->params['action'], $extrafields);
// Update On Genneral Information For Document Lock
                                $lockdata['last_status_id'] = 4;
                                $lockdata['last_status_date'] = "'" . date('Y-m-d H:i:s') . "'";
                                $lockdata = $this->add_default_fields_updateAll($lockdata);
                                $this->genernalinfoentry->updateAll($lockdata, array('token_no' => $token));
                                // by madhuri
                                $this->save_documentstatus(4, $token, $office_id);

                                $this->exchange_property($token);



                                //sms code
                                $event = $this->smsevent->find("all", array('conditions' => array('event_id' => 3, 'send_flag' => 'Y')));
                                if (!empty($event)) {

                                    $regconf = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 166, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                    $seller = array();
                                    $buyer = array();
                                    $presenter = array();

                                    if ($regconf['regconfig']['info_value'] == 'L' || $regconf['regconfig']['info_value'] == 'A') {
                                        $seller = $this->party_entry->find('list', array(
                                            'fields' => array('mobile_no', 'mobile_no'),
                                            'conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'not' => array('mobile_no' => NULL)),
                                            'joins' => array(
                                                array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='1'"))
                                            )
                                        ));
                                    }
                                    if ($regconf['regconfig']['info_value'] == 'R' || $regconf['regconfig']['info_value'] == 'A') {
                                        $buyer = $this->party_entry->find('list', array(
                                            'fields' => array('mobile_no', 'mobile_no'),
                                            'conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'not' => array('mobile_no' => NULL)),
                                            'joins' => array(
                                                array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
                                            )
                                        ));
                                    }
                                    if ($regconf['regconfig']['info_value'] == 'R' || $regconf['regconfig']['info_value'] == 'A') {
                                        $presenter = $this->party_entry->find('list', array(
                                            'fields' => array('mobile_no', 'mobile_no'),
                                            'conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'is_presenter' => 'Y', 'not' => array('mobile_no' => NULL))
                                        ));
                                    }
                                    $mobilenum_arr = array_merge($presenter, $buyer, $seller);
                                    $mobilenumbers = implode(',', $mobilenum_arr);
                                    $office = $this->ApplicationSubmitted->find("first", array('fields' => array('office.office_id', 'office.office_name_en', 'doc_reg_no', 'final_doc_reg_no'),
                                        'joins' => array(
                                            array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'conditions' => array("office.office_id=ApplicationSubmitted.office_id "))
                                        ),
                                        'conditions' => array('token_no' => $this->Session->read("Selectedtoken")
                                        )
                                    ));



                                    if (!empty($office) && !empty($mobilenum_arr)) {
                                        $docregno = '';
                                        $regconf = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 165, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                        if (!empty($regconf) && $regconf['regconfig']['info_value'] == 'FIRST') {
                                            $docregno = $office['ApplicationSubmitted']['doc_reg_no'];
                                        } else if (!empty($regconf) && $regconf['regconfig']['info_value'] == 'FINAL') {
                                            $docregno = $office['ApplicationSubmitted']['final_doc_reg_no'];
                                        }
                                        $message = $office['office']['office_name_en'] . '    ' . ' with deed number -  ' . $docregno;
                                        $this->smssend(4, $mobilenumbers, $message, $userid, 3);
                                    }
                                }


                                $this->Session->setFlash(__("Registration Completed Successfully"));
                                return $this->redirect('document_final');
                            } else {
                                $this->Session->setFlash(__("Error:" . $docresponce['ERROR']));
                                return $this->redirect('document_final');
                            }
                        } else {
                            $this->Session->setFlash(__("Please Complete All Stamps!"));
                            return $this->redirect('document_final');
                        }
                    } else {
                        $this->Session->setFlash(__("Authentication  Failed...Please try again...!!!"));
                        $this->redirect(array('action' => 'document_final'));
                    }
                } // v error if
            }
            $this->Session->write('userotp', NULL);
            $stampconfig = $this->stamp_and_functions_config();
            $report = new ReportsController();
            $report->constructClasses();
            $summary1 = $report->summary1_report(base64_encode($token), 'V');
            //   $pendingdoc = $report->rpt_deed_pending(base64_encode($token), 'V', 'F');
            $summary2full = $report->summary2_report(base64_encode($token), 'V', 'F');
            $summary2partial = $report->summary2_report(base64_encode($token), 'V', 'F');
            $thumb = $report->thumbbook_goa(base64_encode($token), 'V', 'F');
            $homevisit = $report->get_home_visit(base64_encode($token), 'V');

            $DocumentDisposal = $this->DocumentDisposal->find("list", array('fields' => array('disposal_id', 'disposal_desc_' . $lang), 'conditions' => array('display_flag' => 'Y')));
            $DocumentDisposalReasons = $this->DocumentDisposalReasons->find("list", array('fields' => array('reason_id', 'reason_desc_' . $lang), 'conditions' => array('display_flag' => 'Y')));

            $DocumentDisposalEntry = $this->DocumentDisposalEntry->find("all", array('conditions' => array('token_no' => $token)));

            $userlist = $this->User->find("list", array('fields' => array('user_id', 'full_name'), 'conditions' => array('activeflag' => 'Y', 'role_id' => '888888')));
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 36, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $article = $this->article->find('first', array('conditions' => array('article_id' => $this->Session->read("selectedarticle_id"))));
            if (!empty($article)) {
                $article = $article['article'];
            }
            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($token, $office_id)));
                $this->Session->write("doc_reg_no", $documents['0']['0']['doc_reg_no']);
                $regconf_docnofinal1 = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 140, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                $this->set(compact('pendingdoc', 'thumb', 'homevisit', 'SroAcceptance', 'lang', 'token', 'regconf', 'Srochecklist', 'stampconfig', 'summary2full', 'summary2partial', 'summary1', 'DocumentDisposal', 'DocumentDisposalEntry', 'userlist', 'article', 'regconfbiometric', 'DocumentDisposalReasons', 'regconf_docnofinal1', 'state_id'));
            }
            // echo  $this->create_final_document($token, '1234', $office_id);
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function volume_number_page_number_entry($token = NULL) {
        $this->loadModel('genernalinfoentry');
        $this->loadModel('VolumeNumberEntry');
        $this->loadModel('party_entry');
        $this->loadModel('regconfig');
        $this->loadModel('MstSerialNumbersFinal');

        $office_id = $this->Auth->user('office_id');
        $response['ERROR'] = NULL;
        $exist = $this->VolumeNumberEntry->find('first', array('conditions' => array('token_no' => $token)));

        if (empty($exist)) {
            if (!is_null($token)) {
                $info = $this->genernalinfoentry->find('first', array(
                    'fields' => array('genernalinfoentry.no_of_pages', 'article.book_number', 'office.office_code'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'type' => 'INNER', 'conditions' => array('article.article_id=genernalinfoentry.article_id')),
                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'type' => 'INNER', 'conditions' => array('office.office_id=genernalinfoentry.office_id')),
                    ),
                    'conditions' => array('genernalinfoentry.token_no' => $token)
                ));
                if (!empty($info) && !is_null($info['article']['book_number'])) {
                    $VolumeNumber = $this->VolumeNumberEntry->find('first', array('conditions' => array('office_code' => $info['office']['office_code'], 'book_number' => $info['article']['book_number'], 'year' => date('Y')),
                        'order' => array('id' => 'DESC')));
                    $currvol = NULL;
                    $lastpage = NULL;
                    $firstpage = NULL;

                    if (empty($VolumeNumber)) {
                        $VolumeNumber = $this->MstSerialNumbersFinal->find('first', array('conditions' => array('office_code' => $info['office']['office_code'], 'book_number' => $info['article']['book_number'], 'year' => date('Y'))));
                        if (!empty($VolumeNumber)) {
                            $currvol = $VolumeNumber['MstSerialNumbersFinal']['volume_number'];
                            $lastpage = $VolumeNumber['MstSerialNumbersFinal']['page_number'];
                            $lastpage = $lastpage - 1;
                        } else {
                            $currvol = 1;
                            $lastpage = 0;
                        }
                    } else {
                        $firstpage = $VolumeNumber['VolumeNumberEntry']['page_number_start'];
                        $lastpage = $VolumeNumber['VolumeNumberEntry']['page_number_end'];
                        $currvol = $VolumeNumber['VolumeNumberEntry']['volume_number'];
                    }

                    if (!is_null($currvol) and ! is_null($lastpage)) {


                        $totalpages = $info['genernalinfoentry']['no_of_pages'];

                        $extrapages = $this->number_of_pages($token);
                        $totalpages = (($totalpages + $extrapages) * 2);

                        if (($lastpage + $totalpages) > 600) {
                            $currvol++;
                            $lastpage = $totalpages;
                            $firstpage = 1;
                        } else {
                            $firstpage = $lastpage + 1;
                            $lastpage = $lastpage + $totalpages;
                        }

                        $insert_check['office_code'] = $info['office']['office_code'];
                        $insert_check['book_number'] = $info['article']['book_number'];
                        $insert_check['year'] = date('Y');

                        $insert_check['volume_number'] = $currvol;
                        $insert_check['page_number_start'] = $firstpage;

                        $insert = $insert_check;

                        $insert['page_number_end'] = $lastpage;
                        $insert['token_no'] = $token;

                        $VolumeNumber_check = $this->VolumeNumberEntry->find('first', array('conditions' => $insert_check));
                        if (empty($VolumeNumber_check)) {
                            if ($this->VolumeNumberEntry->save($insert)) {
                                $response['SUCCESS'] = 'lblsavemsg';
                            } else {
                                $response['ERROR'] = 'Not able to save please try again';
                            }
                        } else {
                            $response['ERROR'] = 'please try again';
                        }
                    } else {
                        $response['ERROR'] = 'Initial Counter not found';
                    }
                } else {
                    $response['ERROR'] = 'Document not found';
                }
            } else {
                $response['ERROR'] = 'Token not found';
            }
        } else {
            $response['ERROR'] = 'Record Already Exist';
        }
        // pr($response);exit;
        return $response;
    }

    public function document_upload() {
        $this->check_role_escalation_tab();
        try {
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('scan_upload');
            $this->loadModel('file_config');
            $this->loadModel('office');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('regconfig');

            $token = $this->Session->read("reg_token");
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read("sess_langauge");
            $state_id = $this->Auth->user('state_id');


            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($token, $office_id)));
            }
            $scan_upload = $this->scan_upload->find("first", array('conditions' => array('token_no' => $token)));
            $this->set("scan_upload", $scan_upload);
            if ($this->request->is('post') && isset($this->request->data['document_upload'])) {
                $this->check_csrf_token($this->request->data['document_upload']['csrftoken']);

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path) || !is_dir($path['file_config']['filepath'])) {
                    $this->Session->setFlash(__('Database Base Path Not Found!'));
                    return $this->redirect(array('action' => 'document_upload'));
                }
                $basepath = $path['file_config']['filepath'];
                $check = $this->check_document_folder_structure($basepath, $token, $office_id);
                if ($check == 1) {
                    // pr($this->request->data);exit;
                    $responce = $this->validatepdffile($this->request->data['document_upload']['upload_file']);
                    //pr($responce);exit;
                    if (empty($responce['ERROR'])) {
                        $reqdata = $this->request->data['document_upload'];
                        $regconf = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 144, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                        if (!empty($regconf)) {
                            $ginfo = $this->genernalinfoentry->find("first", array('conditions' => array('token_no' => $token)));
                            $totalpages = $ginfo['genernalinfoentry']['no_of_pages'];
                            $extrapages = $this->number_of_pages($token);
                            $totalpages = (($totalpages + 4 + $extrapages) * 2);
                            /* /usr/bin/pdfinfo */
                            $pdfinfopath = $regconf['regconfig']['info_value'];
                            $num_pag = exec($pdfinfopath . ' ' . "\"" . $reqdata['upload_file']['tmp_name'] . "\"" . ' | awk \'/Pages/ {print $2}\'', $output);
                            if (!isset($num_pag) || empty($num_pag)) {
                                $num_pag = 0;
                            }

                            if ($totalpages != $num_pag) {
                                $this->Session->setFlash(
                                        __($num_pag . ' Pages Uploaded .Please upload ' . $totalpages . ' Pages')
                                );
                                $this->redirect(array('controller' => 'Registration', 'action' => 'document_upload'));
                            }
                        }

                        $office = $this->office->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id'),
                            'joins' => array(
                                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                            ),
                            'conditions' => array('office_id' => $office_id)
                        ));
                        if (!empty($office)) {

                            if (isset($office['district']['district_name_en']) and ! is_null($office['district']['district_name_en'])) {
                                if (isset($office['office']['taluka_id']) || !is_null($office['office']['taluka_id'])) {
                                    $file_ext = pathinfo($reqdata['upload_file']['name'], PATHINFO_EXTENSION);
                                    $destination = $basepath . "Documents/" . $office['district']['district_name_en'] . "/" . $office['office']['taluka_id'] . "/" . $office_id . "/" . $token . "/Scanning/" . $token . "." . $file_ext;
                                    $success = move_uploaded_file($reqdata['upload_file']['tmp_name'], $destination);
                                    if ($success == 1) {
                                        $dataarr['scan_name'] = $token . "." . $file_ext;
                                        $dataarr['doc_reg_no'] = $documents[0][0]['doc_reg_no'];
                                        $dataarr['conf_flag'] = 'Y';
                                        $dataarr['token_no'] = $token;
                                        if ($this->scan_upload->save($dataarr)) {

                                            $scandata['document_scan_flag'] = "'Y'";
                                            $scandata['document_scan_date'] = "'" . date('Y-m-d H:i:s') . "'";
                                            $scandata = $this->add_default_fields_updateAll($scandata);

                                            $this->ApplicationSubmitted->updateAll(
                                                    $scandata, array('token_no' => $token)
                                            );
                                            $this->Session->setFlash(
                                                    __('File Uploaded Successfully!')
                                            );
                                        } else {
                                            $this->Session->setFlash(
                                                    __('Fail to save data')
                                            );
                                        }
                                    } else {
                                        $this->Session->setFlash(
                                                __('File Uploade Failed')
                                        );
                                    }
                                } else {
                                    $this->Session->setFlash(
                                            __('Taluka id Not Found')
                                    );
                                }
                            } else {
                                $this->Session->setFlash(
                                        __('District Name Not Found')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Office  Not Found for office  id ' . $office_id)
                            );
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Error : ' . $responce['ERROR'])
                        );
                    }
                } else {
                    $this->Session->setFlash(
                            __('Upload Folder canot be created!')
                    );
                }
                $this->redirect(array('controller' => 'Registration', 'action' => 'document_upload'));
            }
        } catch (Exception $ex) {
            // pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function indexreport($report_type, $flag) {
        $token = $this->Session->read("reg_token");
        $report = new ReportsController();
        $report->constructClasses();
        switch ($report_type) {
            case 1:
                return $index = $report->rpt_index1(base64_encode($token), $flag);
                break;
            case 2:
                return $index = $report->rpt_index2(base64_encode($token), $flag);
                break;
            case 3:
                return $index = $report->rpt_index3(base64_encode($token), $flag);
                break;
            default:
                return $index = $report->rpt_index4(base64_encode($token), $flag);
        }
    }

    // exchange deed 32
    public function exchange_property($token) {
        $this->loadModel('party_entry');
        $doc_lang = $this->Session->read("doc_lang");
        $lang = $this->Session->read("session_lang");
        $document = $this->ApplicationSubmitted->application_document($token, $doc_lang);
        $office_id = $this->Auth->user('office_id');
        if (isset($document) && !empty($document)) {
            //$document['0']['0']['article_id']=32;
            if ($document['0']['0']['article_id'] == 32) {
                $document_party_left = $this->ApplicationSubmitted->party_information_exchange_property($token, 0);
                $document_party_right = $this->ApplicationSubmitted->party_information_exchange_property($token, 1);
                $left_party_ids = "";
                $right_party_ids = "";
                if (!empty($document_party_left) && !empty($document_party_right)) {
                    foreach ($document_party_left as $key => $left_party) {
                        $left_property_id = $left_party[0]['property_id'];
                        $left_party_ids .= ",'" . $left_party[0]['party_id'] . "'";
                    }
                    foreach ($document_party_right as $key => $right_party) {
                        $right_property_id = $right_party[0]['property_id'];
                        $right_party_ids .= ",'" . $right_party[0]['party_id'] . "'";
                    }
                    $left_party_ids = substr($left_party_ids, 1);
                    $right_party_ids = substr($right_party_ids, 1);

                    $left_party_ids = " (" . $left_party_ids . " )";
                    $right_party_ids = " (" . $right_party_ids . " )";
                    if (is_numeric($left_property_id) && is_numeric($right_property_id)) {
                        $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET property_id=? WHERE party_id IN $right_party_ids", array($left_property_id));
                        $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET property_id=? WHERE party_id IN $left_party_ids", array($right_property_id));
                    }
                }
            }
        }
    }

    public function document_checklist() {
        $this->check_role_escalation_tab();
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('SroAcceptance');
        $this->loadModel('SroChecklistDetails');
        $this->loadModel('NGDRSErrorCode');
        try {
            $userid = $this->Session->read("session_user_id");
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);

            $this->check_function_hierarchy($this->request->params['action']);
            $token = $this->Session->read("reg_token");
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read("sess_langauge");

            $Srochecklist = $this->SroChecklistDetails->query("SELECT checklist.checklist_id, checklist.checklist_desc_$lang, details.checklist_flag FROM ngdrstab_mst_sro_checklist AS checklist LEFT JOIN ngdrstab_trn_sro_checklist_details  AS details    ON details.checklist_id = checklist.checklist_id   AND    details.token_no=?", array($token));

            /* creating field list for dynamic forms(client side validation) */

            foreach ($Srochecklist as $checklist) {
                $fieldlistmultiform['final_stamp']['checklist' . $checklist[0]['checklist_id']]['checkbox'] = 'is_required';
            }

            if (!empty($fieldlistmultiform)) {
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }
            /* Creationg Field set END */
            if ($this->request->is('post')) {

                $data = $this->request->data;
                if (isset($data['final_stamp'])) {
                    $csrftoken = $data['final_stamp']['csrftoken'];
                } else {
                    $csrftoken = NULL;
                }
                $this->check_csrf_token($csrftoken);

                foreach ($Srochecklist as $checklist) {
                    if (isset($this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']]) && $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']] == $checklist[0]['checklist_id']) {

                        $requestdata['checklist' . $checklist[0]['checklist_id']] = $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']];
                    } else {

                        $requestdata['checklist' . $checklist[0]['checklist_id']] = '';
                    }
                }

                $verrors = $this->validatedata($requestdata, $fieldlistmultiform['final_stamp']);
                if ($this->ValidationError($verrors)) {
                    $checklist = array();
                    $checklist = $fieldlistmultiform['final_stamp'];
                    $this->SroChecklistDetails->deleteAll(array('token_no' => $token));
                    foreach ($Srochecklist as $checklist) {
                        $savechecklist['token_no'] = $token;
                        $savechecklist['checklist_id'] = $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']];
                        $savechecklist['checklist_flag'] = 'Y';

                        $this->SroChecklistDetails->saveAll($savechecklist);
                    }
                    $this->update_stamp_function_flags($this->request->params['action']);


                    $this->Session->setFlash(__("Checklist  Completed !"));
                    return $this->redirect('document_checklist');
                }
            }

            //pr($Srochecklist_trn);exit;
            $stampconfig = $this->stamp_and_functions_config();
            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($token, $office_id)));
                $this->set(compact('lang', 'token', 'Srochecklist', 'stampconfig'));
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function document_disposal_review() {
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('DocumentDisposalEntry');
        $this->loadModel('DocumentDisposal');


        try {
            $userid = $this->Auth->user("user_id");
            $lang = $this->Session->read("sess_langauge");

            $doc_lang = $this->Session->read("doc_lang");
            $lang = $this->Session->read("sess_langauge");
            $DocumentDisposalEntry = $this->DocumentDisposalEntry->find("all", array('conditions' => array('forward_user_id' => $userid)));
            $disposaldoc = $this->ApplicationSubmitted->document_disposal_review($doc_lang);
            $DocumentDisposal = $this->DocumentDisposal->find("list", array('fields' => array('disposal_id', 'disposal_desc_' . $lang), 'conditions' => array('display_flag' => 'Y')));

            foreach ($disposaldoc as $document) {
                $document = $document[0];
                $fieldlist['disposal_review' . $document['app_id']]['csrftoken' . $document['app_id']]['text'] = 'is_required';
                $fieldlist['disposal_review' . $document['app_id']]['disposal_id' . $document['app_id']]['select'] = 'is_select_req';
                $fieldlist['disposal_review' . $document['app_id']]['disposal_remark' . $document['app_id']]['text'] = 'is_required';
                $fieldlist['disposal_review' . $document['app_id']]['disposal_close_status' . $document['app_id']]['select'] = 'is_alpha';
                $fieldlist['disposal_review' . $document['app_id']]['app_id' . $document['app_id']]['text'] = 'is_numeric';
            }
            if (isset($fieldlist)) {
                $this->set("fieldlistmultiform", $fieldlist);
                $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
            }

            if ($this->request->is('post')) { //&& isset($this->request->data['disposal_review'])
                $fieldlistnew['csrftoken']['text'] = 'is_required';
                $fieldlistnew['disposal_id']['select'] = 'is_select_req';
                $fieldlistnew['disposal_remark']['text'] = 'is_required';
                $fieldlistnew['disposal_close_status']['select'] = 'is_alpha';
                $fieldlistnew['app_id']['text'] = 'is_numeric';
                $errors = $this->validatedata($this->request->data['disposal_review'], $fieldlistnew);
                $c = $this->ValidationError($errors);
                // pr($c);exit;
                if ($this->ValidationError($errors)) {
                    $app = $this->ApplicationSubmitted->find("first", array('conditions' => array('app_id' => $this->request->data['disposal_review']['app_id'], 'disposal_flag' => 'Y', 'disposal_review_flag' => 'N')));

                    if (!empty($app)) {
                        $data = $this->request->data['disposal_review'];
                        $data['token_no'] = $app['ApplicationSubmitted']['token_no'];
                        $data['disposal_date'] = date('Y-m-d H:i:s');
                        $data['forward_user_id'] = $this->Auth->user('user_id');
                        $data = $this->add_default_fields($data);
                        $data['office_id'] = $app['ApplicationSubmitted']['office_id'];

                        if ($this->DocumentDisposalEntry->Save($data)) {
                            if ($data['disposal_close_status'] == 'Y') {
                                $disposalflag['disposal_review_flag'] = "'Y'";
                                $disposalflag = $this->add_default_fields_updateAll($disposalflag);
                                $this->ApplicationSubmitted->updateAll($disposalflag, array('app_id' => $data['app_id']));
                            }

                            $this->Session->setFlash(__("lblsavemsg"));
                            return $this->redirect('document_disposal_review');
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                } else {
                    $errorsnew = array();
                    foreach ($fieldlistnew as $key => $field) {
                        $errorsnew[$key . $this->request->data['disposal_review']['app_id'] . "_error"] = $errors[$key . "_error"];
                    }
                    $this->set("errarr", $errorsnew);
                    $this->Session->setFlash(__("Please Check Validation Errors!"));
                }
            }

            $this->set(compact('disposaldoc', 'doc_lang', 'lang', 'DocumentDisposalEntry', 'DocumentDisposal'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Documents_disposal() {
        $this->loadModel('ApplicationSubmitted');

        if ($this->request->is('post')) {
            $from_date = $this->request->data['Documents_disposal']['from_date'];
            $to_date = $this->request->data['Documents_disposal']['to_date'];
//            $type = $this->request->data['Documents_disposal']['type'];

            if (!empty($from_date) && !empty($to_date)) {
                $stamp_conf = $this->stamp_and_functions_config();
                foreach ($stamp_conf as $stamp) {
                    if ($stamp['is_last'] == 'Y') {
                        $col_date = $stamp['stamp_title'] . "_date"; // find last stamp flag  
                        $col_flag = $stamp['stamp_flag']; // find last stamp flag  
                    }
                }

                $released_list = $this->ApplicationSubmitted->document_disposal_summery($from_date, $to_date, $col_date, $col_flag);

//                pr($released_list); exit; 
                $this->set('released_list', $released_list);
                $this->set('from_date', $from_date);
                $this->set('to_date', $to_date);



//                $this->set('col_flag', $col_flag);
            }
        }
    }

    public function disposal_all_report($from_date, $to_date) {

        $this->loadModel('ApplicationSubmitted');
        $from_date = date("d-m-Y", $from_date);
        $to_date = date("d-m-Y", $to_date);

        $stamp_conf = $this->stamp_and_functions_config();
        foreach ($stamp_conf as $stamp) {
            if ($stamp['is_last'] == 'Y') {
                $col_date = $stamp['stamp_title'] . "_date"; // find last stamp flag  
                $col_flag = $stamp['stamp_flag']; // find last stamp flag  
            }
        }

        $released_list1 = $this->ApplicationSubmitted->document_disposal_summery($from_date, $to_date, $col_date, $col_flag);

        $released_list2 = $this->ApplicationSubmitted->document_disposal_summery2($from_date, $to_date, $col_date, $col_flag);

        $rpt_title = "Documents Disposal Reports";

        if ($released_list1) {
            $html_body = "<tr>"
                    . "<th>Sr.No.</th>"
                    . "<th>Registration Date</th>"
                    . "<th>Party Name</th>"
                    . "<th>Registration No</th>"
                    . "<th>Article Description</th>"
                    . "</tr>";
            $i = 1;
            foreach ($released_list1 as $rs) {
                $rs[0]['party_full_name_en'] = trim($rs[0]['party_full_name_en']);
                $rs[0]['doc_reg_date'] = trim($rs[0]['doc_reg_date']);

                $html_body .= "<tr>"
                        . "<td><center>" . $i . "</center></td>"
                        . "<td><center>" . $rs[0]['doc_reg_date'] . "</center></td>"
                        . "<td><center>" . $rs[0]['party_full_name_en'] . "</center></td>"
                        . "<td><center>" . $rs[0]['doc_reg_no'] . "</center></td>"
                        . "<td><center>" . $rs[0]['article_desc_en'] . "</center></td>"
                        . "</tr>";

                $j = 0;
                $html_body .= "<tr>"
                        . "<td></td>"
                        . "<td><b>Disposal Discription</b></td>"
                        . "</tr>";
                foreach ($released_list2 as $rs1) {

                    if ($rs[0]['token_no'] == $rs1[0]['token_no']) {
                        $j ++;
                        $html_body .= "<tr>"
                                . "<td></td>"
                                . "<td>" . $j . ". " . $rs1[0]['disposal_desc_en'] . "</td>"
                                . "<td>" . "" . "</td>"
                                . "</tr>";
                    }
                }
                $html_body .= "<tr>"
                        . "<td></td>"
                        . "</tr>";
                $i++;
            }

            $html = "<style>th{font-size:12px;align:center;} td{font-size:12px;align:center; padding:5px;}</style>"
                    . "<html><body>"
                    . "<h1 align=center>" . $rpt_title . "</h1>"
//                                    . "<table align='center' style='border:0; width:50%'><tr style='border:0;'></tr></table> "
                    . "<h6 align='center'>Report Date:" . date('d-m-Y') . "</h6>"
                    . "<hr width='2'/>"
                    . "<table width=90% align='center' border=0>";

//                            pr($html);exit;    
            $html .= $html_body;
        } else {
            $this->Session->setFlash("lblnotfoundmsg");
            $this->redirect(array('controller' => 'Registration', 'action' => 'Documents_disposal'));
        }
        $html .= "</table>";

        $this->create_pdf($html, $rpt_title, 'A4-L', '');
    }

    public function final_check_all_flags() {
        $this->loadModel('ApplicationSubmitted');
        $token = $this->Session->read("reg_token");
        $office_id = $this->Session->read("office_id");
        $lang = $this->Session->read("sess_langauge");
        $doc_lang = $this->Session->read("doc_lang");

        $result = $this->ApplicationSubmitted->application_document($token, $doc_lang);
        $stampconfig = $this->stamp_and_functions_config();
        $check = NULL;
        if (!empty($result)) {
            $result = $result[0][0];
            $check = 1;
            foreach ($stampconfig as $stamp) {
                if ($stamp['is_last'] == 'N' && $result[$stamp['stamp_flag']] == 'N') {
                    $check = 0;
                }
            }
        }
        return $check;
    }

    public function update_sro_acceptance($token, $extrafields = NULL) {
        $this->loadModel("property_details_entry");
        $this->loadModel('SroAcceptanceDetails');
        $this->loadModel('stamp_duty_adjustment');
        $this->loadModel('fee_exemption');
        $this->loadModel('investment_details');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('stamp_duty');
        $this->loadModel('regconfig');


        if ($extrafields == NULL) {
            //-------------------------------------- For prohibited property : acceptance_id =1 --------------------------------------------------------------------------------------------
            $propertydetails = $this->property_details_entry->find("all", array('conditions' => array('token_no' => $token, 'prohibited_flag' => 'Y')));
            if (!empty($propertydetails)) {
                $SroAcceptance = $this->SroAcceptanceDetails->find("all", array('conditions' => array('token_no' => $token, 'acceptance_id' => 1)));
                if (empty($SroAcceptance)) {
                    $this->SroAcceptanceDetails->create();
                    $this->SroAcceptanceDetails->save(array('acceptance_id' => 1, 'token_no' => $token));
                }
            }
            //----------------------------------------------------------------------------------------------------------------------------------
            //-------------------------------------- For adjustment : acceptance_id =2 --------------------------------------------------------------------------------------------
            $adjustment = $this->stamp_duty_adjustment->find("all", array('conditions' => array('token_no' => $token)));
            if (!empty($adjustment)) {
                $SroAcceptance = $this->SroAcceptanceDetails->find("all", array('conditions' => array('token_no' => $token, 'acceptance_id' => 2)));
                if (empty($SroAcceptance)) {
                    $this->SroAcceptanceDetails->create();
                    $this->SroAcceptanceDetails->save(array('acceptance_id' => 2, 'token_no' => $token));
                }
            }
            //-------------------------------------- For fee_exemption : acceptance_id =3 --------------------------------------------------------------------------------------------

            $exemption = $this->fee_exemption->find("all", array('conditions' => array('token_no' => $token)));
            if (!empty($exemption)) {
                $SroAcceptance = $this->SroAcceptanceDetails->find("all", array('conditions' => array('token_no' => $token, 'acceptance_id' => 3)));
                if (empty($SroAcceptance)) {
                    $this->SroAcceptanceDetails->create();
                    $this->SroAcceptanceDetails->save(array('acceptance_id' => 3, 'token_no' => $token));
                }
            }
            //-------------------------------------- For 5GA : acceptance_id =4 --------------------------------------------------------------------------------------------
            $info = $this->genernalinfoentry->find("first", array('conditions' => array('token_no' => $token)));

            if (!empty($info) && !empty($info['genernalinfoentry']['presentation_date'])) {
                $pdate = date("Y-m-d", strtotime($info['genernalinfoentry']['presentation_date']));
                $investments = $this->investment_details->query("select * from ngdrstab_trn_stamp_duty_investment_detail where  token_no=? AND online_invest_doc_date > '$pdate'::date -365 ", array($token));
                if (!empty($investments)) {
                    $SroAcceptance = $this->SroAcceptanceDetails->find("all", array('conditions' => array('token_no' => $token, 'acceptance_id' => 4)));
                    if (empty($SroAcceptance)) {
                        $this->SroAcceptanceDetails->create();
                        $this->SroAcceptanceDetails->save(array('acceptance_id' => 4, 'token_no' => $token));
                    }
                }
            }
            $regconf = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 141, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            //pr($regconf);exit;
            if (!empty($regconf)) {
                $stamp_duty = $this->stamp_duty->get_stamp_duty_agriment($token, 'en', $regconf['regconfig']['info_value']);
                //pr($stamp_duty);exit;
                if (!empty($stamp_duty)) {
                    $SroAcceptance = $this->SroAcceptanceDetails->find("all", array('conditions' => array('token_no' => $token, 'acceptance_id' => 5)));
                    if (empty($SroAcceptance)) {
                        $this->SroAcceptanceDetails->create();
                        $this->SroAcceptanceDetails->save(array('acceptance_id' => 5, 'token_no' => $token));
                    }
                }
            }


            //----------------------------------------------------------------------------------------------------------------------------------
        } else {
            $condition['acceptance_id'] = $extrafields['acceptance_id'];
            $extrafields['remark_flag'] = "'Y'";
            $extrafields['acceptance_flag'] = $extrafields['acceptance_flag'];
            $condition['token_no'] = $token;
            $this->SroAcceptanceDetails->updateAll($extrafields, $condition);
        }
    }

    public function details_sro_acceptance($acceptance_id, $removebuttonflag = NULL) {
        $this->loadModel('stamp_duty_adjustment');
        $this->loadModel('fee_exemption');
        $this->loadModel('property_details_entry');
        $this->loadModel('investment_details');

        $token = $this->Session->read("reg_token");
        $lang = $this->Session->read("sess_langauge");
        try {
            //  pr($acceptance_id);
            $html = '<table class="table table-bordered">';
            switch ($acceptance_id) {
                case 1: $propertydetails = $this->property_details_entry->query("select list.prohibition_desc_$lang,list.prohibition_remark_$lang from ngdrstab_trn_property_details_entry  as property, ngdrstab_mst_prohibited_prop_list as list  where list.prohibited_id=property.prohibited_id and  property.token_no=?", array($token));

                    $html .= "<thead><tr><td>" . __('lblprohibitedprop') . "</td><td>" . __('lblprohibitionrmk') . "</td></tr></thead><tbody>";
                    foreach ($propertydetails as $propertydetail) {
                        $propertydetail = $propertydetail[0];
                        $html .= "<tr><td>" . $propertydetail['prohibition_desc_' . $lang] . "</td><td>" . $propertydetail['prohibition_remark_' . $lang] . "</td></tr>";
                    }

                    break;
                case 2:
                    $adjustments = $this->stamp_duty_adjustment->find("all", array('conditions' => array('token_no' => $token)));

                    if ($removebuttonflag == 'Y') {
                        $html .= "<thead><tr><td>" . __('lbldocregno') . "</td><td>" . __('lbladjustmentamt') . "</td><td>Action</td></tr></thead><tbody>";
                    } else {
                        $html .= "<thead><tr><td>" . __('lbldocregno') . "</td><td>" . __('lbladjustmentamt') . "</td></tr></thead><tbody>";
                    }
                    foreach ($adjustments as $adjustment) {
                        $adjustment = $adjustment['stamp_duty_adjustment'];

                        $docno = $adjustment['online_adj_doc_no'] ? $adjustment['online_adj_doc_no'] : $adjustment['counter_adj_doc_no'];
                        $amt = $adjustment['online_adj_amt'] ? $adjustment['online_adj_amt'] : $adjustment['counter_adj_amt'];
                        if ($removebuttonflag == 'Y') {
                            $html .= "<tr><td>" . $docno . "</td><td>" . $amt . "</td><td><a href='" . $this->webroot . "Registration/remove_sro_acceptance/2/" . $adjustment['sd_adj_id'] . "'>Delete</a></td></tr>";
                        } else {
                            $html .= "<tr><td>" . $docno . "</td><td>" . $amt . "</td></tr>";
                        }
                    }
                    break;
                case 3:
                    $exemptions = $this->fee_exemption->query("select feerule.fee_rule_desc_$lang,fee_exe.exemption_amt,fee_exe.fee_calc_id from ngdrstab_trn_fee_exemption as fee_exe, ngdrstab_mst_article_fee_rule feerule where fee_exe.fee_rule_id=feerule.fee_rule_id and  fee_exe.token_no=?", array($token));
                    if ($removebuttonflag == 'Y') {
                        $html .= "<thead><tr><td>" . __('lblExemptionFeerule') . "</td><td>" . __('lblFeeExemption') . "</td><td>Action</td></tr></thead><tbody>";
                    } else {
                        $html .= "<thead><tr><td>" . __('lblExemptionFeerule') . "</td><td>" . __('lblFeeExemption') . "</td></tr></thead><tbody>";
                    }
                    foreach ($exemptions as $exemption) {

                        if ($removebuttonflag == 'Y') {
                            $html .= "<tr><td>" . $exemption[0]['fee_rule_desc_' . $lang] . "</td><td>" . $exemption[0]['exemption_amt'] . "</td><td><a href='" . $this->webroot . "Registration/remove_sro_acceptance/3/" . $exemption['0']['fee_calc_id'] . "'>Delete</a></td></tr>";
                        } else {
                            $html .= "<tr><td>" . $exemption[0]['fee_rule_desc_' . $lang] . "</td><td>" . $exemption[0]['exemption_amt'] . "</td></tr>";
                        }
                    }
                    break;
                case 4:
                    $investments = $this->investment_details->find("all", array('conditions' => array('token_no' => $token)));

                    if ($removebuttonflag == 'Y') {
                        $html .= "<thead>" . "<tr><td colspan=3>" . __('Investment Clause Adjusted Amount') . "</td></tr>"
                                . "<tr><td>" . __('lblolddocno') . "</td><td>" . __('lblamount') . "</td><td>Action</td></tr></thead><tbody>";
                    } else {
                        $html .= "<thead><tr><td colspan=2>" . __('Investment Clause Adjusted Amount') . "</td></tr>"
                                . "<tr><td>" . __('lblolddocno') . "</td><td>" . __('lblamount') . "</td></tr></thead><tbody>";
                    }
                    foreach ($investments as $investment) {
                        if ($removebuttonflag == 'Y') {
                            $html .= "<tr><td>" . $investment['investment_details']['online_invest_doc_no'] . "</td><td>" . $investment['investment_details']['invest_stamp_amount'] . "</td><td><a href='" . $this->webroot . "Registration/remove_sro_acceptance/4/" . $investment['investment_details']['sd_invet_id'] . "'>Delete</a></td></tr>";
                        } else {
                            $html .= "<tr><td>" . $investment['investment_details']['online_invest_doc_no'] . "</td><td>" . $investment['investment_details']['invest_stamp_amount'] . "</td></tr>";
                        }
                    }
                    break;
            }
            $html .= '</tbody></table>';
            return $html;
        } catch (Exception $exc) {
            // pr($exc->getMessage());
            // exit;
        }
    }

    public function remove_sro_acceptance($acceptance_id, $reference_id) {
        $this->loadModel('stamp_duty_adjustment');
        $this->loadModel('fee_exemption');
        $this->loadModel('property_details_entry');
        $this->loadModel('SroAcceptanceDetails');
        $this->loadModel('fees_calculation');
        $this->loadModel('fees_calculation_detail');
        $this->loadModel('investment_details');
        $token = $this->Session->read("reg_token");
        $lang = $this->Session->read("sess_langauge");
        try {

            switch ($acceptance_id) {
                case 1:
                    break;
                case 2:
                    $adjustments = $this->stamp_duty_adjustment->deleteAll(array('sd_adj_id' => $reference_id, 'token_no' => $token));
                    $this->SroAcceptanceDetails->deleteAll(array('acceptance_id' => $acceptance_id, 'token_no' => $token));
                    break;
                case 3:
                    $fee = $this->fees_calculation->find('all', array('conditions' => array('fee_calc_id' => $reference_id, 'token_no' => $token)));
                    if (!empty($fee)) {
                        $this->fees_calculation->deleteAll(array('fee_calc_id' => $reference_id, 'token_no' => $token));
                        $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $reference_id));
                        $this->fee_exemption->deleteAll(array('fee_calc_id' => $reference_id, 'token_no' => $token));
                        $this->SroAcceptanceDetails->deleteAll(array('acceptance_id' => $acceptance_id, 'token_no' => $token));
                    }
                    break;
                case 4:
                    $investments = $this->investment_details->deleteAll(array('sd_invet_id' => $reference_id, 'token_no' => $token));
                    $this->SroAcceptanceDetails->deleteAll(array('acceptance_id' => $acceptance_id, 'token_no' => $token));
                    break;
            }
            $this->Session->setFlash("lbldeletemsg");
            $this->redirect(array('controller' => 'Registration', 'action' => 'payment_verification'));
        } catch (Exception $exc) {
            // pr($exc->getMessage());
            // exit;
        }
    }

    public function document_qr_bar_code($type = 'QR') {
        $this->loadModel('ApplicationSubmitted');
        $this->autoRender = FALSE;
        try {
            $utility = new UtilityController();
            $utility->constructClasses();
            $token = $this->Session->read("reg_token");
            if (isset($token) && is_numeric($token)) {
                $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? ", array($token));
                if (!empty($application)) {
                    $application = $application['0']['0'];
                    if ($type == 'QR') {
                        $utility->QRcode($application['doc_reg_no']);
                    } elseif ($type == 'BAR') {
                        $utility->Barcode($application['doc_reg_no']);
                    }
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function main_menu() {
        $this->loadModel('RegistrationMainmenu');
        return $this->RegistrationMainmenu->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id')), 'order' => 'mm_serial ASC'));
    }

    public function sub_menu() {
        $this->loadModel('RegistrationSubmenu');
        return $this->RegistrationSubmenu->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id')), 'order' => 'sm_serial ASC'));
    }

    public function subsub_menu() {
        $this->loadModel('RegistrationSubsubmenu');
        $this->loadModel('RegistrationScreenMapping');

        $article = $this->Session->read('selectedarticle_id');
        if (!is_null($article)) {
            $menusc = $this->RegistrationSubsubmenu->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('is_optional' => 'N', 'state_id' => $this->Auth->user('state_id')), 'order' => 'ssm_serial ASC'));
            $menuso = $this->RegistrationScreenMapping->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_id'), 'conditions' => array('article_id' => $article)));
            $ids = array_merge($menusc, $menuso);
            $functions = $this->RegistrationSubsubmenu->find('all', array(
                'conditions' => array('subsubmenu_id' => $ids, 'role_id' => $this->Session->read("user_role_id"))
                , 'order' => 'function_order ASC'
            ));
        } else {
            $functions = $this->RegistrationSubsubmenu->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id'), 'role_id' => $this->Session->read("user_role_id")), 'order' => 'function_order ASC'));
        }

        return $functions;
    }

    public function document_identification($lock_identifire_id = NULL, $csrftoken = NULL) {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            $this->loadModel("identification");
            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('cap', NULL);
            $this->set('pic', NULL);
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $this->Session->write("sroidetifier", 'Y');
            $language = $this->Session->read('sess_langauge');
            $citizen_user_id = $this->Session->read("citizen_user_id");
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $this->set("identifiers", $identifier = $this->identification->get_identification_details($language, $tokenno, $citizen_user_id));
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $userid = $this->Auth->User('user_id');
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);

            if ($this->request->is('post')) {

                $uploadeddate = date('Y-m-d H:i:s');
                $this->request->data['identifier']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['identifier']['req_ip'] = $this->request->clientIp();
                $csrf = (@$this->request->data['identifier']['csrftoken'] ? $this->request->data['identifier']['csrftoken'] : $this->request->data['other_options']['csrftoken']);
                $this->check_csrf_token($csrf);
                if (isset($this->request->data['other_options'])) {
                    $rdata = $this->request->data['other_options'];
                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    }

                    if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);

                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);

                        $this->identification->updateAll(
                                array('biodevice_working_flag' => "'Y'"), array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    }


                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('document_identification');
                }
                if (isset($this->request->data['btnaccept'])) {
                    // should validate before update
                    if ($this->identification->validate($identifier, $path)) {
                        $this->update_stamp_function_flags($this->request->params['action']);
                        $lockall['record_lock'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                        );
                        $this->Session->setFlash(__("Identification Details Completed Sucessfully"));
                    } else {
                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                    }
                    $this->redirect('document_identification');
                }


                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];
                if ($_POST['actiontype'] == '1') {
                    $folder = "Biometric_Identifier";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";

                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_identifierid_' . $id . '.png';

                    $check_record = $this->identification->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_identifierid_' . $id . '.png';

                        $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET biometric_fingure=? , biometric_img=?, biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=? ", array($cap, $loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                    $this->redirect('document_identification');
                }
                if ($_POST['actiontype'] == '2') {
                    $loc = $this->identification->query("select biometric_img,photo_img from ngdrstab_trn_identification WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                    if (!empty($loc)) {
                        $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET biometric_fingure=? , biometric_img=?, biometric_upload=?, photo_upload=?, photo_img=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL) {
                            $loc1 = $loc[0][0]['biometric_img'];
                            $loc2 = $loc[0][0]['photo_img'];
                            if (is_file($loc1)) {
                                unlink($loc1);
                            }
                            if (is_file($loc2)) {
                                unlink($loc2);
                            }
                            $this->reset_stamp_function_flags($this->request->params['action']);
                            $this->Session->setFlash(__("Biometric Reset Successfully"));
                        } else {
                            $this->Session->setFlash(__("Biometric Reset Failed"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                    $this->redirect('document_identification');
                }
                if ($_POST['actiontype'] == '3') {
                    $folder = "Photo_Identifier";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";

                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['pic'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_identifierid_' . $id . '.jpg';
                    $check_record = $this->identification->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_identifierid_' . $id . '.jpg';
                        $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET photo_img=?, photo_upload=?,org_user_id=?,org_updated=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                    $this->redirect('document_identification');
                }
            }

            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            }
            if (is_numeric($lock_identifire_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->identification->validate($identifier, $path, $lock_identifire_id)) {
                    $lockall = array();
                    $lockall['record_lock'] = "'Y'";
                    $lockall = $this->add_default_fields_updateAll($lockall);
                    $this->identification->updateAll(
                            $lockall, array('token_no' => $tokenno, 'record_lock' => "N", 'identification_id' => $lock_identifire_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                } else {
                    $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                }
                $this->redirect('document_identification');
            }
            $stampconfig = $this->stamp_and_functions_config('stampconfig');
            $this->set("stampconfig", $stampconfig);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function document_witness($lock_witness_id = NULL, $csrftoken = NULL) {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            $this->loadModel("witness");
            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('cap', NULL);
            $this->set('pic', NULL);
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $language = $this->Session->read('sess_langauge');
            $doc_lang = $this->Session->read('doc_lang');
            $this->set('doc_lang', $doc_lang);
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $this->set("witness", $witness = $this->witness->get_witness($language, $tokenno));
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $userid = $this->Auth->User('user_id');
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);
            if ($this->request->is('post')) {
                $this->request->data['witness']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['witness']['req_ip'] = $this->request->clientIp();
                $uploadeddate = date('Y-m-d H:i:s');

                $csrf = (@$this->request->data['witness']['csrftoken'] ? $this->request->data['witness']['csrftoken'] : $this->request->data['other_options']['csrftoken']);
                $this->check_csrf_token($csrf);
                if (isset($this->request->data['other_options'])) {
                    $rdata = $this->request->data['other_options'];
                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    }

                    if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    }


                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('document_witness');
                }

                if (isset($this->request->data['btnaccept'])) {
// should validate before update
                    if ($this->witness->validate($witness, $path)) {
                        $this->update_stamp_function_flags($this->request->params['action']);
                        $lockall = array();
                        $lockall['record_lock'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);

                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                        );
                        $this->Session->setFlash(__("Witness Details Completed Sucessfully"));
                    } else {
                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                    }
                    $this->redirect('document_witness');
                }


                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];
                if ($_POST['actiontype'] == '1') {
                    $folder = "biometric_witness";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_witnessid_' . $id . '.png';
                    $check_record = $this->witness->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_witnessid_' . $id . '.png';
                        $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET biometric_fingure=? , biometric_img=? , biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect('document_witness');
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect('document_witness');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('document_witness');
                    }
                }
                if ($_POST['actiontype'] == '2') {
                    $loc = $this->witness->query("select biometric_img,photo_img from ngdrstab_trn_witness WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                    if (!empty($loc)) {
                        $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET biometric_fingure=? , biometric_img=?, photo_img=? , biometric_upload=?, photo_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL) {
                            $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
                            $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                            if (is_file($loc1)) {
                                unlink($loc1);
                            }
                            if (is_file($loc2)) {
                                unlink($loc2);
                            }
                            $this->reset_stamp_function_flags($this->request->params['action']);
                            $this->Session->setFlash(__("Biometric Reset Successfully"));
                            $this->redirect('document_witness');
                        } else {
                            $this->Session->setFlash(__("Biometric Reset Failed"));
                            $this->redirect('document_witness');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('document_witness');
                    }
                }
                if ($_POST['actiontype'] == '3') {

                    $folder = "photo_witness";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
// to check directory exist or not 
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['pic'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_witnessid_' . $id . '.jpg';
                    $check_record = $this->witness->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_witnessid_' . $id . '.jpg';
                        $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET photo_img=? , photo_upload=?,org_user_id=?,org_updated=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                            $this->redirect('document_witness');
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                            $this->redirect('document_witness');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('document_witness');
                    }
                }
            }

            if (is_numeric($lock_witness_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->witness->validate($witness, $path, $lock_witness_id)) {
                    $lockall = array();
                    $lockall['record_lock'] = "'Y'";
                    $lockall = $this->add_default_fields_updateAll($lockall);
                    $this->witness->updateAll(
                            $lockall, array('token_no' => $tokenno, 'witness_id' => $lock_witness_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                } else {
                    $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                }
                $this->redirect('document_witness');
            }


            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            }

            $stampconfig = $this->stamp_and_functions_config('stampconfig');
            $this->set("stampconfig", $stampconfig);
            $this->set_csrf_token();
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //_five finger config 72
    public function party_old($lock_party_id = NULL, $csrftoken = NULL) {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            $this->loadModel("party_entry");
            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->loadModel('regconfig');

            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('hffinger', NULL);
            $this->set('cap', NULL);
            $this->set('pic', NULL);
//             $this->set('path', NULL);
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $userid = $this->Auth->User('user_id');
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $language = $this->Session->read('sess_langauge');
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $citizen_user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read("doc_lang");
            $this->Session->write("sroparty", 'Y');

            $this->set("partylist", $party = $this->party_entry->get_partyrecord($tokenno, $citizen_user_id, $doc_lang, $language, 'N'));

            foreach ($party as $party1) {
                if ($party1[0]['home_visit_flag'] == 'N') {
                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                        $fieldlistmultiform['otheroptions' . $party1[0]['party_id']]['admission_pending_remark_' . $party1[0]['party_id']]['text'] = 'is_required,is_alphanumericspace';
                    }
                }
            }
            if (!empty($fieldlistmultiform)) {
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }


            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);

            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 72)));
            if (!empty($regconfig)) {
                $fivefinger = $regconfig['regconfig']['info_value'];
//                pr($fivefinger);exit;
                $this->set('fivefinger', $fivefinger);
            }
            if ($fivefinger == 'Y') {
//            $this->loadModel('fingerdescription');
                $this->set('fingerdescription', ClassRegistry::init('fingerdescription')->find('list', array('fields' => array('fingerdescription_id', 'finger_description'), 'order' => array('fingerdescription_id' => 'ASC'))));
            } else {
                $this->loadModel('fingerdescription');
                // $this->set('fingerdescription', ClassRegistry::init('fingerdescription')->find('list',array('fields' => array('fingerdescription_id', 'finger_description'),array('Conditions' => array('orderflag' => 'F')))));
                $fingerdescription = $this->fingerdescription->find("list", array('conditions' => array('fingerdescription_id' => 1), 'fields' => array('fingerdescription_id', 'finger_description')));
                $this->set('fingerdescription', $fingerdescription);
            }



            if ($this->request->is('post')) {
                $csrf = (@$this->request->data['party']['csrftoken'] ? $this->request->data['party']['csrftoken'] : $this->request->data['other_options']['csrftoken']);
                $this->check_csrf_token($csrf);
                $uploadeddate = date('Y-m-d H:i:s');
                if (isset($this->request->data['other_options'])) {
                    $rdata = $this->request->data['other_options'];

                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    } else {
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    if ($fivefinger == 'Y') {

                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }



                        if (@$rdata['biodevice_working_flag2'] == 1) { //  biodevice_working_flag2
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag3'] == 1) { //  biodevice_working_flag3
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag4'] == 1) { //  biodevice_working_flag4
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag5'] == 1) { //  biodevice_working_flag5
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    } else {
                        //one thumb
                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    }




                    if (@$rdata['admission_pending_flag'] == 1) { //  admission_pending_flag
                        $fieldsetnew['admission_pending_remark']['text'] = 'is_required,is_alphanumericspace';
                        $errors = $this->validatedata($rdata, $fieldsetnew);
                        if ($this->ValidationError($errors)) {
                            $this->party_entry->updateAll(
                                    array('admission_pending_flag' => "'Y'", 'admission_pending_remark' => "'" . $rdata['admission_pending_remark'] . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->Session->setFlash(__("Please Check validations"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->party_entry->updateAll(
                                array('admission_pending_flag' => "'N'", 'admission_pending_remark' => "'" . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('party');
                }


                //  $this->request->data['party']['state_id'] = $this->Auth->User('state_id');
                //  $this->request->data['party']['user_id'] = $this->Auth->User('user_id');
// $this->request->data['party']['created_date'] = date('Y/m/d H:i:s');
                //  $this->request->data['party']['req_ip'] = $_SERVER['REMOTE_ADDR'];

                if (isset($this->request->data['btnaccept'])) {
// should validate before update
                    if ($this->party_entry->validate($party, $path)) {
                        $this->update_stamp_function_flags($this->request->params['action']);

                        $lockall['record_lock'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->party_entry->updateAll(
                                $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                        );
                        $this->save_documentstatus(3, $tokenno, $office_id);
                        $this->Session->setFlash(__("Party Details Completed Sucessfully"));
                    } else {
                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                    }
                    $this->redirect('party');
                }

                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];
                $fingervalue = $_POST['hffinger'];
                if ($fingervalue != Null) {
                    $fingerdesc = $this->fingerdescription->query("select * from ngdrstab_mst_five_finger where fingerdescription_id=?", array($fingervalue));

                    $fingername = $fingerdesc[0][0]['finger_description'];
                }
                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 79, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                $confphoto = $regconf ? $regconf[0]['regconfig']['info_value'] : 0;

                if ($_POST['actiontype'] == '1') {
                    $folder = "Biometric_Party";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";

//                    pr($this->request->data);
//                    pr($UPLOAD_DIR);exit;
// to check directory exist or not 
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
//                    pr($file);exit;
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
                        if ($fivefinger == 'Y') {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'IndexFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure2=? , biometric_img2=?, biometric_upload2=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'MiddleFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure3=? , biometric_img3=?, biometric_upload3=?  ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'RingFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure4=? , biometric_img4=?, biometric_upload4=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'BabyFinge') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        } else {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        }

                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect('party');
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('party');
                    }
                }

                //reset biometric
                if ($_POST['actiontype'] == '2') {
//                    pr($fivefinger);exit;

                    if ($fivefinger == 'Y') {


                        $loc = $this->party_entry->query("select biometric_img,biometric_img2,biometric_img3,biometric_img4,biometric_img5,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
//                  pr($loc);
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                                 biometric_fingure2=? , biometric_img2=?, biometric_upload2=?,"
                                    . "                                                                 biometric_fingure3=? , biometric_img3=?, biometric_upload3=?,"
                                    . "                                                                 biometric_fingure4=? , biometric_img4=?, biometric_upload4=?,"
                                    . "                                                                 biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,photo_upload=?, photo_img=?,org_updated=?,org_user_id=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
                                $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
//                            pr($loc1);exit;
                                $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
                                $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
                                $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
                                $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
                                if (is_file($loc3)) {
                                    unlink($loc3);
                                }
                                if (is_file($loc4)) {
                                    unlink($loc4);
                                }
                                if (is_file($loc5)) {
                                    unlink($loc5);
                                }
                                if (is_file($loc6)) {
                                    unlink($loc6);
                                }
                                if (is_file($loc2)) {
                                    unlink($loc2);
                                }
                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('party');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('party');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('party');
                        }
                    } else {
                        $loc = $this->party_entry->query("select biometric_img,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                              photo_upload=?, photo_img=? ,org_updated=?,org_user_id=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
//                            $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
////                            pr($loc1);exit;
//                            $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
//                            $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
//                            $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
                                $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
//                             if (is_file($loc3)) {
//                                unlink($loc3);
//                            }
//                             if (is_file($loc4)) {
//                                unlink($loc4);
//                            }
//                             if (is_file($loc5)) {
//                                unlink($loc5);
//                            }
//                             if (is_file($loc6)) {
//                                unlink($loc6);
//                            }
                                if (is_file($loc2)) {
                                    unlink($loc2);
                                }
                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('party');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('party');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('party');
                        }
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
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $updateflag = 0;
                        if ($confphoto == 0) {
                            $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '.jpg';
                            $success = file_put_contents($file, $data);
                            $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '.jpg';
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            $updateflag = 1;
                        } else if ($confphoto == 1) {
                            foreach ($party as $party1) {
                                if ($party1[0]['home_visit_flag'] == 'N' && $party1[0]['record_lock'] == 'N') {
                                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                                        $file = UPLOAD_DIR . $tokenno . '_partyid_' . $party1[0]['id'] . '.jpg';
                                        $success = file_put_contents($file, $data);
                                        $loc = $folder . "/" . $tokenno . '_partyid_' . $party1[0]['id'] . '.jpg';
                                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $party1[0]['id'], $tokenno, 'N'));
                                        $updateflag = 1;
                                    }
                                }
                            }
                        }
                        if ($check == NULL && $loc != NULL && $updateflag == 1) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                            $this->redirect('party');
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('party');
                    }
                }
            }
            if (is_numeric($lock_party_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->party_entry->validate($party, $path, $lock_party_id)) {
                    $uploadeddate = date('Y-m-d H:i:s');
                    $this->party_entry->updateAll(
                            array('record_lock' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                    $this->redirect('party');
                }
            }



            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            }
            $stampconfig = $this->stamp_and_functions_config();
            $this->set("stampconfig", $stampconfig);
            $this->set_csrf_token();
        } catch (Exception $exc) {
//            pr($exc);
//            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function sroaction() {
        try {
            $this->autoRender = False;
            if (isset($_POST['id']) && isset($_POST['text'])) {
                array_map([$this, 'loadModel'], ['ekyc']);

                $this->ekyc->query("update ngdrstab_trn_ekycverification_details set sro_accept_flag=? where id=?", array($_POST['text'], $_POST['id']));
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function otpekyc() {
        try {
//            $ret = 'y';
//            $txn = 4545545;
//            $err = 'slfsdlkfjsl';
            $uid = $_POST['uid'];
            $sa = "100103100";
            $req = "OTP";
            $kycurl = "http://aadhaar.jharkhand.gov.in:8055/aua/api/2.0/xml/request/otp";
            $path = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/ekycotpjh.jar";
            $message = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $path . ' ' . $req . ' ' . $uid . ' ' . $sa . ' ' . $kycurl, $result);
            $xmlresp = new SimpleXMLElement(utf8_encode($message));
            $ret = (String) $xmlresp['ret'];
            $txn = (String) $xmlresp['txn'];

            if ($ret == "y") {
                $info = (String) $xmlresp['info'];
                $info1 = substr($info, strpos($info, '*') + strlen('*'));
                $mobno = substr($info1, 0, strpos($info1, ','));
                $message = "OTP has Sent to your Registered Mobile Number $mobno....!!!";
                $resultarray = array('status' => $ret,
                    'message' => $message,
                    'txn' => $txn);
                $this->autoRender = False;
                echo json_encode($resultarray);
//                $this->Session->setFlash("OTP has Sent to your Registered Mobile Number....!!!");
            } else {
                $err = (String) $xmlresp['err'];
                $message = "Something went wrong...KUA server response Error-$err....!!!";
                $resultarray = array('status' => $ret,
                    'message' => $message);
                $this->autoRender = False;
                echo json_encode($resultarray);
//                $this->Session->setFlash("Something went wrong...KUA server response Error-$err....!!!");
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    public function ekycverification() {
        try {

//            pr($_POST);
//            exit;
            $this->set('ret', null);
            $this->set('hfverificationid', null);
            if ($_POST['flag'] == 'O') {
                array_map([$this, 'loadModel'], ['ekyc']);
                $uid = $_POST['uid'];
                $txn = $_POST['txn'];
                $sa = "100103100";
                $req = "AUTH";
                $otp = $_POST['otp'];
                $kycurl = "http://aadhaar.jharkhand.gov.in:8055/otp/rest/2.0/api/ekyc/";
                $path = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/ekycotpjh.jar";
                $message = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $path . ' ' . $req . ' ' . $uid . ' ' . $sa . ' ' . $kycurl . ' ' . $otp . ' ' . $txn, $result);
                $response = json_decode($message, true);
                $ret = $response['ret'];
                $txn = $response['txn'];
                $this->set('ret', $ret);
                $Pht = $response['kyc']['photo'];
                $record['party_id'] = $_POST['id'];
                $record['token_no'] = $this->Session->read("reg_token");
                $record['txn_no'] = $txn;
                $record['ekyc_resp_flag'] = $ret;
                $record['state_id'] = $this->Auth->User("state_id");
                $record['user_id'] = $this->Auth->User('user_id');
                $record['created_date'] = date('Y/m/d H:i:s');
                $record['req_ip'] = $_SERVER['REMOTE_ADDR'];

                $check = $this->ekyc->query("select * from ngdrstab_trn_ekycverification_details where party_id=?", array($_POST['id']));
                $verificationid = null;
                if ($check == NULL) {
                    $this->ekyc->save($record);
                    $verificationid = $this->ekyc->getLastInsertId();
                } else {
                    $record['id'] = $check[0][0]['id'];
                    $this->ekyc->save($record);
                    $verificationid = $check[0][0]['id'];
                }
//                    pr($response);
//                    if ($this->ekyc->save($record)) {
//                        $verificationid = $this->ekyc->getLastInsertId();
                $this->set('hfverificationid', $verificationid);
                if ($ret == "Y") {
                    $Pht = $response['kyc']['photo'];
                    $uid = $response['kyc']['uid'];

                    $gender = $response['kyc']['poi']['gender'];
                    $dob = $response['kyc']['poi']['dob'];
                    $name = $response['kyc']['poi']['name'];

                    $co = $response['kyc']['poa']['co'];
                    $country = $response['kyc']['poa']['pc'];
                    $dist = $response['kyc']['poa']['dist'];
                    $house = $response['kyc']['poa']['house'];
                    $lm = $response['kyc']['poa']['lm'];
                    $loc = $response['kyc']['poa']['loc'];
                    $pc = $response['kyc']['poa']['pc'];
                    $po = $response['kyc']['poa']['po'];
                    $state = $response['kyc']['poa']['state'];
                    $street = $response['kyc']['poa']['street'];
                    $subdist = $response['kyc']['poa']['subdist'];
                    $vtc = $response['kyc']['poa']['vtc'];

                    $add = $house . ", " . $lm . ", " . $street . ", " . $loc . ", " . $vtc . ", " . $subdist . ", " . $dist . ", " . $pc . ", " . $po . ", " . $state . ", " . $country;

                    $record1['id'] = $verificationid;
                    $record1['photo'] = $Pht;
                    $record1['uid_no'] = $uid;
                    $record1['gender'] = $gender;
                    $record1['dob'] = date('Y-m-d', strtotime($dob));
                    $record1['fullname'] = $name;
                    $record1['sonof'] = $co;
                    $record1['address'] = $add;
                    $record1['verify_by'] = 'O';
                    $this->ekyc->save($record1);
                    if ($gender == 'M') {
                        $gender = "MALE";
                    } else if ($gender == 'F') {
                        $gender = "FEMALE";
                    }
                    $this->set(compact('Pht', 'uid', 'gender', 'dob', 'name', 'add', 'co'));
                } else {
                    $this->autoRender = False;
                    $err = $response['err'];
                    $resultarray = array('message' => "Something went wrong...KUA server response Error-$err....!!!");
                    echo json_encode($resultarray);
//                                $this->Session->setFlash("Something went wrong...KUA server response Error-$err....!!!");
                }
//                    }
            } else {
//            if (isset($_POST['id']) && isset($_POST['uid']) && isset($_POST['capturexml']) && isset($_POST['consent'])) {
                array_map([$this, 'loadModel'], ['ekyc']);
                $uid = $_POST['uid'];
                $xmldata = $_POST['capturexml'];
                $concent = $_POST['consent'];
                date_default_timezone_set("Asia/Kolkata");
                $appCode = "KYCApp";
                $sa = "100103100";
                $saTxn = "UKC:100103100-" . date("Y-m-d") . "T" . date("h:i:s");

                $xml = new SimpleXMLElement($xmldata);
                $errCode = (String) $xml->Resp[0]['errCode'];
                if ($errCode == 0) {
                    $Data = (String) $xml->Data;
                    $Hmac = (String) $xml->Hmac;
                    $Skey = (String) $xml->Skey;
                    $ci = (String) $xml->Skey[0]['ci'];
                    $udc = "enBIO10052012XDev09";
                    $rdsId = (String) $xml->DeviceInfo[0]['rdsId'];
                    $rdsVer = (String) $xml->DeviceInfo[0]['rdsVer'];
                    $dpId = (String) $xml->DeviceInfo[0]['dpId'];
                    $dc = (String) $xml->DeviceInfo[0]['dc'];
                    $mi = (String) $xml->DeviceInfo[0]['mi'];
                    $mc = (String) $xml->DeviceInfo[0]['mc'];
                    $xmlns = "http://www.uidai.gov.in/authentication/uid-auth-request/2.0"; // Jharkhand
                    $kycurl = "http://aadhaar.jharkhand.gov.in:8055/aua/api/2.0/xml/ekyc"; // Jharkhand

                    $path = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/Ekycjharkhand.jar";
                    $message = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $path . ' ' . $uid . ' ' . $appCode . ' ' . $sa . ' ' . $saTxn . ' ' . $Data . ' ' . $Hmac . ' ' . $Skey . ' ' . $ci . ' ' . $udc . ' ' . $rdsId . ' ' . $rdsVer . ' ' . $dpId . ' ' . $dc . ' ' . $mi . ' ' . $mc . ' ' . $concent . ' ' . $xmlns . ' ' . $kycurl, $result);
                    $result1 = null;
                    foreach ($result as $r) {
                        $result1 = $result1 . $r;
                    }

                    if ($message != "Sorry...!!! You have not accpted concent...!!!") {
                        $xmlresp = new SimpleXMLElement(utf8_encode($result1));
//                        pr($xmlresp);exit;
                        $ret = (String) $xmlresp['ret'];
                        $txn = (String) $xmlresp['txn'];
                        $this->set('ret', $ret);

                        $record['party_id'] = $_POST['id'];
                        $record['token_no'] = $this->Session->read("reg_token");
                        $record['txn_no'] = $txn;
                        $record['ekyc_resp_flag'] = $ret;
                        $record['state_id'] = $this->Auth->User("state_id");
                        $record['user_id'] = $this->Auth->User('user_id');
                        $record['created_date'] = date('Y/m/d H:i:s');
                        $record['req_ip'] = $_SERVER['REMOTE_ADDR'];
//                    pr($record);exit;

                        $check = $this->ekyc->query("select * from ngdrstab_trn_ekycverification_details where party_id=?", array($_POST['id']));
                        $verificationid = null;
                        if ($check == NULL) {
                            $this->ekyc->save($record);
                            $verificationid = $this->ekyc->getLastInsertId();
                        } else {
                            $record['id'] = $check[0][0]['id'];
                            $this->ekyc->save($record);
                            $verificationid = $check[0][0]['id'];
                        }


                        // if ($this->ekyc->save($record)) {
//                        $verificationid = $this->ekyc->getLastInsertId();
                        $this->set('hfverificationid', $verificationid);
                        if ($ret == "Y") {
                            $Pht = (String) $xmlresp->UidData->Pht;
                            $uid = (String) $xmlresp->UidData['uid'];
                            $gender = (String) $xmlresp->UidData->Poi['gender'];
                            $dob = (String) $xmlresp->UidData->Poi['dob'];
                            $name = (String) $xmlresp->UidData->Poi['name'];
                            $pc = (String) $xmlresp->UidData->Poa['pc'];
                            $state = (String) $xmlresp->UidData->Poa['state'];
                            $dist = (String) $xmlresp->UidData->Poa['dist'];
                            $vtc = (String) $xmlresp->UidData->Poa['vtc'];
                            $lm = (String) $xmlresp->UidData->Poa['lm'];
                            $co = (String) $xmlresp->UidData->Poa['co'];
                            $loc = (String) $xmlresp->UidData->Poa['loc'];
                            $country = (String) $xmlresp->UidData->Poa['country'];
                            $add = $loc . ", " . $lm . ", " . $vtc . ", " . $dist . ", " . $pc . ", " . $state . ", " . $country;

                            $record1['id'] = $verificationid;
                            $record1['photo'] = $Pht;
                            $record1['uid_no'] = $uid;
                            $record1['gender'] = $gender;
                            $record1['dob'] = date('Y-m-d', strtotime($dob));
                            $record1['fullname'] = $name;
                            $record1['sonof'] = $co;
                            $record1['address'] = $add;
                            $record1['verify_by'] = 'B';
                            $this->ekyc->save($record1);
                            if ($gender == 'M') {
                                $gender = "MALE";
                            } else if ($gender == 'F') {
                                $gender = "FEMALE";
                            }
                            $this->set(compact('Pht', 'uid', 'gender', 'dob', 'name', 'add', 'co'));
                        } else {
                            $this->autoRender = False;
                            $err = (String) $xmlresp['err'];
                            $resultarray = array('message' => "Something went wrong...KUA server response Error-$err....!!!");
                            echo json_encode($resultarray);
//                                $this->Session->setFlash("Something went wrong...KUA server response Error-$err....!!!");
                        }
                        // }
                    } else {
                        $this->autoRender = False;
                        $resultarray = array('message' => $message);
                        echo json_encode($resultarray);
//                        $this->Session->setFlash($message);
                    }
                } else {
                    $errInfo = (String) $xml->Resp[0]['errInfo'];
                    $this->autoRender = False;
                    $resultarray = array('message' => "Something went wrong...Error-$errInfo...Please Caputre Finger again....!!!");
                    echo json_encode($resultarray);
//                    $this->Session->setFlash("Something went wrong...Error-$errInfo...Please Caputre Finger again....!!!");
                }
            }
//            } else {
//                $this->Session->setFlash("Please Caputre Finger....!!!");
//            }
        } catch (Exception $exc) {
            pr($exc);
            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function party($lock_party_id = NULL, $csrftoken = NULL) {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            $this->loadModel('fingerdescription');
            $this->loadModel("party_entry");
            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->loadModel('regconfig');
            $this->set('hfxml', NULL);
            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('hffinger', NULL);
            $this->set('cap', NULL);
            $this->set('txn', NULL);
            $this->set('pic', NULL);
            $action = "wadh";
            $verStr = "2.5";
            $raStr = "F";
            $rcStr = "Y";
            $lrStr = "N";
            $deStr = "N";
            $pfrStr = "N";
            $path1 = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/Ekycjharkhand.jar";
            $wadh = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $path1 . ' ' . $action . ' ' . $verStr . ' ' . $raStr . ' ' . $rcStr . ' ' . $lrStr . ' ' . $deStr . ' ' . $pfrStr, $result1);
            $this->set('wadh', $wadh);
//            pr($wadh);
//            $message="lhr6Td1Tj4+b49a1KQrtOgmjrwq0/fZOflrjFg4dWtAPfcP69sjdWgYn6XmquMeUcRpxXA==";
//            $message1=utf8_encode($message);
//            pr($message1);
//            $xmlresp = new SimpleXMLElement(utf8_encode($message));
//            pr($xmlresp);exit;
//             $this->set('path', NULL);
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $userid = $this->Auth->User('user_id');
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $language = $this->Session->read('sess_langauge');
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $citizen_user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read("doc_lang");
            $this->Session->write("sroparty", 'Y');
            $article_id = $this->Session->read("selectedarticle_id");

            $this->set("partylist", $party = $this->party_entry->get_partyrecord($tokenno, $citizen_user_id, $doc_lang, $language, 'N'));

            foreach ($party as $party1) {
                if ($party1[0]['home_visit_flag'] == 'N') {
                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                        $fieldlistmultiform['otheroptions' . $party1[0]['party_id']]['admission_pending_remark_' . $party1[0]['party_id']]['text'] = 'is_required,is_alphanumericspace';
                    }
                }
            }
            if (!empty($fieldlistmultiform)) {
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }


            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);

            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 72)));
            if (!empty($regconfig)) {
                $fivefinger = $regconfig['regconfig']['info_value'];
//                pr($fivefinger);exit;
                $this->set('fivefinger', $fivefinger);
            }
            if ($fivefinger == 'Y') {
//            $this->loadModel('fingerdescription');
                $this->set('fingerdescription', ClassRegistry::init('fingerdescription')->find('list', array('fields' => array('fingerdescription_id', 'finger_description'), 'order' => array('fingerdescription_id' => 'ASC'))));
            } else {
                //            $this->loadModel('fingerdescription');  
                // $this->set('fingerdescription', ClassRegistry::init('fingerdescription')->find('list',array('fields' => array('fingerdescription_id', 'finger_description'),array('Conditions' => array('orderflag' => 'F')))));
                $fingerdescription = $this->fingerdescription->find("list", array('conditions' => array('fingerdescription_id' => 1), 'fields' => array('fingerdescription_id', 'finger_description')));
                $this->set('fingerdescription', $fingerdescription);
            }
            $btnpan = 0;
            $regconfigpan = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 133, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            if (!empty($regconfigpan)) {
                $valamount = $this->party_entry->query("select coalesce(SUM(cons_amt),0) as consamount from ngdrstab_trn_fee_calculation as fee where  fee.token_no=? and fee.article_id=? and fee.delete_flag='N'", array($tokenno, $article_id));
                if ($valamount[0][0]['consamount'] > $regconfigpan['regconfig']['info_value']) {
                    $btnpan = 1;
                }
                $valamount = $this->party_entry->query("select SUM(final_value)  as consamount
from ngdrstab_trn_property_details_entry as prop
JOIN ngdrstab_trn_valuation_details as vd ON vd.val_id=prop.val_id 
and item_type_id=2
where prop.val_id >0 AND prop.token_no=?", array($tokenno));

                if ($valamount[0][0]['consamount'] >= $regconfigpan['regconfig']['info_value']) {
                    $btnpan = 1;
                }
            }
            $this->set("btnpan", $btnpan);


            if ($this->request->is('post')) {
                if (isset($this->request->data['party']['csrftoken'])) {
                    $csrf = $this->request->data['party']['csrftoken'];
                } elseif (isset($this->request->data['other_options']['csrftoken'])) {
                    $csrf = $this->request->data['other_options']['csrftoken'];
                } elseif (isset($this->request->data['panparty']['csrftoken'])) {
                    $csrf = $this->request->data['panparty']['csrftoken'];
                }
                //$this->check_csrf_token($csrf);
                $uploadeddate = date('Y-m-d H:i:s');
                if (isset($this->request->data['other_options'])) {
                    $rdata = $this->request->data['other_options'];

                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    } else {
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    if ($fivefinger == 'Y') {

                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }



                        if (@$rdata['biodevice_working_flag2'] == 1) { //  biodevice_working_flag2
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag3'] == 1) { //  biodevice_working_flag3
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag4'] == 1) { //  biodevice_working_flag4
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag5'] == 1) { //  biodevice_working_flag5
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    } else {
                        //one thumb
                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    }




                    if (@$rdata['admission_pending_flag'] == 1) { //  admission_pending_flag
                        $fieldsetnew['admission_pending_remark']['text'] = 'is_required,is_alphanumericspace';
                        $errors = $this->validatedata($rdata, $fieldsetnew);
                        if ($this->ValidationError($errors)) {
                            $this->party_entry->updateAll(
                                    array('admission_pending_flag' => "'Y'", 'admission_pending_remark' => "'" . $rdata['admission_pending_remark'] . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->Session->setFlash(__("Please Check validations"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->party_entry->updateAll(
                                array('admission_pending_flag' => "'N'", 'admission_pending_remark' => "'" . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('party');
                }


                //  $this->request->data['party']['state_id'] = $this->Auth->User('state_id');
                //  $this->request->data['party']['user_id'] = $this->Auth->User('user_id');
// $this->request->data['party']['created_date'] = date('Y/m/d H:i:s');
                //  $this->request->data['party']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                if (isset($this->request->data['panparty'])) {
                    $data = $this->request->data['panparty'];
                    $r = $this->party_entry->query("update ngdrstab_trn_party_entry_new  set pan_verified=? where party_id=? and record_lock=? and token_no=? and pan_no=? ", array('Y', $data['party_id'], 'N', $tokenno, $data['pan_no']));
                    if ($r) {
                        $this->Session->setFlash(__("Record not updated. Record is locked."));
                    } else {
                        $this->Session->setFlash(__("lbleditmsg"));
                    }
                    $this->redirect('party');
                }



                if (isset($this->request->data['btnaccept'])) {

                    if ($btnpan) {
                        $pancheck = $this->party_entry->query("select id from ngdrstab_trn_party_entry_new where token_no=? and pan_verified='N' and pan_no <> NULL and ( presenty_require='Y' or  is_executer='Y' )", array($tokenno));
                        if (!empty($pancheck)) {
                            $this->Session->setFlash(__("Verify Party pan Details"));
                            $this->redirect('party');
                        }
                    }

// should validate before update
                    if ($this->party_entry->validate($party, $path)) {
                        $this->update_stamp_function_flags($this->request->params['action']);

                        $lockall['record_lock'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->party_entry->updateAll(
                                $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                        );
                        $this->save_documentstatus(3, $tokenno, $office_id);
                        $this->Session->setFlash(__("Party Details Completed Sucessfully"));
                    } else {
                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                    }
                    $this->redirect('party');
                }

                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];
                $fingervalue = $_POST['hffinger'];
                if ($fingervalue != Null) {
                    $fingerdesc = $this->fingerdescription->query("select * from ngdrstab_mst_five_finger where fingerdescription_id=?", array($fingervalue));

                    $fingername = $fingerdesc[0][0]['finger_description'];
                }
                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 79, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                $confphoto = $regconf ? $regconf[0]['regconfig']['info_value'] : 0;

                if ($_POST['actiontype'] == '1') {
                    $folder = "Biometric_Party";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";

//                    pr($this->request->data);
//                    pr($UPLOAD_DIR);exit;
// to check directory exist or not 
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
//                    pr($file);exit;
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
                        if ($fivefinger == 'Y') {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'IndexFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure2=? , biometric_img2=?, biometric_upload2=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'MiddleFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure3=? , biometric_img3=?, biometric_upload3=?  ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'RingFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure4=? , biometric_img4=?, biometric_upload4=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'BabyFinge') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        } else {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        }

                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect('party');
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('party');
                    }
                }

                //reset biometric
                if ($_POST['actiontype'] == '2') {
//                    pr($fivefinger);exit;

                    if ($fivefinger == 'Y') {


                        $loc = $this->party_entry->query("select biometric_img,biometric_img2,biometric_img3,biometric_img4,biometric_img5,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
//                  pr($loc);
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                                 biometric_fingure2=? , biometric_img2=?, biometric_upload2=?,"
                                    . "                                                                 biometric_fingure3=? , biometric_img3=?, biometric_upload3=?,"
                                    . "                                                                 biometric_fingure4=? , biometric_img4=?, biometric_upload4=?,"
                                    . "                                                                 biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,photo_upload=?, photo_img=?,org_updated=?,org_user_id=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
                                $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
//                            pr($loc1);exit;
                                $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
                                $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
                                $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
                                $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
                                if (is_file($loc3)) {
                                    unlink($loc3);
                                }
                                if (is_file($loc4)) {
                                    unlink($loc4);
                                }
                                if (is_file($loc5)) {
                                    unlink($loc5);
                                }
                                if (is_file($loc6)) {
                                    unlink($loc6);
                                }
                                if (is_file($loc2)) {
                                    unlink($loc2);
                                }
                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('party');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('party');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('party');
                        }
                    } else {
                        $loc = $this->party_entry->query("select biometric_img,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                              photo_upload=?, photo_img=? ,org_updated=?,org_user_id=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
//                            $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
////                            pr($loc1);exit;
//                            $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
//                            $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
//                            $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
                                $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
//                             if (is_file($loc3)) {
//                                unlink($loc3);
//                            }
//                             if (is_file($loc4)) {
//                                unlink($loc4);
//                            }
//                             if (is_file($loc5)) {
//                                unlink($loc5);
//                            }
//                             if (is_file($loc6)) {
//                                unlink($loc6);
//                            }
                                if (is_file($loc2)) {
                                    unlink($loc2);
                                }
                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('party');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('party');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('party');
                        }
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
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $updateflag = 0;
                        if ($confphoto == 0) {
                            $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '.jpg';
                            $success = file_put_contents($file, $data);
                            $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '.jpg';
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            $updateflag = 1;
                        } else if ($confphoto == 1) {
                            foreach ($party as $party1) {
                                if ($party1[0]['home_visit_flag'] == 'N' && $party1[0]['record_lock'] == 'N') {
                                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                                        $file = UPLOAD_DIR . $tokenno . '_partyid_' . $party1[0]['id'] . '.jpg';
                                        $success = file_put_contents($file, $data);
                                        $loc = $folder . "/" . $tokenno . '_partyid_' . $party1[0]['id'] . '.jpg';
                                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $party1[0]['id'], $tokenno, 'N'));
                                        $updateflag = 1;
                                    }
                                }
                            }
                        }
                        if ($check == NULL && $loc != NULL && $updateflag == 1) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                            $this->redirect('party');
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                            $this->redirect('party');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('party');
                    }
                }
            }


            if (is_numeric($lock_party_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->party_entry->validate($party, $path, $lock_party_id)) {
                    $uploadeddate = date('Y-m-d H:i:s');
                    $this->party_entry->updateAll(
                            array('record_lock' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                    $this->redirect('party');
                }
            }



            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            }
            $stampconfig = $this->stamp_and_functions_config();
            $this->set("stampconfig", $stampconfig);
            $this->set_csrf_token();
        } catch (Exception $exc) {
//            pr($exc);
//            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function admission($locktype = NULL, $lock_party_id = NULL, $csrftoken = NULL) {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            $this->loadModel('fingerdescription');
            $this->loadModel("party_entry");
            $this->loadModel("witness");
            $this->loadModel("identification");


            $this->loadModel("article");
            $this->loadModel("file_config");
            $this->loadModel('regconfig');
            $this->set('hfxml', NULL);
            $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hfimg', NULL);
            $this->set('hffinger', NULL);
            $this->set('cap', NULL);
            $this->set('txn', NULL);
            $this->set('pic', NULL);
            $action = "wadh";
            $verStr = "2.5";
            $raStr = "F";
            $rcStr = "Y";
            $lrStr = "N";
            $deStr = "N";
            $pfrStr = "N";
            $path1 = "//FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/FDx_SDK_PRO_LINUX3_X64_3_7_1_BETA1_REV675/java/Ekycjharkhand.jar";
            $wadh = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $path1 . ' ' . $action . ' ' . $verStr . ' ' . $raStr . ' ' . $rcStr . ' ' . $lrStr . ' ' . $deStr . ' ' . $pfrStr, $result1);
            $this->set('wadh', $wadh);

            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $userid = $this->Auth->User('user_id');
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $language = $this->Session->read('sess_langauge');
            $this->set('language', $language);
            $tokenno = $this->Session->read("reg_token");
            $office_id = $this->Auth->user('office_id');
            $citizen_user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read("doc_lang");
            $this->Session->write("sroparty", 'Y');
            $article_id = $this->Session->read("selectedarticle_id");

            $this->set("partylist", $party = $this->party_entry->get_partyrecord($tokenno, $citizen_user_id, $doc_lang, $language, 'N'));
            $this->set("identifiers", $identifier = $this->identification->get_identification_details($language, $tokenno, $citizen_user_id));

            foreach ($party as $party1) {
                if ($party1[0]['home_visit_flag'] == 'N') {
                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                        $fieldlistmultiform['otheroptions' . $party1[0]['party_id']]['admission_pending_remark_' . $party1[0]['party_id']]['text'] = 'is_required,is_alphanumericspace';
                    }
                }
            }
            if (!empty($fieldlistmultiform)) {
                $this->set("fieldlistmultiform", $fieldlistmultiform);
                $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));
            }

            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $this->set('path', $path);
            $check = $this->file_config->query("select server_biometric_flag from ngdrstab_mst_user where user_id=?", array($userid));
            $serverbioflag = $check[0][0]['server_biometric_flag'];
            $this->set('biometserverflag', $serverbioflag);

            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 72)));
            if (!empty($regconfig)) {
                $fivefinger = $regconfig['regconfig']['info_value'];
//                pr($fivefinger);exit;
                $this->set('fivefinger', $fivefinger);
            }
            if ($fivefinger == 'Y') {
                $this->set('fingerdescription', ClassRegistry::init('fingerdescription')->find('list', array('fields' => array('fingerdescription_id', 'finger_description'), 'order' => array('fingerdescription_id' => 'ASC'))));
            } else {
                $fingerdescription = $this->fingerdescription->find("list", array('conditions' => array('fingerdescription_id' => 1), 'fields' => array('fingerdescription_id', 'finger_description')));
                $this->set('fingerdescription', $fingerdescription);
            }
            $btnpan = 0;
            $regconfigpan = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 133, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            if (!empty($regconfigpan)) {
                $valamount = $this->party_entry->query("select coalesce(SUM(cons_amt),0) as consamount from ngdrstab_trn_fee_calculation as fee where  fee.token_no=? and fee.article_id=? and fee.delete_flag='N'", array($tokenno, $article_id));
                if ($valamount[0][0]['consamount'] > $regconfigpan['regconfig']['info_value']) {
                    $btnpan = 1;
                }
            }
            $this->set("btnpan", $btnpan);
            /* witness   start */
            $this->set("witness", $witness = $this->witness->get_witness($language, $tokenno));
            /* witness   start */
            if ($this->request->is('post')) {
                if (isset($this->request->data['admission']['csrftoken'])) {
                    $csrf = $this->request->data['admission']['csrftoken'];
                } elseif (isset($this->request->data['other_options']['csrftoken'])) {
                    $csrf = $this->request->data['other_options']['csrftoken'];
                } elseif (isset($this->request->data['panparty']['csrftoken'])) {
                    $csrf = $this->request->data['panparty']['csrftoken'];
                }
                $this->check_csrf_token($csrf);
                $uploadeddate = date('Y-m-d H:i:s');
                // pr($this->request->data);exit;
                if (isset($this->request->data['other_options']) && $this->request->data['other_options']['optionstype'] == 'PARTY') {
                    $rdata = $this->request->data['other_options'];

                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    } else {
                        $this->party_entry->updateAll(
                                array('camera_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    if ($fivefinger == 'Y') {

                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }



                        if (@$rdata['biodevice_working_flag2'] == 1) { //  biodevice_working_flag2
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag2' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag3'] == 1) { //  biodevice_working_flag3
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag3' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag4'] == 1) { //  biodevice_working_flag4
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag4' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }

                        if (@$rdata['biodevice_working_flag5'] == 1) { //  biodevice_working_flag5
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag5' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    } else {
                        //one thumb
                        if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'N'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->party_entry->updateAll(
                                    array('biodevice_working_flag' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        }
                    }




                    if (@$rdata['admission_pending_flag'] == 1) { //  admission_pending_flag
                        $fieldsetnew['admission_pending_remark']['text'] = 'is_required,is_alphanumericspace';
                        $errors = $this->validatedata($rdata, $fieldsetnew);
                        if ($this->ValidationError($errors)) {
                            $this->party_entry->updateAll(
                                    array('admission_pending_flag' => "'Y'", 'admission_pending_remark' => "'" . $rdata['admission_pending_remark'] . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                            );
                        } else {
                            $this->Session->setFlash(__("Please Check validations"));
                            $this->redirect('admission');
                        }
                    } else {
                        $this->party_entry->updateAll(
                                array('admission_pending_flag' => "'N'", 'admission_pending_remark' => "'" . "'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $rdata['optionsid'])
                        );
                    }

                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('admission');
                }
                if (isset($this->request->data['other_options']) && $this->request->data['other_options']['optionstype'] == 'WITNESS') {
                    $rdata = $this->request->data['other_options'];
                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    }

                    if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->witness->updateAll(
                                $lockall, array('token_no' => $tokenno, 'witness_id' => $rdata['optionsid'])
                        );
                    }


                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('admission');
                }
                if (isset($this->request->data['other_options']) && $this->request->data['other_options']['optionstype'] == 'IDENTIFIRE') {
                    $rdata = $this->request->data['other_options'];
                    if (@$rdata['camera_working_flag'] == 1) { // device_working_flag
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['camera_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);
                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    }

                    if (@$rdata['biodevice_working_flag'] == 1) { //  biodevice_working_flag
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'N'";
                        $lockall = $this->add_default_fields_updateAll($lockall);

                        $this->identification->updateAll(
                                $lockall, array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    } else {
                        $lockall = array();
                        $lockall['biodevice_working_flag'] = "'Y'";
                        $lockall = $this->add_default_fields_updateAll($lockall);

                        $this->identification->updateAll(
                                array('biodevice_working_flag' => "'Y'"), array('token_no' => $tokenno, 'identification_id' => $rdata['optionsid'])
                        );
                    }


                    $this->Session->setFlash(__("lblsavemsg"));
                    $this->redirect('admission');
                }

                if (isset($this->request->data['panparty'])) {
                    $data = $this->request->data['panparty'];
                    $r = $this->party_entry->query("update ngdrstab_trn_party_entry_new  set pan_verified=? where party_id=? and record_lock=? and token_no=? and pan_no=? ", array('Y', $data['party_id'], 'N', $tokenno, $data['pan_no']));
                    if ($r) {
                        $this->Session->setFlash(__("Record not updated. Record is locked."));
                    } else {
                        $this->Session->setFlash(__("lbleditmsg"));
                    }
                    $this->redirect('admission');
                }



                if (isset($this->request->data['btnaccept'])) {

                    if ($btnpan) {
                        $pancheck = $this->party_entry->query("select id from ngdrstab_trn_party_entry_new where token_no=? and pan_verified='N' and pan_no <> NULL and ( presenty_require='Y' or  is_executer='Y' )", array($tokenno));
                        if (!empty($pancheck)) {
                            $this->Session->setFlash(__("Verify Party pan Details"));
                            $this->redirect('admission');
                        }
                    }

// should validate before update
                    if ($this->party_entry->validate($party, $path)) {

                        if ($this->witness->validate($witness, $path)) {
                            if ($this->identification->validate($identifier, $path)) {

                                $this->update_stamp_function_flags($this->request->params['action']);

                                $lockall['record_lock'] = "'Y'";
                                $lockall = $this->add_default_fields_updateAll($lockall);
                                $this->party_entry->updateAll(
                                        $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                                );
                                $this->witness->updateAll(
                                        $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                                );

                                $this->identification->updateAll(
                                        $lockall, array('token_no' => $tokenno, 'record_lock' => "N")
                                );
                                $this->save_documentstatus(3, $tokenno, $office_id);
                                $this->Session->setFlash(__("Party Details Completed Sucessfully"));
                            } else {
                                $this->Session->setFlash(__("Please Check Photo and Biometric Captured for identification"));
                            }
                        } else {
                            $this->Session->setFlash(__("Please Check Photo and Biometric Captured for witness"));
                        }
                    } else {
                        $this->Session->setFlash(__("Please Check Photo and Biometric Captured for party"));
                    }
                    $this->redirect('admission');
                }

                $cap = $_POST['cap'];
                $id = $_POST['hfid'];
                $img = $_POST['hfimg'];
                $fingervalue = $_POST['hffinger'];
                if ($fingervalue != Null) {
                    $fingerdesc = $this->fingerdescription->query("select * from ngdrstab_mst_five_finger where fingerdescription_id=?", array($fingervalue));

                    $fingername = $fingerdesc[0][0]['finger_description'];
                }
                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 145, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                $confphoto = $regconf ? $regconf[0]['regconfig']['info_value'] : 'NA';

                // pr($this->request->data);exit;

                if ($_POST['actiontype'] == '1' && $_POST['hftype'] == 'PARTY') {
                    $folder = "Biometric_Party";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
                    // pr($this->request->data);exit;
//                    pr($UPLOAD_DIR);exit;
// to check directory exist or not 
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
//                    pr($file);exit;
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '_' . $fingername . '.png';
                        if ($fivefinger == 'Y') {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'IndexFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure2=? , biometric_img2=?, biometric_upload2=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'MiddleFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure3=? , biometric_img3=?, biometric_upload3=?  ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'RingFinger') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure4=? , biometric_img4=?, biometric_upload4=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            } else if ($fingername == 'BabyFinge') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        } else {
                            if ($fingername == 'Thumb') {
                                $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=? ,org_updated=?,org_user_id=? WHERE id= ? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            }
                        }

                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect('admission');
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect('admission');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('admission');
                    }
                }
                if ($_POST['actiontype'] == '1' && $_POST['hftype'] == 'WITNESS') {
                    $folder = "biometric_witness";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";
                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_witnessid_' . $id . '.png';
                    $check_record = $this->witness->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_witnessid_' . $id . '.png';
                        $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET biometric_fingure=? , biometric_img=? , biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array($cap, $loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                            $this->redirect('admission');
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                            $this->redirect('admission');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('admission');
                    }
                }
                if ($_POST['actiontype'] == '1' && $_POST['hftype'] == 'IDENTIFIRE') {
                    $folder = "Biometric_Identifier";
                    $UPLOAD_DIR = $path['file_config']['filepath'] . $folder . "/";

                    if (!file_exists($UPLOAD_DIR)) {
                        mkdir($UPLOAD_DIR, 0744, true);
                    }
                    define('UPLOAD_DIR', $UPLOAD_DIR);
                    $img = $_REQUEST['hfimg'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $tokenno . '_identifierid_' . $id . '.png';

                    $check_record = $this->identification->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_identifierid_' . $id . '.png';

                        $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET biometric_fingure=? , biometric_img=?, biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=? ", array($cap, $loc, $uploadeddate, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL && $loc != NULL) {
                            $this->Session->setFlash(__("Biometric Registration Successfully"));
                        } else {
                            $this->Session->setFlash(__("Biometric Registration Failed"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                    $this->redirect('admission');
                }



                // pr($_POST);exit;
                //reset biometric
                if ($_POST['actiontype'] == '2' && $_POST['hftype'] == 'PARTY') {
//                    pr($fivefinger);exit;

                    if ($fivefinger == 'Y') {


                        $loc = $this->party_entry->query("select biometric_img,biometric_img2,biometric_img3,biometric_img4,biometric_img5,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
//                  pr($loc);
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                                 biometric_fingure2=? , biometric_img2=?, biometric_upload2=?,"
                                    . "                                                                 biometric_fingure3=? , biometric_img3=?, biometric_upload3=?,"
                                    . "                                                                 biometric_fingure4=? , biometric_img4=?, biometric_upload4=?,"
                                    . "                                                                 biometric_fingure5=? , biometric_img5=?, biometric_upload5=? ,org_updated=?,org_user_id=? WHERE  id= ? and token_no=? and record_lock=?", array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
                                $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
//                            pr($loc1);exit;
                                $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
                                $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
                                $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
                                if (is_file($loc3)) {
                                    unlink($loc3);
                                }
                                if (is_file($loc4)) {
                                    unlink($loc4);
                                }
                                if (is_file($loc5)) {
                                    unlink($loc5);
                                }
                                if (is_file($loc6)) {
                                    unlink($loc6);
                                }

                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('admission');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('admission');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('admission');
                        }
                    } else {
                        $loc = $this->party_entry->query("select biometric_img,photo_img from ngdrstab_trn_party_entry_new WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                        if (!empty($loc)) {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET biometric_fingure=? , biometric_img=?, biometric_upload=?, "
                                    . "                                                              org_updated=?,org_user_id=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, $uploadeddate, $userid, $id, $tokenno, 'N'));



//                        pr($check);

                            if ($check == NULL) {
                                $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
//                            $loc3 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img2'];
////                            pr($loc1);exit;
//                            $loc4 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img3'];
//                            $loc5 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img4'];
//                            $loc6 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img5'];
//                                $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                                if (is_file($loc1)) {
                                    unlink($loc1);
                                }
//                             if (is_file($loc3)) {
//                                unlink($loc3);
//                            }
//                             if (is_file($loc4)) {
//                                unlink($loc4);
//                            }
//                             if (is_file($loc5)) {
//                                unlink($loc5);
//                            }
//                             if (is_file($loc6)) {
//                                unlink($loc6);
//                            }
//                                if (is_file($loc2)) {
//                                    unlink($loc2);
//                                }
                                $this->reset_stamp_function_flags($this->request->params['action']);
                                $this->Session->setFlash(__("Biometric Reset Successfully"));
                                $this->redirect('admission');
                            } else {
                                $this->Session->setFlash(__("Biometric Reset Failed"));
                                $this->redirect('admission');
                            }
                        } else {
                            $this->Session->setFlash(__("lblnotfoundmsg"));
                            $this->redirect('admission');
                        }
                    }
                }
                if ($_POST['actiontype'] == '2' && $_POST['hftype'] == 'WITNESS') {
                    $loc = $this->witness->query("select biometric_img,photo_img from ngdrstab_trn_witness WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                    if (!empty($loc)) {
                        $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET biometric_fingure=? , biometric_img=?, biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL) {
                            $loc1 = $path['file_config']['filepath'] . $loc[0][0]['biometric_img'];
//                            $loc2 = $path['file_config']['filepath'] . $loc[0][0]['photo_img'];
                            if (is_file($loc1)) {
                                unlink($loc1);
                            }
//                            if (is_file($loc2)) {
//                                unlink($loc2);
//                            }
                            $this->reset_stamp_function_flags($this->request->params['action']);
                            $this->Session->setFlash(__("Biometric Reset Successfully"));
                            $this->redirect('admission');
                        } else {
                            $this->Session->setFlash(__("Biometric Reset Failed"));
                            $this->redirect('admission');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('admission');
                    }
                }
                if ($_POST['actiontype'] == '2' && $_POST['hftype'] == 'IDENTIFIRE') {
                    $loc = $this->identification->query("select biometric_img,photo_img from ngdrstab_trn_identification WHERE id= ? and token_no=? and record_lock=?", array($id, $tokenno, 'N'));
                    if (!empty($loc)) {
                        $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET biometric_fingure=? , biometric_img=?, biometric_upload=?,org_user_id=?,org_updated=? WHERE id=? and token_no=? and record_lock=?", array(NULL, NULL, NULL, $userid, $uploadeddate, $id, $tokenno, 'N'));
                        if ($check == NULL) {
                            $loc1 = $loc[0][0]['biometric_img'];
//                            $loc2 = $loc[0][0]['photo_img'];
                            if (is_file($loc1)) {
                                unlink($loc1);
                            }
//                            if (is_file($loc2)) {
//                                unlink($loc2);
//                            }
                            $this->reset_stamp_function_flags($this->request->params['action']);
                            $this->Session->setFlash(__("Biometric Reset Successfully"));
                        } else {
                            $this->Session->setFlash(__("Biometric Reset Failed"));
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                    }
                    $this->redirect('admission');
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
                    $check_record = $this->party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $tokenno, 'record_lock' => 'N')));
                    if (!empty($check_record)) {
                        $updateflag = 0;
                        $file = UPLOAD_DIR . $tokenno . '_partyid_' . $id . '.jpg';
                        $success = file_put_contents($file, $data);
                        $loc = $folder . "/" . $tokenno . '_partyid_' . $id . '.jpg';
                        if ($confphoto == 'NA') {
                            $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $id, $tokenno, 'N'));
                            $updateflag = 1;
                        }

                        if (strpos($confphoto, 'P') !== false) {
                            foreach ($party as $party1) {
                                if ($party1[0]['home_visit_flag'] == 'N' && $party1[0]['record_lock'] == 'N') {
                                    if ($party1[0]['is_executer'] == 'Y' || $party1[0]['presenty_require'] == 'Y') {
                                        $check = $this->party_entry->query("UPDATE ngdrstab_trn_party_entry_new SET photo_img=?, photo_upload=? ,org_updated=?,org_user_id=? WHERE id=?  and token_no=? and  record_lock=? ", array($loc, $uploadeddate, $uploadeddate, $userid, $party1[0]['id'], $tokenno, 'N'));
                                        $updateflag = 1;
                                    }
                                }
                            }
                        }
                        if (strpos($confphoto, 'W') !== false) {
                            $check = $this->witness->query("UPDATE ngdrstab_trn_witness SET photo_img=? , photo_upload=?,org_user_id=?,org_updated=? WHERE    token_no=? and  record_lock=? ", array($loc, $uploadeddate, $userid, $uploadeddate, $tokenno, 'N'));
                            $updateflag = 1;
                        }
                        if (strpos($confphoto, 'I') !== false) {
                            $check = $this->identification->query("UPDATE ngdrstab_trn_identification SET photo_img=? , photo_upload=?,org_user_id=?,org_updated=? WHERE    token_no=? and  record_lock=? ", array($loc, $uploadeddate, $userid, $uploadeddate, $tokenno, 'N'));
                            $updateflag = 1;
                        }



                        if ($loc != NULL && $updateflag == 1) {
                            $this->Session->setFlash(__("Photo Uploaded Successfully"));
                            $this->redirect('admission');
                        } else {
                            $this->Session->setFlash(__("Photo Uploaded Failed"));
                            $this->redirect('admission');
                        }
                    } else {
                        $this->Session->setFlash(__("lblnotfoundmsg"));
                        $this->redirect('admission');
                    }
                }
            }


            if ($locktype == 'PARTY' && is_numeric($lock_party_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->party_entry->validate($party, $path, $lock_party_id)) {
                    $uploadeddate = date('Y-m-d H:i:s');
                    $this->party_entry->updateAll(
                            array('record_lock' => "'Y'", 'org_updated' => "'" . $uploadeddate . "'", 'org_user_id' => $userid), array('token_no' => $tokenno, 'party_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                    $this->redirect('admission');
                }
            }
            if ($locktype == 'IDENTIFIRE' && is_numeric($lock_party_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->identification->validate($identifier, $path, $lock_party_id)) {
                    $lockall = array();
                    $lockall['record_lock'] = "'Y'";
                    $lockall = $this->add_default_fields_updateAll($lockall);
                    $this->identification->updateAll(
                            $lockall, array('token_no' => $tokenno, 'record_lock' => "N", 'identification_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                } else {
                    $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                }
                $this->redirect('admission');
            }
            if ($locktype == 'WITNESS' && is_numeric($lock_party_id)) {
                $this->check_csrf_token($csrftoken);
                if ($this->witness->validate($witness, $path, $lock_party_id)) {
                    $lockall = array();
                    $lockall['record_lock'] = "'Y'";
                    $lockall = $this->add_default_fields_updateAll($lockall);
                    $this->witness->updateAll(
                            $lockall, array('token_no' => $tokenno, 'witness_id' => $lock_party_id)
                    );
                    $this->Session->setFlash(__("Record Locked"));
                } else {
                    $this->Session->setFlash(__("Please Check Photo and Biometric Captured"));
                }
                $this->redirect('admission');
            }


            if (isset($office_id) && is_numeric($office_id) && isset($tokenno) && is_numeric($tokenno)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($tokenno, $office_id)));
            }
            $stampconfig = $this->stamp_and_functions_config();
            $this->set("stampconfig", $stampconfig);
            $this->set_csrf_token();
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function payment_verification() {
        $this->check_role_escalation_tab();
        $this->check_function_hierarchy($this->request->params['action']);
        try {
            array_map(array($this, 'loadModel'), array('payment', 'payment_mode', 'bank_master', 'ApplicationSubmitted', 'PaymentFields', 'article_fee_items', 'CitizenPaymentEntry', 'ReceiptCounter', 'article_fee_items', 'fees_calculation', 'fees_calculation_detail', 'regconfig', 'OnlinePayment', 'SroAcceptance', 'SroDependentFields'));
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('frmflag', NULL);

            $user_id = $this->Auth->User("user_id");
// $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $office_user_id = $this->Auth->User("user_id");
            $article_id = $this->Session->read("selectedarticle_id");

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read("doc_lang");
            $token = $this->Session->read('reg_token');
            $office_id = $this->Session->read("office_id");

            $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
//pr($payment_mode_counter);
            $payment_mode_online = $this->payment_mode->get_payment_mode_online($lang);
            $feedetails = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_$lang,mode.verification_flag,mode.receipt_flag  FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=?  ", array($token));
            $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?", array($token, $office_id));
            $paymentfields = $this->PaymentFields->find('list', array('fields' => array('field_name', 'field_name_desc_' . $lang), 'order' => 'srno ASC'));
            $this->set("application", $documents[0][0]);
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => 2), 'fields' => array('account_head_code', 'fee_item_desc_' . $lang)));

            $citizen_payment_entry = $this->CitizenPaymentEntry->get_all_payment($token, $lang);
            $paymentfields_trn = $this->PaymentFields->find('all', array('conditions' => array('is_transaction_flag' => 'Y'), 'order' => 'srno ASC'));
            $stampconfig = $this->stamp_and_functions_config();
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 36, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $regconf_amount_tally = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 48, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $regconf_amount_infront_of_sro = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 84, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));

            $this->update_sro_acceptance($token);
            $SroAcceptance = $this->SroAcceptance->query("SELECT A.*,B.* FROM ngdrstab_mst_sro_acceptance AS A, ngdrstab_trn_sro_acceptance_details AS B WHERE  A.acceptance_id=B.acceptance_id AND B.token_no=?", array($token));
            $infront_of_sro = $this->SroDependentFields->find('first', array('conditions' => array('token_no' => $token, 'field_id' => 1)));
            if (!empty($infront_of_sro)) {
                $infront_of_sro = $infront_of_sro['SroDependentFields']['field_value'];
            } else {
                $infront_of_sro = 0;
            }
            $regconf_hide_paytab = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 95, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));

            $this->set(compact('paymentfields_trn', 'payment_mode_counter', 'payment_mode_online', 'payment', 'paymentfields', 'regconf', 'documents', 'accounthead', 'token', 'feedetails', 'citizen_payment_entry', 'paymentfields_trn', 'stampconfig', 'lang', 'regconf_amount_tally', 'SroAcceptance', 'infront_of_sro', 'regconf_amount_infront_of_sro', 'regconf_hide_paytab'));
            /* Validation Field set For Client Side Valuation */
            $fieldlist['paymentGetPaymentDetailsForm'] = $this->PaymentFields->fieldlist();

            $fieldlist['payment_head']['final_value']['text'] = 'is_required,is_numeric';
            $fieldlist['payment_head']['account_head_id']['select'] = 'is_select_req';
            $fieldlist['payment_verification']['paid_amount_infront_of_sro']['text'] = 'is_numeric';
            $this->set("fieldlistmultiform", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
            if ($this->request->is('post')) {
                $data = $this->request->data;
                if (isset($data['payment_head']['csrftoken'])) {
                    $csrftoken = $data['payment_head']['csrftoken'];
                } elseif (isset($data['payment_verification']['csrftoken'])) {
                    $csrftoken = $data['payment_verification']['csrftoken'];
                } elseif (isset($data['payment']['csrftoken'])) {
                    $csrftoken = $data['payment']['csrftoken'];
                } elseif (isset($data['refuse']['csrftoken'])) {
                    $csrftoken = $data['refuse']['csrftoken'];
                } else {
                    $csrftoken = NULL;
                }
                $this->check_csrf_token($csrftoken);
                /* new payment head entry in stamp duity */
                if (isset($this->request->data['payment_head'])) {
                    $fieldsetnew['final_value']['text'] = 'is_required,is_numeric';
                    $fieldsetnew['account_head_id']['select'] = 'is_select_req';
                    $requestdata = $this->request->data['payment_head'];
                    $errors = $this->validatedata($requestdata, $fieldsetnew);
                    if ($this->ValidationError($errors)) {
                        $insertdata['final_value'] = $requestdata['final_value'];
                        $insertdata['fee_calc_desc'] = $requestdata['final_value'];
                        $insertdata = $this->org_add_default_fields($insertdata, 'I');
                        $finditem = $this->article_fee_items->find("first", array('conditions' => array('account_head_code' => $requestdata['account_head_id'], 'fee_param_type_id' => 2)));
                        if (!empty($finditem)) {
                            $insertdata['fee_item_id'] = $finditem['article_fee_items']['fee_item_id'];
                            $fees_calculation = $this->fees_calculation->find("first", array('conditions' => array('token_no' => $token, 'delete_flag' => 'N')));
                            if (!empty($fees_calculation)) {
                                $insertdata['fee_calc_id'] = $fees_calculation['fees_calculation']['fee_calc_id'];
                                if ($this->fees_calculation_detail->save($insertdata)) {
                                    $this->Session->setFlash(__("lblsavemsg"));
                                } else {
                                    $this->Session->setFlash(__("lblnotsavemsg"));
                                }
                            } else {
                                $this->Session->setFlash(__("Fee Calculation Id Not Found"));
                            }
                        } else {
                            $this->Session->setFlash(__("Account Head Not Found"));
                        }
                        $this->redirect('payment_verification');
                    }
                }
                /* Payment Defacement And Stamp */ else if (isset($this->request->data['payment_verification'])) {
                    $data = $this->request->data['payment_verification'];
                    $usertype = $this->Session->read("session_usertype");
                    $allrecordstatus = array();
                    foreach ($payment as $single) {
                        if ($single[0]['verification_flag'] == 'Y' && $single[0]['defacement_flag'] == 'N') {
//                          Check this payment is deface with another Payment Id (Full Deface)
                            $check_deface = $this->payment->find('list', array('fields' => array('payment_id', 'defacement_flag'), 'conditions' => array('payment_id' => $single[0]['payment_id'], 'defacement_flag' => 'Y')));
                            if (empty($check_deface)) {
                                $extrafields['token_no'] = $token;

                                if ($usertype == 'C') {
                                    $extrafields['user_id'] = $user_id;
                                } else {
                                    $extrafields['org_user_id'] = $user_id;
                                    $extrafields['org_updated'] = "'" . date('Y-m-d H:i:s') . "'";
                                }
                                $extrafields['user_type'] = "'" . $this->Session->read("session_usertype") . "'";
                                $extrafields['req_ip'] = "'" . $this->request->clientIp() . "'";
                                $extrafields['remark'] = $token;
                                $extrafields['regdocno'] = @$documents[0][0]['doc_reg_no'];

                                $webserviceobj = new WebServiceController();
                                $webserviceobj->constructClasses();
                                switch ($single[0]['payment_mode_id']) {
                                    case 1:
                                        $deface_response = $webserviceobj->GrasDeface($single[0], $extrafields);
                                        break;
                                    case 5:
                                        $deface_response = $webserviceobj->ERegistrationLock($single[0], $extrafields);
                                        break;
                                    case 6:
                                        $deface_response = $webserviceobj->EstampLock($single[0], $extrafields);
                                        break;
                                    default: $deface_response['Error'] = 'Webservices Not Implemented For Payment Mode Id: ' . $single[0]['payment_mode_id'];
                                }

                                if (empty($deface_response['Error'])) {
                                    if (isset($deface_response['Condition'])) {
                                        $this->payment->create();
                                        $this->payment->updateAll($deface_response['PaymentData'], $deface_response['Condition']);
                                    }
                                } else {
                                    $allrecordstatus[$single[0]['payment_id']] = $deface_response['Error'];
                                }
                            }
                        }
                    }
                    if (!empty($allrecordstatus)) {
                        $message = "<ul>";
                        foreach ($allrecordstatus as $key => $error) {
                            $message .= "<li>[ Payment Id : " . $key . " ] " . $error . "</li>";
                        }
                        $message .= "</ul>";
                        $this->Session->setFlash($message);
                    } else {
                        // defacement Recheck for Confirmation
                        $dcheck = $this->payment->validate_online_payment($token);
                        if ($dcheck == 1) {
                            // should validate before update
                            if ($this->payment->validate_payment($feedetails, $payment, $regconf_amount_tally)) {
                                $check = $this->document_upload_check(); // Check Missing Documents For Upload
                                if ($check == 'N') {
                                    if (isset($data['paid_amount_infront_of_sro'])) {
                                        $dependentfields = array('field_id' => 1, 'token_no' => $token, 'field_value' => $data['paid_amount_infront_of_sro']);
                                        $dependentfields = $this->add_default_fields($dependentfields);
                                        $this->SroDependentFields->save($dependentfields);
                                    }
                                    $receipt_number = $this->generate_receipt_number($token);

                                    if (!empty($receipt_number) && $receipt_number != 0) {
                                        $this->update_stamp_function_flags($this->request->params['action']);
                                        $this->payment->updateAll(
                                                array('record_lock' => "'Y'", 'receipt_number' => "'" . $receipt_number . "'", 'org_updated' => "'" . date('Y-m-d H:i:s') . "'"), array('token_no' => $token)
                                        );
                                        $this->save_documentstatus(6, $token, $office_id);
                                        $this->Session->setFlash(__("payment Accepted"));

                                        $this->lock_document($token, $office_id);
                                    }
                                } else {
                                    $this->Session->setFlash(__("Please Upload Missing Document!"));
                                    $this->redirect('document_presentation');
                                }
                            } else {
                                $this->Session->setFlash(__("Insufficient payment"));
                            }
                        } else {
                            $this->Session->setFlash(__("Problem in Online Payment Service. Please Try Again!"));
                        }
                    }
                    $this->redirect('payment_verification');
                }
                /* New  Payment Entry ( Counter) or Verification Of Payment */ else if (isset($this->request->data['payment'])) {
                    $request_data = $this->request->data['payment'];
                    $fieldlist_new = $this->PaymentFields->fieldlist($request_data['payment_mode_id']);
                    $errors = $this->validatedata($request_data, $fieldlist_new);
                    /* Validation Errors  Using Validation Script */
                    if ($this->ValidationError($errors)) {
                        $request_data['token_no'] = $token;
                        // check date validations

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
                            /*  If Counter Payment ( Save Data into Table) */
                            if (isset($payment_mode_counter[$request_data['payment_mode_id']])) {
                                if (isset($request_data['payment_id'])) {
                                    $request_data = $this->org_add_default_fields($request_data, 'U');
                                    $check = $this->payment->find("all", array('conditions' => array('token_no' => $token, 'payment_id' => $request_data['payment_id'], 'payment_mode_id' => $request_data['payment_mode_id'], 'record_lock' => 'N')));
                                    if (!empty($check)) {
                                        if (!isset($request_data['account_head_code']) || empty($request_data['account_head_code'])) {
                                            $request_data['account_head_code'] = $check[0]['payment']['account_head_code'];
                                        }
                                        $this->payment->Save($request_data);
                                        $this->Session->setFlash(__("lbleditmsg"));
                                    } else {
                                        $this->Session->setFlash(__("Record Not Available For Update"));
                                    }
                                } else if (isset($request_data['account_head_code']) && empty($request_data['account_head_code'])) {
                                    $request_data = $this->org_add_default_fields($request_data, 'I');
                                    $feedetails = $this->payment->stampduty_fee_details($token, $lang, $article_id, $request_data['payment_mode_id']);
                                    if (!empty($feedetails)) {
                                        $paymentarr = $this->prepair_fee_preference($feedetails, $payment, $request_data);
                                        if (!empty($paymentarr)) {
                                            $this->payment->saveAll($paymentarr);
                                            $this->Session->setFlash(__("lblsavemsg"));
                                        } else {
                                            $this->Session->setFlash(__("Payment Already Paid!"));
                                        }
                                    } else {
                                        $this->Session->setFlash(__("Payment Mode mapping not found. Please Select Account Heading!"));
                                    }
                                } else {
                                    $request_data = $this->org_add_default_fields($request_data, 'I');
                                    if ($this->payment->Save($request_data)) {
                                        $this->Session->setFlash(__("lblsavemsg"));
                                    } else {
                                        $this->Session->setFlash(__('lblnotsavemsg'));
                                    }
                                }
                                $this->redirect('payment_verification');
                            } else {
                                /*  If Online Payment (Verify And Save Data into Table) */

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
                                $extrafields['article_id'] = $this->Session->read("selectedarticle_id");
                                $extrafields['lang'] = $lang;

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
                                } else if ($request_data['payment_mode_id'] == 11) {  // 11. E - Challan
                                    $ServiceResponse = $webserviceobj->EchallanVerification($request_data, $extrafields);
                                } else {
                                    $ServiceResponse['Error'] = 'Payment Mode Not FOund!';
                                }

                                if (empty($ServiceResponse['Error'])) {
                                    if (!empty($ServiceResponse['PaymentData'])) {
                                        $this->payment->SaveAll($ServiceResponse['PaymentData']);
                                        if (!empty($ServiceResponse['OnlinePaymentData'])) {
                                            $this->OnlinePayment->Save($ServiceResponse['OnlinePaymentData']);
                                        }
                                        $this->Session->setFlash(__("Payment  Verified  Successfully!"));
                                    } else {
                                        $this->Session->setFlash(__("Record Not Saved. Please check Payment Already Done for perticular account head"));
                                    }
                                } else {
                                    $this->Session->setFlash(__("Error:" . $ServiceResponse['Error']));
                                }
                                $this->redirect('payment_verification');
                            }
                        } else {
                            $this->Session->setFlash(__("Please Select Correct date"));
                            $this->set('RequestData', $this->request->data['payment']);
                        }
                    } else {
                        $this->set('RequestData', $this->request->data['payment']);
                    }
                } else if (isset($this->request->data['refuse'])) {
                    $errors = $this->validatedata($this->request->data['refuse'], $fieldlist['refuse']);
                    if ($this->ValidationError($errors)) {
                        $regconf_bkno = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 164, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                        if (!empty($regconf_bkno) && !is_null($regconf_bkno['regconfig']['info_value'])) {
                            $extraparam['book_number'] = $regconf_bkno['regconfig']['info_value'];
                            $regconf_docno = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 165, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                            if (!empty($regconf_docno)) {

                                if ($regconf_docno['regconfig']['info_value'] == 'FIRST') {
                                    $docnumbercheck = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'doc_reg_no' => NULL)));
                                    if (!empty($docnumbercheck)) {
                                        $docnumber = $this->generate_document_number($extraparam);
                                        if (strcmp($docnumber, '0') == 0) {
                                            $this->Session->setFlash(__('Not able to generate document number'));
                                            return $this->redirect('payment_verification');
                                        }
                                        $this->ApplicationSubmitted->updateAll(array('doc_reg_no' => "'" . $docnumber . "'", 'doc_reg_date' => "'" . date('Y-m-d H:i:s') . "'"), array('token_no' => $token));
                                        $this->ApplicationSubmitted->query("update ngdrstab_trn_application_submitted set doc_refuse_flag=?,doc_refuse_date=?,doc_refuse_remark=? where token_no=?", array('Y', date('Y-m-d H:i:s'), $this->request->data['refuse']['doc_refuse_remark'], $token));
                                        $this->save_documentstatus(8, $token, $office_id);
                                        $this->Session->setFlash(__('Document Refused Successfully'));
                                        $this->redirect('payment_verification');
                                    }
                                } else if ($regconf_docno['regconfig']['info_value'] == 'FINAL') {
                                    $docnumbercheck = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token, 'final_doc_reg_no' => NULL)));
                                    if (!empty($docnumbercheck)) {
                                        $regconf_docnofinal = $this->regconfig->find("first", array('conditions' => array('reginfo_id' => 139, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                                        if (!empty($regconf_docnofinal)) {
                                            if ($regconf_docnofinal['regconfig']['info_value'] == 'AUTO') {
                                                $docnumberfinal = $this->generate_document_number_final_auto($extraparam);
                                            } else {
                                                $docnumberfinal = $this->generate_document_number_final($extraparam);
                                            }
                                            if (strcmp($docnumberfinal, '0') == 0) {
                                               // $this->Session->setFlash(__("Not able to generate final document number"));
                                                return $this->redirect('payment_verification');
                                            }
                                            $this->ApplicationSubmitted->updateAll(array('final_doc_reg_no' => "'" . $docnumberfinal . "'"), array('token_no' => $token));

                                            $this->ApplicationSubmitted->query("update ngdrstab_trn_application_submitted set doc_refuse_flag=?,doc_refuse_date=?,doc_refuse_remark=? where token_no=?", array('Y', date('Y-m-d H:i:s'), $this->request->data['refuse']['doc_refuse_remark'], $token));
                                            $this->save_documentstatus(8, $token, $office_id);
                                            $this->Session->setFlash(__('Document Refused Successfully'));
                                            $this->redirect('payment_verification');
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->Session->setFlash(__('Book Number Not  Found In Configuration For Refused Document'));
                            $this->redirect('payment_verification');
                        }
                    } else {
                        $this->Session->setFlash(__('Please Check validations'));
                    }
                }
            }

            $this->set_csrf_token();
        } catch (Exception $ex) {
           // pr($ex);
           // exit; 
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
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

    function prepair_fee_preference($feedetails, $payment, $request_data) {
        $sdamount = 0;
        $paidamount = 0;
        $paymentarr = array();
        $entryamount = $request_data['pamount'];
        // pr($feedetails);
        if (!empty($feedetails)) {
            $lastarr = end($feedetails);
            $last_head_code = $lastarr[0]['account_head_code'];
            // pr($last_head_code);
        }

        foreach ($feedetails as $fee):
            $amount = 0;
            foreach ($payment as $paydetails):
                $paydetails = $paydetails[0];
                if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                    $amount += $paydetails['pamount'];
                    $paidamount += $paydetails['pamount'];
                }
            endforeach;
            if ($entryamount > 0 && $entryamount > $fee[0]['totalsd'] - $amount && $fee[0]['totalsd'] - $amount > 0) {
                if ($fee[0]['account_head_code'] == $last_head_code) {
                    $request_data['pamount'] = $entryamount;
                    $request_data['account_head_code'] = $fee[0]['account_head_code'];
                    $entryamount = 0;
                } else {
                    $request_data['pamount'] = $fee[0]['totalsd'] - $amount;
                    $request_data['account_head_code'] = $fee[0]['account_head_code'];
                    $entryamount = $entryamount - ($fee[0]['totalsd'] - $amount);
                }

                array_push($paymentarr, $request_data);
            } else if ($entryamount > 0 && $fee[0]['totalsd'] - $amount > 0) {
                $request_data['pamount'] = $entryamount;
                $request_data['account_head_code'] = $fee[0]['account_head_code'];
                array_push($paymentarr, $request_data);
                $entryamount = 0;
            }
        endforeach;

        if ($entryamount > 0 && empty($paymentarr)) {
            $request_data['pamount'] = $entryamount;
            $request_data['account_head_code'] = $fee[0]['account_head_code'];
            array_push($paymentarr, $request_data);
        } elseif ($entryamount > 0 && !empty($paymentarr)) {
            $request_data['pamount'] = $entryamount;
            $request_data['account_head_code'] = $last_head_code;
            array_push($paymentarr, $request_data);
            $entryamount = 0;
        }
        //pr($paymentarr);
        //exit;
        return $paymentarr;
    }

    function get_payment_details() {
        try {
            array_map(array($this, 'loadModel'), array('payment', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items', 'PaymentModeMapping', 'payment_mode'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read("doc_lang");
            $article_id = $this->Session->read("selectedarticle_id");
            $modemapping = array();
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
                $payment_mode = $this->payment_mode->find("first", array('conditions' => array('payment_mode_id' => $data['mode'])));
                $this->set("payment_mode", $payment_mode['payment_mode']);
                $accounthead = $this->payment->fee_headings($token, $lang, $article_id, $data['mode']);
                $accarr = array();
                foreach ($accounthead as $acckey => $value) {
                    array_push($accarr, "" . $acckey . "");
                }
                $modemapping = $this->PaymentModeMapping->find("all", array(
                    'fields' => array('COALESCE(SUM(pay.pamount),0) as paidfee', 'items.account_head_code', 'PaymentModeMapping.payment_mode_id', 'items.fee_item_id', 'max_amount'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'items', 'conditions' => array('items.fee_item_id=PaymentModeMapping.fee_item_id')),
                        array('table' => 'ngdrstab_trn_payment_details', 'alias' => 'pay', 'type' => 'LEFT', 'conditions' => array('pay.token_no=' . $token, 'pay.payment_mode_id' => $data['mode'])),
                    ),
                    'conditions' => array('PaymentModeMapping.payment_mode_id' => $data['mode'], 'items.account_head_code' => $accarr),
                    'group' => array('items.account_head_code', 'PaymentModeMapping.payment_mode_id', 'items.fee_item_id', 'max_amount')
                        )
                );
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->payment->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'], 'token_no' => $token)));
                $this->set("payment", $payment);
                // pr($payment);
            }
            if (isset($paymentfields)) {
                foreach ($paymentfields as $field) {
                    if ($field['PaymentFields']['field_name'] == 'bank_id') {
                        $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                        $this->set("bank_master", $bank_master);
                        if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                            $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                        } else {
                            $branch_master = array();
                        }
                        $this->set("branch_master", $branch_master);
                    }
                    if ($field['PaymentFields']['field_name'] == 'cos_id') {
                        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('hierarchy_id' => 45)));
                        $this->set("office", $office);
                    }
                }
            }

            $feedetails = $this->payment->stampduty_fee_details($token, $lang, $article_id);

            $this->set(compact('accounthead', 'doc_lang', 'lang', 'feedetails', 'modemapping'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_payment_field_values($field_id = NULL) {
        try {

            $this->loadModel('PaymentFieldValues');
            $lang = $this->Session->read("sess_langauge");
            if (is_null($lang)) {
                $lang = 'en';
            }
            $result = $this->PaymentFieldValues->find("list", array(
                'fields' => array('field_value', 'field_values_desc_' . $lang),
                'conditions' => array('field_id' => $field_id)
            ));
            return $result;
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
        }
    }

    function get_payment_details_certified_copy() {
        try {
            array_map(array($this, 'loadModel'), array('payment', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read("doc_lang");

            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->payment->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'])));
                $this->set("payment", $payment);
                // pr($payment);
            }
            if (isset($paymentfields)) {
                foreach ($paymentfields as $field) {
                    if ($field['PaymentFields']['field_name'] == 'bank_id') {
                        $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                        $this->set("bank_master", $bank_master);
                        if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                            $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                        } else {
                            $branch_master = array();
                        }
                        $this->set("branch_master", $branch_master);
                    }
                    if ($field['PaymentFields']['field_name'] == 'cos_id') {
                        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('hierarchy_id' => 45)));
                        $this->set("office", $office);
                    }
                }
            }
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => array(2)), 'fields' => array('account_head_code', 'fee_item_desc_' . $lang)));
            $this->set(compact('accounthead', 'doc_lang', 'lang'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function save_srodetails($tokenno, $id) {
        $this->loadModel("identification");
        $this->loadModel("User");
        $sro = $this->User->query('select a.*,b.* from ngdrstab_mst_employee a,ngdrstab_mst_user b where a.emp_code=b.employee_id and b.user_id=?', array($this->Auth->User('user_id')));

        if (!empty($sro)) {
            $data = array('salutation' => $sro[0][0]['salutation'],
                'fname_en' => $sro[0][0]['emp_fname'],
                'mname_en' => $sro[0][0]['emp_mname'],
                'lname_en' => $sro[0][0]['emp_lname'],
                'mobile_no' => $sro[0][0]['mobile_no'],
                'email_id' => $sro[0][0]['email_id'],
                'pincode' => $sro[0][0]['pincode'],
                'uid_no' => $sro[0][0]['uid_no'],
                'district_id' => $sro[0][0]['dist_id'],
                'taluka_id' => $sro[0][0]['taluka_id'],
                'party_full_name_en' => $sro[0][0]['full_name']);
            $this->identification->id = $id;
            $this->identification->save($data);
            return true;
        }
    }

    function payment_verification_status() {
        $this->loadModel('OnlinePayment');
        $this->loadModel('payment');
        $lang = $this->Session->read("sess_langauge");
        $user_id = $this->Session->read("citizen_user_id");
        $token = $this->Session->read('Selectedtoken');
        $data = $this->params->params['pass'];
        $sflag = 1;
        $trasaction = array();
        if (isset($data['payment_mode_id']) && is_numeric($data['payment_mode_id'])) {
            $options = array();
            if ($data['payment_mode_id'] == 1) {
                $options['grn_no'] = $data['grn_no'];
                $options['defacement_flag'] = 'Y';
                $trasaction['grn_no'] = $data['grn_no'];
            } elseif ($data['payment_mode_id'] == 2) {
                $options['bank_trn_id'] = $data['bank_trn_id'];
                $trasaction['bank_trn_id'] = $data['bank_trn_id'];
            } elseif ($data['payment_mode_id'] == 3) {
                $sflag = 0;
            } elseif ($data['payment_mode_id'] == 4) {
                $options['franking_no'] = $data['franking_no'];
                $trasaction['franking_no'] = $data['franking_no'];
            } elseif ($data['payment_mode_id'] == 5) {
                $options['cos_no'] = $data['cos_no'];
                $options['certificate_no'] = $data['certificate_no'];
                $trasaction['cos_no'] = $data['cos_no'];
                $trasaction['certificate_no'] = $data['certificate_no'];
            } elseif ($data['payment_mode_id'] == 6) {
                $options['estamp_acc_no'] = $data['estamp_acc_no'];
                $options['estamp_issue_date'] = $data['estamp_issue_date'];
                $trasaction['estamp_acc_no'] = $data['estamp_acc_no'];
                $trasaction['estamp_issue_date'] = $data['estamp_issue_date'];
            } elseif ($data['payment_mode_id'] == 7) {
                $options['esbtr_no'] = $data['esbtr_no'];
                $options['cin_no'] = $data['cin_no'];
                $trasaction['esbtr_no'] = $data['esbtr_no'];
                $trasaction['cin_no'] = $data['cin_no'];
            } elseif ($data['payment_mode_id'] == 8) {
                $sflag = 0;
            }


            $options['payment_mode_id'] = $data['payment_mode_id'];
            $options['pamount'] = $data['pamount'];
            if ($sflag == 1) {
                $payment = $this->payment->find('all', array('conditions' => $options));
                if (count($payment) == 0) {
                    $result[0] = $trasaction;
                    return $result;
                } else if (count($payment) == 1) {
                    $result[1] = $trasaction;
                    return $result;
                } else {
                    $result[2] = $trasaction;
                    return $result;
                }
            } else {
                $result[99] = $trasaction;
                return $result;
            }
        }
    }

    public function execute_url($url, $data) {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //appointment
    public function appointment() {
        $this->loadModel('appointment');
        $today = strtotime(date("Y-m-d"));
        $from = date('Y-m-d', $today);
        $to = date('Y-m-d', $today);
        $days_10 = date('Y-m-d', strtotime("+10 days"));
        $office_id = $this->Session->read("office_id");
        $appointment = $this->appointment->get_SRO_appointment($office_id, $from, $to);
        $appointment_10days = $this->appointment->get_SRO_appointment($office_id, $from, $days_10);
        $this->set('app_10days', $appointment_10days);
        $this->set('appointment', $appointment);
    }

    public function get_available_appointment() {
        $this->loadModel('appointment');
        $office_id = $this->Session->read("office_id");

        if (isset($_POST['from']) && isset($_POST['to'])) {

            if ($_POST['from'] != NULL && $_POST['to'] != NULL) {
                $from = date('Y-m-d', strtotime($_POST['from']));
                $to = date('Y-m-d', strtotime($_POST['to']));
                $appointment = $this->appointment->get_SRO_appointment($office_id, $from, $to);

                $this->set('appointment', $appointment);
            } else {
                echo 'e';
                exit;
            }
        } else {
            echo 'e';
            exit;
        }
    }

//    public function generate_document_number() {
//        $this->loadModel('DocumentNumber');
//        $this->loadModel('finyear');
//        $this->loadModel('genernalinfoentry');
//        $this->loadModel('SerialNumbers');
//        $doc_token_no = $this->Session->read("reg_token");
//
//        $config = $this->DocumentNumber->find("all", array('conditions' => array('format_field_flag' => 'Y'), 'order' => 'display_order ASC'));
//        $finyear = $this->finyear->find("first", array('fields' => array('finyear_desc_short'), 'conditions' => array('current_year' => 'Y')));
//        if (!empty($finyear)) {
//            $currfinyear = $finyear['finyear']['finyear_desc_short'];
//        } else {
//            $currfinyear = '';
//        }
//        $articleinfo = $this->genernalinfoentry->find('all', array(
//            'fields' => array('article.article_id', 'article.book_number'),
//            'joins' => array(
//                array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=genernalinfoentry.article_id')), //1                
//            ),
//            'conditions' => array('genernalinfoentry.token_no' => $doc_token_no)));
//        if (!empty($articleinfo)) {
//            $artical_id = $articleinfo[0]['article']['article_id'];
//            $book_no = $articleinfo[0]['article']['book_number'];
//        } else {
//            $artical_id = '';
//            $book_no = '';
//        }
//
//        $SerialNumbers = $this->SerialNumbers->find("all", array('conditions' => array('token_no' => $doc_token_no)));
//        if (empty($SerialNumbers)) {
//            if ($book_no == 1) {
//                $option = array('book1_serial_number IS NOT NULL');
//            } else if ($book_no == 2) {
//                $option = array('book2_serial_number IS NOT NULL');
//            } else if ($book_no == 3) {
//                $option = array('book3_serial_number IS NOT NULL');
//            } else if ($book_no == 4) {
//                $option = array('book4_serial_number IS NOT NULL');
//            }
//            $SerialNumbers = $this->SerialNumbers->find("first", array('conditions' => $option, 'order' => array('counter_id' => 'DESC')));
//            if (empty($SerialNumbers)) {
//                // first time insert ( empty table)
//                if ($book_no == 1) {
//                    $data['book1_serial_number'] = 1;
//                } else if ($book_no == 2) {
//                    $data['book2_serial_number'] = 1;
//                } else if ($book_no == 3) {
//                    $data['book3_serial_number'] = 1;
//                } else if ($book_no == 4) {
//                    $data['book4_serial_number'] = 1;
//                }
//            } else {
//                $SerialNumbers = $SerialNumbers['SerialNumbers'];
//                // insert token
//                if ($book_no == 1) {
//                    $data['book1_serial_number'] = $SerialNumbers['book1_serial_number'] + 1;
//                } else if ($book_no == 2) {
//                    $data['book2_serial_number'] = $SerialNumbers['book2_serial_number'] + 1;
//                } else if ($book_no == 3) {
//                    $data['book3_serial_number'] = $SerialNumbers['book3_serial_number'] + 1;
//                } else if ($book_no == 4) {
//                    $data['book4_serial_number'] = $SerialNumbers['book4_serial_number'] + 1;
//                }
//            }
//            $data = $this->add_default_fields($data);
//            $data['token_no'] = $doc_token_no;
//            $this->SerialNumbers->Save($data);
//        } else {
//            // record exist . update field
//            // pr($SerialNumbers);exit;
//            $SerialNumbers = $SerialNumbers[0]['SerialNumbers'];
//            //   $data['counter_id']=$SerialNumbers['counter_id'];
//            $data = array();
//            if ($book_no == 1 && !is_null($SerialNumbers['book1_serial_number'])) {
//                $data['book1_serial_number'] = $SerialNumbers['book1_serial_number'];
//            } else if ($book_no == 2 && !is_null($SerialNumbers['book2_serial_number'])) {
//                $data['book2_serial_number'] = $SerialNumbers['book2_serial_number'];
//            } else if ($book_no == 3 && !is_null($SerialNumbers['book3_serial_number'])) {
//                $data['book3_serial_number'] = $SerialNumbers['book3_serial_number'];
//            } else if ($book_no == 4 && !is_null($SerialNumbers['book4_serial_number'])) {
//                $data['book4_serial_number'] = $SerialNumbers['book4_serial_number'];
//            }
//            if (empty($data)) {
//                if ($book_no == 1) {
//                    $option = array('book1_serial_number IS NOT NULL');
//                } else if ($book_no == 2) {
//                    $option = array('book2_serial_number IS NOT NULL');
//                } else if ($book_no == 3) {
//                    $option = array('book3_serial_number IS NOT NULL');
//                } else if ($book_no == 4) {
//                    $option = array('book4_serial_number IS NOT NULL');
//                }
//                $SerialNumbers = $this->SerialNumbers->find("first", array('conditions' => $option, 'order' => array('counter_id' => 'DESC')));
//                if (empty($SerialNumbers)) {
//                    // first time insert ( empty table)
//                    if ($book_no == 1) {
//                        $data1['book1_serial_number'] = 1;
//                    } else if ($book_no == 2) {
//                        $data1['book2_serial_number'] = 1;
//                    } else if ($book_no == 3) {
//                        $data1['book3_serial_number'] = 1;
//                    } else if ($book_no == 4) {
//                        $data1['book4_serial_number'] = 1;
//                    }
//                } else {
//                    $SerialNumbers = $SerialNumbers['SerialNumbers'];
//                    // insert token
//                    $data1['counter_id'] = $SerialNumbers['counter_id'];
//                    if ($book_no == 1) {
//                        $data1['book1_serial_number'] = $SerialNumbers['book1_serial_number'] + 1;
//                    } else if ($book_no == 2) {
//                        $data1['book2_serial_number'] = $SerialNumbers['book2_serial_number'] + 1;
//                    } else if ($book_no == 3) {
//                        $data1['book3_serial_number'] = $SerialNumbers['book3_serial_number'] + 1;
//                    } else if ($book_no == 4) {
//                        $data1['book4_serial_number'] = $SerialNumbers['book4_serial_number'] + 1;
//                    }
//                }
//                $data1 = $this->add_default_fields($data1);
//                $data1['token_no'] = $doc_token_no;
//                $this->SerialNumbers->create();
//                $this->SerialNumbers->Save($data1);
//            }
//        }
//
//        //final fetch
//        $book_sr_no = '';
//        $SerialNumbers = $this->SerialNumbers->find("all", array('conditions' => array('token_no' => $doc_token_no), 'order' => array('counter_id' => 'DESC')));
//
//        if (!empty($SerialNumbers)) {
//            $SerialNumbers = $SerialNumbers[0]['SerialNumbers'];
//            if ($book_no == 1) {
//                $book_sr_no = $SerialNumbers['book1_serial_number'];
//            } else if ($book_no == 2) {
//                $book_sr_no = $SerialNumbers['book2_serial_number'];
//            } else if ($book_no == 3) {
//                $book_sr_no = $SerialNumbers['book3_serial_number'];
//            } else if ($book_no == 4) {
//                $book_sr_no = $SerialNumbers['book4_serial_number'];
//            }
//        }
//
//
//        $number = '';
//        $separater = '';
//
//        foreach ($config as $key => $field) {
//            switch ($field['DocumentNumber']['format_field']) {
//                case 'SP': $separater = $field['DocumentNumber']['static_value'];
//                    break;
//                case 'Y': $number_arr[$key] = date('Y');
//                    break;
//                case 'FY': $number_arr[$key] = $currfinyear;
//                    break;
//                case 'CN':$number_arr[$key] = date('Ymd');
//                    break;
//                case 'SN': $number_arr[$key] = $this->Session->read("reg_record_no");
//                    break;
//                case 'OI': $number_arr[$key] = $this->Session->read("office_id");
//                    break;
//                case 'BN': $number_arr[$key] = $book_no;
//                    break;
//                case 'BSN':$number_arr[$key] = $book_sr_no;
//                    break;
//            }
//        }
//
//        $number = implode($separater, $number_arr);
//
//        return $number;
//    }
//   

    public function generate_document_number($extraparam = NULL) {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('DocumentNumber');
            $this->loadModel('finyear');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('SerialNumbers');
            $this->loadModel('office');

            $doc_token_no = $this->Session->read("reg_token");
            $configall = $this->DocumentNumber->find("all", array('fields' => array('format_field', 'format_field_flag', 'repeat_flag', 'osn_repeat_flag', 'bsn_repeat_flag', 'padding_flag', 'padding_length', 'padding_char'), 'order' => 'h_order ASC'));
            $finyear = $this->finyear->find("first", array('fields' => array('finyear_id', 'finyear_desc_short'), 'conditions' => array('current_year' => 'Y')));
            //pr($finyear);exit;
            if (!empty($finyear)) {
                $currfinyear = $finyear['finyear']['finyear_desc_short'];
                $officers = $this->office->find('first', array('fields' => array('article.article_id', 'article.book_number', 'article.titlewise_book_number', 'title.book_number', 'article.article_code', 'state.state_id', 'state.state_code', 'office.state_id', 'office.office_id', 'office.office_code', 'district.district_id', 'district.district_code', 'taluka.taluka_id', 'taluka.taluka_code'),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=office.taluka_id')),
                        array('table' => 'ngdrstab_conf_admblock1_state', 'alias' => 'state', 'conditions' => array('state.state_id=office.state_id')),
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'conditions' => array('info.token_no' => $doc_token_no)), //1
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=info.article_id')), //1
                        array('table' => 'ngdrstab_mst_articledescriptiondetail', 'alias' => 'title', 'type' => 'LEFT', 'conditions' => array('title.article_id=article.article_id')), //1
                    ),
                    'conditions' => array('office.office_id' => $this->Auth->user('office_id'))
                ));

                $format_values = array();
                $insert_values = array();
                $OS_options = array();
                $BS_options = array();

                if (!empty($officers)) {
                    if ($officers['article']['titlewise_book_number'] == 'Y') {
                        $officers['article']['book_number'] = $officers['title']['book_number'];
                    }
                    if (isset($extraparam['book_number'])) {
                        $officers['article']['book_number'] = $extraparam['book_number'];
                    }
                    $generateflag = 1;
                    foreach ($configall as $config) {
                        $config = $config['DocumentNumber'];

                        if ($config['format_field'] == 'SI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_id'] = $officers['state']['state_id'];
                        }
                        if ($config['format_field'] == 'SC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_code'] = $officers['state']['state_code'];
                        }
                        if ($config['format_field'] == 'DI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_id'] = $officers['district']['district_id'];
                        }
                        if ($config['format_field'] == 'DC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_code'] = $officers['district']['district_code'];
                        }
                        if ($config['format_field'] == 'TI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_id'] = $officers['taluka']['taluka_id'];
                        }
                        if ($config['format_field'] == 'TC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_code'] = $officers['taluka']['taluka_code'];
                        }
                        if ($config['format_field'] == 'AI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_id'] = $officers['article']['article_id'];
                        }
                        if ($config['format_field'] == 'AC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_code'] = $officers['article']['article_code'];
                        }

                        if ($config['format_field'] == 'Y' && $config['format_field_flag'] == 'Y') {
                            $insert_values['year'] = date('Y');
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['year'] = date('Y');
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['year'] = date('Y');
                            }
                        }
                        if ($config['format_field'] == 'FY' && $config['format_field_flag'] == 'Y') {
                            $insert_values['finyear_id'] = $finyear['finyear']['finyear_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                        }

                        if ($config['format_field'] == 'OI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_id'] = $officers['office']['office_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_id'] = $officers['office']['office_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_id'] = $officers['office']['office_id'];
                            }
                        }
                        if ($config['format_field'] == 'OC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_code'] = $officers['office']['office_code'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_code'] = $officers['office']['office_code'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_code'] = $officers['office']['office_code'];
                            }
                        }


                        if ($config['format_field'] == 'OSN' && $config['format_field_flag'] == 'Y') {
                            $office_serial = $this->SerialNumbers->find("first", array('fields' => array('office_serial_number'), 'conditions' => $OS_options, 'order' => 'counter_id DESC'));
                            $off_sn = $office_serial ? $office_serial['SerialNumbers']['office_serial_number'] + 1 : 1;
                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $off_sn = str_pad($off_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }
                            $format_values['OSN'] = $off_sn;
                            $insert_values['office_serial_number'] = $off_sn;
                            // pr($insert_values);
                            //  exit;
                        }
                        if ($config['format_field'] == 'BN' && $config['format_field_flag'] == 'Y') {
                            $insert_values['book_number'] = $officers['article']['book_number'];

                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['book_number'] = $officers['article']['book_number'];
                            }
                        }

                        if ($config['format_field'] == 'BSN' && $config['format_field_flag'] == 'Y') {
                            $book_serial = $this->SerialNumbers->find("first", array('fields' => array('book_serial_number'), 'conditions' => $BS_options, 'order' => 'counter_id DESC'));
                            $book_sn = $book_serial ? $book_serial['SerialNumbers']['book_serial_number'] + 1 : 1;
                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $book_sn = str_pad($book_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }
                            $format_values['BSN'] = $book_sn;
                            $insert_values['book_serial_number'] = $book_sn;
                        }
                    }




                    $check = $this->SerialNumbers->find("first", array('conditions' => array('token_no' => $doc_token_no), 'order' => array('counter_id DESC')));

                    if (empty($check)) {
                        // check duplicate entry
                        $checkduplicate = $this->SerialNumbers->find("all", array('conditions' => $insert_values));
                        if (empty($checkduplicate)) {
                            $checkflag = 1;
                            foreach ($insert_values as $key => $single) {
                                if (empty($single)) {
                                    $checkflag = 0;
                                }
                            }
                            if ($checkflag) {
                                $insert_values['token_no'] = $doc_token_no;
                                $insert_values['user_id'] = $this->Auth->User("user_id");
                                $insert_values['state_id'] = $this->Auth->User("state_id");
                                $insert_values['req_ip'] = $this->request->clientIp();
                                $insert_values['user_type'] = $this->Session->read("session_usertype");
                                $this->SerialNumbers->save($insert_values);
                            } else {
                                return 0;
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Something went wrong.Please try again')
                            );
                            $generateflag = 0;
                        }
                    } else {
                        $format_values['BSN'] = $check['SerialNumbers']['book_serial_number'];
                        $format_values['OSN'] = $check['SerialNumbers']['office_serial_number'];
                    }

                    if ($generateflag) {

                        $config = $this->DocumentNumber->find("list", array('fields' => array('format_field', 'static_value'), 'conditions' => array('format_field_flag' => 'Y'), 'order' => 'display_order ASC'));

                        $number = '';
                        $separater = '';

                        foreach ($config as $key => $field) {
                            switch ($key) {
                                case 'SP': $separater = $field;
                                    break;
                                case 'SI': $number_arr[$key] = $insert_values['state_id'];
                                    break;
                                case 'SC': $number_arr[$key] = $insert_values['state_code'];
                                    break;
                                case 'DI': $number_arr[$key] = $insert_values['district_id'];
                                    break;
                                case 'DC': $number_arr[$key] = $insert_values['district_code'];
                                    break;
                                case 'TI': $number_arr[$key] = $insert_values['taluka_id'];
                                    break;
                                case 'TC': $number_arr[$key] = $insert_values['taluka_code'];
                                    break;
                                case 'AI': $number_arr[$key] = $officers['article']['article_id'];
                                    break;
                                case 'AC': $number_arr[$key] = $officers['article']['article_code'];
                                    break;
                                case 'Y': $number_arr[$key] = date('Y');
                                    break;
                                case 'FY': $number_arr[$key] = $currfinyear;
                                    break;
                                case 'CN':$number_arr[$key] = date('Ymd');
                                    break;
                                case 'OI': $number_arr[$key] = $officers['office']['office_id'];
                                    break;
                                case 'OC':$number_arr[$key] = $officers['office']['office_code'];
                                    break;
                                case 'OSN': $number_arr[$key] = $format_values['OSN'];
                                    break;
                                case 'BN': $number_arr[$key] = $officers['article']['book_number'];
                                    break;
                                case 'BSN':$number_arr[$key] = $format_values['BSN'];
                                    break;
                            }
                        }

                        $number = implode($separater, $number_arr);

                        return $number;
                    } else {
                        return 0;
                    }
                } else {
                    $this->Session->setFlash(
                            __('Article Not Found!')
                    );

                    return 0;
                }
            } else {

                $this->Session->setFlash(
                        __('Financial Year Not Found!')
                );
                return 0;
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function generate_document_number_final_auto($extraparam = NULL) {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('DocumentNumberFinal');
            $this->loadModel('finyear');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('SerialNumbersFinal');
            $this->loadModel('office');

            $doc_token_no = $this->Session->read("reg_token");
            $configall = $this->DocumentNumberFinal->find("all", array('fields' => array('format_field', 'format_field_flag', 'repeat_flag', 'osn_repeat_flag', 'bsn_repeat_flag', 'padding_flag', 'padding_length', 'padding_char'), 'order' => 'h_order ASC'));
            $finyear = $this->finyear->find("first", array('fields' => array('finyear_id', 'finyear_desc_short'), 'conditions' => array('current_year' => 'Y')));
            //pr($finyear);exit;
            if (!empty($finyear)) {
                $currfinyear = $finyear['finyear']['finyear_desc_short'];
                $officers = $this->office->find('first', array('fields' => array('article.article_id', 'article.book_number', 'article.titlewise_book_number', 'title.book_number', 'article.article_code', 'state.state_id', 'state.state_code', 'office.state_id', 'office.office_id', 'office.office_code', 'district.district_id', 'district.district_code', 'taluka.taluka_id', 'taluka.taluka_code'),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=office.taluka_id')),
                        array('table' => 'ngdrstab_conf_admblock1_state', 'alias' => 'state', 'conditions' => array('state.state_id=office.state_id')),
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'conditions' => array('info.token_no' => $doc_token_no)), //1
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=info.article_id')), //1
                        array('table' => 'ngdrstab_mst_articledescriptiondetail', 'alias' => 'title', 'type' => 'LEFT', 'conditions' => array('title.article_id=article.article_id')), //1
                    ),
                    'conditions' => array('office.office_id' => $this->Auth->user('office_id'))
                ));

                $format_values = array();
                $insert_values = array();
                $OS_options = array();
                $BS_options = array();

                if (!empty($officers)) {
                    if ($officers['article']['titlewise_book_number'] == 'Y') {
                        $officers['article']['book_number'] = $officers['title']['book_number'];
                    }
                    if (isset($extraparam['book_number'])) {
                        $officers['article']['book_number'] = $extraparam['book_number'];
                    }

                    $generateflag = 1;
                    foreach ($configall as $config) {
                        $config = $config['DocumentNumberFinal'];

                        if ($config['format_field'] == 'SI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_id'] = $officers['state']['state_id'];
                        }
                        if ($config['format_field'] == 'SC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_code'] = $officers['state']['state_code'];
                        }
                        if ($config['format_field'] == 'DI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_id'] = $officers['district']['district_id'];
                        }
                        if ($config['format_field'] == 'DC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_code'] = $officers['district']['district_code'];
                        }
                        if ($config['format_field'] == 'TI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_id'] = $officers['taluka']['taluka_id'];
                        }
                        if ($config['format_field'] == 'TC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_code'] = $officers['taluka']['taluka_code'];
                        }
                        if ($config['format_field'] == 'AI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_id'] = $officers['article']['article_id'];
                        }
                        if ($config['format_field'] == 'AC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_code'] = $officers['article']['article_code'];
                        }

                        if ($config['format_field'] == 'Y' && $config['format_field_flag'] == 'Y') {
                            $insert_values['year'] = date('Y');
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['year'] = date('Y');
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['year'] = date('Y');
                            }
                        }
                        if ($config['format_field'] == 'FY' && $config['format_field_flag'] == 'Y') {
                            $insert_values['finyear_id'] = $finyear['finyear']['finyear_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                        }

                        if ($config['format_field'] == 'OI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_id'] = $officers['office']['office_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_id'] = $officers['office']['office_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_id'] = $officers['office']['office_id'];
                            }
                        }
                        if ($config['format_field'] == 'OC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_code'] = $officers['office']['office_code'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_code'] = $officers['office']['office_code'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_code'] = $officers['office']['office_code'];
                            }
                        }


                        if ($config['format_field'] == 'OSN' && $config['format_field_flag'] == 'Y') {
                            $office_serial = $this->SerialNumbersFinal->find("first", array('fields' => array('office_serial_number'), 'conditions' => $OS_options, 'order' => 'counter_id DESC'));
                            $off_sn = $office_serial ? $office_serial['SerialNumbersFinal']['office_serial_number'] + 1 : 1;

                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $off_sn = str_pad($off_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }

                            $format_values['OSN'] = $off_sn;
                            $insert_values['office_serial_number'] = $off_sn;
                            // pr($insert_values);
                            //  exit;
                        }
                        if ($config['format_field'] == 'BN' && $config['format_field_flag'] == 'Y') {
                            $insert_values['book_number'] = $officers['article']['book_number'];

                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['book_number'] = $officers['article']['book_number'];
                            }
                        }

                        if ($config['format_field'] == 'BSN' && $config['format_field_flag'] == 'Y') {
                            $book_serial = $this->SerialNumbersFinal->find("first", array('fields' => array('book_serial_number'), 'conditions' => $BS_options, 'order' => 'counter_id DESC'));
                            $book_sn = $book_serial ? $book_serial['SerialNumbersFinal']['book_serial_number'] + 1 : 1;
                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $book_sn = str_pad($book_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }
                            $format_values['BSN'] = $book_sn;
                            $insert_values['book_serial_number'] = $book_sn;
                        }
                    }




                    $check = $this->SerialNumbersFinal->find("first", array('conditions' => array('token_no' => $doc_token_no), 'order' => array('counter_id DESC')));

                    if (empty($check)) {
                        // check duplicate entry
                        $checkduplicate = $this->SerialNumbersFinal->find("all", array('conditions' => $insert_values));
                        if (empty($checkduplicate)) {
                            $checkflag = 1;
                            foreach ($insert_values as $key => $single) {
                                if (empty($single)) {
                                    $checkflag = 0;
                                }
                            }
                            if ($checkflag) {
                                $insert_values['token_no'] = $doc_token_no;
                                $insert_values['user_id'] = $this->Auth->User("user_id");
                                $insert_values['state_id'] = $this->Auth->User("state_id");
                                $insert_values['req_ip'] = $this->request->clientIp();
                                $insert_values['user_type'] = $this->Session->read("session_usertype");
                                $this->SerialNumbersFinal->save($insert_values);
                            } else {
                                return 0;
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Something went wrong.Please try again')
                            );
                            $generateflag = 0;
                        }
                    } else {
                        $format_values['BSN'] = $check['SerialNumbersFinal']['book_serial_number'];
                        $format_values['OSN'] = $check['SerialNumbersFinal']['office_serial_number'];
                    }

                    if ($generateflag) {

                        $config = $this->DocumentNumberFinal->find("list", array('fields' => array('format_field', 'static_value'), 'conditions' => array('format_field_flag' => 'Y'), 'order' => 'display_order ASC'));

                        $number = '';
                        $separater = '';

                        foreach ($config as $key => $field) {
                            switch ($key) {
                                case 'SP': $separater = $field;
                                    break;
                                case 'SI': $number_arr[$key] = $insert_values['state_id'];
                                    break;
                                case 'SC': $number_arr[$key] = $insert_values['state_code'];
                                    break;
                                case 'DI': $number_arr[$key] = $insert_values['district_id'];
                                    break;
                                case 'DC': $number_arr[$key] = $insert_values['district_code'];
                                    break;
                                case 'TI': $number_arr[$key] = $insert_values['taluka_id'];
                                    break;
                                case 'TC': $number_arr[$key] = $insert_values['taluka_code'];
                                    break;
                                case 'AI': $number_arr[$key] = $officers['article']['article_id'];
                                    break;
                                case 'AC': $number_arr[$key] = $officers['article']['article_code'];
                                    break;
                                case 'Y': $number_arr[$key] = date('Y');
                                    break;
                                case 'FY': $number_arr[$key] = $currfinyear;
                                    break;
                                case 'CN':$number_arr[$key] = date('Ymd');
                                    break;
                                case 'OI': $number_arr[$key] = $officers['office']['office_id'];
                                    break;
                                case 'OC':$number_arr[$key] = $officers['office']['office_code'];
                                    break;
                                case 'OSN': $number_arr[$key] = $format_values['OSN'];
                                    break;
                                case 'BN': $number_arr[$key] = $officers['article']['book_number'];
                                    break;
                                case 'BSN':$number_arr[$key] = $format_values['BSN'];
                                    break;
                            }
                        }

                        $number = implode($separater, $number_arr);

                        return $number;
                    } else {

                        return 0;
                    }
                } else {
                    $this->Session->setFlash(
                            __('Article Not Found!')
                    );

                    return 0;
                }
            } else {

                $this->Session->setFlash(
                        __('Financial Year Not Found!')
                );
                return 0;
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function generate_document_number_final($extraparam = NULL) {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('DocumentNumberFinal');
            $this->loadModel('finyear');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('SerialNumbersFinal');
            $this->loadModel('office');
            $this->loadModel('MstSerialNumbersFinal');


            $doc_token_no = $this->Session->read("reg_token");
            $configall = $this->DocumentNumberFinal->find("all", array('fields' => array('format_field', 'format_field_flag', 'repeat_flag', 'osn_repeat_flag', 'bsn_repeat_flag', 'padding_flag', 'padding_length', 'padding_char'), 'order' => 'h_order ASC'));
            $finyear = $this->finyear->find("first", array('fields' => array('finyear_id', 'finyear_desc_short'), 'conditions' => array('current_year' => 'Y')));
            //pr($finyear);exit;
            if (!empty($finyear)) {
                $currfinyear = $finyear['finyear']['finyear_desc_short'];
                $officers = $this->office->find('first', array('fields' => array('article.article_id', 'article.book_number', 'article.titlewise_book_number', 'title.book_number', 'article.article_code', 'state.state_id', 'state.state_code', 'office.state_id', 'office.office_id', 'office.office_code', 'district.district_id', 'district.district_code', 'taluka.taluka_id', 'taluka.taluka_code'),
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=office.taluka_id')),
                        array('table' => 'ngdrstab_conf_admblock1_state', 'alias' => 'state', 'conditions' => array('state.state_id=office.state_id')),
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'conditions' => array('info.token_no' => $doc_token_no)), //1
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=info.article_id')), //1
                        array('table' => 'ngdrstab_mst_articledescriptiondetail', 'alias' => 'title', 'type' => 'LEFT', 'conditions' => array('title.article_id=article.article_id')), //1
                    ),
                    'conditions' => array('office.office_id' => $this->Auth->user('office_id'))
                ));

                $format_values = array();
                $insert_values = array();
                $OS_options = array();
                $BS_options = array();

                if (!empty($officers)) {
                    if ($officers['article']['titlewise_book_number'] == 'Y') {
                        $officers['article']['book_number'] = $officers['title']['book_number'];
                    }
                    if (isset($extraparam['book_number'])) {
                        $officers['article']['book_number'] = $extraparam['book_number'];
                    }
                    $generateflag = 1;
                    foreach ($configall as $config) {
                        $config = $config['DocumentNumberFinal'];

                        if ($config['format_field'] == 'SI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_id'] = $officers['state']['state_id'];
                        }
                        if ($config['format_field'] == 'SC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['state_code'] = $officers['state']['state_code'];
                        }
                        if ($config['format_field'] == 'DI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_id'] = $officers['district']['district_id'];
                        }
                        if ($config['format_field'] == 'DC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['district_code'] = $officers['district']['district_code'];
                        }
                        if ($config['format_field'] == 'TI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_id'] = $officers['taluka']['taluka_id'];
                        }
                        if ($config['format_field'] == 'TC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['taluka_code'] = $officers['taluka']['taluka_code'];
                        }
                        if ($config['format_field'] == 'AI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_id'] = $officers['article']['article_id'];
                        }
                        if ($config['format_field'] == 'AC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['article_code'] = $officers['article']['article_code'];
                        }

                        if ($config['format_field'] == 'Y' && $config['format_field_flag'] == 'Y') {
                            $insert_values['year'] = date('Y');
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['year'] = date('Y');
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['year'] = date('Y');
                            }
                        }
                        if ($config['format_field'] == 'FY' && $config['format_field_flag'] == 'Y') {
                            $insert_values['finyear_id'] = $finyear['finyear']['finyear_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                            }
                        }

                        if ($config['format_field'] == 'OI' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_id'] = $officers['office']['office_id'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_id'] = $officers['office']['office_id'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_id'] = $officers['office']['office_id'];
                            }
                        }
                        if ($config['format_field'] == 'OC' && $config['format_field_flag'] == 'Y') {
                            $insert_values['office_code'] = $officers['office']['office_code'];
                            if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                                $OS_options['office_code'] = $officers['office']['office_code'];
                            }
                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['office_code'] = $officers['office']['office_code'];
                            }
                        }


                        if ($config['format_field'] == 'OSN' && $config['format_field_flag'] == 'Y') {
                            $office_serial = $this->SerialNumbersFinal->find("first", array('fields' => array('office_serial_number'), 'conditions' => $OS_options, 'order' => 'counter_id DESC'));
                            if (!empty($office_serial)) {
                                $off_sn = $office_serial['SerialNumbersFinal']['office_serial_number'] + 1;
                            } else {
                                $office_serial = $this->MstSerialNumbersFinal->find("first", array('fields' => array('office_serial_number'), 'conditions' => $OS_options, 'order' => 'counter_id DESC'));
                                if (!empty($office_serial)) {
                                    $off_sn = $office_serial['MstSerialNumbersFinal']['office_serial_number'];
                                } else {
                                    $this->Session->setFlash(
                                            __('office Serial number Initialise missing')
                                    );
                                    return 0;
                                }
                            }
                            //$off_sn = $office_serial ? $office_serial['SerialNumbersFinal']['office_serial_number'] + 1 : 1;
                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $off_sn = str_pad($off_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }
                            $format_values['OSN'] = $off_sn;
                            $insert_values['office_serial_number'] = $off_sn;
                            // pr($insert_values);
                            //  exit;
                        }
                        if ($config['format_field'] == 'BN' && $config['format_field_flag'] == 'Y') {
                            $insert_values['book_number'] = $officers['article']['book_number'];

                            if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                                $BS_options['book_number'] = $officers['article']['book_number'];
                            }
                        }

                        if ($config['format_field'] == 'BSN' && $config['format_field_flag'] == 'Y') {
                            $book_serial = $this->SerialNumbersFinal->find("first", array('fields' => array('book_serial_number'), 'conditions' => $BS_options, 'order' => 'counter_id DESC'));
                            if (!empty($book_serial)) {
                                $book_sn = $book_serial['SerialNumbersFinal']['book_serial_number'] + 1;
                            } else {
                                // pr($BS_options);exit;
                                $book_serial = $this->MstSerialNumbersFinal->find("first", array('fields' => array('book_serial_number'), 'conditions' => $BS_options, 'order' => 'counter_id DESC'));
                                if (!empty($book_serial)) {
                                    $book_sn = $book_serial['MstSerialNumbersFinal']['book_serial_number'];
                                } else {
                                    $this->Session->setFlash(
                                            __('Book Serial number Initialise missing')
                                    );
                                    return 0;
                                }
                            }

                            //$book_sn = $book_serial ? $book_serial['SerialNumbersFinal']['book_serial_number'] + 1 : 1;
                            if ($config['padding_flag'] == 'Y' && is_numeric($config['padding_length']) && !empty($config['padding_char'])) {
                                $book_sn = str_pad($book_sn, $config['padding_length'], $config['padding_char'], STR_PAD_LEFT);
                            }
                            $format_values['BSN'] = $book_sn;
                            $insert_values['book_serial_number'] = $book_sn;
                        }
                    }




                    $check = $this->SerialNumbersFinal->find("first", array('conditions' => array('token_no' => $doc_token_no), 'order' => array('counter_id DESC')));

                    if (empty($check)) {
                        // check duplicate entry
                        $checkduplicate = $this->SerialNumbersFinal->find("all", array('conditions' => $insert_values));
                        if (empty($checkduplicate)) {
                            $checkflag = 1;
                            foreach ($insert_values as $key => $single) {
                                if (empty($single)) {
                                    $checkflag = 0;
                                }
                            }
                            if ($checkflag) {
                                $insert_values['token_no'] = $doc_token_no;
                                $insert_values['user_id'] = $this->Auth->User("user_id");
                                $insert_values['state_id'] = $this->Auth->User("state_id");
                                $insert_values['req_ip'] = $this->request->clientIp();
                                $insert_values['user_type'] = $this->Session->read("session_usertype");
                                $this->SerialNumbersFinal->save($insert_values);
                            } else {
                                $this->Session->setFlash(
                                        __('record exist sr no')
                                );
                                return 0;
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Something went wrong.Please try again')
                            );
                            $generateflag = 0;
                        }
                    } else {
                        $format_values['BSN'] = $check['SerialNumbersFinal']['book_serial_number'];
                        $format_values['OSN'] = $check['SerialNumbersFinal']['office_serial_number'];
                    }

                    if ($generateflag) {

                        $config = $this->DocumentNumberFinal->find("list", array('fields' => array('format_field', 'static_value'), 'conditions' => array('format_field_flag' => 'Y'), 'order' => 'display_order ASC'));

                        $number = '';
                        $separater = '';

                        foreach ($config as $key => $field) {
                            switch ($key) {
                                case 'SP': $separater = $field;
                                    break;
                                case 'SI': $number_arr[$key] = $insert_values['state_id'];
                                    break;
                                case 'SC': $number_arr[$key] = $insert_values['state_code'];
                                    break;
                                case 'DI': $number_arr[$key] = $insert_values['district_id'];
                                    break;
                                case 'DC': $number_arr[$key] = $insert_values['district_code'];
                                    break;
                                case 'TI': $number_arr[$key] = $insert_values['taluka_id'];
                                    break;
                                case 'TC': $number_arr[$key] = $insert_values['taluka_code'];
                                    break;
                                case 'AI': $number_arr[$key] = $officers['article']['article_id'];
                                    break;
                                case 'AC': $number_arr[$key] = $officers['article']['article_code'];
                                    break;
                                case 'Y': $number_arr[$key] = date('Y');
                                    break;
                                case 'FY': $number_arr[$key] = $currfinyear;
                                    break;
                                case 'CN':$number_arr[$key] = date('Ymd');
                                    break;
                                case 'OI': $number_arr[$key] = $officers['office']['office_id'];
                                    break;
                                case 'OC':$number_arr[$key] = $officers['office']['office_code'];
                                    break;
                                case 'OSN': $number_arr[$key] = $format_values['OSN'];
                                    break;
                                case 'BN': $number_arr[$key] = $officers['article']['book_number'];
                                    break;
                                case 'BSN':$number_arr[$key] = $format_values['BSN'];
                                    break;
                            }
                        }

                        $number = implode($separater, $number_arr);

                        return $number;
                    } else {

                        return 0;
                    }
                } else {
                    $this->Session->setFlash(
                            __('Article Not Found!')
                    );

                    return 0;
                }
            } else {

                $this->Session->setFlash(
                        __('Financial Year Not Found!')
                );
                return 0;
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function generate_receipt_number($doc_token_no = NULL) {
        try {
            // $this->autoRender = FALSE;
            $this->loadModel('ReceiptNumber');
            $this->loadModel('finyear');
            $this->loadModel('TrnReceiptNumber');
            $this->loadModel('ReceiptCounter');

            //  pr($this->referer());exit;
            $configall = $this->ReceiptNumber->find("all", array('fields' => array('format_field', 'format_field_flag', 'repeat_flag', 'osn_repeat_flag', 'bsn_repeat_flag'), 'order' => 'h_order ASC'));
            //pr($config);exit;
            $finyear = $this->finyear->find("first", array('fields' => array('finyear_id', 'finyear_desc_short'), 'conditions' => array('current_year' => 'Y')));
            if (!empty($finyear)) {
                $currfinyear = $finyear['finyear']['finyear_desc_short'];

                $format_values = array();
                $insert_values = array();
                $insert_values['remark'] = $doc_token_no;

                foreach ($configall as $config) {
                    $config = $config['ReceiptNumber'];
                    if ($config['format_field'] == 'Y' && $config['format_field_flag'] == 'Y') {
                        $insert_values['year'] = date('Y');
                        if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                            $OS_options['year'] = date('Y');
                        }
                        if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                            $BS_options['year'] = date('Y');
                        }
                    }
                    if ($config['format_field'] == 'FY' && $config['format_field_flag'] == 'Y') {
                        $insert_values['finyear_id'] = $finyear['finyear']['finyear_id'];
                        if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                            $OS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                        }
                        if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                            $BS_options['finyear_id'] = $finyear['finyear']['finyear_id'];
                        }
                    }

                    if ($config['format_field'] == 'OI' && $config['format_field_flag'] == 'Y') {
                        $insert_values['office_id'] = $this->Auth->user('office_id');
                        if ($config['repeat_flag'] == 'Y' && $config['osn_repeat_flag'] == 'Y') {
                            $OS_options['office_id'] = $this->Auth->user('office_id');
                        }
                        if ($config['repeat_flag'] == 'Y' && $config['bsn_repeat_flag'] == 'Y') {
                            $BS_options['office_id'] = $this->Auth->user('office_id');
                        }
                    }


                    if ($config['format_field'] == 'OSN' && $config['format_field_flag'] == 'Y') {
                        $office_serial = $this->TrnReceiptNumber->find("first", array('fields' => array('office_serial_number'), 'conditions' => $OS_options, 'order' => 'counter_id DESC'));
                        $off_sn = $office_serial ? $office_serial['TrnReceiptNumber']['office_serial_number'] + 1 : 1;
                        $format_values['OSN'] = $off_sn;
                        $insert_values['office_serial_number'] = $off_sn;
                    }
                }
                $insert_values = $this->add_default_fields($insert_values);
                if ($this->TrnReceiptNumber->save($insert_values)) {
                    $config = $this->ReceiptNumber->find("list", array('fields' => array('format_field', 'static_value'), 'conditions' => array('format_field_flag' => 'Y'), 'order' => 'display_order ASC'));
                    $number = '';
                    $separater = '';
                    foreach ($config as $key => $field) {
                        switch ($key) {
                            case 'SP': $separater = $field;
                                break;
                            case 'Y': $number_arr[$key] = date('Y');
                                break;
                            case 'FY': $number_arr[$key] = $currfinyear;
                                break;
                            case 'CN':$number_arr[$key] = date('Ymd');
                                break;
                            case 'OI': $number_arr[$key] = $this->Auth->user("office_id");
                                break;
                            case 'OSN': $number_arr[$key] = $format_values['OSN'];
                                break;
                        }
                    }

                    $number = implode($separater, $number_arr);
                    $receipt['receipt_number'] = "" . $number . "";
                    $receipt['token_no'] = $doc_token_no;
                    $receipt = $this->add_default_fields($receipt);
                    if ($this->ReceiptCounter->save($receipt)) {
                        return $number;
                    } else {
                        $this->Session->setFlash(
                                __('Fail To Save Receipt Number!')
                        );
                        return 0;
                    }
                } else {
                    $this->Session->setFlash(
                            __('Fail To Generate Receipt Number!')
                    );
                    return 0;
                }
            } else {
                $this->Session->setFlash(
                        __('Financial Year Not Found!')
                );
                return 0;
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_main_menu_old() {
        try {
            $this->loadModel('RegistrationMainmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('mainmenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $mainmenurecord = $this->RegistrationMainmenu->find('all');
            $this->set('mainmenurecord', $mainmenurecord);
            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['mainmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['mainmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['controller']['text'] = 'is_required,is_alpha';
            $fieldlist['action']['text'] = 'is_required';
            $fieldlist['mm_serial']['text'] = 'is_required,is_positiveinteger';//is_numeric';


            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];

                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_main_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_main_menu']['user_id'] = $user_id;
                        $this->request->data['reg_main_menu']['created_date'] = $created_date;
                        $this->request->data['reg_main_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_main_menu']['mainmenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['RegistrationMainmenu'] = $this->istrim($this->request->data['reg_main_menu']);
//                          pr($this->request->data['RegistrationMainmenu']);exit;

                        $errarr = $this->validatedata($this->request->data['reg_main_menu'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
//                            pr( $this->request->data);exit;
                            if ($this->RegistrationMainmenu->save($this->request->data['RegistrationMainmenu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_main_menu'));
                                $this->set('mainmenurecord', $this->RegistrationMainmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $functionlist_used = $this->RegistrationMainmenu->find('list', array('fields' => array('action', 'action')));
            $this->set("functionlist_used", $functionlist_used);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_main_menu_delete_old($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationMainmenu');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'officehierarchy') {

                if ($this->RegistrationMainmenu->deleteAll(array('mainmenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_main_menu'));
                }
                // }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_menu_old() {
        try {
            $this->loadModel('RegistrationSubmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');

            $this->set('mainmenuid', ClassRegistry::init('RegistrationMainmenu')->find('list', array('fields' => array('mainmenu_id', 'mainmenu_desc_en'), 'order' => array('mainmenu_desc_en' => 'ASC'))));
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('submenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $submenurecord = $this->RegistrationSubmenu->find('all');
            $this->set('submenurecord', $submenurecord);
            $fieldlist = array();

            $fieldlist['mainmenu_id']['select'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['submenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['submenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['sm_serial']['text'] = 'is_required,is_positiveinteger';//is_numeric';
            $fieldlist['is_stamp']['radio'] = 'is_required';
            $fieldlist['stamp_id']['select'] = 'is_select_req';



            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $this->request->data['reg_sub_menu']['stateid'] = $stateid;
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_sub_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_sub_menu']['user_id'] = $user_id;
                        $this->request->data['reg_sub_menu']['created'] = $created_date;
                        $this->request->data['reg_sub_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_sub_menu']['submenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['reg_sub_menu'] = $this->istrim($this->request->data['reg_sub_menu']);
                        //  pr($this->request->data['RegistrationSubmenu']);exit;
                        if (isset($this->request->data['reg_sub_menu']['is_stamp']) && $this->request->data['reg_sub_menu']['is_stamp'] == 'N') {
                            unset($this->request->data['reg_sub_menu']['stamp_id']);
                            unset($fieldlist['stamp_id']);
                        }

                        $errarr = $this->validatedata($this->request->data['reg_sub_menu'], $fieldlist);
//                         pr($this->request->data);
//                        pr($errarr);exit;
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
//                            pr($this->request->data);exit;
                            if ($this->RegistrationSubmenu->save($this->request->data['reg_sub_menu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_sub_menu'));
                                $this->set('submenurecord', $this->RegistrationSubmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $stamp_id_used = $this->RegistrationSubmenu->find('list', array('fields' => array('stamp_id', 'stamp_id')));
            $this->set("stamp_id_used", $stamp_id_used);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_menu_delete_old($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationSubmenu');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'officehierarchy') {


                if ($this->RegistrationSubmenu->deleteAll(array('submenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_menu'));
                } else {
                    $this->Session->setFlash(
                            __('lblnotdeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_menu'));
                }
                // }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_sub_menu_old() {
        try {
            $this->loadModel('RegistrationSubsubmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $lang = $this->Session->read("sess_langauge");
            $this->set('submenuid', ClassRegistry::init('RegistrationSubmenu')->find('list', array('fields' => array('submenu_id', 'submenu_desc_en'), 'order' => array('submenu_desc_en' => 'ASC'))));

            $this->set('roledata', ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'conditions' => array('role_id' => array(999901, 999902, 999903)), 'order' => array('role_name_' . $lang => 'ASC'))));

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);

            $this->set('subsubmenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $subsubmenurecord = $this->RegistrationSubsubmenu->find('all');
            $this->set('subsubmenurecord', $subsubmenurecord);
            $fieldlist = array();

            $fieldlist['submenu_id']['text'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['subsubmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['subsubmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['controller']['text'] = 'is_required,is_alpha';
            $fieldlist['action']['text'] = 'is_required';
            $fieldlist['ssm_serial']['text'] = 'is_required,is_positiveinteger';//is_numeric';
            $fieldlist['function_order']['text'] = 'is_required,is_positiveinteger';//is_numeric';
            $fieldlist['role_id']['select'] = 'is_select_req';
            $fieldlist['function_sr_no']['text'] = 'is_required,is_numeric';
            //$fieldlist['function_hierarchy']['checkbox'] = 'is_numeric';






            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $this->request->data['reg_sub_sub_menu']['stateid'] = $stateid;
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_sub_sub_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_sub_sub_menu']['user_id'] = $user_id;
                        $this->request->data['reg_sub_sub_menu']['created_date'] = $created_date;
                        $this->request->data['reg_sub_sub_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_sub_sub_menu']['subsubmenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['reg_sub_sub_menu'] = $this->istrim($this->request->data['reg_sub_sub_menu']);
                        //pr($this->request->data['reg_sub_sub_menu']);exit;
                        $errarr = $this->validatedata($this->request->data['reg_sub_sub_menu'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {//                            
                            if (isset($this->request->data['reg_sub_sub_menu']['function_hierarchy']) && is_array($this->request->data['reg_sub_sub_menu']['function_hierarchy'])) {
                                $this->request->data['reg_sub_sub_menu']['function_hierarchy'] = implode("-", $this->request->data['reg_sub_sub_menu']['function_hierarchy']);
                            }
                            if ($this->RegistrationSubsubmenu->save($this->request->data['reg_sub_sub_menu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_sub_sub_menu'));
                                $this->set('subsubmenurecord', $this->RegistrationSubsubmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $functionid_used = $this->RegistrationSubsubmenu->find('list', array('fields' => array('function_sr_no', 'function_sr_no')));
            $this->set("functionid_used", $functionid_used);

            $functionlist_used = $this->RegistrationSubsubmenu->find('list', array('fields' => array('action', 'action')));
            $this->set("functionlist_used", $functionlist_used);


            $function_hierarchy = $this->RegistrationSubsubmenu->find('list', array('fields' => array('function_sr_no', 'subsubmenu_desc_' . $laug), 'order' => 'function_sr_no ASC'));
            $this->set("function_hierarchy", $function_hierarchy);

            $stamp_conf = $this->stamp_and_functions_config();
            $this->set("stamp_conf", $stamp_conf);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_sub_menu_delete_old($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationSubsubmenu');
        try {
            if (isset($id) && is_numeric($id)) {
                if ($this->RegistrationSubsubmenu->deleteAll(array('subsubmenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_sub_menu'));
                }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function document_release() {
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('DocumentRelease');
        $user_id = $this->Auth->User("user_id");
        $stateid = $this->Auth->User("state_id");
        $office_id = $this->Auth->User("office_id");
        $fieldlist = array();
        $fieldlist['document_number']['text'] = 'is_required,is_digit';
        $this->set('fieldlist', $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));




        if ($this->request->is('post') && isset($this->request->data['document_release']['document_number'])) {
            $errarr = $this->validatedata($this->request->data['document_release'], $fieldlist);

            if ($this->ValidationError($errarr)) {
                // pr($this->request->data);exit;
                $document_number = $this->request->data['document_release']['document_number'];
                if (!empty($document_number)) {
                    $check_release = $this->DocumentRelease->find("all", array('conditions' => array('doc_reg_no' => $document_number, 'document_change_status' => 'N')));
                    $stamp_conf = $this->stamp_and_functions_config();
                    foreach ($stamp_conf as $stamp) {
                        if ($stamp['is_last'] == 'Y') {
                            $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
                        }
                    }
                    $result = $this->ApplicationSubmitted->search_document($document_number, $check_stamp_flag);

                    if (isset($this->request->data['document_release']['document_release_remark']) && !empty($this->request->data['document_release']['document_release_remark']) && !empty($result) && empty($check_release)) {
                        $data = $this->request->data['document_release'];
                        $data['token_no'] = $result[0][0]['token_no'];
                        $data['release_date'] = date('Y-m-d H:i:s');
                        $data['doc_reg_no'] = $result[0][0]['doc_reg_no'];
                        $data['req_ip'] = getenv('HTTP_CLIENT_IP');
                        $data['user_id'] = $user_id;
                        $data['state_id'] = $stateid;
                        $data['office_id'] = $office_id;
                        $this->DocumentRelease->save($data);

                        $reset_stamp_flag = array('stamp1_flag' => "'N'", 'stamp2_flag' => "'N'", 'stamp3_flag' => "'N'", 'stamp4_flag' => "'N'", 'stamp5_flag' => "'N'", 'stamp6_flag' => "'N'", 'stamp7_flag' => "'N'", 'stamp8_flag' => "'N'");
                        $reset_stamp_date = array('stamp1_date' => NULL, 'stamp2_date' => NULL, 'stamp3_date' => NULL, 'stamp4_date' => NULL, 'stamp5_date' => NULL, 'stamp6_date' => NULL, 'stamp7_date' => NULL, 'stamp8_date' => NULL);
                        $reset_fun_flag = array('fun1_flag' => "'N'", 'fun2_flag' => "'N'", 'fun3_flag' => "'N'", 'fun4_flag' => "'N'", 'fun5_flag' => "'N'", 'fun6_flag' => "'N'", 'fun7_flag' => "'N'", 'fun8_flag' => "'N'", 'fun9_flag' => "'N'", 'fun10_flag' => "'N'",);
                        $reset_fun_date = array('fun1_date' => NULL, 'fun2_date' => NULL, 'fun3_date' => NULL, 'fun4_date' => NULL, 'fun5_date' => NULL, 'fun6_date' => NULL, 'fun7_date' => NULL, 'fun8_date' => NULL, 'fun9_date' => NULL, 'fun10_date' => NULL,);
                        $reset_other_fields = array('document_entry_flag' => "'N'", 'document_entry_remark' => NULL, 'check_in_flag' => "'N'", 'check_in_date' => NULL, 'document_scan_flag' => "'N'", 'document_scan_date' => NULL, 'org_user_id' => $this->Auth->user('user_id'));

                        $reset_data = array_merge($reset_stamp_flag, $reset_stamp_date, $reset_fun_flag, $reset_fun_date, $reset_other_fields);
                        $reset_data = $this->add_default_fields_updateAll($reset_data);
                        $this->ApplicationSubmitted->updateAll($reset_data, array('doc_reg_no' => $result[0][0]['doc_reg_no']));

                        $this->Session->setFlash(
                                __('The Document Released Successfully')
                        );
                        return $this->redirect(array('action' => 'document_release'));
                    }

                    $this->set('result', $result);
                    $this->set('check_release', $check_release);
                }
            }
        }
        $released_list = $this->ApplicationSubmitted->released_document_list();
        $this->set('released_list', $released_list);
    }

    public function reports() {
        
    }

    public function reprint_summary_report() {
        $this->loadModel('ApplicationSubmitted');

        if ($this->request->is('post') && isset($this->request->data['reprint_summary_report']['document_number'])) {
            $document_number = $this->request->data['reprint_summary_report']['document_number'];
            if (!empty($document_number)) {
                $stamp_conf = $this->stamp_and_functions_config();
                foreach ($stamp_conf as $stamp) {
                    if ($stamp['is_last'] == 'Y') {
                        $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
                    }
                }
                $result = $this->ApplicationSubmitted->search_document($document_number, $check_stamp_flag);
            }
            $this->set("result", $result);
        }
    }

    public function search_registration_summary() {
        $this->loadModel('ApplicationSubmitted');

        if ($this->request->is('post')) {
            $from_date = $this->request->data['search_registration_summary']['from_date'];
            $to_date = $this->request->data['search_registration_summary']['to_date'];
            $type = $this->request->data['search_registration_summary']['type'];

            if (!empty($from_date) && !empty($to_date) && !empty($type)) {
                $stamp_conf = $this->stamp_and_functions_config();
                foreach ($stamp_conf as $stamp) {
                    if ($stamp['is_last'] == 'Y') {
                        $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
                    }
                }
                $released_list = $this->ApplicationSubmitted->search_document_summary($from_date, $to_date, $type, $check_stamp_flag);
                $this->set('released_list', $released_list);
                $this->set('check_stamp_flag', $check_stamp_flag);
            }
        }
    }

    public function certified_copy_print() {
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->set('hfid', NULL);
            if ($this->request->is('post')) {
                $details_id = $_POST['hfid'];
                $now = date('Y-m-d H:i:s');
                if (isset($details_id) && is_numeric($details_id)) {
                    $this->ApplicationSubmitted->query("update ngdrstab_trn_certificates_issue_details set issue_flag=?,issue_date=? where details_id=?", array('Y', $now, $details_id));
                    $design = "<h1> This is Test Report...!!!!</h1>";
                    $this->create_pdf($design, "test", 'A4-P');
                    return $this->redirect(array('action' => 'certified_copy_print'));
                }
            }
            $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,app.token_no as token,article.*, party.party_full_name_en, cert.*
                          
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id                           
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            inner join ngdrstab_trn_certificates_issue_details cert on app.doc_reg_no=cert.doc_reg_no and cert.issue_flag='N' and cert.ctype='C'"
            ));
//            pr($alldocuments);
//            exit;
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function certified_copy_payment($reg_no = NULL, $detail_id = NULL) {
        $this->Session->write('type', 'C');
        array_map(array($this, 'loadModel'), array('payment', 'payment_mode', 'bank_master', 'ApplicationSubmitted', 'PaymentFields', 'article_fee_items', 'ReceiptCounter', 'certificatesissuedetails'));
        $user_id = $this->Auth->User("user_id");
        $office_id = $this->Auth->User("office_id");
        $token_info = $this->ApplicationSubmitted->find('first', array('conditions' => array('doc_reg_no' => $reg_no)));

        if (is_numeric($token_info)) {

            $this->Session->write('certified_copy_token', $token_info['ApplicationSubmitted']['token_no']);
        }
        if (is_numeric($reg_no)) {
            $this->Session->write('reg_no', $reg_no);
        }

        if (is_numeric($detail_id)) {
            $this->Session->write('details_id', $detail_id);
        }
        $lang = $this->Session->read("sess_langauge");
        $token = $this->Session->read('certified_copy_token');
        $office_id = $this->Session->read("office_id");
        $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
        $payment_mode_online = $this->payment_mode->get_payment_mode_online($lang);
        $feedetails = $this->article_fee_items->query("SELECT 
         feeitem.account_head_code,
feeitem.fee_item_desc_$lang,
certificat.fee_amount as totalsd,         
certificat.payment_accept_flag,
certificat.issue_flag,
certificat.doc_reg_no,
certificat.payment_id,
certificat.online_payment_id
FROM
ngdrstab_trn_certificates_issue_details certificat 
LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.account_head_code=certificat.account_head_code 
  WHERE  certificat.doc_reg_no=?  
  and certificat.details_id=? 
 and  certificat.ctype='C'
 AND feeitem.fee_param_type_id=2 
group by feeitem.fee_item_id,certificat.fee_amount,certificat.payment_accept_flag,certificat.issue_flag,certificat.token_no,certificat.doc_reg_no,certificat.payment_id,
certificat.online_payment_id
order by feeitem.fee_preference ASC
", array($reg_no, $detail_id));

        $paymentfields = $this->PaymentFields->find('list', array('fields' => array('field_name', 'field_name_desc_en'), 'order' => 'srno ASC'));
        if (!empty($feedetails)) {
            $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_$lang ,mode.verification_flag  FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.payment_id=?  ", array($feedetails[0][0]['payment_id']));
        }

        $this->set(compact('payment_mode_counter', 'payment_mode_online', 'token', 'feedetails', 'payment', 'lang', 'paymentfields'));
        /* Validation Field set For Client Side Valuation */
        $fieldlist['paymentGetPaymentDetailsCertifiedCopyForm'] = $this->PaymentFields->fieldlist();
        $this->set("fieldlistmultiform", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
        if ($this->request->is('post')) {
            $data = $this->request->data;

            if (isset($data['payment'])) {

                $this->check_csrf_token($this->request->data['payment']['csrftoken']);
                if ($data['payment']['pdate'] != '' || $data['payment']['pdate'] != NULL) {
                    $data['payment']['pdate'] = date('Y-m-d', strtotime($data['payment']['pdate']));
                }
                $data['payment']['token_no'] = $this->Session->read('certified_copy_token');
                $fieldlist_new = $this->PaymentFields->fieldlist($data['payment']['payment_mode_id']);
                $errors = $this->validatedata($data['payment'], $fieldlist_new);
                if ($this->ValidationError($errors)) {
                    if ($this->payment->save($data)) {
                        if (!isset($data['payment']['payment_id'])) {
//                       echo $this->Session->read('details_id');
//                       exit;
                            $payment_id = $this->payment->getLastInsertID();
                            if (is_numeric($this->Session->read('details_id'))) {
                                $this->certificatesissuedetails->updateAll(
                                        array('payment_id' => $payment_id, 'account_head_code' => $data['payment']['account_head_code']), array('details_id' => $this->Session->read('details_id'))
                                );
                            }
                        }
                        $this->Session->setFlash('lblsavemsg');
                    }
                } else {
                    $this->Session->setFlash('lblnotsavemsg');
                }
                $this->redirect(array('controller' => 'Registration', 'action' => 'certified_copy_payment', $this->Session->read('reg_no'), $this->Session->read('details_id')));
            } elseif ($data['certified_copy_payment']) {
                $this->check_csrf_token($this->request->data['certified_copy_payment']['csrftoken']);
                $allrecordstatus = array();
//                pr($payment);
//                exit;
                if (isset($payment) && !empty($payment)) {
                    foreach ($payment as $single) {

                        if ($single[0]['verification_flag'] == 'Y' && $single[0]['defacement_flag'] == 'N') {
                            // Check this payment is deface with another Payment Id (Full Deface)
                            $check_deface = $this->payment->find('list', array('fields' => array('payment_id', 'defacement_flag'), 'conditions' => array('payment_id' => $single[0]['payment_id'], 'defacement_flag' => 'Y')));
                            if (empty($check_deface)) {
                                $deface_response = $this->payment_defacement($single[0]['payment_id']);
                            }

                            if ($deface_response['Status'] == 0) {
                                $allrecordstatus[$single[0]['payment_id']] = $deface_response['Error'];
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__("payment Not Accepted"));
                    $this->redirect('encumberance_cert');
                }

                if (!empty($allrecordstatus)) {
                    $message = "<ul>";
                    foreach ($allrecordstatus as $key => $error) {
                        $message .= "<li>[ Payment Id : " . $key . " ] " . $error . "</li>";
                    }
                    $message .= "</ul>";
                    $this->Session->setFlash($message);
                    $this->redirect('certified_copy_payment');
                } else {

                    if ($data['certified_copy_payment']['totalpaid'] < $data['certified_copy_payment']['tobepaid']) {
                        $this->Session->setFlash(__("Insufficient payment"));
                        $this->redirect('certified_copy_payment');
                    }
                    $this->certificatesissuedetails->updateAll(
                            array('payment_accept_flag' => "'Y'", 'payment_accept_date' => "'" . date('Y-m-d H:i:sa') . "'"), array('doc_reg_no' => $reg_no)
                    );
                    $this->Session->setFlash(__("payment Accepted"));
                    $this->redirect('certified_copy_print');
                }
            }
        }
        $this->set_csrf_token();
    }

    function certified_copy_payment_delete($payemnt_id = NULL) {
        try {

            $this->autoRender = FALSE;
            if (is_numeric($payemnt_id)) {

                $this->loadModel('payment');
                $this->payment->id = trim($payemnt_id);
                if ($this->payment->delete()) {

                    $this->Session->setFlash(__("Payment Deleted"));
                    $this->redirect('certified_copy_print');
                } else {
                    $this->Session->setFlash(__("Payment Not Deleted"));
                    $this->redirect('certified_copy_print');
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function encum_payment_delete($payemnt_id = NULL) {
        try {

            $this->autoRender = FALSE;
            if (is_numeric($payemnt_id)) {

                $this->loadModel('payment');
                $this->payment->id = trim($payemnt_id);
                if ($this->payment->delete()) {

                    $this->Session->setFlash(__("Payment Deleted"));
                    $this->redirect('encum_cert_payment');
                } else {
                    $this->Session->setFlash(__("Payment Not Deleted"));
                    $this->redirect('encum_cert_payment');
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function encumberance_cert() {
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->set('hfid', NULL);
            $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,app.token_no as token,article.*, party.party_full_name_en, cert.*
                          
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id                           
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            inner join ngdrstab_trn_certificates_issue_details cert on app.doc_reg_no=cert.doc_reg_no and cert.issue_flag='N' and cert.ctype='E'"
            ));
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function encum_cert_payment($reg_no = NULL, $detail_id = NULL) {
        $this->Session->write('type', 'E');
        array_map(array($this, 'loadModel'), array('payment', 'payment_mode', 'bank_master', 'ApplicationSubmitted', 'PaymentFields', 'article_fee_items', 'ReceiptCounter', 'certificatesissuedetails'));
        $user_id = $this->Auth->User("user_id");
        $office_id = $this->Auth->User("office_id");
        $token_info = $this->ApplicationSubmitted->find('first', array('conditions' => array('doc_reg_no' => $reg_no)));
        if (is_numeric($token_info)) {
            $this->Session->write('certified_copy_token', $token_info['ApplicationSubmitted']['token_no']);
        }
        if (is_numeric($reg_no)) {
            $this->Session->write('reg_no', $reg_no);
        }
        if (is_numeric($detail_id)) {
            $this->Session->write('details_id', $detail_id);
        }
        $lang = $this->Session->read("sess_langauge");
        $token = $this->Session->read('certified_copy_token');
        $office_id = $this->Session->read("office_id");
        $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
        $payment_mode_online = $this->payment_mode->get_payment_mode_online($lang);
        $feedetails = $this->article_fee_items->query("SELECT 
         feeitem.account_head_code,
feeitem.fee_item_desc_$lang,
certificat.fee_amount as totalsd,         
certificat.payment_accept_flag,
certificat.issue_flag,
certificat.doc_reg_no,
certificat.payment_id,
certificat.online_payment_id
FROM
ngdrstab_trn_certificates_issue_details certificat 
LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.account_head_code=certificat.account_head_code 
  WHERE  certificat.doc_reg_no=?  
  and certificat.details_id=? 
 and  certificat.ctype='E'
 AND feeitem.fee_param_type_id=2 
group by feeitem.fee_item_id,certificat.fee_amount,certificat.payment_accept_flag,certificat.issue_flag,certificat.token_no,certificat.doc_reg_no,certificat.payment_id,
certificat.online_payment_id
order by feeitem.fee_preference ASC
", array($reg_no, $detail_id));

        $paymentfields = $this->PaymentFields->find('list', array('fields' => array('field_name', 'field_name_desc_en'), 'order' => 'srno ASC'));
        if (!empty($feedetails)) {
            $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_$lang ,mode.verification_flag  FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.payment_id=?  ", array($feedetails[0][0]['payment_id']));
        }

        $this->set(compact('payment_mode_counter', 'payment_mode_online', 'token', 'feedetails', 'payment', 'lang', 'paymentfields'));
        $fieldlist['paymentGetPaymentDetailsCertifiedCopyForm'] = $this->PaymentFields->fieldlist();
        $this->set("fieldlistmultiform", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
        if ($this->request->is('post')) {
            $data = $this->request->data;

            if (isset($data['payment'])) {
                if ($data['payment']['pdate'] != '' || $data['payment']['pdate'] != NULL) {
                    $data['payment']['pdate'] = date('Y-m-d', strtotime($data['payment']['pdate']));
                }
                $this->check_csrf_token($this->request->data['payment']['csrftoken']);
                $data['payment']['token_no'] = $this->Session->read('certified_copy_token');
                $fieldlist_new = $this->PaymentFields->fieldlist($data['payment']['payment_mode_id']);
                $errors = $this->validatedata($data['payment'], $fieldlist_new);
                if ($this->ValidationError($errors)) {
                    if ($this->payment->save($data)) {
                        if (!isset($data['payment']['payment_id'])) {
                            $payment_id = $this->payment->getLastInsertID();
                            if (is_numeric($this->Session->read('details_id'))) {
                                $this->certificatesissuedetails->updateAll(
                                        array('payment_id' => $payment_id, 'account_head_code' => $data['payment']['account_head_code']), array('details_id' => $this->Session->read('details_id'))
                                );
                            }
                        }
                        $this->Session->setFlash('lblsavemsg');
                    }
                } else {
                    $this->Session->setFlash('lblnotsavemsg');
                }
                $this->redirect(array('controller' => 'Registration', 'action' => 'encum_cert_payment', $this->Session->read('reg_no'), $this->Session->read('details_id')));
            } elseif ($data['encum_cert_payment']) {
                $this->check_csrf_token($this->request->data['encum_cert_payment']['csrftoken']);
                $allrecordstatus = array();
//                pr($payment);
//                exit;
                if (isset($payment) && !empty($payment)) {
                    foreach ($payment as $single) {

                        if ($single[0]['verification_flag'] == 'Y' && $single[0]['defacement_flag'] == 'N') {
                            // Check this payment is deface with another Payment Id (Full Deface)
                            $check_deface = $this->payment->find('list', array('fields' => array('payment_id', 'defacement_flag'), 'conditions' => array('payment_id' => $single[0]['payment_id'], 'defacement_flag' => 'Y')));
                            if (empty($check_deface)) {
                                $deface_response = $this->payment_defacement($single[0]['payment_id']);
                            }

                            if ($deface_response['Status'] == 0) {
                                $allrecordstatus[$single[0]['payment_id']] = $deface_response['Error'];
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__("payment Not Accepted"));
                    $this->redirect('encumberance_cert');
                }

                if (!empty($allrecordstatus)) {
                    $message = "<ul>";
                    foreach ($allrecordstatus as $key => $error) {
                        $message .= "<li>[ Payment Id : " . $key . " ] " . $error . "</li>";
                    }
                    $message .= "</ul>";
                    $this->Session->setFlash($message);
                    $this->redirect('encum_cert_payment');
                } else {

                    if ($data['encum_cert_payment']['totalpaid'] < $data['encum_cert_payment']['tobepaid']) {
                        $this->Session->setFlash(__("Insufficient payment"));
                        $this->redirect('encum_cert_payment');
                    }
                    $this->certificatesissuedetails->updateAll(
                            array('payment_accept_flag' => "'Y'", 'payment_accept_date' => "'" . date('Y-m-d H:i:sa') . "'"), array('doc_reg_no' => $reg_no)
                    );
                    $this->Session->setFlash(__("payment Accepted"));
                    $this->redirect('encumberance_cert');
                }
            }
        }
    }

    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = NULL) {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mpdf');
//            $mpdf = new mPDF('utf-8', $page_size);
            $mpdf = new mPDF('utf-8', $page_size, '', '', '', '', 20, 15, 25, 25, 10, 10);
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            if ($waterMark) {
                $mpdf->SetWatermarkText($waterMark);
                $mpdf->watermarkTextAlpha = 0.2;
                $mpdf->showWatermarkText = true;
            }
            $mpdf->WriteHTML($html_design);
            $mpdf->Output($file_name . ".pdf", 'D');
        } catch (Exception $ex) {
            echo 'there is some error in creating PDF';
        }
    }

    public function gras_payment_verification($data = NULL) {
        $this->loadModel('GrasVerification');
        $this->loadModel('OnlinePayment');
        $this->loadModel('payment');
        $this->loadModel('external_interface');

        if ($data != NULL) {
            $userid = $this->Session->read("session_user_id");
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
                $checkexist = $this->OnlinePayment->find("all", array('conditions' => array('grn_no' => $online_data['grn_no'])));
                //pr($checkexist);exit;
                if (empty($checkexist)) {
                    $this->OnlinePayment->save($online_data);
                    $this->payment->saveAll($payment_entry_all);
                    $this->Session->setFlash(__('lblsavemsg'));
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
    }

    function payment_defacement($payment_id = NULL) {
        $token = $this->Session->read('reg_token');
        $user_id = $this->Session->read("citizen_user_id");
        array_map(array($this, 'loadModel'), array('OnlinePayment', 'payment', 'PaymentFields', 'external_interface', 'extinterfacefielddetails'));
        $deface_response = array('Status' => 1, 'Error' => '');
        if (is_numeric($payment_id)) {
            if ($token) {
                $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=? AND pay.payment_id=? ", array($token, $payment_id));
            } else {
                $payment = $this->payment->query("select pay.*,mode.payment_mode_desc_en FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.payment_id=? ", array($payment_id));
            }

            if (!empty($payment)) {
                $payment = $payment[0][0];
                $paymode = $payment['payment_mode_id'];
                if ($paymode == 1) { //GRAS (interface_id=2)
                    $deface_response = $this->gras_payment_defacement($payment);
                    return $deface_response;
                } else {
                    $deface_response = array('Status' => 0, 'Error' => 'Payment Mode Defacement Not Available');
                    return $deface_response;
                }
            } else {
                $deface_response = array('Status' => 0, 'Error' => 'Payment lblnotfoundmsg');
                return $deface_response;
            }
        } else {
            $deface_response = array('Status' => 0, 'Error' => 'Empty Payment ID');
            return $deface_response;
        }
        $deface_response = array('Status' => 0, 'Error' => 'Somethig Went Wrong');
        return $deface_response;
    }

    public function add_default_fields($request_data = NULL) {
        $user_id = $this->Auth->User("user_id");
        $stateid = $this->Auth->User("state_id");
        $office_id = $this->Auth->User("office_id");

        $request_data['state_id'] = $stateid;
        $request_data['office_id'] = $office_id;
        $request_data['req_ip'] = $this->request->clientIp();
        $request_data['user_type'] = $this->Session->read("session_usertype");
        $request_data['user_id'] = $user_id;

        return $request_data;
    }

    public function org_add_default_fields($request_data = NULL, $type = 'I') {
        $user_id = $this->Auth->User("user_id");
        $stateid = $this->Auth->User("state_id");
        $office_id = $this->Auth->User("office_id");

        $request_data['state_id'] = $stateid;
        $request_data['office_id'] = $office_id;
        $request_data['req_ip'] = $this->request->clientIp();
        $request_data['user_type'] = $this->Session->read("session_usertype");
        $request_data['org_user_id'] = $user_id;
        if ($type == 'I') {
            $request_data['org_created'] = date('Y-m-d H:i:s');
        } else {
            $request_data['org_updated'] = date('Y-m-d H:i:s');
        }

        return $request_data;
    }

    public function add_default_fields_updateAll($request_data = NULL) {
        $user_id = $this->Auth->User("user_id");
        $stateid = $this->Auth->User("state_id");
        $request_data['state_id'] = $stateid;
        $request_data['req_ip'] = "'" . $this->request->clientIp() . "'";
        $request_data['org_user_id'] = $user_id;
        $request_data['org_updated'] = "'" . date('Y-m-d H:i:s') . "'";
        return $request_data;
    }

    public function document_number($id = NULL) {
        try {
            $this->loadModel('DocumentNumber');

            $fieldlist['format_field_id']['text'] = 'is_required,is_integer';
            $fieldlist['format_field']['text'] = 'is_required,is_alpha';
            $fieldlist['format_field_desc']['text'] = 'is_required,is_alphanumericspace';
            $fieldlist['format_field_flag']['text'] = 'is_required,is_radioboxstring';
            $fieldlist['display_order']['text'] = 'is_digit';
            //$fieldset['static_value']['text']='is_digit';

            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('put') && isset($this->request->data['document_number'])) {
                if (!isset($this->request->data['document_number']['display_order'])) {
                    $this->request->data['document_number']['display_order'] = '';
                }
                if (!isset($this->request->data['document_number']['static_value'])) {
                    $this->request->data['document_number']['static_value'] = '';
                }
                $errors = $this->validatedata($this->request->data['document_number'], $fieldlist);
                if ($this->ValidationError($errors)) {
                    $this->check_csrf_token($this->request->data['document_number']['csrftoken']);
                    $req_id = $this->Session->read("number_format_id");
                    if ($req_id == $this->request->data['document_number']['format_field_id']) {
                        if ($this->DocumentNumber->Save($this->request->data['document_number'])) {
                            $this->Session->setFlash(__("lbleditmsg"));
                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_number'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__("Wrong Format id"));
                        $this->redirect(array('controller' => 'Registration', 'action' => 'document_number'));
                    }
                } else {
                    $this->Session->setFlash(__("Check Validation Errors"));
                    $this->set("errarr", $errors);
                }
            }
            $this->set_csrf_token();

            if ($id != NULL) {
                $this->Session->write("number_format_id", $id);
                $result = $this->DocumentNumber->find('first', array('conditions' => array('format_field_id' => $id)));
                $this->request->data['document_number'] = $result['DocumentNumber'];
            }
            //  document_number

            $this->set('docrecord', $this->DocumentNumber->find('all'));
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function lr_mutation() {
        try {
            $this->autoRender = false;
            array_map(array($this, 'loadModel'), array('external_interface', 'party_entry', 'lr_taluka_mapping', 'ApplicationSubmitted', 'parameter', 'property_details_entry', 'genernalinfoentry'));
            //$token = $this->Session->read('reg_token');
            $token = '9';
            $lang = $this->Session->read('sess_langauge');

            $party = $this->party_entry->get_party_record($token, $lang);
            $property_record = $this->property_details_entry->get_property_record($token, $lang);
            $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('token_no' => $token)));
            $submission = $this->ApplicationSubmitted->find("all", array('conditions' => array('token_no' => $token)));

            //for district and taluka 
            $taluka_dist = $this->lr_taluka_mapping->find('first', array('conditions' => array('lr_taluka_mapping.ngdrs_taluka_id' => $property_record[0][0]['taluka_id'], 'district_id' => $property_record[0][0]['district_id'])));

//for land type and census code
            $land_type = $this->parameter->get_land_type($property_record[0][0]['property_id']);
            $censuscode = '';
            $landtype = '';
            if ($land_type) {
                $censuscode = ($land_type[0][0]['census_code'] != '') ? ($land_type[0][0]['census_code']) : 0;
                $landtype = ($land_type[0][0]['developed_land_types_id'] != '') ? ($land_type[0][0]['developed_land_types_id']) : 0;
            }

            $doc_reg_no = ($submission[0]['ApplicationSubmitted']['doc_reg_no'] != '') ? ($submission[0]['ApplicationSubmitted']['doc_reg_no']) : 0;

//khata number,potkharaba,party area

            $khata_no_s = 0;
            $khata_no_p = 0;

            // for usage item list
            if (isset($property_record) && !empty($property_record)) {
                foreach ($property_record as $rec) {

                    $potkharaba = ($rec[0]['item_id'] == 44) ? ($rec[0]['item_value']) : 0;
                    $partyarea = ($rec[0]['item_id'] == 1) ? ($rec[0]['item_value']) : 0;
                    $vikriarea = ($rec[0]['item_id'] == 5) ? ($rec[0]['item_value']) : 0;
                    $vikripotkharabarea = ($rec[0]['item_id'] == 44) ? ($rec[0]['item_value']) : 0;


                    if ($rec[0]['parameter_type'] == 'S') {
                        $khata_no_s = ($rec[0]['paramter_id'] == 241) ? ($rec[0]['paramter_value']) : 0;
                    }
                    if ($rec[0]['parameter_type'] == 'P') {
                        $khata_no_p = ($rec[0]['paramter_id'] == 241) ? ($rec[0]['paramter_value']) : 0;
                    }
                }
                $property_no = ($property_record[0][0]['unique_property_no_' . $lang] != '') ? ($property_record[0][0]['unique_property_no_' . $lang]) : 0;
                $finyear = explode("-", $property_record[0][0]['finyear_desc']);
            }

            if (isset($party) && !empty($party)) {
                foreach ($party as $party1) {
                    if ($party1[0]['party_type_flag'] == 1) {
                        $sellerfname = $party1[0]['party_fname_' . $lang];
                        $sellermname = $party1[0]['party_mname_' . $lang];
                        $sellerlname = $party1[0]['party_lname_' . $lang];
                    }
                }
                $seller = array();
                $other_party = array();
                $purchaser = array();
                $property = array();
                $otheright = array();

                foreach ($party as $party1) {
                    if ($party1[0]['party_type_flag'] == 1) {
                        $seller = array(
                            'docnumber' => $token,
                            'fname' => $party1[0]['party_fname_' . $lang],
                            'mname' => $party1[0]['party_mname_' . $lang],
                            'lname' => $party1[0]['party_lname_' . $lang],
                            'address' => $party1[0]['village_name_' . $lang] . ',' . $party1[0]['taluka_name_' . $lang] . ',' . $party1[0]['district_name_' . $lang],
                            'khatano' => $khata_no_s,
                            'party_code' => $party1[0]['party_id'],
                            'partyarea' => $partyarea,
                            'vikriarea' => $vikriarea,
                            'partypotkharabarea' => $potkharaba,
                            'vikripotkharabarea' => $vikripotkharabarea,
                            'pins' => 0, // or send Null don't know about this field
                            'sellernewkhatano' => $khata_no_s,
                            'aname' => 0 // or send Null don't know about this field
                        );
                    } elseif ($party1[0]['party_type_flag'] == 0) {
                        $purchaser = array('docnumber' => $token,
                            'fname' => $party1[0]['party_fname_' . $lang],
                            'mname' => $party1[0]['party_mname_' . $lang],
                            'lname' => $party1[0]['party_lname_' . $lang],
                            'address' => $party1[0]['village_name_' . $lang] . ',' . $party1[0]['taluka_name_' . $lang] . ',' . $party1[0]['district_name_' . $lang],
                            'khatano' => $khata_no_p,
                            'party_code' => $party1[0]['party_id'],
                            'partyarea' => $partyarea,
                            'vikriarea' => $vikriarea,
                            'partypotkharabarea' => $potkharaba,
                            'vikripotkharabarea' => $vikripotkharabarea,
                            'pins' => 0, // or send Null don't know about this field
                            'sellerkhatano' => $khata_no_s,
                            'sellerfname' => $sellerfname,
                            'sellermname' => $sellermname,
                            'sellerlname' => $sellerlname,
                            'sellernewkhatano' => $khata_no_s,
                            'aname' => 0, // or send Null don't know about this field
                            'partytype' => $party1[0]['party_type_id']
                        );
                    } elseif ($party1[0]['party_type_flag'] == 2) {
                        $other_party = array('docnumber' => $token,
                            'fname' => $party1[0]['party_fname_' . $lang],
                            'mname' => $party1[0]['party_mname_' . $lang],
                            'lname' => $party1[0]['party_lname_' . $lang],
                            'address' => $party1[0]['village_name_' . $lang] . ',' . $party1[0]['taluka_name_' . $lang] . ',' . $party1[0]['district_name_' . $lang],
                            'khatano' => $khata_no_s,
                            'party_code' => $party1[0]['party_id'],
                            'partyarea' => $partyarea,
                            'vikriarea' => $vikriarea,
                            'partypotkharabarea' => $potkharaba,
                            'vikripotkharabarea' => $vikripotkharabarea,
                            'pins' => 0, // or send Null don't know about this field
                            'sellernewkhatano' => $khata_no_s,
                            'aname' => 0 // or send Null don't know about this field);
                        );
                    }

                    $property = array('docnumber' => $token,
                        'documentyear' => $finyear[0],
                        'article_code' => $gen_info[0]['genernalinfoentry']['article_id'],
                        'censuscode' => $censuscode,
                        'talukacode' => $taluka_dist['lr_taluka_mapping']['igr_district_id'],
                        'districcode' => $taluka_dist['lr_taluka_mapping']['igr_taluka_id'],
                        'urbanrural' => $landtype,
                        'property_number' => $property_no,
                        'area' => $partyarea,
                        'parea' => $potkharaba,
                        'stamp5datetime' => $submission[0]['ApplicationSubmitted']['stamp5_date'],
                        'attribute_code' => 0, // or send Null don't know about this field
                        'amount' => $property_record[0][0]['rounded_val_amt'],
                        'srocode' => $this->Auth->User('user_id'),
                        'internaldocumentnumber' => $token,
                        'strregdate' => date('Y-m-d H:i:s', strtotime($submission[0]['ApplicationSubmitted']['doc_reg_date'])),
                        'rejectflag' => 0, // or send Null don't know about this field
                        'rejectremark' => 0 // or send Null don't know about this field);
                    );

                    $otheright = array('attribute_code' => 0, // or send Null don't know about this field
                        'property_number' => $property_no,
                        'area' => $partyarea,
                        'parea' => $potkharaba,
                        'unity_type' => 0, // or send Null don't know about this field
                        'itarhakk' => 0 // or send Null don't know about this field
                    );


                    $my_array = array(
                        'Seller' => $seller,
                        'Purchaser' => $purchaser,
                        'Property' => $property,
                        'Otherparty' => $other_party,
                        'Otherright' => $otheright
                    );


// create new instance of simplexml
                    $xml = new SimpleXMLElement('<root/>');
// function callback
                    $this->array2XML($xml, $my_array);
                    $xmlString = $xml->asXML();

                    $interface = $this->external_interface->find('all', array('conditions' => array(
                            'external_interface.interface_id ' => 4)));
                    $ch = curl_init($interface[0]['external_interface']['interface_url']);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Content-Length: ' . strlen($xmlString))
                    );
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
                    $result1 = curl_exec($ch);
                }
            }
            pr($result1);

            exit;
            return true;
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    function array2XML($obj, $array) {
        foreach ($array as $key => $value) {
            if (is_numeric($key))
                $key = 'item' . $key;

            if (is_array($value)) {
                $node = $obj->addChild($key);
                $this->array2XML($node, $value);
            } else {
                $obj->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function genernal_info() {
        $this->Session->write("Selectedtoken", NULL);
        $this->Session->write("manual_flag", 'N');
        $this->Session->write("citizen_user_id", $this->Auth->User("user_id"));
        return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info'));
    }

    public function genernalinfoentry() {
        $this->Session->write("Selectedtoken", NULL);
        $this->Session->write("manual_flag", 'N');
        $this->Session->write("citizen_user_id", $this->Auth->User("user_id"));
        return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info'));
    }

    public function printgeneralinfo($token = NULL) {

        if (!is_numeric($token)) {
            $this->Session->setFlash('Invalid Token');
            return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
        }

        try {

            $this->loadModel('mainlanguage');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('TrnBehavioralPatterns');
            $this->loadModel('party_entry');
            $this->loadModel('identification');
            $this->loadModel('conf_reg_bool_info');
            $this->loadModel('DataEntryReject');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('witness');
            $this->loadModel('RevertBackReasons');
            $this->loadModel('appointment');
            $this->loadModel('uploaded_file_trn');
            $this->loadModel('SroChecklistDetails');

            //========checklist====================================================================================================================================
            //$token = $this->Session->read("reg_token");
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read("sess_langauge");

            $Srochecklist = $this->SroChecklistDetails->query("SELECT checklist.checklist_id, checklist.checklist_desc_$lang, details.checklist_flag FROM ngdrstab_mst_sro_checklist AS checklist LEFT JOIN ngdrstab_trn_sro_checklist_details  AS details    ON details.checklist_id = checklist.checklist_id   AND    details.token_no=?", array($token));
            $regconfchecklist = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 108, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $stampconfig = $this->stamp_and_functions_config();
            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($token, $office_id)));
                $this->set(compact('lang', 'token', 'Srochecklist', 'stampconfig', 'regconfchecklist'));
            }
            //===============================================================================================================================================================================================
            $lang = $this->Session->read('sess_langauge');
            $doclang = $this->Session->read('doc_lang');
            $this->set('lang', $lang);
            $this->set('token', $token);

            $application = $this->ApplicationSubmitted->application_document($token, $doclang);
            // pr($application);exit;
            if (empty($application)) {
                $this->Session->setFlash('Invalid Token');
                return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
            } else if ($application[0][0]['check_in_flag'] == 'Y') {
                $this->Session->setFlash('Invalid Token: Document already check in!');
                return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
            }
            $document_list = $this->uploaded_file_trn->find('all', array('fields' => array('document.document_id', 'document.document_name_' . $lang, 'uploaded_file_trn.document_id', 'uploaded_file_trn.up_id', 'uploaded_file_trn.out_fname'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_upload_document', 'alias' => 'document', 'conditions' => array('document.document_id=uploaded_file_trn.document_id'))
                ),
                'conditions' => array('token_no' => $token),
                'order' => 'document.document_name_en,uploaded_file_trn.up_id'
            ));
            $this->set(compact('document_list'));

            $inspectionflag = 0;
            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 40)));
            if (!empty($regconf)) {
                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                    $inspectionflag = 1;
                }
            }
            if ($inspectionflag == 1) {
                $istatus = $this->inspection_status($token);
                if ($istatus == 1) {
                    $this->Session->setFlash('Inspection Pending');
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
                }
            }

            $fieldlistmultiform['dataentryaccept']['revertback_id']['select'] = 'is_select_req';
            $fieldlistmultiform['dataentryaccept']['document_entry_remark']['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
            $fieldlistmultiform['dataentryaccept']['csrftoken']['text'] = 'is_required';
            foreach ($Srochecklist as $checklist) {
                $fieldlistmultiform['final_stamp']['checklist' . $checklist[0]['checklist_id']]['checkbox'] = 'is_required';
            }
            $this->set("fieldlistmultiform", $fieldlistmultiform);
            $this->set('result_codes', $this->getvalidationruleset($fieldlistmultiform, TRUE));


            if ($this->request->is("post") && isset($this->request->data['document_entry'])) {
                $errors = $this->validatedata($this->request->data['document_entry'], $fieldlistmultiform['dataentryaccept']);
                if ($this->ValidationError($errors)) {
                    $this->check_csrf_token($this->request->data['document_entry']['csrftoken']);
                    $this->request->data['document_entry'] = $this->add_default_fields($this->request->data['document_entry']);

                    $app = $this->ApplicationSubmitted->find('first', array('recursive' => -1, 'fields' => array('app_id'), 'conditions' => array('token_no' => $token)));
                    if (!empty($app)) {
                        $this->request->data['document_entry']['app_id'] = $app['ApplicationSubmitted']['app_id'];
                        $this->request->data['document_entry']['token_no'] = $token;
                        if ($this->DataEntryReject->save($this->request->data['document_entry'])) {
                            $changestatus['last_status_id'] = 1;
                            $changestatus = $this->add_default_fields_updateAll($changestatus);
                            $this->genernalinfoentry->updateAll(
                                    $changestatus, array('token_no' => $token)
                            );

                            // by madhuri
                            $office_id = $this->Auth->User("office_id");
                            $this->save_documentstatus(5, $token, $office_id);


                            $this->ApplicationSubmitted->deleteAll(array('token_no' => $token));
                            $this->appointment->deleteAll(array('token_no' => $token));
                            $this->Session->setFlash('Application Reverted!');
                            return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
                        } else {
                            $this->Session->setFlash('Fail To Save Data!');
                            return $this->redirect(array('controller' => 'Registration', 'action' => 'printgeneralinfo', $token));
                        }
                    } else {
                        $this->Session->setFlash('Application not Found!');
                        return $this->redirect(array('controller' => 'Registration', 'action' => 'documentindex'));
                    }
                } else {
                    $this->set("errarr", $errors);
                    $this->Session->setFlash('Validation Errors');
                }
            }


//================================checklist===============================================================================================================================================================================================================          
            if ($this->request->is('post') && isset($this->request->data['final_stamp'])) {

                $data = $this->request->data;

                $this->check_csrf_token(@$data['csrftoken']);

                foreach ($Srochecklist as $checklist) {
                    if (isset($this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']]) && $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']] == $checklist[0]['checklist_id']) {

                        $requestdata['checklist' . $checklist[0]['checklist_id']] = $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']];
                    } else {

                        $requestdata['checklist' . $checklist[0]['checklist_id']] = '';
                    }
                }

                $verrors = $this->validatedata($requestdata, $fieldlistmultiform['final_stamp']);
                $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id ,info.article_id ,presentation_date,exec_date FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND app.office_id=? AND app.token_no=? ", array($office_id, $token));
                $this->Session->write("reg_record_no", $application[0][0]['app_id']);
                $this->Session->write("reg_token", $token);
                if ($this->ValidationError($verrors)) {
                    $checklist = array();
                    $checklist = $fieldlistmultiform['final_stamp'];
                    $this->SroChecklistDetails->deleteAll(array('token_no' => $token));
                    foreach ($Srochecklist as $checklist) {
                        $savechecklist['token_no'] = $token;
                        $savechecklist['checklist_id'] = $this->request->data['final_stamp']['checklist' . $checklist[0]['checklist_id']];
                        $savechecklist['checklist_flag'] = 'Y';
                        $this->SroChecklistDetails->saveAll($savechecklist);
                    }

                    $this->Session->setFlash(__("Document Accepted!"));
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'document_checkin', $token));
                }
            }
            //================================checklist===============================================================================================================================================================================================================          

            $result = $this->genernalinfoentry->query("select g.sro_remark,g.no_of_pages,g.exec_date,g.ref_doc_no,g.ref_doc_date,a.article_desc_$lang,l.language_name,g.local_language_id,d.execution_type_$lang,t.articledescription_$lang
                                        from ngdrstab_trn_generalinformation g
                                        left outer join ngdrstab_mst_language l on l.id=g.local_language_id
                                        left outer join ngdrstab_mst_article a on a.article_id=g.article_id
                                        left outer join ngdrstab_mst_document_execution_type d on d.id=g.doc_execution_type_id
                                        left outer join ngdrstab_mst_articledescriptiondetail t on t.articledescription_id=g.title_id
                                        where g.token_no=?", array($token));
            $this->set('result', $result);
            $languagecode = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'conditions' => array('id' => $result[0][0]['local_language_id'])));

            $doc_lang = $languagecode['0']['mainlanguage']['language_code'];
            if ($doc_lang != 'en') {
                $doc_lang = 'll';
            }
            $this->set('doc_lang', $doc_lang);
            $valid = $this->genernalinfoentry->query("select val_id,property_id from ngdrstab_trn_property_details_entry where token_no=? ", array($token));
            $this->set('valid', $valid);
//            if (isset($valid[0][0]['val_id'])) {
//                $this->set('valid', $valid[0][0]['val_id']);                
//            }
            if ($valid != null) {
                $tmp_property_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $valid[0][0]['property_id'], $token, '1', $doc_lang);
                $this->set('prop_add', $tmp_property_address);

                $attributes = $this->TrnBehavioralPatterns->query("select t.paramter_value,t.paramter_value1,t.paramter_value2,p.eri_attribute_name_$lang 
                                                    from ngdrstab_trn_parameter t
                                                    inner join ngdrstab_mst_attribute_parameter p on p.attribute_id=t.paramter_id
                                                    where t.property_id=?", array($valid[0][0]['property_id']));
                $this->set('attributes', $attributes);
            }
            $partyid = $this->genernalinfoentry->query("select party_id from ngdrstab_trn_party_entry_new where token_no=? and (id=repeat_party_id or repeat_party_id IS NULL) ", array($token));
            if ($partyid != null) {
                $party_id = array();
                foreach ($partyid as $key => $value) {
                    array_push($party_id, $value[0]['party_id']);
                }

                $party_record = $this->genernalinfoentry->query("select  a.*,cst.category_name_$lang as caste_name,a.property_id, a.party_fname_$doc_lang,b.party_type_desc_$lang,c.category_name_$lang ,
                                d.salutation_desc_$lang,e.desc_$lang,f.identificationtype_desc_$lang as idntity,h.gender_desc_$lang,i.occupation_name_$lang,
                                j.district_name_$lang,k.taluka_name_$lang,l.village_name_$lang
                                from ngdrstab_trn_party_entry_new a
                                left outer join ngdrstab_mst_party_type b on b.party_type_id = a.party_type_id
                                left outer join ngdrstab_mst_caste_category cst on cst.category_id = a.cast_id
                                left outer join ngdrstab_mst_party_category c on c.category_id = a.party_catg_id
                                left outer join ngdrstab_mst_salutation d on d.id = a.salutation_id
                                left outer join ngdrstab_mst_presentation_exemption e on e.exemption_id = a.exemption_id
                                left outer join ngdrstab_mst_identificationtype f on f.identificationtype_id = a.identificationtype_id
                                left outer join ngdrstab_mst_gender h on h.id = a.gender_id 
                                left outer join ngdrstab_mst_occupation i on i.id = a.occupation_id
                                left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                                left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                                left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                                where a.party_id in (" . implode(',', $party_id) . ") order by a.party_type_id ASC");
                $this->set('party_record', $party_record);

                $pattern = $this->TrnBehavioralPatterns->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_en', 'pattern.pattern_desc_ll', 'pattern.field_id', 'TrnBehavioralPatterns.field_value_en', 'TrnBehavioralPatterns.field_value_ll', 'TrnBehavioralPatterns.mapping_ref_val'),
                    'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $party_id, 'TrnBehavioralPatterns.token_no' => $token, 'TrnBehavioralPatterns.mapping_ref_id' => 2), // for property:mapping_ref_id => 1
                    'joins' => array(
                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                    ),
                    'order' => 'pattern.field_id ASC'
                ));
                $this->set('pattern_data', $pattern);
            }
            $identifier_detail = $this->identification->get_identification($lang, $token);

            if ($identifier_detail != null) {
                $this->set('identifier', $identifier_detail);
                $identification_id = array();
                foreach ($identifier_detail as $key => $value) {
                    array_push($identification_id, $value['identification']['identification_id']);
                }
                $tmp_identifier_address = $this->TrnBehavioralPatterns->get_pattern_detail($doc_lang, $identification_id, $token, '4');
                $this->set('identifier_add', $tmp_identifier_address);
            }

            $witness = $this->witness->get_witness($lang, $token);

            if ($witness != null) {
                $witness_id = array();
                foreach ($witness as $key => $value) {
                    array_push($witness_id, $value['witness']['witness_id']);
                }
                $tmp_witness_address = $this->TrnBehavioralPatterns->get_pattern_detail($doc_lang, $witness_id, $token, '5');
                $this->set('witness_add', $tmp_witness_address);
            }

            $this->set('witness', $witness);
            $Reasons = $this->RevertBackReasons->find("list", array('fields' => array('revertback_id', 'revertback_desc_' . $lang)));
            $this->set('Reasons', $Reasons);
            $this->set_csrf_token();
//               pr($tmp_identifier_address);exit;
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    function simple_reciept() {
        try {

            array_map(array($this, 'loadModel'), array('payment', 'payment_mode', 'bank_master', 'ApplicationSubmitted', 'PaymentFields', 'article_fee_items', 'CitizenPaymentEntry'));
            $lang = $this->Session->read("sess_langauge");
            $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
            $this->set(compact('payment_mode_counter', 'lang'));

            $fieldlist = array();

            $fieldlist['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['payment_mode_id']['select'] = 'is_select_req';

            $paymentfields = $this->PaymentFields->fieldlist();
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            if ($this->request->is('post')) {

                $fieldlistnew = $this->modifyfieldlistdependentsr($fieldlist, $this->request->data['simple_reciept']); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
                $errarr = $this->validatedata($this->request->data['inspection_search'], $fieldlistnew);

                $request_data = $this->request->data['simple_reciept'];
                if (isset($request_data['csrftoken'])) {
                    $csrftoken = $request_data['csrftoken'];
                } else {
                    $csrftoken = NULL;
                }
                //$this->check_csrf_token($csrftoken);
                if (isset($this->request->data['simple_reciept']['pdate'])) {
                    $request_data['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['simple_reciept']['pdate'])));
                }
                if (isset($this->request->data['simple_reciept']['estamp_issue_date'])) {
                    $request_data['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['simple_reciept']['estamp_issue_date'])));
                }

                /*  If Counter Payment ( Save Data into Table) */
                if (isset($payment_mode_counter[$request_data['payment_mode_id']])) {
                    $request_data = $this->add_default_fields($request_data);
                    $request_data['simple_receipt_flag'] = 'Y';
                    $request_data['office_id'] = $this->Auth->user('office_id');

                    if ($this->payment->Save($request_data)) {
                        $this->Session->setFlash(__("lblsavemsg"));
                    } else {
                        $this->Session->setFlash(__("lblnotsavemsg"));
                    }
                } else {
                    $this->Session->setFlash(__("Counter Payment Mode Not Found!"));
                }

                return $this->redirect('simple_reciept');
            }
            $office_id = $this->Auth->user('office_id');
            $paymentdetails = $this->payment->query("SELECT  payment.*, mode.* ,head.* FROM  ngdrstab_trn_payment_details payment 
                LEFT join    ngdrstab_mst_payment_mode mode ON  mode.payment_mode_id=payment.payment_mode_id
                 LEFT join    ngdrstab_mst_article_fee_items head ON  head.account_head_code=payment.account_head_code
                
                WHERE  payment.simple_receipt_flag=? AND payment.office_id=? ", array('Y', $office_id));

            $this->set("paymentdetails", $paymentdetails);

            $this->set_csrf_token();
        } catch (Exception $ex) {
            //  pr($ex);
            //  exit;
        }
    }

    public function modifyfieldlistdependentsr($fieldlist, $data) {

        if (!empty($data['payment_mode_id'])) {
            unset($fieldlist['certificate_no']);
            unset($fieldlist['pamount']);
            unset($fieldlist['pdate']);
            unset($fieldlist['account_head_code']);

            unset($fieldlist['bank_id']);
            unset($fieldlist['branch_id']);
            unset($fieldlist['ifsc_code']);
        }


        return $fieldlist;
    }

    function simple_reciept_print($payment_id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('payment', 'payment_mode', 'bank_master', 'ApplicationSubmitted', 'PaymentFields', 'article_fee_items', 'CitizenPaymentEntry'));
            $lang = $this->Session->read("sess_langauge");
            $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
            $this->set(compact('payment_mode_counter', 'lang'));
            $office_id = $this->Auth->user('office_id');

            if (!is_null($payment_id)) {

                $paymentdetails = $this->payment->query("SELECT  payment.*, mode.* ,head.* FROM  ngdrstab_trn_payment_details payment 
                LEFT join    ngdrstab_mst_payment_mode mode ON  mode.payment_mode_id=payment.payment_mode_id
                 LEFT join    ngdrstab_mst_article_fee_items head ON  head.account_head_code=payment.account_head_code
                
                WHERE  payment.simple_receipt_flag=? AND payment.office_id=? ", array('Y', $office_id));
                if (!empty($paymentdetails)) {
                    $paydetails = $paymentdetails[0][0];

                    $html = "<div width=100% style='padding: 2cm 4cm 3cm 4cm;'><table border=1 style='border-collapse: collapse; width:80%;'>
    <thead >  
        <tr>  
            <th class='center' colspan=2>" . __('lblreceipt') . "</th> 
</tr>
    </thead>
    <tbody> 
     <tr> <th class='center'>" . __('lblreceiptno') . "</th><td class='tblbigdata'>" . $paydetails['payment_id'] . "</td> </tr> 
        <tr> <th class='center'>" . __('lblpaymode') . "</th><td class='tblbigdata'>" . $paydetails['payment_mode_desc_' . $lang] . "</td> </tr> 
           <tr> <th class='center'>" . __('lblpaymenthead') . "</th> <td class='tblbigdata'>" . $paydetails['fee_item_desc_' . $lang] . "</td>      </tr>                           
           <tr> <th class='center'>" . __('lbldocno') . "</th> <td class='tblbigdata'>" . $paydetails['doc_reg_no'] . "</td></tr> 
            <tr><th class='center'>" . __('lbldepamt') . " </th><td class='tblbigdata'>" . $paydetails['pamount'] . "</td></tr> 
            
    </tbody>
</table></center> ";
                } else {
                    $html = "lblnotfoundmsg";
                }
            } else {
                $html = "lblnotfoundmsg";
            }
            $file_name = "Receipt_payid" . $payment_id;
            $this->create_pdf($html, $file_name);
        } catch (Exception $ex) {
            //pr($ex);
            // exit;
        }
    }

    function get_payment_details_simple_reciept() { // used for Receipt
        try {
            array_map(array($this, 'loadModel'), array('payment', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read("doc_lang");
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->payment->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'], 'token_no' => $token)));
                $this->set("payment", $payment);
                // pr($payment);
                //exit;
            }
            if (isset($paymentfields)) {
                foreach ($paymentfields as $field) {
                    if ($field['PaymentFields']['field_name'] == 'bank_id') {
                        $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                        $this->set("bank_master", $bank_master);
                        if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                            $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                        } else {
                            $branch_master = array();
                        }
                        $this->set("branch_master", $branch_master);
                    }
                    if ($field['PaymentFields']['field_name'] == 'cos_id') {
                        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('hierarchy_id' => 45)));
                        $this->set("office", $office);
                    }
                }
            }
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => array(2)), 'fields' => array('account_head_code', 'fee_item_desc_' . $lang)));
            $this->set(compact('accounthead', 'doc_lang', 'lang'));
        } catch (Exception $e) {
            // pr($e);
            //exit;
        }
    }

    function payment_remove($id = NULL, $csrftoken = NULL) {
        try {
            $this->check_csrf_token($csrftoken);
            array_map(array($this, 'loadModel'), array('payment', 'ReceiptCounter'));
            $token = $this->Session->read('Selectedtoken');
            if (is_numeric($id)) {
                if ($this->payment->deleteAll(array('payment_id' => $id, 'token_no' => $token, 'record_lock' => 'N'))) {
                    if ($this->payment->getAffectedRows()) {
                        $this->ReceiptCounter->deleteAll(array('token_no' => $token, 'payment_id' => $id));
                        $this->Session->setFlash(__("lbldeletemsg"));
                    } else {
                        $this->Session->setFlash(__("lblnotdeletemsg"));
                    }
                }
            }
            return $this->redirect($this->referer());
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function inspection_search($id = NULL) {
        try {

            array_map(array($this, 'loadModel'), array('payment_mode', 'PaymentFields', 'bank_master', 'PaymentPreference', 'inspection_payment', 'article_fee_items', 'ReceiptCounter'));
            $lang = $this->Session->read("sess_langauge");
            $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
            $this->set(compact('payment_mode_counter', 'lang'));
            $user_id = $this->Session->read("citizen_user_id");
            $office_user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $office_id = $this->Auth->User("office_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read("doc_lang");
            $this->set("doc_lang", $this->Session->read('doc_lang'));
            $this->set("printflag", 'N');
            $this->set("hfid", NULL);
            $token = $this->Session->read('Selectedtoken');
            if (is_numeric($id)) {
                if ($this->inspection_payment->deleteAll(array('payment_id' => $id, 'token_no' => $token))) {
                    $this->ReceiptCounter->deleteAll(array('token_no' => $token, 'payment_id' => $id));
                    $this->Session->setFlash(__("lbldeletemsg"));
                }
                return $this->redirect($this->referer());
            }
            $result = $this->inspection_payment->query("select a.payment_id,a.presenter_name_en,a.advocate_name_en,a.address,a.pdate,a.tdate,a.fdate,a.pamount,b.payment_mode_desc_en
                                    from ngdrstab_trn_inspection_payment a
                                    inner join ngdrstab_mst_payment_mode b on b.payment_mode_id=a.payment_mode_id");
            $this->set("result", $result);

            $fieldlist = array();

            $fieldlist['presenter_name_en']['text'] = 'is_required,is_alpha,is_maxlength255';
            $fieldlist['advocate_name_en']['text'] = 'is_required,is_alpha,is_maxlength255';
            $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
            $fieldlist['fdate']['text'] = 'is_required';
            $fieldlist['tdate']['text'] = 'is_required';
            $fieldlist['paymentmode_selection']['select'] = 'is_select_req';
//            $fieldlist['level1_id']['select'] = 'is_select_req';
//            $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
            $paymentfields = $this->PaymentFields->fieldlist();
            $fieldlist1 = array_merge($fieldlist, $paymentfields);

            $this->set('fieldlist', $fieldlist1);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $request_data = $this->request->data['inspection_search'];
                $paymentfields = $this->PaymentFields->fieldlist($request_data['paymentmode_id']);
                $fieldlist1 = array_merge($fieldlist, $paymentfields);
                $fieldlistnew = $this->modifyfieldlistdependent($fieldlist1, $this->request->data['inspection_search']); //UNCOMMENT AFTER FUNCTIONAL ISSUE SOLVED
                $errarr = $this->validatedata($this->request->data['inspection_search'], $fieldlistnew);

//               
                //$this->check_csrf_token($this->request->data['payment']['csrftoken']);
                if (isset($this->request->data['inspection_search']['docno'])) {
                    $this->request->data['inspection_search']['token_no'] = $this->request->data['inspection_search']['docno'];
                } else {
                    $this->request->data['inspection_search']['token_no'] = $token;
                }

                if (isset($this->request->data['inspection_search']['pdate'])) {
                    $this->request->data['inspection_search']['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['inspection_search']['pdate'])));
                }
                if (isset($this->request->data['inspection_search']['estamp_issue_date'])) {
                    $this->request->data['inspection_search']['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['inspection_search']['estamp_issue_date'])));
                }

                $request_data = $this->request->data['inspection_search'];


                if ($request_data['payment_mode_id'] == 8 || $request_data['payment_mode_id'] == 3) {
                    $request_data = $this->add_default_fields($request_data);
                    if ($this->inspection_payment->Save($request_data)) {

                        $last_payment_id = $this->inspection_payment->getLastInsertId();
                        if (is_numeric($last_payment_id)) {
                            $receptdata['token_no'] = $token;
                            $receptdata['office_id'] = $office_id;
                            $receptdata['user_id'] = $office_user_id;
                            $receptdata['req_ip'] = $_SERVER['REMOTE_ADDR'];
                            $receptdata['state_id'] = $stateid;
                            $receptdata['payment_id'] = $this->inspection_payment->getLastInsertId();
                            $this->ReceiptCounter->save($receptdata);
                            $this->set("printflag", 'Y');
                            $this->Session->setFlash(__("lblsavemsg"));

//                            
                        } else {
                            $this->Session->setFlash(__("lbleditmsg"));
                        }
                    } else {
                        $this->Session->setFlash(__('lblnotsavemsg'));
                    }
                } else if ($request_data['payment_mode_id'] == 1) {
                    $verify_status = $this->gras_payment_verification($request_data);
                } else {
                    $this->Session->setFlash(__("Payment Mode Not Found!"));
                }

                return $this->redirect($this->referer());
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    function inspection_search_print($payment_id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('inspection_payment'));
            $lang = $this->Session->read("sess_langauge");
            $html = '';
            $rpt_title = "Inspection Payment Receipt";


            $result = $this->inspection_payment->query("select a.presenter_name_$lang,a.advocate_name_$lang,a.address,a.pdate,a.tdate,a.fdate,a.pamount,b.payment_mode_desc_$lang
                                    from ngdrstab_trn_inspection_payment a
                                    inner join ngdrstab_mst_payment_mode b on b.payment_mode_id=a.payment_mode_id
                                    where a.id=?", array($payment_id));
//                              pr($result);exit;
            if ($result) {

                $html_body = "<style>table{border-collapse:collapse;background-color:#F0E0E0;} td,th{padding:15px;}</style>"
                        . "<table border=1 align=center width=80%>"
                        . "<tr><td><b>Presenter Name</b></td><td align=left><b>" . $result[0][0]['presenter_name_' . $lang] . "</b> </td>"
                        . "<td ><b>Advocate Name</b></td><td align=left><b>" . $result[0][0]['advocate_name_' . $lang] . "</b> </td></tr>"
                        . "<tr><td><b>Address</b></td><td align=left><b>" . $result[0][0]['address'] . "</b> </td></tr>"
                        . "<tr><td><b>From Date</b></td><td align=left><b>" . $result[0][0]['fdate'] . "</b> </td>"
                        . "<td><b>To Date</b></td><td align=left><b>" . $result[0][0]['tdate'] . "</b> </td></tr>"
                        . "<tr><td><b>Payment Mode</b></td><td align=left><b>" . $result[0][0]['payment_mode_desc_' . $lang] . "</b> </td>"
                        . "<td><b>Payment Date</b></td><td align=left><b>" . $result[0][0]['pdate'] . "</b> </td></tr>"
                        . "<tr><td><b>Amount</b></td><td align=left><b>" . $result[0][0]['pamount'] . "</b> </td></tr>"
                        . "</table>";
                $html = "<style>th{font-size:16px;} td{font-size:16px;align:center; padding:15px;}</style>"
                        . "<html><body>"
                        . "<h1 align=center  style=background-color:#F0E0F0;>" . $rpt_title . "</h1>"
                        . "<h3 align='center' style=background-color:#E0E0F0;>Receipt Date:" . date('d-m-Y') . "</h6>";
                $html .= $html_body;
                $html .= "</body></html>";

                if ($html) {
//                                        pr($html);exit;
                    $this->create_pdf($html, $rpt_title, 'A4-L', '');
                }
            }
        } catch (Exception $ex) {
            //pr($ex);
            // exit;
        }
    }

    public function modifyfieldlistdependent($fieldlist, $data) {
//pr($data);
        //   pr($fieldlist);
        if (isset($data['ctype']) && $data['ctype'] == 'N') {
            unset($fieldlist['uniq_prop_id']);
            unset($fieldlist['survey_no']);
        }

        return $fieldlist;
    }

    function get_payment_details2() { // used for Receipt
        try {
            array_map(array($this, 'loadModel'), array('payment', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read("doc_lang");
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->payment->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'], 'token_no' => $token)));
                $this->set("payment", $payment);
                pr($payment);
                exit;
            }
            if (isset($paymentfields)) {
                foreach ($paymentfields as $field) {
                    if ($field['PaymentFields']['field_name'] == 'bank_id') {
                        $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                        $this->set("bank_master", $bank_master);
                        if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                            $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                        } else {
                            $branch_master = array();
                        }
                        $this->set("branch_master", $branch_master);
                    }
                    if ($field['PaymentFields']['field_name'] == 'cos_id') {
                        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('hierarchy_id' => 45)));
                        $this->set("office", $office);
                    }
                }
            }
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => array(2)), 'fields' => array('account_head_code', 'fee_item_desc_' . $lang)));
            $this->set(compact('accounthead', 'doc_lang', 'lang'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function display_pro_list() {
        try {
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                $lang = $this->Session->read("sess_langauge");
                $this->loadModel("certificatesissuedetails");
                $this->loadModel("property_details_entry");
                $details = $this->certificatesissuedetails->find('first', array('conditions' => array('details_id' => $_POST['id'])));

                if ($details) {
                    $result = $this->property_details_entry->property_list_forcertificates($details['certificatesissuedetails']['uniq_prop_id'], $details['certificatesissuedetails']['survey_no'], $lang);

                    $pattern = $this->property_details_entry->get_property_forcertificates($lang, $details['certificatesissuedetails']['uniq_prop_id']);

                    $this->set('result', $result);
                    $this->set('patterns', $pattern);
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function charge_hand_over($hand_over_id = NULL) {
        array_map(array($this, 'loadModel'), array('office', 'User', 'ChargeHandOver', 'ChargeHandOverDetails'));
        $lang = $this->Session->read("sess_langauge");
        $office = $this->office->find("list", array('fields' => array('office_id', 'office_name_' . $lang)));

        $fieldlist = $this->ChargeHandOver->fieldlist();
        $result_codes = $this->getvalidationruleset($fieldlist);
        $ChargeHandOver = $this->ChargeHandOver->Charge_hand_over_list($lang);
        if ($this->request->is('post') && isset($this->request->data['charge_hand_over'])) {
            $data = $this->request->data['charge_hand_over'];
            $errors = $this->validatedata($data, $fieldlist);
            if ($this->ValidationError($errors)) {
                $this->check_csrf_token($data['csrftoken']);
                $data['from_date'] = date("Y-m-d", strtotime($data['from_date']));
                $data['to_date'] = date("Y-m-d", strtotime($data['to_date']));
                $data = $this->add_default_fields($data);
                $datanew = $this->add_default_fields($data);
                if ($this->ChargeHandOver->Save($data)) {
                    $last_id = $this->ChargeHandOver->getLastInsertId();
                    $date = strtotime("+1 day", strtotime($data['to_date'])); // increment one day
                    $data['to_date'] = date("Y-m-d", $date);

                    $begin = new DateTime($data['from_date']);
                    $end = new DateTime($data['to_date']);
                    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

                    foreach ($daterange as $date) {
                        $datanew['hand_over_id'] = $last_id;
                        $datanew['single_date'] = $date->format("Y-m-d");
                        $this->ChargeHandOverDetails->create();
                        $this->ChargeHandOverDetails->Save($datanew);
                    }

                    $this->Session->setFlash(__('lblsavemsg'));
                    return $this->redirect('charge_hand_over');
                } else {
                    $this->Session->setFlash(__('lblnotsavemsg'));
                }
            } else {
                $this->Session->setFlash(__('Please Find Validation Errors'));
            }
        }
        if ($this->request->is('post') && isset($this->request->data['charge_hand_over_search'])) {
            $data = $this->request->data['charge_hand_over_search'];
            $data['curr_date'] = date("Y-m-d", strtotime($data['curr_date']));
            $ChargeHandOver = $this->ChargeHandOver->Charge_hand_over_list($lang, $data);
        }
        if (is_numeric($hand_over_id)) {
            if ($this->ChargeHandOver->delete($hand_over_id)) {
                $this->Session->setFlash(__('lbldeletemsg'));
            } else {
                $this->Session->setFlash(__('lblnotdeletemsg'));
            }
            return $this->redirect('charge_hand_over');
        }


        $this->set(compact('office', 'lang', 'result_codes', 'fieldlist', 'ChargeHandOver'));
        $this->set_csrf_token();
    }

    public function office_user_list() {
        array_map(array($this, 'loadModel'), array('User'));
        $this->autoRender = FALSE;
        $userlist = array();
        if (is_numeric($this->request->data['office_id']))
            ;
        {

            $userlist = $this->User->find("list", array('fields' => array('user_id', 'full_name'), 'conditions' => array('office_id' => $this->request->data['office_id'])));
        }
        return json_encode($userlist, TRUE);
    }

    function reg_screen_mapping() {
        try {
            $this->loadModel('RegistrationSubsubmenu');
            $this->loadModel('article');
            $this->loadModel('RegistrationScreenMapping');

            $lang = $this->Session->read("sess_langauge");

            $screens = $this->RegistrationSubsubmenu->find('list', array('fields' => array('subsubmenu_id', 'subsubmenu_desc_' . $lang), 'conditions' => array('is_optional' => 'Y')));
            $article = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y')));
            if ($this->request->is('post')) {
                $screen_id = $this->request->data['article_mapping']['subsubmenu_id'];
                if (is_numeric($screen_id)) {
                    $this->RegistrationScreenMapping->deleteAll(array('subsubmenu_id' => $screen_id));

                    if (isset($this->request->data['article_mapping']['article'])) {
                        $data = $this->request->data['article_mapping']['article'];
                        $insert['subsubmenu_id'] = $screen_id;
                        foreach ($data as $article_id) {
                            if (is_numeric($article_id)) {
                                $insert['article_id'] = $article_id;
                                $this->RegistrationScreenMapping->create();
                                $this->RegistrationScreenMapping->save($insert);
                            }
                        }
                    }
                    $this->Session->setFlash(__('lblsavemsg'));
                    return $this->redirect(array('action' => 'reg_screen_mapping'));
                }
            }

            $this->set(compact('lang', 'screens', 'article'));
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function screen_mapping_article() {
        $this->autoRender = FALSE;
        try {
            $this->loadModel('RegistrationScreenMapping');
            if (is_numeric($this->request->data['subsubmenu_id'])) {
                $result['fields'] = $this->RegistrationScreenMapping->find("list", array('fields' => array('subsubmenu_id', 'article_id'), 'conditions' => array('subsubmenu_id' => $this->request->data['subsubmenu_id'])));
                echo json_encode($result);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function document_esign() {
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('NGDRSErrorCode');
        $this->loadModel('file_config');
        $this->loadModel('employee');
        try {

            $userid = Sanitize::html($this->Session->read("session_user_id"));
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $this->check_function_hierarchy($this->request->params['action']);
            $token = $this->Session->read("reg_token");
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read("sess_langauge");

            if ($this->request->is('post') && isset($this->request->data['document_esign'])) {
                $data = $this->request->data['document_esign'];

                $path1 = $this->file_config->find('first', array('fields' => array('filepath')));
                if (!empty($path1)) {

                    if (isset($this->request->data['otprequest'])) {
                        if (isset($data['consent'])) {
//                            $basepath = $path1['file_config']['filepath'];
                            $basepath = "D:/NGDRS_Upload_mh";
                            $path = $path1['file_config']['filepath'] . "Esign/esignotp.jar";
//                            echo $path;exit;
                            if (file_exists($path)) {
                                $user = $this->employee->find('first', array('conditions' => array('emp_code' => $this->Auth->user('employee_id'))));
                                if (!empty($user) && !is_null($user['employee']['uid_no'])) {
                                    $aadhaar = $user['employee']['uid_no'];

//                                      $aadhaar = "531477220553";
                                    $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath, $result);
//                                    $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath, $result);
//                                   pr($message);exit; 
//echo $aadhaar;exit;
//                                    
//             $jksPath="C:/Program Files/Java/jre1.8.0_131/lib/security/ngdrsrsanew16.jks";
//             $httpsURL="https://196.1.113.253/esignlevel1/1.0/getotp";
//             $rsapath="C:/Program Files/Java/jre1.8.0_131/lib/security/ngdrsrsanew.key";
//          pr('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath.' '.$jksPath.' '.$httpsURL.' '.$rsapath, $result);exit;
//                                     $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath.' '.$jksPath.' '.$httpsURL.' '.$rsapath, $result);
//                                    $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath, $result);
                                    if ($message == 'OTP send Successfully....!!!!!') {
                                        $this->Session->setFlash(__("OTP send Successfully....!!!!!"));
                                    } else {
                                        $this->Session->setFlash(__("Error :($aadhaar) $message "));
                                    }
                                } else {
                                    $this->Session->setFlash(__("UID NOT FOUND: Please Update Your profile."));
                                }
                            } else {
                                $this->Session->setFlash(__("OTP JAR NOT FOUND!"));
                            }
                        } else {
                            $this->Session->setFlash(__("CONCENT NOT FOUND!"));
                        }
                    } else if (isset($this->request->data['esignrequest'])) {

                        if (isset($data['otp'])) {
                            $path = $path1['file_config']['filepath'] . "Esign/esignpdf.jar";
                            $basepath = $path1['file_config']['filepath'];

                            if (file_exists($path)) {
                                $user = $this->employee->find('first', array('conditions' => array('emp_code' => $this->Auth->user('employee_id'))));
                                if (!empty($user) && !is_null($user['employee']['uid_no'])) {
                                    $aadhaar = $user['employee']['uid_no'];
                                    $ci = "abcdefghi";
                                    $filename = "20170000060_final_document";
                                    $folder = "Documents/20170000060/Report/";
//  pr($path);                                  
// pr($basepath);exit;
//                                    $urlrequestpath = "https://196.1.113.253/esignlevel1/1.0/signdoc";
//                                    $jksPaht = "C:/Program Files/Java/jre1.8.0_131/lib/security/ngdrsrsanew16.jks";
//                                    $public_cdacpath = "C:/Program Files/Java/jre1.8.0_131/lib/security/uidai_auth_prod.cer";
//pr('java -jar ' . $path . ' ' . $userid . ' ' . $aadhaar . ' ' . $data['otp'] . ' ' . $ci . ' ' . $basepath . ' ' . $filename . ' ' . $folder);
                                    //$message = exec('java -jar ' . $path . ' ' . $userid . ' ' . $aadhaar . ' ' . $data['otp'] . ' ' . $ci . ' ' . $basepath . ' ' . $filename . ' ' . $folder. ' ' .$urlrequestpath. ' ' .$jksPaht. ' ' .$public_cdacpath, $result);
                                    $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['otp'] . ' ' . $ci . ' ' . $filename . ' ' . $basepath . ' ' . $folder, $result);
//$message = exec('java -jar ' . $path, $result);
                                    //    pr($result);exit;
                                    //$message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath, $result);
//                                    $message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $data['consent'] . ' ' . $basepath, $result);
                                    $this->Session->setFlash(__("Error : " . "(" . $aadhaar . ")" . $message));
                                } else {
                                    $this->Session->setFlash(__("UID NOT FOUND: Please Update Your profile."));
                                }
                            } else {
                                $this->Session->setFlash(__("OTP JAR NOT FOUND!"));
                            }
                        } else {
                            $this->Session->setFlash(__("OTP NOT FOUND!"));
                        }
                    } else {
                        $this->Session->setFlash(__("REQUEST NOT FOUND!"));
                    }
                } else {
                    $this->Session->setFlash(__("FILE DIRECTORY NOT FOUND!"));
                }
                $this->redirect('document_esign');
            } else {
//                $response = $this->create_final_document();
//                if (!empty($response['ERROR'])) {
//                    $this->Session->setFlash(__($response['ERROR']));
//                } else {
//                    $this->Session->setFlash(__($response['SUCCESS']));
//                }
            }


            if (isset($office_id) && is_numeric($office_id) && isset($token) && is_numeric($token)) {
                $this->set("documents", $documents = $this->ApplicationSubmitted->query("SELECT app.*,article.* FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id AND  app.token_no=? AND app.office_id=?; ", array($token, $office_id)));
                $this->set(compact('lang', 'token', 'Srochecklist'));
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_document_folder_structure($basepath = NULL, $token = NULL, $office_id = NULL) {
        $this->loadModel('office');
        $mode = 0777;
        $currentpath = $basepath;
        if (!is_null($currentpath)) {
            if (!file_exists($currentpath)) {
                return 0; // base path not found
            }
        }


        $currentpath .= "Documents";
        if (!file_exists($currentpath)) {
            mkdir($currentpath, $mode);
        }


        if (is_null($office_id)) {
            return 0; // office id not found
        }

        $office = $this->office->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id'),
            'joins' => array(
                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
            ),
            'conditions' => array('office.office_id' => $office_id)
        ));


        if (empty($office)) {
            return 0;
        }
        if (!isset($office['district']['district_name_en']) || is_null($office['district']['district_name_en'])) {
            return 0;
        }
        $currentpath .= "/" . $office['district']['district_name_en'];
        if (!file_exists($currentpath)) {
            mkdir($currentpath, $mode);
        }

        if (!isset($office['office']['taluka_id']) || is_null($office['office']['taluka_id'])) {
            return 0;
        }

        $currentpath .= "/" . $office['office']['taluka_id'];
        if (!file_exists($currentpath)) {
            mkdir($currentpath, $mode);
        }

        $currentpath .= "/" . $office_id;
        if (!file_exists($currentpath)) {
            mkdir($currentpath, $mode);
        }

        $currentpath .= "/" . $token;
        if (!file_exists($currentpath)) {
            mkdir($currentpath, $mode);
        }


        if (!file_exists($currentpath . "/Uploads")) {
            mkdir($currentpath . "/Uploads", $mode);
        }
        if (!file_exists($currentpath . "/Esign")) {
            mkdir($currentpath . "/Esign", $mode);
        }
        if (!file_exists($currentpath . "/QRBarcode")) {
            mkdir($currentpath . "/QRBarcode", $mode);
        }
        if (!file_exists($currentpath . "/Report")) {
            mkdir($currentpath . "/Report", $mode);
        }
        if (!file_exists($currentpath . "/Scanning")) {
            mkdir($currentpath . "/Scanning", $mode);
        }
        if (!file_exists($currentpath . "/Temp")) {
            mkdir($currentpath . "/Temp", $mode);
        }

        return 1;
    }

    public function create_final_document($token = NULL, $doc_reg_no = NULL, $office_id = NULL) {
        $this->loadModel('file_config');
        $this->loadModel('regconfig');
        $this->loadModel('office');
        $response['ERROR'] = '';
        $response['SUCCESS'] = '';

        if (empty($token)) {
            $response['ERROR'] = 'Token Not Found';
            return $response;
        }
        if (empty($doc_reg_no)) {
            $response['ERROR'] = 'Registration Number Not Found!';
            return $response;
        }
        if (is_null($office_id)) {
            $response['ERROR'] = 'Office ID  Not Found';
            return $response; // office id not found
        }

        $office = $this->office->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id'),
            'joins' => array(
                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
            ),
            'conditions' => array('office_id' => $office_id)
        ));

        if (empty($office)) {
            $response['ERROR'] = 'Office  Not Found for office  id ' . $office_id;
            return $response; // office id not found
        }
        if (!isset($office['district']['district_name_en']) || is_null($office['district']['district_name_en'])) {
            $response['ERROR'] = 'District Name Not Found';
            return $response; // District Name not found
        }

        if (!isset($office['office']['taluka_id']) || is_null($office['office']['taluka_id'])) {
            $response['ERROR'] = 'Taluka Name Not Found';
            return $response; // Taluka Name not found
        }


        $path = $this->file_config->find('first', array('fields' => array('filepath')));
        $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 36, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
        if (empty($path)) {
            $response['ERROR'] = 'File Upload Path Not Found On Database';
            return $response;
        } else {
            $basepath = $path['file_config']['filepath'];
            $currentpath = $basepath . "Documents/" . $office['district']['district_name_en'] . "/" . $office['office']['taluka_id'] . "/" . $office_id . "/" . $token . "/";
            $check = $this->check_document_folder_structure($basepath, $token, $office_id);
            if ($check == 0) {
                $response['ERROR'] = 'File Upload Base Path Not Found!';
                return $response;
            }
        }
        if (empty($regconf)) {
            $response['ERROR'] = 'QR or BARCode Configuration Not Found!';
            return $response;
        }

        $finaldocumentpath_qrcode = $currentpath . "Report/" . $token . "_final_document.pdf";
        if (file_exists($finaldocumentpath_qrcode)) {
            $response['SUCCESS'] = 'Final Document Exist. Canot Created!';
            return $response;
        }

        $utility = new UtilityController();
        $utility->constructClasses();
        //echo $currentpath;exit;
        if ($regconf[0]['regconfig']['info_value'] == 'QR') {
            $utility->QRcode($doc_reg_no, $currentpath . "QRBarcode/" . $token . "_qrbarcode.png");
        } else {
            $utility->QRcode($doc_reg_no, $currentpath . "QRBarcode/" . $token . "_qrbarcode.png");
        }

        $report = new ReportsController();
        $report->constructClasses();
        $summaryreport_design = $report->joint_report(base64_encode($token), 'V');
        $savepath_summaryreport = $currentpath . "Report/" . $token . "_summaryreport.pdf";
        $summary_deed_combined = $currentpath . "Report/" . $token . "_summaryreport_deed.pdf";
        $this->create_and_save_pdf($savepath_summaryreport, $summaryreport_design, $waterMark = "Final Report");

        $savepath_deed = $currentpath . "Uploads/" . $token . "_9999.pdf";

        $finaldocumentpath = $currentpath . "Temp/" . $token . "_final_document.pdf";
        $qrcode_path = $currentpath . "QRBarcode/" . $token . "_qrbarcode.png";

        $combinepdfjar = $basepath . "jar_files/combinepdf.jar";
        $qrcodejar = $basepath . "jar_files/qrcodeattach.jar";

        if (!file_exists($combinepdfjar)) {
            $response['ERROR'] = 'PDF Combile JAR Not Found';
            return $response;
        }
        if (!file_exists($qrcodejar)) {
            $response['ERROR'] = 'PDF QRCode JAR Not Found';
            return $response;
        }
        if (file_exists($savepath_summaryreport)) {
            if (file_exists($savepath_deed)) {
                $message = exec('java -jar ' . $combinepdfjar . ' ' . $savepath_deed . ' ' . $savepath_summaryreport . ' ' . $summary_deed_combined, $result);
                if (file_exists($summary_deed_combined)) {
                    $message = exec('java -jar ' . $qrcodejar . ' ' . $summary_deed_combined . ' ' . $finaldocumentpath_qrcode . ' ' . $qrcode_path, $result);
                    $response['SUCCESS'] = $message;
                } else {
                    $response['ERROR'] = 'Final Document Not Found For Attach QR or BAR code!';
                }
                return $response;
            } else {
                if (file_exists($savepath_summaryreport)) {
                    $message = exec('java -jar ' . $qrcodejar . ' ' . $savepath_summaryreport . ' ' . $finaldocumentpath_qrcode . ' ' . $qrcode_path, $result);
                    $response['SUCCESS'] = $message;
                } else {
                    $response['ERROR'] = 'Final Document Not Found For Attach QR or BAR code!';
                }
                return $response;
            }
        } else {
            $response['ERROR'] = 'Summary Report Not Generated!';
            return $response;
        }
    }

    public function create_final_document_before_esign() {
        $this->loadModel('file_config');
        $this->loadModel('regconfig');
        $token = $this->Session->read("reg_token");
        $office_id = $this->Session->read("office_id");
        $response['ERROR'] = '';
        $response['SUCCESS'] = '';


        $path = $this->file_config->find('first', array('fields' => array('filepath')));
        $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 36, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
        if (empty($path)) {
            $response['ERROR'] = 'File Upload Path Not Found On Database';
            return $response;
        } else {
            $basepath = $path['file_config']['filepath'];
            $check = $this->check_document_folder_structure($basepath, $token, $office_id);
            if ($check == 0) {
                $response['ERROR'] = 'File Upload Base Path Not Found!';
                return $response;
            }
        }
        if (empty($regconf)) {
            $response['ERROR'] = 'QR or BARCode Configuration Not Found!';
            return $response;
        }

        $finaldocumentpath_qrcode = $basepath . "Documents/" . $token . "/Esign/" . $token . "_final_document.pdf";
        if (file_exists($finaldocumentpath_qrcode)) {
            $response['SUCCESS'] = 'Final Document Exist. Canot Created!';
            return $response;
        }

//        $utility = new UtilityController();
//        $utility->constructClasses();
//        if ($regconf[0]['regconfig']['info_value'] == 'QR') {
//            $utility->QRcode('hello', $basepath . "Documents/" . $token . "/QRBarcode/" . $token . "_qrbarcode.png");
//        } else {
//            $utility->QRcode('hello', $basepath . "Documents/" . $token . "/QRBarcode/" . $token . "_qrbarcode.png");
//        }

        $report = new ReportsController();
        $report->constructClasses();
//        $summaryreport_design = $report->joint_report(base64_encode($token), 'V');
//        $savepath_summaryreport = $basepath . "Documents/" . $token . "/Report/" . $token . "_summaryreport.pdf";
//        $this->create_and_save_pdf($savepath_summaryreport, $summaryreport_design);

        $savepath_deed = $basepath . "Documents/" . $token . "/Uploads/" . $token . "_9999.pdf";
        $savepath_scan = $basepath . "Documents/" . $token . "/Scanning/" . $token . "_scan.pdf";
        $summary_deed_combined = $basepath . "Documents/" . $token . "/Temp/" . $token . "_summary_deed_combined.pdf";
        $finaldocumentpath = $basepath . "Documents/" . $token . "/Temp/" . $token . "_final_document.pdf";
        $qrcode_path = $basepath . "Documents/" . $token . "/QRBarcode/" . $token . "_qrbarcode.png";

        $combinepdfjar = $basepath . "jar_files/combinepdf.jar";
        $qrcodejar = $basepath . "jar_files/qrcodeattach.jar";

        if (!file_exists($combinepdfjar)) {
            $response['ERROR'] = 'PDF Combile JAR Not Found';
            return $response;
        }
        if (!file_exists($qrcodejar)) {
            $response['ERROR'] = 'PDF QRCode JAR Not Found';
            return $response;
        }
        if (file_exists($savepath_summaryreport)) {
            if (file_exists($savepath_deed)) {
                $message = exec('java -jar ' . $combinepdfjar . ' ' . $savepath_deed . ' ' . $savepath_summaryreport . ' ' . $summary_deed_combined, $result);
                if (file_exists($summary_deed_combined) && file_exists($savepath_scan)) {
                    $message = exec('java -jar ' . $combinepdfjar . ' ' . $summary_deed_combined . ' ' . $savepath_scan . ' ' . $finaldocumentpath, $result);
                    if (file_exists($finaldocumentpath)) {
                        $message = exec('java -jar ' . $qrcodejar . ' ' . $finaldocumentpath . ' ' . $finaldocumentpath_qrcode . ' ' . $qrcode_path, $result);
                        $response['SUCCESS'] = $message;
                    } else {
                        $response['ERROR'] = 'Final Document Not Found For Attach QR or BAR code!';
                    }
                    return $response;
                } else {
                    $response['ERROR'] = 'Scanned File or Deed File Not Found';
                    return $response;
                }
            } else if (file_exists($savepath_scan)) {
                $message = exec('java -jar ' . $combinepdfjar . ' ' . $savepath_summaryreport . ' ' . $savepath_scan . ' ' . $finaldocumentpath, $result);
                if (file_exists($finaldocumentpath)) {
                    $message = exec('java -jar ' . $qrcodejar . ' ' . $finaldocumentpath . ' ' . $finaldocumentpath_qrcode . ' ' . $qrcode_path, $result);
                    $response['SUCCESS'] = $message;
                } else {
                    $response['ERROR'] = 'Final Document Not Found For Attach QR or BAR code!';
                }
                return $response;
            } else {
                $response['ERROR'] = 'Scanned File Not Found';
                return $response;
            }
        } else {
            $response['ERROR'] = 'Summary Report Not Generated!';
            return $response;
        }
    }

    function reschedule_appointment($token, $date) {
        array_map(array($this, 'loadModel'), array('officeshift', 'office', 'appointment', 'ApplicationSubmitted', 'regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 44)));
        $lang = $this->Session->read("sess_langauge");
        $this->set('normal_days', '+' . $regconfig['regconfig']['info_value'] . 'd');
        $office_id = $this->ApplicationSubmitted->field('office_id', array('token_no' => $token));
        $officeshift = $this->officeshift->find('list', array('fields' => array('shift_id', 'desc_' . $lang), 'order' => array('shift_id' => 'ASC')));
        $this->set('office_id', $office_id);
        $this->set('officeshift', $officeshift);
        $this->set('tokenval', $token);
        $appointment = $this->appointment->find('all', array('conditions' => array('appointment.token_no ' => $token)));
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->set('appointment', $appointment);
        $this->set('date', $date);
        $submission = $this->ApplicationSubmitted->find('all', array('conditions' => array('ApplicationSubmitted.token_no ' => $token)));
        $fieldlist = array();
        $fielderrorarray = array();
        $fieldlist['appointment_date']['text'] = 'is_required';
        $fieldlist['shift_id']['select'] = 'is_select_req';
        $this->set('fieldlist', $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        if ($this->request->is('post')) {
            // $this->check_csrf_token($this->request->data['appointment']['csrftoken']);

            $this->request->data['appointment'] = $this->istrim($this->request->data['appointment']);
            $errarr = $this->validatedata($this->request->data['appointment'], $fieldlist);
            if ($this->ValidationError($errarr)) {
                $this->request->data['appointment']['user_type'] = $this->Session->read("session_usertype");

                if (!isset($_POST['slot']) || $_POST['slot'] == '') {
                    $this->Session->setFlash(__("Please Select slot"));
                    $this->redirect(array('controller' => 'Registration', 'action' => 'reschedule_appointment', $token, $this->request->data['appointment']['appointment_date']));
                }
                list($interval, $slot) = explode('_', $_POST['slot']);
                $data = array(
                    'interval_id' => $interval,
                    'slot_no' => $slot,
                    'appointment_date' => "'" . date('Y-m-d', strtotime($this->request->data['appointment']['appointment_date'])) . "'",
                    'totalslot' => $_POST['totalslot'],
                    'req_ip' => "'" . $ip . "'",
                    'sheduled_time' => "'" . $_POST['time'] . "'",
                    'shift_id' => $this->request->data['appointment']['shift_id'],
                );
                $this->appointment->updateAll($data, array('appointment.token_no' => $this->request->data['appointment']['token_no']));

                $this->Session->setFlash(__("lbleditmsg"));
                $this->set_csrf_token();
                $this->redirect(array('controller' => 'Registration', 'action' => 'appointment', $this->Session->read('csrftoken')));
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
            }
        }
    }

    public function mst_identification($identification_id = NULL) {
        try {

            array_map(array($this, 'loadModel'), array('MstIdentification', 'identification', 'cast_category', 'gov_partytype', 'District', 'taluka', 'identificatontype', 'identification_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category', 'mainlanguage', 'TrnBehavioralPatterns', 'VillageMapping'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Auth->user("user_id");
            $state_id = $this->Auth->user("user_id");
            $token = $this->Session->read('Selectedtoken');
            $office_id = $this->Auth->user('office_id');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
// set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $lang)));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $lang), 'conditions' => array('identifier_flag' => 'Y'), 'order' => 'occupation_name_' . $lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $lang));
            $taluka = array();
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $lang), 'conditions' => array('separate_list' => 'Y'), 'order' => array('identificationtype_desc_' . $lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $lang), 'order' => array('category_name_' . $lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $lang), 'order' => array('government_type_' . $lang => 'ASC')));
            $identifirefields = $this->identification_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));
            $villagelist = array();
//category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);


            $fieldlist = $this->MstIdentification->fieldlist($lang, NULL, $languagelist);

//pr($fieldlist);exit;
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            if ($this->request->is('post') || $this->request->is('put')) {
                $data = @$this->request->data['mst_identification'];
                $this->check_csrf_token($data['csrftoken']);
                $address = @$this->request->data['property_details'];
                $fieldlist = $this->MstIdentification->fieldlist($lang, @$data['village_id'], $languagelist);
                if (isset($data['dob']) && !empty($data['dob'])) {
                    $date = date_create($data['dob']);
                    $data['dob'] = date_format($date, "Y-m-d");
                }
                if (!empty($address)) {
                    foreach ($address['pattern_id'] as $key => $value) {
                        foreach ($languagelist as $singlelang) {
                            $data['field_' . $singlelang['mainlanguage']['language_code'] . "_" . $value] = @$address['pattern_value_' . $singlelang['mainlanguage']['language_code']][$key];
                        }
                    }
                }
                $errors = $this->validatedata($data, $fieldlist);

                // pr($errors);exit;
                if ($this->ValidationError($errors)) {
                    $data = $this->mergeidentifirecolumns($data, $languagelist);
                    $data = $this->mst_common_fields($data);

                    if ($this->MstIdentification->save($data)) {
                        $lastid = $this->MstIdentification->getInsertID();
                        if (empty($lastid)) {
                            if (isset($data['identification_id']) && is_numeric($data['identification_id'])) {
                                $this->TrnBehavioralPatterns->deleteAll(array('mapping_ref_val' => $data['identification_id'], 'mapping_ref_id' => 9999));
                            }
                            $lastid = $data['identification_id'];
                            $message = "lbleditmsg";
                        } else {
                            $message = "lblsavemsg";
                        }
                        $arrdata = array();
                        $arrdata = $this->mst_common_fields($arrdata);
                        foreach ($address['pattern_id'] as $key => $value) {
                            $arrdata['field_id'] = $value;
                            foreach ($languagelist as $singlelang) {
                                $arrdata['field_value_' . $singlelang['mainlanguage']['language_code']] = @$address['pattern_value_' . $singlelang['mainlanguage']['language_code']][$key];
                            }
                            $arrdata['mapping_ref_id'] = 9999;
                            $arrdata['mapping_ref_val'] = $lastid;
                            $this->TrnBehavioralPatterns->create();
                            $this->TrnBehavioralPatterns->save($arrdata);
                        }
                        $this->Session->setFlash(__($message));
                    } else {
                        $this->Session->setFlash(
                                __('lblnotsavemsg')
                        );
                    }
                } else {
                    $this->request->data['mst_identification'] = $data;
                    $this->Session->setFlash(
                            __('Please Check Validations')
                    );
                }

                return $this->redirect(array('action' => 'mst_identification'));
            }
            $identifirelist = $this->MstIdentification->Identifirelist($lang, $office_id);

            if (!is_null($identification_id)) {
                $identifire = $this->MstIdentification->find('first', array('conditions' => array('office_id' => $office_id, 'identification_id' => $identification_id)));
//                pr($identifire);exit;
                if (isset($identifire['MstIdentification']['dob']) && !empty($identifire['MstIdentification']['dob'])) {
                    $date = date_create($identifire['MstIdentification']['dob']);
                    $identifire['MstIdentification']['dob'] = date_format($date, "m/d/Y");
                }
                $this->request->data['mst_identification'] = $identifire['MstIdentification'];
                //  pr($identifire['MstIdentification']);exit;
                $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'conditions' => array('district_id' => $identifire['MstIdentification']['district_id']), 'order' => array('taluka_name_' . $lang => 'ASC')));
                $villagelist = $this->VillageMapping->find('list', array('fields' => array('village_id', 'village_name_' . $lang), 'conditions' => array('district_id' => $identifire['MstIdentification']['district_id'], 'taluka_id' => $identifire['MstIdentification']['taluka_id']), 'order' => array('village_name_' . $lang => 'ASC')));
            }

            // pr($pattern);exit;
            $this->set(compact('districtdata', 'patterns', 'category', 'cast_cat_flag', 'gov_partytype', 'taluka', 'villagelist', 'identifirefields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka', 'identifirelist'));
        } catch (Exception $ex) {
            pr($ex);
            exit;

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function mst_pattern_detail($id = NULL) {
        $lang = $this->Session->read("sess_langauge");
        $this->loadModel('TrnBehavioralPatterns');
        $patterns = array();
        if (is_numeric($id)) {
            $patterns = $this->TrnBehavioralPatterns->mst_pattern_detail($lang, 9999, $id);
        }
        return $patterns;
    }

    public function mst_identification_remove($id = NULL) {
        try {
            $this->loadModel('MstIdentification');
            if (is_numeric($id)) {
                $result = $this->MstIdentification->deleteAll(array('identification_id' => $id, 'office_id' => $this->Auth->user('office_id')));
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
            return $this->redirect(array('action' => 'mst_identification'));
        } catch (Exception $ex) {
            
        }
    }

    public function mst_common_fields($data) {
        $data['user_id'] = $this->Auth->user("user_id");
        $data['state_id'] = $this->Auth->user("state_id");
        $data['office_id'] = $this->Auth->user("office_id");
        $data['req_ip'] = $this->request->clientIp();
        $data['user_type'] = $this->Session->read("session_usertype");
        return $data;
    }

    public function mergeidentifirecolumns($data, $languagelist) {

        if (!empty($languagelist)) {
            foreach ($languagelist as $singlelang) {
                $lang = $singlelang['mainlanguage']['language_code'];
                if (isset($data['fname_' . $lang])) {
                    $data['identification_full_name_' . $lang] = $data['fname_' . $lang];
                }if (isset($data['mname_' . $lang])) {
                    $data['identification_full_name_' . $lang] .= " " . $data['mname_' . $lang];
                }if (isset($data['lname_' . $lang])) {
                    $data['identification_full_name_' . $lang] .= " " . $data['lname_' . $lang];
                }
            }
        }
        return $data;
    }

    function set_common_fields() {
        $data['stateid'] = $this->Auth->User("state_id");
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Session->read("citizen_user_id");
        return $data;
    }

    public function get_name_format() {
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 29)));
        if (!empty($regconfig)) {
            return $regconfig['regconfig']['conf_bool_value'];
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

    function token_status() {
        $fieldlist = array();
        $fieldlist['from']['text'] = 'is_required';
        $fieldlist['to']['text'] = 'is_required';
        $this->set('fieldlist', $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
    }

    function get_all_token_bydate() {
        try {
            array_map(array($this, 'loadModel'), array('trndocumentstatus'));


            if (isset($_POST['from']) && isset($_POST['to'])) {

                $from = date('Y-m-d', strtotime($_POST['from']));
                $to = date('Y-m-d', strtotime($_POST['to']));
                $alltoken = $this->trndocumentstatus->get_alltoken($from, $to);


                $this->set('alltoken', $alltoken);
            }
        } catch (Exception $e) {
            
        }
    }

    function view_single_tokenstate() {
        try {

            if (isset($_POST["token_no"]) && is_numeric($_POST["token_no"])) {
                array_map(array($this, 'loadModel'), array('trndocumentstatus'));
                $result = $this->trndocumentstatus->query('select d.document_status_desc_en,o.office_name_en,DATE(td.created) as dte from ngdrstab_mst_office o,ngdrstab_mst_document_status_description d,
                                                 ngdrstab_trn_document_status td ,ngdrstab_trn_generalinformation g where d.id=td.status_id and o.office_id=td.office_id and g.token_no=td.token_no and td.token_no=?', array($_POST["token_no"]));


                $design = "<table border=2 width=100%>"
                        . '<tr style="background-color: #d68910"><td colspan="4"> <b>Pre Reg Number :' . $_POST["token_no"] . '</b></td></tr>'
//.'<tr><td colspan="4">&nbsp;</td></tr>'
                        . '<tr style="background-color: #f8c471"><th>Sr. No.</th><th>Status</th><th>Office Name</th><th>Date</th></tr>';
                if (!empty($result)) {
                    $i = 1;
                    foreach ($result as $res) {

                        $design .= '<tr style="background-color: #fcf3cf ">'
                                . '<td>' . $i . '</td><td>' . $res[0]['document_status_desc_en'] . '</td>'
                                . '<td>' . $res[0]['office_name_en'] . '</td><td>' . $res[0]['dte'] . '</td>'
                                . '<tr>';
                        $i++;
                    }
                }
                $design .= "</table'>";

                echo $design;
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function ispresenter() {
        try {
            if (isset($_POST['id'])) {
                array_map(array($this, 'loadModel'), array('party_entry'));
                //check presenter
                if ($_POST['is_presenter'] == 'Y') {
                    $is_pre = $this->party_entry->find("first", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'is_presenter' => 'Y')));
                    if (count($is_pre) > 0) {
                        $this->party_entry->id = $is_pre['party_entry']['id'];
                        $this->party_entry->saveField('is_presenter', 'N');
                    }
                }
                //set presenter
                $result = $this->party_entry->find("first", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'party_id' => $_POST['id'])));
                $this->party_entry->id = $result['party_entry']['id'];
                if ($this->party_entry->saveField('is_presenter', $_POST['is_presenter'])) {
                    echo 1;
                    exit;
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

    public function isexecuter() {
        try {
            if (isset($_POST['id'])) {
                array_map(array($this, 'loadModel'), array('party_entry'));
                //check presenter
                //set presenter
                $result = $this->party_entry->find("first", array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'party_id' => $_POST['id'])));
                $this->party_entry->id = $result['party_entry']['id'];
                if ($this->party_entry->saveField('is_executer', $_POST['is_executer'])) {
                    if ($this->party_entry->saveField('presenty_require', $_POST['presenty_require'])) {
                        echo 1;
                        exit;
                    }
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

    public function documentcancel() {
        try {
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel("article");
            $this->loadModel("regconfig");


            // reset sessions 
            $this->Session->write("reg_token", NULL);
            $this->Session->write("reg_record_no", NULL);
            $this->Session->write("citizen_user_id", NULL);
            $this->Session->write("Selectedtoken", NULL);
            $this->Session->write("selectedarticle_id", NULL);

            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("office_id", $this->Auth->user('office_id'));
            $office_id = $this->Session->read("office_id");
            $lang = $this->Session->read('sess_langauge');
            if ($lang == 'en') {
                $this->Session->write("doc_lang", 'en');
            } else {
                $this->Session->write("doc_lang", 'll');
            }
            $doc_lang = $this->Session->read("doc_lang");
            $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 41, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            if (isset($office_id) && is_numeric($office_id)) {
                $stamp_conf = $this->stamp_and_functions_config();
                foreach ($stamp_conf as $stamp) {
                    if ($stamp['is_last'] == 'Y') {
                        $check_stamp_flag = $stamp['stamp_flag']; // find last stamp flag
                    }
                }
                if (!empty($regconf)) {
                    $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            where app.office_id=? and app.cancel_remark_flag=?", array($office_id, 'N')));
//                            app.office_id=? and check_in_flag=? and document_scan_flag=? or app.office_id=? and check_in_flag=? and  final_stamp_flag=?  or app.office_id=? and check_in_flag=? and  esign_flag=? and app.cancel_remark_flag=?", array($office_id, 'Y', 'N', $office_id, 'Y', 'N', $office_id, 'Y', 'N','N')));
                } else {
                    $this->set("alldocuments", $alldocuments = $this->ApplicationSubmitted->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            where  where app.office_id=? and app.cancel_remark_flag=?", array($office_id, 'N')));
                    // app.office_id=? and check_in_flag=? and document_scan_flag=? or app.office_id=? and check_in_flag=? and  final_stamp_flag=? and app.cancel_remark_flag=?", array($office_id, 'Y', 'N', $office_id, 'Y', 'N','N')));
                }
            }


            $this->set(compact('doc_lang', 'stamp_conf', 'regconf'));
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function remarkdocument($token = NULL) {
        try {
            $this->loadModel('documentcancel');
            $this->loadModel("ApplicationSubmitted");
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();

            $fielderrorarray = array();
            $fieldlist['remark']['text'] = 'is_required,is_alpha';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $this->set('token', $token);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $ip = $this->request->clientIp();
                $state_id = $this->Auth->user('state_id');
                $userid = $this->Auth->user('user_id');
                $office_id = $this->Session->read("office_id");
//                 echo $office_id;exit;
                $request_data = $this->request->data['remarkdocument'];
                // pr($request_data);
                $tokenno = $request_data['token_no'];
                $docremark = $request_data['remark'];

                if ($docremark == '') {
                    $this->Session->setFlash(__('Please Enter Remark'));
                    $this->redirect(array('action' => 'documentcancel'));
                }

                $data = array('token_no' => $tokenno,
                    'remark' => $docremark,
                    'created_date' => $date,
                    'req_ip' => $ip,
                    'state_id' => $state_id,
                    'user_id' => $userid
                );
                $errarr = $this->validatedata($this->request->data['remarkdocument'], $fieldlist);
                if ($this->ValidationError($errarr)) {

                    if ($this->documentcancel->save($data)) {
                        $savemsg = 'lblsavemsg';
                        $this->Session->setFlash(__($savemsg));
//                  pr($office_id);
//                   pr($tokenno);exit;
                        $updatedata = $this->ApplicationSubmitted->query("update ngdrstab_trn_application_submitted set cancel_remark_flag='Y' where office_id=? and token_no=?", array($office_id, $tokenno));
                        //pr($updatedata);exit;
                        $this->redirect(array('action' => 'documentcancel'));
                    }
                }
            }
        } catch (Exception $exc) {
            pr($exc);
            exit;
        }
    }

    public function eregistration_test() {
        $this->autoRender = False;
        $jarpath = "/home/NGDRS_UPLOAD_PB/jar_files/eregistrationverify.jar";
        pr('java -jar ' . $jarpath . ' ' . "pbreglockusr" . ' ' . "RSU501KCOLGERBP" . ' ' . 'PB1717581711998');
        $jarmessage = exec('java -jar ' . $jarpath . ' ' . "pbreglockusr" . ' ' . "RSU501KCOLGERBP" . ' ' . 'PB1717581711998', $result);
        pr($result);
        exit;
    }

    public function willexecution() {
        $this->loadModel("genernalinfoentry");
        $this->loadModel("willexecution_date");
        $this->set('hfactualexecdate', NULL);
        $this->set('hfupdateflag', NULL);
        $this->set('hfdateofdeath', NULL);
        $this->set('hfid', NULL);
        $this->set('actiontype', NULL);
        $this->set('cap', NULL);
        $this->set("result", $result = $this->genernalinfoentry->query("select c.id,a.token_no,a.doc_reg_no,b.exec_date,c.date_of_death,c.actual_exec_date,b.article_id from ngdrstab_trn_application_submitted a
                                                            inner join ngdrstab_trn_generalinformation b on a.token_no=b.token_no 
                                                            inner join ngdrstab_trn_willexecution_date c on c.doc_reg_no =a.doc_reg_no where c.article_id=? and c.lock_flag=?", array('63', 'N')));
//       pr($result);exit;

        if ($this->request->is('post')) {
//            pr($this->request->data);exit;hfupdateflag
            $this->set('actiontype', $_POST['actiontype']);

            if ($this->request->data['hfupdateflag'] == 'Y') {
                $updatedata = $this->willexecution_date->query("Update ngdrstab_trn_willexecution_date set lock_flag=? where id=?", array('Y', $_POST['hfid']));
                $this->Session->setFlash(__("lblsavemsg"));
                $this->redirect(array('action' => 'willexecution'));
            }

            $updatedata = $this->willexecution_date->query("Update ngdrstab_trn_willexecution_date set date_of_death=? ,actual_exec_date=? where id=?", array($_POST['date_of_death'], $_POST['actual_exec_date'], $_POST['hfid']));

            if ($updatedata == NULL) {
                $this->Session->setFlash(__("lbleditmsg"));
                $this->redirect(array('action' => 'willexecution'));
            }
        }
    }

    public function sendotp() {
        $this->autoRender = FALSE;
        //smssend($smsid, $mobile_no, $extramsg, $userid, $event_id)
        $this->loadModel('otpcitizen');
        $mobile_no = $this->Auth->user('mobile_no');
        $otp = rand(100000, 999999);
        $user_id = $this->Auth->user('user_id');
        $username = $this->Auth->user('username');
        $req_ip = $_SERVER['REMOTE_ADDR'];
        $stateid = $this->Auth->user('state_id');
        $createdate = date('Y-m-d');
        $this->Session->write('userotp', $otp);
        $this->otpcitizen->query('insert into ngdrstab_trn_otp(user_id,username,otp,created,state_id,req_ip)values(?,?,?,?,?,?)', array($user_id, $username, $otp, $createdate, $stateid, $req_ip));
        $this->smssend(1, $mobile_no, $otp, $user_id, 4);
    }

    public function payment_receipt($payment_id = NULL) {
        $this->autoRender = FALSE;
        array_map(array($this, 'loadModel'), array('payment'));
        if (!is_null($payment_id)) {
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $article_id = $this->Session->read("selectedarticle_id");

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read("doc_lang");
            $token = $this->Session->read('reg_token');
            $office_id = $this->Session->read("office_id");

            $payment = $this->payment->find("first", array(
                'fields' => array('payment.*', 'mode.payment_mode_id', 'mode.verification_flag'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_payment_mode', 'alias' => 'mode', 'conditions' => array('mode.payment_mode_id=payment.payment_mode_id')),
                ),
                'conditions' => array('payment_id' => $payment_id)
            ));
            //pr($payment);exit;
            if (!empty($payment)) {

                $serviceobj = new WebServiceController();
                $serviceobj->constructClasses();
                $extrafields['org_user_id'] = $user_id;
                $extrafields['token_no'] = $token;
                $response['Error'] = '';
                switch ($payment['mode']['payment_mode_id']) {
                    case 1:
                        $response = $serviceobj->GrasPaymentReceipt($payment['payment'], $extrafields);
                        break;
                    default : echo 'Receipt Not Available for this payment mode';
                }
                if (!empty($response['Error'])) {
                    echo $response['Error'];
                }
            } else {
                echo 'payment record not found';
            }
        } else {
            echo 'payment ID is Null';
        }
    }

    public function step_ip_date($token = NULL, $step_no = NULL) {
        try {
            if (isset($token) && is_numeric($token) && isset($step_no) && is_numeric($step_no)) {
                $this->loadModel('ApplicationSubmitted');
                $stateid = $this->Auth->User('state_id');
                $userid = $this->Auth->User('user_id');
                $createddate = date('Y/m/d H:i:s');
                $ip_add = $_SERVER['REMOTE_ADDR'];
                $this->ApplicationSubmitted->query("insert into ngdrstab_trn_ip_date (token_no, step_no, user_id, created, state_id, req_ip)"
                        . "values(?,?,?,?,?,?)", array($token, $step_no, $userid, $createddate, $stateid, $ip_add));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function approve_document_list_OLD() {
        $this->loadModel('genernalinfoentry');
        $office_id = $this->Auth->user('office_id');
        $lang = $this->Session->read("sess_langauge");

        $rpt_data = $this->genernalinfoentry->Query("select   geninfo.token_no,geninfo.exec_date,article.book_number,     article.article_desc_$lang from ngdrstab_trn_generalinformation geninfo
                     left join ngdrstab_mst_article article on article.article_id=geninfo.article_id   
                    where  geninfo.office_id=? and sro_approve_flag=? and sro_action_flag=?", array($office_id, 'N', 'N'));

        $this->set("rpt_data", $rpt_data);
        $this->set("lang", $lang);
    }

    public function approve_document($token = NULL) {
        try {

            $this->loadModel('genernalinfoentry');
            $this->loadModel('uploaded_file_trn');
            $office_id = $this->Auth->user('office_id');
            $lang = $this->Session->read("sess_langauge");
            $token = $this->Session->read("approve_token");
            $rpt_data = $this->genernalinfoentry->Query("select   geninfo.token_no,geninfo.exec_date,article.book_number,     article.article_desc_$lang,
title.articledescription_en from ngdrstab_trn_generalinformation geninfo
                     left join ngdrstab_mst_article article on article.article_id=geninfo.article_id   
                     LEFT join ngdrstab_mst_articledescriptiondetail as title ON title.articledescription_id=geninfo.title_id
                    where  geninfo.office_id=? and geninfo.token_no=? and sro_approve_flag=? ", array($office_id, $token, 'N'));
//pr($rpt_data);exit;
            if (!empty($rpt_data)) {

                $fieldlist['sro_remark']['text'] = 'is_required,is_alphanumericspace,is_minmaxlength1000';

                $this->set("fieldlist", $fieldlist);
                $this->set('result_codes', $this->getvalidationruleset($fieldlist));

                if ($this->request->is('post')) {


                    $errors = $this->validatedata($this->request->data['final_stamp'], $fieldlist);
//pr($errors);exit;
                    if ($this->ValidationError($errors)) {

                        if ($this->request->data['final_stamp']['sro_action_flag'] == 'A') {
                            $this->genernalinfoentry->updateAll(array('sro_approve_flag' => "'Y'", 'sro_approve_date' => "'" . date('Y-m-d H:i:s') . "'", 'sro_remark' => "'" . $this->request->data['final_stamp']['sro_remark'] . "'", 'sro_action_flag' => "'" . $this->request->data['final_stamp']['sro_action_flag'] . "'"), array('token_no' => $token));
                            $this->Session->setFlash(__('Observation sent Successfully. now it is available to citizen.'));
                        } else {
                            $this->genernalinfoentry->updateAll(array('sro_approve_flag' => "'N'", 'sro_approve_date' => "'" . date('Y-m-d H:i:s') . "'", 'sro_remark' => "'" . $this->request->data['final_stamp']['sro_remark'] . "'", 'sro_action_flag' => "'" . $this->request->data['final_stamp']['sro_action_flag'] . "'"), array('token_no' => $token));
                            $this->Session->setFlash(__('Document Rejected.'));
                        }


                        return $this->redirect(array('controller' => 'Registration', 'action' => 'approve_document_list'));
                    }
                }


                $document_list = $this->uploaded_file_trn->find('all', array('fields' => array('document.document_id', 'document.document_name_' . $lang, 'uploaded_file_trn.document_id', 'uploaded_file_trn.up_id', 'uploaded_file_trn.out_fname', 'uploaded_file_trn.token_no'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_upload_document', 'alias' => 'document', 'conditions' => array('document.document_id=uploaded_file_trn.document_id'))
                    ),
                    'conditions' => array('token_no' => $token),
                    'order' => 'document.document_name_en,uploaded_file_trn.up_id'
                ));

                $this->set(compact('document_list', 'rpt_data', 'lang'));
            } else {
                $this->Session->setFlash(__('Token not found!')
                );
                return $this->redirect(array('controller' => 'Registration', 'action' => 'approve_document_list'));
            }

            $this->set_csrf_token();
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function party_pan_verification() {
        //$this->autoRender = FALSE;
        $this->loadModel('party_entry');
        $token = $this->Session->read('Selectedtoken');
        $party_id = $this->request->data['party_id'];

        $result = $this->party_entry->find("first", array('fields' => array('pan_no'), 'conditions' => array('token_no' => $token, 'party_id' => $party_id)));
        if (!empty($result)) {
            $serviceobj = new WebServiceController();
            $serviceobj->constructClasses();
            $response = $serviceobj->Pan_verification($result['party_entry']['pan_no']);
            if (empty($response['Error'])) {
                $this->set("pandetails", $response['Data']);
                $this->set("party_id", $party_id);
            } else {
                $this->set("panerror", $response['Error']);
            }
        } else {
            $this->set("panerror", 'Invalid Party Details');
        }
    }

    public function approve_document_list() {
        $this->loadModel('genernalinfoentry');
        $this->loadModel('office');

        $office_id = $this->Auth->user('office_id');
        $lang = $this->Session->read("sess_langauge");
        $user_id = $this->Auth->user('user_id');

        $office = $this->office->find("first", array('conditions' => array('office_id' => $office_id, 'is_virtual_office' => 'Y')));
        if (!empty($office)) {
            $rpt_data = $this->genernalinfoentry->Query("select geninfo.token_no,
            uploadinfo.created,
geninfo.exec_date,
article.book_number,
article.article_desc_en ,
title.articledescription_en

from ngdrstab_trn_generalinformation geninfo
join ngdrstab_mst_article article on article.article_id=geninfo.article_id   
LEFT join ngdrstab_mst_articledescriptiondetail as title ON title.articledescription_id=geninfo.title_id
join ngdrstab_trn_fileuploadinfo  uploadinfo on uploadinfo.token_no=geninfo.token_no  and uploadinfo.id=(select max(id) from ngdrstab_trn_fileuploadinfo where token_no=geninfo.token_no)
WHERE 
geninfo.office_id=? and sro_user_id=? and sro_approve_flag=?

 
order by uploadinfo.created ASC ", array($office_id, $user_id, 'N'));
        } else {
            $rpt_data = $this->genernalinfoentry->Query("select geninfo.token_no,
            uploadinfo.created,
geninfo.exec_date,
article.book_number,
article.article_desc_en ,
title.articledescription_en

from ngdrstab_trn_generalinformation geninfo
join ngdrstab_mst_article article on article.article_id=geninfo.article_id   
LEFT join ngdrstab_mst_articledescriptiondetail as title ON title.articledescription_id=geninfo.title_id
join ngdrstab_trn_fileuploadinfo  uploadinfo on uploadinfo.token_no=geninfo.token_no  and uploadinfo.id=(select max(id) from ngdrstab_trn_fileuploadinfo where token_no=geninfo.token_no)
WHERE 
geninfo.office_id=?   and sro_approve_flag=?

 
order by uploadinfo.created ASC ", array($office_id, 'N'));
        }



        $this->set("rpt_data", $rpt_data);
        $this->set("lang", $lang);
        if (isset($rpt_data)) {
            $i = 1;
            foreach ($rpt_data as $document) {
                if ($i == 1) {
                    $i++;
                    $document = $document[0];
                    $this->Session->write("approve_token", $document['token_no']);
                }
            }
        }
    }

    public function update_estamp_lock_status() {
        $this->loadModel('payment');
        try {
            if ($this->request->is('post')) {
                $data = $this->request->data;
                if (isset($data['update_estamp_lock'])) {
                    $data = $data['update_estamp_lock'];
                    $fieldlist['manually_lock_remark']['text'] = 'is_required,is_alphanumericspace';
                    $fieldlist['certificate_no']['text'] = 'is_required';
                    $fieldlist['lock_date']['text'] = 'is_required';
                    $errarr = $this->validatedata($data, $fieldlist);
                    if ($this->validationError($errarr)) {
                        $token_no = $this->Session->read("tokenlastestamp");
                        $lockdate = date('Y-m-d H:s:i', strtotime($data['lock_date']));
                        $user_id = $this->Auth->user('user_id');
                        $this->payment->updateAll(
                                array('defacement_flag' => "'Y'", 'defacement_time' => "'" . $lockdate . "'", 'certificate_lock_date' => "'" . $lockdate . "'", 'manually_lock_remark' => "'" . $data['manually_lock_remark'] . "'", 'org_user_id' => $user_id), array('token_no' => $token_no, 'certificate_no' => $data['certificate_no'])
                        );

                        $this->payment->updateAll(
                                array('defacement_flag' => "'Y'", 'defacement_time' => "'" . $lockdate . "'", 'certificate_lock_date' => "'" . $lockdate . "'", 'manually_lock_remark' => "'" . $data['manually_lock_remark'] . "'", 'org_user_id' => $user_id), array('token_no' => $token_no, 'base_certificate_no' => $data['certificate_no'])
                        );

                        $this->Session->setFlash(__('Certificate Lock Details Updated')
                        );
                        return $this->redirect(array('controller' => 'Registration', 'action' => 'update_estamp_lock_status'));
                    } else {
                        $this->Session->setFlash(__('Please check validations. ')
                        );
                    }
                } else if (is_numeric($data['token_no'])) {
                    $this->Session->write("tokenlastestamp", $data['token_no']);
                    $results = $this->payment->find("all", array('conditions' => array('token_no' => $data['token_no'], 'payment_mode_id' => 6, 'defacement_flag' => 'N')));
                    $this->set("results", $results);
                    if (isset($results)) {
                        $fieldlist = array();
                        $counter = 0;
                        foreach ($results as $result) {
                            $counter++;
                            $fieldlist['update_estamp_lock' . $counter]['manually_lock_remark' . $counter]['text'] = 'is_required,is_alphanumericspace';
                            $fieldlist['update_estamp_lock' . $counter]['certificate_no' . $counter]['text'] = 'is_required';
                            $fieldlist['update_estamp_lock' . $counter]['lock_date' . $counter]['text'] = 'is_required';
                        }
                    }
                    $this->set("fieldlistmultiform", $fieldlist);
                    $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Something Went Wrong')
            );
        }
    }

    public function application_revert_status() { // for JH state
        try {
            array_map([$this, 'loadModel'], ['ApplicationSubmitted', 'appointment', 'RevertBackReasons', 'DocumentEditStatus', 'genernalinfoentry', 'DataEntryReject']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $user_id = $this->Auth->User("user_id");
            $state_id = $this->Auth->User("state_id");
            $this->set('hfaction', NULL);
            $this->set('hfid', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);

            $doc_revt_reasons = $this->RevertBackReasons->find("list", array('fields' => array('revertback_id', 'revertback_desc_' . $lang)));
            $this->set('Reasons', $doc_revt_reasons);

            $fieldlist = array();
            $fieldlist['tok_no']['text'] = 'is_required,is_digit';
            $fieldlist['doc_edit_remark']['text'] = 'is_required,is_alphanumericspace,is_maxlength200';
            $fieldlist['revertback_id']['select'] = 'is_select_req';

            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //$data = $this->request->data;
                $errarr = $this->validatedata($this->request->data['application_revert_status'], $fieldlist);

                //   if ($this->validationError($errarr)) {
                //   pr($this->request->data);
                $this->set('actiontypeval', $_POST['actiontype']);

                if ($_POST['actiontype'] == 1) {
                    $token = $this->request->data['application_revert_status']['tok_no'];
                    $searchbytok = $this->ApplicationSubmitted->query("select token_no, apps.office_id, off.office_name_en,org_user_id from ngdrstab_trn_application_submitted apps
                                    join ngdrstab_mst_office as off on off.office_id = apps.office_id
                                    where apps.document_entry_flag='Y' and apps.final_stamp_flag='N' and apps.token_no=$token");

                    if (!empty($searchbytok)) {
                        $this->set('tokengrid', $searchbytok);
                    } else {
                        $this->Session->setFlash(__('lblnotfoundmsg!!!'));
                        $this->redirect(array('controller' => 'Registration', 'action' => 'application_revert_status'));
                    }
                }
                if ($_POST['actiontype'] == '2') {
                    $formDataC = array();
                    $doc_remark = $this->request->data['document_edit_revert']['doc_edit_remark'];
                    $reason_id = $this->request->data['document_edit_revert']['revertback_id'];
                    $token_no = $this->request->data['application_revert_status']['tok_no'];

                    $app = $this->ApplicationSubmitted->find('first', array('fields' => array('app_id', 'office_id'), 'conditions' => array('token_no' => $token_no)));
                    $app_id = $app['ApplicationSubmitted']['app_id'];
                    $office_id = $app['ApplicationSubmitted']['office_id'];

                    $formDataC['token_no'] = $token_no;
                    $formDataC['document_entry_remark'] = $doc_remark;
                    $formDataC['req_ip'] = $this->request->clientIp();
                    $formDataC['app_id'] = $app_id;
                    $formDataC['created'] = date('Y-m-d H:i:s');
                    $formDataC['user_id'] = $user_id;
                    $formDataC['state_id'] = $state_id;
                    $formDataC['updated'] = date('Y-m-d H:i:s');
                    $formDataC['revertback_id'] = $reason_id;
                    //  $formDataC['revert_by'] = 'master_form';

                    if ($this->DataEntryReject->save($formDataC)) {
                        $changestatus = array();
                        $changestatus['last_status_id'] = 1;
                        $changestatus = $this->add_default_fields_updateAll($changestatus);
                        $this->genernalinfoentry->updateAll(
                                $changestatus, array('token_no' => $token_no)
                        );

                        $this->save_documentstatus($reason_id, $token_no, $office_id);

                        $this->ApplicationSubmitted->deleteAll(array('token_no' => $token_no));
                        $this->appointment->deleteAll(array('token_no' => $token_no));

                        $this->Session->setFlash(__('Application Reverted!!!'));
                        $this->redirect(array('controller' => 'Registration', 'action' => 'application_revert_status'));
                    } else {
                        $this->Session->setFlash('Fail To Revert!!!');
                        $this->redirect(array('controller' => 'Registration', 'action' => 'application_revert_status'));
                    }
                }
                //  }
            }
        } catch (Exception $ex) {
            pr($ex);
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function DocumentNumberFinal($id = null) {
        try {

            $this->loadModel('DocumentNumberFinal');

            $this->request->data['DocumentNumberFinal']['state_id'] = $this->Auth->User("state_id");

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            $arrCategory = array('Y' => "Yes", 'N' => "No");
            $this->set('arrCategory', $arrCategory);

            $this->set("fieldlist", $fieldlist = $this->DocumentNumberFinal->fieldlist());
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                //pr($this->request->data);exit;
                $verrors = $this->validatedata($this->request->data['DocumentNumberFinal'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->DocumentNumberFinal->get_duplicate();
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['DocumentNumberFinal']);
                    if ($checkd) {
//            if($this->request->is('post'))
//            {
//            $maxidresult = $this->DocumentNumberFinal->find("all", array('fields'=> array('MAX(format_field_id) AS maxid'),'group by' => 'format_field_id'));
//            $this->request->data['DocumentNumberFinal']['format_field_id']=$maxidresult[0][0]['maxid']+1;
//            //pr($this->request->data);exit;
//            }
                        if ($this->DocumentNumberFinal->save($this->request->data['DocumentNumberFinal'])) {
                            $this->Session->setFlash(
                                    __("$actionvalue ")
                            );

                            return $this->redirect(array('controller' => 'Registration', 'action' => 'documentnumberfinal'));
                        } else {
                            $this->Session->setFlash(
                                    __("lblnotsavemsg")
                            );
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }

            $result = $this->DocumentNumberFinal->find("all", array('order' => 'display_order ASC'));
            $this->set("DocumentNumberFormatFinalResult", $result);
            $DocNoWillBe = '';
            foreach ($result as $res) {
                $sep = array_search('SP', $res['DocumentNumberFinal']);
                if ($sep != '') {
                    if ($res['DocumentNumberFinal']['format_field_flag'] == 'Y') {
                        $sep = $res['DocumentNumberFinal']['static_value'];
                        break;
                    }
                }
            }
            foreach ($result as $res) {
                $DocNoWillBe = $DocNoWillBe . ($res['DocumentNumberFinal']['format_field_flag'] == 'Y' ? $sep . $res['DocumentNumberFinal']['format_field_desc'] : '');
            }
            $DocNoWillBe = str_replace($sep . 'Seperater', '', $DocNoWillBe);
            $DocNoWillBe = ltrim($DocNoWillBe, $sep);

            $this->set("DocNoWillBe", $DocNoWillBe);

            if (!is_null($id) && is_numeric($id)) {
                $resultedit = $this->DocumentNumberFinal->find("first", array('conditions' => array('format_field_id' => $id)));
                if (!empty($resultedit)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['DocumentNumberFinal'] = $resultedit['DocumentNumberFinal'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
//         pr($resultedit);exit;
            }
        } catch (Exception $ex) {
            pr($ex);
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function DocumentNumber($id = null) {
        $this->loadModel('DocumentNumber');

        $this->request->data['DocumentNumber']['state_id'] = $this->Auth->User("state_id");

        if (!empty($id)) {
            $actionvalue = 'lbleditmsg';
        } else {
            $actionvalue = 'lblsavemsg';
        }

        $arrCategory = array('Y' => "Yes", 'N' => "No");
        $this->set('arrCategory', $arrCategory);

        $this->set("fieldlist", $fieldlist = $this->DocumentNumber->fieldlist());
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if ($this->request->is('post') || $this->request->is('put')) {
//            pr($this->request->data);exit;
            $verrors = $this->validatedata($this->request->data['DocumentNumber'], $fieldlist);
//pr($verrors);exit;
            if ($this->ValidationError($verrors)) {
                $duplicate = $this->DocumentNumber->get_duplicate();
                $checkd = $this->check_duplicate($duplicate, $this->request->data['DocumentNumber']);
                if ($checkd) {
//            if($this->request->is('post'))
//            {
//            $maxidresult = $this->DocumentNumber->find("all", array('fields'=> array('MAX(format_field_id) AS maxid'),'group by' => 'format_field_id'));
//            $this->request->data['DocumentNumber']['format_field_id']=$maxidresult[0][0]['maxid']+1;
                    //pr($this->request->data);exit;
//            }
                    if ($this->DocumentNumber->save($this->request->data['DocumentNumber'])) {
                        $this->Session->setFlash(
                                __("$actionvalue ")
                        );

                        return $this->redirect(array('controller' => 'Registration', 'action' => 'documentnumber'));
                    } else {
                        $this->Session->setFlash(
                                __("lblnotsavemsg")
                        );
                    }
                } else {
                    $this->Session->setFlash(__('lblduplicatemsg'));
                }
            } else {
                $this->Session->setFlash(__('Find validations '));
            }
        }

        $result = $this->DocumentNumber->find("all", array('order' => 'display_order ASC'));
        $this->set("DocumentNumberFormatResult", $result);
        $DocNoWillBe = '';
        foreach ($result as $res) {
            $sep = array_search('SP', $res['DocumentNumber']);
            if ($sep != '') {
                if ($res['DocumentNumber']['format_field_flag'] == 'Y') {
                    $sep = $res['DocumentNumber']['static_value'];
                    break;
                }
            }
        }
        foreach ($result as $res) {
            $DocNoWillBe = $DocNoWillBe . ($res['DocumentNumber']['format_field_flag'] == 'Y' ? $sep . $res['DocumentNumber']['format_field_desc'] : '');
        }
        $DocNoWillBe = str_replace($sep . 'Seperater', '', $DocNoWillBe);
        $DocNoWillBe = ltrim($DocNoWillBe, $sep);

        $this->set("DocNoWillBe", $DocNoWillBe);

        if (!is_null($id) && is_numeric($id)) {
            $resultedit = $this->DocumentNumber->find("first", array('conditions' => array('format_field_id' => $id)));
            if (!empty($resultedit)) {
                $this->set('editflag', 'Y');
                $this->request->data['DocumentNumber'] = $resultedit['DocumentNumber'];
            } else {
                $this->Session->setFlash(
                        __('lblnotfoundmsg')
                );
            }
//         pr($resultedit);exit;
        }
    }

    public function DocumentNumberFormat_Delete($id = NULL) {
        $this->loadModel('DocumentNumber');
        if (!is_null($id) && is_numeric($id)) {
            if ($this->DocumentNumber->deleteAll(array('format_field_id' => $id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }
        return $this->redirect(array('controller' => 'Registration', 'action' => 'documentnumber'));
    }

    public function srochecklist($checklist_id = NULL) {
        try {
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
            $this->loadModel('SroChecklist');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $Developedland = $this->SroChecklist->find('list', array('fields' => array('SroChecklist.checklist_id', 'SroChecklist.checklist_desc_' . $laug), 'order' => array('checklist_desc_en' => 'ASC')));
            $this->set('SroChecklist', $Developedland);
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
            $landtdata = $this->SroChecklist->query("select * from ngdrstab_mst_sro_checklist");
            $this->set('landtdata', $landtdata);
            $this->set("fieldlist", $fieldlist = $this->SroChecklist->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data['srochecklist']['ip_address'] = $this->request->clientIp();
                $this->request->data['srochecklist']['created_date'] = $created_date;
                $this->request->data['srochecklist']['user_id'] = $user_id;

                $verrors = $this->validatedata($this->request->data['srochecklist'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->SroChecklist->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['srochecklist']);

                    if ($checkd) {
                        if ($this->SroChecklist->save($this->request->data['srochecklist'])) {
                            $lastid = $this->SroChecklist->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            }else{
                                 $this->Session->setFlash(__('lbleditmsg'));
                            }

                            return $this->redirect(array('controller' => 'Registration','action' => 'srochecklist'));
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
            if (!is_null($checklist_id) && is_numeric($checklist_id)) {

                $this->Session->write('checklist_id', $checklist_id);
                $result = $this->SroChecklist->find("first", array('conditions' => array('checklist_id' => $checklist_id)));
                // pr($result);exit;
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['srochecklist'] = $result['SroChecklist'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //pr($ex);
           // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_srochecklist($checklist_id = null) {
        $this->autoRender = false;
        $this->loadModel('SroChecklist');
        try {

            if (isset($checklist_id) && is_numeric($checklist_id)) {
                $this->SroChecklist->checklist_id = $checklist_id;
                if ($this->SroChecklist->delete($checklist_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'Registration', 'action' => 'srochecklist'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //Pravav
    public function disposal_type($disposal_id = NULL) {
        try {
//             $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('DocumentDisposal');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('display_flag', null);
            $this->set('DocumentDisposal', $this->DocumentDisposal->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->DocumentDisposal->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($disposal_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->data);exit;
//                $this->request->data['disposal_type']['ip_address'] = $this->request->clientIp();
//                $this->request->data['disposal_type']['created_date'] = $created_date;
//                $this->request->data['disposal_type']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['disposal_type'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->DocumentDisposal->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['disposal_type']);
                    if ($checkd) {
                        if ($this->DocumentDisposal->save($this->request->data['disposal_type'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Holiday Type saved Successful.'));
                            return $this->redirect(array('action' => 'disposal_type'));
                            $lastid = $this->DocumentDisposal->getLastInsertId();
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
            if (!is_null($disposal_id) && is_numeric($disposal_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('disposal_id', $disposal_id);
                $result = $this->DocumentDisposal->find("first", array('conditions' => array('disposal_id' => $disposal_id)));

                $this->set('display_flag', $result['DocumentDisposal']['display_flag']);


                $this->request->data['disposal_type'] = $result['DocumentDisposal'];
            }
        } catch (exception $ex) {

//            pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_disposal_type($disposal_id = null) {
        $this->autoRender = false;
        $this->loadModel('DocumentDisposal');
        try {
            if (isset($disposal_id) && is_numeric($disposal_id)) {
                $this->DocumentDisposal->disposal_id = $disposal_id;
                if ($this->DocumentDisposal->delete($disposal_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'disposal_type'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function disposal_reason($reason_id = NULL) {
        try {
//             $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('DocumentDisposal');
            $this->loadModel('DocumentDisposalReasons');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('display_flag', null);
            $this->set('DocumentDisposalReasons', $this->DocumentDisposalReasons->find('all'));

            $res = $this->DocumentDisposalReasons->query("select re.*,dis.* from ngdrstab_mst_document_disposal_reasons as re
 JOIN ngdrstab_mst_document_disposal as dis ON dis.disposal_id=re.disposal_id");
            $this->set('res', $res);

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

            $this->set("fieldlist", $fieldlist = $this->DocumentDisposalReasons->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $this->set('disposaltype', ClassRegistry::init('DocumentDisposal')->find('list', array('fields' => array('disposal_id', 'disposal_desc_' . $laug), 'order' => array('disposal_desc_' . $laug => 'ASC'))));
//          pr($b);exit;
            if (!empty($reason_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

//                pr($this->request->data);exit;
//                $this->request->data['disposal_type']['ip_address'] = $this->request->clientIp();
//                $this->request->data['disposal_type']['created_date'] = $created_date;
//                $this->request->data['disposal_type']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['disposal_reason'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->DocumentDisposalReasons->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['disposal_reason']);
                    if ($checkd) {
                        if ($this->DocumentDisposalReasons->save($this->request->data['disposal_reason'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Holiday Type saved Successful.'));
                            return $this->redirect(array('action' => 'disposal_reason'));
                            $lastid = $this->DocumentDisposalReasons->getLastInsertId();
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
            if (!is_null($reason_id) && is_numeric($reason_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('reason_id', $reason_id);
                $result = $this->DocumentDisposalReasons->find("first", array('conditions' => array('reason_id' => $reason_id)));

                $this->set('display_flag', $result['DocumentDisposalReasons']['display_flag']);


                $this->request->data['disposal_reason'] = $result['DocumentDisposalReasons'];
            }
        } catch (exception $ex) {

//            pr($ex);
//            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_disposal_reason($reason_id = null) {
        $this->autoRender = false;
        $this->loadModel('DocumentDisposalReasons');
        try {
            if (isset($reason_id) && is_numeric($reason_id)) {
                $this->DocumentDisposalReasons->reason_id = $reason_id;
                if ($this->DocumentDisposalReasons->delete($reason_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'disposal_reason'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //end
    //shakeel
    public function receiptnumber($id = null) {
        $this->loadModel('receiptnumber');

        $this->request->data['receiptnumber']['state_id'] = $this->Auth->User("state_id");

        if (!empty($id)) {
            $actionvalue = 'lbleditmsg';
        } else {
            $actionvalue = 'lblsavemsg';
        }

        $arrCategory = array('Y' => "Yes", 'N' => "No");
        $this->set('arrCategory', $arrCategory);

        $this->set("fieldlist", $fieldlist = $this->receiptnumber->fieldlist());
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if ($this->request->is('post') || $this->request->is('put')) {
            //pr($this->request->data);exit;
            $verrors = $this->validatedata($this->request->data['receiptnumber'], $fieldlist);
//pr($verrors);exit;
            if ($this->ValidationError($verrors)) {
                $duplicate = $this->receiptnumber->get_duplicate();
                $checkd = $this->check_duplicate($duplicate, $this->request->data['receiptnumber']);
                if ($checkd) {
//            if($this->request->is('post'))
//            {
//            $maxidresult = $this->DocumentNumberFinal->find("all", array('fields'=> array('MAX(format_field_id) AS maxid'),'group by' => 'format_field_id'));
//            $this->request->data['DocumentNumberFinal']['format_field_id']=$maxidresult[0][0]['maxid']+1;
//            //pr($this->request->data);exit;
//            }
                    if ($this->receiptnumber->save($this->request->data['receiptnumber'])) {
                        $this->Session->setFlash(
                                __($actionvalue)
                        );

                        return $this->redirect(array('controller' => 'Registration', 'action' => 'receiptnumber'));
                    } else {
                        $this->Session->setFlash(
                                __("lblnotsavemsg")
                        );
                    }
                } else {
                    $this->Session->setFlash(__('lblduplicatemsg'));
                }
            } else {
                $this->Session->setFlash(__('Find validations '));
            }
        }

        $result = $this->receiptnumber->find("all", array('order' => 'display_order ASC'));
        $this->set("receiptnumberResult", $result);
        $DocNoWillBe = '';
        foreach ($result as $res) {
            $sep = array_search('SP', $res['receiptnumber']);
            if ($sep != '') {
                if ($res['receiptnumber']['format_field_flag'] == 'Y') {
                    $sep = $res['receiptnumber']['static_value'];
                    break;
                }
            }
        }
        foreach ($result as $res) {
            $DocNoWillBe = $DocNoWillBe . ($res['receiptnumber']['format_field_flag'] == 'Y' ? $sep . $res['receiptnumber']['format_field_desc'] : '');
        }
        $DocNoWillBe = str_replace($sep . 'Seperater', '', $DocNoWillBe);
        $DocNoWillBe = ltrim($DocNoWillBe, $sep);

        $this->set("DocNoWillBe", $DocNoWillBe);

        if (!is_null($id) && is_numeric($id)) {
            $resultedit = $this->receiptnumber->find("first", array('conditions' => array('format_field_id' => $id)));
            if (!empty($resultedit)) {
                $this->set('editflag', 'Y');
                $this->request->data['receiptnumber'] = $resultedit['receiptnumber'];
            } else {
                $this->Session->setFlash(
                        __('lblnotfoundmsg')
                );
            }
//         pr($resultedit);exit;
        }
    }

    public function receiptnumber_Delete($id = NULL) {
        $this->loadModel('receiptnumber');
        if (!is_null($id) && is_numeric($id)) {
            if ($this->receiptnumber->deleteAll(array('format_field_id' => $id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }
        return $this->redirect(array('controller' => 'Registration', 'action' => 'receiptnumber'));
    }

//end
    //pranav


    public function document_status($id = NULL) {
        try {
//             $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('document_status_description');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('display_flag', null);
            $this->set('document_status_description', $this->document_status_description->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->document_status_description->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->data);exit;
//                $this->request->data['disposal_type']['ip_address'] = $this->request->clientIp();
//                $this->request->data['disposal_type']['created_date'] = $created_date;
//                $this->request->data['disposal_type']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['document_status'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->document_status_description->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['document_status']);
                    if ($checkd) {
                        if ($this->document_status_description->save($this->request->data['document_status'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Holiday Type saved Successful.'));
                            return $this->redirect(array('action' => 'document_status'));
                            $lastid = $this->document_status_description->getLastInsertId();
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
                $result = $this->document_status_description->find("first", array('conditions' => array('id' => $id)));

//                $this->set('document_status', $result['document_status_description']['display_flag']);


                $this->request->data['document_status'] = $result['document_status_description'];
            }
        } catch (exception $ex) {

//            pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_document_status($id = null) {
        $this->autoRender = false;
        $this->loadModel('document_status_description');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->document_status_description->id = $id;
                if ($this->document_status_description->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'document_status'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //end


    public function reg_main_menu() {
        try {
            $this->loadModel('RegistrationMainmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('mainmenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $mainmenurecord = $this->RegistrationMainmenu->find('all');
            $this->set('mainmenurecord', $mainmenurecord);
            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['mainmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['mainmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['controller']['text'] = 'is_required,is_alpha';
            $fieldlist['action']['text'] = 'is_required';
            $fieldlist['mm_serial']['text'] = 'is_required,is_positiveinteger';//is_numeric';


            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];

                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_main_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_main_menu']['user_id'] = $user_id;
                        $this->request->data['reg_main_menu']['created_date'] = $created_date;
                        $this->request->data['reg_main_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_main_menu']['mainmenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['RegistrationMainmenu'] = $this->istrim($this->request->data['reg_main_menu']);
//                          pr($this->request->data['RegistrationMainmenu']);exit;

                        $errarr = $this->validatedata($this->request->data['reg_main_menu'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
//                            pr( $this->request->data);exit;
                            if ($this->RegistrationMainmenu->save($this->request->data['RegistrationMainmenu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_main_menu'));
                                $this->set('mainmenurecord', $this->RegistrationMainmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $functionlist_used = $this->RegistrationMainmenu->find('list', array('fields' => array('action', 'action')));
            $this->set("functionlist_used", $functionlist_used);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_main_menu_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationMainmenu');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'officehierarchy') {

                if ($this->RegistrationMainmenu->deleteAll(array('mainmenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_main_menu'));
                }
                // }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_menu() {
        try {
            $this->loadModel('RegistrationSubmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');

            $this->set('mainmenuid', ClassRegistry::init('RegistrationMainmenu')->find('list', array('fields' => array('mainmenu_id', 'mainmenu_desc_en'), 'order' => array('mainmenu_desc_en' => 'ASC'))));
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('submenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $submenurecord = $this->RegistrationSubmenu->find('all');
            $this->set('submenurecord', $submenurecord);
            $fieldlist = array();

            $fieldlist['mainmenu_id']['select'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['submenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['submenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['sm_serial']['text'] = 'is_required,is_positiveinteger';//is_numeric';
            $fieldlist['is_stamp']['radio'] = 'is_required';
            $fieldlist['stamp_id']['select'] = 'is_select_req';



            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $this->request->data['reg_sub_menu']['stateid'] = $stateid;
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_sub_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_sub_menu']['user_id'] = $user_id;
                        $this->request->data['reg_sub_menu']['created'] = $created_date;
                        $this->request->data['reg_sub_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_sub_menu']['submenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['reg_sub_menu'] = $this->istrim($this->request->data['reg_sub_menu']);
                        //  pr($this->request->data['RegistrationSubmenu']);exit;
                        if (isset($this->request->data['reg_sub_menu']['is_stamp']) && $this->request->data['reg_sub_menu']['is_stamp'] == 'N') {
                            unset($this->request->data['reg_sub_menu']['stamp_id']);
                            unset($fieldlist['stamp_id']);
                        }

                        $errarr = $this->validatedata($this->request->data['reg_sub_menu'], $fieldlist);
//                         pr($this->request->data);
//                        pr($errarr);exit;
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            // pr($this->request->data);exit; 
                            if ($this->RegistrationSubmenu->save($this->request->data['reg_sub_menu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_sub_menu'));
                                $this->set('submenurecord', $this->RegistrationSubmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $stamp_id_used = $this->RegistrationSubmenu->find('list', array('fields' => array('stamp_id', 'stamp_id')));
            $this->set("stamp_id_used", $stamp_id_used);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_menu_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationSubmenu');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'officehierarchy') {


                if ($this->RegistrationSubmenu->deleteAll(array('submenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_menu'));
                } else {
                    $this->Session->setFlash(
                            __('lblnotdeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_menu'));
                }
                // }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_sub_menu() {
        try {
            $this->loadModel('RegistrationSubsubmenu');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $lang = $this->Session->read("sess_langauge");
            $this->set('submenuid', ClassRegistry::init('RegistrationSubmenu')->find('list', array('fields' => array('submenu_id', 'submenu_desc_en'), 'order' => array('submenu_desc_en' => 'ASC'))));

            $this->set('roledata', ClassRegistry::init('role')->find('list', array('fields' => array('role_id', 'role_name_' . $lang), 'conditions' => array('role_id' => array(999901, 999902, 999903)), 'order' => array('role_name_' . $lang => 'ASC'))));

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);

            $this->set('subsubmenurecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $subsubmenurecord = $this->RegistrationSubsubmenu->find('all');
            $this->set('subsubmenurecord', $subsubmenurecord);
            $fieldlist = array();

            $fieldlist['submenu_id']['text'] = 'is_select_req';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['subsubmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength255';
                } else {
                    $fieldlist['subsubmenu_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['controller']['text'] = 'is_required,is_alpha';
            $fieldlist['action']['text'] = 'is_required';
            $fieldlist['ssm_serial']['text'] = 'is_required,is_positiveinteger'; //--is_numeric';
            $fieldlist['function_order']['text'] = 'is_required,is_positiveinteger';//is_numeric';
            $fieldlist['role_id']['select'] = 'is_select_req';
            $fieldlist['function_sr_no']['text'] = 'is_required,is_numeric';
            //$fieldlist['function_hierarchy']['checkbox'] = 'is_numeric';






            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $this->request->data['reg_sub_sub_menu']['stateid'] = $stateid;
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['reg_sub_sub_menu']['req_ip'] = $this->request->clientIp();
                        $this->request->data['reg_sub_sub_menu']['user_id'] = $user_id;
                        $this->request->data['reg_sub_sub_menu']['created_date'] = $created_date;
                        $this->request->data['reg_sub_sub_menu']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['reg_sub_sub_menu']['subsubmenu_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['reg_sub_sub_menu'] = $this->istrim($this->request->data['reg_sub_sub_menu']);
                        //pr($this->request->data['reg_sub_sub_menu']);exit;
                        $errarr = $this->validatedata($this->request->data['reg_sub_sub_menu'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {//                            
                            if (isset($this->request->data['reg_sub_sub_menu']['function_hierarchy']) && is_array($this->request->data['reg_sub_sub_menu']['function_hierarchy'])) {
                                $this->request->data['reg_sub_sub_menu']['function_hierarchy'] = implode("-", $this->request->data['reg_sub_sub_menu']['function_hierarchy']);
                            }
                            if ($this->RegistrationSubsubmenu->save($this->request->data['reg_sub_sub_menu'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'Registration', 'action' => 'reg_sub_sub_menu'));
                                $this->set('subsubmenurecord', $this->RegistrationSubsubmenu->find('all'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                }
            }

            $functionid_used = $this->RegistrationSubsubmenu->find('list', array('fields' => array('function_sr_no', 'function_sr_no')));
            $this->set("functionid_used", $functionid_used);

            $functionlist_used = $this->RegistrationSubsubmenu->find('list', array('fields' => array('action', 'action')));
            $this->set("functionlist_used", $functionlist_used);


            $function_hierarchy = $this->RegistrationSubsubmenu->find('list', array('fields' => array('function_sr_no', 'subsubmenu_desc_' . $laug), 'order' => 'function_sr_no ASC'));
            $this->set("function_hierarchy", $function_hierarchy);

            $stamp_conf = $this->stamp_and_functions_config();

            $this->set("stamp_conf", $stamp_conf);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function reg_sub_sub_menu_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('RegistrationSubsubmenu');
        try {
            if (isset($id) && is_numeric($id)) {
                if ($this->RegistrationSubsubmenu->deleteAll(array('subsubmenu_id' => $id))) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'reg_sub_sub_menu'));
                }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
