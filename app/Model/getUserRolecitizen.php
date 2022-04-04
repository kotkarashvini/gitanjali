<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getUserRolecitizen
 *
 * @author acer
 */
class getUserRolecitizen extends AppModel {
    //put your code here
    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_userroles_citizen';

//    public function saveData($data) {
//        try {
//            $q2 = $this->query("insert into ngdrstab_mst_userroles(user_id,module_id,role_id) values(".$data['user_id'].",".$data['module_id'].",".$data['role_id'].",'".$data['created']."')");
//            return TRUE;
//        } catch (Exception $e) {
//            $this->redirect(array('action' => 'error404'));
//        }
//    }
}
