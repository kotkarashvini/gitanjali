<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Vendor', 'reader');
App::uses('Vendor', 'Autoload');
App::import('Vendor', 'SimpleXLSX');

//include('Net/SSH2.php');

class SearchlegacyfinaluploadController extends AppController {

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
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $this->Session->renew();
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        $this->Auth->allow('upload_excel_to_tbl', 'get_party_type', 'test_file');
        $laug = $this->Session->read("sess_langauge");

        if (is_null($laug)) {
            $this->Session->write("sess_langauge", 'en');
        }

        if (isset($this->Security)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    function upload_excel_to_tbl_data1() {
        $this->loadModel('Leg_uploaded_file_trn');
        $this->loadModel('office');
        $this->loadModel('Leg_file_config');
        if ($this->request->is('post')) {
            $info_fileupload = $this->Leg_uploaded_file_trn->query("select doc_reg_no,doc_reg_date,doc_processing_year,state_id,office_id,document_id,input_fname,book_code from ngdrstab_trn_tmp_legacy_fileuploadinfo where file_transfer_flag='N'");
            if (!empty($info_fileupload)) {
                foreach ($info_fileupload as $filenm) {
                    $get_district = $this->Leg_uploaded_file_trn->query("select district_id from ngdrstab_mst_office where office_id=" . $filenm[0]['office_id']);
                    $path = 'D:' . '/' . $get_district[0][0]['district_id'] . '/' . $filenm[0]['office_id'] . '/' . $filenm[0]['doc_processing_year'] . '/' . $filenm[0]['input_fname'];
                    if (file_exists($path)) {
                        $filepath = file_get_contents('D:' . '/' . $get_district[0][0]['district_id'] . '/' . $filenm[0]['office_id'] . '/' . $filenm[0]['doc_processing_year'] . '/' . $filenm[0]['input_fname']);

                        $file_tokenno = $this->Leg_uploaded_file_trn->query("select info.token_no from ngdrstab_trn_legacy_generalinformation info inner join ngdrstab_trn_legacy_serial_numbers_final serialno on info.token_no=serialno.token_no and info.office_id=serialno.office_id inner join ngdrstab_conf_admblock3_district dist on dist.district_id=info.district_id
                        where year=" . $filenm[0]['doc_processing_year'] . "and info.office_id=" . $filenm[0]['office_id'] . "and book_number=" . "'" . $filenm[0]['book_code'] . "'" . "and book_serial_number=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and info.state_id=" . $filenm[0]['state_id']);

                        $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                                'office.office_id' => $filenm[0]['office_id']), 'joins' => array(
                                array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                        )));

                        if (!empty($file_tokenno)) {
                            $get_document_id = $this->Leg_uploaded_file_trn->query("select document_id,input_fname from ngdrstab_trn_legacy_fileuploadinfo where token_no=" . $file_tokenno[0][0]['token_no']);
                            $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                            $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Documents/');
                            $dist = $this->create_folder($createFolder1, $office['dist']['district_name_en'] . '/');
                            $taluka = $this->create_folder($dist, $office['office']['taluka_id'] . '/');
                            $office = $this->create_folder($taluka, $office['office']['office_id'] . '/');
                            $final_folder1 = $this->create_folder($office, $file_tokenno[0][0]['token_no'] . '/');
                            $final_folder = $this->create_folder($final_folder1, 'Uploads/');
                            $new_name = $file_tokenno[0][0]['token_no'] . '_' . $get_document_id[0][0]['document_id'];
                            $localfile = fopen($final_folder . '/' . $new_name . '.' . 'pdf', "w");
                            if (fwrite($localfile, $filepath)) {
                                $string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_tmp_legacy_fileuploadinfo set file_transfer_flag=" . "'" . 'Y' . "'" . ", out_fname=" . "'" . $new_name . "'" . ",file_transfer_date=" . "'" . date('Y-m-d H:i:s') . "'" . "where doc_reg_no=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and document_id=" . $get_document_id[0][0]['document_id']);
                                $string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_legacy_fileuploadinfo set out_fname='$new_name' where token_no=" . $file_tokenno[0][0]['token_no']);

                                $this->Session->setFlash('File Saved Successfully..');
                            } else {

                                $this->Session->setFlash('File Not Uploaded..');
                                fclose($localfile);
                            }
                        }
                    }
                }
            } else {
                $this->Session->setFlash('No Records Found');
            }
        }
    }

}
