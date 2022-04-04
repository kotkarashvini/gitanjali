<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class InspectorController extends AppController {

    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        // $this->Security->unlockedActions = array('inspectionverification', 'party', 'property');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

//        $this->Auth->allow('welcomenote', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration');
    }

    public function inspectionverification() {
        try {
            array_map(array($this, 'loadModel'), array('majorfunction', 'minorfunction', 'formbehaviour', 'fieldformlinkage', 'genernal_info', 'document_status_description', 'document_status_description'));
            $this->Session->write("inspector_office_id", $this->Auth->user('office_id'));
            $this->Session->write("inspector_user_id", $this->Auth->user('user_id'));
//            pr($_SESSION);exit;
            $user_id = $this->Session->read("inspector_user_id");
            $office_id = $this->Session->read("inspector_office_id");
//            pr($_SESSION);exit;
            $session_tokenval = $this->Session->read("Selectedtoken");
            $created_date = date('Y/m/d H:i:s');
            $token_no = $this->genernal_info->query("select a.token_no from ngdrstab_trn_application_submitted a
                                                 WHERE   a.office_id=?", array($office_id));
//            pr($token_no);
            $tokenno=array();
              foreach($token_no as $key => $value){
                  $tokenno[$key]=$value[0]['token_no'];
              }
              $mystring = implode(', ',$tokenno);
//            pr($tokenno);exit;
//            $token_no = $token_no[0][0]['token_no'];
            $statusrecord = $this->genernal_info->query("select village.village_name_en,doc.document_status_desc_en,a.token_no,eval.evalrule_desc_en,
                                                        b.article_desc_en,c.title_name,d.language_name,e.execution_type_en,a.data_entry_flag,a.submitted_flag,
                                                        a.reg_proc_flag,a.registerd_flag,a.inspecation_completed_flag
                                                 from ngdrstab_trn_generalinformation a
                                                 left outer join ngdrstab_mst_article b on b.id=a.article_id
                                                 left outer join ngdrstab_mst_document_title c on c.id=a.title_id
                                                 left outer join ngdrstab_mst_language d on d.id=a.local_language_id
                                                 left outer join ngdrstab_mst_document_execution_type e on e.id=a.doc_execution_type_id
                                                 left outer join ngdrstab_trn_property_details_entry prop on prop.token_no=a.token_no
                                                   left outer join ngdrstab_conf_admblock7_village_mapping village on village.village_id=prop.village_id
                                                   left outer join ngdrstab_trn_valuation_details v on v.val_id=prop.val_id
                                                   left outer join ngdrstab_mst_evalrule_new eval on eval.evalrule_id=v.rule_id
                                                    left outer join ngdrstab_mst_document_status_description doc on doc.id=a.last_status_id
                                                 WHERE   a.token_no IN ($mystring)  order by a.user_id");

            $status = $this->document_status_description->find('all');

            $this->set('statusrecord', $statusrecord);
        } catch (Exception $ex) {
            pr($ex);exit;
        }
    }

    public function party($tokenval = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('User', 'language', 'mainlanguage', 'party_entry', 'genernalinfoentry', 'doc_levels', 'State', 'TrnBehavioralPatterns', 'articletrnfields', 'article', 'documenttitle', 'document_execution_type'));
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->Session->write("tokenno", $tokenval);
            $tokenno = $this->Session->read("tokenno");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $user_id = $this->Session->read("citizen_user_id");
            $lang = $this->Session->read("sess_langauge");
            $article = $this->article->get_article($lang);
            $documenttitle = $this->documenttitle->get_title();
            $language = $this->mainlanguage->get_main_lag();
            $language2 = $this->mainlanguage->get_state_lang($stateid);

            $partyrecord = $this->party_entry->query("select  a.id,a.property_id, a.party_full_name_$lang,b.party_type_desc_$lang,c.category_name_$lang
                                                        from ngdrstab_trn_party_entry_new a
                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where token_no=?", array($tokenval));
            $this->set('username', $this->Auth->User('username'));
            $this->set(compact('language', 'language2', 'document_execution_type', 'partyrecord', 'documenttitle', 'lang'));
            if ($this->request->is('post')) {

                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                $this->request->data['party']['user_id'] = $user_id;
                $this->request->data['party']['created_date'] = date('Y-m-d');
                $this->request->data['party']['req_ip'] = $this->RequestHandler->getClientIp();
                $this->request->data['party']['state_id'] = $stateid;

                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);
                if ($_POST['actiontype'] == '1') {
                    $party_record = $this->party_entry->query("select  a.*,a.property_id, a.party_fname_$lang,b.party_type_desc_$lang,c.category_name_$lang ,
                                d.salutation_desc_$lang,e.desc_$lang,f.identificationtype_desc_$lang as idntity,h.gender_desc_$lang,i.occupation_name_$lang,
                                j.district_name_$lang,k.taluka_name_$lang,l.village_name_$lang
                                from ngdrstab_trn_party_entry_new a
                                left outer join ngdrstab_mst_party_type b on b.party_type_id = a.party_type_id
                                left outer join ngdrstab_mst_party_category c on c.category_id = a.party_catg_id
                                left outer join ngdrstab_mst_salutation d on d.id = a.salutation_id
                                left outer join ngdrstab_mst_presentation_exemption e on e.exemption_id = a.exemption_id
                                left outer join ngdrstab_mst_identificationtype f on f.identificationtype_id = a.identificationtype_id
                                left outer join ngdrstab_mst_gender h on h.id = a.gender_id 
                                left outer join ngdrstab_mst_occupation i on i.id = a.occupation_id
                                left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                                left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                                left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                                where a.id=?", array($_POST['hfid']));
                    $this->set('party_record', $party_record);
                    $pattern = $this->TrnBehavioralPatterns->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_en', 'pattern.pattern_desc_ll', 'pattern.field_id', 'TrnBehavioralPatterns.field_value_en', 'TrnBehavioralPatterns.field_value_ll'),
                        'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $_POST['hfid'], 'TrnBehavioralPatterns.token_no' => $tokenno, 'TrnBehavioralPatterns.mapping_ref_id' => 2), // for property:mapping_ref_id => 1
                        'joins' => array(
//                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id AND pattern.behavioral_id=TrnBehavioralPatterns.mapping_ref_id')),
                            array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                        ),
                        'order' => 'pattern.field_id DESC'
                    ));
                    $this->set('pattern_data', $pattern);
//                     pr($pattern);exit;
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Masters', 'action' => 'regconfig'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    public function property($tokenval = NULL, $hfid = NULL) {
        try {
//            pr($tokenval);exit;
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode','User', 'language', 'mainlanguage', 'property_details_entry', 'genernalinfoentry', 'TrnBehavioralPatterns', 'State', 'inspection', 'articletrnfields', 'article', 'documenttitle', 'document_execution_type', 'TrnBehavioralPatterns'));
            $this->set('actiontypeval', NULL);
             $this->set('actiontype', NULL);
            $this->set('hfid', NULL);
            $this->set('hftoken', NULL);
            $this->set('remark', NULL);
            $this->set('vflag', 'N');
            $this->set('divfinal', 'N');
            $this->set('tokenval1', $tokenval);
            $this->Session->write("tokenno", $tokenval);
            $tokenno = $this->Session->read("tokenno");
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y-m-d H:i:s');
            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $article = $this->article->get_article($lang);
            $documenttitle = $this->documenttitle->get_title();
            $language = $this->mainlanguage->get_main_lag();
           
         
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
             $fieldlist = array();
            $fieldlist['property']['remark']['text'] = 'is_required,is_alpha';
             $fieldlist['property']['verified_flag']['radio'] = 'is_required';
               $fieldlist['property1']['is_yes_no']['radio'] = 'is_required';
              $this->set("fieldlistmultiform", $fieldlist);
//              $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist, TRUE));
            
            
            

            $language2 = $this->mainlanguage->get_state_lang($stateid);
            $property_list = $this->property_details_entry->query("select prop.id,prop.property_id,prop.val_id,village.ulb_type_id,village.developed_land_types_id,
                            village.village_name_$lang,e.evalrule_desc_$lang
                            from ngdrstab_trn_property_details_entry prop ,ngdrstab_mst_evalrule_new e,ngdrstab_trn_valuation_details v,
                                ngdrstab_conf_admblock7_village_mapping village
                                where  village.village_id=prop.village_id and  prop.token_no=?  and prop.val_id=v.val_id and v.rule_id=e.evalrule_id
                                group by prop.property_id,village.village_name_$lang,e.evalrule_desc_$lang ,village.ulb_type_id,village.developed_land_types_id
                                order by prop.property_id", array($tokenval));
//            pr($property_list);
            $property_pattern = $this->property_details_entry->query("SELECT 
                    trn_patterns.field_id, trn_patterns.field_value_$lang, trn_patterns.mapping_ref_val ,village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                  FROM 
                   ngdrstab_trn_behavioral_patterns AS trn_patterns, 
                   ngdrstab_trn_property_details_entry AS prop, 
                   ngdrstab_conf_admblock7_village_mapping AS village,
                   ngdrstab_conf_behavioral_patterns AS conf_patterns
                  WHERE 
                    trn_patterns.mapping_ref_val = prop.property_id AND prop.village_id = village.village_id  and conf_patterns.field_id=trn_patterns.field_id AND prop.token_no=?  
                group by  trn_patterns.id,trn_patterns.mapping_ref_val,trn_patterns.field_id,  trn_patterns.field_value_$lang, village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                order by trn_patterns.id ASC   ", array($tokenval));
//            pr($property_pattern);exit;
            $this->set('username', $this->Auth->User('username'));
            $this->set(compact('language', 'language2', 'document_execution_type', 'property_list', 'property_pattern', 'lang'));
            $vflagcount = $this->property_details_entry->query("SELECT count(verified_flag) from ngdrstab_trn_inspection_detail where token_no=?", array($tokenno));
            $vflagcount = $vflagcount[0][0]['count'];
            if ($vflagcount == count($property_list)) {
                $this->set('divfinal', 'Y');
            } else {
                $this->set('divfinal', 'N');
            }
            $checkremark = $this->property_details_entry->query("SELECT property_no from ngdrstab_trn_inspection_detail where token_no=?", array($tokenno));
            $this->set('checkremark', $checkremark);
            
            if($hfid!=NULL){
                 $property_record = $this->property_details_entry->query("select a.id,c.finyear_desc,j.district_name_$lang,k.taluka_name_$lang,b.governingbody_name_$lang,l.village_name_$lang,
                    d.level_1_desc_$lang, h.list_1_desc_$lang,
                    a.unique_property_no_en,a.unique_property_no_ll,a.remark_en,a.remark_ll,
                    a.boundries_east_en,a.boundries_east_ll,a.boundries_west_en,a.boundries_west_ll,a.boundries_south_en,a.boundries_south_ll,
                    a.boundries_north_en,a.boundries_north_ll,a.additional_information_en,a.additional_information_ll,
                    ll.usage_main_catg_desc_$lang,m.usage_sub_catg_desc_$lang,n.usage_sub_sub_catg_desc_$lang
                    from ngdrstab_trn_property_details_entry a
                    left outer join ngdrstab_mst_finyear c on c.finyear_id=a.finyear_id
                    left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                    left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                    left outer join ngdrstab_conf_admblock_local_governingbody_list b on b.corp_id=a.corp_id
                    left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                    left outer join ngdrstab_mst_location_levels_1_property d on d.level_1_id = a.level1_id
                    left outer join ngdrstab_mst_loc_level_1_prop_list h on h.prop_level1_list_id = a.level1_list_id 
                     left outer join ngdrstab_trn_valuation_details v on v.val_id = a.val_id 
                     left outer join ngdrstab_mst_usage_lnk_category r on r.evalrule_id = v.rule_id
                     inner join ngdrstab_mst_usage_main_category ll on ll.usage_main_catg_id = r.usage_main_catg_id
                    inner join ngdrstab_mst_usage_sub_category m on m.usage_sub_catg_id = r.usage_sub_catg_id
                    left outer join ngdrstab_mst_usage_sub_sub_category n on n.usage_sub_sub_catg_id = r.usage_sub_sub_catg_id
                    where a.token_no=? and a.id=?", array($tokenno, $hfid));
                    $this->set('property_record', $property_record);
//                     pr($property_record);exit;
                    $pattern = $this->TrnBehavioralPatterns->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_en', 'pattern.pattern_desc_ll', 'pattern.field_id', 'TrnBehavioralPatterns.field_value_en', 'TrnBehavioralPatterns.field_value_ll'),
                        'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $hfid, 'TrnBehavioralPatterns.token_no' => $tokenno, 'TrnBehavioralPatterns.mapping_ref_id' => 1), // for property:mapping_ref_id => 1
                        'joins' => array(
//                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id AND pattern.behavioral_id=TrnBehavioralPatterns.mapping_ref_id')),
                            array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                        ),
                        'order' => 'pattern.field_id DESC'
                    ));
                    $this->set('pattern_data', $pattern);

                    $inspkdata = $this->property_details_entry->query("select id, remark, verified_flag from ngdrstab_trn_inspection_detail where token_no=? and property_no=?", array($tokenno, $hfid));
                    if ($inspkdata != NULL) {
                        $remark = $inspkdata[0][0]['remark'];
                        $vflag = $inspkdata[0][0]['verified_flag'];
                        $this->set('remark', $remark);
                        $this->set('vflag', $vflag);
                    } else {
                        $this->set('remark', '');
                        $this->set('vflag', 'N');
                    }
                     $this->set('actiontype', 1);
            }
           
            
//                 pr($checkremark);exit;
            if ($this->request->is('post') && isset($this->request->data['property'])) {
                 $this->check_csrf_token($this->request->data['property']['csrftoken']);
//                pr($this->request->data);exit;
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                $this->request->data['property']['user_id'] = $user_id;
                $this->request->data['property']['created_date'] = date('Y-m-d');
                $this->request->data['property']['req_ip'] = $this->RequestHandler->getClientIp();
                $this->request->data['property']['state_id'] = $stateid;
                $this->set('vflag', '');
                 $this->request->data['property'] = $this->istrim($this->request->data['property']);
                $fieldlistnew = $this->modifyfieldlist($fieldlist['property'], $this->request->data['property']);

                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);
                if ($_POST['actiontype'] == 2) {
//                    pr($this->request->data);exit;
                    $this->request->data['property']['property_no'] = $_POST['hfid'];
                    $this->request->data['property']['token_no'] = $_POST['hftoken'];
                    $remark = $this->property_details_entry->query("select id, remark from ngdrstab_trn_inspection_detail where token_no=? and property_no=?", array($_POST['hftoken'], $_POST['hfid']));
                    if ($remark != NULL) {
                        $remark = $remark[0][0]['id'];
                        $this->request->data['property']['id'] = $remark;
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }
                    
                      $errarr = $this->validatedata($this->request->data['property'], $fieldlistnew);
                     
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                            
                        } else {
                    if ($this->inspection->save($this->request->data['property'])) {
                        $this->Session->setFlash(__("Record $actionvalue Successfully"));
//                        pr($_POST['hftoken']);exit;
                        $this->redirect(array('controller' => 'Inspector', 'action' => 'property', $_POST['hftoken']));
                    } else {
                        $this->Session->setFlash(__("Record Not $actionvalue "));
                        $this->redirect(array('controller' => 'Inspector', 'action' => 'property', $_POST['hftoken']));
                    }
                        }
                }


                $vflagcount = $this->property_details_entry->query("SELECT count(*) from ngdrstab_trn_inspection_detail where token_no=? and verified_flag IN ('N','Y')", array($tokenno));
                $vflagcount = $vflagcount[0][0]['count'];
                if ($vflagcount == count($property_list)) {
                    $this->set('divfinal', 'Y');
                } else {
                    $this->set('divfinal', 'N');
                }
            }
             if ($this->request->is('post') && isset($this->request->data['property1'])) {
                 $this->request->data['property1'] = $this->istrim($this->request->data['property1']);
                $fieldlistnew = $this->modifyfieldlist($fieldlist['property1'], $this->request->data['property1']);
                $errarr = $this->validatedata($this->request->data['property1'], $fieldlistnew);
                     
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                            
                        } else {
                  $compflag = $this->request->data['property1']['finalflag'];
                    $vflagcount = $this->property_details_entry->query("SELECT count(*) from ngdrstab_trn_inspection_detail where token_no=? and verified_flag =?", array($tokenno, 'Y'));
                    $vflagcount = $vflagcount[0][0]['count'];
                    if ($compflag == 'Y') {
                        if ($vflagcount == count($property_list)) {
                            $finalsave = $this->property_details_entry->query("update ngdrstab_trn_generalinformation set inspecation_completed_flag =? where token_no=? ", array('Y', $tokenno));
                            $this->Session->setFlash(__("All Property Document are Acppected Successfully...!!!"));
                            $this->redirect(array('controller' => 'Inspector', 'action' => 'inspectionverification'));
                        } else {
                            $finalsave = $this->property_details_entry->query("update ngdrstab_trn_generalinformation set inspecation_completed_flag =? where token_no=? ", array('N', $tokenno));
                            $this->Session->setFlash(__("Some Property Documents having Issue...!!!! "));
                            $this->redirect(array('controller' => 'Inspector', 'action' => 'inspectionverification'));
                        }
                    } else {
                        $this->Session->setFlash(__("Verify Documents once again...!!!! "));
                        $this->redirect(array('controller' => 'Inspector', 'action' => 'inspectionverification'));
                    }
             }
             }
        } catch (Exception $ex) {
            pr($ex);
        }
    }
    public function modifyfieldlist($fieldlist, $data) {
       

       
        if (!isset($data['remark'])) {
             unset($fieldlist['remark']);
        }
        if (!isset($data['is_yes_no']) ) {
             unset($fieldlist['is_yes_no']);
        }
        if (!isset($data['verified_flag'])) {
             unset($fieldlist['verified_flag']);
        }
        
        return $fieldlist;
    }


}
