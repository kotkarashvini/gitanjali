<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Vendor', 'reader');
App::uses('Vendor', 'Autoload');
App::import('Vendor', 'SimpleXLSX');

//include('Net/SSH2.php');

class SearchlegacyfinalController extends AppController {

    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha', 'Cookie',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Citizenentry', 'action' => 'welcome'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'welcomenote'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authorize' => array('Controller')
    ));
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $this->Session->renew();
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        $this->Auth->allow('upload_excel_to_tbl', 'get_party_type', 'test_file');
        $laug = $this->Session->read("sess_langauge");

        if (is_null($laug)) {
            $this->Session->write("sess_langauge", 'en');
        }

        if (isset($this->Security)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    function check_duplicate_batch_no() {
        $this->autoRender = false;
        $batchNo = $this->request->data['batch_no'];
        $this->loadModel('Legacy_tmp_generalinformation');
        return $this->Legacy_tmp_generalinformation->find('count', array('conditions' => array('batch_no' => $batchNo)));
    }

    public function upload_my() {
        try {
            $this->loadModel('Leg_file_config');
            $this->loadModel('Leg_uploaded_file_trn');
            $info_fileupload = $this->Leg_uploaded_file_trn->query("select doc_reg_no,doc_reg_date,doc_processing_year,state_id,office_id,document_id,input_fname,book_code from ngdrstab_trn_tmp_legacy_fileuploadinfo where file_transfer_flag='N'");
            $fieldlist = array();
pr('hello');exit();
            foreach ($info_fileupload as $filenm) {
                //   $fieldlist= $info_fileupload[0][0]['input_fname'];
                $fieldlist = $info_fileupload;
            }
            $this->set('filenm', $fieldlist);
            if ($this->request->is('post')) {
                //pr($this->request->data);exit;
                $formid = $_POST['formid'];

                if (!empty($info_fileupload)) {
                    $fieldlist = array();
                    //$file_ext = pathinfo($this->request->data['upload']['upload_file']['type'], PATHINFO_EXTENSION);
                    $file_ext = explode("/", $this->request->data['upload']['upload_file']['type']);
                    if (isset($file_ext)) {
                        if (@$file_ext[1] == 'pdf') {
                            $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                            $new_name = $this->request->data['upload']['upload_file']['name'];
                            $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Legacy_Documents/');
                            $success = move_uploaded_file($this->request->data['upload']['upload_file']['tmp_name'], $createFolder1 . '/' . $new_name);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);exit;
            $this->Session->setFlash($ex->getMessage());
        }
    }

    function upload_excel_to_tbl_data() {
        $this->loadModel('file_config');
        $this->loadModel('Legacy_tmp_generalinformation');
        $this->set('batch_no', 0);
        array_map(array($this, 'loadModel'), array('finyear', 'counter', 'Leg_generalinformation', 'Leg_application_submitted', 'Legacy_tmp_propertydetails', 'Leg_property_details_entry', 'Leg_trn_valuation', 'Leg_trn_valuation_details', 'Leg_parameter', 'Leg_party_entry', 'Leg_witness', 'Leg_identification', 'Leg_fee_calculation', 'Leg_fee_calculation_detail', 'Leg_uploaded_file_trn', 'Leg_counter', 'Leg_file_config', 'Leg_SerialNumbersFinal'));
        if ($this->request->is('post'))
         {
            //pr($this->request->data);exit;
            //$this->set('batch_no', $this->request->data['upload_excel_to_tbl']['batch_no']);

            if ($_POST['action'] == 'btnupload') {

                if ($this->request->data['upload_excel_to_tbl']['upload_file']['error'] == 0) 
                {
                    $file_ext = pathinfo($this->request->data['upload_excel_to_tbl']['upload_file']['name'], PATHINFO_EXTENSION);
                  pr('flie extension ');
                  pr($file_ext);

                    if ($file_ext == 'xlsx') 
                    {
                        pr('excel sheet');
                        $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                        $new_name = $this->request->data['upload_excel_to_tbl']['upload_file']['name'];
                        $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Legacy_Documents/');
                        $success = move_uploaded_file($this->request->data['upload_excel_to_tbl']['upload_file']['tmp_name'], $createFolder1 . '/' . $new_name);
                        $to_read = $path['Leg_file_config']['filepath'] . '/Legacy_Documents/' . $new_name;
                        $excel = new SimpleXLSX($to_read);
                        $excel = SimpleXLSX::parse($to_read);

                        $fieldlist = array();
                        $fieldlist1 = array();
                        $fieldlist2 = array();
                        $fieldlist3 = array();
                        $fieldlist4 = array();
                        $fieldlist5 = array();
                        $fieldlist6 = array();
                        $fieldlist7 = array();
                        $fieldlist8 = array();
                        $data = array();
                        $data1 = array();
                        $data2 = array();
                        $data3 = array();
                        $data4 = array();
                        $data5 = array();
                        $data6 = array();
                        $data7 = array();
                        $data8 = array();
//  *************************************************************************

                        $sheet0_no = $excel->rows(0);
                        $sheet0_count = count($sheet0_no);
                        // pr($sheet0_count);exit;

                        $fieldlist['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist['district_id']['text'] = 'is_required,is_numeric';
                        $fieldlist['taluka_id']['text'] = 'is_required,is_numeric';
                        $fieldlist['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist['doc_entered_office']['text'] = 'is_numeric';
                        $fieldlist['local_language_id']['text'] = 'is_required,is_numeric';
                        $fieldlist['article_id']['text'] = 'is_required,is_numeric'; //0-9
                        $fieldlist['exec_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist['presentation_no']['text'] = 'is_alphanumeric';
                        $fieldlist['presentation_dt']['text'] = 'is_date_empty';
                        $fieldlist['year_for_token']['text'] = 'is_required,is_numeric';
                        $fieldlist['doc_type']['text'] = 'is_numeric';
                        $fieldlist['reference_no']['text'] = 'is_alphanumeric';
                        $fieldlist['is_doc_scanned']['text'] = 'is_alpha';
//                        $fieldlist['doc_scan_date']['text'] = '';
                        $this->set('fieldlist', $fieldlist);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

                        $geninfocount = 0;
                        $allerr = array();
                        $errarr5 = array();
                        for ($m = 1; $m <= $sheet0_count - 1; $m++) {

                            $reference_sr_no = $excel->rows(0)[$m][0];
                            $doc_reg_no = $excel->rows(0)[$m][1];
                            $book_code = $excel->rows(0)[$m][2];
                            $doc_reg_date = $excel->rows(0)[$m][3];
                            $doc_process_yr = $excel->rows(0)[$m][4];
                            $state_id = $excel->rows(0)[$m][5];
                            $district_id = $excel->rows(0)[$m][6];
                            $taluka_id = $excel->rows(0)[$m][7];
                            $office_id = $excel->rows(0)[$m][8];
                            $doc_entered_office = $excel->rows(0)[$m][9];
                            $lang = $excel->rows(0)[$m][10];
                            $article = $excel->rows(0)[$m][11];
                            $exc_date = $excel->rows(0)[$m][12];
                            $presentation_no = $excel->rows(0)[$m][13];
                            $presentation_dt = $excel->rows(0)[$m][14];
                            $year_for_token = $excel->rows(0)[$m][15];
                            $doc_type = $excel->rows(0)[$m][16];
                            $reference_no = $excel->rows(0)[$m][17];

                            $is_doc_scanned = $excel->rows(0)[$m][18]; // is_doc_scanned
                            $doc_scan_date = ($excel->rows(0)[$m][19] ? date('Y-m-d', strtotime($excel->rows(0)[$m][19])) : date('Y-m-d')) . "'"; // $doc_scan_date
                            $user_id = $this->Auth->user('user_id');

                            $data['reference_sr_no']['text'] = $reference_sr_no;
                            $data['doc_reg_no']['text'] = $doc_reg_no;
                            $data['book_code']['text'] = $book_code;
                            $data['doc_reg_date']['text'] = $doc_reg_date;
                            $data['doc_processing_year']['text'] = $doc_process_yr;
                            $data['state_id']['text'] = $state_id;
                            $data['district_id']['text'] = $district_id;
                            $data['taluka_id']['text'] = $taluka_id;
                            $data['office_id']['text'] = $office_id;
                            $data['doc_entered_office']['text'] = $doc_entered_office;
                            $data['local_language_id']['text'] = $lang;
                            $data['article_id']['text'] = $article;
                            $data['exec_date']['text'] = $exc_date;
                            $data['presentation_no']['text'] = $presentation_no;
                            $data['presentation_dt']['text'] = $presentation_dt;
                            $data['year_for_token']['text'] = $year_for_token;
                            $data['doc_type']['text'] = $doc_type;
                            $data['reference_no']['text'] = $reference_no;
                            $data['is_doc_scanned']['text'] = $is_doc_scanned;
                            $data['doc_scan_date']['text'] = $doc_scan_date;

                            $data['user_id'] = $user_id;
                            $errarr = $this->validatedata($data, $fieldlist);

                            //pr($errarr);exit;
                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                $errarr5['sheetid'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(0)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 1';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }

                            for ($m1 = 1; $m1 <= $sheet0_count - 1; $m1++) {
                                if ($m != $m1) {
                                    if ($doc_reg_no == $excel->rows(0)[$m1][1] && $book_code == $excel->rows(0)[$m1][2] && $doc_process_yr == $excel->rows(0)[$m1][4] && $state_id == $excel->rows(0)[$m1][5] && $office_id == $excel->rows(0)[$m1][8]) {
                                        $geninfocount = 1;
                                        $errarr5['number'] = $doc_reg_no;
                                        $errarr5['sheetid'] = 'Sheet 1';
                                        $errarr5['err'] = 'Duplicate record found in General Information excel.';
                                        array_push($allerr, $errarr5);
                                        $this->set('allerr', $allerr);
                                    }
                                }
                            }

                            $Generalinfo = $this->Legacy_tmp_generalinformation->query("select * from ngdrstab_trn_tmp_legacy_generalinformation where doc_reg_no=" . "'" . $doc_reg_no . "' and $m=$m" . "and doc_processing_year=" . $doc_process_yr . " and state_id=" . $state_id . "and office_id=" . $office_id . "and book_code=" . "'" . $book_code . "'");

                            if (!empty($Generalinfo)) {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 1';
                                $errarr5['err'] = 'Duplicate Records found in Database General Information'; //duplicate
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }


                        /////////////////////////
                        //For Property Details Validation
                        $sheet1_no = $excel->rows(1);
                        $sheet1_count = count($sheet1_no);
                        $fieldlist1['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist1['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist1['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist1['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist1['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist1['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist1['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist1['property_serial_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist1['district_id']['text'] = 'is_required,is_numeric';
                        $fieldlist1['district_id']['subdivision_id'] = 'is_required,is_numeric'; //New Field
                        $fieldlist1['developed_land_types_id']['text'] = 'is_numeric';
                        $fieldlist1['taluka_id']['text'] = 'is_required,is_numeric';
                        $fieldlist1['circle_id']['text'] = 'is_required,is_numeric'; //New Field

                        $fieldlist1['village_id']['text'] = 'is_required,is_numeric';
                        $fieldlist1['location1']['text'] = 'is_alphaspace';
                        $fieldlist1['unique_property_no_en']['text'] = 'is_alphanumeric';
                        $fieldlist1['boundries_east_en']['text'] = 'is_alphaspace';
                        $fieldlist1['boundries_west_en']['text'] = 'is_alphaspace';
                        $fieldlist1['boundries_south_en']['text'] = 'is_alphaspace';
                        $fieldlist1['boundries_north_en']['text'] = 'is_alphaspace';
                        $fieldlist1['additional_information_en']['text'] = 'is_required,is_address_field';

                        $this->set('fieldlist1', $fieldlist1);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist1));

                        for ($m = 1; $m <= $sheet1_count - 1; $m++) {

                            $reference_sr_no = $excel->rows(1)[$m][0];
                            //pr($reference_sr_no);exit;
                            $doc_reg_no = $excel->rows(1)[$m][1];
                            $book_code = $excel->rows(1)[$m][2];
                            $doc_reg_date = $excel->rows(1)[$m][3];
                            $doc_process_yr = $excel->rows(1)[$m][4];
                            $state_id = $excel->rows(1)[$m][5];

                            $office_id = $excel->rows(1)[$m][6];
                            // pr($office_id);exit;
                            $property_serial_no = $excel->rows(1)[$m][7];
                            $district = $excel->rows(1)[$m][8];
                            $subdivision = $excel->rows(1)[$m][9];
                            $area_type = $excel->rows(1)[$m][10];
                            $taluka = $excel->rows(1)[$m][11];
                            $circle = $excel->rows(1)[$m][12];
                            $village = $excel->rows(1)[$m][13];
                            $location = $excel->rows(1)[$m][14];
                            $unique_prop_no = $excel->rows(1)[$m][15];
                            $boundries_east = $excel->rows(1)[$m][16];
                            $boundries_west = $excel->rows(1)[$m][17];
                            $boundries_south = $excel->rows(1)[$m][18];
                            $boundries_north = $excel->rows(1)[$m][19];
                            $prop_address = $excel->rows(1)[$m][20];

                            $data1['reference_sr_no'] = $reference_sr_no;
                            $data1['doc_reg_no']['text'] = $doc_reg_no;
                            $data1['book_code']['text'] = $book_code;
                            $data1['doc_reg_date']['text'] = $doc_reg_date;
                            $data1['doc_processing_year']['text'] = $doc_process_yr;
                            $data1['state_id']['text'] = $state_id;
                            $data1['office_id']['text'] = $office_id;
                            $data1['property_serial_no'] = $property_serial_no;
                            $data1['district_id']['text'] = $district;
                            $data1['subdivision_id']['text'] = $subdivision;
                            $data1['developed_land_types_id']['text'] = $area_type;
                            $data1['taluka_id']['text'] = $taluka;
                            $data1['circle_id']['text'] = $circle;
                            $data1['village_id']['text'] = $village;
                            $data1['location1']['text'] = $location;
                            $data1['unique_property_no_en']['text'] = $unique_prop_no;
                            $data1['boundries_east_en']['text'] = $boundries_east;
                            $data1['boundries_west_en']['text'] = $boundries_west;
                            $data1['boundries_south_en']['text'] = $boundries_south;
                            $data1['boundries_north_en']['text'] = $boundries_north;
                            $data1['additional_information_en']['text'] = $prop_address;

                            $errarr = $this->validatedata($data1, $fieldlist1);
                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                $errarr5['sheetid'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(1)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 2';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                            // pr($allerr);exit;
                            // To Check Excel Duplicate Data in Excel
                            for ($m1 = 1; $m1 <= $sheet1_count - 1; $m1++) {

                                if ($m != $m1) {
                                    if ($doc_reg_no == $excel->rows(1)[$m1][1] && $book_code == $excel->rows(1)[$m1][2] && $doc_process_yr == $excel->rows(1)[$m1][4] && $state_id == $excel->rows(1)[$m1][5] && $office_id = $excel->rows(1)[$m1][6] && $property_serial_no == $excel->rows(1)[$m1][7]) {

                                        $geninfocount = 1;
                                        $errarr5['number'] = $doc_reg_no;
                                        $errarr5['sheetid'] = 'Sheet 2';
                                        $errarr5['err'] = 'Duplicate record found in Property Details excel';
                                        array_push($allerr, $errarr5);
                                        $this->set('allerr', $allerr);
                                    }
                                }
                            }

                            $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_property_details_entry where doc_reg_no=" . "'" . $excel->rows(1)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(1)[$m][5] . "and office_id=" . $excel->rows(1)[$m][6] . "and property_serial_no=" . $excel->rows(1)[$m][7] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                            if (EMPTY($propertyinfo)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 2';
                                $errarr5['err'] = 'Duplicate Records found in Database Property Details';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }

                        //pr("Sndip");exit;
//pr($allerr);exit;
                        //////////////////////////////////////////// For validation of valuation details
                        $sheet2_no = $excel->rows(2);
                        $sheet2_count = count($sheet2_no);

                        $fieldlist2['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist2['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist2['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist2['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist2['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist2['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist2['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist2['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist2['property_serial_no']['text'] = 'is_required,is_numeric';
                        $fieldlist2['usage_main_catg_id']['text'] = 'is_required,is_numeric';
                        $fieldlist2['usage_sub_catg_id']['text'] = 'is_required,is_numeric';
                        $fieldlist2['area']['text'] = 'is_required,is_blankdotnumber';
                        $fieldlist2['area_unit']['text'] = 'is_required,is_select_req';
                        $fieldlist2['market_value']['text'] = 'is_required,is_numeric';
                        $fieldlist2['consideration_amt']['text'] = 'is_required,is_numeric';
                        $this->set('fieldlist2', $fieldlist2);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist2));

                        for ($m = 1; $m <= $sheet2_count - 1; $m++) {
                            $reference_sr_no = $excel->rows(2)[$m][0];
                            $doc_reg_no = $excel->rows(2)[$m][1];
                            $book_code = $excel->rows(2)[$m][2];
                            $doc_reg_date = $excel->rows(2)[$m][3];
                            $doc_process_yr = $excel->rows(2)[$m][4];
                            $state_id = $excel->rows(2)[$m][5];
                            $office_id = $excel->rows(2)[$m][6];
                            $property_serial_no = $excel->rows(2)[$m][7];
                            $usage_main_catg_id = $excel->rows(2)[$m][8];
                            $usage_sub_catg_id = $excel->rows(2)[$m][9];
                            $area = $excel->rows(2)[$m][10];
                            $area_unit = $excel->rows(2)[$m][11];
                            $market_value = $excel->rows(2)[$m][12];
                            $consideration_amt = $excel->rows(2)[$m][13];

                            $data2['reference_sr_no']['text'] = $reference_sr_no;
                            $data2['doc_reg_no']['text'] = $doc_reg_no;
                            $data2['book_code']['text'] = $book_code;
                            $data2['doc_reg_date']['text'] = $doc_reg_date;
                            $data2['doc_processing_year']['text'] = $doc_process_yr;
                            $data2['state_id']['text'] = $state_id;
                            $data2['office_id']['text'] = $office_id;
                            $data2['property_serial_no'] = $property_serial_no;
                            $data2['usage_main_catg_id']['text'] = $usage_main_catg_id;
                            $data2['usage_sub_catg_id']['text'] = $usage_sub_catg_id;
                            $data2['area']['text'] = $area;
                            $data2['area_unit']['text'] = $area_unit;
                            $data2['market_value']['text'] = $market_value;
                            $data2['consideration_amt']['text'] = $consideration_amt;

                            $errarr = $this->validatedata($data2, $fieldlist2);

                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                $errarr5['sheetid'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(2)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 3';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }

                            // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet2_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(2)[$m1][1] && $doc_process_yr == $excel->rows(2)[$m1][3] && $state_id == $excel->rows(2)[$m1][4] && $office_id=$excel->rows(2)[$m1][5]  && $property_serial_no == $excel->rows(2)[$m1][6]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 3';
//                                        $errarr5['err'] = 'Duplicate record found in Valuation Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }
                            //  $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_valuation where doc_reg_no=" . "'" . $excel->rows(2)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(2)[$m][5] . "and office_id=" . $excel->rows(2)[$m][6] . "and property_serial_no=" . $excel->rows(2)[$m][7] . "and $m=$m" . " and book_code=" . "'" . $book_code . "'");
                            $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_valuation where doc_reg_no=" . "'" . $excel->rows(2)[$m][1] . "' and doc_processing_year=" . $excel->rows(2)[$m][4] . " and state_id=" . $excel->rows(2)[$m][5] . "and office_id=" . $excel->rows(2)[$m][6] . "and property_serial_no=" . $excel->rows(2)[$m][7] . "and $m=$m" . " and book_code=" . "'" . $excel->rows(2)[$m][2] . "'");
                            // pr($propertyinfo);exit;

                            if (EMPTY($propertyinfo)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 3';
                                $errarr5['err'] = 'Duplicate Records found in Database Valuation Details';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }

//////////////////////////////////////////// For validation of property attribute details
                        $sheet3_no = $excel->rows(3);
                        $sheet3_count = count($sheet3_no);
                        $fieldlist3['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist3['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist3['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist3['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist3['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist3['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist3['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist3['property_serial_no']['text'] = 'is_required,is_numeric';
                        $fieldlist3['attribute_id']['text'] = 'is_required,is_numeric';
                        $fieldlist3['attribute_value']['text'] = 'is_alphanumdashslash';
                        $fieldlist3['attribute_value2']['text'] = 'is_alphanumdashslash';
                        $fieldlist3['attribute_value3']['text'] = 'is_alphanumdashslash';
                        $this->set('fieldlist3', $fieldlist3);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist3));

                        for ($m = 1; $m <= $sheet3_count - 1; $m++) {
                            $reference_sr_no = $excel->rows(3)[$m][0];
                            $doc_reg_no = $excel->rows(3)[$m][1];
                            $book_code = $excel->rows(3)[$m][2];
                            $doc_reg_date = $excel->rows(3)[$m][3];
                            $doc_process_yr = $excel->rows(3)[$m][4];
                            $state_id = $excel->rows(3)[$m][5];
                            $office_id = $excel->rows(3)[$m][6];
                            $property_serial_no = $excel->rows(3)[$m][7];
                            $attribute_id = $excel->rows(3)[$m][8];
                            $attribute_value = $excel->rows(3)[$m][9];
                            $attribute_value1 = $excel->rows(3)[$m][10];
                            $attribute_value2 = $excel->rows(3)[$m][11];
                            $data3['reference_sr_no']['text'] = $reference_sr_no;
                            $data3['doc_reg_no']['text'] = $doc_reg_no;
                            $data3['book_code']['text'] = $book_code;
                            $data3['doc_reg_date']['text'] = $doc_reg_date;
                            $data3['doc_processing_year']['text'] = $doc_process_yr;
                            $data3['state_id']['text'] = $state_id;
                            $data3['office_id']['text'] = $office_id;
                            $data3['property_serial_no'] = $property_serial_no;
                            $data3['attribute_id']['text'] = $attribute_id;
                            $data3['attribute_value']['text'] = $attribute_value;
                            $data3['attribute_value2']['text'] = $attribute_value1;
                            $data3['attribute_value3']['text'] = $attribute_value2;

                            $errarr = $this->validatedata($data3, $fieldlist3);
                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                $errarr5['sheetid'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(3)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 4';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }


                            // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet3_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(3)[$m1][1] && $doc_process_yr == $excel->rows(3)[$m1][3] && $state_id == $excel->rows(3)[$m1][4] && $office_id=$excel->rows(3)[$m1][5]  && $property_serial_no == $excel->rows(3)[$m1][6]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 4';
//                                        $errarr5['err'] = 'Duplicate record found in Parameter Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }

                            $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_parameter where doc_reg_no=" . "'" . $excel->rows(3)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(3)[$m][5] . "and office_id=" . $excel->rows(3)[$m][6] . "and property_serial_no=" . $excel->rows(3)[$m][7] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                            if (EMPTY($propertyinfo)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 4';
                                $errarr5['err'] = 'Duplicate Records found in Database Parameter Details';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }
//////////////////////For Validation of Party Details
                        $fieldlist4['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist4['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist4['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist4['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist4['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist4['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist4['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist4['party_type_id']['text'] = 'is_required,is_numeric';
                        $fieldlist4['party_catg_id']['text'] = 'is_numeric'; //0-9
                        $fieldlist4['party_full_name_en']['text'] = 'is_required';
                        $fieldlist4['age']['text'] = 'is_numeric'; //'is_alphanumeric';
                        $fieldlist4['uid']['text'] = 'is_alphanumspace'; //'is_uidnum';
                        $fieldlist4['pan_no']['text'] = 'is_alphanumspace'; //'is_pancard';
                        $fieldlist4['father_full_name_en']['text'] = 'is_alphacommadot';
                        $fieldlist4['gender_id']['text'] = 'is_numeric';
                        $fieldlist4['address_en']['text'] = 'is_required,is_address_field';
                        $fieldlist4['pin_code']['text'] = //'is_pincode';
                                $this->set('fieldlist4', $fieldlist4);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist4));
                        $sheet4_no = $excel->rows(4);
                        $sheet4_count = count($sheet4_no);

                        for ($m = 1; $m <= $sheet4_count - 1; $m++) {
                            $reference_sr_no = $excel->rows(4)[$m][0];
                            $doc_reg_no = $excel->rows(4)[$m][1];
                            $book_code = $excel->rows(4)[$m][2];
                            $doc_reg_date = $excel->rows(4)[$m][3];
                            $doc_process_yr = $excel->rows(4)[$m][4];
                            $state_id = $excel->rows(4)[$m][5];
                            $office_id = $excel->rows(4)[$m][6];
                            $party_type = $excel->rows(4)[$m][7];
                            $party_catg = $excel->rows(4)[$m][8];
                            $party_fullnm = $excel->rows(4)[$m][9];
                            $age = $excel->rows(4)[$m][10];
                            $Aadhar_no = $excel->rows(4)[$m][11];
                            $pan_no = $excel->rows(4)[$m][12];
                            $father_fullnm = $excel->rows(4)[$m][13];
                            $Gender = $excel->rows(4)[$m][14];
                            $party_address = $excel->rows(4)[$m][15];
                            $pincode = $excel->rows(4)[$m][16];

                            $data4['reference_sr_no']['text'] = $reference_sr_no;
                            $data4['doc_reg_no']['text'] = $doc_reg_no;
                            $data4['book_code']['text'] = $book_code;
                            $data4['doc_reg_date']['text'] = $doc_reg_date;
                            $data4['doc_processing_year']['text'] = $doc_process_yr;
                            $data4['state_id']['text'] = $state_id;
                            $data4['office_id']['text'] = $office_id;
                            $data4['party_type_id']['text'] = $party_type;
                            $data4['party_catg_id']['text'] = $party_catg;
                            $data4['party_full_name_en']['text'] = $party_fullnm;
                            $data4['age']['text'] = $age;
                            $data4['uid']['text'] = $Aadhar_no;
                            $data4['pan_no']['text'] = $pan_no;
                            $data4['father_full_name_en']['text'] = $father_fullnm;
                            $data4['gender_id']['text'] = $Gender;
                            $data4['address_en']['text'] = $party_address;
                            $data4['pin_code']['text'] = $pincode;

                            $errarr = $this->validatedata($data4, $fieldlist4);
                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                $errarr5['sheetid'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(4)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 5';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }

                            // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet4_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(4)[$m1][1] && $doc_process_yr == $excel->rows(4)[$m1][3] && $state_id == $excel->rows(4)[$m1][4] && $office_id=$excel->rows(4)[$m1][5]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 5';
//                                        $errarr5['err'] = 'Duplicate record found in Party Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }

                            $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_party_entry_new where doc_reg_no=" . "'" . $excel->rows(4)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(4)[$m][5] . "and office_id=" . $excel->rows(4)[$m][6] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                            if (EMPTY($propertyinfo)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 5';
                                $errarr5['err'] = 'Duplicate Records found in Database Party Details';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }
                        ///////////////////////////For Validation of Witness Details
                        $fieldlist5['reference_sr_no']['text'] = 'is_numeric';
                        $fieldlist5['doc_reg_no']['text'] = 'is_alphanumeric';
                        $fieldlist5['book_code']['text'] = 'is_alphanumeric';
                        $fieldlist5['doc_reg_date']['text'] = 'is_date_empty';
                        $fieldlist5['doc_processing_year']['text'] = 'is_numeric';
                        $fieldlist5['state_id']['text'] = 'is_numeric';
                        $fieldlist5['office_id']['text'] = 'is_numeric';
                        $fieldlist5['salutation']['text'] = 'is_numeric';
                        $fieldlist5['witness_full_name_en']['text'] = 'is_alphanumspace'; //0-9
                        $fieldlist5['father_full_name_en']['text'] = 'is_alphanumspace';
                        $fieldlist5['dob']['text'] = 'is_date_empty';
                        $fieldlist5['age']['text'] = 'is_numeric';
                        $fieldlist5['gender_id']['text'] = 'is_numeric';
                        $fieldlist5['email_id']['text'] = 'is_email';
                        $fieldlist5['mobile_no']['text'] = 'is_numeric';
                        $fieldlist5['identificationtype_id']['text'] = 'is_numeric';
                        $fieldlist5['identificationtype_desc_en']['text'] = 'is_alphanumeric';
                        $fieldlist5['district_id']['text'] = 'is_numeric';
                        $fieldlist5['taluka_id']['text'] = 'is_numeric';
                        $fieldlist5['village_id']['text'] = 'is_numeric';
                        $fieldlist5['address_en']['text'] = 'is_address_field';
                        $this->set('fieldlist5', $fieldlist5);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist5));

                        $sheet5_no = $excel->rows(5);
                        $sheet5_count = count($sheet5_no);
                        if ($sheet5_count > 1) {
                            for ($m = 1; $m <= $sheet5_count - 1; $m++) {
                                $reference_sr_no = $excel->rows(5)[$m][0];
                                $doc_reg_no = $excel->rows(5)[$m][1];
                                $book_code = $excel->rows(5)[$m][2];
                                $doc_reg_date = $excel->rows(5)[$m][3];
                                $doc_process_yr = $excel->rows(5)[$m][4];
                                $state_id = $excel->rows(5)[$m][5];
                                $office_id = $excel->rows(5)[$m][6];
                                $saluation = $excel->rows(5)[$m][7];
                                $witness_fullnm = $excel->rows(5)[$m][8];
                                $father_fullnm = $excel->rows(5)[$m][9];
                                $dob = $excel->rows(5)[$m][10];
                                $age = $excel->rows(5)[$m][11];
                                $gender_id = $excel->rows(5)[$m][12];
                                $email_id = $excel->rows(5)[$m][13];
                                $mobileno = $excel->rows(5)[$m][14];
                                //  pr($mobileno);exit;
                                $identification_type_id = $excel->rows(5)[$m][15];
                                $identification_type_desc = $excel->rows(5)[$m][16];
                                $district_id = $excel->rows(5)[$m][17];
                                $taluka_id = $excel->rows(5)[$m][18];
                                $village_id = $excel->rows(5)[$m][19];
                                $address_en = $excel->rows(5)[$m][20];

                                $data5['reference_sr_no']['text'] = $reference_sr_no;
                                $data5['doc_reg_no']['text'] = $doc_reg_no;
                                $data5['book_code']['text'] = $book_code;
                                $data5['doc_reg_date']['text'] = $doc_reg_date;
                                $data5['doc_processing_year']['text'] = $doc_process_yr;
                                $data5['state_id']['text'] = $state_id;
                                $data5['office_id']['text'] = $office_id;
                                $data5['salutation']['text'] = $saluation;
                                $data5['witness_full_name_en']['text'] = $witness_fullnm;
                                $data5['father_full_name_en']['text'] = $father_fullnm;
                                $data5['dob']['text'] = $dob;
                                $data5['age']['text'] = $age;
                                $data5['gender_id']['text'] = $gender_id;
                                $data5['email_id']['text'] = $email_id;
                                $data5['mobile_no']['text'] = $mobileno;
                                $data5['identificationtype_id']['text'] = $identification_type_id;
                                $data5['identificationtype_desc_en']['text'] = $identification_type_desc;
                                $data5['district_id']['text'] = $district_id;
                                $data5['taluka_id']['text'] = $taluka_id;
                                $data5['village_id']['text'] = $village_id;
                                $data5['address_en']['text'] = $address_en;
                                $errarr = $this->validatedata($data5, $fieldlist5);
//pr($data5);pr($fieldlist5);exit;
                                if ($this->ValidationError($errarr)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['err'] = '';
                                    $errarr5['sheetid'] = '';
                                    foreach ($errarr as $key => $value) {
                                        if (!empty($value)) {
                                            $errarr5['err'] .= $key . '-' . $value . ", ";
                                        }
                                    }
                                    $errarr5['number'] = $excel->rows(5)[$m][1];
                                    $errarr5['sheetid'] = 'Sheet 6';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }
                                $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_witness where doc_reg_no=" . "'" . $excel->rows(5)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(5)[$m][5] . "and office_id=" . $excel->rows(5)[$m][6] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                                if (EMPTY($propertyinfo)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['number'] = $doc_reg_no;
                                    $errarr5['sheetid'] = 'Sheet 6';
                                    $errarr5['err'] = 'Duplicate Records found in Database Witness Details';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }
                            }
                        }
/////For validation of identifier Details
                        $fieldlist6['reference_sr_no']['text'] = 'is_numeric';
                        $fieldlist6['doc_reg_no']['text'] = 'is_alphanumeric';
                        $fieldlist6['book_code']['text'] = 'is_alphanumeric';
                        $fieldlist6['doc_reg_date']['text'] = 'is_date_empty';
                        $fieldlist6['doc_processing_year']['text'] = 'is_numeric';
                        $fieldlist6['state_id']['text'] = 'is_numeric';
                        $fieldlist6['office_id']['text'] = 'is_numeric';
                        $fieldlist6['salutation']['text'] = 'is_numeric';
                        $fieldlist6['identification_full_name_en']['text'] = 'is_alphanumspace'; //0-9
                        $fieldlist6['father_full_name_en']['text'] = 'is_alphanumspace';
                        $fieldlist6['dob']['text'] = 'is_date_empty';
                        $fieldlist6['age']['text'] = 'is_numeric';
                        $fieldlist6['gender_id']['text'] = 'is_numeric';
                        $fieldlist6['mobile_no']['text'] = 'is_required';
                        $fieldlist6['identificationtype_id']['text'] = 'is_numeric';
                        $fieldlist6['identificationtype_desc_en']['text'] = 'is_alphanumeric';
                        $fieldlist6['district_id']['text'] = 'is_numeric';
                        $fieldlist6['taluka_id']['text'] = 'is_numeric';
                        $fieldlist6['village_id']['text'] = 'is_numeric';
                        $fieldlist6['address_en']['text'] = 'is_address_field';
                        $this->set('fieldlist6', $fieldlist6);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist6));

                        $sheet6_no = $excel->rows(6);
                        $sheet6_count = count($sheet6_no);
                        if ($sheet6_count > 1) {
                            for ($m = 1; $m <= $sheet4_count - 1; $m++) {
                                $reference_sr_no = $excel->rows(6)[$m][0];
                                $doc_reg_no = $excel->rows(6)[$m][1];
                                $book_code = $excel->rows(6)[$m][2];
                                $doc_reg_date = $excel->rows(6)[$m][3];
                                $doc_process_yr = $excel->rows(6)[$m][4];
                                $state_id = $excel->rows(6)[$m][5];
                                $office_id = $excel->rows(6)[$m][6];
                                $saluation = $excel->rows(6)[$m][7];
                                $identifier_fullnm = $excel->rows(6)[$m][8];
                                $father_fullnm = $excel->rows(6)[$m][9];
                                $dob = $excel->rows(6)[$m][10];
                                $age = $excel->rows(6)[$m][11];
                                $gender_id = $excel->rows(6)[$m][12];
                                $mobileno = $excel->rows(6)[$m][13];
                                $identification_type_id = $excel->rows(6)[$m][14];
                                $identification_type_desc = $excel->rows(6)[$m][15];
                                $district_id = $excel->rows(6)[$m][16];
                                $taluka_id = $excel->rows(6)[$m][17];
                                $village_id = $excel->rows(6)[$m][18];
                                $address_en = $excel->rows(6)[$m][19];

                                $data6['reference_sr_no']['text'] = $reference_sr_no;
                                $data6['doc_reg_no']['text'] = $doc_reg_no;
                                $data6['book_code']['text'] = $book_code;
                                $data6['doc_reg_date']['text'] = $doc_reg_date;
                                $data6['doc_processing_year']['text'] = $doc_process_yr;
                                $data6['state_id']['text'] = $state_id;
                                $data6['office_id']['text'] = $office_id;
                                $data6['salutation']['text'] = $saluation;
                                $data6['identification_full_name_en']['text'] = $identifier_fullnm;
                                $data6['father_full_name_en']['text'] = $father_fullnm;
                                $data6['dob']['text'] = $dob;
                                $data6['age']['text'] = $age;
                                $data6['gender_id']['text'] = $gender_id;
                                $data6['mobile_no']['text'] = $mobileno;
                                $data6['identificationtype_id']['text'] = $identification_type_id;
                                $data6['identificationtype_desc_en']['text'] = $identification_type_desc;
                                $data6['district_id']['text'] = $district_id;
                                $data6['taluka_id']['text'] = $taluka_id;
                                $data6['village_id']['text'] = $village_id;
                                $data6['address_en']['text'] = $address_en;

                                $errarr = $this->validatedata($data6, $fieldlist6);
                                if ($this->ValidationError($errarr)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['err'] = '';
                                    $errarr5['sheetid'] = '';
                                    foreach ($errarr as $key => $value) {
                                        if (!empty($value)) {
                                            $errarr5['err'] .= $key . '-' . $value . ", ";
                                        }
                                    }
                                    $errarr5['number'] = $excel->rows(6)[$m][1];
                                    $errarr5['sheetid'] = 'Sheet 7';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }

                                // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet6_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(6)[$m1][1] && $doc_process_yr == $excel->rows(6)[$m1][3] && $state_id == $excel->rows(6)[$m1][4] && $office_id=$excel->rows(6)[$m1][5]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 7';
//                                        $errarr5['err'] = 'Duplicate record found in Identifier Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }

                                $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_identifier where doc_reg_no=" . "'" . $excel->rows(6)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(6)[$m][5] . "and office_id=" . $excel->rows(6)[$m][6] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                                if (EMPTY($propertyinfo)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['number'] = $doc_reg_no;
                                    $errarr5['sheetid'] = 'Sheet 7';
                                    $errarr5['err'] = 'Duplicate Records found in Database Identifier Details';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }
                            }
                        }
//////////////For validation of Fee details
                        $fieldlist7['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist7['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist7['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist7['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist7['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist7['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist7['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist7['fee_item_id']['text'] = 'is_required,is_numeric';
                        $fieldlist7['final_value']['text'] = 'is_required,is_alphanumspace';

                        $sheet7_no = $excel->rows(7);
                        $sheet7_count = count($sheet7_no);
                        if ($sheet7_count > 1) {
                            for ($m = 1; $m <= $sheet7_count - 1; $m++) {
                                $reference_sr_no = $excel->rows(7)[$m][0];
                                $doc_reg_no = $excel->rows(7)[$m][1];
                                $book_code = $excel->rows(7)[$m][2];
                                $doc_reg_date = $excel->rows(7)[$m][3];
                                $doc_process_yr = $excel->rows(7)[$m][4];
                                $state_id = $excel->rows(7)[$m][5];
                                $office_id = $excel->rows(7)[$m][6];
                                $fee_item = $excel->rows(7)[$m][7];
                                $final_val = $excel->rows(7)[$m][8];

                                $data7['reference_sr_no']['text'] = $reference_sr_no;
                                $data7['doc_reg_no']['text'] = $doc_reg_no;
                                $data7['book_code']['text'] = $book_code;
                                $data7['doc_reg_date']['text'] = $doc_reg_date;
                                $data7['doc_processing_year']['text'] = $doc_process_yr;
                                $data7['state_id']['text'] = $state_id;
                                $data7['office_id']['text'] = $office_id;
                                $data7['fee_item_id']['text'] = $fee_item;
                                $data7['final_value']['text'] = $final_val;

                                $errarr = $this->validatedata($data7, $fieldlist7);

                                if ($this->ValidationError($errarr)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['err'] = '';
                                    foreach ($errarr as $key => $value) {
                                        if (!empty($value)) {
                                            $errarr5['err'] .= $key . '-' . $value . ", ";
                                        }
                                    }
                                    $errarr5['number'] = $excel->rows(7)[$m][1];
                                    $errarr5['sheetid'] = 'Sheet 8';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }

                                // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet7_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(7)[$m1][1] && $doc_process_yr == $excel->rows(7)[$m1][3] && $state_id == $excel->rows(7)[$m1][4] && $office_id=$excel->rows(7)[$m1][5]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 8';
//                                        $errarr5['err'] = 'Duplicate record found in Fee Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }

                                $feeinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_fee_calculation where doc_reg_no=" . "'" . $excel->rows(7)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(7)[$m][5] . "and office_id=" . $excel->rows(7)[$m][6] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                                if (empty($feeinfo)) {
                                    
                                } else {
                                    $geninfocount = 1;
                                    $errarr5['number'] = $doc_reg_no;
                                    $errarr5['sheetid'] = 'Sheet 8';
                                    $errarr5['err'] = 'Duplicate Records found in Database Fee Details';
                                    array_push($allerr, $errarr5);
                                    $this->set('allerr', $allerr);
                                }
                            }
                        }


                        ///For Validation of document upload
                        $fieldlist8['reference_sr_no']['text'] = 'is_required,is_numeric';
                        $fieldlist8['doc_reg_no']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist8['book_code']['text'] = 'is_required,is_alphanumeric';
                        $fieldlist8['doc_reg_date']['text'] = 'is_required,is_date_empty';
                        $fieldlist8['doc_processing_year']['text'] = 'is_required,is_numeric';
                        $fieldlist8['state_id']['text'] = 'is_required,is_numeric';
                        $fieldlist8['office_id']['text'] = 'is_required,is_numeric';
                        $fieldlist8['document_id']['text'] = 'is_required,is_numeric';
                        $fieldlist8['file_name']['text'] = 'is_required';

                        $sheet8_no = $excel->rows(8);
                        $sheet8_count = count($sheet8_no);
                        for ($m = 1; $m <= $sheet8_count - 1; $m++) {
                            $reference_sr_no = $excel->rows(8)[$m][0];
                            $doc_reg_no = $excel->rows(8)[$m][1];
                            $book_code = $excel->rows(8)[$m][2];
                            $doc_reg_date = $excel->rows(8)[$m][3];
                            $doc_process_yr = $excel->rows(8)[$m][4];
                            $state_id = $excel->rows(8)[$m][5];
                            $office_id = $excel->rows(8)[$m][6];
                            $document_id = $excel->rows(8)[$m][7];
                            $file_name = $excel->rows(8)[$m][8];

                            $data8['reference_sr_no']['text'] = $reference_sr_no;
                            $data8['doc_reg_no']['text'] = $doc_reg_no;
                            $data8['book_code']['text'] = $book_code;
                            $data8['doc_reg_date']['text'] = $doc_reg_date;
                            $data8['doc_processing_year']['text'] = $doc_process_yr;
                            $data8['state_id']['text'] = $state_id;
                            $data8['office_id']['text'] = $office_id;
                            $data8['document_id']['text'] = $document_id;
                            $data8['file_name']['text'] = $file_name;

                            $errarr = $this->validatedata($data8, $fieldlist8);

                            if ($this->ValidationError($errarr)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['err'] = '';
                                foreach ($errarr as $key => $value) {
                                    if (!empty($value)) {
                                        $errarr5['err'] .= $key . '-' . $value . ", ";
                                    }
                                }
                                $errarr5['number'] = $excel->rows(8)[$m][1];
                                $errarr5['sheetid'] = 'Sheet 9';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }

                            // To Check Excel Duplicate Data in Excel
//                            for ($m1 = 1; $m1 <= $sheet7_count - 1; $m1++) {
//                                if ($m != $m1) {
//                                    if ($doc_reg_no == $excel->rows(7)[$m1][1] && $doc_process_yr == $excel->rows(7)[$m1][3] && $state_id == $excel->rows(7)[$m1][4] && $office_id=$excel->rows(7)[$m1][5]) {
//                                        $geninfocount = 1;
//                                        $errarr5['number'] = $doc_reg_no;
//                                        $errarr5['sheetid'] = 'Sheet 8';
//                                        $errarr5['err'] = 'Duplicate record found in Fee Details excel';
//                                        array_push($allerr, $errarr5);
//                                        $this->set('allerr', $allerr);
//                                    }
//                                }
//                            }

                            $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_fileuploadinfo where doc_reg_no=" . "'" . $excel->rows(8)[$m][1] . "' and doc_processing_year=$doc_process_yr and state_id=" . $excel->rows(8)[$m][5] . "and office_id=" . $excel->rows(8)[$m][6] . "and $m=$m" . "and book_code=" . "'" . $book_code . "'");
                            if (EMPTY($propertyinfo)) {
                                
                            } else {
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 9';
                                $errarr5['err'] = 'Duplicate Records found in Database Document Upload';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                            }
                        }

                        //////////////////////////
                        ///For Data Save in temporary table
                        $info_arr = array();
                        if ($geninfocount == 0) {


                            $info_arr = $this->save_tmp_general_info($sheet0_count, $excel, $info_arr);
                            $info_property_serial_no = $this->save_tmp_property_details($sheet1_count, $excel, $info_arr);
                            // pr($info_property_serial_no);exit;
                            $this->save_tmp_valuation($sheet2_count, $excel, $info_arr, $info_property_serial_no);

                            $this->save_tmp_parameter($sheet3_count, $excel, $info_arr, $info_property_serial_no);
                            $this->save_tmp_party_details($sheet4_count, $excel, $info_arr);

                            $this->save_tmp_witness_details($sheet5_count, $excel, $info_arr);

                            $this->save_tmp_identifier_details($sheet6_count, $excel, $info_arr);

                            $this->save_tmp_fee_details($sheet7_count, $excel, $info_arr);

                            $this->save_tmp_document_upload_details($sheet8_count, $excel, $info_arr);
                        }
                    } 
                    else {
                        $this->Session->setFlash('Please Select Valid File..');
                    }
                } else {
                    $this->Session->setFlash('Please select file to upload..');
                }
            } else if ($_POST['action'] == 'btndownload') 
            {
                pr('in upload docuent');
                $fetch_string = file_get_contents('ssh2.sftp://root:Ngdrs@app01@10.194.162.176');
//
//
//
//
//$ssh = new Net_SSH2('10.194.162.176');
//if (!$ssh->login('root', 'Ngdrs@app01')) {
//    pr('Login Failed');exit;
//}
//else
//{
//    pr('Login s');exit;
//}
//
//
//               $host = '10.194.162.176';
//$port = 22;
//$username = 'root';
//$password = 'Ngdrs@app01';
//$remoteDir = '/Home/';
//$localDir = '/hello.php/';
//
//if (!function_exists("ssh2_connect"))
//    die('Function ssh2_connect does not exist.');
//
//if (!$connection = ssh2_connect($host, $port))
//    die('Failed to connect.');
//
//if (!ssh2_auth_password($connection, $username, $password))
//    die('Failed to authenticate.');
//
//if (!$sftp_conn = ssh2_sftp($connection))
//    die('Failed to create a sftp connection.');
//
//if (!$dir = opendir("ssh2.sftp://$sftp_conn$remoteDir"))
//    die('Failed to open the directory.');
//
                //$contents = file_get_contents('sftp://root:ngdrs@2018#$%@10.153.45.29/jj.pdf');
                //$contents = file_get_contents('ssh2.sftp://root:ngdrs@app01@10.194.162.176::22');
                $fetch_string = file_get_contents('ssh2.sftp://root:Ngdrs@app01@10.194.162.176');

                $info_fileupload = $this->Leg_uploaded_file_trn->query("select doc_reg_no,doc_reg_date,doc_processing_year,state_id,office_id,document_id,input_fname from ngdrstab_trn_tmp_legacy_fileuploadinfo where file_transfer_flag='N'");
                pr('file not uploaded of follwing doc');
                pr( $info_fileupload ); exit;
                $get_district = $this->Leg_uploaded_file_trn->query("select district_id from ngdrstab_mst_office where office_id=" . $info_fileupload[0][0]['office_id']);
//pr($get_district);exit;
                if (!empty($info_fileupload)) {

                    $contents = file_get_contents('ftp://Acer:nic@10.153.8.186/' . $get_district[0][0]['district_id'] . '/' . $file_tokenno[0][0]['office_id'] . '/' . $file_tokenno[0][0]['doc_processing_year'] . '/' . $info_fileupload[0][0]['input_fname']);

                    // $contents = file_get_contents('ftp://Acer:nic@10.153.8.186/' . $info_fileupload[0][0]['input_fname']);
                    //  $contents = file_get_contents('ftp://Acer:nic@10.153.8.186/' . '000001_I_1998_103_1.pdf');
                    //pr($contents);exit;
                    // $get_fileupload_id=explode("_", $info_fileupload[0][0]['input_fname']);
                    foreach ($info_fileupload as $filenm) {
                        //pr($filenm);exit;
                        $file_tokenno = $this->Leg_uploaded_file_trn->query("select app.token_no,taluka_id,info.district_id,district_name_en,info.office_id from ngdrstab_trn_legacy_application_submitted app
inner join ngdrstab_trn_legacy_generalinformation  info on info.token_no=app.token_no
inner join ngdrstab_conf_admblock3_district dist on dist.district_id=info.district_id where final_doc_reg_no=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and final_stamp_date=" . "'" . $filenm[0]['doc_reg_date'] . "'" . "and app.state_id=" . $filenm[0]['state_id'] . "and app.office_id=" . $filenm[0]['office_id']);
//                        pr($file_tokenno);
//                        exit;

                        if (!empty($file_tokenno)) {
                            // $contents = file_get_contents('ftp://Acer:nic@10.153.8.186/'.'270/'.'103/'.'1998/' . '000001_I_1998_103_1.pdf');
                            // pr($contents);exit;

                            $get_document_id = $this->Leg_uploaded_file_trn->query("select document_id,input_fname from ngdrstab_trn_legacy_fileuploadinfo where token_no=" . $file_tokenno[0][0]['token_no']);
                            $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                            $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Documents/');
                            $dist = $this->create_folder($createFolder1, $file_tokenno[0][0]['district_name_en'] . '/');
                            $taluka = $this->create_folder($dist, $file_tokenno[0][0]['taluka_id'] . '/');
                            $office = $this->create_folder($taluka, $file_tokenno[0][0]['office_id'] . '/');
                            $final_folder1 = $this->create_folder($office, $file_tokenno[0][0]['token_no'] . '/');
                            $final_folder = $this->create_folder($final_folder1, 'Uploads/');
                            //$new_name = $token . '_' . $fid;
                            $new_name = $file_tokenno[0][0]['token_no'] . '_' . $get_document_id[0][0]['document_id'];
                            $localfile = fopen($final_folder . '/' . $new_name . '.' . 'pdf', "w");
                            if (fwrite($localfile, $contents)) {
                                //echo "success";
                                $string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_tmp_legacy_fileuploadinfo set file_transfer_flag=" . "'" . 'Y' . "'" . ", out_fname=" . "'" . $new_name . "'" . ",file_transfer_date=" . "'" . date('Y-m-d H:i:s') . "'" . "where doc_reg_no=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and document_id=" . $get_document_id[0][0]['document_id']);
                                // $string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_tmp_legacy_fileuploadinfo set file_transfer_flag='Y and out_fname='$new_name' where doc_reg_no=" . "'" . $filenm[0]['doc_reg_no'] . "'"."and document_id=".$get_document_id[0][0]['document_id']);
                                //$this->Session->setFlash('File Uploaded Successfully..');
                                $this->Session->setFlash('File Saved Successfully..');
                            } else {
                                // echo "unable to write file";
                                $this->Session->setFlash('File Not Uploaded..');
                                fclose($localfile);
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash('No Records Found');
                }
            } 
            else if ($_POST['action'] == 'btnuploaddoc') 
            {

                $this->loadModel('Leg_uploaded_file_trn');
                $this->loadModel('office');
                $this->loadModel('Leg_file_config');

                if ($this->request->is('post')) {

                    //pr($this->request->data);exit;
                    //data store into final table but file not uploaded
                    $info_fileupload = $this->Leg_uploaded_file_trn->query("select doc_reg_no,doc_reg_date,doc_processing_year,state_id,office_id,document_id,input_fname,book_code from ngdrstab_trn_tmp_legacy_fileuploadinfo where file_transfer_flag='N'");
                    pr('file upload info');
                    pr($info_fileupload);
                    $fieldlist = array();

                    foreach ($info_fileupload as $filenm) {
                        //   $fieldlist= $info_fileupload[0][0]['input_fname'];
                        $fieldlist = $info_fileupload;
                    }
                    $this->set('filenm', $fieldlist);

                    if (!empty($info_fileupload)) {
                        $fieldlist = array();
                        $file_ext = pathinfo($this->request->data['upload_excel_to_tbl']['upload_file']['name'], PATHINFO_EXTENSION);

                        if ($file_ext == 'pdf') {

                            $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                            $new_name = $this->request->data['upload_excel_to_tbl']['upload_file']['name'];
                            $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Legacy_Documents/');
                            $success = move_uploaded_file($this->request->data['upload_excel_to_tbl']['upload_file']['tmp_name'], $createFolder1 . '/' . $new_name);
                        }
     
                    //  $formid = $_POST['formid'];
                     // pr($_POST);
                        if ($this->request->data['upload_file']['error'] == 0) 
                        {
                            $formid = $_POST['formid'];
                            $fid = $_POST['file_id' . $formid];
                        }
                        $fieldlist['local_language_id']['select'] = 'is_select_req';
                        $i=0;
                        foreach ($info_fileupload as $filenm) {
                            pr($filenm);
                            $this->set('filenm', $filenm[$i]['doc_reg_no']);
                            //  i=i+1;
                        }
                        PR($info_fileupload);

                       $doc_id =$filenm[$i]['doc_reg_no'];
                       pr($doc_id);
                       // $doc_id = filenm[0][doc_reg_no];
                      //  PR($doc_id);
                       // EXIT();
                        $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                        $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Documents/');

                        pr($path);
                        pr($path['Leg_file_config']['filepath']);
                        $get_district = $this->Leg_uploaded_file_trn->query("select district_id from ngdrstab_mst_office where office_id=" . $filenm[0]['office_id']);
                        $path = $createFolder1 . $get_district[0][0]['district_id'] . '/' . $filenm[0]['office_id'] . '/' . $filenm[0]['doc_processing_year'] . '/' . $filenm[0]['input_fname'];

                        pr($path);
                     

                        if (file_exists($path)) {
                            $filepath = file_get_contents('D:' . '/' . $get_district[0][0]['district_id'] . '/' . $filenm[0]['office_id'] . '/' . $filenm[0]['doc_processing_year'] . '/' . $filenm[0]['input_fname']);

                            $file_tokenno = $this->Leg_uploaded_file_trn->query("select info.token_no from ngdrstab_trn_legacy_generalinformation info inner join ngdrstab_trn_legacy_serial_numbers_final serialno on info.token_no=serialno.token_no and info.office_id=serialno.office_id inner join ngdrstab_conf_admblock3_district dist on dist.district_id=info.district_id
                        where year=" . $filenm[0]['doc_processing_year'] . "and info.office_id=" . $filenm[0]['office_id'] . "and book_number=" . "'" . $filenm[0]['book_code'] . "'" . "and book_serial_number=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and info.state_id=" . $filenm[0]['state_id']);

                            $office = $this->office->find('first', array('fields' => array('dist.district_name_en', 'office.taluka_id', 'office.office_id'), 'conditions' => array(
                                    'office.office_id' => $filenm[0]['office_id']), 'joins' => array(
                                    array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'dist', 'conditions' => array('dist.district_id=office.district_id')),
                            )));

                            if (!empty($file_tokenno)) {
                                $get_document_id = $this->Leg_uploaded_file_trn->query("select document_id,input_fname from ngdrstab_trn_legacy_fileuploadinfo where token_no=" . $file_tokenno[0][0]['token_no']);
                                $path = $this->Leg_file_config->find('first', array('fields' => array('filepath')));
                                $createFolder1 = $this->create_folder($path['Leg_file_config']['filepath'], 'Documents/');
                                $dist = $this->create_folder($createFolder1, $office['dist']['district_name_en'] . '/');
                                $taluka = $this->create_folder($dist, $office['office']['taluka_id'] . '/');
                                $office = $this->create_folder($taluka, $office['office']['office_id'] . '/');
                                $final_folder1 = $this->create_folder($office, $file_tokenno[0][0]['token_no'] . '/');
                                $final_folder = $this->create_folder($final_folder1, 'Uploads/');
                                $new_name = $file_tokenno[0][0]['token_no'] . '_' . $get_document_id[0][0]['document_id'];
                                $localfile = fopen($final_folder . '/' . $new_name . '.' . 'pdf', "w");
                                if (fwrite($localfile, $filepath)) {
                                    //$string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_tmp_legacy_fileuploadinfo set file_transfer_flag=" . "'" . 'Y' . "'" . ", out_fname=" . "'" . $new_name . "'" . ",file_transfer_date=" . "'" . date('Y-m-d H:i:s') . "'" . "where doc_reg_no=" . "'" . $filenm[0]['doc_reg_no'] . "'" . "and document_id=" . $get_document_id[0][0]['document_id']);
                                    // $string = $this->Leg_uploaded_file_trn->query("update ngdrstab_trn_legacy_fileuploadinfo set out_fname='$new_name' where token_no=" . $file_tokenno[0][0]['token_no']);

                                    $this->Session->setFlash('File Saved Successfully..');
                                } else {

                                    $this->Session->setFlash('File Not Uploaded..');
                                    fclose($localfile);
                                }
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash('No Records Found');
                }
            }
        }
    }

    public function importFromTmpToTrnTables() {
        try {
            $this->autoRender = false;
            $this->layout = 'ajax';
            $this->save_general_information_data();
            $this->save_property_details_data();
            $this->save_party_details_data();
            $this->save_witness_details_data();
            $this->save_identifier_details_data();
            $this->save_fee_details_data();
            $this->save_document_upload_details();
            return true;
        } catch (Exception $ex) {
            $this->Session->setFlash($ex->getMessage());
        }
    }

    public function removeDataFromTmpTables() {
        try {
            //remove data from temp table with Batch No.
            $this->autoRender = false;
            $this->loadModel('Legacy_tmp_generalinformation');
            $batchNo = $this->request->data['batch_no'];
            $arrDocRegNos = $this->Legacy_tmp_generalinformation->find('list', array('fields' => array('doc_reg_no', 'doc_reg_no'), 'conditions' => array('batch_no' => $batchNo)));
            if ($arrDocRegNos) {
                $arrDocRegNos = "'" . implode("','", $arrDocRegNos) . "'";
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_fee_calculation where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_fileuploadinfo where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_identifier where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_witness where doc_reg_no IN($arrDocRegNos)");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_no = ?", array($batchNo));
            }
        } catch (Exception $ex) {
            return $ex;
            $this->Session->setFlash($ex->getMessage());
        }
    }

    public function get_temp_upload_data($batch_no = null) {
        try {
            $batch_no = isset($this->request->data['batch_no']) ? $this->request->data['batch_no'] : $batch_no;
            $this->loadModels('Legacy_tmp_generalinformation', 'Legacy_tmp_propertydetails', 'LegacyTmpPartyDetail', 'LegacyTmpWithnessDetail', 'LegacyTmpIdentifierDetail', 'LegacyTmpFeesDetail');
            $generalInfo = $this->Legacy_tmp_generalinformation->getTempGeneralInfoByBatchNo($batch_no);
            $arrDocRegNos = $this->Legacy_tmp_generalinformation->find('list', array('fields' => array('doc_reg_no', 'doc_reg_no'), 'conditions' => array('batch_no' => $batch_no)));
            $propertyDetail = $this->Legacy_tmp_propertydetails->getPropertyDetailsByDocumentNo($arrDocRegNos);
            $partyDetail = $this->LegacyTmpPartyDetail->getTempPartyDetailByDocRegNo($arrDocRegNos);
            $withnessDetail = $this->LegacyTmpWithnessDetail->getTempWithnessDetailByDocRegNo($arrDocRegNos);
            $identifierDetail = $this->LegacyTmpIdentifierDetail->getTempIdentifierDetailByDocRegNo($arrDocRegNos);
            $feesDetail = $this->LegacyTmpFeesDetail->getTempFeesDetailByDocRegNo($arrDocRegNos);
            $this->set(compact('generalInfo', 'propertyDetail', 'partyDetail', 'withnessDetail', 'identifierDetail', 'feesDetail'));
            return;
        } catch (Exception $ex) {
            return -1;
        }
    }

    public function save_tmp_general_info($sheet0_count, $excel, $info_arr) {
        $this->loadModel('LegBatchmaster');
        $batchmaster_count = $this->LegBatchmaster->find('all');
        $Last_batchid = ($batchmaster_count[0]['LegBatchmaster']['batch_id']) + 1;
        $this->Session->write("batch_id", $Last_batchid);
        $this->LegBatchmaster->updateAll(
                array('batch_id' => $Last_batchid));

        for ($m = 1; $m <= $sheet0_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(0)[$m][0];
                $doc_reg_no = $excel->rows(0)[$m][1];
                $book_code = $excel->rows(0)[$m][2];
                $reg_date = $excel->rows(0)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(0)[$m][4];
                $state_id = $excel->rows(0)[$m][5];
                $district_id = $excel->rows(0)[$m][6];
                $taluka_id = $excel->rows(0)[$m][7];
                $office_id = $excel->rows(0)[$m][8];
                $doc_entered_office = $excel->rows(0)[$m][9];
                $lang = $excel->rows(0)[$m][10];
                $article = $excel->rows(0)[$m][11];
                $exec_date = $excel->rows(0)[$m][12];
                $temp_exc_date = explode('-', $exec_date);
                $exc_date = $temp_exc_date[2] . '-' . $temp_exc_date[1] . '-' . $temp_exc_date[0];
                $presentation_no = $excel->rows(0)[$m][13];
                $pres_dt = $excel->rows(0)[$m][14];
                $temp_pres_date = explode('-', $pres_dt);
                $presentation_dt = $temp_pres_date[2] . '-' . $temp_pres_date[1] . '-' . $temp_pres_date[0];
                $year_for_token = $excel->rows(0)[$m][15];
                $doc_type = $excel->rows(0)[$m][16];
                $reference_no = $excel->rows(0)[$m][17];
                $is_doc_scanned = $excel->rows(0)[$m][18];
                $doc_scan_date = $excel->rows(0)[$m][19] ? date('Y-m-d', strtotime($excel->rows(0)[$m][19])) : date('Y-m-d');
                $user_id = $this->Auth->user('user_id');
                $req_ip = $this->RequestHandler->getClientIp();
                $last_status_id = 1;
                $last_status_date = date('Y-m-d H:i:s');
                $batch_id = $this->Session->read("batch_id");
                $batch_no = $this->request->data['upload_excel_to_tbl']['batch_no'];
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_generalinformation(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,district_id,taluka_id,office_id,doc_entered_office,local_language_id,article_id,exec_date,presentation_no,presentation_dt,year_for_token,doc_type,reference_no,user_id,req_ip,last_status_id,last_status_date,batch_id,batch_no,is_doc_scanned,doc_scan_date) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$district_id,$taluka_id,$office_id,$doc_entered_office,$lang,$article,'$exc_date','$presentation_no','$presentation_dt',$year_for_token,$doc_type,'$reference_no',$user_id,'$req_ip',$last_status_id,'$last_status_date',$batch_id,$batch_no,'$is_doc_scanned','$doc_scan_date')");
                //  array_push($info_arr, $reference_sr_no . '|' . $office_id);
                array_push($info_arr, $reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id);
                $this->Session->setFlash('File Uploaded Successfully..');
            } catch (Exception $ex) {
                pr($ex);
                exit;
                $remark = 'Tab 1 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$book_code','$doc_reg_no','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in General Information with reference serial is' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
        return $info_arr;
    }

    public function save_tmp_property_details($sheet1_count, $excel, $info_arr) {
        $info_property_serial_no = array();
        for ($m = 1; $m <= $sheet1_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(1)[$m][0];
                $doc_reg_no = $excel->rows(1)[$m][1];
                $book_code = $excel->rows(1)[$m][2];
                $reg_date = $excel->rows(1)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(1)[$m][4];
                $state_id = $excel->rows(1)[$m][5];
                $office_id = $excel->rows(1)[$m][6];
                $property_serial_no = $excel->rows(1)[$m][7];
                $district = $excel->rows(1)[$m][8];
                $subdivision = $excel->rows(1)[$m][9];
                $area_type = $excel->rows(1)[$m][10];
                $taluka = $excel->rows(1)[$m][11];
                $circle = $excel->rows(1)[$m][12];
                $village = $excel->rows(1)[$m][13];
                $location = $excel->rows(1)[$m][14];
                $unique_prop_no = $excel->rows(1)[$m][15];
                $boundries_east = $excel->rows(1)[$m][16];
                $boundries_west = $excel->rows(1)[$m][17];
                $boundries_south = $excel->rows(1)[$m][18];
                $boundries_north = $excel->rows(1)[$m][19];
                $prop_address = $excel->rows(1)[$m][20];
                $batch_id = $this->Session->read("batch_id");

                array_push($info_property_serial_no, $property_serial_no);

                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_propertydetails->query("insert into ngdrstab_trn_tmp_legacy_property_details_entry(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,property_serial_no,district_id,subdivision_id,developed_land_types_id,taluka_id,circle_id,village_id,location1_en,unique_property_no_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,additional_information_en,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$property_serial_no,$district,$subdivision,$area_type,$taluka,$circle,$village,'$location','$unique_prop_no','$boundries_east','$boundries_west','$boundries_south','$boundries_north','$prop_address',$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                pr($ex);
                exit;
                $remark = 'Tab 2 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Property Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
        //pr($info_property_serial_no);exit;
        return $info_property_serial_no;
    }

    public function save_tmp_valuation($sheet2_count, $excel, $info_arr, $info_property_serial_no) {
        // pr($info_property_serial_no);exit;
        for ($m = 1; $m <= $sheet2_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(2)[$m][0];
                $doc_reg_no = $excel->rows(2)[$m][1];
                $book_code = $excel->rows(2)[$m][2];
                $reg_date = $excel->rows(2)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(2)[$m][4];
                $state_id = $excel->rows(2)[$m][5];
                $office_id = $excel->rows(2)[$m][6];
                $property_serial_no = $excel->rows(2)[$m][7];
                $usage_main_catg = $excel->rows(2)[$m][8];
                $usage_sub_catg = $excel->rows(2)[$m][9];
                $area = $excel->rows(2)[$m][10];
                $unit = $excel->rows(2)[$m][11];
                $market_val = $excel->rows(2)[$m][12];
                $consideration_amt = $excel->rows(2)[$m][13];
                $batch_id = $this->Session->read("batch_id");
                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    if (in_array($property_serial_no, $info_property_serial_no)) {
                        $inst5 = $this->Legacy_tmp_propertydetails->query("insert into ngdrstab_trn_tmp_legacy_valuation(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,property_serial_no,usage_main_catg_id,usage_sub_catg_id,item_value,area_unit,final_value,consideration_amt,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$property_serial_no,$usage_main_catg,$usage_sub_catg,$area,$unit,$market_val,$consideration_amt,$batch_id)");
                        $this->Session->setFlash('File Uploaded Successfully..');
                    }
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = 'Tab 3 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Valuation Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_parameter($sheet3_count, $excel, $info_arr, $info_property_serial_no) {
        for ($m = 1; $m <= $sheet3_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(3)[$m][0];
                $doc_reg_no = $excel->rows(3)[$m][1];
                $book_code = $excel->rows(3)[$m][2];
                $reg_date = $excel->rows(3)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(3)[$m][4];
                $state_id = $excel->rows(3)[$m][5];
                $office_id = $excel->rows(3)[$m][6];
                $property_serial_no = $excel->rows(3)[$m][7];
                $prop_attribute = $excel->rows(3)[$m][8];
                $attribute_val = $excel->rows(3)[$m][9];
                $attribute_val1 = $excel->rows(3)[$m][10];
                $attribute_val2 = $excel->rows(3)[$m][11];
                $batch_id = $this->Session->read("batch_id");
                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    if (in_array($property_serial_no, $info_property_serial_no)) {
                        $inst5 = $this->Legacy_tmp_propertydetails->query("insert into ngdrstab_trn_tmp_legacy_parameter(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,property_serial_no,paramter_id,paramter_value,paramter_value1,paramter_value2,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$property_serial_no,$prop_attribute,'$attribute_val','$attribute_val1','$attribute_val2',$batch_id)");
                        $this->Session->setFlash('File Uploaded Successfully..');
                    }
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = 'Tab 4 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where  batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Parameter Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_party_details($sheet4_count, $excel, $info_arr) {
        for ($m = 1; $m <= $sheet4_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(4)[$m][0];
                $doc_reg_no = $excel->rows(4)[$m][1];
                $book_code = $excel->rows(4)[$m][2];
                $reg_date = $excel->rows(4)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(4)[$m][4];
                $state_id = $excel->rows(4)[$m][5];
                $office_id = $excel->rows(4)[$m][6];
                $party_type = $excel->rows(4)[$m][7];
                $party_catg = $excel->rows(4)[$m][8];
                $party_fullnm = $excel->rows(4)[$m][9];
                $age = $excel->rows(4)[$m][10];
                $uid = $excel->rows(4)[$m][11];
                $pan_no = $excel->rows(4)[$m][12];
                $father_fullnm = $excel->rows(4)[$m][13];
                $gender = $excel->rows(4)[$m][14];
                $party_add = $excel->rows(4)[$m][15];
                if (!empty($excel->rows(4)[$m][16])) {
                    $pincode = $excel->rows(4)[$m][16];
                } else {
                    $pincode = 'null';
                    // pr($pincode);exit;
                }

                $batch_id = $this->Session->read("batch_id");

                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_party_entry_new(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,party_type_id,party_catg_id,party_full_name_en,age,uid,pan_no,father_full_name_en,gender_id,address_en,pin_code,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$party_type,$party_catg,'$party_fullnm',$age,'$uid','$pan_no','$father_fullnm',$gender,'$party_add',$pincode,$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = 'Tab 5 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Party Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_witness_details($sheet5_count, $excel, $info_arr) {
        for ($m = 1; $m <= $sheet5_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(5)[$m][0];
                $doc_reg_no = $excel->rows(5)[$m][1];
                $book_code = $excel->rows(5)[$m][2];
                $reg_date = $excel->rows(5)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(5)[$m][4];
                $state_id = $excel->rows(5)[$m][5];
                $office_id = $excel->rows(5)[$m][6];
                $saluation = $excel->rows(5)[$m][7];
                $witness_fullnm = $excel->rows(5)[$m][8];
                $father_fullnm = $excel->rows(5)[$m][9];
                $temp_dob = explode('-', $excel->rows(5)[$m][10]);
                $dob = $temp_dob[2] . '-' . $temp_dob[1] . '-' . $temp_dob[0];
                $age = $excel->rows(5)[$m][11];
                $gender_id = $excel->rows(5)[$m][12];
                $email_id = $excel->rows(5)[$m][13];
                $mobile_no = $excel->rows(5)[$m][14];
                $identificationtype_id = $excel->rows(5)[$m][15];
                $identificationtype_desc = $excel->rows(5)[$m][16];
                $district_id = $excel->rows(5)[$m][17];
                $taluka_id = $excel->rows(5)[$m][18];
                $village_id = $excel->rows(5)[$m][19];
                $address = $excel->rows(5)[$m][20];
                $batch_id = $this->Session->read("batch_id");
                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_witness(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,salutation,witness_full_name_en,father_full_name_en,dob,age,gender_id,email_id,mobile_no,identificationtype_id,identificationtype_desc_en,district_id,taluka_id,village_id,address_en,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$saluation,'$witness_fullnm','$father_fullnm','$dob',$age,$gender_id,'$email_id',$mobile_no,$identificationtype_id,'$identificationtype_desc',$district_id,$taluka_id,$village_id,'$address',$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = 'Tab 6 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_witness where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Witness Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_identifier_details($sheet6_count, $excel, $info_arr) {
        for ($m = 1; $m <= $sheet6_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(6)[$m][0];
                $doc_reg_no = $excel->rows(6)[$m][1];
                $book_code = $excel->rows(6)[$m][2];
                $reg_date = $excel->rows(6)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(6)[$m][4];
                $state_id = $excel->rows(6)[$m][5];
                $office_id = $excel->rows(6)[$m][6];
                $saluation = $excel->rows(6)[$m][7];
                $identifier_fullnm = $excel->rows(6)[$m][8];
                $father_fullnm = $excel->rows(6)[$m][9];
                $temp_dob = explode('-', $excel->rows(6)[$m][10]);
                $dob = $temp_dob[2] . '-' . $temp_dob[1] . '-' . $temp_dob[0];
                $age = $excel->rows(6)[$m][11];
                $gender_id = $excel->rows(6)[$m][12];
                $mobile_no = $excel->rows(6)[$m][13];
                $identificationtype_id = $excel->rows(6)[$m][14];
                $identificationtype_desc = $excel->rows(6)[$m][15];
                $district_id = $excel->rows(6)[$m][16];
                $taluka_id = $excel->rows(6)[$m][17];
                $village_id = $excel->rows(6)[$m][18];
                $address = $excel->rows(6)[$m][19];
                $batch_id = $this->Session->read("batch_id");
                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_identifier(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,salutation,identification_full_name_en,father_full_name_en,dob,age,gender_id,mobile_no,identificationtype_id,identificationtype_desc_en,district_id,taluka_id,village_id,address_en,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$saluation,'$identifier_fullnm','$father_fullnm','$dob',$age,$gender_id,$mobile_no,$identificationtype_id,'$identificationtype_desc',$district_id,$taluka_id,$village_id,'$address',$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = ' Tab 7 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no','$book_code','$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_witness where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_identifier where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Identifier Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_fee_details($sheet7_count, $excel, $info_arr) {
        $this->loadModel('Legacy_tmp_generalinformation');
        for ($m = 1; $m <= $sheet7_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(7)[$m][0];
                $doc_reg_no = $excel->rows(7)[$m][1];
                $book_code = $excel->rows(7)[$m][2];
                $reg_date = $excel->rows(7)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(7)[$m][4];
                $state_id = $excel->rows(7)[$m][5];
                $office_id = $excel->rows(7)[$m][6];
                $fee_item_id = $excel->rows(7)[$m][7];
                $final_value = $excel->rows(7)[$m][8];
                $batch_id = $this->Session->read("batch_id");

                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_fee_calculation(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,fee_item_id,final_value,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$fee_item_id,$final_value,$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                //  pr($ex);exit;
                $remark = 'Tab 8 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no',$book_code,'$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_witness where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_identifier where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_fee_calculation where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in Fee Details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_tmp_document_upload_details($sheet8_count, $excel, $info_arr) {
        $this->loadModel('Legacy_tmp_generalinformation');
        for ($m = 1; $m <= $sheet8_count - 1; $m++) {
            try {
                $reference_sr_no = $excel->rows(8)[$m][0];
                $doc_reg_no = $excel->rows(8)[$m][1];
                $book_code = $excel->rows(8)[$m][2];
                $reg_date = $excel->rows(8)[$m][3];
                $temp_reg_date = explode('-', $reg_date);
                $doc_reg_date = $temp_reg_date[2] . '-' . $temp_reg_date[1] . '-' . $temp_reg_date[0];
                $doc_process_yr = $excel->rows(8)[$m][4];
                $state_id = $excel->rows(8)[$m][5];
                $office_id = $excel->rows(8)[$m][6];
                $document_id = $excel->rows(8)[$m][7];
                $file_name = $excel->rows(8)[$m][8];
                $batch_id = $this->Session->read("batch_id");

                if (in_array($reference_sr_no . '|' . $doc_reg_no . '|' . $book_code . '|' . $doc_process_yr . '|' . $state_id . '|' . $office_id, $info_arr)) {
                    $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_fileuploadinfo(reference_sr_no,doc_reg_no,book_code,doc_reg_date,doc_processing_year,state_id,office_id,document_id,input_fname,batch_id) values($reference_sr_no,'$doc_reg_no','$book_code','$doc_reg_date',$doc_process_yr,$state_id,$office_id,$document_id,'$file_name',$batch_id)");
                    $this->Session->setFlash('File Uploaded Successfully..');
                } else {
                    
                }
            } catch (Exception $ex) {
                $remark = 'Tab 8 Error';
                $inst5 = $this->Legacy_tmp_generalinformation->query("insert into ngdrstab_trn_tmp_legacy_errorlog(reference_sr_no,doc_reg_no,book_code,doc_processing_year,state_id,office_id,remark) values('$reference_sr_no','$doc_reg_no',$book_code,'$doc_process_yr','$state_id','$office_id','$remark')");
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_generalinformation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_property_details_entry where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_valuation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_parameter where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_party_entry_new where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_witness where  batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_identifier where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_fee_calculation where batch_id=" . $this->Session->read("batch_id"));
                $this->Legacy_tmp_generalinformation->query("delete from ngdrstab_trn_tmp_legacy_fileuploadinfo where batch_id=" . $this->Session->read("batch_id"));
                $this->Session->setFlash('Invalid data is getting uploaded in document details with reference serial is ' . $reference_sr_no);
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        }
    }

    public function save_general_information_data() {
        $this->loadModels('Legacy_tmp_generalinformation', 'Leg_generalinformation', 'finyear', 'Leg_counter', 'Leg_SerialNumbersFinal', 'Leg_application_submitted');
        $Generalinfo = $this->Legacy_tmp_generalinformation->query("select * from ngdrstab_trn_tmp_legacy_generalinformation where transfer_flag='N'");
        if (!empty($Generalinfo)) {
            foreach ($Generalinfo as $info) {

                $check_duplicaterecords = $this->Legacy_tmp_generalinformation->query("select year,serial_no.office_id,book_number,book_serial_number from ngdrstab_trn_legacy_application_submitted app
inner join ngdrstab_trn_legacy_serial_numbers_final serial_no on serial_no.token_no=app.token_no and serial_no.office_id=app.office_id
 WHERE year=? and serial_no.office_id=? and book_number=? and book_serial_number=?", array($info[0]['doc_processing_year'], $info[0]['office_id'], $info[0]['book_code'], $info[0]['doc_reg_no']));
                //pr($check_duplicaterecords);exit;
                if (empty($check_duplicaterecords)) {
                    $gen_info = array();
                    $finyear = $this->finyear->field('finyear_id', array('current_year' => 'Y'));
                    $year = $this->finyear->field('year_for_token', array('current_year' => 'Y'));
                    $counter = $this->Leg_counter->find('all');

                    if (!empty($counter)) {
                        $count = $counter[0]['Leg_counter']['token_no_count'];
                        $token_no = ($year . $count) + 1;
                    } else {
                        $count = '0000000001';
                        $this->Leg_counter->save(array('fin_year_id' => $finyear, 'token_no_count' => $count));
                        $token_no = ($year . $count);
                    }
                    $rem = "'" . substr($token_no, 4) . "'";
                    $this->Leg_counter->updateAll(
                            array('fin_year_id' => $finyear, 'token_no_count' => $rem));
                    $data['token_no'] = $token_no;
                    $data['batch_no'] = $info[0]['batch_no'];
                    //$data['final_doc_reg_no'] = $info[0]['doc_reg_no'];
                    $data['final_stamp_date'] = $info[0]['doc_reg_date'];
                    $data['local_language_id'] = $info[0]['local_language_id'];
                    $data['article_id'] = $info[0]['article_id'];
                    $data['exec_date'] = $info[0]['exec_date'];
                    $data['presentation_no'] = $info[0]['presentation_no'];
                    $data['presentation_dt'] = $info[0]['presentation_dt'];
                    $data['year_for_token'] = $info[0]['year_for_token'];
                    $data['doc_type'] = $info[0]['doc_type'];
                    $data['reference_no'] = $info[0]['reference_no'];
                    $data['state_id'] = $info[0]['state_id'];
                    $data['district_id'] = $info[0]['district_id'];
                    // $data['taluka_id'] = $info[0]['taluka_id'];
                    $data['subdivision_id'] = $info[0]['taluka_id'];
                    $data['office_id'] = $info[0]['office_id'];
                    $data['doc_entered_office'] = $info[0]['doc_entered_office'];
                    $data['user_id'] = $info[0]['user_id'];
                    $data['req_ip'] = $this->RequestHandler->getClientIp();
                    $data['last_status_id'] = $info[0]['last_status_id'];
                    $data['last_status_date'] = $info[0]['last_status_date'];
                    $data['is_doc_scanned'] = $info[0]['is_doc_scanned'];
                    $data['last_status_date'] = $info[0]['doc_scan_date'];

                    $data['year'] = $info[0]['doc_processing_year'];
                    $book_number = $this->Leg_generalinformation->query("select book_number from ngdrstab_mst_article where article_id=" . $info[0]['article_id']);
                    if (!empty($book_number)) {
                        //pr("amar");exit;
                        $data['book_number'] = $book_number[0][0]['book_number'];
                        $data['book_serial_number'] = $info[0]['doc_reg_no'];
                        $data['final_doc_reg_no'] = $data['year'] . '-' . $data['office_id'] . '-' . $data['book_number'] . '-' . $data['book_serial_number'];

                        array_push($gen_info, $data);
                        // pr($gen_info);exit;
                        $this->Leg_generalinformation->saveAll($gen_info);
                        ///Changes on dated 07dec2020
                        $this->Leg_SerialNumbersFinal->saveAll($gen_info);
                        $this->Leg_application_submitted->saveAll($gen_info);
                        ///For update temporary Table
                        $string3 = $this->Leg_generalinformation->query("update ngdrstab_trn_tmp_legacy_generalinformation set transfer_flag='Y' where doc_reg_no=" . "'" . $info[0]['doc_reg_no'] . "'");
                    } else {
                        pr($info[0]['doc_reg_no']);
                        pr($info[0]['article_id']);
                        exit;
                    }
                } else {
                    $this->Session->setFlash('Duplicate Records found in table');
                }
            }
        } else {
            
        }
    }

    public function save_property_details_data() {
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_property_details_entry', 'Leg_generalinformation', 'Leg_trn_valuation', 'Leg_trn_valuation_details', 'Leg_parameter');
        //////////////////////////For Property Details Save
        $propertyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_property_details_entry where transfer_flag='N'");
        //pr($propertyinfo);exit;
        if (!empty($propertyinfo)) {
            foreach ($propertyinfo as $propinfo) {
                $data1 = array();
                //pr($propinfo);exit;
                //$token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $propinfo[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $propinfo[0]['doc_reg_date'] . "'::date) and state_id=" . $propinfo[0]['state_id'] . "and office_id=" . $propinfo[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $propinfo[0]['doc_processing_year'] . " and state_id=" . $propinfo[0]['state_id'] . "and office_id=" . $propinfo[0]['office_id'] . " and book_number=" . "'" . $propinfo[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $propinfo[0]['doc_reg_no'] . "'");
                $data1['token_no'] = $token_no[0][0]['token_no'];
                $data1['district_id'] = $propinfo[0]['district_id'];
                $data1['subdivision_id'] = $propinfo[0]['subdivision_id'];
                $data1['developed_land_types_id'] = $propinfo[0]['developed_land_types_id'];
                $data1['taluka_id'] = $propinfo[0]['taluka_id'];
                $data1['circle_id'] = $propinfo[0]['circle_id'];
                $data1['village_id'] = $propinfo[0]['village_id'];
                $data1['location1_en'] = $propinfo[0]['location1_en'];
                $data1['unique_property_no_en'] = $propinfo[0]['unique_property_no_en'];
                $data1['boundries_east_en'] = $propinfo[0]['boundries_east_en'];
                $data1['boundries_west_en'] = $propinfo[0]['boundries_west_en'];
                $data1['boundries_south_en'] = $propinfo[0]['boundries_south_en'];
                $data1['boundries_north_en'] = $propinfo[0]['boundries_north_en'];
                $data1['additional_information_en'] = $propinfo[0]['additional_information_en'];

                $this->Leg_property_details_entry->create();
                $this->Leg_property_details_entry->save($data1);
                $string3 = $this->Leg_generalinformation->query("update ngdrstab_trn_tmp_legacy_property_details_entry set transfer_flag='Y' where doc_reg_no=" . "'" . $propinfo[0]['doc_reg_no'] . "'");

                $valuationinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_valuation where transfer_flag='N' and doc_reg_no='" . $propinfo[0]['doc_reg_no'] . "'and property_serial_no='" . $propinfo[0]['property_serial_no'] . "'" . "and book_code=" . "'" . $propinfo[0]['book_code'] . "'" . "and doc_processing_year=" . $propinfo[0]['doc_processing_year'] . "and state_id=" . $propinfo[0]['state_id'] . "and office_id=" . $propinfo[0]['office_id']);
                // pr($valuationinfo);exit;
                if (!empty($valuationinfo)) {

                    foreach ($valuationinfo as $valinfo) {

                        $data1 = array();
                        $prop_id = $this->Leg_property_details_entry->getLastInsertID($token_no[0][0]['token_no']);
                        $data1['token_no'] = $token_no[0][0]['token_no'];
                        $data1['property_id'] = $prop_id;
                        $data1['usage_main_catg_id'] = $valinfo[0]['usage_main_catg_id'];
                        $data1['usage_sub_catg_id'] = $valinfo[0]['usage_sub_catg_id'];
                        $this->Leg_trn_valuation->create();
                        $this->Leg_trn_valuation->save($data1);
                        $val_id = $this->Leg_trn_valuation->getLastInsertID($token_no[0][0]['token_no']);

                        $data1['val_id'] = $val_id;
                        $data1['item_value'] = $valinfo[0]['item_value'];
                        $data1['area_unit'] = $valinfo[0]['area_unit'];
                        $data1['final_value'] = $valinfo[0]['final_value'];
                        $data1['consideration_amt'] = $valinfo[0]['consideration_amt'];
                        $this->Leg_trn_valuation_details->create();
                        $this->Leg_trn_valuation_details->save($data1);
                        $string3 = $this->Leg_generalinformation->query("update ngdrstab_trn_tmp_legacy_valuation set transfer_flag='Y' where doc_reg_no=" . "'" . $propinfo[0]['doc_reg_no'] . "'and id='" . $valinfo[0]['id'] . "'");
                    }
                }

                $attributeinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_parameter where transfer_flag='N' and doc_reg_no='" . $propinfo[0]['doc_reg_no'] . "'and property_serial_no='" . $propinfo[0]['property_serial_no'] . "'" . "and book_code=" . "'" . $propinfo[0]['book_code'] . "'" . "and doc_processing_year=" . $propinfo[0]['doc_processing_year'] . "and state_id=" . $propinfo[0]['state_id'] . "and office_id=" . $propinfo[0]['office_id']);
                if (!empty($attributeinfo)) {
                    foreach ($attributeinfo as $attriinfo) {
                        $data1 = array();
                        $prop_id = $this->Leg_property_details_entry->getLastInsertID($token_no[0][0]['token_no']);
                        $data1['token_id'] = $token_no[0][0]['token_no'];
                        $data1['property_id'] = $prop_id;
                        $data1['paramter_id'] = $attriinfo[0]['paramter_id'];
                        $data1['paramter_value'] = $attriinfo[0]['paramter_value'];
                        $data1['paramter_value1'] = $attriinfo[0]['paramter_value1'];
                        $data1['paramter_value2'] = $attriinfo[0]['paramter_value2'];
                        $this->Leg_parameter->create();
                        $this->Leg_parameter->save($data1);
                        $this->Session->setFlash('Data Saved Successfully');
                        $string3 = $this->Leg_generalinformation->query("update ngdrstab_trn_tmp_legacy_parameter set transfer_flag='Y' where doc_reg_no=" . "'" . $propinfo[0]['doc_reg_no'] . "'and id='" . $attriinfo[0]['id'] . "'");
                    }
                }
            }
            // exit;
        } else {
            //Pr("No Records Found..");
        }
    }

    public function save_party_details_data() {
        ////////For Party Details Save
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_party_entry');
        $partyinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_party_entry_new where transfer_flag='N'");
        if (!empty($partyinfo)) {
            foreach ($partyinfo as $prtyinfo) {
                $party_info = array();
                //$token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $prtyinfo[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $prtyinfo[0]['doc_reg_date'] . "'::date) and state_id=" . $prtyinfo[0]['state_id'] . "and office_id=" . $prtyinfo[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $prtyinfo[0]['doc_processing_year'] . " and state_id=" . $prtyinfo[0]['state_id'] . "and office_id=" . $prtyinfo[0]['office_id'] . " and book_number=" . "'" . $prtyinfo[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $prtyinfo[0]['doc_reg_no'] . "'");
                $data2['token_no'] = $token_no[0][0]['token_no'];
                $data2['party_type_id'] = $prtyinfo[0]['party_type_id'];
                $data2['party_catg_id'] = $prtyinfo[0]['party_catg_id'];
                $data2['party_full_name_en'] = $prtyinfo[0]['party_full_name_en'];
                $data2['age'] = $prtyinfo[0]['age'];
                $data2['uid'] = $prtyinfo[0]['uid'];
                $data2['pan_no'] = $prtyinfo[0]['pan_no'];
                $data2['father_full_name_en'] = $prtyinfo[0]['father_full_name_en'];
                $data2['gender_id'] = $prtyinfo[0]['gender_id'];
                $data2['address_en'] = $prtyinfo[0]['address_en'];
                $data2['pin_code'] = $prtyinfo[0]['pin_code'];

                array_push($party_info, $data2);
                $this->Leg_party_entry->saveAll($party_info);
                $this->Session->setFlash('Data Saved Successfully');
                $string3 = $this->Leg_party_entry->query("update ngdrstab_trn_tmp_legacy_party_entry_new set transfer_flag='Y' where doc_reg_no=" . "'" . $prtyinfo[0]['doc_reg_no'] . "'");
            }
        } else {
            
        }
    }

    public function save_witness_details_data() {
        ////////////For Witness Details Save
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_witness', 'Leg_party_entry');
        $witnessinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_witness where transfer_flag='N'");
        if (!empty($witnessinfo)) {
            foreach ($witnessinfo as $witniesinfo) {
                $witness_info = array();
                // $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $witniesinfo[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $witniesinfo[0]['doc_reg_date'] . "'::date) and state_id=" . $witniesinfo[0]['state_id'] . "and office_id=" . $witniesinfo[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $witniesinfo[0]['doc_processing_year'] . " and state_id=" . $witniesinfo[0]['state_id'] . "and office_id=" . $witniesinfo[0]['office_id'] . " and book_number=" . "'" . $witniesinfo[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $witniesinfo[0]['doc_reg_no'] . "'");
                $data3['token_no'] = $token_no[0][0]['token_no'];
                $data3['salutation'] = $witniesinfo[0]['salutation'];
                $data3['witness_full_name_en'] = $witniesinfo[0]['witness_full_name_en'];
                $data3['father_full_name_en'] = $witniesinfo[0]['father_full_name_en'];
                $data3['dob'] = $witniesinfo[0]['dob'];
                $data3['age'] = $witniesinfo[0]['age'];
                $data3['gender_id'] = $witniesinfo[0]['gender_id'];
                $data3['email_id'] = $witniesinfo[0]['email_id'];
                $data3['mobile_no'] = $witniesinfo[0]['mobile_no'];
                $data3['identificationtype_id'] = $witniesinfo[0]['identificationtype_id'];
                $data3['identificationtype_desc_en'] = $witniesinfo[0]['identificationtype_desc_en'];
                $data3['district_id'] = $witniesinfo[0]['district_id'];
                $data3['taluka_id'] = $witniesinfo[0]['taluka_id'];
                $data3['village_id'] = $witniesinfo[0]['village_id'];
                $data3['address_en'] = $witniesinfo[0]['address_en'];
                array_push($witness_info, $data3);
                $this->Leg_witness->saveAll($witness_info);
                $this->Session->setFlash('Data Saved Successfully');
                $string3 = $this->Leg_party_entry->query("update ngdrstab_trn_tmp_legacy_witness set transfer_flag='Y' where doc_reg_no=" . "'" . $witniesinfo[0]['doc_reg_no'] . "'");
            }
        } else {
            
        }
    }

    public function save_identifier_details_data() {
        ////////////For Identifier Details Save
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_identification', 'Leg_party_entry');
        $identifierinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_identifier where transfer_flag='N'");
        if (!empty($identifierinfo)) {
            foreach ($identifierinfo as $identifiinfo) {
                $identifier_info = array();
                // $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $identifiinfo[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $identifiinfo[0]['doc_reg_date'] . "'::date) and state_id=" . $identifiinfo[0]['state_id'] . "and office_id=" . $identifiinfo[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $identifiinfo[0]['doc_processing_year'] . " and state_id=" . $identifiinfo[0]['state_id'] . "and office_id=" . $identifiinfo[0]['office_id'] . " and book_number=" . "'" . $identifiinfo[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $identifiinfo[0]['doc_reg_no'] . "'");
                $data4['token_no'] = $token_no[0][0]['token_no'];
                $data4['salutation'] = $identifiinfo[0]['salutation'];
                $data4['identification_full_name_en'] = $identifiinfo[0]['identification_full_name_en'];
                $data4['father_full_name_en'] = $identifiinfo[0]['father_full_name_en'];
                $data4['dob'] = $identifiinfo[0]['dob'];
                $data4['age'] = $identifiinfo[0]['age'];
                $data4['gender_id'] = $identifiinfo[0]['gender_id'];
                $data4['mobile_no'] = $identifiinfo[0]['mobile_no'];
                $data4['identificationtype_id'] = $identifiinfo[0]['identificationtype_id'];
                $data4['identificationtype_desc_en'] = $identifiinfo[0]['identificationtype_desc_en'];
                $data4['district_id'] = $identifiinfo[0]['district_id'];
                $data4['taluka_id'] = $identifiinfo[0]['taluka_id'];
                $data4['village_id'] = $identifiinfo[0]['village_id'];
                $data4['address_en'] = $identifiinfo[0]['address_en'];
                array_push($identifier_info, $data4);
                $this->Leg_identification->saveAll($identifier_info);
                $this->Session->setFlash('Data Saved Successfully');
                $string3 = $this->Leg_party_entry->query("update ngdrstab_trn_tmp_legacy_identifier set transfer_flag='Y' where doc_reg_no=" . "'" . $identifiinfo[0]['doc_reg_no'] . "'");
            }
        } else {
            
        }
    }

    public function save_fee_details_data() {
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_fee_calculation', 'Leg_fee_calculation_detail', 'Leg_party_entry');
        ///For Fee Details Save
        $feeinfo = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_fee_calculation where transfer_flag='N'");

        if (!empty($feeinfo)) {
            foreach ($feeinfo as $feeiesinfo) {

                $fee_info = array();
                $fee_detail_info = array();
                //$token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $feeiesinfo[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $feeiesinfo[0]['doc_reg_date'] . "'::date) and state_id=" . $feeiesinfo[0]['state_id'] . "and office_id=" . $feeiesinfo[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $feeiesinfo[0]['doc_processing_year'] . " and state_id=" . $feeiesinfo[0]['state_id'] . "and office_id=" . $feeiesinfo[0]['office_id'] . " and book_number=" . "'" . $feeiesinfo[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $feeiesinfo[0]['doc_reg_no'] . "'");
                $data5['token_no'] = $token_no[0][0]['token_no'];
                array_push($fee_info, $data5);
                $this->Leg_fee_calculation->saveAll($fee_info);
                $last_fee_calc_id = $this->Leg_fee_calculation->getLastInsertID($token_no);

                $data6['fee_calc_id'] = $last_fee_calc_id;
                $data6['fee_item_id'] = $feeiesinfo[0]['fee_item_id'];
                $data6['final_value'] = $feeiesinfo[0]['final_value'];
                array_push($fee_detail_info, $data6);
                $this->Leg_fee_calculation_detail->saveAll($fee_detail_info);
                $this->Session->setFlash('Data Saved Successfully');
                $string3 = $this->Leg_party_entry->query("update ngdrstab_trn_tmp_legacy_fee_calculation set transfer_flag='Y' where doc_reg_no=" . "'" . $feeiesinfo[0]['doc_reg_no'] . "'");
            }
        } else {
            
        }
    }

    public function save_document_upload_details() {
        $this->loadModels('Legacy_tmp_propertydetails', 'Leg_uploaded_file_trn', 'Leg_party_entry');
        ///For Document upload Details Save
        $doc_upload_info = $this->Legacy_tmp_propertydetails->query("select * from ngdrstab_trn_tmp_legacy_fileuploadinfo where transfer_flag='N'");
//pr($doc_upload_info);exit;
        if (!empty($doc_upload_info)) {
            foreach ($doc_upload_info as $upload_info) {

                $document_upload_info = array();
                //$fee_detail_info = array();
                //$token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_application_submitted where final_doc_reg_no='" . $upload_info[0]['doc_reg_no'] . "' and date_part('year', final_stamp_date)=date_part('year','" . $upload_info[0]['doc_reg_date'] . "'::date) and state_id=" . $upload_info[0]['state_id'] . "and office_id=" . $upload_info[0]['office_id']);
                $token_no = $this->Legacy_tmp_propertydetails->query("select token_no from ngdrstab_trn_legacy_serial_numbers_final where year=" . $upload_info[0]['doc_processing_year'] . " and state_id=" . $upload_info[0]['state_id'] . "and office_id=" . $upload_info[0]['office_id'] . " and book_number=" . "'" . $upload_info[0]['book_code'] . "'" . " and book_serial_number=" . "'" . $upload_info[0]['doc_reg_no'] . "'");
                $data7['token_no'] = $token_no[0][0]['token_no'];
                $data7['document_id'] = $upload_info[0]['document_id'];
                $data7['input_fname'] = $upload_info[0]['input_fname'];
                $data7['out_fname'] = $token_no[0][0]['token_no'] . '_' . $upload_info[0]['document_id'];
                //pr($data7['out_fname']);exit;
                $data7['state_id'] = $upload_info[0]['state_id'];
                array_push($document_upload_info, $data7);
                $this->Leg_uploaded_file_trn->saveAll($document_upload_info);

                $this->Session->setFlash('Data Saved Successfully');
                $string3 = $this->Leg_party_entry->query("update ngdrstab_trn_tmp_legacy_fileuploadinfo set transfer_flag='Y' where doc_reg_no=" . "'" . $upload_info[0]['doc_reg_no'] . "'");
            }
        } else {
            
        }
    }

    public function test_file() {

        $this->autoRender = FALSE;

        $connection = ssh2_connect('10.153.45.29', 22);
        ssh2_auth_password($connection, 'root', 'ngdrs@2018#$%');

        $sftp = ssh2_sftp($connection);

//$stream = fopen('ssh2.sftp://' . intval($sftp) . '/path/to/file', 'r');
        //   $fetch_string =file_get_contents('ssh2.sftp://root:Ngdrs@app01@10.194.162.176');
    }

    public function srch_legacy_app() {
        try {
            $this->loadModel('article');
            $this->loadModel('office');
            $this->loadModel('partytype');
            $doc_lang = $this->Session->read("sess_langauge");
            $article = $this->article->get_article($doc_lang);
            $this->set('article', $article);
            $office = $this->office->find('list', array('fields' => array('office.office_id', 'office.office_name_en'), 'order' => array('office.office_name_en' => 'ASC')));
            $this->set('office', $office);
            $partytype_name = $this->partytype->find('list', array('fields' => array('partytype.party_type_id', 'partytype.party_type_desc_en'), 'order' => array('partytype.party_type_desc_en' => 'ASC')));
            $this->set('partytype', $partytype_name);
        } catch (Exception $ex) {
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
