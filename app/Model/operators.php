<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of articleparameters
 *
 * @author Administrator
 */
class operators extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_operators';
    public $primaryKey = 'operator_id';
    var $virtualFields = array(
        'optrname' => "CONCAT(operatorsign, ' ', operator_name_en)"
    );

}
