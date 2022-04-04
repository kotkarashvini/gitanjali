<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DynamicVariablesController
 *
 * @author nic
 */
class DynamicVariablesController extends AppController {

    //put your code here

    public function veriables_sd($token = NULL, $data = array(), $Propertyid = NULL) {
        $this->loadModel('parameter');
//        try {
//           // $result = $this->parameter->query("select usage_param_code,count(trn.paramter_id) as total  FROM ngdrstab_trn_parameter as trn 
//            //            JOIN ngdrstab_mst_attribute_parameter mst ON mst.attribute_id=trn.paramter_id
//             //           where trn.token_id=? and parameter_type='S' group by usage_param_code ", array($token));
//
//						
//						 $result = $this->parameter->query("select usage_param_code,count(trn.paramter_id) as total,trn.paramter_value  FROM ngdrstab_trn_parameter as trn 
//                        JOIN ngdrstab_mst_attribute_parameter mst ON mst.attribute_id=trn.paramter_id
//                        where trn.token_id=? and parameter_type='S' group by usage_param_code ,paramter_value",array($token));
////            pr($result);exit;
//
//
//            foreach ($result as $single) {
//                $count = substr_count(trim($single[0]['paramter_value']), ' ');
//                $count1 = $count + 1;
//                $data[$single[0]['usage_param_code']] = $count1;
//            }
//           
//            return $data;
//        } catch (Exception $ex) {
//            return $data;
//        }
//    }

        //Multiple property some usage number of khata add shrishail and vishal
        try {
            $this->loadModel('parameter');
            
                if ($Propertyid == NULL) {

                    $result = $this->parameter->query("select usage_param_code,count(trn.paramter_id) as total,trn.paramter_value  FROM ngdrstab_trn_parameter as trn 
                        JOIN ngdrstab_mst_attribute_parameter mst ON mst.attribute_id=trn.paramter_id
                        where trn.token_id=? and parameter_type='S' group by usage_param_code ,paramter_value", array($token));


                    $temp = array();
                    foreach ($result as $single) {
                        // pr($single); 
                        if ($single[0]['usage_param_code'] == 'FBJ') {
                            $count = substr_count(trim($single[0]['paramter_value']), ' ');
                            $count1 = $count + 1;
                            array_push($temp, $count1);
                        }
                    }
                    $i = 0;
                    foreach ($temp as $key => $value) {
                        $i+=$value;
                    }

                    $data['FBJ'] = $i;

                    return $data;
                
            }else {
                    $result = $this->parameter->query("select usage_param_code,count(trn.paramter_id) as total,trn.paramter_value  FROM ngdrstab_trn_parameter as trn 
                        JOIN ngdrstab_mst_attribute_parameter mst ON mst.attribute_id=trn.paramter_id
                        where trn.token_id=? and parameter_type='S' and property_id =? group by usage_param_code ,paramter_value", array($token, $Propertyid));
                    foreach ($result as $single) {
                        // pr($single); 
                        if ($single[0]['usage_param_code'] == 'FBJ') {
                            $count = substr_count(trim($single[0]['paramter_value']), ' ');
                            $count1 = $count + 1;
                            $data[$single[0]['usage_param_code']] = $count1;
                        }
                    }
                    return $data;
                }
            
        } catch (Exception $ex) {
            return $data;
        }
    }

        
}
