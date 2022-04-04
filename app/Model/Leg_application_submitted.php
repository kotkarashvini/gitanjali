<?php

class Leg_application_submitted extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_application_submitted';
    public $primaryKey = 'app_id';
    
              public function get_registration_no_old($final_doc_reg_no) 
                      {
             $data = $this->query("select final_doc_reg_no from ngdrstab_trn_legacy_application_submitted
           
 WHERE final_doc_reg_no=?",array($final_doc_reg_no)); 

        return $data;

        
    }
    

    
    
      public function get_registration_no_oldd($final_doc_reg_no,$year,$office_id) {
             $data = $this->query("select final_doc_reg_no,final_stamp_date,app.office_id  from ngdrstab_trn_legacy_application_submitted app
           inner join ngdrstab_trn_legacy_generalinformation info on info.token_no=app.token_no
 WHERE final_doc_reg_no=? and date_part('year', final_stamp_date)=? and info.office_id=?",array($final_doc_reg_no,$year,$office_id)); 

        return $data;

        
    }
    
    
     public function get_registration_no($year,$office_id,$book_no,$book_serial_no) {
             $data = $this->query("select year,serial_no.office_id,book_number,book_serial_number from ngdrstab_trn_legacy_application_submitted app
inner join ngdrstab_trn_legacy_serial_numbers_final serial_no on serial_no.token_no=app.token_no and serial_no.office_id=app.office_id
 WHERE year=? and serial_no.office_id=? and book_number=? and book_serial_number=?",array($year,$office_id,$book_no,$book_serial_no)); 

        return $data; 
    }
}