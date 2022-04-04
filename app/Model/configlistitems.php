<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of areatype
 *
 * @author Administrator
 */
class configlistitems extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_list_items';
    public $primaryKey = 'id'; 
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_list_items';
        $duplicate['PrimaryKey'] = 'id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'item_id,item_desc_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'item_id,item_desc_id');
         
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) { 
        $fieldlist['item_id']['select'] = 'is_select_req,is_numeric';
        $fieldlist['item_desc_id']['text'] = 'is_required,is_integer';
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['item_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['item_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        } 
        
        return $fieldlist;
    }


}
