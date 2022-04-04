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
class Subdivision extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock4_subdivision';
    public $primaryKey = 'subdivision_id';
    
     public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock4_subdivision';      
        $duplicate['PrimaryKey'] = 'subdivision_id';        
        $fields=array();
        foreach ($languagelist as $language){          
            array_push($fields, 'subdivision_name_'.$language['mainlanguage']['language_code']);
        }      
         $duplicate['Fields'] = $fields;
         return $duplicate;
    }
}
