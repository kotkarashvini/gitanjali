<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JHWebServiceController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }

        $this->response->disableCache();
        $this->Auth->allow('gras_payment_entry', 'gras_payment_response', 'mutationws', 'mutation_manually', 'mutationwss','ngdrsjhapi2');
        $this->request->addDetector('ssl', array('callback' => function() {
                return CakeRequest::header('X-Forwarded-Proto') == 'https';
            }));

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
        $this->Auth->allow('mutationwss');
    }

    //put your code here
    public function estamp_certificate_verification_OLD($data = NULL, $extrafields = NULL) {
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

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(2), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'Certificate Already Exist In Verified List';
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 9)));
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

            $jarpath = $basepath . "jar_files/estampverification.jar";
            //$jarpath = $basepath . "jar_files/test.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Jar File Not Found!';
                return $response;
            }

            $jarmessage = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }
            if ($result[1] == 1) {
                $response['Error'] = $result[2];
                return $response;
            }
            if ($result[1] == 2) {
                $response['Error'] = $result[2];
                return $response;
            }
            if ($result[1] == 0) {
                $xmlstr = '';
                foreach ($result as $key => $val) {
                    if ($key > 1) {
                        $xmlstr .= "" . $val;
                    }
                }
                //  pr($sstr);
                $xml = simplexml_load_string($xmlstr, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xml);
                $result = json_decode($json, TRUE);
                /*
                  $mode = 0777;
                  if (!file_exists($basepath . "jar_files/log")) {
                  mkdir($basepath . "jar_files/log", $mode);
                  }
                  $myfile = fopen($basepath . "jar_files/log/" . $certid . "_1.xml", "w");
                  fwrite($myfile, $xmlstr);
                  fclose($myfile);
                 */

                //  pr($result);
                //  exit;
                //NOT_LOCK , SR_LOCK
                if (strcmp(trim($result['CertStatus']), "SR_LOCK") == 0) {
                    $response['Error'] = 'Locked Certificate Found!';
                    return $response;
                }


                $PaymentData = array();
                $onlinedata = array();
                if (isset($result['CertificatesDetails']['BaseCertificateNo'])) {
                    $checkbase = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $result['CertificatesDetails']['BaseCertificateNo'], 'payment_mode_id' => $data['payment_mode_id'])));
                    if (empty($checkbase)) {
                        $response['Error'] = 'Please Add Base Certificate First';
                        return $response;
                    }
                }


                $totalfee = trim(str_replace(',', '', $result['CertificatesDetails']['StampDutyAmountRs']));
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
                            $insertdata['estamp_issue_date'] = date('Y-m-d H:i:s', strtotime($result['CertificatesDetails']['CertificateIssuedDate'])); //CertificateIssuedDate
                            $insertdata['estamp_acc_no'] = $result['CertificatesDetails']['AccountReference'];
                            $insertdata['estamp_purchaser_name'] = $result['CertificatesDetails']['Purchasedby'];
                            $insertdata['payee_fname_en'] = $result['CertificatesDetails']['Purchasedby'];
                            $insertdata['estamp_vender_place'] = $result['StateName'];
                            $insertdata['estamp_vender_name'] = @$result['CertificatesDetails']['CertificateIssuedBy'];
                            $insertdata['certificate_unique_no'] = $result['CertificatesDetails']['UniqueDocReference'];
                            if (isset($result['CertificatesDetails']['BaseCertificateNo'])) {
                                $insertdata['base_certificate_no'] = $result['CertificatesDetails']['BaseCertificateNo'];
                            }

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
                                case 45:
                                    //Social infrastructure cess 
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
                $onlinedata['pamount'] = trim(str_replace(',', '', $result['CertificatesDetails']['StampDutyAmountRs']));
                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
                return $response;
            }
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

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(2), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
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
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 9)));
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

            $jarpath = $basepath . "jar_files/estampverification.jar";
            //$jarpath = $basepath . "jar_files/test.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Jar File Not Found!';
                return $response;
            }

            $jarmessage = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }
            if ($result[1] == 1) {
                $response['Error'] = $result[2];
                return $response;
            }
            if ($result[1] == 2) {
                $response['Error'] = $result[2];
                return $response;
            }
            if ($result[1] == 0) {
                $xmlstr = '';
                foreach ($result as $key => $val) {
                    if ($key > 1) {
                        $xmlstr .= "" . $val;
                    }
                }
                //  pr($sstr);
                $xml = simplexml_load_string($xmlstr, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xml);
                $result = json_decode($json, TRUE);
                /*
                  $mode = 0777;
                  if (!file_exists($basepath . "jar_files/log")) {
                  mkdir($basepath . "jar_files/log", $mode);
                  }
                  $myfile = fopen($basepath . "jar_files/log/" . $certid . "_1.xml", "w");
                  fwrite($myfile, $xmlstr);
                  fclose($myfile);
                 */

                //  pr($result);
                //  exit;
                //NOT_LOCK , SR_LOCK
                if (strcmp(trim($result['CertStatus']), "NOT_LOCK") != 0) {
                    $response['Error'] = 'Locked Certificate Found!';
                    return $response;
                }


                $PaymentData = array();
                $onlinedata = array();
                if (isset($result['CertificatesDetails']['BaseCertificateNo']) && isset($result['CertificatesDetails']['CertificateNo'])) {
                    $checkbase = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $result['CertificatesDetails']['BaseCertificateNo'], 'payment_mode_id' => $data['payment_mode_id'])));
                    if (empty($checkbase)) {
                        $response['Error'] = 'Please Add Base Certificate First';
                        return $response;
                    }
                }


                $totalfee = trim(str_replace(',', '', $result['CertificatesDetails']['StampDutyAmountRs']));
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
                            $insertdata['estamp_issue_date'] = date('Y-m-d H:i:s', strtotime($result['CertificatesDetails']['CertificateIssuedDate'])); //CertificateIssuedDate
                            $insertdata['estamp_acc_no'] = $result['CertificatesDetails']['AccountReference'];
                            $insertdata['estamp_purchaser_name'] = $result['CertificatesDetails']['Purchasedby'];
                            $insertdata['payee_fname_en'] = $result['CertificatesDetails']['Purchasedby'];
                            $insertdata['estamp_vender_place'] = $result['StateName'];
                            $insertdata['estamp_vender_name'] = @$result['CertificatesDetails']['CertificateIssuedBy'];
                            $insertdata['certificate_unique_no'] = $result['CertificatesDetails']['UniqueDocReference'];
                            if (isset($result['CertificatesDetails']['BaseCertificateNo']) && isset($result['CertificatesDetails']['CertificateNo'])) {
                                $insertdata['base_certificate_no'] = $result['CertificatesDetails']['BaseCertificateNo'];
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
                                case 45:
                                    //Social infrastructure cess 
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
                $onlinedata['pamount'] = trim(str_replace(',', '', $result['CertificatesDetails']['StampDutyAmountRs']));
                if (!empty($PaymentData)) {
                    $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                    $response['PaymentData'] = $PaymentData;
                    $response['OnlinePaymentData'] = $onlinedata;
                }
                return $response;
            }
        }
    }

    public function estamp_certificate_lock($payment = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items']);
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
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 9)));
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

            $jarpath = $basepath . "jar_files/estamplocknew.jar";
//            $jarpath = $basepath . "jar_files/test/estamplocknew.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Jar File Not Found!';
                return $response;
            }
            $jarmessage = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $payment['certificate_no'] . ' ' . $certdate_new . ' ' . $user_id . ' ' . $regno, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Payment gateway not working.';
                return $response;
            }
            if ($result[1] == 1) {
                $response['Error'] = $result[2];
                return $response;
            }
            if ($result[1] == 2) {
                $response['Error'] = 'Payment gateway not working.';
                return $response;
            }

            if ($result[1] == 0) {
                $xmlstr = '';
                foreach ($result as $key => $val) {
                    if ($key > 1) {
                        $xmlstr .= $val;
                    }
                }
                /*
                  $xmlstr = '<?xml version="1.0" encoding="UTF-8"?><LockedeStampCertificate xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="LockCertificateDeatils.xsd">  <BaseCertificateNo>IN-JH04533446925737Q</BaseCertificateNo>  <CertStatus>LOCKED</CertStatus>  <LinkedCertificates>    <LinkedCertificate>IN-JH04533542446849Q</LinkedCertificate>  </LinkedCertificates>  <LockedDateTime>07-06-2018 3:02:36 PM</LockedDateTime>  <LockedByUserId>272</LockedByUserId></LockedeStampCertificate>';
                 */

                $xml = simplexml_load_string($xmlstr, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xml);
                $result = json_decode($json, TRUE);

                /*
                  $mode = 0777;
                  if (!file_exists($basepath . "jar_files/log")) {
                  mkdir($basepath . "jar_files/log", $mode);
                  }
                  $myfile = fopen($basepath . "jar_files/log/" . $payment['certificate_no'] . "_2.xml", "w");
                  fwrite($myfile, $xmlstr);
                  fclose($myfile);
                 */
                if (strcmp(trim($result['CertStatus']), "LOCKED") != 0) {
                    $response['Error'] = 'Failed to Lock Certificate!';
                    return $response;
                }

                $lockdate = date('Y-m-d H:i:s', strtotime($result['LockedDateTime']));
                $response['PaymentData']['defacement_flag'] = "'" . 'Y' . "'";
                $response['PaymentData']['certificate_lock_date'] = "'" . $lockdate . "'";
                $response['PaymentData']['defacement_time'] = "'" . $lockdate . "'";
                if (isset($result['BaseCertificateNo'])) {
                    if (isset($result['LinkedCertificates'])) {
                        if (is_array($result['LinkedCertificates']['LinkedCertificate'])) {
                            $response['Condition']['certificate_no'] = $result['LinkedCertificates']['LinkedCertificate'];
                        } else {
                            $response['Condition']['certificate_no'][0] = $result['LinkedCertificates']['LinkedCertificate'];
                        }
                        array_push($response['Condition']['certificate_no'], $result['BaseCertificateNo']);
                    }
                } else {
                    $response['Condition']['certificate_no'] = $result['CertificateNo'];
                }
                $response['Condition']['token_no'] = $payment['token_no'];
                $response['Condition']['payment_mode_id'] = $payment['payment_mode_id'];
                // pr($response['Condition']);exit;
                return $response;
            }
        }
    }

    public function gras_payment_verification_old_old($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment', 'genernalinfoentry']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $grn_no = trim(@$data['grn_no']); // 'IN-PB00100028749518M';
            $transaction_id = trim(@$data['transaction_id']); // 'IN-PB00100028749518M';
            if (empty($transaction_id)) {
                $transaction_id = trim(@$data['bank_trn_id']); // 'IN-PB00100028749518M';   
            }

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($transaction_id)) {
                $response['Error'] = 'DEPT Transaction id Not Found!';
                return $response;
            }

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 9999001, 9999002, 9999003, 9999004, 9999005, 9999006, 9999007, 9999008, 9999009, 9999010, 9999011, 9999020, 9999021, 9999023, 9999025, 9999027, 9999032), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('bank_trn_id' => $transaction_id, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'GRN Already Exist In Verified List';
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 13)));
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


            // $basepath='D:/dist/';
            $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";

            // $jarpath = "java -jar D:\dist\jh_gras_encryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Encription Jar File Not Found!';
                return $response;
            }
//            if (empty($transaction_id)) {
//                $BankPayment = $this->BankPayment->find("first", array('conditions' => array('gateway_trans_id' => $grn_no, 'payment_mode_id' => $data['payment_mode_id'], 'token_no' => $token)));
//                if (empty($BankPayment)) {
//                    $response['Error'] = 'GRN Not Found!';
//                    return $response;
//                } else {
//                    $BankPayment = $BankPayment['BankPayment'];
//                    $transaction_id = $BankPayment['transaction_id'];
//                }
//            }
            // $grn_no=1802964023;
            $testArr = $grn_no . '-' . $bankapi['interface_user_id'] . '-' . $transaction_id . '-' . $bankapi['secure_key'];
            //pr($testArr);
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . ' ' . $testArr;
            //pr($jarpath);
            exec($jarpath, $output);
            // pr($output);
            // exit;
            // pr($enc_val);
            if (empty($output)) {
                $response['Error'] = 'Not able to encrypt data!';
                return $response;
            }
            $enc_val = $output[0];
            $post_string = array(
                "EncryptTxt" => $enc_val,
                "REQDEPTID" => $bankapi['interface_user_id']
            );

            $data_string = json_encode($post_string);
            $ch = curl_init($bankapi['interface_url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            // pr($result);exit;
            // $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }

            $deval = $result['SBIePayDoubleVerificationResult'];

            $jarpath = $basepath . "jar_files/jh_gras_decryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Decription Jar File Not Found!';
                return $response;
            }
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath;
            $jarpath = $jarpath . ' ' . $deval;
            exec($jarpath, $outputv);
            if (empty($outputv)) {
                $response['Error'] = 'Not able to decript data!';
                return $response;
            }
            // pr($outputv[0]);
            $result_arr = explode("|", $outputv[0]);
            //pr($result_arr);exit;
            if (empty($result_arr) || !is_array($result_arr)) {
                $response['Error'] = 'Invalid Service Response!';
                return $response;
            }
            $resmst = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
            foreach ($resmst as $key => $val) {
                $response_new[$val] = $result_arr[$key];
            }
            $result_arr = $response_new;

            if (strcmp(trim($result_arr['STATUS']), "SUCCESS") != 0) {
                $response['Error'] = $result_arr['PAYMENTSTATUSMESSAGE'] . " : " . $result_arr['STATUS'];
                return $response;
            }
            $result_office = $this->genernalinfoentry->find("first", array(
                'joins' => array(
                    array('table' => 'ngdrstab_mst_office', 'type' => 'INNER', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                ),
                'fields' => array('office.gras_office_code', 'office.gras_treasury_code', 'genernalinfoentry.token_no'),
                'conditions' => array('token_no' => trim($token))
            ));

            if (strcmp(trim($result_arr['TREASCODE']), trim($result_office['office']['gras_treasury_code'])) != 0) {
                $response['Error'] = 'TREASURY  CODE Missmatch Found';
                return $response;
            }
            if (strcmp(trim($result_arr['IFMSOFFICECODE']), trim($result_office['office']['gras_office_code'])) != 0) {
                $response['Error'] = 'IFMS OFFICE CODE Missmatch Found ' . $result_arr['IFMSOFFICECODE'] . "  -  " . $result_office['office']['gras_office_code'];
                return $response;
            }
            if (strcmp(trim($result_arr['DEPOSITERID']), trim($token)) != 0) {
                $response['Error'] = 'Invalid Token Number';
                return $response;
            }

            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            $totalfee = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            //$totalfee=2464;
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
                        $insertdata['grn_no'] = $result_arr['GRN'];
                        $insertdata['cin_no'] = $result_arr['CIN'];
                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = $result_arr['TXN_DATE'];
                        $insertdata['invoice_url'] = $result_arr['CHALLAN_URL'];
                        $insertdata['payee_fname_en'] = $result_arr['DEPOSITERNAME'];
                        $insertdata['bank_trn_id'] = $result_arr['DEPTTRANID'];

                        $paidamt = 0;
                        foreach ($paidamount as $paidamountsingle) {
                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                $paidamt += $paidamountsingle['payment']['pamount'];
                            }
                        }
                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;

                        if ($totalfee >= $single[0]['totalsd']) {
                            $insertdata['pamount'] = $single[0]['totalsd'];
                            $totalfee = $totalfee - $single[0]['totalsd'];
                        } else {
                            $insertdata['pamount'] = $totalfee;
                            $totalfee = 0;
                        }

                        if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                            array_push($PaymentData, $insertdata);
                            $onlinedata = $insertdata;
                        }
                    }
                }
            }
            $onlinedata['pamount'] = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            if (!empty($PaymentData)) {
                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
            } else {
                
            }
            return $response;
        }
    }

    public function gras_payment_verification_old($data = NULL, $extrafields = NULL) {

        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $grn_no = trim(@$data['grn_no']); // 'IN-PB00100028749518M';
            $transaction_id = trim(@$data['transaction_id']); // 'IN-PB00100028749518M';
            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($grn_no) && empty($transaction_id)) {
                $response['Error'] = 'GRN or Transaction id Not Found!';
                return $response;
            }

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(2, 45), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('grn_no' => $grn_no, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'GRN Already Exist In Verified List';
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 13)));
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


            // $basepath='D:/dist/';
            $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";

            // $jarpath = "java -jar D:\dist\jh_gras_encryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Encription Jar File Not Found!';
                return $response;
            }
            if (empty($transaction_id)) {
                $BankPayment = $this->BankPayment->find("first", array('conditions' => array('gateway_trans_id' => $grn_no, 'payment_mode_id' => $data['payment_mode_id'], 'token_no' => $token)));
                if (empty($BankPayment)) {
                    $response['Error'] = 'GRN Not Found!';
                    return $response;
                } else {
                    $BankPayment = $BankPayment['BankPayment'];
                    $transaction_id = $BankPayment['transaction_id'];
                }
            }


            $testArr = $grn_no . '-' . $bankapi['interface_user_id'] . '-' . $transaction_id . '-' . $bankapi['secure_key'];

            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . ' ' . $testArr;
            //pr($jarpath);
            exec($jarpath, $output);
            //pr($output);
            // exit;
            // pr($enc_val);
            if (empty($output)) {
                $response['Error'] = 'Not able to encrypt data!';
                return $response;
            }
            $enc_val = $output[0];
            $post_string = array(
                "EncryptTxt" => $enc_val,
                "REQDEPTID" => $bankapi['interface_user_id']
            );

            $data_string = json_encode($post_string);
            $ch = curl_init($bankapi['interface_url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            // pr($result);exit;
            // $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }

            $deval = $result['SBIePayDoubleVerificationResult'];

            $jarpath = $basepath . "jar_files/jh_gras_decryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Decription Jar File Not Found!';
                return $response;
            }
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath;
            $jarpath = $jarpath . ' ' . $deval;
            exec($jarpath, $outputv);
            if (empty($outputv)) {
                $response['Error'] = 'Not able to decript data!';
                return $response;
            }
            // pr($outputv[0]);
            $result_arr = explode("|", $outputv[0]);
            if (empty($result_arr) || !is_array($result_arr)) {
                $response['Error'] = 'Invalid Service Response!';
                return $response;
            }
            $resmst = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
            foreach ($resmst as $key => $val) {
                $response_new[$val] = $result_arr[$key];
            }
            $result_arr = $response_new;
            // pr($result_arr);exit;
//            $mode = 0777;
//            if (!file_exists($basepath . "jar_files/log")) {
//                mkdir($basepath . "jar_files/log", $mode);
//            }
//            $myfile = fopen($basepath . "jar_files/log/" . $grn_no . "_1.xml", "w");
//            fwrite($myfile, $outputv[0]);
//            fclose($myfile);
            // pr($result_arr);
            //  exit;


            if (strcmp(trim($result_arr['STATUS']), "FAIL") == 0) {
                $response['Error'] = $result_arr['PAYMENTSTATUSMESSAGE'] . " : " . $result_arr['STATUS'];
                return $response;
            }

            if (strcmp(trim($result_arr['DEPOSITERID']), $token) != 0) {
                $response['Error'] = 'Token Number missmatch. ';
                return $response;
            }

            $PaymentData = array();
            $onlinedata = array();
            $amountmatch = 0;
            $INFO = NULL;
            for ($i = 1; $i <= 3; $i++) {
                if (isset($result_arr['ADDINFO' . $i]) && $result_arr['ADDINFO' . $i] != 'NA') {
                    $INFO .= $result_arr['ADDINFO' . $i];
                }
            }
            $INFO_ARR = explode('##', $INFO);
            // pr($INFO_ARR);
            // exit;

            if (isset($INFO_ARR) && is_array($INFO_ARR)) {

                foreach ($INFO_ARR as $map) {

                    if (!empty($map)) {
                        $single = explode("**", $map);
                        if (count($single) > 1) {
                            $insertdata = array();
                            $insertdata = array_merge($insertdata, $extrafields);
                            $insertdata['online_verified_flag'] = 'Y';
                            $insertdata['defacement_flag'] = 'Y';
                            $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                            $insertdata['grn_no'] = $result_arr['GRN'];
                            $insertdata['cin_no'] = $result_arr['CIN'];
                            $insertdata['account_head_code'] = $single[0];
                            $insertdata['pamount'] = $single[1];
                            $amountmatch = $amountmatch + $single[1];
                            $insertdata['pdate'] = $result_arr['TXN_DATE'];
                            $insertdata['invoice_url'] = $result_arr['CHALLAN_URL'];
                            $insertdata['payee_fname_en'] = $result_arr['DEPOSITERNAME'];
                            $insertdata['bank_trn_id'] = $result_arr['DEPTTRANID'];
                            if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                                array_push($PaymentData, $insertdata);
                                $onlinedata = $insertdata;
                                $onlinedata['pamount'] = $result_arr['TXN_AMOUNT'];
                            }
                        }
                    }
                }
            }

            if ($result_arr['TXN_AMOUNT'] != $amountmatch) {
                $response['Error'] = 'Amount Missmatch - total amount and additional information amount.';
            }
            if (empty($PaymentData)) {
                $response['Error'] = 'Invalid Data Received';
            }

            $response['PaymentData'] = $PaymentData;
            $response['OnlinePaymentData'] = $onlinedata;
            //pr($response);exit;
            return $response;
        }
    }

    public function gras_payment_verification($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment', 'genernalinfoentry']);
        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $grn_no = trim(@$data['grn_no']); // 'IN-PB00100028749518M';
            $transaction_id = trim(@$data['transaction_id']); // 'IN-PB00100028749518M';
            if (empty($transaction_id)) {
                $transaction_id = trim(@$data['bank_trn_id']); // 'IN-PB00100028749518M';   
            }

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($transaction_id)) {
                $response['Error'] = 'DEPT Transaction id Not Found!';
                return $response;
            }

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 19, 20, 9999001, 9999002, 9999003, 9999004, 9999005, 9999006, 9999007, 9999008, 9999009, 9999010, 9999011, 9999020, 9999021, 9999023, 9999025, 9999027, 9999032), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('bank_trn_id' => $transaction_id, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'GRN Already Exist In Verified List';
                return $response;
            }

            if (@$data['f_vender_name'] == 2) {
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
            } else {
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 13)));
            }



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


            // $basepath='D:/dist/';
            $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";

            // $jarpath = "java -jar D:\dist\jh_gras_encryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Encription Jar File Not Found!';
                return $response;
            }
//            if (empty($transaction_id)) {
//                $BankPayment = $this->BankPayment->find("first", array('conditions' => array('gateway_trans_id' => $grn_no, 'payment_mode_id' => $data['payment_mode_id'], 'token_no' => $token)));
//                if (empty($BankPayment)) {
//                    $response['Error'] = 'GRN Not Found!';
//                    return $response;
//                } else {
//                    $BankPayment = $BankPayment['BankPayment'];
//                    $transaction_id = $BankPayment['transaction_id'];
//                }
//            }
            // $grn_no=1802964023;
            $testArr = $grn_no . '-' . $bankapi['interface_user_id'] . '-' . $transaction_id . '-' . $bankapi['secure_key'];
            //pr($testArr);
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . ' ' . $testArr;
            //pr($jarpath);
            exec($jarpath, $output);
            // pr($output);
            // exit;
            // pr($enc_val);
            if (empty($output)) {
                $response['Error'] = 'Not able to encrypt data!';
                return $response;
            }
            $enc_val = $output[0];
            $post_string = array(
                "EncryptTxt" => $enc_val,
                "REQDEPTID" => $bankapi['interface_user_id']
            );

            $data_string = json_encode($post_string);
            $ch = curl_init($bankapi['interface_url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            // pr($result);exit;
            // $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }

            $deval = $result['SBIePayDoubleVerificationResult'];

            $jarpath = $basepath . "jar_files/jh_gras_decryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Decription Jar File Not Found!';
                return $response;
            }
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath;
            $jarpath = $jarpath . ' ' . $deval;
            exec($jarpath, $outputv);
            if (empty($outputv)) {
                $response['Error'] = 'Not able to decript data!';
                return $response;
            }
            // pr($outputv[0]);
            $result_arr = explode("|", $outputv[0]);
            //pr($result_arr);exit;
            if (empty($result_arr) || !is_array($result_arr)) {

                $response['Error'] = 'Invalid Service Response!';
                return $response;
            }
            $resmst = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
            foreach ($resmst as $key => $val) {
                $response_new[$val] = $result_arr[$key];
            }
            $result_arr = $response_new;

            if (strcmp(trim($result_arr['STATUS']), "SUCCESS") != 0) {
                $response['Error'] = $result_arr['PAYMENTSTATUSMESSAGE'] . " : " . $result_arr['STATUS'];
                return $response;
            }
            $result_office = $this->genernalinfoentry->find("first", array(
                'joins' => array(
                    array('table' => 'ngdrstab_mst_office', 'type' => 'INNER', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                ),
                'fields' => array('office.gras_office_code', 'office.gras_treasury_code', 'genernalinfoentry.token_no'),
                'conditions' => array('token_no' => trim($token))
            ));
//            pr($result_arr['TREASCODE']);pr($result_office['office']['gras_treasury_code']);exit;
            if (strcmp(trim($result_arr['TREASCODE']), trim($result_office['office']['gras_treasury_code'])) != 0) {
                $response['Error'] = 'TREASURY  CODE Missmatch Found  (' . $result_arr['TREASCODE'] . ' | ' . $result_office['office']['gras_treasury_code'] . ' )';
                return $response;
            }
            if (strcmp(trim($result_arr['IFMSOFFICECODE']), trim($result_office['office']['gras_office_code'])) != 0) {
                $response['Error'] = 'IFMS OFFICE CODE Missmatch Found' . $result_arr['IFMSOFFICECODE'] . "  -  " . $result_office['office']['gras_office_code'];
                return $response;
            }
            if (strcmp(trim($result_arr['DEPOSITERID']), trim($token)) != 0) {
                if (@$data['f_vender_name'] == 2) {
                    
                } else {
                    $response['Error'] = 'Invalid Token Number';
                    return $response;
                }
            }

            if (isset($extrafields['StatusOnly'])) {
                return $result_arr;
            }

            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            $totalfee = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            //$totalfee=2464;
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
                        $insertdata['grn_no'] = $result_arr['GRN'];
                        $insertdata['cin_no'] = $result_arr['CIN'];
                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = $result_arr['TXN_DATE'];
                        $insertdata['invoice_url'] = $result_arr['CHALLAN_URL'];
                        $insertdata['payee_fname_en'] = $result_arr['DEPOSITERNAME'];
                        $insertdata['bank_trn_id'] = $result_arr['DEPTTRANID'];

                        $paidamt = 0;
                        foreach ($paidamount as $paidamountsingle) {
                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                $paidamt += $paidamountsingle['payment']['pamount'];
                            }
                        }
                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;
                        if ($single[0]['totalsd'] > 0) {
                            if ($totalfee >= $single[0]['totalsd']) {
                                $insertdata['pamount'] = $single[0]['totalsd'];
                                $totalfee = $totalfee - $single[0]['totalsd'];
                            } else {
                                $insertdata['pamount'] = $totalfee;
                                $totalfee = 0;
                            }
                        }
                        if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                            array_push($PaymentData, $insertdata);
                            $onlinedata = $insertdata;
                        }
                    }
                }
            }
            $onlinedata['pamount'] = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            if (!empty($PaymentData)) {
                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
            } else {
                $response['Error'] = 'NO account head found...!!!';
            }
            return $response;
        }
    }

    public function gras_payment_defacement($payment = NULL, $extrafields = NULL) {
        
    }

    public function gras_payment_entry_OLD($transid = NULL) {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $this->loadModel('office');

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
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            }
            // pr($result);
            $data['transaction_id'] = $transid;
            $data['payment_mode_id'] = $result[0]['BankPayment']['payment_mode_id'];

            $extrafields['token_no'] = $result[0]['info']['token_no'];
            $extrafields['article_id'] = $result[0]['info']['article_id'];
            $extrafields['lang'] = 'en';
            $responce = $this->gras_payment_verification($data, $extrafields);
            // pr($responce);
            // exit;
            if (isset($responce['Error']) && !empty($responce['Error'])) {
                $status_arr = explode(":", $responce['Error']);
                if (isset($status_arr[1])) {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set payment_status=? where token_no=?  and transaction_id=?", array(strtoupper(trim($status_arr[1])), $extrafields['token_no'], $transid));
                }

                $this->Session->setFlash(
                        __('' . $responce['Error'])
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            } else {
                $resdata = $responce['OnlinePaymentData'];
                $usertype = $this->Session->read("session_usertype");
                $now = date('Y-m-d H:i:s');
                if ($usertype == 'C') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,user_type=?,gateway_trans_id=?,invoice_url=? where token_no=?  and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                } elseif ($usertype == 'O') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=?,invoice_url=? where token_no=? and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                }
                $this->Session->setFlash(
                        __('Payment Status Updated')
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            }
        }


        $mapping = $this->BankPayment->mapping_account_heads(1, 20);


        $action = '';
        $txnid = '';
        $enc_val = '';
        $hash = '';
        $PAYU_BASE_URL = "";
        $requestparam = "";

        $fieldlist = array();

        $fieldlist['RESPONSE_URL']['text'] = 'is_required';
//        $fieldlist['DEPTID']['text'] = 'is_required,is_alpha';
//        $fieldlist['RECIEPTHEADCODE']['text'] = 'is_required,is_alpha';
//        $fieldlist['DEPTTRANID']['text'] = 'is_required,is_phone';
//        $fieldlist['TREASCODE']['text'] = 'is_required';
//        $fieldlist['IFMSOFFICECODE']['text'] = 'is_required,is_alphanumericspace';
//        $fieldlist['SECURITYCODE']['text'] = 'is_alphanumericspace';
        $fieldlist['DEPOSITERID']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['DEPOSITERNAME']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['AMOUNT']['text'] = 'is_required,is_numeric';
        $fieldlist['PANNO']['text'] = 'is_required,is_alphanumericspace';



        $i = 0;
        foreach ($mapping as $map) {
            $i++;
            $fieldlist['ADDINFOFRM' . $i]['text'] = 'is_required,is_numeric';
        }



        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 19);
        //echo $txnid;

        if ($this->request->is('post')) {
            $data = $_POST;
            $errarr = $this->validatedata($data, $fieldlist);

            if ($this->ValidationError($errarr)) {

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }

                $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";
                //$jarpath = $basepath . "jar_files/sample_jar.jar";
                if (!file_exists($jarpath)) {
                    $this->Session->setFlash(
                            __('Jar File Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                }

                $result_office = $this->genernalinfoentry->find("first", array(
                    'fields' => array('office.gras_treasury_code', 'office.gras_office_code'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                    ),
                    'conditions' => array('token_no' => trim($data['DEPOSITERID']))
                ));
//pr($result_office);exit;
                if (empty($result_office)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'WebService', 'action' => 'gras_payment_entry'));
                }

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 12)));
                //pr($bankapi);
                //  exit;
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $PAYU_BASE_URL = $bankapi['interface_url'];

                if (empty($data['requestparam'])) {
                    do {
                        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                        $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                    } while (!empty($check));

                    $amt = 0;
                    $info = 1;
                    $data['ADDINFO1'] = NULL;
                    if (isset($mapping)) {
                        $i = 0;
                        foreach ($mapping as $map) {
                            $i++;
                            if (isset($data['ADDINFOFRM' . $i]) && is_numeric($data['ADDINFOFRM' . $i])) {
                                $amt += $data['ADDINFOFRM' . $i];
                                if (strlen($data['ADDINFO' . $info]) > 150) {
                                    $info++;
                                }
                                $data['ADDINFO' . $info] .= "##" . $map[0]['account_head_code'] . "**" . $data['ADDINFOFRM' . $i];
                            }
                        }
                    }

                    if (isset($data['ADDINFO1']) && !is_null($data['ADDINFO1'])) {
                        $data['ADDINFO1'] = substr($data['ADDINFO1'], 2);
                    }

                    $data['AMOUNT'] = number_format($amt, 2, ".", "");

                    $savedata['payment_mode_id'] = 1;
                    $savedata['transaction_id'] = $txnid;
                    $savedata['payee_fname_en'] = $data['DEPOSITERNAME'];
                    $savedata['pamount'] = number_format($data['AMOUNT'], 2, ".", "");
                    $savedata['payment_status'] = 'CREATED';
                    $savedata['token_no'] = $data['DEPOSITERID'];


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
                        $data['DEPTID'] = $bankapi['interface_user_id'];
                        $data['SECURITYCODE'] = $bankapi['secure_key'];
                        $data['DEPTTRANID'] = $txnid;
                        $data['RECIEPTHEADCODE'] = '003003104010101'; //$bankapi['remark'];
                        $data['TREASCODE'] = $result_office['office']['gras_treasury_code'];
                        $data['IFMSOFFICECODE'] = $result_office['office']['gras_office_code'];


                        $hashSequence = "DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|SECURITYCODE|RESPONSE_URL";
                        $hashVarsSeq = explode('|', $hashSequence);
                        $hash_string = '';
                        foreach ($hashVarsSeq as $hash_var) {
                            $hash_string .= '-';
                            $hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : 'NA';
                        }
                        if (!empty($hash_string)) {
                            $hash_string = substr($hash_string, 1);
                        }
                        //  $testArr = $bankapi['interface_user_id'].'-003003104010101-' . $name . '-' . $txnid . '-' . $amt . '-DRID001-NA-NA-NA-NA-PRJ-PRJFIN001-'.$bankapi['secure_key'];
                        //   JHNGDRS|003003104010101|shree|09803cd9b22d1da064c0|100|20170015460|sssss|100|NA|NA|PRJ|PRJFIN001|sec1234
                        //pr($hash_string);exit;
                        // pr($jarpath);
                        // sample_jar.jar
                        exec("/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . " " . $hash_string, $output);


                        // exec('java -jar /home/NGDRS_Upload_jh/jar_files/sample_jar.jar',$output);
                        //  exec('systemctl status httpd',$output);
                        //shell_exec("java -jar " . $jarpath . " " . $hash_string, $output);
                        //  
                        // pr($output);exit;
                        if (isset($output[0])) {
                            $hash = $output[0];
                            $action = $PAYU_BASE_URL;
                        } else {
                            $this->Session->setFlash(
                                    __('Not able to encript:JAR not Executed')
                            );
                            $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                        }
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



        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result', 'mapping'));
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
        $this->loadModel('office');
        $tokenval = $this->Session->read("Selectedtoken");
        if (!is_null($transid) && strlen($transid) == 20) {
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
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            }
            // pr($result);
            $data['transaction_id'] = $transid;
            $data['payment_mode_id'] = $result[0]['BankPayment']['payment_mode_id'];

            $extrafields['token_no'] = $result[0]['info']['token_no'];
            $extrafields['article_id'] = $result[0]['info']['article_id'];
            $extrafields['lang'] = 'en';
            $responce = $this->gras_payment_verification($data, $extrafields);
            // pr($responce);
            // exit;
            if (isset($responce['Error']) && !empty($responce['Error'])) {
                $status_arr = explode(":", $responce['Error']);
                if (isset($status_arr[1])) {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set payment_status=? where token_no=?  and transaction_id=?", array(strtoupper(trim($status_arr[1])), $extrafields['token_no'], $transid));
                }

                $this->Session->setFlash(
                        __('' . $responce['Error'])
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            } else if (isset($responce['OnlinePaymentData'])) {
                $resdata = $responce['OnlinePaymentData'];
                $usertype = $this->Session->read("session_usertype");
                $now = date('Y-m-d H:i:s');
                if ($usertype == 'C') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,user_type=?,gateway_trans_id=?,invoice_url=? where token_no=?  and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                } elseif ($usertype == 'O') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=?,invoice_url=? where token_no=? and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                }
                $this->Session->setFlash(
                        __('Payment Status Updated')
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            }
        }





        $action = '';
        $txnid = '';
        $enc_val = '';
        $hash = '';
        $PAYU_BASE_URL = "";
        $requestparam = "";

        $fieldlist = array();

        $fieldlist['RESPONSE_URL']['text'] = 'is_required';
//        $fieldlist['DEPTID']['text'] = 'is_required,is_alpha';
//        $fieldlist['RECIEPTHEADCODE']['text'] = 'is_required,is_alpha';
//        $fieldlist['DEPTTRANID']['text'] = 'is_required,is_phone';
//        $fieldlist['TREASCODE']['text'] = 'is_required';
//        $fieldlist['IFMSOFFICECODE']['text'] = 'is_required,is_alphanumericspace';
//        $fieldlist['SECURITYCODE']['text'] = 'is_alphanumericspace';
        $fieldlist['DEPOSITERID']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['DEPOSITERNAME']['text'] = 'is_required,is_alphanumeric';
        $fieldlist['AMOUNT']['text'] = 'is_required,is_numeric';
        $fieldlist['PANNO']['text'] = 'is_alphanumericspace';



        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if ($this->request->is('post')) {
            $data = $_POST;
            $errarr = $this->validatedata($data, $fieldlist);

            if ($this->ValidationError($errarr)) {

                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }

                $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";
                //$jarpath = $basepath . "jar_files/test.jar";
                if (!file_exists($jarpath)) {
                    $this->Session->setFlash(
                            __('Jar File Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                }

                $result_office = $this->genernalinfoentry->find("first", array(
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_office', 'type' => 'INNER', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                    ),
                    'fields' => array('office.gras_office_code', 'office.gras_treasury_code', 'genernalinfoentry.token_no'),
                    'conditions' => array('token_no' => trim($data['DEPOSITERID']))
                ));

                if (empty($result_office)) {
                    $this->Session->setFlash(
                            __('Token Not Found')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                }

                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 12)));
                //pr($bankapi);
                //  exit;
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }

                $PAYU_BASE_URL = $bankapi['interface_url'];

                if (empty($data['requestparam'])) {
                    do {
                        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                        $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                    } while (!empty($check));


                    $data['AMOUNT'] = number_format($data['AMOUNT'], 2, ".", "");

                    $savedata['payment_mode_id'] = 1;
                    $savedata['transaction_id'] = $txnid;
                    $savedata['payee_fname_en'] = $data['DEPOSITERNAME'];
                    $savedata['pamount'] = number_format($data['AMOUNT'], 2, ".", "");
                    $savedata['payment_status'] = 'CREATED';
                    $savedata['token_no'] = $data['DEPOSITERID'];


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
                    //pr($result_office);
                    //exit;
                    if ($this->BankPayment->save($savedata)) {
                        // Hash Sequence
                        $data['DEPTID'] = $bankapi['interface_user_id'];
                        $data['SECURITYCODE'] = $bankapi['secure_key'];
                        $data['DEPTTRANID'] = $txnid;
                        $data['RECIEPTHEADCODE'] = '003003104010101'; //$bankapi['remark'];
                        $data['TREASCODE'] = $result_office['office']['gras_treasury_code'];
                        $data['IFMSOFFICECODE'] = $result_office['office']['gras_office_code'];

                        //pr($data);exit;
                        $hashSequence = "DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|SECURITYCODE|RESPONSE_URL";
                        $hashVarsSeq = explode('|', $hashSequence);
                        $hash_string = '';
                        foreach ($hashVarsSeq as $hash_var) {
                            $hash_string .= '-';
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

                        exec("/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . " " . $hash_string, $output);
                        if (isset($output[0])) {
                            $hash = $output[0];
                            $action = $PAYU_BASE_URL;
                        } else {
                            $this->Session->setFlash(
                                    __('Not able to encript')
                            );
                            $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                        }
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

        if ($usertype == 'C') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 1, 'token_no' => $tokenval),
                'order' => array('trn_id DESC'),
                    )
            );
        }
        if ($usertype == 'O') {
            $result = $this->BankPayment->find("all", array(
                'conditions' => array('org_user_id' => $this->Auth->user('user_id'), 'payment_mode_id' => 1, 'token_no' => $tokenval),
                'order' => array('trn_id DESC'),
            ));
        }

        $this->set(compact('action', 'hash', 'requestparam', 'MERCHANT_KEY', 'SALT', 'txnid', 'posted', 'result'));
    }

    public function gras_payment_entry_update($transid = NULL) {

        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $this->loadModel('office');
        $tokenval = $this->Session->read("Selectedtoken");

        $fieldlist = array();
        $fieldlist['token_no']['text'] = 'is_required';
        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        if (!is_null($transid) && strlen($transid) == 20) {
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
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
            }
            // pr($result);
            $data['transaction_id'] = $transid;
            $data['payment_mode_id'] = $result[0]['BankPayment']['payment_mode_id'];

            $extrafields['token_no'] = $result[0]['info']['token_no'];
            $extrafields['article_id'] = $result[0]['info']['article_id'];
            $extrafields['lang'] = 'en';
            $responce = $this->gras_payment_verification($data, $extrafields);

            if (isset($responce['Error']) && !empty($responce['Error'])) {
                $status_arr = explode(":", $responce['Error']);
                if (isset($status_arr[1])) {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set payment_status=? where token_no=?  and transaction_id=?", array(strtoupper(trim($status_arr[1])), $extrafields['token_no'], $transid));
                }

                $this->Session->setFlash(
                        __('' . $responce['Error'])
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry_update'));
            } else if (isset($responce['OnlinePaymentData'])) {
                $resdata = $responce['OnlinePaymentData'];
                $usertype = $this->Session->read("session_usertype");
                $now = date('Y-m-d H:i:s');
                if ($usertype == 'C') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,updated=?,user_type=?,gateway_trans_id=?,invoice_url=? where token_no=?  and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                } elseif ($usertype == 'O') {
                    $this->BankPayment->query("update ngdrstab_trn_bank_payment set bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=?,invoice_url=? where token_no=? and transaction_id=?", array($resdata['cin_no'], $resdata['pamount'], 'SUCCESS', $resdata['pdate'], $now, $usertype, $resdata['grn_no'], $resdata['invoice_url'], $resdata['token_no'], $resdata['bank_trn_id']));
                }
                $this->Session->setFlash(
                        __('Payment Status Updated')
                );
                $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry_update'));
            }
        }

        if ($this->request->is('post')) {
            $user_id = $this->Auth->user('user_id');
            $usertype = $this->Session->read("session_usertype");
            $result = array();
            $tokenval = $this->request->data['token_no'];
            if ($usertype == 'C') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('payment_mode_id' => 1, 'token_no' => $tokenval),
                    'order' => array('trn_id DESC'),
                        )
                );
            }
            if ($usertype == 'O') {
                $result = $this->BankPayment->find("all", array(
                    'conditions' => array('payment_mode_id' => 1, 'token_no' => $tokenval),
                    'order' => array('trn_id DESC'),
                ));
            }

            $this->set(compact('result'));
        }
    }

    public function gras_payment_response() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );

        $this->loadModel('BankPayment');
        $this->loadModel('file_config');
//        pr($_POST);
//        pr($_REQUEST);
//       pr($_GET);
//        pr($this->request);
//        exit;
        if ($this->request->is('post')) {

//pr($this->request->data);exit;
            if (isset($this->request->data['responseparam']) && !empty($this->request->data['responseparam'])) {
                $responseparam = $this->request->data['responseparam'];
                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }

                $jarpath = $basepath . "jar_files/jh_gras_decryption.jar";
                if (!file_exists($jarpath)) {
                    $this->Session->setFlash(
                            __('Jar File Not Found!')
                    );
                    $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
                }

                exec("/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . " " . $responseparam, $output);
                //pr($output);exit;
                if (!empty($output) && isset($output[0])) {
                    $response = explode("|", $output[0]);
                    if (is_array($response)) {
                        $resmst = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
                        foreach ($resmst as $key => $val) {
                            $response_new[$val] = $response[$key];
                        }
                        $response = $response_new;
                        //  pr($response);
                        //  exit;
                        $usertype = $this->Session->read("session_usertype");
                        $userid = $this->Auth->user('user_id');
                        $now = date('Y-m-d H:i:s');
                        if ($response['TXN_DATE'] == 'NA' || empty($response['TXN_DATE'])) {
                            $response['TXN_DATE'] = NULL;
                        } else {
                            $datedb_part = explode(" ", $response['TXN_DATE']);
                            $datedb_arr = explode("/", $datedb_part[0]);
                            $response['TXN_DATE'] = $datedb_arr[2] . "-" . $datedb_arr[1] . "-" . $datedb_arr[0];
                        }
                        if (!is_numeric($response['AMOUNT'])) {
                            $response['AMOUNT'] = NULL;
                        }
                        if (!empty($response['DEPOSITERID']) && !empty($response['DEPTTRANID'])) {
                            $this->BankPayment->query("update ngdrstab_trn_bank_payment set  bank_trn_ref_number=? , pamount=?,payment_status=?,pdate=?,org_updated=?,user_type=? ,gateway_trans_id=? ,error_code=?, error_message=?,invoice_url=? where token_no=? and transaction_id=?", array($response['CIN'], $response['AMOUNT'], strtoupper(trim($response['STATUS'])), $response['TXN_DATE'], $now, $usertype, $response['GRN'], 'NA', $response['PAYMENTSTATUSMESSAGE'], $response['CHALLAN_URL'], $response['DEPOSITERID'], $response['DEPTTRANID']));
                        }
                        $this->set("response", $response);
                    }
                }
            }
        } else {
//            $usertype = $this->Session->read("session_usertype");
//            if ($usertype == 'C') {
//                $reverification=$this->BankPayment->query("select transaction_id from  ngdrstab_trn_bank_payment where user_id=? and payment_mode_id=1 ORDER BY id  DESC LIMIT 1",array($this->Auth->user('user_id')));
//            }
//            if ($usertype == 'O') {
//                $reverification=$this->BankPayment->query("select transaction_id from  ngdrstab_trn_bank_payment where org_user_id=? and payment_mode_id=1 ORDER BY id  DESC LIMIT 1",array($this->Auth->user('user_id')));
//            }    
//            //$u=$this->Auth->user('user_id');
//           // pr($u);
//           // pr($reverification);exit;
//            if(!empty($reverification)){
//              $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry',$reverification[0][0]['transaction_id']));  
//            }else{
//              
//            }
            $this->Session->setFlash(
                    __('Something Went Wrong')
            );
            $this->redirect(array('controller' => 'JHWebService', 'action' => 'gras_payment_entry'));
        }
    }

    public function pan_verification($pannumber = NULL) {
        $this->loadModel('external_interface');
        $this->loadModel('file_config');
        $response['Error'] = '';
        $path = $this->file_config->find('first', array('fields' => array('filepath')));
        if (!empty($path) && file_exists($path['file_config']['filepath'])) {
            $api = $this->external_interface->find("first", array('conditions' => array('interface_id' => 15)));
            if (!empty($api)) {
                $api = $api['external_interface'];
                $input = $api['interface_user_id'] . "^" . $pannumber;

                $basepath = $path['file_config']['filepath'] . "cert_files/";

                $temp_file_input = tempnam(sys_get_temp_dir(), 'pan');
                $temp_file_sign = tempnam(sys_get_temp_dir(), 'sin');
                if (file_put_contents($temp_file_input, $input)) {
                    if (file_exists($basepath . "eMudhraSigner.pfx")) {
                        $pkcs12 = file_get_contents($basepath . "eMudhraSigner.pfx");
                        $cert_password = $api['interface_password']; // Cert password
                        $certs = array();
                        if (openssl_pkcs12_read($pkcs12, $certs, $cert_password)) {
                            $cert_data = openssl_x509_read($certs['cert']);
                            $private_key = openssl_pkey_get_private($certs['pkey'], $cert_password);
                            if (openssl_pkcs7_sign($temp_file_input, $temp_file_sign, $cert_data, $private_key, array(), PKCS7_BINARY | PKCS7_DETACHED)) {
                                $signature = file_get_contents($temp_file_sign);
                                unlink($temp_file_input);
                                unlink($temp_file_sign);

                                $signature_arr = explode("\n", $signature);
                                $signature_str = '';
                                if (is_array($signature_arr)) {
                                    $last = 0;
                                    foreach ($signature_arr as $key => $signline) {
                                        if ($key >= 12 && $last == 0) {
                                            if ($signline != '') {
                                                $signature_str .= $signline . "\n";
                                            } else {
                                                $last = 1;
                                            }
                                        }
                                    }
                                }

                                $postdata['data'] = $input;
                                $postdata['signature'] = $signature_str;
                                $postdata = http_build_query($postdata);

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $api['interface_url']);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  //2
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                $result = curl_exec($ch);
                                curl_close($ch);
                                if (!empty($result)) {
                                    $result_arr = explode("^", $result);
                                    if ($result_arr[0] == 1) {

                                        $data['pan'] = $result_arr[1];
                                        $data['type'] = $result_arr[2];
                                        $data['salutation'] = $result_arr[6];
                                        $data['firstname'] = $result_arr[4];
                                        $data['middlename'] = $result_arr[5];
                                        $data['lastname'] = $result_arr[3];
                                        $data['lastmodified'] = $result_arr[7];
                                        $response['Data'] = $data;
                                    } else {
                                        $errorcode = array('1' => 'Success', '2' => 'System Error', '3' => 'Authentication Failure', '4' => 'User not authorized', '5' => 'No PANs Entered', '6' => 'User validity has expired', '7' => 'Number of PANs exceeds the limit (5)', '8' => 'Not enough balance', '9' => 'Not an HTTPs request', '10' => 'POST method not used', '11' => 'Slab Change Running');
                                        $response['Error'] = 'Unable to find data : ' . @$errorcode[$result_arr[0]];
                                    }
                                } else {
                                    $response['Error'] = 'Unable to connect  pan service';
                                }
                            } else {
                                $response['Error'] = 'Unable to sign  file';
                            }
                        } else {
                            $response['Error'] = 'Unable to  read  certificate';
                        }
                    } else {
                        $response['Error'] = 'Certificate not found';
                    }
                } else {
                    $response['Error'] = 'Unable to write  input file';
                }
            } else {
                $response['Error'] = 'Service api not found';
            }
        } else {
            $response['Error'] = 'Base path not found';
        }

        return $response;
    }

    public function gras_payment_verification_test($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment', 'genernalinfoentry']);

        $data['grn_no'] = '';
        $data['transaction_id'] = '0810201805232812493';
        $data['f_vender_name'] = 2;
        $data['payment_mode_id'] = 1;

        $extrafields['token_no'] = 20190000002566;
        $extrafields['article_id'] = 83;
        $extrafields['lang'] = 'en';


        if ($data != NULL) {
            $response['Error'] = '';
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            $grn_no = trim(@$data['grn_no']); // 'IN-PB00100028749518M';
            $transaction_id = trim(@$data['transaction_id']); // 'IN-PB00100028749518M';
            if (empty($transaction_id)) {
                $transaction_id = trim(@$data['bank_trn_id']); // 'IN-PB00100028749518M';   
            }

            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            if (empty($transaction_id)) {
                $response['Error'] = 'DEPT Transaction id Not Found!';
                return $response;
            }

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 19, 20, 9999001, 9999002, 9999003, 9999004, 9999005, 9999006, 9999007, 9999008, 9999009, 9999010, 9999011, 9999020, 9999021, 9999023, 9999025, 9999027, 9999032), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            // pr($accounthead);exit;
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('bank_trn_id' => $transaction_id, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
//                $response['Error'] = 'GRN Already Exist In Verified List';
//                return $response;
            }

            if (@$data['f_vender_name'] == 2) {
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
            } else {
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 13)));
            }



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


            // $basepath='D:/dist/';
            $jarpath = $basepath . "jar_files/jh_gras_encryption.jar";

            // $jarpath = "java -jar D:\dist\jh_gras_encryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Encription Jar File Not Found!';
                return $response;
            }
//            if (empty($transaction_id)) {
//                $BankPayment = $this->BankPayment->find("first", array('conditions' => array('gateway_trans_id' => $grn_no, 'payment_mode_id' => $data['payment_mode_id'], 'token_no' => $token)));
//                if (empty($BankPayment)) {
//                    $response['Error'] = 'GRN Not Found!';
//                    return $response;
//                } else {
//                    $BankPayment = $BankPayment['BankPayment'];
//                    $transaction_id = $BankPayment['transaction_id'];
//                }
//            }
            // $grn_no=1802964023;
            $testArr = $grn_no . '-' . $bankapi['interface_user_id'] . '-' . $transaction_id . '-' . $bankapi['secure_key'];
            //pr($testArr);
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath . ' ' . $testArr;
            //pr($jarpath);
            exec($jarpath, $output);
            // pr($output);
            // exit;
            // pr($enc_val);
            if (empty($output)) {
                $response['Error'] = 'Not able to encrypt data!';
                return $response;
            }
            $enc_val = $output[0];
            $post_string = array(
                "EncryptTxt" => $enc_val,
                "REQDEPTID" => $bankapi['interface_user_id']
            );

            $data_string = json_encode($post_string);
            //pr($data_string); exit; 
            $ch = curl_init($bankapi['interface_url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
                    )
            );

            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            // pr($result);exit;
            // $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }

            $deval = $result['SBIePayDoubleVerificationResult'];

            $jarpath = $basepath . "jar_files/jh_gras_decryption.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Decription Jar File Not Found!';
                return $response;
            }
            $jarpath = "/usr/java/jdk1.8.0_131/bin/java -jar " . $jarpath;
            $jarpath = $jarpath . ' ' . $deval;
            exec($jarpath, $outputv);
            if (empty($outputv)) {
                $response['Error'] = 'Not able to decript data!';
                return $response;
            }
            // pr($outputv[0]);
            $result_arr = explode("|", $outputv[0]);
            //pr($result_arr);exit;
            if (empty($result_arr) || !is_array($result_arr)) {

                $response['Error'] = 'Invalid Service Response!';
                return $response;
            }
            $resmst = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
            foreach ($resmst as $key => $val) {
                $response_new[$val] = $result_arr[$key];
            }
            $result_arr = $response_new;

            if (strcmp(trim($result_arr['STATUS']), "SUCCESS") != 0) {
                $response['Error'] = $result_arr['PAYMENTSTATUSMESSAGE'] . " : " . $result_arr['STATUS'];
                return $response;
            }
            $result_office = $this->genernalinfoentry->find("first", array(
                'joins' => array(
                    array('table' => 'ngdrstab_mst_office', 'type' => 'INNER', 'alias' => 'office', 'conditions' => array("office.office_id=genernalinfoentry.office_id"))
                ),
                'fields' => array('office.gras_office_code', 'office.gras_treasury_code', 'genernalinfoentry.token_no'),
                'conditions' => array('token_no' => trim($token))
            ));

            if (strcmp(trim($result_arr['TREASCODE']), trim($result_office['office']['gras_treasury_code'])) != 0) {
                $response['Error'] = 'TREASURY  CODE Missmatch Found';
                return $response;
            }
            if (strcmp(trim($result_arr['IFMSOFFICECODE']), trim($result_office['office']['gras_office_code'])) != 0) {
                $response['Error'] = 'IFMS OFFICE CODE Missmatch Found';
                return $response;
            }
            if (strcmp(trim($result_arr['DEPOSITERID']), trim($token)) != 0) {
                if (@$data['f_vender_name'] == 2) {
                    
                } else {
                    $response['Error'] = 'Invalid Token Number';
                    return $response;
                }
            }

            if (isset($extrafields['StatusOnly'])) {
                return $result_arr;
            }

            $paidamount = $this->payment->find("all", array('fields' => 'account_head_code ,pamount', 'conditions' => array('token_no' => $token)));
            //pr($paidamount);exit;
            $totalfee = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            //$totalfee=2464;
            $PaymentData = array();
            $onlinedata = array();
            foreach ($acchead as $itemid => $headcode) {
                foreach ($accounthead as $key => $single) {
                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                        // if ($single[0]['totalsd'] > 0) {
                        $insertdata = array();
                        $insertdata = array_merge($insertdata, $extrafields);
                        $insertdata['online_verified_flag'] = 'Y';
                        $insertdata['defacement_flag'] = 'Y';
                        $insertdata['payment_mode_id'] = $data['payment_mode_id'];
                        $insertdata['grn_no'] = $result_arr['GRN'];
                        $insertdata['cin_no'] = $result_arr['CIN'];
                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = $result_arr['TXN_DATE'];
                        $insertdata['invoice_url'] = $result_arr['CHALLAN_URL'];
                        $insertdata['payee_fname_en'] = $result_arr['DEPOSITERNAME'];
                        $insertdata['bank_trn_id'] = $result_arr['DEPTTRANID'];

                        $paidamt = 0;
                        foreach ($paidamount as $paidamountsingle) {
                            if (strcmp(trim($headcode), $paidamountsingle['payment']['account_head_code']) == 0) {
                                $paidamt += $paidamountsingle['payment']['pamount'];
                            }
                        }
                        $single[0]['totalsd'] = $single[0]['totalsd'] - $paidamt;
                        if ($single[0]['totalsd'] > 0) {
                            if ($totalfee >= $single[0]['totalsd']) {
                                $insertdata['pamount'] = $single[0]['totalsd'];
                                $totalfee = $totalfee - $single[0]['totalsd'];
                            } else {
                                $insertdata['pamount'] = $totalfee;
                                $totalfee = 0;
                            }
                        }

                        if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                            array_push($PaymentData, $insertdata);
                            $onlinedata = $insertdata;
                        }
                        //}
                    }
                }
            }//
            $onlinedata['pamount'] = trim(str_replace(',', '', $result_arr['TXN_AMOUNT']));
            // pr($PaymentData);
            // exit;
            if (!empty($PaymentData)) {
                $PaymentData[count($PaymentData) - 1]['pamount'] += $totalfee;
                $response['PaymentData'] = $PaymentData;
                $response['OnlinePaymentData'] = $onlinedata;
            } else {
                $response['Error'] = 'NO account head found...!!!';
            }

            pr($response);
            exit;
            return $response;
        }
    }

    function gras_payment_verification_test_call() {
        $this->autoRender = FALSE;
        $rs = $this->gras_payment_verification_test();
        pr($rs);
        //  
//        if(437.52>=690){
//            echo 'IN';
//        }else{
//            echo 'out';
//        }
        exit;
    }

    public function mutationwss_old() {
//        pr($_REQUEST);exit;
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('District');
        $this->loadModel('ApiCredentials');
        $this->loadModel('genernalinfoentry');
//        $lang = $this->Session->read("sess_langauge");
//        $stateid = $this->Auth->User("state_id");
        $this->autoRender = FALSE;
        $this->layout = 'ajax';
        $response = array();
        $errorcodes = array(
            '1' => 'Failed for validation',
            '2' => 'Authention Failed',
            '3' => 'No Data Found',
            '4' => 'The maximum record limit is exceeded. Please add more conditions',
            '5' => 'Parametes not found',
            '6' => 'Someting Went Wrong'
        );
        $newsXML = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><DocumentElement></DocumentElement>", LIBXML_NOEMPTYTAG);
        $newsXML->addAttribute('xmlns', '');
// pr($_REQUEST);
        try {
            if ($_REQUEST['api_username'] != null && $_REQUEST['api_password'] != null && $_REQUEST['district_code_ll'] != null && $_REQUEST['taluka_code_ll'] != null && $_REQUEST['from_date'] != null && $_REQUEST['end_date'] != null) {

                $API_CODE = 'API00002';
//                $fieldlist['api_code']['text'] = 'is_alphanumeric';
//                $fieldlist['api_username']['text'] = 'is_required';
//                $fieldlist['api_password']['text'] = 'is_required';
//                $fieldlist['district_id']['select'] = 'is_select_req';
//                $fieldlist['taluka_id']['select'] = 'is_select_req';
//                $fieldlist['from_date']['text'] = 'is_required';
//                $fieldlist['end_date']['text'] = 'is_required';

                $data = $_REQUEST;
//                pr($data);
//                pr($data['district_id']);
                $data['api_code'] = $API_CODE;
//                 $data['district_code_ll'] = 18;
//                  $data['taluka_code_ll'] = 2;
//                  $district_id = $data['data']['district_id'];
//                  $taluka_id = $data['data']['taluka_id'];
//                  $district_code_ll = $this->District->query("select district_code_ll from ngdrstab_conf_admblock3_district where district_id = $district_id");
//                   $taluka_code_ll = $this->District->query("select taluka_code_ll from ngdrstab_conf_admblock5_taluka where taluka_id = $taluka_id");
//                   $data['district_code_ll'] = $district_code_ll[0][0]['district_code_ll'];
//                  $data['taluka_code_ll'] = $taluka_code_ll[0][0]['taluka_code_ll'];
//                  pr($data);exit;
//                $verrors = $this->validatedata($data, $fieldlist);
//pr($verrors);exit;
//                if ($this->ValidationError($verrors)) {
                if ($this->ApiCredentials->authenticate($data)) {
                    $details1 = $this->genernalinfoentry->query("select app.token_no,
district.district_name_en,
office.office_name_en,
app.final_doc_reg_no,
article.article_desc_en,
info.presentation_date,
info.exec_date,
(SELECT cons_amt
                                                          FROM   ngdrstab_trn_fee_calculation fee
                                                          WHERE  fee.token_no = info.token_no                                                         
                                                          AND delete_flag = 'N'
                                                          AND fee.article_id = info.article_id),

                                                          (SELECT Sum(final_value)
                                                           FROM   ngdrstab_trn_valuation_details vd
                                                          WHERE  vd.val_id IN (select val_id from ngdrstab_trn_property_details_entry where token_no=app.token_no and val_id>0 )  
                                                           AND item_type_id = 2)    AS DocumentValue 
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = app.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
	   JOIN ngdrstab_mst_office as office ON office.office_id=app.office_id	 
          JOIN ngdrstab_conf_admblock3_district AS district
         ON district.district_id = office.district_id
         where app.token_no IN(
         select app.token_no                                   
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = app.token_no
         JOIN ngdrstab_conf_admblock3_district as dist ON dist.district_id= prop.district_id          
          JOIN ngdrstab_conf_admblock5_taluka as taluka ON taluka.taluka_id=prop.taluka_id
and dist.district_code_ll=? and taluka.taluka_code_ll=?
WHERE 
final_stamp_flag = 'Y'
and final_stamp_date::date>=?  and final_stamp_date::date <=?
)

       ", array($data['district_code_ll'], $data['taluka_code_ll'], $data['from_date'], $data['end_date']));
//                        pr($details1);exit;
                    if (!empty($details1)) {
                        $token = $details1[0][0]['token_no'];

                        foreach ($details1 as $d) {
                            $RegDetails = $newsXML->addChild('RegDetails');
//            $newsIntro->addAttribute('type', 'latest');

                            $RegDetails1 = $RegDetails->addChild('DISTRICT', ' ' . $d[0]['district_name_en']);
                            $RegDetails2 = $RegDetails->addChild('SUBOFFICE', ' ' . $d[0]['office_name_en']);
                            $RegDetails3 = $RegDetails->addChild('DEEDNO', ' ' . $d[0]['final_doc_reg_no']);
                            $RegDetails4 = $RegDetails->addChild('DOP', ' ' . $d[0]['presentation_date']);
                            $RegDetails5 = $RegDetails->addChild('DOE', ' ' . $d[0]['exec_date']);
                            $RegDetails6 = $RegDetails->addChild('DTYPE', ' ' . $d[0]['article_desc_en']);
                            $RegDetails7 = $RegDetails->addChild('DOCVALUE', ' ' . $d[0]['documentvalue']);

//-----------------------------second session property details--------------------
                            $details2 = $this->genernalinfoentry->query("SELECT info.token_no,
       prop.property_id,
       taluka.taluka_name_en as anchal,
       circle.circle_name_en,
       circle.circle_code,
       village.village_name_en as mauja,
       village.state_spacific_code as mauja_code,
       taluka.taluka_code as thana_code,
       taluka.taluka_code_ll as taluka_code,
       prop.boundries_north_en as boundries_n,
       prop.boundries_south_en as boundries_s,
       prop.boundries_east_en as boundries_e,
       prop.boundries_west_en as boundries_w,
       article.article_desc_en,   
          (SELECT
String_agg( paramter_value ,' , ') as khata
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205),
         (SELECT
String_agg(paramter_value, ' , ') as plot
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=206),
        array_to_string(ARRAY(SELECT Concat(Sum(item_value), '|', unit.unit_desc_en)
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1
            GROUP  BY unit.unit_desc_en ), ',') as area
      
FROM   ngdrstab_trn_application_submitted AS app
       JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop .token_no = app.token_no
       JOIN ngdrstab_mst_finyear AS finyear
         ON finyear.finyear_id = prop.finyear_id
       JOIN ngdrstab_conf_admblock5_taluka AS taluka
         ON taluka.taluka_id = prop.taluka_id
       JOIN ngdrstab_conf_admblock7_village_mapping AS village
         ON village.village_id = prop.village_id
       JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = prop.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
         left JOIN ngdrstab_conf_admblock6_circle AS circle
         ON circle.circle_id = village.circle_id
WHERE  final_stamp_flag = 'Y'
       AND prop .val_id > 0 AND prop.token_no=$token
GROUP  BY info.token_no,
          info.article_id,
          app.final_stamp_date,
          app.final_doc_reg_no,
          prop.property_id,
          taluka.taluka_name_en,
          village.village_name_en,
          article.article_desc_en,
          finyear.finyear_desc  ,
          circle.circle_code,
          village.state_spacific_code,
          taluka.taluka_code ,
          taluka.taluka_code_ll,
       prop.boundries_north_en,
       prop.boundries_south_en,
       prop.boundries_east_en,
       prop.boundries_west_en,
           circle.circle_name_en");
//pr($details2);exit;
//                                (SELECT
//String_agg(Concat(attribute_id,'-',mstparam.eri_attribute_name, '|', paramter_value), ' , ') as khata
// FROM   ngdrstab_trn_parameter trnparam
//        JOIN ngdrstab_mst_attribute_parameter AS mstparam
//          ON mstparam.attribute_id = trnparam.paramter_id
// WHERE  trnparam.token_id = info.token_no
//        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205)
//pr($details2);
                            if (!empty($details2)) {
                                $PropDetails = $RegDetails->addChild('property');
                                $i = 0;

                                foreach ($details2 as $d) {

                                    $i++;
                                    pr($d[0]['boundries_n']);
                                    $PropDetailss = $PropDetails->addChild('p' . $i, '');
                                    $PropDetailss->addChild('Anchal', ' ' . $d[0]['anchal']);
                                    $PropDetailss->addChild('Circle_Code', ' ' . $d[0]['taluka_code']);
                                    $PropDetailss->addChild('Circle_Name', ' ' . $d[0]['anchal']);
                                    $PropDetailss->addChild('Mauja', ' ' . $d[0]['mauja']);
                                    $PropDetailss->addChild('Mauja_Code', ' ' . $d[0]['mauja_code']);
                                    $PropDetailss->addChild('Mauja_Name', ' ' . $d[0]['mauja']);
                                    $PropDetailss->addChild('Thana_Code', ' ' . $d[0]['thana_code']);
                                    $PropDetailss->addChild('Plot_No', ' ' . $d[0]['plot']);
                                    $PropDetailss->addChild('Plot_Type', ' ');
                                    $PropDetailss->addChild('Boundary_N', ' ' . $d[0]['boundries_n']);
                                    $PropDetailss->addChild('Boundary_S', ' ' . $d[0]['boundries_s']);
                                    $PropDetailss->addChild('Boundary_E', ' ' . $d[0]['boundries_e']);
                                    $PropDetailss->addChild('Boundary_W', ' ' . $d[0]['boundries_w']);
                                    $PropDetailss->addChild('Khata_No', ' ' . $d[0]['khata'] . ' ');
                                    $PropDetailss->addChild('Area', ' ' . $d[0]['area'] . ' ');
                                    $PropDetailss->addChild('Unit', ' ' . $d[0]['area'] . ' ');
//                                        $PropDetailss->addChild('Unit', ' ' . $d[0]['area'] . ' ');
                                    // $PropDetailss->addChild('Unit', ' ' . $d[0]['area'] . ' ');
                                    //echo $PropDetails->asXML();
                                }
                            }
//-----------------------party 1 and 2-----------------------------------------------------------------------------------
                            $detailsparty = $this->genernalinfoentry->query("select party.token_no,party.party_type_id,party.party_full_name_en,party.father_full_name_en,ptype.party_type_desc_en,party.address_en,party.mobile_no,party.email_id,gender.gender_desc_en,party.uid
 from ngdrstab_trn_party_entry_new as party
JOIN ngdrstab_mst_party_type ptype
          ON ptype.party_type_id = party.party_type_id  
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = party.gender_id
           JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = party.token_no
        WHERE  party.token_no=$token   order by party_type_flag ASC");

                            if (!empty($detailsparty)) {
                                $partyDetails = $RegDetails->addChild('party');
                                foreach ($detailsparty as $d) {
                                    $partyDetailss = $partyDetails->addChild('prt');
                                    $partyDetailss->addChild('Type', ' ' . $d[0]['party_type_desc_en']);
                                    $partyDetailss->addChild('Name', ' ' . $d[0]['party_full_name_en']);
                                    $partyDetailss->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);
                                    $partyDetailss->addChild('Relation_Code', ' ' . $d[0]['father_full_name_en']);
                                    $partyDetailss->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                    $partyDetailss->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                    $partyDetailss->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                    $partyDetailss->addChild('Email', ' ' . $d[0]['email_id']);
                                    $partyDetailss->addChild('Caste_Code', ' ' . $d[0]['father_full_name_en']);
                                    $partyDetailss->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                    $partyDetailss->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid']));
                                }

                                //---------------------identifier details------------------------------
                                $detailsidentification = $this->genernalinfoentry->query("select itype.desc_en,identification.identification_full_name_en,identification.father_full_name_en,
 identification.address_en,
 identification.mobile_no,
 identification.email_id,gender.gender_desc_en,
 identification.uid_no
 from ngdrstab_trn_identification as identification
JOIN ngdrstab_mst_identifier_type itype
          ON itype.type_id = identification.identifire_type
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = identification.gender_id
        WHERE  identification.token_no=$token");
//                pr($detailsparty);
//                exit;
                                if (!empty($detailsidentification)) {
                                    //$identificationDetails = $RegDetails->addChild('Identifier');
                                    foreach ($detailsidentification as $d) {
                                        $identificationDetailss = $partyDetails->addChild('prt');
                                        $identificationDetailss->addChild('Type', 'Identifier');
                                        $identificationDetailss->addChild('Name', ' ' . $d[0]['identification_full_name_en']);
                                        $identificationDetailss->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);
                                        $identificationDetailss->addChild('Relation_Code', ' ' . $d[0]['father_full_name_en']);
                                        $identificationDetailss->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                        $identificationDetailss->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                        $identificationDetailss->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                        $identificationDetailss->addChild('Email', ' ' . $d[0]['email_id']);
                                        $identificationDetailss->addChild('Caste_Code', ' ' . $d[0]['father_full_name_en']);
                                        $identificationDetailss->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                        $identificationDetailss->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid_no']));
                                    }
                                }

                                //---------------------witness details------------------------------
                                $detailswitness = $this->genernalinfoentry->query(" select witness.witness_full_name_en,witness.father_full_name_en,
 witness.address_en,
 witness.mobile_no,
 witness.email_id,gender.gender_desc_en,
 witness.uid_no
 from ngdrstab_trn_witness as witness

          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = witness.gender_id
        WHERE  witness.token_no=$token");
//                pr($detailsparty);
//                exit;
                                if (!empty($detailswitness)) {
                                    // $detailswitnes1 = $RegDetails->addChild('Witness');
                                    foreach ($detailswitness as $d) {
                                        //  pr($d);exit;
                                        $detailswitnes = $partyDetails->addChild('prt');
                                        $detailswitnes->addChild('Type', 'Witness');
                                        $detailswitnes->addChild('Name', ' ' . $d[0]['witness_full_name_en']);
                                        $detailswitnes->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);
                                        $detailswitnes->addChild('Relation_Code', ' ' . $d[0]['father_full_name_en']);
                                        $detailswitnes->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                        $detailswitnes->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                        $detailswitnes->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                        $detailswitnes->addChild('Email', ' ' . $d[0]['email_id']);
                                        $detailswitnes->addChild('Caste_Code', ' ' . $d[0]['father_full_name_en']);
                                        $detailswitnes->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                        $detailswitnes->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid_no']));
                                    }
                                }
                            }
                        }
                    } else {
                        $response['ERROR_CODE'] = '3';
                    }
                } else {
                    $response['ERROR_CODE'] = '2';
                }
//                } else {
//                    $response['ERROR_CODE'] = '1';
//                }
            } else {
                $response['ERROR_CODE'] = '5';
            }
        } catch (Exception $ex) {
            $response['ERROR_CODE'] = '6';
            //pr($ex);exit;
            $response['ERROR_MSG'] = $ex->getMessage();
        }

        if (isset($response['ERROR_CODE'])) {
            $newsXML->addChild("ERROR_CODE", $response['ERROR_CODE']);
            $newsXML->addChild("ERROR_DESC", @$errorcodes[$response['ERROR_CODE']] . " - " . @$response['ERROR_MSG']);
        }
        ob_clean();
        Header('Content-type: text/xml');
        echo $newsXML->asXML();
        exit;
    }

    public function mutationwss() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('District');
        $this->loadModel('ApiCredentials');
        $this->loadModel('genernalinfoentry');
//        $lang = $this->Session->read("sess_langauge");
//        $stateid = $this->Auth->User("state_id");
        $this->autoRender = FALSE;
        $this->layout = 'ajax';
        $response = array();
        $errorcodes = array(
            '1' => 'Failed for validation',
            '2' => 'Authention Failed',
            '3' => 'No Data Found',
            '4' => 'The maximum record limit is exceeded. Please add more conditions',
            '5' => 'Parametes not found',
            '6' => 'Someting Went Wrong'
        );
        $newsXML = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><DocumentElement></DocumentElement>", LIBXML_NOEMPTYTAG);
        $newsXML->addAttribute('xmlns', '');

        try {
            if ($this->request->is('post')) {

                $API_CODE = 'API00002';
                $fieldlist['api_code']['text'] = 'is_alphanumeric';
                $fieldlist['api_username']['text'] = 'is_required';
                $fieldlist['api_password']['text'] = 'is_required';
                $fieldlist['district_code_ll']['select'] = 'is_select_req';
                $fieldlist['taluka_code_ll']['select'] = 'is_select_req';
                $fieldlist['from_date']['text'] = 'is_required';
                $fieldlist['end_date']['text'] = 'is_required';


                // pr($this->request);exit;
                $data = $_REQUEST;

                $data['api_code'] = $API_CODE;

                $verrors = $this->validatedata($data, $fieldlist);
                //pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    if ($this->ApiCredentials->authenticate($data)) {
                        $details1 = $this->genernalinfoentry->query("select app.token_no,
district.district_name_en,
office.office_name_en,
app.final_doc_reg_no,
article.article_desc_en,
info.presentation_date,
info.exec_date,
(SELECT cons_amt
                                                          FROM   ngdrstab_trn_fee_calculation fee
                                                          WHERE  fee.token_no = info.token_no                                                         
                                                          AND delete_flag = 'N'
                                                          AND fee.article_id = info.article_id),

                                                          (SELECT Sum(final_value)
                                                           FROM   ngdrstab_trn_valuation_details vd
                                                          WHERE  vd.val_id IN (select val_id from ngdrstab_trn_property_details_entry where token_no=app.token_no and val_id>0 )  
                                                           AND item_type_id = 2)    AS DocumentValue 
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = app.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
	   JOIN ngdrstab_mst_office as office ON office.office_id=app.office_id	 
          JOIN ngdrstab_conf_admblock3_district AS district
         ON district.district_id = office.district_id
         where app.token_no IN(
         select app.token_no                                   
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = app.token_no
         JOIN ngdrstab_conf_admblock3_district as dist ON dist.district_id= prop.district_id          
          JOIN ngdrstab_conf_admblock5_taluka as taluka ON taluka.taluka_id=prop.taluka_id
and dist.district_code_ll=? and taluka.taluka_code_ll=?
WHERE 
final_stamp_flag = 'Y'
and final_stamp_date::date>=?  and final_stamp_date::date <=?
)

       ", array($data['district_code_ll'], $data['taluka_code_ll'], $data['from_date'], $data['end_date']));


                        if (!empty($details1)) {
                            $token = $details1[0][0]['token_no'];

                            foreach ($details1 as $d) {
                                $RegDetails = $newsXML->addChild('RegDetails');
//            $newsIntro->addAttribute('type', 'latest');

                                $RegDetails1 = $RegDetails->addChild('DISTRICT', ' ' . $d[0]['district_name_en']);
                                $RegDetails2 = $RegDetails->addChild('SUBOFFICE', ' ' . $d[0]['office_name_en']);
                                $RegDetails3 = $RegDetails->addChild('DEEDNO', ' ' . $d[0]['final_doc_reg_no']);
                                $RegDetails4 = $RegDetails->addChild('DOP', ' ' . $d[0]['presentation_date']);
                                $RegDetails5 = $RegDetails->addChild('DOE', ' ' . $d[0]['exec_date']);
                                $RegDetails6 = $RegDetails->addChild('DTYPE', ' ' . $d[0]['article_desc_en']);
                                $RegDetails7 = $RegDetails->addChild('DOCVALUE', ' ' . $d[0]['documentvalue']);

//-----------------------------second session property details--------------------
                                $details2 = $this->genernalinfoentry->query("SELECT info.token_no,
       prop.property_id,
       taluka.taluka_name_en as anchal,
       taluka.taluka_code_ll ,
       circle.circle_name_en,
       circle.circle_code,
       village.village_name_en as mauja,
       village.village_code as mauja_code,
       taluka.taluka_code as thana_code,
       prop.boundries_north_en as boundries_n,
       prop.boundries_south_en as boundries_s,
       prop.boundries_east_en as boundries_e,
       prop.boundries_west_en as boundries_w,
       article.article_desc_en,   
          (SELECT
String_agg( paramter_value ,' , ') as khata
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205),
         (SELECT
String_agg(paramter_value, ' , ') as plot
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=206),
        array_to_string(ARRAY(SELECT Concat(Sum(item_value), '|', unit.unit_desc_en)
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1
            GROUP  BY unit.unit_desc_en ), ',') as area
      
FROM   ngdrstab_trn_application_submitted AS app
       JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop .token_no = app.token_no
       JOIN ngdrstab_mst_finyear AS finyear
         ON finyear.finyear_id = prop.finyear_id
       JOIN ngdrstab_conf_admblock5_taluka AS taluka
         ON taluka.taluka_id = prop.taluka_id
       JOIN ngdrstab_conf_admblock7_village_mapping AS village
         ON village.village_id = prop.village_id
       JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = prop.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
         left JOIN ngdrstab_conf_admblock6_circle AS circle
         ON circle.circle_id = village.circle_id
WHERE  final_stamp_flag = 'Y'
       AND prop .val_id > 0 AND prop.token_no=$token
GROUP  BY info.token_no,
          info.article_id,
          app.final_stamp_date,
          app.final_doc_reg_no,
          prop.property_id,
          taluka.taluka_name_en,
          village.village_name_en,
          article.article_desc_en,
          finyear.finyear_desc  ,
          circle.circle_code,
          village.village_code,
          taluka.taluka_code ,
       prop.boundries_north_en,
       prop.boundries_south_en,
       prop.boundries_east_en,
       prop.boundries_west_en,
           circle.circle_name_en");
//pr($details2);exit;
//                                (SELECT
//String_agg(Concat(attribute_id,'-',mstparam.eri_attribute_name, '|', paramter_value), ' , ') as khata
// FROM   ngdrstab_trn_parameter trnparam
//        JOIN ngdrstab_mst_attribute_parameter AS mstparam
//          ON mstparam.attribute_id = trnparam.paramter_id
// WHERE  trnparam.token_id = info.token_no
//        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205)



                                if (!empty($details2)) {
                                    $PropDetails = $RegDetails->addChild('property');
                                    $i = 0;

                                    foreach ($details2 as $d) {
                                        $i++;
                                        $PropDetailss = $PropDetails->addChild('p' . $i, '');
                                        $PropDetailss->addChild('Anchal', ' ' . $d[0]['anchal']);
                                        $PropDetailss->addChild('Circle_Code', ' ' . $d[0]['taluka_code_ll']);
                                        $PropDetailss->addChild('Circle_Name', ' ' . $d[0]['anchal']);
                                        $PropDetailss->addChild('Mauja', ' ' . $d[0]['mauja']);
                                        $PropDetailss->addChild('Mauja_Code', ' ' . $d[0]['mauja_code']);
                                        $PropDetailss->addChild('Mauja_Name', ' ' . $d[0]['mauja']);
                                        $PropDetailss->addChild('Thana_Code', ' ' . $d[0]['thana_code']);
                                        $PropDetailss->addChild('Plot_No', ' ' . $d[0]['plot']);
                                        $PropDetailss->addChild('Boundary_N', ' ' . $d[0]['boundries_n']);
                                        $PropDetailss->addChild('Boundary_S', ' ' . $d[0]['boundries_s']);
                                        $PropDetailss->addChild('Boundary_E', ' ' . $d[0]['boundries_e']);
                                        $PropDetailss->addChild('Boundary_W', ' ' . $d[0]['boundries_w']);
                                        $PropDetailss->addChild('Khata_No', ' ' . $d[0]['khata'] . ' ');
                                        $PropDetailss->addChild('Area', ' ' . $d[0]['area'] . ' ');
                                        // $PropDetailss->addChild('Unit', ' ' . $d[0]['area'] . ' ');
                                        //echo $PropDetails->asXML();
                                    }
                                }
//-----------------------party 1 and 2-----------------------------------------------------------------------------------
                                $detailsparty = $this->genernalinfoentry->query("select party.token_no,party.party_type_id,party.party_full_name_en,party.father_full_name_en,ptype.party_type_desc_en,party.address_en,party.mobile_no,party.email_id,gender.gender_desc_en,party.uid,party.maincast_id,party.cast_id
 from ngdrstab_trn_party_entry_new as party
JOIN ngdrstab_mst_party_type ptype
          ON ptype.party_type_id = party.party_type_id  
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = party.gender_id
           JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = party.token_no
        WHERE  party.token_no=$token   order by party_type_flag ASC");

                                if (!empty($detailsparty)) {
                                    $partyDetails = $RegDetails->addChild('party');
                                    foreach ($detailsparty as $d) {
                                        $partyDetailss = $partyDetails->addChild('prt');
                                        $partyDetailss->addChild('Type', ' ' . $d[0]['party_type_desc_en']);
                                        $partyDetailss->addChild('Name', ' ' . $d[0]['party_full_name_en']);
                                        $partyDetailss->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);
                                        $partyDetailss->addChild('Relation_Code', ' ' );
                                        $partyDetailss->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                        $partyDetailss->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                        $partyDetailss->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                        $partyDetailss->addChild('Email', ' ' . $d[0]['email_id']);
                                        $partyDetailss->addChild('Caste_Code', ' '  . $d[0]['cast_id']);
                                        $partyDetailss->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                        $partyDetailss->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid']));
                                    }

                                    //---------------------identifier details------------------------------
                                    $detailsidentification = $this->genernalinfoentry->query("select itype.desc_en,identification.identification_full_name_en,identification.father_full_name_en,
 identification.address_en,
 identification.mobile_no,
 identification.email_id,gender.gender_desc_en,
 identification.uid_no
 from ngdrstab_trn_identification as identification
JOIN ngdrstab_mst_identifier_type itype
          ON itype.type_id = identification.identifire_type
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = identification.gender_id
        WHERE  identification.token_no=$token");
//                pr($detailsparty);
//                exit;
                                    if (!empty($detailsidentification)) {
                                        //$identificationDetails = $RegDetails->addChild('Identifier');
                                        foreach ($detailsidentification as $d) {
                                            $identificationDetailss = $partyDetails->addChild('prt');
                                            $identificationDetailss->addChild('Type', 'Identifier');
                                            $identificationDetailss->addChild('Name', ' ' . $d[0]['identification_full_name_en']);
                                            $identificationDetailss->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);                                
                                            $identificationDetailss->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                            $identificationDetailss->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                            $identificationDetailss->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                            $identificationDetailss->addChild('Email', ' ' . $d[0]['email_id']);
                                            $identificationDetailss->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                            $identificationDetailss->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid_no']));
                                        }
                                    }

                                    //---------------------witness details------------------------------
                                    $detailswitness = $this->genernalinfoentry->query(" select witness.witness_full_name_en,witness.father_full_name_en,
 witness.address_en,
 witness.mobile_no,
 witness.email_id,gender.gender_desc_en,
 witness.uid_no
 from ngdrstab_trn_witness as witness

          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = witness.gender_id
        WHERE  witness.token_no=$token");
//                pr($detailsparty);
//                exit;
                                    if (!empty($detailswitness)) {
                                        // $detailswitnes1 = $RegDetails->addChild('Witness');
                                        foreach ($detailswitness as $d) {
                                            //  pr($d);exit;
                                            $detailswitnes = $partyDetails->addChild('prt');
                                            $detailswitnes->addChild('Type', 'Witness');
                                            $detailswitnes->addChild('Name', ' ' . $d[0]['witness_full_name_en']);
                                            $detailswitnes->addChild('Father_Name', ' ' . $d[0]['father_full_name_en']);                                           
                                            $detailswitnes->addChild('Present_Address', ' ' . $d[0]['address_en']);
                                            $detailswitnes->addChild('Permanent_Address', ' ' . $d[0]['address_en']);
                                            $detailswitnes->addChild('Mobile', ' ' . $d[0]['mobile_no']);
                                            $detailswitnes->addChild('Email', ' ' . $d[0]['email_id']);
                                            $detailswitnes->addChild('Gender_Code', ' ' . $d[0]['gender_desc_en']);
                                            $detailswitnes->addChild('Aadhaar', ' ' . $this->dec($d[0]['uid_no']));
                                        }
                                    }
                                }
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
        } catch (Exception $ex) {
            $response['ERROR_CODE'] = '6';
            $response['ERROR_MSG'] = $ex->getMessage();
        }

        if (isset($response['ERROR_CODE'])) {
            $newsXML->addChild("ERROR_CODE", $response['ERROR_CODE']);
            $newsXML->addChild("ERROR_DESC", @$errorcodes[$response['ERROR_CODE']] . " - " . @$response['ERROR_MSG']);
        }
        ob_clean();
        Header('Content-type: text/xml');
        echo $newsXML->asXML();
        exit;
    }

    public function mutationws() {
        $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('District');
        $this->loadModel('ApiCredentials');
        $this->loadModel('genernalinfoentry');
//        $lang = $this->Session->read("sess_langauge");
//        $stateid = $this->Auth->User("state_id");
        $this->autoRender = FALSE;
        $this->layout = 'ajax';
        $response = array();
        $errorcodes = array(
            '1' => 'Failed for validation',
            '2' => 'Authention Failed',
            '3' => 'No Data Found',
            '4' => 'The maximum record limit is exceeded. Please add more conditions',
            '5' => 'Parametes not found',
            '6' => 'Someting Went Wrong'
        );
        $newsXML = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><DocumentElement></DocumentElement>", LIBXML_NOEMPTYTAG);
        $newsXML->addAttribute('xmlns', '');

        try {
            if ($this->request->is('post')) {

                $API_CODE = 'API00002';
                $fieldlist['api_code']['text'] = 'is_alphanumeric';
                $fieldlist['api_username']['text'] = 'is_required';
                $fieldlist['api_password']['text'] = 'is_required';
                $fieldlist['district_code_ll']['select'] = 'is_select_req';
                $fieldlist['taluka_code_ll']['select'] = 'is_select_req';
                //$fieldlist['from_date']['text'] = 'is_required';
                //$fieldlist['end_date']['text'] = 'is_required';


                // pr($this->request);exit;
                $data = $_REQUEST;
               // pr($data);exit;
                $data['api_code'] = $API_CODE;

                $verrors = $this->validatedata($data, $fieldlist);
                //pr($verrors);exit;
               
                if ($this->ValidationError($verrors)) {
                    if ($this->ApiCredentials->authenticate($data)) {
                        $details1 = $this->genernalinfoentry->query("select app.token_no,
district.district_name_en,
office.office_name_en,
app.final_doc_reg_no,
app.doc_reg_date,
article.article_desc_en,
info.presentation_date,
info.exec_date,
(SELECT cons_amt
                                                          FROM   ngdrstab_trn_fee_calculation fee
                                                          WHERE  fee.token_no = info.token_no                                                         
                                                          AND delete_flag = 'N'
                                                          AND fee.article_id = info.article_id),

                                                          (SELECT Sum(final_value)
                                                           FROM   ngdrstab_trn_valuation_details vd
                                                          WHERE  vd.val_id IN (select val_id from ngdrstab_trn_property_details_entry where token_no=app.token_no and val_id>0 )  
                                                           AND item_type_id = 2)    AS DocumentValue 
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = app.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
	   JOIN ngdrstab_mst_office as office ON office.office_id=app.office_id	 
          JOIN ngdrstab_conf_admblock3_district AS district
         ON district.district_id = office.district_id
         where app.token_no IN(
         select app.token_no                                   
FROM   ngdrstab_trn_application_submitted AS app
        JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = app.token_no
         JOIN ngdrstab_conf_admblock3_district as dist ON dist.district_id= prop.district_id          
          JOIN ngdrstab_conf_admblock5_taluka as taluka ON taluka.taluka_id=prop.taluka_id
and dist.district_code_ll=? and taluka.taluka_code_ll=?
WHERE 
final_stamp_flag = 'Y'
and final_stamp_date::date>=?  and final_stamp_date::date <=?
)

       ", array($data['district_code_ll'], $data['taluka_code_ll'], $data['from_date'], $data['to_date']));


                        if (!empty($details1)) {
                           // pr($details1);exit;
                            
                            //$valiid=$details1[0][0]['val_id'];
                            foreach ($details1 as $d) {
                                
                                $token = $d[0]['token_no'];
                                //pr($token);//exit;
                                $RegDetails = $newsXML->addChild('RegDetails');
//            $newsIntro->addAttribute('type', 'latest');

                                $RegDetails1 = $RegDetails->addChild('DISTRICT', '' .$d[0]['district_name_en']);
                                $RegDetails2 = $RegDetails->addChild('SUBOFFICE', '' . $d[0]['office_name_en']);
                                $RegDetails3 = $RegDetails->addChild('DEEDNO', '' . $d[0]['final_doc_reg_no']);
                                $RegDetails4 = $RegDetails->addChild('DOP', '' . $d[0]['presentation_date']);
                               // $RegDetails5 = $RegDetails->addChild('DOE', '' . $d[0]['exec_date']);
                                $RegDetails5 = $RegDetails->addChild('DOE', '' . $d[0]['doc_reg_date']);
                                $RegDetails6 = $RegDetails->addChild('DTYPE', '' . $d[0]['article_desc_en']);
                                $RegDetails7 = $RegDetails->addChild('DOCVALUE', '' . $d[0]['documentvalue']);

                               // pr($valiid);
                               // pr($token);
//-----------------------------second session property details--------------------
                                $details2 = $this->genernalinfoentry->query("SELECT info.token_no,
       prop.property_id,
       taluka.taluka_name_en as anchal,
       taluka.taluka_code_ll ,
       circle.circle_name_en,
       circle.circle_code,
       village.village_name_en as mauja,
       village.village_code as mauja_code,
       taluka.taluka_code as thana_code,
       prop.boundries_north_en as boundries_n,
       prop.boundries_south_en as boundries_s,
       prop.boundries_east_en as boundries_e,
       prop.boundries_west_en as boundries_w,
       article.article_desc_en,   
          (SELECT
String_agg( paramter_value ,' , ') as khata
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205),
         (SELECT
String_agg(paramter_value, ' , ') as plot
 FROM   ngdrstab_trn_parameter trnparam
        JOIN ngdrstab_mst_attribute_parameter AS mstparam
          ON mstparam.attribute_id = trnparam.paramter_id
 WHERE  trnparam.token_id = info.token_no
        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=206),
        array_to_string(ARRAY(SELECT Concat(Sum(item_value), '|', unit.unit_desc_en)
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1
            GROUP  BY unit.unit_desc_en ), ',') as area,
            
             array_to_string(ARRAY(SELECT Concat(Sum(item_value))
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1 and item_id=8
            GROUP  BY unit.unit_desc_en ), ',') as area_con,
            
 array_to_string(ARRAY(SELECT Concat(Sum(item_value))
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1 and item_id=1
            GROUP  BY unit.unit_desc_en ), ',') as area_land,
            
             array_to_string(ARRAY(SELECT Concat((unit.unit_desc_en))
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1 and item_id=8
            GROUP  BY unit.unit_desc_en ), ',') as unit_con,
            
 array_to_string(ARRAY(SELECT Concat((unit.unit_desc_en))
            FROM   ngdrstab_trn_valuation_details vd
                   JOIN ngdrstab_mst_usage_items_list item
                     ON item.usage_param_id = vd.item_id
                        AND area_field_flag = 'Y'
                   JOIN ngdrstab_mst_unit unit
                     ON unit.unit_id = vd.area_unit
            WHERE  vd.val_id = prop.val_id
                   AND vd.item_type_id = 1 and item_id=1
            GROUP  BY unit.unit_desc_en ), ',') as unit_land
            
      
FROM   ngdrstab_trn_application_submitted AS app
       JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop .token_no = app.token_no
       JOIN ngdrstab_mst_finyear AS finyear
         ON finyear.finyear_id = prop.finyear_id
       JOIN ngdrstab_conf_admblock5_taluka AS taluka
         ON taluka.taluka_id = prop.taluka_id
       JOIN ngdrstab_conf_admblock7_village_mapping AS village
         ON village.village_id = prop.village_id
       JOIN ngdrstab_trn_generalinformation AS info
         ON info.token_no = prop.token_no
       JOIN ngdrstab_mst_article AS article
         ON article.article_id = info.article_id
         left JOIN ngdrstab_conf_admblock6_circle AS circle
         ON circle.circle_id = village.circle_id
WHERE  final_stamp_flag = 'Y'
       AND prop .val_id > 0 AND prop.token_no=$token
GROUP  BY info.token_no,
          info.article_id,
          app.final_stamp_date,
          app.final_doc_reg_no,
          prop.property_id,
          taluka.taluka_name_en,
          taluka.taluka_code_ll,
          village.village_name_en,
          article.article_desc_en,
          finyear.finyear_desc  ,
          circle.circle_code,
          village.village_code,
          taluka.taluka_code ,
       prop.boundries_north_en,
       prop.boundries_south_en,
       prop.boundries_east_en,
       prop.boundries_west_en,
           circle.circle_name_en");
//pr($details2);exit;
//                                (SELECT
//String_agg(Concat(attribute_id,'-',mstparam.eri_attribute_name, '|', paramter_value), ' , ') as khata
// FROM   ngdrstab_trn_parameter trnparam
//        JOIN ngdrstab_mst_attribute_parameter AS mstparam
//          ON mstparam.attribute_id = trnparam.paramter_id
// WHERE  trnparam.token_id = info.token_no
//        AND trnparam.property_id = prop.property_id and  mstparam.attribute_id=205)



                                if (!empty($details2)) {
                                    $PropDetails = $RegDetails->addChild('property');
                                    $i = 0;

                                    foreach ($details2 as $d) {
                                        $i++;
                                        $PropDetailss = $PropDetails->addChild('p' . $i, '');
                                        $PropDetailss->addChild('Anchal', '' . $d[0]['anchal']);
                                        $PropDetailss->addChild('Circle_Code', '' . $d[0]['taluka_code_ll']);
                                        $PropDetailss->addChild('Circle_Name', '' . $d[0]['anchal']);
                                        $PropDetailss->addChild('Mauja', '' . $d[0]['mauja']);
                                        if($d[0]['mauja_code'])
                                            $PropDetailss->addChild('Mauja_Code', '' . $d[0]['mauja_code']);
                                        else
                                            $PropDetailss->addChild('Mauja_Code', '' . 'NA');
                                        $PropDetailss->addChild('Mauja_Name', '' . $d[0]['mauja']);
                                        $PropDetailss->addChild('Thana_Code', '' . $d[0]['thana_code']);
                                       // pr($d[0]['plot']);exit;
                                        $plot_no_send=explode(' ',$d[0]['plot']);
                                        
                                        $PropDetailss->addChild('Plot_No', '' . $plot_no_send[0]);
                                        /*$PropDetailss->addChild('Boundary_N', '' . $d[0]['boundries_n']);
                                        $PropDetailss->addChild('Boundary_S', '' . $d[0]['boundries_s']);
                                        $PropDetailss->addChild('Boundary_E', '' . $d[0]['boundries_e']);
                                        $PropDetailss->addChild('Boundary_W', '' . $d[0]['boundries_w']);*/
                                        
                                        $boundriesn=str_replace("'","",$d[0]['boundries_n']);
                                        $boundriesn=  htmlspecialchars($boundriesn);
                                        $PropDetailss->addChild('Boundary_N', '' . $boundriesn);
                                        
                                        $boundriess=str_replace("'","",$d[0]['boundries_s']);
                                        $boundriess=  htmlspecialchars($boundriess);
                                        $PropDetailss->addChild('Boundary_S', '' . $boundriess);
                                        
                                        $boundriese=str_replace("'","",$d[0]['boundries_e']);
                                        $boundriese=  htmlspecialchars($boundriese);
                                        $PropDetailss->addChild('Boundary_E', '' . $boundriese);
                                        
                                        $boundriesw=str_replace("'","",$d[0]['boundries_w']);
                                        $boundriesw=  htmlspecialchars($boundriesw);
                                       // pr($boundriesw);exit;
                                        $PropDetailss->addChild('Boundary_W', '' . $boundriesw);
                                        
                                        $PropDetailss->addChild('Khata_No', '' . $d[0]['khata'] . '');
                                        $PropDetailss->addChild('Land_Area', '' . $d[0]['area_land'] . '');
                                        $PropDetailss->addChild('Land_Area_Unit', '' . $d[0]['unit_land'] . '');
                                        $PropDetailss->addChild('Constructed_Area', '' . $d[0]['area_con'] . '');
                                        $PropDetailss->addChild('Constructed_Area_Unit', '' . $d[0]['unit_con'] . '');
                                        
                                        // $PropDetailss->addChild('Unit', ' ' . $d[0]['area'] . ' ');
                                        //echo $PropDetails->asXML();
                                    }
                                }
//-----------------------party 1 and 2-----------------------------------------------------------------------------------
                                $detailsparty = $this->genernalinfoentry->query("select party.token_no,party.party_type_id,party.party_full_name_en,party.father_full_name_en,ptype.party_type_desc_en,party.address_en,party.mobile_no,party.email_id,gender.gender_desc_en,party.uid,party.maincast_id,party.cast_id
 from ngdrstab_trn_party_entry_new as party
JOIN ngdrstab_mst_party_type ptype
          ON ptype.party_type_id = party.party_type_id  
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = party.gender_id
           JOIN ngdrstab_trn_property_details_entry AS prop
         ON prop.token_no = party.token_no
        WHERE  party.token_no=$token   order by party_type_flag ASC");

                                if (!empty($detailsparty)) {
                                    $partyDetails = $RegDetails->addChild('party');
                                    foreach ($detailsparty as $d) {
                                        $partyDetailss = $partyDetails->addChild('prt');
                                        $partyDetailss->addChild('Type', '' . $d[0]['party_type_desc_en']);
                                        $partyDetailss->addChild('Name', '' . $d[0]['party_full_name_en']);
                                        $partyDetailss->addChild('Father_Name', '' . $d[0]['father_full_name_en']);
                                        $partyDetailss->addChild('Relation_Code', ''.'NA' );
                                        $partyDetailss->addChild('Present_Address', '' . $d[0]['address_en']);
                                        $partyDetailss->addChild('Permanent_Address', '' . $d[0]['address_en']);
                                        $partyDetailss->addChild('Mobile', '' . $d[0]['mobile_no']);
                                        if($d[0]['email_id'])
                                            $partyDetailss->addChild('Email', '' . $d[0]['email_id']);
                                        else
                                            $partyDetailss->addChild('Email', '' . 'NA');
                                        if($d[0]['cast_id'])
                                            $partyDetailss->addChild('Caste_Code', ''  . $d[0]['cast_id']);
                                        else
                                            $partyDetailss->addChild('Caste_Code', ''  . 'NA');
                                        $partyDetailss->addChild('Gender_Code', '' . $d[0]['gender_desc_en']);
                                        $partyDetailss->addChild('Aadhaar', '' . $this->dec($d[0]['uid']));
                                    }

                                    //---------------------identifier details------------------------------
                                    $detailsidentification = $this->genernalinfoentry->query("select itype.desc_en,identification.identification_full_name_en,identification.father_full_name_en,
 identification.address_en,
 identification.mobile_no,
 identification.email_id,gender.gender_desc_en,
 identification.uid_no
 from ngdrstab_trn_identification as identification
JOIN ngdrstab_mst_identifier_type itype
          ON itype.type_id = identification.identifire_type
          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = identification.gender_id
        WHERE  identification.token_no=$token");
//                pr($detailsparty);
//                exit;
                                    if (!empty($detailsidentification)) {
                                        //$identificationDetails = $RegDetails->addChild('Identifier');
                                        foreach ($detailsidentification as $d) {
                                            $identificationDetailss = $partyDetails->addChild('prt');
                                            $identificationDetailss->addChild('Type', 'Identifier');
                                            $identificationDetailss->addChild('Name', '' . $d[0]['identification_full_name_en']);
                                            $identificationDetailss->addChild('Father_Name', '' . $d[0]['father_full_name_en']);                                
                                            $identificationDetailss->addChild('Present_Address', '' . $d[0]['address_en']);
                                            $identificationDetailss->addChild('Permanent_Address', '' . $d[0]['address_en']);
                                            $identificationDetailss->addChild('Mobile', '' . $d[0]['mobile_no']);
                                            if($d[0]['email_id'])
                                                $identificationDetailss->addChild('Email', '' . $d[0]['email_id']);
                                            else
                                                $identificationDetailss->addChild('Email', '' . 'NA');
                                            $identificationDetailss->addChild('Gender_Code', '' . $d[0]['gender_desc_en']);
                                            $identificationDetailss->addChild('Aadhaar', '' . $this->dec($d[0]['uid_no']));
                                        }
                                    }

                                    //---------------------witness details------------------------------
                                    $detailswitness = $this->genernalinfoentry->query(" select witness.witness_full_name_en,witness.father_full_name_en,
 witness.address_en,
 witness.mobile_no,
 witness.email_id,gender.gender_desc_en,
 witness.uid_no
 from ngdrstab_trn_witness as witness

          JOIN ngdrstab_mst_gender gender
          ON gender.gender_id = witness.gender_id
        WHERE  witness.token_no=$token");
//                pr($detailsparty);
//                exit;
                                    if (!empty($detailswitness)) {
                                        // $detailswitnes1 = $RegDetails->addChild('Witness');
                                        foreach ($detailswitness as $d) {
                                            //  pr($d);exit;
                                            $detailswitnes = $partyDetails->addChild('prt');
                                            $detailswitnes->addChild('Type', 'Witness');
                                            $detailswitnes->addChild('Name', '' . $d[0]['witness_full_name_en']);
                                            $detailswitnes->addChild('Father_Name', '' . $d[0]['father_full_name_en']);                                           
                                            $detailswitnes->addChild('Present_Address', '' . $d[0]['address_en']);
                                            $detailswitnes->addChild('Permanent_Address', '' . $d[0]['address_en']);
                                            $detailswitnes->addChild('Mobile', '' . $d[0]['mobile_no']);
                                            if($d[0]['email_id'])
                                                $detailswitnes->addChild('Email', '' . $d[0]['email_id']);
                                            else 
                                                $detailswitnes->addChild('Email', '' . 'NA');
                                            $detailswitnes->addChild('Gender_Code', '' . $d[0]['gender_desc_en']);
                                            $detailswitnes->addChild('Aadhaar', '' . $this->dec($d[0]['uid_no']));
                                        }
                                    }
                                }
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
        } catch (Exception $ex) {
            $response['ERROR_CODE'] = '6';
            $response['ERROR_MSG'] = $ex->getMessage();
        }

        if (isset($response['ERROR_CODE'])) {
            $newsXML->addChild("ERROR_CODE", $response['ERROR_CODE']);
            $newsXML->addChild("ERROR_DESC", @$errorcodes[$response['ERROR_CODE']] . " - " . @$response['ERROR_MSG']);
        }
        ob_clean();
        Header('Content-type: text/xml');
        echo $newsXML->asXML();
        exit;
    }

    public function mutation_manually() {
        $this->loadModel('District');
        $stateid = $this->Auth->User("state_id");
        $lang = $this->Session->read("sess_langauge");
        $District = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => 'district_name_' . $lang));

        if ($this->request->is('post')) {
            $some_data = $this->request->data;
//            pr($some_data);exit;
            $some_data['api_password'] = hash("sha256", $some_data['api_password']);

            $curl = curl_init();
//            pr($curl);exit;
            // You can also set the URL you want to communicate with by doing this:
            // $curl = curl_init('http://localhost/echoservice');
            // We POST the data
            curl_setopt($curl, CURLOPT_POST, 1);
            // Set the url path we want to call
            curl_setopt($curl, CURLOPT_URL, 'http://localhost/NGDRS_GA/JHWebService/mutationws');
//            pr($curl);exit;
            // Make it so the data coming back is put into a string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            // Insert the data
            curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

            // You can also bunch the above commands into an array if you choose using: curl_setopt_array
            // Send the request
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);

            curl_close($curl);

//             pr($info);exit;
            pr($result);
            exit;
            $this->autoRender = FALSE;
            $this->response->type('text/xml');
            echo trim($result);
            exit;
        }

        $this->set("District", $District);
    }

    public function mutation_manually_old() {
        $this->loadModel('District');
        $stateid = $this->Auth->User("state_id");
        $lang = $this->Session->read("sess_langauge");
        $District = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => 'district_name_' . $lang));

        if ($this->request->is('post')) {
            $some_data = $this->request->data;
            pr($some_data);
            exit;
            $some_data['api_password'] = hash("sha256", $some_data['api_password']);

            $curl = curl_init();
//            pr($curl);exit;
            // You can also set the URL you want to communicate with by doing this:
            // $curl = curl_init('http://localhost/echoservice');
            // We POST the data
            curl_setopt($curl, CURLOPT_POST, 1);
            // Set the url path we want to call
            curl_setopt($curl, CURLOPT_URL, 'http://localhost/NGDRS_GA/JHWebService/mutationws');
//            pr($curl);exit;
            // Make it so the data coming back is put into a string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            // Insert the data
            curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

            // You can also bunch the above commands into an array if you choose using: curl_setopt_array
            // Send the request
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);

            curl_close($curl);

//             pr($info);exit;
            pr($result);
            exit;
            $this->autoRender = FALSE;
            $this->response->type('text/xml');
            echo trim($result);
            exit;
        }

        $this->set("District", $District);
    }
    public function ngdrsjhapi2() {
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
            '4' => 'The maximum record limit is exceeded. Please add more conditions',
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

//District code
//2. circle code
//3. halka  code
//4. Mauja code
//5. volume number
//6. Page Number

            if ($this->request->is('post')) {
                $API_CODE = 'API00003';
                $fieldlist['api_code']['text'] = 'is_alphanumeric';
                $fieldlist['api_username']['text'] = 'is_username';
                $fieldlist['api_password']['text'] = 'is_alphanumeric';

                $fieldlist['district_code_ll']['text'] = 'is_numeric';
                $fieldlist['taluka_code_ll']['text'] = 'is_numeric';
                $fieldlist['village_code']['text'] = 'is_numeric';
                $fieldlist['halka_code']['text'] = 'is_numeric';

                $fieldlist['volume_number']['text'] = 'is_numeric';
                $fieldlist['page_number']['text'] = 'is_numeric';

                $data = $_REQUEST;
                $data['api_code'] = $API_CODE;
                
                $verrors = $this->validatedata($data, $fieldlist);
                //pr(implode(',', $verrors));exit;
                if ($this->ValidationError($verrors)) {

                    if ($this->ApiCredentials->authenticate($data)) {

                        $sql = "  SELECT app.token_no 
FROM   ngdrstab_trn_application_submitted AS app 
       JOIN ngdrstab_trn_property_details_entry AS prop 
         ON prop.token_no = app.token_no ";
                        if (isset($data['district_code_ll']) && !empty($data['district_code_ll'])) {
                            $sql.= "  JOIN ngdrstab_conf_admblock3_district AS district 
         ON district.district_id = prop.district_id 
            AND district.district_code_ll   = '" . $data['district_code_ll'] . "' ";
                        }
                        if (isset($data['taluka_code_ll']) && !empty($data['taluka_code_ll'])) {
                            $sql.= "  JOIN ngdrstab_conf_admblock5_taluka AS taluka 
         ON taluka.taluka_id = prop.taluka_id 
            AND taluka.taluka_code_ll   = '" . $data['taluka_code_ll'] . "' ";
                        }

                        if (isset($data['village_code']) && !empty($data['village_code'])) {
                            $sql.= "  JOIN ngdrstab_conf_admblock7_village_mapping AS village
ON village.village_id = prop.village_id
AND village.state_spacific_code   = '" . $data['village_code'] . "' ";
                        }

                        if (isset($data['volume_number']) && !empty($data['volume_number'])) {
                            $sql.= "  JOIN ngdrstab_trn_parameter AS trnparam
ON trnparam.property_id = prop.property_id
AND trnparam.paramter_id = 207
AND trnparam.paramter_value = '" . $data['volume_number'] . "'
AND parameter_type = 'S'
JOIN ngdrstab_mst_attribute_parameter AS mstparam
ON mstparam.attribute_id = trnparam.paramter_id ";
                        }
                        if (isset($data['page_number']) && !empty($data['page_number'])) {
                            $sql.= "  JOIN ngdrstab_trn_parameter AS trnparam1
ON trnparam1.property_id = prop.property_id
AND trnparam1.paramter_id = 209
AND trnparam1.paramter_value = '" . $data['page_number'] . "'
AND trnparam1.parameter_type = 'S'
JOIN ngdrstab_mst_attribute_parameter AS mstparam1
ON mstparam1.attribute_id = trnparam1.paramter_id ";
                        }


                        $sql.= " WHERE final_stamp_flag = 'Y' ";


                        $sql.= " GROUP BY app.token_no ";

//pr($sql);exit;

                        $results = $this->ApplicationSubmitted->query("SELECT info.token_no,
 article.article_desc_en,
 article.article_id,
 app.final_stamp_date,
 app.final_doc_reg_no,
 info.presentation_date,
 String_agg( (
SELECT  party_full_name_en
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '0'

WHERE party.token_no = info.token_no and is_presenter = 'Y'
), ',') as party2 ,
 String_agg( (
SELECT  party_full_name_en
FROM ngdrstab_trn_party_entry_new AS party
JOIN ngdrstab_mst_party_type ptype
ON ptype.party_type_id = party.party_type_id
AND party_type_flag = '1'

WHERE party.token_no = info.token_no and is_presenter = 'Y'
), ',') as party1 
FROM ngdrstab_trn_application_submitted AS app
JOIN ngdrstab_trn_generalinformation AS info
ON info.token_no = app.token_no and info.office_id = app.office_id
JOIN ngdrstab_mst_article AS article
ON article.article_id = info.article_id
WHERE app.token_no IN($sql)  GROUP BY info.token_no, article.article_desc_en,
 article.article_id,
 app.final_stamp_date,
  info.presentation_date,
 app.final_doc_reg_no 
");

//pr($results);exit;

                        if (!empty($results)) {

                            foreach ($results as $tokenkey => $result) {
                                $nodetoken = $newsXML->addChild('Token');
                                $nodetoken->addAttribute('TOKEN', $result[0]['token_no']);
                                $nodetoken->addChild('DOP', $result[0]['presentation_date']);
                                $nodetoken->addChild('DOE', $result[0]['final_stamp_date']);
                                $nodetoken->addChild('DeedNumber', $result[0]['final_doc_reg_no']);
                                $nodetoken->addChild('VendeeName', $result[0]['party1']);
                                $nodetoken->addChild('VendorName', $result[0]['party2']);
                            }/// token
                        } else {
                            $response['ERROR_CODE'] = '3';
                        }
                    } else {
                        $response['ERROR_CODE'] = '2';
                    }
                } else {
                    $response['ERROR_CODE'] = '1';
                     $response['ERROR_MSG'] = implode(',', $verrors);
                }
            } else {
                $response['ERROR_CODE'] = '5';
            }
        } catch (Exception $exc) {
            pr($exc);exit;
            $response['ERROR_CODE'] = '6';
            // $response['ERROR_DESC'] = $exc->getMessage();
        }

        if (isset($response['ERROR_CODE'])) {
            $newsXML->addChild("ERROR_CODE", $response['ERROR_CODE']);
           $newsXML->addChild("ERROR_DESC", @$errorcodes[$response['ERROR_CODE']]);
           $newsXML->addChild("ERROR_MSG", @$response['ERROR_MSG']);
        }

        $this->response->type('text/xml');
        echo $newsXML->asXML();
    }


}
