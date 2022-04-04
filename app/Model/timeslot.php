<?php

class timeslot extends AppModel{
    //put your code here
    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_timeslot';
    public $primaryKey='slot_id';
    
    public function get_duplicate() {
        $duplicate['Table'] = 'ngdrstab_mst_timeslot';
        $duplicate['PrimaryKey'] = 'slot_id';
        $fields = array();
            array_push($fields, 'slot_time_minute');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['slot_time_minute']['text'] = 'is_required,is_integer';
        return $fieldlist;
    }
    
}
