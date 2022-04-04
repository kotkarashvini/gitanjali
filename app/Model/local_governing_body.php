<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of local_governing_body
 *
 * @author nic
 */
class local_governing_body extends AppModel {

    //put your code here

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock_local_governingbody';
    public $primaryKey = 'ulb_type_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock_local_governingbody';
        $duplicate['PrimaryKey'] = 'ulb_type_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'class_description_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['class_description_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength50';
            } else {
                $fieldlist['class_description_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].',maxlength_unicode_0to50';
            }
        }
       // $fieldlist['class_type']['text'] = 'is_required,is_alpha,is_maxlength1';

        return $fieldlist;
    }
}
    