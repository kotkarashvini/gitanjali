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
class MHWebServiceController extends AppController {

    //put your code here
    public function gras_payment_defacement($payment = NULL, $extrafields = NULL) {
        array_map(array($this, 'loadModel'), array('OnlinePayment', 'payment', 'PaymentFields', 'external_interface', 'extinterfacefielddetails', 'office'));
        $defacement_type = 'F';
        $response['Error'] = '';
        if (empty($payment)) {
            $response['Error'] = 'Empty Payment Record!';
            return $response;
        }
        if (!isset($extrafields['remark'])) {
            $response['Error'] = 'Provide remark As Parameter!(ex. doc number)';
            return $response;
        }
        $user_id = 'NA';
        if (isset($extrafields['user_id'])) {
            $user_id = $extrafields['user_id'];
        } else if (isset($extrafields['org_user_id'])) {
            $user_id = $extrafields['org_user_id'];
        }

        $remark = $extrafields['remark'];
        if (!is_numeric($user_id)) {
            $response['Error'] = 'Please Provide User Id ';
            return $response;
        }

        $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 3)));
        $office = $this->office->find("first", array('conditions' => array('office_id' => $this->Auth->user('office_id'))));
        // pr($office);exit;
        if (empty($office)) {
            $response['Error'] = 'Office Details Not Found';
            return $response;
        }
        if (empty($bankapi)) {
            $response['Error'] = 'Bank Api Not Found!';
            return $response;
        }
        $bankapi = $bankapi['external_interface'];
        $url = $bankapi['interface_url'];
        $paymode = $payment['payment_mode_id'];

        $onlinepayment = $this->OnlinePayment->find("first", array('conditions' => array('grn_no' => $payment['grn_no'])));
        if (empty($onlinepayment)) {
            $response['Error'] = 'GRN Not Found';
            return $response;
        }
        $onlinepayment = $onlinepayment['OnlinePayment'];

        $fields = array(
            'GRN' => urlencode($payment['grn_no']),
            'AMOUNT' => urlencode($onlinepayment['pamount']),
            'OFFICECODE' => urlencode($office['office']['gras_office_code']),
            'REMARK' => urlencode($remark),
            'VERIFICATIONNO' => urlencode($payment['verification_number']),
            'USERID' => urlencode($user_id),
        );
        if ($defacement_type == 'F') {
            $fields['ACTIONCODE'] = 'GETFULLCANCELCHALLAN';
        } else {
            $fields['ACTIONCODE'] = 'GETPARTIALCANCEL';
            $fields['PARAM'] = $onlinepayment['gras_account_details']; //'S#0030046401##02#Stamp Duty#200.00#0030063301##01#Registration Fee#1000.00';
            $fields['CANCELLATIONAMOUNT'] = urlencode($payment['pamount']);
        }

        $fields_string = '';
        $i = 1;
        foreach ($fields as $key => $value) {
            if (count($fields) > $i) {
                $fields_string .= $key . '=' . $value . '&';
            } else {
                $fields_string .= $key . '=' . $value;
            }
            $i++;
        }

        $fields_string = trim($fields_string, '&');
        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($fields_string))
        );
        curl_setopt($ch1, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
        $service_result = curl_exec($ch1);
// defaced string 
//$service_result = '$DEFACEMENTNO$0000005063201617$DEFACEMENTTIME$$GRN$MH000401642201617E$';
// defaced error string 
// $service_result='$ERROR$ AMOUNT EXCEEDING THE LIMIT OF DEFACING THE AMOUNT OF CHALLAN.$' ;
// pull deface  
// $service_result = '$DEFACEMENTNO$0000005094201617$DEFACEMENTTIME$2017-03-07 14:28:02.732111$GRN$MH000401642201617E$';
        if (empty($service_result)) {
            $response = array('Error' => 'Empty Service  Response');
            return $response;
        }
// create array  of responce
        $service_result_array = explode('$', $service_result);
        $reskey = NULL;
        foreach ($service_result_array as $key => $array_val) {
            if ($key != 0) {
                if ($key % 2 != 0) {
                    $reskey = $array_val;
                } else {
                    $response_array[$reskey] = $array_val;
                }
            }
        }

        if (empty($response_array)) {
            $response['Error'] = 'GRN : ' . $payment['grn_no'] . " ] " . 'Wrong Service Responce';
            return $response;
        }
        $check_error = NULL;
        if (isset($response_array['ERROR'])) {
            $check_error = $response_array['ERROR'];
            $check_error = trim($check_error);
        }

        if (strlen($check_error) > 1) {  // error occured   
            $response['Error'] = 'GRN : ' . $payment['grn_no'] . " ] " . $check_error;
            return $response;
        }


        $update_data['defacement_flag'] = "'" . 'Y' . "'";
        $update_data['defacement_number'] = $response_array['DEFACEMENTNO'];
        $update_data['defacement_time'] = "'" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $response_array['DEFACEMENTTIME']))) . "'"; //$response_array['DEFACEMENTTIME'] .

        if ($defacement_type == 'F') {
            $conditions['grn_no'] = $payment['grn_no'];
        } else {
            $conditions['payment_id'] = $payment['payment_id'];
        }

        $response['PaymentData'] = $update_data;
        $response['Condition'] = $conditions;
        return $response;
    }

    public function gras_payment_verification($data = NULL, $extrafields = NULL) {
        $this->loadModel('GrasVerification');
        $this->loadModel('OnlinePayment');
        $this->loadModel('payment');
        $this->loadModel('external_interface');
        $this->loadModel('office');



        if ($data == NULL) {
            $response['Error'] = 'Request Data cant be null!';
            return $response;
        }

        if (!isset($extrafields['user_id'])) {
            if (!isset($extrafields['org_user_id'])) {
                $response['Error'] = 'User Id cant be null!';
                return $response;
            } else {
                $userid = $extrafields['org_user_id'];
            }
        } else {
            $userid = $extrafields['user_id'];
        }
        if (!isset($extrafields['token_no'])) {
            $token = $extrafields['token_no'];
        } else {
            $token = '';
        }

        $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 2)));
        $office = $this->office->find("first", array('conditions' => array('office_id' => $this->Auth->user('office_id'))));
        // pr($office);exit;
        if (empty($office)) {
            $response['Error'] = 'Office Details Not Found';
            return $response;
        }
        if (empty($bankapi)) {
            $response['Error'] = 'Bank Api Not Found For GRAS Verification';
            return $response;
        }
        $bankapi = $bankapi['external_interface'];
        $url = $bankapi['interface_url'];

        $fields = array(
            'GRN' => urlencode($data['grn_no']),
            'AMOUNT' => urlencode($data['pamount']),
            'OFFICECODE' => $office['office']['gras_office_code'],
            'VIEWCHALLAN' => NULL,
            'USERID' => urlencode($userid),
        );
        $fields_string = '';

//build Query String
        $i = 1;
        foreach ($fields as $key => $value) {
            if (count($fields) > $i) {
                $fields_string .= $key . '=' . $value . '&';
            } else {
                $fields_string .= $key . '=' . $value;
            }
            $i++;
        }

//pr($fields_string);exit;
        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($fields_string))
        );
        curl_setopt($ch1, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
        $service_result = curl_exec($ch1);
// pr($service_result);exit;
        curl_close($ch1);
// with error string  
// $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$IP MAPPING WITH GRAS NOT PRESENT 10.153.8.105 FOR OFFICE IGR039$$';
// verified  
// $service_result = '$GRN$MH000401642201617E$ENTRYDATE$06/03/2017$AMOUNT$1300.00$CIN$12345678910$PARTYNAME$Shrishail Gobbi$VERIFICATIONNUMBER$0000008585201617$DEFACEFLAG$$DEFACEMENTNO$$REFUNDNO$$RBIDATE$$ACCOUNTDETAILS$S#0030046401##02#Stamp Duty#300.00#0030063301##01#Registration Fee#1000.00$STATIONARYNO$$ERROR$-$';
// build array of responce   
        if (!empty($service_result)) {
            $service_result_array = (explode("$", $service_result));
// pr($service_result_array);
//exit;
            $response_array = array();
            $reskey = NULL;
            $errorflag = 0;
            foreach ($service_result_array as $key => $array_val) {

                if ($array_val == 'ERROR') {
                    $errorflag = 1;
                    $response_array['ERROR'] = '';
                } elseif ($errorflag == 1) {
                    $response_array['ERROR'] = $response_array['ERROR'] . " | " . $array_val;
                } elseif ($key != 0 && !empty($array_val)) {
                    if ($key % 2 != 0) {
                        $reskey = $array_val;
                    } else {
                        $response_array[$reskey] = $array_val;
                    }
                }
            }
// pr($response_array);exit;
            if (empty($response_array)) {
                $response['Error'] = 'Wrong Service Responce';
                return $response;
            }
            $check_error = $response_array['ERROR'];
            $check_error = trim($check_error);

            if (strlen($check_error) > 5) {  // error occured   
                $response['Error'] = $check_error;
                return $response;
            }
//Seperation of payment Details 

            $account_result_array = (explode("#", $response_array['ACCOUNTDETAILS']));
            $account_array = array();


            $counter = 0;

            foreach ($account_result_array as $key => $array_val) {
                if ($key != 0 && $counter == 1) {
                    $reskey = $array_val;
                } else if ($key != 0 && $counter == 5) {
                    $account_array[$reskey] = $array_val;
                    $counter = 0;
                }
                $counter++;
            }
// Update Online Payment Received
            // $online_data = $this->add_default_fields();
            $online_data['payment_mode_id'] = $data['payment_mode_id'];
            $online_data['payee_fname_en'] = $response_array['PARTYNAME'];
            $online_data['grn_no'] = $response_array['GRN'];
            $online_data['cin_no'] = $response_array['CIN'];
            $online_data['verification_number'] = $response_array['VERIFICATIONNUMBER'];
            $online_data['gras_account_details'] = $response_array['ACCOUNTDETAILS'];
            $online_data['pamount'] = $response_array['AMOUNT'];
            $entrydate = $response_array['ENTRYDATE'];
            $entrydate_arr = explode("/", $entrydate);  //   06/03/2017
            $online_data['pdate'] = $entrydate_arr[2] . "-" . $entrydate_arr[1] . "-" . $entrydate_arr[0];
// Update Payment Entry Table
            $payment_entry = $online_data;
            $payment_entry_all = array();
            foreach ($account_array as $keyval => $array_val) {
                $payment_entry['payment_mode_id'] = $data['payment_mode_id'];
                $payment_entry['account_head_code'] = $keyval;
                $payment_entry['pamount'] = $array_val;
                $payment_entry['token_no'] = $token;
                $payment_entry['online_verified_flag'] = 'Y';
                if (!is_null($extrafields)) {
                    $payment_entry = array_merge($payment_entry, $extrafields);
                }
                array_push($payment_entry_all, $payment_entry);
            }
            $checkexist = $this->OnlinePayment->find("all", array('conditions' => array('grn_no' => $online_data['grn_no'])));
            if (!empty($checkexist)) {
                $response['Error'] = 'Already Verified Record Exist';
                return $response;
            }
            $response['PaymentData'] = $payment_entry_all;
            $response['OnlinePaymentData'] = $online_data;
            if (!is_null($extrafields)) {
                $response['OnlinePaymentData'] = array_merge($response['OnlinePaymentData'], $extrafields);
            }
            return $response;
        } else {
            $response['Error'] = 'Empty service Response';
            return $response;
        }
    }

    public function gras_payment_receipt($payment = NULL, $extrafields = NULL) {
        $this->loadModel('GrasVerification');
        $this->loadModel('OnlinePayment');
        $this->loadModel('payment');
        $this->loadModel('external_interface');
        $this->loadModel('office');



        if ($payment == NULL) {
            $response['Error'] = 'Request Data cant be null!';
            return $response;
        }

        if (!isset($extrafields['user_id'])) {
            if (!isset($extrafields['org_user_id'])) {
                $response['Error'] = 'User Id cant be null!';
                return $response;
            } else {
                $userid = $extrafields['org_user_id'];
            }
        } else {
            $userid = $extrafields['user_id'];
        }
        if (!isset($extrafields['token_no'])) {
            $token = $extrafields['token_no'];
        } else {
            $token = '';
        }
        $onlinepayment = $this->OnlinePayment->find("first", array('conditions' => array('grn_no' => $payment['grn_no'])));
        if (empty($onlinepayment)) {
            $response['Error'] = 'GRN Not Found';
            return $response;
        }
        $onlinepayment = $onlinepayment['OnlinePayment'];
        $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 2)));
        $office = $this->office->find("first", array('conditions' => array('office_id' => $this->Auth->user('office_id'))));

        // pr($office);exit;
        if (empty($office)) {
            $response['Error'] = 'Office Details Not Found';
            return $response;
        }
        if (empty($bankapi)) {
            $response['Error'] = 'Bank Api Not Found For GRAS Verification';
            return $response;
        }
        $bankapi = $bankapi['external_interface'];
        $url = $bankapi['interface_url'];

        $fields = array(
            'GRN' => urlencode($payment['grn_no']),
            'AMOUNT' => urlencode($onlinepayment['pamount']),
            'OFFICECODE' => $office['office']['gras_office_code'],
            'VIEWCHALLAN' => 'Y',
            'USERID' => urlencode($userid),
        );
        $fields_string = '';

//build Query String
        $i = 1;
        foreach ($fields as $key => $value) {
            if (count($fields) > $i) {
                $fields_string .= $key . '=' . $value . '&';
            } else {
                $fields_string .= $key . '=' . $value;
            }
            $i++;
        }
        // pr($fields_string);

        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($fields_string))
        );
        curl_setopt($ch1, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);
        $service_result = curl_exec($ch1);
        curl_close($ch1);

        if (!empty($service_result)) {
            $checkpdf = substr($service_result, 0, 5);
            if (strcmp($checkpdf, '%PDF-') == 0) {
                $name = 'grn_' . $payment['grn_no'] . '.pdf';
                header('Content-Type: application/pdf');
                header('Content-Length: ' . strlen($service_result));
                header('Content-disposition: inline; filename="' . $name . '"');
                header('Cache-Control: public, must-revalidate, max-age=0');
                header('Pragma: public');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                echo $service_result;
                exit;
            } else {
                $service_result_array = (explode("$", $service_result));
// pr($service_result_array);
//exit;
                $response_array = array();
                $reskey = NULL;
                $errorflag = 0;
                foreach ($service_result_array as $key => $array_val) {

                    if ($array_val == 'ERROR') {
                        $errorflag = 1;
                        $response_array['ERROR'] = '';
                    } elseif ($errorflag == 1) {
                        $response_array['ERROR'] = $response_array['ERROR'] . " | " . $array_val;
                    } elseif ($key != 0 && !empty($array_val)) {
                        if ($key % 2 != 0) {
                            $reskey = $array_val;
                        } else {
                            $response_array[$reskey] = $array_val;
                        }
                    }
                }
// pr($response_array);exit;
                if (empty($response_array)) {
                    $response['Error'] = 'Wrong Service Responce';
                    return $response;
                }
                $check_error = $response_array['ERROR'];
                $check_error = trim($check_error);

                if (strlen($check_error) > 5) {  // error occured   
                    $response['Error'] = $check_error;
                    return $response;
                }
            }
        } else {
            $response['Error'] = 'Empty service Response';
            return $response;
        }
    }

}
