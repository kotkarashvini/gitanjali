<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::import('Controller', 'PunjabReports'); // mention at top
                    
class MISReportsController extends AppController {
    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');

        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Auth->allow('inspection_search','get_payment_details2','simple_reciept','get_payment_details1', 'scan', 'checkscan', 'loadfile', 'upload');
        $this->Auth->allow('exception_occurred');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }
    
     //----------------------------------Check if given input is date-------------------------------------------------------------
  

    public function rpt_index_register() {
        try {
            $this->set('pdf_flag', NULL);
            if ($this->request->is('post')) {
                $this->Session->write('rpt_index_register', $this->request->data['rpt_index_register']);
               // $this->set('pdf_flag', 'Y');
                $this->request->data['rpt_index_register'] = $this->request->data['rpt_index_register'];

                $filterby = $this->request->data['rpt_index_register']['filterby'];
                $state_id = $this->Auth->user('state_id'); 
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }

                 
                $report->constructClasses();
                    
                 if ($filterby == 'IR1') {
                     
                             return $report->index_register_1($this->request->data['rpt_index_register']);
                             
                         
                        } else if ($filterby == 'IR2') {

                            return $report->index_register_2($this->request->data['rpt_index_register']);
                        } else if ($filterby == 'IR3') {

                             $report->index_register_3($this->request->data['rpt_index_register']);
                             
                        } else if ($filterby == 'IR4') {
                            
                             $report->index_register_4($this->request->data['rpt_index_register']);
                        } else {
                            echo 'nothing';
                            exit;
                        }
                
            }
        } catch (Exception $ex) {
            
        }
    }
   
    public function rpt_cash_receipt(){
        try{
              $this->set('pdf_flag', NULL);
              
              
               $fieldlist = array();
             
              
               $fieldlist['from']['text'] = 'is_required';  
               $fieldlist['to']['text'] = 'is_required';  
            
              $this->set('fieldlist', $fieldlist);
              $this->set('result_codes', $this->getvalidationruleset($fieldlist));
              
              
              
              
                if ($this->request->is('post')) {
                     $this->Session->write('rpt_cash_receipt', $this->request->data['rpt_cash_receipt']);
               // $this->set('pdf_flag', 'Y');
                $this->request->data['rpt_cash_receipt'] = $this->request->data['rpt_cash_receipt'];
                 $state_id = $this->Auth->user('state_id'); 
//                 pr($state_id);exit;
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
                
                
                
                 
                $report->constructClasses();
                
                   return $report->cash_receipt($this->request->data['rpt_cash_receipt']); 
                }
            
        } catch (Exception $ex) {

        }
    }

     
    public function rpt_goshwara_cashbook(){
        try{
            $this->set('pdf_flag', NULL);
                if ($this->request->is('post')) {
                     $this->Session->write('rpt_goshwara_cashbook', $this->request->data['rpt_goshwara_cashbook']);
               // $this->set('pdf_flag', 'Y');
                $this->request->data['rpt_goshwara_cashbook'] = $this->request->data['rpt_goshwara_cashbook'];
                 $state_id = $this->Auth->user('state_id'); 
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
                
                
                
                 
                $report->constructClasses();
                
                   return $report->goshwara_cash_book($this->request->data['rpt_goshwara_cashbook']); 
                }
        } catch (Exception $ex) {

        }
    }
	
       public function rpt_naksha_no(){
        try{
            $this->set('pdf_flag', NULL);
            
            $fieldlist = array();
                $fieldlist['from']['text'] = 'is_required';  
               $fieldlist['to']['text'] = 'is_required';  
               $this->set('fieldlist', $fieldlist);
              $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            
            
            
                if ($this->request->is('post')) {
                     $this->Session->write('rpt_naksha_no', $this->request->data['rpt_naksha_no']);
               // $this->set('pdf_flag', 'Y');
                $this->request->data['rpt_naksha_no'] = $this->request->data['rpt_naksha_no'];
                 $state_id = $this->Auth->user('state_id'); 
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
                
                
                
                 
                $report->constructClasses();
                
                   return $report->naksha_no_3($this->request->data['rpt_naksha_no']); 
                }
        } catch (Exception $ex) {

        }
    }
    
    public function rpt_stamp_and_regfees(){
        try{
             $this->set('pdf_flag', NULL);
             
             $fieldlist = array();
                $fieldlist['from']['text'] = 'is_required';  
               $fieldlist['to']['text'] = 'is_required';  
               $this->set('fieldlist', $fieldlist);
              $this->set('result_codes', $this->getvalidationruleset($fieldlist));
             
             
             
                if ($this->request->is('post')) {
                     $this->Session->write('rpt_stamp_and_regfees', $this->request->data['rpt_stamp_and_regfees']);
               // $this->set('pdf_flag', 'Y');
                $this->request->data['rpt_stamp_and_regfees'] = $this->request->data['rpt_stamp_and_regfees'];
                 $state_id = $this->Auth->user('state_id'); 
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
                
                
                
                 
                $report->constructClasses();
                
                   return $report->stamp_and_reg_fees($this->request->data['rpt_stamp_and_regfees']); 
                }
        } catch (Exception $ex) {

        }
    }
    
      public function rpt_additional_stamp_duty(){
        try{
                 $this->set('pdf_flag', NULL);
                 array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel','payment']);
                 $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';    
                 
                 $fieldlist = array();
               $fieldlist['from']['text'] = 'is_required';  
               $fieldlist['to']['text'] = 'is_required';  
              $this->set('fieldlist', $fieldlist);
              $this->set('result_codes', $this->getvalidationruleset($fieldlist));
                 
                 
                if ($this->request->is('post')) {
                    
                $this->Session->write('rpt_additional_stamp_duty', $this->request->data['rpt_additional_stamp_duty']);
                $this->request->data['rpt_additional_stamp_duty'] = $this->request->data['rpt_additional_stamp_duty'];
                $state_id = $this->Auth->user('state_id'); 

                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
          
                $report->constructClasses();
                return $report->additional_stamp_duty($this->request->data['rpt_additional_stamp_duty']); 
                }
        } catch (Exception $ex) {

        }
    }
    
      public function rpt_stamp_and_reg_state(){
        try{
                 $this->set('pdf_flag', NULL);
                    array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel','payment']);
                        $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';  
                        
                     $fieldlist = array();
                $fieldlist['from']['text'] = 'is_required';  
               $fieldlist['to']['text'] = 'is_required';  
               $this->set('fieldlist', $fieldlist);
              $this->set('result_codes', $this->getvalidationruleset($fieldlist));   
                        
                        
                        
                if ($this->request->is('post')) {
                    
                $this->Session->write('rpt_stamp_and_reg_state', $this->request->data['rpt_stamp_and_reg_state']);
                $this->request->data['rpt_stamp_and_reg_state'] = $this->request->data['rpt_stamp_and_reg_state'];
                $state_id = $this->Auth->user('state_id'); 
//                pr($state_id);exit;
//                 $state = $this->State->find('all', array('conditions' => array('state_id' => $state_id)));
//                 pr($state);exit;
//                  $statename = $this->Session->read("state_name_".$lang);
//                  pr($statename);exit;
                //$report=NULL;
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
          
                $report->constructClasses();
                return $report->stamp_and_reg_comp_state($this->request->data['rpt_stamp_and_reg_state']); 
                }
        } catch (Exception $ex) {

        }
    }
    
     public function rpt_reg_doc(){
        try{
               $this->set('pdf_flag', NULL);
                if ($this->request->is('post')) {
               $this->Session->write('rpt_reg_doc', $this->request->data['rpt_reg_doc']);
               $this->request->data['rpt_reg_doc'] = $this->request->data['rpt_reg_doc'];
               $state_id = $this->Auth->user('state_id'); 
                switch ($state_id) {
                    case 4:  $report = new PunjabReportsController();    break;
                      
                    default :
                        $this->create_pdf('<h1> Report Not Available For State id :'.$state_id.' </h1>');
                }
                $report->constructClasses();
                   return $report->register_doc($this->request->data['rpt_reg_doc']); 
                }
            
        } catch (Exception $ex) {

        }
    }
}