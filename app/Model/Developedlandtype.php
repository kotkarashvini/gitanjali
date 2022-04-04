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
class Developedlandtype extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_developed_land_types';
    public $primaryKey = 'developed_land_types_id';

//    var $virtualFields = array(
//        'name' => "CONCAT(district.district_name)"
//    );



    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_developed_land_types';
        $duplicate['PrimaryKey'] = 'developed_land_types_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'developed_land_types_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['developed_land_types_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alpha,is_maxlength50';
            } else {
                $fieldlist['developed_land_types_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';
            }
        }

        return $fieldlist;
    }

}
