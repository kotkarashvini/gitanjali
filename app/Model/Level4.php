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
class Level4 extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_loc_level_4_prop_list';
 var $virtualFields = array(
    'name' => "CONCAT(Level4.level_4_from_range, '-', Level4.level_4_to_range)"
);

}
