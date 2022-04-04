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
class corporationclass extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock_local_governingbody';
    public $virtualFields = array('ulb_desc' => 'CONCAT(corporationclass.ulb_type_id|| \' : \' || corporationclass.class_description_en)');

}
