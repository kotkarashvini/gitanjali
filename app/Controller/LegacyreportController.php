<?php
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');



class LegacyreportController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();
//
//        if ($this->name == 'CakeError') {
//            $this->layout = 'error';
//        }
        $this->response->disableCache();
        $this->Auth->allow('report');
       // $this->Auth->allow('Fee');
    }
    
    
       public function report() {
           
           array_map(array($this, 'loadModel'), array('NGDRSErrorCode'));
           $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['from_date']['text'] = 'is_required';
            $fieldlist['to_date']['text'] = 'is_required';
            
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
           // pr($fieldlist);exit;
           
           
           
           
           if ($this->request->is('post')) {
               $errarr = $this->validatedata($this->request->data['report'], $fieldlist);
                    if ($this->validationError($errarr)) {
               
               
               $user_id=$this->Auth->user('user_id');
              
               $from_date = $this->request->data['report']['from_date'];
             // pr($from_date);exit;
                $fromdt = "'" . date('Y-m-d', strtotime($from_date)) . "'";
                $to_date = $this->request->data['report']['to_date'];
                $todt = "'" . date('Y-m-d', strtotime($to_date)) . "'";
               
               
               
            $disposaldetails = NULL;
                    $disposaldetails = "<div style='text-align:center'><h2></h2></div>";
//                    $disposaldetails .= "<table style='width:100%'><tr><td colspan='2' style='text-align:right'><h4> Date :" . date("d-m-yy") . "</h4></td></tr><tr>";
//                   $disposaldetails .= "</table><br>";
                     $disposaldetails .= "<table style='width:100%'><tr><td colspan='2' style='text-align:right'><h4> Date :" . date("d-m-yy") . "</h4></td></tr><tr><td style='text-align:left'></td>";
                    $disposaldetails .= "<td style='text-align:right'><h4> From Date :$from_date To Date :$to_date</h4></td></tr></table><br>";
                    $disposaldetails .= "<table style='border-collapse: collapse;border: 1px solid black; width:100%'><caption><h3></h3></caption>"
                            . "<tr>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Token No.</th>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Registration Number</th>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Registration Date</th>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Party Details</th>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Property Details</th>"
                            . "<th style='border: 1px solid black;background-color: #4CAF50;color: white;'>Fee Details</th>"
                            . "</tr>";

                     $this->loadModel('Leg_generalinformation');
                     
                   //  pr($this->Session->read("legacyinfo"));exit;
         if($this->Session->read('legacyinfo') == 'Y'){            
                     
                      $string = $this->Leg_generalinformation->query("select token_no,final_doc_reg_no,to_char(final_stamp_date,'dd/mm/yyyy')final_stamp_date ,
array((select STRING_AGG ( party_type_desc_en ||'|'|| party_full_name_en ||'|'|| address_en,'$') from ngdrstab_trn_legacy_party_entry_new party 
inner join ngdrstab_mst_party_type on ngdrstab_mst_party_type.party_type_id=party.party_type_id


 where party.token_no=app.token_no)) party_details,

array((select STRING_AGG( property_id ||'|'|| district_name_en ||'|'|| taluka_name_en ||'|'|| boundries_east_en||'|'|| boundries_west_en ||'|'|| boundries_south_en||'|'|| boundries_north_en,'$') from ngdrstab_trn_legacy_property_details_entry property 
inner join ngdrstab_conf_admblock3_district dist on dist.district_id=property.district_id
inner join ngdrstab_conf_admblock5_taluka taluka on taluka.taluka_id=property.taluka_id
left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=property.village_id


 where property.token_no=app.token_no  group by property_id)) property_details,


array((select concat(property_id||'|'|| ngdrstab_mst_usage_sub_category.usage_sub_catg_desc_en ||':'|| item_value ||' '|| unit_desc_en)  from ngdrstab_trn_legacy_valuation
inner join ngdrstab_trn_legacy_valuation_details on ngdrstab_trn_legacy_valuation_details.val_id=ngdrstab_trn_legacy_valuation.val_id
inner join ngdrstab_mst_unit on unit_id=ngdrstab_trn_legacy_valuation_details.area_unit
inner join ngdrstab_mst_usage_sub_category on ngdrstab_mst_usage_sub_category.usage_sub_catg_id=ngdrstab_trn_legacy_valuation.usage_sub_catg_id
where ngdrstab_trn_legacy_valuation.token_no=app.token_no order by property_id)) Area,

array((select concat(fee_item_desc_en,':',final_value) from  ngdrstab_trn_legacy_fee_calculation_detail
inner join ngdrstab_mst_article_fee_items on ngdrstab_mst_article_fee_items.fee_item_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_item_id
inner join ngdrstab_trn_legacy_fee_calculation on  ngdrstab_trn_legacy_fee_calculation.fee_calc_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_calc_id
 where ngdrstab_trn_legacy_fee_calculation.token_no=app.token_no )) Fee_details

 

from ngdrstab_trn_legacy_application_submitted as app
where app.user_id=$user_id and created::date >= $fromdt::date and  created::date   <= $todt::date
");
         }
         else if ($this->Session->read("authinfo") == 'Y') 
         {
           // pr($user_id);exit;
                        $string = $this->Leg_generalinformation->query("select app.token_no,final_doc_reg_no,to_char(final_stamp_date,'dd/mm/yyyy')final_stamp_date ,
array((select STRING_AGG ( party_type_desc_en ||'|'|| party_full_name_en ||'|'|| address_en,'$') from ngdrstab_trn_legacy_party_entry_new party 
inner join ngdrstab_mst_party_type on ngdrstab_mst_party_type.party_type_id=party.party_type_id


 where party.token_no=app.token_no)) party_details,

array((select STRING_AGG( property_id ||'|'|| district_name_en ||'|'|| taluka_name_en ||'|'|| boundries_east_en||'|'|| boundries_west_en ||'|'|| boundries_south_en||'|'|| boundries_north_en,'$') from ngdrstab_trn_legacy_property_details_entry property 
inner join ngdrstab_conf_admblock3_district dist on dist.district_id=property.district_id
inner join ngdrstab_conf_admblock5_taluka taluka on taluka.taluka_id=property.taluka_id
left join ngdrstab_conf_admblock7_village_mapping village on village.village_id=property.village_id


 where property.token_no=app.token_no  group by property_id)) property_details,


array((select concat(property_id||'|'|| ngdrstab_mst_usage_sub_category.usage_sub_catg_desc_en ||':'|| item_value ||' '|| unit_desc_en)  from ngdrstab_trn_legacy_valuation
inner join ngdrstab_trn_legacy_valuation_details on ngdrstab_trn_legacy_valuation_details.val_id=ngdrstab_trn_legacy_valuation.val_id
inner join ngdrstab_mst_unit on unit_id=ngdrstab_trn_legacy_valuation_details.area_unit
inner join ngdrstab_mst_usage_sub_category on ngdrstab_mst_usage_sub_category.usage_sub_catg_id=ngdrstab_trn_legacy_valuation.usage_sub_catg_id
where ngdrstab_trn_legacy_valuation.token_no=app.token_no order by property_id)) Area,

array((select concat(fee_item_desc_en,':',final_value) from  ngdrstab_trn_legacy_fee_calculation_detail
inner join ngdrstab_mst_article_fee_items on ngdrstab_mst_article_fee_items.fee_item_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_item_id
inner join ngdrstab_trn_legacy_fee_calculation on  ngdrstab_trn_legacy_fee_calculation.fee_calc_id=ngdrstab_trn_legacy_fee_calculation_detail.fee_calc_id
 where ngdrstab_trn_legacy_fee_calculation.token_no=app.token_no )) Fee_details

 

from ngdrstab_trn_legacy_application_submitted as app
inner join ngdrstab_trn_legacy_generalinformation gen on gen.token_no=app.token_no
where authorized_user_id=$user_id and app.created::date >= $fromdt::date and  app.created::date   <= $todt::date
"); 
         }
              
 //pr($string);exit;
        
                     
           
                     

                    foreach ($string as $string) {
                        $str=substr($string[0]['party_details'],1,-1);
                      $str=str_replace('"', '', $str);
                        $str= explode("$", $str);   
                      //  pr($str);exit;
                      /////////////////////////
                         //For Property Details
                         $property=substr($string[0]['property_details'],1,-1);
                      $property=str_replace('"', '', $property);
                       // $property=str_replace(",", '<br>', $property);
                        $property= explode(",", $property);
                      ////////////////////////
                   $fee=substr($string[0]['fee_details'],1,-1);
                      $fee=str_replace('"', '', $fee);
                       // $fee= explode(",", $fee);
                       //pr($fee);exit;
                      /////////////////
                //  $str_arr= explode("|", $str);
                            $disposaldetails .= "<tr style='border: 1px solid black;'>"
                                    . "<td style='border: 1px solid black;'>" . $string[0]['token_no'] . "</td>"
                                    . "<td style='border: 1px solid black;'>" . $string[0]['final_doc_reg_no'] . "</td>"
                                    . "<td style='border: 1px solid black;'>" . $string[0]['final_stamp_date'] . "</td>";  
                                  // ."<td style='border: 1px solid black;'>". $str ."</td></tr>";
                                  //  ."<td style='border: 1px solid black;'>Party Type : ". $str ."</td></tr>"
                                    //$disposaldetails .= "<tr style='border: 1px solid black;'>";
                                    
                                   $disposaldetails .="<td style='border: 1px solid black;width:700px'> ";
                                           foreach ($str as $str1) 
                                           {
                                               $tmp='';
                                              // $disposaldetails .= $str."<br>"; 
                                                $str_arr= explode("|", $str1);
                                                
                                                    //  $tmp.='<b>Party Type :</b>'.$str_arr[0] .''.'<b> Party Name:</b>'.$str_arr[1].''.'<b> Party Address:</b>'.$str_arr[2].',';
                                                $tmp.='<b>Party Type :</b>'.@$str_arr[0] .''.'<b> Party Name:</b>'.@$str_arr[1].''.'<b> Party Address:</b>'.@$str_arr[2].',';
                                                
                                                $disposaldetails .= $tmp."<br>"; 
                                           }
                                   $disposaldetails .="</td>";
                                   $disposaldetails .="<td style='border: 1px solid black;width:700px'> ";
                                           foreach ($property as $property1) 
                                           {
                                               $prop_tmp='';
                                              // $disposaldetails .= $str."<br>"; 
                                                $prop_arr= explode("|", $property1);
                                               
                                                      //$prop_tmp.='<b>Property Id :</b>'.$prop_arr[0] .''.'<b> Property Details:</b>'.$prop_arr[1].',';
                                                      //$prop_tmp.='<b>Property Id :</b>'.$prop_arr[0] .''.'<b> Property Address:</b>'.@$prop_arr[1].' '.@$prop_arr[2].',';
                                                     $prop_tmp.='<b>Property Id :</b>'.@$prop_arr[0] .''.'<b> Property Address:</b>'.@$prop_arr[1].' '.@$prop_arr[2].'<b> Boundries East :</b>'.@$prop_arr[3].'<b> Boundries West :</b>'.@$prop_arr[4] .''.'<b> Boundries South: </b>'.@$prop_arr[5].'<b>Boundries North:</b>'.@$prop_arr[6].',';
                                                $disposaldetails .= $prop_tmp."<br>"; 
                                           }
                                   $disposaldetails .="</td>";
                                  
                                  $disposaldetails.= "<td style='border: 1px solid black;'>" . $fee . "</td>"; 
                                  
                                      
                        
                    }
                    $disposaldetails .= "</table>";
                   // exit;
                    // pr($disposaldetails);exit;
                    if($string!=NULL)
                    {
                        
                    $this->create_pdf($disposaldetails, 'Legacy', 'A4-L', 'NGDRS', 'D');
                    }
                    else
                    {
                        $this->Session->setFlash('No Records found..');
                    }
           }
           }
           
       }
       
       
       
           public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $waterMark = '', $display_flag = 'D') {
        try {
            //  pr("Hii Rani");
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
}