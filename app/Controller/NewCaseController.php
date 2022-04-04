 
<?php

class NewCaseController extends AppController {

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('create_pdf', 'sample_dashboard', 'case_disposal','casestatus', 'Casetype', 'Objectiontype', 'date', 'datewiselist', 'judgement_details', 'notice_generation', 'proceeding_details', 'respondententry', 'genernalinfoentry', 'get_adj_doc_exess_amt', 'citizenlogin', 'logout', 'setdoc_lang', 'update_sd', 'leaseandlicense', 'appointment', 'getarticlefield', 'genernal_info', 'genernalinfoentry', 'usagecategory_change_event', 'property_details', 'getattributeupdate', 'valuation_entry', 'party_entry', 'taluka_change_event', 'getusagevisibilitynew', 'rulechangeevent', 'getattributeparameter', 'add_property_attribute', 'witness', 'behavioral_patterns', 'article_change_event', 'check_appointmentdate', 'slot_alocation', 'get_time_difference', 'tatkalappoinment', 'tatkal_slot_alocation', 'check_maxappointmentday', 'getdependent_article', 'get_7_12_record', 'check_filevalidation', 'upload_document', 'article_mapping_screen', 'get_valuation_amt', 'getarticledepfeild', 'check_prohibited_prop', 'stamp_duty', 'check_config_prohibition', 'check_execution_date', 'party_address', 'get_instrument', 'get_fees_falc_ids', 'payment', 'get_payment_details', 'final_submit', 'check_land_record_fetching', 'check_7_12_compulsary', 'get_adj_doc_exess_amt_detail', 'is_uid_compulsary', 'is_identity_compulsary', 'identification', 'multiple_property_allowed', 'check_property_count', 'check_presenter', 'set_presenter', 'is_party_ekyc_auth_compusory', 'party_ekyc_authentication', 'is_party_ekyc_done');
        $this->Auth->allow('welcomenote', 'citizenlogin', 'csrftoken', 'set_csrf_token', 'case_disposal', 'check_csrf_token', 'sample_dashboard', 'date', 'judgement_details', 'notice_generation', 'respondententry', 'login', 'genernalinfoentry', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration');

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function new_session() {
        $this->Session->write("selected_token", null);
        return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernalinfoentry'));
    }

    public function dashboard_2() {
        
    }

    public function sample_dashboard() {
        
    }

    public function all_cases_status_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation'));
            return $registeredcases = $this->NewCase->query(" select 
distinct a.case_type_desc,a.case_type_id,count(b.case_id)
		from
		ngdrstab_mst_ccms_casetype as a,
		ngdrstab_trn_ccms_casedetails as b
                where 
                b.case_type_id=a.case_type_id
                GROUP BY a.case_type_desc,a.case_type_id");
            $casetype_id = $registeredcases[0][0]['case_type_id'];
            // $created_date = date('Y-m-d');
            $dataid = $this->NewCase->query("select case_id from ngdrstab_trn_ccms_casedetails where case_type_id=$casetype_id");
            $this->set('dataid', $dataid);
        } catch (exception $ex) {
            
        }
    }

//registered cases list
    public function registered_cases_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation'));
            return $registeredcases = $this->NewCase->query(" select 
distinct a.case_type_desc,a.case_type_id,count(b.case_id)
		from
		ngdrstab_mst_ccms_casetype as a,
		ngdrstab_trn_ccms_casedetails as b
                where 
                b.case_type_id=a.case_type_id
                GROUP BY a.case_type_desc,a.case_type_id");
            $casetype_id = $registeredcases[0][0]['case_type_id'];
            $dataid = $this->NewCase->query("select case_id from ngdrstab_trn_ccms_casedetails where case_type_id=$casetype_id");
            $this->set('dataid', $dataid);
        } catch (exception $ex) {
            
        }
//        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function onboard_cases_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation'));
            $created_date = date('Y-m-d');
            return $onboardcases = $this->notice_generation->query("select c.case_id,c.case_code,c.case_year,g.case_id,t.office_name_en,ct.case_type_desc,co.objection_name,g.first_hearing_date
 from ngdrstab_ccms_notice_gen as g ,
 ngdrstab_trn_ccms_casedetails AS c,
 ngdrstab_mst_ccms_casetype  ct,
 ngdrstab_mst_ccms_objectiontype  co,
 ngdrstab_mst_office  t
 where
   g.case_id=c.case_id and
    t.office_id=g.place_of_hearing and
   ct.case_type_id=c.case_type_id and
   co.objection_type_id=c.objection_type_id
and g.first_hearing_date='$created_date'");
        } catch (exception $ex) {
            
        }
        //  $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function revenue_cases_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'office', 'casemenus', 'NewCase', 'notice_generation'));
//            $created_date = date('Y-m-d');
//            return $onboardcases = $this->notice_generation->query("select c.case_id,c.case_code,c.case_year,g.case_id,t.office_name_en,ct.case_type_desc,co.objection_name,g.first_hearing_date
// from ngdrstab_ccms_notice_gen as g ,
// ngdrstab_trn_ccms_casedetails AS c,
// ngdrstab_mst_ccms_casetype  ct,
// ngdrstab_mst_ccms_objectiontype  co,
// ngdrstab_mst_office  t
// where
//   g.case_id=c.case_id and
//    t.office_id=g.place_of_hearing and
//   ct.case_type_id=c.case_type_id and
//   co.objection_type_id=c.objection_type_id
//and g.first_hearing_date='$created_date'");
            return $onboardcases1 = $this->office->query("select office_name_en from ngdrstab_mst_office");
        } catch (exception $ex) {
            
        }
        //  $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function officewise_cases_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation', 'office'));
            $created_date = date('Y-m-d');
            return $officewisecases = $this->notice_generation->query("select 
distinct a.office_name_en,a.office_id,count(b.case_id)
		from
		ngdrstab_mst_office as a,
		ngdrstab_trn_ccms_casedetails as b
                where 
                b.case_belongs_to=a.office_id
                GROUP BY a.office_name_en,a.office_id");
        } catch (exception $ex) {
            
        }
        //  $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function datewise_cases_list() {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation', 'office'));
            $created_date = date('Y-m-d');
            return $datewisecases = $this->notice_generation->query("select case_id,case_admited_date,
count(distinct case_code) as case_code,
count(distinct case_year) as  case_year from ngdrstab_trn_ccms_casedetails 
group by case_admited_date,case_id");
        } catch (exception $ex) {
            
        }
        //  $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function dashboard() {
        try {

            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'NewCase', 'notice_generation'));
            $onboardcases = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id");
            $this->set("onboardcases", $onboardcases);
            $created_date = date('Y-m-d');
            $caseresult = $this->notice_generation->query("select case_id,place_of_hearing from ngdrstab_ccms_notice_gen where first_hearing_date='$created_date'");
            // pr($caseresult);exit;
            $this->set("caseresult", $cascaseresult);
        } catch (Exception $ex) {
            
        }
    }

    public function status_info($idtype = NULL, $id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'formbehaviour', 'fieldformlinkage', 'genernal_info', 'document_status_description', 'document_status_description'));
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("citizen_user_id", $this->Auth->user('user_id'));
            $user_id = $this->Session->read("citizen_user_id");
            $session_tokenval = $this->Session->read("Selectedtoken");
            if ($idtype == 'case') {
                $casetype_id = $id;
                if (is_numeric($casetype_id)) {
                    $allresult2 = $this->NewCase->query("select  c.*, c.case_year,o.objection_name,t.case_type_desc,
                                             c.case_admited_date,c.stamp_duty,e.office_name_en 
                                                 from ngdrstab_trn_ccms_casedetails c
                                                   left join ngdrstab_mst_ccms_objectiontype o on o.objection_type_id=c.objection_type_id
                                                  left join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                                                  left join ngdrstab_mst_office e on e.office_id=c.case_belongs_to
                                                   where c.case_type_id=$casetype_id");
                }
            } else if ($idtype == 'office') {
                $office_id = $id;
                if (is_numeric($office_id)) {
                    $allresult2 = $this->NewCase->query("select distinct c.*, c.case_year, o.objection_name,t.case_type_desc,c.case_admited_date,
                        c.stamp_duty,e.office_name_en
                                                   from ngdrstab_trn_ccms_casedetails c
                                                    inner join ngdrstab_mst_ccms_objectiontype o on o.objection_type_id=c.objection_type_id
                                                   inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id
                                                   inner join ngdrstab_mst_office e on e.office_id=c.case_belongs_to                                                   
                                                  where c.case_belongs_to=$office_id");
                }
            } else {
                $allresult2 = $this->NewCase->query("select distinct c.*, c.case_year, o.objection_name,t.case_type_desc,c.case_admited_date,c.stamp_duty,e.office_name_en 
                                                       from ngdrstab_trn_ccms_casedetails c
                                                    inner join ngdrstab_mst_ccms_objectiontype o on o.objection_type_id=c.objection_type_id
                                                   inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                                                    inner join ngdrstab_mst_office e on e.office_id=c.case_belongs_to
                                                   ");
            }
            $this->set("allresult2", $allresult2);
            $created_date = date('Y/m/d H:i:s');
        } catch (Exception $ex) {
            
        }
    }

    public function genernal_info() {
        try {

            array_map(array($this, 'loadModel'), array('casemainmenus', 'casemenus', 'formbehaviour', 'fieldformlinkage', 'genernal_info', 'document_status_description', 'document_status_description'));
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            $this->Session->write("citizen_user_id", $this->Auth->user('user_id'));
            $user_id = $this->Session->read("citizen_user_id");
            $session_tokenval = $this->Session->read("Selectedtoken");
            $allresult2 = $this->NewCase->query("select distinct c.*, c.case_year, o.objection_name,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
                                                    inner join ngdrstab_mst_ccms_objectiontype o on o.objection_type_id=c.objection_type_id
                                                   inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                                                   ");
            $this->set("allresult2", $allresult2);
            $created_date = date('Y/m/d H:i:s');
        } catch (Exception $ex) {
            
        }
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function genernalinfoentry($tokenval = NULL) {
        try {
            // $this->check_role_escalation();
            $this->loadModel('CaseStatusDetails');
            $this->loadModel('HearingDetails');
            $this->loadModel('NewCase');
            $this->loadModel('CaseType');
            $this->loadModel('ObjectionType');
            $this->loadModel('office');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            if (!$this->request->is('post')) {
                if ($tokenval != NULL && is_numeric($tokenval)) {
                    $this->Session->write("selected_token", $tokenval);
                }
                $case_id = $this->Session->read("selected_token");
                // pr($case_id);exit;
                $this->set('case_code_id', $case_id);
                if (is_numeric($case_id)) {
                    $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
                        inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                           where case_id=$case_id");
                    if (empty($case_code)) {
                        $this->Session->setFlash(__("Invalid Case ID"));
                        $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                    }


                    $case_code1 = $case_code[0][0]['case_code'];
                    $case_year = $case_code[0][0]['case_year'];
                    $case_type = $case_code[0][0]['case_type_desc'];
                    $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                    $this->set('ccms_case', $ccms_case);
                    $this->Session->write("ccms_case", $ccms_case);
                } else {
                    $this->set('ccms_case', NULL);
                    $this->Session->write("ccms_case", NULL);
                }
                $user_id = $this->Auth->User('user_id');
                $salutation = ClassRegistry::init('salutation')->find('list', array('fields' => array('id', 'salutation_desc_en'), 'order' => array('salutation_desc_en' => 'ASC')));
                $this->set('salutation', $salutation);
                $casetypedesc = ClassRegistry::init('CaseType')->find('list', array('fields' => array('case_type_id', 'case_type_desc'), 'order' => array('case_type_desc' => 'ASC')));
                $this->set('casetypedesc', $casetypedesc);
                $objectiontype = ClassRegistry::init('ObjectionType')->find('list', array('fields' => array('objection_type_id', 'objection_name'), 'order' => array('objection_name' => 'ASC')));
                $this->set('objectiontype', $objectiontype);
                $sofficename = ClassRegistry::init('office')->find('list', array('fields' => array('id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC')));
                $this->set('sofficename', $sofficename);
            }

            $fieldlist = array();
            $fieldlist['case_type_id']['select'] = 'is_select_req';
            $fieldlist['case_code']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['date_of_entry']['text'] = 'is_required';
            $fieldlist['applicant_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['case_belongs_to']['select'] = 'is_select_req';
            $fieldlist['objection_type_id']['select'] = 'is_select_req';
            $fieldlist['stamp_duty']['text'] = 'is_required,is_numeric';
            $fieldlist['lc_paper']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['adjudication_no']['text'] = 'is_integer';
//            $fieldlist['adj_case_no']['text'] = 'is_alphanumeric';
//            $fieldlist['adj_date']['text'] = '';
            $fieldlist['old_doc_reg_no']['text'] = 'is_integer';
            $fieldlist['old_doc_office']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['ref_doc_reg_no']['text'] = 'is_integer';
            $fieldlist['ref_doc_office']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['salutation']['select'] = 'is_select_req';
            //   $fieldlist['ref_doc_reg_date']['text'] = '';
            $fieldlist['advocate_f_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['advocate_m_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['advocate_l_name']['text'] = 'is_required,is_alphaspace';
//            $fieldlist['hfid']['text'] = 'is_numeric';
//             $fieldlist['hfaction']['text'] = 'is_s_n';
//            $fieldlist['actiontype']['text'] = 'is_numeric';
//            $fieldlist['case_id']['text'] = 'is_numeric';
            $fieldlist['csrftoken']['text'] = 'is_integer';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['genernalinfoentry']['csrftoken']);
//                $actiontype = $_POST['actiontype'];
//                $hfid = $_POST['hfid'];
//                $this->set('hfid', $hfid);
//                $hfactionval = $_POST['hfaction'];

                $user_id = $this->Auth->User('user_id');
                $this->request->data['user_id'] = $user_id;
                $this->request->data['genernalinfoentry'] = $this->istrim($this->request->data['genernalinfoentry']);

//                $this->request->data['genernalinfoentry']['actiontype'] = $actiontype;
//                //  $this->request->data['CaseType']['hfupdateflag'] = $hfactionval;
//                $this->request->data['genernalinfoentry']['hfaction'] = $hfactionval;
//                $this->request->data['genernalinfoentry']['hfid'] = $hfid;
                $errarr = $this->validatedata($this->request->data['genernalinfoentry'], $fieldlist);

                if ($this->ValidationError($errarr)) {
                    $this->request->data['genernalinfoentry'] = $this->encode_special_char($this->request->data['genernalinfoentry']);
                    if ($this->NewCase->Save($this->request->data['genernalinfoentry'])) {
                        $lastid = $this->NewCase->getLastInsertId();
                        if (!is_numeric($lastid)) {
                            $lastid1 = $this->request->data['genernalinfoentry']['case_id'];
                            $lastid = $lastid1; //$this->decrypt($lastid1, $this->Session->read("randamkey"));
                        }
                        $this->case_status_update($lastid);
                        $this->Session->write("selected_token", $lastid);
                        $this->Session->setFlash(__('Record Saved Successfully'));
                        if (is_numeric($lastid)) {
                            return $this->redirect(array('controller' => 'NewCase', 'action' => 'respondententry/' . $lastid));
                        } else {
                            return $this->redirect(array('controller' => 'NewCase', 'action' => 'respondententry/' . $case_id));
                        }
                    }
                    $this->Session->setFlash(_('Record not saved'));
                    //  }
                } else {
                    $this->Session->setFlash(__('Enter proper data to proper fields'));
                }
                $this->Session->write('Selectedtoken', $tokenval);
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                $gen_info = $this->NewCase->find('all', array('conditions' => array('case_id' => $tokenval)));
                if ($gen_info != NULL) { // set data  to form
                    $this->request->data['genernalinfoentry'] = $gen_info[0]['NewCase'];
                }
            } $this->set_csrf_token();
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function respondententry($tokenval = NULL) {
        try {

            $this->loadModel('CaseStatusDetails');
            $this->loadModel('NewCase');
            $this->loadModel('RespDetails');
            $this->loadModel('CaseType');
            $this->loadModel('salutation');
            $this->loadModel('ObjectionType');
            $this->loadModel('office');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User('user_id');
            if ($tokenval != NULL && is_numeric($tokenval)) {
                //$tokenval = $this->decrypt($tokenval, $this->Session->read("randamkey"));
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
            $this->set('case_code_id', $case_id);
            if (is_numeric($case_id)) {
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_code from ngdrstab_trn_ccms_casedetails c
                inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                where case_id=$case_id");

                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_code'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }
            $salutation = ClassRegistry::init('salutation')->find('list', array('fields' => array('id', 'salutation_desc_en'), 'order' => array('salutation_desc_en' => 'ASC')));
            $this->set('salutation', $salutation);
            $casetypedesc = ClassRegistry::init('CaseType')->find('list', array('fields' => array('case_type_id', 'case_type_desc'), 'order' => array('case_type_desc' => 'ASC')));
            $this->set('casetypedesc', $casetypedesc);
            $objectiontype = ClassRegistry::init('ObjectionType')->find('list', array('fields' => array('objection_type_id', 'objection_name'), 'order' => array('objection_name' => 'ASC')));
            $this->set('objectiontype', $objectiontype);
            $sofficename = ClassRegistry::init('office')->find('list', array('fields' => array('id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC')));
            $this->set('sofficename', $sofficename);

            $resp_record = $this->RespDetails->query("select id, respondent_f_name ,  respondent_m_name ,  respondent_l_name ,  respondent_advocate_f_name ,
            respondent_advocate_m_name ,  respondent_advocate_l_name ,respondent_address,respondent_email_id,mobile_no,  liable_for_payment_flag  from ngdrstab_mst_respdetails where case_id=$case_id");
            $this->set('resp_record', $resp_record);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist = array();
            $fieldlist['salutation']['select'] = 'is_select_req';
            $fieldlist['respondent_f_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_m_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_l_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_address']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian';
            $fieldlist['salutation_id']['select'] = 'is_select_req';
            $fieldlist['respondent_advocate_f_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_advocate_m_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['respondent_advocate_l_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['hfid']['text'] = 'is_numeric';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_numeric';
//            $fieldlist['id']['text'] = 'is_integer';
            $fieldlist['csrftoken']['text'] = 'is_integer';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post') || $this->request->is('put')) {
                //$this->check_csrf_token($this->request->data['respondententry']['csrftoken']);
                $this->request->data['user_id'] = $user_id;
                $created_date = date('Y/m/d H:i:s');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['respondententry']['req_ip'] = $this->request->clientIp();
                        $this->request->data['respondententry']['case_id'] = $case_id;
                        $this->request->data['respondententry']['user_id'] = $user_id;
                        $this->request->data['respondententry']['created_date'] = $created_date;
                        $this->request->data['respondententry']['case_id'] = $case_id;
                        $this->request->data['respondententry']['actiontype'] = $actiontype;
                        //  $this->request->data['CaseType']['hfupdateflag'] = $hfactionval;
                        $this->request->data['respondententry']['hfaction'] = $hfactionval;
                        $this->request->data['respondententry']['hfid'] = $hfid;
                        //  $this->request->data['respondententry']['id']= $this->request->data['hfid'];

                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['respondententry']['resp_id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                            //  $adbc = $alerts['respondententry']['btnupdate'][$laug];
                        } else {
                            $actionvalue = "Saved";
                            // $adbc = $alerts['respondententry']['btnadd'][$laug];
                        }
                        $this->request->data['respondententry'] = $this->istrim($this->request->data['respondententry']);
                        $errarr = $this->validatedata($this->request->data['respondententry'], $fieldlist);
//                      pr($errarr);exit;
                        if ($this->ValidationError($errarr)) {

                            $this->request->data['respondententry'] = $this->encode_special_char($this->request->data['respondententry']);
                            if ($this->RespDetails->Save($this->request->data['respondententry'])) {
                                $lastid = $this->RespDetails->getLastInsertId();
                                //      echo "hi";
                                $this->case_status_update($case_id);
                                //     echo "hello";
                                // $this->Session->write("selected_token", $case_id);
//                            $case_status = $this->CaseStatusDetails->query("INSERT INTO  ngdrstab_ccms_case_status_details(case_id,case_status_id,case_status_flag) VALUES($lastid,11,'Y')");
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                return $this->redirect(array('controller' => 'NewCase', 'action' => 'respondententry'));
                            }
                            $this->Session->setFlash(_('Record not saved'));
                        } else {
                            $this->Session->setFlash(__('Enter proper data to proper fields'));
                        }
                    }
                }
            }
            $this->set_csrf_token();
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function respondent_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('RespDetails');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->RespDetails->id = $id;
                if ($this->RespDetails->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'respondententry'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function notice_generation($tokenval = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('notice_generation', 'CaseStatus', 'CaseStatusDetails', 'casemainmenus', 'casemenus', 'formbehaviour', 'fieldformlinkage', 'genernal_info', 'document_status_description', 'document_status_description'));
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d H:i:s');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            if ($tokenval != NULL && is_numeric($tokenval)) {
                //$tokenval = $this->decrypt($tokenval, $this->Session->read("randamkey"));
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
            $this->set('case_code_id', $case_id);
            if (is_numeric($case_id)) {
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_code from ngdrstab_trn_ccms_casedetails c
                 inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                where case_id=$case_id");
                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_code'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }
            $this->set('office', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC'))));
            $this->set('noticerecord', $this->notice_generation->query("select n.place_of_hearing,n.notice_gen_id,n.stamp_duty_revised,n.notice_date,n.first_hearing_date,t.office_name_en,n.remark from ngdrstab_ccms_notice_gen as n 
inner join ngdrstab_mst_office  t on t.office_id=n.place_of_hearing 
where
 n.case_id=$case_id"));
            $case_status = $this->CaseStatus->find('list', array('fields' => array('case_status_id', 'case_status_desc'), 'order' => array('case_status_desc' => 'ASC'), 'conditions' => array('case_status_id' => array(3, 4))));
            $this->set('case_status', $case_status);

            $fieldlist['notice_date']['text'] = 'is_required';
            $fieldlist['first_hearing_date']['text'] = 'is_required';
            // $fieldlist['case_status_id']['select'] = 'is_select_req';
            $fieldlist['place_of_hearing']['select'] = 'is_select_req';
            $fieldlist['stamp_duty_revised']['text'] = 'is_required,is_numeric';
            $fieldlist['remark']['text'] = 'is_required,is_alphaspace';
            $fieldlist['hfid']['text'] = 'is_digit';
//            $fieldlist['notice_gen_id']['text'] = 'is_integer';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_integer';
            $fieldlist['csrftoken']['text'] = 'is_integer';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
              
                //    $this->check_csrf_token($this->request->data['notice_generation']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                 // pr($hfactionval);
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                }
                if ($hfactionval == 'S') {
                    $this->request->data['notice_generation']['req_ip'] = $this->request->clientIp();
                    $this->request->data['notice_generation']['user_id'] = $user_id;
                    $this->request->data['notice_generation']['created'] = $created_date;
                    $this->request->data['notice_generation']['case_id'] = $case_id;
                    $this->request->data['notice_generation']['actiontype'] = $actiontype;
                    //  $this->request->data['notice_generation']['hfupdateflag'] = $hfactionval;
                    $this->request->data['notice_generation']['hfaction'] = $hfactionval;
                    $this->request->data['notice_generation']['hfid'] = $hfid;

                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['notice_generation']['notice_gen_id'] = $this->request->data['hfid'];
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }
                    $this->request->data['notice_generation'] = $this->istrim($this->request->data['notice_generation']);
                    //  pr($fieldlist);exit;
                    $errarr = $this->validatedata($this->request->data['notice_generation'], $fieldlist);
                       //pr($errarr);exit;
                    if ($this->ValidationError($errarr)) {
                        if ($this->notice_generation->save($this->request->data['notice_generation'])) {
                            $lastid = $this->notice_generation->getLastInsertId();
                            $this->case_status_update($case_id);
                            //CcmsPaymentDetails
//                        $case_status = $this->CaseStatusDetails->query("INSERT INTO  ngdrstab_ccms_case_status_details(case_id,case_status_id,case_status_flag) VALUES($lastid,10,'Y')");
                            $this->Session->setFlash(__("Record $actionvalue Successfully"));
                            $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation'));
                        } else {
                            $this->Session->setFlash(__('Record Not Saved '));
                        }
                    } else {
                        $this->Session->setFlash(__('Enter proper data to proper fields'));
                    }
                }
            }
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function notice_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('notice_generation');
        try {
            // $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->notice_generation->id = $id;
                if ($this->notice_generation->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'notice_generation'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function proceeding_details($tokenval = NULL) {
        try {
            $this->loadModel('CaseStatus');
            $this->loadModel('HearingDetails');
            $this->loadModel('NewCase');
            $this->loadModel('RespDetails');
            $this->loadModel('CaseType');
            $this->loadModel('salutation');
            $this->loadModel('ObjectionType');
            $this->loadModel('office');
            $this->loadModel('CaseStatusDetails');
            $this->loadModel('notice_generation');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User('user_id');
            if ($tokenval != NULL && is_numeric($tokenval)) {
                //$tokenval = $this->decrypt($tokenval, $this->Session->read("randamkey"));
                // pr($tokenval);exit;
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
//           if (is_null($case_id)) {
//                $this->Session->setFlash(__("Please filled the notice for selected case"));
//                return $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation'));
//            }
            //   pr($case_id);
            //  $case_id = $this->decrypt($case_id1, $this->Session->read("randamkey"));
            $this->set('case_code_id', $case_id);
            $created_date = date('Y-m-d');
//                pr("select first_hearing_date from ngdrstab_ccms_notice_gen where case_id=$case_id");
            $first_hearing_date = $this->notice_generation->query("select first_hearing_date,t.office_name_en,stamp_duty_revised from ngdrstab_ccms_notice_gen as n
inner join ngdrstab_mst_office  t on t.office_id=n.place_of_hearing 
  where case_id=$case_id");
            //  pr($first_hearing_date);exit;
            if (!isset($first_hearing_date[0][0]['first_hearing_date']) && !isset($place_of_hearing[0][0]['office_name_en'])) {
                $this->Session->setFlash(__("Please Fill Notice"));
                $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation/' . $case_id));
            }
            $stamp_duty_revised = $first_hearing_date[0][0]['stamp_duty_revised'];
            $this->set('stamp_duty_revised', $stamp_duty_revised);
            $place_of_hearing = $first_hearing_date[0][0]['office_name_en'];
            $this->set('place_of_hearing', $place_of_hearing);
            $fhdate = $first_hearing_date[0][0]['first_hearing_date'];
            $this->set('fhdate', $fhdate);

            if (is_numeric($case_id)) {
//                pr($case_id);
//                pr("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
//inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
//                where case_id=$case_id");exit;
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_code from ngdrstab_trn_ccms_casedetails c
inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                where case_id=$case_id");
//                 pr($case_code);exit;
                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_code'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }
            $sofficename = ClassRegistry::init('office')->find('list', array('fields' => array('id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC')));
            $this->set('sofficename', $sofficename);
            $case_status = $this->CaseStatus->find('list', array('fields' => array('case_status_id', 'case_status_desc'), 'order' => array('case_status_desc' => 'ASC'), 'conditions' => array('case_status_id' => array(3, 4))));
            $this->set('case_status', $case_status);
            $resp_record = $this->HearingDetails->query("select proceeding_id,hearing_date,next_hearing_date,remark  from ngdrstab_ccms_notice_hearing_details where case_id=$case_id");
            $this->set('resp_record', $resp_record);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
//            $fieldlist['hearing_date']['text'] = '';
            $fieldlist['remark']['text'] = 'is_alphaspace';
            $fieldlist['case_status_id']['select'] = 'is_select_req';
            $fieldlist['remark']['text'] = 'is_required,is_alphaspace';
            $fieldlist['hfid']['text'] = 'is_digit';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_integer';
            // $fieldlist['case_type_id']['text'] = 'is_numeric';
            $fieldlist['csrftoken']['text'] = 'is_integer';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $listdata = $this->RespDetails->find('list', array('fields' => array('resp_id', 'full_name'), 'conditions' => array('case_id' => $case_id), 'order' => 'resp_id DESC'));
            $this->set('listdata', $listdata);
            if ($this->request->is('post') || $this->request->is('put')) {
                // $this->check_csrf_token($this->request->data['proceeding_details']['csrftoken']);
                $this->request->data['user_id'] = $user_id;
                $created_date = date('Y/m/d H:i:s');
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['proceeding_details']['req_ip'] = $this->request->clientIp();
                        $this->request->data['proceeding_details']['user_id'] = $user_id;
                        $this->request->data['proceeding_details']['created_date'] = $created_date;
                        $this->request->data['proceeding_details']['case_id'] = $case_id;
                        $this->request->data['proceeding_details']['actiontype'] = $actiontype;
                        //  $this->request->data['CaseType']['hfupdateflag'] = $hfactionval;
                        $this->request->data['proceeding_details']['hfaction'] = $hfactionval;
                        $this->request->data['proceeding_details']['hfid'] = $hfid;

                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['proceeding_details']['proceeding_id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                            //  $adbc = $alerts['respondententry']['btnupdate'][$laug];
                        } else {
                            $actionvalue = "Saved";
                            // $adbc = $alerts['respondententry']['btnadd'][$laug];
                        }
                        $case_satus_id = $this->request->data['proceeding_details']['case_status_id'];
//                        $this->request->data['proceeding_details'] = $this->istrim($this->request->data['proceeding_details']);
                        $errarr = $this->validatedata($this->request->data['proceeding_details'], $fieldlist);
//                           pr($errarr);exit;
                        if ($this->ValidationError($errarr)) {
                            if ($this->HearingDetails->Save($this->request->data['proceeding_details'])) {
                                $lastid = $this->HearingDetails->getLastInsertId();
                                $this->case_status_update($case_id);
//                            $case_status = $this->CaseStatusDetails->query("INSERT INTO  ngdrstab_ccms_case_status_details(case_id,case_status_id,case_status_flag) VALUES($lastid,$case_satus_id,'Y')");
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                return $this->redirect(array('controller' => 'NewCase', 'action' => 'proceeding_details/' . $case_id));
                            }
                            $this->Session->setFlash(_('Record not saved'));
                        } else {
                            $this->Session->setFlash(__('Enter proper data to proper fields'));
                        }
                    }
                }
            }
            $this->set_csrf_token();
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function proceeding_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('HearingDetails');
        try {
            // $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->HearingDetails->id = $id;
                if ($this->HearingDetails->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );

                    return $this->redirect(array('action' => 'proceeding_details'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function judgement_details($tokenval = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('office', 'notice_generation', 'CaseStatus', 'judgement_details', 'casemainmenus', 'RespDetails', 'casemenus', 'CaseStatusDetails', 'genernal_info', 'CcmsPaymentDetails'));
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d H:i:s');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            if ($tokenval != NULL && is_numeric($tokenval)) {
                // $tokenval = $this->decrypt($tokenval, $this->Session->read("randamkey"));
                //PR($tokenval);EXIT;
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
            //  PR($case_id);
            $this->set('case_code_id', $case_id);
            $stamp_duty_revised1 = $this->notice_generation->query("select stamp_duty_revised from ngdrstab_ccms_notice_gen  where case_id=$case_id");
            //  pr($first_hearing_date);exit;
            if (!isset($stamp_duty_revised1[0][0]['stamp_duty_revised'])) {
                $this->Session->setFlash(__("Please Fill Notice"));
                $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation/' . $case_id));
            }
            $stamp_duty_revised = $stamp_duty_revised1[0][0]['stamp_duty_revised'];
            $this->set('stamp_duty_revised', $stamp_duty_revised);
            if (is_numeric($case_id)) {
                //   pr($case_id);
                //   pr("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
                //                                       inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id where case_id=$case_id");exit;
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_code from ngdrstab_trn_ccms_casedetails c
                                                    inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id where case_id=$case_id");
                //  PR($case_code);EXIT;
                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_code'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }
            $this->set('office', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_en'), 'order' => array('office_name_en' => 'ASC'))));
            $case_status = $this->CaseStatus->find('list', array('fields' => array('case_status_id', 'case_status_desc'), 'order' => array('case_status_desc' => 'ASC'), 'conditions' => array('case_status_id' => array(5, 6))));
            $this->set('case_status', $case_status);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist['date_of_order']['text'] = 'is_required';
            $fieldlist['place_of_hearing']['select'] = 'is_select_req';
            $fieldlist['stamp_duty']['text'] = 'is_required,is_integer';
            $fieldlist['registration_fees']['text'] = 'is_required,is_integer';
            $fieldlist['penalty']['text'] = 'is_required,is_integer';
            $fieldlist['remark']['text'] = 'is_required,is_alphaspace';
            $fieldlist['case_status_id']['select'] = 'is_select_req';
//            $fieldlist['next_hearing_date']['text'] = '';
            $fieldlist['total_amount']['text'] = 'is_integer';
//            $fieldlist['interest']['text'] = 'is_integer';
//            $fieldlist['surcharge']['text'] = 'is_integer';
            $fieldlist['final_judgement']['text'] = 'is_alphanumeric';
            $fieldlist['hfid']['text'] = 'is_digit';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_integer';
            // $fieldlist['case_type_id']['text'] = 'is_numeric';
            $fieldlist['csrftoken']['text'] = 'is_integer';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
//            $name=$this->RespDetails->query("select respondent_f_name,respondent_m_name,respondent_l_name from ngdrstab_mst_respdetails ");
            //$fullname=$name[0][0]['respondent_f_name']." ".$name[0][0]['respondent_m_name']." ".$name[0][0]['respondent_l_name'];

            $listdata = $this->RespDetails->find('list', array('fields' => array('resp_id', 'full_name'), 'conditions' => array('case_id' => $case_id), 'order' => 'resp_id DESC'));
            //   pr($listdata);exit;
            $this->set('listdata', $listdata);
            if ($this->request->is('post')) {

                //  $this->check_csrf_token($this->request->data['judgement_details']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                }
                if ($hfactionval == 'S') {
                    $this->request->data['judgement_details']['req_ip'] = $this->request->clientIp();
                    $this->request->data['judgement_details']['user_id'] = $user_id;
                    $this->request->data['judgement_details']['created'] = $created_date;
                    $this->request->data['judgement_details']['case_id'] = $case_id;
                    $this->request->data['judgement_details']['actiontype'] = $actiontype;
                    //  $this->request->data['CaseType']['hfupdateflag'] = $hfactionval;
                    $this->request->data['judgement_details']['hfaction'] = $hfactionval;
                    $this->request->data['judgement_details']['hfid'] = $hfid;

                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['judgement_details']['id'] = $this->request->data['hfid'];
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }
                    $case_status_id = $this->request->data['judgement_details']['case_status_id'];
                    $this->request->data['judgement_details'] = $this->istrim($this->request->data['judgement_details']);
                    $errarr = $this->validatedata($this->request->data['judgement_details'], $fieldlist);

                    if ($this->ValidationError($errarr)) {
                        // PR($this->request->data['judgement_details']);EXIT;
                        if ($this->judgement_details->Save($this->request->data['judgement_details'])) {
                            $lastid = $this->judgement_details->getLastInsertId();
                            $this->case_status_update($case_id);
                            //  $this->Session->write("selected_token", $lastid);
//                        $case_status = $this->CaseStatusDetails->query("INSERT INTO  ngdrstab_ccms_case_status_details(case_id,case_status_id,case_status_flag) VALUES($lastid,$case_status_id,'Y')");
                            $data1['case_id'] = $case_id;
                            $data1['account_head_code'] = '0030045501';
                            $data1['fee_amount'] = $this->request->data['judgement_details']['stamp_duty'];

                            $data2['case_id'] = $case_id;
                            $data2['account_head_code'] = '0030063301';
                            $data2['fee_amount'] = $this->request->data['judgement_details']['registration_fees'];

                            $data3['case_id'] = $case_id;
                            $data3['account_head_code'] = '41';
                            $data3['fee_amount'] = $this->request->data['judgement_details']['penalty'];

                            $this->CcmsPaymentDetails->create();
                            $this->CcmsPaymentDetails->save($data1);
                            $this->CcmsPaymentDetails->create();
                            $this->CcmsPaymentDetails->save($data2);
                            $this->CcmsPaymentDetails->create();
                            $this->CcmsPaymentDetails->save($data3);

                            $this->Session->setFlash(__("Record $actionvalue Successfully"));
                            $this->redirect(array('controller' => 'NewCase', 'action' => 'judgement_details/' . $case_id));
                        } else {
                            $this->Session->setFlash(__('Record Not Saved '));
                        }
                    } else {
                        $this->Session->setFlash(__('Enter proper data to proper fields'));
                    }
                }
            }
            $this->set_csrf_token();
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function judgement_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('judgement_details');
        try {
            //$id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->judgement_details->id = $id;
                if ($this->judgement_details->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'judgement_details'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

//    function payment_old($tokenval = NULL, $id = NULL) {
//        try {
//            array_map(array($this, 'loadModel'), array('payment_mode', 'bank_master', 'NewCase', 'PaymentPreference', 'CasePaymentDetails', 'article_fee_items'));
//            $this->set('actiontypeval', NULL);
//            $this->set('hfactionval', NULL);
//            $this->set('hfid', NULL);
//            $this->set('hfupdateflag', NULL);
//            $user_id = $this->Session->read("citizen_user_id");
//            $stateid = $this->Auth->User("state_id");
//            $created_date = date('Y-m-d H:i:s');
//            $req_ip = $_SERVER['REMOTE_ADDR'];
//            $lang = $this->Session->read("sess_langauge");
//            $this->set("doc_lang", $this->Session->read('doc_lang'));
//            if ($tokenval != NULL) {
//                $this->Session->write("selected_token", $tokenval);
//            }
//            $case_id = $this->Session->read("selected_token");
//            if (is_null($case_id)) {
//                $this->Session->setFlash(__("Please Select Case"));
//                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
//            }
//            $this->set('case_code_id', $case_id);
//            if (is_numeric($case_id)) {
//                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c inner join ngdrstab_mst_ccms_casetype  t
//                                                   on t.case_type_id=c.case_type_id where case_id=$case_id");
//                $case_code1 = $case_code[0][0]['case_code'];
//                $case_year = $case_code[0][0]['case_year'];
//                $case_type = $case_code[0][0]['case_type_desc'];
//                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
//                $this->set('ccms_case', $ccms_case);
//                $this->Session->write("ccms_case", $ccms_case);
//            } else {
//                $this->set('ccms_case', NULL);
//                $this->Session->write("ccms_case", NULL);
//            }
//            if (is_numeric($id)) {
//                if ($this->CasePaymentDetails->deleteAll(array('id' => $id, 'case_id' => $case_id, 'user_id' => $user_id))) {
//                    $this->Session->setFlash(__("Record Deleted Successfully"));
//                }
//
//                $this->redirect(array('controller' => 'NewCase', 'action' => 'payment'));
//            }
//            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => 2), 'fields' => array('fee_item_id', 'fee_item_desc_en')));
//            //$payment_mode = $this->payment_mode->get_payment_mode($lang);
//
//            $payment = $this->CasePaymentDetails->get_all_payment($case_id, $user_id);
//            $this->set(compact('payment_mode', 'payment', 'accounthead'));
//
//
//
//            if ($this->request->is('post')) {
////                $this->check_csrf_token($this->request->data['payment']['csrftoken']);
//                $this->request->data['payment']['state_id'] = $this->Auth->User('state_id');
//                $this->request->data['payment']['user_id'] = $user_id;
//                $this->request->data['payment']['req_ip'] = $_SERVER['REMOTE_ADDR'];
//                $this->request->data['payment']['case_id'] = $case_id;
//                if (isset($this->request->data['payment']['pdate'])) {
//                    $this->request->data['payment']['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['pdate'])));
//                }
//                if (isset($this->request->data['payment']['estamp_issue_date'])) {
//                    $this->request->data['payment']['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['estamp_issue_date'])));
//                }
//                if ($this->CasePaymentDetails->Save($this->request->data['payment'])) {
//                    if (isset($this->request->data['payment']['id'])) {
//                        $lastid = $this->request->data['payment']['id'];
//                        $this->Session->setFlash(__("Record Updated Successfully"));
//                    } else {
//                        $lastid = $this->CasePaymentDetails->getLastInsertId();
//                        $this->case_status_update($case_id);
//                        $this->Session->setFlash(__("Record Saved Successfully"));
//                    }
//                } else {
//                    $this->Session->setFlash(__('Record Not saved '));
//                }
//                if ($this->Session->read("user_role_id") == 1) {
//                    $this->redirect(array('controller' => 'NewCase', 'action' => 'payment/' . $case_id));
//                } else {
//                    
//                }
//            }
//        } catch (Exception $exc) {
//            pr($exc);
//            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
////        $this->set_csrf_token();
//    }

    function payment($tokenval = NULL, $id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('payment_mode', 'judgement_details', 'notice_generation', 'bank_master', 'NewCase', 'PaymentPreference', 'CasePaymentDetails', 'article_fee_items', 'PaymentFields', 'CcmsPayment'));
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $lang = $this->Session->read("sess_langauge");
            $this->set("doc_lang", $this->Session->read('doc_lang'));
            if ($tokenval != NULL && is_numeric($tokenval)) {
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
            $this->set('case_code_id', $case_id);
            $judgement_Details = $this->judgement_details->query("select date_of_order,final_judgement from ngdrstab_ccms_judgement_details where case_id=$case_id");
//            pr($judgement_Details);
//            exit;

            $first_hearing_date = $this->notice_generation->query("select stamp_duty_revised from ngdrstab_ccms_notice_gen  where case_id=$case_id");

            if (!isset($judgement_Details[0][0]['date_of_order']) && !isset($judgement_Details[0][0]['final_judgement'])) {
                $this->Session->setFlash(__("Please Fill Judgement details"));
                $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation/' . $case_id));
            }
            $stamp_duty_revised = $first_hearing_date[0][0]['stamp_duty_revised'];
            $this->set('stamp_duty_revised', $stamp_duty_revised);
            $final_judgement = $judgement_Details[0][0]['final_judgement'];
            $this->set('final_judgement', $final_judgement);
            $date_of_order = $judgement_Details[0][0]['date_of_order'];
            $this->set('date_of_order', $date_of_order);
            if (is_numeric($case_id)) {
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c inner join ngdrstab_mst_ccms_casetype  t
                                                   on t.case_type_id=c.case_type_id where case_id=$case_id");

                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_desc'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }
            if (is_numeric($id)) {
                if ($this->CasePaymentDetails->deleteAll(array('id' => $id, 'case_id' => $case_id, 'user_id' => $user_id))) {
                    $this->Session->setFlash(__("Record Deleted Successfully"));
                }
                $this->redirect(array('controller' => 'NewCase', 'action' => 'payment'));
            }
            $lang = $this->Session->read("sess_langauge");
            $token = $this->Session->read('certified_copy_token');
            $office_id = $this->Session->read("office_id");
            $payment_mode_counter = $this->payment_mode->get_payment_mode_counter($lang);
            $payment_mode_online = $this->payment_mode->get_payment_mode_online($lang);
            $feedetails = $this->article_fee_items->query("SELECT 
         feeitem.account_head_code,feeitem.fee_item_desc_$lang,payment.fee_amount as totalsd,payment.case_id,payment.payment_id FROM ngdrstab_trn_ccms_payment_details payment 
LEFT JOIN ngdrstab_mst_article_fee_items feeitem  ON feeitem.account_head_code=payment.account_head_code  WHERE  payment.case_id=?     AND feeitem.fee_param_type_id=2 
group by feeitem.fee_item_id,payment.fee_amount,payment.case_id,payment.payment_id order by feeitem.fee_preference ASC ", array($case_id));
            //pr($case_id);exit;
            $paymentfields = $this->PaymentFields->find('list', array('fields' => array('field_name', 'field_name_desc_en'), 'order' => 'srno ASC'));

            $payment = $this->CcmsPayment->query("select pay.*,mode.payment_mode_desc_$lang ,mode.verification_flag  FROM ngdrstab_trn_ccms_payment pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.case_id=?  ", array($case_id));

            $this->set(compact('payment_mode_counter', 'payment_mode_online', 'token', 'feedetails', 'payment', 'lang', 'paymentfields'));
            $fieldlist['paymentGetPaymentDetailsForm'] = $this->PaymentFields->fieldlist();
            $fieldlist['payment_head']['final_value']['text'] = 'is_required,is_numeric';
            $fieldlist['payment_head']['account_head_id']['select'] = 'is_select_req';
            $fieldlist['payment_head']['paymentmode_id']['select'] = 'is_select_req';
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
                } else {
                    $csrftoken = NULL;
                }
                $this->check_csrf_token($csrftoken);
//                $this->check_csrf_token($this->request->data['payment']['csrftoken']);
                if (isset($this->request->data['payment'])) {
                    $request_data = $this->request->data['payment'];
                    $fieldlist_new = $this->PaymentFields->fieldlist($request_data['payment_mode_id']);
                    $errors = $this->validatedata($request_data, $fieldlist_new);
                    /* Validation Errors  Using Validation Script */
                    if ($this->ValidationError($errors)) {
                        $this->request->data['payment']['state_id'] = $this->Auth->User('state_id');
                        $this->request->data['payment']['user_id'] = $user_id;
                        $this->request->data['payment']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                        $this->request->data['payment']['case_id'] = $case_id;
                        if (isset($this->request->data['payment']['pdate'])) {
                            $this->request->data['payment']['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['pdate'])));
                        }
                        if (isset($this->request->data['payment']['estamp_issue_date'])) {
                            $this->request->data['payment']['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['estamp_issue_date'])));
                        }
                        if ($this->CcmsPayment->Save($this->request->data['payment'])) {
                            if (isset($this->request->data['payment']['id'])) {
                                $lastid = $this->request->data['payment']['id'];
                                $this->Session->setFlash(__("Record Updated Successfully"));
                            } else {
                                $lastid = $this->CasePaymentDetails->getLastInsertId();
                                $this->Session->setFlash(__("Record Saved Successfully"));
                            }
                            $this->redirect(array('controller' => 'NewCase', 'action' => 'payment'));
                        } else {
                            $this->Session->setFlash(__('Record Not saved '));
                        }
                    } else {
                        $this->set('RequestData', $request_data);
                        $this->Session->setFlash(__("Validation errors"));
                    }
                } else if (isset($this->request->data['payment_accept'])) {
                    $this->case_status_update($case_id);
                    $this->Session->setFlash(__("Status Updated Successfully"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'payment'));
                }
            }
              $this->set_csrf_token();
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
//        $this->set_csrf_token();
    }

    function get_ccms_payment_details() {
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

    function get_payment_details() {
        try {
            array_map(array($this, 'loadModel'), array('CasePaymentDetails', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items'));
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $case_id = $this->Session->read('selected_token');
            $data = $this->request->data;
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode']), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->CasePaymentDetails->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'id' => $data['id'], 'case_id' => $case_id, 'user_id' => $user_id)));
                $this->set("payment", $payment);
            }
            foreach ($paymentfields as $field) {
                if ($field['PaymentFields']['field_name'] == 'bank_id') {
                    $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name')));
                    $this->set("bank_master", $bank_master);
                    if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                        $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch'), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                    } else {
                        $branch_master = array();
                    }
                    $this->set("branch_master", $branch_master);
                }
                if ($field['PaymentFields']['field_name'] == 'cos_id') {
                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_en'), 'conditions' => array('hierarchy_id' => 45)));
                    $this->set("office", $office);
                }
            }
            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => array(2)), 'fields' => array('fee_item_id', 'fee_item_desc_en')));
            $this->set("accounthead", $accounthead);
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function major_functions() {
        $this->loadModel('casemainmenus');
        $result = $this->casemainmenus->find("all", array('order' => array('mf_serial ASC')));
        return $result;
    }

    public function minor_functions() {
        $this->loadModel('casemenus');
        $result = $this->casemenus->find("all", array('order' => array('mf_serial ASC')));
        return $result;
    }

    public function case_status($case_id) {

        $this->loadModel('CaseStatusDetails');
        $this->loadModel('CaseStatus');
        $this->case_status_update($case_id);
        $check = $this->CaseStatusDetails->query("select csd.case_id,status.case_status_id,
            status.case_status_code,csd.case_status_deatils_id 
            from ngdrstab_ccms_case_status_details as
            csd,ngdrstab_ccms_case_status as status
            where csd.case_status_id=status.case_status_id and  csd.case_id=$case_id");
        if (!empty($check)) {
            return $check[0][0]['case_status_code'];
            //   return $status;}
        } else {
            return "NA";
        }
    }

//    public function case_status_update($case_id) {
//        $this->loadModel('notice_generation');
//        $this->loadModel('NewCase');
//        $this->loadModel('RespDetails');
//        $this->loadModel('judgement_details');
//        $this->loadModel('HearingDetails');
//        $this->loadModel('CasePaymentDetails');
//        $this->loadModel('CaseStatus');
//        $this->loadModel('CaseStatusDetails');
//        $this->loadModel('case_disposal');
//        $this->loadModel('CcmsPayment');
//
//        $status = array();
//        $newstatus = "NA";
//        //pr($case_id);exit;
//        $result1 = $this->NewCase->find("all", array('conditions' => array('case_id' => $case_id)));
//        $result2 = $this->RespDetails->find("all", array('conditions' => array('case_id' => $case_id)));
//        if (!empty($result1) && !empty($result2)) {
//            $status['CASESUB'] = 1;
//            $newstatus = 'NOT';
//        } else {
//            $status['CASESUB'] = 0;
//            $newstatus = 'CASESUB';
//        }
//        $created_date = date('Y-m-d');
//        $result = $this->notice_generation->find("all", array('conditions' => array('case_id' => $case_id)));
//        // pr($result);
//        if (!empty($result)) {
//            $flag = 0;
//            foreach ($result as $record) {
//                if ($record['notice_generation']['first_hearing_date'] == $created_date) {
//                    $newstatus = 'ONBOARD';
//                    $flag = 1;
//                }
//            }
//            if ($flag == 0) {
//                $newstatus = 'NBOARD';
//            }
//        }
//        $result1 = $this->HearingDetails->query("select proceeding_id,status.case_status_code from ngdrstab_ccms_notice_hearing_details as
//                                                 hearing,ngdrstab_ccms_case_status as status
//                                                where hearing.case_status_id=status.case_status_id AND hearing.case_id=$case_id ORDER BY proceeding_id DESC");
//        // pr($result1);
//
//        if (!empty($result1)) {
//            if ($result1[0][0]['case_status_code'] == 'CLHEA') {
//                $newstatus = 'FORD';
//            }
//        }
//        $result2 = $this->judgement_details->query("select judgement_details_id,status.case_status_code from ngdrstab_ccms_judgement_details 
//                                                   as judgement,ngdrstab_ccms_case_status as status
//                                                 where judgement.case_status_id=status.case_status_id AND judgement.case_id=$case_id ORDER BY judgement_details_id DESC");
//        if (!empty($result2)) {
//            if ($result2[0][0]['case_status_code'] == 'CLORD') {
//                $newstatus = 'NPAID';
//            }
//        }
//        $result3 = $this->CcmsPayment->find("all", array('conditions' => array('case_id' => $case_id)));
////        pr($case_id);
////        pr($result3);exit;
//        if (!empty($result3)) {
//            $newstatus = 'PAID';
//        } if ($newstatus == 'CLORD') {
//            $newstatus = 'NPAID';
//        }
//        $updatedstatus = $this->CaseStatus->find('list', array('fields' => array('case_status_code', 'case_status_id'), 'conditions' => array('case_status_code' => $newstatus)));
//        $check = $this->CaseStatusDetails->find("all", array('conditions' => array('case_id' => $case_id)));
////       pr($check);
//        if (!empty($check)) {
//            $case_status_id = $updatedstatus[$newstatus];
//// pr($case_status_id);exit;
//            $result2 = $this->CaseStatusDetails->query("UPDATE ngdrstab_ccms_case_status_details SET case_status_id=$case_status_id WHERE case_id=$case_id");
//        } else {
//            $case_status_id = $updatedstatus[$newstatus];
//
//            $result2 = $this->CaseStatusDetails->query("insert into  ngdrstab_ccms_case_status_details(case_id,case_status_id) values($case_id,$case_status_id)");
//        }
//
//        $case_disposed = $this->case_disposal->find("all", array('conditions' => array('case_id' => $case_id)));
//        // pr($case_id);exit;
//        if (!empty($case_disposed)) {
//            $newstatus = 'DISP';
//            $updatedstatus = $this->CaseStatus->find('list', array('fields' => array('case_status_code', 'case_status_id'), 'conditions' => array('case_status_code' => $newstatus)));
//
//            // PR($updatedstatus);EXIT;
//            $case_status_id = $updatedstatus[$newstatus];
//
//            $result2 = $this->CaseStatusDetails->query("UPDATE ngdrstab_ccms_case_status_details SET case_status_id=$case_status_id WHERE case_id=$case_id");
//        }
//    }
    public function case_status_update($case_id) {
        $this->loadModel('notice_generation');
        $this->loadModel('NewCase');
        $this->loadModel('RespDetails');
        $this->loadModel('judgement_details');
        $this->loadModel('HearingDetails');
        $this->loadModel('CasePaymentDetails');
        $this->loadModel('CaseStatus');
        $this->loadModel('CaseStatusDetails');
        $this->loadModel('case_disposal');
        $this->loadModel('CcmsPayment');

        $status = array();
        $newstatus = "NA";
        //pr($case_id);exit;
        $result1 = $this->NewCase->find("all", array('conditions' => array('case_id' => $case_id)));
        $result2 = $this->RespDetails->find("all", array('conditions' => array('case_id' => $case_id)));
        if (!empty($result1) && !empty($result2)) {
            $status['CASESUB'] = 1;
            $newstatus = 'NOT';
        } else {
            $status['CASESUB'] = 0;
            $newstatus = 'CASESUB';
        }



        $created_date = date('Y-m-d');
        $result = $this->notice_generation->find("all", array('conditions' => array('case_id' => $case_id)));
        // pr($result);
        if (!empty($result)) {
            $flag = 0;
            foreach ($result as $record) {
                if ($record['notice_generation']['first_hearing_date'] == $created_date) {
                    $newstatus = 'ONBOARD';
                    $flag = 1;
                }
            }
            if ($flag == 0) {
                $newstatus = 'NBOARD';
            }
        }
        $result1 = $this->HearingDetails->query("select proceeding_id,status.case_status_code from ngdrstab_ccms_notice_hearing_details as
                                                 hearing,ngdrstab_ccms_case_status as status
                                                where hearing.case_status_id=status.case_status_id AND hearing.case_id=$case_id ORDER BY proceeding_id DESC");
        // pr($result1);

        if (!empty($result1)) {
            if ($result1[0][0]['case_status_code'] == 'CLHEA') {
                $newstatus = 'FORD';
            }
        }
        $result2 = $this->judgement_details->query("select judgement_details_id,status.case_status_code from ngdrstab_ccms_judgement_details 
                                                   as judgement,ngdrstab_ccms_case_status as status
                                                 where judgement.case_status_id=status.case_status_id AND judgement.case_id=$case_id ORDER BY judgement_details_id DESC");
        if (!empty($result2)) {
            if ($result2[0][0]['case_status_code'] == 'CLORD') {
                $newstatus = 'NPAID';
            }
        }
        $result3 = $this->CcmsPayment->find("all", array('conditions' => array('case_id' => $case_id)));
//        pr($case_id);
//        pr($result3);exit;
        if (!empty($result3)) {
            $newstatus = 'PAID';
        } if ($newstatus == 'CLORD') {
            $newstatus = 'NPAID';
        }




        $updatedstatus = $this->CaseStatus->find('list', array('fields' => array('case_status_code', 'case_status_id'), 'conditions' => array('case_status_code' => $newstatus)));
        $check = $this->CaseStatusDetails->find("all", array('conditions' => array('case_id' => $case_id)));
//       pr($check);
        if (!empty($check)) {
            $case_status_id = $updatedstatus[$newstatus];
// pr($case_status_id);exit;
            $result2 = $this->CaseStatusDetails->query("UPDATE ngdrstab_ccms_case_status_details SET case_status_id=$case_status_id WHERE case_id=$case_id");
        } else {
            $case_status_id = $updatedstatus[$newstatus];

            $result2 = $this->CaseStatusDetails->query("insert into  ngdrstab_ccms_case_status_details(case_id,case_status_id) values($case_id,$case_status_id)");
        }

        $case_disposed = $this->case_disposal->find("all", array('conditions' => array('case_id' => $case_id)));
        // pr($case_id);exit;
        if (!empty($case_disposed)) {
            $newstatus = 'DISP';
            $updatedstatus = $this->CaseStatus->find('list', array('fields' => array('case_status_code', 'case_status_id'), 'conditions' => array('case_status_code' => $newstatus)));

            // PR($updatedstatus);EXIT;
            $case_status_id = $updatedstatus[$newstatus];

            $result2 = $this->CaseStatusDetails->query("UPDATE ngdrstab_ccms_case_status_details SET case_status_id=$case_status_id WHERE case_id=$case_id");
        }
    }

    function get_bank_branch() {
        $this->autoRender = FALSE;
        $this->loadModel('BankBranch');
        $branch = $this->BankBranch->find("list", array('fields' => array('id', 'branch'), 'conditions' => array('bank_id' => $_GET['bank'])));
        return json_encode($branch);
    }

    function get_bank_branch_code() {
        $this->autoRender = FALSE;
        $this->loadModel('BankBranch');
        $branch = $this->BankBranch->find("list", array('fields' => array('id', 'ifsc'), 'conditions' => array('id' => $_GET['branch'])));
        return json_encode($branch);
    }

    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = NULL) {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mPDF');
            $mpdf = new mPDF('utf-8', $page_size);
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
            $mpdf->Output($file_name . ".pdf", 'I');
        } catch (Exception $ex) {
            echo 'there is some error in creating PDF';
        }
    }

    public function sample($notice_gen_id = null) {
        try {
            // $notice_gen_id = $this->decrypt($notice_gen_id, $this->Session->read("randamkey"));
            //   $notice_gen_id = $this->decrypt($notice_gen_id);
            //  pr($notice_gen_id);exit;
            $this->loadModel('notice_generation');
            $data = $this->notice_generation->query("select n.case_id,n.notice_gen_id,c.stamp_duty,t.office_name_en,n.notice_date,n.first_hearing_date,c.case_code,c.case_year from ngdrstab_ccms_notice_gen as n 
inner join ngdrstab_mst_office  t on t.office_id=n.place_of_hearing 
inner join ngdrstab_trn_ccms_casedetails  c on c.case_id=n.case_id 
and
 n.notice_gen_id=$notice_gen_id");
            $office_name = $data[0][0]['office_name_en'];
            $stamp_duty = $data[0][0]['stamp_duty'];
            $notice_date = $data[0][0]['notice_date'];
            $first_hearing_date = $data[0][0]['first_hearing_date'];
            $case_deatils = $data[0][0]['case_id'] . "-" . $data[0][0]['case_code'] . "-" . $data[0][0]['case_year'];
            $design = "<h1>sample</h1>";
            $design .= "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=50% border=1 align=center>"
                    . "<hr style='color: #FA9;height: 5px;'/>"
                    . "<tr> <td> <b>Place of Hearing</b> </td> <td width=60%>" . $office_name . "</td></tr>"
                    . "<tr> <td> <b> Notice Dtae</b> </td> <td>" . $stamp_duty . "</td> </tr>"
                    . "<tr> <td> <b> Notice Dtae</b> </td> <td>" . $notice_date . "</td> </tr>"
                    . "<tr> <td> <b>First Hearing Date</b></td> <td>" . $first_hearing_date . "</td> </tr>"
                    . "<tr> <td><b>Case Details</b> </td> <td>" . $case_deatils . "</td></tr>"
                    . "</table>";

            $this->create_pdf($design, "doc_payment_receipt_" . $payment_id, 'A4', 'ccms');
        } catch (Exception $ex) {
            
        }
    }

    public function history($tokenval = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('notice_generation', 'CaseStatus', 'casemainmenus', 'casemenus'));
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d H:i:s');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('result1', ClassRegistry::init('NewCase')->find('list', array('fields' => array('case_id', 'case_code'), 'order' => array('case_code' => 'ASC'))));
            $this->set('result2', ClassRegistry::init('CaseStatus')->find('list', array('fields' => array('case_status_id', 'case_status_desc'), 'order' => array('case_status_desc' => 'ASC'))));
            if ($tokenval != NULL) {
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            $this->set('case_code_id', $case_id);
            if (is_numeric($case_id)) {
                $case_code = $this->NewCase->query(" select c.case_id,c.case_code,c.case_year,t.case_type_desc,v.office_name_en from ngdrstab_trn_ccms_casedetails c
                inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id 
                inner join ngdrstab_mst_office v on v.office_id=c.case_belongs_to 
                where case_id=$case_id");
                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
//                pr($case_code);exit;
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_desc'];
                $office_name = $case_code[0][0]['office_name_en'];
                $this->set('office_name', $office_name);
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }

            $this->set('noticerecord', $this->notice_generation->query("select c.case_id,t.case_status_code,d.case_code,d.created,r.respondent_f_name,r.respondent_m_name,r.respondent_l_name from ngdrstab_ccms_case_status_details c
                inner join ngdrstab_ccms_case_status  t on t.case_status_id=c.case_status_id 
                inner join ngdrstab_trn_ccms_casedetails d on d.case_id=c.case_id 
                inner join ngdrstab_mst_respdetails r on r.case_id=c.case_id 
            and c.case_status_id=11 and c.case_status_deatils_id=150 and c.case_id=$case_id"));
        } catch (Exception $ex) {
            
        }
    }

    public function casestatus() {
        try {
            $this->loadModel('CaseStatus');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->set('casestatusrecord', $this->CaseStatus->find('all'));
            if ($this->request->is('post')) {
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['CaseStatus']['req_ip'] = $this->request->clientIp();
                        $this->request->data['CaseStatus']['user_id'] = $user_id;
                        $this->request->data['CaseStatus']['created_date'] = $created_date;
                        $this->request->data['CaseStatus']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['CaseStatus']['id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                        } else {
                            $actionvalue = "Saved";
                        }
                        if ($this->CaseStatus->save($this->request->data['CaseStatus'])) {
                            $this->Session->setFlash(__("Record $actionvalue Successfully"));
                            $this->redirect(array('controller' => 'NewCase', 'action' => 'casestatus'));
                        } else {
                            $this->Session->setFlash(__('Record Not saved'));
                        }
                    }
                }
                if ($_POST['actiontype'] == '3') {
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'casestatus'));
                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_casestatus($id = null) {
        $this->autoRender = false;
        $this->loadModel('CaseStatus');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->CaseStatus->id = $id;
                if ($this->CaseStatus->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'casestatus'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function Casetype() {
        try {
            // $this->check_role_escalation();
            $this->loadModel('CaseType');
            $this->loadModel('mainlanguage');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->set('casetyperecord', $this->CaseType->find('all'));
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist = array();
            $fieldlist['case_type_desc']['text'] = 'is_required,is_alphaspace';
            $fieldlist['case_type_code']['text'] = 'is_required,is_alpha';

            //  $fieldlist['hfupdateflag']['text'] = 'is_yes_no';

            $fieldlist['hfid']['text'] = 'is_numeric';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_numeric';
            $fieldlist['case_type_id']['text'] = 'is_digit';
            $fieldlist['csrftoken']['text'] = 'is_integer';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                //  pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['CaseType']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['CaseType']['req_ip'] = $this->request->clientIp();
                        $this->request->data['CaseType']['user_id'] = $user_id;
                        $this->request->data['CaseType']['created_date'] = $created_date;
                        $this->request->data['CaseType']['state_id'] = $stateid;
                        $this->request->data['CaseType']['actiontype'] = $actiontype;
                        //  $this->request->data['CaseType']['hfupdateflag'] = $hfactionval;
                        $this->request->data['CaseType']['hfaction'] = $hfactionval;
                        $this->request->data['CaseType']['hfid'] = $hfid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['CaseType']['case_type_id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                        } else {

                            $actionvalue = "Saved";
                        }
                        $this->request->data['CaseType'] = $this->istrim($this->request->data['CaseType']);
                        if (isset($this->request->data['hfid'])) {
                            $this->request->data['CaseType']['case_type_id'] = $this->request->data['hfid'];
                        }
                        $errarr = $this->validatedata($this->request->data['CaseType'], $fieldlist);
                        // pr($errarr);exit;
                        if ($this->ValidationError($errarr)) {
                            $this->request->data['CaseType'] = $this->encode_special_char($this->request->data['CaseType']);
                            if ($this->CaseType->save($this->request->data['CaseType'])) {
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                $this->redirect(array('controller' => 'NewCase', 'action' => 'Casetype'));
                            } else {
                                $this->Session->setFlash(__('Record Not saved '));
                            }
                        } else {
                            $this->Session->setFlash(__('Enter proper data to proper fields'));
                        }
                    }
                }

                if ($_POST['actiontype'] == '3') {
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'Casetype'));
                }
            }
            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_Casetype($id = null) {
        $this->autoRender = false;
        $this->loadModel('CaseType');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->CaseType->id = $id;
                if ($this->CaseType->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'Casetype'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function Objectiontype() {
        try {
            // $this->check_role_escalation();
            $this->loadModel('ObjectionType');
            $this->loadModel('mainlanguage');
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->set('objectionrecord', $this->ObjectionType->find('all'));
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $fieldlist = array();
            $fieldlist['objection_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['hfid']['text'] = 'is_numeric';
             $fieldlist['hfaction']['text'] = 'is_s_n';
            $fieldlist['actiontype']['text'] = 'is_numeric';
            $fieldlist['objection_type_id']['text'] = 'is_digit';
            $fieldlist['csrftoken']['text'] = 'is_integer';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['ObjectionType']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $this->request->data['ObjectionType']['req_ip'] = $this->request->clientIp();
                        $this->request->data['ObjectionType']['user_id'] = $user_id;
                        $this->request->data['ObjectionType']['created_date'] = $created_date;
                        $this->request->data['ObjectionType']['state_id'] = $stateid;
                        $this->request->data['ObjectionType']['actiontype'] = $actiontype;
                        //  $this->request->data['ObjectionType']['hfupdateflag'] = $hfactionval;
                        $this->request->data['ObjectionType']['hfaction'] = $hfactionval;
                        $this->request->data['ObjectionType']['hfid'] = $hfid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['ObjectionType']['objection_type_id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                        } else {
                            $actionvalue = "Saved";
                        }
                        $this->request->data['ObjectionType'] = $this->istrim($this->request->data['ObjectionType']);
                        if (isset($this->request->data['hfid'])) {
                            $this->request->data['ObjectionType']['objection_type_id'] = $this->request->data['hfid'];
                        }
                        $errarr = $this->validatedata($this->request->data['ObjectionType'], $fieldlist);
                        if ($this->ValidationError($errarr)) {
                            $this->request->data['CaseType'] = $this->encode_special_char($this->request->data['ObjectionType']);
                            if ($this->ObjectionType->save($this->request->data['ObjectionType'])) {
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                $this->redirect(array('controller' => 'NewCase', 'action' => 'Objectiontype'));
                            } else {
                                $this->Session->setFlash(__('Record Not saved '));
                            }
                        } else {
                            $this->Session->setFlash(__('Enter proper data to proper fields'));
                        }
                    }
                }
                if ($_POST['actiontype'] == '3') {
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'Objectiontype'));
                }
            }

            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_Objectiontype($id = null) {
        $this->autoRender = false;
        $this->loadModel('ObjectionType');
        try {
            $id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->ObjectionType->id = $id;
                if ($this->ObjectionType->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'Objectiontype'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

    public function reports($tokenval = NULL) {
        $this->loadModel('notice_generation');
        if ($tokenval != NULL) {
            $this->Session->write("selected_token", $tokenval);
        }
        $case_id = $this->Session->read("selected_token");
        $this->set('noticerecord', $this->notice_generation->query("select * from ngdrstab_trn_ccms_casedetails"));
    }

    public function sample_report($case_id = null) {
        try {
            $this->loadModel('notice_generation');
            $data = $this->notice_generation->query("select cd.case_id,cd.case_year,cd.case_code,ct.case_type_desc,ot.objection_name,cd.stamp_duty,cd.case_admited_date,
t.office_name_en,cd.advocate_f_name,cd.advocate_m_name,cd.advocate_l_name
from
      ngdrstab_trn_ccms_casedetails as cd
left outer join ngdrstab_mst_office  t on t.office_id=cd.case_belongs_to 
left outer join ngdrstab_mst_ccms_objectiontype  ot on ot.objection_type_id=cd.objection_type_id
left outer join ngdrstab_mst_ccms_casetype  ct on ct.case_type_id=cd.case_type_id
left outer join ngdrstab_mst_respdetails rd on rd.case_id=cd.case_id
left outer join ngdrstab_ccms_notice_gen ng on ng.case_id=cd.case_id
where cd.case_id=$case_id");
            // $casedetailsid=$data[0][0]['case_id'];
            $respondentdata = $this->notice_generation->query("select * from ngdrstab_mst_respdetails where case_id=$case_id");
//            pr($respondentdata);exit;
            $noticedata = $this->notice_generation->query("select * from ngdrstab_ccms_notice_gen where case_id=$case_id");
//               pr($noticedata);exit;
            foreach ($noticedata as $nd) {
                //  pr($nd);exit;
                $currentdata = $nd[0]['notice_gen_id'];
            }
            $advocatename = $data[0][0]['advocate_f_name'] . $data[0][0]['advocate_m_name'] . $data[0][0]['advocate_l_name'];
            $respondentname = $respondentdata[0][0]['respondent_f_name'] . $respondentdata[0][0]['respondent_m_name'] . $respondentdata[0][0]['respondent_l_name'];
            $respondentadvocatename = $respondentdata[0][0]['respondent_advocate_f_name'] . $respondentdata[0][0]['respondent_advocate_m_name'] . $respondentdata[0][0]['respondent_advocate_l_name'];
            $case_deatils = $data[0][0]['case_code'] . "-" . $data[0][0]['case_type_desc'] . "-" . $data[0][0]['case_year'];
            $first_hearing_date = $noticedata[0][0]['first_hearing_date'];
            $case_deatils = $data[0][0]['case_id'] . "-" . $data[0][0]['case_code'] . "-" . $data[0][0]['case_year'];
            $design = "<h1>Demo</h1>";
            $design .= "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=50% border=1 align=center>"
                    . "<hr style='color: #FA9;height: 5px;'/>"
                    . "<tr> <td> <b>Case ID</b> </td> <td width=60%>" . $data[0][0]['case_id'] . "</td></tr>"
                    . "<tr> <td><b>Case Details</b> </td> <td>" . $case_deatils . "</td></tr>"
                    . "<tr> <td> <b>Place of Hearing</b> </td> <td width=60%>" . $data[0][0]['office_name_en'] . "</td></tr>"
                    . "<tr> <td> <b> Advocate Name</b> </td> <td>" . $advocatename . "</td> </tr>"
                    . "<tr> <td> <b> Respondent Name</b> </td> <td>" . $respondentname . "</td> </tr>"
                    . "<tr> <td> <b> Respondent Advocate Name</b> </td> <td>" . $respondentadvocatename . "</td> </tr>"
                    . "<tr> <td> <b> Respondent Address</b> </td> <td>" . $respondentdata[0][0]['respondent_address'] . "</td> </tr>"
                    . "<tr> <td> <b> Respondent Email ID</b> </td> <td>" . $respondentdata[0][0]['respondent_email_id'] . "</td> </tr>"
                    . "<tr> <td>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    "
                    . "<b> First Hearing Date</b> </td> <td>" . $currentdata . "</td> </tr>"
                    . "<tr> <td> <b>Remark</b></td> <td>" . $currentdata . "</td> </tr>"
                    . "</table>";
            $this->create_pdf($design, "doc_payment_receipt_" . $case_deatils, 'A4', 'ccms');
        } catch (Exception $ex) {
            
        }
    }

    public function case_disposal($tokenval = NULL) {
        try {

            array_map(array($this, 'loadModel'), array('office', 'CcmsPayment', 'CaseStatus', 'judgement_details', 'casemainmenus', 'casemenus', 'CaseStatusDetails', 'genernal_info', 'case_disposal'));

            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d H:i:s');
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            if ($tokenval != NULL && is_numeric($tokenval)) {
                // $tokenval = $this->decrypt($tokenval, $this->Session->read("randamkey"));
                //PR($tokenval);EXIT;
                $this->Session->write("selected_token", $tokenval);
            }
            $case_id = $this->Session->read("selected_token");
            if (is_null($case_id)) {
                $this->Session->setFlash(__("Please Select Case"));
                return $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
            }
            //  PR($case_id);
            $this->set('case_code_id', $case_id);
            if (is_numeric($case_id)) {
                //   pr($case_id);
                //   pr("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
                //                                       inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id where case_id=$case_id");exit;
                $case_code = $this->NewCase->query("select c.case_code,c.case_year,t.case_type_desc from ngdrstab_trn_ccms_casedetails c
                                                    inner join ngdrstab_mst_ccms_casetype  t on t.case_type_id=c.case_type_id where case_id=$case_id");
                //  PR($case_code);EXIT;

                if (empty($case_code)) {
                    $this->Session->setFlash(__("Invalid Case ID"));
                    $this->redirect(array('controller' => 'NewCase', 'action' => 'genernal_info'));
                }
                $case_code1 = $case_code[0][0]['case_code'];
                $case_year = $case_code[0][0]['case_year'];
                $case_type = $case_code[0][0]['case_type_desc'];
                $ccms_case = $case_type . "-" . $case_code1 . "-" . $case_year;
                $this->set('ccms_case', $ccms_case);
                $this->Session->write("ccms_case", $ccms_case);
            } else {
                $this->set('ccms_case', NULL);
                $this->Session->write("ccms_case", NULL);
            }

//              $judgement_Details = $this->judgement_details->query("select date_of_order,final_judgement from ngdrstab_ccms_judgement_details where case_id=$case_id");
//              if (!isset($judgement_Details[0][0]['date_of_order']) && !isset($judgement_Details[0][0]['final_judgement'])) {
//                $this->Session->setFlash(__("Please Fill Judgement details"));
//                $this->redirect(array('controller' => 'NewCase', 'action' => 'notice_generation/' . $case_id));
//            }
            $judgement_date = $this->case_disposal->query(" SELECT date_of_order FROM ngdrstab_ccms_judgement_details  where case_id=$case_id");
            //  $judge_date=$judgement_date[0][0]['date_of_order'];
            $this->set('judge_date', $judgement_date[0][0]['date_of_order']);
            $case_status = $this->CaseStatus->find('list', array('fields' => array('case_status_id', 'case_status_desc'), 'order' => array('case_status_desc' => 'ASC'), 'conditions' => array('case_status_id' => array(13))));
            $this->set('case_status', $case_status);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
//            $fieldlist['judgement_date']['text'] = '';
            $fieldlist['remark']['text'] = 'is_alphaspace';

            $fieldlist['case_status_id']['select'] = 'is_select_req';
            $fieldlist['receipt_number']['text'] = 'is_integer';
            $fieldlist['remark']['text'] = 'is_required,is_alphaspace';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {

                //  $this->check_csrf_token($this->request->data['case_disposal']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                }
                if ($hfactionval == 'S') {
                    $this->request->data['case_disposal']['req_ip'] = $this->request->clientIp();
                    $this->request->data['case_disposal']['user_id'] = $user_id;
                    $this->request->data['case_disposal']['created'] = $created_date;
                    $this->request->data['case_disposal']['case_id'] = $case_id;
                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['case_disposal']['case_disposal_id'] = $this->request->data['hfid'];
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }
                    $case_status_id = $this->request->data['case_disposal']['case_status_id'];
                    $this->request->data['case_disposal'] = $this->istrim($this->request->data['case_disposal']);
                    $errarr = $this->validatedata($this->request->data['case_disposal'], $fieldlist);
//pr($errarr);exit;
                    if ($this->ValidationError($errarr)) {
                        // PR($this->request->data['case_disposal']);EXIT;
                        if ($this->case_disposal->Save($this->request->data['case_disposal'])) {
                            $lastid = $this->case_disposal->getLastInsertId();
                            $this->case_status_update($case_id);
                            //  $this->Session->write("selected_token", $lastid);
//                        $case_status = $this->CaseStatusDetails->query("INSERT INTO  ngdrstab_ccms_case_status_details(case_id,case_status_id,case_status_flag) VALUES($lastid,$case_status_id,'Y')");
                            $this->Session->setFlash(__("Record $actionvalue Successfully"));
                            $this->redirect(array('controller' => 'NewCase', 'action' => 'case_disposal/' . $case_id));
                        } else {
                            $this->Session->setFlash(__('Record Not Saved '));
                        }
                    } else {
                        $this->Session->setFlash(__('errors'));
                    }
                }
            }

            $result = $this->CcmsPayment->find('first', array('case_id' => $case_id));
            $receptId = "";
            if (!empty($result)) {
                // pr($result);exit;
                $receptId = $result['CcmsPayment']['pay_id'];
            } else {
                $this->Session->setFlash(__('Payment Pending'));
                $this->redirect(array('controller' => 'NewCase', 'action' => 'payment/' . $case_id));
            }
            $this->set('receptId', $receptId);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->Session->write("randamkey", rand(111111, 999999));
    }

    public function case_disposal_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('case_disposal');
        try {
            //$id = $this->decrypt($id, $this->Session->read("randamkey"));
            if (isset($id) && is_numeric($id)) {
                $this->case_disposal->id = $id;
                if ($this->case_disposal->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    return $this->redirect(array('action' => 'case_disposal'));
                }
            }
        } catch (exception $ex) {
//             pr($ex);exit;
        }
    }

}

?>