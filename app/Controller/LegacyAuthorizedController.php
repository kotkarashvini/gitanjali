<?php
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');


class LegacyAuthorizedController extends AppController
{

    public function beforeFilter()
    {
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


    public function authorized()
    {
        try {            
            $this->loadModels('NGDRSErrorCode','Leg_generalinformation','Leg_application_submitted');

            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['authorized_remark']['text'] = 'is_alphaspace';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $gen_info = $this->Leg_generalinformation->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
            $this->set('gen_info', $gen_info);
            if ($this->request->is('post')) {
                $formData = $this->request->data['authorizedctp'];//authorizedctp  is ctp
                $formData['authorized_user_id'] = $this->Auth->user('user_id');
                $formData['authorized_ip'] = $this->RequestHandler->getClientIp();
                $formData['authorized_date'] = date('Y-m-d H:i:s');
                $formData['token_no'] = $this->Session->read('Leg_Selectedtoken');
                $formData['general_info_id'] = $this->Leg_generalinformation->field('general_info_id',array('token_no'=>$formData['token_no']));// as general_info_id is primary key we must provide while updating data.
                if ($this->Leg_generalinformation->save($formData)) {
                    $appSubData = [
                        'final_stamp_flag'=>'Y',
                    ];
                    $this->Leg_application_submitted->id = $this->Leg_application_submitted->field('app_id',array('token_no'=>$formData['token_no']));
                    $this->Leg_application_submitted->save($appSubData);
                    $this->Session->setFlash('Data Added Successfully');
                    return $this->redirect(array('action' => 'authorized'));
                }

            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }

    }
}