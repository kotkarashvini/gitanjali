<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of District
 *
 * @author Acer
 */
class article_screen_mapping extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article_screen_mapping';
    public $primaryKey = 'id';

    public function get_duplicate() {
        $duplicate['Table'] = 'ngdrstab_mst_article_screen_mapping';
        $duplicate['PrimaryKey'] = 'id';
        $fields = array();
        array_push($fields, 'article_id,minorfun_id');        
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }
}
