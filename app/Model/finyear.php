<?php

class finyear extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_finyear';
    public $primaryKey = 'finyear_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_finyear';
        $duplicate['PrimaryKey'] = 'finyear_id';

        $fields = array();
        array_push($fields, 'finyear_desc');
        array_push($fields, 'finyear_desc_short');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();

        $fieldlist['finyear_desc']['text'] = 'is_required'; 
        $fieldlist['finyear_desc_short']['text'] = 'is_required'; 
        $fieldlist['year_for_token']['text'] = 'is_required,is_tokenyear';

        $fieldlist['current_year']['text'] = 'is_yes_no';
        $fieldlist['display_flag']['text'] = 'is_yes_no';


        return $fieldlist;
    }

}
