<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bdruserpermissions
 *
 * @author Administrator
 */
class userpermissions extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_userpermissions';   
    
    public function fieldlist() {
        $fieldlist = array();       
        $fieldlist['role_id']['select'] = 'is_select_req';         
        $fieldlist['module_id']['select'] = 'is_select_req';  
        return $fieldlist;
    }


}