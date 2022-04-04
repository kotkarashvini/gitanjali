<?php

class documenttitle extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_document_title';

    public function get_title()
    {
 $documenttitle = $this->find('list', array('fields' => array('documenttitle.article_id', 'documenttitle.title_name'), 'order' => array('documenttitle.title_name' => 'ASC')));
  
          return $documenttitle;
          }
   
}
