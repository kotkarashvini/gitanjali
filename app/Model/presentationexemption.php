<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of presentationexemption
 *
 * @author acer
 */
class presentationexemption extends AppModel {
    //put your code here
    
    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_presentation_exemption';
   public $primaryKey = 'exemption_id';
    
          
public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_presentation_exemption';
        $duplicate['PrimaryKey'] = 'exemption_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }
    
    public function fieldlist($languagelist) {

        $fieldlist = array();
         
//         $fieldlist['behavioral_id']['select'] = 'is_required'; 
//           $fieldlist['behavioral_details_id']['select'] = 'is_required'; 
//           $fieldlist['is_required']['select'] = 'is_required'; 
//           $fieldlist['vrule_en']['select'] = 'is_required'; 
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
                // $fieldlist['vrule_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
                // $fieldlist['vrule_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
          

        return $fieldlist;
    }
    
   
    
    
    
}
