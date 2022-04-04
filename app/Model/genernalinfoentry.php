<?php

 class genernalinfoentry extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_generalinformation';
    public $primaryKey='general_info_id';

//----------------------------by Shridhar----------------------------   
    public function get_general_info($lang=NULL,$doc_token_no=NULL){
        return $this->find('all', array(// generalinfoentry=1,5,7
            'fields' => array('genernalinfoentry.token_no', 'article.article_desc_' . $lang,'article.article_id' , 'sd.final_amt', 'genernalinfoentry.exec_date', 'genernalinfoentry.no_of_pages', 'art_ty_nm.articledescription_'.$lang,'genernalinfoentry.title_id'),
            'joins' => array(
                array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=genernalinfoentry.article_id')), //1                
                array('table' => 'ngdrstab_mst_articledescriptiondetail', 'alias' => 'art_ty_nm', 'conditions' => array('art_ty_nm.articledescription_id=genernalinfoentry.title_id')), //2
                array('table' => 'ngdrstab_trn_stamp_duty', 'alias' => 'sd', 'type' => 'left', 'conditions' => array('sd.token_no=genernalinfoentry.token_no'))//4
            ),
            'conditions' => array('genernalinfoentry.token_no' => $doc_token_no)));
    }
}
