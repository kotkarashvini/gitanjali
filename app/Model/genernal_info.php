<?php

class genernal_info extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_generalinformation';

     public function get_alldocument($user_id, $laug,$user_type) {
        try {
            
            if($user_type=='C'){
            $q1 = $this->query("select village.village_name_$laug,doc.document_status_desc_$laug,tal.taluka_name_$laug,dist.district_name_$laug,a.token_no, b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag
                ,a.last_status_id,app.appointment_date,app.flag,a.sro_action_flag,a.sro_remark,reject.document_entry_remark
                                                 from ngdrstab_trn_generalinformation a
                                                 left outer join ngdrstab_mst_article b on b.article_id=a.article_id
                                                 left  join ngdrstab_mst_articledescriptiondetail c on a.title_id=c.articledescription_id
                                                 left outer join ngdrstab_trn_property_details_entry prop on prop.token_no=a.token_no
                                                   left outer join ngdrstab_conf_admblock7_village_mapping village on village.village_id=prop.village_id
                                                    left outer join ngdrstab_conf_admblock3_district dist on dist.district_id=prop.district_id
                                                     left outer join ngdrstab_conf_admblock5_taluka tal on tal.taluka_id=prop.taluka_id
                                                    left outer join ngdrstab_mst_document_status_description doc on doc.id=a.last_status_id
                                                     left outer join ngdrstab_trn_appointment_details app on app.token_no=a.token_no
                                                     left outer join ngdrstab_trn_application_dataentry_reject reject on a.token_no=reject.token_no
                                                 WHERE  a.user_type=? and a.last_status_id!=4 and a.user_id=? 
                                                GROUP BY a.token_no,village.village_name_$laug,doc.document_status_desc_$laug,tal.taluka_name_$laug,dist.district_name_$laug,
                                                b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag,a.last_status_id,app.appointment_date,app.flag,a.sro_action_flag,a.sro_remark,reject.document_entry_remark
                                                 order by a.token_no desc", array($user_type,$user_id));
            }else{
                $q1 = $this->query("select village.village_name_$laug,doc.document_status_desc_$laug,tal.taluka_name_$laug,dist.district_name_$laug,a.token_no, b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag
                ,a.last_status_id,app.appointment_date,app.flag,a.sro_action_flag,a.sro_remark,reject.document_entry_remark
                                                 from ngdrstab_trn_generalinformation a
                                                 left outer join ngdrstab_mst_article b on b.article_id=a.article_id
                                                 left  join ngdrstab_mst_articledescriptiondetail c on a.title_id=c.articledescription_id
                                                 left outer join ngdrstab_trn_property_details_entry prop on prop.token_no=a.token_no
                                                   left outer join ngdrstab_conf_admblock7_village_mapping village on village.village_id=prop.village_id
                                                    left outer join ngdrstab_conf_admblock3_district dist on dist.district_id=prop.district_id
                                                     left outer join ngdrstab_conf_admblock5_taluka tal on tal.taluka_id=prop.taluka_id
                                                    left outer join ngdrstab_mst_document_status_description doc on doc.id=a.last_status_id
                                                     left outer join ngdrstab_trn_appointment_details app on app.token_no=a.token_no
                                                     left outer join ngdrstab_trn_application_dataentry_reject reject on a.token_no=reject.token_no
                                                 WHERE  a.user_type=? and a.last_status_id!=4 and a.org_user_id=? 
                                                GROUP BY a.token_no,village.village_name_$laug,doc.document_status_desc_$laug,tal.taluka_name_$laug,dist.district_name_$laug,
                                                b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag,a.last_status_id,app.appointment_date,app.flag,a.sro_action_flag,a.sro_remark,reject.document_entry_remark
                                                 order by a.token_no desc", array($user_type,$user_id));
            }
           

            return $q1;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    
    
      public function get_alldocument_homevisit($user_id, $laug, $usertyp,$office_id){
       
         try {
            $q1 = $this->query("select doc.document_status_desc_$laug,a.token_no, b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag
                ,a.last_status_id
                                                 from ngdrstab_trn_generalinformation a
                                                 left outer join ngdrstab_mst_article b on b.article_id=a.article_id
                                                 left  join ngdrstab_mst_articledescriptiondetail c on a.title_id=c.articledescription_id
                                                 left outer join ngdrstab_mst_document_status_description doc on doc.id=a.last_status_id 
                                                left outer join ngdrstab_mst_office_homevisitor_mapping hom on hom.office_id = a.office_id 
                                                 WHERE a.last_status_id=2  and b.home_visit='Y' and hom.visitor_id=$user_id  and a.office_id=$office_id
                                                GROUP BY a.token_no,doc.document_status_desc_$laug,
                                                b.article_desc_$laug,c.articledescription_$laug,a.data_entry_flag,a.submitted_flag,a.reg_proc_flag,a.registerd_flag,a.last_status_id
                                                 order by a.token_no desc");
          //  pr($q1);exit;
             return $q1;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
        
    }

}
