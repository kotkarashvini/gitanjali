<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usagesubsubcategory
 *
 * @author Administrator
 */
class getDatabyTable extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_usage_items_list';

    public function getData($tableName = Null) {
        //echo $tableName;
        //exit;
        $columnNames = $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ?", array($tableName));
        $referencedcolumn = $this->Query("SELECT
                        tc.constraint_name, tc.table_name, kcu.column_name, 
                        ccu.table_name AS foreign_table_name,
                        ccu.column_name AS foreign_column_name 
                    FROM 
                        information_schema.table_constraints AS tc
                        JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                        JOIN information_schema.constraint_column_usage AS ccu  ON ccu.constraint_name = tc.constraint_name
                        WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name=?", array($tableName));

        $jointable = " ";
        $query = " Select ";
        $finalColumn = NULL;
        $columnLabel = NULL;
        $JT = 1;
        foreach ($columnNames as $colname) {
            $joinflag = 0;
            $colname = $colname[0]['column_name'];
            $arr = explode("_", $colname, 2);
            foreach ($referencedcolumn as $rcolumn) {
                if ($colname == $rcolumn[0]['column_name']) {
                    $finalColumn.="this." . $colname . ",";
                    $joinflag = 1;
                    $jointable.="left outer join " . $rcolumn[0]['foreign_table_name'] . " t" . $JT . " on this." . $colname . " = " . " t" . $JT . "." . $rcolumn[0]['foreign_column_name'] . " ";
                    $columnname = null;
                    if ($arr[0] == 'user') {
                        $columnname = $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ? and column_name like '%$arr[0]%name%' limit 1", array($rcolumn[0]['foreign_table_name']));
                    } else {
                        $columnname = $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ? and column_name like '%name%'", array($rcolumn[0]['foreign_table_name']));
                        if (!$columnname) {
                            $columnname = $this->Query("select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = ? and column_name like '%desc%'", array($rcolumn[0]['foreign_table_name']));
                        }
                    }

                    foreach ($columnname as $clnm) {
                        $finalColumn.="t" . $JT . "." . $clnm[0]['column_name'] . ",";
                    }
                    $JT++;
                }
            }
            if (!$joinflag) {
                if (count($arr) > 1 && $arr[1] == "date") {
                    $finalColumn.="TO_CHAR(this." . $colname . ",'dd-mm-yyyy') " . $colname . ",";
                } else {
                    $finalColumn.="this." . $colname . ",";
                }
            }
        }
        $columnnames = substr($finalColumn, 0, -1);
        $query .= " " . $columnnames . " from " . $tableName . " this ";        
        if ($referencedcolumn) {
            $query.=" " . $jointable;
        }
        $query = substr($query, 0, -1) . " order by 1,2,3 desc";
        echo $query;exit;
        return $this->query($query);
    }

}
