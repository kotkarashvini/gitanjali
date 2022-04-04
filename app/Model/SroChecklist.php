<?php class SroChecklist extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_sro_checklist';  
    public $primaryKey = 'checklist_id';
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_sro_checklist';
        $duplicate['PrimaryKey'] = 'checklist_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'checklist_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();


        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['checklist_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {  
                $fieldlist['checklist_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }

        return $fieldlist;
    }
}