<?php

class Legacy_tmp_propertydetails extends AppModel {

    public $useDbConfig = 'default';
    public $useTable = 'ngdrstab_trn_tmp_legacy_property_details_entry';
    public $primaryKey = 'property_id';

    function getPropertyDetailsByDocumentNo($docNo) {
        $data = $this->find('all', array('conditions' => array('doc_reg_no' => $docNo)));
        return $this->formatData($data, 'Legacy_tmp_propertydetails', 'id');
    }

}

?>