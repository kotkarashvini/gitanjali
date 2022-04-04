<?php

class RajasthanReportsController extends AppController {

    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');

        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Auth->allow('inspection_search','get_payment_details2','simple_reciept','get_payment_details1', 'scan', 'checkscan', 'loadfile', 'upload');
        $this->Auth->allow('exception_occurred');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function cashregister() {
        try {            
            $this->set('pdf_flag', NULL);
            if ($this->request->is('post')) {
                $from_date = $this->request->data['cashregister']['from'];
                $to_date = $this->request->data['cashregister']['to'];
                $this->cashbook($from_date, $to_date);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function cashbook($from_date, $to_date) {
        try {

            array_map([$this, 'loadModel'], ['payment', 'ReportLabel']);
            $lang = $this->Session->read("sess_langauge");
            $created_date = date('Y/m/d');
            $rpt_title = 'Cash Book';
            $index = 1;
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h5 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h5>"
                    . "<h3 style='text-align:center;'>Cash Book</h3>";
            // pr($html_title);
            $html_title.=''
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 15)));
            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=50% border=1 align=center>"
                    . "<tr  style='border:1px solid black'>"
                    . "<th>" . $labels[148] . "</th>"
                    . "<th>" . $labels[149] . "</th>"
                    . "<th>" . $labels[150] . "</th>"
                    . "<th>" . $labels[151] . "</th>"
                    . "<th>" . $labels[152] . "</th>"
                    . "<th>" . $labels[153] . "</th>"
                    . "<th>" . $labels[154] . "</th>"
                    . "</tr>";

            $cashhbook = $this->payment->query("select afi.fee_item_desc_en ,pd.pdate,pd.token_no,SUM(pamount)as pamount from ngdrstab_trn_payment_details pd
                                                inner join  ngdrstab_trn_application_submitted ast on ast.token_no=pd.token_no 
                                                inner join  ngdrstab_mst_article_fee_items afi on afi.account_head_code=pd.account_head_code
                                                where pdate  BETWEEN ? and ?
                                                group by ast.token_no,pd.token_no,pd.pdate,afi.fee_item_desc_en", array($from_date, $to_date));
//           pr($cashhbook);exit;

            foreach ($cashhbook as $cashhbook1) {

                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td>" . $cashhbook1[0]['pdate'] . "</td>"
                        . "<td width='40%'>" . $cashhbook1[0]['fee_item_desc_en'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "<td style='text-align:center;'>" . $cashhbook1[0]['pdate'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "<td style='text-align:center;'>" . $cashhbook1[0]['pamount'] . "</td>"
                        . "</tr>";
            }
            $html_design.="</table>";

            $html_design.="<br><br><br>"
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5> Cashier Signature</h5></td>"
                    . "<td align=right width='50%'> <h5 > Sub Register</h5> </td></tr></table>";

            $html_title.= $html_design;
//                            pr($html_title);exit;
            $this->create_pdf($html_title, $rpt_title, 'A4', '');
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function valuation() {
        try {
            if ($this->request->is('post')) {
                $from_date = $this->request->data['valuation']['from'];
                $to_date = $this->request->data['valuation']['to'];
                $filterby = $this->request->data['valuation']['filterby'];
                if ($filterby == 'HVS') {
                    $this->rpt_higher_value_summary($from_date, $to_date);
                } else if ($filterby == 'OP') {
                    $this->on_presentation($from_date, $to_date);
                } else if ($filterby == 'AV') {
                    $this->after_visit($from_date, $to_date);
                } else if ($filterby == 'SD') {
                    $this->stamp_duty($from_date, $to_date);
                } else if ($filterby == 'AWSR') {

                    $this->rpt_areawise_surcharge_report($from_date, $to_date);
                } else {
                    echo 'nothing';
                    exit;
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function rpt_regoffee_office_subregister() {
        try {
            $rpt_title = 'Register Of Fee Of the Office Of Sub Register';

            $html_title = "<tr>"
                    . "<th style='text-align:center;'> Goverment Of Rajasthan <br></th>"
                    . "<th> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER</th>"
                    . "<th> Register Of Fee Of the Office Of Sub Register  </th>"
                    . "</tr>";
            // pr($html_title);
            $html_design = "<table style='border-collapse: collapse;border: 1px solid black; width:100%'>"
                    . "<tr>"
                    . "<th>Sr.No.&nbsp;&nbsp;</th>"
                    . "<th>Document S.NO &nbsp;&nbsp; </th>"
                    . "<th>Book No. &nbsp;&nbsp;</th>"
                    . "<th>Document Type &nbsp;&nbsp; </th>"
                    . "<th>Ord Reg. Fee &nbsp;&nbsp; </th>"
                    . "<th>Stamp Duty &nbsp;&nbsp; </th>"
                    . "<th>Total (5 to 6) &nbsp;&nbsp;</th>"
                    . "<th>Copying Fee u/s 57 &nbsp;&nbsp; </th>"
                    . "<th>Commission Fee &nbsp;&nbsp; </th>"
                    . "<th>Custody Fee &nbsp;&nbsp; </th>"
                    . "<th>Sealed the Envlope(WILL) &nbsp;&nbsp;</th>"
                    . "<th>Total Fee (7 to 11) &nbsp;&nbsp;</th>"
                    . "<th>Stamp &nbsp;&nbsp;</th>"
                    . "<th>e-Stamp &nbsp;&nbsp;</th>"
                    . "<th>e-Registration &nbsp;&nbsp;</th>"
                    . "<th>e-GRASS &nbsp;&nbsp;</th>"
                    . "<th>DD &nbsp;&nbsp;</th>"
                    . "<th>Cash &nbsp;&nbsp;</th>"
                    . "<th>Grand Total (13 to 18) &nbsp;&nbsp;</th>"
                    . "<th>Receipt No. &nbsp;&nbsp;</th>"
                    . "<th>S R Initials &nbsp;&nbsp;</th>"
                    . "</tr>";


            $html_title.= $html_design;
            $html_title .= "</table>";
            pr($html_title);
            exit;
        } catch (Exception $ex) {
            $this->create_pdf($html_title, $rpt_title, 'A4');
        }
    }

    public function rpt_higher_value_summary($from_date, $to_date) {
        try {

//            pr($from_date);
//            pr($to_date);
            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'stamp_duty']);
            $lang = $this->Session->read("sess_langauge");
            $rpt_title = 'Higher Value Summary';
            $index = 1;
            $market_total = 0;
            $calulated_total = 0;
            $stampduty_total = 0;
            $created_date = date('Y/m/d');
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Higher Value Summary</h5>";
            $html_title.=''
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 9)));
            $html_design = ""
                    . "<style> td{padding:3px;}  table{border-collapse: collapse;'}   </style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr style='border:1px solid black'>"
                    . "<th>" . $labels[111] . "</th>"
                    . "<th>" . $labels[106] . "</th>"
                    . "<th>" . $labels[107] . "</th>"
                    . "<th>" . $labels[108] . "</th>"
                    . "<th>" . $labels[109] . " </th>"
                    . "<th>" . $labels[110] . "</th>"
                    . "</tr>";
//            echo'yetoy';exit;
//              pr($html_design);exit;
            $data = $this->stamp_duty->query("select  sd.final_amt,SUM(fc.cons_amt) as con_amt,fc.market_value,sd.online_adj_date,ast.doc_reg_no from ngdrstab_trn_stamp_duty sd
                                              inner join ngdrstab_trn_application_submitted ast on ast.token_no=sd.token_no
                                              inner join ngdrstab_trn_fee_calculation fc on fc.token_no=ast.token_no where  sd.online_adj_date  BETWEEN ? and ?
                                              group by fc.market_value,ast.doc_reg_no,sd.final_amt,sd.online_adj_date", array($from_date, $to_date));

//            pr($data);exit;
            foreach ($data as $data1) {
                $market_total = $data1[0]['market_value'] + $market_total;
                $calulated_total = $data1[0]['con_amt'] + $calulated_total;
                $stampduty_total = $data1[0]['final_amt'] + $stampduty_total;
                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td>" . $data1[0]['doc_reg_no'] . "</td>"
                        . "<td width='40%'>" . $data1[0]['market_value'] . "</td>"
                        . "<td style='text-align:center;'>" . $data1[0]['con_amt'] . "</td>"
                        . "<td style='text-align:center;'>" . $data1[0]['final_amt'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "</tr>";
            }
            $html_design.= "<tr style='border:1px solid black; '>"
                    . "<th></th>"
                    . "<th > Total </th>"
                    . "<th>$market_total </th>"
                    . "<th>$calulated_total</th>"
                    . "<th> $stampduty_total</th>"
                    . "<th> - </th>"
                    . "</tr>";
//           $html_design= "<hr>";
            $html_design.="</table>";

            $html_design.="<br>"
                    . "<h5 align=right> Sub Register</h5>";

            $html_title.= $html_design;
//            pr($html_title);
//            exit;
            $this->create_pdf($html_title, $rpt_title, 'A4-L', '');
        } catch (Exception $ex) {
            
        }
    }

    public function rpt_areawise_surcharge_report($from_date, $to_date) {
        try {
            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'stamp_duty']);
            $lang = $this->Session->read("sess_langauge");
            $rpt_title = ' Area Wise Surcharge Report';
            $index = 1;
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 14)));
            $created_date = date('Y/m/d');
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Area Wise Surcharge Report</h5>";
            $html_title.=''
                    . "<table width='95%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr  style='border:1px solid black'>"
                    . "<th>" . $labels[144] . "</th>"
                    . "<th>" . $labels[145] . "</th>"
                    . "<th>" . $labels[146] . "</th>"
                    . "<th>" . $labels[147] . "</th>"
                    . "</tr>";

            //$labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 9)));
            $surchage = $this->stamp_duty->query("select village.developed_land_types_id,land_type.developed_land_types_desc_en, count(aps.*)as no_of_docs, sum(pd.pamount) as surcharge from ngdrstab_trn_application_submitted aps
                                                join ngdrstab_trn_property_details_entry prop on prop.token_no=aps.token_no
                                                join ngdrstab_conf_admblock7_village_mapping village on village.village_id=prop.village_id
                                                join ngdrstab_mst_developed_land_types land_type on land_type.developed_land_types_id=village.developed_land_types_id
                                                join ngdrstab_trn_payment_details as pd on pd.token_no=aps.token_no
                                                where account_head_code='0030045501' and pd.pdate between ? and ?
                                                group by village.developed_land_types_id,land_type.developed_land_types_desc_en,pd.pamount", array($from_date, $to_date));
// echo 'fsdlajfsjafj';exit;
            foreach ($surchage as $surchage1) {
                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td style='text-align:center;'>" . $surchage1[0]['developed_land_types_desc_en'] . "</td>"
                        . "<td style='text-align:center;'>" . $surchage1[0]['no_of_docs'] . "</td>"
                        . "<td style='text-align:center;'>" . $surchage1[0]['surcharge'] . "</td>"
                        . "</tr>";
            }
            $html_design.="</table>";

            $html_design.="<br>"
                    . "<h5 align=right> Sub Register</h5>";
            $html_title.= $html_design;
//            pr($html_title);  exit;
            $this->create_pdf($html_title, $rpt_title, 'A4', '');
        } catch (Exception $ex) {
            
        }
    }

    public function inspection_report() {
        try {
            if ($this->request->is('post')) {
                $from_date = $this->request->data['inspection_report']['from'];
                $to_date = $this->request->data['inspection_report']['to'];

                $filterby = $this->request->data['inspection_report']['filterby'];
                if ($filterby == 'SIA') {
                    $this->site_inspection_allotment($from_date, $to_date);
                } else if ($filterby == 'IWVDL') {
                    $this->inspector_wise_visited_documents($from_date, $to_date);
                } else if ($filterby == 'VA') {
                    $this->visit_notdone_for_allotmentdate($from_date, $to_date);
                } else if ($filterby == 'VSRL') {
                    $this->visit_sunregister_lottery_doc($from_date, $to_date);
                } else if ($filterby == 'SIB') {

                    $this->rpt_areawise_surcharge_report($from_date, $to_date);
                } else {

                    echo 'nothing';
                    exit;
                }



                $this->site_inspection_allotment($from_date, $to_date);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function site_inspection_allotment($from_date, $to_date) {
        try {

//            echo 'hiii';exit;
            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'party_entry', 'TrnBehavioralPatterns']);
            $lang = $this->Session->read("sess_langauge");
            $created_date = date('Y/m/d');
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 10)));
            $rpt_title = ' Site Inspection Allotment';
            $index = 1;
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Site Inspection Allotment</h5>";


            $html_title.=''
                    . "<br><br><br>"
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr   style='border:1px solid black'>"
                    . "<th>" . $labels[112] . "</th>"
                    . "<th>" . $labels[113] . "</th>"
                    . "<th>" . $labels[114] . "</th>"
                    . "<th>" . $labels[115] . "</th>"
                    . "<th>" . $labels[116] . " </th>"
                    . "<th>" . $labels[117] . "</th>"
                    . "<th>" . $labels[118] . "</th>"
                    . "<th>" . $labels[119] . "</th>"
                    . "</tr>";



            $party = $this->party_entry->query("select distinct p.party_id,village_name_en,taluka.taluka_name_en,district.district_name_en,state.state_name_en,
                                                ge.article_id,ar.article_desc_en as type_of_doc, aps.doc_reg_no ,aps.token_no as doc_s_no,pe.party_full_name_en as presenter_name
                                                from  ngdrstab_trn_party_entry_new p 
                                                join ngdrstab_trn_application_submitted aps on aps.token_no =p.token_no
                                                join  ngdrstab_trn_party_entry_new pe on pe.token_no=aps.token_no 
                                                join  ngdrstab_trn_generalinformation ge on ge.token_no=aps.token_no
                                                 join  ngdrstab_mst_article ar on ar.article_id=ge.article_id
                                                left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=p.village_id 
                                                left join ngdrstab_conf_admblock5_taluka taluka on taluka.taluka_id=p.taluka_id 
                                                left join ngdrstab_conf_admblock3_district district on district.district_id=p.district_id 
                                                left join ngdrstab_conf_admblock1_state state on state.state_id=p.state_id where pe.is_presenter='Y' and aps.token_submit_date between ? and ? ", array($from_date, $to_date));

            foreach ($party as $party) {

                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['doc_s_no'] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['doc_reg_no'] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['presenter_name'] . "</td>"
                        . "<td style='text-align: center;'>" . $party[0]['village_name_' . $lang] . ', ' . $party[0]['taluka_name_' . $lang] . ', ' . $party[0]['district_name_' . $lang] . ', ' . $party[0]['state_name_' . $lang] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['type_of_doc'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "</tr>";
            }
            $html_design.="</table>";

            $html_design.="<br>"
                    . "<h5 align=right> Sub Register</h5>";

            $html_title.= $html_design;
//            pr($html_title);
//            exit;
            $this->create_pdf($html_title, $rpt_title, 'A4-L', '');
        } catch (Exception $ex) {
            
        }
    }

    public function inspector_wise_visited_documents($from_date, $to_date) {
        try {
//                echo 'hiii';exit;
            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'party_entry', 'TrnBehavioralPatterns']);
            $lang = $this->Session->read("sess_langauge");
            $created_date = date('Y/m/d');
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 11)));
            $rpt_title = ' Inspecot Wise visited documents list';
            $index = 1;
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Inspecot Wise visited documents list</h5>";


            $html_title.=''
                    . "<br><br><br>"
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr  style='border-bottom-style: outset'>"
                    . "<th>" . $labels[120] . "</th>"
                    . "<th>" . $labels[121] . "</th>"
                    . "<th>" . $labels[122] . "</th>"
                    . "<th>" . $labels[123] . " </th>"
                    . "<th>" . $labels[124] . "</th>"
                    . "<th>" . $labels[125] . "</th>"
                    . "<th>" . $labels[126] . "</th>"
                    . "</tr>";



            $party = $this->party_entry->query("select distinct p.party_id,village_name_en,taluka.taluka_name_en,district.district_name_en,state.state_name_en,
                                                ge.article_id,ar.article_desc_en as type_of_doc, aps.doc_reg_no ,aps.token_no as doc_s_no,pe.party_full_name_en as presenter_name
                                                from  ngdrstab_trn_party_entry_new p 
                                                join ngdrstab_trn_application_submitted aps on aps.token_no =p.token_no
                                                join  ngdrstab_trn_party_entry_new pe on pe.token_no=aps.token_no 
                                                join  ngdrstab_trn_generalinformation ge on ge.token_no=aps.token_no
                                                 join  ngdrstab_mst_article ar on ar.article_id=ge.article_id
                                                left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=p.village_id 
                                                left join ngdrstab_conf_admblock5_taluka taluka on taluka.taluka_id=p.taluka_id 
                                                left join ngdrstab_conf_admblock3_district district on district.district_id=p.district_id 
                                                left join ngdrstab_conf_admblock1_state state on state.state_id=p.state_id where pe.is_presenter='Y'
                                                and aps.token_submit_date between ? and ? ", array($from_date, $to_date));

            foreach ($party as $party) {

                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['doc_s_no'] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['presenter_name'] . "</td>"
                        . "<td style='text-align: center;'>" . $party[0]['village_name_' . $lang] . ', ' . $party[0]['taluka_name_' . $lang] . ', ' . $party[0]['district_name_' . $lang] . ', ' . $party[0]['state_name_' . $lang] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['type_of_doc'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "</tr>";
            }
            $html_design.="</table>";

            $html_design.="<br>"
                    . "<h5 align=right> Sub Register</h5>";

            $html_title.= $html_design;
//            pr($html_title);
//            exit;
            $this->create_pdf($html_title, $rpt_title, 'A4-L', '');
        } catch (Exception $ex) {
            
        }
    }

    public function visit_notdone_for_allotmentdate($from_date, $to_date) {
        try {
            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'party_entry', 'TrnBehavioralPatterns']);
            $lang = $this->Session->read("sess_langauge");
            $created_date = date('Y/m/d');
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 12)));
            $rpt_title = ' Visit Note Done for the Allotment Date';
            $index = 1;
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Visit Note Done for the Allotment Date</h5>";


            $html_title.=''
                    . "<br><br><br>"
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr  style='border-bottom-style: outset'>"
                    . "<th>" . $labels[127] . "</th>"
                    . "<th>" . $labels[128] . "</th>"
                    . "<th>" . $labels[129] . "</th>"
                    . "<th>" . $labels[130] . " </th>"
                    . "<th>" . $labels[131] . "</th>"
                    . "<th>" . $labels[132] . "</th>"
                    . "<th>" . $labels[133] . "</th>"
                    . "</tr>";

            $party = $this->party_entry->query("select distinct p.party_id,village_name_en,taluka.taluka_name_en,district.district_name_en,state.state_name_en,
                                                ge.article_id,ar.article_desc_en as type_of_doc, aps.doc_reg_no ,aps.token_no as doc_s_no,pe.party_full_name_en as presenter_name
                                                from  ngdrstab_trn_party_entry_new p 
                                                join ngdrstab_trn_application_submitted aps on aps.token_no =p.token_no
                                                join  ngdrstab_trn_party_entry_new pe on pe.token_no=aps.token_no 
                                                join  ngdrstab_trn_generalinformation ge on ge.token_no=aps.token_no
                                                 join  ngdrstab_mst_article ar on ar.article_id=ge.article_id
                                                 join ngdrstab_trn_appointment_details ad on ad.token_no=aps.token_no
                                                left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=p.village_id 
                                                left join ngdrstab_conf_admblock5_taluka taluka on taluka.taluka_id=p.taluka_id 
                                                left join ngdrstab_conf_admblock3_district district on district.district_id=p.district_id 
                                                left join ngdrstab_conf_admblock1_state state on state.state_id=p.state_id where pe.is_presenter='Y'
                                                and aps.token_submit_date between ? and ? 
                                                and  ad.appointment_date!=date(aps.stamp1_date)", array($from_date, $to_date));

//            pr($party);exit;

            foreach ($party as $party) {

                $html_design.= "<tr>"
                        . "<td style='text-align:center;'>" . $index++ . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['doc_s_no'] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['presenter_name'] . "</td>"
                        . "<td style='text-align: center;'>" . $party[0]['village_name_' . $lang] . ', ' . $party[0]['taluka_name_' . $lang] . ', ' . $party[0]['district_name_' . $lang] . ', ' . $party[0]['state_name_' . $lang] . "</td>"
                        . "<td style='text-align:center;'>" . $party[0]['type_of_doc'] . "</td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "<td style='text-align:center;'> - </td>"
                        . "</tr>";
            }
            $html_design.="</table>";

            $html_design.="<br>"
                    . "<h5 align=right> Sub Register</h5>";

            $html_title.= $html_design;
//            pr($html_title);
//            exit;
            $this->create_pdf($html_title, $rpt_title, 'A4-L', '');
        } catch (Exception $ex) {
            
        }
    }

    public function visit_sunregister_lottery_doc($from_date, $to_date) {
        try {

            array_map([$this, 'loadModel'], ['payment', 'ReportLabel', 'party_entry', 'TrnBehavioralPatterns']);
            $lang = $this->Session->read("sess_langauge");
            $created_date = date('Y/m/d');
            $labels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 13)));
            $rpt_title = ' Visit Report Of Sub Registeer Lottery Documentst';
            $index = 1;
            $html_title = ""
                    . "<h3 align=center> Goverment Of Rajasthan</h3>"
                    . "<h4 align=center> REGISTRATION & STAMP DEPARTMENT,RAJASTHAN ,AJMER  </h4>"
                    . "<h3 style='text-align:center;'> Visit Report Of Sub Registeer Lottery Documentst</h5>";


            $html_title.=''
                    . "<br><br><br>"
                    . "<table width='100%'>"
                    . "<tr> <td width='50%'><h5>From date-$from_date &nbsp;&nbsp;To date-$to_date</h5></td>"
                    . "<td align=right width='50%'> <h5 > Print Date -$created_date </h5> </td></tr></table>";

            $html_design = ""
                    . "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                    . "<table width=100% border=0 align=center>"
                    . "<tr  style='border-bottom-style: outset'>"
                    . "<th>" . $labels[134] . "</th>"
                    . "<th>" . $labels[135] . "</th>"
                    . "<th>" . $labels[136] . "</th>"
                    . "<th>" . $labels[137] . " </th>"
                    . "<th>" . $labels[138] . "</th>"
                    . "<th>" . $labels[139] . "</th>"
                    . "<th>" . $labels[140] . "</th>"
                    . "</tr>";
        } catch (Exception $ex) {
            
        }
    }

     public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = 'NGDRS Demo', $display_flag = 'D') {
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
    
    
}
