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
class corporationclasslist extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_admblock_local_governingbody_list';
    public $primaryKey = 'corp_id';    

  public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_admblock_local_governingbody_list';
        $duplicate['PrimaryKey'] = 'corp_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'governingbody_name_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['governingbody_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength100';
            } else {
                $fieldlist['governingbody_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'].",maxlength_unicode_0to100";
            }
        }
        
         $fieldlist['ulb_type_id']['select'] = 'is_select_req'; 
         $fieldlist['class_type']['text'] = 'is_required,is_alpha,is_maxlength1';  

        return $fieldlist;
    }

    

}
