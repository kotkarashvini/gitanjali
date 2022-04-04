<?php

class ApplicationSubmitted extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_application_submitted';
    public $primaryKey='app_id';
    public function general_information($lang, $office_id, $token) {

        $documents = $this->query("SELECT 
article.article_desc_$lang,
 title.title_name,
info.no_of_pages,
lang.language_name
FROM  ngdrstab_trn_application_submitted app
LEFT join    ngdrstab_trn_generalinformation info ON  app.token_no=info.token_no 
LEFT join    ngdrstab_mst_article article   ON info.article_id=article.article_id 
LEFT join    ngdrstab_mst_document_title title ON   info.title_id=title.id
LEFT join   ngdrstab_mst_language lang ON info.local_language_id=lang.id
 WHERE  app.office_id=? AND app.token_no=? ", array($office_id, $token));
        if (!empty($documents)) {
            $documents = $documents[0][0];
        }

        return $documents;
    }

    public function party_information($lang, $doc_lang, $office_id, $token) {

        $party = $this->query("SELECT 
 party.party_full_name_en,
  party.party_full_name_ll,
 salutation.salutation_desc_en,
 salutation.salutation_desc_$doc_lang,
     ptype.party_type_desc_$doc_lang
FROM  ngdrstab_trn_application_submitted app
LEFT join    ngdrstab_trn_party_entry_new party ON  app.token_no=party.token_no 
LEFT join    ngdrstab_mst_salutation salutation ON  salutation.salutation_id=party.salutation_id 
LEFT join    ngdrstab_mst_party_type ptype ON  ptype.party_type_id=party.party_type_id  
 WHERE  app.office_id=? AND app.token_no=? ", array($office_id, $token));


        return $party;
    }

    public function witness_information($lang, $doc_lang, $office_id, $token) {

        $witness = $this->query("SELECT 
 witness.witness_full_name_en,
 witness.witness_full_name_ll,
 salutation.salutation_desc_en,
 salutation.salutation_desc_$doc_lang
FROM  ngdrstab_trn_application_submitted app
LEFT join    ngdrstab_trn_witness witness ON  app.token_no=witness.token_no 
LEFT join    ngdrstab_mst_salutation salutation ON  salutation.salutation_id=witness.salutation_id 


 
 WHERE  app.office_id=? AND app.token_no=? ", array($office_id, $token));


        return $witness;
    }

    public function payment_information($lang, $doc_lang, $office_id, $token) {

        $payment = $this->query("select pay.*,mode.payment_mode_desc_$lang FROM ngdrstab_trn_payment_details pay,ngdrstab_mst_payment_mode mode WHERE pay.payment_mode_id=mode.payment_mode_id AND  pay.token_no=?  ", array($token));
        return $payment;
    }

    public function get_property_list($lang, $token, $user_id) {
        $q = $this->query("select prop.property_id,prop.val_id,village.village_name_$lang,e.evalrule_desc_$lang from ngdrstab_trn_property_details_entry prop ,ngdrstab_mst_evalrule_new e,ngdrstab_trn_valuation_details v,
                    ngdrstab_conf_admblock7_village_mapping village where  village.village_id=prop.village_id  
                  and  prop.token_no=? and prop.user_id=?  and prop.val_id=v.val_id and v.rule_id=e.evalrule_id group by prop.property_id,village.village_name_$lang,e.evalrule_desc_$lang ", array($token, $user_id));
        return $q;
    }

    public function get_property_pattern($lang, $token, $user_id) {
        $q = $this->query("SELECT 
                    trn_patterns.field_id, trn_patterns.field_value_en,trn_patterns.field_value_ll, trn_patterns.mapping_ref_val ,village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                  FROM 
                   ngdrstab_trn_behavioral_patterns AS trn_patterns, 
                   ngdrstab_trn_property_details_entry AS prop, 
                   ngdrstab_conf_admblock7_village_mapping AS village,
                   ngdrstab_conf_behavioral_patterns AS conf_patterns
                  WHERE 
                    trn_patterns.mapping_ref_val = prop.property_id AND prop.village_id = village.village_id  and conf_patterns.field_id=trn_patterns.field_id AND prop.token_no=? and prop.user_id=? 
                group by  trn_patterns.id,trn_patterns.mapping_ref_val,trn_patterns.field_id,  trn_patterns.field_value_$lang, village.village_name_$lang,conf_patterns.pattern_desc_en,conf_patterns.pattern_desc_ll
                order by trn_patterns.id ASC   ", array($token, $user_id));
        return $q;
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------
    public function get_reg_gen_info($lang = NULL, $doc_reg_no = NULL, $doc_token_no = NULL, $office_id = NULL) {
        return $this->find('first', array('fields' => array('ApplicationSubmitted.doc_reg_no', 'article.article_desc_' . $lang, 'gen.exec_date', 'gen.no_of_pages', 'gen.presentation_date', 'sd.final_amt'),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no=ApplicationSubmitted.token_no')),
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=gen.article_id')),
                        array('table' => 'ngdrstab_trn_stamp_duty', 'alias' => 'sd', 'conditions' => array('sd.token_no=ApplicationSubmitted.token_no'))
                    ),
                    'conditions' => array('doc_reg_no' => $doc_reg_no)
        ));
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------
    //--------------------Shrishail ---------------------------------------------------------------------------------------------------------------
    
    public function search_document($document_number,$check_stamp_flag) {
       // pr($check_stamp_flag);exit;
        $result = $this->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_en,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            where app.doc_reg_no=? and $check_stamp_flag=?", array($document_number,'Y'));
        return $result;
    }

    public function released_document_list() {
        $result = $this->query("SELECT release.release_date,release.document_release_remark,app.*,article.*,appoint.appointment_id, party.party_full_name_en,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_document_release release
                            left outer join ngdrstab_trn_application_submitted app on app.token_no=release.token_no and release.document_change_status='N'
                            
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            ");
        return $result;
    }

    public function search_document_summary($from_date, $to_date, $type, $check_stamp_flag) {
        $from_date_db = date("Y-m-d", strtotime($from_date));
        $to_date_db = date("Y-m-d", strtotime($to_date));
        if ($type == 1) { $flag_value = 'Y';  } else {  $flag_value = 'N'; }
        $result = $this->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_en,
                            appoint.appointment_date,appoint.sheduled_time ,office.*
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_mst_office office on app.office_id=office.office_id                              
                            where app.doc_reg_date  between ? and ? and $check_stamp_flag=?  and check_in_flag=?", array($from_date_db, $to_date_db, $flag_value,'Y'));
        return $result;
    }
     public function application_document($token_no,$doc_lang){
        return $this->query("SELECT app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            where app.token_no=?", array($token_no));
         
    }
public function document_disposal_review($doc_lang){
         
        return $this->query("SELECT office.*,app.*,article.*,appoint.appointment_id, party.party_full_name_$doc_lang,
                            appoint.appointment_date,appoint.sheduled_time
                            FROM ngdrstab_trn_application_submitted app
                            left outer join ngdrstab_trn_generalinformation info on app.token_no=info.token_no
                            left outer join ngdrstab_mst_article article on info.article_id=article.article_id
                            left outer join ngdrstab_trn_appointment_details appoint on app.token_no=appoint.token_no
                            left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                            left outer join ngdrstab_mst_office office on app.office_id=office.office_id 
                            where app.disposal_flag='Y' and disposal_review_flag='N' ");
         
    }
   
    public function document_disposal_summery($from_date, $to_date, $col_date, $col_flag) {

        $from_date_db = date("Y-m-d", strtotime($from_date));
        $to_date_db = date("Y-m-d", strtotime($to_date));

        $result = $this->query("SELECT app.token_no,app.doc_reg_date,party.party_full_name_en,app.doc_reg_no,article.article_desc_en,title.title_name,
                                info.no_of_pages,lang.language_name
                                
                                FROM  ngdrstab_trn_application_submitted app
                                LEFT join    ngdrstab_trn_generalinformation info ON  app.token_no=info.token_no 
                                LEFT join    ngdrstab_mst_article article   ON info.article_id=article.article_id 
                                LEFT join    ngdrstab_mst_document_title title ON   info.title_id=title.id
                                LEFT join   ngdrstab_mst_language lang ON info.local_language_id=lang.id 
                                left outer join ngdrstab_trn_document_disposal tds on app.token_no=tds.token_no
                                left outer join ngdrstab_mst_document_disposal mds on tds.disposal_id=mds.disposal_id  
                                left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                                where app.$col_date  between ? and ? and $col_flag='Y' GROUP BY "
                                 . "app.token_no,app.doc_reg_date,party.party_full_name_en,app.doc_reg_no,article.article_desc_en,title.title_name,
                                info.no_of_pages,lang.language_name", array($from_date_db, $to_date_db));
                            return $result;
    }
    
    
    public function document_disposal_summery2($from_date, $to_date, $col_date, $col_flag) {

        $from_date_db = date("Y-m-d", strtotime($from_date));
        $to_date_db = date("Y-m-d", strtotime($to_date));

        $result = $this->query("SELECT app.token_no,app.doc_reg_date,party.party_full_name_en,app.doc_reg_no,article.article_desc_en,title.title_name,
                                info.no_of_pages,lang.language_name,mds.disposal_desc_en
                                
                                FROM  ngdrstab_trn_application_submitted app
                                LEFT join    ngdrstab_trn_generalinformation info ON  app.token_no=info.token_no 
                                LEFT join    ngdrstab_mst_article article   ON info.article_id=article.article_id 
                                LEFT join    ngdrstab_mst_document_title title ON   info.title_id=title.id
                                LEFT join   ngdrstab_mst_language lang ON info.local_language_id=lang.id 
                                left outer join ngdrstab_trn_document_disposal tds on app.token_no=tds.token_no
                                left outer join ngdrstab_mst_document_disposal mds on tds.disposal_id=mds.disposal_id  
                                left outer join ngdrstab_trn_party_entry_new party on app.token_no=party.token_no and party.is_presenter='Y'
                                where app.$col_date  between ? and ? and $col_flag='Y'", array($from_date_db, $to_date_db));
                            return $result;
    }
    public function party_information_exchange_property($token,$party_type_flag) {

        $party = $this->query("SELECT 
 party.party_id,
 party.property_id,
 party.party_type_id,
 ptype.party_type_flag   
FROM  ngdrstab_trn_party_entry_new party 
LEFT join    ngdrstab_mst_party_type ptype ON  ptype.party_type_id=party.party_type_id  
 WHERE   ptype.party_type_flag=? and party.token_no=? ", array($party_type_flag,$token)); 
        return $party;
    }
    
    
public function srchdetails($docregno,$office_id,$myDate)
	{
		
		//$articleslct=$this->query("select c.article_id,amt_paid,article_desc_en from ngdrstab_trn_application_submitted b inner join ngdrstab_trn_generalinformation a  on a.token_no=b.token_no inner join ngdrstab_mst_article c on a.article_id=c.article_id where doc_reg_no='2018-19/2/571' and date(doc_reg_date)='2018-05-17' and b.office_id='2'");
		$articleslct=$this->query("select b.token_no,c.article_id,amt_paid,article_desc_en from ngdrstab_trn_application_submitted b inner join ngdrstab_trn_generalinformation a  on a.token_no=b.token_no inner join ngdrstab_mst_article c on a.article_id=c.article_id where doc_reg_no=? and date(doc_reg_date)=? and b.office_id=?",array($docregno,$myDate,$office_id));
		return $articleslct;
		
	}
	public function srchpartydetails($docregno,$office_id,$myDate)
	{
		//$partydetail=$this->query("select party_fname_en,party_mname_en,party_lname_en,a.party_type_id,party_type_desc_en from ngdrstab_trn_application_submitted b inner join ngdrstab_trn_party_entry_new a on a.token_no=b.token_no inner join ngdrstab_mst_party_type c on a.party_type_id=c.party_type_id where doc_reg_no='2018-19/2/571' and date(doc_reg_date)='2018-05-17' and b.office_id='2'");
		$partydetail=$this->query("select party_fname_en,party_mname_en,party_lname_en,a.party_type_id,party_type_desc_en from ngdrstab_trn_application_submitted b inner join ngdrstab_trn_party_entry_new a on a.token_no=b.token_no inner join ngdrstab_mst_party_type c on a.party_type_id=c.party_type_id where doc_reg_no=? and date(doc_reg_date)=? and b.office_id=?",array($docregno,$myDate,$office_id));
		return $partydetail;
	}
}
