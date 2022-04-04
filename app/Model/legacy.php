<?php


class legacy extends AppModel {
    public $useDbConfig = 'ngprs';
     public $useTable = 'ngdrstab_conf_admblock5_taluka';
    
    
    public function get_general_info_old($token) {

                    $data = $this->query("select local_language_id,article_id,to_char(exec_date,'dd-mm-yyyy')exec_date ,year_for_token,final_doc_reg_no,to_char(final_stamp_date,'dd-mm-yyyy')final_stamp_date,ngdrstab_trn_legacy_generalinformation.state_id,district_id,taluka_id,ngdrstab_trn_legacy_generalinformation.office_id,
 doc_entered_state,doc_entered_district,doc_entered_taluka,doc_entered_office,presentation_no,presentation_dt,doc_type,reference_no,subdivision_id
 from ngdrstab_trn_legacy_application_submitted
 inner join ngdrstab_trn_legacy_generalinformation on ngdrstab_trn_legacy_generalinformation.token_no=ngdrstab_trn_legacy_application_submitted.token_no
 WHERE  ngdrstab_trn_legacy_application_submitted.token_no=?",array( $token)); 
        

        return $data;
    
        
    }
    
    public function get_general_info($token) {

                    $data = $this->query("select local_language_id,ngdrstab_trn_legacy_generalinformation.article_id,to_char(exec_date,'dd-mm-yyyy')exec_date ,year_for_token,final_doc_reg_no,to_char(final_stamp_date,'dd-mm-yyyy')final_stamp_date,ngdrstab_trn_legacy_generalinformation.state_id,ngdrstab_trn_legacy_generalinformation.district_id,ngdrstab_trn_legacy_generalinformation.taluka_id,ngdrstab_trn_legacy_generalinformation.office_id,
 doc_entered_state,doc_entered_district,doc_entered_taluka,doc_entered_office,presentation_no,presentation_dt,doc_type,reference_no,subdivision_id,book_serial_number
 from ngdrstab_trn_legacy_application_submitted
 inner join ngdrstab_trn_legacy_generalinformation on ngdrstab_trn_legacy_generalinformation.token_no=ngdrstab_trn_legacy_application_submitted.token_no 
 inner join ngdrstab_trn_legacy_serial_numbers_final on ngdrstab_trn_legacy_serial_numbers_final.token_no=ngdrstab_trn_legacy_generalinformation.token_no and ngdrstab_trn_legacy_serial_numbers_final.office_id=ngdrstab_trn_legacy_generalinformation.office_id and ngdrstab_trn_legacy_serial_numbers_final.district_id=ngdrstab_trn_legacy_generalinformation.district_id
 WHERE  ngdrstab_trn_legacy_application_submitted.token_no=?",array( $token)); 
        

        return $data;
    
        
    }
    
    
//        public function get_data() {
//             $data = $this->query("select ngdrstab_trn_application_submitted
//.token_no,article_desc_en,final_doc_reg_no from
//ngdrstab_trn_application_submitted
//inner join ngdrstab_trn_generalinformation on ngdrstab_trn_generalinformation.token_no=ngdrstab_trn_application_submitted.token_no
//inner join ngdrstab_mst_article on ngdrstab_mst_article.article_id=ngdrstab_trn_generalinformation.article_id group by ngdrstab_trn_application_submitted
//.token_no,article_desc_en,final_doc_reg_no");
//        
//
//        return $data;
//    
//        
//    }
    
          public function get_data($user_id)
          
          {
             $data = $this->query("
select app.token_no,article_desc_en,article_desc_ll,final_doc_reg_no,
array((select location1_en  from ngdrstab_trn_legacy_property_details_entry as prop  
where prop.token_no=app.token_no
)) as location_en,array((select location2_ll  from ngdrstab_trn_legacy_property_details_entry as prop  

where prop.token_no=app.token_no
)) as location_ll,
array((
select party_full_name_en from  ngdrstab_trn_legacy_party_entry_new as party where party.token_no=app.token_no  
))as Party_en,array((
select party_full_name_ll from  ngdrstab_trn_legacy_party_entry_new as party where party.token_no=app.token_no  
))as Party_ll,document_status_desc_en,last_status_id

from ngdrstab_trn_legacy_generalinformation  as info 
right join ngdrstab_trn_legacy_application_submitted as app on app.token_no=info.token_no
inner join ngdrstab_mst_article on ngdrstab_mst_article.article_id=info.article_id
inner join ngdrstab_mst_document_status_description on ngdrstab_mst_document_status_description.id=info.last_status_id 
where info.user_id=$user_id
group by app.token_no,article_desc_en,article_desc_ll,final_doc_reg_no,document_status_desc_en,last_status_id 
 order by token_no");

 return $data;     
    }
    
         public function get_data_auth() {
             $data = $this->query("
select app.token_no,article_desc_en,final_doc_reg_no,
array((select village_name_en  from ngdrstab_trn_property_details_entry as prop  
inner join ngdrstab_conf_admblock7_village_mapping on ngdrstab_conf_admblock7_village_mapping.village_id=prop.village_id
where prop.token_no=app.token_no
)) as location,
array((
select party_full_name_en from  ngdrstab_trn_party_entry_new as party where party.token_no=app.token_no  
))as Party

from ngdrstab_trn_generalinformation  as info 
right join ngdrstab_trn_application_submitted as app on app.token_no=info.token_no
inner join ngdrstab_mst_article on ngdrstab_mst_article.article_id=info.article_id where authorized_flag='N'
group by app.token_no,article_desc_en,final_doc_reg_no
");
 return $data;     
    }
    
   public function get_unauthorised_data($lang,$office_id) {
       
              $data = $this->query("
select app.token_no,article_desc_$lang,final_doc_reg_no,
array((select village_name_$lang  from ngdrstab_trn_legacy_property_details_entry as prop  
inner join ngdrstab_conf_admblock7_village_mapping on ngdrstab_conf_admblock7_village_mapping.village_id=prop.village_id
where prop.token_no=app.token_no
)) as location_$lang,
array((
select party_full_name_$lang from  ngdrstab_trn_legacy_party_entry_new as party where party.token_no=app.token_no  
))as Party_$lang,document_status_desc_en,last_status_id

from ngdrstab_trn_legacy_generalinformation  as info 
right join ngdrstab_trn_legacy_application_submitted as app on app.token_no=info.token_no
inner join ngdrstab_mst_article on ngdrstab_mst_article.article_id=info.article_id
inner join ngdrstab_mst_document_status_description on ngdrstab_mst_document_status_description.id=info.last_status_id 
where (info.office_id=$office_id) and authorized_flag='N' and last_status_id=2
group by app.token_no,article_desc_$lang,final_doc_reg_no,document_status_desc_en,last_status_id 
 order by token_no");
 return $data; 
 //info.office_id=$office_id || 
 //or info.doc_entered_office=1
   }
    
}