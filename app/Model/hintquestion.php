<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hintquestion
 *
 * @author Acer
 */
class hintquestion extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_hint_questions';
    public $primaryKey='hint_questions_id';
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_hint_questions';
        $duplicate['PrimaryKey'] = 'hint_questions_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'questions_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['questions_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace,is_maxlength20';
            } else {
                $fieldlist['questions_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }
}
