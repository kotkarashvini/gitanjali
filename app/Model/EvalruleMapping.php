<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EvalruleMapping
 *
 * @author nic
 */
class EvalruleMapping  extends AppModel{
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_evalrule_mapping';
    public $primaryKey ='mapping_id';
    
    
}
