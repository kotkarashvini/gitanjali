<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyDocumentuploadController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
        //$this->Auth->allow('physub');
        //$this->Auth->allow('document_upload','check_filevalidation');
    }

    //upload Document
    function upload($csrftoken = NULL){
        try {
            $last_status_id = $this->Session->read('last_status_id');
            if ($this->Session->read('reschedule_flag') == 'Y') {
                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            $this->set('formid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $token = $this->Session->read('Leg_Selectedtoken');
            array_map(array($this, 'loadModel'), array('upload_document', 'Leg_generalinformation', 'article_doc_map', 'office', 'Leg_file_config', 'Leg_uploaded_file_trn', 'upload_file_format', 'regconfig'));
            $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id', 'upload_document.document_name_en', 'ad.is_required'), 'joins' => array(
                array(
                    'table' => 'ngdrstab_mst_article_document_mapping',
                    'alias' => 'ad',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='N' and ad.article_id is null")
                )), 'order' => array('upload_document.document_id' => 'ASC')));
            $this->set('upload_file1', $upload_file1);

            $upload_fileinfo = $this->Leg_uploaded_file_trn->find('all', array('conditions' => array('token_no' => $this->Session->read("Leg_Selectedtoken"))));
            $this->set('upload_fileinfo', $upload_fileinfo);

            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            if ($this->request->is('post')) {
                if ($this->request->data['upload']['upload_file']['error'] == 0) {
                    $formid = $_POST['formid'];
                    $fid = $_POST['file_id' . $formid];
                    $file_ext = pathinfo($this->request->data['upload']['upload_file']['name'], PATHINFO_EXTENSION);
                    $filename = str_replace(' ', '_', $this->request->data['upload']['upload_file']['name']);
                    $general = $this->Leg_generalinformation->find('first', array('fields' => array('Leg_generalinformation.office_id'), 'conditions' => array(
                        'Leg_generalinformation.token_no' => $token)));
                    $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                        'office.office_id' => $general['Leg_generalinformation']['office_id']), 'joins' => array(
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                    )));
                    $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                   // pr($path);exit();
                    $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Documents/');
                    if (!empty($general)) {
                        $dist = $this->create_folder($createFolder1, $office['dist']['district_name_en'] . '/');
                        $taluka = $this->create_folder($dist, $office['office']['taluka_id'] . '/');
                        $office = $this->create_folder($taluka, $general['Leg_generalinformation']['office_id'] . '/');
                        $final_folder1 = $this->create_folder($office, $token . '/');
                        $final_folder = $this->create_folder($final_folder1, 'Uploads/');
                        $new_name = $token . '_' . $fid;
                        if (file_exists($final_folder . '/' . $new_name)) {
                            unlink($final_folder . '/' . $new_name);
                        }
                        $success = move_uploaded_file($this->request->data['upload']['upload_file']['tmp_name'], $final_folder . '/' . $new_name . '.' . $file_ext);
                        if ($success == 1) {
                            $upload_info = $this->Leg_uploaded_file_trn->find('first', array('conditions' => array('token_no' => $token, 'document_id' => $fid)));
                            if (!empty($upload_info)) {
                                $this->Leg_uploaded_file_trn->deleteAll(array('token_no' => $token, 'document_id' => $fid));
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
                                'token_no' => $token
                            );
                            if (count($upload_info) > 0) {
                                $this->Leg_uploaded_file_trn->id = $upload_info['Leg_uploaded_file_trn']['id'];
                                if ($this->Leg_uploaded_file_trn->save($data)) {
                                    $this->Session->setFlash(__("File Updated  Successfully"));
                                    $this->redirect(array('controller' => 'LegacyDocumentupload', 'action' => 'upload', $this->Session->read('csrftoken')));
                                }
                            } else {
                                $data['org_updated'] = date('Y-m-d H:i:s');
                                if ($this->Leg_uploaded_file_trn->save($data)) {
                                    $this->Session->setFlash(__("File Uploaded Successfully"));
                                    $this->redirect(array('controller' => 'LegacyDocumentupload', 'action' => 'upload', $this->Session->read('csrftoken')));
                                }
                            }
                        }
                    } else {
                        $this->Session->setFlash(__("File format not suported"));
                        $this->redirect(array('controller' => 'LegacyDocumentUpload', 'action' => 'upload', $this->Session->read('csrftoken')));

                    }
                } else {
                    $this->check_csrf_token_withoutset($csrftoken);
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function check_filevalidation()
    {
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
                array_map(array($this, 'loadModel'), array('Leg_file_config', 'Leg_generalinformation', 'office'));
                $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                $general = $this->Leg_generalinformation->find('first', array('fields' => array('Leg_generalinformation.office_id'), 'conditions' => array(
                    'Leg_generalinformation.token_no' => $this->Session->read("Leg_Selectedtoken"))));

                $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                    'office.office_id' => $general['Leg_generalinformation']['office_id']), 'joins' => array(
                    array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                )));
                if (!empty($general)) {

                    $path = $path['Leg_file_config']['filepath'] . 'Documents' . '/' . $office['dist']['district_name_en'] . '/' . $office['office']['taluka_id'] . '/' . $general['Leg_generalinformation']['office_id'] . '/' . $this->Session->read("Leg_Selectedtoken") . '/' . 'Uploads' . '/' . $file;
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

    // Document upload for Legacy records uploaded by excel sheet
    function upload_documents( ){
        try {
            if ($this->Session->read('reschedule_flag') == 'Y') {
                return $this->redirect(array('action' => 'appointment', $this->Session->read('csrftoken')));
            }

            $this->set('formid', NULL);
            $lang = $this->Session->read("sess_langauge");
            $this->loadModels(array('upload_document', 'Leg_generalinformation', 'article_doc_map', 'office', 'Leg_file_config', 'Leg_uploaded_file_trn', 'upload_file_format', 'regconfig'));
            $docsToBeUploaded = $this->Leg_generalinformation->getLegacyDocToBeUploadList();
            $this->set('docsToBeUploaded', $docsToBeUploaded);
            $upload_fileinfo = $this->Leg_uploaded_file_trn->find('all', array('conditions' => array('token_no' => $this->Session->read("Leg_Selectedtoken"))));
            $this->set('upload_fileinfo', $upload_fileinfo);
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            if ($this->request->is('post')) {
                $this->Session->write('Leg_Selectedtoken', $this->request->data['token_no']);
                $this->redirect(array('controller'=>'LegacyDocumentupload','action'=>'upload'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
}