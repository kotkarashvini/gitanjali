<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of citizenuserreg
 *
 * @author Acer
 */
class citizenuserreg extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_usercitizen_registartion';
    
    public function findbyecitiuser($username) {
        try {
            $username=strtoupper($username);
           
            $check = $this->query("select * from ngdrstab_trn_usercitizen_registartion where UPPER(user_name)=?",array($username));
            
            $c = count($check);
           // $c1 = count($check1);
          
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
    
    public function updateforgotpassword_citizen($password, $username) {
        try {
            $new = $password;
            $result = $this->query("Update ngdrstab_mst_user_citizen SET password='" . $new . "' where username='" . $username . "'");
            $result1 = $this->query("Update ngdrstab_trn_usercitizen_registartion SET user_pass='" . $new . "' where user_name='" . $username . "'");
            return true;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    public function findbyemail($email) {
        try {
            $email=strtoupper($email);
           
            $check = $this->query("select * from ngdrstab_trn_usercitizen_registartion where UPPER(email_id)=?",array($email));
            
            $c = count($check);
           // $c1 = count($check1);
          
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
    public function findbymobile($mobile) {
        try {
            $check = $this->query("select * from ngdrstab_trn_usercitizen_registartion where mobile_no=?",array($mobile));
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
    
    public function findbyuid($uid) {
        try {
            $result= base64_encode($uid);
            $check = $this->query("select * from ngdrstab_trn_usercitizen_registartion where uid=?",array($result));
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
    
    
    
    public function findbyuidproof($pan_no) {
        try {
           
            $check = $this->query("select * from ngdrstab_trn_usercitizen_registartion where pan_no=?",array($pan_no));
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
    
}
