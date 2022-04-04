<?php

class Legacy_tmp_generalinformation extends AppModel {

    public $useDbConfig = 'default';
    public $useTable = 'ngdrstab_trn_tmp_legacy_generalinformation';
    public $primaryKey = 'general_info_id';

    public function getTempGeneralInfoByBatchNo($batchNo) {
        $data = $this->find('all', array('conditions' => array('batch_no' => $batchNo)));
        return $this->formatData($data, 'Legacy_tmp_generalinformation', 'id');
    }
}

?>