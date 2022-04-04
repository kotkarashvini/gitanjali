<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of valuation_details
 *
 * @author Administrator
 */
class valuation_details extends AppModel {

    //put your code here.

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_valuation_details';

//---------------------------- by Shridhar---Modified on 22-May-2017-----------------------------------------------
//    public function getValuationDetail($val_id, $item_type_id, $rule_id, $lang) {
//        $conditions = ($item_type_id == 1) ? 'cast(vd.item_value as numeric)!= 0 ' : (($item_type_id == 2) ? 'cast(vd.final_value as numeric)!= 0 ' : -1);
//        if ($conditions == -1) {
//            return 'Wrong Input';
//        }
//        return $this->Query(" select   itemlink.display_order,unit.unit_id, unit.unit_desc_$lang,area_type.rate_built_area_type_desc_$lang,il.usage_param_type_id,vd.*,
//	    il.is_list_field_flag,listdesc.item_desc_$lang, il.usage_param_desc_$lang
//            from ngdrstab_trn_valuation_details vd
//            left outer join ngdrstab_conf_list_items listdesc on listdesc.item_desc_id=cast(vd.item_value as numeric) and listdesc.item_id=vd.item_id
//            left outer join ngdrstab_mst_usage_items_list il on il.usage_param_id = vd.item_id 
//            
//            join ngdrstab_mst_usage_category ucat on ucat.evalrule_id=vd.rule_id 
//            left outer join ngdrstab_mst_unit unit on unit.unit_id=vd.area_unit
//            LEFT JOIN ngdrstab_mst_usage_lnk_category itemlink ON itemlink.usage_param_id=il.usage_param_id and itemlink.evalrule_id=?
//           
//            left outer join ngdrstab_mst_rate_built_area_type area_type on area_type.rate_built_area_type_id=vd.area_type
//            where  vd.val_id=? and vd.item_type_id=?  and vd.rule_id=? and $conditions order by itemlink.display_order,vd.rule_id, vd.item_type_id, vd.item_id", array($rule_id,$val_id, $item_type_id, $rule_id));
//  }
    public function getValuationDetail($val_id, $item_type_id, $rule_id, $lang) {
        $conditions = ($item_type_id == 1) ? ' 1=1 ' : (($item_type_id == 2) ? 'cast(vd.final_value as numeric)!= 0 ' : -1);
        if ($conditions == -1) {
            return 'Wrong Input';
        }
        return $this->Query("Select DISTINCT unit.unit_id, unit.unit_desc_$lang,area_type.rate_built_area_type_desc_$lang,il.area_type_flag,il.usage_param_type_id,il.is_string,il.usage_param_code,vd.*,
                il.is_list_field_flag,listdesc.item_desc_$lang, il.usage_param_desc_$lang,il.area_field_flag
           from ngdrstab_trn_valuation_details vd
           left outer join ngdrstab_conf_list_items listdesc on listdesc.item_desc_id=cast(vd.item_value as numeric) and listdesc.item_id=vd.item_id
           left outer join ngdrstab_mst_usage_items_list il on il.usage_param_id = vd.item_id  
            join ngdrstab_mst_usage_category ucat on ucat.evalrule_id=vd.rule_id
           left outer join ngdrstab_mst_unit unit on unit.unit_id=vd.area_unit
           left outer join ngdrstab_mst_rate_built_area_type area_type on area_type.rate_built_area_type_id=vd.area_type
           where  vd.val_id=? and vd.item_type_id=?  and vd.rule_id=? and $conditions order by vd.rule_id, vd.item_type_id, vd.item_id", array($val_id, $item_type_id, $rule_id));
    }

    public function getValuationDetail_all($val_id, $item_type_id, $rule_id, $lang) {

        return $this->Query("Select DISTINCT unit.unit_id, unit.unit_desc_$lang,area_type.rate_built_area_type_desc_$lang,il.usage_param_type_id,il.usage_param_code,vd.*,
                il.is_list_field_flag,listdesc.item_desc_$lang, il.usage_param_desc_$lang,il.area_field_flag,il.is_string
           from ngdrstab_trn_valuation_details vd
           left outer join ngdrstab_conf_list_items listdesc on listdesc.item_desc_id=cast(vd.item_value as numeric) and listdesc.item_id=vd.item_id
           left outer join ngdrstab_mst_usage_items_list il on il.usage_param_id = vd.item_id  
            join ngdrstab_mst_usage_category ucat on ucat.evalrule_id=vd.rule_id
           left outer join ngdrstab_mst_unit unit on unit.unit_id=vd.area_unit
           left outer join ngdrstab_mst_rate_built_area_type area_type on area_type.rate_built_area_type_id=vd.area_type
           where  vd.val_id=? and vd.item_type_id=?  and vd.rule_id=?  order by vd.rule_id, vd.item_type_id, vd.item_id", array($val_id, $item_type_id, $rule_id));
    }

//---------------------------- by Shridhar--------------------------------------------------
    public function get_valuation_details_cake($lang = NULL, $val_id = NULL) {
        return $this->find('all', array('fields' => array('DISTINCT valuation_details.item_id', 'item.usage_param_desc_' . $lang, 'item.is_list_field_flag', 'valuation_details.item_value', 'valuation_details.area_unit', 'unit.unit_desc_' . $lang, 'list.item_desc_' . $lang, 'valuation_details.area_type', 'valuation_details.rule_id'),
                    'conditions' => array('val_id' => $val_id, 'valuation_details.item_type_id' => 1),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_usage_items_list', 'type' => 'left', 'alias' => 'item', 'conditions' => array('item.usage_param_id=valuation_details.item_id')),
                        array('table' => 'ngdrstab_mst_unit', 'type' => 'left', 'alias' => 'unit', 'conditions' => array('unit.unit_id=valuation_details.area_unit')),
                        array('table' => 'ngdrstab_conf_list_items', 'type' => 'left', 'alias' => 'list', 'conditions' => array('list.item_id=valuation_details.item_id AND list.item_desc_id=CAST(valuation_details.item_value as numeric)')),
        )));
    }

//---------------------------------------------------------------------------------------------
}
