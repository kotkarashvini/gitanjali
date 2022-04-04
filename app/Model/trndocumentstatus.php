DISTINCT<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of District
 *
 * @author Acer
 */
class trndocumentstatus extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_document_status';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );
    
    
    function get_alltoken($from, $to){
        
       $alltoken = $this->find('all', array('fields'=>array('DISTINCT token_no'),'conditions' => array('and' => array(
                       'created >=' => $from,
                              'created <=' =>$to
                             ))));  
      return($alltoken);
      
    }

}
