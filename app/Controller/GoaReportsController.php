<?php

//App::import('Controller', 'Fees'); // mention at top

class GoaReportsController extends AppController {

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('document_status','confreg', 'tablereport', 'login_statistics', 'rpt_login_statistics', 'tablelistreport', 'rpt_fee_calc_list', 'rpt_fee_calc', 'rptvaluation', 'getvaluationlist', 'rptview', 'getsurveynumbers', 'ratereport', 'doc_payment_receipt', 'rpt_reg_summary1', 'rpt_reg_summary2', 'is_Date', 'rpt_payment_cashbook', 'payment_cashbook', 'get_identification_data');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
//        $this->Auth->allow();
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

//------------------------------------------------Create PDF- by Shridhar-----------------------------------------------------------------------------------------------
    /*
     * $html_design:Report Design
     * $file_name:name of pdf file
     * $page_size=Paper size with oriantion e.g A4-L, A4-P
     * $watermark='';// Watermark to be added in PDF Report
     */
    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = '', $display_flag = 'D') {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mpdf');
            $mpdf = new mPDF('utf-8', $page_size, 10, 'dejavusans');
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

    public function rpt_cash_book() {
        try {
            array_map([$this, 'loadModel'], ['office']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';

            $office_id = $this->Auth->User('office_id');
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $office_id");
            $officename = $officename[0][0]['office_name_' . $lang];
            $this->set('officename', $officename);

            if ($this->request->is('post')) {
//                  pr($this->request->data);  
                $from = date('Y-m-d', strtotime($this->request->data['rpt_cash_book']['from']));
                $to = date('Y-m-d', strtotime($this->request->data['rpt_cash_book']['to']));

//                pr($from);exit;

                $html_design = "<style>td{padding:5px;} </style>"
                        . "<h2 align=center style='color:#9C6F7A';> REGISTRATION DEPARTMENT, GOVERNMENT OF GOA  </h2>"
                        . "<h4 align=center> SUB-REGISTRY SECTION, DAILY CASH BOOK </h4>"
                        . "<hr style='color:red';>"
                        . "<div class='table-responsive'>"
                        . "<table width=100%>"
                        . "<tr><td>Office Name:- " . $officename . "</td></tr>"
                        . "<tr><td>This has been enerated from :-  " . date('d-M-Y', strtotime($from)) . "  to :  " . date('d-M-Y', strtotime($to)) . " </td></tr>"
                        . "</table>"
                        . "<table border=1 style='border-collapse:collapse;' width=100%>"
                        . "<tr>"
                        . "<th rowspan='3' style='text-align:center;' >Sr.No</th>"
                        . "<th rowspan='3' style='text-align:center;' >Receipt No</th>"
                        . "<th colspan='3' style='text-align:center;' >Registration Fee</th>"
                        . "<th rowspan='3' style='text-align:center;' >Fess For Copies Granted</th>"
                        . "<th colspan='5' style='text-align:center;' >Misclellius Fees </th>"
                        . "<th rowspan='3' style='text-align:center;' >Total Fees </th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th rowspan='2' style='text-align:center;' >Registration fee</th>"
                        . "<th rowspan='2' style='text-align:center;' >Processing Fee</th>"
                        . "<th rowspan='2' style='text-align:center;' >Total Registration Fee</th>"
                        . "<th rowspan='2' style='text-align:center;' >Fines Or Penalties</th>"
                        . "<th rowspan='2' style='text-align:center;' >Fees For Authentication</th>"
                        . "<th colspan='2' style='text-align:center;' >Other Items</th>"
                        . "<th rowspan='2' style='text-align:center;' >Total Registration Fee</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align:center;' >Fees </th>"
                        . "<th style='text-align:center;' >Other Fees Desciption</th>"
                        . "</tr>";


                $result = $this->office->query("select DISTINCT rcoun.receipt_number,artfee.fee_item_desc_en,feede.final_value,aps.token_no from ngdrstab_trn_application_submitted aps
                                join ngdrstab_trn_receipt_counter rcoun on rcoun.token_no=aps.token_no
                                join ngdrstab_trn_fee_calculation feecal on feecal.token_no=aps.token_no
                                join ngdrstab_trn_fee_calculation_detail feede on feede.fee_calc_id=feecal.fee_calc_id
                                join ngdrstab_mst_article_fee_items artfee on artfee.fee_item_id=feede.fee_item_id
                                where  aps.office_id=$office_id and aps.final_stamp_flag='Y' and DATE(aps.final_stamp_date) >= '01-01-2018' and DATE(aps.final_stamp_date) <= '10-01-2018' 
                                and artfee.fee_param_type_id=2 and rcoun.receipt_number is not NULL group by rcoun.receipt_number,artfee.fee_item_desc_en,feede.final_value,aps.token_no");
//           pr($result);exit;


                $SrNo = 1;
                $reg_fee = '';
                $pro_fee = '';
                $res_id = '';
                $RegFeesTotal = 0;
                $Total_RegFees_Total = 0;
                $Total_fees = 0;

                $profee = 0;




                foreach ($result as $result1) {

                    $res_id = $result1[0]['receipt_number'];
                    $res_print = '';

                    if ($res_id != $res_print) {
                        $res_print = $res_id;
//pr($res_print);exit;

                        if ($result1[0]['fee_item_desc_en'] == 'Registration Fee') {
                            $reg_fee = $result1[0]['final_value'];
                        } else if ($result1[0]['fee_item_desc_en'] == 'Stamp Duty') {
                            $pro_fee = $result1[0]['final_value'];
                        }


                        if ($reg_fee != '' && $pro_fee != '') {

                            $RegFeesTotal = $RegFeesTotal + $reg_fee;
                            $total = $reg_fee + $pro_fee;
                            $Total_RegFees_Total = $Total_RegFees_Total + $total;
                            $Total_fees = $Total_fees + $total;
                            $profee = $profee + $pro_fee;


                            $html_design .= "<tr>"
                                    . "<td style='text-align:center;' >" . $SrNo++ . "</td>"
                                    . "<td style='text-align:center;' >" . $res_print . "</td>"
                                    . "<td style='text-align:center;' >" . $reg_fee . "</td>"
                                    . "<td style='text-align:center;' >" . $pro_fee . "  </td>"
                                    . "<td style='text-align:center;' >" . $total . " </td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >0</td>"
                                    . "<td style='text-align:center;' >" . $total . "</td>"
                                    . "</tr>";
                            $pro_fee = '';
                            $res_id = '';
                        }
                    }
                }
                $html_design.="<tr><td colspan=2> <b>Total </b></td>"
                        . "<td style='text-align:center; font-size:medium;' >" . $RegFeesTotal . "</td>"
                        . "<td style='text-align:center;' >" . $profee . "</td>"
                        . "<td style='text-align:center; font-size:medium;' >" . $Total_RegFees_Total . "</td><td></td><td></td><td></td><td></td><td></td><td></td>"
                        . "<td style='text-align:center; font-size:medium;' >" . $Total_fees . "</td>"
                        . "</tr>";
                $html_design.= "</table></div>";

                $html_design.= "<table><tr><td style='font-size:medium;'>Total Receipt Amount Rs.Ps :- " . $Total_fees . "</td></tr></table>";
//                pr($html_design);exit;

                $this->create_pdf($html_design, 'CashBook', 'A4', 'Cash-Book');
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function rpt_day_book() { // update on 19th july 2019
        try {
            array_map([$this, 'loadModel'], ['office']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';

            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $this->set('officename', $officename);

            if ($this->request->is('post')) {
//                  pr($this->request->data);  
                $from = date('Y-m-d', strtotime($this->request->data['rpt_day_book']['from']));
                $to = date('Y-m-d', strtotime($this->request->data['rpt_day_book']['to']));

//pr($to);exit;

                $html_design = "<style>td{padding:5px;} </style>"
                        . "<h6 align=right style='color:black';>Print Date : " . date('d/M/Y h:i:s a') . "</h6>"
                        . "<h4 align=center style='color:black';> OFFICE OF THE CIVIL REGISTRAR CUM SUB-REGISTRAR,<b> $officename</b> REGISTRATION DEPARTMENT, GOVERNMENT OF GOA  </h4>"
                        . "<h2 align=center style='color:#9C6F7A';> Day Book  </h2>"
                        . "<h4 align=center style='color:black';> $officename  </h4>"
                        . "<table  align=center border=0 width=100%><tr><h3>This day book has been genrated from :<h3> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($from)) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($to)) . "  </td></tr></table>"
                        . "<hr style='color:balck';>"
                        . "<div class='table-responsive'>"
                        . "<table border=1 style='border-collapse:collapse;' width=100%>"
                        . "<thead>"
                        . "<tr>"
                        . "<th rowspan='2'>Sr.No Of Year</th>"
                        . "<th rowspan='2'>Description of Document and value (Consideration amount/Market value)</th>"
                        . "<th rowspan='2'>Name of presenter and place of residence</th>"
                        . "<th rowspan='2' class='vericaltext'>(a)whether Ordered (b)refused (c)withdrawn with date</th>"
                        . "<th colspan='3'>Registred</th>"
                        . "<th rowspan='2'>completion</th>"
                        . "<th colspan='3'>Date Of</th>"
                        . "<th rowspan='2'>Date of dispatch to Register for destruction</th>"
                        . "<th rowspan='2' width='15%'>Signature of Recipient </th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th rowspan='1'>Book</th>"
                        . "<th rowspan='1'>Vol.</th>"
                        . "<th rowspan='1'>Registration No.</th>"
                        . "<th rowspan='1'>Notice(Form V)</th>"
                        . "<th rowspan='1'>Return</th>"
                        . "<th rowspan='1'>Notice(Form AB)</th>"
                        . "</tr></thead>";

//                pr("$html_design");exit();



                /*   $result1= $this->office->query("select distinct A.token_no,cal.market_value,cal.cons_amt,art.book_number,A.doc_reg_no,art.article_desc_en,SUM(vald.final_value),Date(A.final_stamp_date),party.party_full_name_en,party.address_en,state.state_name_en,dist.district_name_en,taluka.taluka_name_en from ngdrstab_trn_application_submitted A 
                  join ngdrstab_trn_generalinformation info on A.token_no=info.token_no
                  join ngdrstab_trn_fee_calculation cal on A.token_no=cal.token_no
                  join ngdrstab_mst_article art on info.article_id=art.article_id
                  join ngdrstab_trn_valuation value on value.token_no=A.token_no
                  join ngdrstab_trn_party_entry_new party on A.token_no=party.token_no
                  join ngdrstab_trn_valuation_details vald on vald.val_id=value.val_id
                  join ngdrstab_conf_admblock1_state state on party.state_id=state.state_id
                  join ngdrstab_conf_admblock3_district dist on party.district_id=dist.district_id
                  join ngdrstab_conf_admblock5_taluka taluka on party.taluka_id=taluka.taluka_id
                  where cal.cons_amt is not NULL and A.office_id=$officeid and A.final_stamp_flag='Y' and is_presenter='Y'
                  and DATE(A.final_stamp_date) >= '$from' and DATE(A.final_stamp_date) <= '$to'
                  group by A.token_no,cal.market_value,cal.cons_amt,art.book_number,A.doc_reg_no,art.article_desc_en,A.final_stamp_date,party.party_full_name_en,party.address_en,state.state_name_en,dist.district_name_en,taluka.taluka_name_en order by date DESC"); */


                $result = $this->office->query("select distinct srno.office_serial_number,A.token_no,cal.market_value,cal.cons_amt,art.book_number,A.doc_reg_no,A.final_doc_reg_no,art.article_desc_en,SUM(vald.final_value),Date(A.final_stamp_date),party.party_full_name_en,party.address_en
                                        from ngdrstab_trn_application_submitted A 
                                        join ngdrstab_trn_generalinformation info on A.token_no=info.token_no 
                                        left join ngdrstab_trn_fee_calculation cal on A.token_no=cal.token_no and cal.cons_amt is not NULL
                                        join ngdrstab_mst_article art on info.article_id=art.article_id
                                        left join ngdrstab_trn_valuation value on value.token_no=A.token_no
                                        left join ngdrstab_trn_party_entry_new party on A.token_no=party.token_no
                                        left join ngdrstab_trn_valuation_details vald on vald.val_id=value.val_id
                                        join ngdrstab_trn_serial_numbers srno on srno.token_no=A.token_no
                                        where A.office_id=$officeid and A.final_stamp_flag='Y' and is_presenter='Y' 
                                        and DATE(A.final_stamp_date) >= '$from' and DATE(A.final_stamp_date) <= '$to'
                                        OR(A.doc_refuse_flag='Y' and is_presenter='Y'
                                        and DATE(A.doc_refuse_date) >= '$from' and DATE(A.doc_refuse_date) <= '$to')
                                        group by A.token_no,cal.market_value,cal.cons_amt,art.book_number,A.doc_reg_no,A.final_doc_reg_no,art.article_desc_en,A.final_stamp_date,party.party_full_name_en,party.address_en,srno.office_serial_number
                                        order by srno.office_serial_number,doc_reg_no ASC");

//                 pr($result);exit;

                $srno = 1;
                $reg_fee = 0;
                $pro_fee = 0;
                $res_id = '';
                $Name = 'Name';
                $Add = 'Address';
                $res_print = '';
                foreach ($result as $result1) {
//                    pr($result1);exit;
//                    $res_id=$result1[0]['receipt_number'];
//                    if($res_id!=$res_print){
//                        $res_print=$res_id;         
                    $html_design .= "<tr>";
//                    }
//                    if($result1[0]['fee_item_desc_en']=='Registration Fee'){
//                           $reg_fee=$result1[0]['final_value'];
//                    }else if($result1[0]['fee_item_desc_en']=='Processing Fee'){
//                           $pro_fee=$result1[0]['final_value'];
//                    }
//                    pr($result1[0]['final_doc_reg_no']);exit;
                    list($y, $d, $m) = split('[/.-]', $result1[0]['doc_reg_no']); // for serial number of year(doc_reg_no)  
                    $bk_num = explode("-", $result1[0]['final_doc_reg_no']);
                    // pr($bk_num[1]);exit;
                    $html_design .= "<td style='text-align:center;'>" . $d . "-" . $m . "-" . $y . "</td>"
                            . "<td style='text-align:center;'>" . $result1[0]['article_desc_en'] . "<br>\n----------\n<br>" . $result1[0]['cons_amt'] . "<br>\n---------\n<br>" . $result1[0]['sum'] . "</td>"
                            . "<td style='text-align:center;'>" . $Name . " : " . $result1[0]['party_full_name_en'] . "<br>\n-------------\n<br>" . $Add . " : " . $result1[0]['address_en'] . "</td>";
                    if ($bk_num[1] != 2) {
                        $html_design .= "<td></td>";
                    } else {
                        $html_design .= "<td style='text-align:center;'>refused</td>";
                    }
                    $html_design .= "<td style='text-align:center;'>" . $bk_num[1] . "</td>"
                            . "<td style='text-align:center;'></td>";
                    if ($bk_num[1] != 2) {
                        $html_design .= "<td style='text-align:center;'>" . $result1[0]['final_doc_reg_no'] . "</td>";
                    } else {
                        $html_design .= "<td style='text-align:center;'></td>";
                    }

                    $html_design .=
                            "<td style='text-align:center;'>" . $result1[0]['date'] . "</td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td ></td>"
                            . "<td ></td>"
                            . "<td ></td>"
                            . "</tr>";
                }

                $html_design .= "</table></div>";
//                pr($html_design);exit; 
//              $mfile=fopen("D:/A_Old_Pc_Data/abc.txt","w");
//                fwrite($mfile,$html_design);
//                fclose($mfile);
//                exit;
//                      
                $this->create_pdf($html_design, 'rpt_day_book', 'A4-L', ' ');
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function rpt_form13() { // GOA state
        try {
            array_map([$this, 'loadModel'], ['office', 'District', 'taluka', 'VillageMapping', 'property_details_entry', 'valuation_details', 'TrnBehavioralPatterns']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $stateid = $this->Auth->User('state_id');
            $officeid = $this->Auth->User("office_id");
            $officename = $this->office->query("select office_name_$lang from ngdrstab_mst_office where office_id = $officeid");
            $officename = $officename[0][0]['office_name_' . $lang];
            $this->set('officename', $officename);
            $this->set('District', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_' . $lang => 'ASC'))));
            $this->set('village', NULL);
            $this->set('taluka', NULL);

            $fieldlist = array();
            $fielderrorarray = array();


            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {

                $from = date('Y-m-d', strtotime($this->request->data['rpt_form13']['from']));
                $to = date('Y-m-d', strtotime($this->request->data['rpt_form13']['to']));

                $distid = $this->request->data['rpt_form13']['district_id'];
                $district_name = $this->District->field('district_name_en', array('district_id' => $distid));

                $villageid = $this->request->data['rpt_form13']['village_id'];
                $village_name = $this->VillageMapping->field('village_name_en', array('village_id' => $villageid));

                $talid = $this->request->data['rpt_form13']['taluka_id'];
                $taluka_name = $this->office->query("select taluka_name_$lang from ngdrstab_conf_admblock5_taluka where taluka_id = $talid");
                $taluka_name = $this->taluka->field('taluka_name_en', array('taluka_id' => $talid));

                $html_design = "<style>td{padding:5px;} </style>"
                        . "<h6 align=right style='color:black';>Download Date : " . date('d-M-Y H:i:s') . "</h6>"
                        . "<h2 align=center style='color:black';><u> Form XIII Proforma transmitted by Sub-Registrar Intiating Mutation </u></h2>"
                        . "<h2 align=center style='color:#9C6F7A';>   </h2>"
                        . "<h4 align=left style='color:black';> Registering Officers transaction affecting land in <b style='color:#9C6F7A';><u>$village_name</u></b> Village<b style='color:#9C6F7A';><u> $taluka_name </u></b> Taluka <b style='color:#9C6F7A';><u> $district_name </u></b> District  </h4>"
                        . "<table  align=center border=0 width=100%><tr><h3>This FomXIII has been genrated :<h3> <td align=center><b> From Date: </b> " . date('d-M-Y', strtotime($from)) . "  </td> <td align=center><b>To Date:</b> " . date('d-M-Y', strtotime($to)) . "  </td></tr></table>"
                        . "<hr style='color:balck';>"
                        . "<table border=1 style='border-collapse:collapse;' width=100%>"
                        . "<thead><tr>"
                        . "<th>Sr.No in Registration Book</th>"
                        . "<th >Name Of Village in which land is Situated</th>"
                        . "<th >Nature Of The Document</th>"
                        . "<th >Survey Number and Sub-division No</th>"
                        . "<th >Area in Sq.Metre</th>"
                        . "<th >Assessment</th>"
                        . "<th >Tenure</th>"
                        . "<th >Name and Residence of the executor of the Document</th>"
                        . "<th >Name of the person in whose favour the document is to be executed </th>"
                        . "<th >Where the registratered transaction is by order of court or otherwise  </th>"
                        . "<th >Amount in Consideration in Rs. </th>"
                        . "<th >Registration Date</th>"
                        . "<th >Remarks</th>"
                        . "</tr></thead>";

//                $result = $this->office->query("select distinct A.doc_reg_no,A.token_no,art.article_desc_en,village.village_name_en,cal.cons_amt,A.doc_reg_date,dist.district_name_en,taluka.taluka_name_en,final_stamp_pending_remark from ngdrstab_trn_application_submitted A
//                                            join ngdrstab_trn_generalinformation info on A.token_no=info.token_no
//                                            join ngdrstab_mst_article art on info.article_id=art.article_id
//                                            join ngdrstab_conf_admblock7_village_mapping village on info.village_id=village.village_id
//                                            join ngdrstab_trn_property_details_entry prop on prop.token_no=A.token_no
//                                            join ngdrstab_conf_admblock3_district dist on info.district_id=dist.district_id
//                                            join ngdrstab_conf_admblock5_taluka taluka on info.taluka_id=taluka.taluka_id
//                                            join ngdrstab_trn_fee_calculation cal on A.token_no=cal.token_no
//                                            where cal.cons_amt is not NULL and A.office_id=$officeid and prop.village_id=$villageid and info.district_id=$distid and info.taluka_id=$talid and DATE(A.final_stamp_date) >= '$from' and DATE(A.final_stamp_date) <= '$to' group by A.token_no ,A.doc_reg_no,art.article_desc_en,village.village_name_en,cal.cons_amt,A.doc_reg_date,dist.district_name_en,taluka.taluka_name_en,final_stamp_pending_remark");

                $result = $this->office->query("select distinct prop.village_id, prop.district_id, prop.taluka_id, A.doc_reg_no,A.token_no,art.article_desc_en,cal.cons_amt,A.doc_reg_date,final_stamp_pending_remark from ngdrstab_trn_application_submitted A
                                            join ngdrstab_trn_generalinformation info on A.token_no=info.token_no
                                            join ngdrstab_mst_article art on info.article_id=art.article_id
                                             join ngdrstab_trn_property_details_entry prop on prop.token_no=A.token_no
                                            join ngdrstab_trn_fee_calculation cal on A.token_no=cal.token_no
                                            where cal.cons_amt is not NULL and
                                            prop.village_id=$villageid and prop.district_id=$distid and prop.taluka_id=$talid and 
                                            DATE(A.final_stamp_date) >= '$from' and DATE(A.final_stamp_date) <= '$to' 
                                            group by prop.village_id, prop.district_id, prop.taluka_id,A.token_no ,A.doc_reg_no,art.article_desc_en,cal.cons_amt,A.doc_reg_date,final_stamp_pending_remark");
//                pr($result);exit;
                $SrNo = 1;
                $reg_fee = 0;
                $pro_fee = 0;
                $res_id = '';
                $Name = 'Name';
                $Add = 'Address';
                $res_print = '';

                foreach ($result as $result1) {
                    //  $party_party = $this->party_entry->get_party_entry($lang, $result1[0]['token_no']);
                    $rptPropDtl = $this->property_details_entry->get_property_detail_list($lang, $result1[0]['token_no']);

                    $html_design .= "<tr>";
                    $html_design .= "<td style='text-align:center;'>" . $result1[0]['doc_reg_no'] . "</td>"
                            . "<td style='text-align:center;'>$village_name</td>"
                            . "<td style='text-align:center;'>" . $result1[0]['article_desc_en'] . "</td>";

                    foreach ($rptPropDtl as $prpt) {
                        $prop_area = $this->valuation_details->get_valuation_details_cake($lang, $prpt['property_details_entry']['val_id']);
                        $tmp_prop_area = array();
                        foreach ($prop_area as $parea) {
                            if ($parea['item']['is_list_field_flag'] == 'Y')
                                $parea['valuation_details']['item_value'] = $parea['list']['item_desc_' . $lang];
                            array_push($tmp_prop_area, $parea['item']['usage_param_desc_' . $lang] . ' : ' . $parea['valuation_details']['item_value'] . ' ' . $parea['unit']['unit_desc_' . $lang]);
                        }
//                            pr($tmp_prop_area); exit;

                        $serveyno = $this->property_details_entry->query("SELECT mparam.eri_attribute_name,param.paramter_value
                                    from ngdrstab_trn_parameter as param
                                    JOIN ngdrstab_mst_attribute_parameter as mparam ON mparam.attribute_id=param.paramter_id
                                    where 
                                    param.token_id=? and param.property_id=?", array($result1[0]['token_no'], $prpt['property_details_entry']['property_id']));
                        // pr($serveyno); exit;
                        $html_design .= "<td>";
                        foreach ($serveyno as $serveyno1) {

                            $html_design .= $serveyno1[0]['eri_attribute_name'] . ':-' . $serveyno1[0]['paramter_value'] . ',';
                        }
                        $html_design .= "<td>" . implode(', ', $tmp_prop_area) . "</td>";
                    }
                    $html_design .="</td>";
                    $html_design .=
                            "<td style='text-align:center;'></td>"
                            . "<td style='text-align:center;'></td>";

                    $seller_name = $this->office->Query("select party.party_full_name_en as seller_name,party.id
					from  ngdrstab_trn_party_entry_new party
					join ngdrstab_mst_party_type party_type on party_type.party_type_id=party.party_type_id									
					where party_type.party_type_flag='1' and  party.party_type_id=1 and party.token_no=?", array($result1[0]['token_no']));

                    $buyer_name = $this->office->Query("select party_new.party_full_name_en as buyer_name,party_new.id from ngdrstab_trn_party_entry_new as party_new 
					    join ngdrstab_mst_party_type party_type on party_type.party_type_id=party_new.party_type_id
					    where  party_type.party_type_flag='0' and party_new.party_type_id=2 and party_new.token_no=?", array($result1[0]['token_no']));


                    $html_design .= "<td>";
                    foreach ($seller_name as $seller_name1) {
                        $tmp_adds = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $seller_name1[0]['id'], $result1[0]['token_no'], '2', $lang);
                        $party_address = array();
                        foreach ($tmp_adds as $tmp_address) {
                            array_push($party_address, $tmp_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                        }

                        $html_design .= $seller_name1[0]['seller_name'] . "," . implode('<br>', $party_address);
                    }

                    $html_design .=
                            "</td><td style='text-align:center;'></td>"
                            . "<td style='text-align:center;'></td><td>";
                    foreach ($buyer_name as $buyer_name1) {

                        $tmp_adds = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $buyer_name1[0]['id'], $result1[0]['token_no'], '2', $lang);
                        $party_address = array();
                        foreach ($tmp_adds as $tmp_address) {
                            array_push($party_address, $tmp_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                        }
                        $html_design .= $buyer_name1[0]['buyer_name'] . ',' . implode('<br>', $party_address);
                    }

                    $html_design .= "</td><td></td>"
                            . "<td >" . $result1[0]['cons_amt'] . "</td>"
                            . "<td >" . $result1[0]['doc_reg_date'] . "</td>"
                            . "<td ></td>"
                            . "</tr>";
                }
                $html_design .= "</table>";
//                       echo $html_design;exit;
                $this->create_pdf($html_design, 'rpt_form13', 'A4-L', 'NGDRS');
                //  $this->set("html_design",$html_design);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function rpt_appointment_pendency() { // for new role as stateregistrar => GOA State
        try {
            array_map([$this, 'loadModel'], ['party_entry', 'ApplicationSubmitted', 'office']);
            $unmae = $this->Auth->User("username");
            $stateid = $this->Auth->User("state_id");
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'order' => 'office_id'));
            $this->set('office', $office);
            $this->set('rdbtn', '');
            $flag = ['O' => 'All Offices', 'S' => 'Office Wise'];
            $this->set('usercreate_flag', $flag);

            $fieldlist = array();
            //$fieldlist['office_id']['select'] = 'is_select_req';
            $fieldlist['usercreate_flag']['select'] = 'is_alpha_select';
            $fieldlist['from']['text'] = 'is_required';
            $fieldlist['to']['text'] = 'is_required';
            $this->set('fieldlist', $fieldlist);

            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $errarr = $this->validatedata($this->request->data['rpt_appointment_pendency'], $fieldlist);
                if ($this->validationError($errarr)) {
                    $this->set('rdbtn', $_POST['rdbtn']);

                    if ($this->request->data['rpt_appointment_pendency']['usercreate_flag'] == 'O') {
                        $from = date('Y-m-d', strtotime($this->request->data['rpt_appointment_pendency']['from']));
                        $to = date('Y-m-d', strtotime($this->request->data['rpt_appointment_pendency']['to']));


                        $data_off = $this->ApplicationSubmitted->Query("SELECT office.office_name_en,
                                                                    Count(*) AS assigned, 
                                                                    (SELECT Count(*) 
                                                                     FROM   ngdrstab_trn_generalinformation 
                                                                     WHERE  created :: DATE >= '$from' 
                                                                            AND created :: DATE <= '$to' 
                                                                            AND office_id = info.office_id 
                                                                            AND sro_user_id = info.sro_user_id 
                                                                            AND sro_approve_flag = 'Y') AS approved, 
                                                                    usr.username 
                                                             FROM   ngdrstab_trn_generalinformation AS info 
                                                                    join ngdrstab_mst_office AS office 
                                                                      ON office.office_id = info.office_id 
                                                                    join ngdrstab_mst_user AS usr 
                                                                      ON usr.user_id = info.sro_user_id 
                                                             WHERE 
                                                               info.created :: DATE >= '$from' 
                                                               AND info.created :: DATE <= '$to' 
                                                             GROUP  BY info.office_id, 
                                                                       office.office_name_en, 
                                                                       info.sro_user_id, 
                                                                       usr.username 
                                                             ORDER  BY info.office_id, 
                                                                       office.office_name_en, 
                                                                       usr.username ");
                        if (!empty($data_off)) {
                            $this->set('app_off', $data_off);
                            //$this->set('office', $ofnm);
                            // $this->set('username', $unmae);
                        } else {
                            $this->Session->setFlash(__('Record Not Found!!!'));
                            $this->redirect(array('controller' => 'Masters', 'action' => 'rpt_appointment_pendency'));
                        }
                    } else {

                        $from = date('Y-m-d', strtotime($this->request->data['rpt_appointment_pendency']['from']));
                        $to = date('Y-m-d', strtotime($this->request->data['rpt_appointment_pendency']['to']));
                        $officeid = $this->request->data['rpt_appointment_pendency']['office_id'];
                        if ($officeid == NULL) {
                            $this->Session->setFlash(__('Please Select Office!!!'));
                            $this->redirect(array('controller' => 'Masters', 'action' => 'rpt_appointment_pendency'));
                        }
                        $data_off = $this->ApplicationSubmitted->Query("SELECT office.office_name_en,
                                                                    Count(*) AS assigned, 
                                                                    (SELECT Count(*) 
                                                                     FROM   ngdrstab_trn_generalinformation 
                                                                     WHERE  created :: DATE >= '$from' 
                                                                            AND created :: DATE <= '$to' 
                                                                            AND office_id = info.office_id 
                                                                            AND sro_user_id = info.sro_user_id 
                                                                            AND sro_approve_flag = 'Y') AS approved, 
                                                                    usr.username 
                                                             FROM   ngdrstab_trn_generalinformation AS info 
                                                                    join ngdrstab_mst_office AS office 
                                                                      ON office.office_id = info.office_id 
                                                                    join ngdrstab_mst_user AS usr 
                                                                      ON usr.user_id = info.sro_user_id 
                                                             WHERE 
                                                               info.office_id = $officeid
                                                               AND info.created :: DATE >= '$from' 
                                                               AND info.created :: DATE <= '$to' 
                                                             GROUP  BY info.office_id, 
                                                                       office.office_name_en, 
                                                                       info.sro_user_id, 
                                                                       usr.username 
                                                             ORDER  BY info.office_id, 
                                                                       office.office_name_en, 
                                                                       usr.username");

                        if (!empty($data_off)) {
                            $this->set('app_off', $data_off);
                            //$this->set('office', $ofnm);
                            //$this->set('username', $unmae);
                        } else {
                            $this->Session->setFlash(__('Record Not Found!!!'));
                            $this->redirect(array('controller' => 'Masters', 'action' => 'rpt_appointment_pendency'));
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
	
	public function rpt_daily_mutation_dtwise() {
        try {
            array_map([$this, 'loadModel'], [ 'ApplicationSubmitted']);
            $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : 'en';
            $fieldlist = array();
            $fieldlist['from']['text'] = 'is_required';
            $fieldlist['to']['text'] = 'is_required';
            //  $fieldlist['rounding_id']['text'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $errarr = $this->validatedata($this->request->data['rpt_daily_mutation_dtwise'], $fieldlist);
//                     pr($errarr);exit;
                if ($this->ValidationError($errarr)) {
                    $from = date('Y-m-d', strtotime($this->request->data['rpt_daily_mutation_dtwise']['from']));
                    $to = date('Y-m-d', strtotime($this->request->data['rpt_daily_mutation_dtwise']['to']));

                    $mutation = $this->ApplicationSubmitted->Query("select count(app.token_no) as ttl_reg,app.mutation_date::date 
                                        from ngdrstab_trn_application_submitted as app
                                        JOIN ngdrstab_trn_generalinformation as info ON info.token_no=app.token_no	
                                        JOIN ngdrstab_mst_article as article ON article.article_id=info.article_id
                                        JOIN ngdrstab_trn_payment_details as pay ON pay.token_no=info.token_no and account_head_code='48'
                                        where article.property_applicable='Y' and app.final_stamp_flag='Y' and mutation_flag='Y' 
                                        and app.mutation_date::date >= '$from' and app.mutation_date::date <= '$to'
                                        group by app.mutation_date::date
                                        order by app.mutation_date::date");
                    //pr($index1);exit;
                    if (!empty($mutation)) {
                        $this->set('dcount', $mutation);
                    } else {
                        $this->Session->setFlash(__('Record Not Found!!!'));
                        $this->redirect(array('controller' => 'GoaReports', 'action' => 'rpt_daily_mutation_dtwise'));
                    }
                }
            }
        } catch (Exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}

?>