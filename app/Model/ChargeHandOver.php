<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChargeHandOver
 *
 * @author nic
 */
class ChargeHandOver extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_charge_hand_over';
    public $primaryKey='hand_over_id';
//    public $belongsTo = array(
//        'User' => array(
//            'className' => 'User',
//            'foreignKey' => 'from_user_id'
//        ),
//          'office' => array(
//            'className' => 'office',
//            'foreignKey' => 'from_office_id'
//        ),
//        'User' => array(
//            'className' => 'User',
//            'foreignKey' => 'to_user_id'
//        ),
//          'office' => array(
//            'className' => 'office',
//            'foreignKey' => 'to_office_id'
//        )
//    );
    public function fieldlist(){
      $fieldlist['from_office_id']['select']='is_select_req';
      $fieldlist['from_user_id']['select']='is_select_req'; 
      $fieldlist['to_office_id']['select']='is_select_req';
      $fieldlist['to_user_id']['select']='is_select_req';
      $fieldlist['from_date']['text']='is_required'; // 
      $fieldlist['to_date']['text']='is_required';
      return $fieldlist;
    } 
    public function Charge_hand_over_list($lang,$data=NULL){
        
        if(!is_null($data)){
            $now=$data['curr_date'];
            $idlist=$this->query("SELECT * FROM ngdrstab_trn_charge_hand_over_details WHERE DATE(single_date) = '$now'");
           $str="";
            foreach ($idlist as $record){
                $str.=",'".$record[0]['hand_over_id']."'";             
            }
            $str= substr($str, 1);
            if(empty($str)){
               $str="'0'";
            } 
           // pr($str);exit;
              return $this->query("SELECT hand.hand_over_id, hand.from_date,hand.to_date, from_office.office_name_$lang as FROM_OFFICE , to_office.office_name_$lang as TO_OFFICE ,from_user.full_name as FROM_USER,to_user.full_name as TO_USER  FROM  ngdrstab_trn_charge_hand_over hand
                                LEFT join    ngdrstab_mst_office from_office ON  from_office.office_id=hand.from_office_id 
                                LEFT join    ngdrstab_mst_office to_office ON  to_office.office_id=hand.to_office_id 
                                LEFT join    ngdrstab_mst_user from_user ON  from_user.user_id=hand.from_user_id 
                                LEFT join    ngdrstab_mst_user to_user ON  to_user.user_id=hand.to_user_id 
                                WHERE hand_over_id IN($str)");  
        }else{
           $now=date("Y-m-d");           
           return $this->query("SELECT hand.hand_over_id, hand.from_date,hand.to_date, from_office.office_name_$lang as FROM_OFFICE , to_office.office_name_$lang as TO_OFFICE ,from_user.full_name as FROM_USER,to_user.full_name as TO_USER  FROM  ngdrstab_trn_charge_hand_over hand
                                LEFT join    ngdrstab_mst_office from_office ON  from_office.office_id=hand.from_office_id 
                                LEFT join    ngdrstab_mst_office to_office ON  to_office.office_id=hand.to_office_id 
                                LEFT join    ngdrstab_mst_user from_user ON  from_user.user_id=hand.from_user_id 
                                LEFT join    ngdrstab_mst_user to_user ON  to_user.user_id=hand.to_user_id 
                                WHERE DATE(hand.created) = '$now'");
        }
        
       
        
    }
    
    
}
