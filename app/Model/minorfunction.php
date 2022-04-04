<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of minorfunction
 *
 * @author Nicsi
 */
class minorfunction extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_minorfunctions';
    public $primaryKey = 'minor_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_minorfunctions';
        $duplicate['PrimaryKey'] = 'minor_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'function_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();

        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['function_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['function_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        // $fieldlist['controller']['text'] = 'is_required,is_alphaunderscore';
        // $fieldlist['action']['text'] = 'is_required,is_alphaunderscore';
        $fieldlist['mf_serial']['text'] = 'is_required,is_digit';
        $fieldlist['dispaly_flag']['select'] = 'is_alpha_select';
        $fieldlist['status_flag']['select'] = 'is_yes_no';

        $fieldlist['citizen_flag']['select'] = 'is_yes_no';
        $fieldlist['sro_menu_flag']['select'] = 'is_yes_no';
        $fieldlist['manual_reg_flag']['select'] = 'is_yes_no';

        $fieldlist['e_reg_menu']['select'] = 'is_yes_no';
        $fieldlist['e_filing_flag']['select'] = 'is_yes_no';
        $fieldlist['leave_licence_flag']['select'] = 'is_yes_no';

        $fieldlist['cidco_citizen_flag']['select'] = 'is_yes_no';
        $fieldlist['delete_flag']['select'] = 'is_yes_no';


        return $fieldlist;
    }

    public function get_duplicate_dev($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_minorfunctions';
        $duplicate['PrimaryKey'] = 'minor_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'function_desc_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'id');
        array_push($fields, 'function_desc');
        
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }
    public function fieldlist_dev($languagelist) {
        $fieldlist = array();

        $fieldlist['id']['text'] = 'is_required,is_positiveinteger';
        $fieldlist['function_desc']['text'] = 'is_required,is_alphanumericspace';
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['function_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['function_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        $fieldlist['controller']['text'] = 'is_required,is_alphaunderscore';
        $fieldlist['action']['text'] = 'is_required,is_alphaunderscore';
        $fieldlist['mf_serial']['text'] = 'is_required,is_positiveinteger';
        $fieldlist['dispaly_flag']['select'] = 'is_alpha_select';
        $fieldlist['status_flag']['select'] = 'is_yes_no';

        $fieldlist['citizen_flag']['select'] = 'is_yes_no';
        $fieldlist['sro_menu_flag']['select'] = 'is_yes_no';
        $fieldlist['manual_reg_flag']['select'] = 'is_yes_no';

        $fieldlist['e_reg_menu']['select'] = 'is_yes_no';
        $fieldlist['e_filing_flag']['select'] = 'is_yes_no';
        $fieldlist['leave_licence_flag']['select'] = 'is_yes_no';

        $fieldlist['cidco_citizen_flag']['select'] = 'is_yes_no';
        $fieldlist['delete_flag']['select'] = 'is_yes_no';


        return $fieldlist;
    }

}
