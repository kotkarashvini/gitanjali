<?php

App::uses('Sanitize', 'Utility');
App::import('Controller', 'Registration');

class GraphsroController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

//        $this->Security->unlockedActions = array('dashboard', 'getgraph', 'graph1', 'getgraph3');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

        $this->Auth->allow('dashboard');
    }

    public function dashboard() {
        $this->loadModel('damblkdpnd');
        $office_id = $this->Auth->user('office_id');

        $frmdate = $todate = date('Y-m-d');
        $registered = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and office_id=? and DATE(final_stamp_date)>= ? and DATE(final_stamp_date)<=?", array('Y', $office_id, $frmdate, $todate));
        $submitted = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and office_id=? and DATE(token_submit_date)>= ? and DATE(token_submit_date)<=? ", array('N', $office_id, $frmdate, $todate));
        $distcol = $this->damblkdpnd->query("select sum(pamount) as pamount from ngdrstab_trn_payment_details 
                                            where office_id=? and DATE(pdate) >= Date(?)  and DATE(pdate) <= Date(?)", array($office_id, $frmdate, $todate));
        $appointment = $this->damblkdpnd->query("select count(office_id) as total from ngdrstab_trn_appointment_details
                                            where office_id=? and DATE(appointment_date) >= Date(?)  and DATE(appointment_date) <= Date(?)", array($office_id, $frmdate, $todate));
//        pr($office);pr($distcol);exit;
        $frmdate = $todate = date('d-m-Y');
        $this->set(compact('registered', 'submitted', 'frmdate', 'todate', 'distcol', 'appointment', 'office_id'));

        if ($this->request->is('post')) {
//            pr($this->request->data);exit;

            $frmdate = $this->request->data['dashboard']['fromdate'];
            $todate = $this->request->data['dashboard']['todate'];
            $frm = date('Y-m-d', strtotime($frmdate));
            $to = date('Y-m-d', strtotime($frmdate));
            $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
            $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

            $registered = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and office_id=? and DATE(final_stamp_date)>= ? and DATE(final_stamp_date)<=?", array('Y', $office_id, $frmdate, $todate));
            $submitted = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and office_id=? and DATE(token_submit_date)>= ? and DATE(token_submit_date)<=? ", array('N', $office_id, $frmdate, $todate));
            $distcol = $this->damblkdpnd->query("select sum(pamount) as pamount from ngdrstab_trn_payment_details 
                                            where office_id=? and DATE(pdate) >= Date(?)  and DATE(pdate) <= Date(?)", array($office_id, $frmdate, $todate));
        $appointment = $this->damblkdpnd->query("select count(office_id) as total from ngdrstab_trn_appointment_details
                                            where office_id=? and DATE(appointment_date) >= Date(?)  and DATE(appointment_date) <= Date(?)", array($office_id, $frmdate, $todate));
//        pr($office);pr($submitted);exit;
            $frmdate = $this->request->data['dashboard']['fromdate'];
            $todate = $this->request->data['dashboard']['todate'];

            $this->set(compact('registered', 'submitted', 'frmdate', 'todate', 'distcol', 'appointment', 'office_id'));
        }
    }

    public function appointment() {
//                $frmdate = '23-05-2017';
//                $todate =  '23-05-2018';
        $office_id = $_POST['office_id'];
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query("select c.district_name_en,d.taluka_name_en,b.office_name_en, count(a.office_id) as total  From ngdrstab_trn_appointment_details a
                                                join  ngdrstab_mst_office b on  a.office_id =b.office_id
                                                join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                                                join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id 
                                                where a.office_id=? and DATE(a.appointment_date)>= ? and DATE(a.appointment_date)<=?
                                                group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array($office_id, $frmdate, $todate));

        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function acccollection() {

        $laug = $this->Session->read("sess_langauge");
        $this->set('laug', $laug);
        $this->loadModel('damblkdpnd');

//       $frmdate = '23-12-2017';
//        $todate = '23-05-2018';
        $office_id = $_POST['office_id'];
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
//        $frmdate =  date('Y-m-d', strtotime($frmdate)) ;
//        $todate =  date('Y-m-d', strtotime($todate)) ;

        $this->damblkdpnd->query("select * from dashaccheadfee_sro($office_id,$frmdate,$todate)");
        $data = $this->damblkdpnd->query("select b.district_name_en, c.taluka_name_en, d.office_name_en,e.fee_item_desc_en,SUM(a.total) as pay_income from ngdrstab_temp_graphdata1 a 
                                                inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id 
                                                inner join ngdrstab_conf_admblock5_taluka c on a.taluka_id = c.taluka_id
                                                inner join ngdrstab_mst_office d on a.office_id = d.office_id 
                                                inner join ngdrstab_mst_article_fee_items e on a.account_head_code=e.account_head_code 
                                                GROUP BY b.district_name_en,c.taluka_name_en,d.office_name_en,e.fee_item_desc_en");

        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
//        pr($data1);exit;
        $this->set('data', $data1);
    }

    public function docsubmitted() {
//                $frmdate = '23-05-2017';
//                $todate =  '23-05-2018';
        $office_id = $_POST['office_id'];
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query("select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
                                                join  ngdrstab_mst_office b on  a.office_id =b.office_id
                                                join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                                                join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id
                                                where a.final_stamp_flag=? and a.office_id=? and DATE(a.token_submit_date)>= ? and DATE(a.token_submit_date)<=?
                                                group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('N', $office_id, $frmdate, $todate));

        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function docregistered() {

        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
//                 $frmdate = '23-05-2017';
//                $todate =  '23-05-2018';
        $office_id = $_POST['office_id'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query(" select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
                        join  ngdrstab_mst_office b on  a.office_id =b.office_id
                        join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                        join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id
                        where a.final_stamp_flag=?  and a.office_id=? and DATE(a.final_stamp_date)>= ? and DATE(a.final_stamp_date)<=?
                        group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('Y', $office_id, $frmdate, $todate));

        $data1 = array();
//       pr($data);exit;
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }




}
