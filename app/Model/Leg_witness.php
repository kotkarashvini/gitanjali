<?php

class Leg_witness extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_witness';
    
//    var $virtualFields = array(
//    'name' => "CONCAT(Level1.level_1_from_range, '-', Level1.level_1_to_range)"
//);
function get_allwitness($language,$tokenval)
{
    $witness=$this->query("select a.*,b.salutation_desc_" . $language . "  as salutaion_desc
                                                        from ngdrstab_trn_legacy_witness a
                                                        left outer join  ngdrstab_mst_salutation b on a.salutation = b.salutation_id 
                                                        where a.token_no= ?",array($tokenval));
    return $witness;
}

//------------------------------by Shridhar--------------------------------
public function get_witness($lang=NULL,$doc_token_no=NULL)
{
   return  $this->find('all', array('fields' => array('saluation.salutation_desc_' . $lang, 'occupation.occupation_name_'.$lang, 'gender.gender_desc_'.$lang, 'witness_type.witness_type_desc_' . $lang, 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'identy.identificationtype_desc_'.$lang, 'Leg_witness.*'),
            'conditions' => array('token_no' => $doc_token_no),
            'joins' => array(
                array('table' => 'ngdrstab_mst_salutation', 'type' => 'left','alias' => 'saluation', 'conditions' => array('saluation.salutation_id=Leg_witness.salutation')),
                array('table' => 'ngdrstab_mst_gender','type' => 'left', 'alias' => 'gender', 'conditions' => array('gender.gender_id=Leg_witness.gender_id')),
                array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=Leg_witness.occupation_id')),
                array('table' => 'ngdrstab_mst_identificationtype', 'type' => 'left', 'alias' => 'identy', 'conditions' => array('identy.identificationtype_id=Leg_witness.identificationtype_id')),
                array('table' => 'ngdrstab_mst_witness_type', 'type' => 'left', 'alias' => 'witness_type', 'conditions' => array('witness_type.witness_type_id=Leg_witness.witness_type_id')),
                array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=Leg_witness.village_id')),
                array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=Leg_witness.district_id')),
                array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=Leg_witness.taluka_id'))
        )));
    
}

function save_witness($data,$tokenval,$stateid,$user_id,$hfid, $user_type)
    {
                      $data['token_no'] = $tokenval;
                       $data['state_id'] = $stateid;
                       $data['user_id'] = $user_id;
                       $data['user_type'] = $user_type;
                      
                        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
                       $data['dob'] = date('Y-m-d H:i:s', strtotime($data['dob']));
                        if($hfid){
                              $this->id=$hfid;
                        $this->save($data);
                        }  else {
                            $this->save($data);
                        }
                                
                        return true;
                       
    }
    
     function get_wit($lang, $doc_token_no,$report_type='F') {
       
     
       $options['token_no']=$doc_token_no;
       if($report_type=='P'){
         $options['biometric_upload']=date('Y-m-d');  
         $options['photo_upload']=date('Y-m-d');  
       }
       
        return $this->find('all', array('fields' => array('saluation.salutation_desc_' . $lang,
            'occupation.occupation_name_'.$lang, 'gender.gender_desc_' . $lang,  'witness_type.witness_type_desc_' . $lang, 'state.state_name_' . $lang, 'village.village_name_' . $lang, 'taluka.taluka_name_' . $lang, 'district.district_name_' . $lang, 'witness.*'),
                    'conditions' => $options,
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_salutation','type' => 'left', 'alias' => 'saluation', 'conditions' => array('saluation.salutation_id=witness.salutation')),
                        array('table' => 'ngdrstab_mst_gender', 'type' => 'left','alias' => 'gender', 'conditions' => array('gender.gender_id=witness.gender_id')),
                        array('table' => 'ngdrstab_mst_occupation', 'type' => 'left', 'alias' => 'occupation', 'conditions' => array('occupation.occupation_id=witness.occupation_id')),
                       // array('table' => 'ngdrstab_mst_party_type', 'type' => 'left', 'alias' => 'party_type', 'conditions' => array('party_type.party_type_id=witness.party_type_id')),
                        array('table' => 'ngdrstab_mst_witness_type', 'type' => 'left', 'alias' => 'witness_type', 'conditions' => array('witness_type.witness_type_id=witness.witness_type_id')),
                        array('table' => 'ngdrstab_conf_admblock1_state', 'type' => 'left', 'alias' => 'state', 'conditions' => array('state.state_id=witness.state_id')),
                        array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'type' => 'left', 'alias' => 'village', 'conditions' => array('village.village_id=witness.village_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=witness.district_id')),
                        array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=witness.taluka_id'))
        )));
    }
   //-----------------------------------------------------------------------------------------
        /* Created By shrishail 02-jun-17*/    
       function validate($witness, $path, $lock_witness_id = NULL) {
        $result = 1;
        $lock_flag = 0;
        foreach ($witness as $witnessrow) {
            $imagedata = $path['file_config']['filepath'] . $witnessrow['witness']['photo_img'];
            $imagedata1 = $path['file_config']['filepath'] . $witnessrow['witness']['biometric_img'];
            if ($lock_witness_id != NULL) {
                if ($lock_witness_id == $witnessrow['witness']['witness_id']) {
                   $lock_flag = 1;
                        if ($witnessrow['witness']['photo_img'] == null || !is_file($imagedata)) {
                            if ($witnessrow['witness']['camera_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                        if ($witnessrow['witness']['biometric_img'] == null || !is_file($imagedata1)) {
                            if ($witnessrow['witness']['biodevice_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                }
            } else {
                 if ($witnessrow['witness']['photo_img'] == null || !is_file($imagedata)) {
                            if ($witnessrow['witness']['camera_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
                        if ($witnessrow['witness']['biometric_img'] == null || !is_file($imagedata1)) {
                            if ($witnessrow['witness']['biodevice_working_flag'] == 'Y') {
                                $result = 0;
                            }
                        }
            }
        }
        if (is_numeric($lock_witness_id)) {
            if ($lock_flag == 0) {
                $result = 0;
            }
        }
        return $result;
    }

}