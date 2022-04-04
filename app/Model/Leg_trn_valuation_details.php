<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of State
 *
 * @author Acer
 */
class Leg_trn_valuation_details extends AppModel {

    //put your code here    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_valuation_details';
  //  public $primaryKey = 'val_id';
    
    
      //---------------------------- by Prasmita--------------------------------------------------
    public function get_valuation_details_cake($lang = NULL, $property_id = NULL) {
        return $this->find('all', array('fields' => array('sub_catg.usage_sub_catg_desc_'.$lang,'Leg_trn_valuation_details.item_value', 'unit.unit_desc_'.$lang),
                    'conditions' => array('trn_valuation.property_id' => $property_id),
                    'joins' => array(
                       array('table' => 'ngdrstab_trn_legacy_valuation', 'type' => 'inner', 'alias' => 'trn_valuation', 'conditions' => array('trn_valuation.val_id=Leg_trn_valuation_details.val_id')),
                        array('table' => 'ngdrstab_mst_unit', 'type' => 'inner', 'alias' => 'unit', 'conditions' => array('unit.unit_id=Leg_trn_valuation_details.area_unit')),
                        array('table' => 'ngdrstab_mst_usage_sub_category', 'type' => 'inner', 'alias' => 'sub_catg', 'conditions' => array('sub_catg.usage_sub_catg_id=trn_valuation.usage_sub_catg_id'))
                       // array('table' => 'ngdrstab_conf_list_items', 'type' => 'left', 'alias' => 'list', 'conditions' => array('list.item_id=valuation_details.item_id AND list.item_desc_id=CAST(valuation_details.item_value as numeric)')),
                        //, 'valuation_details.item_type_id' => 1
        )));
    }
    
    
    public function get_market_value($lang = NULL, $property_id = NULL) {
        return $this->find('all', array('fields' => array('Leg_trn_valuation_details.final_value'),
                    'conditions' => array('trn_valuation.property_id' => $property_id),
                    'joins' => array(
                       array('table' => 'ngdrstab_trn_legacy_valuation', 'type' => 'inner', 'alias' => 'trn_valuation', 'conditions' => array('trn_valuation.val_id=Leg_trn_valuation_details.val_id')),
                       // array('table' => 'ngdrstab_mst_unit', 'type' => 'inner', 'alias' => 'unit', 'conditions' => array('unit.unit_id=valuation_details.area_unit')),
                       // array('table' => 'ngdrstab_mst_usage_sub_category', 'type' => 'inner', 'alias' => 'sub_catg', 'conditions' => array('sub_catg.usage_sub_catg_id=trn_valuation.usage_sub_catg_id'))
                       // array('table' => 'ngdrstab_conf_list_items', 'type' => 'left', 'alias' => 'list', 'conditions' => array('list.item_id=valuation_details.item_id AND list.item_desc_id=CAST(valuation_details.item_value as numeric)')),
                        //, 'valuation_details.item_type_id' => 1
        )));
    }

}