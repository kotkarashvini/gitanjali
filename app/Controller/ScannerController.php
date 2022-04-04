<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class ScannerController extends AppController {

    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('mainlanguage');
        $this->Session->renew();
        $this->Security->unlockedActions = array('checkscan', 'checkusername', 'activate_biometric_user', 'ngdrsclient', 'checkpasswordauth', 'checkbiometricauth', 'role', 'delete_role', 'scannerclient', 'webcamclient', 'officedisplay', 'roledisplay', 'deactivate', 'login', 'otpsave', 'language', 'checkusercitizen', 'checkemailcitizen', 'checkmobilenocitizen', 'citizenlogin', 'biometriclogin', 'biometricregistration', 'langaugechange', 'empregistration', 'activate', 'checkcaptcha', 'checkemail', 'checkmobileno', 'checkuser', 'welcome', 'new_user', 'userpermission', 'assign_role', 'resetpassword', 'citizenregistration', 'termsandconditions', 'policies', 'accessabilitystmt', 'aboutus', 'contactus', 'feedback', 'help', 'sidemap', 'send_sms', 'secugenclient', 'normalappointment', 'get_available_appointment', 'appointment');
        $this->Auth->allow('scan', 'checkscan', 'upload', 'loadfile', 'scanattachimg','combinepdf');
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

    public function scanattachimg() {
        try {
            $this->loadModel('file_config');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfdelete', NULL);


            if ($this->request->is('post')) {

                if (!empty($this->data)) {
                    if ($_POST['actiontype'] == '1') {
                        //Check if image has been uploaded
                        if (!empty($this->data['scanattachimg']['scanfile']['name']) && !empty($this->data['scanattachimg']['logo_path']['name'])) {

                            $scanfile = $this->data['scanattachimg']['scanfile']['name'];
                            $qrimage = $this->data['scanattachimg']['logo_path']['name']; //put the  data into a var for easy use
                            $destname = $this->data['scanattachimg']['destname'];
                            $path = $this->file_config->find('first', array('fields' => array('filepath')));
                            $src = $path['file_config']['filepath'] . "OutsideScanned/Temp/" . $scanfile;
                            $dest = $path['file_config']['filepath'] . "OutsideScanned/QRattachDoc/" . $destname . ".pdf";
                            $img = $path['file_config']['filepath'] . "OutsideScanned/QRimage/" . $qrimage;
//                            pr($src);pr($dest);pr($img);exit;
                            $path1 = $path['file_config']['filepath'] . "OutsideScanned/qrcodeattach.jar";
                            $message = exec('java -jar ' . $path1 . ' ' . $src . ' ' . $dest . ' ' . $img, $result);
//                    pr($message);exit;   7720078687/9822867687
                            $this->Session->setFlash(__($message));
                        } else {
                            $this->Session->setFlash(__('The data could not be saved. Please, Choose your image.'), 'default', array('class' => 'errors'));
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Masters', 'action' => 'scanattachimg'));
                }
                
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }
    
     public function combinepdf() {
        try {
            $this->loadModel('file_config');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfdelete', NULL);


            if ($this->request->is('post')) {

                if (!empty($this->data)) {
                    if ($_POST['actiontype'] == '1') {
                        //Check if image has been uploaded
//                        pr($this->data);exit;
                        if (!empty($this->data['combinepdf']['srcfilename1']['name']) && !empty($this->data['combinepdf']['srcfilename2']['name'])) {

                            $src1 = $this->data['combinepdf']['srcfilename1']['tmp_name'];
                            $src2 = $this->data['combinepdf']['srcfilename2']['tmp_name']; //put the  data into a var for easy use
                            $path = $this->file_config->find('first', array('fields' => array('filepath')));
//                            $srcfilename1 = $path['file_config']['filepath'] . "OutsideScanned/Temp/" . $src1;
//                            $srcfilename2 = $path['file_config']['filepath'] . "OutsideScanned/QRattachDoc/" . $src2;
                            $destfile = $path['file_config']['filepath'] . "OutsideScanned/QRattachDoc/result.pdf";
//                            pr($src);pr($dest);pr($img);exit;
                            $path1 = $path['file_config']['filepath'] . "OutsideScanned/combinepdf.jar";
                            $message = exec('java -jar ' . $path1 . ' ' . $src1 . ' ' . $src2 . ' ' . $destfile, $result);
//                    pr($message);exit;   7720078687/9822867687
                            $this->Session->setFlash(__($message));
                        } else {
                            $this->Session->setFlash(__('The data could not be saved. Please, Choose your image.'), 'default', array('class' => 'errors'));
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Masters', 'action' => 'combinepdf'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }
    

    //=================================== Scanner Start=================================================================

    public function scan() {
        try {

//            $this->check_function_hierarchy($this->request->params['action'], $token);
            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');
            $this->set('rval', 'SC');
            $office_id = $this->Session->read("office_id");

            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $createFolder = $path['file_config']['filepath'] . "OutsideScanned";
            if (!file_exists($createFolder)) {
                mkdir($createFolder, 0744, true); // creates folder if  not found
            }
            $this->set('path', $createFolder);

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
            if ($this->request->is('post')) {
                $docid = $this->request->data['scan']['doc_id'];
                $check = $this->ApplicationSubmitted->query("select count(doc_reg_no) from ngdrstab_trn_scanuploadinfo WHERE doc_reg_no=? ", array($docid));
                if ($check[0][0]['count'] != 0) {
                    $updatecheck = $this->ApplicationSubmitted->query("update ngdrstab_trn_scanuploadinfo set conf_flag='Y' WHERE doc_reg_no=? ", array($docid));
                    $this->Session->setFlash(__("Document Uploaded Permenently"));
                } else {
                    $this->Session->setFlash(__("Document not Scanned Properly"));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function checkscan() {
        try {
            $this->autoRender = false;
            $this->loadModel('scan_upload');

            if (isset($_POST['path']) && isset($_POST['docid'])) {
                $filename = $_POST['path'] . "/QRattachDoc/" . $_POST['docid'] . ".pdf";
                $file = $this->scan_upload->query("SELECT count(scan_name) FROM ngdrstab_trn_scanuploadinfo WHERE scan_name=?", array($filename));
                if ($file[0][0]['count'] != 0 && file_exists($filename)) {
                    echo json_encode(1);   //file exist 
                    exit;
                } else {
                    echo json_encode(2);
                    exit;
                }
            }
        } catch (Exception $e) {
            pr($e);
            exit;
//            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
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
//            pr($this->request->data);;exit;
            $this->autoRender = false;
            $this->loadModel('scan_upload');
            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            $createFolder = $path['file_config']['filepath'] . "OutsideScanned";
            if (!file_exists($createFolder)) {
                mkdir($createFolder, 0744, true); // creates folder if  not found
            }
            $nopages = $_POST['nopages'];
//            $pages = $this->ApplicationSubmitted->query("SELECT no_of_pages FROM ngdrstab_trn_generalinformation WHERE token_no=? ", array($token));
//            $pages = $pages[0][0]['no_of_pages'];
            $pdfname = $_FILES['asprise_scans']['tmp_name'];
            $pdftext = file_get_contents($pdfname);
            $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
            if ($num != $nopages) {
                if ($num < $nopages) {
                    $misspages = $nopages - $num;
                    echo "Document required $nopages pages. You have Scanned $num pages. $misspages pages are Missing in this Document.";
                    return;
                } else if ($num > $nopages) {
                    $misspages = $num - $nopages;
                    echo "Document required $nopages pages. You have Scanned $num pages. $misspages pages are more added in this Document.";
                    return;
                }
            }

            $docid = $_POST['docid'];
            $qrimage = 'qrcode.png';
            $src = $pdfname;
            $dest = $path['file_config']['filepath'] . "OutsideScanned/QRattachDoc/" . $docid . ".pdf";
            $img = $path['file_config']['filepath'] . "OutsideScanned/QRimage/" . $qrimage;
            $path1 = $path['file_config']['filepath'] . "OutsideScanned/qrcodeattach.jar";
            $message = exec('java -jar ' . $path1 . ' ' . $src . ' ' . $dest . ' ' . $img, $result);
//            $udir = $createFolder . "/" . base64_encode($DOCIDNEW) . ".pdf";
//            $newname = base64_encode($DOCIDNEW) . ".pdf";
//            $save1 = move_uploaded_file($_FILES['asprise_scans']['tmp_name'], $udir);
//            $stateid = $this->Auth->User('state_id');
//            $userid = $this->Auth->User('user_id');
            $data['doc_reg_no'] = $_POST['docid'];
//            $data['token_no'] = $token;
            $data['scan_name'] = $dest;
//            $data['state_id'] = $stateid;
//            $data['user_id'] = $userid;
            $data['created_date'] = date('Y/m/d H:i:s');
            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            if ($_POST['saveflag'] == 'U') {
                $check = $this->scan_upload->query("SELECT id FROM ngdrstab_trn_scanuploadinfo where doc_reg_no=?", array($_POST['docid']));
                $data['id'] = $check[0][0]['id'];
            }
            $save2 = $this->scan_upload->save($data);

            if ($message && $_POST['saveflag'] == 'S') {
                $this->ApplicationSubmitted->query("UPDATE ngdrstab_trn_application_submitted SET document_scan_flag=? , document_scan_date=? WHERE doc_reg_no=?", array('Y', date('Y-m-d H:i:s'), $docid));
                echo "Document Saved Successfully...!!!";
            } else if ($message && $_POST['saveflag'] == 'U') {
                echo "Document Updated Successfully...!!!";
            } else {
                echo "Document Failed...Please try again...!!!";
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $exc->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//=================================== Scanner End=================================================================
}
