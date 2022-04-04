<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::import('Controller', 'MHWebService'); // mention at top
App::import('Controller', 'PBWebService'); // mention at top
App::import('Controller', 'JHWebService'); // mention at top
App::import('Controller', 'GAWebService'); // mention at top
App::import('Controller', 'TRWebService'); // mention at top

class WebServiceController extends AppController {

    /**
     * GrasVerification Common Webservice for Verify GRAS
     *
     * @param	array  Request Data	
     * @param	array  extrafields ( token_no:Optional  )
     */
    public function GrasVerification($data = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Gras Verification  Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 27:
                $serviceobj = new MHWebServiceController();
                break;
            case 20:
                $serviceobj = new JHWebServiceController();
                break;
            case 30:
                $serviceobj = new TRWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->gras_payment_verification($data, $extrafields);
        return $response;
    }

    /**
     * GrasVerification Common Webservice for Verify GRAS
     *
     * @param	array  Payment Record	
     * @param	array  extrafields ( user_id:Required,remark:Required)
     */
    public function GrasDeface($payment = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'GrasDeface Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 27:
                $serviceobj = new MHWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->gras_payment_defacement($payment, $extrafields);
        return $response;
    }

    public function GrasPaymentReceipt($payment = NULL, $extrafields = NULL) {

        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Gras  Payment Receipt Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 27:
                $serviceobj = new MHWebServiceController();
                break;
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->gras_payment_receipt($payment, $extrafields);
        return $response;
    }

    /**
     * EstampVerification Common Webservice for Verify Estamp
     *
     * @param	array  Request Data	
     * @param	array  extrafields ( token_no:Optional  )
     */
    public function EstampVerification($data = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Estamp Verification Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
           
            case 20:
                $serviceobj = new JHWebServiceController();
                break;
            case 30:
                $serviceobj = new TRWebServiceController();
                break;
           
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->estamp_certificate_verification($data, $extrafields);
        return $response;
    }

    /**
     * EstampLock Common Webservice for lock Estamp
     *
     * @param	array  Payment details	
     * @param	array  extrafields ( user_id:required , docregno:optional )
     */
    public function EstampLock($payment = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Estamp Lock Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
            case 20:
                $serviceobj = new JHWebServiceController();
                break;
            case 30:
                $serviceobj = new TRWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->estamp_certificate_lock($payment, $extrafields);
        return $response;
    }

    public function ERegistrationVerification($data = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'ERegistration Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->eregistration_certificate_verification($data, $extrafields);
        return $response;
    }

    /**
     * ERegistrationLock Common Web service for verify  ERegistration
     *
     * @param	array  Payment Record	
     * @param	array  extrafields ( token number )
     */
    public function ERegistrationLock($payment = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'ERegistration Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
            default: return $response;
        }

        $serviceobj->constructClasses();
        $response = $serviceobj->eregistration_certificate_lock($payment, $extrafields);
        return $response;
    }

    public function EchallanVerification($data = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Echallan  Payment  Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 31:
                $serviceobj = new GAWebServiceController();
                break;
            default: return $response;
        }
        $serviceobj->constructClasses();
        $response = $serviceobj->EchallanVerification($data, $extrafields);
        return $response;
    }

    public function Mutation($token_no) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Mutation   Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 31:
                $serviceobj = new GAWebServiceController();
                break;
            default: return $response;
        }
        $serviceobj->constructClasses();
        $response = $serviceobj->mutation($token_no);
        return $response;
    }

    public function Pan_verification($pannumber) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'Mutation  Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 20:
                $serviceobj = new JHWebServiceController();
                break;
            default: return $response;
        }
        $serviceobj->constructClasses();
        $response = $serviceobj->pan_verification($pannumber);
        return $response;
    }

    public function PayuPayment($data = NULL, $extrafields = NULL) {
        $stateid = $this->Auth->user('state_id');
        $response['Error'] = 'PayU Payment  Web Service Not Configured For State ID :' . $stateid;
        switch ($stateid) {
            case 4:
                $serviceobj = new PBWebServiceController();
                break;
            default: return $response;
        }
        $serviceobj->constructClasses();
        $response = $serviceobj->PayuPayment($data, $extrafields);
        return $response;
    }

    /*
     * *********************************** Payment Gate Way UI ************************************************************        
     */

    public function payu_payment_entry($transid = NULL) {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');


        if (!is_null($transid)) {
            $result = $this->BankPayment->find("all", array(
                'fields' => array('info.token_no', 'info.article_id', 'BankPayment.payment_mode_id'),
                'joins' => array(
                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'conditions' => array('info.token_no=BankPayment.token_no')),
                ),
                'conditions' => array('BankPayment.transaction_id' => $transid)
            ));
            if (empty($result)) {
                $this->Session->setFlash(
                        __('Token Number Not Available!')
                );
                $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
            }
            // pr($result);
            $data['bank_trn_id'] = $transid;
            $data['payment_mode_id'] = $result[0]['BankPayment']['payment_mode_id'];

            $extrafields['token_no'] = $result[0]['info']['token_no'];
            $extrafields['article_id'] = $result[0]['info']['article_id'];
            $extrafields['lang'] = 'en';
            $responce = $this->PayuPayment($data, $extrafields);
            if (isset($responce['Error']) && !empty($responce['Error'])) {
                $status_arr = explode(":", $responce['Error']);
                if (isset($status_arr[1])) {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set payment_status=? where token_no=?  and transaction_id=?", array(strtoupper(trim($status_arr[1])), $extrafields['token_no'], $transid));
                }

                $this->Session->setFlash(
                        __('' . $responce['Error'])
                );
                $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
            } else {
                $resdata = $responce['OnlinePaymentData'];
                $usertype = $this->Session->read("session_usertype");
                $now = date('Y-m-d H:i:s');
                if ($usertype == 'C') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,user_type=?,gateway_trans_id=? where token_no=?  and transaction_id=?", array($resdata['verification_number'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['certificate_no'], $resdata['token_no'], $resdata['bank_trn_id']));
                } elseif ($usertype == 'O') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=? where token_no=? and transaction_id=?", array($resdata['verification_number'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['certificate_no'], $resdata['token_no'], $resdata['bank_trn_id']));
                }
                $this->Session->setFlash(
                        __('Payment Status Updated')
                );
                $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
            }
        }

        $mapping = $this->BankPayment->mapping_account_heads(10, 4);

        $hash = '';
        $hash_string = '';
        $action = '';
        $txnid = '';
        // Merchant key here as provided by Payu
        $MERCHANT_KEY = ""; //Please change this value with live key for production        
        // Merchant Salt as provided by Payu
        $SALT = ""; //Please change this value with live salt for production
        // End point - change to https://secure.payu.in for LIVE mode
        $PAYU_BASE_URL = "";

        $fieldlist = array();

        // $fieldlist['key']['text'] = 'is_required';
        $fieldlist['surl']['text'] = 'is_required';
        $fieldlist['furl']['text'] = 'is_required';
        $fieldlist['curl']['text'] = 'is_required';
        $fieldlist['udf1']['text'] = 'is_required,is_numeric';
        $fieldlist['firstname']['text'] = 'is_required,is_alpha';
        $fieldlist['lastname']['text'] = 'is_required,is_alpha';
        $fieldlist['email']['text'] = 'is_required,is_email';
        $fieldlist['phone']['text'] = 'is_required,is_phone';
        $fieldlist['productinfo']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['amount']['text'] = 'is_required,is_numeric';
        //$fieldlist['address1']['text'] = 'is_required,is_alphanumericspace';
        // $fieldlist['address2']['text'] = 'is_required';
        //$fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        // $fieldlist['state']['text'] = 'is_alphanumericspace';
        //$fieldlist['country']['text'] = 'is_alphanumericspace';
        // $fieldlist['zipcode']['text'] = 'is_pincode';

        $i = 1;
        foreach ($mapping as $map) {
            $i++;
            $fieldlist['udf' . $i]['text'] = 'is_required,is_numeric';
        }


        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));


        if ($this->request->is('post')) {
            $errarr = $this->validatedata($_POST, $fieldlist);

            if ($this->ValidationError($errarr)) {
                $result = $this->genernalinfoentry->find("all", array(
                    'conditions' => array('token_no' => trim($_POST['udf1']))
                ));
                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                }
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 10)));
                //pr($bankapi);
                //  exit;
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }
                // Merchant key here as provided by Payu
                $MERCHANT_KEY = $bankapi['interface_user_id']; //Please change this value with live key for production        
                // Merchant Salt as provided by Payu
                $SALT = $bankapi['interface_password']; //Please change this value with live salt for production
                // End point - change to https://secure.payu.in for LIVE mode
                $PAYU_BASE_URL = $bankapi['interface_url'];

                // pr($_POST);exit;
                $posted = array();
                if (!empty($_POST)) {
                    foreach ($_POST as $key => $value) {
                        $posted[$key] = $value;
                    }
                }
                $posted['key'] = $MERCHANT_KEY;
                if (empty($posted['txnid'])) {
                    // Generate random transaction id
                    do {
                        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                        $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                    } while (!empty($check));
                    $amt = 0;
                    if (isset($mapping)) {
                        $i = 1;
                        foreach ($mapping as $map) {
                            $i++;
                            if (isset($posted['udf' . $i]) && is_numeric($posted['udf' . $i])) {
                                $amt += $posted['udf' . $i];
                                $posted['udf' . $i] = $map[0]['account_head_code'] . "|" . $posted['udf' . $i];
                            }
                        }
                    }


                    $posted['amount'] = $amt;
                    $posted['amount'] = number_format($posted['amount'], 2, ".", "");
                    $posted['txnid'] = $txnid;

                    $savedata['payment_mode_id'] = 10;
                    $savedata['payment_status'] = 'CREATED';
                    $savedata['payee_fname_en'] = $posted['firstname'];
                    $savedata['payee_lname_en'] = $posted['lastname'];
                    $savedata['pamount'] = $posted['amount'];
                    $savedata['email_id'] = $posted['email'];
                    $savedata['mobile'] = $posted['phone'];
                    $savedata['transaction_id'] = $txnid;
                    $savedata['token_no'] = $posted['udf1'];

                    $usertype = $this->Session->read("session_usertype");
                    $savedata['user_type'] = $usertype;
                    $now = date('Y-m-d H:i:s');
                    if ($usertype == 'C') {
                        $savedata['user_id'] = $this->Auth->user('user_id');
                        $savedata['created'] = $now;
                    } elseif ($usertype == 'O') {
                        $savedata['org_user_id'] = $this->Auth->user('user_id');
                        $savedata['org_created'] = $now;
                    }
                    if ($this->BankPayment->save($savedata)) {
                        // Hash Sequence
                        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

                        $hashVarsSeq = explode('|', $hashSequence);

                        foreach ($hashVarsSeq as $hash_var) {
                            $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                            $hash_string .= '|';
                        }

                        $hash_string .= $SALT;



                        $hash = strtolower(hash('sha512', $hash_string));
//                        $myfile = fopen("D:/xyz.txt", "w");
//                        fwrite($myfile, $hash_string . PHP_EOL . $hash);
//                        fclose($myfile);
                        $action = $PAYU_BASE_URL;
                    }
                } else {
                    $txnid = $posted['txnid'];
                }
            } else {
                $this->Session->setFlash(
                        __('Please Check Validations')
                );
            }
        } //post



        $usertype = $this->Session->read("session_usertype");
        $result = array();
        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 10),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('org_user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 10),
                'order' => array('trn_id DESC'),
            ));
        }



        $this->set(compact('action', 'hash', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping'));
    }

    public function payu_payment_responce() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');

        if ($this->request->is('post')) {
            $rdata = $this->request->data;

            if (isset($rdata['status'])) {
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 10)));

                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                // Merchant Salt as provided by Payu
                $SALT = $bankapi['interface_password']; //Please change this value with live salt for production
                // Hash Sequence
                //$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
                $hashSequence = "status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key";
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
                $hash_string .= $SALT;
                foreach ($hashVarsSeq as $hash_var) {
                    $hash_string .= '|';
                    $hash_string .= isset($rdata[$hash_var]) ? $rdata[$hash_var] : '';
                }

                //pr($hash_string);exit;
                $rehash = strtolower(hash('sha512', $hash_string));

//                $myfile = fopen("D:/xyz1.txt", "w");
//                fwrite($myfile, $hash_string . PHP_EOL . $rehash . PHP_EOL . $rdata['hash']);
//                fclose($myfile);

                if (strcmp($rehash, $rdata['hash']) != 0) {
                    $this->Session->setFlash(
                            __('Something went wrong in response: Parameter missmatch')
                    );
                    $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
                }
                $usertype = $this->Session->read("session_usertype");
                $userid = $this->Auth->user('user_id');
                $now = date('Y-m-d H:i:s');
                if (strtolower($rdata['status']) == 'success') {
                    if ($usertype == 'C') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set user_id=?,     bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,    user_type=?,gateway_trans_id=? ,error_code=?, error_message=? where token_no=? and transaction_id=?", array($userid, $rdata['bank_ref_num'], $rdata['net_amount_debit'], 'SUCCESS', $rdata['addedon'], $now, $usertype, $rdata['mihpayid'], $rdata['error'], $rdata['error_Message'], $rdata['udf1'], $rdata['txnid']));
                    } elseif ($usertype == 'O') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set org_user_id=?, bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=? ,error_code=?, error_message=? where token_no=? and transaction_id=?", array($userid, $rdata['bank_ref_num'], $rdata['net_amount_debit'], 'SUCCESS', $rdata['addedon'], $now, $usertype, $rdata['mihpayid'], $rdata['error'], $rdata['error_Message'], $rdata['udf1'], $rdata['txnid']));
                    }
                } else {
                    if ($usertype == 'C') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set user_id=?,     bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,    user_type=?,gateway_trans_id=? ,error_code=?, error_message=? where token_no=? and transaction_id=?", array($userid, $rdata['bank_ref_num'], $rdata['net_amount_debit'], strtoupper(trim($rdata['status'])), $rdata['addedon'], $now, $usertype, $rdata['mihpayid'], $rdata['error'], $rdata['error_Message'], $rdata['udf1'], $rdata['txnid']));
                    } elseif ($usertype == 'O') {
                        $this->BankPayment->query("update ngdrstab_trn_bank_payment set org_user_id=?, bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=? ,error_code=?, error_message=? where token_no=? and transaction_id=?", array($userid, $rdata['bank_ref_num'], $rdata['net_amount_debit'], strtoupper(trim($rdata['status'])), $rdata['addedon'], $now, $usertype, $rdata['mihpayid'], $rdata['error'], $rdata['error_Message'], $rdata['udf1'], $rdata['txnid']));
                    }
                }
                $this->Session->setFlash(
                        __('')
                );
                $this->set("rdata", $rdata);
            }
        } else {
            $this->Session->setFlash(
                    __('Something Went Wrong')
            );
            $this->redirect(array('controller' => 'WebService', 'action' => 'payu_payment_entry'));
        }
    }

    function check_payment() {
        $this->autoRender = FALSE;


        if (isset($_POST['csrftoken'])) {
            $this->loadModel('BankPayment');
            $this->loadModel('tatkal_app_config');

            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $payment = $this->BankPayment->find('all', array('conditions' => array('token_no' => $this->Session->read("Selectedtoken"), 'payment_status' => 'SUCCESS')));
            $paid_amt = 0;
            foreach ($payment as $pay) {

                $data['bank_trn_id'] = $pay['BankPayment']['transaction_id'];
                $data['payment_mode_id'] = $pay['BankPayment']['payment_mode_id'];
                $extrafields['token_no'] = $pay['BankPayment']['token_no'];
                $extrafields['article_id'] = $this->Session->read("article_id");
                $extrafields['lang'] = 'en';
                $responce = $this->PayuPayment($data, $extrafields);


                if (!empty($responce)) {


                    $paid_amt = $paid_amt + $responce['OnlinePaymentData']['pamount'];
                }
            }

            $tatkal_amt = $this->tatkal_app_config->find('first');
            if (!empty($tatkal_amt)) {
                $amount = $tatkal_amt['tatkal_app_config']['amount'];
            } else {
                $amount = 0;
            }
            if ($paid_amt >= $amount) {
                echo 1;
            } else {
                echo 0;
            }
//                    
        }
    }

}
