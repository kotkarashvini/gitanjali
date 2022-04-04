<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'Fees'); // mention at top
App::import('Controller', 'Registration'); // mention at top

class FineController extends AppController {

    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function calculate_fine($tokenno) {
        try {
//            echo $article;exit;
            $returnflag = 1;
            $this->loadModel('FineRuleMapping');
            $this->loadModel('fees_calculation_detail');
            $this->loadModel('fees_calculation');
            $this->loadModel('ApplicationSubmitted');
            $this->loadModel('party');
            $this->loadModel('regconfig');
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $office_id = $this->Auth->User("office_id");
            $application = $this->ApplicationSubmitted->query("SELECT app.*,info.user_id As citizen_user_id,info.local_language_id ,info.article_id ,presentation_date,exec_date FROM ngdrstab_trn_application_submitted app,ngdrstab_mst_article article,ngdrstab_trn_generalinformation info WHERE app.token_no=info.token_no AND info.article_id=article.article_id  AND app.token_no=? ", array($tokenno));
            //   pr($application);
            //    exit;
            if (!empty($application)) { // Document found
                $fine_ids = array();
                $months = 0;
                $monthsarr = array();
                $regctl = new RegistrationController();
                $regctl->constructClasses();
                $stampconfig = $regctl->stamp_and_functions_config();

                /*
                 *   Fine Case 1 Start
                 *   Delay Presentation
                 */
                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 70, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($regconf)) {
                    $stampflag = NULL;
                    foreach ($stampconfig as $stamprec) {
                        if (isset($stamprec['functions'])) {
                            foreach ($stamprec['functions'] as $funrec) {
                                if ($funrec['action'] == 'document_presentation') {
                                    $stampflag = $stamprec['stamp_title'];
                                }
                            }
                        }
                    }
                    if (!is_null($stampflag) && $application[0][0][$stampflag . '_flag'] == 'N') {
                        $confstr = $regconf[0]['regconfig']['info_value'] ? $regconf[0]['regconfig']['info_value'] : '0 M';
                        $confarr = explode(" ", $confstr);
                        if (!empty($application[0][0]['exec_date'])) {
                            $datetime1 = date_create($application[0][0]['exec_date']);
                            $datetime2 = date_create(date('Y-m-d'));
                            $interval = date_diff($datetime1, $datetime2);
                            $months = $interval->y * 12 + $interval->m;
                            if ($interval->d > 0) {
                                $months++;
                            }
                            // pr($months);exit;
                            if ($confarr['1'] == 'M' && $months > $confarr[0]) {
                                $finefeecheck = $this->FineRuleMapping->query("SELECT map.fine_id,map.fee_rule_id FROM ngdrstab_mst_finerule_mapping AS map ,ngdrstab_trn_fee_calculation AS fee WHERE map.fee_rule_id=fee.fee_rule_id AND fee.token_no=? AND map.article_id=?  AND map.fine_id=?", array($tokenno, $application[0][0]['article_id'], 2));
                                if (empty($finefeecheck)) {
                                    $fine_ids[1] = 1;
                                    $monthsarr[1] = $months;
                                }
                            }
                        }
                    }
                }
                /*
                 *   Fine Case 1 End
                 */


                /*
                 *  Fine Case 2 Start
                 *  Delay Admmission
                 */

                $regconf = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 71, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($regconf)) {
                    $confstr = $regconf[0]['regconfig']['info_value'] ? $regconf[0]['regconfig']['info_value'] : '0 M';
                    $confarr = explode(" ", $confstr);

                    $admission_date = NULL;
                    $stampflag = NULL;
                    foreach ($stampconfig as $stamprec) {
                        if (isset($stamprec['functions'])) {
                            foreach ($stamprec['functions'] as $funrec) {
                                if ($funrec['action'] == 'party') {
                                    $stampflag = $stamprec['stamp_title'];
                                }
                            }
                        }
                    }
                    if (!is_null($stampflag)) {
                        if ($application[0][0][$stampflag . '_flag'] == 'Y') {
                            $admission_date = $application[0][0][$stampflag . '_date'];
                        }
                    }


                    if (!is_null($admission_date) && $application[0][0]['final_stamp_flag'] == 'N') {
                        $datetime1 = date_create(date('Y-m-d'));
                        $datetime2 = date_create($admission_date);
                        $interval = date_diff($datetime1, $datetime2);
                        $months = $interval->y * 12 + $interval->m;
                        if ($interval->d > 0) {
                            $months++;
                        }
                        //pr($months);
                        if ($confarr['1'] == 'M' && $months > $confarr[0]) {
                            $finefeecheck = $this->FineRuleMapping->query("SELECT map.fine_id,map.fee_rule_id FROM ngdrstab_mst_finerule_mapping AS map ,ngdrstab_trn_fee_calculation AS fee WHERE map.fee_rule_id=fee.fee_rule_id AND fee.token_no=? AND map.article_id=?  AND map.fine_id=?", array($tokenno, $application[0][0]['article_id'], 1));
                            if (empty($finefeecheck)) {
                                $fine_ids[2] = 2;
                                $monthsarr[2] = $months;
                            }
                        }
                    }
                }
                /*
                 *   Fine Case 2 End
                 */

//pr($fine_ids);exit;


                if (!empty($fine_ids)) { // Fine True
                    foreach ($fine_ids as $key => $fine_id) {
                        $finefee = $this->FineRuleMapping->find("list", array('fields' => array('fine_id', 'fee_rule_id'), 'conditions' => array('article_id' => $application[0][0]['article_id'], 'fine_id' => $fine_id)));
                        if (!empty($finefee) && is_numeric($finefee[$fine_id])) {
                            $feedetails = $this->fees_calculation->query("SELECT item.fee_param_code,item.fee_item_id,FEED.fee_item_value FROM ngdrstab_trn_fee_calculation FEE 
                                JOIN ngdrstab_trn_fee_calculation_detail FEED ON FEED.fee_calc_id=FEE.fee_calc_id
                                 JOIN ngdrstab_mst_article_fee_items item ON item.fee_item_id=FEED.fee_item_id
                                
                                WHERE FEE.token_no=? AND FEE.article_id=? AND item.fee_param_type_id=1
                                
                               ", array($tokenno, $application[0][0]['article_id']));
                            $frmdata = array();
                            foreach ($feedetails as $fee) {
                                $frmdata[$fee[0]['fee_param_code']] = $fee[0]['fee_item_value'];
                            }
                            $feedetails = $this->fees_calculation->find("first", array('conditions' => array('token_no' => $tokenno, 'article_id' => $application[0][0]['article_id'], 'delete_flag' => 'N')));
                            if (!empty($feedetails)) {
                                $consamount = $feedetails['fees_calculation']['cons_amt'] ? $feedetails['fees_calculation']['cons_amt'] : 0;
                            } else {
                                $consamount = 0;
                            }

                            if (isset($frmdata['FAA'])) {
                                if (is_numeric($frmdata['FAA'])) {
                                    if ($frmdata['FAA'] < $consamount) {
                                        $frmdata['FAA'] = $consamount;
                                    }
                                } else {
                                    $frmdata['FAA'] = $consamount;
                                }
                            }


                            $frmdata['ZAA'] = $monthsarr[$fine_id]; // hard coded
                            $frmdata['fee_rule_id'] = $finefee[$fine_id];
                            $frmdata['token_no'] = $tokenno;
                            // $frmdata['article_id']=9997;
                            // 
                          //  pr($frmdata);
                            //exit;
                          //  pr(date('Y-m-d'));
                            $cal = new FeesController();
                            $cal->constructClasses();
                            $cald = $cal->calculate_fees($frmdata, 'Y');
                         //   pr($cald);
                          //  exit;
                            if (is_numeric($cald)) {
                                $returnflag = 1;
                            } else {
                                $returnflag = $cald;
                            }
                        } else {
                            $returnflag = 'Fine Feerule Not Found';
                        }
                    } //loop
                }// Fine True End
            } else {
                $returnflag = 'Document Not Found!';
            }// Document found End

            return $returnflag;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Error :' . $ex)
            );
        }
    }

}
