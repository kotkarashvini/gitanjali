<?php

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
class ErrorMessages extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_msg_alerts';
    public $primaryKey ='msg_id';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );

}
