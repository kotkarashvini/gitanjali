<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DocumentDisposalEntry extends AppModel {
    //put your code here
      public $useDbConfig = 'ngprs';
      public $useTable= 'ngdrstab_trn_document_disposal';
      public  $primaryKey='disposal_details_id';
      
     public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'forward_user_id'
        ),
          'DocumentDisposal' => array(
            'className' => 'DocumentDisposal',
            'foreignKey' => 'disposal_id'
        ),
          'DocumentDisposalReasons' => array(
            'className' => 'DocumentDisposalReasons',
            'foreignKey' => 'reason_id'
        )
    );
}
 