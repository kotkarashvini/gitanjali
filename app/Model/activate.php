<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of activate
 *
 * @author Acer
 */
class activate extends AppModel {
    //put your code here
    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_user';
    
    public function test() {
        try {

            $select_user = $this->query("select * from ngprtab_mst_user where activeflag='N'");
            return($select_user);
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
}
