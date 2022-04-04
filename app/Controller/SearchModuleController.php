<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class SearchModuleController extends AppController {

    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha', 'Cookie',
        'Session',
    );
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {

        $this->loadModel('language');
        $this->Session->renew();
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
//        $this->Security->unlockedActions = array('srchrec','searchmodule');
        $this->Auth->allow('searchmodule', 'srchrec','viewrptdetails');
        $laug = $this->Session->read("sess_langauge");
        
           if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    /*
    public function searchmodule($csrftoken = Null) {
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../error.html');
                    exit;
                }
            }
            $this->loadModel('office');
         //    $this->loadModel('office');
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('genernal_info');
        $this->loadModel('payment');
            $laug = $this->Session->read("sess_langauge");
            $csrft = $this->Session->read("csrftoken");
            //pr($csrft);
            $this->set('laug', $laug);
            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC')));
            $this->set('office', $office);
            $fieldlist = array();
            $fieldlist['doc_reg_no']['text'] = 'is_required,is_numberdashslash';
            $fieldlist['doc_reg_date']['text'] = 'is_required';
            $fieldlist['office_id']['select'] = 'is_select_req';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                             
                 $errarr = $this->validatedata($this->request->data['searchform'], $fieldlist);
        // pr($_POST);exit;
                 //$_POST=$this->request->data['searchform'];
                        if ($this->ValidationError($errarr)) {
                               $laug = $this->Session->read("sess_langauge");
                    $csrft = $this->Session->read("csrftoken");
        $office_id = $this->request->data['searchform']['office_id'];
        $docregno = $this->request->data['searchform']['doc_reg_no'];
        $docregdate = $this->request->data['searchform']['doc_reg_date'];
        $docsearchtype = $this->request->data['docno'];
        $lang = 'en';
        $totalamtpaid = 0;

        if ($docsearchtype == 'O') {
            $this->ApplicationSubmitted->setDataSource('ngprs');
            $this->office->setDataSource('ngprs');
            $this->payment->setDataSource('ngprs');
        } else {
            $this->ApplicationSubmitted->setDataSource('default');
            $this->office->setDataSource('default');
            $this->payment->setDataSource('default');
        }

        $myDate = date('Y-m-d', strtotime($docregdate));
        //echo $myDate;
        $office_name_slct = $this->office->find('first', array('fields' => array('office_id', 'office_name_' . $laug), 'conditions' => array('office.office_id' => $office_id)));
        $office_name = $office_name_slct['office']['office_name_' . $laug];

        $info_taken = $this->ApplicationSubmitted->srchdetails($docregno, $office_id, $myDate);
        //  pr($info_taken);exit;



        if (sizeof($info_taken) > 0) {
            $articlenm = $info_taken[0][0]['article_desc_en'];
            $amt_paid = $info_taken[0][0]['amt_paid'];
            $token_no = $info_taken[0][0]['token_no'];
            $article_id = $info_taken[0][0]['article_id'];

            if ($info_taken[0][0]['article_desc_en'] == '')
                $articlenm = 'Not available';
            if ($info_taken[0][0]['amt_paid'] == '')
                $amt_paid = 'Not available';

            if ($info_taken[0][0]['token_no'] == '') {
                $totalamtpaid = 'Not available';
            } else {
                $paidfee = $this->payment->stampduty_fee_details($token_no, $lang, $article_id, $payment_mode_id = Null);
                if (sizeof($paidfee) > 0) {
                    for ($j = 0; $j < sizeof($paidfee); $j++) {
                        $totalamtpaid = $totalamtpaid + $paidfee[$j][0]['totalsd'];
                    }
                } else {
                    $totalamtpaid = 'Not available';
                }
                //echo 'amt:'.$totalamtpaid;
                //pr($paidfee);exit;
                //$totalsd1
            }
        } else {
            $articlenm = 'Not available';
            $amt_paid = 'Not available';
            $msg = 'This application is not submitted to office';
        }



        $party_info = $this->ApplicationSubmitted->srchpartydetails($docregno, $office_id, $myDate);

        //pr($party_info);
        //echo 'aray size:'.sizeof($party_info);
        //pr($info_taken);

        $validated_field = array('office_id' => $office_id, 'docregno' => $docregno, 'docregdate' => $docregdate, 'office_name' => $office_name, 'articlenm' => $articlenm, 'amt_paid' => $totalamtpaid);

        //$validated_field=$office_id;
        //echo json_encode($validated_field);

        if (sizeof($info_taken) > 0) {
            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : ' . $docregno . ' </b></td><td><b>Document Registration Date : ' . $docregdate . ' </b></td>		<td ><b>Office : ' . $office_name . ' </b></td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;"><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article</b></td><td align="left" width="25%">' . $articlenm . '</td>	<td align="center" width="25%"><b>Fee</b></td><td align="left" width="25%">Rs.' . $totalamtpaid . '/- </td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">';

            for ($ii = 0; $ii < sizeof($party_info); $ii++) {
                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_type_desc_en'] . '</td><td align="center" width="25%"><b>Party Name</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_fname_en'] . ' ' . $party_info[$ii][0]['party_mname_en'] . ' ' . $party_info[$ii][0]['party_lname_en'] . '</td></tr>';
            }
            $dispcolf = $dispcolf . '</tbody></table></div></div>';
        } else {
            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><center><b>' . $msg . '</b></center></div></div>';
        }
        $this->set('dispcolf',$dispcolf) ;
       
                        }
                        else{
                       
                            
                            
                            
                        }
                
                
                
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    
    */
    
    public function searchmodule($csrftoken = Null) {
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../error.html');
                    exit;
                }
            }
            $this->loadModel('office');
         //    $this->loadModel('office');
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('genernal_info');
        $this->loadModel('payment');
        $this->loadModel('LegacyData');
        $this->loadModel('LegacyDataMain');
        $this->loadModel('LegacyDataProperty');
        $this->loadModel('LegacyDataPropertyDetails');
        $this->loadModel('LegacyDataPropertyLandDetails');
        $this->loadModel('LegacyDataParty');
        $this->loadModel('LegacyDataPayment');
            
            $laug = $this->Session->read("sess_langauge");
            $csrft = $this->Session->read("csrftoken");
            //pr($csrft);
            $this->set('laug', $laug);
            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'order' => array('office_name_' . $laug => 'ASC')));
            $this->set('office', $office);
            $fieldlist = array();
            $fieldlist['doc_reg_no']['text'] = 'is_required,is_numberdashslash';
            $fieldlist['doc_reg_date']['text'] = 'is_required';
            $fieldlist['office_id']['select'] = 'is_select_req';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                             
                 $errarr = $this->validatedata($this->request->data['searchform'], $fieldlist);
        // pr($_POST);exit;
                 //$_POST=$this->request->data['searchform'];
                if ($this->ValidationError($errarr)) {
                    
                    
                    $laug = $this->Session->read("sess_langauge");
                    $csrft = $this->Session->read("csrftoken");
                    $office_id = $this->request->data['searchform']['office_id'];
                    $docregno = $this->request->data['searchform']['doc_reg_no'];
                    $docregdate = $this->request->data['searchform']['doc_reg_date'];
                    $docsearchtype = $this->request->data['docno'];
                    $lang = 'en';
                    $totalamtpaid = 0;
                    $myDate = date('Y-m-d', strtotime($docregdate));
                    $office_name_slct = $this->office->find('first', array('fields' => array('office_id', 'office_name_' . $laug), 'conditions' => array('office.office_id' => $office_id)));
                    $office_name = $office_name_slct['office']['office_name_' . $laug];
                        
                    /*if ($docsearchtype == 'O') {
                        $this->ApplicationSubmitted->setDataSource('ngprs');
                        $this->office->setDataSource('ngprs');
                        $this->payment->setDataSource('ngprs');
                    } else {
                        $this->ApplicationSubmitted->setDataSource('default');
                        $this->office->setDataSource('default');
                        $this->payment->setDataSource('default');
                    }*/
        
                    if ($docsearchtype == 'O') {
                       /* $infnew=$this->LegacyData->query("select * from ngdrstab_trn_legacy_data where doc_reg_no='$docregno' and doc_reg_date='$myDate' and office_id='$office_id'");
                        //pr($infnew);
                         if (sizeof($infnew) > 0) {
                        $articlenm= $infnew[0][0]['article_desc_en'];
                        $totalamtpaid= $infnew[0][0]['market_value'];
                        
                       
                            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="0" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: black;"><td colspan="3" align="center"><b><i>Old Uploaded Data</i></b></tr><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : ' . $docregno . ' </b></td><td><b>Document Registration Date : ' . $docregdate . ' </b></td>		<td ><b>Office : ' . $office_name . ' </b></td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;"><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article</b></td><td align="left" width="25%">' . $articlenm . '</td>	<td align="center" width="25%"><b>Fee</b></td><td align="left" width="25%">Rs.' . $totalamtpaid . '/- </td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">';

                            for ($ii = 0; $ii < sizeof($infnew); $ii++) {
                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="left" width="25%">' . $infnew[$ii][0]['party1_type_desc_en'] . '</td><td align="center" width="25%"><b>Party Name</b></td><td align="left" width="25%">' . $infnew[$ii][0]['party1_full_name_en']. '</td></tr>';
                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="left" width="25%">' . $infnew[$ii][0]['party2_type_desc_en'] . '</td><td align="center" width="25%"><b>Party Name</b></td><td align="left" width="25%">' . $infnew[$ii][0]['party2_full_name_en']. '</td></tr>';
                            }
                            $dispcolf = $dispcolf . '</tbody></table></div></div>';
                        } else {
                            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><center><b>Record not available</b></center></div></div>';
                        }
                        $this->set('dispcolf',$dispcolf) ;*/
                        
                        $infnew=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main where doc_reg_no='$docregno' and doc_reg_date='$myDate' and office_id='$office_id'");
                       // pr($infnew);
                        $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:45vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="0" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: black;"><td colspan="3" align="center"><b><i>Old Uploaded Data</i></b></tr><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : ' . $docregno . ' </b></td><td><b>Document Registration Date : ' . $docregdate . ' </b></td>		<td ><b>Office : ' . $office_name . ' </b></td></tr></table>';
                        if (sizeof($infnew) > 0) {
                            for($r=0;$r<sizeof($infnew);$r++)
                            {
                                $reference_sr_no=$infnew[$r][0]['reference_sr_no'];
                                $dispcolf = $dispcolf . '<br><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">';
                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article Name : </b></td><td align="center" width="25%">'.$infnew[$r][0]['article_desc_en'].'</td><td align="center" width="25%"><b>Office District Name : </b></td><td align="center" width="25%">'.$infnew[$r][0]['office_district_name_en'].'</td></tr>';
                                $data2=$this->LegacyDataProperty->query("select * from ngdrstab_trn_legacy_data_property where doc_reg_no='$docregno' and doc_reg_date='$myDate' and reference_sr_no='$reference_sr_no'");
                                //pr($data2);   
                                if(sizeof($data2) > 0)
                                {
                                    for($mm=0;$mm<sizeof($data2);$mm++)
                                    {
                                        $developed_land_types_desc_en=$data2[$mm][0]['developed_land_types_desc_en'];
                                        $district_name_en=$data2[$mm][0]['district_name_en'];
                                        $taluka_name_en=$data2[$mm][0]['taluka_name_en'];
                                        $village_name_en=$data2[$mm][0]['village_name_en'];
                                        $addr=$district_name_en.', '.$taluka_name_en.', '.$village_name_en;
                                        $boundries_east_en=$data2[$mm][0]['boundries_east_en'];
                                        $boundries_west_en=$data2[$mm][0]['boundries_west_en'];
                                        $boundries_south_en=$data2[$mm][0]['boundries_south_en'];
                                        $boundries_north_en=$data2[$mm][0]['boundries_north_en'];
                                        $property_sr_no=$data2[$mm][0]['property_sr_no'];
                                        
                                        
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%" colspan="4"><font color=red><b>Property :  '.$property_sr_no.'</b></font></td></tr>';
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Land Type : </b></td><td align="center" width="25%">'.$developed_land_types_desc_en.'</td><td align="center" width="25%"><b>Property Address : </b></td><td align="center" width="25%">'.$addr.'</td></tr>';
                                        
                                
                                        $data3=$this->LegacyDataPropertyDetails->query("select * from ngdrstab_trn_legacy_data_property_details where doc_reg_no='$docregno' and doc_reg_date='$myDate' and reference_sr_no='$reference_sr_no' and property_sr_no=$property_sr_no");
                                        //pr($data2);   
                                        if(sizeof($data3) > 0)
                                        {
                                            for($nn=0;$nn<sizeof($data3);$nn++)
                                            {
                                                $usage_main_catg_desc_en=$data3[$nn][0]['usage_main_catg_desc_en'];
                                                $usage_sub_catg_desc_en=$data3[$nn][0]['usage_sub_catg_desc_en'];
                                                $item_value=$data3[$nn][0]['item_value'];
                                                $unit_desc_en=$data3[$nn][0]['unit_desc_en'];
                                                $market_value=$data3[$nn][0]['market_value'];
                                                $cons_amt=$data3[$nn][0]['cons_amt'];
                                                $property_land_sr_no=$data3[$nn][0]['property_land_sr_no'];
                                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Usage Main Category : </b></td><td align="center" width="25%">'.$usage_main_catg_desc_en.'</td><td align="center" width="25%"><b>Usage Sub Category : </b></td><td align="center" width="25%">'.$usage_sub_catg_desc_en.'</td></tr>';
                                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Area : </b></td><td align="center" width="25%">'.$item_value.'  '.$unit_desc_en.'</td><td align="center" width="25%"><b>Market Value (in Rs.) : </b></td><td align="center" width="25%">'.$market_value.'</td></tr>';

                                                $data4=$this->LegacyDataPropertyLandDetails->query("select * from ngdrstab_trn_legacy_data_property_land_details where doc_reg_no='$docregno' and doc_reg_date='$myDate' and reference_sr_no='$reference_sr_no' and property_sr_no=$property_sr_no and property_land_sr_no=$property_land_sr_no");
                                                if(sizeof($data4) > 0)
                                                {
                                                    for($tt=0;$tt<sizeof($data4);$tt++)
                                                    {
                                                        $eri_attribute_name=$data4[$tt][0]['eri_attribute_name'];
                                                        $paramter_value=$data4[$tt][0]['paramter_value'];
                                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="right" width="25%" colspan="2"><b>'.$eri_attribute_name.'</b></td><td align="left" width="25%" colspan="2">'.$paramter_value.'</td></tr>';
                                                        
                                                    }
                                                    
                                                }
                                                   
                                            }
                                        }
                                    }
                                }
                                
                                $data5=$this->LegacyDataParty->query("select * from ngdrstab_trn_legacy_data_party where doc_reg_no='$docregno' and doc_reg_date='$myDate' and reference_sr_no='$reference_sr_no'");
                                //pr($data5);
                                if(sizeof($data5) > 0)
                                {
                                    for($bb=0;$bb<sizeof($data5);$bb++)
                                    {
                                        $party_sr_no=$data5[$bb][0]['party_sr_no'];
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%" colspan="4"><font color=red><b>Party '.$party_sr_no.' Details </b></font></td></tr>';
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type : </b></td><td align="center" width="25%">'.$data5[$bb][0]['party_type_desc_en'].'</td><td align="center" width="25%"><b>Party Category : </b></td><td align="center" width="25%">'.$data5[$bb][0]['category_name_en'].'</td></tr>';
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Full Name : </b></td><td align="center" width="25%">'.$data5[$bb][0]['party_full_name_en'].'</td><td align="center" width="25%"><b>Party Father Full Name : </b></td><td align="center" width="25%">'.$data5[$bb][0]['father_full_name_en'].'</td></tr>';
                                    }
                                }
                                
                                $data6=$this->LegacyDataPayment->query("select * from ngdrstab_trn_legacy_data_payment where doc_reg_no='$docregno' and doc_reg_date='$myDate' and reference_sr_no='$reference_sr_no'");
                                if(sizeof($data6) > 0)
                                {
                                    $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%" colspan="4"><font color=red><b> Payment Details </b></font></td></tr>';
                                    for($gg=0;$gg<sizeof($data6);$gg++)
                                    {
                                        $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="right" width="25%" colspan="2">'.$data6[$gg][0]['fee_item_desc_en'].'</td><td align="left" width="25%" colspan="2">'.$data6[$gg][0]['final_value'].' Rs.</td></tr>';
                                    }
                                }
                                $dispcolf = $dispcolf . '</tbody></table>';
                            }
                        }
                        $dispcolf = $dispcolf . '</div></div>';
                        $this->set('dispcolf',$dispcolf) ;
                    }
                    else {
                        
                        //echo $myDate;
                       

                        $info_taken = $this->ApplicationSubmitted->srchdetails($docregno, $office_id, $myDate);
                        //  pr($info_taken);exit;



                        if (sizeof($info_taken) > 0) {
                            $articlenm = $info_taken[0][0]['article_desc_en'];
                            $amt_paid = $info_taken[0][0]['amt_paid'];
                            $token_no = $info_taken[0][0]['token_no'];
                            $article_id = $info_taken[0][0]['article_id'];

                            if ($info_taken[0][0]['article_desc_en'] == '')
                                $articlenm = 'Not available';
                            if ($info_taken[0][0]['amt_paid'] == '')
                                $amt_paid = 'Not available';

                            if ($info_taken[0][0]['token_no'] == '') {
                                $totalamtpaid = 'Not available';
                            } else {
                                $paidfee = $this->payment->stampduty_fee_details($token_no, $lang, $article_id, $payment_mode_id = Null);
                                if (sizeof($paidfee) > 0) {
                                    for ($j = 0; $j < sizeof($paidfee); $j++) {
                                        $totalamtpaid = $totalamtpaid + $paidfee[$j][0]['totalsd'];
                                    }
                                } else {
                                    $totalamtpaid = 'Not available';
                                }
                                //echo 'amt:'.$totalamtpaid;
                                //pr($paidfee);exit;
                                //$totalsd1
                            }
                        } else {
                            $articlenm = 'Not available';
                            $amt_paid = 'Not available';
                            $msg = 'This application is not submitted to office';
                        }



                        $party_info = $this->ApplicationSubmitted->srchpartydetails($docregno, $office_id, $myDate);

                        //pr($party_info);
                        //echo 'aray size:'.sizeof($party_info);
                        //pr($info_taken);

                        $validated_field = array('office_id' => $office_id, 'docregno' => $docregno, 'docregdate' => $docregdate, 'office_name' => $office_name, 'articlenm' => $articlenm, 'amt_paid' => $totalamtpaid);

                        //$validated_field=$office_id;
                        //echo json_encode($validated_field);

                        if (sizeof($info_taken) > 0) {
                            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: black;"><td colspan="3" align="center"><b><i>Current Data</i></b></tr><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : ' . $docregno . ' </b></td><td><b>Document Registration Date : ' . $docregdate . ' </b></td>		<td ><b>Office : ' . $office_name . ' </b></td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;"><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article</b></td><td align="left" width="25%">' . $articlenm . '</td>	<td align="center" width="25%"><b>Fee</b></td><td align="left" width="25%">Rs.' . $totalamtpaid . '/- </td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">';

                            for ($ii = 0; $ii < sizeof($party_info); $ii++) {
                                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_type_desc_en'] . '</td><td align="center" width="25%"><b>Party Name</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_fname_en'] . ' ' . $party_info[$ii][0]['party_mname_en'] . ' ' . $party_info[$ii][0]['party_lname_en'] . '</td></tr>';
                            }
                            $dispcolf = $dispcolf . '</tbody></table></div></div>';
                        } else {
                            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><center><b>' . $msg . '</b></center></div></div>';
                        }
                        $this->set('dispcolf',$dispcolf) ;
                    }
        
        
        
                        }
                        else{
                       
                            
                            
                            
                        }
                
                
                        
                        
                        
                
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function cer_receipt(){
        try {
            $this->autoRender = FALSE;
            $this->loadModel('office');
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('genernal_info');
            $this->loadModel('payment');
            $this->loadModel('LegacyData');
            $this->loadModel('LegacyDataMain');
            $this->loadModel('LegacyDataProperty');
            $this->loadModel('LegacyDataPropertyDetails');
            $this->loadModel('LegacyDataPropertyLandDetails');
            $this->loadModel('LegacyDataParty');
            $this->loadModel('LegacyDataPayment');
			//echo doc_reg_no;
			$doc_reg_no = $_POST['doc_reg_no'];
            $laug = $this->Session->read("sess_langauge");
            $csrft = $this->Session->read("csrftoken");
            $officeid = $this->Auth->User("office_id");
            //$officename = $this->office->query("select office_name_$laug from ngdrstab_mst_office where office_id = $officeid");
            //$officename = $officename[0][0]['office_name_' . $laug];
            
			$application_id = $this->Session->read("application_id");
            $searcherdata = $this->office->query("select * from ngdrstab_trn_searcher_datails where application_id = $application_id");
			$searcherdata = $searcherdata[0][0];
           // $imagedata = "img/state_logos_img/JH_logo.png";
            $imagedata = "img/state_logos_img/4_Punjab.jpg";
            $image = file_get_contents($imagedata);
            $image_codes = base64_encode($image);
            $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' align='middle' /> ";
            $application_date = date('d/m/Y', strtotime($searcherdata['application_date']));
            //$application_date='06/05/2019';
            $html_design = "";
            
			/*$feedesc='';
			$feeitem = $this->LegacyDataPayment->query("select * from ngdrstab_trn_legacy_data_payment_final where doc_reg_no='$doc_reg_no'");
			//pr($feeitem); 
			for($ii=0;$ii<sizeof($feeitem);$ii++){
				if($ii==0)
					$feedesc.=$feeitem[$ii][0]['fee_item_desc_en'];
				else
					$feedesc.=' ,'.$feeitem[$ii][0]['fee_item_desc_en'];
			}*/
			
			$feeitem = $this->office->query("select * from ngdrstab_mst_article_fee_items where fee_item_id=32");
			$feedesc = null;
            $fee = 0;
            if (!empty($feeitem)) {
                $feedesc = $feeitem[0][0]['fee_item_desc_en'];
                $fee = $feeitem[0][0]['fix_amount'];
            }
			$years_set=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main_final where doc_reg_no='$doc_reg_no'");
			
            $officename='Office Name';
            //$searcherdata['applicant_name'];$searcherdata['application_id'] 
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
                    . "<tr><td><b>Fee For:</b></td><td colspan=3 align=left><b>" . $feedesc . "</b></td></tr>"
                    . "<tr><td><b>Years:</b></td><td colspan=3 align=left><b>" . $years_set[0][0]['doc_processing_year']. "</b></td></tr>"
                    . "<tr><td><b>Fee Amount:</b></td><td colspan=3 align=left><b>" . $fee . "</b></td></tr>"
                    . "<tr><td><b>GRN No.</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>CIN No.</b></td><td colspan=3 align=left></td></tr>"
                    . "<tr><td><b>Pay Status</b></td><td colspan=3 align=left></td></tr>"
                    . "</table><BR><BR>"
                    . "<h3 align=right style='padding: 35px;'>Registration Officer</h3>"
                    . "</div>";
            
            //echo $html_design;
            $downloaddeed = "";
           //$downloaddeed .= "<a href=../../../../GAWebService/gras_payment_entry target='_blank' class='btn btn-primary'>Make Payment</a>";
		    $downloaddeed .= "<a href='" . $this->webroot . "JHWebService/gras_payment_entry' target='_blank' class='btn btn-primary'>Make Payment</a>";
            $downloaddeed .= "<a href='" . $this->webroot . "GASearch/payment_verification' target='_blank' class='btn btn-primary'>Verify Payment</a>";
		   /*<a href='/Search/payment_verification' target='_blank' class='btn btn-primary'>Verify Payment</a>";*/
		   
           $resultarray = array('html_design' => $html_design,
                                    'deed' => $downloaddeed);
            echo json_encode($resultarray);
			//echo json_encode($html_design);
            exit;
             //echo json_encode($html_design);
            
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }
    
    public function nonem_comb_cer(){
         try {
            $this->autoRender = FALSE;
            $this->loadModel('office');
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('genernal_info');
            $this->loadModel('payment');
            $this->loadModel('LegacyData');
            $this->loadModel('LegacyDataMain');
            $this->loadModel('LegacyDataProperty');
            $this->loadModel('LegacyDataPropertyDetails');
            $this->loadModel('LegacyDataPropertyLandDetails');
            $this->loadModel('LegacyDataParty');
            $this->loadModel('LegacyDataPayment');
            $this->loadModel('ReportLabel');
            
            $lang = $this->Session->read("sess_langauge");
            $csrft = $this->Session->read("csrftoken");
            $rptlabels = $this->ReportLabel->find('list', array('fields' => array('label_id', 'label_desc_' . $lang), 'conditions' => array('report_id' => 42)));
            $imagedata = "img/state_logos_img/31_goa_logo.jpg";
            $image = file_get_contents($imagedata);
            $image_codes = base64_encode($image);
            $img1 = "<img src='data:image/jpg;charset=utf-8;base64," . $image_codes . "' height='70px' width='70px' align='left' /> ";

            $html_design = "<div align=left>" . $img1 . "<h2 align=center style='color:#9C6F7A';> " . $rptlabels[436] . "  </h2></div><br>";
            
            return $html_design;
            
            } catch (Exception $ex) {
               pr($ex);
               exit;
           }
        
    }
    
    
    
    function srchrec() {
        
        
        
        $this->loadModel('office');
        $this->loadModel('ApplicationSubmitted');
        $this->loadModel('genernal_info');
        $this->loadModel('payment');
        
   $fieldlist = array();
            $fieldlist['docregno']['text'] = 'is_required,is_numberdashslash';
            $fieldlist['docregdate']['text'] = 'is_required';
            $fieldlist['office_id']['select'] = 'is_select_req';

            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            
             $errarr = $this->validatedata($_POST, $fieldlist);
             //pr($_POST);
                        if ($this->ValidationError($errarr)) {
                               $laug = $this->Session->read("sess_langauge");
        $csrft = $this->Session->read("csrftoken");
        $office_id = $_POST['office_id'];
        $docregno = $_POST['docregno'];
        $docregdate = $_POST['docregdate'];
        $docsearchtype = $_POST['docsearchtype'];
        $lang = 'en';
        $totalamtpaid = 0;

        if ($docsearchtype == 'O') {
            $this->ApplicationSubmitted->setDataSource('ngprs');
            $this->office->setDataSource('ngprs');
            $this->payment->setDataSource('ngprs');
        } else {
            $this->ApplicationSubmitted->setDataSource('ngdrs_search');
            $this->office->setDataSource('ngdrs_search');
            $this->payment->setDataSource('ngdrs_search');
        }

        $myDate = date('Y-m-d', strtotime($docregdate));
        //echo $myDate;
        $office_name_slct = $this->office->find('first', array('fields' => array('office_id', 'office_name_' . $laug), 'conditions' => array('office.office_id' => $office_id)));
        $office_name = $office_name_slct['office']['office_name_' . $laug];

        $info_taken = $this->ApplicationSubmitted->srchdetails($docregno, $office_id, $myDate);
        //  pr($info_taken);exit;



        if (sizeof($info_taken) > 0) {
            $articlenm = $info_taken[0][0]['article_desc_en'];
            $amt_paid = $info_taken[0][0]['amt_paid'];
            $token_no = $info_taken[0][0]['token_no'];
            $article_id = $info_taken[0][0]['article_id'];

            if ($info_taken[0][0]['article_desc_en'] == '')
                $articlenm = 'Not available';
            if ($info_taken[0][0]['amt_paid'] == '')
                $amt_paid = 'Not available';

            if ($info_taken[0][0]['token_no'] == '') {
                $totalamtpaid = 'Not available';
            } else {
                $paidfee = $this->payment->stampduty_fee_details($token_no, $lang, $article_id, $payment_mode_id = Null);
                if (sizeof($paidfee) > 0) {
                    for ($j = 0; $j < sizeof($paidfee); $j++) {
                        $totalamtpaid = $totalamtpaid + $paidfee[$j][0]['totalsd'];
                    }
                } else {
                    $totalamtpaid = 'Not available';
                }
                //echo 'amt:'.$totalamtpaid;
                //pr($paidfee);exit;
                //$totalsd1
            }
        } else {
            $articlenm = 'Not available';
            $amt_paid = 'Not available';
            $msg = 'This application is not submitted to office';
        }



        $party_info = $this->ApplicationSubmitted->srchpartydetails($docregno, $office_id, $myDate);

        //pr($party_info);
        //echo 'aray size:'.sizeof($party_info);
        //pr($info_taken);

        $validated_field = array('office_id' => $office_id, 'docregno' => $docregno, 'docregdate' => $docregdate, 'office_name' => $office_name, 'articlenm' => $articlenm, 'amt_paid' => $totalamtpaid);

        //$validated_field=$office_id;
        //echo json_encode($validated_field);

        if (sizeof($info_taken) > 0) {
            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : ' . $docregno . ' </b></td><td><b>Document Registration Date : ' . $docregdate . ' </b></td>		<td ><b>Office : ' . $office_name . ' </b></td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;"><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article</b></td><td align="left" width="25%">' . $articlenm . '</td>	<td align="center" width="25%"><b>Fee</b></td><td align="left" width="25%">Rs.' . $totalamtpaid . '/- </td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">';

            for ($ii = 0; $ii < sizeof($party_info); $ii++) {
                $dispcolf = $dispcolf . '<tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_type_desc_en'] . '</td><td align="center" width="25%"><b>Party Name</b></td><td align="left" width="25%">' . $party_info[$ii][0]['party_fname_en'] . ' ' . $party_info[$ii][0]['party_mname_en'] . ' ' . $party_info[$ii][0]['party_lname_en'] . '</td></tr>';
            }
            $dispcolf = $dispcolf . '</tbody></table></div></div>';
        } else {
            $dispcolf = '<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><center><b>' . $msg . '</b></center></div></div>';
        }
        echo $dispcolf;
         exit;
                        }
                        else{
                        echo "Check the validations";
                        exit;
                        }
            
     
       
    }

    public function search_data_module($district_id, $fromyear, $toyear){
            try {
				//pr('dsfsdfsdf');
					//public $useDbConfig = 'ngdrs_legacy';
					
					
					
                    array_map([$this, 'loadModel'], ['Searcher']);
					//$this->Searcher->setDataSource('ngprs');
                    $this->loadModel('LegacyDataMain');
					
					//$g=$this->LegacyDataMain->getDataSource();
					//pr($g);
					//exit;
					//$this->LegacyDataMain->setDataSource('ngprs_legacy');
                    $this->loadModel('LegacyDataProperty');
                    $this->loadModel('LegacyDataPropertyDetails');
                    $this->loadModel('LegacyDataPropertyLandDetails');
                    $this->loadModel('LegacyDataParty');
                    $this->loadModel('LegacyDataPayment');
                    $this->loadModel('finyear');
					$fromyearset=substr($fromyear,0,4);
					$toyearset=substr($toyear,0,4);
                     $stateid = $this->Auth->User('state_id');
                    $lang = $this->Session->read("sess_langauge");
                    $this->set('lang', $lang);
                    $this->set('fromyearset', $fromyearset);
                    $this->set('toyearset', $toyearset);
                   // $this->set('hffromyear', $fromyear);
                   // $this->set('hftoyear', $toyear);
                    $this->set('action', null);
                    $this->set('village', null);
                    $this->set('partytype', null);
                    $this->set('partygrid', 0);
                    $this->set('propertygrid', 0);
                    $this->set('deedgrid', 0);
                    $this->set('year', ClassRegistry::init('finyear')->find('list', array('fields' => array('finyear_id', 'year_for_token'), 'order' => array('year_for_token' => 'ASC'))));
                    $yearset=$this->LegacyDataMain->query("select distinct(doc_processing_year) from ngdrstab_trn_legacy_data_main");
                    //pr($yearset);
                    $this->set('Search', ClassRegistry::init('Search')->find('list', array('fields' => array('id', 'search_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC'))));
                    $this->set('article', ClassRegistry::init('article')->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'conditions' => array('display_flag' => 'Y'), 'order' => array('article_desc_' . $lang => 'ASC'))));
                    $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'order' => array('taluka_name_' . $lang => 'ASC'))));
					//$this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'conditions' => array('district_id' => $district_id), 'order' => array('taluka_name_' . $lang => 'ASC'))));
                    
                    $fieldlist = array();
                    $fieldlist['Search']['select'] = 'is_select_req';
                    $fieldlist['party_name']['text'] = 'is_required,is_alphaspace';
                    $fieldlist['father_name']['text'] = 'is_alphaspace';
                    $fieldlist['fromyear']['text'] = 'is_required,is_integer';
                    $fieldlist['toyear']['text'] = 'is_required,is_integer';
                    $fieldlist['article_id']['select'] = 'is_select_req';
                    $fieldlist['taluka_id']['select'] = 'is_select_req';
                    $fieldlist['village_id']['select'] = 'is_select_req';
                    $fieldlist['plot_no']['text'] = 'is_alphanumspacedashdotslashroundbrackets';
                    $fieldlist['fromyear1']['text'] = 'is_required,is_integer';
                    $fieldlist['toyear1']['text'] = 'is_required,is_integer';
                    $fieldlist['article_id1']['select'] = 'is_select_req';
                    $this->set('fieldlist', $fieldlist);
                    $this->set('result_codes', $this->getvalidationruleset($fieldlist));
                    
                    if ($this->request->is('post')) {
                        
                        $file = new File(WWW_ROOT . 'files/searchjson_' . $this->Auth->user('user_id') . '.json');
                        $json = $file->read(true, 'r');
                        $json2array = json_decode($json, TRUE);
                    
                        if ($_POST['action'] == 1) {
                            //pr($this->request->data);
                            $articleid=$this->request->data['search_data_module']['article_id'];
                            $partyname=$this->request->data['search_data_module']['party_name'];
                            
							/*$fromyear=$this->request->data['search_data_module']['fromyear'];
                           
                            $fromyearval = $this->finyear->find('all', array('conditions' => array('finyear_id' => $fromyear)));
                            $fromyearval_val=$fromyearval[0]['finyear']['year_for_token'];
                            //pr($fromyearval_val);
                            $toyear=$this->request->data['search_data_module']['toyear'];
                            
                            $toyearval = $this->finyear->find('all', array('conditions' => array('finyear_id' => $toyear)));
                            $toyearval_val=$toyearval[0]['finyear']['year_for_token'];
							*/
							
							$fromyearval_val=$this->request->data['search_data_module']['fromyear'];
							$toyearval_val=$this->request->data['search_data_module']['toyear'];
                            //pr($toyearval_val);
                            $fathername=$this->request->data['search_data_module']['father_name'];
                            $partytypeid=$this->request->data['search_data_module']['party_type_id'];
                            if (!empty($partyname) && !empty($articleid) && !empty($fromyearval_val) && !empty($toyearval_val)) {
                                //pr($articleid);
                               // pr($partyname);
                                $condition="one.article_id=$articleid and two.party_full_name_en='$partyname' and one.doc_processing_year > '$fromyearval_val' and one.doc_processing_year <= '$toyearval_val'";
                                if(!empty($fathername))
                                {
                                    $condition= $condition. " and two.father_full_name_en='$fathername'";
                                }
                                /*if(!empty($partytypeid))
                                {
                                    $condition= $condition. " two.party_type_id=$partytypeid";
                                }*/
                                //pr($condition);
                                $data_to_disp=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main_final one inner join ngdrstab_trn_legacy_data_party_final two on one.state_id=two.state_id and one.reference_sr_no=two.reference_sr_no and one.doc_reg_no=two.doc_reg_no and one.doc_reg_date=two.doc_reg_date and one.doc_processing_year=two.doc_processing_year where $condition");
                               // pr($data_to_disp);
                                $this->set('partygrid',$data_to_disp);
                                $this->set('action',$_POST['action']);
                            }
                        }
                        if ($_POST['action'] == 2) {
                            //pr($this->request->data);
                            $talukaid=$this->request->data['search_data_module']['taluka_id'];
                            $villageid=$this->request->data['search_data_module']['village_id'];
                            $plotno=$this->request->data['search_data_module']['plot_no'];
                            
                            /*$fromyear1=$this->request->data['search_data_module']['fromyear1'];
                            $fromyearval1 = $this->finyear->find('all', array('conditions' => array('finyear_id' => $fromyear1)));
                            $fromyearval_val1=$fromyearval1[0]['finyear']['year_for_token'];
                            
                            $toyear1=$this->request->data['search_data_module']['toyear1'];
                            $toyearval1 = $this->finyear->find('all', array('conditions' => array('finyear_id' => $toyear1)));
                            $toyearval_val1=$toyearval1[0]['finyear']['year_for_token'];*/
							
							$fromyearval_val1=$this->request->data['search_data_module']['fromyear1'];
							$toyearval_val1=$this->request->data['search_data_module']['toyear1'];
                            
                            $article_id1=$this->request->data['search_data_module']['article_id1'];
                            $data_to_disp_second_arr_attr_nm=array();
                            $data_to_disp_second_arr_para_val=array();
                            $data_to_disp_second_arr_docno=array();
                            $data_to_disp_third_item_value=array();
                            $data_to_disp_third_unit_desc_en=array();
                            
                            if (!empty($talukaid) && !empty($villageid) && !empty($fromyearval_val1) && !empty($toyearval_val1) && !empty($article_id1)) {
                                
                                
                                //$data_to_disp_prop=$this->LegacyDataMain->query("select a.doc_processing_year,a.doc_reg_no,a.doc_reg_date,a.article_desc_en,b.district_name_en,a.office_name_en,c.property_sr_no,c.usage_main_catg_desc_en,c.usage_sub_catg_desc_en,c.item_value,c.unit_desc_en,c.market_value,c.cons_amt,d.property_land_sr_no,d.eri_attribute_name,d.paramter_value from ngdrstab_trn_legacy_data_main_final a inner join ngdrstab_trn_legacy_data_property_final b on a.reference_sr_no=b.reference_sr_no and  a.doc_reg_no=b.doc_reg_no and a.state_id=b.state_id and a.doc_reg_date=b.doc_reg_date and a.doc_processing_year=b.doc_processing_year inner join ngdrstab_trn_legacy_data_property_details_final c on b.reference_sr_no=c.reference_sr_no and  b.doc_reg_no=c.doc_reg_no and b.state_id=c.state_id and b.doc_reg_date=c.doc_reg_date and b.doc_processing_year=c.doc_processing_year and b.property_sr_no=c.property_sr_no left join ngdrstab_trn_legacy_data_property_land_details_final   d on d.reference_sr_no=c.reference_sr_no and  d.doc_reg_no=c.doc_reg_no and d.state_id=c.state_id and d.doc_reg_date=c.doc_reg_date and d.doc_processing_year=c.doc_processing_year and d.property_sr_no=c.property_sr_no  and d.property_land_sr_no=c.property_land_sr_no where a.article_id=4 and a.doc_processing_year> '2014' and a.doc_processing_year<='2018' and b.taluka_id=2 and b.village_id=83");
                                //select a.doc_processing_year,a.doc_reg_no,a.doc_reg_date,a.article_desc_en,b.district_name_en,a.office_name_en,c.property_sr_no,c.usage_main_catg_desc_en,c.usage_sub_catg_desc_en,c.item_value,c.unit_desc_en,c.market_value,c.cons_amt from ngdrstab_trn_legacy_data_main_final a inner join ngdrstab_trn_legacy_data_property_final b on a.reference_sr_no=b.reference_sr_no and  a.doc_reg_no=b.doc_reg_no and a.state_id=b.state_id and a.doc_reg_date=b.doc_reg_date and a.doc_processing_year=b.doc_processing_year inner join ngdrstab_trn_legacy_data_property_details_final c on b.reference_sr_no=c.reference_sr_no and  b.doc_reg_no=c.doc_reg_no and b.state_id=c.state_id and b.doc_reg_date=c.doc_reg_date and b.doc_processing_year=c.doc_processing_year and b.property_sr_no=c.property_sr_no where a.article_id=4 and a.doc_processing_year> '2014' and a.doc_processing_year<='2018' and b.taluka_id=2 and b.village_id=83
                                //select a.doc_processing_year,a.doc_reg_no,a.doc_reg_date,a.article_desc_en,b.district_name_en,a.office_name_en,c.property_sr_no,c.usage_main_catg_desc_en,c.usage_sub_catg_desc_en,c.item_value,c.unit_desc_en,c.market_value,c.cons_amt,d.property_land_sr_no,d.eri_attribute_name,d.paramter_value from ngdrstab_trn_legacy_data_main_final a inner join ngdrstab_trn_legacy_data_property_final b on a.reference_sr_no=b.reference_sr_no and  a.doc_reg_no=b.doc_reg_no and a.state_id=b.state_id and a.doc_reg_date=b.doc_reg_date and a.doc_processing_year=b.doc_processing_year inner join ngdrstab_trn_legacy_data_property_details_final c on b.reference_sr_no=c.reference_sr_no and  b.doc_reg_no=c.doc_reg_no and b.state_id=c.state_id and b.doc_reg_date=c.doc_reg_date and b.doc_processing_year=c.doc_processing_year and b.property_sr_no=c.property_sr_no inner join ngdrstab_trn_legacy_data_property_land_details_final   d on d.reference_sr_no=c.reference_sr_no and  d.doc_reg_no=c.doc_reg_no and d.state_id=c.state_id and d.doc_reg_date=c.doc_reg_date and d.doc_processing_year=c.doc_processing_year and d.property_sr_no=c.property_sr_no  and d.property_land_sr_no=c.property_land_sr_no where a.article_id=4 and a.doc_processing_year> '2014' and a.doc_processing_year<='2018' and b.taluka_id=2 and b.village_id=83
                                //select a.doc_processing_year,a.doc_reg_no,a.doc_reg_date,a.article_desc_en,b.district_name_en,a.office_name_en,c.property_sr_no,c.usage_main_catg_desc_en,c.usage_sub_catg_desc_en,c.item_value,c.unit_desc_en,c.market_value,c.cons_amt,d.property_land_sr_no,d.eri_attribute_name,d.paramter_value from ngdrstab_trn_legacy_data_main_final a inner join ngdrstab_trn_legacy_data_property_final b on a.reference_sr_no=b.reference_sr_no and  a.doc_reg_no=b.doc_reg_no and a.state_id=b.state_id and a.doc_reg_date=b.doc_reg_date and a.doc_processing_year=b.doc_processing_year inner join ngdrstab_trn_legacy_data_property_details_final c on b.reference_sr_no=c.reference_sr_no and  b.doc_reg_no=c.doc_reg_no and b.state_id=c.state_id and b.doc_reg_date=c.doc_reg_date and b.doc_processing_year=c.doc_processing_year and b.property_sr_no=c.property_sr_no left join ngdrstab_trn_legacy_data_property_land_details_final   d on d.reference_sr_no=c.reference_sr_no and  d.doc_reg_no=c.doc_reg_no and d.state_id=c.state_id and d.doc_reg_date=c.doc_reg_date and d.doc_processing_year=c.doc_processing_year and d.property_sr_no=c.property_sr_no  and d.property_land_sr_no=c.property_land_sr_no where a.article_id=4 and a.doc_processing_year> '2014' and a.doc_processing_year<='2018' and b.taluka_id=2 and b.village_id=83
                                
                                $data_to_disp_prop=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_final a inner join ngdrstab_trn_legacy_data_main_final b on a.state_id=b.state_id and a.reference_sr_no=b.reference_sr_no and a.doc_reg_no=b.doc_reg_no and a.doc_processing_year=b.doc_processing_year where taluka_id=$talukaid and village_id=$villageid and article_id=$article_id1 and a.doc_processing_year > '$fromyearval_val1' and a.doc_processing_year <= '$toyearval_val1'");
                                        
                                $this->set('propertygrid',$data_to_disp_prop);
                                for($iii=0;$iii<sizeof($data_to_disp_prop);$iii++){
                                   // pr($data_to_disp_prop);
                                    $doc_no=$data_to_disp_prop[$iii][0]['reference_sr_no'];
                                    $doc_reg_no=$data_to_disp_prop[$iii][0]['doc_reg_no'];
                                    $property_sr_no=$data_to_disp_prop[$iii][0]['property_sr_no'];
                                    $doc_processing_year=$data_to_disp_prop[$iii][0]['doc_processing_year'];
                                    
                                    $data_to_disp_second=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_land_details_final where reference_sr_no='$doc_no' and doc_reg_no='$doc_reg_no' and property_sr_no='$property_sr_no' and doc_processing_year='$doc_processing_year'");
                                    //pr($data_to_disp_second);
                                    for($k=0;$k<sizeof($data_to_disp_second);$k++){
                                        $data_to_disp_second_arr_docno[$iii][$k] =$data_to_disp_second[$k][0]['doc_reg_no'];
                                        $data_to_disp_second_arr_attr_nm[$iii][$k]=$data_to_disp_second[$k][0]['eri_attribute_name'];
                                        $data_to_disp_second_arr_para_val[$iii][$k]=$data_to_disp_second[$k][0]['paramter_value'];
                                    }
                                    
                                    $data_to_disp_third=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_details_final where reference_sr_no='$doc_no' and doc_reg_no='$doc_reg_no' and property_sr_no='$property_sr_no' and doc_processing_year='$doc_processing_year'");
                                    //pr($data_to_disp_third);
                                     for($l=0;$l<sizeof($data_to_disp_third);$l++){
                                        $data_to_disp_third_arr_docno[$iii][$l] =$data_to_disp_third[$l][0]['doc_reg_no'];
                                        $data_to_disp_third_item_value[$iii][$l] =$data_to_disp_third[$l][0]['item_value'];
                                        $data_to_disp_third_unit_desc_en[$iii][$l]=$data_to_disp_third[$l][0]['unit_desc_en'];
                                     
                                     }
                                }
                                //pr($data_to_disp_second);
                               // pr($data_to_disp_second_arr_docno);
                                //pr($data_to_disp_second_arr_attr_nm);
                                //pr($data_to_disp_second_arr_para_val);
                                //pr($data_to_disp_third_arr_docno);
                                //pr($data_to_disp_third_item_value);
                                //pr($data_to_disp_third_unit_desc_en);
                                $this->set('data_to_disp_third_arr_docno',$data_to_disp_third_arr_docno);
                                $this->set('data_to_disp_third_item_value',$data_to_disp_third_item_value);
                                $this->set('data_to_disp_third_unit_desc_en',$data_to_disp_third_unit_desc_en);
                                
                                $this->set('data_to_disp_second_arr_docno',$data_to_disp_second_arr_docno);
                                $this->set('data_to_disp_second_arr_attr_nm',$data_to_disp_second_arr_attr_nm);
                                $this->set('data_to_disp_second_arr_para_val',$data_to_disp_second_arr_para_val);
                                $this->set('action',$_POST['action']);
                                //$this->set('village', $villageid);
                                $this->set('village', $json2array['village']);
                            }
                        }
                        if ($_POST['action'] == 3) {
                            //pr($this->request->data);
                            $deed_no=$this->request->data['search_data_module']['deed_no'];
                            if (!empty($deed_no))
                            {
                                $data_to_disp_second_arr_attr_nm=array();
                                $data_to_disp_second_arr_para_val=array();
                                $data_to_disp_second_arr_docno=array();
                                $data_to_disp_third_item_value=array();
                                $data_to_disp_third_unit_desc_en=array();

                                $data_to_disp_prop=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_final a inner join ngdrstab_trn_legacy_data_main_final b on a.state_id=b.state_id and a.reference_sr_no=b.reference_sr_no and a.doc_reg_no=b.doc_reg_no and a.doc_processing_year=b.doc_processing_year where a.doc_reg_no= '$deed_no'");
                                //pr($data_to_disp_prop);
                                $this->set('deedgrid',$data_to_disp_prop);
                                for($iii=0;$iii<sizeof($data_to_disp_prop);$iii++){
                                   // pr($data_to_disp_prop);
                                    $doc_no=$data_to_disp_prop[$iii][0]['reference_sr_no'];
                                    $doc_reg_no=$data_to_disp_prop[$iii][0]['doc_reg_no'];
                                    $property_sr_no=$data_to_disp_prop[$iii][0]['property_sr_no'];
                                    $doc_processing_year=$data_to_disp_prop[$iii][0]['doc_processing_year'];
                                    
                                    $data_to_disp_second=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_land_details_final where reference_sr_no='$doc_no' and doc_reg_no='$doc_reg_no' and property_sr_no='$property_sr_no' and doc_processing_year='$doc_processing_year'");
                                    //pr($data_to_disp_second);
                                    for($k=0;$k<sizeof($data_to_disp_second);$k++){
                                        $data_to_disp_second_arr_docno[$iii][$k] =$data_to_disp_second[$k][0]['doc_reg_no'];
                                        $data_to_disp_second_arr_attr_nm[$iii][$k]=$data_to_disp_second[$k][0]['eri_attribute_name'];
                                        $data_to_disp_second_arr_para_val[$iii][$k]=$data_to_disp_second[$k][0]['paramter_value'];
                                    }
                                    
                                    $data_to_disp_third=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_property_details_final where reference_sr_no='$doc_no' and doc_reg_no='$doc_reg_no' and property_sr_no='$property_sr_no' and doc_processing_year='$doc_processing_year'");
                                    //pr($data_to_disp_third);
                                     for($l=0;$l<sizeof($data_to_disp_third);$l++){
                                        $data_to_disp_third_arr_docno[$iii][$l] =$data_to_disp_third[$l][0]['doc_reg_no'];
                                        $data_to_disp_third_item_value[$iii][$l] =$data_to_disp_third[$l][0]['item_value'];
                                        $data_to_disp_third_unit_desc_en[$iii][$l]=$data_to_disp_third[$l][0]['unit_desc_en'];
                                     
                                     }
                                }
                                
                                $this->set('data_to_disp_third_arr_docno',$data_to_disp_third_arr_docno);
                                $this->set('data_to_disp_third_item_value',$data_to_disp_third_item_value);
                                $this->set('data_to_disp_third_unit_desc_en',$data_to_disp_third_unit_desc_en);
                                
                                $this->set('data_to_disp_second_arr_docno',$data_to_disp_second_arr_docno);
                                $this->set('data_to_disp_second_arr_attr_nm',$data_to_disp_second_arr_attr_nm);
                                $this->set('data_to_disp_second_arr_para_val',$data_to_disp_second_arr_para_val);
                                $this->set('action',$_POST['action']);
                                
                                $this->set('village', $json2array['village']);
                                
                            }
                            
                        }
                    }
                
            } catch (Exception $ex) {
                $this->Session->setFlash(
                        __('Record Cannot be displayed. Error :' . $ex->getMessage())
                );
                return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
            }
        
    }
        public function viewrptdetails(){
            try {
                $this->autoRender = FALSE;
                $refno = $_POST['refno'];
                $regno = $_POST['regno'];
                $regdt = $_POST['regdt'];
                $procyear = $_POST['procyear'];
                $this->loadModel('LegacyDataMain');
                $this->loadModel('LegacyDataProperty');
                $this->loadModel('LegacyDataPropertyDetails');
                $this->loadModel('LegacyDataPropertyLandDetails');
                $this->loadModel('LegacyDataParty');
                $this->loadModel('LegacyDataPayment');
                $stateid = $this->Auth->User('state_id');
                $lang = $this->Session->read("sess_langauge");
                
                $maindetail=$this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main_final where reference_sr_no=$refno and doc_reg_no='$regno' and doc_processing_year='$procyear'");
                    
                //$html_design="<html><body><table><tr><td>".$regno."</td></tr></table></body></html>";
                $html_design = "<html><body><style>td{padding:5px;}  </style>"
                        . "<h2 align=center style='color:#9C6F7A';> Search Details  </h2><br/><br/><br/>"
                        . "<table border=0 width=100%>"
                        . "<tr><td><b>Reference No. : " . $refno . "</b></td></tr>"
                        . "<tr><td><b>Deed No. : " . $regno . "</b></td></tr>"
                        . "<tr><td><b>Date : " . $regdt . "</b></td></tr>"
                        . "<tr><td><b>Processing Year : " . $procyear . "</b></td></tr>"
                        . "<tr><td><b>Deed Type : " . $maindetail[0][0]['article_desc_en'] . "</b></td></tr>";
                
                $html_design .= "</table>"
                        . "<hr style='color:balck';>"
                        . "<div class='table-responsive'>"
                        . "<table border=1 style='border-collapse:collapse;' width=100%>"
                        . "<thead>"
                        . "<tr>"
                        . "<th rowspan='2' style='text-align:center;' >Sr.No</th>"
                        . "<th rowspan='2' style='text-align:center;' >Property Details</th>"
                       // . "<th rowspan='2' style='text-align:center;' >Date</th>"
                      //  . "<th rowspan='2' style='text-align:center;' >Type of Deed</th>"
                        . "<th rowspan='2' style='text-align:center;' >Parties</th>"
                        //. "<th colspan='3' style='text-align:center;' >Deed Details</th>"
                        . "</tr>"
                        . "<tr>"
                       // . "<th rowspan='1' style='text-align:center;'>Vol.</th>"
                       // . "<th rowspan='1' style='text-align:center;'>Year</th>"
                       // . "<th rowspan='1' style='text-align:center;'>Pages</th>"
                        . "</tr>"
                        . "</thead>";
                
                $srno = 1;
                $html_design .= "<tbody>"
                        . "<tr><td style='text-align:center;'>" . $srno++ . "</td>";
                $html_design .= "<td style='text-align:left;'>";
                
                $propdetails=$this->LegacyDataProperty->query("select * from ngdrstab_trn_legacy_data_property_final where reference_sr_no=$refno and doc_reg_no='$regno' and doc_processing_year='$procyear'");
                //pr($propdetails);
                for($r=0;$r<sizeof($propdetails);$r++){
                    $property_sr_no=$propdetails[$r][0]['property_sr_no'];
                    $developed_land_types_desc_en=$propdetails[$r][0]['developed_land_types_desc_en'];
                    $district_name_en=$propdetails[$r][0]['district_name_en'];
                    $taluka_name_en=$propdetails[$r][0]['taluka_name_en'];
                    $village_name_en=$propdetails[$r][0]['village_name_en'];
                    $boundries_east_en=$propdetails[$r][0]['boundries_east_en'];
                    $boundries_west_en=$propdetails[$r][0]['boundries_west_en'];
                    $boundries_south_en=$propdetails[$r][0]['boundries_south_en'];
                    $boundries_north_en=$propdetails[$r][0]['boundries_north_en'];
                    $property_address_details=$propdetails[$r][0]['property_address_details'];
                    
                    $html_design .=$property_sr_no." - <b>Property Type : </b>".$developed_land_types_desc_en;
                    $html_design .="<br><b>District : </b>".$district_name_en."<b> Taluka : </b>".$taluka_name_en."<b> Village : </b>".$village_name_en;
                    $html_design .="<br><b>Boundaries : </b>";
                    $html_design .="<br><b>East : </b>".$boundries_east_en." <b>West : </b>".$boundries_west_en;
                    $html_design .="<br><b>South : </b>".$boundries_south_en." <b>North : </b>".$boundries_north_en;
                    $html_design .="<br><b>Property Address Details: </b>".$property_address_details;
                    
                    $propdetails_two=$this->LegacyDataPropertyDetails->query("select * from ngdrstab_trn_legacy_data_property_details_final where reference_sr_no=$refno and doc_reg_no='$regno' and doc_processing_year='$procyear' and property_sr_no=$property_sr_no");
                    for($m=0;$m<sizeof($propdetails_two);$m++){
                        //$property_land_sr_no=$propdetails_two[$m][0]['property_land_sr_no'];
                        $usage_main_catg_desc_en=$propdetails_two[$m][0]['usage_main_catg_desc_en'];
                        $usage_sub_catg_desc_en=$propdetails_two[$m][0]['usage_sub_catg_desc_en'];
                        $item_value=$propdetails_two[$m][0]['item_value'];
                        $unit_desc_en=$propdetails_two[$m][0]['unit_desc_en'];
                        $market_value=$propdetails_two[$m][0]['market_value'];
                        $cons_amt=$propdetails_two[$m][0]['cons_amt'];
                        $html_design .="<br>";
                        $html_design .="<b>main category : </b>".$usage_main_catg_desc_en." <b>Sub Category : </b>".$usage_sub_catg_desc_en;
                        $html_design .="<br><b>Area : </b>".$item_value." ".$unit_desc_en." <b>Market Value (in Rs.) : </b>".$market_value;
                        $html_design .="<br><b>Consideration Amount (in Rs. ) : </b>".$cons_amt;
                        
                        
                        
                    }
                    $propdetails_three=$this->LegacyDataPropertyLandDetails->query("select * from ngdrstab_trn_legacy_data_property_land_details_final where reference_sr_no=$refno and doc_reg_no='$regno' and doc_processing_year='$procyear' and property_sr_no=$property_sr_no");
                         for($q=0;$q<sizeof($propdetails_three);$q++){
                            $html_design .="<br><b>".$propdetails_three[$q][0]['eri_attribute_name']." : </b>".$propdetails_three[$q][0]['paramter_value'];
                        }
                        
                    $html_design .="<br><hr>";
                }
                $html_design .= "</td><td>";
                
                $partydetails=$this->LegacyDataParty->query("select * from ngdrstab_trn_legacy_data_party_final where reference_sr_no=$refno and doc_reg_no='$regno' and doc_processing_year='$procyear'");
                for($n=0;$n<sizeof($partydetails);$n++){
                    $party_sr_no=$partydetails[$n][0]['party_sr_no'];
                    $party_type_desc_en=$partydetails[$n][0]['party_type_desc_en'];
                    $category_name_en=$partydetails[$n][0]['category_name_en'];
                    $party_full_name_en=$partydetails[$n][0]['party_full_name_en'];
                    $father_full_name_en=$partydetails[$n][0]['father_full_name_en'];
                    
                    $html_design .="<br>".$party_sr_no.") <b>Party Type : </b>".$party_type_desc_en."<br><b>Party Category : </b>".$category_name_en;
                    $html_design .="<br><b>Party Full Name : </b>".$party_full_name_en."<br><b>Father Full Name : </b>".$father_full_name_en;
                    $html_design .="<hr>";
                }
                $html_design .= "</td></tr>";
                
                return $html_design;
               
              } catch (Exception $ex) {
                    pr($ex);
                    exit;
                }
        }
        public function get_party_type() {
        try {
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            if (isset($_GET['article_id'])) {

                $article_id = $_GET['article_id'];
                $party_type_id = ClassRegistry::init('article_partymapping')->find('list', array('fields' => array('party_type_id'), 'conditions' => array('article_id' => array($article_id))));
                $party_typename = ClassRegistry::init('partytype')->find('list', array('fields' => array('party_type_id', 'party_type_desc_' . $laug), 'conditions' => array('party_type_id' => $party_type_id), 'order' => array('party_type_desc_' . $laug => 'ASC')));
                //pr($party_typename);exit;
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
    
    public function pay_for_cert($docregno = Null, $flag = ''){
         $this->response->header(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type'
                )
        );
        $this->loadModel('BankPayment');
        $this->loadModel('external_interface');
        $this->loadModel('genernalinfoentry');
        $this->loadModel('file_config');
        $this->loadModel('payment');
        
        $fieldlist = array();

       // $fieldlist['doc_regno']['text'] = 'is_required,is_numeric';
        $fieldlist['payee_fname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['payee_lname_en']['text'] = 'is_required,is_alpha';
        $fieldlist['email_id']['text'] = 'is_required,is_email';
        $fieldlist['mobile']['text'] = 'is_required,is_mobileindian';
        $fieldlist['address']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['city']['text'] = 'is_required,is_alphanumericspace';
        $fieldlist['pincode']['text'] = 'is_required,is_pincode';
        $fieldlist['feetype']['text'] = 'is_required,is_select_req';
        $fieldlist['feeflag']['text'] = 'is_required,is_select_req';
        $fieldlist['pamount']['text'] = 'is_required,is_numeric';

        $this->set("fieldlist", $fieldlist);
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        
        $this->set('docregno', $docregno);
        
        if ($this->request->is('post')) {
            $data = $this->request->data;
            pr($data);
             $errarr = $this->validatedata($data, $fieldlist);
            //pr($errarr);exit;
            if ($this->ValidationError($errarr)) {
                
                $bankapi = $this->external_interface->find("first", array('conditions' => array('interface_id' => 14)));
                if (empty($bankapi)) {
                    $this->Session->setFlash(
                            __('Bank Api Not Found')
                    );
                    $this->redirect(array('controller' => 'SearchModule', 'action' => 'pay_for_cert'));
                } else {
                    $bankapi = $bankapi['external_interface'];
                }
                
                $path = $this->file_config->find('first', array('fields' => array('filepath')));
                if (empty($path)) {
                    $this->Session->setFlash(
                            __('Base Path Not Found')
                    );
                    $this->redirect(array('controller' => 'SearchModule', 'action' => 'pay_for_cert'));
                } else {
                    $basepath = $path['file_config']['filepath'];
                }
                
                 do {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                    $check = $this->BankPayment->find("first", array('conditions' => array('transaction_id' => $txnid)));
                } while (!empty($check));

                
                $data['payment_mode_id'] = 11;
                $data['transaction_id'] = $txnid;


                $usertype = $this->Session->read("session_usertype");
                $savedata['user_type'] = $usertype;
                $now = date('Y-m-d H:i:s');
                $data['state_id'] = $this->Auth->user('state_id');
                if ($usertype == 'C') {
                    $data['user_id'] = $this->Auth->user('user_id');
                    $data['created'] = $now;
                } elseif ($usertype == 'O') {
                    $data['org_user_id'] = $this->Auth->user('user_id');
                    $data['org_created'] = $now;
                }
                if ($data['feetype'] == 1) {
                    $data['account_head_desc'] = '0030063301|9999001';
                } else if ($data['feetype'] == 2) {
                    $data['account_head_desc'] = '48';
                } else {
                    $this->Session->setFlash(
                            __('Account head not found')
                    );
                    $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                }

                $flags = array('1' => 'NOTARY', '2' => 'URBAN', '3' => 'RURALNORTH', '4' => 'RURALSOUTH');
                $data['RuralUrbanFlag'] = @$flags[$data['feeflag']];

                
                try {
                    $client = new SoapClient($bankapi['interface_url']);
                    $pushdata = array(
                        'webUser' => $bankapi['interface_user_id'],
                        'webPass' => $bankapi['interface_password'],
                        'No_of_fee_items' => 1,
                        'Feeamt' => $data['pamount'],
                        'mobile' => $data['mobile'],
                        'Partyname' => $data['payee_fname_en'],
                        'Party_Address' => $data['address'],
                        'Party_PIN' => $data['pincode'],
                        'email' => $data['email_id'],
                        'Party_taluka' => $data['city'],
                        'IPAddress' => @$this->request->clientIp(),
                        'Reason' => 'NGDRS Fee Colletion for document Reg. no' . $data['doc_regno'],
                        'OtherDetails' => $data['doc_regno'] . " " . $data['RuralUrbanFlag'],
                        'RuralUrbanFlag' => $data['RuralUrbanFlag']
                    );
                    
                    /*if ($data['feetype'] == 1) {
                        $result = $client->Generate_eChallan_RegFee($pushdata);
                        $servicedata = (string) $result->Generate_eChallan_RegFeeResult;
                    } else {
                        $result = $client->Generate_eChallan_MutationFee($pushdata);
                        $servicedata = (string) $result->Generate_eChallan_MutationFeeResult;
                    }
                    
                    $start = substr(trim($servicedata), 0, 1);
                    if ($start != '<') {
                        $this->Session->setFlash(
                                __('Service Return - ' . $servicedata)
                        );
                        $this->redirect(array('controller' => 'GAWebService', 'action' => 'gras_payment_entry_new'));
                    }

                    $xml = simplexml_load_string($servicedata, "SimpleXMLElement", LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $array = json_decode($json, TRUE);
                    // pr($array);exit;
                    */
                    
                    $file = $basepath . "webservice_files/" . $txnid . '_challan.pdf';
                    
                    /*if (file_put_contents($file, base64_decode($array['Table1']['filebytes']))) {
                        //  Table1/eno
                        if (file_exists($file)) {
                            $data['gateway_trans_id'] = $array['Table1']['eno'];
                            $data['payment_status'] = 'CREATED';
                            $data['transaction_id'] = $txnid;
                            $data['udf1'] = $data['RuralUrbanFlag'];

                            if ($this->BankPayment->save($data)) {
                                //    pr($data);exit;
                                $this->Session->setFlash(
                                        __('Successfully created challan.')
                                );
                            } else {
                                $this->Session->setFlash(
                                        __('Unable create Challan ')
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                    __('Unable create Challan ')
                            );
                        }
                    } else {
                        $this->Session->setFlash(
                                __('Unable create Challan ')
                        );
                    }
                     */
                    
                    
                } catch (SoapFault $e) {
                    $this->Session->setFlash(
                            __('Unable to connect web service ' . $e->getMessage())
                    );
                }
                
            }
            
        }
    }
    
    
    
}

?>