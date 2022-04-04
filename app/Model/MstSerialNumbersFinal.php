<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MstSerialNumbersFinal
 *
 * @author nic
 */
 
class MstSerialNumbersFinal extends AppModel {
    //put your code here

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_serial_numbers_final';
    public $primaryKey = 'counter_id';

}