<?php

class doc_title extends AppModel {

    public $useDbConfig = 'ngprs';    
    public $useTable = 'ngdrstab_mst_articledescriptiondetail';    
    //public $primaryKey = 'id';  
    
     public function get_title()
    {
 $documenttitle = $this->find('list', array('fields' => array('articledescription_id', 'articledescription_en'), 'order' => array('articledescription_en' => 'ASC')));
  
          return $documenttitle;
          }

}
