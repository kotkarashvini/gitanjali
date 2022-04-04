<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evalrule
 *
 * @author Administrator
 */
class article_feerule extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article_feerule';
    public $primaryKey = 'fee_rule_id';

}
