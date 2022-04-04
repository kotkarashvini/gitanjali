<?php

//App::import('Controller', 'Fees'); // mention at top

class BiometricController extends AppController {

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('document_status','confreg', 'tablereport', 'login_statistics', 'rpt_login_statistics', 'tablelistreport', 'rpt_fee_calc_list', 'rpt_fee_calc', 'rptvaluation', 'getvaluationlist', 'rptview', 'getsurveynumbers', 'ratereport', 'doc_payment_receipt', 'rpt_reg_summary1', 'rpt_reg_summary2', 'is_Date', 'rpt_payment_cashbook', 'payment_cashbook', 'get_identification_data');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
        $this->Auth->allow('Secugen_api');
    }
    
    public function secugen_api() {
//        pr("hii");exit;
    }
    
    public function startek_api() {
        
    }
    
    public function Nitgen_api() {
        
    }
    
    public function Next_api() {
        
    }

}