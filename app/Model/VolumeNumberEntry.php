<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VolumeNumberEntry
 *
 * @author nic
 */
class VolumeNumberEntry extends AppModel {

    public $useDbConfig = 'ngprs';    
    public $useTable = 'ngdrstab_trn_volume_number_page_number_entry';    
    public $primaryKey = 'entry_id';  

}
