<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getDropdown
 *
 * @author Administrator
 */
class fillDropdown extends AppModel {

    //put your code here

     public function getdropdown($dropdown_id = NULL, $arrparam = NULL) {

        App::uses('CakeSession', 'Model/Datasource');
        $lang = CakeSession::read('sess_langauge');
        if (is_null($lang)) {
            $lang = 'en';
        }
        $result = NULL;
        if ($dropdown_id == 'party_type') {
            $result = ClassRegistry::init('partytype')->find('list', array('fields' => array('party_type_id', 'party_type_desc_' . CakeSession::read('sess_langauge'))));
        } else if ($dropdown_id == 'district_id') {
            $result = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_en')));
        } else if ($dropdown_id == 'state_id') {
            $result = ClassRegistry::init('State')->find('list', array('fields' => array('id', 'state_name_en')));
        } else if ($dropdown_id == 'govt_nongovt') {
            $result = array(1 => 'Government', 2 => 'Non Government');
        } else if ($dropdown_id == 'id_proof') {
            $result = array(1 => 'Adhar Card', 2 => 'Driving Licence');
        } else if ($dropdown_id == 'attribute_type') {
            $result = ClassRegistry::init('attributetype')->find('list', array('fields' => array('attribute_type_id', 'attribute_type_desc_' . CakeSession::read('sess_langauge'))));
        } else if ($dropdown_id == 'hadd_type') {
            $result = ClassRegistry::init('haddtype')->find('list', array('fields' => array('hadd_type_id', 'hadd_type_desc_' . CakeSession::read('sess_langauge'))));
        } else if ($dropdown_id == 'hadd_name') {
            $result = ClassRegistry::init('haddname')->find('list', array('fields' => array('haddname_id', 'haddname_' . $lang)));
        } else if ($dropdown_id == 'area_unit') {
            $result = ClassRegistry::init('areaunit')->find('list', array('fields' => array('unit_id', 'unit_desc_' . CakeSession::read('sess_langauge'))));
        } else if ($dropdown_id == 'village_id') {
            $result = ClassRegistry::init('villagemapping')->find('list', array('fields' => array('village_id', 'village_name_' . $lang)));
        } else if ($dropdown_id == 'taluka_id') {
            $result = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang)));
        } else if ($dropdown_id == 'unit') {

            if ($arrparam['single_unit_flag'] == 'N') {
                $usage = ClassRegistry::init('usagelnk')->find('first', array('fields' => array('evalrule_id', 'usage_main_catg_id', 'usage_sub_catg_id'), 'conditions' => array('evalrule_id' => $arrparam['evalrule_id'])));
                if (isset($usage['usagelnk']['evalrule_id'])) {
                    $result = ClassRegistry::init('UnitMapping')->find('list', array(
                        'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                        'joins' => array(
                            array(
                                'table' => 'ngdrstab_mst_unit',
                                'alias' => 'unit',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'unit.unit_id=UnitMapping.unit_id'
                                )
                            ),
                        ),
                        'conditions' => array(
                            'UnitMapping.usage_main_catg_id' => $usage['usagelnk']['usage_main_catg_id'],
                            'UnitMapping.usage_sub_catg_id' => $usage['usagelnk']['usage_sub_catg_id'],
                            'UnitMapping.district_id' => $arrparam['district_id'],
                            'unit.unit_cat_id' => $arrparam['unit_cat_id'],
                        ), 'order' => 'sr_no ASC'
                            )
                    );
                    if (empty($result)) {

                        $result = ClassRegistry::init('UnitMapping')->find('list', array(
                            'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                            'joins' => array(
                                array(
                                    'table' => 'ngdrstab_mst_unit',
                                    'alias' => 'unit',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'unit.unit_id=UnitMapping.unit_id'
                                    )
                                ),
                            ),
                            'conditions' => array(
                                'UnitMapping.usage_main_catg_id' => $usage['usagelnk']['usage_main_catg_id'],
                                'UnitMapping.usage_sub_catg_id' => $usage['usagelnk']['usage_sub_catg_id'],
                                'unit.unit_cat_id' => $arrparam['unit_cat_id'],
                            ), 'order' => 'sr_no ASC'
                                )
                        );
                    }
                }
            } else if ($arrparam['districtwise_unit_change_flag'] == 'Y') {

                $result = ClassRegistry::init('UnitMappingItem')->find('list', array(
                    'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                    'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_unit',
                            'alias' => 'unit',
                            'type' => 'INNER',
                            'conditions' => array(
                                'unit.unit_id=UnitMappingItem.unit_id'
                            )
                        ),
                    ),
                    'conditions' => array(
                        'UnitMappingItem.district_id' => $arrparam['district_id'],
                        'unit.unit_cat_id' => $arrparam['unit_cat_id'],
                    )
                        )
                );
                if (empty($result)) {
                    $result = ClassRegistry::init('unit')->find('list', array(
                        'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                        'conditions' => array(
                            'unit.unit_id' => $arrparam['unit_id'],
                    )));
                }
            } else {
                $result = ClassRegistry::init('unit')->find('list', array(
                    'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                    'conditions' => array(
                        'unit.unit_id' => $arrparam['unit_id'],
                )));
            }
        } else if ($dropdown_id == 'areatype') {
            $result = ClassRegistry::init('areatype')->find('list', array('fields' => array('rate_built_area_type_id', 'rate_built_area_type_desc_' . $lang)));
        } else {
            $result = NULL;
        }

        return $result;
    }

    public function getradiobuttonlist($radiobutton_id = NULL) {

        App::uses('CakeSession', 'Model/Datasource');
        if ($radiobutton_id == 'sex') {
            $result = array('M' => 'Male', 'F' => 'Female', 'O' => 'Other');
        } else if ($radiobutton_id == 'urban_rural') {
            $result = array('1' => 'Urban', '2' => 'Rural');
        } else if ($radiobutton_id == 'address_otherdetails') {
            $result = array('1' => 'Property Address', '2' => 'Property Other Details');
        } else {
            $result = array('1' => 'Option 1', '2' => 'Option 2');
        }

        return $result;
    }

}
