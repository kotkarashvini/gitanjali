<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class identification_fields extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_identifire_fields';

    public function fieldlist($doc_lang, $village_id = NULL) {


        $partyfields = $this->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));


        foreach ($partyfields as $field) {
            $field = $field['identification_fields'];

            if ($field['is_list'] == 'N') {
                if (!empty($field['vrule_en'])) {
                    $fieldlist[$field['field_id_name_en']]['text'] = $field['vrule_en'];
                }

                if ($doc_lang == 'll') {
                    if (!empty($field['vrule_ll']))
                        $fieldlist[$field['field_id_name_ll']]['text'] = $field['vrule_ll'];
                }
            } else if ($field['is_list'] == 'Y') {
                if (!empty($field['vrule_en']))
                    $fieldlist[$field['field_id_name_en']]['select'] = $field['vrule_en'];
            }
        }
        $BehavioralPatterns = array();
        if (!is_null($village_id)) {
            $villagedetails = $this->query("select developed_land_types_id from ngdrstab_conf_admblock7_village_mapping where village_id=$village_id");

            if (!empty($villagedetails)) {
                $land_type = $villagedetails[0][0]['developed_land_types_id'];

                if ($land_type == '1') {
                    $land_flag = "U";
                } else if ($land_type == '2') {
                    $land_flag = "R";
                } else if ($land_type == '3') {
                    $land_flag = "I";
                } else {
                    $land_flag = "U";
                }

                //usage_id



                $BehavioralPatterns = $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=2 AND behavioral.behavioral_id=details.behavioral_id  AND details.developed_land_types_flag='$land_flag' ");
            }

            foreach ($BehavioralPatterns as $field) {
                $field = $field['0'];
                if (!empty($field['vrule_en'])) {
                    $fieldlist['field_en' . $field['field_id']]['text'] = $field['vrule_en'];
                }
                if ($doc_lang == 'll') {
                    if (!empty($field['vrule_ll'])) {
                        $fieldlist['field_ll' . $field['field_id']]['text'] = $field['vrule_ll'];
                    }
                }
            }
            $fieldlist1 = array();
            foreach ($fieldlist as $key => $value) {
                if (isset($key) && $key != '') {

                    $fieldlist1[$key] = $value;
                }
            }
        } else {
            $BehavioralPatterns = $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=2 AND behavioral.behavioral_id=details.behavioral_id ");


//            foreach ($BehavioralPatterns as $field) {
//                $field = $field['0'];
//                 if(!empty($field['vrule_en'])){
//                $fieldlist['field_en' . $field['field_id']]['text'] = $field['vrule_en'];
//                 }
//                   if ($doc_lang =='ll') {
//                       if(!empty($field['vrule_ll'])){
//                $fieldlist['field_ll' . $field['field_id']]['text'] = $field['vrule_ll'];
//                       }
//                   }
//            }
            $fieldlist1 = array();
            if (!empty($fieldlist)){
            foreach ($fieldlist as $key => $value) {
                if (isset($key) && $key != '') {

                    $fieldlist1[$key] = $value;
                }
            }
            }
        }

        return $fieldlist1;
    }

}
