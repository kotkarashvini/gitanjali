<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of division
 *
 * @author Acer
 */
class tablelist extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_alltables_list';

//    var $virtualFields = array(
//        'name' => "CONCAT(division.division_name)"
//    );

    function get_record($column, $audittable, $from, $to, $token_no) {
        $from = date('Y-m-d', strtotime(str_replace('/', '-', $from)));
        $to = date('Y-m-d', strtotime(str_replace('/', '-', $to)));
        if ($token_no) {
          
            return $this->query("select updated_date," . $column . ",old_userid,new_userid from audit_trail." . $audittable . " where '" . $from . "' <= updated_date and updated_date <= '" . $to . "' and token_no=" . $token_no);
        } else {
            
            return $this->query("select updated_date," . $column . ",old_userid,new_userid from audit_trail." . $audittable . " where '" . $from . "' <= updated_date and updated_date <= '" . $to . "'");
        }
    }

    function get_curentrecord($column, $table, $from, $to, $token_no) {
        $from = date('Y-m-d', strtotime(str_replace('/', '-', $from)));
        $to = date('Y-m-d', strtotime(str_replace('/', '-', $to)));
        if ($token_no) {
            return $this->query("select updated," . $column . " from " . $table . " where '" . $from . "' <= updated and updated <= '" . $to . "' and token_no=" . $token_no);
        } else {
            return $this->query("select updated," . $column . " from " . $table . " where '" . $from . "' <= updated and updated <= '" . $to . "'");
        }
    }
    
    function getcolumns($tblname = NULL) 
    {
         //return $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ?", array($tblname));
      
         return $this->Query("SELECT
    cols.column_name,
    (
        SELECT
            pg_catalog.col_description(c.oid, cols.ordinal_position::int)
        FROM
            pg_catalog.pg_class c
        WHERE
            c.oid = (SELECT ('' || cols.table_name || '')::regclass::oid)
            AND c.relname = cols.table_name
    ) AS column_comment
FROM
    information_schema.columns cols
WHERE
  
     cols.table_name   = ?
    AND cols.table_schema = ?",array($tblname,"public"));
         
    }

}
