<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'Fees');
App::import('Controller', 'Registration'); // mention at top

class ManualRegController extends FeesController {

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

        //$this->Security->unlockedActions = array('payment','stamp_duty','genernal_info', 'genernalinfoentry', 'set_common_fields', 'property_details', 'party_entry','set_token_session','identification','edit_propertydetails','set_value_for_save_identification','witness');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }


        $this->Auth->allow('welcomenote');
    }

    public function major_functions() {
        $this->loadModel('majorfunction');
        $result = $this->majorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'Y')));
        return $result;
    }

    public function minor_functions() {
        $this->loadModel('minorfunction');
        $result = $this->minorfunction->find("all", array('conditions' => array('manual_reg_flag' => 'Y'), 'order' => array('mf_serial ASC')));
        return $result;
    }

    //function for your documents
       public function genernal_info() {
        try {
            array_map(array($this, 'loadModel'), array('genernal_info'));
            $this->Session->write("user_role_id", $this->Auth->user('role_id'));
            
            $this->Session->write("Selectedtoken", NULL);
        $this->Session->write("manual_flag",'Y');
        $this->Session->write("citizen_user_id", $this->Auth->User("user_id"));
        return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernal_info'));
           
        } catch (Exception $ex) {
            
        }
    }

    //function for general infoentry start

    public function genernalinfoentry($flag = '') {

        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }



            array_map(array($this, 'loadModel'), array('User', 'conf_article_feerule_items', 'language', 'mainlanguage', 'articaledepfields', 'genernalinfoentry', 'doc_levels', 'doc_title', 'State', 'User', 'articletrnfields', 'article', 'documenttitle', 'document_execution_type', 'ApplicationSubmitted'));
            $fields = $this->set_common_fields();
            $documenttitle = $this->doc_title->get_title();
            $doc_lang = $this->Session->read("sess_langauge");
            $language = $this->mainlanguage->get_main_lag();
            $language2 = $this->mainlanguage->get_state_lang($fields['stateid']);
            $document_execution_type = $this->document_execution_type->get_doc_execution_type();
            $search_type = array('T' => 'Token Number', 'R' => 'Reference Document Number');
            $this->set('username', $this->Auth->User('username'));
            $this->set(compact('language', 'language2', 'document_execution_type', 'article', 'documenttitle', 'search_type'));
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $article = $this->article->get_article($doc_lang);
            $languagecode = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'conditions' => array('language_code' => $this->Session->read('sess_langauge'))));
            // echo $languagecode[0]['mainlanguage']['id'];
            //exit;
            $this->set('lang_id', $languagecode[0]['mainlanguage']['id']);

            $this->set('article', $article);
            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC')));
            $this->set('office', $office);
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
            $fieldlist = array();

            $fieldlist['local_language_id']['select'] = 'is_select_req';
            //BRING your fields FROM TABLE DYNAMICALLY
            $dynamicfields = $this->conf_article_feerule_items->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_' . $doc_lang, 'conf_article_feerule_items.vrule'),
                'conditions' => array('item.gen_dis_flag' => 'Y'), 'order' => 'item.fee_item_desc_' . $doc_lang,
                'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                        'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')))));
            //advocate name flag
            $advocate_name_flag = $this->advoate_feild_require_flag();
            $this->set('advocate_name_flag', $advocate_name_flag);
            // pr($dynamicfields);exit;
            foreach ($dynamicfields as $field) {
                $fieldlist['fieldval_' . $field['conf_article_feerule_items']['fee_param_code']]['text'] = $field['conf_article_feerule_items']['vrule'];
            }
            $fieldlist['article_id']['select'] = 'is_select_req';
            $fieldlist['no_of_pages']['text'] = 'is_required,is_numeric';
            $fieldlist['exec_date']['text'] = 'is_date_empty';
            $fieldlist['ref_doc_no']['text'] = 'is_alphanumspacedash';
            $fieldlist['title_id']['select'] = '';
            $fieldlist['ref_doc_date']['text'] = 'is_date_empty';
            $fieldlist['link_doc_date']['text'] = 'is_date_empty';
            if ($advocate_name_flag == 'Y') {
                $fieldlist['adv_name_en']['text'] = 'is_alphaspace';
            }
            $fieldlist['doc_writer_name']['text'] = 'is_alphaspace';
            $fieldlist['link_doc_no']['text'] = 'is_alphanumspacedash';

            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($errarr));

            if ($this->request->is('post') || $this->request->is('put')) {



                $languagecode = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'conditions' => array('language_code' => $this->Session->read('sess_langauge'))));
                if ($languagecode) {
                    $this->request->data['genernalinfoentry']['local_language_id'] = $languagecode[0]['mainlanguage']['id'];
                }
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));

                $errarr = $this->validatedata($this->request->data['genernalinfoentry'], $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    // PR($dd);
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                    $file = new File(WWW_ROOT . 'files/jsonfile_dfields_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($errarr));
                } else {
                    $this->request->data['genernalinfoentry']['user_type'] = $this->Session->read("session_usertype");

//                    $a=$this->request->data['genernalinfoentry']['user_type'];
//                    echo $a; exit;

                    $setdata = $this->set_value_tosave_generalinfo($this->request->data['genernalinfoentry'], $fields['user_id'], $fields['stateid'], $fields['user_id']);
                    $this->request->data['genernalinfoentry']['local_language_id'] = $languagecode[0]['mainlanguage']['id'];
                    $this->request->data['genernalinfoentry'] = $setdata;
                    $frmData = $this->request->data['genernalinfoentry'];
                    //server side validations
                    $this->request->data['genernalinfoentry'] = $this->istrim($this->request->data['genernalinfoentry']);

                    if ($this->genernalinfoentry->save($this->request->data['genernalinfoentry'])) {
                        $last_id = $this->genernalinfoentry->getLastInsertID();
                        if (!is_numeric($last_id)) {
                            $last_id = $this->request->data['genernalinfoentry']['general_info_id'];
                        }
                        $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('general_info_id' => $last_id)));
                        // $this->set_token_session($gen_info['0']['genernalinfoentry']['token_no']);
                        if ($gen_info) {
                            $this->Session->write('Selectedtoken', $gen_info[0]['genernalinfoentry']['token_no']);
                            $this->Session->write('article_id', $gen_info[0]['genernalinfoentry']['article_id']); //madhuri
                            $this->set('delay_flag', $gen_info[0]['genernalinfoentry']['delay_flag']); //madhuri
                            $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $gen_info[0]['genernalinfoentry']['local_language_id'])));
                            if ($language) {
                                $this->Session->write('doc_lang', $language['0']['mainlanguage']['language_code']);
                            }
                        }
                        //save article dependent feilds
//                        pr($this->request->data);exit;
                        $this->articletrnfields->savedependent_field($doc_lang, $this->request->data['genernalinfoentry'], $this->Session->read('Selectedtoken'), $fields['user_id']);

                        //----------------------------by Shridhar SD Calculation-------------------------------------------------
                        $tmp_doc_token_no = $gen_info['0']['genernalinfoentry']['token_no'];
                        $sd_values = array(
                            'token_no' => $tmp_doc_token_no,
                            'article_id' => 9999,
                            'FAJ' => $frmData['no_of_pages'], //valuation Amount
                        );
                        $fieldlist = array();
                        $fieldlist['token_no']['text'] = 'is_required,is_digit';
                        $fieldlist['article_id']['text'] = 'is_required,is_digit';
                        $fieldlist['FAJ']['text'] = 'is_required,is_digit';
//                        $this->set('fieldlist', $fieldlist);
                        $json2array['fieldlist'] = $fieldlist;
                        $file = new File(WWW_ROOT . 'files/vjsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                        // calculate Common Stamp Duty (Scanning & Handeing Charges)
                        $this->calculate_fees($sd_values);
                        array_map([$this, 'loadModel'], ['fees_calculation', 'fees_calculation_detail', 'stamp_duty']);
                        $fee_calc_id = $this->fees_calculation->find('first', array('fields' => array('fee_calc_id'), 'conditions' => array('token_no' => $tmp_doc_token_no, 'article_id' => 9999, 'delete_flag' => 'N')));
                        if ($fee_calc_id) {
                            $total_sd = $this->fees_calculation_detail->find('first', array('fields' => array('fees_total'), 'conditions' => array('fee_calc_id' => $fee_calc_id['fees_calculation']['fee_calc_id'])));

                            $tmp_total_sd = $total_sd['fees_calculation_detail']['fees_total'];
                            $sdData = array(
                                'token_no' => $tmp_doc_token_no,
                                'article_id' => $gen_info['0']['genernalinfoentry']['article_id'],
                                'counter_sd_amt' => $tmp_total_sd,
                            );
                            unset($tmp_total_sd);
                            if ($this->stamp_duty->save($sdData)) {
                                $this->stamp_duty->update_sd_amt();
                            }
                            $office_id = $this->Auth->User("office_id");
                            $finalappData1 = array(
                                'token_no' => $tmp_doc_token_no,
                                'office_id' => $office_id,
                                'token_submit_date' => date('Y/m/d'),
                                'user_id' => $this->request->data['genernalinfoentry']['user_id'],
                                'req_ip' => $this->request->data['genernalinfoentry']['req_ip'],
                                'state_id' => $this->request->data['genernalinfoentry']['state_id']
                            );
                            if ($this->ApplicationSubmitted->save($finalappData1)) {
                                $last_id1 = $this->ApplicationSubmitted->getLastInsertID();
                                $this->Session->write("reg_record_no", $last_id1);
                                $this->Session->write("office_id", $office_id);
                                $reg = new RegistrationController();
                                $reg->constructClasses();
                                $doc_reg_no = $reg->generate_document_number();
                                $this->Session->write("doc_reg_no", $doc_reg_no);
                                $finalappData2['id'] = $last_id1;
                                $finalappData2['doc_reg_no'] = $doc_reg_no;
                                $finalappData2['doc_reg_date'] = date('Y/m/d');
                                if ($this->ApplicationSubmitted->save($finalappData2)) {
                                    $this->Session->setFlash("Saved Successfully");
                                    return $this->redirect('property_details');
                                }
                            }
                        }
                        //-----------------------------------------------------------------------------------------------------


                        $this->Session->setFlash("Saved Successfully");
                        return $this->redirect('property_details');
                    }
                }
                // $this->Session->setFlash("Saved Not Saved");
            } else {
                //  $this->set_token_session($tokenval);
                // $this->Session->write('Selectedtoken', $tokenval);
                $this->set('Selectedtoken', $tokenval = $this->Session->read('Selectedtoken'));
                $gen_info = $this->genernalinfoentry->find('all', array('conditions' => array('token_no' => $tokenval, 'user_id' => $fields['user_id'])));
                if ($gen_info != NULL) { // set data  to form
                    $this->Session->write('no_of_pages', $gen_info[0]['genernalinfoentry']['no_of_pages']);
                    $this->request->data['genernalinfoentry'] = $gen_info[0]['genernalinfoentry'];
                    if (is_numeric($this->Session->read('doc_lang_id'))) {
                        $this->request->data['genernalinfoentry']['local_language_id'] = $this->Session->read('doc_lang_id');
                    }
                    if ($gen_info[0]['genernalinfoentry']['exec_date'] != '' || $gen_info[0]['genernalinfoentry']['exec_date'] != NULL) {
                        $this->request->data['genernalinfoentry']['exec_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['exec_date']));
                    }
                    if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                        $this->request->data['genernalinfoentry']['presentation_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['presentation_date']));
                    }
                    $this->request->data['genernalinfoentry']['ref_doc_date'] = ($gen_info[0]['genernalinfoentry']['ref_doc_date']) ? date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['ref_doc_date'])) : NULL;

                    $this->request->data['genernalinfoentry']['link_doc_date'] = ($gen_info[0]['genernalinfoentry']['link_doc_date']) ? date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['link_doc_date'])) : NULL;

                    if ($gen_info[0]['genernalinfoentry']['court_order_date']) {
                        $this->request->data['genernalinfoentry']['court_order_date'] = date('d-m-Y', strtotime($gen_info[0]['genernalinfoentry']['court_order_date']));
                    }
                    $language = $this->mainlanguage->find("all", array('conditions' => array('id' => $gen_info[0]['genernalinfoentry']['local_language_id'])));
                    if (!is_numeric($this->Session->read('doc_lang_id'))) {
                        $this->Session->write('doc_lang', $language['0']['mainlanguage']['language_code']);
                    }
                    $this->Session->write('article_id', $gen_info[0]['genernalinfoentry']['article_id']);
                    $this->set('delay_flag', $gen_info[0]['genernalinfoentry']['delay_flag']);
                }
            }
        } catch (Exception $ex) {
            // pr($ex);
        }
    }

    function advoate_feild_require_flag() {

        //this->autoRender = false;
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 39)));
        if (!empty($regconfig)) {

            return $regconfig['regconfig']['conf_bool_value'];
        }
    }

    function set_common_fields() {
        $data['stateid'] = $this->Auth->User("state_id");
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Session->read("citizen_user_id");
        return $data;
    }

    function delete_session() {
        $this->Session->write('Selectedtoken', NULL);
        $this->Session->write('doc_lang', NULL);
        $this->Session->write('article_id', NULL);
        $this->redirect(array('action' => 'genernalinfoentry'));
    }

    function set_value_tosave_generalinfo($data, $user_id, $stateid) {
        try {
            $data['user_id'] = $user_id;
            $data['req_ip'] = $this->RequestHandler->getClientIp();
            $data['state_id'] = $stateid;

            $data['ref_doc_date'] = ($data['ref_doc_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['ref_doc_date']))) : NULL;

            if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
                $data['presentation_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['presentation_date'])));
            }
            $data['exec_date'] = ($data['exec_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['exec_date']))) : NULL;
            $data['link_doc_date'] = ($data['link_doc_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['link_doc_date']))) : NULL;
            $data['court_order_date'] = ($data['doc_execution_type_id'] == 3) ? (($data['court_order_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['court_order_date']))) : NULL) : NULL;
            $data['last_status_id'] = 1;
            $data['last_status_date'] = date('Y-m-d');

            return $data;
        } catch (Exception $ex) {
            
        }
    }

    //general infoentry end
    //Property Entry Start
    public function property_details($prop_id = NULL) {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect('genernalinfoentry');
            }
            // Load Model
            array_map(array($this, 'loadModel'), array('property_details_entry', 'regconfig', 'attribute_parameter', 'articaledepfields', 'articletrnfields', 'parameter', 'items_parameter', 'TrnBehavioralPatterns', 'article_screen_mapping'));
            // Declere Variable
            $user_id = $this->Session->read("citizen_user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $Selectedtoken = $token = $this->Session->read('Selectedtoken');
            $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 33)));
            $regval = $regconfig['regconfig']['conf_bool_value'];
            $this->set(compact('lang', 'doc_lang', 'Selectedtoken', 'regval'));
            $result = $this->article_screen_mapping->find("all", array('conditions' => array('article_id' => $this->Session->read('article_id'), 'minorfun_id' => 2)));
            if (empty($result)) {
                return $this->redirect('party_entry'); // screen no avalable to article
            }
            // Load data to json file and set variable for ctp
            $json2array = $this->load_json_file();
            //---------------------------------EDIT EDIT---------------------------------------------------------------------------------------------  
            if (!$this->request->is('post') and is_numeric($prop_id)) {
                $this->edit_propertydetails($prop_id);
            }
            //----------------------------------END EDIT-----------------------------------------------------------------------------------------------------    


            $this->set("fieldlist", $fieldlist = $this->valuation->fieldlist());
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['propertyscreennew']['csrftoken']);

                if (isset($this->request->data['propertyscreennew']['corp_id']) && !is_numeric($this->request->data['propertyscreennew']['corp_id'])) {
                    $this->request->data['propertyscreennew']['corp_id'] = '';
                }

                if (!is_numeric($this->request->data['propertyscreennew']['taluka_id'])) {
                    $this->request->data['propertyscreennew']['taluka_id'] = '';
                }
                if (isset($this->request->data['propertyscreennew']['level1_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                    $this->request->data['propertyscreennew']['level1_id'] = NULL;
                }
                if (isset($this->request->data['propertyscreennew']['level1_list_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_list_id'])) {
                    $this->request->data['propertyscreennew']['level1_list_id'] = NULL;
                }

                $this->post_back_valuation_data($json2array);
                $unique_record_id = '';
                if (isset($this->request->data['propertyscreennew']['property_id']) and $this->request->data['propertyscreennew']['property_id'] == $this->Session->read('prop_edit_id')) {
                    $unique_record_id = $this->Session->read('prop_edit_id');
                }
                $this->request->data['propertyscreennew']['token_no'] = $token;
                $this->request->data['propertyscreennew']['user_id'] = $user_id;
                $this->property_details_entry->create();
                $this->request->data['propertyscreennew']['property_id'] = $unique_record_id;

                $last_prop_id = $unique_record_id;
                $this->request->data['propertyscreennew']['user_type'] = $this->Session->read("session_usertype");

                // pr($this->request->data['propertyscreennew']);exit;
                if ($this->property_details_entry->save($this->request->data['propertyscreennew'])) {
                    $last_prop_id = $this->property_details_entry->getLastInsertID();

                    if (empty($last_prop_id)) {  // ON update Null
                        $last_prop_id = $unique_record_id;
                    }

                    if (isset($this->request->data['propertyscreennew']['usage_cat_id']) and ! empty($this->request->data['propertyscreennew']['usage_cat_id'])) {
                        // SET DELETE FLAG FOR VALUATION                  
                        $this->valuation->query("UPDATE ngdrstab_trn_valuation SET delete_flag='Y' WHERE property_id=$last_prop_id AND token_no=$token AND user_id= $user_id");
                        $this->request->data['propertyscreennew']['property_id'] = $last_prop_id;
                        $this->request->data['propertyscreennew']['token_no'] = $token;
                        unset($this->request->data['propertyscreennew']['property_id']); // other wise updates valuation Records
                        $result = $this->property_valuation();
                        $val_id = is_numeric($result) ? $this->request->data['propertyscreennew']['val_id'] : 0;
                        $this->property_details_entry->query("UPDATE ngdrstab_trn_property_details_entry  SET val_id=$val_id WHERE property_id=$last_prop_id AND token_no=$token AND user_id= $user_id ");

                        $sd_values = array(
                            'token_no' => $token,
                            'property_id' => $last_prop_id,
                            'article_id' => $this->Session->read('article_id'),
                            'FAA' => $this->get_valuation_amt($val_id), //valuation Amount
                            'village_id' => $this->request->data['propertyscreennew']['village_id']
                        );
//                       pr($this->Auth->User("user_id")); exit;
//                        $feesobj = new FeesController;
//                        $temp = $this->calculatefees($sd_values);//13-feb-2017 commented 
                    }
                    // Usage Items Entry (for Multiple usage and multiple item)
                    foreach ($json2array['usageitemlist'] as $usageitem) {
                        $this->items_parameter->save_item_prameter($this->request->data['propertyscreennew'], $usageitem, $last_prop_id, $token, $user_id, $this->Session->read("session_usertype"));
                    }
                    //  For Attribute Entry
                    // ( Delete   Existing Entry For Edit)
                    $this->parameter->deleteAll(['property_id' => $last_prop_id, 'token_id' => $token]);

                    if (isset($json2array['prop_attributes_seller'])) {

                        $this->parameter->save_parameter($json2array['prop_attributes_seller'], $last_prop_id, $token, $user_id, 'S', $this->Session->read("session_usertype"));
                    }
                    if ($regval == 'Y') {
                        if (isset($json2array['prop_attributes_pur'])) {
                            $this->parameter->save_parameter($json2array['prop_attributes_pur'], $last_prop_id, $token, $user_id, 'P', $this->Session->read("session_usertype"));
                        }
                    }
                    //  For Behavioral Pattens Entry //( Delete   Existing Entry For Edit)
                    $this->TrnBehavioralPatterns->deletepattern($token, $user_id, $last_prop_id, 1);
                    if (isset($this->request->data['property_details']['pattern_id'])) {
                        $this->TrnBehavioralPatterns->savepattern($token, $user_id, $last_prop_id, $this->request->data['property_details'], 1, $this->Session->read("session_usertype"));
                    }
                    $this->Session->setFlash(("Record Saved Succefully"));
                    $this->redirect(array('action' => 'property_details'));
                } else {
                    $this->Session->setFlash(("Record Not Saved"));
                }
            }
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $token, $user_id);
            $this->set('property_list', $property_list);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $token, $user_id);
            $this->set('property_pattern', $property_pattern);
        } catch (Exception $ex) {
            pr($ex);
        }
        $this->set_csrf_token();
    }

    public function edit_propertydetails($prop_id) {
        try {
            $prop_result = $this->property_details_entry->find("all", array('conditions' => array('property_id' => $prop_id)));
            $this->set("prop_result", $prop_result);
            if (empty($prop_result)) {
                $this->Session->setFlash("Property Not Found");
                return $this->redirect('property_details');
            }
            $this->Session->write('prop_edit_id', $prop_id);
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $attributes_result = $this->parameter->find("all", array('conditions' => array('property_id' => $prop_id)));

            foreach ($attributes_result as $key => $attributes) {
                if ($attributes['parameter']['parameter_type'] == 'S') {
                    $prop_attributes_seller[$attributes['parameter']['paramter_id']] = array('attribute_value' => $attributes['parameter']['paramter_value'], 'attribute_value1' => $attributes['parameter']['paramter_value1'], 'attribute_value2' => $attributes['parameter']['paramter_value2']);
                } else if ($attributes['parameter']['parameter_type'] == 'P') {
                    $prop_attributes_pur[$attributes['parameter']['paramter_id']] = array('attribute_value' => $attributes['parameter']['paramter_value'], 'attribute_value1' => $attributes['parameter']['paramter_value1'], 'attribute_value2' => $attributes['parameter']['paramter_value2']);
                }
            }
            if (isset($prop_attributes_seller)) {
                $json2array['prop_attributes_seller'] = $prop_attributes_seller;
                $this->set('prop_attributes_seller', $prop_attributes_seller);
            }

            if (isset($prop_attributes_pur)) {
                $json2array['prop_attributes_pur'] = $prop_attributes_pur;
                $this->set('prop_attributes_pur', $prop_attributes_pur);
            }
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));

            $this->set('attributes', $json2array['attributes']);
        } catch (Exception $ex) {
            
        }
    }

    public function add_property_attribute() {
        try {
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            if ($this->request->data['type'] == 'S') {
                if (isset($json2array['prop_attributes_seller'])) {
                    $prop_attributes = $json2array['prop_attributes_seller'];
                }
                $prop_attributes[$this->request->data['attribute_id']] = array('attribute_value' => $this->request->data['attribute_value'], 'attribute_value1' => $this->request->data['attribute_value1'], 'attribute_value2' => $this->request->data['attribute_value2']);
                $json2array['prop_attributes_seller'] = $prop_attributes;
            } else if ($this->request->data['type'] == 'P') {
                if (isset($json2array['prop_attributes_pur'])) {
                    $prop_attributes = $json2array['prop_attributes_pur'];
                }
                $prop_attributes[$this->request->data['attribute_id']] = array('attribute_value' => $this->request->data['attribute_value'], 'attribute_value1' => $this->request->data['attribute_value1'], 'attribute_value2' => $this->request->data['attribute_value2']);

                $json2array['prop_attributes_pur'] = $prop_attributes;
            }

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
            $this->set('prop_attributes', $prop_attributes);
            $this->set('attributes', $json2array['attributes']);
        } catch (Exception $ex) {
            
        }
    }

    public function property_remove($id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('property_details_entry', 'parameter', 'TrnBehavioralPatterns'));
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');

            $this->parameter->deleteAll(['property_id' => $id, 'token_id' => $token]);
            $this->TrnBehavioralPatterns->deleteAll(['mapping_ref_val' => 1, 'mapping_ref_val' => $id, 'token_no' => $token, 'user_id' => $user_id]);

            if ($this->property_details_entry->deleteAll(['property_details_entry.property_id' => $id, 'token_no' => $token, 'user_id' => $user_id])) {
                $this->Session->setFlash(("Record Deleted Successfully"));
                $this->redirect(array('controller' => 'citizenentry', 'action' => 'property_details'));
            }
        } catch (Exception $ex) {
            
        }
    }

    //party entry start

    public function party_entry() {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect('genernalinfoentry');
            }//load Model
            array_map([$this, 'loadModel'], ['identificatontype', 'bank_master', 'party_entry', 'property_details_entry', 'salutation', 'gender', 'occupation', 'party_category', 'partytype', 'article_screen_mapping',
                'articaledepfields', 'articletrnfields', 'regconfig', 'property_details_entry', 'TrnBehavioralPatterns', 'extinterfacefielddetails', 'article_partymapping', 'District', 'taluka', 'presentation_exmp', 'party_category_fields', 'BehavioralPattens']);
            //declare & assign variable
            $popupstatus = $actiontypeval = $hfid = $hfupdateflag = $hfactionval = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $lang = $this->Session->read("sess_langauge");
            $laug = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $this->set('laug', $laug);
            $fields = $this->set_common_fields();
            $party_category = $this->party_category->find('list', array('fields' => array('party_category.category_id', 'party_category.category_name_' . $doc_lang), 'order' => array('category_id' => 'ASC')));
            $property = $this->article_screen_mapping->query('select minorfun_id from ngdrstab_mst_article_screen_mapping where article_id=' . $this->Session->read("article_id") . ' and minorfun_id =2');
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $tokenval, $fields['user_id']);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $tokenval, $fields['user_id']);
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $party_record = $this->party_entry->get_partyrecord($tokenval, $fields['user_id'], $doc_lang, $lang);
            $name_format = $this->get_name_format();
            $this->set('name_format', $name_format);
            $this->set('partytype', $partytype_name);
            $this->set(compact('hfactionval', 'bank_master', 'lang', 'Selectedtoken', 'popupstatus', 'actiontypeval', 'hfid', 'hfupdateflag', 'hfactionval', 'districtdata', 'taluka', 'party_category', 'property', 'property_list', 'property_pattern', 'party_record', 'condition'));

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $allrule = $this->identificatontype->query('select e.error_code ,e.pattern_rule_client ,e.error_messages_' . $laug . ' from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id ');
            $this->set('allrule', $allrule);
            $doc_lang = $this->Session->read('doc_lang');
            $fieldlist = array();
            $fielderrorarray = array();
            $partyfields = $this->party_category_fields->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));
            foreach ($partyfields as $field) {
                $field = $field['party_category_fields'];
                if ($field['is_list'] == 'N') {
                    $fieldlist[$field['field_id_name_en']]['text'] = $field['vrule_en'];
                    if (!empty($field['field_id_name_ll'])) {
                        $fieldlist[$field['field_id_name_ll']]['text'] = $field['vrule_ll'];
                    }
                } else if ($field['is_list'] == 'Y') {
                    $fieldlist[$field['field_id_name_en']]['select'] = $field['vrule_en'];
                }
            }
            $BehavioralPatterns = $this->BehavioralPattens->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=2 AND behavioral.behavioral_id=details.behavioral_id  ");
            foreach ($BehavioralPatterns as $field) {
                $field = $field['0'];
                $fieldlist['field_en' . $field['field_id']]['text'] = $field['vrule_en'];
                $fieldlist['field_ll' . $field['field_id']]['text'] = $field['vrule_ll'];
            }
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            $this->set('formData', NULL);
//            $json2array['fieldlist'] = $fieldlist;
//            $file = new File(WWW_ROOT . 'files/vjsonfile1_' . $this->Auth->user('user_id') . '.json', true);
//            $file->write(json_encode($json2array));
//            foreach ($fieldlist as $key => $valrule) {
//                $errarr[$key . '_error'] = "";
//            }
//            $this->set("errarr", $errarr);
//            $file->write(json_encode($errarr));


            if ($this->request->is('post')) {

                $fromdata = $this->request->data['party_entry'];
                $this->request->data['party_entry'] = $this->istrim($this->request->data['party_entry']);
                if (isset($this->request->data['property_details'])) {
                    $bdata = $this->request->data['property_details'];
//                    pr($this->request->data);

                    foreach ($bdata as $datafield) {
                        foreach ($datafield as $key => $fieldid) {
                            $this->request->data['party_entry']['field_en' . $fieldid] = $bdata['pattern_value_en'][$key];
                            //  pr($field);
                            if (isset($bdata['pattern_value_ll'][$key])) {
                                $this->request->data['party_entry']['field_ll' . $fieldid] = $bdata['pattern_value_ll'][$key];
                            }
                        }
                    }
                }
                //-------------------------------------------------------------------------------
                $errarr = $this->validatedata($this->request->data['party_entry'], $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
//                    $this->request->data['party_entry'] = $fromdata;
                    $this->set('fromdata', $fromdata);
                } else {

                    $val_amt = $this->get_valuation_amt($_POST['val_id']);
                    if (is_numeric($val_amt) && $val_amt > 1000000) {
                        if (!$this->request->data['party_entry']['pan_no']) {
                            // $this->Session->setFlash(__('Please Enter PAN'));
                            //$this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $tokenval));
                        }
                    }
                    $actiontype = $_POST['actiontype'];
                    $hfactionval = $_POST['hfaction'];
                    $property_id = $_POST['propertyid'];
                    $hfid = $_POST['hfid'];
                    $uid_compulsary = $this->is_uid_compulsary();
                    $identity = $this->is_identity_compulsary();
                    if ($uid_compulsary == 'Y') {
                        if (!$this->request->data['party_entry']['uid']) {
                            $this->Session->setFlash(__('Please Enter UID'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $tokenval));
                        }
                    }
                    if ($identity == 'Y') {
                        if (!$this->request->data['party_entry']['identificationtype_id'] || $this->request->data['party_entry']['identificationtype_desc_' . $doc_lang] == '') {
                            $this->Session->setFlash(__('Please Select Identity'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry', $tokenval));
                        }
                    }
                    $this->set('hfid', $hfid);
                    $name_format = $this->get_name_format();
                    if ($name_format == 'Y') {
                        if (isset($this->request->data['party_entry']['party_full_name_en'])) {
                            if ($this->request->data['party_entry']['party_full_name_en'] == '') {
                                $this->request->data['party_entry']['party_full_name_en'] = isset($this->request->data['party_entry']['party_fname_en']) ? $this->request->data['party_entry']['party_fname_en'] . ' ' . $this->request->data['party_entry']['party_mname_en'] . ' ' . $this->request->data['party_entry']['party_lname_en'] : '';
                            } else {
                                $this->request->data['party_entry']['party_full_name_en'] = $this->request->data['party_entry']['party_full_name_en'];
                            }
                        } else {
                            $this->request->data['party_entry']['party_full_name_en'] = isset($this->request->data['party_entry']['party_fname_en']) ? $this->request->data['party_entry']['party_fname_en'] . ' ' . $this->request->data['party_entry']['party_mname_en'] . ' ' . $this->request->data['party_entry']['party_lname_en'] : '';
                        }
                        if (isset($this->request->data['party_entry']['party_full_name_ll'])) {
                            if ($this->request->data['party_entry']['party_full_name_ll'] == '') {
                                $this->request->data['party_entry']['party_full_name_ll'] = isset($this->request->data['party_entry']['party_fname_ll']) ? $this->request->data['party_entry']['party_fname_ll'] . ' ' . $this->request->data['party_entry']['party_mname_ll'] . ' ' . $this->request->data['party_entry']['party_lname_ll'] : '';
                            } else {
                                $this->request->data['party_entry']['party_full_name_ll'] = $this->request->data['party_entry']['party_full_name_ll'];
                            }
                        } else {
                            $this->request->data['party_entry']['party_full_name_ll'] = isset($this->request->data['party_entry']['party_fname_ll']) ? $this->request->data['party_entry']['party_fname_ll'] . ' ' . $this->request->data['party_entry']['party_mname_ll'] . ' ' . $this->request->data['party_entry']['party_lname_ll'] : '';
                        }
                    }
                    if (1) {
                        $this->set('actiontypeval', $actiontype);
                        $this->set('hfactionval', $hfactionval);
                        if ($hfactionval == 'S') {
                            $this->request->data['party_entry']['user_type'] = $this->Session->read("session_usertype");

                            if ($this->party_entry->save_party($this->request->data['party_entry'], $tokenval, $fields['stateid'], $fields['user_id'], $property_id, $hfid)) {
                                if ($this->request->data['hfupdateflag'] == 'Y') {
                                    $party_id = $hfid;
                                    $actionvalue = "Updated";
                                } else {
                                    $party_id = $this->party_entry->getLastInsertID();
                                    $actionvalue = "Saved";
                                }
                                if (isset($this->request->data['property_details']['pattern_id'])) {
                                    $this->TrnBehavioralPatterns->deletepattern($tokenval, $fields['user_id'], $party_id, 2);
                                    $this->TrnBehavioralPatterns->savepattern($tokenval, $fields['user_id'], $party_id, $this->request->data['property_details'], 2, $this->Session->read("session_usertype"));
                                }
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'party_entry'));
                                $this->set('party_record', $this->party_entry->find('all'));
                            } else {
                                $this->Session->setFlash(__('Record Not Saved '));
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
        $this->set_csrf_token();
    }

    public function get_name_format() {
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 29)));
        if (!empty($regconfig)) {
            return $regconfig['regconfig']['conf_bool_value'];
        }
    }

    public function set_token_session($token) {
        $this->loadModel('language');

        $lang = $this->language->query('select DISTINCT l.language_code ,g.local_language_id from ngdrstab_trn_generalinformation g,ngdrstab_mst_language l ,ngdrstab_conf_language cl where g.local_language_id=l.id and g.token_no=?', array($token));
        if (!empty($lang)) {
            $this->Session->write('doc_lang', $lang[0][0]['language_code']);
        }
        $this->Session->write('Selectedtoken', $token);
        $data = $this->language->query('select office_id,doc_reg_no from ngdrstab_trn_application_submitted where token_no=?', array($token));
        $this->Session->write('office_id', $data[0][0]['office_id']);
        $this->Session->write('doc_reg_no', $data[0][0]['doc_reg_no']);
        $this->redirect(array('action' => 'genernalinfoentry'));
    }

    public function identification() {
        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect('genernalinfoentry');
            }
//load Model
            array_map(array($this, 'loadModel'), array('identificatontype', 'identification', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'identifire_type', 'party_entry'));
            $actiontypeval = $hfid = $hfupdateflag = $popupstatus = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $identification = $this->identification->get_identification_details($doc_lang, $tokenval, $user_id);
            $sro = $this->User->query('select a.*,b.* from ngdrstab_mst_employee a,ngdrstab_mst_user b where a.emp_code=b.employee_id and b.user_id=?', array($this->Auth->User('user_id')));
            $identifire_type = $this->identifire_type->find('list', array('fields' => array('identifire_type.type_id', 'identifire_type.desc_' . $doc_lang)));
            $this->set('sro', $sro);
            $this->set('identification', $identification);
            $this->set('identifire_type', $identifire_type);
            $alllevel = $this->doc_levels->get_alllevel();
//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Selectedtoken', 'language', 'identification', 'doclevels', 'sro'));
            $this->set('salutation', ClassRegistry::init('salutation')->find('list', array('fields' => array('salutation_id', 'salutation_desc_' . $doc_lang), 'order' => array('salutation_desc_' . $doc_lang => 'ASC'))));
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_en' => 'ASC'))));
            $this->set('gender', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $doc_lang))));
            $this->set('occupation', ClassRegistry::init('occupation')->find('list', array('fields' => array('occupation_id', 'occupation_name_'.$doc_lang))));
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
            $this->set('partytype', $partytype_name);
            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $doc_lang))));
            $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang))));
            $partytype_name = $this->partytype->get_party_typename($this->Session->read("article_id"));
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
            if ($name_format == 'Y') {
                //list for english single fields
                $fieldlist['fname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['mname_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['lname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['aliasname_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';
                if ($doc_lang != 'en') {
                    //list for all unicode fields
                    $fieldlist['fname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['mname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['lname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['aliasname_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['idetification_mark1_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicode_rule_' . $doc_lang;
                }
            } else {
                $fieldlist['identification_full_name_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['aliasname_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';
                if ($doc_lang != 'en') {
                    $fieldlist['identification_full_name_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['aliasname_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['idetification_mark1_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicode_rule_' . $doc_lang;
                }
            }
            $fieldlist['salutation']['select'] = 'is_select_req'; //dob,
            // $fieldlist['occupation_id']['select'] = 'is_select_req'; //occupation_id
            $fieldlist['gender_id']['select'] = 'is_select_req';
            $fieldlist['district_id']['select'] = 'is_select_req'; //district_id
            $fieldlist['taluka_id']['select'] = 'is_select_req'; //taluka_id
            $fieldlist['village_id']['select'] = 'is_select_req'; //village_id
            //  $fieldlist['identificationtype_id']['select'] = 'is_select_req'; //identificationtype_id
//           $fieldlist['dob']['text'] = 'is_alphanumspace'; 
            $fieldlist['uid_no']['text'] = 'is_uidnum'; //uid_no
            $fieldlist['age']['text'] = 'is_digit'; //age
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian'; //7,8,9 start
            $fieldlist['pan_no']['text'] = 'is_pancard'; //pan_no
            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $errarr['identificationtype_desc_en_error'] = '';
            $errarr['identificationtype_desc_ll_error'] = '';
            $this->set("errarr", $errarr);



            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['identification']['csrftoken']);
                $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");

                $data = $this->set_value_for_save_identification($this->request->data['identification'], $stateid, $tokenval, $user_id);
                $this->request->data['identification'] = $data;
                if ($_POST['actiontype'] == '1') {
                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['identification']['id'] = $this->request->data['hfid'];
                        $actionvalue = "Updated";
                    } else {
                        $actionvalue = "Saved";
                    }

                    $this->request->data['identification'] = $this->istrim($this->request->data['identification']);

                    if ($this->request->data['identification']['identificationtype_id']) {
                        $rule = $this->identification->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['identification']['identificationtype_id']);
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
                        $this->request->data['identification']['user_type'] = $this->Session->read("session_usertype");
                        if ($this->identification->save($this->request->data['identification'])) {
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
                                $this->redirect(array('action' => 'identification', $tokenval));
                            } else {
                                $identification1 = $this->identification->find('all', array('conditions' => array('id' => $this->identification->getLastInsertId())));
                                $this->redirect(array('action' => 'identification', $identification1[0]['identification']['token_no']));
                            }
                        } else {
                            $this->Session->setFlash(__("Record Not $actionvalue "));
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $tokenval));
                }
                if ($_POST['actiontype'] == '3') {
                    if ($_POST['hfid'] != NULL) {
                        $this->identification->id = $_POST['hfid'];
                        if ($this->identification->delete()) {
                            $this->Session->setFlash(__('Record Deleted Successfully'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'identification', $tokenval));
                        } else {
                            $this->Session->setFlash(__('Record Not Deleted'));
                        }
                    }
                }



//            } else {
//                if ($identification) {
//                    $this->identification->id = $identification[0][0]['id'];
//                    $dataarray = $this->identification->read();
//                    $this->request->data['identification']['token_no'] = $dataarray['identification']['token_no'];
//                }
            }
            $checkuid_entry = $this->party_entry->find("all", array('conditions' => array('token_no' => $tokenval)));
            $this->set("checkuid_entry", $checkuid_entry);
        } catch (Exception $ex) {
            pr($ex);
        }
        $this->set_csrf_token();
    }

    function set_value_for_save_identification($data, $stateid, $tokenval, $user_id) {

        $language = $this->Session->read("sess_langauge");
        $doc_lang = $this->Session->read('doc_lang');
        $uid_compulsary = $this->is_uid_compulsary();
        $identity = $this->is_identity_compulsary();
        if ($uid_compulsary == 'Y') {
            if (!$data['uid_no']) {
                $this->Session->setFlash(__('Please Enter UID'));
                $this->redirect(array('controller' => 'ManualReg', 'action' => 'identification', $tokenval));
            }
        }
        if ($identity == 'Y') {
            if (!$data['identificationtype_id'] || $data['identificationtype_desc_' . $doc_lang] == '') {
                $this->Session->setFlash(__('Please Select Identity'));
                $this->redirect(array('controller' => 'ManualReg', 'action' => 'identification', $tokenval));
            }
        }
        $data['user_id'] = $user_id;
        // $data['created_date'] = date('Y/m/d');
        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['state_id'] = $stateid;
        $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));

        $this->set('actiontypeval', $_POST['actiontype']);
        $this->set('hfid', $_POST['hfid']);

        $name_format = $this->get_name_format();
        if ($name_format == 'Y') {
            $data['identification_full_name_en'] = $data['fname_en'] . ' ' . $data['mname_en'] . ' ' . $data['lname_en'];
            $data['identification_full_name_ll'] = $data['fname_ll'] . ' ' . $data['mname_ll'] . ' ' . $data['lname_ll'];
        }
        return $data;
    }

    //--------------------------------------------------------------------Stamp Duty Related------------------------------------------------------------
    public function stamp_duty() {
        // load Model

        if ($this->referer() != '' && $this->referer() != '/') {
            if (strpos($this->referer(), $this->webroot) == false) {
                header('Location:../cterror.html');
                exit;
            }
        }



        if (!is_numeric($this->Session->read('Selectedtoken'))) {
            $this->Session->setFlash("Please first fill general Information");
            return $this->redirect('genernalinfoentry');
        }
        //load Model
        array_map(array($this, 'loadModel'), array('article', 'property_details_entry', 'article_fee_rule', 'stamp_duty', 'stamp_duty_adjustment', 'conf_reg_bool_info', 'office'));
        $user_id = $this->Session->read("citizen_user_id");
        $citizen_token_no = $this->Session->read('Selectedtoken');
        $state_id = $this->Auth->User("state_id");
        //$user_id = $this->Auth->User("user_id");
        $lang = $this->Session->read("sess_langauge");
        $formdata = $this->stamp_duty->find('first', array('conditions' => array('state_id' => $state_id, 'token_no' => $citizen_token_no)));
        $doc_lang = $this->Session->read('doc_lang');
        $property_list = $this->property_details_entry->get_property_list($doc_lang, $citizen_token_no, $user_id);
        $this->set('property_list', $property_list);
        $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $citizen_token_no, $user_id);
        $this->set('property_pattern', $property_pattern);

        $article_id = $this->Session->read('article_id');
        $exemption_flag = $this->article->field('exemption_applicable', array('article_id' => $article_id));
        $this->set('exemption', $exemption_flag);
        $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'order' => array('office_name_' . $lang => 'ASC')));
        $this->set('office', $office);
        $sd_flags = $this->conf_reg_bool_info->find('list', array('fields' => array('reginfo_id', 'conf_bool_value'), 'conditions' => array('state_id' => $state_id)));
        $delay_flag = $sd_flags[6];
        $sd_adj_flag = $sd_flags[8];
        $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('article_id' => 9998))); //get only Exemption Rules(9998)
        $this->set(compact('lang', 'article_id', 'exemption_rule', 'citizen_token_no', 'article_rule', 'property_pattern', 'property_list', 'sd_adj_flag', 'delay_flag'));
        //validations kalyani
        $this->loadModel('NGDRSErrorCode');
        $result_codes = $this->NGDRSErrorCode->find("all");
        $this->set('result_codes', $result_codes);
        $name_format = $this->get_name_format();
        $this->set('name_format', $name_format);
        $laug = $this->Session->read("sess_langauge");
        $this->set('laug', $laug);

        $doc_lang = $this->Session->read('doc_lang');
        $fieldlist = array();
        $fieldlist['cons_amt']['text'] = 'is_digit';
        $this->set('fieldlist', $fieldlist);
        $json2array['fieldlist'] = $fieldlist;
        $file = new File(WWW_ROOT . 'files/vjsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
        foreach ($fieldlist as $key => $valrule) {
            $errarr[$key . '_error'] = "";
        }

        $this->set("errarr", $errarr);
        $fieldlist1 = array();
        $fieldlist1['online_adj_doc_no']['text'] = 'is_alphanumdashslash';
        $fieldlist1['online_adj_doc_date']['text'] = '';
        //  $fieldlist1['online_adj_amt'] ['text'] = 'is_required';
        $this->set('fieldlist1', $fieldlist1);
        foreach ($fieldlist1 as $key => $valrule) {
            $errarr1[$key . '_error'] = "";
        }
        $this->set("errarr1", $errarr1);
        if ($this->request->is('post')) {
            $data = array('online_adj_doc_no' => $this->request->data['frm']['online_adj_doc_no'],
                'online_adj_doc_date' => $this->request->data['frm']['online_adj_doc_date'],
                'online_adj_amt' => $this->request->data['frm']['online_adj_amt']);
            // $this->check_csrf_token($this->request->data['frm']['csrftoken']);
            $errarr1 = $this->validatedata($data, $fieldlist1);
            $flag = 0;
            foreach ($errarr1 as $dd) {
                if ($dd != "") {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
                $this->set("errarr1", $errarr1);
            } else {
                $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                //-------------------------------Date 01-March-2017 by shridhar for Stamp Duty Adjustment---------------------------------------------------------
                $frm = $this->request->data['frm'];
                $sd_update_result = 1;
                $this->update_sd($this->request->data['frm']);
                if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                    $adjustable_amount = $this->get_adj_doc_exess_amt($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                    if ($frm['online_adj_amt'] < $adjustable_amount) {
                        $sd_update_result = $this->update_sd($this->request->data['frm']);
                    } else {
                        $sd_update_result = 0;
                        $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                    }
                } else {
                    $this->stamp_duty_adjustment->id = $citizen_token_no;
                    $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                    $sd_update_result = $this->update_sd($this->request->data['frm']);
                }


//------------------------------------------------------------------------------------------------------------------------------------------------
                if ($sd_update_result == 1) {
                    $this->Session->setFlash('Record Saved Successfully ');
                    $this->redirect('payment');
                } else if ($sd_update_result == 0) {
                    $this->Session->setFlash('!SD update Failed');
                } else {
                    $this->Session->setFlash('!No proper Input');
                }
            }
        } else {
            if ($formdata) {
                $this->stamp_duty->id = $formdata['stamp_duty']['token_no'];
                $dataarray = $this->stamp_duty->read();
                $this->request->data['frm'] = $dataarray['stamp_duty'];
            }
        }
        $this->set_csrf_token();
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------

    function payment($id = NULL) {

        try {

            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }


            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect('genernalinfoentry');
            }//load Model

            array_map(array($this, 'loadModel'), array('stamp_duty', 'external_links', 'payment_mode', 'bank_master', 'PaymentPreference', 'CitizenPaymentEntry', 'article_fee_items', 'PaymentFields', 'payment'));
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
            $token = $this->Session->read('Selectedtoken');


            if (is_numeric($id)) {
                if ($this->CitizenPaymentEntry->deleteAll(array('id' => $id, 'token_no' => $token, 'user_id' => $user_id))) {
                    $this->Session->setFlash(__("Record Deleted Successfully"));
                }

                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment'));
            }

            $accounthead = $this->article_fee_items->find("list", array('conditions' => array('fee_param_type_id' => 2), 'fields' => array('fee_item_id', 'fee_item_desc_en')));
            $payment_mode = $this->payment_mode->get_payment_mode_online($lang);
            $payment = $this->CitizenPaymentEntry->get_all_payment($token, $user_id);
            $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('is_transaction_flag' => 'Y'), 'order' => 'srno ASC'));
            $stamp_duty_details = $this->stamp_duty->get_stamp_duty($token, $user_id, $lang, 1); //1 fee Type Id for Online Payment Only
            $payment_url = $this->external_links->find('all', array('conditions' => array('link_id' => 1))); // one for Payment Gateway
//                pr($payment_url);exit;
            $this->set(compact('payment_mode', 'payment_url', 'lang', 'stamp_duty_details', 'payment', 'accounthead', 'paymentfields'));
            $this->set("fieldlist", $fieldlist = $this->PaymentFields->fieldlist());
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //$this->check_csrf_token($this->request->data['payment']['csrftoken']);
                $this->request->data['payment']['state_id'] = $this->Auth->User('state_id');
                $this->request->data['payment']['user_id'] = $user_id;
                //  $this->request->data['payment']['created_date'] = $created_date;
                $this->request->data['payment']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['payment']['token_no'] = $token;
                if (isset($this->request->data['payment']['pdate'])) {
                    $this->request->data['payment']['pdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['pdate'])));
                }
                if (isset($this->request->data['payment']['estamp_issue_date'])) {
                    $this->request->data['payment']['estamp_issue_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['payment']['estamp_issue_date'])));
                }
                $errors = $this->validatedata($this->request->data['payment'], $fieldlist);
                if ($this->ValidationError($errors)) {
                    $this->request->data['payment']['user_type'] = $this->Session->read("session_usertype");

                    if ($this->CitizenPaymentEntry->Save($this->request->data['payment'])) {
                        $this->Session->setFlash(__("Record Saved Successfully"));
                    } else {
                        $this->Session->setFlash(__('Record Not saved'));
                    }
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'payment'));
                } else {
                    $this->set("RequestData", $this->request->data['payment']);
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function witness() {
        try {

            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Please first fill general Information");
                return $this->redirect('genernalinfoentry');
            }
//load Model
            array_map(array($this, 'loadModel'), array('witness', 'identificatontype', 'doc_levels', 'State', 'User', 'partytype', 'TrnBehavioralPatterns', 'witness_type'));
            $popupstatus = $actiontypeval = $hfid = $hfupdateflag = $hfactionval = NULL;
            $tokenval = $Selectedtoken = $this->Session->read("Selectedtoken");
            $language = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $doc_lang = $this->Session->read('doc_lang');
            $witness = $this->witness->get_allwitness($doc_lang, $tokenval, $user_id); //            pr($witness);exit;
            $this->set('witness', $witness);
            $alllevel = $this->doc_levels->get_alllevel();

//set Values
            $this->set(compact('actiontypeval', 'hfid', 'hfupdateflag', 'popupstatus', 'Selectedtoken', 'language', 'witness', 'doclevels'));
            $this->set('salutation', ClassRegistry::init('salutation')->find('list', array('fields' => array('salutation_id', 'salutation_desc_' . $doc_lang), 'order' => array('salutation_desc_' . $doc_lang => 'ASC'))));
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $this->set('gender', ClassRegistry::init('gender')->find('list', array('fields' => array('gender_id', 'gender_desc_' . $doc_lang))));
            $this->set('occupation', ClassRegistry::init('occupation')->find('list', array('fields' => array('occupation_id', 'occupation_name_'.$doc_lang))));
            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $doc_lang))));
            $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('id', 'taluka_name_' . $doc_lang))));
            $this->set('witness_type', ClassRegistry::init('witness_type')->find('list', array('fields' => array('witness_type_id', 'witness_type_desc_' . $doc_lang), 'conditions' => array('display_flag' => 'C'))));
//Status check box code            
            if ($tokenval != NULL) {
                $popupstatus = $this->doc_levels->query('select s.completed_status ,l.status_code from ngdrstab_mst_doc_status s inner join ngdrstab_mst_statuscheck l on s.level_id=l.status_id where s.level_id=l.status_id and s.token_id =' . $tokenval . ' order by l.status_code');
                $this->set('popupstatus', $popupstatus);
            }
            $this->set(compact('lang', 'Selectedtoken', 'popupstatus', 'exemption', 'actiontypeval', 'hfid', 'hfupdateflag', 'hfactionval', 'districtdata', 'taluka', 'party_category', 'occupation', 'gender', 'salutation', 'property', 'property_list', 'property_pattern', 'party_record'));
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
            if ($name_format == 'Y') {
                //list for english single fields
                $fieldlist['fname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['mname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['lname_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['aliasname_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';
                if ($doc_lang != 'en') {
                    //list for all unicode fields
                    $fieldlist['fname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['mname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['lname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['aliasname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['idetification_mark1_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                }
            } else {
                $fieldlist['witness_full_name_en']['text'] = 'is_required,is_alphaspace,is_maxlength20';
                $fieldlist['aliasname_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark1_en']['text'] = 'is_alphaspace,is_maxlength20';
                $fieldlist['idetification_mark2_en']['text'] = 'is_alphaspace,is_maxlength20';
                if ($doc_lang != 'en') {
                    $fieldlist['witness_full_name_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['aliasname_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['idetification_mark1_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                    $fieldlist['idetification_mark2_ll']['text'] = 'unicoderequired_rule_' . $doc_lang;
                }
            }
            $fieldlist['salutation']['select'] = 'is_select_req';
            $fieldlist['occupation_id']['select'] = 'is_select_req';
            $fieldlist['gender_id']['select'] = 'is_select_req';
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
            $fieldlist['identificationtype_id']['select'] = 'is_select_req';
            //$fieldlist['dob']['text'] = 'is_alphanumspace';
            $fieldlist['uid_no']['text'] = 'is_uidnum';
            $fieldlist['age']['text'] = 'is_digit';
            $fieldlist['email_id']['text'] = 'is_email';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian'; //7,8,9 start
            $fieldlist['pan_no']['text'] = 'is_pancard';


            $this->set('fieldlist', $fieldlist);

            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $errarr['identificationtype_desc_en_error'] = '';
            $this->set("errarr", $errarr);

            if ($this->request->is('post')) {
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];

                $hfid = $_POST['hfid'];
                // $this->check_csrf_token($this->request->data['witness']['csrftoken']);
                $uid_compulsary = $this->is_uid_compulsary();
                $identity = $this->is_identity_compulsary();

                $this->set('actiontypeval', $_POST['actiontype']);
                $this->set('hfid', $_POST['hfid']);
                $name_format = $this->get_name_format();
                if ($name_format == 'Y') {
                    $this->request->data['witness']['witness_full_name_en'] = isset($this->request->data['witness']['fname_en']) ? $this->request->data['witness']['fname_en'] . ' ' . $this->request->data['witness']['mname_en'] . ' ' . $this->request->data['witness']['lname_en'] : '';
                    $this->request->data['witness']['witness_full_name_ll'] = isset($this->request->data['witness']['fname_ll']) ? $this->request->data['witness']['fname_ll'] . ' ' . $this->request->data['witness']['mname_ll'] . ' ' . $this->request->data['witness']['lname_ll'] : '';
                }
                if ($actiontype == '1') {

                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {

                        if ($uid_compulsary == 'Y') {
                            if (!$this->request->data['witness']['uid_no']) {
                                $this->Session->setFlash(__('Please Enter UID'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $tokenval));
                            }
                        }
                        if ($identity == 'Y') {
                            if (!$this->request->data['witness']['identificationtype_id'] || $this->request->data['witness']['identificationtype_desc_' . $doc_lang] == '') {
                                $this->Session->setFlash(__('Please Select Identity'));
                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $tokenval));
                            }
                        }
//validations kalyani
                        $this->request->data['witness'] = $this->istrim($this->request->data['witness']);
                        if ($this->request->data['witness']['identificationtype_id']) {
                            $rule = $this->witness->query('select e.error_code from ngdrstab_mst_errorcodes e, ngdrstab_mst_identificationtype i where e.error_code_id=i.error_code_id and i.identificationtype_id=' . $this->request->data['witness']['identificationtype_id']);
                            if ($rule) {
                                $fieldlist['identificationtype_desc_en']['text'] = $rule[0][0]['error_code'];
                            }
                        }


                        $errarr = $this->validatedata($this->request->data['witness'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            if ($this->witness->save_witness($this->request->data['witness'], $tokenval, $stateid, $user_id, $hfid, $this->Session->read("session_usertype"))) {
                                if ($this->request->data['hfupdateflag'] == 'Y') {
                                    $witnessid = $this->request->data['witness']['id'] = $this->request->data['hfid'];
                                    $actionvalue = "Updated";
                                } else {
                                    $witnessid = $this->witness->getLastInsertID();
                                    $actionvalue = "Saved";
                                }
                                if (isset($this->request->data['property_details']['pattern_id'])) {
                                    $this->TrnBehavioralPatterns->deletepattern($tokenval, $user_id, $witnessid, 3);

                                    $this->TrnBehavioralPatterns->savepattern($tokenval, $user_id, $witnessid, $this->request->data['property_details'], 3, $this->Session->read("session_usertype"));
                                }
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
                                $this->redirect(array('action' => 'witness', $tokenval));
                            }
                        }
                    }
                }
                if ($_POST['actiontype'] == 2) {
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $tokenval));
                }
                if ($_POST['actiontype'] == '3') {

                    if ($_POST['hfid'] != NULL) {

                        $this->witness->id = $_POST['hfid'];

                        if ($this->witness->delete()) {
                            $this->Session->setFlash(__('Record Deleted Successfully'));
                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'witness', $tokenval));
                        } else {
                            $this->Session->setFlash(__('Record Not Deleted'));
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
        $this->set_csrf_token();
    }

}
