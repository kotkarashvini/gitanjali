<?php

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
class SearchController extends AppController {

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

    public function srosearch() {
        try {
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('User');
            $this->set('documentrecord', Null);
            $this->set('actiontypeval', NULL);
            $this->set('hfsetradio', NULL);
            $user_id = $this->Auth->User("user_id");
            $office1 = $this->User->query("select office_id from ngdrstab_mst_user where user_id=? ", array($user_id));
            if (isset($office1) && !empty($office1)) {
                $office_id = $office1[0][0]['office_id'];
            }
            $office_id = $office1[0][0]['office_id'];
            $fieldlist = array();
            //  $fieldlist['docno']['radio'] = 'is_document_radio';
            //$fieldlist['doc_reg_no']['text'] = 'is_required,is_integer'; // dependent
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['srosearch']['csrftoken']);

                $this->request->data['srosearch'] = $this->istrim($this->request->data['srosearch']);
                $fieldlistnew = $this->modifyfieldlist($fieldlist, $this->request->data['srosearch']);
                //------------------------------------------ Server side validation-----------------------------------------------------------
                $errarr = $this->validatedata($this->request->data['srosearch'], $fieldlistnew);
                if ($this->ValidationError($errarr)) {
                    $this->set('actiontypeval', $_POST['actiontype']);
                    $this->set('hfsetradio', $_POST['hfsetradio']);
                    if ($_POST['actiontype'] == 'Docregsearch') {
                        $docno = $this->request->data['srosearch']['doc_reg_no'];
                        $documentrecord = array();
                        $documentrecord = $this->ApplicationSubmitted->query("select a.office_id,a.user_id,a.doc_reg_no,b.party_full_name_en,b.address_en,c.party_type_desc_en from ngdrstab_trn_application_submitted a
            inner join ngdrstab_trn_party_entry_new b on a.token_no=b.token_no 
            inner join ngdrstab_mst_party_type c on b.party_type_id=c.party_type_id
            where doc_reg_no='$docno' and a.final_stamp_flag='Y' and 
            a.office_id in($office_id,(select office_id from ngdrstab_trn_concurrent_jurisdiction where map_user_id=$user_id))"); //2 office_id session 132 user_id session and user_id
                        $this->set('documentrecord', $documentrecord);
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    //validation function for dependent fields
    public function modifyfieldlist($fieldlist, $data) {
        if (isset($data['docno']) && $data['docno'] == 'D') {
            unset($fieldlist['doc_reg_no']);
        }
        return $fieldlist;
    }

    public function document_download($regno) {
        try {

            $regno = base64_decode($regno);
//            $path = WWW_ROOT . 'files/auth_let/' .'sdfg' . ".pdf";
            $this->autoRender = false;

            $this->loadModel('file_config');
            $this->loadModel('ApplicationSubmitted');
            $this->set('rval', 'SC');
            $path = $this->file_config->find('first', array('fields' => array('filepath')));


            $result = $this->ApplicationSubmitted->find('first', array('conditions' => array('doc_reg_no' => $regno, 'final_stamp_flag' => 'Y')));
            if (!empty($result)) {
                $token = $result['ApplicationSubmitted']['token_no'];
                $filepath = $path['file_config']['filepath'] . "Documents/" . $token . "/Report/" . $token . "_final_document.pdf";


                if (file_exists($filepath)) {
                    $name = $token . "_final_document.pdf";
                    $this->response->file($filepath, array('download' => true, 'name' => $name));
                    return $this->response->download($name);
                } else {
                    echo "file not found";
                }

//                if (file_exists($filepath)) {
//                    $this->response->file($filepath, array('download' => true, 'name' => 'code'));
//                    return $this->response->download(base64_decode($token.".pdf"));
//                } else {
//                   // echo $filepath . "<br>";
//                    echo 'file not find';
//                    exit;
//                }
            }

            // /home/NGDRS_UPLOAD_PB/Documents/20170000001/Report
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function searchindex() {
        try {
            array_map([$this, 'loadModel'], ['Searcher']);
            $stateid = $this->Auth->User('state_id');
            $lang = $this->Session->read("sess_langauge");
            $this->set('hffromyear', null);
            $this->set('hftoyear', null);
            $this->set('lang', $lang);
            $this->set('todaydate', date("d/m/Y"));
            $this->set('District', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $lang => 'ASC'))));
            $this->set('office', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('office_name_' . $lang => 'ASC'))));
            $this->set('year', ClassRegistry::init('finyear')->find('list', array('fields' => array('finyear_id', 'year_for_token'), 'order' => array('year_for_token' => 'ASC'))));

            $fieldlist = array();
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['applicant_name']['text'] = 'is_required';
            $fieldlist['fromyear']['text'] = 'is_required';
            $fieldlist['toyear']['text'] = 'is_required';

            $fieldlist['email_id']['text'] = 'is_required,is_email';
            $fieldlist['mobile_no']['text'] = 'is_required,is_mobileindian';
            $fieldlist['address_en']['text'] = 'is_required';


//              foreach ($languagelist as $languagecode) {
//                if ($languagecode['mainlanguage']['language_code'] == 'en') {
//                    //list for english single fields
//                    $fieldlist['address_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_maxlength255';
//                } else {
//                    //list for all unicode fields
//                    $fieldlist['address_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
//                }
//            }

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));





            if ($this->request->is('post')) {
                $errarr = $this->validatedata($this->request->data['searchindex'], $fieldlist);
                if ($this->validationError($errarr)) {
//                pr($this->request->data);exit;
                    $district_id = $this->request->data['searchindex']['district_id'];
                    $this->request->data['searchindex']['application_date'] = date('Y/m/d H:i:s');
                    $fromyear = $_POST['hffromyear'];
                    $toyear = $_POST['hftoyear'];
                    $this->request->data['searchindex']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $this->request->data['searchindex']['user_id'] = $this->Auth->User("user_id");
                    ;
                    $this->request->data['searchindex']['state_id'] = $stateid;
                    if ($this->Searcher->save($this->request->data['searchindex'])) {
                        $last_id = $this->Searcher->getLastInsertId();
                        $application_id = ClassRegistry::init('Searcher')->find('all', array('fields' => array('application_id'), 'conditions' => array('id' => $last_id)));
                        $this->Session->write("application_id", $application_id[0]['Searcher']['application_id']);
                        $this->Session->setFlash(__('The search application submitted.'));
                        $this->redirect(array('controller' => 'Search', 'action' => 'searchdefault', $district_id, $fromyear, $toyear));
//                                $this->set('documentrecord', $this->document->find('all'));
                    } else {
                        $this->Session->setFlash(__('Something went wrong ... !!!!'));
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function searchdefault($district_id, $fromyear, $toyear) {
        try {
            array_map([$this, 'loadModel'], ['Searcher']);
            $stateid = $this->Auth->User('state_id');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
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

            $fieldlist = array();
            $fieldlist['Search']['select'] = 'is_select_req';
            //party details=1
            $fieldlist['party_name']['text'] = 'is_required,is_alphaspace';
            $fieldlist['father_name']['text'] = 'is_alphaspace';
            $fieldlist['fromyear']['text'] = 'is_required,is_integer';
            $fieldlist['toyear']['text'] = 'is_required,is_integer';
            $fieldlist['article_id']['select'] = 'is_select_req';
      //      $fieldlist['party_type_id']['select'] = 'is_select_req';
            //property details=2
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
            $fieldlist['khata_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['plot_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['fromyear1']['text'] = 'is_required,is_integer';
            $fieldlist['toyear1']['text'] = 'is_required,is_integer';
//            $fieldlist['article_id1']['select'] = 'is_select_req';
            //Deed No=3
            $fieldlist['deed_no']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {

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
                        $startdate = $fromyear . '-01-01';
                        $enddate = $toyear . '-12-31';
                        $condition = "aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate'";
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
                            $startdate = $fromyear . '-01-01';
                            $enddate = $toyear . '-12-31';
                            $condition = "aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date)>= '$startdate' and DATE(aps.final_stamp_date)<= '$enddate' and p.taluka_id=$taluka_id and p.village_id=$village_id";

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
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
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
            array_map(array($this, 'loadModel'), array('office', 'TrnBehavioralPatterns', 'regconfig', 'State', 'payment', 'SroDependentFields', 'ApplicationSubmitted', 'file_config', 'article', 'stamp_duty', 'identification', 'witness', 'party', 'ReportLabel', 'genernalinfoentry', 'identification', 'valuation', 'article', 'party_entry', 'witness', 'property_details_entry', 'TrnBehavioralPatterns', 'valuation_details'));
            $lang = $this->Session->read("sess_langauge");
            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $application_id = $this->Session->read("application_id");
            $searcherdata = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
            $searcherdata = $searcherdata[0][0];

            $imagedata = "img/state_logos_img/JH_logo.png";
            $image = file_get_contents($imagedata);
            $image_codes = base64_encode($image);
            $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' align='middle' /> ";
            $application_date = date('d/m/Y', strtotime($searcherdata['application_date']));
            $html_design = "";
            $html_design .= "<style>td{padding:5px;} div.ex1{width:90%; margin: auto; border: 3px solid red;} </style>"
                    . "<div class=ex1>"
                    . "<p align=center>" . $img1 . "</p>"
                    . "<h3 align=center style='color:#9C6F7A';> Govt. of Jharkhand  </h3>"
                    . "<h3 align=center style='color:#9C6F7A';>Department of Registration</h3>"
                    . "<h3 align=center style='color:#9C6F7A';>$officename</h3>"
                    . "<hr style color:red;>"
                    . "<h3 align=center style='color:#9C6F7A';>Receipt Challon for Fees deposited for Search/Copy/Non-Encumbrance</h3>"
                    . "<hr style color:red;>"
                    . "<table border=0 style='border-collapse:collapse;' width=100%>"
                    . "<tr><td style='border-bottom:1pt solid black;'><b>Application ID:</b></td><td style='border-bottom:1pt solid black;' colspan=3 align=left><b>" . $searcherdata['application_id'] . "</b></td></tr>"
                    . "<tr><td><b>Payment ID:</b></td><td></td>"
                    . "<td align=right><b>Payment Date:</b></td><td><b></b></td></tr>"
                    . "<tr><td><b>Transaction ID:</b></td><td><b></b></td>"
                    . "<td align=right><b>Payment Time:</b></td><td><b></b></td></tr>"
                    . "<tr><td><b>Reference No.</b></td><td colspan=3></td></tr>"
                    . "<tr><td><b>Date of Application:</b></td><td colspan=3 align=left><b>" . $application_date . "</b></td></tr>"
                    . "<tr><td><b>Applicant Name:</b></td><td colspan=3 align=left><b>" . $searcherdata['applicant_name'] . "</b></td></tr>"
                    . "<tr><td><b>Fee For:</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>Years:</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>Fee Amount:</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>GRN No.</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>CIN No.</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>Pay Status</b></td><td colspan=3 align=left></td></tr>"
                    . "</table><BR><BR>"
                    . "<h3 align=right style='padding: 35px;'>Registration Officer</h3>"
                    . "</div>";
            
            $scan_upload = $this->getscandata($_POST['token_no']);
            $downloaddeed = "";
            if (!empty($scan_upload)) {
                $downloaddeed .= "<a href='".$this->webroot . "Search/downloadfile/" . $scan_upload['scan_upload']['scan_name']."/Scanning/".$_POST['token_no']."/C' class='btn btn-primary'>Download Deed</a>";
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

            $imagedata = "img/state_logos_img/JH_logo.png";
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

            $html_design .= "<tr><td align=left style='color:black';><b>Anchal:</b>" . $vt[0][0]['taluka_name_en'] . " </td></tr>"
                    . "<tr><td align=left style='color:black';><b>Mauza:</b>" . $vt[0][0]['village_name_en'] . "  </td></tr>"
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
        foreach($getdata1 as $key=>$getdata) {
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
            $no=1;
            $propaddress = null;
            if(!empty($propdata)){
               foreach($propdata as $propdata1){
                   $propaddress = $propaddress."Property :- ".$no."<br>".$propdata1."<br><br>";
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
                
                        if($flag == 'P'){
                            $html_design .="<tr><td><b>Years : " . $_POST['from'] . " To " . $_POST['to'] . "</b></td></tr>"
                        . "<tr><td><b>Party Name : " . $result[0][0]['party_full_name_en'] . "</b></td></tr>";
                        } else if ($flag == 'R'){
                             $html_design .="<tr><td><b>Years:" . $_POST['from'] . " To " . $_POST['to'] . "</b></td></tr>"
                        . "<tr><td><b>Anchal : " . $vt[0][0]['taluka_name_en'] . "</b></td></tr>"
                                . "<tr><td><b>Mauza : " . $vt[0][0]['village_name_en'] . "</b></td></tr>";
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

//pr($path);exit;
            $files = glob($path."*");
            $now = time();
           if(!empty($files)){
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
//            pr($path);exit;
            
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


}
