<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyGeneralinfoController extends AppController {

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

    public function information() {
        try {
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'mainlanguage', 'article', 'doc_type', 'District', 'taluka', 'office', 'general_info', 'Leg_generalinformation', 'Leg_application_submitted', 'finyear', 'Leg_counter', 'legacy', 'Leg_SerialNumbersFinal'));
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            //   PR($result_codes);EXIT();
            $fieldlist = array();
            $fieldlist['local_language_id']['select'] = 'is_select_req';
            $fieldlist['article_id']['select'] = 'is_required,is_select_req';
            $fieldlist['exec_date']['text'] = 'is_required,is_date_ymd';
         //   $fieldlist['presentation_no']['text'] = 'is_numeric';
            $fieldlist['presentation_dt']['text'] = 'is_date_ymd';
            $fieldlist['year_for_token']['text'] = 'is_required,is_numeric';
            
            // $fieldlist['final_doc_reg_no']['text'] = 'is_required,is_alphanumeric';
         //$fieldlist['book_serial_number']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['final_stamp_date']['text'] = 'is_required,is_date_ymd';
            //  $fieldlist['state_id']['select'] = 'is_select_req';
            $fieldlist['district_id']['select'] = 'is_required,is_select_req';
            // $fieldlist['taluka_id']['select'] = 'is_required,is_select_req';
           $fieldlist['subdivision_id']['select'] = 'is_required,is_select_req';
            $fieldlist['office_id']['select'] = 'is_required,is_select_req';
         //  $fieldlist['doc_entered_state']['select'] = 'is_required,is_select_req';
            // $fieldlist['doc_entered_district']['select'] = 'is_required,is_select_req';
            //$fieldlist['doc_entered_taluka']['select'] = 'is_required,is_select_req';
            $fieldlist['doc_entered_office']['select'] = 'is_required,is_select_req';  
            $fieldlist['bookno']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['deedno']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['deedyear']['text'] = 'is_required,is_alphanumeric';
             
            $this->set('fieldlist', $fieldlist);
           // pr($fieldlist); exit();
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        //  pr($result_codes); exit();
            $language = $this->mainlanguage->find('list', array('fields' => array('id', 'language_name'), 'order' => array('id' => 'ASC')));
            $this->set('language', $language);
            $this->request->data['General_infoctp']['local_language_id'] = 1;

            $article = $this->article->get_article('en');
            $this->set('article', $article);

            $doc_type = $this->doc_type->find('list', array('fields' => array('doc_id', 'doc_desc_en'), 'order' => array('doc_id' => 'ASC')));
            $this->set('doc_type', $doc_type);

            $district = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_name_en' => 'ASC')));
            $this->set('district', $district);

            
            $doc_entered_district = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_name_en' => 'ASC')));
            $this->set('doc_entered_district', $doc_entered_district);

           // doc_entered_subdivision

            $subdivision = array();
            $this->set('subdivision', $subdivision);


            $doc_entered_subdivision = array();
            $this->set('doc_entered_subdivision', $doc_entered_subdivision);
            
            
            // $taluka = array();
            // $this->set('taluka', $taluka);

            $doc_entered_taluka = array();
            $this->set('doc_entered_taluka', $doc_entered_taluka);

            $office = array();
            $this->set('office', $office);

            $doc_entered_office = array();
            $this->set('doc_entered_office', $doc_entered_office);


            $Selectedtoken = $this->Session->read('Leg_Selectedtoken');
           
            if ($this->request->is('post') || $this->request->is('put'))
             {
               
                if ($_POST['action'] == 'submit_b') {
                  
                    $errarr = $this->validatedata($this->request->data['General_infoctp'], $fieldlist);
                  
                    if ($this->validationError($errarr)) {
                        if (!empty($Selectedtoken)) {
                            $final_stamp_date = explode("-", $this->request->data['General_infoctp']['final_stamp_date']);
                            $book_number = $this->Leg_generalinformation->query("select book_number from ngdrstab_mst_article where article_id=" . $this->request->data['General_infoctp']['article_id']);
                          $this->request->data['General_infoctp']['bookno'] = $this->request->data['General_infoctp']['bookno'] ;
                              $this->request->data['General_infoctp']['deedno']= $this->request->data['General_infoctp']['deedno'] ;
                              $this->request->data['General_infoctp']['deedyear']= $this->request->data['General_infoctp']['deedyear'] ;
                            
                            $registration_number = $this->Leg_application_submitted->get_registration_no($final_stamp_date[2], $this->request->data['General_infoctp']['office_id'], $book_number[0][0]['book_number'], $this->request->data['General_infoctp']['deedno']);
                            if (empty($registration_number[0][0]['final_doc_reg_no'])) {
                            $result_gen_info = $this->Leg_generalinformation->find("first", array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
                            $result_app_submitted = $this->Leg_application_submitted->find("first", array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
                            ////Changes Added on Date 07Dec2020
                            $result_serialnofinal = $this->Leg_SerialNumbersFinal->find("first", array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
                            ///////////////////
                         //   pr($this->request->data);
                            $this->request->data['General_infoctp']['general_info_id'] = $result_gen_info['Leg_generalinformation']['general_info_id'];
                            $this->request->data['General_infoctp']['app_id'] = $result_app_submitted['Leg_application_submitted']['app_id'];
                            $this->request->data['General_infoctp']['counter_id'] = $result_serialnofinal['Leg_SerialNumbersFinal']['counter_id'];
                            $this->request->data['General_infoctp']['token_no'] = $this->Session->read('Leg_Selectedtoken');
                            $this->request->data['General_infoctp']['exec_date'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['exec_date']));
                            $this->request->data['General_infoctp']['presentation_dt'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['presentation_dt']));
                            $this->request->data['General_infoctp']['final_stamp_date'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['final_stamp_date']));
                            
                            $this->request->data['General_infoctp']['bookno'] = $this->request->data['General_infoctp']['bookno'] ;
                              $this->request->data['General_infoctp']['deedno']= $this->request->data['General_infoctp']['deedno'] ;
                              $this->request->data['General_infoctp']['deedyear']= $this->request->data['General_infoctp']['deedyear'] ;
//                          pr('hello');
//                            pr($this->request->data);exit; 
                            if ($this->request->data['General_infoctp']['doc_type'] == '@') {
                                $this->request->data['General_infoctp']['doc_type'] = 0;
                            }
                            if ($this->Leg_generalinformation->save($this->request->data['General_infoctp'])) {
                                ////////////Chnages Added on Date 07 Dec2020 
                                $this->Leg_SerialNumbersFinal->save($this->request->data['General_infoctp']);
                                $get_final_stamp_dt = explode('-', $this->request->data['General_infoctp']['final_stamp_date']);
                                $this->request->data['General_infoctp']['year'] = $get_final_stamp_dt[0];
                                $book_number = $this->Leg_generalinformation->query("select book_number from ngdrstab_mst_article where article_id=" . $this->request->data['General_infoctp']['article_id']);
                                $this->request->data['General_infoctp']['book_number'] = $book_number[0][0]['book_number'];
                               // $this->request->data['General_infoctp']['final_doc_reg_no'] = $this->request->data['General_infoctp']['year'] . '-' . $this->request->data['General_infoctp']['office_id'] . '-' . $this->request->data['General_infoctp']['book_number'] . '-' . $this->request->data['General_infoctp']['book_serial_number'];

                              
                              // pr($this->request->data['General_infoctp']['final_doc_reg_no']);exit();
                               
                              $this->request->data['General_infoctp']['bookno'] = $this->request->data['General_infoctp']['bookno'] ;
                              $this->request->data['General_infoctp']['deedno']= $this->request->data['General_infoctp']['deedno'] ;
                              $this->request->data['General_infoctp']['deedyear']= $this->request->data['General_infoctp']['deedyear'] ;
                              
                              
                         
                         
                              $this->request->data['General_infoctp']['final_doc_reg_no'] = $this->request->data['General_infoctp']['deedyear'] . '-' . $this->request->data['General_infoctp']['office_id'] . '-' . $this->request->data['General_infoctp']['bookno'] . '-' . $this->request->data['General_infoctp']['deedno'];

                                // pr('hiiiii reg no');
                               // pr($this->request->data['General_infoctp']['year'] . '-' . $this->request->data['General_infoctp']['office_id'] . '-' . $this->request->data['General_infoctp']['book_number'] . '-' . $this->request->data['General_infoctp']['book_serial_number']);
                               // exit();
                                $this->Leg_application_submitted->save($this->request->data['General_infoctp']);
                                /////////////////////////
                                $this->Session->setFlash('Data Added Successfully');
                                return $this->redirect(array('action' => 'information'));
                            } else {
                                $this->Session->setFlash(__('Record not saved.'));
                                return $this->redirect(array('action' => 'information'));
                            }
                            } 
                            
                            
                        } else {

                            $final_stamp_date = explode("-", $this->request->data['General_infoctp']['final_stamp_date']);
                            $book_number = $this->Leg_generalinformation->query("select book_number from ngdrstab_mst_article where article_id=" . $this->request->data['General_infoctp']['article_id']);
                        //    $registration_number = $this->Leg_application_submitted->get_registration_no($final_stamp_date[2], $this->request->data['General_infoctp']['office_id'], $book_number[0][0]['book_number'], $this->request->data['General_infoctp']['book_serial_number']);
                                                        $registration_number = $this->Leg_application_submitted->get_registration_no($final_stamp_date[2], $this->request->data['General_infoctp']['office_id'], $book_number[0][0]['book_number'], $this->request->data['General_infoctp']['deedno']);
                          
                             if (empty($registration_number[0][0]['final_doc_reg_no'])) {
                                $finyear = $this->finyear->field('finyear_id', array('current_year' => 'Y'));
                                $year = $this->finyear->field('year_for_token', array('current_year' => 'Y'));
                                $Leg_counter = $this->Leg_counter->find('all');
                                //  $Legacy_flag='L';

                                if (!empty($Leg_counter)) {
                                    $count = $Leg_counter[0]['Leg_counter']['token_no_count'];
                                    $token_no = ($year . $count) + 1;
                                } else {
                                    $count = '0000000001';
                                    $this->Leg_counter->save(array('fin_year_id' => $finyear, 'token_no_count' => $count));
                                    $token_no = ($year . $count);
                                    //pr($token_no);exit;
                                }
                                $this->request->data['genernalinfoentry']['token_no'] = $token_no;
                                $rem = "'" . substr($token_no, 4) . "'";

                                $this->Leg_counter->updateAll(
                                        array('fin_year_id' => $finyear, 'token_no_count' => $rem));
                                $token_no = $token_no;
                                $this->Session->write('Leg_Selectedtoken', $token_no);

                                $this->request->data['General_infoctp']['token_no'] = $token_no;
                                $this->request->data['General_infoctp']['exec_date'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['exec_date']));
                                $this->request->data['General_infoctp']['final_stamp_date'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['final_stamp_date']));
                                $this->request->data['General_infoctp']['user_id'] = $this->Auth->user('user_id');
                                $this->request->data['General_infoctp']['req_ip'] = $this->RequestHandler->getClientIp();
                                $this->request->data['General_infoctp']['last_status_id'] = 1;
                                $this->request->data['General_infoctp']['last_status_date'] = date('Y-m-d H:i:s');
                                $this->request->data['General_infoctp']['presentation_dt'] = date('Y-m-d', strtotime($this->request->data['General_infoctp']['presentation_dt']));
                                if ($this->request->data['General_infoctp']['doc_type'] == '@') {
                                    $this->request->data['General_infoctp']['doc_type'] = 0;
                                }
                              //  $this->request->data['General_infoctp']['book_serial_number']=;
                                $this->request->data['General_infoctp']['legacy_flag'] = 'L';
                                $this->request->data['General_infoctp']['state_id'] = $this->Auth->User("state_id");
                              //  pr( $this->request->data['General_infoctp']['book_serial_number']);exit();
                                if ($this->Leg_generalinformation->save($this->request->data['General_infoctp'])) {
                                    ////////////Chnages Added on Date 07 Dec2020 
                                    $get_final_stamp_dt = explode('-', $this->request->data['General_infoctp']['final_stamp_date']);
                                    $this->request->data['General_infoctp']['year'] = $get_final_stamp_dt[0];
                                    $book_number = $this->Leg_generalinformation->query("select book_number from ngdrstab_mst_article where article_id=" . $this->request->data['General_infoctp']['article_id']);
                                    $this->request->data['General_infoctp']['book_number'] = $book_number[0][0]['book_number'];
                                    $this->Leg_SerialNumbersFinal->save($this->request->data['General_infoctp']);
                                    $this->request->data['General_infoctp']['final_doc_reg_no'] = $this->request->data['General_infoctp']['year'] . '-' . $this->request->data['General_infoctp']['office_id'] . '-' . $this->request->data['General_infoctp']['book_number'] . '-' . $this->request->data['General_infoctp']['deedno'];
                                    $this->Leg_application_submitted->save($this->request->data['General_infoctp']);
                                  
                                    /////////////////////////
                                    $this->Session->setFlash('Data Added Successfully');
                                    //return $this->redirect(array('action' => 'information'));
                                    //return $this->redirect(array('Controller'=>'Propertydetails','action' => 'property'));
                                    $this->redirect(array('controller' => 'LegacyPropertydetails', 'action' => 'property'));
                                } else {
                                    $this->Session->setFlash(__('Record not saved.'));
                                }
                                $this->Session->setFlash('Data Added Successfully');
                            } else {
                                $this->Session->setFlash(__('This Registration number is already present'));
                            }
                        }
                    }
                }
            } else {
               // PR('HELLO IN ELES ');
                if (!empty($Selectedtoken)) {
                    $token = $this->Session->read('Leg_Selectedtoken');
                    $demodata = $this->legacy->get_general_info($token);
                     //pr($demodata);exit;
                    $this->request->data['General_infoctp']['local_language_id'] = $demodata[0][0]['local_language_id'];
                    $this->request->data['General_infoctp']['article_id'] = $demodata[0][0]['article_id'];
                    $this->request->data['General_infoctp']['exec_date'] = date('d-m-Y', strtotime($demodata[0][0]['exec_date'])); //$demodata[0][0]['exec_date'];
                  
                    $this->request->data['General_infoctp']['serial_no'] = $demodata[0][0]['serial_no'];
                    $this->request->data['General_infoctp']['volume_no'] = $demodata[0][0]['volume_no'];
                    $this->request->data['General_infoctp']['page_no'] = $demodata[0][0]['page_no'];
                 // pr($demodata[0][0]['presentation_no']);
                    //  $popupstatus1 = $this->Leg_application_submitted->query("select deedno,deedyear,bookno from ngdrstab_trn_legacy_application_submitted where token_no= $token");
                   // pr($popupstatus1 );exit();
                    
                        $string3 = $this->Leg_application_submitted->query('select deedno,deedyear,bookno from ngdrstab_trn_legacy_application_submitted where token_no='. $token );
                      //  pr($string3 );exit();
                        
                    $this->request->data['General_infoctp']['deedno'] = $string3[0][0]['deedno'] ;
                    
                              $this->request->data['General_infoctp']['bookno']= $string3[0][0]['bookno'] ;
                              $this->request->data['General_infoctp']['deedyear']= $string3[0][0]['deedyear'] ;
                   
                      IF($demodata[0][0]['presentation_dt'])
                      
  //  if ($demodata[0][0]['presentation_dt'] == '1970-01-01 00:00:00+05:30') 
  if ($demodata[0][0]['presentation_dt'] == '1970-01-01 00:00:00') 
    { //PR('INN IF');
                        $this->request->data['General_infoctp']['presentation_dt'] = '';
                    } 
                    else {
                       // PR('IN ELSE');
                        $this->request->data['General_infoctp']['presentation_dt'] = date('d-m-Y', strtotime($demodata[0][0]['presentation_dt']));
                    }
                    $this->request->data['General_infoctp']['doc_type'] = $demodata[0][0]['doc_type'];
                    $this->request->data['General_infoctp']['reference_no'] = $demodata[0][0]['reference_no'];
                    $this->request->data['General_infoctp']['year_for_token'] = $demodata[0][0]['year_for_token'];
                    $this->request->data['General_infoctp']['book_serial_number'] = $demodata[0][0]['book_serial_number'];
                    //$this->request->data['General_infoctp']['final_doc_reg_no'] = $demodata[0][0]['final_doc_reg_no'];

                    $this->request->data['General_infoctp']['final_stamp_date'] = date('d-m-Y', strtotime($demodata[0][0]['final_stamp_date'])); //$demodata[0][0]['final_stamp_date'];
                    $this->request->data['General_infoctp']['state_id'] = $demodata[0][0]['state_id'];
                    $this->request->data['General_infoctp']['district_id'] = $demodata[0][0]['district_id'];


//                    $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $demodata[0][0]['district_id']), 'order' => array('taluka_name_en' => 'ASC')));
//                    $this->set('taluka', $taluka);
                    //$this->request->data['General_infoctp']['taluka_id'] = $demodata[0][0]['taluka_id'];
//                     $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('taluka_id' => $demodata[0][0]['taluka_id']), 'order' => array('office_name_en' => 'ASC')));
//                    $this->set('office', $office);

                    $this->loadModel('Subdivision');
                    $subdivision = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'conditions' => array('district_id' => $demodata[0][0]['district_id']), 'order' => array('subdivision_name_en' => 'ASC')));
                    $this->set('subdivision', $subdivision);
                    $this->request->data['General_infoctp']['subdivision_id'] = $demodata[0][0]['subdivision_id'];
                    //pr($this->request->data['General_infoctp']['subdivision_id']);exit;

                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('subdivision_id' => $demodata[0][0]['subdivision_id']), 'order' => array('office_name_en' => 'ASC')));
                    $this->set('office', $office);


                    $this->request->data['General_infoctp']['office_id'] = $demodata[0][0]['office_id'];
                    $this->request->data['General_infoctp']['doc_entered_state'] = $demodata[0][0]['doc_entered_state'];

                    $doc_entered_district = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'conditions' => array('state_id' => $demodata[0][0]['doc_entered_state']), 'order' => array('district_name_en' => 'ASC')));
                    $this->set('doc_entered_district', $doc_entered_district);
                    $this->request->data['General_infoctp']['doc_entered_district'] = $demodata[0][0]['doc_entered_district'];

                    $doc_entered_taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $demodata[0][0]['doc_entered_district']), 'order' => array('taluka_name_en' => 'ASC')));
                    $this->set('doc_entered_taluka', $doc_entered_taluka);
                    $this->request->data['General_infoctp']['doc_entered_taluka'] = $demodata[0][0]['doc_entered_taluka'];

                    $doc_entered_office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('subdivision_id' => $demodata[0][0]['subdivision_id']), 'order' => array('office_name_en' => 'ASC')));
                    $this->set('doc_entered_office', $doc_entered_office);
                    $this->request->data['General_infoctp']['doc_entered_office'] = $demodata[0][0]['doc_entered_office'];
                }
            }
        } 
        catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getdistrict() {
        $this->autoRender = FALSE;
        $this->loadModel('District');
        $stateid = $_GET['stateid'];
        $district = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'conditions' => array('state_id' => $stateid)));
        echo json_encode($district);
        exit;
    }

//    public function getdistrict_doc_entereed() {
//        $this->autoRender = FALSE;
//        $this->loadModel('District');
//        $stateid_entered = $_GET['stateid_entered'];
//        $district = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'conditions' => array('state_id' => $stateid_entered)));
//        echo json_encode($district);
//        exit;
//    }
//    public function gettaluka() {
//        $this->autoRender = FALSE;
//        $this->loadModel('taluka');
//        $districtid = $this->request->data['districtid'];
//        $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $districtid)));
//        echo json_encode($taluka);
//        exit;
//    }

   public function getsubdivision1() {
        $this->autoRender = FALSE;
        $this->loadModel('Subdivision');
        $districtid = $this->request->data['districtid'];
        $subdivision = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'conditions' => array('district_id' => $districtid)));
        echo json_encode($subdivision);
        exit;
    }
        public function getoffice1() {
        $this->autoRender = FALSE;
        $this->loadModel('office');
        $subdivisionid = $this->request->data['subdivisionid'];
        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('subdivision_id' => $subdivisionid)));
        echo json_encode($office);
        exit;
    }
    public function getsubdivision() 
    {
        $this->autoRender = FALSE;
        $this->loadModel('Subdivision');
        $districtid = $this->request->data['districtid'];
        // pr("district id");
        // pr($districtid);
        $subdivision = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'conditions' => array('district_id' => $districtid)));
        // pr('subdivision');
        // pr($subdivision );
        echo json_encode($subdivision);
        exit;
    }

//    public function gettaluka_doc_entered() {
//        $this->autoRender = FALSE;
//        $this->loadModel('taluka');
//        $districtid_entered = $_GET['districtid_entered'];
//        $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $districtid_entered)));
//        echo json_encode($taluka);
//        exit;
//    }
//    public function getoffice() {
//        $this->autoRender = FALSE;
//        $this->loadModel('office');
//        $talukaid = $this->request->data['talukaid'];
//        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('taluka_id' => $talukaid)));
//        echo json_encode($office);
//        exit;
//    }
//
//    public function getoffice_doc_entered() {
//        $this->autoRender = FALSE;
//        $this->loadModel('office');
//        $talukaid_entered = $_GET['talukaid_entered'];
//        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('taluka_id' => $talukaid_entered)));
//        echo json_encode($office);
//        exit;
//    }




    public function getoffice() {
        $this->autoRender = FALSE;
        $this->loadModel('office');
        $subdivisionid = $this->request->data['subdivisionid'];
        // pr('subdivision id');
        // pr($subdivisionid);
        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('subdivision_id' => $subdivisionid)));
        // pr("office");
        // pr( $office);
        echo json_encode($office);
        exit;
    }

//    public function getoffice_doc_entered() {
//        $this->autoRender = FALSE;
//        $this->loadModel('office');
//        $talukaid_entered = $_GET['talukaid_entered'];
//        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('taluka_id' => $talukaid_entered)));
//        echo json_encode($office);
//        exit;
//    }

    public function get_reg_no() {
        $this->autoRender = FALSE;
        $this->loadModel('Leg_application_submitted');
        $reg_no = $_GET['reg_no'];
        $reg = $this->Leg_application_submitted->get_registration_no($reg_no);
        echo json_encode($reg);
        exit;
    }

}
