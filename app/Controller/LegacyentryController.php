<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyentryController extends AppController {
    
     public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();
        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
       // $this->Auth->allow('legacyinfo','getid','delete_session');
        //$this->Auth->allow('Legency_data_entry_new');
    } 
    
    public function legacyinfo() {

        $this->Session->write('authinfo','N');
        $this->Session->write('legacyinfo','Y');
        $this->loadModel('legacy');
       
        $user_id=$this->Auth->user('user_id');
        $demodata = $this->legacy->get_data($user_id);
        //PR( $demodata);
        $this->set('demodata', $demodata);
        //PR('HIII');EXIT();
    }
    
    public function getid($token_no) {
         $this->loadModel('legacy');
        $last_status = $this->legacy->query("select last_status_id from ngdrstab_trn_legacy_generalinformation where token_no=$token_no");
        if($last_status[0][0]['last_status_id']=='1' && $this->Auth->User('role_id')=='999923') {
            $this->Session->write('Leg_Selectedtoken', $token_no);
        }
        else if($last_status[0][0]['last_status_id']=='2' && $this->Auth->User('role_id')=='999924'){
          $this->Session->write('Leg_Selectedtoken', $token_no);
        }    
         return $this->redirect(array('controller' => 'LegacyGeneralinfo', 'action' => 'information')); 
        
    }
    
      function delete_session() {
        $this->Session->write('Leg_Selectedtoken', NULL);
        return $this->redirect(array('controller' => 'LegacyGeneralinfo', 'action' => 'information')); 
//        $this->Session->write('doc_lang', NULL);
//        $this->Session->write('article_id', NULL);
//        $this->redirect(array('action' => 'information', $this->Session->read('csrftoken')));
    }
    
     public function authinfo() {
        $lang = $this->Session->read("sess_langauge");       
       $this->loadModel('legacy');
        $office_id=$this->Auth->user('office_id');
       
       // $doc_entered_office=$this->Auth->user('doc_entered_office');
        //pr($doc_entered_office);exit;
        $this->Session->write('authinfo','Y');
        $this->Session->write('legacyinfo','N');
        $demodata = $this->legacy->get_unauthorised_data($lang,$office_id);
       // pr($demodata);exit;
        $this->set('demodata', $demodata);
    
    }
}