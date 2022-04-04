<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of 
 *
 * @author nic
 */
class emp_office_link extends AppModel{
    //put your code here
      public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_emp_office_link';
    
    function deleterecord($id)
    {
        try
        {
        $this->query('delete from ngdrstab_mst_emp_office_link where emp_id=?',array($id));
        return true;
        }
 catch (Exception $e)
 {
   return $e;         
 }
}
}
