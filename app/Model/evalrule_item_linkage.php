<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usageitemlist
 *
 * @author Administrator
 */
class evalrule_item_linkage extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_usage_lnk_category';
    public $primaryKey = 'usage_lnk_id';

}
