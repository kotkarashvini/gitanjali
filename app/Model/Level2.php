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
class Level2 extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_loc_level_2_prop_list';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );
    
    var $virtualFields = array(
    'name' => "CONCAT(Level2.level_2_from_range, '-', Level2.level_2_to_range)"
);

}
