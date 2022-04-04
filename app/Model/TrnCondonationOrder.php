<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrnCondonationOrder
 *
 * @author Admin1
 */
class TrnCondonationOrder extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_condonation_order';
    public $primaryKey='order_id';

}