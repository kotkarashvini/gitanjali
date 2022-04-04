<?php
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Vendor', 'reader');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SearchlegacyController extends AppController
{

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
//    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
                public $helpers = array('Js', 'Html', 'Form', 'Paginator');

                  public function beforeFilter()
                  {
                      $this->loadModel('language');
                      $this->Session->renew();
                      $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
                      $this->set('langaugelist', $langaugelist);
                      $this->Auth->allow('upload_excel_to_tbl', 'get_party_type','isValidDate1','check');
                      $laug = $this->Session->read("sess_langauge");

                      if (is_null($laug))
                      {
                          $this->Session->write("sess_langauge", 'en');
                      }

                      if (isset($this->Security)) {
                          $this->Security->validatePost = false;
                          $this->Security->enabled = false;
                          $this->Security->csrfCheck = false;
                      }
                  }


            public  function isValidDate1($dateString) {
                    //$dateString="2012-09-12";
                 
                  if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dateString)) {
                      return true;
                  } else {
                      return false;
                  }
              }

            // function isValidDate1($dateString)
            // {
            //     if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dateString)) {
            //         return true;
            //     } else {
            //         return false;
            //     }
            //   }

              function isdigit1($dateString) {
                if (preg_match("/^[0-9 ]*$/",$dateString)) {
                    return true;
                } else {
                    return false;
                }
              }
    function upload_excel_to_tbl()
     {
        try
        {
            $this->loadModel('file_config');
            $this->loadModel('LegacyData');
            $this->loadModel('LegacyDataMain');
            $this->loadModel('LegacyDataProperty');
            $this->loadModel('LegacyDataPropertyDetails');
            $this->loadModel('LegacyDataPropertyLandDetails');
            $this->loadModel('LegacyDataParty');
            $this->loadModel('LegacyDataPayment');

            $this->loadModel('LegacyDataMainInvalidData');
            $this->loadModel('LegacyDataPropertyInvalidData');
            $this->loadModel('LegacyDataPropertyDetailsInvalidData');
            $this->loadModel('LegacyDataPropertyLandDetailsInvalidData');
            $this->loadModel('LegacyDataPartyInvalidData');
            $this->loadModel('LegacyDataPaymentInvalidData');
           
           
            if ($this->request->is('post'))
            {
                if ($this->request->data['upload_excel_to_tbl']['upload_file']['error'] == 0)
                {
                 
                  $file_ext = pathinfo($this->request->data['upload_excel_to_tbl']['upload_file']['name'], PATHINFO_EXTENSION);
                                 
                   $path = $this->file_config->find('first', array('fields' => array('filepath')));
               
                    $new_name = $this->request->data['upload_excel_to_tbl']['upload_file']['name'];
                   
                    $createFolder1 = $this->create_folder($path['file_config']['filepath'], 'Legacy_Documents/');
                 
                    $success = move_uploaded_file($this->request->data['upload_excel_to_tbl']['upload_file']['tmp_name'], $createFolder1 . '/' . $new_name);
   
                    $to_read = $path['file_config']['filepath'] . 'Legacy_Documents/' . $new_name;
               
                    $excel = new Spreadsheet_Excel_Reader();
                    $excel->setOutputEncoding('CP1251');
                 
                    $excel->read($to_read);
                    // print number of rows, columns and sheets
                    echo "Number of sheets: " . sizeof($excel->sheets) . "\n";
                 
//-----------------------VALIDATION SHEET 1----------------------------------
                    $fieldlist = array();
                    $fieldlist2 = array();
                    $fieldlist3 = array();
                    $fieldlist4 = array();
                    $fieldlist5 = array();
                    $fieldlist6 = array();
                    $data = array();
                    $data2 = array();
                    $data3 = array();
                    $data4 = array();
                    $data5 = array();
                    $data6 = array();
                    $geninfocount = 0;
                    $allerr = array();
                    $errarr5 = array();
//-----------------------VALIDATION SHEET 1----------------------------------                    
//--------SHEET 1
                    $sheet1_no = $excel->sheets[0]["numRows"];
                 
               for ($i = 2; $i <= $sheet1_no; $i++)
               {

//-----------------------VALIDATION  SHEET 1----------------------------------
                        $fieldlist['state_id']['text'] = 'is_reuired,is_numeric';
                        $fieldlist['state_name_en']['text'] = 'is_alpha'; //a-z, A-Z
                        $fieldlist['reference_sr_no']['text'] = 'is_numeric'; //0-9
                        $fieldlist['doc_reg_no']['text'] = 'is_alphanumspacedash'; //a-z,A-Z,0-9,Space,-
                       // $fieldlist['doc_reg_date']['text'] = 'is_date'; //DATE FORMAT
                       //  $fieldlist['doc_reg_date']['date'] = 'is_date'; //DATE FORMAT
                         $fieldlist['doc_reg_date']['text'] = 'is_date_dash';
                        $fieldlist['doc_processing_year']['text'] = 'is_numeric'; //0-9
                        $fieldlist['article_id']['text'] = 'is_numeric'; //0-9
                        $fieldlist['article_desc_en']['text'] = 'is_alphanumspacedash'; //a-z,A-Z,0-9,Space,-
                        $fieldlist['office_district_id']['text'] = 'is_numeric'; //0-9
                        $fieldlist['office_district_name_en']['text'] = 'is_alphaspacedashslash'; //a-z,A-Z,0-9,Space,-,/
                        $fieldlist['office_taluka_id']['text'] = 'is_numeric'; //0-9
                        $fieldlist['office_taluka_name_en']['text'] = 'is_alphaspacedashslash'; //a-z,A-Z,0-9,Space,-,/
                        $fieldlist['office_id']['text'] = 'is_numeric'; //0-9
                        $fieldlist['office_name_en']['text'] = 'is_alphaspacedashdotcommacolonroundbrackets'; //"Valid entry A-Z,a-z,0-9,-,.,/,( ,) and space allowed"
                       // $fieldlist['doc_file_nm']['text'] = 'is_alphaspacedashdotcommacolonroundbrackets';
                        $fieldlist['doc_file_nm']['text'] = 'is_required';
                        $this->set('fieldlist', $fieldlist);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist));


//-----------------------VALIDATION  SHEET 1----------------------------------
                        $state_id = $excel->sheets[0]['cells'][$i][1];
                        $state_name_en = $excel->sheets[0]['cells'][$i][2];
                        $reference_sr_no = $excel->sheets[0]['cells'][$i][3];
                        $doc_reg_no = $excel->sheets[0]['cells'][$i][4];

                        pr('docuemnt reg no for '.$i);
                        pr($excel->sheets[0]['cells'][$i][4]);

                        $regdate = $excel->sheets[0]['cells'][$i][5];
                        $doc_reg_date=$excel->sheets[0]['cells'][$i][5];
                            //DATE CONVERT IN TO DATE FORMAT
                          //  $unixTimestamp = ($regdate - 25569) * 86400;
                        //   $doc_reg_date = date('Y-m-d', $unixTimestamp);

                          // echo 'date_form_sheet:';
                            // pr($excel->sheets[0]['cells'][$i][5]);
                          //  PR('EXIT');

                            // echo '\n date_covert:';
                            //  pr($doc_reg_date);exit();  
                 
                        $doc_processing_year = $excel->sheets[0]['cells'][$i][6];
                        $article_id = $excel->sheets[0]['cells'][$i][7];
                        $article_desc_en = $excel->sheets[0]['cells'][$i][8];
                        $office_district_id = $excel->sheets[0]['cells'][$i][9];
                        $office_district_name_en = $excel->sheets[0]['cells'][$i][10];
                        $office_taluka_id = $excel->sheets[0]['cells'][$i][11];
                        $office_taluka_name_en = $excel->sheets[0]['cells'][$i][12];
                        $office_id = $excel->sheets[0]['cells'][$i][13];
                        $office_name_en = $excel->sheets[0]['cells'][$i][14];
                        $doc_file_nm = $excel->sheets[0]['cells'][$i][15];
                        //-----------------------VALIDATION  SHEET 1----------------------------------
                        $uniqueno = $excel->sheets[0]['cells'][$i][17];
                        //-----------------------VALIDATION  SHEET 1----------------------------------
                        $user_id = $this->Session->read("citizen_user_id");
                     
                     
                        if ($user_id == '' || $user_id == NULL)
                        $user_id = 9999;
                        $creation_date = date('Y-m-d');


//-----------------------VALIDATION  SHEET 1----------------------------------
                        $data['state_id'] = $state_id;
                        $data['state_name_en'] = $state_name_en;
                        $data['reference_sr_no'] = $reference_sr_no;
                        $data['doc_reg_no'] = $doc_reg_no;
                        $data['doc_reg_date'] = $doc_reg_date;
                        $data['doc_processing_year'] = $doc_processing_year;
                        $data['article_id'] = $article_id;
                        $data['article_desc_en'] = $article_desc_en;
                        $data['office_district_id'] = $office_district_id;
                        $data['office_district_name_en'] = $office_district_name_en;
                        $data['office_taluka_id'] = $office_taluka_id;
                        $data['office_taluka_name_en'] = $office_taluka_name_en;
                        $data['office_id'] = $office_id;
                        $data['office_name_en'] = $office_name_en;
                        $data['doc_file_nm'] = $doc_file_nm;
                                       

                      $errarr = $this->validatedata($data, $fieldlist);

                      if($this->ValidationError($errarr))
                      {
                        $Generalinfo = $this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main where doc_reg_no=" . "'" . $doc_reg_no . "'" . "and doc_processing_year=" . $doc_processing_year . " and state_id=" . $state_id . "and office_id=" . $office_id );          
                        if (!empty($Generalinfo))
                         {
                         }
                        else
                        {
                        $inst = $this->LegacyDataMain->query("insert into ngdrstab_trn_legacy_data_main(state_id,state_name_en,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,article_id,article_desc_en,office_district_id,office_district_name_en,office_taluka_id,office_taluka_name_en,office_id,office_name_en,user_id,creation_date,doc_file_nm,uniqueno,updated_flag) values($state_id,'$state_name_en',$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$article_id,'$article_desc_en',$office_district_id,'$office_district_name_en',$office_taluka_id,'$office_taluka_name_en',$office_id,'$office_name_en',$user_id,'$creation_date','$doc_file_nm','$uniqueno','Y')");  
                        }
                     }

                          // ERROR PEERSENT IN CODE
                      else
                      {
                       $geninfocount = 1;
                       $errarr5['err'] = '';
                       $errarr5['sheetid'] = '';
                                             
                         foreach ($errarr as $key => $value)
                            {
                               if(!empty($value))
                              {
                                 $errarr5['err'] .= $key . '-' . $value . ", ";
                                $errarr5['number'] = $excel->sheets[0]['cells'][$i][4];
                              }
                            }
                       
                            $errarr5['sheetid'] = 'Sheet 1';
                            array_push($allerr, $errarr5);
                            $this->set('allerr', $allerr);
                       
                             // code Duplicate record found in General Information excel
                          for ($i1 = 1; $i1 <= $sheet1_no - 1; $i1++) //ROW
                          {
               
                            if ($i != $i1)
                             {
                              if ($doc_reg_no == $excel->sheets[0]['cells'][$i1][4]  &&  $doc_processing_year == $excel->sheets[0]['cells'][$i1][6]  && $state_id == $excel->sheets[0]['cells'][$i1][1] && $office_id ==  $excel->sheets[0]['cells'][$i1][13])
                              {
                               pr('a');
                                $geninfocount = 1;
                                $errarr5['number'] = $doc_reg_no;
                                $errarr5['sheetid'] = 'Sheet 1';
                                $errarr5['err'] = 'Duplicate record found in General Information excel.';
                                array_push($allerr, $errarr5);
                                $this->set('allerr', $allerr);
                              }
                             }
                         }

                        // code Duplicate record found in database
                        //PR('DATA IS GOING TO STORE INTO INVALID TABLE **********************');
                        $Generalinfo = $this->LegacyDataMain->query("select * from ngdrstab_trn_legacy_data_main_invalid_data where doc_reg_no=" . "'" . $doc_reg_no . "'" . "and doc_processing_year=" . $doc_processing_year . " and state_id=" . $state_id . "and office_id=" . $office_id );

                          if (!empty($Generalinfo))
                          {
                          //pr('b');
                          $geninfocount = 1;
                          $errarr5['number'] = $doc_reg_no;
                          $errarr5['sheetid'] = 'Sheet 1';
                          $errarr5['err'] = 'Duplicate Records found in Database General Information'; //duplicate
                          array_push($allerr, $errarr5);
                          //  pr('in database dublication');
                          //  pr($errarr5);
                          $this->set('allerr', $allerr);
                        }

                         else
                         {
                         $inst = $this->LegacyDataMain->query("insert into ngdrstab_trn_legacy_data_main_invalid_data(state_id,state_name_en,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,article_id,article_desc_en,office_district_id,office_district_name_en,office_taluka_id,office_taluka_name_en,office_id,office_name_en,user_id,creation_date,doc_file_nm,uniqueno,updated_flag) values($state_id,'$state_name_en',$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$article_id,'$article_desc_en',$office_district_id,'$office_district_name_en',$office_taluka_id,'$office_taluka_name_en',$office_id,'$office_name_en',$user_id,'$creation_date','$doc_file_nm','$uniqueno','Y')");
                         }
                     }
               }

           

//************************************************************************* */


//-----------------------VALIDATION  SHEET 1----------------------------------

         //     25/11/2021 Sonam change comment all sheet code..

                    $sheet2_no = $excel->sheets[1]["numRows"];

                    for ($j = 2; $j <= $sheet2_no; $j++)
                    {
////-----------------------VALIDATION  SHEET 2----------------------------------
                        $fieldlist2['state_id']['text'] = 'is_numeric';//0-9
                        $fieldlist2['reference_sr_no']['text'] = 'is_numeric';//0-9
                        $fieldlist2['doc_reg_no']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist2['regdate']['text'] = 'is_alphanumspacedash';//DATE FORMAT
                      //  $fieldlist['unixTimestamp']['text'] = 'is_alphanumspacedash';//???
                        $fieldlist2['doc_reg_date']['text'] = 'is_required';//DATE FORMAT
                        $fieldlist2['doc_processing_year']['text'] = 'is_numeric';//0-9
                        $fieldlist2['property_sr_no']['text'] = 'is_numeric';//0-9
                        $fieldlist2['developed_land_types_desc_en']['text'] = 'is_alphanumspacedot';//a-z,A-Z,.
                        $fieldlist2['district_id']['text'] = 'is_numeric';//0-9
                        $fieldlist2['district_name_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['taluka_id']['text'] = 'is_numeric';//0-9
                        $fieldlist2['taluka_name_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['village_id']['text'] = 'is_numeric';//0-9
                        $fieldlist2['village_name_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['unique_property_no_en']['text'] = 'is_alphanumeric';//a-z,A-Z,0-9
                       
                        /*
                        $fieldlist2['level_1_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['list_1_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['list_2_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['boundries_east_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_west_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_south_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_north_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['property_address_details']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';//a-z,A-Z,0-9,Space,-,/,.,""
                         */
                       
                        $fieldlist2['level_1_desc_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['list_1_desc_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['list_2_desc_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist2['boundries_east_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_west_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_south_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['boundries_north_en']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/,.,""
                        $fieldlist2['property_address_details']['text'] = 'is_required';//a-z,A-Z,0-9,Space,-,/,.,""
                         

                        $this->set('fieldlist2', $fieldlist2);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist2));
////-----------------------VALIDATION  SHEET 2----------------------------------
                        //echo '<br>'.$j;
                        $state_id = $excel->sheets[1]['cells'][$j][1];
                        $reference_sr_no = $excel->sheets[1]['cells'][$j][2];
                        $doc_reg_no = $excel->sheets[1]['cells'][$j][3];
                        //$doc_reg_date=$excel->sheets[1]['cells'][$j][4];
                      //  $regdate = $excel->sheets[1]['cells'][$j][4];
                      //  $unixTimestamp = ($regdate - 25569) * 86400;
                       // $doc_reg_date = date('Y-m-d', $unixTimestamp);

                       $doc_reg_date=$excel->sheets[1]['cells'][$j][4];
                        $doc_processing_year = $excel->sheets[1]['cells'][$j][5];
                        $property_sr_no = $excel->sheets[1]['cells'][$j][6];
                        $developed_land_types_desc_en = $excel->sheets[1]['cells'][$j][7];
                        $district_id = $excel->sheets[1]['cells'][$j][8];
                        $district_name_en = $excel->sheets[1]['cells'][$j][9];
                        $taluka_id = $excel->sheets[1]['cells'][$j][10];
                        $taluka_name_en = $excel->sheets[1]['cells'][$j][11];
                        $village_id = $excel->sheets[1]['cells'][$j][12];
                        $village_name_en = $excel->sheets[1]['cells'][$j][13];
                        $unique_property_no_en = $excel->sheets[1]['cells'][$j][14];
                        $level_1_desc_en = $excel->sheets[1]['cells'][$j][15];
                        $list_1_desc_en = $excel->sheets[1]['cells'][$j][16];
                        $level_2_desc_en = $excel->sheets[1]['cells'][$j][17];
                        $list_2_desc_en = $excel->sheets[1]['cells'][$j][18];
                        $boundries_east_en = $excel->sheets[1]['cells'][$j][19];
                        $boundries_west_en = $excel->sheets[1]['cells'][$j][20];
                        $boundries_south_en = $excel->sheets[1]['cells'][$j][21];
                        $boundries_north_en = $excel->sheets[1]['cells'][$j][22];
                        $property_address_details = $excel->sheets[1]['cells'][$j][23];

                        ////-----------------------VALIDATION  SHEET 2----------------------------------
                        $uniqueno = $excel->sheets[1]['cells'][$j][24];
                        $data2['state_id'] = $state_id;
                        $data2['reference_sr_no'] = $reference_sr_no;
                        $data2['doc_reg_no'] = $doc_reg_no;
                        $data2['regdate'] = $regdate;
                       // $data['unixTimestamp'] = $unixTimestamp;
                        $data2['doc_reg_date'] = $doc_reg_date;
                        $data2['doc_processing_year'] = $doc_processing_year;
                        $data2['property_sr_no'] = $property_sr_no;
                        $data2['developed_land_types_desc_en'] = $developed_land_types_desc_en;
                        $data2['district_id'] = $district_id;
                        $data2['district_name_en'] = $district_name_en;
                        $data2['taluka_id'] = $taluka_id;
                        $data2['taluka_name_en'] = $taluka_name_en;
                        $data2['village_id'] = $village_id;
                        $data2['village_name_en'] = $village_name_en;
                        $data2['unique_property_no_en'] = $unique_property_no_en;
                        $data2['level_1_desc_en'] = $level_1_desc_en;
                        $data2['list_1_desc_en'] = $list_1_desc_en;
                        $data2['list_2_desc_en'] = $list_2_desc_en;
                        $data2['boundries_east_en'] = $boundries_east_en;
                        $data2['boundries_west_en'] = $boundries_west_en;
                        $data2['boundries_south_en'] = $boundries_south_en;
                        $data2['boundries_north_en'] = $boundries_north_en;
                        $data2['property_address_details'] = $property_address_details;

                        // $errarr2 = $this->validatedata($data2, $fieldlist2);
                        //  //  pr($errarr2);//exit;
                        // if ($this->ValidationError($errarr2)) {
                        //     $inst2=$this->LegacyDataProperty->query("insert into ngdrstab_trn_legacy_data_property(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,developed_land_types_desc_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,unique_property_no_en,level_1_desc_en,list_1_desc_en,level_2_desc_en,list_2_desc_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,property_address_details,uniqueno) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$developed_land_types_desc_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en','$unique_property_no_en','$level_1_desc_en','$list_1_desc_en','$level_2_desc_en','$list_2_desc_en','$boundries_east_en','$boundries_west_en','$boundries_south_en','$boundries_north_en','$property_address_details','$uniqueno')");
                        // }
                        // else {
                        //     $errmsg2 = NULL;


                        //     foreach ($errarr2 as $key2 => $value2) {
                        //         if (!empty($value2)) {
                        //             $errmsg2 .= $key2 . '-' . $value2 . '<br>';
                        //         }
                        //     }
                        //     //echo '<br>err:'.$errmsg;
                        //     $inst_invalid = $this->LegacyDataPropertyInvalidData->query("insert into ngdrstab_trn_legacy_data_property_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,developed_land_types_desc_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,unique_property_no_en,level_1_desc_en,list_1_desc_en,level_2_desc_en,list_2_desc_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,property_address_details,uniqueno,invalid_msg) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$developed_land_types_desc_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en','$unique_property_no_en','$level_1_desc_en','$list_1_desc_en','$level_2_desc_en','$list_2_desc_en','$boundries_east_en','$boundries_west_en','$boundries_south_en','$boundries_north_en','$property_address_details','$uniqueno','$errmsg2')");
                        //     // $this->Session->setFlash('Invalid data is getting uploaded in record no ' . $unique_record . '(' . $errmsg . ')');
                        //     //$this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
                        // }
IF($this ->isValidDate1($doc_reg_date))
{
    pr('date in property tab');
    PR($doc_reg_date);
    PR('TRUE');

   // $inst = $this->LegacyDataMain->query("insert into ngdrstab_trn_legacy_data_main(state_id,state_name_en,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,article_id,article_desc_en,office_district_id,office_district_name_en,office_taluka_id,office_taluka_name_en,office_id,office_name_en,user_id,creation_date,doc_file_nm,uniqueno,updated_flag) values($state_id,'$state_name_en',$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$article_id,'$article_desc_en',$office_district_id,'$office_district_name_en',$office_taluka_id,'$office_taluka_name_en',$office_id,'$office_name_en',$user_id,'$creation_date','$doc_file_nm','$uniqueno','Y')");
   $inst_valid = $this->LegacyDataProperty->query("insert into ngdrstab_trn_legacy_data_property(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,developed_land_types_desc_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,unique_property_no_en,level_1_desc_en,list_1_desc_en,level_2_desc_en,list_2_desc_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,property_address_details,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$developed_land_types_desc_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en','$unique_property_no_en','$level_1_desc_en','$list_1_desc_en','$level_2_desc_en','$list_2_desc_en','$boundries_east_en','$boundries_west_en','$boundries_south_en','$boundries_north_en','$property_address_details','$uniqueno','Y')");
   $inst_valid = $this->LegacyDataProperty->query("insert into ngdrstab_trn_legacy_data_property_final(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,developed_land_types_desc_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,unique_property_no_en,level_1_desc_en,list_1_desc_en,level_2_desc_en,list_2_desc_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,property_address_details,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$developed_land_types_desc_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en','$unique_property_no_en','$level_1_desc_en','$list_1_desc_en','$level_2_desc_en','$list_2_desc_en','$boundries_east_en','$boundries_west_en','$boundries_south_en','$boundries_north_en','$property_address_details','$uniqueno','Y')");
   
   
   PR('DATA STORE INTO MAIN TABLE ');
}
else
{
    pr('in property tab');
    PR($doc_reg_date);
    PR('DATA STORE INTO INVALID  TABLE ');
  pr("insert into ngdrstab_trn_legacy_data_property_invalid_data(state_id,state_name_en,reference_sr_no,doc_reg_no,doc_processing_year,article_id,article_desc_en,office_district_id,office_district_name_en,office_taluka_id,office_taluka_name_en,office_id,office_name_en,user_id,creation_date,doc_file_nm,uniqueno,invalid_msg,updated_flag,doc_reg_date) values($state_id,'$state_name_en',$reference_sr_no,'$doc_reg_no','$doc_processing_year',$article_id,'$article_desc_en',$office_district_id,'$office_district_name_en',$office_taluka_id,'$office_taluka_name_en',$office_id,'$office_name_en',$user_id,'$creation_date','$doc_file_nm',$uniqueno,'','N','$doc_reg_date')");
 // exit();
 $inst_invalid = $this->LegacyDataProperty->query("insert into ngdrstab_trn_legacy_data_property_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,property_sr_no,developed_land_types_desc_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,unique_property_no_en,level_1_desc_en,list_1_desc_en,level_2_desc_en,list_2_desc_en,boundries_east_en,boundries_west_en,boundries_south_en,boundries_north_en,property_address_details,uniqueno,updated_flag,doc_reg_date) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$property_sr_no,'$developed_land_types_desc_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en','$unique_property_no_en','$level_1_desc_en','$list_1_desc_en','$level_2_desc_en','$list_2_desc_en','$boundries_east_en','$boundries_west_en','$boundries_south_en','$boundries_north_en','$property_address_details','$uniqueno','N','$doc_reg_date')");
//  $inst_invalid = $this->LegacyDataProperty->query("insert into ngdrstab_trn_legacy_data_property_invalid_data(state_id,state_name_en,reference_sr_no,doc_reg_no,doc_processing_year,article_id,article_desc_en,office_district_id,office_district_name_en,office_taluka_id,office_taluka_name_en,office_id,office_name_en,user_id,creation_date,doc_file_nm,uniqueno,invalid_msg,updated_flag,doc_reg_date) values($state_id,'$state_name_en',$reference_sr_no,'$doc_reg_no','$doc_processing_year',$article_id,'$article_desc_en',$office_district_id,'$office_district_name_en',$office_taluka_id,'$office_taluka_name_en',$office_id,'$office_name_en',$user_id,'$creation_date','$doc_file_nm',$uniqueno,'','N','$doc_reg_date')");
    PR('DATA STORE INTO INVALID  TABLE ');
}
  }
////-----------------------VALIDATION  SHEET 2----------------------------------
                    $sheet3_no = $excel->sheets[2]["numRows"];
//SHEET 3-------------------------------------
                    for ($k = 2; $k <= $sheet3_no; $k++)
                    {
////-----------------------VALIDATION  SHEET 3----------------------------------
                        $fieldlist3['state_id']['text'] = 'is_numeric';//0-9
                        $fieldlist3['reference_sr_no']['text'] = 'is_numeric';//0-9
                        $fieldlist3['doc_reg_no']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist3['regdate']['text'] = 'is_alphanumspacedash';//DATE FORMAT
                        //$fieldlist['unixTimestamp']['text'] = 'is_alphanumspacedash';
                        $fieldlist3['doc_reg_date']['text'] = 'is_required';
                        $fieldlist3['doc_processing_year']['text'] = 'is_numeric';//0-9
                        $fieldlist3['property_sr_no']['text'] = 'is_numeric';
                        $fieldlist3['usage_main_catg_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        //$fieldlist3['usage_sub_catg_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist3['usage_sub_catg_desc_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
                        //$fieldlist3['usage_sub_catg_desc_en']['text'] = 'is_numeric';//0-9
                        //$fieldlist3['unit_desc_en']['text'] = 'is_alphaspacedashslash';//a-z,A-Z,0-9,Space,-,/
                        $fieldlist3['unit_desc_en']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
                        $fieldlist3['market_value']['text'] = 'is_numeric';//0-9
                        $fieldlist3['cons_amt']['text'] = 'is_numeric';//0-9
                        $this->set('fieldlist', $fieldlist3);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist3));

////-----------------------VALIDATION  SHEET 3----------------------------------
                        $state_id = $excel->sheets[2]['cells'][$k][1];
                        $reference_sr_no = $excel->sheets[2]['cells'][$k][2];
                        $doc_reg_no = $excel->sheets[2]['cells'][$k][3];
                        $regdate = $excel->sheets[2]['cells'][$k][4];
                      //  $unixTimestamp = ($regdate - 25569) * 86400;
                      //  $doc_reg_date = date('Y-m-d', $unixTimestamp);
                      $doc_reg_date =$excel->sheets[2]['cells'][$k][4];
                        $doc_processing_year = $excel->sheets[2]['cells'][$k][5];
                        $property_sr_no = $excel->sheets[2]['cells'][$k][6];
                        //$property_land_sr_no=$excel->sheets[2]['cells'][$k][7];
                        $usage_main_catg_desc_en = $excel->sheets[2]['cells'][$k][7];
                        $usage_sub_catg_desc_en = $excel->sheets[2]['cells'][$k][8];
                        $item_value = $excel->sheets[2]['cells'][$k][9];
                        $unit_desc_en = $excel->sheets[2]['cells'][$k][10];
                        $market_value = $excel->sheets[2]['cells'][$k][11];
                    //    $market_value=  (String) $market_value1;
                       // pr('market value');
                    //    pr($market_value);
                       // exit();
                        $cons_amt = $excel->sheets[2]['cells'][$k][12];
                        ////-----------------------VALIDATION  SHEET ----------------------------------
                        $uniqueno = $excel->sheets[2]['cells'][$k][13];
                        $data3['state_id'] = $state_id;
                        $data3['reference_sr_no'] = $reference_sr_no;
                        $data3['doc_reg_no'] = $doc_reg_no;
                        $data3['regdate'] = $regdate;
                     //   $data['unixTimestamp'] = $unixTimestamp;
                        $data3['doc_reg_date'] = $doc_reg_date;
                        $data3['doc_processing_year'] = $doc_processing_year;
                        $data3['property_sr_no'] = $property_sr_no;
                        $data3['usage_main_catg_desc_en'] = $usage_main_catg_desc_en;
                        $data3['usage_sub_catg_desc_en'] = $usage_sub_catg_desc_en;
                        $data3['item_value'] = $item_value;
                        $data3['unit_desc_en'] = $unit_desc_en;
                        $data3['market_value'] = $market_value;
                        $data3['cons_amt'] = $cons_amt;



                        // $errarr3 = $this->validatedata($data3, $fieldlist3);
                        // //pr($errarr3);
                        // if ($this->ValidationError($errarr3)) {
                        //     $inst3=$this->LegacyDataPropertyDetails->query("insert into ngdrstab_trn_legacy_data_property_details(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en',$market_value,$cons_amt,'$uniqueno')");
                        // } else {
                        //     $errmsg3 = NULL;


                        //     foreach ($errarr3 as $key3 => $value3) {
                        //         if (!empty($value3)) {
                        //             $errmsg3 .= $key3 . '-' . $value3 . '<br>';
                        //         }
                        //     }
                        //     //echo '<br>err:'.$errmsg;
                        //     $inst_invalid = $this->LegacyDataPropertyDetailsInvalidData->query("insert into ngdrstab_trn_legacy_data_property_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,invalid_msg) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en',$market_value,$cons_amt,'$uniqueno','$errmsg3')");
                        //     // $this->Session->setFlash('Invalid data is getting uploaded in record no ' . $unique_record . '(' . $errmsg . ')');
                        //     //$this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
                        // }


                        IF($this ->isValidDate1($doc_reg_date) && $this ->isdigit1($market_value) &&  $this ->isdigit1($cons_amt)  )
                        {
                            pr('date in property DETAIL tab');
                            PR($doc_reg_date);
                            PR('TRUE');
                       
                       
                         
                          $inst3=$this->LegacyDataPropertyDetails->query("insert into ngdrstab_trn_legacy_data_property_details(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,updated_flag)
                                                                                                                        values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en',$market_value,$cons_amt,'$uniqueno','Y')");

                      $inst3=$this->LegacyDataPropertyDetails->query("insert into ngdrstab_trn_legacy_data_property_details_final(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,updated_flag)
                                                                   values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en',$market_value,$cons_amt,'$uniqueno','Y')");
                         
                        //  PR('DATA STORE INTO MAIN TABLE ');
                        //  exit;
                        }
                        else
                        {
                            pr('in property detail latest tab');
                            PR($doc_reg_date);
                            PR('DATA STORE INTO INVALID  TABLE ');
                       

                      //  $inst3=$this->LegacyDataPropertyDetails->query("insert into ngdrstab_trn_legacy_data_property_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,property_land_sr_no,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,doc_reg_date,updated_flag)
                         
                      //values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en','$market_value',$cons_amt,'$uniqueno','$doc_reg_date','N')");
                      $inst3=$this->LegacyDataPropertyDetails->  query("insert into ngdrstab_trn_legacy_data_property_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,doc_reg_date,updated_flag)
 values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$property_sr_no,
'$usage_main_catg_desc_en','$usage_sub_catg_desc_en','$item_value','$unit_desc_en','$market_value',$cons_amt,'$uniqueno',$doc_reg_date,'N')");          
                   
                      PR('DATA STORE INTO INVALID  TABLE ');                  
                        }

                    }


                    ////-----------------------VALIDATION  SHEET 3----------------------------------
//sheet 4
                    $sheet4_no = $excel->sheets[3]["numRows"];
                    for ($l = 2; $l <= $sheet4_no; $l++)
                    {
////-----------------------VALIDATION  SHEET 4----------------------------------
                        $fieldlist4['state_id']['text'] = 'is_numeric';
                        $fieldlist4['reference_sr_no']['text'] = 'is_numeric';
                        $fieldlist4['doc_reg_no']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist4['regdate']['text'] = 'is_numeric';//date format
                       // $fieldlist['unixTimestamp']['text'] = 'is_numeric';
                        $fieldlist4['doc_reg_date']['text'] = 'is_required';//date format
                        $fieldlist4['doc_processing_year']['text'] = 'is_numeric';//0=9
                        $fieldlist4['property_sr_no']['text'] = 'is_numeric';//0-9
                        $fieldlist4['property_land_attribute_no']['text'] = 'is_numeric';//0-9
                        //$fieldlist4['eri_attribute_name']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist4['eri_attribute_name']['text'] = 'is_required';
                       // $fieldlist['paramter_value']['text'] = 'is_alphaspacedashdotcommacolon';
                        $fieldlist4['paramter_value']['text'] = 'is_required';
                        $this->set('fieldlist', $fieldlist4);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist4));
////-----------------------VALIDATION  SHEET 4----------------------------------          


                        $state_id = $excel->sheets[3]['cells'][$l][1];
                        $reference_sr_no = $excel->sheets[3]['cells'][$l][2];
                        $doc_reg_no = $excel->sheets[3]['cells'][$l][3];
                        //$doc_reg_date=$excel->sheets[3]['cells'][$l][4];
                        $regdate = $excel->sheets[3]['cells'][$l][4];
                      //  $unixTimestamp = ($regdate - 25569) * 86400;
                     //   $doc_reg_date = date('Y-m-d', $unixTimestamp);
                     $doc_reg_date = $regdate ;
                        $doc_processing_year = $excel->sheets[3]['cells'][$l][5];
                        $property_sr_no = $excel->sheets[3]['cells'][$l][6];
                        $property_land_attribute_no = $excel->sheets[3]['cells'][$l][7];
                        $eri_attribute_name = $excel->sheets[3]['cells'][$l][8];
                        $paramter_value = $excel->sheets[3]['cells'][$l][9];
////-----------------------VALIDATION  SHEET 4----------------------------------
                        $uniqueno = $excel->sheets[3]['cells'][$l][10];
                        $data4['state_id'] = $state_id;
                        $data4['reference_sr_no'] = $reference_sr_no;
                        $data4['doc_reg_no'] = $doc_reg_no;
                        $data4['regdate'] = $regdate;
                       // $data['unixTimestamp'] = $unixTimestamp;
                        $data4['doc_reg_date'] = $doc_reg_date;
                        $data4['doc_processing_year'] = $doc_processing_year;
                        $data4['property_sr_no'] = $property_sr_no;
                        $data4['property_land_attribute_no'] = $property_land_attribute_no;
                        $data4['eri_attribute_name'] = $eri_attribute_name;
                        $data4['paramter_value'] = $paramter_value;

                        // $errarr4 = $this->validatedata($data4, $fieldlist4);
                        // //pr($errarr4);
                        // if ($this->ValidationError($errarr4)) {
                        //     $inst4=$this->LegacyDataPropertyLandDetails->query("insert into ngdrstab_trn_legacy_data_property_land_details(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno')");
                        // } else
                        // {
                        //     $errmsg4 = NULL;


                        //     foreach ($errarr4 as $key4 => $value4) {
                        //         if (!empty($value4)) {
                        //             $errmsg4 .= $key4 . '-' . $value4 . '<br>';
                        //         }
                        //     }
                        //     //echo '<br>err:'.$errmsg;
                        //     $inst_invalid = $this->LegacyDataPropertyLandDetailsInvalidData->query("insert into ngdrstab_trn_legacy_data_property_land_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno,invalid_msg) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno','$errmsg4')");
                        //     // $this->Session->setFlash('Invalid data is getting uploaded in record no ' . $unique_record . '(' . $errmsg . ')');
                        //     //$this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
                        // }


                        IF($this ->isValidDate1($doc_reg_date)   )
                        {
                            pr('date in land tab');
                            PR($doc_reg_date);
                            PR('TRUE');
                       
                       
                         
                          $inst3=$this->LegacyDataPropertyLandDetails->query("insert into ngdrstab_trn_legacy_data_property_land_details(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno','Y')");
                          $inst3=$this->LegacyDataPropertyLandDetails->query("insert into ngdrstab_trn_legacy_data_property_land_details_final(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno','Y')");
                         
                        //  PR('DATA STORE INTO MAIN TABLE ');
                        //  exit;
                        }
                        else
                        {
                            pr('in property detail...... tab');
                           
                            PR($doc_reg_date);

                            PR('DATA STORE INTO INVALID  TABLE ');
                       

                      //  $inst3=$this->LegacyDataPropertyLandDetails->query("insert into ngdrstab_trn_legacy_data_property_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,property_sr_no,usage_main_catg_desc_en,usage_sub_catg_desc_en,item_value,unit_desc_en,market_value,cons_amt,uniqueno,doc_reg_date,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$property_sr_no,'$usage_main_catg_desc_en','$usage_sub_catg_desc_en',$item_value,'$unit_desc_en','$market_value',$cons_amt,'$uniqueno','$doc_reg_date','N')");
                   
                   
                   
                      $inst3=$this->LegacyDataPropertyLandDetails->query("insert into  ngdrstab_trn_legacy_data_property_land_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno,updated_flag)
                                                                                                                              values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno','N')");  
                   
                                                                                                                            //  ngdrstab_trn_legacy_data_property_land_details_invalid_data
                   
                   
                      // $inst3=$this->LegacyDataPropertyLandDetails->query("insert into ngdrstab_trn_legacy_data_property_details_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,property_sr_no,property_land_attribute_no,eri_attribute_name,paramter_value,uniqueno,doc_reg_date,updated_flag)   values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$property_sr_no,'$property_land_attribute_no','$eri_attribute_name','$paramter_value','$uniqueno',$doc_reg_date,'N')");  
                        PR('DATA STORE INTO INVALID  TABLE ');                  
                        }
                    }


////-----------------------VALIDATION  SHEET 4----------------------------------
//--sheet 5
                    $sheet5_no = $excel->sheets[4]["numRows"];
                    for ($m = 2; $m <= $sheet5_no; $m++)
                    {
////-----------------------VALIDATION  SHEET 5----------------------------------
                        $fieldlist5['state_id']['text'] = 'is_numeric';//0-9
                        $fieldlist5['reference_sr_no']['text'] = 'is_numeric';//0-9
                        $fieldlist5['doc_reg_no']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist5['regdate']['text'] = 'is_alphanumspacedash';//date format
                       // $fieldlist['unixTimestamp']['text'] = 'is_alphanumspacedash';
                        $fieldlist5['doc_reg_date']['text'] = 'is_required';//date format
                        $fieldlist5['doc_processing_year']['text'] = 'is_numeric';
                        $fieldlist5['party_sr_no']['text'] = 'is_numeric';
                        $fieldlist5['party_type_desc_en']['text'] = 'is_alphanumericspace';
                        $fieldlist5['category_name_en']['text'] = 'is_alphanumericspace';
                        //$fieldlist5['party_full_name_en']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist5['party_full_name_en']['text'] = 'is_required';
                       // $fieldlist['father_full_name_en']['text'] = 'is_alphanumspacedash';//a-z,A-Z,0-9,Space,-
                        $fieldlist5['father_full_name_en']['text'] = 'is_required';
                        $fieldlist5['gender']['text'] = 'is_alpha';
                        $this->set('fieldlist', $fieldlist5);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist5));

////-----------------------VALIDATION  SHEET 5----------------------------------
                        $state_id = $excel->sheets[4]['cells'][$m][1];
                        $reference_sr_no = $excel->sheets[4]['cells'][$m][2];
                        $doc_reg_no = $excel->sheets[4]['cells'][$m][3];
                        //$doc_reg_date=$excel->sheets[4]['cells'][$m][4];
                        $doc_reg_date = $excel->sheets[4]['cells'][$m][4];
                     //   $unixTimestamp = ($regdate - 25569) * 86400;
                      //  $doc_reg_date = date('Y-m-d', $unixTimestamp);
                        $doc_processing_year = $excel->sheets[4]['cells'][$m][5];
                        $party_sr_no = $excel->sheets[4]['cells'][$m][6];
                        $party_type_desc_en = $excel->sheets[4]['cells'][$m][7];
                        $category_name_en = $excel->sheets[4]['cells'][$m][8];
                        $party_full_name_en = $excel->sheets[4]['cells'][$m][9];
                        $father_full_name_en = $excel->sheets[4]['cells'][$m][10];
                        $gender = $excel->sheets[4]['cells'][$m][11];
////-----------------------VALIDATION  SHEET 5----------------------------------                        
                        $uniqueno = $excel->sheets[4]['cells'][$m][12];
                        $data5['state_id'] = $state_id;
                        $data5['reference_sr_no'] = $reference_sr_no;
                        $data5['doc_reg_no'] = $doc_reg_no;
                       // $data5['regdate'] = $regdate;
                       // $data['unixTimestamp'] = $unixTimestamp;
                        $data5['doc_reg_date'] = $doc_reg_date;
                        $data5['doc_processing_year'] = $doc_processing_year;
                        $data5['party_sr_no'] = $party_sr_no;
                        $data5['party_type_desc_en'] = $party_type_desc_en;
                        $data5['category_name_en'] = $category_name_en;
                        $data5['party_full_name_en'] = $party_full_name_en;
                        $data5['father_full_name_en'] = $father_full_name_en;
                        $data5['gender'] = $gender;
                        $errarr5 = $this->validatedata($data5, $fieldlist5);


                        IF($this ->isValidDate1($doc_reg_date)   )
                        {
                          pr('in party tab');
                            pr('date in tab');
                            PR($doc_reg_date);
                            PR('TRUE');
                       
                       
                         
                            $inst5=$this->LegacyDataParty->query("insert into ngdrstab_trn_legacy_data_party(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno,updated_flag)
                            values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender','$uniqueno','Y')");
                            $inst5=$this->LegacyDataParty->query("insert into ngdrstab_trn_legacy_data_party_final(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno,updated_flag)
                            values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender','$uniqueno','Y')");
                         
                        PR('DATA STORE INTO party TABLE ');
                        //  exit;
                        }
                        else
                        {
                          pr('in party tab');
                           
                            PR($doc_reg_date);

                            PR('DATA STORE INTO partyb INVALID  TABLE ');
                       

                            $inst5=$this->LegacyDataParty->query("insert into ngdrstab_trn_legacy_data_party_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno,updated_flag,doc_reg_date)
                                                          values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender',$uniqueno,'N','$doc_reg_date')");
                    }


                        //pr($errarr5);
                        // if ($this->ValidationError($errarr5)) {
                        //     $inst5=$this->LegacyDataParty->query("insert into ngdrstab_trn_legacy_data_party(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno)
                        //      values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender','$uniqueno')");
                        // } else {
                        //     $errmsg5 = NULL;


                        //     foreach ($errarr5 as $key5 => $value5) {
                        //         if (!empty($value5)) {
                        //             $errmsg5 .= $key5 . '-' . $value5 . '<br>';
                        //         }
                        //     }
                            //echo '<br>err:'.$errmsg;
                       //     $inst_invalid = $this->LegacyDataPartyInvalidData->query("insert into ngdrstab_trn_legacy_data_party_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno,invalid_msg) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender','$uniqueno','$errmsg5')");
                            // $this->Session->setFlash('Invalid data is getting uploaded in record no ' . $unique_record . '(' . $errmsg . ')');
                            //$this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
                        }
                   
////-----------------------VALIDATION  SHEET 5----------------------------------
//sheet 6
                    $sheet6_no = $excel->sheets[5]["numRows"];
                    for ($n = 2; $n <= $sheet6_no; $n++)
                     {
////-----------------------VALIDATION  SHEET 6----------------------------------
                        $fieldlist6['state_id']['text'] = 'is_numeric';
                        $fieldlist6['reference_sr_no']['text'] = 'is_numeric';
                        $fieldlist6['doc_reg_no']['text'] = 'is_alphanumspacedash';
                        $fieldlist6['regdate']['text'] = 'is_alphanumspacedash';//date format
                       // $fieldlist['unixTimestamp']['text'] = 'is_alphanumspacedash';
                        $fieldlist6['doc_reg_date']['text'] = 'is_required';//date format
                        $fieldlist6['doc_processing_year']['text'] = 'is_numeric';
                        $fieldlist6['payment_sr_no']['text'] = 'is_numeric';
                        $fieldlist6['fee_item_desc_en']['text'] = 'is_alphanumspacedash';
                        $fieldlist6['final_value']['text'] = 'is_numeric';
                        $this->set('fieldlist', $fieldlist6);
                        $this->set('result_codes', $this->getvalidationruleset($fieldlist6));

////-----------------------VALIDATION  SHEET 6----------------------------------                        
                        $state_id = $excel->sheets[5]['cells'][$n][1];
                        $reference_sr_no = $excel->sheets[5]['cells'][$n][2];
                        $doc_reg_no = $excel->sheets[5]['cells'][$n][3];
                        //$doc_reg_date=$excel->sheets[5]['cells'][$n][4];
                     //   $regdate = $excel->sheets[5]['cells'][$n][4];
                      //  $unixTimestamp = ($regdate - 25569) * 86400;
                        $doc_reg_date =$excel->sheets[5]['cells'][$n][4];
                        $doc_processing_year = $excel->sheets[5]['cells'][$n][5];
                        $payment_sr_no = $excel->sheets[5]['cells'][$n][6];
                        $fee_item_desc_en = $excel->sheets[5]['cells'][$n][7];
                        $final_value = $excel->sheets[5]['cells'][$n][8];
////-----------------------VALIDATION  SHEET 6----------------------------------
                        $uniqueno = $excel->sheets[5]['cells'][$n][9];
                        $data6['state_id'] = $state_id;
                        $data6['reference_sr_no'] = $reference_sr_no;
                        $data6['doc_reg_no'] = $doc_reg_no;
                        $data6['regdate'] = $regdate;
                       // $data['unixTimestamp'] = $unixTimestamp;
                        $data6['doc_reg_date'] = $doc_reg_date;
                        $data6['doc_processing_year'] = $doc_processing_year;
                        $data6['payment_sr_no'] = $payment_sr_no;
                        $data6['fee_item_desc_en'] = $fee_item_desc_en;
                        $data6['final_value'] = $final_value;
                        $errarr6 = $this->validatedata($data6, $fieldlist6);
//pr($errarr6);


IF($this ->isValidDate1($doc_reg_date)   )
{
  pr('in fee tab');
    pr('date in tab');
    PR($doc_reg_date);
    PR('TRUE');


 
    $inst6=$this->LegacyDataPayment->query("insert into ngdrstab_trn_legacy_data_payment(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,payment_sr_no,fee_item_desc_en,final_value,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$payment_sr_no,'$fee_item_desc_en',$final_value,'$uniqueno','Y')");
    $inst6=$this->LegacyDataPayment->query("insert into ngdrstab_trn_legacy_data_payment_final(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,payment_sr_no,fee_item_desc_en,final_value,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$payment_sr_no,'$fee_item_desc_en',$final_value,'$uniqueno','Y')");
 
PR('DATA STORE INTO party TABLE ');
//  exit;
}
else
{
  pr('in party tab');
   
    PR($doc_reg_date);

    PR('DATA STORE INTO PAYMNET INVALID  TABLE ');

    $inst6=$this->LegacyDataPayment->query("insert into ngdrstab_trn_legacy_data_payment_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,payment_sr_no,fee_item_desc_en,final_value,uniqueno,updated_flag,doc_reg_date)
                                                                                           values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$payment_sr_no,'$fee_item_desc_en',$final_value,'$uniqueno','N','$doc_reg_date')");
   // $inst5=$this->LegacyDataParty->query("insert into ngdrstab_trn_legacy_data_party_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,party_sr_no,party_type_desc_en,category_name_en,party_full_name_en,father_full_name_en,gender,uniqueno,updated_flag,doc_reg_date)
   // values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$party_sr_no,'$party_type_desc_en','$category_name_en','$party_full_name_en','$father_full_name_en','$gender','$uniqueno','N','$doc_reg_date')");
}
                        // if ($this->ValidationError($errarr6))
                        // {
                        //      $inst6=$this->LegacyDataPayment->query("insert into ngdrstab_trn_legacy_data_payment(state_id,reference_sr_no,doc_reg_no,doc_reg_date,doc_processing_year,payment_sr_no,fee_item_desc_en,final_value,uniqueno,updated_flag) values($state_id,$reference_sr_no,'$doc_reg_no','$doc_reg_date','$doc_processing_year',$payment_sr_no,'$fee_item_desc_en',$final_value,'$uniqueno','Y')");
                        // } else
                        // {
                        //     $errmsg6 = NULL;


                        //     foreach ($errarr6 as $key6 => $value6)
                        //     {
                        //         if (!empty($value6))
                        //          {
                        //             $errmsg6 .= $key6 . '-' . $value6 . '<br>';
                        //         }
                        //     }
                        //     //echo '<br>err:'.$errmsg;
                        //     $inst_invalid = $this->LegacyDataPaymentInvalidData->query("insert into ngdrstab_trn_legacy_data_payment_invalid_data(state_id,reference_sr_no,doc_reg_no,doc_processing_year,payment_sr_no,fee_item_desc_en,final_value,uniqueno,invalid_msg,updated_flag,doc_reg_date)
                        //                                                                                                                    values($state_id,$reference_sr_no,'$doc_reg_no','$doc_processing_year',$payment_sr_no,'$fee_item_desc_en',$final_value,'$uniqueno','$errmsg6','N','$doc_reg_date')");
                        //     // $this->Session->setFlash('Invalid data is getting uploaded in record no ' . $unique_record . '(' . $errmsg . ')');
                        //     //$this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
                        // }
                    }


               // }
                /* $cell_disp=$excel->sheets[0]['cells'][1][4];
                  $cell_disp=$excel->sheets[1]['cells'][1][2];
                  pr($cell_disp);

                  foreach($excel->sheets as $k=>$data)
                  {
                  echo "\n\n ";
                  pr($excel->boundsheets[$k]);
                  echo "\n\n";

                  foreach($data['cells'] as $row)
                  {
                  foreach($row as $cell)
                  {
                  //echo "$cell\t";
                  pr($cell);
                  // echo '\t';
                  }
                  echo "\n\n";
                  }
                  } */

                /* $x=1;
                  while($x;=$excel->sheets[0]['numRows']) {
                  echo "\t&lt;tr>\n";
                  $y=1;
                  while($y&lt;=$excel->sheets[0]['numCols']) {
                  $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
                  echo "\t\t&lt;td>$cell&lt;/td>\n";
                  $y++;
                  }
                  echo "\t&lt;/tr>\n";
                  $x++;
                  } */


                //pr($this->request->data);
                /*
                 if ($this->request->data['upload_excel_to_tbl']['upload_file']['error'] == 0)
                {
                  $file_ext = pathinfo($this->request->data['upload_excel_to_tbl']['upload_file']['name'], PATHINFO_EXTENSION);
                  //pr( $file_ext );
                  $path = $this->file_config->find('first', array('fields' => array('filepath')));
                  //pr($path['file_config']['filepath']);
                  $new_name='temp';
                  $createFolder1 = $this->create_folder($path['file_config']['filepath'], 'Legacy_Documents/');
                  $success = move_uploaded_file($this->request->data['upload_excel_to_tbl']['upload_file']['tmp_name'], $createFolder1 . '/' . $new_name . '.' . $file_ext);

                  $file='D:\NGDRS_Upload_ga\Legacy_Documents\temp.csv';
                  $handle = fopen($file, "r");
                  while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
                  {
                  //echo $nick_name = $filesop[0];
                  //$first_name = $filesop[1];
                  //$last_name = $filesop[2];
                  //pr($filesop);
                  $state_id=$filesop[0];
                  $state_name_en=$filesop[1];
                  $district_id=$filesop[2];
                  $district_name_en=$filesop[3];
                  $taluka_id=$filesop[4];
                  $taluka_name_en=$filesop[5];
                  $village_id=$filesop[6];
                  $village_name_en=$filesop[7];
                  $article_id=$filesop[8];
                  $article_desc_en=$filesop[9];
                  $office_id=$filesop[10];
                  $office_name_en=$filesop[11];
                  $doc_reg_no=$filesop[12];
                  $doc_reg_date=$filesop[13];
                  $property_id=$filesop[14];
                  $developed_land_types_id=$filesop[15];
                  $developed_land_types_desc_en=$filesop[16];
                  $unique_property_no_en=$filesop[17];
                  $property_address_details=$filesop[18];
                  $survey_number=$filesop[19];
                  $plot_number=$filesop[20];
                  $khasra_number=$filesop[21];
                  $block_number=$filesop[22];
                  $usage_main_catg_id=$filesop[23];
                  $usage_main_catg_desc_en=$filesop[24];
                  $usage_sub_catg_id=$filesop[25];
                  $usage_sub_catg_desc_en=$filesop[26];
                  $level1_id =$filesop[27];
                  $level1_list_id =$filesop[28];
                  $level2_id =$filesop[29];
                  $level2_list_id =$filesop[30];
                  $boundries_east_en =$filesop[31];
                  $boundries_west_en =$filesop[32];
                  $boundries_south_en =$filesop[33];
                  $boundries_north_en =$filesop[34];
                  $area=$filesop[35];
                  $area_unit =$filesop[36];
                  $val_id =$filesop[37];
                  $fee_calc_id =$filesop[38];
                  $party_id=$filesop[39];
                  $party1_full_name_en =$filesop[40];
                  $father1_full_name_en =$filesop[41];
                  $party1_type_id=$filesop[42];
                  $party1_type_desc_en=$filesop[43];
                  $party1_catg_id =$filesop[44];
                  $category1_name_en=$filesop[45];

                  $party2_full_name_en=$filesop[46];
                  $father2_full_name_en =$filesop[47];
                  $party2_type_id=$filesop[48];
                  $party2_type_desc_en=$filesop[49];
                  $party2_catg_id =$filesop[50];
                  $category2_name_en=$filesop[51];


                  $market_value =$filesop[52];
                  $cons_amt =$filesop[53];
                  $stamp_duty =$filesop[54];
                  $registration_fee =$filesop[55];
                  $processing_fee =$filesop[56];
                  $other_fee=$filesop[57];
                  $inst=$this->LegacyData->query("insert into ngdrstab_trn_legacy_data(state_id,state_name_en,district_id,district_name_en,taluka_id,taluka_name_en,village_id,village_name_en,article_id,article_desc_en,office_id,office_name_en,doc_reg_no,doc_reg_date,property_id,developed_land_types_id,developed_land_types_desc_en,unique_property_no_en,property_address_details,survey_number,plot_number,khasra_number,block_number,usage_main_catg_id,usage_main_catg_desc_en,usage_sub_catg_id,usage_sub_catg_desc_en,level1_id ,level1_list_id ,level2_id ,level2_list_id ,boundries_east_en ,boundries_west_en ,boundries_south_en ,boundries_north_en ,area ,area_unit ,val_id ,fee_calc_id ,party_id ,party1_full_name_en ,father1_full_name_en ,party1_type_id ,party1_type_desc_en,party1_catg_id ,category1_name_en,party2_full_name_en ,father2_full_name_en ,party2_type_id ,party2_type_desc_en,party2_catg_id ,category2_name_en,market_value ,cons_amt ,stamp_duty ,registration_fee ,processing_fee ,other_fee) values ($state_id,'$state_name_en',$district_id,'$district_name_en',$taluka_id,'$taluka_name_en',$village_id,'$village_name_en',$article_id,'$article_desc_en',$office_id,'$office_name_en','$doc_reg_no','$doc_reg_date',$property_id,$developed_land_types_id,'$developed_land_types_desc_en','$unique_property_no_en','$property_address_details','$survey_number','$plot_number','$khasra_number','$block_number',$usage_main_catg_id,'$usage_main_catg_desc_en',$usage_sub_catg_id,'$usage_sub_catg_desc_en',$level1_id ,$level1_list_id ,$level2_id ,$level2_list_id ,'$boundries_east_en' ,'$boundries_west_en' ,'$boundries_south_en' ,'$boundries_north_en' ,$area ,'$area_unit' ,$val_id ,$fee_calc_id ,$party_id ,'$party1_full_name_en' ,'$father1_full_name_en' ,$party1_type_id ,'$party1_type_desc_en',$party1_catg_id ,'$category1_name_en','$party2_full_name_en' ,'$father2_full_name_en' ,$party2_type_id ,'$party2_type_desc_en',$party2_catg_id ,'$category1_name_en',$market_value ,$cons_amt ,$stamp_duty ,$registration_fee ,$processing_fee ,$other_fee)");
                  // pr($inst);


                  }

                  } */
//$sucess=check($inst);

pr('at end');
                $this->Session->setFlash('Data Uploaded Successfully');
             //// $this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
           //  $this->redirect(array('action' => 'upload_excel_to_tbl', $this->Session->read('csrftoken')));
           
            } ////post
        }     //post
      }
         catch (Exception $ex)
          {
            pr($ex);exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
         }    


    }   //function
    // function check($inst)
    //  {
    // }
    function srch_legacy_appl()
    {
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
        }
        catch (Exception $ex) {
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_party_type()
    {
        $this->loadModel('partytype');
        // $partytype = $this->partytype->find("list", array('fields' => array('party_type_id', 'party_type_desc_en'), 'conditions' => array('article_id' => $this->request->data['article_id'])));
        $partytype_name = $this->partytype->find('list', array('fields' => array('partytype.party_type_id', 'partytype.party_type_desc_en'), 'conditions' => array('partytype.display_flag' => 'C')));
        $options1['conditions'] = array('m.article_id' => trim($this->request->data['article_id']));
        $options1['joins'] = array(array('table' => 'ngdrstab_mst_article_partytype_mapping', 'alias' => 'm', 'type' => 'INNER', 'conditions' => array('partytype.party_type_id=m.party_type_id')),);
        $options1['fields'] = array('partytype.party_type_id', 'partytype.party_type_desc_en');
        $party = $this->partytype->find('list', $options1);
        $partytype = $partytype_name + $party;
        //pr($partytype);
        echo json_encode($partytype);
        exit;
    }

}