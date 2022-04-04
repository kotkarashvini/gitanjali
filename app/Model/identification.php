<?php

class identification extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_identification';

//    var $virtualFields = array(
//    'name' => "CONCAT(Level1.level_1_from_range, '-', Level1.level_1_to_range)"
//);

    function get_identification_details($language, $tokenval) {
               return $this->query("select a.*,b.salutation_desc_" . $language . "  as salutaion_desc  ,gender.gender_desc_$language,party_type.party_type_desc_en
                                                        from ngdrstab_trn_identification a
                                                        left outer join  ngdrstab_mst_salutation b on a.salutation = b.salutation_id 
                                                        left outer join ngdrstab_mst_gender gender on gender.gender_id=a.gender_id 
                                                        left outer join ngdrstab_mst_party_type party_type on party_type.party_type_id=a.party_type_id 
                                                        where a.token_no= ? ", array($tokenval));
    }

    //--------------------------------------------------- Modified on  22-March-2017--------------------------------------------------------------
    function get_identification($lang, $doc_token_no, $report_type = 'F') {

        $options['token_no'] = $doc_token_no;
        if ($report_type == 'P') {
            $options['biometric_upload'] = date('Y-m-d');
            $options['photo_upload'] = date('Y-m-d');
        }

        return $this->find('all', array('fields' => array('saluation.salutation_desc_' . $lang, 'occupation.occupation_name_' . $lang, 'gender.gender_desc_' . $lang, 'party_type.party_type_desc_' . $lang, 'identification_type.desc_' . $lang, 'state.state_name_' . $lang, 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'identification.*'),
                    'conditions' => $options,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=identification.salutation')),
                        array('table' => 'ngdrstab_mst_gender', 'alias' => 'gender', 'conditions' => array('gender.gender_id=identification.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=identification.occupation_id')),
                        array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=identification.party_type_id')),
                        array('table' => 'ngdrstab_mst_identifier_type', 'type' => 'left', 'alias' => 'identification_type', 'conditions' => array('identification.identificationtype_id=identification_type.type_id')),
                        array('table' => 'ngdrstab_conf_admblock1_state', 'type' => 'left', 'alias' => 'state', 'conditions' => array('state.state_id=identification.state_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=identification.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=identification.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=identification.taluka_id'))
        )));
    }

    /* created By shrishail 02-jun-2017 */

    function validate($identifications, $path, $lock_identifire_id = NULL) {
        $result = 1;
        $lock_flag = 0;
        foreach ($identifications as $identifirerow) {
            if ($identifirerow['0']['photo_require'] == 'Y') {
                $imagedata = $path['file_config']['filepath'] . $identifirerow['0']['photo_img'];
                $imagedata1 = $path['file_config']['filepath'] . $identifirerow['0']['biometric_img'];
                if ($lock_identifire_id != NULL) {
                    if ($lock_identifire_id == $identifirerow['0']['identification_id']) {
                        $lock_flag = 1;
                        if ($identifirerow[0]['photo_img'] == null || !is_file($imagedata)) {
                            if ($identifirerow[0]['camera_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                        if ($identifirerow[0]['biometric_img'] == null || !is_file($imagedata1)) {
                            if ($identifirerow[0]['biodevice_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                    }
                }else {
                    if ($identifirerow[0]['photo_img'] == null || !is_file($imagedata)) {
                        if ($identifirerow[0]['camera_working_flag'] == 'Y') {
                            $result = 0;
                        }
                    }
                    if ($identifirerow[0]['biometric_img'] == null || !is_file($imagedata1)) {
                        if ($identifirerow[0]['biodevice_working_flag'] == 'Y') {
                            $result = 0;
                        }
                    }
                }
            }
        }
        if (is_numeric($lock_identifire_id)) {
            if ($lock_flag == 0) {
                $result = 0;
            }
        }
        return $result;
    }

    
     function get_identirename_forstatus($token) {
        return $this->find('all', array('fields' => array('DISTINCT pt.party_type_flag'), 'conditions' => array('identification.token_no' => $token),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_party_type', 'alias' => 'pt', 'conditions' => array("identification.party_type_id=pt.party_type_id AND pt.party_type_flag IN ('0','1')"))
                    ),
                    'order' => 'pt.party_type_flag'));
    }

}
