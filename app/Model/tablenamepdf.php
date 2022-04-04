<?php

class tablenamepdf extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_tabnameforpdf';
    public $primaryKey = 'id';

    public function getReference($tablename = NULL) {
        if ($tablename) {
            return $this->Query("SELECT  tc.constraint_name, tc.table_name, kcu.column_name,  ccu.table_name AS foreign_table_name, ccu.column_name AS foreign_column_name 
                    FROM  information_schema.table_constraints AS tc 
                    JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                    JOIN information_schema.constraint_column_usage AS ccu  ON ccu.constraint_name = tc.constraint_name
                    WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name=?", array($tablename));
        } else {
            return "invalid input provided";
        }
    }

    public function getcolumns($tblname = NULL) {
        if ($tblname) {
            return $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ?", array($tblname));
        } else {
            return "invalid input provided";
        }
    }
    public function getalltable(){
        
    }

}
