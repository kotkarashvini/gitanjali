<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PropertyFields
 *
 * @author Admin1
 */
 

class PropertyFields extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_property_dependent_fields';
    public $primaryKey = 'field_id';
    
    public function fieldlist($ruleids = NULL, $lang = 'en', $LanTypeId = NULL) {
        //$a=$this->find("all");
      //  pr($a);
        //exit;
        $fieldlist = array();
        if (is_null($ruleids)) {
//            $usage_category = $this->find("all", array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id'),
//                'conditions' => array('evalrule_id' => $rulearr)
//            ));
            $sql = "select propfield.field_id,propfield.field_desc_en,propfield.field_desc_en ,is_required,vrule_en from ngdrstab_conf_property_dependent_fields_mapping  as  propfieldmap JOIN ngdrstab_conf_property_dependent_fields  as propfield ON propfield.field_id=propfieldmap.field_id";
            $PropertyFields = $this->query($sql);
            if (!empty($PropertyFields)) {
                foreach ($PropertyFields as $key => $Pattens) {
                    if (!empty($Pattens[0]['vrule_en'])) {
                        $fieldlist['field_en' . $Pattens[0]['field_id']]['text'] = $Pattens[0]['vrule_en'];
                    }
                    if ($lang != 'en') {
                        if (!empty($Pattens[0]['vrule_ll'])) {
                            $fieldlist['field_ll' . $Pattens[0]['field_id']]['text'] = $Pattens[0]['vrule_ll'];
                        }
                    }
                }
            }
        } else {
            $usage_category = ClassRegistry::init('usage_category')->find("all", array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id'),
                'conditions' => array('evalrule_id' => $ruleids)
            ));
            $sql = "select propfield.field_id,propfield.field_desc_en,propfield.field_desc_en ,is_required

from ngdrstab_conf_property_dependent_fields_mapping  as  propfieldmap
JOIN ngdrstab_conf_property_dependent_fields  as propfield ON propfield.field_id=propfieldmap.field_id

where  1=0 ";
            $flag = 0;
            if (!empty($usage_category)) {
                foreach ($usage_category as $usage) {
                    $usage = $usage['usage_category'];
                    if (is_numeric($LanTypeId) && is_numeric($usage['usage_main_catg_id']) && is_numeric($usage['usage_sub_catg_id'])) {
                        $flag = 1;
                        $sql = $sql . " OR  (developed_land_types_id=" . $LanTypeId . " and usage_main_catg_id=" . $usage['usage_main_catg_id'] . " and usage_sub_catg_id=" . $usage['usage_sub_catg_id'] . ")";
                    }
                }
            }
            if ($flag) {
                $PropertyFields = ClassRegistry::init('PropertyFields')->query($sql);
                if (!empty($PropertyFields)) {
                    foreach ($PropertyFields as $key => $Pattens) {
                        if (!empty($Pattens[0]['vrule_en'])) {
                            $fieldlist['field_en' . $Pattens[0]['field_id']]['text'] = $Pattens[0]['vrule_en'];
                        }
                        if ($lang != 'en') {
                            if (!empty($Pattens[0]['vrule_ll'])) {
                                $fieldlist['field_ll' . $Pattens[0]['field_id']]['text'] = $Pattens[0]['vrule_ll'];
                            }
                        }
                    }
                }
            }
        }
        return $fieldlist;
    }

}