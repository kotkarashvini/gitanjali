<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class article_doc_map extends AppModel {

    //put your code here.

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article_document_mapping';
     public $primaryKey = 'article_doc_map_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_article_document_mapping';
        $duplicate['PrimaryKey'] = 'article_doc_map_id';
        $fields = array();
        array_push($fields,'article_id,document_id');


       // array_push($fields,'article_id');
//        array_push($fields, 'document_id');
//        array_push($fields, 'is_required');


        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        $fieldlist['article_id']['select'] = 'is_select_req';
        $fieldlist['document_id']['select'] = 'is_select_req';
         //$fieldlist['is_required']['select'] = is_required;

        return $fieldlist;
    }

}
