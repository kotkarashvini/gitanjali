<?php

class EmployeeController extends AppController {

    public $components = array('RequestHandler', 'Security', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('login', 'employeetransfer', 'getemployeetransfer','empdemo');
        $this->Auth->allow('welcomenote', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'activate', 'checkmobileno', 'get_taluka_name', 'get_division_name', 'empdemo');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }
 
    function get_validation_rule() {
        try {

            if (isset($this->request->data['type']) && $this->request->data['type'] != '') {
                $data = array();
                $lang = $this->Session->read("sess_langauge");
                $this->loadModel('identificatontype');
                $rule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $lang . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['type']);
                if ($rule) {
                    $data['message'] = $rule[0][0]['error_messages_' . $lang];
                    $data['pattern'] = trim($rule[0][0]['pattern_rule_client']);
                    $data['error_code'] = trim($rule[0][0]['error_code']);
                    echo json_encode($data);
                    exit;
                }
            }
            exit;
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_district_name() {
        try {
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['state'])) {
//                $division = $_GET['division'];
                $state = $_GET['state'];
//                 echo $state; exit;
                $districtname = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $lang), 'conditions' => array('state_id' => array($state))));
//                pr($districtname);exit;
                echo json_encode($districtname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function gettaluka() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('taluka');
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['district'])) {
                $district = $_GET['district'];
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $district)));
                echo json_encode($talukadata1);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    public function employee($empid = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('salutation');
            $this->loadModel('qualification');
            $this->loadModel('designation');
            $this->loadModel('office');
            $this->loadModel('department');
            $this->loadModel('employee');
            $this->set('employeerecord', NULL);
//              $this->set('employeerecord', NULL);
            $created_date = date('Y/m/d');
            $user_id = $this->Session->read("session_user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('officedec', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_'. $laug), 'order' => array('office_name_en' => 'ASC'))));
            $this->set('designationdec', ClassRegistry::init('designation')->find('list', array('fields' => array('desg_id', 'desg_desc_'. $laug), 'order' => array('desg_desc_en' => 'ASC'))));
            $employeerecord = $this->employee->query("select * from ngdrstab_mst_employee");
            $this->set('employeerecord', $employeerecord);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);

            $allrule = $this->NGDRSErrorCode->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            
            $distdata = '';
            $this->set('distdata', $distdata);

            $talukadata = '';
            $this->set('talukadata', $talukadata);

            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $laug)));
            $this->set('salutation', $salutation);

            $qualification = $this->qualification->find('list', array('fields' => array('qualification.qualification_id', 'qualification.qualification_desc_' . $laug)));
            $this->set('qualification', $qualification);

            $designation = $this->designation->find('list', array('fields' => array('designation.desg_id', 'designation.desg_desc_'. $laug)));
            $this->set('designation', $designation);

            $office = $this->office->find('list', array('fields' => array('office_id', 'office.office_name_'. $laug)));
            $this->set('office', $office);

            $department = $this->department->find('list', array('fields' => array('department.dept_id', 'department.dept_name_'. $laug)));
            $this->set('department', $department);

            
            $stateid = $this->State->query("select state_id from ngdrs_current_state");
            $state_id=$stateid[0][0]['state_id'];
           
            $State = $this->State->find('list', array('fields' => array('state_id', 'state_name_'. $laug), 'conditions' => array('state_id' => $state_id), 'order' => array('state_name_en' => 'ASC')));
            $this->set('State', $State);
            $this->loadModel('District');
            $District = $this->District->find('list', array('fields' => array('id', 'district_name_'. $laug), 'order' => array('district_name_en' => 'ASC')));
            $this->set('District', $District);
            $this->loadModel('taluka');
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_'. $laug), 'order' => array('taluka_name_en' => 'ASC')));
            $this->set('taluka', $taluka);

            $this->loadModel('id_type');
            $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_'. $laug), 'conditions' => array('emp_reg_flag' => 'Y'), 'order' => array('identificationtype_desc_en' => 'ASC')));
//             $idtype = $this->id_type->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_en'), 'order' => array('identificationtype_desc_en' => 'ASC')));
            $this->set('idtype', $idtype);

            $this->set('Empcode', ClassRegistry::init('employee')->find('list', array('fields' => array('emp_code', 'name'), 'order' => array('emp_code' => 'ASC'))));

            $this->loadModel('hintquestion');
            $hintquestion = $this->hintquestion->find('list', array('fields' => array('id', 'questions_'. $laug), 'order' => array('questions_en' => 'ASC')));
            foreach ($hintquestion as $key=>$hintquestion1){
                $hintquestion[$key]=$hintquestion1." ?";
            }
            $this->set('hintquestion', $hintquestion);
            //adding field list dynamically from language list
            //   pr($this->request->data);
            if ($empid == NULL) {
                $emp_id = $this->employee->query("select max(emp_id) as empid from ngdrstab_mst_employee");
                $employeeid = $emp_id[0][0]['empid'];
                if ($employeeid != Null) {
                    $employeeid = $employeeid + 1;
                } else {
                    $employeeid = 1;
                }
                $this->set('employeeid', $employeeid);
                // $empcode = 'EMP0' . $employeeid;
                $empcode = $employeeid;
                $this->set('empcode', $empcode);
            } else {
                $empcode = $this->employee->query("select emp_code from ngdrstab_mst_employee where emp_id=$empid");
                $empcode = $empcode[0][0]['emp_code'];
                $this->set('empcode', $empcode);
            }


            $this->set("fieldlist", $fieldlist = $this->employee->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


 if (!empty($empid)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }



            //pr($this->request->data);exit;
            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['employee']['emp_id'] = $empid;
                $this->request->data['employee']['user_id'] = $this->Auth->User("user_id");
                $this->request->data['employee']['req_ip'] = $this->request->clientIp();
//                $empcode = $this->request->data['employee']['emp_code'];

                $verrors = $this->validatedata($this->request->data['employee'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->employee->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['employee']);

                    if ($checkd) {
                        if ($this->employee->save($this->request->data['employee'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__("Record Save Successfully"));
                            $this->redirect(array('controller' => 'Employee', 'action' => 'employee'));
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
            if (!is_null($empid)) {
                 $this->set('editflag', 'Y');    
                $this->Session->write('emp_id', $empid);
                $result = $this->employee->find("first", array('conditions' => array('emp_id' => $empid)));
                //pr($result);exit;
                $this->request->data['employee'] = $result['employee'];

                $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC')));
                $this->set('distdata', $distdata);
                $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => $result['employee']['dist_id']), 'order' => array('taluka_name_' . $laug => 'ASC')));
                $this->set('talukadata', $talukadata);
//                $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $laug),'conditions' => array('salutation_id' => $result['employee']['dist_id'])));
//            $this->set('salutation', $salutation);
//               
            }
            $this->Session->write("salt", rand(111111, 999999));
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            //  return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

  

    public function employee_delete($emp_code = null) {
        $this->autoRender = false;
        $this->loadModel('employee');
        try {

            if (isset($emp_code)) {
                $this->employee->emp_id = $emp_code;
                if ($this->employee->delete($emp_code)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'employee'));
                }
                // }
            }
        } catch (exception $ex) {
             return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //======salim============================================================================

    public function designation($desg_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('designation');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('designation', $this->designation->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->designation->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
 if (!empty($desg_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {


                $this->request->data['designation']['ip_address'] = $this->request->clientIp();
                $this->request->data['designation']['created_date'] = $created_date;
                $this->request->data['designation']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['designation'], $fieldlist);

                if ($this->ValidationError($verrors)) {
//                    pr($this->request->data);exit;
                    $duplicate = $this->designation->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['designation']);
                    if ($checkd) {
                        if ($this->designation->save($this->request->data['designation'])) {
                            $this->Session->setFlash(__($actionvalue));
                           // $this->Session->setFlash(__('lblsavemsg'));
                            return $this->redirect(array('action' => 'designation'));
                            $lastid = $this->designation->getLastInsertId();
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
            if (!is_null($desg_id) && is_numeric($desg_id)) {
                $this->set('editflag', 'Y');    
                $this->Session->write('desg_id', $desg_id);
                $result = $this->designation->find("first", array('conditions' => array('desg_id' => $desg_id)));
                $this->request->data['designation'] = $result['designation'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_designation($desg_id = null) {
        $this->autoRender = false;
        $this->loadModel('designation');
        try {

            if (isset($desg_id) && is_numeric($desg_id)) {
                $this->designation->dept_id = $desg_id;
                if ($this->designation->delete($desg_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'designation'));
                }
                // }
            }
        } catch (exception $ex) {
          return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
  
        }
    }

    public function qualification($qualification_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('qualification');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('qualification', $this->qualification->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->qualification->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
 if (!empty($qualification_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {



                $this->request->data['qualification']['ip_address'] = $this->request->clientIp();
                $this->request->data['qualification']['created_date'] = $created_date;
                $this->request->data['qualification']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['qualification'], $fieldlist);

                if ($this->ValidationError($verrors)) {
//                    pr($this->request->data);exit;
                    $duplicate = $this->qualification->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['qualification']);
                    if ($checkd) {
                        if ($this->qualification->save($this->request->data['qualification'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Qualification saved Successfully.'));
                            return $this->redirect(array('action' => 'qualification'));
                            $lastid = $this->qualification->getLastInsertId();
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
            if (!is_null($qualification_id) && is_numeric($qualification_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('qualification_id', $qualification_id);
                $result = $this->qualification->find("first", array('conditions' => array('qualification_id' => $qualification_id)));
                $this->request->data['qualification'] = $result['qualification'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_qualification($qualification_id = null) {
        $this->autoRender = false;
        $this->loadModel('qualification');
        try {

            if (isset($qualification_id) && is_numeric($qualification_id)) {
                $this->qualification->qualification_id = $qualification_id;
                if ($this->qualification->delete($qualification_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'qualification'));
                }
                // }
            }
        } catch (exception $ex) {
          return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));  
        }
    }

    public function salutation($salutation_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('salutation');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('salutation', $this->salutation->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->salutation->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
 if (!empty($salutation_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['salutation']['ip_address'] = $this->request->clientIp();
                $this->request->data['salutation']['created_date'] = $created_date;
                $this->request->data['salutation']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['salutation'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->salutation->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['salutation']);
                    if ($checkd) {
                        if ($this->salutation->save($this->request->data['salutation'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Salutation saved Successful.'));
                            return $this->redirect(array('action' => 'salutation'));
                            $lastid = $this->salutation->getLastInsertId();
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
            if (!is_null($salutation_id) && is_numeric($salutation_id)) {
                 $this->set('editflag', 'Y');   
                $this->Session->write('salutation_id', $salutation_id);
                $result = $this->salutation->find("first", array('conditions' => array('salutation_id' => $salutation_id)));

                $this->request->data['salutation'] = $result['salutation'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_salutation($salutation_id = null) {
        $this->autoRender = false;
        $this->loadModel('salutation');
        try {

            if (isset($salutation_id) && is_numeric($salutation_id)) {
                $this->salutation->salutation_id = $salutation_id;
                if ($this->salutation->delete($salutation_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'salutation'));
                }
                // }
            }
        } catch (exception $ex) {
             return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }
    
     public function hint_questions($hint_questions_id = NULL) {
        try {
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('hintquestion');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
             
            $this->set('hintquestion', $this->hintquestion->find('all'));
            
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

            $this->set("fieldlist", $fieldlist = $this->hintquestion->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

 if (!empty($hint_questions_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                
                
                $this->request->data['hintquestion']['ip_address'] = $this->request->clientIp();
                $this->request->data['hintquestion']['created_date'] = $created_date;
                $this->request->data['hintquestion']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['hint_questions'], $fieldlist);
                
                if ($this->ValidationError($verrors)) {
//                    pr($this->request->data);exit;
                    $duplicate = $this->hintquestion->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['hint_questions']);
                    if ($checkd) {
                        if ($this->hintquestion->save($this->request->data['hint_questions'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Hint Question Type saved Successfully.'));
                            return $this->redirect(array('action' => 'hint_questions'));
                            $lastid = $this->hintquestion->getLastInsertId();
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
            if (!is_null($hint_questions_id) && is_numeric($hint_questions_id)) {
                 $this->set('editflag', 'Y');    
                $this->Session->write('hint_questions_id', $hint_questions_id);
                $result = $this->hintquestion->find("first", array('conditions' => array('hint_questions_id' => $hint_questions_id)));
                $this->request->data['hint_questions'] = $result['hintquestion'];
            }
        } catch (exception $ex) {

            //pr($ex);
           // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_hint_questions($hint_questions_id = null) {
        $this->autoRender = false;
        $this->loadModel('hintquestion');
        try {

            if (isset($hint_questions_id) && is_numeric($hint_questions_id)) {
                $this->hintquestion->hint_questions_id = $hint_questions_id;
                if ($this->hintquestion->delete($hint_questions_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'hint_questions'));
                }
                // }
            }
        } catch (exception $ex) {
             return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }
    
    public function identificatontype($identificationtype_id = NULL) {
        try {
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('identificatontype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
             
            $this->set('identificatontype', $this->identificatontype->find('all'));
            
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

            $this->set("fieldlist", $fieldlist = $this->identificatontype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

if (!empty($identificationtype_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post') || $this->request->is('put')) {

                
                
                $this->request->data['identificatontype']['ip_address'] = $this->request->clientIp();
                $this->request->data['identificatontype']['created_date'] = $created_date;
                $this->request->data['identificatontype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['identificatontype'], $fieldlist);
                
                if ($this->ValidationError($verrors)) {
//                    pr($this->request->data);exit;
                    $duplicate = $this->identificatontype->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['identificatontype']);
                    if ($checkd) {
                        if ($this->identificatontype->save($this->request->data['identificatontype'])) {
                            $this->Session->setFlash(__($actionvalue));
                           // $this->Session->setFlash(__('Identificaton Type saved Successfully.'));
                            return $this->redirect(array('action' => 'identificatontype'));
                            $lastid = $this->identificatontype->getLastInsertId();
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
            if (!is_null($identificationtype_id) && is_numeric($identificationtype_id)) {
                 $this->set('editflag', 'Y');    
                $this->Session->write('identificationtype_id', $identificationtype_id);
                $result = $this->identificatontype->find("first", array('conditions' => array('identificationtype_id' => $identificationtype_id)));
                $this->request->data['identificatontype'] = $result['identificatontype'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_identificatontype($identificationtype_id = null) {
        $this->autoRender = false;
        $this->loadModel('identificatontype');
        try {

            if (isset($identificationtype_id) && is_numeric($identificationtype_id)) {
                $this->identificatontype->identificationtype_id = $identificationtype_id;
                if ($this->identificatontype->delete($identificationtype_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'identificatontype'));
                }
                // }
            }
        } catch (exception $ex) {
             return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    } 
 
}
