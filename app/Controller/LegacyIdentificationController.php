<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyIdentificationController extends AppController {

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
    
    
   public function identifier($csrftoken = NULL) {
        
        array_map(array($this, 'loadModel'), array('Leg_generalinformation','identificatontype', 'MstIdentification', 'conf_reg_bool_info', 'identification_fields', 'Leg_identification', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'identifire_type', 'party_entry', 'partytype','Leg_witness'));
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
            
            
//              $witness_data = $this->Leg_witness->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
//            if (empty($witness_data)) {
//                $this->Session->setFlash(__('Please enter Witness Details first.'));
//                return $this->redirect('../LegacyWitness/witness');
//            }
            

//load Model
            
           // $this->Session->write('Selectedtoken', '20200000002083');
            
            $actiontypeval = $hfid = $hfupdateflag = $popupstatus = NULL;
            $tokenval = $Leg_Selectedtoken = $this->Session->read("Leg_Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $identification = $this->Leg_identification->get_identification_details($doc_lang, $tokenval, $user_id);
            //pr($identification);exit;
            //pr($this->Session->read("article_id"));exit;
            //$this->Session->write('article_id', '96');
            
             //$article_id = $this->partytype->get_article_id($this->Session->read("Leg_Selectedtoken"));
            $article_id = $this->Leg_generalinformation->get_article_id($this->Session->read("Leg_Selectedtoken"));
            // pr($article_id);exit;
            $this->Session->write('article_id', $article_id[0][0]['article_id']);
            
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
          // pr($partytype_name);exit;
            
            $sro = $this->User->query('select a.*,b.* from ngdrstab_mst_employee a,ngdrstab_mst_user b where a.emp_code=b.employee_id and b.user_id=?', array($this->Auth->User('user_id')));

            if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang)));
            } else {
                $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang), 'conditions' => array('citizen_flag' => 'Y')));
            }
            //PR($identifire_type);EXIT;
            $idenfire_disply = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 68)));
            $this->set('sro', $sro);
            $this->set('idenfire_disply', $idenfire_disply);
            $this->set('identification', $identification);
            $this->set('identifire_type', $identifire_type);

            $alllevel = $this->doc_levels->get_alllevel();
//set Values
            // master identifire
            $office_id1 = ClassRegistry::init('Leg_generalinformation')->field('office_id', array('token_no' => $this->Session->read("Leg_Selectedtoken")));
            $masterrecord = $this->MstIdentification->Identifirelist($language, $office_id1);

//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Leg_Selectedtoken', 'masterrecord', 'language', 'identification', 'doclevels', 'sro'));

            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype', $partytype_name);

            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype_name', $partytype_name);
//Status check box code   
            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            //validation
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $doc_lang = $this->Session->read('doc_lang');
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            //  PR($languagelist);EXIT;
            $fieldlist = array();
            $fielderrorarray = array();
            $fieldlist = $this->identification_fields->fieldlist($doc_lang);

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            if ($this->request->is('post')) {
                // pr("Amra");exit;
                //$this->check_csrf_token($this->request->data['identification']['csrftoken']);


                if (isset($this->request->data['identification']['identifire_type'])) {
                   // pr("amar");exit;
                    if ($this->request->data['identification']['identifire_type'] == 2) {
                        $user = $this->Auth->user();
                        $identification_data = array('token_no' => $this->Session->read('Leg_Selectedtoken'),
                            'identifire_type' => $this->request->data['identification']['identifire_type'],
                            'identification_full_name_en' => $user['full_name'],
                            'mobile_no' => $user['mobile_no'],
                            'email_id' => $user['email_id'],
                            'photo_require' => 'N',
                            'party_type_id' => $this->request->data['identification']['party_type_id'],
                            'state_id' => $stateid,
                            'org_user_id' => $this->Auth->User('user_id'),
                            'req_ip' => $_SERVER['REMOTE_ADDR']
                        );
                        if ($this->Leg_identification->save($identification_data)) {
                            $this->Session->setFlash(__("Record Saved Successfully"));


                            $this->redirect(array('controller' => 'LegacyIdentification', 'action' => 'identifier', $this->Session->read('csrftoken'), $tokenval));
                        }
                    }
                }

                $this->set_csrf_token();
                $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");
                if (!isset($this->request->data['identification']['village_id'])) {
                    $village_id = NULL;
                } else {
                    $village_id = $this->request->data['identification']['village_id'];
                }
                $fieldlist = $this->identification_fields->fieldlist($doc_lang, $village_id);

                $data = $this->set_value_for_save_identification($this->request->data['identification'], $stateid, $tokenval, $user_id);
                 //pr(data);exit;
                $this->request->data['identification'] = $data;
                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
//                    pr($this->request->data);

                    foreach ($bdata as $datafield) {
                        foreach ($datafield as $key => $fieldid) {
                            $this->request->data['identification']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                            //  pr($field);
                            if (isset($bdata['pattern_value_ll'][$key])) {
                                $this->request->data['identification']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                            }
                        }
                    }
                }
                if (is_numeric($this->request->data['identification']['master_id']) && is_numeric($this->request->data['identification']['master_office_id'])) {

                    if (!$this->check_sameidentifire($this->request->data['identification']['master_id'], $this->request->data['identification']['master_office_id'])) {
                        $this->Session->setFlash(__("Record Not Saved,Not a valid Identifire "));
                        if ($this->Session->read('sroidetifier') == 'N') {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                        } else if ($this->Session->read('sroidetifier') == 'Y') {
                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
                        }
                    }
                }
                $pan = isset($this->request->data['identification']['pan_no']) ? ($this->request->data['identification']['pan_no']) : ('');
                $mobile = isset($this->request->data['identification']['mobile_no']) ? ($this->request->data['identification']['mobile_no']) : ('');
                $uid = isset($this->request->data['identification']['uid_no']) ? ($this->enc($this->request->data['identification']['uid_no'])) : ('');
                $email = isset($this->request->data['identification']['email_id']) ? ($this->request->data['identification']['email_id']) : ('');
                if ($this->request->data['hfupdateflag'] == 'Y') {
                    $action = 'U';
// Comment Condition on duplicate error

                    if (!$this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
                        $this->Session->setFlash(__("Record Not Saved,Duplicate entry not allowed"));
                        if ($this->Session->read('sroidetifier') == 'N') {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                        } else if ($this->Session->read('sroidetifier') == 'Y') {
                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
                        }
                    }
                    $this->request->data['identification']['id'] = $this->request->data['hfid'];
                    $actionvalue = "Updated";
                } else {
                    $action = 'S';
                    if (!$this->check_duplicate_piw($tokenval, $mobile, $pan, $uid, $email, $action)) {
                        $this->Session->setFlash(__("Record Not Saved,Duplicate entry not allowed "));
                        if ($this->Session->read('sroidetifier') == 'N') {
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
                        } else if ($this->Session->read('sroidetifier') == 'Y') {
                            $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
                        }
                    }

                    $actionvalue = "Saved";
                }


                $this->request->data['identification'] = $this->istrim($this->request->data['identification']);

                if ($this->request->data['identification']['identificationtype_id']) {
                    $rule = $this->Leg_identification->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['identification']['identificationtype_id']);
                    if ($rule) {
                        $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                    }
                }
                $errarr = $this->validatedata($this->request->data['identification'], $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }

                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                } else {

                    if ($this->request->data['identification']['identificationtype_id'] == 9999) {
                        $this->request->data['identification']['identificationtype_desc_en'] = $this->enc($this->request->data['identification']['identificationtype_desc_en']);
                    }
                    if (isset($this->request->data['identification']['uid_no']) && is_numeric($this->request->data['identification']['uid_no'])) {
                        $this->request->data['identification']['uid_no'] = $this->enc($this->request->data['identification']['uid_no']);
                    }
                    $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");
                    $this->request->data['identification']['token_no'] = $this->Session->read("Leg_Selectedtoken");

                    if ($this->Session->read("session_usertype") == 'O') {
                        unset($this->request->data['identification']['user_id']);

                        if ($this->Auth->user('office_id')) {

                            $this->request->data['identification']['org_user_id'] = $this->Auth->User('user_id');
                            if (isset($this->request->data['identification']['id']) && is_numeric($this->request->data['identification']['id'])) {

                                $this->request->data['identification']['org_updated'] = date('Y-m-d H:i:s');
                            } else {
                                $this->request->data['identification']['org_created'] = date('Y-m-d H:i:s');
                            }
                        }
                    }

                    if ($this->Leg_identification->save($this->request->data['identification'])) {
                        if ($actionvalue == 'Updated') {
                            $patterndata['mapping_ref_val'] = $this->request->data['identification']['id'];
                            $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $patterndata['mapping_ref_val'], 5);
                        } else {
                          
                            $patterndata['mapping_ref_val'] = $this->identification->getLastInsertID();
                            
                        }

                        if (isset($this->request->data['property_details']['pattern_id'])) {
                            $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $patterndata['mapping_ref_val'], $this->request->data['property_details'], 5, $this->Session->read("session_usertype"));
                        }

                        $this->Session->setFlash(__("Record $actionvalue Successfully"));
                        if ($actionvalue == 'Updated') {
                            //  $this->redirect(array('action' => 'identification', $tokenval));
                        } else {
                          //  pr("Sandip");exit;
                            $identification1 = $this->Leg_identification->find('all', array('conditions' => array('id' => $this->Leg_identification->getLastInsertId())));
                              $this->redirect(array('action' => 'identifier', $identification1[0]['Leg_identification']['token_no']));
                            
                        }
                    } else {
                        $this->Session->setFlash(__("Record Not $actionvalue "));
                    }
                }

//                if ($this->Session->read('sroidetifier') == 'N') {
//                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $this->Session->read('csrftoken'), $tokenval));
//                } else if ($this->Session->read('sroidetifier') == 'Y') {   
//                    $this->redirect(array('controller' => 'Registration', 'action' => 'document_identification'));
//                }
            } else {
                //$this->check_csrf_token_withoutset($csrftoken);
            }
            $checkuid_entry = $this->party_entry->find("all", array('conditions' => array('token_no' => $tokenval)));
            $this->set("checkuid_entry", $checkuid_entry);
        } catch (Exception $ex) {
            pr($ex);
            exit;

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
    
     function get_identification_feilds() {
        try {

            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('Leg_identification', 'party_entry', 'partytype', 'MstIdentification', 'cast_category', 'gov_partytype', 'District', 'taluka', 'identificatontype', 'identification_fields', 'bank_master', 'salutation', 'gender', 'occupation', 'presentation_exmp', 'party_category','identifire_type'));
            $laug = $this->Session->read("sess_langauge");
            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Leg_Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read('doc_lang');
            $fields = $this->set_common_fields();
            $this->set('laug', $laug);
            // set array for selection or list fields
            $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $executer = array('Y' => 'Yes', 'N' => 'NO');
            $salutation = $this->salutation->find('list', array('fields' => array('salutation.salutation_id', 'salutation.salutation_desc_' . $doc_lang)));
            $gender = $this->gender->find('list', array('fields' => array('gender.gender_id', 'gender.gender_desc_' . $doc_lang)));
            $occupation = $this->occupation->find('list', array('fields' => array('occupation.occupation_id', 'occupation.occupation_name_' . $doc_lang), 'conditions' => array('identifier_flag' => 'Y'), 'order' => 'occupation_name_' . $doc_lang));
            $exemption = $this->presentation_exmp->find('list', array('fields' => array('presentation_exmp.exemption_id', 'presentation_exmp.desc_' . $doc_lang)));
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $name_format = $this->get_name_format();
            $identificatontype = ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'conditions' => array('identification_flag' => 'Y'), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC')));
            $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
            $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
            $category = $this->cast_category->find('list', array('fields' => array('id', 'category_name_' . $doc_lang), 'order' => array('category_name_' . $doc_lang => 'ASC')));
            $gov_partytype = $this->gov_partytype->find('list', array('fields' => array('id', 'government_type_' . $doc_lang), 'order' => array('government_type_' . $doc_lang => 'ASC')));
            $allparty = $this->party_entry->find('list', array('fields' => array('party_entry.party_id', 'party_entry.party_full_name_' . $doc_lang), 'conditions' => array('token_no' => $this->Session->read("Selectedtoken"))));
            $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang), 'conditions' => array('citizen_flag' => 'Y')));


            if (isset($data['type'])) {
                if ($data['type'] == 3) {
                    $this->Leg_identification->query("update ngdrstab_mst_identifire_fields set display_flag='Y' where field_id_name_en='party_type_id'");
                } else {
                    $this->Leg_identification->query("update ngdrstab_mst_identifire_fields set display_flag='N' where field_id_name_en='party_type_id'");
                }
            } else {
                $this->Leg_identification->query("update ngdrstab_mst_identifire_fields set display_flag='N' where field_id_name_en='party_type_id'");
            }

//validations
            //category flag check
            $cast_cat_flag = $this->cast_category_applicable_flag();

            $identifirefields = array();

//                $partyfields = $this->party_category_fields->find('all', array('fields'=>array(''),'conditions' => array('category_id' => $data['category'], 'is_auth_signtry_field' => 'N', 'display_flag' => 'Y'), 'order' => 'order ASC'));
            $identifirefields = $this->identification_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));
//pr($identifirefields);exit;

            if (isset($data['id']) && is_numeric($data['id'])) {
                $rec = $this->Leg_identification->find('all', array('conditions' => array('id' => $data['id'], 'token_no' => $token)));
                pr($rec);exit;
                if ($rec[0]['identification']['identificationtype_id'] == 9999) {
                    $rec[0]['identification']['identificationtype_desc_en'] = $this->dec($rec[0]['identification']['identificationtype_desc_en']);
                }
                if ($rec[0]['identification']['uid_no'] != NULL || $rec[0]['identification']['uid_no'] != '') {
                    $rec[0]['identification']['uid_no'] = $this->dec($rec[0]['identification']['uid_no']);
                }
                $this->set("rec", $rec);
                //pr($payment);
            }

            if (isset($data['master_id']) && is_numeric($data['master_id'])) {
                $rec = $this->MstIdentification->find('all', array('conditions' => array('identification_id' => $data['master_id'])));
                $rec[0]['identification'] = $rec[0]['MstIdentification'];
                $this->set("rec", $rec);
            }
            // to set data for edit;
            foreach ($identifirefields as $field) {
                if ($field['identification_fields']['field_id_name_en'] == 'district_id') {
                    $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $doc_lang), 'conditions' => array('state_id' => $fields['stateid']), 'order' => 'district_name_' . $doc_lang));
                    if (isset($rec) and is_numeric($rec[0]['identification']['district_id'])) {
                        $taluka = $this->taluka->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang), 'conditions' => array('district_id' => $rec[0]['identification']['district_id']), 'order' => array('taluka_name_' . $doc_lang => 'ASC')));
                    } else {
                        $taluka = array();
                    }
                }
                if (isset($rec) and is_numeric($rec[0]['identification']['taluka_id'])) {
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $doc_lang), 'conditions' => array('taluka_id' => $rec[0]['identification']['taluka_id'])));
                } else {
                    $villagelist = array();
                }
            }

            $this->set(compact('identifire_type','districtdata', 'partytype_name', 'category', 'allparty', 'cast_cat_flag', 'gov_partytype', 'taluka', 'villagelist', 'identifirefields', 'identificatontype', 'bank_master', 'executer', 'salutation', 'gender', 'occupation', 'exemption', 'allrule', 'name_format', 'errarr', 'districtdata', 'taluka'));
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    public function delete_identifire($csrf, $id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->check_csrf_token_withoutset($csrf);
        $this->loadModel('Leg_identification');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'identifire_type') {
                $this->Leg_identification->id = $id;
                if ($this->Leg_identification->deleteAll(['id' => $id, 'token_no' => $this->Session->read("Leg_Selectedtoken")])) {
                    $this->Session->setFlash(
                            __('The Record  has been deleted')
                    );
                    $this->redirect(array('controller' => 'LegacyIdentification', 'action' => 'identifier', $this->Session->read('csrftoken')));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }
    
    
      function set_common_fields() {
        $data['stateid'] = $this->Auth->User("state_id");
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Session->read("citizen_user_id");
        return $data;
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
    
    
     function set_value_for_save_identification($data, $stateid, $tokenval, $user_id) {
        try {
            $language = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');

            $data['user_id'] = $user_id;
            // $data['created_date'] = date('Y/m/d');
            $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['state_id'] = $stateid;
            if (isset($data['dob']) && $data['dob'] != NULL) {
                $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
            }

            $this->set('actiontypeval', $_POST['actiontype']);
            $this->set('hfid', $_POST['hfid']);
            if (isset($data['identification_full_name_en'])) {
                if ($data['identification_full_name_en'] == '') {
                    $data['identification_full_name_en'] = isset($data['fname_en']) ? $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'] : '';
                } else {
                    $data['identification_full_name_en'] = $data['identification_full_name_en'];
                }
            } else {
                $data['identification_full_name_en'] = isset($data['fname_en']) ? $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'] : '';
            }
            if (isset($data['identification_full_name_ll'])) {
                if ($data['identification_full_name_ll'] == '') {
                    $data['identification_full_name_ll'] = isset($data['fname_ll']) ? $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'] : '';
                } else {
                    $data['identification_full_name_ll'] = $data['identification_full_name_ll'];
                }
            } else {
                $data['identification_full_name_ll'] = isset($data['fname_ll']) ? $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'] : '';
            }

            $data['identification_full_name_en'] = ucwords($data['identification_full_name_en']);



            return $data;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
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
      // pr($tal);pr($csrftoken);exit;
       // pr($this->request->data['csrftoken']);exit;
        try {
          //  $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
           
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {
                $tal = $this->request->data['tal'];
//pr($tal);exit;
                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                $result_array = array('village' => $villagelist);
//pr($villagelist);exit;

                echo json_encode($result_array);
                exit;
            } else {
              //  pr("sdfd");exit;
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            pr($ex);exit;
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