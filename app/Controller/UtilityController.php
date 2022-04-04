<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of utility
 *
 * @author acer
 */
class UtilityController extends AppController {
 public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('QRcode', 'qrcode_test', 'Barcode', 'barcode_test','ValidationReport'); 
    }
    function checkrule() {
        try {
            $this->autoRender = false;

            $this->loadModel('usagelnkitemlist');
            $this->loadModel('evalsubrule');
            $this->loadModel('evalrule');
            $this->loadModel('subrule');


            $input = $this->usagelnkitemlist->find("list", array('fields' => array('usage_param_code', 'id'), 'conditions' => array('usage_param_type_id' => 1)));
            $input['RRR'] = 100;
            $input['RR1'] = 200;
            $input['RR2'] = 300;
            $input['ABE'] = 400;
            $rules = $this->subrule->find("all", array('fields' => array('evalrule_id', 'subrule_id', 'evalsubrule_cond1', 'evalsubrule_cond2', 'evalsubrule_cond3', 'evalsubrule_cond4', 'evalsubrule_cond5', 'evalsubrule_formula1', 'evalsubrule_formula2', 'evalsubrule_formula3', 'evalsubrule_formula4', 'evalsubrule_formula5'),
                'conditions' => array('evalrule_id' => 144)));
            $rules = $this->evalrule->find("all", array('fields' => array('evalrule_id', 'evalrule_cond1', 'evalrule_cond2', 'evalrule_cond3', 'evalrule_cond4', 'evalrule_cond5', 'evalrule_formula1', 'evalrule_formula2', 'evalrule_formula3', 'evalrule_formula4', 'evalrule_formula5'),
                'conditions' => array('subrule_flag' => 'N')));

//            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
//            $file->write(json_encode($json2array));
//            foreach ($rules as $rule) {
//               // pr($rule);exit;
//                $flag = 0;
//                foreach ($rule['subrule'] as $formula) {
//                    if ($flag == 1) {
//                        $flag ++;
//                    } else {
//
//                        foreach ($input as $key => $value) {
//                            $formula = str_replace($key, $value, $formula);
//                        }
//                        if ($formula) {
//                            echo $rule['subrule']['evalrule_id'] . ':' . $formula . "<br>";
//                            eval("return ($formula);");
//                        }
//                    }
//                }
//            }
            foreach ($rules as $rule) {
                // pr($rule);exit;
                $flag = 0;
                foreach ($rule['evalrule'] as $formula) {
                    if ($flag == 1) {
                        $flag ++;
                    } else {

                        foreach ($input as $key => $value) {
                            $formula = str_replace($key, $value, $formula);
                        }
                        if ($formula) {
                            echo $rule['evalrule']['evalrule_id'] . ':' . $formula . "<br>";
                            eval("return ($formula);");
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }
    
    
   public function QRcode($str,$path=NULL) {
        App::import('Vendor', 'QRcode/QRcode');
        $this->autoRender = FALSE;
        $err = "H";
        $qrcode = new QRcode(utf8_encode($str), $err);
        $qrcode->disableBorder();      
     if(!is_null($path)){
        $qrcode->displayPNG($w=100, $background=array(255,255,255), $color=array(0,0,0), $path, $quality = 0);       
     }else{
         $qrcode->displayPNG(200);
     }       
    }

    public function Barcode($str) {
        App::import('Vendor', 'Barcode/Barcode');
        $this->autoRender = FALSE;
        $generator = new Barcode();
        echo $generator->getBarcode($str, $generator::TYPE_CODE_128);
    }

    public function qrcode_test() {
        
    }
    public function barcode_test() {
        
    }
     public function ValidationReport() {
        
        $fieldlist=$this->params->params['pass']; 
        $this->loadModel('file_config');
        $fileconf = $this->file_config->find("first");
        $this->autoRender = FALSE;
        if (!empty($fileconf)) {
            //pr($fileconf);
            $ROOT = $fileconf['file_config']['filepath'];
            $FOLDER = "validation";
            $CONTRO = $this->params->params['referrer']['c'];
            $ACT = $this->params->params['referrer']['a']; 
            $mode = 777;
             if (!file_exists($ROOT)) {
                mkdir($ROOT, $mode);
            }
            if (!file_exists($ROOT . $FOLDER)) {
                mkdir($ROOT . $FOLDER, $mode);
            }
            if (!file_exists($ROOT . $FOLDER . "/" . $CONTRO)) {
                mkdir($ROOT . $FOLDER . "/" . $CONTRO, $mode);
            }
            $filestorepath=$ROOT . $FOLDER . "/" . $CONTRO . "/" . $ACT . ".pdf";
            if (!file_exists($filestorepath)) {
               
                    $html = "<style> .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 10px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { border-bottom: 1px solid #E1EEF4; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }

</style>";

                    

                    $html.="<div class='datagrid'><table>
<thead><tr><th colspan=2>Form Name</th><th width='20%'>Time </th><th width='30%'> IP </th></tr></thead>

<tbody>
<tr><th colspan=2> " . $CONTRO . " - " . $ACT . " </th><th>" . date('d-M-Y h:s:i a') . " </th><th>" . $this->request->clientIp() . " </th></tr>
</tbody>
";
                 
                    $html.="<thead><tr><th width='15%'>Sr.No.</th><th>Field Name</th><th>Field Type</th><th>Rules</th></tr></thead>
<tbody>";
                    $i=0;
                      
                    foreach ($fieldlist as $fieldname => $field) {
                        $i++;
                        foreach ($field as $fieldtype => $rules) {
                            if (!empty($rules)) {
                                $roles_arr = explode(",", $rules);
                                $rhtml = "<ul >";
                                foreach ($roles_arr as $rule) {
                                    $rhtml.="<li>$rule</li>";
                                }
                                $rhtml.="</ul>";
                                
                                $html.="<tr class='alt'><td>$i</td><td>$fieldname</td><td>$fieldtype</td><td>$rhtml</td></tr>";
                            }
                        }
                    }
                    
                    $html.="</tbody></table></div>";

            
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mPDF');
            $mpdf = new mPDF('utf-8', 'A4');
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $waterMark="Validation Report";
            if ($waterMark) {
                $mpdf->SetWatermarkText($waterMark);
                $mpdf->watermarkTextAlpha = 0.2;
                $mpdf->showWatermarkText = true;
            }
           
            $mpdf->WriteHTML($html);
            $mpdf->Output($filestorepath, 'F'); // 'I' for Display PDF in Next Tab
   
            }
        }
    }


}
