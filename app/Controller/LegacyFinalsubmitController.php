<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyFinalsubmitController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
        //$this->Auth->allow('physub');
        //$this->Auth->allow('Legency_data_entry_new');
    }

    public function finalsubmit() {
         try
            {
//             $this->loadModel('Leg_fee_calculation');
//             $fee_data = $this->Leg_fee_calculation->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
//            if (empty($fee_data)) {
//                $this->Session->setFlash(__('Please enter fee Details first.'));
//                return $this->redirect('../LegacyFeedetails/fee');
//            }

            // pr($this->Session->read("Selectedtoken"));exit;
            //checking mandatory document
            array_map(array($this, 'loadModel'), array('CitizenUser', 'stamp_duty', 'uploaded_file_trn', 'upload_document', 'smsevent', 'property_details_entry', 'party_category_fields', 'CitizenPaymentEntry', 'article_fee_items', 'article_fee_rule', 'article_fee_subrule', 'party_entry', 'office', 'office_village_map', 'ApplicationSubmitted', 'genernalinfoentry', 'BankPayment', 'conf_reg_bool_info'));
            $uploaded_file = $this->uploaded_file_trn->find("all", array('conditions' => array('token_no' => $this->Session->read("Leg_Selectedtoken"))));
//pr($this->Session->read("Selectedtoken"));exit;
            $u1 = array();
            if (!empty($uploaded_file)) {
                for ($j = 0; $j < count($uploaded_file); $j++) {
                    $u1[$j] = $uploaded_file[$j]['uploaded_file_trn']['document_id'];
                    //pr($u1[$j]);exit;
                }
            }

            $upload_file1 = $this->upload_document->find('all', array('fields' => array('upload_document.document_id'), 'joins' => array(
                array(
                    'table' => 'ngdrstab_mst_article_document_mapping',
                    'alias' => 'ad',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array("ad.document_id = upload_document.document_id and partywise_flag='Y' and ad.is_required='Y' and ad.article_id=" . 0)
                )), 'order' => array('upload_document.document_id' => 'ASC')));
            $u = array();
            if (!empty($upload_file1)) {
                for ($i = 0; $i < count($upload_file1); $i++) {
                    $u[$i] = $upload_file1[$i]['upload_document']['document_id'];
                    // pr($u[$i]);exit;
                }
            }
            $containsSearch = count(array_intersect($u1, $u)) == count($u);

            if (count(array_intersect($u1, $u)) != count($u)) {
                $this->Session->setFlash("Please Upload All Mandatory Documents");
                // pr("Amita");exit;
                //$this->redirect(array('controller' => 'DocumentUpload', 'action' => 'upload', $this->Session->read('csrftoken')));
                return $this->redirect('../LegacyDocumentUpload/upload');
                // pr("Sarika");exit;
            }

            ////////////////////////////
            $this->loadModel('Leg_generalinformation');
            $gen_info = $this->Leg_generalinformation->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
            $this->set('gen_info', $gen_info);

            if ($this->request->is('post')) {
                $this->loadModel('Leg_generalinformation');

                $last_status_id = 2;
                $last_status_date = date('Y-m-d H:i:s');

                $query1 = $this->Leg_generalinformation->query('update ngdrstab_trn_legacy_generalinformation set last_status_id=' . "'" . $last_status_id . "'" . ',last_status_date=' . "'" . $last_status_date . "'" .
                    ' where token_no=' . $this->Session->read('Leg_Selectedtoken'));
                $this->Session->setFlash('Data Added Successfully');
                return $this->redirect(array('action' => 'finalsubmit'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }

    }
}