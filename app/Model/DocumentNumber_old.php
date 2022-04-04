<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DocumentNumber extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_document_number';
    public $primaryKey='format_field_id';
}

  
  