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
class Level3 extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_loc_level_3_prop_list';
//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );
    
    var $virtualFields = array(
    'name' => "CONCAT(Level3.level_3_from_range, '-', Level3.level_3_to_range)"
);

}
