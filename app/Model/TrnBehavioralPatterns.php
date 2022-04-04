<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TrnBehavioralPatterns extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_behavioral_patterns';
    //------------------------by Shridhar-----------------------------------------
     public function get_pattern_detail($lang = NULL, $prop_id = NULL, $doc_token_no = NULL, $ref_id = NULL,$doc_lang='en') {
        return $this->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_' . $lang,'pattern.field_id', 'TrnBehavioralPatterns.field_value_' . $doc_lang,'TrnBehavioralPatterns.mapping_ref_val'),
                    'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $prop_id, 'TrnBehavioralPatterns.token_no' => $doc_token_no, 'TrnBehavioralPatterns.mapping_ref_id' => $ref_id), // for property:mapping_ref_id => 1
                    'joins' => array(
//                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id AND pattern.behavioral_id=TrnBehavioralPatterns.mapping_ref_id')),
                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                    ),
        'order'=>'pattern.field_id DESC'    
        ));
    }
    //------------------------------------------------------------------------
    public function deletepattern($token,$user_id,$id,$ref_id)
    {
      
         $this->deleteAll(
                            [  'mapping_ref_id' => $ref_id,
                                'mapping_ref_val' => $id,
                                'token_no' => $token 
                    ]);
         return true;
    }
    public function savepattern($token,$user_id,$id,$data,$ref_id, $user_type)
    {
     
         foreach ($data['pattern_id'] as $key => $value) {
                            $patterndata['field_id'] = $value;
                            $patterndata['field_value_en'] = $data['pattern_value_en'][$key];
                            if (isset($data['pattern_value_ll'][$key])) {
                                $patterndata['field_value_ll'] =$data['pattern_value_ll'][$key];
                            }
                            $patterndata['token_no'] = $token;

                            $patterndata['mapping_ref_id'] = $ref_id; // property
                            $patterndata['mapping_ref_val'] = $id;
                            $patterndata['user_id'] = $user_id;
                             $patterndata['user_type'] = $user_type;
                             
                            if (!empty($patterndata['field_value_en'])) {

                                $this->create();
                                $this->save($patterndata);
                                //pr($patterndata);
                            }
                        }
                    return true;
    }
//------------------------by Shrishail-----------------------------------------
    public function mst_pattern_detail($lang = NULL, $ref_id = NULL,$ref_val=NULL) {
        return $this->find('all', array('fields' => array('DISTINCT pattern.pattern_desc_' . $lang, 'pattern.field_id', 'TrnBehavioralPatterns.field_value_' . $lang, 'TrnBehavioralPatterns.mapping_ref_val'),
                    'conditions' => array('TrnBehavioralPatterns.mapping_ref_val' => $ref_val, 'TrnBehavioralPatterns.token_no' => NULL, 'TrnBehavioralPatterns.mapping_ref_id' => $ref_id), // for property:mapping_ref_id => 1
                    'joins' => array(
//                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id AND pattern.behavioral_id=TrnBehavioralPatterns.mapping_ref_id')),
                        array('table' => 'ngdrstab_conf_behavioral_patterns', 'type' => 'left', 'alias' => 'pattern', 'conditions' => array('pattern.field_id=TrnBehavioralPatterns.field_id')),
                    ),
                    'order' => 'pattern.field_id DESC'
        ));
    }
}