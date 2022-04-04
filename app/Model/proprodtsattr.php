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
class proprodtsattr extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_prohibited_attribute';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );
    
//    var $virtualFields = array(
//    'name' => "CONCAT(Level1.level_1_from_range, '-', Level1.level_1_to_range)"
//);

}
