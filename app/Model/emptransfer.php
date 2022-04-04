<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of emptransfer
 *
 * @author nic
 */
class emptransfer extends AppModel {
    //put your code here
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_employee_transfer';
    public $primaryKey = 'transfer_id';
}
