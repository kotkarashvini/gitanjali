<?php
class fee_round_value extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_rounding_value';
    public $primaryKey = 'rounding_id';
    
     public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_rounding_value';
        $duplicate['PrimaryKey'] = 'rounding_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'rounding_desc_' . $language['mainlanguage']['language_code']);
        }
        
        array_push($fields, 'next_rounding_value');
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
        
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['rounding_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['rounding_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
         $fieldlist['next_rounding_value']['text'] = 'is_required,is_numeric'; 
          
//          $fieldlist['start_date']['text'] = 'is_required';
//            $fieldlist['end_date']['text'] = 'is_required';
//            $fieldlist['active_flag']['select'] = 'is_yes_no';
//            $fieldlist['verification_flag']['select'] = 'is_yes_no';
           

        return $fieldlist;
    }
    
}
