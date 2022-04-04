<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UnitCategory
 *
 * @author Admin1
 */
 
class UnitCategory extends AppModel{
     public $useDbConfig = 'default';
    public $useTable = 'ngdrstab_mst_unit_category';
    public $primaryKey = 'unit_cat_id';
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_unit_category';
        $duplicate['PrimaryKey'] = 'unit_cat_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'unit_cat_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['unit_cat_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required';
            } else {
                $fieldlist['unit_cat_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }

        return $fieldlist;
    }
}