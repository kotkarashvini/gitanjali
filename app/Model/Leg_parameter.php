<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of parameter
 *
 * @author nic
 */
class Leg_parameter extends AppModel{
    //put your code here
    
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_parameter';
    
    function save_parameter($prp_attr,$last_prop_id,$token,$user_id,$type, $user_type)
    {        
      
             foreach ($prp_attr as $key => $value) {
                
                            $attributedata['paramter_id'] = $key;
                            $attributedata['paramter_value'] = $value['attribute_value']; 
                            $attributedata['paramter_value1'] = $value['attribute_value1'];
                            $attributedata['paramter_value2'] = $value['attribute_value2'];
                            $attributedata['token_id'] = $token;
                            $attributedata['property_id'] = $last_prop_id;
                            $attributedata['parameter_type'] = $type;
                            $attributedata['user_type'] = $user_type;

                            if (!empty($attributedata['paramter_value'])) {
                                $this->create();
                                $this->save($attributedata); 
                            }
                            
                  }            
                            
       
        return true;
    }
    
    function get_land_type($id)
    {
       return $this->query('select v.developed_land_types_id ,v.census_code from ngdrstab_conf_admblock7_village_mapping v,ngdrstab_trn_property_details_entry p
                                    where p.village_id=v.village_id and p.property_id=?',array($id));
    }
    
    function get_property_parameter($property_id,$state_id,$type)
    {
        if($type==1)
        {
            $t='S';
        }
        else if($type==2)
        {
           $t='P'; 
        }
      
       return $this->query("select p.paramter_value,a.eri_attribute_name,a.mapping_name,a.compulsary_used  from ngdrstab_trn_parameter p,ngdrstab_mst_attribute_parameter a
                                 where p.paramter_id=a.attribute_id and a.lr_mapping='Y' and p.property_id=? and a.state_id=? and p.parameter_type=?",array($property_id,$state_id,$t));

    }
    
    
      function save_attribute_parameter($prp_attr,$last_prop_id,$token)
    {   
             foreach ($prp_attr as $key => $value) {                
                            $attributedata['paramter_id'] = $value['paramter_id'];
                            $attributedata['paramter_value'] = $value['paramter_value']; 
                            $attributedata['paramter_value1'] =@$value['paramter_value1'];
                            $attributedata['paramter_value2'] =@$value['paramter_value2'];
                            $attributedata['token_id'] = $token;
                            $attributedata['property_id'] = $last_prop_id;
                           // $attributedata['parameter_type'] = $type;
                           // $attributedata['user_type'] = $user_type;

                            if (!empty($attributedata['paramter_value'])) {
                                $this->create();
                                $this->save($attributedata); 
                            }                            
                  } 
        return true;
    }
}
