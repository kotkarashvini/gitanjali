<?php

App::import('Controller', 'WebService'); // mention at top
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchController
 *
 * @author nic
 */
class MHSearchController extends AppController {

    //put your code here

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('srosearch');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }

        $this->Auth->allow('searchindex');
    }

    public function searchindex() {
        try {
            array_map([$this, 'loadModel'], ['Searcher']);
            $stateid = $this->Auth->User('state_id');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $this->set('todaydate', date("d/m/Y"));
            $this->set('District', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $lang => 'ASC'))));
            $this->set('office', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('office_name_' . $lang => 'ASC'))));

            $fieldlist = array();
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['applicant_name']['text'] = 'is_required,is_alphanumeric';
            $fieldlist['fromdate']['text'] = 'is_required';
            $fieldlist['todate']['text'] = 'is_required';

            $fieldlist['email_id']['text'] = 'is_required,is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['address_en']['text'] = 'is_alphanumspacedashdotslashroundbrackets,is_maxlength255';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['searchindex']['csrftoken']);
//                pr($this->request->data);  exit;
                $district_id = $this->request->data['searchindex']['district_id'];
                $this->request->data['searchindex']['application_date'] = date('Y/m/d H:i:s');
                $frmdate = $this->request->data['searchindex']['fromdate'];
                $todate = $this->request->data['searchindex']['todate'];
                $fromyear = date('Y', strtotime($frmdate));
                $toyear = date('Y', strtotime($todate));
                $frmdate = date('Y-m-d', strtotime($frmdate));
                $todate = date('Y-m-d', strtotime($todate));
//                $frmdate = "'" . date('Y-m-d', strtotime($frmdate)) . "'";
//                $todate = "'" . date('Y-m-d', strtotime($todate)) . "'";
                $datedata = $this->Searcher->query("select * from ngdrstab_conf_date where state_id=$stateid");
                $old_date = date('Y-m-d', strtotime($datedata[0][0]['old_date']));
                $new_date = date('Y-m-d', strtotime($datedata[0][0]['new_date']));
                if ($frmdate <= $todate && $todate >= $new_date && $frmdate >= $new_date) {
                    $this->request->data['searchindex']['fromdate'] = $frmdate;
                    $this->request->data['searchindex']['todate'] = $todate;
                    $this->request->data['searchindex']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $this->request->data['searchindex']['user_id'] = $this->Auth->User("user_id");
                    $this->request->data['searchindex']['state_id'] = $stateid;
                    $errarr = $this->validatedata($this->request->data['searchindex'], $fieldlist);
                    if ($this->validationError($errarr)) {
                        if ($this->Searcher->save($this->request->data['searchindex'])) {
                            $last_id = $this->Searcher->getLastInsertId();
                            $application_id = ClassRegistry::init('Searcher')->find('all', array('fields' => array('application_id'), 'conditions' => array('id' => $last_id)));
                            $this->Session->write("application_id", $application_id[0]['Searcher']['application_id']);
                            $this->Session->setFlash(__('The search application submitted.'));
                            $this->redirect(array('controller' => 'MHSearch', 'action' => 'searchdefault', $district_id, $frmdate, $todate));
//                                $this->set('documentrecord', $this->document->find('all'));
                        } else {
                            $this->Session->setFlash(__('Something went wrong ... !!!!'));
                        }
                    }
                } else if ($frmdate <= $todate && $todate <= $old_date && $frmdate <= $old_date) {

                    pr("i m in old data base");
                } else {

                    pr(" Pleae select from and to date either less than $old_date or more than $new_date...!!!");
                }
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
//            pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function searchdefault($district_id, $fromyear, $toyear) {
        try {
//            pr($district_id);
//            pr($fromyear);
//            pr($toyear);
//            exit;

            array_map([$this, 'loadModel'], ['Searcher']);
            $stateid = $this->Auth->User('state_id');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $fromyear = date('d-m-Y', strtotime($fromyear));
            $toyear = date('d-m-Y', strtotime($toyear));
            $this->set('fromyear', $fromyear);
            $this->set('toyear', $toyear);
            $this->set('hffromyear', $fromyear);
            $this->set('hftoyear', $toyear);
            $this->set('action', null);
            $this->set('village', null);
            $this->set('partytype', null);
            $this->set('partygrid', 0);
            $this->set('propertygrid', 0);
            $this->set('deedgrid', 0);
            $this->set('Search', ClassRegistry::init('Search')->find('list', array('fields' => array('id', 'search_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC'))));
            $this->set('article', ClassRegistry::init('article')->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => array('article_desc_' . $lang => 'ASC'))));
            $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'conditions' => array('district_id' => $district_id), 'order' => array('taluka_name_' . $lang => 'ASC'))));
            $this->set('payment_mode_online', ClassRegistry::init('payment_mode')->get_payment_mode_online($lang));

            $fieldlist = array();
            $fieldlist['Search']['select'] = 'is_select_req';
            //party details=1
            $fieldlist['party_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['father_name']['text'] = 'is_alphaspace';
            $fieldlist['fromyear']['text'] = 'is_required';
            $fieldlist['toyear']['text'] = 'is_required';
            $fieldlist['article_id']['select'] = 'is_select_req';
            //      $fieldlist['party_type_id']['select'] = 'is_select_req';
            //property details=2
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
            $fieldlist['khata_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['plot_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['fromyear1']['text'] = 'is_required';
            $fieldlist['toyear1']['text'] = 'is_required';
//            $fieldlist['article_id1']['select'] = 'is_select_req';
            //Deed No=3
            $fieldlist['deed_no']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $info_value = $this->Searcher->query("select info_value from ngdrstab_conf_reg_bool_info where reginfo_id=153");
            $info_value = $info_value[0][0]['info_value'];
            $this->set('info_value', $info_value);

            if ($this->request->is('post')) {
//                $this->check_csrf_token($this->request->data['searchdefault']['csrftoken']);
                // pr( $this->request->data);
                $fieldlistnew = $this->modifysearchfieldlist($fieldlist, $this->request->data['searchdefault']);

//                pr($fieldlistnew);
//                exit;
                $errarr = $this->validatedata($this->request->data['searchdefault'], $fieldlistnew);

//pr($errarr);exit;

                if ($this->ValidationError($errarr)) {
                    $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                    $this->set('hffromyear', $_POST['hffromyear']);
                    $this->set('hftoyear', $_POST['hftoyear']);
                    $this->set('action', $_POST['action']);

                    if ($_POST['action'] == 1) {
                        $this->set('partytype', $json2array['partytype']);
                        $fromyear = $this->request->data['searchdefault']['fromyear'];
                        $toyear = $this->request->data['searchdefault']['toyear'];
//                        $startdate = $fromyear . '-01-01';
//                        $enddate = $toyear . '-12-31';
                        $startdate = date('Y-m-d', strtotime($fromyear));
                        $enddate = date('Y-m-d', strtotime($toyear));
//                        $condition = "art.book_number='BK1' and aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate'";
                        $condition = " aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate'";
                        $party_name = $this->request->data['searchdefault']['party_name'];
                        $article_id = $this->request->data['searchdefault']['article_id'];
                        if (!empty($party_name) && !empty($article_id)) {

                            $party_name = strtoupper($party_name);
                            $condition = $condition . " and upper(p.party_full_name_en) like '%$party_name%'";
                            $condition = $condition . " and gen.article_id=$article_id";

                            if (!empty($this->request->data['searchdefault']['father_name'])) {
                                $father_name = $this->request->data['searchdefault']['father_name'];
                                $father_name = strtoupper($father_name);
                                $condition = $condition . " and upper(p.father_full_name_en) like '%$father_name%'";
                            }

                            if (!empty($this->request->data['searchdefault']['party_type_id'])) {
                                $party_type_id = $this->request->data['searchdefault']['party_type_id'];
                                $condition = $condition . " and p.party_type_id=$party_type_id";
                            }
                            $searchbyparty = $this->Searcher->query("SELECT distinct p.party_id, p.token_no,p.party_id,year.year, aps.final_doc_reg_no,p.party_full_name_en,p.father_full_name_en,p.address_en, p.address2_en
                                                ,art.book_number,volno.volume_number,volno.page_number_start,volno.page_number_end
                                                ,pt.party_type_desc_en,ofs.office_name_en
                                                 FROM ngdrstab_trn_party_entry_new p
                                                join ngdrstab_mst_party_type pt on pt.party_type_id=p.party_type_id 
                                                left join ngdrstab_trn_application_submitted aps on aps.token_no=p.token_no
                                                join ngdrstab_trn_generalinformation gen on gen.token_no=aps.token_no
                                                join ngdrstab_mst_article art on art.article_id=gen.article_id 
                                                LEFT JOIN ngdrstab_trn_volume_number_page_number_entry volno ON volno.token_no=aps.token_no
                                                join ngdrstab_mst_office ofs on ofs.office_id=aps.office_id
                                                join ngdrstab_trn_serial_numbers_final year on year.token_no=aps.token_no
                                                where $condition");
                            $this->set('partygrid', $searchbyparty);
                        } else {
                            $this->Session->setFlash(__('Please fill all mandatory fields... !!!!'));
                        }
                    }

                    if ($_POST['action'] == 2) {
                        $this->set('village', $json2array['village']);
//                    pr($this->request->data);exit;
                        $fromyear = $this->request->data['searchdefault']['fromyear1'];
                        $toyear = $this->request->data['searchdefault']['toyear1'];
                        $taluka_id = $this->request->data['searchdefault']['taluka_id'];
                        $village_id = $this->request->data['searchdefault']['village_id'];
                        if (!empty($taluka_id) && !empty($village_id)) {
//                            $startdate = $fromyear . '-01-01';
//                            $enddate = $toyear . '-12-31';
                            $startdate = date('Y-m-d', strtotime($fromyear));
                            $enddate = date('Y-m-d', strtotime($toyear));
//                            $condition = "art.book_number='BK1' and aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate' and p.taluka_id=$taluka_id and p.village_id=$village_id";
                            $condition = " aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate' and p.taluka_id=$taluka_id and p.village_id=$village_id";

                            if (!empty($this->request->data['searchdefault']['khata_no'])) {
                                $khata_no = $this->request->data['searchdefault']['khata_no'];
                                $condition = $condition . " and pr.paramter_id=205 and pr.paramter_value='$khata_no'";
                            }

                            if (!empty($this->request->data['searchdefault']['plot_no'])) {
                                $plot_no = $this->request->data['searchdefault']['plot_no'];
                                $condition = $condition . " and pr.paramter_id=206 and pr.paramter_value='$plot_no'";
                            }

                            if (!empty($this->request->data['searchdefault']['article_id1'])) {
                                $article_id = $this->request->data['searchdefault']['article_id1'];
                                $condition = $condition . " and gen.article_id=$article_id";
                            }

                            $searchbyproperty = $this->Searcher->query("SELECT  distinct p.property_id, p.token_no,year.year,
                                    aps.final_doc_reg_no,dist.district_name_en,art.article_desc_en,ofs.office_name_en, 
                                    art.book_number,volno.volume_number,volno.page_number_start,volno.page_number_end
                                    FROM ngdrstab_trn_property_details_entry p
                                    join ngdrstab_trn_application_submitted aps on aps.token_no=p.token_no
                                    join ngdrstab_trn_generalinformation gen on gen.token_no=aps.token_no
                                    join ngdrstab_mst_article art on art.article_id=gen.article_id
                                    join ngdrstab_conf_admblock3_district dist on dist.district_id=gen.district_id
                                    LEFT JOIN ngdrstab_trn_volume_number_page_number_entry volno ON volno.token_no=aps.token_no
                                    join ngdrstab_mst_office ofs on ofs.office_id=aps.office_id
                                    join ngdrstab_trn_serial_numbers_final year on year.token_no=aps.token_no
                                    join ngdrstab_trn_parameter pr on pr.token_id=p.token_no
                                    where $condition");
                            $this->set('propertygrid', $searchbyproperty);
                        } else {
                            $this->Session->setFlash(__('Please fill all mandatory fields... !!!!'));
                        }
                    }

                    if ($_POST['action'] == 3) {

                        $deed_no = $this->request->data['searchdefault']['deed_no'];
                        if (!empty($deed_no)) {
//                            $condition = "art.book_number='BK1' and aps.final_stamp_flag='Y' and aps.final_doc_reg_no='$deed_no'";
                            $condition = "aps.final_stamp_flag='Y' and aps.final_doc_reg_no='$deed_no'";
                            $searchbydeed = $this->Searcher->query("SELECT  p.property_id, p.token_no,year.year,
                                                aps.final_doc_reg_no,dist.district_name_en,art.article_desc_en,ofs.office_name_en, 
                                                art.book_number,volno.volume_number,volno.page_number_start,volno.page_number_end
                                                FROM ngdrstab_trn_property_details_entry p
                                                join ngdrstab_trn_application_submitted aps on aps.token_no=p.token_no
                                                join ngdrstab_trn_generalinformation gen on gen.token_no=aps.token_no
                                                join ngdrstab_mst_article art on art.article_id=gen.article_id
                                                join ngdrstab_conf_admblock3_district dist on dist.district_id=gen.district_id
                                                LEFT JOIN ngdrstab_trn_volume_number_page_number_entry volno ON volno.token_no=aps.token_no
                                                join ngdrstab_mst_office ofs on ofs.office_id=aps.office_id
                                                join ngdrstab_trn_serial_numbers_final year on year.token_no=aps.token_no
                                                where $condition");
                            $this->set('deedgrid', $searchbydeed);
                        } else {
                            $this->Session->setFlash(__('Please fill all mandatory fields... !!!!'));
                        }
                    }
                } else {
                    $this->Session->setFlash(__('check all fields validated'));
                }
            }

            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function modifysearchfieldlist($fieldlist, $data) {
        if ($data['Search'] == 1) {
            unset($fieldlist['taluka_id']);
            unset($fieldlist['village_id']);
            unset($fieldlist['khata_no']);
            unset($fieldlist['plot_no']);
            unset($fieldlist['fromyear1']);
            unset($fieldlist['toyear1']);
            unset($fieldlist['article_id1']);
            unset($fieldlist['deed_no']);
        }
        if ($data['Search'] == 2) {
            unset($fieldlist['party_name']);
            unset($fieldlist['father_name']);
            unset($fieldlist['fromyear']);
            unset($fieldlist['toyear']);
            unset($fieldlist['article_id']);
            unset($fieldlist['party_type_id']);
            unset($fieldlist['deed_no']);
        }

        if ($data['Search'] == 3) {
            unset($fieldlist['taluka_id']);
            unset($fieldlist['village_id']);
            unset($fieldlist['khata_no']);
            unset($fieldlist['plot_no']);
            unset($fieldlist['fromyear1']);
            unset($fieldlist['toyear1']);
            unset($fieldlist['article_id1']);
            unset($fieldlist['party_name']);
            unset($fieldlist['father_name']);
            unset($fieldlist['fromyear']);
            unset($fieldlist['toyear']);
            unset($fieldlist['article_id']);
            unset($fieldlist['party_type_id']);
        }


        return $fieldlist;
    }

    public function get_party_type() {
        try {
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            if (isset($_GET['article_id'])) {

                $article_id = $_GET['article_id'];
                $party_type_id = ClassRegistry::init('article_partymapping')->find('list', array('fields' => array('party_type_id'), 'conditions' => array('article_id' => array($article_id))));
                $party_typename = ClassRegistry::init('partytype')->find('list', array('fields' => array('party_type_id', 'party_type_desc_' . $laug), 'conditions' => array('party_type_id' => $party_type_id), 'order' => array('party_type_desc_' . $laug => 'ASC')));

                $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['partytype'] = $party_typename;
                $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($party_typename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function get_village() {
        try {
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            if (isset($_GET['taluka_id'])) {
                $taluka_id = $_GET['taluka_id'];
                $villagename = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('village_id', 'village_name_' . $laug), 'conditions' => array('taluka_id' => $taluka_id), 'order' => array('village_name_' . $laug => 'ASC')));
//                pr($villagename);exit;
                $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['village'] = $villagename;
                $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($villagename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function propertydata($token_no) {
        array_map([$this, 'loadModel'], ['Searcher', 'valuation_details']);

        $lang = $this->Session->read("sess_langauge");
        $getdata = $this->Searcher->query("select village.village_name_$lang,village.jh_taluka_code, property.val_id  from ngdrstab_trn_property_details_entry as property
                                                JOIN  ngdrstab_conf_admblock7_village_mapping  as village ON village.village_id=property.village_id
                                                where property.token_no=$token_no");

        $villagename = null;
        $area = null;
        if (!empty($getdata)) {
            $villagename = $getdata[0][0]['village_name_' . $lang];
            $thana_no = $getdata[0][0]['jh_taluka_code'];
            $valuation_details = $this->valuation_details->find('all', array('fields' => array('DISTINCT rule_id'), 'conditions' => array('val_id' => $getdata[0][0]['val_id'], 'item_type_id' => 1)));
            foreach ($valuation_details as $vdetails) {
                $areawwww = $this->valuation_details->getValuationDetail($getdata[0][0]['val_id'], 1, $vdetails['valuation_details']['rule_id'], $lang);
                foreach ($areawwww as $area1)
                    if ($area1[0]['area_field_flag'] == 'Y') {
                        $area .= " " . $area1[0]['item_value'] . " " . $area1[0]['unit_desc_' . $lang];
                    };
            }
        }
        $searchbydeed = $this->Searcher->query("select concat(mp.eri_attribute_name,' - ',paramter_value)  from ngdrstab_trn_parameter as tp 
                                                JOIN  ngdrstab_mst_attribute_parameter  as mp ON mp.attribute_id=tp.paramter_id
                                                where tp.token_id=$token_no
                                                order by mp.eri_attribute_name");

        $result = null;
        if (!empty($searchbydeed)) {
            foreach ($searchbydeed as $searchbydeed1) {
                $result = $result . " " . $searchbydeed1[0]['concat'] . ",";
            }
            $result = $villagename . ", Th No. " . $thana_no . ", " . $result . " Area " . $area;
        }
        return $result;
    }

    public function getscandata($token_no) {
        $this->loadModel('scan_upload');
        $scan_upload = $this->scan_upload->find("first", array('conditions' => array('token_no' => $token_no)));
        return $scan_upload;
    }

    public function fee_receipt() {
        try {
            $this->autoRender = FALSE;
            array_map(array($this, 'loadModel'), array('office', 'Searcher', 'article_fee_item_list'));
            $lang = $this->Session->read("sess_langauge");
            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $submitdata = $this->office->query("select final_doc_reg_no,final_stamp_date from ngdrstab_trn_application_submitted where token_no=?", array($_POST['token_no']));
            $submitdata = $submitdata[0][0];
            $application_id = $this->Session->read("application_id");
            $searcherdata = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
            $searcherdata = $searcherdata[0][0];
            $year = $this->office->query("select year from ngdrstab_trn_serial_numbers_final where token_no=?", array($_POST['token_no']));
            if (!empty($year)) {
                $year = $year[0][0]['year'];
            } else {
                $year = null;
            }
            $paymentid = '';
            $paymentdate = '';
            $paymenttime = '';
            $tranid = '';
            $cinno = '';
            $grnno = '';
            $paystatus = 'Not Paid';
            if ($searcherdata['payment_status'] == 'Y') {
                $payment1 = $this->office->query("select * from ngdrstab_trn_payment_details where application_id = $application_id");
                $certificate_no = $payment1[0][0]['certificate_no'];
                $payment2 = $this->office->query("select * from ngdrstab_trn_online_payment_received where certificate_no = '$certificate_no'");
                $paymentid = $payment1[0][0]['payment_id'];
                $dt = new DateTime($payment1[0][0]['pdate']);
                $paymentdate = $dt->format('d/m/Y');
                $paymenttime = $dt->format('H:i:s');
                $tranid = $payment2[0][0]['trn_id'];
                $cinno = $payment1[0][0]['cin_no'];
                $grnno = $payment1[0][0]['certificate_no'];
                $paystatus = 'Paid';
            }
            $feeitem = $this->office->query("select * from ngdrstab_mst_article_fee_items where fee_item_id=32");
//            $pagesdata = $this->office->query("select * from ngdrstab_trn_volume_number_page_number_entry where token_no=?", array($_POST['token_no']));
//            $pages = 0;
//            if (!empty($pagesdata)) {
//                $pages = $pagesdata[0][0]['page_number_end'] - $pagesdata[0][0]['page_number_start'] + 1;
//            }

            $doc_token_no = $_POST['token_no'];
            $itemvalue = $this->article_fee_item_list->query("select no_of_pages from ngdrstab_trn_generalinformation where token_no=$doc_token_no");
            $value = $itemvalue[0][0]['no_of_pages'];
            $total_party = $this->number_of_pages($doc_token_no);
            $pages = $value + $total_party + 4;


            $feedesc = null;
            $fee = 0;
            if (!empty($feeitem)) {
                $feedesc = $feeitem[0][0]['fee_item_desc_en'];
                $fee = $feeitem[0][0]['fix_amount'];
            }
            $amount = $pages * $fee;
            $imagedata = "img/state_logos_img/31_goa_logo.jpg";
            $image = file_get_contents($imagedata);
            $image_codes = base64_encode($image);
            $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' align='middle' /> ";
            $application_date = date('d/m/Y', strtotime($searcherdata['application_date']));

            $html_design = "";
            $html_design .= "<style>td{padding:5px;} div.ex1{width:90%; margin: auto; border: 3px solid red;} </style>"
                    . "<div class=ex1>"
                    . "<p align=center>" . $img1 . "</p>"
                    . "<h3 align=center style='color:#9C6F7A';> Govt. of Goa  </h3>"
                    . "<h3 align=center style='color:#9C6F7A';>Department of Registration</h3>"
                    . "<h3 align=center style='color:#9C6F7A';>$officename</h3>"
                    . "<hr style color:red;>"
                    . "<h3 align=center style='color:#9C6F7A';>Receipt Challon for Fees deposited for Search/Copy/Non-Encumbrance</h3>"
                    . "<hr style color:red;>"
                    . "<table border=0 style='border-collapse:collapse;' width=100%>"
                    . "<tr><td style='border-bottom:1pt solid black;'><b>Application ID :</b></td><td style='border-bottom:1pt solid black;' align=left><b>" . $searcherdata['application_id'] . "</b></td>"
                    . "<td style='border-bottom:1pt solid black;' align=right><b>Token No. :</b></td><td style='border-bottom:1pt solid black;' align=center><b>" . $_POST['token_no'] . "</b></td></tr>"
                    . "<tr><td><b>Payment ID :</b></td><td align=left><b>" . $paymentid . "</b></td>"
                    . "<td align=right><b>Payment Date :</b></td><td align=left><b>" . $paymentdate . "</b></td></tr>"
                    . "<tr><td><b>Transaction ID :</b></td><td align=left><b>" . $tranid . "</b></td>"
                    . "<td align=right><b>Payment Time :</b></td><td align=left><b>" . $paymenttime . "</b></td></tr>"
                    . "<tr><td><b>Reference No. :</b></td><td colspan=3></td></tr>"
                    . "<tr><td><b>Date of Application :</b></td><td colspan=3 align=left><b>" . $application_date . "</b></td></tr>"
                    . "<tr><td><b>Applicant Name :</b></td><td colspan=3 align=left><b>" . $searcherdata['applicant_name'] . "</b></td></tr>"
                    . "<tr><td><b>Fee For :</b></td><td colspan=3 align=left><b>" . $feedesc . "</b></td></tr>"
                    . "<tr><td><b>Years :</b></td><td colspan=3 align=left><b>" . $year . "</b></td></tr>"
                    . "<tr><td><b>Fee Amount :</b></td><td colspan=3 align=left><b>" . $amount . "</b></td></tr>"
                    . "<tr><td><b>GRN No. :</b></td><td colspan=3 align=left><b>" . $grnno ."</b></td></tr>"
                    . "<tr><td><b>CIN No. :</b></td><td colspan=3 align=left><b>" . $cinno ."</b></td></tr>"
                    . "<tr><td><b>Pay Status :</b></td><td colspan=3 align=left><b>" . $paystatus . "</b></td></tr>"
                    . "</table><BR><BR>"
                    . "<h3 align=right style='padding: 35px;'>Registration Officer</h3>"
                    . "</div>";

            $data['id'] = $searcherdata['id'];
            $data['token_no'] = $_POST['token_no'];
            $data['final_doc_reg_no'] = $submitdata['final_doc_reg_no'];
            $data['final_stamp_date'] = $submitdata['final_stamp_date'];
            $data['pages'] = $pages;
            $data['fee'] = $fee;
            $data['amount'] = $amount;
            $this->Searcher->save($data);

            $scan_upload = $this->getscandata($_POST['token_no']);
            $downloaddeed = "";
            if ($searcherdata['payment_status'] == 'Y') {
                $downloaddeed .= "<a href='" . $this->webroot . "MHSearch/downloadfile/" . $scan_upload['scan_upload']['scan_name'] . "/Scanning/" . $_POST['token_no'] . "/C' class='btn btn-primary'>Download Deed</a>";
            } else {
                $downloaddeed .= "<a href='" . $this->webroot . "GAWebService/gras_payment_entry_new_fees' target='_blank' class='btn btn-primary'>Make Payment</a>";
                $downloaddeed .= "<a href='" . $this->webroot . "MHSearch/payment_verification' target='_blank' class='btn btn-primary'>Verify Payment</a>";
            }


            $resultarray = array('html_design' => $html_design,
                'deed' => $downloaddeed);
            echo json_encode($resultarray);
            exit;
//            return($html_design);
//pr($html_design);exit;
//               $this->create_pdf($html_design, 'Yearly Report', 'A4-P', '');
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function rptnonemcomb_copy() {
        try {

            $this->autoRender = FALSE;
            $token_no = $_POST['token_no'];
            array_map([$this, 'loadModel'], ['office', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 42)));
            $application_id = $this->Session->read("application_id");
            $searcherdata = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
            $searcherdata = $searcherdata[0][0];


            $searchbydeed = $this->office->query("select concat(mp.eri_attribute_name,' - ',paramter_value)  from ngdrstab_trn_parameter as tp 
                                                JOIN  ngdrstab_mst_attribute_parameter  as mp ON mp.attribute_id=tp.paramter_id
                                                where tp.token_id=$token_no
                                                order by mp.eri_attribute_name");

            $vt = $this->office->query("select gen.token_no,t.taluka_name_en,d.district_name_en,v.village_name_en  from ngdrstab_trn_property_details_entry gen 
                                            join ngdrstab_conf_admblock3_district  d on d.district_id=gen.district_id
                                            join ngdrstab_conf_admblock5_taluka  t on t.taluka_id=gen.taluka_id
                                            join ngdrstab_conf_admblock7_village_mapping  v on v.village_id=gen.village_id
                                            where gen.token_no=$token_no");

            $imagedata = "img/state_logos_img/31_goa_logo.jpg";
            $image = file_get_contents($imagedata);
            $image_codes = base64_encode($image);
            $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' align='left' /> ";

            $html_design = "<div align=left>" . $img1 . "<h2 align=center style='color:#9C6F7A';> " . $rptlabels[436] . "  </h2></div><br>";

            $html_design .= "<style>td{padding:5px;} </style>"
                    . "<br>"
                    . "<h4 align=left style='color:black';> $officename  </h4>"
                    . "<h4 align=left style='color:black';> <b>Application Id :  " . $searcherdata['application_id'] . "</b></h4>"
                    . "<table border=0 style='border-collapse:collapse;' width=100%>"
//                        . "<tr><td align=left style='color:black';><b>Application Id</b> : </td></tr>"
                    . "<tr><td align=left style='color:black';> " . $rptlabels[437] . "<B>" . $searcherdata['applicant_name'] . "</b> " . $rptlabels[444] . "</td></tr>"
                    . "<tr><td align=left style='color:black';><b>Searched for Years: " . $_POST['from'] . " To " . $_POST['to'] . "</b> </td></tr>"
                    . "<tr><td align=left style='color:black';><b>Property Details </b> </td></tr>";
            foreach ($searchbydeed as $searchbydeed1) {
                $html_design .= "<tr><td align=left style='color:black';><b>" . $searchbydeed1[0]['concat'] . "</b> </td></tr>";
            }

            $html_design .= "<tr><td align=left style='color:black';><b>Taluka:</b>" . $vt[0][0]['taluka_name_en'] . " </td></tr>"
                    . "<tr><td align=left style='color:black';><b>Village:</b>" . $vt[0][0]['village_name_en'] . "  </td></tr>"
                    . "<tr><td align=left style='color:black';> " . $rptlabels[438] . "</td></tr>"
                    . "</table>"
                    . "<table  align=center border=0 width=100%><tr><h3></tr></table>"
                    . "<hr style='color:balck';>"
                    . "<div class='table-responsive'>"
                    . "<table border=1 style='border-collapse:collapse;' width=100%>"
//                    ."<thead>"
                    . "<tr>"
                    . "<th rowspan='2'> " . $rptlabels[427] . "</th>"
                    . "<th rowspan='2'> " . $rptlabels[428] . "</th>"
                    . "<th rowspan='2'>" . $rptlabels[429] . "</th>"
                    . "<th rowspan='2' class='vericaltext'>" . $rptlabels[430] . "</th>"
                    . "<th rowspan='2' class='vericaltext'>" . $rptlabels[431] . "</th>"
                    . "<th colspan='4'>" . $rptlabels[432] . "</th>"
                    . "</tr>"
                    . "<tr>"
                    . "<th rowspan='1'>Deed No.</th>"
                    . "<th rowspan='1'>" . $rptlabels[435] . " </th>"
                    . "<th rowspan='1'>" . $rptlabels[433] . "</th>"
                    . "<th rowspan='1'>" . $rptlabels[434] . "</th>"
                    . "</tr>"
//                    ."</thead>"
                    . "<tbody>";

            $html_design .= $this->esearhpartydata($token_no, $srno = 1);

            $html_design .= "</tbody></table></div>"
                    . "<br><br>";

            $html_design .= "<table><tr>" . $rptlabels[439] . "</tr></table>"
                    . "<tr>" . $rptlabels[440] . "</tr><br><br><br><br>"
                    . "<tr>" . $rptlabels[441] . "</tr><br><br>"
                    . "<tr>" . $rptlabels[442] . "</tr><br><br>"
                    . "<tr>" . $rptlabels[443] . "</tr>";

            return $html_design;
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function esearhpartydata($token_no, $srno) {

        array_map([$this, 'loadModel'], ['office']);
        $result = $this->office->query("SELECT p.token_no,p.party_id,aps.final_doc_reg_no,p.party_full_name_en,p.father_full_name_en,p.address_en, p.address2_en
                                    ,art.book_number,volno.volume_number,volno.page_number_start,volno.page_number_end, gen.link_doc_no
                                    ,pt.party_type_desc_en,ofs.office_name_en,aps.doc_reg_date,art.article_desc_en,aps.id,year.year
                                     FROM ngdrstab_trn_party_entry_new p
                                    left outer join ngdrstab_mst_party_type pt on pt.party_type_id=p.party_type_id 
                                    left join ngdrstab_trn_application_submitted aps on aps.token_no=p.token_no
                                    join ngdrstab_trn_generalinformation gen on gen.token_no=aps.token_no
                                    join ngdrstab_trn_serial_numbers_final year on year.token_no=aps.token_no
                                    join ngdrstab_mst_article art on art.article_id=gen.article_id 
                                    LEFT JOIN ngdrstab_trn_volume_number_page_number_entry volno ON volno.token_no=aps.token_no
                                    join ngdrstab_mst_office ofs on ofs.office_id=aps.office_id
                                    where p.token_no=$token_no");

        $address = $this->propertydata($token_no);

        $html_design = "<tr><td style='text-align:center;'><b>" . $srno . "</b></td>"
                . "<td style='text-align:center;'><b>" . $address . "</b></td>";

        $html_design .= "<td>" . $result[0][0]['doc_reg_date'] . "</td>";
        $html_design .= "<td style='text-align:center;'>" . $result[0][0]['article_desc_en'] . "</td><td align=center>";
        foreach ($result as $result1) {
            $html_design .= "<b>" . $result1[0]['party_full_name_en'] . "</b><br>(" . $result1[0]['party_type_desc_en'] . ")<br>\n-------------\n<br>" . $result1[0]['address_en'] . "<br><hr><br>";
        }
        $html_design .= "</td><td style='text-align:center;'><b>" . $result[0][0]['final_doc_reg_no'] . "</b></td>"
                . "<td style='text-align:center;'><b>" . $result[0][0]['volume_number'] . "</b></td>"
                . "<td style='text-align:center;'><b>" . $result[0][0]['year'] . "</b></td>"
                . "<td style='text-align:center;'><b>" . $result[0][0]['page_number_start'] . "-" . $result1[0]['page_number_end'] . "</b></td>"
                . "</tr>";

        if (!empty($result[0][0]['link_doc_no'])) {
            $link_doc_no = $result[0][0]['link_doc_no'];
            $token = $this->office->query("select token_no from ngdrstab_trn_application_submitted where final_doc_reg_no ='$link_doc_no'");
            $token = $token[0][0]['token_no'];
            $srno = $srno + 1;
            $html_design .= $this->esearhpartydata($token, $srno);
        }
        return $html_design;
    }

    public function propertydatanew($token_no) {
        array_map([$this, 'loadModel'], ['Searcher', 'valuation_details']);

        $lang = $this->Session->read("sess_langauge");
        $getdata1 = $this->Searcher->query("select property.property_id, village.village_name_$lang,village.jh_taluka_code, property.val_id  from ngdrstab_trn_property_details_entry as property
                                                JOIN  ngdrstab_conf_admblock7_village_mapping  as village ON village.village_id=property.village_id
                                                where property.token_no=$token_no");
        $propdata = array();
        $villagename = null;
        $area = null;
        foreach ($getdata1 as $key => $getdata) {
            if (!empty($getdata)) {
                $villagename = $getdata[0]['village_name_' . $lang];
                $thana_no = $getdata[0]['jh_taluka_code'];
                $valuation_details = $this->valuation_details->find('all', array('fields' => array('DISTINCT rule_id'), 'conditions' => array('val_id' => $getdata[0]['val_id'], 'item_type_id' => 1)));
                foreach ($valuation_details as $vdetails) {
                    $areawwww = $this->valuation_details->getValuationDetail($getdata[0]['val_id'], 1, $vdetails['valuation_details']['rule_id'], $lang);
                    foreach ($areawwww as $area1)
                        if ($area1[0]['area_field_flag'] == 'Y') {
                            $area .= " " . $area1[0]['item_value'] . " " . $area1[0]['unit_desc_' . $lang];
                        };
                }
            }

            $property_id = $getdata[0]['property_id'];
            $searchbydeed = $this->Searcher->query("SELECT 
                                                concat(mparam.eri_attribute_name,' - ',param.paramter_value)
                                                FROM ngdrstab_trn_property_details_entry as prop
                                                JOIN ngdrstab_trn_parameter as param ON param.property_id=prop.property_id
                                                JOIN ngdrstab_mst_attribute_parameter as mparam ON mparam.attribute_id=param.paramter_id
                                                where prop.property_id=$property_id");

            $result = null;
            if (!empty($searchbydeed)) {
                foreach ($searchbydeed as $searchbydeed1) {
                    $result = $result . " " . $searchbydeed1[0]['concat'] . ",";
                }
                $result = $villagename . ", Th No. " . $thana_no . ", " . $result . " Area " . $area;
            }
            $propdata[$key] = $result;
        }

        return $propdata;
    }

    public function rptdetails() {
        try {
            $this->autoRender = FALSE;
            $token_no = $_POST['token_no'];
            array_map(array($this, 'loadModel'), array('office'));
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $flag = $_POST['flag'];
            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $application_id = $this->Session->read("application_id");
            $searcherdata = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
            $searcherdata = $searcherdata[0][0];
            $vt = $this->office->query("select gen.token_no,t.taluka_name_en,d.district_name_en,v.village_name_en  from ngdrstab_trn_property_details_entry gen 
                                            join ngdrstab_conf_admblock3_district  d on d.district_id=gen.district_id
                                            join ngdrstab_conf_admblock5_taluka  t on t.taluka_id=gen.taluka_id
                                            join ngdrstab_conf_admblock7_village_mapping  v on v.village_id=gen.village_id
                                            where gen.token_no=$token_no");

            $result = $this->office->query("SELECT p.token_no,p.party_id,aps.final_doc_reg_no,p.party_full_name_en,p.father_full_name_en,p.address_en, p.address2_en
                                    ,art.book_number,volno.volume_number,volno.page_number_start,volno.page_number_end, gen.link_doc_no
                                    ,pt.party_type_desc_en,ofs.office_name_en,aps.doc_reg_date,art.article_desc_en,aps.id,year.year
                                     FROM ngdrstab_trn_party_entry_new p
                                    left outer join ngdrstab_mst_party_type pt on pt.party_type_id=p.party_type_id 
                                    left join ngdrstab_trn_application_submitted aps on aps.token_no=p.token_no
                                    join ngdrstab_trn_generalinformation gen on gen.token_no=aps.token_no
                                    join ngdrstab_trn_serial_numbers_final year on year.token_no=aps.token_no
                                    join ngdrstab_mst_article art on art.article_id=gen.article_id 
                                    LEFT JOIN ngdrstab_trn_volume_number_page_number_entry volno ON volno.token_no=aps.token_no
                                    join ngdrstab_mst_office ofs on ofs.office_id=aps.office_id
                                    where p.token_no=$token_no");


            $propdata = $this->propertydatanew($token_no);
            $no = 1;
            $propaddress = null;
            if (!empty($propdata)) {
                foreach ($propdata as $propdata1) {
                    $propaddress = $propaddress . "Property :- " . $no . "<br>" . $propdata1 . "<br><br>";
                    $no++;
                }
            }

            if (!empty($result)) {

                $html_design = "<html><body><style>td{padding:5px;}  </style>"
                        . "<h2 align=center style='color:#9C6F7A';> Search Details  </h2><br/><br/><br/>"
                        . "<table border=0 width=100%>"
                        . "<tr><td><b>Application ID : " . $searcherdata['application_id'] . " (" . $searcherdata['applicant_name'] . ")</b></td></tr>"
                        . "<tr><td><b>Deed No. : " . $result[0][0]['final_doc_reg_no'] . "</b></td></tr>"
                        . "<tr><td><b>Search Details : </b></td></tr>";

                if ($flag == 'P') {
                    $html_design .="<tr><td><b>Years : " . $_POST['from'] . " To " . $_POST['to'] . "</b></td></tr>"
                            . "<tr><td><b>Party Name : " . $result[0][0]['party_full_name_en'] . "</b></td></tr>";
                } else if ($flag == 'R') {
                    $html_design .="<tr><td><b>Years:" . $_POST['from'] . " To " . $_POST['to'] . "</b></td></tr>"
                            . "<tr><td><b>Taluka : " . $vt[0][0]['taluka_name_en'] . "</b></td></tr>"
                            . "<tr><td><b>Village : " . $vt[0][0]['village_name_en'] . "</b></td></tr>";
                } else {
                    $html_design .="<tr><td><b>Deed No : " . $result[0][0]['final_doc_reg_no'] . "</b></td></tr>"
                            . "<tr><td><b>Year : " . $result[0][0]['year'] . "</b></td></tr>";
                }


                $html_design .= "</table>"
                        . "<hr style='color:balck';>"
                        . "<div class='table-responsive'>"
                        . "<table border=1 style='border-collapse:collapse;' width=100%>"
                        . "<thead>"
                        . "<tr>"
                        . "<th rowspan='2' style='text-align:center;' >Sr.No</th>"
                        . "<th rowspan='2' style='text-align:center;' >Property Details</th>"
                        . "<th rowspan='2' style='text-align:center;' >Date</th>"
                        . "<th rowspan='2' style='text-align:center;' >Type of Deed</th>"
                        . "<th rowspan='2' style='text-align:center;' >Parties</th>"
                        . "<th colspan='3' style='text-align:center;' >Deed Details</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th rowspan='1' style='text-align:center;'>Vol.</th>"
                        . "<th rowspan='1' style='text-align:center;'>Year</th>"
                        . "<th rowspan='1' style='text-align:center;'>Pages</th>"
                        . "</tr>"
                        . "</thead>";

                $srno = 1;
                $html_design .= "<tbody>"
                        . "<tr><td style='text-align:center;'>" . $srno++ . "</td>";

                $html_design .= "<td><b>" . $propaddress . "</b></td>"
                        . "<td style='text-align:center;'>" . $result[0][0]['doc_reg_date'] . "</td>"
                        . "<td style='text-align:center;'>" . $result[0][0]['article_desc_en'] . "</td><td align=center>";

                foreach ($result as $result1) {
                    $html_design .= "<b>" . $result1[0]['party_full_name_en'] . "</b>(" . $result1[0]['party_type_desc_en'] . ")<br>" . $result1[0]['address_en'] . "<br/><hr/><br/>";
                }

                $html_design .= "</td><td style='text-align:center;'><b>" . $result[0][0]['volume_number'] . "</b></td>"
                        . "<td style='text-align:center;'><b>" . $result[0][0]['year'] . "</b></td>"
                        . "<td style='text-align:center;'><b>" . $result[0][0]['page_number_start'] . "-" . $result[0][0]['page_number_end'] . "</b></td>"
                        . "</tr>"
                        . "</tbody>";

                $html_design .= "</table></div><br/></body></html>";
                return $html_design;
                //pr($html_design); exit;
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function checkfile($path) {
        try {

//pr($path);
            $files = glob($path . "*");
            $now = time();
//            pr($files);exit;
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        if ($now - filemtime($file) >= 60 * 60 * 24) { // 2 days
                            unlink($file);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    function downloadfile($file, $folder = NULL, $token = NULL, $flag) {
        try {
            array_map(array($this, 'loadModel'), array('file_config', 'office', 'ApplicationSubmitted'));
            if (is_null($token)) {
                $token = $this->Session->read("reg_token");
            }
            if (!is_null($token)) {
                $office = $this->ApplicationSubmitted->find('first', array('fields' => array('district.district_name_en', 'office.taluka_id', 'office.office_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_office', 'alias' => 'office', 'conditions' => array('office.office_id=ApplicationSubmitted.office_id')),
                        array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district', 'conditions' => array('district.district_id=office.district_id')),
                    ),
                    'conditions' => array('ApplicationSubmitted.token_no' => $token)
                ));

                if (!empty($office)) {
                    if (isset($office['district']['district_name_en']) || !is_null($office['district']['district_name_en'])) {
                        if (isset($office['office']['taluka_id']) || !is_null($office['office']['taluka_id'])) {
                            $folder = 'Documents/' . $office['district']['district_name_en'] . "/" . $office['office']['taluka_id'] . "/" . $office['office']['office_id'] . "/" . $token . '/' . $folder . '/';
                            $token = $this->Session->read("reg_token");
                            $path = $this->file_config->find('first', array('fields' => array('filepath')));
                            $jarpath = $path['file_config']['filepath'] . "jar_files/Watermarkstring.jar";
                            $tempfolder = "Temp/";
                            $UPLOAD_DIR = $path['file_config']['filepath'] . $tempfolder;
                            if (!file_exists($UPLOAD_DIR)) {
                                mkdir($UPLOAD_DIR, 0744, true);
                            }
                            $this->checkfile($UPLOAD_DIR);
                            $source = $path['file_config']['filepath'] . $folder . $file;
                            $destination = $path['file_config']['filepath'] . $tempfolder . $file;
//                            pr($jarpath);
//                            pr($source);
//                            pr($destination);
//                            pr('/usr/java/jdk1.8.0_131/bin/java -jar ' . $jarpath . ' ' . $source . ' ' . $destination . ' ' . $flag);
                            $message = exec('/usr/java/jdk1.8.0_131/bin/java -jar ' . $jarpath . ' ' . $source . ' ' . $destination . ' ' . $flag, $result);
                            $this->any_download_file($destination, $file);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Errors', 'action' => 'error404'));
        }
    }

    function any_download_file($path, $file) {
        try {
//            pr($path);pr($file);exit;

            $this->autoRender = FALSE;
            if (file_exists($path)) {
                $this->response->file($path, array('download' => true, 'name' => $file));
                return $this->response->download($file);
            } else {
                echo "file not found";
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Errors', 'action' => 'error404'));
        }
    }

    function get_payment_details() {
        try {
            array_map(array($this, 'loadModel'), array('payment', 'PaymentFields', 'bank_master', 'BankBranch', 'office', 'officehierarchy', 'article_fee_items', 'PaymentModeMapping', 'payment_mode'));

            $lang = $this->Session->read("sess_langauge");
            $user_id = $this->Session->read("citizen_user_id");
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $doc_lang = $this->Session->read("doc_lang");
            $article_id = $this->Session->read("selectedarticle_id");
            $modemapping = array();
            if (isset($data['mode']) && is_numeric($data['mode'])) {
                $paymentfields = $this->PaymentFields->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'is_input_flag' => 'Y'), 'order' => 'srno ASC'));
                $this->set("paymentfields", $paymentfields);
                $payment_mode = $this->payment_mode->find("first", array('conditions' => array('payment_mode_id' => $data['mode'])));
                $this->set("payment_mode", $payment_mode['payment_mode']);
                $accounthead = $this->payment->fee_headings($token, $lang, $article_id, $data['mode']);
                $modemapping = $this->PaymentModeMapping->find("all", array('conditions' => array('payment_mode_id' => $data['mode'])));
            }
            if (isset($data['id']) && is_numeric($data['id'])) {
                $payment = $this->payment->find('all', array('conditions' => array('payment_mode_id' => $data['mode'], 'payment_id' => $data['id'], 'token_no' => $token)));
                $this->set("payment", $payment);
                // pr($payment);
            }
            if (isset($paymentfields)) {
                foreach ($paymentfields as $field) {
                    if ($field['PaymentFields']['field_name'] == 'bank_id') {
                        $bank_master = $this->bank_master->find('list', array('fields' => array('bank_id', 'bank_name_' . $lang)));
                        $this->set("bank_master", $bank_master);
                        if (isset($payment) and is_numeric($payment[0]['payment']['bank_id'])) {
                            $branch_master = $this->BankBranch->find('list', array('fields' => array('id', 'branch_name_' . $lang), 'conditions' => array('bank_id' => $payment[0]['payment']['bank_id'])));
                        } else {
                            $branch_master = array();
                        }
                        $this->set("branch_master", $branch_master);
                    }
                    if ($field['PaymentFields']['field_name'] == 'cos_id') {
                        $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('hierarchy_id' => 45)));
                        $this->set("office", $office);
                    }
                }
            }

            $feedetails = $this->payment->stampduty_fee_details($token, $lang, $article_id);

            $this->set(compact('accounthead', 'doc_lang', 'lang', 'feedetails', 'modemapping'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function payment_verification() {
        try {
            array_map([$this, 'loadModel'], ['office', 'payment', 'OnlinePayment', 'Searcher']);
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $this->set('payment_mode_online', ClassRegistry::init('payment_mode')->get_payment_mode_online($lang));
            if ($this->request->is('post')) {
                $data['certificate_no'] = $this->request->data['payment']['certificate_no'];
                $data['estamp_vender_place'] = $this->request->data['payment']['estamp_vender_place'];
                $data['payment_mode_id'] = $this->request->data['payment']['payment_mode_id'];
                $check = $this->office->query("select * from ngdrstab_trn_online_payment_received where certificate_no = '".$data['certificate_no']."'");
                if($check != null){
                $application_id = $this->Session->read("application_id");
                $searcher = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
                $article_id = $this->office->query("select article_id from ngdrstab_trn_generalinformation where token_no=?", array($searcher[0][0]['token_no']));
                $extrafields['token_no'] = $searcher[0][0]['token_no'];
                $extrafields['article_id'] = $article_id[0][0]['article_id'];
                $extrafields['lang'] = "en";
                $extrafields['StatusOnly'] = "Y";
                $serviceobj = new WebServiceController();
                $serviceobj->constructClasses();
                $response = $serviceobj->EchallanVerification($data, $extrafields);
                PR($response);
                if (empty($response['Error'])) {
                    $pdata['payment_mode_id'] = $data['payment_mode_id'];
                    $pdata['cin_no'] = $response['cin_no'];
                    $pdata['pdate'] = date('Y/m/d H:i:s');
                    $pdata['pamount'] = $response['AMOUNT'];
                    $pdata['certificate_no'] = $data['certificate_no'];
                    $pdata['estamp_vender_name'] = $data['estamp_vender_place'];
                    $pdata['estamp_issue_date'] = $response['payment_date'];
                    $pdata['state_id'] = $this->Auth->User('state_id');
                    $pdata['org_user_id'] = $this->Auth->User('user_id');
                    $pdata['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $pdata['token_no'] = $searcher[0][0]['token_no'];
                    $pdata['account_head_code'] = 32;
                    $pdata['defacement_flag'] = 'Y';
                    $pdata['record_lock'] = 'Y';
                    $pdata['online_verified_flag'] = 'Y';
                    $pdata['user_type'] = 'O';
                    $pdata['application_id'] = $application_id;
                    if ($this->payment->Save($pdata)) {
                        if ($this->OnlinePayment->Save($pdata)) {
                            $pamount = $this->office->query("select sum(pamount) as pamount from ngdrstab_trn_payment_details where application_id = $application_id");
                            if ($pamount[0][0]['pamount'] >= $searcher[0][0]['amount']) {
                                $sdata['id'] = $searcher[0][0]['id'];
                                $sdata['payment_status'] = 'Y';
                                $this->Searcher->save($sdata);

                                $this->Session->setFlash(__("Payment verified Successfully..!!! Download Certified Copy...!!"));
                                $this->redirect('payment_verification');
                            } else {
                                $p1 = $searcher[0][0]['amount'];
                                $p2 = $pamount[0][0]['pamount'];
                                $p3 = $p1 - $p2;
                                $this->Session->setFlash(__("You have paid $p2...You have to pay more $p3...!!!! "));
                                $this->redirect('payment_verification');
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__("Error:" . $response['Error']));
                    $this->redirect('payment_verification');
                }
                } else{
                    $this->Session->setFlash(__("Challan Number already Exist ....!!!"));
                    $this->redirect('payment_verification');
                }
            }
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    function get_payment_field_values($field_id = NULL) {
        try {

            $this->loadModel('PaymentFieldValues');
            $lang = $this->Session->read("sess_langauge");
            if (is_null($lang)) {
                $lang = 'en';
            }
            $result = $this->PaymentFieldValues->find("list", array(
                'fields' => array('field_value', 'field_values_desc_' . $lang),
                'conditions' => array('field_id' => $field_id)
            ));
            return $result;
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
        }
    }

}
