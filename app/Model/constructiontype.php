<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of constructiontype
 *
 * @author Administrator
 */
class constructiontype extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_construction_type';
    public $primaryKey = 'construction_type_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_construction_type';
        $duplicate['PrimaryKey'] = 'construction_type_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'construction_type_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['construction_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['construction_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}
