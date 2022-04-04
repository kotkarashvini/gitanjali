<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ngdrstab_mst_document_execution_type
 *
 * @author Administrator
 */
class document_execution_type extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_document_execution_type';
    public $primaryKey = 'execution_type_id';
    public function get_doc_execution_type($lang)
    {
       $type= $this->find('list', array('fields' => array('document_execution_type.id', 'document_execution_type.execution_type_'.$lang), 'order' => array('document_execution_type.id' => 'ASC')));
    
       return $type;
    }
  public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_document_execution_type';
        $duplicate['PrimaryKey'] = 'execution_type_id';

        $fields = array();
        foreach ($languagelist as $language) {
            //  array_push($fields, 'holiday_fdate,district_id,holiday_type_id,articledescription_' . $language['mainlanguage']['language_code']);
            array_push($fields, 'execution_type_' . $language['mainlanguage']['language_code']);
        }

        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['execution_type_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_duplicate';
            } else {
                $fieldlist['execution_type_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
            
            
        }
        return $fieldlist;
    }
}
