<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class BehavioralPattens extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_behavioral_patterns';
     public $primaryKey = 'pattern_id'; 
    
    function get_behavioral_pattern($behavioral_id)
    {
      return $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and details.behavioral_id=? AND behavioral.behavioral_id=details.behavioral_id order by patterns.pattern_id",array($behavioral_id)); 
    }
     function fieldlist($behavioral_id, $doclang = 'en', $rulelist = NULL, $village_id = NULL) {
        $maincatg = NULL;
        $landtype = NULL;
       // pr($rulelist);
       // pr($village_id);
        if (!is_null($rulelist) && !is_null($village_id)) {
            $usage = ClassRegistry::init('usagelnk')->find('first', array('fields' => array('evalrule_id', 'usage_main_catg_id', 'usage_sub_catg_id'), 'conditions' => array('evalrule_id' => $rulelist)));
         // pr($rulelist);
          // pr($usage);exit;
            if (!empty($usage)) {
                $maincatg = $usage['usagelnk']['usage_main_catg_id'];
            }
            $village = ClassRegistry::init('VillageMapping')->find('first', array('fields' => array('village_id', 'developed_land_types_id'), 'conditions' => array('village_id' => $village_id)));
            if (!empty($village)) {
                if ($village['VillageMapping']['developed_land_types_id'] == 1) {
                    $landtype = 'U';
                } else if ($village['VillageMapping']['developed_land_types_id'] == 2) {
                    $landtype = 'R';
                }if ($village['VillageMapping']['developed_land_types_id'] == 3) {
                    $landtype = 'I';
                } else {
                    $landtype = 'U';
                }
            }
        }
        if (!is_null($maincatg) && !is_null($landtype)) {       
          //  echo "<br>123";
            $patterns = $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and patterns.behavioral_id=? AND details.developed_land_types_flag=?  AND details.main_usage_id=? AND behavioral.behavioral_id=details.behavioral_id order by patterns.pattern_id", array($behavioral_id,$landtype,$maincatg));
        } else {
           //   echo "<br>1234";
            if(is_null($rulelist)){
            $patterns = $this->query("select behavioral.*,details.*, patterns.* from ngdrstab_conf_behavioral_patterns  patterns, ngdrstab_conf_behavioral_details details,ngdrstab_conf_behavioral behavioral where patterns.behavioral_details_id=details.behavioral_details_id  and patterns.behavioral_id=? AND behavioral.behavioral_id=details.behavioral_id order by patterns.pattern_id", array($behavioral_id));
        }}
        $fieldlist = array();
        
      //  pr($patterns);exit;
        foreach ($patterns as $pattern) {
            if (!empty($pattern['0']['vrule_en'])) {
                $fieldlist['field_en' . $pattern['0']['field_id']]['text'] = $pattern['0']['vrule_en'];
            }
            if ($doclang != 'en' && !empty($pattern['0']['vrule_ll'])) {
                $fieldlist['field_ll' . $pattern['0']['field_id']]['text'] = $pattern['0']['vrule_ll'];
            }
        }
        return $fieldlist;
    }

        
public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_conf_behavioral_patterns';
        $duplicate['PrimaryKey'] = 'pattern_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'pattern_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlistmaster($languagelist) {

        $fieldlist = array();
         
         $fieldlist['behavioral_id']['select'] = 'is_required'; 
           $fieldlist['behavioral_details_id']['select'] = 'is_required'; 
           $fieldlist['is_required']['select'] = 'is_required'; 
           $fieldlist['vrule_en']['select'] = 'is_required'; 
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['pattern_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
                // $fieldlist['vrule_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace';
            } else {
                $fieldlist['pattern_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
                // $fieldlist['vrule_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
          

        return $fieldlist;
    }
}