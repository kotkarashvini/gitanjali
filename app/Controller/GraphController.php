<?php

App::uses('Sanitize', 'Utility');
App::import('Controller', 'Registration');

class GraphController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

        $this->Security->unlockedActions = array('dashboard', 'getgraph', 'graph1', 'getgraph3');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

//        $this->Auth->allow('districtwise', 'bar1', 'bar2', 'srwise', 'reg_doc_registered', 'dashboard_new', 'reg_doc_submitted', 'reg_doc_fee_calculation', 'getgraph1', 'getgraph', 'reg_doc_fee_calculation_feedesc');
    }

     public function dashboard_test() {
        $this->loadModel('damblkdpnd');

        $frmdate = $todate = date('Y-m-d');
        $registered = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and DATE(final_stamp_date)>= ? and DATE(final_stamp_date)<=?", array('Y', $frmdate, $todate));
        $submitted = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and DATE(token_submit_date)>= ? and DATE(token_submit_date)<=? ", array('N', $frmdate, $todate));
        $office = $this->damblkdpnd->query("select count(distinct a.office_id) as total  
                                            From ngdrstab_mst_office a
                                            inner join ngdrstab_trn_generalinformation b on b.office_id = a.office_id");
        $distcol = $this->damblkdpnd->query("select sum(pamount) as pamount from ngdrstab_trn_payment_details 
                                            where DATE(pdate) >= Date(?)  and DATE(pdate) <= Date(?)", array($frmdate, $todate));
        $appointment = $this->damblkdpnd->query("select count(office_id) as total from ngdrstab_trn_appointment_details
                                            where DATE(appointment_date) >= Date(?)  and DATE(appointment_date) <= Date(?)", array($frmdate, $todate));
        $exemption = $this->damblkdpnd->query("select sum(b.final_value) as total
                                                    from ngdrstab_trn_fee_calculation a
                                                    inner join ngdrstab_trn_application_submitted c on c.token_no = a.token_no
                                                    inner join ngdrstab_trn_fee_calculation_detail b on a.fee_calc_id = b.fee_calc_id
                                                    where a.article_id=9998 and c.final_stamp_flag='Y'");
//        pr($office);pr($distcol);exit;
        $frmdate = $todate = date('d-m-Y');
        $this->set(compact('registered', 'submitted', 'office', 'frmdate', 'todate', 'distcol', 'appointment','exemption'));

        if ($this->request->is('post')) {
//            pr($this->request->data);exit;

            $frmdate = $this->request->data['dashboard_test']['fromdate'];
            $todate = $this->request->data['dashboard_test']['todate'];
            $frm = date('Y-m-d', strtotime($frmdate));
            $to = date('Y-m-d', strtotime($frmdate));
            $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
            $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

            $registered = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and DATE(final_stamp_date)>= ? and DATE(final_stamp_date)<=?", array('Y', $frmdate, $todate));
            $submitted = $this->damblkdpnd->query("select count(token_no) as total  From ngdrstab_trn_application_submitted 
                 where final_stamp_flag=?  and DATE(token_submit_date)>= ? and DATE(token_submit_date)<=? ", array('N', $frmdate, $todate));
            $office = $this->damblkdpnd->query("select count(distinct a.office_id) as total  
                                            From ngdrstab_mst_office a
                                            inner join ngdrstab_trn_generalinformation b on b.office_id = a.office_id");
            $distcol = $this->damblkdpnd->query("select sum(pamount) as pamount from ngdrstab_trn_payment_details 
                                            where DATE(pdate) >= Date(?)  and DATE(pdate) <= Date(?)", array($frmdate, $todate));
            $appointment = $this->damblkdpnd->query("select count(office_id) as total from ngdrstab_trn_appointment_details
                                            where DATE(appointment_date) >= Date(?)  and DATE(appointment_date) <= Date(?)", array($frmdate, $todate));
            $exemption = $this->damblkdpnd->query("select sum(b.final_value) as total
                                                    from ngdrstab_trn_fee_calculation a
                                                    inner join ngdrstab_trn_application_submitted c on c.token_no = a.token_no
                                                    inner join ngdrstab_trn_fee_calculation_detail b on a.fee_calc_id = b.fee_calc_id
                                                    where a.article_id=9998 and c.final_stamp_flag='Y'");
//        pr($office);pr($submitted);exit;
            $frmdate = $this->request->data['dashboard_test']['fromdate'];
            $todate = $this->request->data['dashboard_test']['todate'];

            $this->set(compact('registered', 'submitted', 'office', 'frmdate', 'todate', 'distcol', 'appointment','exemption'));
        }
    }

public function office() {
            
        $this->loadModel('damblkdpnd');
        $data = $this->damblkdpnd->query("select c.district_name_en,a.office_name_en, count(distinct a.office_id) as total
                                            From ngdrstab_mst_office a   
                                            inner join ngdrstab_trn_generalinformation b on b.office_id = a.office_id
                                            inner join  ngdrstab_conf_admblock3_district c on c.district_id = a.district_id
                                            group by  c.district_name_en,a.office_name_en");

        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function appointment() {
//                $frmdate = '23-05-2017';
//                $todate =  '23-05-2018';
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query("select c.district_name_en,d.taluka_name_en,b.office_name_en, count(a.office_id) as total  From ngdrstab_trn_appointment_details a
                                                join  ngdrstab_mst_office b on  a.office_id =b.office_id
                                                join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                                                join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id 
                                                where DATE(a.appointment_date)>= ? and DATE(a.appointment_date)<=?
                                                group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array($frmdate, $todate));

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
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
//        $frmdate =  date('Y-m-d', strtotime($frmdate)) ;
//        $todate =  date('Y-m-d', strtotime($todate)) ;

        $this->damblkdpnd->query("select * from dashaccheadfee($frmdate,$todate)");
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

    public function distcollection() {

        $this->loadModel('damblkdpnd');
//        $frmdate = '23-12-2017';
//        $todate = '23-05-2018';
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

        $this->damblkdpnd->query("select * from dashdistrictfee_new($frmdate,$todate)");
        $data = $this->damblkdpnd->query("select b.district_name_en,d.office_name_en, 
                                    a.total
                                    from ngdrstab_temp_graphdata1 a
                                    inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id                                    
                                    inner join ngdrstab_mst_office d on a.office_id = d.office_id");

        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function docsubmitted() {
//                $frmdate = '23-05-2017';
//                $todate =  '23-05-2018';
        $frmdate = $_POST['from'];
        $todate = $_POST['to'];
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";

        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query("select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
                                                join  ngdrstab_mst_office b on  a.office_id =b.office_id
                                                join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                                                join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id where a.final_stamp_flag=?  and DATE(a.token_submit_date)>= ? and DATE(a.token_submit_date)<=?
                                                group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('N', $frmdate, $todate));

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
        $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
        $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
        $this->loadModel('damblkdpnd');

        $data = $this->damblkdpnd->query(" select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
                        join  ngdrstab_mst_office b on  a.office_id =b.office_id
                        join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                        join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id where a.final_stamp_flag=?  and DATE(a.final_stamp_date)>= ? and DATE(a.final_stamp_date)<=?
                        group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('Y', $frmdate, $todate));

        $data1 = array();
//       pr($data);exit;
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function districtwise() {

        $this->set("filter1", null);
        $this->set("filter2", null);
        $this->set('actiontype', NULL);

        $laug = $this->Session->read("sess_langauge");
        $this->set('laug', $laug);

        if ($this->request->is('post')) {

            if ($_POST['actiontype'] == '1') {

                $this->loadModel('damblkdpnd');
                $frmdate = $this->request->data['districtwise']['from'];
                $todate = $this->request->data['districtwise']['to'];
                $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
                $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
                $this->set('filter2', $_POST['filter2']);
                $this->damblkdpnd->query("select * from dashdistrictfee_new($frmdate,$todate)");
                $data = $this->damblkdpnd->query("select b.district_name_en,d.office_name_en, 
                                    a.total
                                    from ngdrstab_temp_graphdata1 a
                                    inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id                                    
                                    inner join ngdrstab_mst_office d on a.office_id = d.office_id");

                $data1 = array();
                foreach ($data as $key => $value) {
                    $data1[$key] = $data[$key][0];
                }
//                PR($data1);exit;
                $this->set('data', $data1);
            }
        }
    }

    public function reg_doc_registered() {

        $this->set('actiontype', NULL);
        $this->set("filter1", null);
        $this->set("filter2", null);
        if ($this->request->is('post')) {

            if ($_POST['actiontype'] == '1') {
                $frmdate = $this->request->data['reg_doc_registered']['from'];
                $todate = $this->request->data['reg_doc_registered']['to'];
                $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
                $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
//                    pr($frmdate);exit;
                $this->set('filter2', $_POST['filter2']);
                $this->loadModel('damblkdpnd');

                $data = $this->damblkdpnd->query(" select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
                        join  ngdrstab_mst_office b on  a.office_id =b.office_id
                        join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
                        join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id where a.final_stamp_flag=?  and a.final_stamp_date BETWEEN ? and ?
                        group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('Y', $frmdate, $todate));

                $data1 = array();
//       pr($data);exit;
                foreach ($data as $key => $value) {
                    $data1[$key] = $data[$key][0];
                }
                $this->set('data', $data1);
            }
        }
    }

    public function reg_doc_submitted() {
        $this->set("filter1", null);
        $this->set("filter2", null);
        $this->set('actiontype', NULL);
        if ($this->request->is('post')) {

            if ($_POST['actiontype'] == '1') {
                $frmdate = $this->request->data['reg_doc_submitted']['from'];
                $todate = $this->request->data['reg_doc_submitted']['to'];
                $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
                $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
//                    pr($frmdate);exit;
                $this->set('filter2', $_POST['filter2']);
                $this->loadModel('damblkdpnd');

                $data = $this->damblkdpnd->query(" select c.district_name_en,d.taluka_name_en,b.office_name_en, count(token_no) as total  From ngdrstab_trn_application_submitted a
join  ngdrstab_mst_office b on  a.office_id =b.office_id
join  ngdrstab_conf_admblock3_district c on c.district_id = b.district_id
join   ngdrstab_conf_admblock5_taluka d on d.taluka_id = b.taluka_id where a.final_stamp_flag=?  and a.final_stamp_date BETWEEN ? and ?
group by  c.district_name_en,d.taluka_name_en,b.office_name_en", array('Y', $frmdate, $todate));

                $data1 = array();
//       pr($data);exit;
                foreach ($data as $key => $value) {
                    $data1[$key] = $data[$key][0];
                }
                $this->set('data', $data1);
            }
        }
    }

    public function reg_doc_fee_calculation() {

        $this->set("filter1", null);
        $this->set("filter2", null);
        $this->set('actiontype', NULL);

        $laug = $this->Session->read("sess_langauge");
        $this->set('laug', $laug);


        $this->loadModel('District');
        $districtdata = $this->District->find('list', array('fields' => array('District.id', 'District.district_name_' . $laug), 'conditions' => array('state_id' => 4), 'order' => 'district_name_' . $laug));
        $taluka = $villagelist = $office = NULL;
        $this->set('districtdata', $districtdata);
        $this->set('taluka', $taluka);
        $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC')));
        $this->set('office', $office);


        if ($this->request->is('post')) {

            if ($_POST['actiontype'] == '1') {
                $this->set('filter1', $_POST['filter1']);
                $this->set('filter2', $_POST['filter2']);
//                pr($this->request->data);exit;
                $this->loadModel('damblkdpnd');
                $frmdate = $this->request->data['reg_doc_fee_calculation']['from'];
                $todate = $this->request->data['reg_doc_fee_calculation']['to'];
                $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
                $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
                $district_id = $this->request->data['reg_doc_fee_calculation']['district_id'];
                $office_id = $this->request->data['reg_doc_fee_calculation']['office_id'];

//                 $officeid = $this->Auth->User('office_id');

                if (!empty($district_id) && is_numeric($district_id) && !empty($office_id) && is_numeric($office_id)) {
                    $this->damblkdpnd->query("select * from dashdistrictfee_officewise($frmdate,$todate,$district_id,$office_id)");
                    $data = $this->damblkdpnd->query("select b.district_name_en, c.taluka_name_en, d.office_name_en,e.fee_item_desc_en,SUM(a.total) as pay_income from ngdrstab_temp_graphdata1 a 
                                                inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id 
                                                inner join ngdrstab_conf_admblock5_taluka c on a.taluka_id = c.taluka_id
                                                inner join ngdrstab_mst_office d on a.office_id = d.office_id 
                                                inner join ngdrstab_mst_article_fee_items e on a.account_head_code=e.account_head_code 
                                                GROUP BY b.district_name_en,c.taluka_name_en,d.office_name_en,e.fee_item_desc_en");
                } else {
                    $this->damblkdpnd->query("select * from dashdistrictfee($frmdate,$todate,$district_id)");
                    $data = $this->damblkdpnd->query("select b.district_name_en, c.taluka_name_en, d.office_name_en,e.fee_item_desc_en,SUM(a.total) as pay_income from ngdrstab_temp_graphdata1 a 
                                                inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id 
                                                inner join ngdrstab_conf_admblock5_taluka c on a.taluka_id = c.taluka_id
                                                inner join ngdrstab_mst_office d on a.office_id = d.office_id 
                                                inner join ngdrstab_mst_article_fee_items e on a.account_head_code=e.account_head_code 
                                                GROUP BY b.district_name_en,c.taluka_name_en,d.office_name_en,e.fee_item_desc_en");
                }

                $data1 = array();
//       pr($data);exit;
                foreach ($data as $key => $value) {
                    $data1[$key] = $data[$key][0];
                }
                $this->set('data', $data1);
            }
        }
    }

    public function dashboard_new() {
        
    }

    function get_office_list() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('office');
            $doc_lang = $this->Session->read('doc_lang');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {

                $tal = $this->request->data['tal'];

                if (isset($this->request->data['village']) && $this->request->data['village'] != '') {
                    $village = $this->request->data['village'];


                    $options1['conditions'] = array('ov.village_id' => trim($village));
                    $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                    );
                    $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                    $office = $this->office->find('list', $options1);
                    if (empty($office)) {
                        $options1['conditions'] = array('ov.taluka_id' => trim($tal));
                        $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                        );
                        $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                        $office = $this->office->find('list', $options1);
                    }
                } elseif (isset($tal) && $tal != '') {

                    $options1['conditions'] = array('ov.taluka_id' => trim($tal));
                    $options1['joins'] = array(array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'ov', 'type' => 'INNER', 'conditions' => array('ov.office_id=office.office_id')),
                    );
                    $options1['fields'] = array('office.office_id', 'office.office_name_' . $lang);
                    $office = $this->office->find('list', $options1);
                }

//                if (empty($office)) {
//
//                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang)));
//                }

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);


                $result_array = array('office' => $office);
                $json2array['office'] = $office;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {

                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function taluka_change_event() {
        try {
//            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($this->request->data['tal']) and is_numeric($this->request->data['tal'])) {
                $tal = $this->request->data['tal'];

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                $result_array = array('village' => $villagelist);


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function district_change_event() {
        try {
            $this->check_csrf_token_withoutset($this->request->data['csrftoken']);
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($this->request->data['dist'])) {
                $laug = $this->Session->read("sess_langauge");
                $doc_lang = $this->Session->read('doc_lang');
                $dist = $this->request->data['dist'];
                $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $laug), 'conditions' => array('district_id' => $dist)));

                $result_array = array('subdiv' => NULL, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => NULL, 'village' => NULL);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function srwise() {

        $this->set("filter1", null);
        $this->set("filter2", null);
        $this->loadModel('damblkdpnd');
        $data = $this->damblkdpnd->query("select b.district_name_en, c.taluka_name_en, d.office_name_en, e.usage_main_catg_desc_en,
                                    f.usage_sub_catg_desc_en, g.usage_sub_sub_catg_desc_en, h.fee_item_desc_en,a.total
                                    from ngdrstab_temp_graphdata1 a
                                    inner join ngdrstab_conf_admblock3_district b on a.district_id = b.district_id
                                    inner join ngdrstab_conf_admblock5_taluka c on a.taluka_id = c.taluka_id
                                    inner join ngdrstab_mst_office d on a.office_id = d.office_id
                                    inner join ngdrstab_mst_usage_main_category e on a.usage_main_catg_id = e.usage_main_catg_id
                                    inner join ngdrstab_mst_usage_sub_category f on a.usage_sub_catg_id = f.usage_sub_catg_id
                                    left join ngdrstab_mst_usage_sub_sub_category g on a.usage_sub_sub_catg_id = g.usage_sub_sub_catg_id
                                    inner join ngdrstab_mst_article_fee_items h on a.fee_item_id = h.fee_item_id");
        $data1 = array();
        foreach ($data as $key => $value) {
            $data1[$key] = $data[$key][0];
        }
        $this->set('data', $data1);
    }

    public function bar2() {

        $this->set("hfid", null);
        $this->loadModel('damblkdpnd');
        $data = $this->damblkdpnd->query(" select * from graphdata");

        $result = array();
        $i = 0;
        foreach ($data as $key => $value) {
            foreach (array_keys($value[0]) as $key1 => $value1) {
                if ($value1 == "male") {
                    $result[$i]['distname'] = $value[0]['distname'];
                    $result[$i]['officename'] = $value[0]['officename'];
                    $result[$i]['document_type'] = $value[0]['document_type'];
                    $result[$i]['model'] = $value1;
                    $result[$i]['Total'] = $value[0]['male'];
                    $i++;
                } else if ($value1 == "female") {
                    $result[$i]['distname'] = $value[0]['distname'];
                    $result[$i]['officename'] = $value[0]['officename'];
                    $result[$i]['document_type'] = $value[0]['document_type'];
                    $result[$i]['model'] = $value1;
                    $result[$i]['Total'] = $value[0]['female'];
                    $i++;
                }
            }
        }
        $this->set('data', $result);
    }

    public function bar1() {
        $this->set("filter1", null);
        $this->set("filter2", null);
        $this->loadModel('damblkdpnd');
        $data = $this->damblkdpnd->query(" select * from graphdata");
//pr($data);
        $result = array();
        $i = 0;
        foreach ($data as $key => $value) {
            foreach (array_keys($value[0]) as $key1 => $value1) {

                if ($value1 == "male") {
                    $result[$i]['distname'] = $value[0]['distname'];
                    $result[$i]['officename'] = $value[0]['officename'];
                    $result[$i]['document_type'] = $value[0]['document_type'];
                    $result[$i]['model'] = $value1;
                    $result[$i]['Total'] = $value[0]['male'];
                    $i++;
                } else if ($value1 == "female") {
                    $result[$i]['distname'] = $value[0]['distname'];
                    $result[$i]['officename'] = $value[0]['officename'];
                    $result[$i]['document_type'] = $value[0]['document_type'];
                    $result[$i]['model'] = $value1;
                    $result[$i]['Total'] = $value[0]['female'];
                    $i++;
                }
            }
        }
//        pr($result);
//        exit;
//        $result1 = Array(
//            Array(
//                'office_name_en' => 'Sub-Registrar Office Moga',
//                'count' => 'Registered',
//                'id' => 21
//            ),
//            Array(
//                'office_name_en' => 'Sub-Registrar Office Moga',
//                'count' => 'Submitted',
//                'id' => 38
//            ),
//            Array(
//                'office_name_en' => 'Sub-Registrar Adampur',
//                'count' => 'Registered',
//                'id' => 12
//            ),
//            Array(
//                'office_name_en' => 'Sub-Registrar Adampur',
//                'count' => 'Submitted',
//                'id' => 20
//            )
//        );
//
//        pr($result);
//        exit;
        $this->set('data', $result);
    }

    public function graph1() {
        
    }

    public function getgraph() {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
//            $animal_name = 'wombat';
//            $data = $this->damblkdpnd->query("SELECT total,record_date FROM animals WHERE name=?", array($animal_name));
//            $data = $this->damblkdpnd->query("SELECT officename,empno FROM office");
            $data = $this->damblkdpnd->query("SELECT  
                                                OFFICE.office_id,
                                                OFFICE.office_name_en,
                                                SUM(PAY.pamount)  as income
                                                FROM ngdrstab_mst_office OFFICE
                                                LEFT JOIN ngdrstab_trn_application_submitted APP ON APP.office_id=OFFICE.office_id
                                                LEFT JOIN ngdrstab_trn_payment_details  PAY ON PAY.token_no=APP.token_no
                                                GROUP BY OFFICE.office_id,OFFICE.office_name_en");
//            $data = ClassRegistry::init('Animals')->find('list', array('fields' => array('total', 'record_date'),'conditions' => array('name' => array($animal_name))));
//            pr($data);
            $data1 = array();
            foreach ($data as $key => $value) {
                $data1[$key] = $data[$key][0];
            }
//            pr($data1);
            echo json_encode($data1);
            exit;
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function dashboard() {
        try {
            $this->set('status', NULL);

            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'mainlanguage'));
            $lang = $this->Session->read("sess_langauge");
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['fromdate']['text'] = 'is_required';
            $fieldlist['todate']['text'] = 'is_required';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['dashboard']['csrftoken']);
                $this->set('status', 'show');
//            pr($this->request->data);exit;
                $fd = $this->request->data['dashboard']['fromdate'];
                $td = $this->request->data['dashboard']['todate'];
                $g1 = $this->getgraph1($fd, $td);
                $this->set('datagraph1', $g1);
                $g2 = $this->getgraph2($fd, $td);
                $this->set('datagraph2', $g2);
                $g3 = $this->getgraph3();
                $this->set('datagraph3', $g3);
                $g4 = $this->getgraph4($fd, $td);
                $this->set('datagraph4', $g4);
                $g5 = $this->getgraph5($fd, $td);
////                pr($g1);pr($g2);pr($g3);pr($g4);
////                pr($g5);exit;
                $this->set('datagraph5', $g5);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgraph1($fd, $td) {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $fd = date('Y-m-d', strtotime($fd));
            $td = date('Y-m-d', strtotime($td));
            $data = $this->damblkdpnd->query("SELECT  
                                                OFFICE.office_id,
                                                OFFICE.office_name_en,
                                                SUM(PAY.pamount)  as income
                                                FROM ngdrstab_mst_office OFFICE
                                                LEFT outer JOIN ngdrstab_trn_application_submitted APP ON APP.office_id=OFFICE.office_id
                                                LEFT outer JOIN ngdrstab_trn_payment_details  PAY ON PAY.token_no=APP.token_no
                                                where DATE(PAY.created) >=? and DATE(PAY.created) <= ? and OFFICE.dashboard_display_flag = ?
                                                GROUP BY OFFICE.office_id,OFFICE.office_name_en", array($fd, $td, 'Y'));


//            pr($data);exit;
            $data1 = array();
            foreach ($data as $key => $value) {
                $data1[$key] = $data[$key][0];
            }
            return $data1;
        } catch (Exception $exc) {
//            pr($exc);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgraph2($fd, $td) {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $todayDate = date('Y-m-d');
            $fd = date('Y-m-d', strtotime($fd));
            $td = date('Y-m-d', strtotime($td));
//            $data = $this->damblkdpnd->query("SELECT total,record_date FROM animals WHERE name=?", array($animal_name));
//            $data = $this->damblkdpnd->query("SELECT count(document_scan_flag) from ngdrstab_trn_application_submitted where document_scan_flag = 'N'");
//            $data1 = $this->damblkdpnd->query("SELECT count(document_scan_flag) from ngdrstab_trn_application_submitted where document_scan_flag = 'N' and document_scan_date='$todayDate'");

            $reg = new RegistrationController();
            $reg->constructClasses();
//            $finalStamp = null;
//            $stamp = $reg->stamp_and_functions_config();
//            foreach ($stamp as $stamp1) {
//                if ($stamp1['is_last'] == 'Y') {
//                    $finalStamp = $stamp1['stamp_flag'];
//                }
//            }
//            $finalStampdate = str_replace("flag", "date", $finalStamp);
            $data = $this->damblkdpnd->query("SELECT count(id) from ngdrstab_trn_application_submitted where document_scan_flag = 'N' and final_stamp_flag = 'Y' and DATE(created) >= '" . $fd . "' and DATE(created) <= '" . $td . "'");
            $data1 = $this->damblkdpnd->query("SELECT count(id) from ngdrstab_trn_application_submitted where document_scan_flag = 'N' and final_stamp_flag = 'Y'  and DATE(created) = '" . $todayDate . "'");
            $data2 = $this->damblkdpnd->query("SELECT count(id) from ngdrstab_trn_application_submitted where final_stamp_flag = 'Y'  and DATE(final_stamp_date) >= '" . $fd . "' and DATE(final_stamp_date) <= '" . $td . "'");
            $data3 = $this->damblkdpnd->query("SELECT count(id) from ngdrstab_trn_application_submitted where final_stamp_flag = 'N'  and DATE(created) >= '" . $fd . "' and DATE(created) <= '" . $td . "'");
            $result['Total Scan Pend'] = $data[0][0]['count'];
            $result['Today Scan Pend'] = $data1[0][0]['count'];
            $result['Registered'] = $data2[0][0]['count'];
            $result['Pending'] = $data3[0][0]['count'];

            return($result);
        } catch (Exception $exc) {
//            pr($exc);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgraph3() {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $todayDate = date('Y-m-d');

            $data = $this->damblkdpnd->query("select count(*) from ngdrstab_mst_loginusers where logindate=? and logintime < ?", array("$todayDate", "10:00:00"));
            $data1 = $this->damblkdpnd->query("select count(*) from ngdrstab_mst_loginusers where logindate=? and logintime < ?", array("$todayDate", "07:00:00"));
            $data2 = $this->damblkdpnd->query("select count(*) from ngdrstab_mst_loginusers where logindate=? and logouttime is null", array("$todayDate"));
            $data3 = $this->damblkdpnd->query("select count(*) from ngdrstab_mst_loginusers where logindate=? and logouttime is not null", array("$todayDate"));

            $result['Login Before 10am'] = $data[0][0]['count'];
            $result['Login Before 7am'] = $data1[0][0]['count'];
            $result['Open'] = $data2[0][0]['count'];
            $result['Closed'] = $data3[0][0]['count'];
            return($result);
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgraph4($fd, $td) {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $fd = date('Y-m-d', strtotime($fd));
            $td = date('Y-m-d', strtotime($td));
            $data = $this->damblkdpnd->query("select o.office_name_en,count(a.office_id)
                                                from ngdrstab_trn_appointment_details a
                                                inner join ngdrstab_mst_office o on o.office_id=a.office_id
                                                where o.office_id=a.office_id
                                                and DATE(a.appointment_date) >= ? and DATE(a.appointment_date) <= ? and o.dashboard_display_flag = ?
                                                group by o.office_name_en", array($fd, $td, 'Y'));
            $data1 = array();
            foreach ($data as $key => $value) {
//                pr($key);
//                 pr($value);
                $data1[$value[0]['office_name_en']] = $data[$key][0]['count'];
            }

            return $data1;
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgraph5($fd, $td) {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $fd = date('Y-m-d', strtotime($fd));
            $td = date('Y-m-d', strtotime($td));
            $reg = new RegistrationController();
            $reg->constructClasses();
//            $finalStamp = null;
//            $stamp = $reg->stamp_and_functions_config();
//            foreach ($stamp as $stamp1) {
//                if ($stamp1['is_last'] == 'Y') {
//                    $finalStamp = $stamp1['stamp_flag'];
//                }
//            }
            $data = $this->damblkdpnd->query(" select o.office_name_en,count(a.id)
                                                from ngdrstab_mst_office o
                                                left outer join ngdrstab_trn_application_submitted a on o.office_id=a.office_id and a.final_stamp_flag = 'Y'
                                                where  o.dashboard_display_flag=?
                                                group by o.office_name_en", array('Y'));
//            select office_name_en,
//(select  count(*)  from ngdrstab_trn_application_submitted where  created BETWEEN '2016-01-20 16:43:31' and '2017-06-20 16:43:31' ) as app_count
// from ngdrstab_mst_office o where o.dashboard_display_flag = 'Y' 

            $data1 = array();
            foreach ($data as $key => $value) {
                $data1[$key] = $data[$key][0];
            }
            return $data1;
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
