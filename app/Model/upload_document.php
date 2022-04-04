<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of upload_file_formate
 *
 * @author nic
 */
class upload_document extends AppModel {

    //put your code here

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_upload_document';
    public $primaryKey = 'document_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_upload_document';
        $duplicate['PrimaryKey'] = 'document_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'document_name_' . $language['mainlanguage']['language_code']);
        }


        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();

        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['document_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            } else {
                $fieldlist['document_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        $fieldlist['file_size']['text'] = 'is_numeric';

        return $fieldlist;
    }

}
