<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usage_sub_category
 *
 * @author Administrator
 */
class usage_sub_category extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_usage_sub_category';
    public $primaryKey = 'usage_sub_catg_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_usage_sub_category';
        $duplicate['PrimaryKey'] = 'usage_sub_catg_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'usage_sub_catg_desc_' . $language['mainlanguage']['language_code']);
        }
        //array_push($fields, 'usage_main_catg_id');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $adminLevelConfig) {

        $fieldlist = array();
        
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['usage_sub_catg_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_aplhanumericspace';
            } else {
                $fieldlist['usage_sub_catg_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
      
        return $fieldlist;
    }
}
