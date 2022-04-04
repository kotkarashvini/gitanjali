
<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_user';
    public $primaryKey = 'user_id';
//    public $useDbConfig = 'default';
//    public $useTable = 'dmatab_user'; 
    
  public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_user';
        $duplicate['PrimaryKey'] = 'user_id';
        $fields = array(); 
        
        array_push($fields, 'username');
        array_push($fields, 'employee_id');
        array_push($fields, 'mobile_no');
        
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }
    
       public function fieldlist($languagelist) {

        $fieldlist = array();
        
         $fieldlist['employee_id']['select'] = 'is_required,is_alphanumeric';
//        $fieldlist['corp_coun_id']['select'] = 'is_select_req';
        
            $fieldlist['authetication_type']['select'] = 'is_select_req';
//             $fieldlist['biometric_capture_flag']['select'] = 'is_select_req';
              $fieldlist['username']['text'] = 'is_required,is_alphanumeric,is_maxlength50';
              $fieldlist['role_id']['select'] = 'is_select_req';

          
//            $fieldlist['password']['text'] = 'is_password';
//            $fieldlist['r_password']['text'] = 'is_password';
           
            $fieldlist['full_name']['text'] = 'is_required,is_alphaspace,is_maxlength100';
            $fieldlist['mobile_no']['text'] = 'is_mobileindian';
            $fieldlist['email_id']['text'] = 'is_email,is_maxlength100';
           
        
        
    
//pr($fieldlist);
        return $fieldlist;
    }
    public function insertLoginDetails_old($userid) {
        try {
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
//            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Logged in successfully')";
            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status)
                values(?,?,?,?,?,?)";
            $this->query($sql, array($userid, $date, $time, $sessionId, $req_ip, 'Logged in successfully'));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
       public function insertLoginDetails($userid) {
        try {
            
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
            $host_ip=$_SERVER['SERVER_ADDR'];
//            echo($host_ip);exit;
//            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Logged in successfully')";
            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status,host_ip,login_status_id)
                values(?,?,?,?,?,?,?,?)";
            $this->query($sql, array($userid, $date, $time, $sessionId, $req_ip, 'Logged in successfully',$host_ip,1));
        } catch (Exception $e) {
            
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function insertUnsuccessfulLogin($userid) {
        try {
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
//            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Unsuccessful Login')";
            $sql = "insert into ngdrstab_mst_loginusers(user_id,logindate,logintime,sessionid,req_ip,login_status)
                values(?,?,?,?,?,?)";
            $this->query($sql, array($userid, $date, $time, $sessionId, $req_ip, 'Unsuccessful Login'));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

   
    
     public function updateLoginDetails_old($userid,$login_id=NULL) {
        try {

            $time = date("H:i:s");
            $date = date('Y/m/d');
            $sessionId = session_id();
//            $sql = "update ngdrstab_mst_loginusers set login_status='Logged out successfully',logouttime='" . $time . "' where user_id=" . $userid . " and sessionid='" . $sessionId . "'";
            $sql = "update ngdrstab_mst_loginusers set login_status=?,logouttime=?,login_status_id=? where user_id=? and logindate=? and id=?";
            $this->query($sql, array('Logged out successfully', $time, 0, $userid, $date, $login_id));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function updateLoginDetails($userid,$login_id=NULL) {
        try {
            $time = date("H:i:s");
            $date = date('Y-m-d'); 
            //$sessionId = session_id();
//            $sql = "update ngdrstab_mst_loginusers set login_status='Logged out successfully',logouttime='" . $time . "' where user_id=" . $userid . " and sessionid='" . $sessionId . "'";
            $sql = "update ngdrstab_mst_loginusers set login_status=?,logouttime=?, login_status_id=? where user_id=? and logindate=? and id=?";
            $this->query($sql, array('Logged out successfully', $time, 0, $userid, $date,$login_id));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function getlogin_details() {

        $result = $this->query("select A.user_id,A.username,B.logintime as LoginTime,to_char(B.logindate,'dd/MM/yyyy')as LoginDate,B.login_status from ngprtab_mst_user A LEFT OUTER JOIN ngdrstab_mst_loginusers B on A.user_id=B.user_id where B.logintime IS not NULL or B.logindate is not null  order by B.logindate desc,B.logintime desc");

        return $result;
    }

    public function findbyusername($user_name) {
        try {
            $user_name = strtoupper($user_name);
            $check = $this->query("select * from ngdrstab_mst_user where UPPER(username)=?", array($user_name));
            //  echo "hi";
//            pr($check);
            $c = count($check);
            if ($c > 0) {
                echo 'r1';
            } else {
                echo 'r0';
            }
            //  exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

//    public function smssend($smsid, $mobile_no, $otp) {
//
//        $q = $this->query("select * from ngdrstab_conf_smstext where textid=" . $smsid);
//        //pr($q);exit;
//        $q1 = $this->query("select * from ngdrstab_conf_sms");
//        //pr($q1);exit;
//        if (function_exists('curl_init')) {
//            $tst = $q[0][0]['smstext'] . $otp;
//            $uid = urlencode($q1[0][0]['uname']);
//            $pass = urlencode($q1[0][0]['passwd']);
//            $send = urlencode("NICSMS"); // 6 characters long SENDERID
//            $dest = urlencode($phone);
//            $msg = urlencode($tst);
//
//            $url = "https://smsgw.sms.gov.in/failsafe/HttpLink?";
//            $data = "username=$uid&pin=$pass&message=$msg&mnumber=$dest&signature=$send";
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//            curl_setopt($ch, CURLOPT_CAINFO, '/etc/pki/tls/certs/ca-bundle.crt');
//            if (curl_errno($ch))
//                echo 'Curl error: ' . curl_error($ch);
//            else
//                $curl_output = curl_exec($ch);
//            curl_close($ch);
//            $messageid = $curl_output;
//            $str = (explode("&", $messageid));
//            $str2 = (explode(" ", $str[0]));
//            if (count($str2 != 1)) {
//                try {
//                    $q2 = $this->query("insert into ngdrstab_trn_smslogs(mobno,messageid,message_time) values(" . $mobile_no . ",'" . $str2[4] . "',now())");
//                } catch (Exception $e) {
//                    header('Location:../cterror.html');
//                    exit;
//                }
//            }
//        }
//        return true;
//    }
    public function smssend($smsid, $mobile_no, $otp) {
        //if (isset($smsid) && isset($mobile_no) && isset($otp) && is_numeric($smsid) && is_numeric($mobile_no) && is_numeric($otp)) {
            //   $q = $this->query("select * from ngdrstab_conf_smstext where textid=$smsid");
            //     $q = $this->query("select * from ngdrstab_conf_smstext where textid=$smsid");
            //  pr($q);
            //  
            //   $q1 = $this->query("select * from ngdrstab_conf_sms");
            //    pr($q1);
            $q2 = $this->query("insert into ngdrstab_trn_smslogs(mobno,messageid,message_time) values(" . $mobile_no . ",'" . $smsid . "',now())");

            ////    pr($q2);
            //    exit;
       // }
    }

}
