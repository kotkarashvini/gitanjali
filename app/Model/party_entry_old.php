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
class party_entry extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_party_entry_new';
    public $primaryKey = 'id';

    function get_partyrecord($tokenval, $user_id, $doc_lang, $language,$sroparty) {
        if($sroparty=='Y'){
        $record = $this->query("select  a.*,a.property_id, a.party_fname_$doc_lang,b.party_type_desc_$language,b.presenter_flag,c.category_name_$language ,gender.gender_desc_$doc_lang,p.party_full_name_$doc_lang as POA
                                                        from ngdrstab_trn_party_entry_new a
                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
                                                        left outer join ngdrstab_mst_gender gender on gender.gender_id=a.gender_id
                                                          left outer join ngdrstab_trn_party_entry_new p on p.power_attoney_party_id=a.party_id
                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where power_attoney_party_id IS NOT NULL and a.token_no=? order by a.party_id  
                                                      
", array($tokenval));
        return $record;
        }else{
               $record = $this->query("select  a.*,a.property_id, a.party_fname_$doc_lang,b.party_type_desc_$language,b.presenter_flag,c.category_name_$language ,gender.gender_desc_$doc_lang,p.party_full_name_$doc_lang as POA
                                                        from ngdrstab_trn_party_entry_new a
                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
                                                        left outer join ngdrstab_mst_gender gender on gender.gender_id=a.gender_id
                                                          left outer join ngdrstab_trn_party_entry_new p on p.power_attoney_party_id=a.party_id
                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where a.token_no=?   and (a.id=a.repeat_party_id or a.repeat_party_id IS NULL) order by a.party_id,a.party_type_id 
                                                      
", array($tokenval));
        return $record;
        }
    }

    function save_party($data, $tokenval, $stateid, $user_id, $property_id, $hfid) {
        $data['token_no'] = $tokenval;
        $data['state_id'] = $stateid;
        $data['user_id'] = $user_id;
        $data['property_id'] = $property_id;
        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
        if ($hfid) {
            $this->id = $hfid;
            $this->save($data);
        } else {
            $this->save($data);
        }

        return true;
    }

    function get_partyname_forstatus($token) {
        return $this->find('all', array('fields' => array('DISTINCT pt.party_type_flag'), 'conditions' => array('party_entry.token_no' => $token),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_party_type', 'alias' => 'pt', 'conditions' => array("party_entry.party_type_id=pt.party_type_id AND pt.party_type_flag IN ('0','1')"))
                    ),
                    'order' => 'pt.party_type_flag'));
    }

//-----------------------------------Shridhar-----------------------------------    
    public function get_party_entry($lang = NULL, $doc_token_no = NULL, $property_id = NULL) {
        $conditions['token_no'] = $doc_token_no;
        if ($property_id) {
            $conditions['property_id'] = $property_id;
        }
        return $this->find('all', array('fields' => array('party_catg.authorised_signatory','saluation.salutation_desc_' . $lang, 'occupation.occupation_name_' . $lang, 'gender.gender_desc_' . $lang, 'party_type.party_type_flag', 'party_type.party_type_desc_' . $lang, 'party_catg.category_name_en', 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'party_entry.*'),
                    'conditions' => $conditions,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation', 'type' => 'left', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=party_entry.salutation_id')),
                        array('table' => 'ngdrstab_mst_gender', 'type' => 'left', 'alias' => 'gender', 'conditions' => array('gender.gender_id=party_entry.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=party_entry.occupation_id')),
                        array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=party_entry.party_type_id')),
                        array('table' => 'ngdrstab_mst_party_category', 'type' => 'left', 'alias' => 'party_catg', 'conditions' => array('party_catg.category_id=party_entry.party_catg_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=party_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=party_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=party_entry.taluka_id'))
        )));
    }
    
    
   public function get_party_entry_new($lang = NULL, $doc_token_no = NULL, $property_id = NULL) {
        
        //if ($property_id) {
            $conditions=array (
                            'OR' => array(
                                'party_entry.id=party_entry.repeat_party_id',
                                'party_entry.repeat_party_id' =>NULL
                            ),
                            'party_entry.token_no'  => $doc_token_no
                        );
          //  $conditions['OR'] = array('party_id' => 'repeat_party_id', 'repeat_party_id' => NULL);
       // }
        
        return $this->find('all', array('fields' => array('party_art.display_order_flag','party_catg.authorised_signatory','saluation.salutation_desc_' . $lang, 'occupation.occupation_name_' . $lang, 'gender.gender_desc_' . $lang, 'party_type.party_type_flag', 'party_type.party_type_desc_' . $lang, 'party_catg.category_name_en', 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'party_entry.*'),
                    'conditions' => $conditions,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation', 'type' => 'left', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=party_entry.salutation_id')),
                        array('table' => 'ngdrstab_mst_gender', 'type' => 'left', 'alias' => 'gender', 'conditions' => array('gender.gender_id=party_entry.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=party_entry.occupation_id')),
                        array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=party_entry.party_type_id')),
                       
                        array('table' => 'ngdrstab_trn_generalinformation', 'type' => 'left', 'alias' => 'party_gen', 'conditions' => array('party_gen.token_no='.$doc_token_no)),
                        array('table' => 'ngdrstab_mst_party_category', 'type' => 'left', 'alias' => 'party_catg', 'conditions' => array('party_catg.category_id=party_entry.party_catg_id')),
                        array('table' => 'ngdrstab_mst_article_partytype_mapping', 'type' => 'left', 'alias' => 'party_art', 'conditions' => array('party_art.party_type_id=party_entry.party_type_id','party_art.article_id=party_gen.article_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=party_entry.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=party_entry.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=party_entry.taluka_id'))
        ),'order'=>'party_type.party_type_id ASC'));
    }
    
    

    //-------------------------------------------------------------------------
    public function get_party_record($token, $lang = 'en') {

        $party_record = $this->query("select  a.*,a.property_id, a.party_fname_$lang,b.party_type_desc_$lang,c.category_name_$lang ,
                                d.salutation_desc_$lang,e.desc_$lang,f.identificationtype_desc_$lang as idntity,h.gender_desc_$lang,i.occupation_name_$lang,
                                j.district_name_$lang,k.taluka_name_$lang,l.village_name_$lang,b.party_type_flag
                                from ngdrstab_trn_party_entry_new a
                                left outer join ngdrstab_mst_party_type b on b.party_type_id = a.party_type_id
                                left outer join ngdrstab_mst_party_category c on c.category_id = a.party_catg_id
                                left outer join ngdrstab_mst_salutation d on d.id = a.salutation_id
                                left outer join ngdrstab_mst_presentation_exemption e on e.exemption_id = a.exemption_id
                                left outer join ngdrstab_mst_identificationtype f on f.identificationtype_id = a.identificationtype_id
                                left outer join ngdrstab_mst_gender h on h.id = a.gender_id 
                                left outer join ngdrstab_mst_occupation i on i.id = a.occupation_id
                                left outer join ngdrstab_conf_admblock3_district j on j.id = a.district_id
                                left outer join ngdrstab_conf_admblock5_taluka k on k.taluka_id = a.taluka_id
                                left outer join ngdrstab_conf_admblock7_village_mapping l on l.village_id = a.village_id
                                left outer join ngdrstab_trn_property_details_entry prop on prop.token_no = a.village_id
                                where a.token_no=?", array($token));
        return $party_record;
        exit;
    }

    /* created By Shrishail 02-jun-2017 */

    function validate($partylist, $path, $lock_party_id = NULL) {
        $result = 1;
        $lock_flag = 0;
        // pr($partylist);exit;
        foreach ($partylist as $partyrow) {
            if ($partyrow[0]['home_visit_flag'] == 'N') {
                if ($partyrow[0]['is_executer'] == 'Y' || $partyrow[0]['presenty_require'] == 'Y') {
                    $imagedata = $path['file_config']['filepath'] . $partyrow[0]['photo_img'];
                    $imagedata1 = $path['file_config']['filepath'] . $partyrow[0]['biometric_img'];
                    if ($lock_party_id != NULL) {
                        if ($lock_party_id == $partyrow[0]['party_id']) {
                            $lock_flag = 1;
                            if ($partyrow[0]['photo_img'] == null || !is_file($imagedata)) {
                                if ($partyrow[0]['camera_working_flag'] == 'Y') {
                                    $result = 0;
                                }
                            }
                            if ($partyrow[0]['biometric_img'] == null || !is_file($imagedata1)) {
                                if ($partyrow[0]['biodevice_working_flag'] == 'Y') {
                                    $result = 0;
                                }
                            }
                        }
                    } else {
                        if ($partyrow[0]['photo_img'] == null || !is_file($imagedata)) {
                            if ($partyrow[0]['camera_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                        if ($partyrow[0]['biometric_img'] == null || !is_file($imagedata1)) {
                            if ($partyrow[0]['biodevice_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                    }
                }
            }
        }
        // pr($result);exit;
        if (is_numeric($lock_party_id)) {
            if ($lock_flag == 0) {
                $result = 0;
            }
        }
        return $result;
    }

    function get_applicable_party_count($article_id) {
        try {
            $record = $this->query("select only_one_party from ngdrstab_mst_article where article_id=?", array($article_id));
            if (count($record) > 0) {
                return $record[0][0]['only_one_party'];
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }
    
     function get_partyrecord_homevisit($tokenval,  $doc_lang, $language) {
      
        $record = $this->query("select  a.*,a.property_id, a.party_fname_$doc_lang,b.party_type_desc_$language,c.category_name_$language ,gender.gender_desc_$doc_lang
                                                        from ngdrstab_trn_party_entry_new a
                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
                                                        left outer join ngdrstab_mst_gender gender on gender.gender_id=a.gender_id
                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where token_no=? and home_visit_flag='Y'
                                                      
", array($tokenval));
        return $record;
    }

}
