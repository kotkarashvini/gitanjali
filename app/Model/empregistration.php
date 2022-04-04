<?php

class empregistration extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_user_registartion';
    
    public function test() {
        try {

            $select_user = $this->query("select * from ngdrstab_mst_user");
            return($select_user);
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
        public function get_deed_writer() {
        try {

            $select_user = $this->query("select * from ngdrstab_mst_user_citizen where deed_writer='Y'");
           return($select_user);
           
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    public function findbyemail($email) {
        try {
            $email=strtoupper($email);
            $check = $this->query("select * from ngdrstab_trn_user_registartion where UPPER(email_id)=?",array($email));
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
    public function findbymobile($mobile) {
        try {
            $check = $this->query("select * from ngdrstab_trn_user_registartion where mobile_no=?",array($mobile));
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
    
    public function get_citizen() {
        try {

            $select_user = $this->query("select * from ngdrstab_mst_user_citizen");
           return($select_user);
           
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function get_srouser() {
        try {

            $select_user = $this->query("select * from ngdrstab_mst_user");
           return($select_user);
           
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
}
