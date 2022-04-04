<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author nic
 */
class employee extends AppModel{
    //put your code here
    
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_employee';
     public $primaryKey = 'emp_id';
    var $virtualFields = array(
    'name' => "CONCAT(emp_code, ' - ', emp_fname,' ',emp_mname,' ',emp_lname)"
);
    
       public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_employee';
        $duplicate['PrimaryKey'] = 'emp_id';
        $fields = array();
//        foreach ($languagelist as $language) {
//            array_push($fields, 'office_name_' . $language['mainlanguage']['language_code']);
//        }
        array_push($fields, 'emp_code');
//        array_push($fields, 'census_code');

        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
        
         $fieldlist['emp_code']['text'] = 'is_required,is_alphanumdashslash,is_maxlength20';
            $fieldlist['designation_id']['select'] = 'is_select_req';
            $fieldlist['office_id']['select'] = 'is_select_req';
            $fieldlist['salutation_id']['select'] = 'is_select_req';
            $fieldlist['emp_fname']['text'] = 'is_required,is_alphaspace,is_maxlength20';
            $fieldlist['emp_mname']['text'] = 'is_alphaspace,is_minmaxlength20'; //EMPTY ALLOW
            $fieldlist['emp_lname']['text'] = 'is_required,is_alphaspace,is_maxlength20';
            $fieldlist['qualification_id']['select'] = 'is_select_req';
            $fieldlist['dept_id']['select'] = 'is_select_req';
//            $fieldlist['reporting_officer_email_id']['text'] = 'is_email';
            $fieldlist['building_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets,is_minmaxlength20';
            $fieldlist['flat_no']['text'] = 'is_alphanumdashslash';//'is_positiveinteger';
            $fieldlist['road_name']['text'] = 'is_alphanumspacedashdotslashroundbrackets,is_minmaxlength20';
            $fieldlist['state_id']['select'] = 'is_select_req';
            $fieldlist['dist_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['locality']['text'] = 'is_alphanumspacedashdotcommaroundbrackets,is_minmaxlength20';
            $fieldlist['city']['text'] = 'is_required,is_alphaspace,is_maxlength20';
            $fieldlist['village']['text'] = 'is_required,is_alphaspacedashdotcommacolon,is_maxlength20';
            $fieldlist['pincode']['text'] = 'is_pincode';
            $fieldlist['contact_no']['text'] = 'is_required,is_mobileindian'; //9999999999
            $fieldlist['contact_no1']['text'] = 'is_phone';
//contact_no
            $fieldlist['email_id']['text'] = 'is_required,is_email,is_maxlength30';
           $fieldlist['id_type']['select'] = 'is_select_req';
//            $fieldlist['uid_no']['text'] = 'is_required';
            $fieldlist['hint_question']['select'] = 'is_select_req';
            $fieldlist['hint_answer']['text'] = 'is_required,is_alphanumspacedashdotcommaroundbrackets';
            //  $fieldlist['mobile_no']['text'] = 'is_mobileindian';
//dependent field
//            $fieldlist['corp_coun_id']['select'] = 'is_select_req';
//            $fieldlist['authetication_type']['select'] = 'is_select_req';
//            $fieldlist['username']['text'] = 'is_required,is_username';
//            $fieldlist['password']['text'] = 'is_required,is_password';
//            $fieldlist['r_password']['text'] = 'is_required,is_password';
//            $fieldlist['full_name']['text'] = 'is_alphaspace';
//            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
//            $fieldlist['role_id']['select'] = 'is_select_req';
        
        
        
    
//pr($fieldlist);
        return $fieldlist;
    }
    
    //-----------------------------------by Shridhar dated 14-July-2017-----------------------------------------------
     public function get_emloyee_detail($emp_id) {
        return $this->Query("SELECT  emp.emp_id,emp.emp_fname||' '||emp.emp_lname as emp_name,emp.reporting_officer_id, report.emp_fname||' '||report.emp_lname as reporting_officer_name,emp.office_id,office.office_name_en,hierarchy.hierarchy_desc_en, 1 AS level
                FROM   ngdrstab_mst_employee emp
                left join ngdrstab_mst_employee report on report.emp_id=emp.reporting_officer_id
                left join ngdrstab_mst_office office on office.office_id=emp.office_id
                left join ngdrstab_mst_office_hierarchy hierarchy on hierarchy.hierarchy_id=office.hierarchy_id
                WHERE  emp.emp_code=(select employee_id from ngdrstab_mst_user where user_id=?)", array($emp_id));
    }
    //-----------------------------------by Shridhar dated 13-July-2017-----------------------------------------------
    public function get_employee_hierarchy($officer_id) {
        try {
            return $this->Query("
               WITH RECURSIVE cte AS 
            (
                SELECT  emp.emp_id,emp.emp_fname||' '||emp.emp_lname as emp_name,emp.reporting_officer_id, report.emp_fname||' '||report.emp_lname as reporting_officer_name,emp.office_id,office.office_name_en,hierarchy.hierarchy_desc_en, 1 AS level
                FROM   ngdrstab_mst_employee emp
                join ngdrstab_mst_employee report on report.emp_id=emp.reporting_officer_id
                join ngdrstab_mst_office office on office.office_id=emp.office_id
                left join ngdrstab_mst_office_hierarchy hierarchy on hierarchy.hierarchy_id=office.hierarchy_id
                WHERE  emp.reporting_officer_id = ?
                            
                UNION  ALL
                
                SELECT t.emp_id, t.emp_fname||t.emp_lname as emp_name ,t.reporting_officer_id,report.emp_fname||' '||report.emp_lname as reporting_officer_name,t.office_id,office.office_name_en,hierarchy.hierarchy_desc_en,c.level + 1
                FROM   cte  c
                JOIN   ngdrstab_mst_employee t ON t.reporting_officer_id = c.emp_id
                join  ngdrstab_mst_employee report on report.emp_id=t.reporting_officer_id
                join ngdrstab_mst_office office on office.office_id=t.office_id
		left join ngdrstab_mst_office_hierarchy hierarchy on hierarchy.hierarchy_id=office.hierarchy_id
            )
            SELECT emp_id,emp_name,reporting_officer_id,reporting_officer_name,office_id,office_name_en,hierarchy_desc_en
            FROM   cte
            ORDER  BY level,office_id", array($officer_id));
        } catch (Exception $ex) {
            
        }
    }
    
    public function updateforgotpassword($password, $username) {
        try {
            $new = $password;
            $result = $this->query("Update ngdrstab_mst_user SET password='" . $new . "' where username='" . $username . "'");
            $result1 = $this->query("Update ngdrstab_mst_employee SET password='" . $new . "' where username='" . $username . "'");
            return true;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function smssend($smsid, $phone, $otp) {

        $q = $this->query("select * from ngdrstab_conf_smstext where textid=" . $smsid);
        //pr($q);exit;
        $q1 = $this->query("select * from ngdrstab_conf_sms");
        //pr($q1);exit;
        if (function_exists('curl_init')) {
            $tst = $q[0][0]['smstext'] . $otp;
            $uid = urlencode($q1[0][0]['uname']);
            $pass = urlencode($q1[0][0]['passwd']);
            $send = urlencode("NICSMS"); // 6 characters long SENDERID
            $dest = urlencode($phone);
            $msg = urlencode($tst);

            $url = "https://smsgw.sms.gov.in/failsafe/HttpLink?";
            $data = "username=$uid&pin=$pass&message=$msg&mnumber=$dest&signature=$send";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CAINFO, '/etc/pki/tls/certs/ca-bundle.crt');
            if (curl_errno($ch))
                echo 'Curl error: ' . curl_error($ch);
            else
                $curl_output = curl_exec($ch);
            curl_close($ch);
            $messageid = $curl_output;
            $str = (explode("&", $messageid));
            $str2 = (explode(" ", $str[0]));
            if (count($str2 != 1)) {
                try {
                    $q2 = $this->query("insert into ngdrstab_trn_smslogs(mobno,messageid,message_time) values(" . $phone . ",'" . $str2[4] . "',now())");
                } catch (Exception $e) {
                    header('Location:../cterror.html');
                    exit;
                }
            }
        }
        return true;
    }
}
