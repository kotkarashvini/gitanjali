<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentNumberFinal
 *
 * @author nic
 */
class DocumentNumberFinal extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_document_number_final';
    public $primaryKey='format_field_id';
    
    public function get_duplicate() {
        $duplicate['Table'] = 'ngdrstab_conf_document_number';
        $duplicate['PrimaryKey'] = 'format_field_id';
        $fields = array();
            array_push($fields, 'format_field_flag,display_order');
            //array_push($fields, 'format_field_desc');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }
    
    public function fieldlist() {
                $fieldlist = array(); 
                $fieldlist['format_field_flag']['text'] = 'is_required,is_yes_no';
                $fieldlist['display_order']['text'] = 'is_required,is_numeric';
                $fieldlist['h_order']['text'] = 'is_required,is_numeric';
            
        return $fieldlist;
    }
}
