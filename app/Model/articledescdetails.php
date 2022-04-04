<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of articledescdetails
 *
 * @author nic
 */
class articledescdetails extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_articledescriptiondetail';
    public $primaryKey = 'articledescription_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_articledescriptiondetail';
        $duplicate['PrimaryKey'] = 'articledescription_id';

        $fields = array();
        foreach ($languagelist as $language) {
            //  array_push($fields, 'holiday_fdate,district_id,holiday_type_id,articledescription_' . $language['mainlanguage']['language_code']);
            array_push($fields, 'articledescription_' . $language['mainlanguage']['language_code']);
        }

        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        $fieldlist['article_id']['select'] = 'is_select_req';
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['articledescription_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacecommaroundbrackets';
            } else {
                $fieldlist['articledescription_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
            
            $fieldlist['book_number']['text'] = 'is_required,is_alphanumeric';
        }
        return $fieldlist;
    }

}
