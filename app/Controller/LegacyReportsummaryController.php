<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyReportsummaryController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
        //$this->Auth->allow('physub');
        //$this->Auth->allow('Legency_data_entry_new');
    }

    public function report($csrftoken = NULL, $doc_token_no = NULL, $flag = NULL) {

        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            $this->set('flag', $flag);

            $doc_token_no = $this->Session->read('Leg_Selectedtoken');
            //Pr($doc_token_no);exit;
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $user_id = $this->Session->read("citizen_user_id");
            $role_id = $this->Session->read("user_role_id");
            $doc_lang = $this->Session->read('doc_lang');
            $this->set(compact('doc_token_no', 'lang', 'stateid', 'user_id', 'doc_lang', 'role_id'));
        } catch (Exception $ex) {
            //pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_party_details_pre_reg_doc($lang = NULL, $doc_token_no = NULL, $prpt_id = NULL) {
        try {
            $this->loadModel('conf_reg_bool_info');
            $doc_lang = $this->Session->read('doc_lang');
            $prop_party = $this->Leg_party_entry->get_party_entry_new($lang, $doc_token_no, $prpt_id);
            //pr($prop_party);exit;
            $id = [3, 6, 9];
            $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => $id)));
            $seller = $purchaser = array();
            $seller_label = $purchaser_label = '';
            // pr($prop_party);
            foreach ($prop_party as $p_party) {

                $tmp_party_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $p_party['Leg_party_entry']['id'], $doc_token_no, '2', $doc_lang);
                // pr($tmp_party_address);exit;

                $party_address = array();
                foreach ($tmp_party_address as $tmp_party_address) {
                    array_push($party_address, $tmp_party_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_party_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                }
                // pr($p_party['party_entry']['party_id']);
                // pr($party_address);
                // if (!empty($party_address)) {
                if ($p_party['Leg_party_entry']['village_id'] == null) {
                    $party_address['address_' . $lang] = '<b></b>' . $p_party['Leg_party_entry']['address_' . $lang];
//                  $party_address['address2'] = '<b>Address2 - </b>' .$p_party['party_entry']['address2_'. $lang];
                }
                //   }
                //   pr($party_address);
                if ($party_address == NULL) {
                    $p_address = '';
                } else {
                    $p_address = '<b></b> ' . implode(', ', $party_address) . '';
                }

                if ($p_party['Leg_party_entry']['branch_name_' . $lang] == NULL) {
                    $p_brchname = '';
                } else {
                    $p_brchname = '<b>' . $rptlabels[415] . '-</b> ' . $p_party['Leg_party_entry']['branch_name_' . $lang] . '';
                }

//                if ($p_party['party_bank']['bank_name_' . $lang] == NULL) {
//                    $p_bnkname = '';
//                } else {
//                    $p_bnkname = '<b>' . $rptlabels[416] . '-</b> ' . $p_party['party_bank']['bank_name_' . $lang] . '';
//                }

                if ($p_party['Leg_party_entry']['father_full_name_' . $lang] == NULL) {
                    $p_father = '';
                } else {
                    //$p_father = '<b>' . $rptlabels[300] . '-</b> ' . $p_party['party_entry']['father_full_name_' . $lang] . ',';
                    $p_father = '<b>' . "Father Name" . '-</b> ' . $p_party['Leg_party_entry']['father_full_name_' . $lang] . ',';
                }
//pr($p_father);exit;
                if ($p_party['Leg_party_entry']['party_full_name_' . $lang] == NULL) {
                    $p_fllname = '';
                } else {
                    // $p_fllname = '<b>' . $rptlabels[414] . '-</b> ' . $p_party['party_entry']['party_full_name_' . $lang] . ',';
                    $p_fllname = '<b>' . "Party Name" . '-</b> ' . $p_party['Leg_party_entry']['party_full_name_' . $lang] . ',';
                }

                if ($p_party['occupation']['occupation_name_' . $lang] == NULL) {
                    $p_occ = '';
                } else {
                    $p_occ = '<b>' . $rptlabels[303] . ':</b> ' . $p_party['occupation']['occupation_name_' . $lang] . '';
                }

                if ($p_party['Leg_party_entry']['pan_no'] == NULL) {
                    $p_pan = '';
                } else {
                    $p_pan = '<b>' . 'PAN No.' . ' - </b>' . $p_party['Leg_party_entry']['pan_no'] . '';
                }

                if ($p_party['Leg_party_entry']['age'] == NULL) {
                    $p_age = '';
                } else {
                    $p_age = '<b>' . 'Age' . ':</b> ' . $p_party['Leg_party_entry']['age'] . ' ';
                }

                if ($p_party['Leg_party_entry']['org_name_' . $lang] == NULL) {
                    $p_org = '';
                } else {
                    $p_org = '<b>' . $rptlabels[413] . ':</b> ' . $p_party['Leg_party_entry']['org_name_' . $lang] . ',';
                }

                if ($p_party['Leg_party_entry']['party_fname_' . $lang] == NULL && $p_party['Leg_party_entry']['party_mname_' . $lang] == NULL && $p_party['Leg_party_entry']['party_lname_' . $lang] == NULL) {
                    $p_fullname = '';
                } else {
                    $p_fullname = '<b>' . $rptlabels[414] . ':</b> ' . $p_party['Leg_party_entry']['party_fname_' . $lang] . ' ' . $p_party['Leg_party_entry']['party_mname_' . $lang] . ' ' . $p_party['Leg_party_entry']['party_lname_' . $lang] . '  ';
                }



                if ($p_party['party_type']['party_type_flag'] == '1') {//seller
                    if ($p_party['party_catg']['authorised_signatory'] == 'N') {

                        if ($p_party['Leg_party_entry']['party_full_name_' . $lang] == NULL) {

                            array_push($seller, '' . $p_party['saluation']['salutation_desc_' . $lang] . ' ' . $p_fullname . ' ' . $p_father . '  ' . $p_age . ' ' . $p_occ . '  ' . $p_pan . ' <br>');
                        } else {

                            array_push($seller, ' ' . $p_org . ' ' . $p_brchname . '  <b>' . $p_party['saluation']['salutation_desc_' . $lang] . '</b>  ' . $p_fllname . ' ' . $p_address . ' ' . $p_father . '' . $p_age . '  ' . $p_occ . '   ' . $p_pan . ' <br>');
                        }
                        $seller_label = $p_party['party_type']['party_type_desc_' . $lang];
                    } else {

                        if ($p_party['Leg_party_entry']['party_full_name_' . $lang] == NULL) {

                            array_push($seller, ' ' . $p_org . ' ' . $p_brchname . '   ' . $p_fullname . '  ' . $p_address . ' ' . $p_father . '' . $p_age . '  ' . $p_occ . '  ' . $p_pan . '<br>');
                        } else {

                            array_push($seller, ' ' . $p_org . ' ' . $p_brchname . '   ' . $p_fllname . '' . $p_address . ' ' . $p_father . ' ' . $p_age . '   ' . $p_occ . '  ' . $p_pan . '<br>');
                        }
                        $seller_label = $p_party['party_type']['party_type_desc_' . $lang];
                    }
                }


                if ($p_party['party_type']['party_type_flag'] == '0') {//purchaser
                    if ($p_party['party_catg']['authorised_signatory'] == 'N') {

                        if ($p_party['Leg_party_entry']['party_full_name_' . $lang] == NULL) {

                            array_push($purchaser, '' . $p_party['saluation']['salutation_desc_' . $lang] . ' ' . $p_fullname . '    ' . $p_address . '  ' . $p_age . '   ' . $p_occ . '  ' . $p_pan . '<br>');
                        } else {
                            array_push($purchaser, ' ' . $p_org . ' ' . $p_brchname . '  <b>' . $p_party['saluation']['salutation_desc_' . $lang] . '  </b>   ' . $p_fllname . ' ' . $p_address . ' ' . $p_father . ' ' . $p_age . '   ' . $p_occ . '  ' . $p_pan . '<br>');
                        }

                        $purchaser_label = $p_party['party_type']['party_type_desc_' . $lang];
                    } else {
                        if ($p_party['Leg_party_entry']['party_full_name_' . $lang] == NULL) {

                            array_push($purchaser, ' ' . $p_org . ' ' . $p_brchname . '    ' . $p_fullname . ' ' . $p_address . ' ' . $p_age . '  ' . $p_occ . ' ' . $p_pan . '<br>');
                        } else {
                            array_push($purchaser, ' ' . $p_org . ' ' . $p_brchname . '   ' . $p_fllname . ' ' . $p_address . ' ' . $p_father . '' . $p_age . '   ' . $p_occ . '  ' . $p_pan . '<br>');
                        }
                        $purchaser_label = $p_party['party_type']['party_type_desc_' . $lang];
                    }
                }
            }
//pr($purchaser);exit;

            if ($seller || $purchaser) {
                $design = "<table  width=95% border=1 align=center>";
                if ($prop_party != NULL) {
                    $design .= "<tr><td colspan=3><b>" . 'Party Details' . "</b></td></tr>";
                }
                $design .= "<tr> <td width=30%> <b> " . $seller_label . "  </b></td> <td>" . implode('<br>', $seller) . "</td> </tr>"
                        . "<tr> <td> <b>" . $purchaser_label . " </b></td> <td>" . implode('<br>', $purchaser) . "</td> </tr>"
                        . "</table>"
                        . "<hr style='color: #FA9;height: 5px;'/>";
                return $design;

                // pr($design);exit;
            } else {
                return;
            }
        } catch (Exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function legacy_report($doc_token_no, $flag = 'V', $lang = 'en', $doc_lang = 'en') {
        try {

            //   pr($doc_token_no);exit;
            $this->autoRender = FALSE;
            $state_id = $this->Auth->user('state_id');
//            $doc_token_no = base64_decode($doc_token_no);

            if (is_integer((int) $doc_token_no) && is_numeric($doc_token_no)) {
                array_map(array($this, 'loadModel'), array('ReportLabel', 'Leg_generalinformation', 'Leg_identification', 'Leg_trn_valuation', 'article', 'Leg_party_entry', 'Leg_witness', 'Leg_property_details_entry', 'TrnBehavioralPatterns', 'Leg_trn_valuation_details', 'conf_reg_bool_info'));

                $rec_exist = $this->Leg_generalinformation->find('first', array('conditions' => array('token_no' => $doc_token_no)));

                if (!empty($rec_exist)) {
                    $lang = ($this->Session->read("sess_langauge")) ? $this->Session->read("sess_langauge") : $lang;
                    $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 3)));
                    $doc_lang = ($this->Session->read("doc_lang")) ? $this->Session->read("doc_lang") : $doc_lang;
                    // $lang='en';
                    $currency_sign = '&#8377;';
                    $officename = ClassRegistry::init('Leg_generalinformation')->find('all', array('fields' => array('off.office_name_en'),
                        'joins' => array(array('table' => 'ngdrstab_mst_office', 'alias' => 'off', 'conditions' => array('off.office_id=Leg_generalinformation.office_id'))), 'conditions' => array('Leg_generalinformation.token_no=' . $doc_token_no)));
                    // pr($officename);exit;
                    if (!empty($officename)) {
                        $off = $officename[0]['off']['office_name_en'];
                    } else {
                        $off = NULL;
                    }
                    $rptData = $this->Leg_generalinformation->get_general_info($lang, $doc_token_no);

                    $rptPropDtl = $this->Leg_property_details_entry->get_property_detail_list($lang, $doc_token_no, $this->Session->read('citizen_user_id'));
//pr($rptPropDtl);exit;


                    foreach ($rptPropDtl as $prpt) {

//                        $tmp_property_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $prpt['property_details_entry']['property_id'], $doc_token_no, '1', $doc_lang);
//
//                        $property_address = array();
//                        if ($tmp_property_address) {
//                            foreach ($tmp_property_address as $tmp_property_address) {
//                                array_push($property_address, $tmp_property_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_property_address['TrnBehavioralPatterns']['field_value_' . $lang]);
//                            }
//                        }

                        $property_id = $prpt['Leg_property_details_entry']['property_id'];
                        //pr($property_id);exit;
                    }
                    $serveyno = $this->Leg_trn_valuation->query("SELECT mparam.eri_attribute_name_en,param.paramter_value
                                                                from ngdrstab_trn_legacy_parameter as param
                                                                JOIN ngdrstab_mst_attribute_parameter as mparam ON mparam.attribute_id=param.paramter_id
                                                                where 
                                                                param.token_id=? and param.property_id=?", array($doc_token_no, @$property_id));

//pr($serveyno);exit;
                    //$imagedata = "img/CG_logo.jpg";
                    // $imagedata1 = $this->state_logo->find('all', array('conditions' => array('state_id' => 2)));
                    // pr($imagedata1);exit;
                    // $imagedata = "img/" . $imagedata1[0]['state_logo']['logo_path'] . "";
                    $imagedata = "img/state_logos_img/TR_logo.jpg";
                    $image = file_get_contents($imagedata);
                    $image_codes = base64_encode($image);
                    if ($flag == 'V') {
                        $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' /> ";
                    } else {
                        $img1 = "<img src='" . $imagedata . "' height='70px' width='70px' />";
                    }

                    $design = "<table  width=100%>"
                            . "<tr><td colspan=2 style='text-align:center;'>" . $img1 . "</td></tr>"
                            . "<tr><td colspan=2 style='text-align:center;'> <h3>" . $rptlabels[86] . "</h3></td></tr>"
                            . "<tr style='border-bottom:1pt solid black;'><td> <b> Date :- </b>" . date('d-m-Y h:s a') . "</td> "
                            . " <td style='text-align:right;'> <br/><b> Office Name :- </b>" . $off . " <br/> <b> Token No:-  </b> " . $doc_token_no . "</td></tr>"
                            . "</table>";

                    if (isset($app_date) && isset($app_time)) {
                        $design .= "<table width=95%><tr><td style='text-align:left;'><b> Appoinment</b> :- $app_date  Time:- $app_time </td></tr></table><br>";
                    }



                    //Sonam changes for display extra no. of pages in pre-registration docket for Jharkhand

                    if (!empty($rptData)) {
                        $rptData = $rptData[0];
                        if ($state_id != 20) {


                            $design .= "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                                    . "<table width=50% border=1 align=center>"
                                    . "<tr> <td> " . $rptlabels[88] . " </td> <td width=60%>" . $rptData['article']['article_desc_' . $lang] . "</td></tr>"
                                    . "<tr> <td>  " . $rptlabels[89] . " </td> <td>" . (($rptData['Leg_generalinformation']['exec_date']) ? date('d-M-Y', strtotime($rptData['Leg_generalinformation']['exec_date'])) : '-') . "</td> </tr>"
                                    //. "<tr> <td>  " . $rptlabels[90] . " </td> <td>" . $total_pages . "</td> </tr>"
                                    // . "<tr> <td>  " . $rptlabels[91] . " </td> <td>$currency_sign" . $this->valuation->format_money_india(number_format((float) $rptData['sd']['final_amt'], 2, '.', '')) . ".</td></tr>"
                                    . "</table>"
                                    . "<hr style='color: #FA9;height: 5px;'/>";
                        } else {
                            //$totstamduty, $totstamduty[0][0]['final_value']= Stamp Duty, $rptData['sd']['final_amt']= Grand Total with all fees without exemption.
                            // $totwithoutsd= Exemption without stamp duty.
//                            if (!empty($totstamduty)) {
//                                $total1 = $rptData['sd']['final_amt'] - $totstamduty[0][0]['final_value'] - $paidsd;
//                                $totalfee = $total1 - $totwithoutsd;
//                            } else {
//                                $total1 = $rptData['sd']['final_amt'] - $paidsd;
//                                $totalfee = $total1 - $totwithoutsd;
//                            }

                            $design .= "<style> td{padding:3px;} table{border-collapse: collapse;'}</style>"
                                    . "<table width=50% border=1 align=center>"
                                    . "<tr> <td> " . $rptlabels[88] . " </td> <td width=60%>" . $rptData['article']['article_desc_' . $lang] . "</td></tr>"
                                    . "<tr> <td>  " . $rptlabels[89] . " </td> <td>" . (($rptData['Leg_generalinformation']['exec_date']) ? date('d-M-Y', strtotime($rptData['Leg_generalinformation']['exec_date'])) : '-') . "</td> </tr>"
                                    //  . "<tr> <td>  " . $rptlabels[90] . " </td> <td>" . $total_pages . "</td> </tr>"
                                    //  . "<tr> <td>  " . $rptlabels[425] . " </td> <td>" . $stamduty . "</td> </tr>"
                                    //   . "<tr> <td>  " . $rptlabels[426] . " </td> <td>" . $paidsd . "</td> </tr>"
                                    // . "<tr> <td>  " . $rptlabels[91] . " </td> <td>$currency_sign" . $this->valuation->format_money_india(number_format((float) $totalfee, 2, '.', '')) . ".</td></tr>"
                                    . "</table>"
                                    . "<hr style='color: #FA9;height: 5px;'/>";
                        }
                    }

                    $rptData = $this->Leg_generalinformation->get_general_info($lang, $doc_token_no);
                    //pr($rptData);exit;
                    if (isset($rptData[0]['article']['article_id']) && $rptData[0]['article']['article_id'] == 63) {
                        $party_party = $this->Leg_party_entry->get_party_entry($lang, $doc_token_no);
                        pr($party_party);
                        exit;
                        if (!empty($party_party)) {
                            $design .= "<table>"
                                    . "<tr>"
                                    . "<td width=30%>" . $rptlabels[105] . " :- </td> <td>" . $party_party[0]['village']['village_name_' . $lang] . "</td>"
                                    . "</tr>"
                                    . "</table>";
                        }
                    }
                    //pr($lang);pr($doc_token_no);pr($this->Session->read('citizen_user_id'));exit;
                    $rptPropDtl = $this->Leg_property_details_entry->get_property_detail_list($lang, $doc_token_no);
                    // pr($rptPropDtl);exit;
                    if ($rptPropDtl) {
                        foreach ($rptPropDtl as $prpt) {
                            // pr($prpt);exit;
                            //pr($prpt['property_details_entry']);exit;
                            $tmp_property_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $prpt['Leg_property_details_entry']['property_id'], $doc_token_no, '1', $doc_lang);
                            // pr($tmp_property_address);exit;
                            $property_address = array();
                            if ($tmp_property_address) {
                                foreach ($tmp_property_address as $tmp_property_address) {
                                    array_push($property_address, $tmp_property_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_property_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                                }
                            }
                            $prop_boundaries = '<b>Property Boundaries</b> <br>East: ' . $prpt['Leg_property_details_entry']['boundries_east_' . $lang] . ', West: ' . $prpt['Leg_property_details_entry']['boundries_west_' . $lang] . ', South: ' . $prpt['Leg_property_details_entry']['boundries_south_' . $lang] . ', North: ' . $prpt['Leg_property_details_entry']['boundries_north_' . $lang];


                            $prop_area = $this->Leg_trn_valuation_details->get_valuation_details_cake($lang, $prpt['Leg_property_details_entry']['property_id']);
                            // pr($prop_area);exit;

                            $prop_marketvalue = $this->Leg_trn_valuation_details->get_market_value($lang, $prpt['Leg_property_details_entry']['property_id']);
                            // pr($prop_marketvalue);exit;
                            $tmp_marketvalue = array();
                            foreach ($prop_marketvalue as $parmarketval) {

                                array_push($tmp_marketvalue, $parmarketval['Leg_trn_valuation_details']['final_value']);
                                // pr($tmp_marketvalue);exit;
                            }


                            $tmp_prop_area = array();
                            foreach ($prop_area as $parea) {
                                array_push($tmp_prop_area, $parea['sub_catg']['usage_sub_catg_desc_' . $lang] . ' : ' . $parea['Leg_trn_valuation_details']['item_value'] . ' ' . $parea['unit']['unit_desc_' . $lang]);
                            }
                            $serveyno = $this->Leg_trn_valuation_details->query("SELECT mparam.eri_attribute_name_en,param.paramter_value
                                                                from ngdrstab_trn_legacy_parameter as param
                                                                JOIN ngdrstab_mst_attribute_parameter as mparam ON mparam.attribute_id=param.paramter_id
                                                                where 
                                                                param.token_id=? and param.property_id=?", array($doc_token_no, @$prpt['Leg_property_details_entry']['property_id']));
///pr($serveyno);exit;

                            $design .= 'Property Id: <b>' . $prpt['Leg_property_details_entry']['property_id'] . "</b><table  width=95% border=1 align=center>"
                                    . "<tr> <td width=30%>" . $rptlabels[105] . " </td> <td>" . $prpt['village']['village_name_' . $lang] . ", " . $prpt['taluka']['taluka_name_' . $lang] . ", " . $prpt['district']['district_name_' . $lang] . "</td> </tr>"
                                    . "<tr> <td> " . $rptlabels[98] . "</td> <td>" . $prop_boundaries . " </td> </tr>"
                                    . "<tr> <td> " . $rptlabels[99] . "</td> <td>" . implode(', ', $tmp_prop_area) . "</td></tr>";
                            // . "<tr> <td> " . $rptlabels[99] . "</td> <td>" . $tmp_prop_area . "</td></tr>";
                            foreach ($serveyno as $attribute) {
                                $design .= "<tr> <td>" . $rptlabels[100] . "</td> <td>" . implode(', ', $property_address) . " <b>" . $attribute[0]['eri_attribute_name_en'] . "</b> - " . $attribute[0]['paramter_value'] . "</td> </tr>";
                            }
                            $design .= "<tr> <td>" . $rptlabels[101] . " </td> <td> Rs." . implode(', ', $tmp_marketvalue) . "</td></tr>"
                                    //. "<tr> <td> " . $rptlabels[102] . " </td> <td> Rs." . $cons_amt . "</td> </tr>"
                                    . "</table> <hr/>";

                            //----------------------------------------------------for party Details----------------------------------------------------------------------
                            //$design .= $this->get_party_details_pre_reg_doc($lang, $doc_token_no, $prpt['property_details_entry']['property_id']);
                        }
                    } else {
                        // $design .= $this->get_party_details_pre_reg_doc($lang, $doc_token_no);
                    }
                    $design .= $this->get_party_details_pre_reg_doc($lang, $doc_token_no);
//pr($design);exit;

                    $witness = array();
                    $witness_detail = $this->Leg_witness->get_witness($lang, $doc_token_no);
//pr($witness_detail);exit;
                    if ($witness_detail) {
                        foreach ($witness_detail as $wit) {
                            $tmp_witness_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $wit['Leg_witness']['witness_id'], $doc_token_no, '3', $doc_lang);

                            $witness_address = array();
                            foreach ($tmp_witness_address as $tmp_address) {
                                array_push($witness_address, $tmp_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                            }
                            if ($wit['Leg_witness']['witness_full_name_' . $lang] == NULL) {
                                array_push($witness, '<b>' . $wit['saluation']['salutation_desc_' . $lang] . ' ' . $wit['Leg_witness']['fname_' . $lang] . ' ' . $wit['Leg_witness']['mname_' . $lang] . ' ' . $wit['Leg_witness']['lname_' . $lang] . '</b>, ' . implode(',', $witness_address) . ', ' . $wit['village']['village_name_' . $lang] . ', ' . $wit['taluka']['taluka_name_' . $lang] . ', ' . $wit['district']['district_name_' . $lang]);
                            } else {
                                array_push($witness, '<b>' . $wit['saluation']['salutation_desc_' . $lang] . ' ' . $wit['Leg_witness']['witness_full_name_' . $lang] . ' ' . '</b>, ' . implode(',', $witness_address) . ', ' . $wit['village']['village_name_' . $lang] . ', ' . $wit['taluka']['taluka_name_' . $lang] . ', ' . $wit['district']['district_name_' . $lang]);
                            }
                        }


                        //Party,Withness Details
                        $design .= '<table width=95% border=1 align=center>'
                                . "<tr> <td width=30%><b> " . $rptlabels[92] . "</b></td> <td>" . implode('<br>', $witness) . "</td> </tr>"
                                . "</table>"
                                . "<hr style='color: #FA9;height: 5px;'/>";
                    }


///////////////////////////////
                    $identification = array();
//        $prop_witness = $this->witness->get_witness($doc_lang, $doc_token_no);//not to use
                    $identifier_detail = $this->Leg_identification->get_identification($lang, $doc_token_no);

                    if ($identifier_detail) {
                        foreach ($identifier_detail as $identifier) {
                            $tmp_identifier_address = $this->TrnBehavioralPatterns->get_pattern_detail($lang, $identifier['Leg_identification']['identification_id'], $doc_token_no, '5', $doc_lang);
                            $identifier_address = array();
                            foreach ($tmp_identifier_address as $tmp_address) {
                                array_push($identifier_address, $tmp_address['pattern']['pattern_desc_' . $lang] . ' - ' . $tmp_address['TrnBehavioralPatterns']['field_value_' . $lang]);
                            }
                            if ($identifier['Leg_identification']['identification_full_name_' . $lang] == NULL) {
                                array_push($identification, '<b>' . $identifier['saluation']['salutation_desc_' . $lang] . ' ' . $identifier['Leg_identification']['fname_' . $lang] . ' ' . $identifier['Leg_identification']['mname_' . $lang] . ' ' . $identifier['Leg_identification']['lname_' . $lang] . '</b>, ' . implode(',', $identifier_address) . ', ' . $identifier['village']['village_name_' . $lang] . ', ' . $identifier['taluka']['taluka_name_' . $lang] . ', ' . $identifier['district']['district_name_' . $lang]);
                            } else {
                                array_push($identification, '<b>' . $identifier['saluation']['salutation_desc_' . $lang] . ' ' . $identifier['Leg_identification']['identification_full_name_' . $lang] . ' ' . '</b>, ' . implode(',', $identifier_address) . ', ' . $identifier['village']['village_name_' . $lang] . ', ' . $identifier['taluka']['taluka_name_' . $lang] . ', ' . $identifier['district']['district_name_' . $lang]);
                            }
                        }
                        //pr($identification);exit;
                        //Party,Withness Details
                        $design .= '<table width=95% border=1 align=center>'
                                . "<tr> <td width=30%><b>" . $rptlabels[93] . "</b></td> <td>" . implode('<br>', $identification) . "</td> </tr>"
                                . "</table>"
                                . "<hr style='color: #FA9;height: 5px;'/>";
                    }






                    $rpt_feedata = $this->Leg_generalinformation->Query("select fee_item_desc_$lang,final_value from  ngdrstab_trn_legacy_fee_calculation_detail
inner join ngdrstab_mst_article_fee_items on ngdrstab_mst_article_fee_items.fee_item_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_item_id
inner join ngdrstab_trn_legacy_fee_calculation on  ngdrstab_trn_legacy_fee_calculation.fee_calc_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_calc_id
where token_no=" . $doc_token_no . " order by ngdrstab_trn_legacy_fee_calculation_detail.fee_item_id");

//pr($rpt_feedata);exit;  

                    $count = 0;
                    $sequence_id = 0;
                    $design .= "<table align=center width=95% border=1>";
                    if ($rpt_feedata != NULL) {
                        $design .= "<tr><td colspan=3><b>" . 'Fee Details' . "</b></td></tr>";
                    }
                    foreach ($rpt_feedata as $rpt_feedata) {
                        $sequence_id = $sequence_id + 1;

                        $design .= "<tr><td>" . $sequence_id . "</td> <td>" . $rpt_feedata[0]['fee_item_desc_' . $lang] . "</td> <td>" . $rpt_feedata[0]['final_value'] . "</td></tr>";
                        $count = $count + $rpt_feedata[0]['final_value'];
                    }
                    if ($rpt_feedata != NULL) {
                        $design .= "<tr><td>" . '' . "</td><td><b>" . 'Total' . "</b></td> <td><b>" . $count . "</b></td></tr>";
                    } else {
                        
                    }
                    $design .= "</table><br>";


//goshvara
//                  ============================== Payment  =========================================
//                $design.=$this->requestAction(array('controller' => 'Reports', 'action' => 'rpt_reg_summary1', $doc_token_no, 'V'));
//                ==============================  Stamp Duty =========================================
                    //   $design .= $this->requestAction(array('controller' => 'Fees', 'action' => 'view_sd_calc', $doc_token_no, 0, $lang));
                    //  $design .= $this->requestAction(array('controller' => 'Fees', 'action' => 'view_exemption', $doc_token_no, $lang));
//                    $design.="<br/><br/><table width=95% border=1 align=center>"
//                            . "<tr><td colspan=2> " . $rptlabels[95] . " </td> </tr>"
//                            . "<tr><td colspan=2 style=height:25px;></td></tr>"
//                            . "<tr><td align=center> <b>(" . $rptlabels[96] . " ) </b></td> <td align=center><b>(" . $rptlabels[97] . ")</b></td> </tr>"
//                            . "</table>";
                    //officer sign
                    $design .= "<br><br>";
                    $design .= "<table style=' border: 1px solid black;' width='100%'>"
                            . "<tr><td colspan=3 style='text-align:center;'><h3><strong><u><em>Declaration To Be Made In The Data Entry Summary Sheet Print Out</em></u></strong></h3></td></tr>"
                            . "<tr><td colspan=3 style='padding-left:5px; padding-right:5px;' align='justify'>All the entries made, have been verified by me and are found same as the entries of the document presented.</td></tr>"
                            . "<tr><td colspan=3 style='padding-left:5px; padding-right:5px;' align='justify'>Disclaimer : I hereby declare that all the contents of uploaded document and the original document are exactly same, "
                            . "and all the information provided by me are true to itself. The detail of property's holding number has been verified by meat the time of entry "
                            . "through alert generated by the system. I am satisfied with the verification and hence proceeding further for registration after seeing the alert.</td></tr><br><br><br>"
                            . "<tr height='80px'>"
                            . "<td valign='bottom'><b>Deed Writer / Advocate </td>"
                            . "<td valign='bottom'><b> Vendee/Claimant : </b> </td>"
                            . "<td valign='bottom'><b>Vendor / Executant</b></td>"
                            . "</tr>"
                            . "</table>";

                    $design .= "<br/><br/><table width=95% border=0 align=center>"
                            . "<tr><td colspan=2 style=height:25px;></td></tr>"
                            . "<tr><td style='text-align:right;'> <b>( Party Signature ) </b></td></tr>"
                            . "</table>";
                    if ($flag == 'D') {
                        $this->create_pdf($design, "doc_" . $doc_token_no, 'A4-P');
                    } else if ($flag == 'V') {
                        return $design;
                    } else {
                        return 'invalid input';
                    }
                } else {
                    return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
                }
            } else {
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        } catch (Exception $ex) {
            //pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
