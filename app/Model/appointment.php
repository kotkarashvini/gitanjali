<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of appointment
 *
 * @author nic
 */
class appointment extends AppModel {
    
    //put your code here
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_appointment_details';
    
    
   function get_appointment_details($office_id,$from,$to)
    {
            $appointment = $this->find('all', array('fields'=>array('appointment_date','flag','totalslot','tatkal_totalslot','count(appointment_date) as reserved'),'conditions' => array('office_id ' => $office_id, 'and' => array(
                       'appointment_date >=' => $from,
                              'appointment_date <=' =>$to
                             )),'group'=>array('appointment_date','totalslot','flag','tatkal_totalslot')));  
      return($appointment);
      
    }
    
    function get_SRO_appointment($office_id,$from,$to)
    {
   
     
       $appointment = $this->find('all', array('fields'=>array('token_no','appointment_date','flag','totalslot','tatkal_totalslot','sheduled_time','slot_no'),'conditions' => array('office_id ' => $office_id, 'and' => array(
                       'appointment_date >=' => $from,
                              'appointment_date <=' =>$to
                             ))));  
      return($appointment);
      
    }
    
}
