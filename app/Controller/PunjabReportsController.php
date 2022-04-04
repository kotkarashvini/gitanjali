<?php

class PunjabReportsController extends AppController {

    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');

        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Auth->allow('inspection_search','get_payment_details2','simple_reciept','get_payment_details1', 'scan', 'checkscan', 'loadfile', 'upload');
        $this->Auth->allow('exception_occurred', 'goshwara_cash_book', 'cash_receipt');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function is_Date($str) {
        $str = str_replace('/', '-', $str);
        $stamp = strtotime($str);
        if (is_numeric($stamp)) {

            $month = date('m', $stamp);
            $day = date('d', $stamp);
            $year = date('Y', $stamp);

            return checkdate($month, $day, $year);
        }
        return false;
    }

    public function index_register_1($frmData = NULL) {
        try {

            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_index_register']) ? $this->request->data['rpt_index_register'] : $frmData;

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 20)));
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            if (isset($frmData['from']) && isset($frmData['to'])) {


                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
//              
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));

                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $indexregister1_data = $this->payment->Query('delete from ngdrstab_temp_index_register where user_id=?', array($user_id));

                    $indexregister1_data = $this->payment->Query('select * from index_register_1(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
//                    pr($indexregister1_data);exit;
                    $indexregister1_data = $this->payment->Query('select * from ngdrstab_temp_index_register');



                    if ($indexregister1_data) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> " . $labels[260] . "  </h2>"
                                . "<table  align=center border=0 width=50%><tr> <td align=center><b>" . $labels[268] . ":</b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>" . $labels[269] . ":</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[203] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[204] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[205] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[206] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[207] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[208] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[209] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[210] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[211] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[212] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[213] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[214] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[215] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[216] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[217] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[218] . "</th>"
                                . "</tr>";


                        $SrNo = 1;
//            $total = 0;
                        foreach ($indexregister1_data as $ir1) {
                            $ir1 = $ir1[0];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . $ir1['village_name'] . "</td>"
                                    . "<td>" . $ir1['first_party_name'] . "</td>"
                                    . "<td>" . $ir1['first_party_father_name'] . "</td>"
                                    . "<td>" . $ir1['first_party_address'] . "</td>"
                                    . "<td>" . $ir1['second_party_name'] . "</td>"
                                    . "<td>" . $ir1['second_party_father_name'] . "</td>"
                                    . "<td>" . $ir1['second_party_address'] . "</td>"
                                    . "<td>" . $ir1['area'] . "</td>"
                                    . "<td>" . $ir1['units_of_measurment'] . "</td>"
                                    . "<td>" . $ir1['type_of_deed'] . "</td>"
                                    . "<td>" . $ir1['consideartion_amount'] . "</td>"
                                    . "<td>" . $ir1['binder_no'] . "</td>"
                                    . "<td>" . $ir1['page_no'] . "</td>"
                                    . "<td>" . $ir1['reg_no'] . "</td>"
                                    . "<td>" . $ir1['created_date'] . "</td>"
                                    . "</tr>";
                        }

                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'> <h5 >" . $labels[264] . "</h5> </td></tr></table>";
                        $this->create_pdf($html_design, 'Index Register 1', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function index_register_2($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_index_register']) ? $this->request->data['rpt_index_register'] : $frmData;

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 21)));
//                            pr($frmData['filterby']);exit;
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            $stateid = $this->Auth->User("state_id");
            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
//                pr($frmData['from']);exit;
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));

                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {


                    $indexregister2_data = $this->payment->Query('delete from ngdrstab_temp_index_register_2 where user_id=?', array($user_id));
//                    exit;
                    $indexregister2_data = $this->payment->Query('select * from index_register_2(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));


                    $indexregister2_data = $this->payment->Query('select * from ngdrstab_temp_index_register_2');
//                       pr($indexregister1_data);exit;
                    if ($indexregister2_data) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> " . $labels[261] . "</h2>"
                                . "<table  align=center border=0 width=50%><tr> <td align=center><b>" . $labels[270] . ":</b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>" . $labels[271] . ":</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[219] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[220] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[221] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[222] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[223] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[224] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[225] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[226] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[227] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[228] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[229] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[230] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[231] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[232] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[233] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[234] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[235] . "</th>"
                                . "</tr>";
                        $SrNo = 1;

                        foreach ($indexregister2_data as $ir2) {
                            $ir2 = $ir2[0];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . $ir2['village_name'] . "</td>"
                                    . "<td>" . $ir2['first_party_name'] . "</td>"
                                    . "<td>" . $ir2['first_party_father_name'] . "</td>"
                                    . "<td>" . $ir2['first_party_address'] . "</td>"
                                    . "<td>" . $ir2['second_party_name'] . "</td>"
                                    . "<td>" . $ir2['second_party_father_name'] . "</td>"
                                    . "<td>" . $ir2['second_party_address'] . "</td>"
                                    . "<td>" . $ir2['area'] . "</td>"
                                    . "<td>" . $ir2['units_of_measurment'] . "</td>"
                                    . "<td>" . $ir2['type_of_deed'] . "</td>"
                                    . "<td>" . $ir2['consideartion_amount'] . "</td>"
                                    . "<td>" . $ir2['binder_no'] . "</td>"
                                    . "<td>" . $ir2['page_no'] . "</td>"
                                    . "<td>" . $ir2['reg_no'] . "</td>"
                                    . "<td>" . $ir2['created_date'] . "</td>"
                                    . "<td>" . $ir2['remarks'] . "</td>"
                                    . "</tr>";
                        }

                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'> <h5>" . $labels[265] . " </h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Index Register 2', 'A4', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $display_flag = 'D') {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mpdf');
            $mpdf = new mPDF('utf-8', $page_size);
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->setFooter('{PAGENO} / {nb}');
            if ($waterMark) {
                $mpdf->SetWatermarkText($waterMark);
                $mpdf->watermarkTextAlpha = 0.2;
                $mpdf->showWatermarkText = true;
            }

            $mpdf->WriteHTML($html_design);
            $mpdf->Output($file_name . ".pdf", $display_flag); // 'I' for Display PDF in Next Tab
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! error in creating PDF');
        }
    }

    public function index_register_3($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_index_register']) ? $this->request->data['rpt_index_register'] : $frmData;

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 22)));

            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            $stateid = $this->Auth->User("state_id");

            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
//                pr($frmData['from']);exit;
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));

                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {


                    $indexregister3_data = $this->payment->Query('delete from ngdrstab_temp_index_register_3');
//                    exit;
                    $indexregister3_data = $this->payment->Query('select * from index_register_3(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));

                    $indexregister3_data = $this->payment->Query('select * from ngdrstab_temp_index_register_3');
//                       pr($indexregister1_data);exit;
                    if ($indexregister3_data) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> " . $labels[262] . " </h2>"
                                . "<table  align=center border=0 width=50%><tr> <td align=center><b>" . $labels[272] . ":</b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>" . $labels[273] . ":</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[236] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[237] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[238] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[239] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[240] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[241] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[242] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[243] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[244] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[245] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[246] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[247] . "</th>"
                                . "</tr>";


                        $SrNo = 1;

                        foreach ($indexregister3_data as $ir3) {
                            $ir3 = $ir3[0];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . $ir3['first_party_name'] . "</td>"
                                    . "<td>" . $ir3['first_party_father_name'] . "</td>"
                                    . "<td>" . $ir3['first_party_address'] . "</td>"
                                    . "<td>" . $ir3['second_party_name'] . "</td>"
                                    . "<td>" . $ir3['second_party_father_name'] . "</td>"
                                    . "<td>" . $ir3['second_party_address'] . "</td>"
                                    . "<td>" . $ir3['type_of_deed'] . "</td>"
                                    . "<td>" . $ir3['binder_no'] . "</td>"
                                    . "<td>" . $ir3['page_no'] . "</td>"
                                    . "<td>" . $ir3['reg_no'] . "</td>"
                                    . "<td>" . $ir3['created_date'] . "</td>"
                                    . "</tr>";
                        }

                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'> <h5>" . $labels[266] . " </h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Index Register 3', 'A4', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function index_register_4($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_index_register']) ? $this->request->data['rpt_index_register'] : $frmData;

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 23)));
//                            pr($frmData['filterby']);exit;
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            $stateid = $this->Auth->User("state_id");
            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
//                pr($frmData['from']);exit;
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));

                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {


                    $indexregister4_data = $this->payment->Query('delete from ngdrstab_temp_index_register_4');
//                    exit;
                    $indexregister4_data = $this->payment->Query('select * from index_register_4(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));

                    $indexregister4_data = $this->payment->Query('select * from ngdrstab_temp_index_register_4');
//                       pr($indexregister1_data);exit;
                    if ($indexregister4_data) {

                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> " . $labels[263] . " </h2>"
                                . "<table  align=center border=0 width=50%><tr> <td align=center><b> " . $labels[274] . ": </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>" . $labels[275] . ":</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[248] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[249] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[250] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[251] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[252] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[253] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[254] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[255] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[254] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[255] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[256] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[257] . "</th>"
                                . "</tr>";

                        $SrNo = 1;

                        foreach ($indexregister4_data as $ir4) {
                            $ir4 = $ir4[0];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . $ir4['first_party_name'] . "</td>"
                                    . "<td>" . $ir4['first_party_father_name'] . "</td>"
                                    . "<td>" . $ir4['first_party_address'] . "</td>"
                                    . "<td>" . $ir4['second_party_name'] . "</td>"
                                    . "<td>" . $ir4['second_party_father_name'] . "</td>"
                                    . "<td>" . $ir4['second_party_address'] . "</td>"
                                    . "<td>" . $ir4['type_of_deed'] . "</td>"
                                    . "<td>" . $ir4['binder_no'] . "</td>"
                                    . "<td>" . $ir4['page_no'] . "</td>"
                                    . "<td>" . $ir4['reg_no'] . "</td>"
                                    . "<td>" . $ir4['created_date'] . "</td>"
                                    . "</tr>";
                        }

                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'> <h5>" . $labels[267] . " </h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Index Register 4', 'A4', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function cash_receipt($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_index_register']) ? $this->request->data['rpt_index_register'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 24)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");


            if (isset($frmData['from']) && isset($frmData['to'])) {
                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));
                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $cash_receipt = $this->payment->Query('delete from ngdrstab_temp_cash_receipt where user_id=?', array($user_id));
                    $cash_receipt = $this->payment->Query('select * from cash_receipt_details(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
                    $cash_receipt = $this->payment->Query('select * from ngdrstab_temp_cash_receipt');

                    if ($cash_receipt) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> Cash Receipt Details </h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[292] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[293] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[294] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[295] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[296] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[297] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[298] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[299] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[300] . "/Handling Charges</th>"
                                . "<th style='text-align:center;'>" . $labels[301] . "</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($cash_receipt as $cr) {
                            $cr = $cr[0];
                            $total_amt = $cr['transaction_value'] + $cr['stamp_duty'] + $cr['registration_fees'] + $cr['pasting_fees'] + $cr['miscellaneous_fees'];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . $cr['registration_no'] . "</td>"
                                    . "<td>" . $cr['deed_name'] . "</td>"
                                    . "<td>" . $cr['transaction_value'] . "</td>"
                                    . "<td>" . $cr['units_of_measurment'] . "</td>"
                                    . "<td>" . $cr['stamp_duty'] . "</td>"
                                    . "<td>" . $cr['registration_fees'] . "</td>"
                                    . "<td>" . $cr['pasting_fees'] . "</td>"
                                    . "<td>" . $cr['miscellaneous_fees'] . "</td>"
                                    . "<td>" . $total_amt . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h4>" . $labels[302] . "</h4></td></tr></table>";
                        $this->create_pdf($html_design, 'Cash Receipt Details', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function goshwara_cash_book($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
//              pr($lang);exit;
            $frmData = isset($this->request->data['rpt_goshwara_cashbook']) ? $this->request->data['rpt_goshwara_cashbook'] : $frmData;
//            pr($lang);exit;
            $stateid = $this->Auth->User("state_id");
//          $labels = $this->ReportLabel->find('all');
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 25)));
//              pr($labels);exit;
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));



                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $ghoshwara_cash_book = $this->payment->Query('delete from ngdrstab_temp_goshwara_cashbook');
                    $ghoshwara_cash_book = $this->payment->Query('select * from goshwara_cashbook(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
                    $ghoshwara_cash_book = $this->payment->Query('select * from ngdrstab_temp_goshwara_cashbook');

                    if ($ghoshwara_cash_book) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center>Goshwara CashBook </h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[303] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[304] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[305] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[306] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[307] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[308] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[309] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[310] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[311] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[312] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[313] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[314] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[315] . "</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($ghoshwara_cash_book as $gb) {
                            $gb = $gb[0];
                            $total_fees = $gb['reg_fee_bahi_no_1'] + $gb['reg_fees_amt_bahi_no_3'] + $gb['reg_fees_amt_bahi_no_4'];

                            $total_5_to_8 = $total_fees + $gb['search_fees'] + $gb['anyold_def_amt'] + $gb['any_new_def_amt'];

                            $grand_total = $total_5_to_8 + $gb['pasting_fees'] + $gb['coping_fees'];

                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td align=center>" . $gb['registry_date'] . "</td>"
                                    . "<td align=center>" . $gb['reg_fee_bahi_no_1'] . "</td>"
                                    . "<td align=center>" . $gb['reg_fees_amt_bahi_no_3'] . "</td>"
                                    . "<td align=center>" . $gb['reg_fees_amt_bahi_no_4'] . "</td>"
                                    . "<td align=center>" . $total_fees . "</td>"
                                    . "<td align=center>" . $gb['search_fees'] . "</td>"
                                    . "<td align=center>" . $gb['anyold_def_amt'] . "</td>"
                                    . "<td align=center>" . $gb['any_new_def_amt'] . "</td>"
                                    . "<td align=center>" . $total_5_to_8 . "</td>"
                                    . "<td align=center>" . $gb['pasting_fees'] . "</td>"
                                    . "<td align=center>" . $gb['coping_fees'] . "</td>"
                                    . "<td align=center>" . $grand_total . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h5>" . $labels[316] . "</h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Ghoshwara CashBook', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function naksha_no_3($frmData = NULL) {

        try {

            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_naksha_no']) ? $this->request->data['rpt_naksha_no'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 26)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");

            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));


                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $naksha_no = $this->payment->Query('delete from ngdrstab_temp_naksha_no_3 where user_id=?', array($user_id));
                    $naksha_no = $this->payment->Query('select * from naksha_no_3(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
                    $naksha_no = $this->payment->Query('select * from ngdrstab_temp_naksha_no_3');



                    if ($naksha_no) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> Naksha No 3 </h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[334] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[317] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[318] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[319] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[320] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[321] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[322] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[323] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[324] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[325] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[326] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[327] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[328] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[329] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[330] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[331] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[332] . "</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($naksha_no as $nn) {
                            $nn = $nn[0];
                            $stmap = ($nn['stamp_duty'] * 0.5) / 100;

                            $grand_total = $nn['reg_fee_bahi_no_1'] + $nn['reg_fees_amt_bahi_no_4'] + $nn['transection_value'] + $stmap +
                                    $nn['social_security_fund'] + $nn['s_c_i'] + $nn['registration_fee'] + $nn['pasting_fees'] +
                                    $nn['search_fees'] + $nn['anyold_def_amt'] + $nn['any_new_def_amt'] + $nn['coping_fees'];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td>" . (($nn['registry_date']) ? date('d-M-Y', strtotime($nn['registry_date'])) : '-') . "</td>"
//                                    . "<td style='text-align:center;'>" . $nn['registry_date'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['reg_fee_bahi_no_1'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['reg_fees_amt_bahi_no_3'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['reg_fees_amt_bahi_no_4'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['deed_type'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['transection_value'] . "</td>"
                                    . "<td style='text-align:center;'>" . $stmap . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['social_security_fund'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['s_c_i'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['registration_fee'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['pasting_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['search_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['anyold_def_amt'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['any_new_def_amt'] . "</td>"
                                    . "<td style='text-align:center;'>" . $nn['coping_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $grand_total . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h5>" . $labels[333] . "</h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Naksha No 3', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function stamp_and_reg_fees($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_stamp_and_regfees']) ? $this->request->data['rpt_stamp_and_regfees'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 27)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));


                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $stamp_reg = $this->payment->Query('delete from ngdrstab_temp_stamp_and_regfees where user_id=?', array($user_id));
                    $stamp_reg = $this->payment->Query('select * from stamp_and_reg_fees(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
                    $stamp_reg = $this->payment->Query('select * from ngdrstab_temp_stamp_and_regfees');



                    if ($stamp_reg) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> Stamp And Registration Fees </h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[335] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[336] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[337] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[338] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[339] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[340] . " </th>"
                                . "<th style='text-align:center;'>" . $labels[341] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[342] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[343] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[344] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[345] . "</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($stamp_reg as $sr) {
                            $sr = $sr[0];
                            $stmap = ($sr['stamp_duty'] * 0.5) / 100;
                            $SSF = ($sr['social_sec_fund'] * 0.3) / 100;
//                          pr($SSF);exit;
                            $grand_total = $stmap + $SSF + $sr['sci'] + $sr['registration_fees'] + $sr['miscellaneous_fees'];



                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
//                                     ."<td>".(($nn['registry_date']) ? date('d-M-Y', strtotime($nn['registry_date'])) : '-') . "</td>"
//                                    . "<td style='text-align:center;'>" . $nn['registry_date'] . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['name_of_reg_office'] . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['estimate_bug_for_3_months'] . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['doc_reg_in_month'] . "</td>"
                                    . "<td style='text-align:center;'>" . $stmap . "</td>"
                                    . "<td style='text-align:center;'>" . $SSF . "</td>"
//                                    . "<td style='text-align:center;'>" . $stmap . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['sci'] . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['registration_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['miscellaneous_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $grand_total . "</td>"
                                    . "<td style='text-align:center;'>" . $sr['remarks'] . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h5>" . $labels[346] . "</h5></td></tr></table>";
                        $this->create_pdf($html_design, 'Stamp And Reg Fees', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function additional_stamp_duty($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_stamp_and_regfees']) ? $this->request->data['rpt_stamp_and_regfees'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            // $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 26)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");

//             pr($user_type_flag);
//             pr($user_id);exit;

            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));


                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $add_stamp = $this->payment->Query('delete from ngdrstab_temp_additional_stamp_duty where user_id=?', array($user_id));

                    $add_stamp = $this->payment->Query('select * from additional_stamp_duty(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
//                    pr($add_stamp);exit;
                    $add_stamp = $this->payment->Query('select * from ngdrstab_temp_additional_stamp_duty');

                    if ($add_stamp) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> Additional Stamp Duty</h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>Sr.No</th>"
                                . "<th style='text-align:center;'>Registered Documents</th>"
                                . "<th style='text-align:center;'>Stamp Duty</th>"
                                . "<th style='text-align:center;'>Social Security Fund</th>"
                                . "<th style='text-align:center;'>Total</th>"
                                . "<th style='text-align:center;'>Remark</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($add_stamp as $as) {
                            $as = $as[0];
                            $total = $as['stamp_duty'] + $as['social_security_fund'];
                            $html_design .= "<tr>"
                                    . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td style='text-align:center;'>" . $as['registered_doc'] . "</td>"
                                    . "<td style='text-align:center;'>" . $as['stamp_duty'] . "</td>"
                                    . "<td style='text-align:center;'>" . $as['social_security_fund'] . "</td>"
                                    . "<td style='text-align:center;'>" . $total . "</td>"
                                    . "<td style='text-align:center;'>" . $as['remarks'] . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h4>Sub Register</h4></td></tr></table>";
                        $this->create_pdf($html_design, 'Additional Stamp Duty', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function stamp_and_reg_comp_state($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $frmData = isset($this->request->data['rpt_stamp_and_regfees']) ? $this->request->data['rpt_stamp_and_regfees'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 29)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
            if (isset($frmData['from']) && isset($frmData['to'])) {

                $frmData['from'] = date('Y-m-d', strtotime($frmData['from']));
                $frmData['to'] = date('Y-m-d', strtotime($frmData['to']));


                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $data = $this->payment->Query('delete from ngdrstab_temp_comprative_statements');
                    $data = $this->payment->Query('select * from stamp_and_reg_comparative_stat(?,?,?,?,?)', array($frmData['from'], $frmData['to'], $stateid, $user_id, $user_type_flag));
                    $data = $this->payment->Query('select * from ngdrstab_temp_comprative_statements');

                    if ($data) {
                        $html_design = "<style>td{padding:5px;} </style>"
                                . "<h2 align=center> STAMP AND REGISTRATION FEES COMPARATIVE STATEMENT </h2>"
                                . "<table  align=center border=0 width=100%><tr> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>" . $labels[370] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[371] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[372] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[373] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[374] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[375] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[376] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[377] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[378] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[379] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[380] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[381] . "</th>"
                                . "<th style='text-align:center;'>" . $labels[382] . "</th>"
                                . "</tr>";
                        $SrNo = 1;
                        foreach ($data as $data1) {
                            $data1 = $data1[0];
                            $stmap_duty = ($data1['stamp_duty'] * 0.5) / 100;
                            $SSF = ($data1['social_security_fund'] * 0.3) / 100;
                            $SCI = ($data1['sci'] * 0.1) / 100;
                            $grand_total = $data1['sale_deed'] + $data1['mortgage_deed'] + $data1['gift_deed'] + $data1['gpa'] + $data1['will'] + $data1['transction_value'] + $data1['stamp_duty'] + $data1['social_security_fund'] + $data1['sci'] + $data1['registration_fees'] + $data1['miscellaneous_fees'];
                            $html_design .= "<tr>"
                                    . "<td style='text-align:center;'>" . $SrNo++ . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['sale_deed'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['mortgage_deed'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['gift_deed'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['gpa'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['will'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['transction_value'] . "</td>"
                                    . "<td style='text-align:center;'>" . $stmap_duty . "</td>"
                                    . "<td style='text-align:center;'>" . $SSF . "</td>"
                                    . "<td style='text-align:center;'>" . $SCI . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['registration_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['miscellaneous_fees'] . "</td>"
                                    . "<td style='text-align:center;'>" . $data1['grand_total'] . "</td>"
                                    . "</tr>";
//                            pr($html_design);exit;     
                        }
                        $html_design .= "</table>";
                        $html_design .= "<br><br><br>"
                                . "<table width='100%'>"
                                . "<tr>"
                                . "<td align=right width='50%'><h4>" . $labels[383] . "</h4></td></tr></table>";
                        $this->create_pdf($html_design, 'Stamp and Registration Comparative Statements', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function register_doc($frmData = NULL) {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['payment', 'finyear', 'ReportLabel']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
//         pr($lang);exit;
            $frmData = isset($this->request->data['rpt_reg_doc']) ? $this->request->data['rpt_reg_doc'] : $frmData;
            $stateid = $this->Auth->User("state_id");
            // $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 24)));
            $user_id = $this->Auth->user('user_id');
            $user_type_flag = $this->Session->read("session_usertype");
//            $office_id = $this->Auth->user('office_id');
//            $office_name = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('office_id' => $office_id), 'order' => 'office_name_' . $lang));
//            $off=  $office_name[2];

            if (isset($frmData['from']) && isset($frmData['to'])) {
                $from = date('Y-m-d', strtotime($frmData['from']));
                 $to = date('Y-m-d', strtotime($frmData['to']));
//                $nextday=strftime("%Y-%m-%d", strtotime("$to +1 day"));
                if ($this->is_Date($frmData['from']) && $this->is_Date($frmData['to'])) {

                    $cash_receipt = $this->payment->Query("select aps.final_stamp_date,app_dt.appointment_date,app_dt.sheduled_time,aps.doc_reg_date, aps.doc_reg_no,aps.token_no,office.office_name_en,SUM(pay.pamount) as total from ngdrstab_trn_application_submitted aps
                                                           join ngdrstab_trn_payment_details pay on pay.token_no=aps.token_no
                                                           join ngdrstab_mst_office office on office.office_id=aps.office_id
                                                            join ngdrstab_trn_appointment_details app_dt on app_dt.token_no=aps.token_no
                                                           where aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date) >='$from' and DATE(aps.final_stamp_date) <= '$to' group by aps.final_stamp_date,app_dt.appointment_date,app_dt.sheduled_time,aps.doc_reg_no,aps.token_no,office.office_name_en,aps.doc_reg_date order by office.office_name_en,aps.doc_reg_date,aps.doc_reg_no");
//                    pr($cash_receipt);exit;
//                 $cash_receipt = $this->payment->Query("select  app.office_id,office.office_name_$lang,count(app.doc_reg_no)as doc_reg, (select SUM(pay.pamount) as total from ngdrstab_trn_payment_details  as  pay,
//                                                            ngdrstab_trn_application_submitted as app1 
//                                                            where     pay.token_no=app1.token_no 
//                                                            and (app1.final_stamp_date BETWEEN '$from' and '$to')
//                                                            and app1.office_id=app.office_id
//                                                               ) as total
//                                                                 from ngdrstab_trn_application_submitted as app 
//                                                                 join ngdrstab_mst_office office on office.office_id=app.office_id
//                                                            where app.final_stamp_flag='Y'  
//                                                            and (app.final_stamp_date BETWEEN '$from' and '$to')
//                                                            group by app.office_id,office.office_name_$lang order by office.office_name_$lang");

                    if ($cash_receipt) {
                        $html_design = "<style>td{padding:5px;} </style>"
                              
                                . "<h2 align=center> Total Registerd Documents </h2>"
                                . "<table  align=center border=0 width=100%><tr><td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($frmData['from'])) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($frmData['to'])) . "  </td></tr></table>"
                                . "<table border=1 style='border-collapse:collapse;' width=100%>"
                                . "<tr>"
                                . "<th style='text-align:center;'>Sr.NO</th>"
                                . "<th style='text-align:center;'>Office Name</th>"
                                . "<th style='text-align:center;'>Document Registration No</th>"
                                . "<th style='text-align:center;'>Document Registration Date</th>"
                                . "<th style='text-align:center;'>Document Registration Time</th>"
                                . "<th style='text-align:center;'>Appointment Date</th>"
                                . "<th style='text-align:center;'>Appointment Time</th>"
                                . "</tr>";
                        $SrNo = 1;
                        $totalFees = 0;
                        foreach ($cash_receipt as $cr) {
                            $cr = $cr[0];
                            $html_design .= "<tr>"
                                      . "<td align=center>" . $SrNo++ . "</td>"
                                    . "<td style='text-align:center;'>" . $cr['office_name_en'] . "</td>"
                                    . "<td style='text-align:center;'>" . $cr['doc_reg_no'] . "</td>"
                                    . "<td style='text-align:center;'>" . date('d/m/Y', strtotime($cr['doc_reg_date'])) . "</td>"
                                    . "<td style='text-align:center;'>" . date('H:i:s a', strtotime($cr['final_stamp_date'])) . "</td>"
                                    . "<td style='text-align:center;'>" . date('d/m/Y ', strtotime($cr['appointment_date'])) . "</td>"
                                    . "<td style='text-align:center;'>" . $cr['sheduled_time'] . "</td>"
                                    . "</tr>";
                        }
                        $html_design .= "</table>";
                        $this->create_pdf($html_design, 'Total Register Document', 'A4-L', 'D');
                    } else {
                        echo 'No Data Found';
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

}
