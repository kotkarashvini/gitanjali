<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrnPropertyFields
 *
 * @author nic
 */
class TrnPropertyFields extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_property_dependent_fields';

    public function deletepropertyfields($token, $property_id) {
//pr($token);pr($property_id);exit;
        $this->deleteAll(
                [
                    'property_id' => $property_id,
                    'token_no' => $token
        ]);
        // echo 1;exit;
        return true;
    }

    // propertyfields($token, $user_id, $last_prop_id, $this->request->data['property_fields'], $this->Session->read("session_usertype"));
    public function savepropertyfields($token, $user_id, $property_id, $data, $user_type) {

        foreach ($data['field_id'] as $key => $value) {
            $patterndata['field_id'] = $value;
            $patterndata['field_value_en'] = $data['field_value_en'][$key];
            if (isset($data['field_value_ll'][$key])) {
                $patterndata['field_value_ll'] = $data['field_value_ll'][$key];
            }
            $patterndata['token_no'] = $token;
            $patterndata['property_id'] = $property_id; // property            
            if ($user_type == 'C') {
                $patterndata['user_id'] = $user_id;
            } elseif ($user_type == 'O') {
                $patterndata['org_user_id'] = $user_id;
            }
            //$patterndata['state_id'] = $this->Auth->User("state_id");
            $patterndata['user_type'] = $user_type;

            if (!empty($patterndata['field_value_en'])) {
                $this->create();
                $this->save($patterndata);
            }
        }
        return true;
    }

}
