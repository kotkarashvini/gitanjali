<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentDisposal
 *
 * @author Admin1
 */
class DocumentDisposal extends AppModel {
    //put your code here
      public $useDbConfig = 'ngprs';
      public $useTable = 'ngdrstab_mst_document_disposal';
      public  $primaryKey='disposal_id';
      
        public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_document_disposal';
        $duplicate['PrimaryKey'] = 'disposal_id';         
        
       $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'disposal_desc_' . $language['mainlanguage']['language_code']);
        }
        
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        
//        $fieldlist['disposal_id']['select']='is_select_req';
         foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['disposal_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['disposal_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }   
//        $fieldlist['holiday_fdate']['text']='is_required';         
//        
//        $fieldlist['district_id']['select']='is_select_req';  
//        $fieldlist['office_id']['checkbox']='is_select_req';  
        
        
        
        return $fieldlist;
    }
}
