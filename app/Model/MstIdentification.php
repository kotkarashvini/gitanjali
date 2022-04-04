<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MstIdentification extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_identifier';
    public $primaryKey = 'identification_id';

    public function fieldlist($lang, $village_id = NULL, $languagelist) {


        $partyfields = ClassRegistry::init('identification_fields')->find('all', array('conditions' => array('display_flag' => 'Y'), 'order' => 'order ASC'));


        foreach ($partyfields as $field) {
            $field = $field['identification_fields'];

            if ($field['is_list'] == 'N') {
                if (!empty($field['vrule_en'])) {
                    $fieldlist[$field['field_id_name_en']]['text'] = $field['vrule_en'];
                }

                //   if ($lang == 'll') {
                if (!empty($field['vrule_ll'])) {
                    foreach ($languagelist as $singlelang) {
                        if ($singlelang['mainlanguage']['language_code'] != 'en') {
                            $fieldarr = explode("_", $field['field_id_name_ll']);
                            $field_name = "";
                            for ($i = 0; $i <= count($fieldarr) - 2; $i++) {
                                $field_name = $field_name . $fieldarr[$i] . "_";
                            }
                            // pr();exit;
                            $field_name .= $singlelang['mainlanguage']['language_code'];
                            if (!empty($field['is_required'])) {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_1to255";
                            } else {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_0to255";
                            }//  pr($field_name);
                        }
                        //exit;
                    }
                }
                //  }
            } else if ($field['is_list'] == 'Y') {
                if (!empty($field['vrule_en']))
                    $fieldlist[$field['field_id_name_en']]['select'] = $field['vrule_en'];
            }
        }
        $BehavioralPatterns = array();
        if (!is_null($village_id)) {
            $villagedetails = $this->query("select developed_land_types_id from ngdrstab_conf_admblock7_village_mapping where village_id=$village_id");


            $land_type = $villagedetails[0][0]['developed_land_types_id'];

            if ($land_type == '1') {
                $land_flag = "U";
            } else if ($land_type == '2') {
                $land_flag = "R";
            } else if ($land_type == '3') {
                $land_flag = "I";
            }

            //usage_id



            $BehavioralPatterns = $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=2 AND behavioral.behavioral_id=details.behavioral_id  AND details.developed_land_types_flag='$land_flag' ");


            foreach ($BehavioralPatterns as $field) {
                $field = $field['0'];
                if (!empty($field['vrule_en'])) {
                    $fieldlist['field_en_' . $field['field_id']]['text'] = $field['vrule_en'];
                }

                if (!empty($field['vrule_ll'])) {
                    foreach ($languagelist as $singlelang) {
                        if ($singlelang['mainlanguage']['language_code'] != 'en') {
                            // pr($field);exit;
                            $field_name = 'field_' . $singlelang['mainlanguage']['language_code'] ."_". $field['field_id'];
                            if (!empty($field['is_required'])) {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_1to255";
                            } else {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_0to255";
                            }//  pr($field_name);
                        }
                        //exit;
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


            foreach ($BehavioralPatterns as $field) {
                $field = $field['0'];
                if (!empty($field['vrule_en'])) {
                    $fieldlist['field_en_' . $field['field_id']]['text'] = $field['vrule_en'];
                }
                if (!empty($field['vrule_ll'])) {
                    foreach ($languagelist as $singlelang) {
                        if ($singlelang['mainlanguage']['language_code'] != 'en') {
                            // pr($field);exit;
                            $field_name = 'field_' . $singlelang['mainlanguage']['language_code'] ."_". $field['field_id'];
                            if (!empty($field['is_required'])) {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_1to255";
                            } else {
                                $fieldlist[$field_name]['text'] = 'unicoderequired_rule_' . $singlelang['mainlanguage']['language_code'] . "," . "minmaxlength_unicode_0to255";
                            }//  pr($field_name);
                        }
                        //exit;
                    }
                }
            }
            $fieldlist1 = array();
            foreach ($fieldlist as $key => $value) {
                if (isset($key) && $key != '') {

                    $fieldlist1[$key] = $value;
                }
            }
        }

        return $fieldlist1;
    }
    
    public function Identifirelist($lang,$office_id){
        $identifirelist = $this->find("all", array('fields' => array('salutation.salutation_desc_'.$lang, 'MstIdentification.*'),
                    'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_salutation', 'alias' => 'salutation', 'conditions' => array('salutation.salutation_id=MstIdentification.salutation'),
//                           'table' => 'ngdrstab_mst_occupation', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=MstIdentification.occupation_id')
                            )
                    ),'conditions' => array('office_id' => $office_id)));
                return $identifirelist;
    }
//  'table' => 'ngdrstab_mst_occupation', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=MstIdentification.salutation_id')
}
