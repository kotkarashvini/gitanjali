<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PBWebServiceController extends AppController {

    //put your code here
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

            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(2, 45), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
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
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 6)));
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

            $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' ' . $certdate_new, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Payment gateway not working.';
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
                    $response['Error'] = 'Invalid Or Locked Certificate Found!';
                    return $response;
                }


                $PaymentData = array();
                $onlinedata = array();
                if (isset($result['CertificatesDetails']['BaseCertificateNo']) &&  isset($result['CertificatesDetails']['CertificateNo'])) {
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
                            if (isset($result['CertificatesDetails']['BaseCertificateNo']) &&  isset($result['CertificatesDetails']['CertificateNo'])) {
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
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 6)));
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
            $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_url'] . ' ' . $bankapi['proxy_server_ip'] . ' ' . $bankapi['proxy_server_port'] . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $payment['certificate_no'] . ' ' . $certdate_new . ' ' . $user_id . ' ' . $regno, $result);

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

    public function eregistration_certificate_verification($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment']);
        if ($data != NULL) {
            $response['Error'] = '';
            $certid = trim(@$data['certificate_no']); // 'IN-PB00100028749518M';   
            if (!isset($extrafields['token_no']) || !isset($extrafields['article_id']) || !isset($extrafields['lang'])) {
                $response['Error'] = 'Please check token number ,article id , lang provided as extra fields';
                return $response;
            }
            if (empty($certid)) {
                $response['Error'] = 'Certificate Number Not Found!';
                return $response;
            }
            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 46, 47, 48, 49, 50), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($acchead)) {
                $response['Error'] = 'SD Account Head Code Not Found';
                return $response;
            }

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid)));
            if (!empty($onlinepay)) {
                $response['Error'] = 'Certificate Already Exist In Verified List';
                return $response;
            }
            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 8)));
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

            $jarpath = $basepath . "jar_files/eregistrationverify.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Jar File Not Found!';
                return $response;
            }

            $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid, $result);
            //pr($result);exit;
            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }
            if (count($result) > 1) {
                $response['Error'] = $result[0] . " " . $result[1];
                return $response;
            }
            $xmlstr = $result[0];

            $xml = simplexml_load_string($xmlstr, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $result = json_decode($json, TRUE);

            if (strcmp(trim($result['TXNDTLS']['REQSTATUS']['RPSTATUS']), "FAIL") == 0) {
                $response['Error'] = 'Invalid Certificate Found!';
                return $response;
            }
            $PaymentData = array();
            $onlinedata = array();
            $originalDate = $result['TXNDTLS']['RDTLS']['GENDATE'];
            $newDate = date("Y-m-d H:i:s", strtotime($originalDate));
            $pastingfee = $result['TXNDTLS']['RDTLS']['PASTING'];
            foreach ($acchead as $itemid => $headcode) {
                foreach ($accounthead as $key => $single) {
                    if (strcmp(trim($headcode), $single[0]['account_head_code']) == 0) {
                        $insertdata = array();
                        $insertdata['account_head_code'] = $headcode;
                        $insertdata['pdate'] = $newDate;
                        $insertdata['online_verified_flag'] = 'Y';
                        $insertdata['payee_fname_en'] = $result['TXNDTLS']['RDTLS']['PAIDBY'];
                        switch ($itemid) {
                            case 1: $insertdata['pamount'] = $result['TXNDTLS']['RDTLS']['REGFEE'];
                                break;
                            case 46:$insertdata['pamount'] = $result['TXNDTLS']['RDTLS']['INFRA']; // PIDB
                                break;
                            case 47: $insertdata['pamount'] = $result['TXNDTLS']['RDTLS']['PLRS'];
                                break;
                            case 48: $insertdata['pamount'] = $result['TXNDTLS']['RDTLS']['MUTATION'];
                                break;
                            case 49: if ($pastingfee >= $single[0]['totalsd']) {
                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                    $pastingfee = $pastingfee - $single[0]['totalsd'];
                                } else {
                                    $insertdata['pamount'] = $pastingfee;
                                    $pastingfee = 0;
                                }
                                break;
                            case 50: if ($pastingfee >= $single[0]['totalsd']) {
                                    $insertdata['pamount'] = $single[0]['totalsd'];
                                    $pastingfee = $pastingfee - $single[0]['totalsd'];
                                } else {
                                    $insertdata['pamount'] = $pastingfee;
                                    $pastingfee = 0;
                                }
                                break;
                        }
                        $insertdata = array_merge($insertdata, $extrafields);
                        $insertdata = array_merge($insertdata, $data);
                        if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                            array_push($PaymentData, $insertdata);
                        }
                    }
                }
            }
            $onlinedata['pdate'] = $newDate;
            $onlinedata['payee_fname_en'] = $result['TXNDTLS']['RDTLS']['PAIDBY'];
            $onlinedata['pamount'] = $result['TXNDTLS']['RDTLS']['TOTFEE'];
            $response['PaymentData'] = $PaymentData;
            $response['OnlinePaymentData'] = $onlinedata;

            if (!is_null($extrafields)) {
                $response['OnlinePaymentData'] = array_merge($response['OnlinePaymentData'], $extrafields);
                $response['OnlinePaymentData'] = array_merge($response['OnlinePaymentData'], $data);
            }
            return $response;
        }
    }

    public function eregistration_certificate_lock($payment = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment']);
        if ($payment != NULL) {
            $response['Error'] = '';
            $certid = @$payment['certificate_no']; // 'IN-PB00100028749518M'; 
            if (!isset($extrafields['token_no'])) {
                $response['Error'] = 'Please check token number  provided as extra fields';
                return $response;
            }
            if (empty($certid)) {
                $response['Error'] = 'Certificate Number Not Found!';
                return $response;
            }
            $token = $extrafields['token_no'];

            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('certificate_no' => $certid)));
            if (empty($onlinepay)) {
                $response['Error'] = 'Certificate Not In Verified List';
                return $response;
            }

            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 8)));
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

            $jarpath = $basepath . "jar_files/eregistrationlock.jar";
            if (!file_exists($jarpath)) {
                $response['Error'] = 'Jar File Not Found!';
                return $response;
            }

//      String username = "pbreglockusr";
//        String password = "RSU501KCOLGERBP";
//        String RcptNo = "PB1711371602416";
//        String State = "PB";
//        String LockBy = "pbreglockusr";
//        String LockRegNo = "1234";
//        String RcptAmt = "7000";
//        String LockUser = "pbreglockusr";
            //   pr('java -jar ' . $jarpath . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid.' PB '.$bankapi['interface_user_id'].' '.$token.' '.$onlinepay['OnlinePayment']['pamount'].' '.$bankapi['interface_user_id']);
            //  exit;
            $lockby = @$this->Auth->User('ws_eregistration_user_id');
            if (is_null($lockby)) {
                $response['Error'] = 'Please provide e-registration User Id for login User';
                return $response;
            }
            $jarmessage = exec('java -jar ' . $jarpath . ' ' . $bankapi['interface_user_id'] . ' ' . $bankapi['interface_password'] . ' ' . $certid . ' PB ' . $lockby . ' ' . $token . ' ' . $onlinepay['OnlinePayment']['pamount'] . ' ' . $lockby, $result);

            if (empty($result) || !is_array($result)) {
                $response['Error'] = 'Empty Service Response!';
                return $response;
            }
            if (count($result) > 1) {
                $response['Error'] = $result[0] . " " . $result[1];
                return $response;
            }
            $xmlstr = $result[0];

//           $xmlstr="<RSWTXN>
//<TXNHDR>
//<MSGVER>RSSHCIL001</MSGVER>
//<MSGTP>LOCKRP</MSGTP>
//<SENDTM>2017110914:30:19</SENDTM>
//</TXNHDR>
//<LOCKRPDTL>
//<STATE>PB</STATE>
//<CERTNO>PB1711371602416</CERTNO>
//<STATUS>LOCKED</STATUS>
//<RPSTATUS>SUCCESS</RPSTATUS>
//</LOCKRPDTL>
//</RSWTXN>";
            $xml = simplexml_load_string($xmlstr, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $result = json_decode($json, TRUE);

            if (strcmp(trim($result['LOCKRPDTL']['RPSTATUS']), "FAIL") == 0) {
                $response['Error'] = 'Invalid Certificate Found!';
                return $response;
            }
            if (strcmp(trim($result['LOCKRPDTL']['STATUS']), "LOCKED") != 0) {
                $response['Error'] = "Status -" . $result['LOCKRPDTL']['STATUS'];
                return $response;
            }
            $lockdate = date('Y-m-d H:i:s');
            $response['PaymentData']['defacement_flag'] = "'" . 'Y' . "'";
            $response['PaymentData']['certificate_lock_date'] = "'" . $lockdate . "'";
            $response['PaymentData']['defacement_time'] = "'" . $lockdate . "'";

            $response['Condition']['token_no'] = $extrafields['token_no'];
            $response['Condition']['certificate_no'] = $result['LOCKRPDTL']['CERTNO'];
            $response['Condition']['payment_mode_id'] = $payment['payment_mode_id'];

            return $response;
        }
    }

    
    public function PayuPayment($data = NULL, $extrafields = NULL) {
        array_map([$this, 'loadModel'], ['external_interface', 'file_config', 'OnlinePayment', 'article_fee_items', 'payment', 'BankPayment']);
        $response['Error'] = '';
        if ($data != NULL) {
            $tranid = trim(@$data['bank_trn_id']); // 'f5666708ed33e039c932'; 
            if (!isset($extrafields['token_no'])) {
                $response['Error'] = 'Please check token number  provided as extra fields';
                return $response;
            }
            if (empty($tranid)) {
                $response['Error'] = 'Transaction  Number Not Found!';
                return $response;
            }
            $token = $extrafields['token_no'];
            $article_id = $extrafields['article_id'];
            $lang = $extrafields['lang'];
            $onlinepay = $this->OnlinePayment->find("first", array('conditions' => array('bank_trn_id' => $tranid, 'payment_mode_id' => $data['payment_mode_id'])));
            if (!empty($onlinepay)) {
                $response['Error'] = 'Transaction Number already exist in verified list';
                return $response;
            }

            $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 11)));
            if (empty($bankapi)) {
                $response['Error'] = 'Bank Api Not Found';
                return $response;
            } else {
                $bankapi = $bankapi['external_interface'];
            }


            // Merchant key here as provided by Payu

            $key = $bankapi['interface_user_id'];
            $salt = $bankapi['interface_password'];
            $command = "verify_payment";
            $var1 = $tranid;
            $hash_str = $key . '|' . $command . '|' . $var1 . '|' . $salt;
            $hash = strtolower(hash('sha512', $hash_str));


            $r = array('key' => $key, 'hash' => $hash, 'var1' => $var1, 'command' => $command);
            $qs = http_build_query($r);
            //pr($bankapi['interface_url']);
            // pr($qs);exit;
            $wsUrl = $bankapi['interface_url'];
            //"https://test.payu.in/merchant/postservice.php?form=1";
            //$wsUrl = "https://info.payu.in/merchant/postservice?form=1";

            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $wsUrl);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $serviceres = curl_exec($c);
            if (curl_errno($c)) {
                $sad = curl_error($c);
                //throw new Exception($sad);
                 $response['Error'] = ''.$sad;
                return $response;
            }
            curl_close($c);

            $valueSerialized = @unserialize($serviceres);
            if ($serviceres === 'b:0;' || $valueSerialized !== false) {
                //  echo 1;
                // print_r($valueSerialized);
                $response['Error'] = 'Payment Gateway Not Responding';
                return $response;
            }
            $mapping = $this->BankPayment->mapping_account_heads(10, 4);
//            $acchead = $this->article_fee_items->find("list", array('fields' => 'fee_item_id,account_head_code', 'conditions' => array('fee_item_id' => array(1, 2, 45, 47, 48, 49, 50), 'account_head_code !=' => NULL), 'order' => 'fee_preference ASC'));
            $accounthead = $this->payment->stampduty_fee_details($token, $lang, $article_id);
            if (empty($mapping)) {
                $response['Error'] = 'SD Account Head Code Mapping Not Found';
                return $response;
            }
            //$myfile = fopen("D:/xyz1.txt", "w");


            $serviceres_arr = json_decode($serviceres, TRUE);
            // pr($serviceres_arr);
            // exit;
            //  pr($serviceres_arr);
            if ($serviceres_arr["status"] == 1) {
                foreach ($serviceres_arr['transaction_details'] as $tranidnew => $singletrans) {
                    if (strtolower($singletrans['status']) == 'success') {

                        $PaymentData = array();
                        $onlinedata = array();
                        // pr($singletrans['addedon']);exit;      
                        $newDate = date("Y-m-d H:i:s", strtotime($singletrans['addedon']));
                        $totalamount = $singletrans['transaction_amount'];

                        $i = 1;
                        $vamt = 0;
                        $famt = 0;

                        foreach ($mapping as $map) {
                            $i++;
                            if (isset($singletrans['udf' . $i]) && !empty($singletrans['udf' . $i])) {
                                $singletrans['udf' . $i] = trim($singletrans['udf' . $i]);
                                $arr = explode('|', $singletrans['udf' . $i]);
                                if (is_array($arr) && is_numeric($arr[0]) && is_numeric($arr[1])) {
                                    $vamt += $arr[1];
                                    foreach ($accounthead as $key => $single) {
                                        if (strcmp(trim($arr[0]), $single[0]['account_head_code']) == 0) {
                                            $famt += $arr[1];
                                            $insertdata = array();
                                            $insertdata['account_head_code'] = $arr[0];
                                            $insertdata['pdate'] = $newDate;
                                            $insertdata['online_verified_flag'] = 'Y';
                                            $insertdata['defacement_flag'] = 'Y';
                                            $insertdata['payee_fname_en'] = $singletrans['firstname'];
                                            $insertdata['bank_trn_id'] = $singletrans['txnid'];
                                            $insertdata['verification_number'] = $singletrans['bank_ref_num'];
                                            $insertdata['pamount'] = $arr[1];
                                            $insertdata = array_merge($insertdata, $extrafields);
                                            $insertdata = array_merge($insertdata, $data);
                                            if (isset($insertdata['pamount']) && $insertdata['pamount'] > 0) {
                                                array_push($PaymentData, $insertdata);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $onlinedata['pdate'] = $newDate;
                        $onlinedata['pamount'] = $singletrans['transaction_amount'];
                        $onlinedata['payee_fname_en'] = $singletrans['firstname'];
                        $onlinedata['bank_trn_id'] = $singletrans['txnid'];
                        $onlinedata['verification_number'] = $singletrans['bank_ref_num'];
                        $onlinedata['certificate_no'] = $singletrans['mihpayid'];
                        $response['PaymentData'] = $PaymentData;
                        $response['OnlinePaymentData'] = $onlinedata;

                        if (!is_null($extrafields)) {
                            $response['OnlinePaymentData'] = array_merge($response['OnlinePaymentData'], $extrafields);
                            $response['OnlinePaymentData'] = array_merge($response['OnlinePaymentData'], $data);
                        }
                        if ($vamt != $singletrans['transaction_amount']) {
                            $response['Error'] = 'Amount Mismatch  Found! (Invalid web service data)';
                        }
                        if ($famt != $singletrans['transaction_amount']) {
                            //$response['Error'] = 'Fee not calculated. Please calculate fee';
                        }
                    } else {
                        $response['Error'] = 'Service Returned Status : ' . @$singletrans['status'];
                    }
                }
            } else if ($serviceres_arr["status"] == 0) {
                $response['Error'] = 'Verification Failed! Service Returned  Status Not Found';
            }
        } else {
            $response['Error'] = 'Inpunt Data Not Found';
        }

        return $response;
    }
                        
}
