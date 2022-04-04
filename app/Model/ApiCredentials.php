<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiCredentials
 *
 * @author Admin
 */
class ApiCredentials extends AppModel {

    //put your code here    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_api_credentials';
    public $primaryKey = 'api_user_id';

    /*
     *  api_username
     *  api_password
     */

    public function authenticate($data) {
        $remoteip = $_SERVER['REMOTE_ADDR'];        
        $result = $this->find("first", array('conditions' => array('api_code' =>$data['api_code'],'api_username' => $data['api_username'], 'api_password' => $data['api_password'])));
        if (!empty($result)) {
           // if (strpos($result['ApiCredentials']['api_whitelist_ips'], $remoteip) !== false) {
                return TRUE;
           // }
        }
        return FALSE;
    }

}
