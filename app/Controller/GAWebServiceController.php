<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MHWebServiceController
 *
 * @author nic
 */
class GAWebServiceController extends AppController {

//put your code here
    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
        $this->Auth->allow('ngdrsgoaapi1', 'ngdrsgoaapi1_test', 'mutation_manually');
        $this->request->addDetector('ssl', array('callback' => function() {
                return CakeRequest::header('X-Forwarded-Proto') == 'https';
            }));

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function gras_payment_entry($transid = NULL) {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');

        $mapping = $this->BankPayment->mapping_account_heads(11, 3);

        $fieldlist = array();

        $fieldlist['token_no']['text'] = 'is_required,is_numeric';
        $fieldlist['payee_fname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['payee_lname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['email_id']['text'] = 'is_required,is_email';
        $fieldlist['mobile']['text'] = 'is_required,is_phone';
        $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pincode']['text'] = 'is_required,is_alphanumericspace';




        $i = 0;
        foreach ($mapping as $map) {
            $i++;
            $fieldlist['map' . $i]['text'] = 'is_required,is_numeric';
        }



        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 19);
        //echo $txnid;

        if ($this->request->is('post')) {
            $data = $this->request->data;
            $errarr = $this->validatedata($data, $fieldlist);
            //pr($errarr);exit;
            if ($this->ValidationError($errarr)) {

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }


                $result = $this->genernalinfoentry->find("all", array(
                    'conditions' => array('token_no' => trim($data['token_no']))
                ));

                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    // $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry'));
                }
                $amt = 0;
                $other_details = '';
                if (isset($mapping)) {
                    $i = 0;
                    foreach ($mapping as $map) {
                        $i++;
                        if (isset($data['map' . $i]) && is_numeric($data['map' . $i])) {
                            $amt += $data['map' . $i];
                            $other_details .= "**" . $map[0]['fee_item_desc_en'] . ":" . $map[0]['account_head_code'] . ":" . $data['map' . $i];
                        }
                    }
                }
                $other_details = substr($other_details, 2);
                $data['pamount'] = number_format($amt, 2, ".", "");

                do {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                    $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                } while (!empty($check));



                $data['payment_mode_id'] = 11;
                $data['transaction_id'] = $txnid;



                $usertype = $this->Session->read("session_usertype");
                $savedata['user_type'] = $usertype;
                $now = date('Y-m-d H:i:s');
                if ($usertype == 'C') {
                    $data['user_id'] = $this->Auth->user('user_id');
                    $data['created'] = $now;
                } elseif ($usertype == 'O') {
                    $data['org_user_id'] = $this->Auth->user('user_id');
                    $data['org_created'] = $now;
                }
                if ($this->BankPayment->save($data)) {
                    try {
                        $client = new SoapClient($bankapi['interface_url']);
                        $result = $client->Generate_eChallan_MutationFee(array(
                            'webUser' => $bankapi['interface_user_id'],
                            'webPass' => $bankapi['interface_password'],
                            'No_of_fee_items' => 1,
                            'Feeamt' => $data['pamount'],
                            'mobile' => $data['mobile'],
                            'Partyname' => $data['payee_fname_en'],
                            'Party_Address' => $data['address'],
                            'Party_PIN' => $data['pincode'],
                            'email' => $data['email_id'],
                            'Party_taluka' => $data['city'],
                            'IPAddress' => '10.153.8.105',
                            'Reason' => 'NGDRS Fee Colletion',
                            'OtherDetails' => $other_details,
                            'RuralUrbanFlag' => 'RURALNORTH'
                        ));


                        $servicedata = (string) $result->Generate_eChallan_MutationFeeResult;
                        //  $file = fopen("D:/challan.txt", "w");
                        //  fwrite($file, $servicedata);
                        // fclose($file);
                        $start = substr(trim($servicedata), 0, 1);
                        if ($start != '<') {
                            $this->Session->setFlash(
                                    __('Service Return - ' . $servicedata)
                            );
                            $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry'));
                        }

                        $xml = simplexml_load_string($servicedata, "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xml);
                        $array = json_decode($json, TRUE);
                        // pr($array);exit;


                        $file = $basepath . "webservice_files/" . $txnid . '_challan.pdf';
                        if (file_put_contents($file, base64_decode($array['Table1']['filebytes']))) {
                            //  Table1/eno
                            $this->BankPayment->query("update ngdrstab_trn_bank_payment set gateway_trans_id=?, payment_status=? where transaction_id=? ", array($array['Table1']['eno'], 'SUCCESS1', $txnid));
                            if (file_exists($file)) {
                                $this->Session->setFlash(
                                        __('Successfully created challan.')
                                );
                            } else {
                                $this->Session->setFlash(
                                        __('Unable create Challan ')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Unable create Challan ')
                            );
                        }
                    } catch (SoapFault $e) {
                        $this->Session->setFlash(
                                __('Unable to connect web service ' . $e->getMessage())
                        );
                    }
                }
            } else {
                $this->Session->setFlash(
                        __('Please Check Validations')
                );
            }
        } //post

        $this->Auth->user('user_id');
        $usertype = $this->Session->read("session_usertype");
        $result = array();
        $this->Auth->user('user_id');
        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 11),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('org_user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 11),
                'order' => array('trn_id DESC'),
            ));
        }



        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping'));
    }

    public function EchallanVerification_OLD($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $certid = trim(@$data['certificate_no']);

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($certid)) {
                $response['Error'] = 'Provide Challan Number ';
                return $response;
            }


            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            $challandetails = $this->BankPayment->find("first", array('fields' => 'account_head_desc,udf1,payment_mode_id', 'conditions' => array('gateway_trans_id' => $certid, 'token_no' => $token)));
            if (empty($challandetails)) {
                $response['Error'] = 'Challan not valid for this token';
                return $response;
            }
            $challanacc = explode("|", $challandetails['BankPayment']['account_head_desc']);
            //pr($challandetails);exit;
            $Rural_Urban = $challandetails['BankPayment']['udf1'];
            if (empty($Rural_Urban)) {
                $response['Error'] = 'Provide Rural/Urban/NOTARY Flag';
                return $response;
            }
            $data['payment_mode_id'] = $challandetails['BankPayment']['payment_mode_id'];

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('account_head_code' => $challanacc, 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            //pr($acchead);
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            //pr($accounthead);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }
            if (!isset($extrafields['StatusOnly'])) {
                $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
                if (!empty($onlinepay)) {
                    $response['Error'] = 'Challan Already Exist In Verified List';
                    return $response;
                }
            }

            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }
            $requestdata['webUser'] = $bankapi['interface_user_id'];
            $requestdata['webPass'] = $bankapi['interface_password'];
            $requestdata['eChallan_No'] = $certid;
            $requestdata['Rural_Urban_Notary'] = $Rural_Urban;
            try {
                App::import('Vendor', 'Soap/nusoap');
                $client = new nusoap_client($bankapi['interface_url'], true);
                $servicedata = $client->call('eChallan_Payment_Status', $requestdata);
                // pr($servicedata);exit;
                if (is_array($servicedata)) {
                    $servicedata = str_replace('utf-16', 'utf-8', $servicedata);
                    $xml = simplexml_load_string($servicedata['eChallan_Payment_StatusResult'], "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $result = json_decode($json, TRUE);
                    //  pr($result);
                    //  exit;
                    if (is_array($result)) {
                        $result = $result['eChallanStatusResponse'];
                        if (strcmp(trim($result['eChallanStatus']), 'S') != 0) {
                            if (empty($result['eChallanStatusDesc'])) {
                                $response['Error'] = 'Status : Not Paid';
                            } else {
                                $response['Error'] = 'Status : ' . $result['eChallanStatusDesc'];
                            }
                            return $response;
                        }
                        // Only For verification Status
                        if (isset($extrafields['StatusOnly']) && $extrafields['StatusOnly'] == 'Y') {
                            $vdata['STATUS'] = 'Y';
                            $vdata['AMOUNT'] = trim(str_replace(',', '', $result['eChallanPaidAmt']));
                            if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {
                                $vdata['payment_date'] = date('Y-m-d H:i:s', strtotime($result['bankReceiveDate'])); //CertificateIssuedDate
                                $vdata['cin_no'] = $result['sbiReferenceNo'];
                            } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                $vdata['payment_date'] = date('Y-m-d H:i:s', strtotime($result['treasuryReceiveDate'])); //CertificateIssuedDate
                                $vdata['cin_no'] = "NA - Paid By treasury";
                            }

                            return $vdata;
                        }



                        $PaymentData = array();
                        $onlinedata = array();
//                        $result['eChallanPaidAmt'] = 15449;
                        $totalfee = trim(str_replace(',', '', $result['eChallanPaidAmt']));
                        // $totalfee = 1050002;
                        foreach ($acchead as $itemid => $headcode) {
                            foreach ($accounthead as $key => $single) {

                                if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                                    $insertdata = array();
                                    $insertdata = array_merge($insertdata, $extrafields);
                                    $insertdata['online_verified_flag'] = 'Y';
                                    $insertdata['defacement_flag'] = 'Y';
                                    $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                                    $insertdata['certificate_no'] = $result['eChallanNo'];
                                    $insertdata['account_head_code'] = $headcode;
                                    $insertdata['pdate'] = date('Y-m-d H:i:s');
                                    if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {
                                        $insertdata['estamp_issue_date'] = date('Y-m-d H:i:s', strtotime($result['bankReceiveDate'])); //CertificateIssuedDate
                                        $insertdata['cin_no'] = $result['sbiReferenceNo'];
                                    } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                        $insertdata['estamp_issue_date'] = date('Y-m-d H:i:s', strtotime($result['treasuryReceiveDate'])); //CertificateIssuedDate
                                        $insertdata['cin_no'] = "NA - Paid By treasury";
                                    }


                                    $paidamt = 0;
                                    foreach ($paidamount as $paidamountsingle) {
                                        if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                            $paidamt += $paidamountsingle['payment']['pamount'];
                                        }
                                    }
                                    $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                                    switch ($itemid) {
                                        case 1:
                                            // REG Fee
                                            if ($totalfee >= $single[0]['totalsd']) {
                                                $insertdata['pamount'] = $single[0]['totalsd'];
                                                $totalfee = $totalfee - $single[0]['totalsd'];
                                            } else {
                                                $insertdata['pamount'] = $totalfee;
                                                $totalfee = 0;
                                            }
                                            break;
                                        case 48:
                                            // Mutation Fee
                                            if ($totalfee >= $single[0]['totalsd']) {
                                                $insertdata['pamount'] = $single[0]['totalsd'];
                                                $totalfee = $totalfee - $single[0]['totalsd'];
                                            } else {
                                                $insertdata['pamount'] = $totalfee;
                                                $totalfee = 0;
                                            }
                                            break;
                                        case 100:
                                            //Processing Fee
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
                        $onlinedata['pamount'] = trim(str_replace(',', '', $result['eChallanPaidAmt']));
                        if (!empty($PaymentData)) {
                            $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                            $response['PaymentData'] = $PaymentData;
                            $response['OnlinePaymentData'] = $onlinedata;
                        } else {
                            $response['Error'] = 'Amount already paid for this account head code';
                        }
//pr($response);exit;



                        return $response;
                    } else {
                        $response['Error'] = 'Invalid Service Responce';
                        return $response;
                    }
                } else {
                    $response['Error'] = 'Invalid Service Responce';
                    return $response;
                }
            } catch (SoapFault $sf) {
                $response['Error'] = 'Not able to connect webservice';
                return $response;
            }
        }
    }

    public function EchallanVerification($data = NULL, $extrafields = NULL) {


        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $certid = trim(@$data['certificate_no']);

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($certid)) {
                $response['Error'] = 'Provide Challan Number ';
                return $response;
            }


            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            $challandetails = $this->BankPayment->find("first", array('fields' => 'account_head_desc,udf1,payment_mode_id', 'conditions' => array('gateway_trans_id' => $certid, 'token_no' => $token)));
            if (empty($challandetails)) {
                $response['Error'] = 'Challan not valid for this token';
                return $response;
            }
            $challanacc = explode("|", $challandetails['BankPayment']['account_head_desc']);
            //pr($challandetails);exit;
            $Rural_Urban = $challandetails['BankPayment']['udf1'];
            $data['payment_mode_id'] = $challandetails['BankPayment']['payment_mode_id'];

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('account_head_code' => $challanacc, 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            //pr($acchead);
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            //pr($accounthead);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }
            if (!isset($extrafields['StatusOnly'])) {
                $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
                if (!empty($onlinepay)) {
                    $response['Error'] = 'Challan Already Exist In Verified List';
                    return $response;
                }
            }

            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }
            $requestdata['webUser'] = $bankapi['interface_user_id'];
            $requestdata['webPass'] = $bankapi['interface_password'];
            $requestdata['eChallan_No'] = $certid;
            $requestdata['Rural_Urban_Notary'] = $Rural_Urban;
            try {
                App::import('Vendor', 'Soap/nusoap');
                $client = new nusoap_client($bankapi['interface_url'], true);
                $servicedata = $client->call('eChallan_Payment_Status', $requestdata);
                //pr($servicedata);exit;
                if (is_array($servicedata)) {
                    $servicedata = str_replace('utf-16', 'utf-8', $servicedata);
                    $start = substr(trim($servicedata['eChallan_Payment_StatusResult']), 0, 1);
                    if ($start != '<') {                                            
                        $response['Error'] = 'Echallan verification service not working';
                        return $response;
                    }
                    $xml = simplexml_load_string($servicedata['eChallan_Payment_StatusResult'], "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $result = json_decode($json, TRUE);
                    //  pr($result);
                    // exit;
                    if (is_array($result)) {

                        if (isset($result['eChallanStatusResponse'])) {
                            $result = $result['eChallanStatusResponse'];
                        }
                        if (isset($result['Error']) && strcmp(trim($result['Error']), 'Y') == 0) {
                            $response['Error'] = 'Status : ' . $result['ErrorDesc'];
                            return $response;
                        }
                        if (strcmp(trim($result['status']), 'F') == 0) {
                            $response['Error'] = 'Status : Transaction Failed';
                            return $response;
                        }
                        if (strcmp(trim($result['status']), 'P') == 0) {
                            $response['Error'] = 'Status : Transaction is Pending, check after 30 minutes';
                            return $response;
                        }
                        // pr(strcmp(trim($result['status']),'Y' ));
                        // pr($result);exit;
                        if (strcmp(trim($result['status']), 'S') == 0 || strcmp(trim($result['status']), 'Y') == 0) {
                            // Only For verification Status
                            if (isset($extrafields['StatusOnly']) && $extrafields['StatusOnly'] == 'Y') {
                                $vdata['STATUS'] = 'Y';
                                $vdata['AMOUNT'] = trim(str_replace(',', '', $result['totalAmount']));
                                if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {
                                    $rdate = explode(' ', $result['bankReceiveDate']);
                                    $rdate = explode('/', $rdate[0]);
                                    $vdata['payment_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate //CertificateIssuedDate
                                    $vdata['cin_no'] = $result['sbiReferenceNo'];
                                } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                    $rdate = explode(' ', $result['treasuryReceiveDate']);
                                    $rdate = explode('/', $rdate[0]);
                                    $vdata['payment_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate //CertificateIssuedDate
                                    $vdata['cin_no'] = "NA - Paid By treasury";
                                }

                                return $vdata;
                            }



                            $PaymentData = array();
                            $onlinedata = array();
//                        $result['eChallanPaidAmt'] = 15449;
                            $totalfee = trim(str_replace(',', '', $result['totalAmount']));
                            // $totalfee = 1050002;
                            // pr($result);exit;
                            foreach ($acchead as $itemid => $headcode) {
                                foreach ($accounthead as $key => $single) {

                                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                                        $insertdata = array();
                                        $insertdata = array_merge($insertdata, $extrafields);
                                        $insertdata['online_verified_flag'] = 'Y';
                                        $insertdata['defacement_flag'] = 'Y';
                                        $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                                        $insertdata['certificate_no'] = $result['echallanNo']; //echallanNo
                                        $insertdata['estamp_vender_name'] = $Rural_Urban; //Rural_Urban flag

                                        $insertdata['account_head_code'] = $headcode;
                                        $insertdata['pdate'] = date('Y-m-d H:i:s');
                                        if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {

                                            $rdate = explode(' ', $result['bankReceiveDate']);
                                            $rdate = explode('/', $rdate[0]);
                                            $insertdata['estamp_issue_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate
                                            $insertdata['cin_no'] = $result['sbiReferenceNo'];
                                        } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                            $rdate = explode(' ', $result['treasuryReceiveDate']);
                                            $rdate = explode('/', $rdate[0]);
                                            $insertdata['estamp_issue_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate                                        
                                            $insertdata['cin_no'] = "NA - Paid By treasury";
                                        }

                                        $paidamt = 0;
                                        foreach ($paidamount as $paidamountsingle) {
                                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                                $paidamt += $paidamountsingle['payment']['pamount'];
                                            }
                                        }
                                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                                        switch ($itemid) {
                                            case 1:
                                                // REG Fee
                                                if ($totalfee >= $single[0]['totalsd']) {
                                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                                } else {
                                                    $insertdata['pamount'] = $totalfee;
                                                    $totalfee = 0;
                                                }
                                                break;
                                            case 48:
                                                // Mutation Fee
                                                if ($totalfee >= $single[0]['totalsd']) {
                                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                                } else {
                                                    $insertdata['pamount'] = $totalfee;
                                                    $totalfee = 0;
                                                }
                                                break;
                                            case 100:
                                                //Processing Fee
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
                            $onlinedata['pamount'] = trim(str_replace(',', '', $result['totalAmount']));
                            $res_string = '';
                            foreach ($result as $key => $value) {
                                if (is_array($value)) {
                                    $res_string .= " , " . $key . ":" . implode('-', $value);
                                } else {
                                    $res_string .= " , " . $key . ":" . $value;
                                }
                            }
                            if (strlen($res_string) > 0) {
                                $onlinedata['gras_account_details'] = substr($res_string, 2);
                            }

                            if (!empty($PaymentData)) {
                                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                                $response['PaymentData'] = $PaymentData;
                                $response['OnlinePaymentData'] = $onlinedata;
                            } else {
                                $response['Error'] = 'Amount already paid for this account head code or fee not calculated';
                            }
                            //pr($response);exit;
                            return $response;
                        } else {
                            $response['Error'] = 'Something went wrong';
                        }
                    } else {
                        $response['Error'] = 'Invalid Service Responce';
                        return $response;
                    }
                } else {
                    $response['Error'] = 'Invalid Service Responce';
                    return $response;
                }
            } catch (SoapFault $sf) {
                $response['Error'] = 'Not able to connect webservice';
                return $response;
            }
        }
    }

    public function mutation($token) {
        $response['Error'] = '';
        $response['STATUS'] = 0;
        try {
            $this->loadModel('external_interface');
            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');

            $path = $this->file_config->find('first', array('fields' => array('filepath')));
            if (!empty($path) && file_exists($path['file_config']['filepath'])) {
                $api = $this->external_interface->find("first", array('conditions' => array('interface_id' => 16)));
                if (!empty($api)) {
                    $api = $api['external_interface'];
                    $client = new SoapClient($api['interface_url']);
                    $headerbody = array('Username' => $api['interface_user_id'],
                        'Password' => $api['interface_password']
                    );
                    $ns = "http://apps.goa.nic.in/dharanigauri/";
                    $header = new soapheader($ns, 'SecuredWebServiceHeader', $headerbody);
                    $client->__setSoapHeaders($header);



                    $application = $this->ApplicationSubmitted->find("first", array(
                        'fields' => array('final_doc_reg_no', 'final_stamp_date', 'office.office_code', 'taluka.taluka_code'),
                        'joins' => array(
                            array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'type' => 'INNER', 'conditions' => array("office.office_id=ApplicationSubmitted.office_id")),
                            array('table' => 'ngdrstab_trn_property_details_entry', 'alias' => 'prop', 'type' => 'left', 'conditions' => array("prop.token_no=ApplicationSubmitted.token_no")),
                            array('table' => 'ngdrstab_conf_admblock5_taluka', 'alias' => 'taluka', 'type' => 'left', 'conditions' => array("taluka.taluka_id=prop.taluka_id"))
                        ),
                        'conditions' => array('ApplicationSubmitted.token_no' => $token)
                    ));

                    if (!empty($application)) {
                        $pushdataxml = $this->generate_dharani_push_xml($token);
                        if ($pushdataxml == null) {
                            $response['Error'] = 'Property valuation not done';
                            return $response;
                        }
                        $regdate = explode(" ", $application['ApplicationSubmitted']['final_stamp_date']);
                        $pushdata = array(
                            'Registration_no' => $application['ApplicationSubmitted']['final_doc_reg_no'],
                            'Registration_Date' => @$regdate[0],
                            'SROCode' => $application['office']['office_code'],
                            'DharaniTalukaCode' => $application['taluka']['taluka_code'],
                            'XMLFileContentsString' => $pushdataxml
                        );
                        //pr($pushdata);
                        //exit;
                        $result = $client->Insert_FormXIII($pushdata);

                        //exit;
                        if (!empty($result)) {
                            if (is_numeric(trim($result->Insert_FormXIIIResult)) && trim($result->Insert_FormXIIIResult) == 0) {
                                $response['STATUS'] = 1;
                            } else {
                                $response['Error'] = 'Fail to push data to dharani';
                            }
                        } else {
                            $response['Error'] = 'Empty mutation service responce';
                        }
                    } else {
                        $response['Error'] = 'Document or Property not found';
                    }
                } else {
                    $response['Error'] = 'Bank api not found';
                }
            } else {
                $response['Error'] = 'Base Path Not Found';
            }
        } catch (SoapFault $e) {
            pr($e->getMessage());
            exit;
            $response['Error'] = 'Fail to push data to dharani: Not able to connect service';
        }
        return $response;
    }

    public function generate_dharani_push_xml($token_no) {

        $this->loadModel('party_entry');
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('office');
        $this->loadModel('genernal_info');
        $this->loadModel('stamp_duty');
        $this->loadModel('payment');
        $this->loadModel('payment_mode');
        $this->loadModel('property_details_entry');
        $this->loadModel('Developedlandtype');
        $this->loadModel('valuation');
        $this->loadModel('valuation_details');
        $this->loadModel('taluka');
        $this->loadModel('VillageMapping');
        $this->loadModel('parameter');
        $this->loadModel('unit');
        $this->loadModel('fees_calculation');

        $property = $this->property_details_entry->find('first', array('conditions' => array('token_no' => $token_no, 'val_id <>' => 0)));

        // pr($property);

        if (!empty($property)) {
            $property_id = $property['property_details_entry']['property_id'];
        }

        if ($property_id == NULL)
            return null;

        $sqlc = $this->ApplicationSubmitted->query("select article.lr_code,office_name_en,a.office_id,final_doc_reg_no,date(final_stamp_date) as doc_reg_date_dt,final_stamp_date as full_doc_reg_date 

from ngdrstab_trn_application_submitted a 

inner join ngdrstab_mst_office b on a.office_id=b.office_id 
JOIN ngdrstab_trn_generalinformation info ON info.token_no=a.token_no
JOIN ngdrstab_mst_article as article ON article.article_id=info.article_id
 
where a.token_no=?", array($token_no));

        $doc_reg_date = $sqlc[0][0]['doc_reg_date_dt'];
        if ($doc_reg_date != '') {
            $full_doc_reg_date = $sqlc[0][0]['full_doc_reg_date'];
            $newDate = date("d-m-Y", strtotime($doc_reg_date));
            $tm = explode(" ", $full_doc_reg_date);
            $reg_dt = $newDate . ' ' . $tm[1];
        } else {
            $reg_dt = '00-00-00 00:00:00';
        }

        $sqld = $this->genernal_info->query("select date(exec_date) as exec_dt,exec_date as full_exec_date from ngdrstab_trn_generalinformation where token_no='$token_no'");
        $exec_dt = $sqld[0][0]['exec_dt'];
        if ($exec_dt != '') {
            $full_exec_date = $sqld[0][0]['full_exec_date'];
            $chng_exec_dt = date("d-m-Y", strtotime($exec_dt));
            $tl = explode(" ", $full_exec_date);
            $exe_dt = $chng_exec_dt . ' ' . $tl[1];
        } else {
            $exe_dt = '00-00-00 00:00:00';
        }

        $sqlf = $this->payment->query("select pmode.payment_mode_desc_en,pmode.payment_mode_id,pay.pamount,date(pay.pdate) as pdate_dt,pay.pdate as full_pdate,pay.grn_no,certificate_no,pay.payee_fname_en from ngdrstab_trn_payment_details as pay JOIN ngdrstab_mst_payment_mode as pmode ON pmode.payment_mode_id=pay.payment_mode_id where pay.token_no='$token_no'  and   pay.account_head_code='48'");


        // pr($token_no);
        // pr($property_id);
        $sqlg = $this->property_details_entry->query("select val.val_id,village.village_name_en,village.village_id,village.village_code,landtype.developed_land_types_desc_en, tal.taluka_name_en,tal.taluka_id,tal.taluka_code,val.rounded_val_amt,prop.boundries_east_en,prop.boundries_west_en,prop.boundries_south_en,boundries_north_en from ngdrstab_trn_property_details_entry as prop JOIN ngdrstab_trn_valuation as val ON  val.val_id=prop.val_id JOIN ngdrstab_conf_admblock5_taluka as tal ON tal.taluka_id=val.taluka_id JOIN ngdrstab_conf_admblock7_village_mapping as village ON village.village_id=val.village_id JOIN ngdrstab_mst_developed_land_types as landtype ON landtype.developed_land_types_id=val.developed_land_types_id where prop.token_no='$token_no' and prop.property_id='$property_id'");

        $scheduleName = '';
        //  pr($sqlg);

        if ($sqlg[0][0]['boundries_east_en'] != '')
            $scheduleName = $sqlg[0][0]['boundries_east_en'];
        if ($sqlg[0][0]['boundries_west_en'] != '')
            $scheduleName .= ' ,' . $sqlg[0][0]['boundries_west_en'];
        if ($sqlg[0][0]['boundries_south_en'] != '')
            $scheduleName .= ' ,' . $sqlg[0][0]['boundries_south_en'];
        if ($sqlg[0][0]['boundries_north_en'] != '')
            $scheduleName .= ' ,' . $sqlg[0][0]['boundries_north_en'];


        $val_id = $sqlg[0][0]['val_id'];


        $sqlp = $this->valuation_details->query("select sum(item_value) as areaval,area_unit,unit_desc_en from ngdrstab_trn_valuation_details  as vald JOIN ngdrstab_mst_usage_items_list as item ON item.usage_param_id=vald.item_id and item.area_field_flag='Y' JOIN ngdrstab_mst_unit as unit ON unit.unit_id=vald.area_unit where vald.val_id='$val_id' group by area_unit,unit.unit_desc_en");


        $slct_const = $this->fees_calculation->query("select cons_amt from ngdrstab_trn_fee_calculation where property_id='$property_id' and  token_no='$token_no' and cons_amt  IS NOT NULL");
        if (sizeof($slct_const) > 0) {
            $consi_amt = $slct_const[0][0]['cons_amt'];
        } else {
            $consi_amt = 0;
        }

        $sqlo = $this->parameter->query(" select mstparam.attribute_id,mstparam.eri_attribute_name ,trnparam.paramter_value from ngdrstab_trn_parameter as trnparam JOIN ngdrstab_mst_attribute_parameter as mstparam ON mstparam.attribute_id=trnparam.paramter_id where trnparam.token_id='$token_no'   and trnparam.property_id='$property_id' and parameter_type='S'");

        if (sizeof($sqlo) > 0) {
            for ($s = 0; $s < sizeof($sqlo); $s++) {
                if ($sqlo[$s][0]['attribute_id'] == '205') {
                    $survey_No = $sqlo[$s][0]['paramter_value'];
                } else if ($sqlo[$s][0]['attribute_id'] == '207') {
                    $subdivision_No = $sqlo[$s][0]['paramter_value'];
                    if ($subdivision_No == '')
                        $isSubdivision_No_Part = "No";
                    else
                        $isSubdivision_No_Part = "Yes";
                }
                else if ($sqlo[$s][0]['attribute_id'] == '208') {
                    $pT_Sheet_No = $sqlo[$s][0]['paramter_value'];
                } else if ($sqlo[$s][0]['attribute_id'] == '209') {
                    $chalta_No = $sqlo[$s][0]['paramter_value'];
                    if ($chalta_No == '')
                        $isChalta_No_Part = "No";
                    else
                        $isChalta_No_Part = "Yes";
                }
            }
        }
        else {
            $survey_No = "NA";
            $subdivision_No = "NA";
            $isSubdivision_No_Part = "No";
            $pT_Sheet_No = "NA";
            $chalta_No = "NA";
            $isChalta_No_Part = "No";
        }

        $generation_date = date("d-M-Y h:i:s A");
        $url_add = '';
        $lRCMutationTypeCode = $sqlc[0][0]['lr_code'];


        $dom = new DOMDocument();

        $dom->encoding = 'utf-8';

        $dom->xmlVersion = '1.0';

        $dom->formatOutput = true;

        $xml_file_name = 'land_parcel_xml_new.xml';

        $root = $dom->createElement('LRCFormXIIISchema');

        $main_node = $dom->createElement('MessageHeader');

        $generationdt = new DOMAttr('XMLGenerationDate', $generation_date);

        $main_node->setAttributeNode($generationdt);

        $sroname = new DOMAttr('SRO_Name', $sqlc[0][0]['office_name_en']);

        $main_node->setAttributeNode($sroname);

        $child_node_title = $dom->createElement('LRCForm_XIII_root');

        $main_node->appendChild($child_node_title);

        $ch1 = $dom->createElement('LRCForm_XIII_header');

        $url = new DOMAttr('URL', $url_add);
        $ch1->setAttributeNode($url);

        $final_reg_no_attr = new DOMAttr('FinalRegistrationNumber', $sqlc[0][0]['final_doc_reg_no']);
        $ch1->setAttributeNode($final_reg_no_attr);

        $reg_dt_attr = new DOMAttr('RegistrationDateTime', $reg_dt);
        $ch1->setAttributeNode($reg_dt_attr);

        $sro_attr = new DOMAttr('SROCode', $sqlc[0][0]['office_id']);
        $ch1->setAttributeNode($sro_attr);

        $sronm_attr = new DOMAttr('SROName', $sqlc[0][0]['office_name_en']);
        $ch1->setAttributeNode($sronm_attr);

        $execdt_attr = new DOMAttr('ExecutionDate', $exe_dt);
        $ch1->setAttributeNode($execdt_attr);

        $considerationamt_attr = new DOMAttr('TotalConsiderationAmount', $consi_amt);
        $ch1->setAttributeNode($considerationamt_attr);

        $remarks = '';
        if ($sqlf != NULL) {
            for ($b = 0; $b < sizeof($sqlf); $b++) {
                $pdate_dt = $sqlf[$b][0]['pdate_dt'];
                if ($pdate_dt != '') {
                    $full_pdate = $sqlf[$b][0]['full_pdate'];
                    $chng_pdate_dt = date("d-m-Y", strtotime($pdate_dt));
                    $tdf = explode(" ", $full_pdate);
                    $eChalan_Date = $chng_pdate_dt . ' ' . $tdf[1];
                } else {
                    $eChalan_Date = '00-00-00 00:00:00';
                }

                $remarks = $remarks . ' & ' . $sqlf[$b][0]['payment_mode_desc_en'] . ' paid of Rs.' . $sqlf[$b][0]['pamount'] . ' on Dated - ' . $eChalan_Date;
            }
        } else {
            $remarks = 'NA';
        }

        $remark_attr = new DOMAttr('Remarks', $remarks);
        $ch1->setAttributeNode($remark_attr);

        $child_node_title->appendChild($ch1);

        $ch2 = $dom->createElement('Transacted_LandParcels');

        $propertyID = new DOMAttr('PropertyID', $property_id);
        $ch2->setAttributeNode($propertyID);

        $rural_or_Urban = new DOMAttr('Rural_or_Urban', $sqlg[0][0]['developed_land_types_desc_en']);
        $ch2->setAttributeNode($rural_or_Urban);

        $gAURITalukaCode = new DOMAttr('GAURITalukaCode', $sqlg[0][0]['taluka_id']);
        $ch2->setAttributeNode($gAURITalukaCode);

        $lRCTalukaCode = new DOMAttr('LRCTalukaCode', $sqlg[0][0]['taluka_code']);
        $ch2->setAttributeNode($lRCTalukaCode);

        $gAURITalukaName = new DOMAttr('GAURITalukaName', $sqlg[0][0]['taluka_name_en']);
        $ch2->setAttributeNode($gAURITalukaName);

        $gAURIVillageCode = new DOMAttr('GAURIVillageCode', $sqlg[0][0]['village_id']);
        $ch2->setAttributeNode($gAURIVillageCode);

        $lRCVillageCode = new DOMAttr('LRCVillageCode', $sqlg[0][0]['village_code']);
        $ch2->setAttributeNode($lRCVillageCode);

        $gAURIVillageName = new DOMAttr('GAURIVillageName', $sqlg[0][0]['village_name_en']);
        $ch2->setAttributeNode($gAURIVillageName);

        $survey_No = new DOMAttr('Survey_No', @$survey_No);
        $ch2->setAttributeNode($survey_No);

        @$subdivision_No = new DOMAttr('Subdivision_No', @$subdivision_No);
        $ch2->setAttributeNode(@$subdivision_No);

        @$isSubdivision_No_Part = new DOMAttr('IsSubdivision_No_Part', @$isSubdivision_No_Part);
        $ch2->setAttributeNode(@$isSubdivision_No_Part);

        $pT_Sheet_No = new DOMAttr('PT_Sheet_No', @$pT_Sheet_No);
        $ch2->setAttributeNode($pT_Sheet_No);

        @$chalta_No = new DOMAttr('Chalta_No', $chalta_No);
        $ch2->setAttributeNode(@$chalta_No);

        @$isChalta_No_Part = new DOMAttr('IsChalta_No_Part', $isChalta_No_Part);
        $ch2->setAttributeNode(@$isChalta_No_Part);

        $totalArea = new DOMAttr('TotalArea', $sqlp[0][0]['areaval']);
        $ch2->setAttributeNode($totalArea);

        $areaMeasurementUnit_attr = new DOMAttr('AreaMeasurementUnit', $sqlp[0][0]['unit_desc_en']);
        $ch2->setAttributeNode($areaMeasurementUnit_attr);

        $considerationAmount = new DOMAttr('ConsiderationAmount', $consi_amt);
        $ch2->setAttributeNode($considerationAmount);

        $marketValue = new DOMAttr('MarketValue', $sqlg[0][0]['rounded_val_amt']);
        $ch2->setAttributeNode($marketValue);

        $lRCMutationTypeCode = new DOMAttr('LRCMutationTypeCode', $lRCMutationTypeCode);
        $ch2->setAttributeNode($lRCMutationTypeCode);

        $child_node_title->appendChild($ch2);

        /// if more than sellers

        $documentrecord = $this->party_entry->query("select * from ngdrstab_trn_party_entry_new as party JOIN ngdrstab_mst_party_type as ptype ON  ptype.party_type_id=party.party_type_id where party_type_flag='1' and token_no=? and property_id=? ", array($token_no, $property_id));
        for ($ai = 0; $ai < sizeof($documentrecord); $ai++) {
            $telephoneNumber = "";
            $stdCode = "";
            $telephoneCountryCode = "";
            $mobileCountryCode = "";

            $ch3 = $dom->createElement('Executer_of_Document_Details');

            $partyNumber_attr = new DOMAttr('PartyNumber', $documentrecord[$ai][0]['party_id']);
            $ch3->setAttributeNode($partyNumber_attr);

            $telephoneNumber_attr = new DOMAttr('TelephoneNumber', $telephoneNumber);
            $ch3->setAttributeNode($telephoneNumber_attr);

            $stdCode_attr = new DOMAttr('STDCode', $stdCode);
            $ch3->setAttributeNode($stdCode_attr);

            $telephoneCountryCode_attr = new DOMAttr('TelephoneCountryCode', $telephoneCountryCode);
            $ch3->setAttributeNode($telephoneCountryCode_attr);

            $mobileNumber_attr = new DOMAttr('MobileNumber', $documentrecord[$ai][0]['mobile_no']);
            $ch3->setAttributeNode($mobileNumber_attr);

            $mobileCountryCode_attr = new DOMAttr('MobileCountryCode', $mobileCountryCode);
            $ch3->setAttributeNode($mobileCountryCode_attr);

            $address_attr = new DOMAttr('Address', $documentrecord[$ai][0]['address_en']);
            $ch3->setAttributeNode($address_attr);

            $fat_Hus_Name_attr = new DOMAttr('Fat_Hus_Name', $documentrecord[$ai][0]['father_full_name_en']);
            $ch3->setAttributeNode($fat_Hus_Name_attr);

            $fullName_attr = new DOMAttr('FullName', $documentrecord[$ai][0]['party_full_name_en']);
            $ch3->setAttributeNode($fullName_attr);

            $child_node_title->appendChild($ch3);
        }


        /// if more than buyers
        $sqlb = $this->party_entry->query("select * from ngdrstab_trn_party_entry_new as party  JOIN ngdrstab_mst_party_type as ptype ON ptype.party_type_id=party.party_type_id where  party_type_flag='0' and token_no=? and property_id=? ", array($token_no, $property_id));
        for ($bi = 0; $bi < sizeof($sqlb); $bi++) {
            $telephoneNumber_f = "";
            $stdCode_f = "";
            $telephoneCountryCode_f = "";
            $mobileCountryCode_f = "";

            $ch5 = $dom->createElement('Executed_In_Favour_Of_PartyDetails');

            $partyNumber_attr_f = new DOMAttr('PartyNumber', $sqlb[$bi][0]['party_id']);
            $ch5->setAttributeNode($partyNumber_attr_f);

            $telephoneNumber_attr_f = new DOMAttr('TelephoneNumber', $telephoneNumber_f);
            $ch5->setAttributeNode($telephoneNumber_attr_f);

            $stdCode_attr_f = new DOMAttr('STDCode', $stdCode_f);
            $ch5->setAttributeNode($stdCode_attr_f);

            $telephoneCountryCode_attr_f = new DOMAttr('TelephoneCountryCode', $telephoneCountryCode_f);
            $ch5->setAttributeNode($telephoneCountryCode_attr_f);

            $mobileNumber_attr_f = new DOMAttr('MobileNumber', $sqlb[$bi][0]['mobile_no']);
            $ch5->setAttributeNode($mobileNumber_attr_f);

            $mobileCountryCode_attr_f = new DOMAttr('MobileCountryCode', $mobileCountryCode_f);
            $ch5->setAttributeNode($mobileCountryCode_attr_f);

            $address_attr_f = new DOMAttr('Address', $sqlb[$bi][0]['address_en']);
            $ch5->setAttributeNode($address_attr_f);

            $fat_Hus_Name_attr_f = new DOMAttr('Fat_Hus_Name', $sqlb[$bi][0]['father_full_name_en']);
            $ch5->setAttributeNode($fat_Hus_Name_attr_f);

            $fullName_attr_f = new DOMAttr('FullName', $sqlb[$bi][0]['party_full_name_en']);
            $ch5->setAttributeNode($fullName_attr_f);

            $child_node_title->appendChild($ch5);
        }

        if ($sqlf != NULL) {
            for ($a = 0; $a < sizeof($sqlf); $a++) {
                $pdate_dt = $sqlf[$a][0]['pdate_dt'];
                if ($pdate_dt != '') {
                    $full_pdate = $sqlf[$a][0]['full_pdate'];
                    $chng_pdate_dt = date("d-m-Y", strtotime($pdate_dt));
                    $tdf = explode(" ", $full_pdate);
                    $eChalan_Date = $chng_pdate_dt . ' ' . $tdf[1];
                } else {
                    $eChalan_Date = '00-00-00 00:00:00';
                }

                $ch10 = $dom->createElement('Mutation_fee_Details');

                $mutation_Fee_attr1 = new DOMAttr('Mutation_Fee', $sqlf[$a][0]['pamount']);
                $ch10->setAttributeNode($mutation_Fee_attr1);

                $eChalan_Date_attr1 = new DOMAttr('EChalan_Date', $eChalan_Date);
                $ch10->setAttributeNode($eChalan_Date_attr1);

                $eChalan_No_attr1 = new DOMAttr('EChalan_No', $sqlf[$a][0]['certificate_no']);
                $ch10->setAttributeNode($eChalan_No_attr1);

                $child_node_title->appendChild($ch10);
            }
        } else {
            $pamount = 0;
            $certificate_no = 'NA';
            $eChalan_Date = '00-00-00 00:00:00';
            $ch10 = $dom->createElement('Mutation_fee_Details');

            $mutation_Fee_attr1 = new DOMAttr('Mutation_Fee', $pamount);
            $ch10->setAttributeNode($mutation_Fee_attr1);

            $eChalan_Date_attr1 = new DOMAttr('EChalan_Date', $eChalan_Date);
            $ch10->setAttributeNode($eChalan_Date_attr1);

            $eChalan_No_attr1 = new DOMAttr('EChalan_No', $certificate_no);
            $ch10->setAttributeNode($eChalan_No_attr1);

            $child_node_title->appendChild($ch10);
        }



        $ch7 = $dom->createElement('Property_Schedules');
        $ch7_attr1 = new DOMAttr('ScheduleName', $scheduleName);
        $ch7->setAttributeNode($ch7_attr1);

        $ch7_attr2 = new DOMAttr('ScheduleArea', $sqlp[0][0]['areaval']);
        $ch7->setAttributeNode($ch7_attr2);

        $ch7_attr3 = new DOMAttr('ProportionateRight', $sqlp[0][0]['areaval']);
        $ch7->setAttributeNode($ch7_attr3);

        $ch7_attr4 = new DOMAttr('ProportionateRightUnit', $sqlp[0][0]['unit_desc_en']);
        $ch7->setAttributeNode($ch7_attr4);

        $ch7_attr5 = new DOMAttr('AreaMeasurementUnit', $sqlp[0][0]['unit_desc_en']);
        $ch7->setAttributeNode($ch7_attr5);

        $ch2->appendChild($ch7);

        for ($aj = 0; $aj < sizeof($documentrecord); $aj++) {
            $ch8 = $dom->createElement('Executer_of_Document_PartyNumbers');
            $ch8_attr = new DOMAttr('PartyNumber', $documentrecord[$aj][0]['party_id']);
            $ch8->setAttributeNode($ch8_attr);
            $ch7->appendChild($ch8);
        }
        for ($bj = 0; $bj < sizeof($sqlb); $bj++) {
            $ch9 = $dom->createElement('Executed_In_Favour_Of_PartyNumbers');
            $ch9_attr = new DOMAttr('PartyNumber', $sqlb[$bj][0]['party_id']);
            $ch9->setAttributeNode($ch9_attr);
            $ch7->appendChild($ch9);
        }

        $root->appendChild($main_node);

        $dom->appendChild($root);


        return $dom->saveHTML();
    }

    public function gras_payment_entry_new_old() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');

        $mapping = $this->BankPayment->mapping_account_heads(11, 3);

        $fieldlist = array();

        $fieldlist['token_no']['text'] = 'is_required,is_numeric';
        $fieldlist['payee_fname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['payee_lname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['email_id']['text'] = 'is_required,is_email';
        $fieldlist['mobile']['text'] = 'is_required,is_phone';
        $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pincode']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pamount']['text'] = 'is_required,is_numeric';
        $fieldlist['feetype']['text'] = 'is_required,is_select_req';





        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 19);
        //echo $txnid;

        if ($this->request->is('post')) {
            $data = $this->request->data;
            $errarr = $this->validatedata($data, $fieldlist);
            //pr($errarr);exit;
            if ($this->ValidationError($errarr)) {

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }


                $result = $this->genernalinfoentry->find("all", array(
                    'conditions' => array('token_no' => trim($data['token_no']))
                ));

                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }


                do {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                    $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                } while (!empty($check));



                $data['payment_mode_id'] = 11;
                $data['transaction_id'] = $txnid;


                $usertype = $this->Session->read("session_usertype");
                $savedata['user_type'] = $usertype;
                $now = date('Y-m-d H:i:s');
                $data['state_id'] = $this->Auth->user('state_id');
                if ($usertype == 'C') {
                    $data['user_id'] = $this->Auth->user('user_id');
                    $data['created'] = $now;
                } elseif ($usertype == 'O') {
                    $data['org_user_id'] = $this->Auth->user('user_id');
                    $data['org_created'] = $now;
                }
                if ($data['feetype'] == 1) {
                    $data['account_head_desc'] = '0030063301|9999001';
                } else if ($data['feetype'] == 2) {
                    $data['account_head_desc'] = '48';
                } else {
                    $this->Session->setFlash(
                            __('Account head not found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }

                if ($this->BankPayment->save($data)) {
                    try {
                        $client = new SoapClient($bankapi['interface_url']);
                        $pushdata = array(
                            'webUser' => $bankapi['interface_user_id'],
                            'webPass' => $bankapi['interface_password'],
                            'No_of_fee_items' => 1,
                            'Feeamt' => $data['pamount'],
                            'mobile' => $data['mobile'],
                            'Partyname' => $data['payee_fname_en'],
                            'Party_Address' => $data['address'],
                            'Party_PIN' => $data['pincode'],
                            'email' => $data['email_id'],
                            'Party_taluka' => $data['city'],
                            'IPAddress' => @$this->request->clientIp(),
                            'Reason' => 'NGDRS Fee Colletion for token ' . $data['token_no'],
                            'OtherDetails' => $data['token_no'],
                                // 'RuralUrbanFlag' => 'RURALNORTH'
                        );
                        // pr($pushdata);exit;

                        if ($data['feetype'] == 1) {
                            $pushdata['RuralUrbanFlag'] = 'NOTARY';
                            $result = $client->Generate_eChallan_RegFee($pushdata);
                            $servicedata = (string) $result->Generate_eChallan_RegFeeResult;
                        } else {
                            $prop = $this->genernalinfoentry->query("select district_id,developed_land_types_id from ngdrstab_trn_property_details_entry where token_no=?", array($data['token_no']));
                            if (empty($prop)) {
                                $this->Session->setFlash(
                                        __('Property not found!')
                                );
                                $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                            }
                            $flagstr = '';
                            if ($prop[0][0]['developed_land_types_id'] == 1) {
                                $flagstr.='URBAN';
                            } elseif ($prop[0][0]['developed_land_types_id'] == 2) {
                                $flagstr.='RURAL';
                            } else {
                                $this->Session->setFlash(
                                        __('Land type not found')
                                );
                                $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                            }

                            if ($prop[0][0]['district_id'] == 1 && $prop[0][0]['developed_land_types_id'] == 2) {
                                $flagstr.='SOUTH';
                            } elseif ($prop[0][0]['district_id'] == 2 && $prop[0][0]['developed_land_types_id'] == 2) {
                                $flagstr.='SOUTH';
                            }

                            $pushdata['RuralUrbanFlag'] = $flagstr;
                            //pr($pushdata);exit;
                            $result = $client->Generate_eChallan_MutationFee($pushdata);
                            $servicedata = (string) $result->Generate_eChallan_MutationFeeResult;
                        }


                        //  $file = fopen("D:/challan.txt", "w");
                        //  fwrite($file, $servicedata);
                        // fclose($file);
                        $start = substr(trim($servicedata), 0, 1);
                        if ($start != '<') {
                            $this->Session->setFlash(
                                    __('Service Return - ' . $servicedata)
                            );
                            $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                        }

                        $xml = simplexml_load_string($servicedata, "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xml);
                        $array = json_decode($json, TRUE);
                        // pr($array);exit;


                        $file = $basepath . "webservice_files/" . $txnid . '_challan.pdf';
                        if (file_put_contents($file, base64_decode($array['Table1']['filebytes']))) {
                            //  Table1/eno
                            $this->BankPayment->query("update ngdrstab_trn_bank_payment set gateway_trans_id=?, payment_status=? where transaction_id=? ", array($array['Table1']['eno'], 'SUCCESS', $txnid));
                            if (file_exists($file)) {
                                $this->Session->setFlash(
                                        __('Successfully created challan.')
                                );
                            } else {
                                $this->Session->setFlash(
                                        __('Unable create Challan ')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Unable create Challan ')
                            );
                        }
                    } catch (SoapFault $e) {
                        $this->Session->setFlash(
                                __('Unable to connect web service ' . $e->getMessage())
                        );
                    }
                }
            } else {
                $this->Session->setFlash(
                        __('Please Check Validations')
                );
            }
        } //post

        $this->Auth->user('user_id');
        $usertype = $this->Session->read("session_usertype");
        $result = array();
        $this->Auth->user('user_id');
        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'token_no' => $this->Session->read("Selectedtoken")),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'token_no' => $this->Session->read("Selectedtoken")),
                'order' => array('trn_id DESC'),
            ));
        }



        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping'));
    }

    public function gras_payment_entry_new($csrftoken = NULL, $challan_no = NULL) {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $this->loadModel('payment');



        $fieldlist = array();

        $fieldlist['token_no']['text'] = 'is_required,is_numeric';
        $fieldlist['payee_fname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['payee_lname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['email_id']['text'] = 'is_required,is_email';
        $fieldlist['mobile']['text'] = 'is_required,is_mobileindian';
        $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pincode']['text'] = 'is_required,is_pincode';
        $fieldlist['feetype']['text'] = 'is_required,is_select_req';
        $fieldlist['feeflag']['text'] = 'is_required,is_select_req';
        $fieldlist['pamount']['text'] = 'is_required,is_numeric';

        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if (!is_null($challan_no)) {
            $data['certificate_no'] = $challan_no;
            $param['token_no'] = $this->Session->read("Selectedtoken");
            $param['article_id'] = $this->Session->read("article_id");
            $param['lang'] = 'en';
            $param['StatusOnly'] = 'Y';
            // pr($param);exit;
            $result = $this->EchallanVerification($data, $param);
            if (empty($result['Error'])) {
                // pr($result);exit;
                // payment_date
                $pdate = NULL;
                $cin_no = NULL;
                if (isset($result['payment_date'])) {
                    $pdate = $result['payment_date'];
                }
                if (isset($result['cin_no'])) {
                    $cin_no = $result['cin_no'];
                }


                $update = $this->BankPayment->query("update ngdrstab_trn_bank_payment set  payment_status=? , bank_trn_ref_number=? ,  pdate=? where gateway_trans_id=?  ", array('SUCCESS', $cin_no, $pdate, $challan_no));
                if (empty($update)) {
                    $this->Session->setFlash(
                            __('Payment verified successfully')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                } else {
                    $this->Session->setFlash(
                            __('Failed to update Payment status')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }
            } else {
                $this->Session->setFlash(
                        __('Error : ' . $result['Error'])
                );
                $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
            }
        }



        if ($this->request->is('post')) {
            $data = $this->request->data;
            $errarr = $this->validatedata($data, $fieldlist);
            //pr($errarr);exit;
            if ($this->ValidationError($errarr)) {

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }


                $result = $this->genernalinfoentry->find("all", array(
                    'conditions' => array('token_no' => trim($data['token_no']))
                ));

                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }


                do {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                    $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                } while (!empty($check));



                $data['payment_mode_id'] = 11;
                $data['transaction_id'] = $txnid;


                $usertype = $this->Session->read("session_usertype");
                $savedata['user_type'] = $usertype;
                $now = date('Y-m-d H:i:s');
                $data['state_id'] = $this->Auth->user('state_id');
                if ($usertype == 'C') {
                    $data['user_id'] = $this->Auth->user('user_id');
                    $data['created'] = $now;
                } elseif ($usertype == 'O') {
                    $data['org_user_id'] = $this->Auth->user('user_id');
                    $data['org_created'] = $now;
                }
                if ($data['feetype'] == 1) {
                    $data['account_head_desc'] = '0030063301|9999001';
                } else if ($data['feetype'] == 2) {
                    $data['account_head_desc'] = '48';
                } else {
                    $this->Session->setFlash(
                            __('Account head not found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }

                $flags = array('1' => 'NOTARY', '2' => 'URBAN', '3' => 'RURALNORTH', '4' => 'RURALSOUTH');
                $data['RuralUrbanFlag'] = @$flags[$data['feeflag']];


                if (empty($data['RuralUrbanFlag'])) {
                    $this->Session->setFlash(
                            __('RuralUrbanFlag Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }


                try {
                    $client = new SoapClient($bankapi['interface_url']);
                    $pushdata = array(
                        'webUser' => $bankapi['interface_user_id'],
                        'webPass' => $bankapi['interface_password'],
                        'No_of_fee_items' => 1,
                        'Feeamt' => $data['pamount'],
                        'mobile' => $data['mobile'],
                        'Partyname' => $data['payee_fname_en'],
                        'Party_Address' => $data['address'],
                        'Party_PIN' => $data['pincode'],
                        'email' => $data['email_id'],
                        'Party_taluka' => $data['city'],
                        'IPAddress' => @$this->request->clientIp(),
                        'Reason' => 'NGDRS Fee Colletion for token ' . $data['token_no'],
                        'OtherDetails' => $data['token_no'] . " " . $data['RuralUrbanFlag'],
                        'RuralUrbanFlag' => $data['RuralUrbanFlag']
                    );
                    // pr($pushdata);exit;



                    if ($data['feetype'] == 1) {
                        $result = $client->Generate_eChallan_RegFee($pushdata);
                        $servicedata = (string) $result->Generate_eChallan_RegFeeResult;
                    } else {
                        $result = $client->Generate_eChallan_MutationFee($pushdata);
                        $servicedata = (string) $result->Generate_eChallan_MutationFeeResult;
                    }


                    //  $file = fopen("D:/challan.txt", "w");
                    //  fwrite($file, $servicedata);
                    // fclose($file);
                    $start = substr(trim($servicedata), 0, 1);
                    if ($start != '<') {
                        $this->Session->setFlash(
                                __('Service Return - ' . $servicedata)
                        );
                        $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                    }

                    $xml = simplexml_load_string($servicedata, "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $array = json_decode($json, TRUE);
                    // pr($array);exit;


                    $file = $basepath . "webservice_files/" . $txnid . '_challan.pdf';
                    if (file_put_contents($file, base64_decode($array['Table1']['filebytes']))) {
                        //  Table1/eno
                        if (file_exists($file)) {
                            $data['gateway_trans_id'] = $array['Table1']['eno'];
                            $data['payment_status'] = 'CREATED';
                            $data['transaction_id'] = $txnid;
                            $data['udf1'] = $data['RuralUrbanFlag'];

                            if ($this->BankPayment->save($data)) {
                                //    pr($data);exit;
                                $this->Session->setFlash(
                                        __('Successfully created challan.')
                                );
                            } else {
                                $this->Session->setFlash(
                                        __('Unable create Challan ')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Unable create Challan ')
                            );
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Unable create Challan ')
                        );
                    }
                } catch (SoapFault $e) {
                    $this->Session->setFlash(
                            __('Unable to connect web service ' . $e->getMessage())
                    );
                }
            } else {
                $this->Session->setFlash(
                        __('Please Check Validations')
                );
            }
        } //post

        $this->Auth->user('user_id');
        $usertype = $this->Session->read("session_usertype");
        $result = array();
        $this->Auth->user('user_id');
        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'token_no' => $this->Session->read("Selectedtoken")),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'token_no' => $this->Session->read("Selectedtoken")),
                'order' => array('trn_id DESC'),
            ));
        }

        $fees = $this->payment->stampduty_fee_details($this->Session->read("Selectedtoken"), 'en', $this->Session->read('article_id'));

        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping', 'fees'));
    }

    public function gras_payment_entry_new_fees($challan_no = NULL) {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $this->loadModel('payment');



        $fieldlist = array();

        $fieldlist['token_no']['text'] = 'is_required,is_numeric';
        $fieldlist['payee_fname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['payee_lname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['email_id']['text'] = 'is_required,is_email';
        $fieldlist['mobile']['text'] = 'is_required,is_mobileindian';
        $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pincode']['text'] = 'is_required,is_pincode';
        $fieldlist['feetype']['text'] = 'is_required,is_select_req';
        //  $fieldlist['feeflag']['text'] = 'is_required,is_select_req';
        $fieldlist['pamount']['text'] = 'is_required,is_numeric';

        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if (!is_null($challan_no)) {

            $challan = $this->BankPayment->find("first", array(
                'fields' => array('gen.article_id', 'BankPayment.token_no'),
                'joins' => array(
                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no=BankPayment.token_no')),
                ),
                'conditions' => array('gateway_trans_id' => $challan_no),
            ));


            if (empty($challan)) {

                $this->Session->setFlash(
                        __('Challan Not Found')
                );
                $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
            }

            $data['certificate_no'] = $challan_no;
            $param['token_no'] = $challan['BankPayment']['token_no'];
            $param['article_id'] = $challan['gen']['article_id'];
            $param['lang'] = 'en';
            $param['StatusOnly'] = 'Y';
            // pr($param);exit;
            $result = $this->EchallanVerification($data, $param);
            //  pr($result);exit;
            if (empty($result['Error'])) {

                // payment_date
                $pdate = NULL;
                $cin_no = NULL;
                if (isset($result['payment_date'])) {
                    $pdate = $result['payment_date'];
                }
                if (isset($result['cin_no'])) {
                    $cin_no = $result['cin_no'];
                }

                $update = $this->BankPayment->query("update ngdrstab_trn_bank_payment set  payment_status=? , bank_trn_ref_number=? ,  pdate=? where gateway_trans_id=?  ", array('SUCCESS', $cin_no, $pdate, $challan_no));
                if (empty($update)) {
                    $this->Session->setFlash(
                            __('Payment verified successfully')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                } else {
                    $this->Session->setFlash(
                            __('Failed to update Payment status')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                }
            } else {
                $this->Session->setFlash(
                        __('Error : ' . $result['Error'])
                );
                $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
            }
        }



        if ($this->request->is('post')) {
            $data = $this->request->data;
            $errarr = $this->validatedata($data, $fieldlist);
            //pr($errarr);exit;
            if ($this->ValidationError($errarr)) {

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }


                $result = $this->genernalinfoentry->find("all", array(
                    'conditions' => array('token_no' => trim($data['token_no']))
                ));

                if (empty($result)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                }


                do {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                    $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                } while (!empty($check));



                $data['payment_mode_id'] = 11;
                $data['transaction_id'] = $txnid;


                $usertype = $this->Session->read("session_usertype");
                $savedata['user_type'] = $usertype;
                $now = date('Y-m-d H:i:s');
                $data['state_id'] = $this->Auth->user('state_id');
                if ($usertype == 'C') {
                    $data['user_id'] = $this->Auth->user('user_id');
                    $data['created'] = $now;
                } elseif ($usertype == 'O') {
                    $data['org_user_id'] = $this->Auth->user('user_id');
                    $data['org_created'] = $now;
                }
                $Reason = '';
                if ($data['feetype'] == 3) {
                    $data['account_head_desc'] = '32';
                    $Reason = 'Certified copy Fee';
                } else {
                    $this->Session->setFlash(
                            __('Account head not found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                }

                $flags = array('1' => 'NOTARY', '2' => 'URBAN', '3' => 'RURALNORTH', '4' => 'RURALSOUTH');
                $data['RuralUrbanFlag'] = @$flags[$data['feeflag']];


                try {
                    $client = new SoapClient($bankapi['interface_url']);
                    $pushdata = array(
                        'webUser' => $bankapi['interface_user_id'],
                        'webPass' => $bankapi['interface_password'],
                        'No_of_fee_items' => 1,
                        'Feeamt' => $data['pamount'],
                        'mobile' => $data['mobile'],
                        'Partyname' => $data['payee_fname_en'],
                        'Party_Address' => $data['address'],
                        'Party_PIN' => $data['pincode'],
                        'email' => $data['email_id'],
                        'Party_taluka' => $data['city'],
                        'IPAddress' => @$this->request->clientIp(),
                        'Reason' => 'NGDRS ' . $Reason . ' Colletion for token ' . $data['token_no'],
                        'OtherDetails' => $data['token_no'],
                    );
                    // pr($pushdata);exit;



                    if ($data['feetype'] == 3) {
                        $result = $client->Generate_eChallan_certcopy($pushdata);
                        $servicedata = (string) $result->Generate_eChallan_certcopyResult;
                    }


                    //  $file = fopen("D:/challan.txt", "w");
                    //  fwrite($file, $servicedata);
                    // fclose($file);
                    $start = substr(trim($servicedata), 0, 1);
                    if ($start != '<') {
                        $this->Session->setFlash(
                                __('Service Return - ' . $servicedata)
                        );
                        $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new_fees'));
                    }

                    $xml = simplexml_load_string($servicedata, "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $array = json_decode($json, TRUE);
                    // pr($array);exit;


                    $file = $basepath . "webservice_files/" . $txnid . '_challan.pdf';
                    if (file_put_contents($file, base64_decode($array['Table1']['filebytes']))) {
                        //  Table1/eno
                        if (file_exists($file)) {
                            $data['gateway_trans_id'] = $array['Table1']['eno'];
                            $data['payment_status'] = 'CREATED';
                            $data['transaction_id'] = $txnid;
                            $data['udf1'] = $data['RuralUrbanFlag'];

                            if ($this->BankPayment->save($data)) {
                                //    pr($data);exit;
                                $this->Session->setFlash(
                                        __('Successfully created challan.')
                                );
                            } else {
                                $this->Session->setFlash(
                                        __('Unable create Challan ')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Unable create Challan ')
                            );
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Unable create Challan ')
                        );
                    }
                } catch (SoapFault $e) {
                    $this->Session->setFlash(
                            __('Unable to connect web service ' . $e->getMessage())
                    );
                }
            } else {
                $this->Session->setFlash(
                        __('Please Check Validations')
                );
            }
        } //post


        $usertype = $this->Session->read("session_usertype");
        $result = array();
        $user_id = $this->Auth->user('user_id');
        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'user_id' => $user_id),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('payment_mode_id' => 11, 'org_user_id' => $user_id),
                'order' => array('trn_id DESC'),
            ));
        }

//        $fees = $this->payment->stampduty_fee_details($this->Session->read("Selectedtoken"), 'en', $this->Session->read('article_id'));

        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping', 'fees'));
    }

    public function echallan_payment_receipt($data = NULL) {

        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $response['Error'] = '';
        //  $data['eChallan_No'] = '201900021996';
        if (empty($data) && !isset($data['eChallan_No'])) {
            $response['Error'] = 'Challan number parameter  not found';
            return $response;
        }
        $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
        if (empty($bankapi)) {
            $response['Error'] = 'Bank Api Not Found';
            return $response;
        } else {
            $bankapi = $bankapi['external_interface'];
        }

        $path = $this->file_config->find('first', array('fields' => array('filepath')));
        if (empty($path)) {
            $response['Error'] = 'Base Path Not Found';
            return $response;
        } else {
            $basepath = $path['file_config']['filepath'];
        }
        try {

            App::import('Vendor', 'Soap/nusoap');
            $client = new nusoap_client($bankapi['interface_url'], true);
            $pushdata = array(
                'webUser' => $bankapi['interface_user_id'],
                'webPass' => $bankapi['interface_password'],
                'eChallan_No' => $data['eChallan_No'],
                'Rural_Urban_Notary' => 'NOTARY'
            );

            $result = $client->call('eChallan_Receipt', $pushdata);
            if (is_array($result) && isset($result['eChallan_ReceiptResult'])) {
                $name = 'grn_' . $data['eChallan_No'] . '.pdf';
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen(base64_decode($result['eChallan_ReceiptResult'])));
                header('Content-disposition: inline; filename="' . $name . '"');
                header('Cache-Control: public, must-revalidate, max-age=0');
                header('Pragma: public');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                echo base64_decode($result['eChallan_ReceiptResult']);
                exit;
            } elseif (!empty($result)) {
                $response['Error'] = 'Unable create Receipt';
                return $response;
            } else {
                $response['Error'] = 'Unable create Receipt';
                return $response;
            }
        } catch (SoapFault $e) {
            $response['Error'] = 'Unable to connect web service : ' . $e->getMessage();
            return $response;
        }
    }

    public function EchallanReceipt($challan_no) {
        $this->autoRender = FALSE;
        $data['eChallan_No'] = $challan_no;
        $response = $this->echallan_payment_receipt($data);
        if (isset($response['Error'])) {
            
        } else {
            echo '' . $response['Error'];
        }
    }

    public function mutation_manually($token = NULL) {

        $this->loadModel('ApplicationSubmitted');

        if (!is_null($token)) {
            $response = $this->mutation($token);
            if (isset($response['STATUS']) && $response['STATUS'] == 1) {
                $now = date('Y-m-d H:i:s');
                $this->ApplicationSubmitted->query("update ngdrstab_trn_application_submitted set mutation_flag='Y', mutation_date =? where token_no=?", array($now, $token));
                $this->Session->setFlash(__('Data Pushed Sucessfully')
                );
            } else {
                $this->Session->setFlash(__('Error: ' . $response['Error'])
                );
            }

            return $this->redirect(array('controller' => 'GAWebService', 'action' => 'mutation_manually'));
        }

        $result = $this->ApplicationSubmitted->query("select app.token_no,article.article_desc_en,mutation_flag from ngdrstab_trn_application_submitted   as app

JOIN ngdrstab_trn_generalinformation as info ON info.token_no=app.token_no
JOIN ngdrstab_mst_article  as article ON article.article_id=info.article_id
JOIN ngdrstab_trn_payment_details as pay ON pay.token_no=info.token_no and account_head_code='48'
where mutation_flag='N' and final_stamp_flag='Y' and property_applicable='Y'");


        $this->set(compact('result'));
    }

    public function ngdrsgoaapi1() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->autoRender = FALSE;
        $response = array();
        $errorcodes = array(
            '1' => 'Failed for validation',
            '2' => 'Authention Failed',
            '3' => 'No Data Found',
            '4' => 'The maximum record limit(100) is exceeded. Please add more conditions',
            '5' => 'Parametes not found',
            '6' => 'Someting Went Wrong'
        );
        $newsXML = new SimpleXMLElement("<DocumentElement></DocumentElement>");
//        $newsXML->addAttribute('time', date('Y-m-d H:i:sa'));

        try {
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('propertydetails');
            $this->loadModel('ApiCredentials');
            $this->loadModel('party_entry');


            //echo 'hi';exit;
            if ($this->request->is('post')) {
                $API_CODE = 'API00001';
                $fieldlist['api_code']['text'] = 'is_alphanumeric';
                $fieldlist['api_username']['text'] = 'is_username';
                $fieldlist['api_password']['text'] = 'is_alphanumeric';

                $fieldlist['Doc_Reg_Number']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
                $fieldlist['Doc_Reg_Date']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
                $fieldlist['Mutation_Date']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';

                $fieldlist['Taluka_Name']['text'] = 'is_required,is_alphanumspacedashdotcommaroundbrackets';
                $fieldlist['Village_Name']['text'] = 'is_required,is_alphanumspacedashdotcommaroundbrackets';
                $fieldlist['Buyer_Name']['text'] = 'is_alphanumericspace';
                $fieldlist['Seller_Name']['text'] = 'is_alphanumericspace';

                $fieldlist['Survey_Number']['text'] = 'is_required,is_numeric';
                $fieldlist['Subdivision_Number']['text'] = 'is_required,is_alphanumdashslash';

                $data = $this->request->data;
                $data['api_code'] = $API_CODE;
                // pr($data);exit;
                $verrors = $this->validatedata($data, $fieldlist);

                if ($this->ValidationError($verrors)) {

                    if ($this->ApiCredentials->authenticate($data)) {

                        $sql = "  SELECT app.token_no 
FROM   ngdrstab_trn_application_submitted AS app 
       JOIN ngdrstab_trn_property_details_entry AS prop 
         ON prop.token_no = app.token_no ";
                        if (isset($data['Taluka_Name']) && !empty($data['Taluka_Name'])) {
                            $sql.= "  JOIN ngdrstab_conf_admblock5_taluka AS taluka 
         ON taluka.taluka_id = prop.taluka_id 
            AND taluka.taluka_name_en   = '" . $data['Taluka_Name'] . "' ";
                        }

                        if (isset($data['Village_Name']) && !empty($data['Village_Name'])) {
                            $sql.= "  JOIN ngdrstab_conf_admblock7_village_mapping AS village
ON village.village_id = prop.village_id
AND village.village_name_en   = '" . $data['Village_Name'] . "' ";
                        }

                        if (isset($data['Survey_Number']) && !empty($data['Survey_Number'])) {
                            $sql.= "  JOIN ngdrstab_trn_parameter AS trnparam
ON trnparam.property_id = prop.property_id
AND trnparam.paramter_id = 205
AND trnparam.paramter_value = '" . $data['Survey_Number'] . "'
AND parameter_type = 'S'
JOIN ngdrstab_mst_attribute_parameter AS mstparam
ON mstparam.attribute_id = trnparam.paramter_id ";
                        }
                        if (isset($data['Seller_Name']) && !empty($data['Seller_Name'])) {
                            $sql.= "  JOIN ngdrstab_trn_party_entry_new AS partyseller
ON partyseller.token_no = app.token_no
AND partyseller.party_full_name_en LIKE  '%" . $data['Seller_Name'] . "%'
JOIN ngdrstab_mst_party_type AS partysellerptype
ON partysellerptype.party_type_id = partyseller.party_type_id
AND partysellerptype.party_type_flag = '1'";
                        }

                        if (isset($data['Buyer_Name']) && !empty($data['Buyer_Name'])) {
                            $sql.= "  JOIN ngdrstab_trn_party_entry_new AS partyPurchaser
ON partyPurchaser.token_no = app.token_no
AND partyPurchaser.party_full_name_en LIKE  '%" . $data['Buyer_Name'] . "%'
JOIN ngdrstab_mst_party_type AS partyPurchaserptype
ON partyPurchaserptype.party_type_id = partyPurchaser.party_type_id
AND partyPurchaserptype.party_type_flag = '0'";
                        }

                        $sql.= " WHERE final_stamp_flag = 'Y' ";

                        if (isset($data['Doc_Reg_Number']) && !empty($data['Doc_Reg_Number'])) {
                            $sql.= " and final_doc_reg_no = '" . $data['Doc_Reg_Number'] . "' ";
                        }
                        if (isset($data['Doc_Reg_Date']) && !empty($data['Doc_Reg_Date'])) {
                            $sql.= " and final_stamp_date::date = '" . $data['Doc_Reg_Date'] . "' ";
                        }
                        if (isset($data['Mutation_Date']) && !empty($data['Mutation_Date'])) {
                            $sql.= " and mutation_date::date = '" . $data['Mutation_Date'] . "' ";
                        }

                        $sql.= " GROUP BY app.token_no ";

//pr($sql);exit;

                        $results = $this->ApplicationSubmitted->query("SELECT info.token_no,
 article.article_desc_en,
 article.article_id,
 app.final_stamp_date,
 app.final_doc_reg_no,
 String_agg( (
SELECT concat(party_full_name_en, '|', pan_no)
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '1'
WHERE party.token_no = info.token_no and is_presenter = 'Y'
), ',') as presenter,
 (SELECT SUM(cons_amt)
FROM ngdrstab_trn_fee_calculation fee
WHERE fee.token_no = info.token_no 
AND delete_flag = 'N'
AND fee.article_id = article.article_id
) as consideration
FROM ngdrstab_trn_application_submitted AS app
JOIN ngdrstab_trn_generalinformation AS info
ON info.token_no = app.token_no and info.office_id = app.office_id
JOIN ngdrstab_mst_article AS article
ON article.article_id = info.article_id
WHERE app.token_no IN($sql)  GROUP BY info.token_no, article.article_desc_en,
 article.article_id,
 app.final_stamp_date,
 app.final_doc_reg_no  
");


                        if (!empty($results)) {
                            if (count($results) < 100) {
                                foreach ($results as $tokenkey => $result) {
                                    $nodetoken = $newsXML->addChild('Token');
                                    $nodetoken->addChild('DocumentNamea', $result[0]['article_desc_en']);
                                    $nodetoken->addChild('DocumentName', $result[0]['article_desc_en']);
                                    $nodetoken->addChild('RegDate', $result[0]['final_stamp_date']);
                                    $nodetoken->addChild('RegNumber', $result[0]['final_doc_reg_no']);
                                    $nodetoken->addChild('ConsiderationAmt', $result[0]['consideration']);
                                    $nodetoken->addChild('Tenure', 'NA');



                                    $presenter_arr = array();
                                    if (!empty($result[0]['presenter'])) {
                                        $presenter_arr = explode('|', $result[0]['presenter']);
                                    }

                                    $nodetoken->addChild('PresenterName', (isset($presenter_arr[0]) ? $presenter_arr[0] : ' '));
                                    $nodetoken->addChild('PresenterPan', (isset($presenter_arr[1]) ? $presenter_arr[1] : ' '));
                                    $nodeproperties = $nodetoken->addChild('Properties');


                                    $propertydetails = $this->propertydetails->query(" SELECT prop.property_id,
 taluka.taluka_name_en,
 village.village_name_en,
 (
SELECT Sum(final_value)
FROM ngdrstab_trn_valuation_details vd
WHERE vd.val_id = prop.val_id
AND item_type_id = 2
) AS assessment,
 (SELECT cons_amt
FROM ngdrstab_trn_fee_calculation fee
WHERE fee.token_no = ?
AND fee.property_id = prop.property_id
AND delete_flag = 'N'
AND fee.article_id = ?
AND fee.property_id = prop.property_id
) as consideration,
 String_agg((SELECT Concat(Sum(item_value * conversion_formula))
FROM ngdrstab_trn_valuation_details vd
JOIN ngdrstab_mst_usage_items_list item
ON item.usage_param_id = vd.item_id
AND area_field_flag = 'Y'
JOIN ngdrstab_mst_unit unit
ON unit.unit_id = vd.area_unit
WHERE vd.val_id = prop.val_id
AND vd.item_type_id = 1
GROUP BY unit.unit_id), ',') as area,
 (SELECT
String_agg(Concat(mstparam.attribute_id, '$', mstparam.eri_attribute_name, '$', paramter_value), ' $$ ')
FROM ngdrstab_trn_parameter trnparam
JOIN ngdrstab_mst_attribute_parameter AS mstparam
ON mstparam.attribute_id = trnparam.paramter_id
WHERE trnparam.token_id = prop.token_no
AND trnparam.property_id = prop.property_id) as attributes

from ngdrstab_trn_property_details_entry AS prop
JOIN ngdrstab_conf_admblock5_taluka AS taluka
ON taluka.taluka_id = prop.taluka_id
JOIN ngdrstab_conf_admblock7_village_mapping AS village
ON village.village_id = prop.village_id
where token_no = ?
AND prop .val_id > 0 GROUP BY prop.property_id,
 taluka.taluka_name_en,
 village.village_name_en ", array($result[0]['token_no'], $result[0]['article_id'], $result[0]['token_no']));
                                    if (!empty($propertydetails)) {

                                        foreach ($propertydetails as $property) {
                                            //  pr($property);exit;
                                            $nodeproperty = $nodeproperties->addChild("Property");
                                            $nodeproperty->addChild("Taluka", $property[0]['taluka_name_en']);
                                            $nodeproperty->addChild("Village", $property[0]['village_name_en']);
                                            $nodeproperty->addChild("Assessment", $property[0]['assessment']);
                                            $nodeproperty->addChild("PropArea", $property[0]['area']);

                                            $survey_no = ' ';
                                            $subdivision = ' ';
                                            if (!empty($property[0]['attributes'])) {
                                                $attributes_arr = explode("$$", $property[0]['attributes']);
                                                if (is_array($attributes_arr)) {
                                                    foreach ($attributes_arr as $attributes_str_single)
                                                        $attributes_arr_single = explode("$", $attributes_str_single);
                                                    if ($attributes_arr_single[0] == 205) {
                                                        $survey_no = $attributes_arr_single[2];
                                                    }
                                                    if ($attributes_arr_single[0] == 206) {
                                                        $subdivision = $attributes_arr_single[2];
                                                    }
                                                }
                                            }
                                            $nodeproperty->addChild("SurveyNumber", $survey_no);
                                            $nodeproperty->addChild("SubdivisionNumber", $subdivision);

                                            $Purchaser = $nodeproperty->addChild("Purchaser");

                                            // Purchaser
                                            $party_entry = $this->party_entry->query("SELECT party_full_name_en
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '0'
WHERE party.token_no = ? and party.property_id = ? GROUP BY party_full_name_en", array($result[0]['token_no'], $property[0]['property_id']));

                                            if (!empty($party_entry)) {
                                                foreach ($party_entry as $partykey => $party) {
                                                    $Party = $Purchaser->addChild("Party");
                                                    $Party->addChild("PartyName", $party[0]['party_full_name_en']);
                                                }
                                            }

                                            $seller = $nodeproperty->addChild("Seller");
                                            // seller
                                            $party_entry = $this->party_entry->query("SELECT party_full_name_en
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '1'
WHERE party.token_no = ? and party.property_id = ? GROUP BY party_full_name_en ", array($result[0]['token_no'], $property[0]['property_id']));

                                            if (!empty($party_entry)) {

                                                foreach ($party_entry as $partykey => $party) {
                                                    $Party = $seller->addChild("Party");
                                                    $Party->addChild("PartyName", $party[0]['party_full_name_en']);
                                                }     // party
                                            }

                                            $executor = $nodeproperty->addChild("Executor");

                                            $party_entry = $this->party_entry->query("SELECT party_full_name_en, pan_no
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '1'
WHERE party.token_no = ? and is_executer = 'Y' and party.property_id = ? GROUP BY party_full_name_en, party.pan_no ", array($result[0]['token_no'], $property[0]['property_id']));

                                            if (!empty($party_entry)) {
                                                foreach ($party_entry as $partykey => $party) {
                                                    $Party = $executor->addChild("Party");
                                                    $Party->addChild("PartyName", $party[0]['party_full_name_en']);
                                                }     // party
                                            }
                                        } // property 
                                    }
                                }/// token
                                $response['SUCCESS'] = $results;
                            } else {
                                $response['ERROR_CODE'] = '4';
                            }
                        } else {
                            $response['ERROR_CODE'] = '3';
                        }
                    } else {
                        $response['ERROR_CODE'] = '2';
                    }
                } else {
                    $response['ERROR_CODE'] = '1';
                }
            } else {
                $response['ERROR_CODE'] = '5';
            }
        } catch (Exception $exc) {
            $response['ERROR_CODE'] = '6';
            // $response['ERROR_MSG'] = $exc->getMessage();
        }

        if (isset($response['ERROR_CODE'])) {
            $newsXML->addChild("ERROR_CODE", $response['ERROR_CODE']);
            $newsXML->addChild("ERROR_DESC", @$errorcodes[$response['ERROR_CODE']] . " : " . @$response['ERROR_MSG']);
        }
        $file = fopen("/home/test1.txt", "w");
        fwrite($file, $newsXML->asXML());
//        fclose($file);
//        ob_start();
//        ob_get_clean();
        $this->response->type('text/xml');
        echo trim($newsXML->asXML());
        exit;
    }

    public function ngdrsgoaapi1_test() {




        if ($this->request->is('post')) {
            $some_data = $this->request->data;
//            $some_data = array(
//                'api_username' => 'test',
//                'api_password' => 'test',
//                'Doc_Reg_Number' => '', //QPM-1-1-2018
//                'Doc_Reg_Date' => '', //2018-12-13
//                'Taluka_Name' => '', //'Quepem'
//                'Village_Name' => '', // Cacora
//                'Seller_Name' => '', //Bhavani
//                'Buyer_Name' => '', //Shiva
//                'Mutation_Date' => '', //2018-12-13
//                'Survey_Number' => '', //354
//                'Subdivision_Number' => '', //8
//            );

            $curl = curl_init();
            // You can also set the URL you want to communicate with by doing this:
            // $curl = curl_init('http://localhost/echoservice');
            // We POST the data
            curl_setopt($curl, CURLOPT_POST, 1);
            // Set the url path we want to call
            curl_setopt($curl, CURLOPT_URL, 'https://ngdrsgoa.gov.in/GAWebService/ngdrsgoaapi1');
            // Make it so the data coming back is put into a string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Insert the data
            curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

            // You can also bunch the above commands into an array if you choose using: curl_setopt_array
            // Send the request
            $result = curl_exec($curl);
            // pr($result);
            $file = fopen("/home/test.txt", "w");
            fwrite($file, $result);
            fclose($file);
            // Get some cURL session information back
            $info = curl_getinfo($curl);

//        echo 'content type: ' . $info['content_type'] . '<br />';
//        echo 'http code: ' . $info['http_code'] . '<br />';
            // Free up the resources $curl is using
            curl_close($curl);
            // $this->layout = 'ajax';
            $this->autoRender = FALSE;
//            $this->response->type('text/xml');
            echo $result;

//pr($result);
            // $this->set("result", $result);
        }
    }

    public function EchallanVerification_test($data = NULL, $extrafields = NULL) {

        $this->autoRender = FALSE;

        $extrafields['token_no'] = 201900001500;
        $extrafields['article_id'] = 46;
        $data['certificate_no'] = 201900818718;
        $extrafields['lang'] = 'en';
        $extrafields['StatusOnly'] = 'Y';
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $certid = trim(@$data['certificate_no']);

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($certid)) {
                $response['Error'] = 'Provide Challan Number ';
                return $response;
            }


            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            $challandetails = $this->BankPayment->find("first", array('fields' => 'account_head_desc,udf1,payment_mode_id', 'conditions' => array('gateway_trans_id' => $certid, 'token_no' => $token)));
            if (empty($challandetails)) {
                $response['Error'] = 'Challan not valid for this token';
                return $response;
            }
            $challanacc = explode("|", $challandetails['BankPayment']['account_head_desc']);
            //pr($challandetails);exit;
            $Rural_Urban = $challandetails['BankPayment']['udf1'];
            $data['payment_mode_id'] = $challandetails['BankPayment']['payment_mode_id'];

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('account_head_code' => $challanacc, 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            //pr($acchead);
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            //pr($accounthead);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }
            if (!isset($extrafields['StatusOnly'])) {
                $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
                if (!empty($onlinepay)) {
                    $response['Error'] = 'Challan Already Exist In Verified List';
                    return $response;
                }
            }

            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }
            $requestdata['webUser'] = $bankapi['interface_user_id'];
            $requestdata['webPass'] = $bankapi['interface_password'];
            $requestdata['eChallan_No'] = $certid;
            $requestdata['Rural_Urban_Notary'] = $Rural_Urban;
            try {
                App::import('Vendor', 'Soap/nusoap');
                $client = new nusoap_client($bankapi['interface_url'], true);
                $servicedata = $client->call('eChallan_Payment_Status', $requestdata);
                // pr($servicedata);exit;
                if (is_array($servicedata)) {
                    $servicedata = str_replace('utf-16', 'utf-8', $servicedata);

                    $start = substr(trim($servicedata['eChallan_Payment_StatusResult']), 0, 1);
                    if ($start != '<') {                                            
                        $response['Error'] = 'Echallan Service not working';
                        return $response;
                    }
                    $xml = simplexml_load_string($servicedata['eChallan_Payment_StatusResult'], "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $result = json_decode($json, TRUE);
                    //  pr($result);
                    // exit;
                    if (is_array($result)) {

                        if (isset($result['eChallanStatusResponse'])) {
                            $result = $result['eChallanStatusResponse'];
                        }
                        if (isset($result['Error']) && strcmp(trim($result['Error']), 'Y') == 0) {
                            $response['Error'] = 'Status : ' . $result['ErrorDesc'];
                            return $response;
                        }
                        if (strcmp(trim($result['status']), 'F') == 0) {
                            $response['Error'] = 'Status : Transaction Failed';
                            return $response;
                        }
                        if (strcmp(trim($result['status']), 'P') == 0) {
                            $response['Error'] = 'Status : Transaction is Pending, check after 30 minutes';
                            return $response;
                        }
                        // pr(strcmp(trim($result['status']),'Y' ));
                        // pr($result);exit;
                        if (strcmp(trim($result['status']), 'S') == 0 || strcmp(trim($result['status']), 'Y') == 0) {
                            // Only For verification Status
                            if (isset($extrafields['StatusOnly']) && $extrafields['StatusOnly'] == 'Y') {
                                $vdata['STATUS'] = 'Y';
                                $vdata['AMOUNT'] = trim(str_replace(',', '', $result['totalAmount']));
                                if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {
                                    $rdate = explode(' ', $result['bankReceiveDate']);
                                    $rdate = explode('/', $rdate[0]);
                                    $vdata['payment_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate //CertificateIssuedDate
                                    $vdata['cin_no'] = $result['sbiReferenceNo'];
                                } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                    $rdate = explode(' ', $result['treasuryReceiveDate']);
                                    $rdate = explode('/', $rdate[0]);
                                    $vdata['payment_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate //CertificateIssuedDate
                                    $vdata['cin_no'] = "NA - Paid By treasury";
                                }

                                return $vdata;
                            }



                            $PaymentData = array();
                            $onlinedata = array();
//                        $result['eChallanPaidAmt'] = 15449;
                            $totalfee = trim(str_replace(',', '', $result['totalAmount']));
                            // $totalfee = 1050002;
                            // pr($result);exit;
                            foreach ($acchead as $itemid => $headcode) {
                                foreach ($accounthead as $key => $single) {

                                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                                        $insertdata = array();
                                        $insertdata = array_merge($insertdata, $extrafields);
                                        $insertdata['online_verified_flag'] = 'Y';
                                        $insertdata['defacement_flag'] = 'Y';
                                        $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                                        $insertdata['certificate_no'] = $result['echallanNo']; //echallanNo
                                        $insertdata['estamp_vender_name'] = $Rural_Urban; //Rural_Urban flag

                                        $insertdata['account_head_code'] = $headcode;
                                        $insertdata['pdate'] = date('Y-m-d H:i:s');
                                        if (isset($result['bankReceiveDate']) && !empty($result['bankReceiveDate'])) {

                                            $rdate = explode(' ', $result['bankReceiveDate']);
                                            $rdate = explode('/', $rdate[0]);
                                            $insertdata['estamp_issue_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate
                                            $insertdata['cin_no'] = $result['sbiReferenceNo'];
                                        } else if (isset($result['treasuryReceiveDate']) && !empty($result['treasuryReceiveDate'])) {
                                            $rdate = explode(' ', $result['treasuryReceiveDate']);
                                            $rdate = explode('/', $rdate[0]);
                                            $insertdata['estamp_issue_date'] = $rdate[2] . "-" . $rdate[1] . "-" . $rdate[0]; //CertificateIssuedDate                                        
                                            $insertdata['cin_no'] = "NA - Paid By treasury";
                                        }

                                        $paidamt = 0;
                                        foreach ($paidamount as $paidamountsingle) {
                                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                                $paidamt += $paidamountsingle['payment']['pamount'];
                                            }
                                        }
                                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                                        switch ($itemid) {
                                            case 1:
                                                // REG Fee
                                                if ($totalfee >= $single[0]['totalsd']) {
                                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                                } else {
                                                    $insertdata['pamount'] = $totalfee;
                                                    $totalfee = 0;
                                                }
                                                break;
                                            case 48:
                                                // Mutation Fee
                                                if ($totalfee >= $single[0]['totalsd']) {
                                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                                    $totalfee = $totalfee - $single[0]['totalsd'];
                                                } else {
                                                    $insertdata['pamount'] = $totalfee;
                                                    $totalfee = 0;
                                                }
                                                break;
                                            case 100:
                                                //Processing Fee
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
                            $onlinedata['pamount'] = trim(str_replace(',', '', $result['totalAmount']));
                            $res_string = '';
                            foreach ($result as $key => $value) {
                                if (is_array($value)) {
                                    $res_string .= " , " . $key . ":" . implode('-', $value);
                                } else {
                                    $res_string .= " , " . $key . ":" . $value;
                                }
                            }
                            if (strlen($res_string) > 0) {
                                $onlinedata['gras_account_details'] = substr($res_string, 2);
                            }

                            if (!empty($PaymentData)) {
                                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                                $response['PaymentData'] = $PaymentData;
                                $response['OnlinePaymentData'] = $onlinedata;
                            } else {
                                $response['Error'] = 'Amount already paid for this account head code or fee not calculated';
                            }
                            //pr($response);exit;
                            return $response;
                        } else {
                            $response['Error'] = 'Something went wrong';
                        }
                    } else {
                        $response['Error'] = 'Invalid Service Responce';
                        return $response;
                    }
                } else {
                    $response['Error'] = 'Invalid Service Responce';
                    return $response;
                }
            } catch (SoapFault $sf) {
                $response['Error'] = 'Not able to connect webservice';
                return $response;
            }
        }
    }

}
