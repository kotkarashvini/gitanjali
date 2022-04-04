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
class state_logo extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_state_logo';
    public $primaryKey = 'state_id';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );

}
