<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of partytype
 *
 * @author Administrator
 */
class partytype extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_party_type';
     public $primaryKey = 'party_type_id';
    
     public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_party_type';
        $duplicate['PrimaryKey'] = 'party_type_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'party_type_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {

        $fieldlist = array();
         
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['party_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_aplhanumericspace';
            } else {
                $fieldlist['party_type_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
         //$fieldlist['taluka_code']['text'] = 'is_numeric';  

        return $fieldlist;
    }
    
    function get_party_typename($article_id)
    {
      $partytype_name=$this->find('list', array('fields' => array('partytype.party_type_id', 'partytype.party_type_desc_en'), 'conditions' => array('partytype.display_flag' => 'C')));
            $options1['conditions'] = array('m.article_id' => trim($article_id));
            $options1['joins'] = array(array('table' => 'ngdrstab_mst_article_partytype_mapping', 'alias' => 'm', 'type' => 'INNER', 'conditions' => array('partytype.party_type_id=m.party_type_id')),);
            $options1['fields'] = array('partytype.party_type_id', 'partytype.party_type_desc_en');
            $party = $this->find('list', $options1);
          
            $partytype_name1 = $partytype_name + $party;  
           
            return $partytype_name1;
    }

}
