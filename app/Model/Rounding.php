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
class Rounding extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_rounding_value';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );

    public $primaryKey = 'rounding_id';

}
