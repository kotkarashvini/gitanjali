<?php

class doc_levels extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_doc_status';

    public function insert_appl_level($appl_id) {
        try {
            //call to database stored procedure
            $this->query("select * from ngpr_fn_insertappl_level(?)",array($appl_id));
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function get_status($result1) {
        try {
            $q = $this->query('select s.* ,l.status_code from ngdrstab_mst_doc_status as s,ngdrstab_mst_statuscheck as l where s.level_id=l.status_id and s.token_id =?',array($result1));
            return $q;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function get_alllevel() {
        try {
//             echo 1;exit;
            $q = $this->query("select * from ngdrstab_mst_statuscheck order by status_code");
         
            return $q;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function updateApplLevel($tokenval, $level_id) {
        try {
            $q2 = $this->query("update ngdrstab_mst_doc_status set completed_status='Y' where level_id=? and token_id=?" ,array($level_id,$tokenval));
            return $q2;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

//    public function insert_appl_stage($appl_id) {
//        try {
//
//            $this->query("insert into ctmstab_trn_appstages(stage_id,app_id,created_date) values(1," . $appl_id . ",now())");
//        } catch (Exception $e) {
//            $this->redirect(array('action' => 'error404'));
//        }
//    }
//
//    public function insert_appl_stage2($appl_id) {
//        try {
//
//            //call to database stored procedure 
//            $this->query("insert into ctmstab_trn_appstages(stage_id,app_id,created) values(2," . $appl_id . ",now())");
//        } catch (Exception $e) {
//            $this->redirect(array('action' => 'error404'));
//        }
//    }
}
