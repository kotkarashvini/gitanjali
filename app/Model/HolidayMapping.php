<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of holiday_mapping
 *
 * @author Admin
 */
 
class HolidayMapping extends AppModel{
    //put your code here
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_holiday_mapping';
     public $primaryKey = 'mapping_id';
}