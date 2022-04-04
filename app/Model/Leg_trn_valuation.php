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
class Leg_trn_valuation extends AppModel {

    //put your code here    
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_valuation';
    public $primaryKey = 'val_id';

    public function get_val_id($token_no) {
        $data = $this->query("SELECT max(val_id) FROM ngdrstab_trn_valuation
 WHERE token_no=?", array($token_no));


        return $data;
//    $string2 = $this->Property_details->query("SELECT max(val_id) FROM ngdrstab_trn_valuation where token_no=$token_no;");
    }

    function save_trn_valuation_parameter($prp_details, $last_prop_id, $token) {
        //$i=0;
       // pr($prp_details);exit;
        foreach ($prp_details as $key => $value) {
            
            $attributedata['usage_main_catg_id'] = $value['usage_main_catg_id'];
            $attributedata['usage_sub_catg_id'] = $value['usage_sub_catg_id'];
//            $attributedata['item_value'] = $value['item_value'];
//           
//            $attributedata['final_value'] = $value['final_value'];
            $attributedata['token_no'] = $token;
            $attributedata['property_id'] = $last_prop_id;
            // $attributedata['parameter_type'] = $type;
            // $attributedata['user_type'] = $user_type;

            //if (!empty($attributedata['usage_main_catg_id'])) {
                $this->create();
                $this->save($attributedata);
          //  }

          // $last_val_id = $this->getLastInsertID();
           // pr($token);pr($last_prop_id);exit;
            $val_id = $this->query('select val_id from ngdrstab_trn_legacy_valuation where token_no='.$token."and property_id=".$last_prop_id);
            $last_val_id=$val_id[0][0]['val_id'];
          // pr($last_val_id);exit;
           
           
           
          // $this->trn_valuation_details->save_trn_valuation_details_parameter($prp_details, $last_val_id);
           $this->save_trn_valuation_details_parameter($value, $last_val_id);
           //pr($prp_details);
           //$i=$i+1;
        }
        return true;
    }
    
      function save_trn_valuation_details_parameter($details,$last_val_id)
    {   
        
          
//          $attribute=$details;
//           $attribute['val_id'] = $last_val_id;
//        pr($attribute['usage_main_catg_id']);
                             $usage_main_catg_id= $details['usage_main_catg_id'];
                            $usage_sub_catg_id = $details['usage_sub_catg_id']; 
                             $item_value=$details['item_value'];
                              $area_unit=$details['area_unit'];
                            $final_value=$details['final_value'];
                            $consideration_amt=$details['consideration_amt'];
                            
                            $val_id= $last_val_id;
                            //if (!empty($attribute['usage_main_catg_id'])) {
                               // $this->create();
                               // $this->save($attribute); 
                                $instdetails = $this->query("insert into ngdrstab_trn_legacy_valuation_details(usage_main_catg_id,usage_sub_catg_id,item_value,area_unit,final_value,val_id,consideration_amt) values ('$usage_main_catg_id','$usage_sub_catg_id','$item_value','$area_unit','$final_value','$val_id','$consideration_amt') ");
                                //pr($instdetails);exit;
                                // $q = 'insert into ngdrstab_trn_valuation_details(usage_main_catg_id,usage_sub_catg_id,item_value,final_value,val_id) values ('$usage_main_catg_id','$usage_sub_catg_id','$item_value','$final_value','$val_id') ;
                            //}   
                 
        return true;
    }
    
    
     public function delete_valualation($token_no,$property_index_id)
    {
      
         $this->deleteAll(
                            [  'token_no' => $token_no, 
                                'property_id' => $property_index_id 
                    ]);
         return true;
    }
    
    
    
    

}
