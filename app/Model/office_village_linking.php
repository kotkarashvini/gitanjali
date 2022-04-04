<?php

class office_village_linking extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_office_village_linking';

    public function fieldlist($adminLevelConfig) {
        $fieldlist = array();

        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
            $fieldlist['division_id']['select'] = 'is_select_req';
        }
        $fieldlist['district_id']['select'] = 'is_select_req';
        if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
        $fieldlist['taluka_id']['select'] = 'is_select_req';
        if ($adminLevelConfig['adminLevelConfig']['is_circle'] == 'Y') {
            $fieldlist['circle_id']['select'] = 'is_select_req';
        }
        $fieldlist['corp_id']['select'] = 'is_select_req';
        $fieldlist['office_id']['select'] = 'is_select_req';
        $fieldlist['jurisdiction_flag']['select'] = 'is_alpha_select';
        

        return $fieldlist;
    }

}
