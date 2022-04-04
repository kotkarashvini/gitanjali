<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyFeedetailsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();
//
//        if ($this->name == 'CakeError') {
//            $this->layout = 'error';
//        }
        $this->response->disableCache();
        //$this->Auth->allow('physub');
        // $this->Auth->allow('Fee');
    }

    public function fee($csrf = NULL, $fee_calc_id = null) {
        try {
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'Leg_party_entry', 'article_fee_items', 'Leg_fee_calculation', 'Leg_fee_calculation_detail'));
            $party_data = $this->Leg_party_entry->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
            if (empty($party_data)) {
                $this->Session->setFlash(__('Please enter Party Details first.'));
                return $this->redirect('../LegacyPartyDetails/Party');
            }

            $doc_lang = $this->Session->read('doc_lang');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['fee_item_id']['select'] = 'is_select_req';
            $fieldlist['final_value']['text'] = 'is_numeric';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            ob_start();
            $fee_id=array(1,2,3);
           // pr($fee_id);exit;
            $feemaster = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_item_desc_'.$doc_lang), 'conditions' => array('fee_item_id' => $fee_id), 'order' => array('fee_item_desc_'.$doc_lang => 'ASC')));
          // pr($feemaster);exit;
            $this->set('feemaster', $feemaster);
            $token_no = $this->Session->read('Leg_Selectedtoken');
            $fee_data = $this->Leg_fee_calculation->get_fee_deails_info($token_no);
           // pr($fee_data);exit;
            $this->set('fee_data', $fee_data);

            
            if (!is_null($fee_calc_id) && is_numeric($fee_calc_id)) {
             
                $this->Session->write('$fee_calc_id', $fee_calc_id);
               $result = $this->Leg_fee_calculation->find("all", array('conditions' => array('fee_calc_id' => $fee_calc_id,'token_no'=>$this->Session->read('Leg_Selectedtoken'))));
             
                //$result = $this->Leg_fee_calculation_detail->find("first", ['id' => $id, 'token_no' => $this->Session->read('Leg_Selectedtoken')]);
                $this->request->data['fee_detailsctp']['fee_calc_id'] = $result[0]['Leg_fee_calculation']['fee_calc_id'];
           
            }

            if ($this->request->is('post') || $this->request->is('put')) {
              $errarr = $this->validatedata($this->request->data['fee_detailsctp'], $fieldlist);
                    if ($this->validationError($errarr)) {
                if ($_POST['action'] == 'submit_data') {
                    if (is_null($fee_calc_id)) {
                        $this->request->data['fee_detailsctp']['token_no'] = $token_no;
                        if ($this->Leg_fee_calculation->save($this->request->data['fee_detailsctp'])) {
                            $last_fee_calc_id = $this->Leg_fee_calculation->getLastInsertID($token_no);
                            $this->request->data['fee_detailsctp']['fee_calc_id'] = $last_fee_calc_id;
                            $this->Leg_fee_calculation_detail->save($this->request->data['fee_detailsctp']);
                            $this->Session->setFlash('Data Added Successfully');
                            return $this->redirect(array('action' => 'Fee'));
                        } else {
                            $this->Session->setFlash(__('Record not saved.'));
                        }
                    } else if (!is_null($fee_calc_id)) {
                        $this->Leg_fee_calculation_detail->save($this->request->data['fee_detailsctp']);
                        $this->Session->setFlash('Data Added Successfully');
                        return $this->redirect(array('action' => 'Fee'));
                    }
                }


                 }
            } else {
               
                $Selectedtoken=$this->Session->read('Leg_Selectedtoken');
                if (!empty($Selectedtoken)) {
 
                    if (!is_null($fee_calc_id) && is_numeric($fee_calc_id)) {
                        
                        $fee_id=array(1,2,3);
                        $feemaster = $this->article_fee_items->find('list', array('fields' => array('fee_item_id', 'fee_item_desc_'.$doc_lang),'conditions' => array('fee_item_id' => $fee_id), 'order' => array('fee_item_desc_'.$doc_lang => 'ASC')));
                        $this->set('feemaster', $feemaster);
                       
                        //$result = $this->Leg_fee_calculation_detail->find("first", array('conditions' => array('id' => $id)));
                        $fee_cal_detail_id = $this->Leg_party_entry->query("select id from ngdrstab_trn_legacy_fee_calculation_detail where fee_calc_id=$fee_calc_id");
                       
                        $result = $this->Leg_fee_calculation_detail->find("all", array('conditions' => array('id' => $fee_cal_detail_id[0][0]['id'])));
                        //pr($result);exit;
                        $this->request->data['fee_detailsctp']['fee_item_id'] = $result[0]['Leg_fee_calculation_detail']['fee_item_id'];
                        $this->request->data['fee_detailsctp']['final_value'] = $result[0]['Leg_fee_calculation_detail']['final_value'];
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

    public function delete_1($csrf = NULL, $fee_calc_id = null) {
       
        $this->autoRender = false;
        $this->loadModel('Leg_fee_calculation');
        $this->loadModel('Leg_fee_calculation_detail');
        
        try {
            if (isset($fee_calc_id) && is_numeric($fee_calc_id)) {
                
               // if($this->aaaa('FeeDetails',$id)){
                    $fee_calculation_detail_result = $this->Leg_fee_calculation_detail->find("first", array('conditions' => array('id' => $id))); 
                $fee_calc_id=$fee_calculation_detail_result['Leg_fee_calculation_detail']['fee_calc_id'];
                if ($this->Leg_fee_calculation->delete($fee_calc_id)) {
                    $this->Leg_fee_calculation_detail->delete($id);
                    $this->Session->setFlash(__('The Record  has been deleted'));
                    return $this->redirect(array('action' => 'Fee'));
                }
//                }
//                else
//                {
//                    //pr("Sandip");exit;
//                }
                
                
                
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
        }
    }
    
    public function delete($csrf = NULL, $fee_calc_id = null) {
       
        $this->autoRender = false;
        $this->loadModel('Leg_fee_calculation');
        $this->loadModel('Leg_fee_calculation_detail');
        
        try {
            if (isset($fee_calc_id) && is_numeric($fee_calc_id)) {
                    //$fee_calculation_result = $this->Leg_fee_calculation->find("first", array('conditions' => array('fee_calc_id' => $fee_calc_id))); 
                  if($this->Leg_fee_calculation->deleteAll(['fee_calc_id' => $fee_calc_id, 'token_no' => $this->Session->read('Leg_Selectedtoken')]))  {
                      $this->Leg_fee_calculation_detail->deleteAll(['fee_calc_id' => $fee_calc_id]);
                   // $this->Leg_fee_calculation_detail->delete($id);
                    $this->Session->setFlash(__('The Record  has been deleted'));
                    return $this->redirect(array('action' => 'fee'));
                } 
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
        }
    }
    
    
    public function aaaa($tabnm, $id ) {
        
        $office_id=$this->Auth->user('office_id');
        $token=$this->Session->read('Leg_Selectedtoken');
        
        if($tabnm=='FeeDetails'){
        $tab_feedetails = $this->Leg_fee_calculation->query("
 select * from ngdrstab_trn_legacy_fee_calculation  
 inner join ngdrstab_trn_legacy_generalinformation on ngdrstab_trn_legacy_generalinformation.token_no=ngdrstab_trn_legacy_fee_calculation.token_no
 inner join ngdrstab_trn_legacy_fee_calculation_detail on ngdrstab_trn_legacy_fee_calculation_detail.fee_calc_id=ngdrstab_trn_legacy_fee_calculation.fee_calc_id
 where ngdrstab_trn_legacy_fee_calculation_detail.id=$id and office_id=$office_id and ngdrstab_trn_legacy_fee_calculation.token_no=$token");
        if(!empty($tab_feedetails)){
             return  true;
        }else{
             return false;
        }
        }else if($tabnm=='Property'){
            
        }else{
            return false;
        }
        
    }

}
