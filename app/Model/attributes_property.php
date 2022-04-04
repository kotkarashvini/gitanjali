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
class attributes_property extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_attribute_parameter';
    public $primaryKey = 'id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_attribute_parameter';
        $duplicate['PrimaryKey'] = 'id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'eri_attribute_name_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['eri_attribute_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['eri_attribute_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }

        return $fieldlist;
    }

}
