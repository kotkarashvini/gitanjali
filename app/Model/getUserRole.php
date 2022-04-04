<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getUserRole
 *
 * @author Anjali
 */
class getUserRole extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_userroles';
    public $primaryKey = 'userroles_id';

    public function saveData($data) {
        try {
            $q2 = $this->query("insert into ngdrstab_mst_userroles(office_id,user_id,role_id) values(?,?,?,?)",array($data['office_id'],$data['user_id'],$data['role_id']));
            return TRUE;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
}
