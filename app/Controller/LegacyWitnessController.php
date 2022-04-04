<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyWitnessController extends AppController {

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
    
    
   public function witness($csrftoken = NULL) {
      
        try {
            $last_status_id = $this->Session->read('last_status_id');
 
//
              $this->loadModel('Leg_party_entry');
//             $party_data = $this->party_entry->find('first', array('conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));
//            if (empty($party_data)) {
//                $this->Session->setFlash(__('Please enter Party Details first.'));
//                return $this->redirect('../PartyDetails/Party');
//            }

           // $this->Session->write('Selectedtoken', '20200000002083');
            
             $party_data = $this->Leg_party_entry->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));

            if (empty($party_data)) {
                $this->Session->setFlash(__('Please enter Party Details first.'));
                return $this->redirect('../LegacyPartyDetails/party');
            }
          
            
            array_map(array($this, 'loadModel'), array('Leg_witness', 'witness_fields', 'identificatontype', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'witness_type'));
            $popupstatus = $actiontypeval = $hfid = $hfupdateflag = $hfactionval = NULL;
            $tokenval = $Leg_Selectedtoken = $this->Session->read("Leg_Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Auth->User("user_id");
          //  $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $witness = $this->Leg_witness->get_allwitness($doc_lang, $tokenval); //  

            $this->set('witness', $witness);
            $alllevel = $this->doc_levels->get_alllevel();

//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Leg_Selectedtoken', 'language', 'witness', 'doclevels'));

//Status check box code            
            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $this->set(compact('lang', 'Leg_Selectedtoken', 'popupstatus', 'exemption', 'actiontypeval', 'hfid', 'hfupdateflag', 'hfactionval', 'districtdata', 'taluka', 'party_category', 'occupation', 'gender', 'salutation', 'property', 'property_list', 'property_pattern', 'party_record'));
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format();

            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            // validations kalyani
            $doc_lang = $this->Session->read('doc_lang');

            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $language . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist = $this->witness_fields->fieldlist($doc_lang);


            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {


                $hfid = $_POST['hfid'];
               // pr($hfid);exit;
                $this->check_csrf_token($this->request->data['witness']['csrftoken']);


                if (!isset($this->request->data['witness']['village_id'])) {
                    $village_id = NULL;
                } else {
                    $village_id = $this->request->data['witness']['village_id'];
                }
                $fieldlist = $this->witness_fields->fieldlist($doc_lang, $village_id);

                $this->set('hfid', $_POST['hfid']);
                $name_format = $this->get_name_format();

                if (isset($this->request->data['witness']['witness_full_name_en'])) {

                    if ($this->request->data['witness']['witness_full_name_en'] == '') {
                        $this->request->data['witness']['witness_full_name_en'] = isset($this->request->data['witness']['fname_en']) ? $this->request->data['witness']['fname_en'] . ' ' . $this->request->data['witness']['mname_en'] . ' ' . $this->request->data['witness']['lname_en'] : '';
                    } else {
                        $this->request->data['witness']['witness_full_name_en'] = $this->request->data['witness']['witness_full_name_en'];
                    }
                } else {
                    $this->request->data['witness']['witness_full_name_en'] = isset($this->request->data['witness']['fname_en']) ? $this->request->data['witness']['fname_en'] . ' ' . $this->request->data['witness']['mname_en'] . ' ' . $this->request->data['witness']['lname_en'] : '';
                }
                if (isset($this->request->data['witness']['witness_full_name_ll'])) {
                    if ($this->request->data['witness']['witness_full_name_ll'] == '') {
                        $this->request->data['witness']['witness_full_name_ll'] = isset($this->request->data['witness']['fname_ll']) ? $this->request->data['witness']['fname_ll'] . ' ' . $this->request->data['witness']['mname_ll'] . ' ' . $this->request->data['witness']['lname_ll'] : '';
                    } else {
                        $this->request->data['witness']['witness_full_name_ll'] = $this->request->data['witness']['witness_full_name_ll'];
                    }
                } else {
                    $this->request->data['witness']['witness_full_name_ll'] = isset($this->request->data['witness']['fname_ll']) ? $this->request->data['witness']['fname_ll'] . ' ' . $this->request->data['witness']['mname_ll'] . ' ' . $this->request->data['witness']['lname_ll'] : '';
                }
                $this->request->data['witness']['witness_full_name_en'] = ucwords($this->request->data['witness']['witness_full_name_en']);



                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
                    foreach ($bdata as $datafield) {
                        foreach ($datafield as $key => $fieldid) {
                            $this->request->data['witness']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                            
                            if (isset($bdata['pattern_value_ll'][$key])) {
                                $this->request->data['witness']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                            }
                        }
                    }
                }
                $this->set('hfactionval', $hfactionval);



//validations kalyani
                $this->request->data['witness'] = $this->istrim($this->request->data['witness']);
                if ($this->request->data['witness']['identificationtype_id']) {
                    $rule = $this->Leg_witness->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['witness']['identificationtype_id']);
                    if ($rule) {
                        $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                    }
                }

                $errarr = $this->validatedata($this->request->data['witness'], $fieldlist);
    //PR($fieldlist);EXIT;
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                   // pr($errarr);exit;
                   // pr("aaaa");exit;
                } else {

                    if ($this->request->data['witness']['identificationtype_id'] == 9999) {
                        $this->request->data['witness']['identificationtype_desc_en'] = $this->enc($this->request->data['witness']['identificationtype_desc_en']);
                    }
                    if (isset($this->request->data['witness']['uid_no']) && is_numeric($this->request->data['witness']['uid_no'])) {
                        $this->request->data['witness']['uid_no'] = $this->enc($this->request->data['witness']['uid_no']);
                    }
                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['witness']['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $this->request->data['witness']['org_user_id'] = $this->Auth->User('user_id');
                            if (is_numeric($hfid)) {

                                $this->request->data['witness']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['witness']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                        //pr("sdasd");exit;
                    }
                  //  pr("sds");exit;
              //  pr($this->request->data['witness']);exit;
                     
                    if ($this->save_witness($this->request->data['witness'], $tokenval, $stateid, $user_id, $hfid, $this->Session->read("session_usertype"))) {
                     // pr("sds");exit;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $witnessid = $this->request->data['witness']['id'] = $this->request->data['hfid'];
                            $actionvalue = "Updated";
                        } else {
                            $witnessid = $this->Leg_witness->getLastInsertID();
                            $actionvalue = "Saved";
                        }
                        if (isset($this->request->data['property_details']['pattern_id'])) {
                          
                            $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $witnessid, 3);

                            $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $witnessid, $this->request->data['property_details'], 3, $this->Session->read("session_usertype"));
                        }
                        $this->Session->setFlash(__("Record $actionvalue Successfully"));
                    } else {
                        $this->Session->setFlash(__("Record Not save, Duplicate Entry not allowed"));
                    }
                }

                $this->set_csrf_token();
                $this->redirect(array('controller' => 'LegacyWitness', 'action' => 'witness', $this->Session->read('csrftoken')));
            } else {
                // $this->check_csrf_token_withoutset($csrftoken);
            }
        } catch (Exception $ex) {
       
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    
     public function get_name_format() {
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 29)));
        if (!empty($regconfig)) {
            return $regconfig['regconfig']['conf_bool_value'];
        }
    }
    
    
    function get_witness_feilds($id=NULL) {
        try {
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('Leg_witness', 'cast_category', 'gov_partytype', 'nationality', 'District', 'taluka', 'identificatontype', 'witness_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Leg_Selectedtoken');
            $data = $this->request->data;
           // pr($this->request->data);exit;
            $doc_lang = $this->Session->read('doc_lang');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
            // set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $marital_status = array('M' => 'Married', 'U' => 'Unmarried');
            $nationality = $this->nationality->find('list', array('fields' => array('nationality.nationality_id', 'nationality.nationality_name_' . $doc_lang)));
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang)));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $doc_lang), 'order' => 'occupation.occupation_name_' . $doc_lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('witness_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $doc_lang), 'order' => array('category_name_' . $doc_lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $doc_lang), 'order' => array('government_type_' . $doc_lang => 'ASC')));
            
            //validations
            //category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();
            $witnessfields = array();
            $witnessfields = $this->witness_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));
//pr($witnessfields);exit;
            if (isset($data['id']) && is_numeric($data['id'])) {
               
                $rec = $this->Leg_witness->find('all', array('conditions' => array('id' => $data['id'], 'token_no' => $token)));
              
                if ($rec[0]['Leg_witness']['identificationtype_id'] == 9999) {
                    $rec[0]['Leg_witness']['identificationtype_desc_en'] = $this->dec($rec[0]['Leg_witness']['identificationtype_desc_en']);
                }

                if ($rec[0]['Leg_witness']['uid_no'] != NULL || $rec[0]['Leg_witness']['uid_no'] != '') {
                    $rec[0]['Leg_witness']['uid_no'] = $this->dec($rec[0]['Leg_witness']['uid_no']);
                }


                $this->set("rec", $rec);
            }
          
            // to set data for edit;
           
            foreach ($witnessfields as $field) {
                if ($field['witness_fields']['field_id_name_en'] == 'district_id') {
                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                    if (isset($rec) and is_numeric($rec[0]['Leg_witness']['district_id'])) {
                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $rec[0]['Leg_witness']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                    } else {
                        $taluka = array();
                    }
                }
               // pr($rec);pr($rec[0]['Leg_witness']['taluka_id']);exit;
                if (isset($rec) and is_numeric($rec[0]['Leg_witness']['taluka_id'])) {
                    
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('circle_id' => $rec[0]['Leg_witness']['taluka_id'])));
                  // pr($villagelist);exit;
                } else {
                    $villagelist = array();
                     //pr("dfsd");exit;
                }
            }

            $this->set(compact('districtdata', 'category', 'cast_cat_flag', 'nationality', 'marital_status', 'gov_partytype', 'taluka', 'villagelist', 'witnessfields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka'));
        } catch (Exception $ex) {
         
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    
      function cast_category_applicable_flag() {
        try {
            array_map(array($this, 'loadModel'), array('regconfig'));
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 40)));
            if (!empty($regconfig)) {

                return $regconfig['regconfig']['conf_bool_value'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    
       function set_common_fields() {
        $data['stateid'] = $this->Auth->User("state_id");
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Session->read("citizen_user_id");
        return $data;
    }
    
    public function delete_witness($csrf, $id = null) {
        $this->autoRender = false;
        $this->check_csrf_token_withoutset($csrf);
        $this->loadModel('Leg_witness');
        try {

            if (isset($id) && is_numeric($id)) {
                $this->witness()->id = $id;
                //if ($this->Leg_witness->delete($id)) {
                    if($this->Leg_witness->deleteAll(['id' => $id, 'token_no' => $this->Session->read("Leg_Selectedtoken")]))
                    {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );

                    $this->redirect(array('controller' => 'LegacyWitness', 'action' => 'witness', $this->Session->read('csrftoken')));
                }
            }
        } catch (exception $ex) {
        }
    }
    
    
    function save_witness($data, $tokenval, $stateid, $user_id, $hfid, $user_type) {
        try {

            array_map([$this, 'loadModel'], ['Leg_witness']);
            $pan = isset($data['pan_no']) ? ($data['pan_no']) : ('');
            $mobile = isset($data['mobile_no']) ? ($data['mobile_no']) : ('');
            $uid = isset($data['uid_no']) ? ($data['uid_no']) : ('');
            $email = isset($data['email_id']) ? ($data['email_id']) : ('');

            $data['token_no'] = $tokenval;
            $data['state_id'] = $stateid;
            $data['user_id'] = $user_id;
            $data['user_type'] = $user_type;

            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            if (isset($data['dob']) && $data['dob'] != NULL) {
                $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
            }
            if ($hfid) {
                $action = 'U';
                    $this->Leg_witness->id = $hfid;
                    $this->Leg_witness->save($data);
                    return true;
            } else {

                $action = 'S';
                    $this->Leg_witness->save($data);
                    return true;
            }
        } catch (Exception $e) {
           
        }
    }
    
    
    
    public function district_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($this->request->data['dist'])) {
                $laug = $this->Session->read("sess_langauge");
                $doc_lang = $this->Session->read('doc_lang');
                $dist = $this->request->data['dist'];
                $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => $dist)));

                $result_array = array('subdiv' => NULL, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => NULL, 'village' => NULL);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    public function taluka_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {
                $tal = $this->request->data['tal'];

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                $result_array = array('village' => $villagelist);


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    public function behavioral_patterns() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            $this->loadModel('Behavioural');
            $this->loadModel('BehaviouralDetails');
            $this->loadModel('BehavioralPattens');
            $this->loadModel('VillageMapping');
            $this->loadModel('TrnBehavioralPatterns');
            $doc_lang = $this->Session->read('doc_lang');
            $trnbehavioral = array();
            //ref_id=screen_id
            if ($_POST['ref_id'] == 1 || $_POST['ref_id'] == 2 || $_POST['ref_id'] == 3 || $_POST['ref_id'] == 4 || $_POST['ref_id'] == 5 || $_POST['ref_id'] == 9999 && is_numeric($_POST['ref_id']) && is_numeric($_POST['behavioral_id'])) {

                if (isset($this->request->data['village_id'])) {
                    $village_id = $this->request->data['village_id'];
                } else {
                    $village_id = NULL;
                }
                $behavioral_id = $this->request->data['behavioral_id'];
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang, 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id'), 'conditions' => array('village_id' => $village_id)));
                if (!empty($villagedetails)) {
                    $land_type = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                } else {
                    $land_type = 'U';
                }

                if ($land_type == '1') {
                    $land_flag = "U";
                } else if ($land_type == '2') {
                    $land_flag = "R";
                } else if ($land_type == '3') {
                    $land_flag = "I";
                } else {
                    $land_flag = "U";
                }
                //usage_id
                $BehavioralPatterns = array();
                if (isset($_POST['usage_id']) && is_numeric($_POST['usage_id'])) {
                    $main_cat_id = ClassRegistry::init('usage_category')->field('usage_main_catg_id', array('evalrule_id' => $_POST['usage_id']));
                    $BehavioralPatterns = $this->BehavioralPattens->query("select * from   ngdrstab_conf_behavioral behavioral,   ngdrstab_conf_behavioral_details details, ngdrstab_conf_behavioral_patterns patterns where behavioral.behavioral_id=details.behavioral_id AND details.behavioral_details_id=patterns.behavioral_details_id AND patterns.behavioral_id=1 AND details.developed_land_types_flag=?  AND details.main_usage_id=?", array($land_flag, $main_cat_id));
                } else {

                    $BehavioralPatterns = $this->BehavioralPattens->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=$behavioral_id AND behavioral.behavioral_id=details.behavioral_id  AND details.developed_land_types_flag='$land_flag' ");
                }
                $this->set("BehavioralPatterns", $BehavioralPatterns);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['BehavioralPatterns'] = $BehavioralPatterns;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
            }
            if (isset($_POST['ref_val']) and is_numeric($_POST['ref_val'])) {
                $trnbehavioral = $this->TrnBehavioralPatterns->find("all", array('conditions' => array('mapping_ref_id' => $_POST['ref_id'], 'mapping_ref_val' => $_POST['ref_val'])));
            }
            $this->set("trnbehavioral", $trnbehavioral);
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    
    function get_validation_rule() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
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
}