<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of party_entry
 *
 * @author Anjali
 */
class Leg_party_entry extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_party_entry_new';
    public $primaryKey = 'id';
    
    
      public function get_party_entry($lang = NULL, $doc_token_no = NULL, $property_id = NULL) {
        $conditions['token_no'] = $doc_token_no;
        if ($property_id) {
            $conditions['property_id'] = $property_id;
        }
        return $this->find('all', array('fields' => array('party_catg.authorised_signatory','saluation.salutation_desc_' . $lang, 'occupation.occupation_name_' . $lang, 'gender.gender_desc_' . $lang, 'party_type.party_type_flag', 'party_type.party_type_desc_' . $lang, 'party_catg.category_name_en', 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'Leg_party_entry.*'),
                    'conditions' => $conditions,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation', 'type' => 'left', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=Leg_party_entry.salutation_id')),
                        array('table' => 'ngdrstab_mst_gender', 'type' => 'left', 'alias' => 'gender', 'conditions' => array('gender.gender_id=Leg_party_entry.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=Leg_party_entry.occupation_id')),
                        array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=Leg_party_entry.party_type_id')),
                        array('table' => 'ngdrstab_mst_party_category', 'type' => 'left', 'alias' => 'party_catg', 'conditions' => array('party_catg.category_id=Leg_party_entry.party_catg_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=Leg_party_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=Leg_party_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=Leg_party_entry.taluka_id'))
        )));
    }
    
    
      public function get_party_entry_new($lang = NULL, $doc_token_no = NULL, $property_id = NULL) {
        
        //if ($property_id) {
            $conditions=array (
                            'OR' => array(
                                'Leg_party_entry.id=Leg_party_entry.repeat_party_id',
                                'Leg_party_entry.repeat_party_id' =>NULL
                            ),
                            'Leg_party_entry.token_no'  => $doc_token_no
                        );
          //  $conditions['OR'] = array('party_id' => 'repeat_party_id', 'repeat_party_id' => NULL);
       // }
        
        return $this->find('all', array('fields' => array('party_art.display_order_flag','party_catg.authorised_signatory','saluation.salutation_desc_' . $lang, 'occupation.occupation_name_' . $lang, 'gender.gender_desc_' . $lang, 'party_type.party_type_flag', 'party_type.party_type_desc_' . $lang, 'party_catg.category_name_en', 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'Leg_party_entry.*'),
                    'conditions' => $conditions,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation', 'type' => 'left', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=Leg_party_entry.salutation_id')),
                        array('table' => 'ngdrstab_mst_gender', 'type' => 'left', 'alias' => 'gender', 'conditions' => array('gender.gender_id=Leg_party_entry.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=Leg_party_entry.occupation_id')),
                        array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=Leg_party_entry.party_type_id')),
                       
                        array('table' => 'ngdrstab_trn_generalinformation', 'type' => 'left', 'alias' => 'party_gen', 'conditions' => array('party_gen.token_no='.$doc_token_no)),
                        array('table' => 'ngdrstab_mst_party_category', 'type' => 'left', 'alias' => 'party_catg', 'conditions' => array('party_catg.category_id=Leg_party_entry.party_catg_id')),
                        array('table' => 'ngdrstab_mst_article_partytype_mapping', 'type' => 'left', 'alias' => 'party_art', 'conditions' => array('party_art.party_type_id=Leg_party_entry.party_type_id','party_art.article_id=party_gen.article_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=Leg_party_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=Leg_party_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=Leg_party_entry.taluka_id'))
        ),'order'=>'party_type.party_type_id ASC'));
    }
}