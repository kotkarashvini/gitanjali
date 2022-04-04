<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of role
 *
 * @author Administrator
 */
class roadvicinity extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_road_vicinity';
    public $primaryKey='road_vicinity_id'; 

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_road_vicinity';
        $duplicate['PrimaryKey'] = 'road_vicinity_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'road_vicinity_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['road_vicinity_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacecommaroundbrackets';
            } else {
                $fieldlist['road_vicinity_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        return $fieldlist;
    }

}
