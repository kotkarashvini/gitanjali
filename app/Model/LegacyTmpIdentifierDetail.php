<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of party_entry
 *
 * @author Anjali
 */
class LegacyTmpIdentifierDetail extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_tmp_legacy_witness';
    public $primaryKey = 'id';

    function getTempIdentifierDetailByDocRegNo($docRegNo) {
        $data = $this->find('all', array('conditions' => array('doc_reg_no' => $docRegNo)));
        return $this->formatData($data, 'LegacyTmpIdentifierDetail', 'id');
    }

}
