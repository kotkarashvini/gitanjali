<?php

class RespDetails extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_respdetails';
    public $primaryKey = 'resp_id';
public $virtualFields = array('full_name' => 'CONCAT(respondent_f_name || \' \' || respondent_m_name || \' \' || respondent_l_name )');
    function get_resprecord($case_id, $user_id) {
//       $record= $this->query("select  a.*,a.property_id, a.party_fname_en,b.party_type_desc_en,c.category_name_en 
//                                                        from ngdrstab_trn_party_entry_new a
//                                                        left outer join ngdrstab_mst_party_type b on b.party_type_id=a.party_type_id
//                                                        left outer join ngdrstab_mst_party_category c on c.category_id=a.party_catg_id where token_no=? and a.user_id=?
//                                                      ",array($case_id,$user_id)); 
        $record = $this->query("  select  respondent_f_name ,
  respondent_m_name ,
  respondent_l_name ,
  respondent_advocate_f_name ,
  respondent_advocate_m_name ,
  respondent_advocate_l_name ,
  liable_for_payment_flag ,
  liable_for_payment_f_name ,
  liable_for_payment_m_name ,
  liable_for_payment_l_name from ngdrstab_mst_respdetails where case_id=? and user_id=?
                                                      ", array($case_id, $user_id));
        return $record;
    }
}

?>