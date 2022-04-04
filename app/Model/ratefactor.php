<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ratefactor
 *
 * @author Administrator
 */
class ratefactor extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_rate_factor';
    public $primaryKey = 'rate_factor_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_rate_factor';
        $duplicate['PrimaryKey'] = 'rate_factor_id';
        $fields = array();
//        foreach ($languagelist as $language) {
            array_push($fields, 'constructiontype_id,depreciation_id');
//        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['constructiontype_id']['select'] = 'is_select_req';
        $fieldlist['depreciation_id']['select'] = 'is_select_req';
        $fieldlist['rate_factor']['text'] = 'is_required,is_numeric';
//        $fieldlist['rate_factor']['text'] = 'is_digit';
        return $fieldlist;
    }

}
