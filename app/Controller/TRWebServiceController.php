<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TRWebServiceController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
//        $this->response->disableCache();
        $this->Auth->allow('sbiepay_failure', 'sbiepay_push', 'sbiepay', 'sbiepay_success');
        $this->request->addDetector('ssl', array('callback' => function() {
                return CakeRequest::header('X-Forwarded-Proto') == 'https';
            }));
    }

    public function grass_payment($transid = NULL) {
        try {


            $this->loadModel('BankPayment');
            $this->loadModel('external_interface');
            $this->loadModel('genernalinfoentry');
            $this->loadModel('file_config');
            $this->loadModel('office');

            $action = '';
            $txnid = '';
            $enc_val = '';
            $hash = '';
            $PAYU_BASE_URL = "";
            $requestparam = "";

            $fieldlist = array();


            $fieldlist['token_no']['text'] = 'is_required,is_digit';
            $fieldlist['Fullname']['text'] = 'is_required,is_alphanumericspace';
            $fieldlist['Cityname']['text'] = 'is_required,is_alphanumericspace';
            $fieldlist['Address']['text'] = 'is_required,is_alphanumericspace';
            $fieldlist['Securityphone']['text'] = 'is_required,is_mobileindian';
            $fieldlist['registration_fee_amount']['text'] = 'is_required,is_numeric';
            $fieldlist['processing_fee_amount']['text'] = 'is_required,is_numeric';
            $fieldlist['TotalAmount']['text'] = 'is_required,is_numeric';


            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $data = $_POST;



                $errarr = $this->validatedata($data, $fieldlist);

                if ($this->ValidationError($errarr)) {


                    $result_office = $this->genernalinfoentry->find("first", array(
                        'joins' => array(
                            array('table' => 'ngdrstab_mst_office', 'type' => 'INNER', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                        ),
                        'fields' => array('office.gras_office_code', 'office.gras_treasury_code', 'office.gras_sub_treasury_code', 'genernalinfoentry.token_no'),
                        'conditions' => array('token_no' => trim($data['token_no']))
                    ));

                    if (empty($result_office)) {
                        $this->Session->setFlash(
                                __('Token Not Found')
                        );
                        $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                    }

                    $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 32)));

                    if (empty($bankapi)) {
                        $this->Session->setFlash(
                                __('Bank Api Not Found')
                        );
                        $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                    } else {
                        $bankapi = $bankapi['external_interface'];
                    }

                    $PAYU_BASE_URL = $bankapi['interface_url'];
                    //$PAYU_BASE_URL='http://10.153.8.105/NGDRS_TR/TRWebService/grass_response';


                    if (empty($data['transaction_id'])) {
                        do {
                            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                            $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                        } while (!empty($check));




                        $savedata['payment_mode_id'] = 1;
                        $savedata['transaction_id'] = $txnid;
                        $savedata['payee_fname_en'] = $data['Fullname'];
                        $savedata['mobile'] = $data['Securityphone'];
                        $savedata['address'] = $data['Address'];
                        $savedata['city'] = $data['Cityname'];


                        $savedata['pamount'] = number_format($data['TotalAmount'], 2, ".", "");
                        $savedata['payment_status'] = 'CREATED';
                        $savedata['token_no'] = $data['token_no'];


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


                        $savedata['udf1'] = $result_office['office']['gras_office_code'];
                        $savedata['udf2'] = $result_office['office']['gras_treasury_code'];
                        $savedata['udf3'] = $result_office['office']['gras_sub_treasury_code'];

                        $data['SCHEMECOUNT'] = 0;
                        $savedata['account_head_desc'] = '';
                        $totalamount = 0;
                        if (is_numeric($data['registration_fee_amount']) && $data['registration_fee_amount'] > 0) {
                            $data['SCHEMECOUNT'] += 1;
                            $data['SCHEMENAME' . $data['SCHEMECOUNT']] = '0030-03-104-06-01'; // Reg Fee     
                            $data['FEEAMOUNT' . $data['SCHEMECOUNT']] = $data['registration_fee_amount'];
                            $savedata['account_head_desc'] .= '#0030031040601' . '|Reg Fee|' . $data['registration_fee_amount'];
                            $totalamount += $data['registration_fee_amount'];
                        }
                        if (is_numeric($data['processing_fee_amount']) && $data['processing_fee_amount'] > 0) {
                            $data['SCHEMECOUNT'] += 1;
                            $data['SCHEMENAME' . $data['SCHEMECOUNT']] = '0030-03-800-06-50';
                            $data['FEEAMOUNT' . $data['SCHEMECOUNT']] = $data['processing_fee_amount'];
                            $savedata['account_head_desc'] .= '#0030038000650' . '|Proccessing Fee|' . $data['processing_fee_amount']; // Proccessing fee   
                            $totalamount += $data['processing_fee_amount'];
                        }

                        $data['TotalAmount'] = number_format($totalamount, 2, ".", "");
                        $savedata['account_head_desc'] = substr($savedata['account_head_desc'], 1);


                        if ($this->BankPayment->save($savedata)) {
                            // Hash Sequence
                            //pr($bankapi);exit;
                            $data['DEPTID'] = $bankapi['interface_user_id'];
                            $data['SECURITYCODE'] = $bankapi['secure_key'];
                            $data['DEPTTRANID'] = $txnid;
                            $data['DTO'] = $result_office['office']['gras_treasury_code'];
                            $data['STO'] = $result_office['office']['gras_sub_treasury_code'];
                            $data['DDO'] = $result_office['office']['gras_office_code'];
                            $data['Officename'] = $data['DTO'] . $data['STO'] . $data['DDO'];
                            $data['Bank'] = '0001509'; //fixed- provided by NIC;
                            $data['Remarks'] = 'Fees to  register document';

                            $data['ptype'] = 'N';


                            $data['Deptcode'] = $bankapi['department_id'];
                            $data['UserID'] = $bankapi['interface_user_id'];
                            $data['Applicationnumber'] = $txnid;

                            $data['UURL'] = Router::url('/', true) . 'TRWebService/grass_response';
//pr($data);
//exit;
                            if (date('m') <= 3) {//Upto June 2014-2015
                                $financial_year = (date('y') - 1) . '' . date('y');
                            } else {//After June 2015-2016
                                $financial_year = date('y') . '' . (date('y') + 1);
                            }

                            $data['ChallanYear'] = $financial_year;

                            $hashSequence = "DTO|STO|DDO|Deptcode|UserID|Applicationnumber|Fullname|Securityphone|TotalAmount|SCHEMECOUNT|SCHEMENAME1|FEEAMOUNT1|UURL";
                            $hashVarsSeq = explode('|', $hashSequence);
                            $hash_string = '';
                            foreach ($hashVarsSeq as $hash_var) {
                                $hash_string .= '|';
                                if (isset($data[$hash_var]) && trim($data[$hash_var]) != '') {
                                    $hash_string .= $data[$hash_var];
                                } else {
                                    $hash_string .= 'NA';
                                }
                                // $hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : 'NA';
                            }
                            if (!empty($hash_string)) {
                                $hash_string = substr($hash_string, 1);
                            }
                            // pr($data);
                            // pr($hash_string);exit;
                            $hash = hash_hmac('sha256', $hash_string, $bankapi['secure_key'], true);
                            $hash = base64_encode($hash);
                            $data['hash'] = $hash;
                            $action = $PAYU_BASE_URL;
                            $posted = $data;
                        }
                    }
                } else {
                    $this->Session->setFlash(
                            __('Please Check Validations')
                    );
                }
            } //post


            if (!is_null($transid)) {

                $BankPayment = $this->BankPayment->find("first", array(
                    'fields' => array('BankPayment.token_no', 'info.article_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'info', 'type' => 'INNER', 'conditions' => array('info.token_no = BankPayment.token_no'))
                    ),
                    'conditions' => array('transaction_id' => $transid, 'payment_mode_id' => 1),
                    'order' => array('trn_id DESC'),
                        )
                );
                //pr($BankPayment);exit;
                if (!empty($BankPayment)) {
                    $data['bank_trn_id'] = $transid;
                    $data['payment_mode_id'] = 1;
                    $param['token_no'] = $BankPayment['BankPayment']['token_no'];
                    $param['article_id'] = $BankPayment['info']['article_id'];
                    $param['lang'] = 'en';
                    $param['StatusOnly'] = 'Y';
                    // pr($param);exit;
                    $result = $this->gras_payment_verification($data, $param);
                    if (empty($result['Error']) && isset($result['OnlinePaymentData'])) {
                        //pr($result);exit;
                        $update = $this->BankPayment->query("update ngdrstab_trn_bank_payment set  payment_status=? , bank_trn_ref_number=? , gateway_trans_id=?, pdate=? where transaction_id=?  ", array('SUCCESS', $result['OnlinePaymentData']['cin_no'], $result['OnlinePaymentData']['grn_no'], $result['OnlinePaymentData']['pdate'], $transid));
                        if (empty($update)) {
                            $this->Session->setFlash(
                                    __('Payment verified successfully')
                            );
                            $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                        } else {
                            $this->Session->setFlash(
                                    __('Failed to update Payment status')
                            );
                            $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                        }
                    } else {

                        if (isset($result['Status'])) {
                            $update = $this->BankPayment->query("update ngdrstab_trn_bank_payment set  payment_status=?  where transaction_id=?  ", array($result['Status'], $transid));
                        }
                        $this->Session->setFlash(
                                __('Error : ' . $result['Error'])
                        );
                        $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                    }
                }
            }





            $usertype = $this->Session->read("session_usertype");
            $result = array();

            if ($usertype == 'C') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 1),
                    'order' => array('trn_id DESC'),
                        )
                );
            }
            if ($usertype == 'O') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('org_user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 1),
                    'order' => array('trn_id DESC'),
                ));
            }

            $this->set(compact('action', 'hash', 'posted', 'result'));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Something Went Wrong')
            );
        }
    }

    public function grass_response() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );

        try {
            $this->loadModel('BankPayment');
            $this->loadModel('external_interface');


            if ($this->request->is('post')) {
                $responseparam = $this->request->data;
                                
                if (!empty($responseparam) && count($responseparam) > 1) {
                    $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 32)));

                    if (empty($bankapi)) {
                        $this->Session->setFlash(
                                __('Bank Api Not Found')
                        );
                        $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                    } else {
                        $bankapi = $bankapi['external_interface'];
                    }
                    if (is_array($responseparam)) {
                        $resmst = explode("|", 'Applicationnumber|amount|GRN|status|CIN|tdate|payment_type|bankcode');
                        $resmst_str = '';
                        foreach ($resmst as $key => $val) {
                            $resmst_str .= '|' . $responseparam[$val];
                        }
                        $resmst_str = substr($resmst_str, 1);
                        $hash = hash_hmac('sha256', $resmst_str, $bankapi['secure_key'], true);
                        $hash = base64_encode($hash);
                        if (strcmp($hash, $responseparam['hash']) == 0) {
                            $usertype = $this->Session->read("session_usertype");
                            $userid = $this->Auth->user('user_id');
                            $now = date('Y-m-d H:i:s');
                            if ($responseparam['tdate'] == 'NA' || empty($responseparam['tdate'])) {
                                $responseparam['tdate'] = NULL;
                            } else {
                                $datedb_arr = explode("/", $responseparam['tdate']);
                                $responseparam['tdate'] = $datedb_arr[2] . "-" . $datedb_arr[1] . "-" . $datedb_arr[0];
                            }
                            $this->BankPayment->query("update ngdrstab_trn_bank_payment set  bank_trn_ref_number=?,gateway_trans_id=?,payment_status=?,pdate=?,org_updated=?  where  transaction_id=?", array($responseparam['CIN'], $responseparam['GRN'], strtoupper(trim($responseparam['status'])), $responseparam['tdate'], $now, $responseparam['Applicationnumber']));
                            $result = $this->BankPayment->find('first', array('conditions' => array('transaction_id' => $responseparam['Applicationnumber'])));
                            $this->set("result", $result);
                        } else {
                            $this->Session->setFlash(
                                    __('Something Went Wrong:5')
                            );
                            $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Something Went Wrong:4')
                        );
                        $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                    }
                } else {
                    $this->Session->setFlash(
                            __('Something Went Wrong:3 - '.@$responseparam['status'])
                    );
                    $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
                }
            } else {
                $this->Session->setFlash(
                        __('Something Went Wrong:2')
                );
                $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Something Went Wrong:1')
            );
            $this->redirect(array('controller' => 'TRWebService', 'action' => 'grass_payment'));
        }
    }

    public function gras_payment_verification($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $certid = trim(@$data['bank_trn_id']);

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($certid)) {
                $response['Error'] = 'Transaction Number  Not Found!';
                return $response;
            }

            $BankPayment = $this->BankPayment->find('first', array('conditions' => array('transaction_id' => $certid, 'token_no' => $token, 'payment_mode_id' => $data['payment_mode_id'])));

            if (empty($BankPayment)) {
                $response['Error'] = 'Invalid Trasanction Number';
                return $response;
            }
            $BankPayment = $BankPayment['BankPayment'];
            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 67, 53, 66, 68), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));

            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));

            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('bank_trn_id' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                if (!isset($extrafields['StatusOnly'])) {
                    $response['Error'] = 'Transaction number is already used';
                    return $response;
                }
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 33)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }

            try {
                error_reporting(0); 
                $client = new SoapClient($bankapi['interface_url'], array("trace" => 0, "exception" => 0));
                $requestdata ['identity'] = $certid;
                $requestdata ['dept'] = $bankapi['department_id'];
                $result = $client->GetGrnDetails_identity($requestdata);
            } catch (SoapFault $exc) {
                $response['Error'] = 'Payment Verification API  Not Working';
                return $response;
            }


            if (empty($result->GetGrnDetails_identityResult)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }

            $result = json_decode($result->GetGrnDetails_identityResult, true);
            //  pr($result);exit;
//            $result['GRN'] = 'XYZ';
//            $result['CIN'] = '123';
//            $result['Status'] = 'SUCCESS';
//            $result['Amount'] = 7280;
//            $result['Bankdate'] = '11/12/2020';

            if (!is_array($result)) {
                $response['Error'] = 'Invalid response received!';
                return $response;
            }
//pr($result);
            //  exit;

            if (strcmp(strtoupper($result[0]['Status']), 'SUCCESS') != 0) {
                $response['Error'] = 'Transaction unsuccessful : ' . strtoupper($result[0]['Status']);
                $response['Status'] = strtoupper($result[0]['Status']);
                return $response;
            }
            $result = $result[0];




            if (isset($result['Bankdate']) && !is_null($result['Bankdate'])) {
                $bank_date_arr = explode('/', $result['Bankdate']);
                $result['Bankdate'] = $bank_date_arr[2] . '-' . $bank_date_arr[1] . '-' . $bank_date_arr[0];
            }


            $totalfee = trim(str_replace(',', '', $result['Amount']));

            $payments = explode('#', $BankPayment['account_head_desc']);
            if (is_array($payments)) {
                foreach ($payments as $pay_str) {
                    $pay_arr = explode("|", $pay_str);
                    $payments_arr[$pay_arr[0]] = $pay_arr[2];
                }
            }


            $PaymentData = array();
            $onlinedata = array();
            foreach ($acchead as $itemid => $headcode) {
                foreach ($accounthead as $key => $single) {
                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                        $insertdata = array();
                        $insertdata = array_merge($insertdata, $extrafields);
                        $insertdata['online_verified_flag'] = 'Y';
                        $insertdata['defacement_flag'] = 'Y';
                        $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                        $insertdata['bank_trn_id'] = $certid;
                        $insertdata['grn_no'] = $result['GRN'];
                        $insertdata['cin_no'] = $result['CIN'];

                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = date('Y-m-d H:i:s', strtotime($result['Bankdate']));
                        $insertdata['payee_fname_en'] = $BankPayment['payee_fname_en'];

                        $paidamt = 0;
                        foreach ($paidamount as $paidamountsingle) {
                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                $paidamt += $paidamountsingle['payment']['pamount'];
                            }
                        }
                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                        if ($single[0]['totalsd'] > 0) {


                            // pr($payments_arr);
                            // exit;
                            switch ($itemid) {
                                case 1: //Reg Fee

                                    if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] >= $single[0]['totalsd']) {
                                        $insertdata['pamount'] = $single[0]['totalsd'];
                                        $payments_arr['0030031040601'] = $payments_arr['0030031040601'] - $single[0]['totalsd'];
                                    } else {
                                        $insertdata['pamount'] = $payments_arr[$headcode];
                                        $payments_arr[$headcode] = 0;
                                    }
                                    break;
                                case 67: //conditional Reg Fee

                                    if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] >= $single[0]['totalsd']) {
                                        $insertdata['pamount'] = $single[0]['totalsd'];
                                        $payments_arr['0030031040601'] = $payments_arr['0030031040601'] - $single[0]['totalsd'];
                                    } else {
                                        $insertdata['pamount'] = $payments_arr['0030031040601'];
                                        $payments_arr[$headcode] = 0;
                                    }
                                    break;

                                case 53:// Visiting fees
                                    if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] >= $single[0]['totalsd']) {
                                        $insertdata['pamount'] = $single[0]['totalsd'];
                                        $payments_arr['0030031040601'] = $payments_arr['0030031040601'] - $single[0]['totalsd'];
                                    } else {
                                        $insertdata['pamount'] = $payments_arr['0030031040601'];
                                        $payments_arr[$headcode] = 0;
                                    }
                                    break;

                                case 66:// Pasting Fee
                                    if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] >= $single[0]['totalsd']) {
                                        $insertdata['pamount'] = $single[0]['totalsd'];
                                        $payments_arr['0030031040601'] = $payments_arr['0030031040601'] - $single[0]['totalsd'];
                                    } else {
                                        $insertdata['pamount'] = $payments_arr['0030031040601'];
                                        $payments_arr[$headcode] = 0;
                                    }
                                    break;
                                case 68:// Processing fee
                                    if (isset($payments_arr['0030038000650']) && $payments_arr['0030038000650'] >= $single[0]['totalsd']) {
                                        $insertdata['pamount'] = $single[0]['totalsd'];
                                        $payments_arr['0030038000650'] = $payments_arr['0030038000650'] - $single[0]['totalsd'];
                                    } else {
                                        $insertdata['pamount'] = $payments_arr['0030038000650'];
                                        $payments_arr[$headcode] = 0;
                                    }
                                    break;
                            }


                            if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                                array_push($PaymentData, $insertdata);
                                $onlinedata = $insertdata;
                            }
                        }
                    }
                }
            }
            // pr($PaymentData);exit;
            $onlinedata['pamount'] = $totalfee;
            if (!empty($PaymentData)) {

                foreach ($PaymentData as $key => $value) {
                    if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] > 0 && $value['account_head_code'] == '0030031040601') {
                        $PaymentData[$key]['pamount'] += $payments_arr['0030031040601'];
                        $payments_arr['0030031040601'] = 0;
                    }
                    if (isset($payments_arr['0030038000650']) && $payments_arr['0030038000650'] > 0 && $value['account_head_code'] == '0030038000650') {
                        $PaymentData[$key]['pamount'] += $payments_arr['0030038000650'];
                        $payments_arr['0030038000650'] = 0;
                    }
                }


                if (isset($payments_arr['0030031040601']) && $payments_arr['0030031040601'] > 0) {
                    $insertdata['pamount'] = $payments_arr['0030031040601'];
                    $insertdata['account_head_code'] = '0030031040601';
                    array_push($PaymentData, $insertdata);
                }
                if (isset($payments_arr['0030038000650']) && $payments_arr['0030038000650'] > 0) {
                    $insertdata['pamount'] = $payments_arr['0030038000650'];
                    $insertdata['account_head_code'] = '0030038000650';
                    array_push($PaymentData, $insertdata);
                }

                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
            } else {
                $response['OnlinePaymentData'] = array(
                    'online_verified_flag' => 'Y',
                    'payment_mode_id' => $data['payment_mode_id'],
                    'certificate_no' => $certid,
                    'grn_no' => $result['GRN'],
                    'cin_no' => $result['CIN'],
                    'pdate' => date('Y-m-d H:i:s', strtotime($result['Bankdate'])),
                    'payee_fname_en' => $BankPayment['payee_fname_en']
                );
            }
            //pr($response);exit;
            return $response;
        }
    }

    public function estamp_certificate_verification($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $certid = trim(@$data['certificate_no']); // 'IN-PB00100028749518M';  
            $certdate = @$data['estamp_issue_date']; // '01-12-2014';
            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($certid) || empty($certdate)) {
                $response['Error'] = 'Certificate Number or Issue Date Not Found!';
                return $response;
            }

            $certdate_new = date('d-m-Y', strtotime($data['estamp_issue_date'])); // '01-12-2014';

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(2, 70), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));

            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'Certificate Already Exist In Verified List';
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 34)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }


            $api_secret = $bankapi['secure_key'];

            //$hash = hash_hmac('sha256', $t = $bankapi['interface_user_id'] . '+' . $certid . '+' . $certdate_new, $api_secret, true);

            $hash = hash_hmac('sha256', $t = $bankapi['interface_user_id'] . '' . $certid . '' . $certdate_new, $api_secret, true);

            // pr($t);exit;
            $hash = base64_encode($hash);
            $hash = urlencode($hash);
            $requestdata['application_id'] = $bankapi['interface_user_id'];
            $requestdata['certificate_number'] = $certid;
            $requestdata['certificate_issue_date'] = $certdate_new;
            $requestdata['hmac'] = $hash;

            $fields_string = '';
            $i = 1;
            foreach ($requestdata as $key => $value) {
                if (count($requestdata) > $i) {
                    $fields_string .= $key . '=' . $value . '&';
                } else {
                    $fields_string .= $key . '=' . $value;
                }
                $i++;
            }
            $result = '';

            $ch1 = curl_init($bankapi['interface_url'] . '?' . $fields_string);

            //  $mode = 0777;                                
            // $myfile = fopen("E:log_estamp.txt", "w");
            // fwrite($myfile, $bankapi['interface_url'] . '?' . $fields_string);
            //  fclose($myfile);
            // pr($bankapi['interface_url'] . '?' . $fields_string);exit;
            //curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/x-www-form-urlencoded',
//                'Content-Length: ' . strlen($fields_string))
//            );
            curl_setopt($ch1, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 10);
            $result = curl_exec($ch1);
            // pr($result);
            // pr(curl_error($ch1));exit;


            if (curl_errno($ch1)) {

                $response['Error'] = 'Empty Service Response! ' . curl_error($ch1);
                return $response;
            }

            if (is_null($result)) {
                $response['Error'] = 'Empty Service Response!' . $result;
                return $response;
            }
            // pr($result);
            $result = json_decode($result, TRUE);
//pr($result);exit;
            if (strcmp(trim(strtoupper($result[0]['status'])), "Fail") == 0) {
                $response['Error'] = $result[0]['details'];
                return $response;
            }

            if (strcmp(trim(strtoupper($result[0]['cert_status'])), "NOT_LOCK") != 0) {
                $response['Error'] = 'Invalid certificate found : Status - ' . $result[0]['cert_status'];
                return $response;
            }


            $PaymentData = array();
            $onlinedata = array();
            if (isset($result['0']['base_certificate_no']) && !empty($result['0']['base_certificate_no'])) {
                $checkbase = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $result['0']['base_certificate_no'], 'payment_mode_id' => $data['payment_mode_id'])));
                if (empty($checkbase)) {
                    $response['Error'] = 'Please Add Base Certificate First';
                    return $response;
                }
            }


            $totalfee = trim(str_replace(',', '', $result['0']['stamp_duty_amount_rs']));
            foreach ($acchead as $itemid => $headcode) {
                foreach ($accounthead as $key => $single) {
                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                        $insertdata = array();
                        $insertdata = array_merge($insertdata, $extrafields);
                        $insertdata['online_verified_flag'] = 'Y';
                        $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                        $insertdata['certificate_no'] = $data['certificate_no'];
                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = date('Y-m-d H:i:s');
                        $insertdata['estamp_issue_date'] = date('Y-m-d H:i:s', strtotime($result['0']['certificate_issued_date'])); //CertificateIssuedDate
                        $insertdata['estamp_acc_no'] = $result['0']['account_reference'];
                        $insertdata['estamp_purchaser_name'] = $result['0']['stamp_duty_paid_by'];
                        $insertdata['payee_fname_en'] = $result['0']['stamp_duty_paid_by'];
                        $insertdata['estamp_vender_place'] = $result[0]['state_name'];
                        $insertdata['certificate_unique_no'] = $result['0']['unique_doc_reference'];
                        if (isset($result['0']['base_certificate_no']) && isset($result['0']['certificate_no'])) {
                            $insertdata['base_certificate_no'] = $result['0']['base_certificate_no'];
                        }
                        $paidamt = 0;
                        foreach ($paidamount as $paidamountsingle) {
                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                $paidamt += $paidamountsingle['payment']['pamount'];
                            }
                        }
                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                        switch ($itemid) {
                            case 2:
                                // stamp duty
                                if ($totalfee >= $single[0]['totalsd']) {
                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                } else {
                                    $insertdata['pamount'] = $totalfee;
                                    $totalfee = 0;
                                }
                                break;
                            case 70:
                                //conditional stamp duty
                                if ($totalfee >= $single[0]['totalsd']) {
                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                } else {
                                    $insertdata['pamount'] = $totalfee;
                                    $totalfee = 0;
                                }
                                break;
                        }

                        if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                            array_push($PaymentData, $insertdata);
                            $onlinedata = $insertdata;
                        }
                    }
                }
            }
            $onlinedata['pamount'] = trim(str_replace(',', '', $result['0']['stamp_duty_amount_rs']));

            if (!empty($PaymentData)) {
                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
            }
            return $response;
        }
    }

    // Not tested on stagging - its live certificates(no stagging environment)
    public function estamp_certificate_lock($payment = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment']);
        $response['Error'] = '';
        if ($payment != NULL) {
            $certdate_new = date('d-m-Y', strtotime($payment['estamp_issue_date'])); // '01-12-2014'; 
            $regno = 'NA';
            $user_id = 'NA';
            if (isset($extrafields['user_id'])) {
                $user_id = $extrafields['user_id'];
            } else if (isset($extrafields['org_user_id'])) {
                $user_id = $extrafields['org_user_id'];
            }

            if (isset($extrafields['regdocno'])) {
                $regno = $extrafields['regdocno'];
            }
            if (!is_numeric($user_id)) {
                $response['Error'] = 'Please Provide User Id ';
                return $response;
            }
            if (!empty($payment['base_certificate_no'])) {
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 35)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }
            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            if (empty($path)) {
                $response['Error'] = 'Base Path Not Found!';
                return $response;
            } else {
                $basepath = $path['file_config']['filepath'];
            }

            $certid = $payment['certificate_no'];
            $api_secret = $bankapi['secure_key'];

            $hash = hash_hmac('sha256', $t = $bankapi['interface_user_id'] . '' . $certid . '' . $certdate_new . $user_id, $api_secret, true);
            $hash = base64_encode($hash);
            $hash = urlencode($hash);


            $requestdata['application_id'] = $bankapi['interface_user_id'];
            $requestdata['certificate_number'] = $certid;
            $requestdata['certificate_issue_date'] = $certdate_new;
            $requestdata['locked_by'] = $user_id;
            $requestdata['locked_reason'] = 'SROLOCK';
            $requestdata['hmac'] = $hash;

            $fields_string = '';
            $i = 1;
            foreach ($requestdata as $key => $value) {
                if (count($requestdata) > $i) {
                    $fields_string .= $key . '=' . $value . '&';
                } else {
                    $fields_string .= $key . '=' . $value;
                }
                $i++;
            }

            $ch1 = curl_init($bankapi['interface_url'] . '?' . $fields_string);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch1, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 300);
            $result = curl_exec($ch1);

            if (curl_errno($ch1)) {
                $response['Error'] = 'Empty Service Response! ' . curl_error($ch1);
                return $response;
            }

            if (is_null($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }
            $result = json_decode($result, TRUE);

            if (strcmp(trim(strtoupper($result['status'])), "SUCCESS") != 0) {
                $response['Error'] = $result['details'];
                return $response;
            }


            $lockdate = date('Y-m-d');
            $response['PaymentData']['defacement_flag'] = "'" . 'Y' . "'";
            $response['PaymentData']['certificate_lock_date'] = "'" . $lockdate . "'";
            $response['PaymentData']['defacement_time'] = "'" . $lockdate . "'";

            $aditionalstamp = $this->payment->find("list", array('fields' => 'payment_id,certificate_no', 'conditions' => array('base_certificate_no' => $payment['certificate_no'])));
            array_push($aditionalstamp, $payment['certificate_no']);

            $response['Condition']['certificate_no'] = $aditionalstamp;
            $response['Condition']['token_no'] = $payment['token_no'];
            $response['Condition']['payment_mode_id'] = $payment['payment_mode_id'];

            return $response;
        }
    }

                            

}
