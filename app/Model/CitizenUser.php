<?php

App::uses('AuthComponent', 'Controller/Component');

class CitizenUser extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_user_citizen';

    
    public $validate = array(
        'username' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'This Fild is require',
                'allowEmpty' => false,
                'required' => true,
            ),
            'isAlfaNumericdotString' => array(
                'rule' => array('isAlfaNumericdotString', 30),
                'message' => 'Invalid username or password',
            ),
        ),
        'password' => array(
            'isPassword' => array(
                'rule' => array('isPassword', 8),
                'message' => 'Invalid username or password',
            ),
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'This Fild is require',
            )
        )
    );
    
    public function findbyusername($user_name) {
        try {
            $user_name=strtoupper($user_name);
            $check = $this->query("select * from ngdrstab_mst_user_citizen where UPPER(username)=?",array($user_name));
            $c = count($check);
          
            if ($c > 0) {
                echo 1;
            } else {
                echo 0;
            }
            exit;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
 public function insertLoginDetails($userid,$role_id,$logintype) {
        try {
//            $ipaddress = "";
//if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1' && empty($ipaddress))
//$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//else if ($_SERVER['HTTP_X_FORWARDED'] != '127.0.0.1' && empty($ipaddress))
//$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
//else if ($_SERVER['HTTP_FORWARDED_FOR'] != '127.0.0.1' && empty($ipaddress))
//$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
//else if ($_SERVER['HTTP_FORWARDED'] != '127.0.0.1' && empty($ipaddress))
//$ipaddress = $_SERVER['HTTP_FORWARDED'];
//else if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && empty($ipaddress))
//$ipaddress = $_SERVER['REMOTE_ADDR'];
//else
//$ipaddress = 'UNKNOWN';
//            $req_ip = $ipaddress;
            
             $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
             $host_ip=$_SERVER['SERVER_ADDR'];
//            $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Logged in successfully')";
              $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status,role_id,host_ip,login_type) values(?,?,?,?,?,?,?,?,?)";
//              ECHO "HGGHJY";
            $this->query($sql,array($userid,$date,$time,$sessionId,$req_ip,'LoggedIn',$role_id,$host_ip,$logintype));
            
        } catch (Exception $e) {
            pr($e);exit;
            $this->redirect(array('action' => 'error404'));
        }
    }
    public function insertLoginDetails_old($userid,$role_id) {
        try {
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
//            $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Logged in successfully')";
              $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status,role_id) values(?,?,?,?,?,?,?)";
//              ECHO "HGGHJY";
            $this->query($sql,array($userid,$date,$time,$sessionId,$req_ip,'LoggedIn',$role_id));
            
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    
    

    public function insertUnsuccessfulLogin($userid,$logintype) {
        try {
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $date = date('Y/m/d');
            $time = date("H:i:s");
            $sessionId = session_id();
//            $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status)
//                values(" . $userid . ",'" . $date . "','" . $time . "','" . $sessionId . "','" . $req_ip . "','Unsuccessful Login')";
            
                     $sql = "insert into ngdrstab_mst_loginusers_citizen(user_id,logindate,logintime,sessionid,req_ip,login_status,login_type)
                values(?,?,?,?,?,?,?)";
//                    ECHO "KK"; 
            $this->query($sql,array($userid,$date , $time,$sessionId , $req_ip ,'Unsuccessful Login',$logintype));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function updateLoginDetails($userid) {
        try {

            $time = date("H:i:s");
            $date = date('Y-m-d'); 
            //$sessionId = session_id();
//            $sql = "update ngdrstab_mst_loginusers_citizen set login_status='Logged out successfully',logouttime='" . $time . "' where user_id=" . $userid . " and sessionid='" . $sessionId . "'";
             $sql = "update ngdrstab_mst_loginusers_citizen set login_status=?,logouttime=?where user_id=? and logindate=?";
            $this->query($sql,array('Logged out successfully',$time,$userid,$date));
            
            
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function getlogin_details() {
        
	$result = $this->query("select A.user_id,A.username,B.logintime as LoginTime,to_char(B.logindate,'dd/MM/yyyy')as LoginDate,B.login_status from ngdrstab_mst_user_citizen A LEFT OUTER JOIN ngdrstab_mst_loginusers_citizen B on A.user_id=B.user_id where B.logintime IS not NULL or B.logindate is not null  order by B.logindate desc,B.logintime desc");
	
        return $result;
    }

}
