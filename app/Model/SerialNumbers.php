<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SerialNumbers extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_serial_numbers';
    public $primaryKey='counter_id';
}
