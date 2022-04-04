<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyPartydetailsController extends AppController {

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

    public function party($csrf = NULL, $id = null) {
        try {
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'Leg_property_details_entry', 'partytype', 'party_category', 'gender', 'Leg_party_entry','Leg_generalinformation'));
            $prop_data = $this->Leg_property_details_entry->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
//            if (empty($prop_data)) {
//                $this->Session->setFlash(__('Please enter Property Details first.'));
//                return $this->redirect('../LegacyPropertydetails/property');
//            }
           // $lang = $this->Session->read("sess_langauge");
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['party_type_id']['select'] = 'is_required,is_select_req';
            $fieldlist['party_catg_id']['select'] = 'is_required,is_select_req';
            $fieldlist['party_full_name_en']['text'] = 'is_required,is_alphaspace';
            $fieldlist['age']['text'] = 'is_blankdotnumber';//'is_age_limit' ;//'is_numeric';
            $fieldlist['uid']['text'] = 'is_uidnum';
            $fieldlist['pan_no']['text'] = 'is_pancard';
            $fieldlist['father_full_name_en']['text'] = 'is_alphaspace';
            $fieldlist['gender_id']['text'] = 'is_required,is_select_req';
            $fieldlist['address_en']['text'] = 'is_required,is_address_field';
            $fieldlist['pin_code']['text'] = '';
            
            //////////////////////
            
            $laug = $this->Session->read("sess_langauge");
           // pr($laug);exit;
            $this->set('laug', $laug);
            $doc_lang = $this->Session->read('doc_lang');
             if ($doc_lang != 'en') {
                    //list for all unicode fields
                    $fieldlist['party_full_name_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['father_full_name_ll']['text'] = 'unicode_rule_' . $doc_lang;
                    $fieldlist['address_ll']['text'] = 'unicode_rule_' . $doc_lang;

                }
            
            ///////////////////////
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));





            $token_no = $this->Session->read('Leg_Selectedtoken');
            $article_id = $this->Leg_generalinformation->get_article_id($token_no);
           // pr($article_id);exit;
            $this->Session->write('article_id', $article_id[0][0]['article_id']);
             //$partytype = $this->partytype->get_party_typename($doc_lang,$article_id[0][0]['article_id']);
            $partytype = $this->partytype->get_party_typename($article_id[0][0]['article_id']);
          //  pr($partytype);exit;
            $this->set('partytype', $partytype);

            $party_category = $this->party_category->find('list', array('fields' => array('category_id', 'category_name_'.$doc_lang), 'order' => array('category_name_en' => 'ASC')));
            $this->set('party_category', $party_category);

            $gender = $this->gender->find('list', array('fields' => array('gender_id', 'gender_desc_'.$doc_lang), 'order' => array('gender_desc_'.$doc_lang => 'ASC')));
            $this->set('gender', $gender);

            $partydata = $this->Leg_party_entry->query("select ngdrstab_trn_legacy_party_entry_new.id,party_type_desc_$doc_lang,category_name_$doc_lang, party_full_name_en,party_full_name_ll,father_full_name_en,father_full_name_ll,gender_id from 
ngdrstab_trn_legacy_party_entry_new
inner join ngdrstab_mst_party_type on ngdrstab_mst_party_type.party_type_id=ngdrstab_trn_legacy_party_entry_new.party_type_id
left join ngdrstab_mst_party_category on ngdrstab_mst_party_category.category_id=ngdrstab_trn_legacy_party_entry_new.party_catg_id
                    where token_no='" . $token_no . "'");
            $this->set('partydata', $partydata);

            if (!is_null($id) && is_numeric($id)) {
                $this->Session->write('id', $id);
               // $result = $this->Leg_party_entry->find("first", array('conditions' => array('id' => $id)));
                $result = $this->Leg_party_entry->find("all", array('conditions' => array('id' => $id, 'token_no' => $this->Session->read('Leg_Selectedtoken'))));
                $this->request->data['Party_details']['id'] = $result[0]['Leg_party_entry']['id'];
                
            }

            if ($this->request->is('post') || $this->request->is('put')) {
               // pr($this->request->data);exit;
                $errarr = $this->validatedata($this->request->data['Party_details'], $fieldlist);
                if ($this->validationError($errarr)) {
                    $this->request->data['Party_details']['token_no'] = $token_no;
                    if($this->request->data['Party_details']['age']=='')
                    {
                        $this->request->data['Party_details']['age']=0;
                    }
                    
                    if ($this->Leg_party_entry->save($this->request->data['Party_details'])) {
                        $this->Session->setFlash(__('Record saved Successful.'));
                        return $this->redirect(array('action' => 'Party'));
                    } else {
                        $this->Session->setFlash(__('Record not saved.'));
                    }
                }
            } else {
                if (!is_null($id) && is_numeric($id)) {
                    $result = $this->Leg_party_entry->find("first", array('conditions' => array('id' => $id)));
                    $this->request->data['Party_details']['party_type_id'] = $result['Leg_party_entry']['party_type_id'];
                    $this->request->data['Party_details']['party_catg_id'] = $result['Leg_party_entry']['party_catg_id'];
                    $this->request->data['Party_details']['party_full_name_en'] = $result['Leg_party_entry']['party_full_name_en'];
                    $this->request->data['Party_details']['father_full_name_en'] = $result['Leg_party_entry']['father_full_name_en'];
                    $this->request->data['Party_details']['gender_id'] = $result['Leg_party_entry']['gender_id'];
                    $this->request->data['Party_details']['address_en'] = $result['Leg_party_entry']['address_en'];
                     $this->request->data['Party_details']['age'] = $result['Leg_party_entry']['age'];
                    $this->request->data['Party_details']['uid'] = $result['Leg_party_entry']['uid'];
                    $this->request->data['Party_details']['pan_no'] = $result['Leg_party_entry']['pan_no'];
                    $this->request->data['Party_details']['pin_code'] = $result['Leg_party_entry']['pin_code'];
                    if($doc_lang!='en')
                        {
                        $this->request->data['Party_details']['party_full_name_ll'] = $result['Leg_party_entry']['party_full_name_ll'];
                    $this->request->data['Party_details']['father_full_name_ll'] = $result['Leg_party_entry']['father_full_name_ll'];
                        $this->request->data['Party_details']['address_ll'] = $result['Leg_party_entry']['address_ll'];
                        }
                    
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete($csrf = NULL, $id = null) {
        //pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('Leg_party_entry');
        try {

            if (isset($id) && is_numeric($id)) {
                $this->Leg_party_entry->id = $id;
                //if ($this->Leg_party_entry->delete($id)) {
                if ($this->Leg_party_entry->deleteAll(['id' => $id, 'token_no' => $this->Session->read('Leg_Selectedtoken')])) {
                    $this->Session->setFlash(__('The Record  has been deleted'));
                    return $this->redirect(array('action' => 'party'));
                }
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
        }
    }

}
