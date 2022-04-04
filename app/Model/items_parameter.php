<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of items_parameter
 *
 * @author nic
 */
class items_parameter extends AppModel{
    //put your code here
       public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_items_parameter';
    
    function save_item_prameter($property,$usageitem,$last_prop_id,$token,$user_id,$user_type)
    {
     
         $param_code = $usageitem['usagelinkcategory']['uasge_param_code'];

                        $usagedata['item_id'] = $usageitem['usagelinkcategory']['usage_param_id'];
                        $usagedata['item_value'] = $property[$param_code . "_" . $usageitem['usagelinkcategory']['evalrule_id']];

                        if ($usageitem['itemlist']['area_field_flag'] == 'Y') { // if is set
                            $usagedata['unit_id'] = $property[$param_code . 'unit' . "_" . $usageitem['usagelinkcategory']['evalrule_id']];
                            if (isset($property[$param_code . 'areatype' . "_" . $usageitem['usagelinkcategory']['evalrule_id']])) {
                                $usagedata['areatype_id'] =$property[$param_code . 'areatype' . "_" . $usageitem['usagelinkcategory']['evalrule_id']];
                            }
                        }
                        $usagedata['property_id'] = $last_prop_id;
                        $usagedata['usage_cat_id'] = $usageitem['usagelinkcategory']['evalrule_id'];
                        $usagedata['token_id'] = $token;
                        $usagedata['user_id'] = $user_id;
                        $usagedata['user_type'] = $user_type;

                        if ($usagedata['item_value'] > 0) {
                            $this->create();
                            $this->save($usagedata);
                        }
                        return true;
    }
}
