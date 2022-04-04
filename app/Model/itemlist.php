<?php

class itemlist extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_usage_items_list';
    public $primaryKey = 'usage_param_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_usage_items_list';
        $duplicate['PrimaryKey'] = 'usage_param_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'usage_param_type_id,usage_param_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $data = NULL) {

        $fieldlist = array();
        $vrule=array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['usage_param_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['usage_param_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        $fieldlist['fieldtype']['select'] = 'is_select_req'; 
        $fieldlist['unit_cat_id']['select'] = 'is_select_req';
        $fieldlist['single_unit_flag']['select'] = 'is_yes_no'; 
        $fieldlist['item_rate_flag']['select'] = 'is_yes_no';
        $fieldlist['output_item_id']['select'] = 'is_select_req'; 
        $fieldlist['area_type_flag']['select'] = 'is_yes_no';      
        $fieldlist['is_input_hidden']['select'] = 'is_yes_no';

        if (!is_null($data)) {
            if (isset($data['fieldtype']) && $data['fieldtype'] == 1) { 
                $data['area_field_flag']='Y';
                $data['is_list_field_flag']='N';
                $data['is_string']='N';  
                
                $vrule['vrule_input']='is_required,is_numeric'; 
                $vrule['vrule_unit']='is_select_req'; 
                if($data['area_type_flag']=='Y'){
                     $vrule['vrule_areatype']='is_select_req'; 
                }                
            }
             if (isset($data['fieldtype']) && $data['fieldtype'] == 2) { 
                $data['area_field_flag']='N';
                $data['is_list_field_flag']='Y';
                $data['is_string']='N';   
                
                unset($fieldlist['unit_cat_id']);
                unset($data['unit_cat_id']);
                
                $data['single_unit_flag']='N';
                $data['area_type_flag']='N';
                
                $vrule['vrule_input']='is_select_req';  
            }
            
              if (isset($data['fieldtype']) && $data['fieldtype'] == 3) { 
                $data['area_field_flag']='N';
                $data['is_list_field_flag']='N';
                $data['is_string']='N';  
                
                unset($fieldlist['unit_cat_id']);
                unset($data['unit_cat_id']);
                
                $data['single_unit_flag']='N';
                $data['area_type_flag']='N';
                
                $vrule['vrule_input']='is_required,is_numeric';  
            }
            
            
             if (isset($data['fieldtype']) && $data['fieldtype'] == 4) { 
                $data['area_field_flag']='N';
                $data['is_list_field_flag']='N';
                $data['is_string']='Y';  
                
                unset($fieldlist['unit_cat_id']);
                unset($data['unit_cat_id']);
                
                $data['single_unit_flag']='N';
                $data['area_type_flag']='N';
                $vrule['vrule_input']='is_required,is_alphanumericspace';  
                
            }
           
            
            if (isset($data['item_rate_flag']) && $data['item_rate_flag'] == 'N') {
                unset($fieldlist['output_item_id']);
                unset($data['output_item_id']);
                
            }
        }


        $response['fieldlist']=$fieldlist;
        $response['data']=$data; 
        $response['vrule']=$vrule; 
        return $response;
    }

    
    public function fieldlist_output($languagelist) {
        $fieldlist = array();       
           
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['usage_param_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['usage_param_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        } 
       $fieldlist['display_order']['text'] = 'is_required,is_integer'; 
        return $fieldlist;
       
    }

}
