<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adminLevelConfig
 *
 * @author Anjali
 */
class adminLevelConfig extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_state_district_div_level'; 
    public $primaryKey = 'state_id';

}
