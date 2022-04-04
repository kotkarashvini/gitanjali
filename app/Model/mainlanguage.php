<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mainlanguage
 *
 * @author nic
 */
class mainlanguage extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_language';
    
    public function get_main_lag()
    {
    $result=$this->find('list', array('fields' => array('id', 'language_name'), 'joins' => array(
                    array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false,'conditions' => array('conf.language_id = mainlanguage.id') ))));

    return $result;
    }
   public function get_state_lang($stateid)
    {
     $language2 = $this->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
     return $language2;
}
}
