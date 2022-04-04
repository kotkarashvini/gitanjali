<?php

class valuationsubzone extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_valuation_subzone';
    var $virtualFields = array(
    'name' => "CONCAT(from_desc_en, '-', to_desc_en)"
);

}
