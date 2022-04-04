<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class party_category_fields extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_party_category_fields';

    public function fieldlist($doc_lang, $tokenval, $article_id, $party_upload_flag, $category_id = NULL, $village_id = NULL) {
        try {
            $val_amt = $this->query("select sum(rounded_val_amt) ,token_no from ngdrstab_trn_valuation where token_no=$tokenval group by token_no");
//        pr($val_amt);
//        exit;
            $partyfields1 = $this->find('all', array('conditions' => array('display_flag' => 'Y', 'article_id' => array(9999, $article_id)), 'order' => 'order ASC'));
            $article = $this->query("select pan_applicable  from ngdrstab_mst_article where article_id=?", array($article_id));

//               if($party_upload_flag=='N'){
//            foreach ($partyfields1 as $f) {
//                $f = $f['party_category_fields'];
//
//                if ($f['condition_flag'] == 'Y') {
//                    if (count($val_amt) > 0) {
//                        if ($f['field_id_name_en'] == 'pan_no') {
//                            if (count($article) > 0) {
//                                if ($article[0][0]['pan_applicable'] == 'Y') {
//
//                                    if ($val_amt[0][0]['sum'] >= $f['condition_value']) {
//
//                                        $this->query("update ngdrstab_mst_party_category_fields set vrule_en='is_required,is_pancard', is_required='*' where field_id_name_en='" . $f['field_id_name_en'] . "'");
//                                    } else {
//                                        $this->query("update ngdrstab_mst_party_category_fields set vrule_en='is_pancard' , is_required='' where field_id_name_en='" . $f['field_id_name_en'] . "'");
//                                    }
//                                } else {
//                                    $this->query("update ngdrstab_mst_party_category_fields set vrule_en='is_pancard' , is_required='' where field_id_name_en='" . $f['field_id_name_en'] . "'");
//                                }
//                            }
//                        } 
//                    }
//                }
//            }
//}
            if (!is_null($category_id)) {

                $partyfields = $this->find('all', array('conditions' => array('display_flag' => 'Y', 'category_id' => $category_id, 'article_id' => array(9999, $article_id)), 'order' => 'order ASC'));
           
                foreach ($partyfields as $field) {
                    $field = $field['party_category_fields'];


                    if ($field['is_list'] == 'N') {
                        if (!empty($field['vrule_en'])) {
                            $fieldlist['party_entry'][$field['field_id_name_en']]['text'] = $field['vrule_en'];
                        }

                        if ($doc_lang == 'll') {
                            if (!empty($field['vrule_ll']))
                                $fieldlist['party_entry'][$field['field_id_name_ll']]['text'] = $field['vrule_ll'];
                        }
                    } else if ($field['is_list'] == 'Y') {
                        if (!empty($field['vrule_en']))
                            $fieldlist['party_entry'][$field['field_id_name_en']]['select'] = $field['vrule_en'];
                    }
                }
                
                
            } else {
                $partyfields = $this->find('all', array('conditions' => array('display_flag' => 'Y', 'article_id' => array(9999, $article_id)), 'order' => 'order ASC'));
            
                  foreach ($partyfields as $field) {
                    $field = $field['party_category_fields'];


                    if ($field['is_list'] == 'N') {
                        if (!empty($field['vrule_en'])) {
                            $fieldlist['party_entry'][$field['field_id_name_en'] . $field['category_id']]['text'] = $field['vrule_en'];
                        }

                        if ($doc_lang == 'll') {
                            if (!empty($field['vrule_ll']))
                                $fieldlist['party_entry'][$field['field_id_name_ll'] . $field['category_id']]['text'] = $field['vrule_ll'];
                        }
                    } else if ($field['is_list'] == 'Y') {
                        if (!empty($field['vrule_en']))
                            $fieldlist['party_entry'][$field['field_id_name_en'] . $field['category_id']]['select'] = $field['vrule_en'];
                    }
                }
                
                
            }

//            foreach ($partyfields as $field) {
//                $field = $field['party_category_fields'];
//                if ($field['is_checkbox_flag'] == 'Y' && $field['is_list'] == 'N') {
//                    if (!empty($field['vrule_en']))
//                        $fieldlist['party_entry'][$field['field_id_name_en']]['checkbox'] = $field['vrule_en'];
//                } else
//
//                if ($field['is_list'] == 'N' && $field['is_checkbox_flag'] == 'N') {
//                    if (!empty($field['vrule_en'])) {
//                        $fieldlist['party_entry'][$field['field_id_name_en']]['text'] = $field['vrule_en'];
//                    }
//
//                    if ($doc_lang == 'll') {
//                        if (!empty($field['vrule_ll']))
//                            $fieldlist['party_entry'][$field['field_id_name_ll']]['text'] = $field['vrule_ll'];
//                    }
//                } else if ($field['is_list'] == 'Y' && $field['is_checkbox_flag'] == 'N') {
//                    if (!empty($field['vrule_en']))
//                        $fieldlist['party_entry'][$field['field_id_name_en']]['select'] = $field['vrule_en'];
//                }
//            }


            $BehavioralPatterns = array();
            foreach ($partyfields as $field1) {
                if ($field1['party_category_fields']['field_id_name_en'] == 'village_id') {
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
                                $fieldlist['party_entry']['field_en' . $field['field_id']]['text'] = $field['vrule_en'];
                            }
                            if ($doc_lang == 'll') {
                                if (!empty($field['vrule_ll'])) {
                                    $fieldlist['party_entry']['field_ll' . $field['field_id']]['text'] = $field['vrule_ll'];
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
                                $fieldlist['party_entry']['field_en' . $field['field_id']]['text'] = $field['vrule_en'];
                            }
                            if ($doc_lang == 'll') {
                                if (!empty($field['vrule_ll'])) {
                                    $fieldlist['party_entry']['field_ll' . $field['field_id']]['text'] = $field['vrule_ll'];
                                }
                            }
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
//pr($fieldlist1);
//exit;
            return $fieldlist1;
        } catch (Exception $e) {
            
        }
    }

}
