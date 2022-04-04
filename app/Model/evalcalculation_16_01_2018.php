<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evalcalculation
 *
 * @author Administrator
 */
class evalcalculation extends AppModel {

    //put your code here
    //for subrule
  public function calculation_part($usageitemlist, $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata) {
        foreach ($usageitemlist as $usageitem) {
            if (isset($formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']])) {
                $evalformula = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalformula);
            }
        }
        
        $evalformula = str_replace('RRR', $rate, $evalformula);
        $evalformula = str_replace('RR2', $fixedrate, $evalformula);
       $evalformula = str_replace('RR5', $additionalrate2, $evalformula);
        $evalformula = str_replace('RR1', $additionalrate, $evalformula);
        $evalformula = str_replace('ABE', $comparisonrate, $evalformula);
    
        return $evalformula;
    }
  public function calculation_part_feet($usageitemlist, $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata) {
        foreach ($usageitemlist as $usageitem) {
          //  pr($formdata);exit;
            if (isset($formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']."act"])) {
                $evalformula = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']."act"], $evalformula);
            }
        }
        $evalformula = str_replace('RRR', $rate, $evalformula);
        $evalformula = str_replace('RR2', $fixedrate, $evalformula);
//        $evalformula = str_replace('RRC', $rate2, $evalformula);
        $evalformula = str_replace('RR1', $additionalrate, $evalformula);
        $evalformula = str_replace('ABE', $comparisonrate, $evalformula);
//        pr($evalformula);exit;
        return $evalformula;
    }

  public function rate_factor_effect($rateflag = NULL, $formdata = NULL, $rate = NULL, $additionalrate = NULL,$additionalrate2=NULL, $fixedrate = NULL, $comparisonrate = NULL, $rate_rivision_formula = NULL, $ratefactorarray = NULL) {
        $effectiverate = NULL;
        $ratefactor=NULL;
        if(isset($ratefactorarray[0]['ratefactor']['rate_factor']) && $ratefactorarray[0]['ratefactor']['rate_factor']!=NULL)
        {
           $ratefactor= $ratefactorarray[0]['ratefactor']['rate_factor'];
        }
        if ($rateflag == 'RRR' AND $rate != NULL) {
            $rate_rivision_formula = str_replace('RRR', $rate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR1', $additionalrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR2', $fixedrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('ABE', $comparisonrate, $rate_rivision_formula);
             $rate_rivision_formula = str_replace('RR5', $additionalrate2, $rate_rivision_formula);
//pr($ratefactorarray);exit;
            if ($formdata['rate_revision_flag'] == 'Y' && $additionalrate != NULL && $ratefactor != NULL && $rate_rivision_formula != NULL) {
                $rivision_rate = eval("return ($rate_rivision_formula);");
                $effectiverate = $ratefactor * $rivision_rate;
            } else if ($ratefactor != NULL) {
             //   pr($ratefactor);exit;
                $effectiverate = eval("return (" . $rate . '*' . $ratefactor . ");");
            }else{
              $effectiverate =$rate;  
            }
        } else if ($rateflag == 'RR2' AND $fixedrate != NULL) {

            $rate_rivision_formula = str_replace('RRR', $rate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR1', $additionalrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR2', $fixedrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('ABE', $comparisonrate, $rate_rivision_formula);
             $rate_rivision_formula = str_replace('RR5', $additionalrate2, $rate_rivision_formula);
            if ($formdata['rate_revision_flag'] == 'Y' && $additionalrate != NULL  && $ratefactor != NULL && $rate_rivision_formula != NULL) {
                $rivision_rate = eval("return ($rate_rivision_formula);");
                $effectiverate = $ratefactor * $rivision_rate;
            } else if ($ratefactor != NULL) {
                $effectiverate = eval("return (" . $fixedrate . '*' . $ratefactor . ");");
            }else{
              $effectiverate =$fixedrate;  
            }
        } else if ($rateflag == 'ABE' AND $comparisonrate != NULL) {

            $rate_rivision_formula = str_replace('RRR', $rate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR1', $additionalrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR2', $fixedrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('ABE', $comparisonrate, $rate_rivision_formula);
            $rate_rivision_formula = str_replace('RR5', $additionalrate2, $rate_rivision_formula);
            if ($formdata['rate_revision_flag'] == 'Y' && $comparisonrate != NULL && $ratefactor != NULL && $rate_rivision_formula != NULL) {
                $rivision_rate = eval("return ($rate_rivision_formula);");
                $effectiverate = $ratefactor * $rivision_rate;
            } else if ($ratefactor != NULL) {
                $effectiverate = eval("return (" . $comparisonrate . '*' . $ratefactor . ");");
            }else{
                 $effectiverate =$comparisonrate; 
            }
        }



        return $effectiverate;
    }

    // $rate1 ---  Land Rate
    // $rate2 ---  Construction Rate

  public function multiplecalculation($formdata = NULL, $json2array = NULL, $subrulecondition = NULL, $rate = NULL, $fixedrate = NULL, $comparisonrate = NULL, $additionalrate = NULL,$additionalrate2=NULL, $ratefactorarray = NULL) {
 
        $evalcondition1 = $subrulecondition['evalsubrule_cond1'];
        $evalcondition2 = $subrulecondition['evalsubrule_cond2'];
        $evalcondition3 = $subrulecondition['evalsubrule_cond3'];
        $evalcondition4 = $subrulecondition['evalsubrule_cond4'];
        $evalcondition5 = $subrulecondition['evalsubrule_cond5'];
        // pr($subrulecondition);
        //pr($json2array['usageitemlist']);exit;
        foreach ($json2array['usageitemlist'] as $usageitem) {
            if ($usageitem['usagelinkcategory']['evalrule_id'] == $subrulecondition['evalrule_id']) {
                $evalcondition1 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition1);
                $evalcondition2 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition2);
                $evalcondition3 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition3);
                $evalcondition4 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition4);
                $evalcondition5 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition5);
            }
        }



        // replace rate in conditions 
        $ratefields = array('RRR' => $rate, 'RR1' => $additionalrate, 'RR2' => $fixedrate, 'ABE' => $comparisonrate,'RR5'=>$additionalrate2);
        foreach ($ratefields as $keyfield => $keyval) {
            $evalcondition1 = str_replace($keyfield, $keyval, $evalcondition1);
            $evalcondition2 = str_replace($keyfield, $keyval, $evalcondition2);
            $evalcondition3 = str_replace($keyfield, $keyval, $evalcondition3);
            $evalcondition4 = str_replace($keyfield, $keyval, $evalcondition4);
            $evalcondition5 = str_replace($keyfield, $keyval, $evalcondition5);
        }


        $result1 = 0;
        $result2 = 0;
        $result3 = 0;
        $result4 = 0;
        $result5 = 0;
        $result = 0;
        $maxvalresult = 0;
        $finalresult = 0;
        $flag = 0;
        $evalformula = NULL;
        $maxvalformula = NULL;
        $usedFormula = NULL;
        $rate_rivision_formula = NULL;
        if ($evalcondition1 != NULL || $subrulecondition['evalsubrule_formula1'] != NULL) {
            if ($evalcondition1 != NULL) {
                if (eval("return ($evalcondition1);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula1'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula1'];
//echo $rate;exit;
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                   //echo $rate;exit;
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                    $usedFormula = $evalformula;
                    $result1 = eval("return ($evalformula);");
                    $result = $result1;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula1'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula1'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                $usedFormula = $evalformula;
                $result1 = eval("return ($evalformula);");
                $result = $result1;
            }
        } else {
            $flag = 1;
        }

//        pr($formdata);exit;

        if (($evalcondition2 != NULL || $subrulecondition['evalsubrule_formula2'] != NULL) && $flag != 1) {
            if ($evalcondition2 != NULL) {
                if (eval("return ($evalcondition2);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula2'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula2'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $usedFormula = $evalformula;
                    $result2 = eval("return ($evalformula);");
                    $result = $result2;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula2'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula2'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $usedFormula = $evalformula;
                $result2 = eval("return ($evalformula);");
                $result = $result2;
            }
        } else {
            $flag = 1;
        }


        if (($evalcondition3 != NULL || $subrulecondition['evalsubrule_formula3'] != NULL) && $flag != 1) {
            if ($evalcondition3 != NULL) {
                if (eval("return ($evalcondition3);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula3'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula3'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $usedFormula = $evalformula;
                    $result3 = eval("return ($evalformula);");
                    $result = $result3;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula3'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula3'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $usedFormula = $evalformula;
                $result3 = eval("return ($evalformula);");
                $result = $result3;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition4 != NULL || $subrulecondition['evalsubrule_formula4'] != NULL) && $flag != 1) {
            if ($evalcondition4 != NULL) {
                if (eval("return ($evalcondition4);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula4'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula4'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $usedFormula = $evalformula;
                    $result4 = eval("return ($evalformula);");
                    $result = $result4;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula4'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula4'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $usedFormula = $evalformula;
                $result4 = eval("return ($evalformula);");
                $result = $result4;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition5 != NULL || $subrulecondition['evalsubrule_formula5'] != NULL) && $flag != 1) {
            if ($evalcondition5 != NULL) {
                if (eval("return ($evalcondition5);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula5'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula5'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                    $usedFormula = $evalformula;
                    $result5 = eval("return ($evalformula);");
                    $result = $result5;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula5'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula5'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $additionalrate2,$fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                $usedFormula = $evalformula;
                $result5 = eval("return ($evalformula);");
                $result = $result5;
            }
        } else {
            $flag = 1;
        }





        if ($subrulecondition['max_value_condition_flag'] == "Y") {
            $maxvalformula = $subrulecondition['max_value_formula'];
            $rate_rivision_formula = $subrulecondition['rate_revision_formula_max'];

//              pr($comparisonrate); 
            $comparisonrate = $this->rate_factor_effect($rateflag = 'ABE', $formdata, $comparisonrate, $additionalrate,$additionalrate2, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

//             exit;

            $maxvalformula = $this->calculation_part($json2array['usageitemlist'], $maxvalformula, $rate, $fixedrate, $additionalrate,$additionalrate2, $comparisonrate, $formdata);
            $maxvalformula = str_replace('ABE', $comparisonrate, $maxvalformula);
            $maxvalresult = eval("return ($maxvalformula);");

//            if ($maxvalresult > $result and $result > 0) {//ORIGINAL
            if ($maxvalresult > $result and $result > 0) {
//                ECHO "HI";
                $finalresult = $maxvalresult;
                $usedFormula = $maxvalformula;
            } else {
                $finalresult = $result;
            }

            if ($subrulecondition['subrule_id'] == 1034) {
//                  pr($maxvalresult); 
//                   pr($result);
//                  pr($maxvalformula);
//                  PR($maxvalresult);
//                exit;
            }
        } else {
            $finalresult = $result;
        }

        $resultarray['derivedresult'] = $result;
        $resultarray['maxvalresult'] = $maxvalresult;
        $resultarray['finalresult'] = $finalresult;
        $resultarray['usedFormula'] = $usedFormula;
         $resultarray['eff_rrr'] = $rate;
         $resultarray['eff_rr2']=$fixedrate;
        
        $resultarray['rate_rivision_formula'] = $rate_rivision_formula;
        return $resultarray;
    }
  public function multiplecalculation_feet($formdata = NULL, $json2array = NULL, $subrulecondition = NULL, $rate = NULL, $fixedrate = NULL, $comparisonrate = NULL, $additionalrate = NULL, $ratefactorarray = NULL) {

        $evalcondition1 = $subrulecondition['evalsubrule_cond1'];
        $evalcondition2 = $subrulecondition['evalsubrule_cond2'];
        $evalcondition3 = $subrulecondition['evalsubrule_cond3'];
        $evalcondition4 = $subrulecondition['evalsubrule_cond4'];
        $evalcondition5 = $subrulecondition['evalsubrule_cond5'];
        // pr($subrulecondition);
        //pr($json2array['usageitemlist']);exit;
        foreach ($json2array['usageitemlist'] as $usageitem) {
            if ($usageitem['usagelinkcategory']['evalrule_id'] == $subrulecondition['evalrule_id']) {
                $evalcondition1 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition1);
                $evalcondition2 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition2);
                $evalcondition3 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition3);
                $evalcondition4 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition4);
                $evalcondition5 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition5);
            }
        }



        // replace rate in conditions 
        $ratefields = array('RRR' => $rate, 'RR1' => $additionalrate, 'RR2' => $fixedrate, 'ABE' => $comparisonrate);
        foreach ($ratefields as $keyfield => $keyval) {
            $evalcondition1 = str_replace($keyfield, $keyval, $evalcondition1);
            $evalcondition2 = str_replace($keyfield, $keyval, $evalcondition2);
            $evalcondition3 = str_replace($keyfield, $keyval, $evalcondition3);
            $evalcondition4 = str_replace($keyfield, $keyval, $evalcondition4);
            $evalcondition5 = str_replace($keyfield, $keyval, $evalcondition5);
        }


        $result1 = 0;
        $result2 = 0;
        $result3 = 0;
        $result4 = 0;
        $result5 = 0;
        $result = 0;
        $maxvalresult = 0;
        $finalresult = 0;
        $flag = 0;
        $evalformula = NULL;
        $maxvalformula = NULL;
        $usedFormula = NULL;
        $rate_rivision_formula = NULL;
        if ($evalcondition1 != NULL || $subrulecondition['evalsubrule_formula1'] != NULL) {
            if ($evalcondition1 != NULL) {
                if (eval("return ($evalcondition1);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula1'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula1'];
//echo $rate;exit;
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                   //echo $rate;exit;
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $usedFormula = $evalformula;
                    $result1 = eval("return ($evalformula);");
                    $result = $result1;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula1'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula1'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $usedFormula = $evalformula;
                $result1 = eval("return ($evalformula);");
                $result = $result1;
            }
        } else {
            $flag = 1;
        }

//        pr($formdata);exit;

        if (($evalcondition2 != NULL || $subrulecondition['evalsubrule_formula2'] != NULL) && $flag != 1) {
            if ($evalcondition2 != NULL) {
                if (eval("return ($evalcondition2);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula2'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula2'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $usedFormula = $evalformula;
                    $result2 = eval("return ($evalformula);");
                    $result = $result2;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula2'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula2'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $usedFormula = $evalformula;
                $result2 = eval("return ($evalformula);");
                $result = $result2;
            }
        } else {
            $flag = 1;
        }


        if (($evalcondition3 != NULL || $subrulecondition['evalsubrule_formula3'] != NULL) && $flag != 1) {
            if ($evalcondition3 != NULL) {
                if (eval("return ($evalcondition3);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula3'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula3'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $usedFormula = $evalformula;
                    $result3 = eval("return ($evalformula);");
                    $result = $result3;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula3'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula3'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $usedFormula = $evalformula;
                $result3 = eval("return ($evalformula);");
                $result = $result3;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition4 != NULL || $subrulecondition['evalsubrule_formula4'] != NULL) && $flag != 1) {
            if ($evalcondition4 != NULL) {
                if (eval("return ($evalcondition4);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula4'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula4'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $usedFormula = $evalformula;
                    $result4 = eval("return ($evalformula);");
                    $result = $result4;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula4'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula4'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $usedFormula = $evalformula;
                $result4 = eval("return ($evalformula);");
                $result = $result4;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition5 != NULL || $subrulecondition['evalsubrule_formula5'] != NULL) && $flag != 1) {
            if ($evalcondition5 != NULL) {
                if (eval("return ($evalcondition5);") == 1) {
                    $evalformula = $subrulecondition['evalsubrule_formula5'];
                    $rate_rivision_formula = $subrulecondition['rate_revision_formula5'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                    $usedFormula = $evalformula;
                    $result5 = eval("return ($evalformula);");
                    $result = $result5;
                }
            } else {
                $evalformula = $subrulecondition['evalsubrule_formula5'];
                $rate_rivision_formula = $subrulecondition['rate_revision_formula5'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $evalformula = $this->calculation_part_feet($json2array['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                $usedFormula = $evalformula;
                $result5 = eval("return ($evalformula);");
                $result = $result5;
            }
        } else {
            $flag = 1;
        }





        if ($subrulecondition['max_value_condition_flag'] == "Y") {
            $maxvalformula = $subrulecondition['max_value_formula'];
            $rate_rivision_formula = $subrulecondition['rate_revision_formula_max'];

//              pr($comparisonrate); 
            $comparisonrate = $this->rate_factor_effect($rateflag = 'ABE', $formdata, $comparisonrate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

//             exit;

            $maxvalformula = $this->calculation_part_feet($json2array['usageitemlist'], $maxvalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
            $maxvalformula = str_replace('ABE', $comparisonrate, $maxvalformula);
            $maxvalresult = eval("return ($maxvalformula);");

//            if ($maxvalresult > $result and $result > 0) {//ORIGINAL
            if ($maxvalresult > $result and $result > 0) {
//                ECHO "HI";
                $finalresult = $maxvalresult;
                $usedFormula = $maxvalformula;
            } else {
                $finalresult = $result;
            }

            if ($subrulecondition['subrule_id'] == 1034) {
//                  pr($maxvalresult); 
//                   pr($result);
//                  pr($maxvalformula);
//                  PR($maxvalresult);
//                exit;
            }
        } else {
            $finalresult = $result;
        }

        $resultarray['derivedresult'] = $result;
        $resultarray['maxvalresult'] = $maxvalresult;
        $resultarray['finalresult'] = $finalresult;
        $resultarray['usedFormula'] = $usedFormula;
         $resultarray['eff_rrr'] = $rate;
         $resultarray['eff_rr2']=$fixedrate;
        
        $resultarray['rate_rivision_formula'] = $rate_rivision_formula;
        return $resultarray;
    }

    public function singlecalculation($formdata = NULL, $json2array = NULL, $rate = NULL, $fixedrate = NULL, $jsonarray = NULL, $comparisonrate = NULL, $additionalrate = NULL, $additionalrate2=NULL,$ratefactorarray = NULL) {


        $evalcondition1 = $json2array['evalrule']['evalrule_cond1'];
        $evalcondition2 = $json2array['evalrule']['evalrule_cond2'];
        $evalcondition3 = $json2array['evalrule']['evalrule_cond3'];
        $evalcondition4 = $json2array['evalrule']['evalrule_cond4'];
        $evalcondition5 = $json2array['evalrule']['evalrule_cond5'];

        foreach ($jsonarray['usageitemlist'] as $usageitem) {
            $evalcondition1 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition1);
            $evalcondition2 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition2);
            $evalcondition3 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition3);
            $evalcondition4 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition4);
            $evalcondition5 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition5);
        }
          // replace rate in conditions 
        $ratefields = array('RRR' => $rate, 'RR1' => $additionalrate, 'RR2' => $fixedrate, 'ABE' => $comparisonrate,'RR5'=>$additionalrate2);
        foreach ($ratefields as $keyfield => $keyval) {
            $evalcondition1 = str_replace($keyfield, $keyval, $evalcondition1);
            $evalcondition2 = str_replace($keyfield, $keyval, $evalcondition2);
            $evalcondition3 = str_replace($keyfield, $keyval, $evalcondition3);
            $evalcondition4 = str_replace($keyfield, $keyval, $evalcondition4);
            $evalcondition5 = str_replace($keyfield, $keyval, $evalcondition5);
        }
        
        $result1 = 0;
        $result2 = 0;
        $result3 = 0;
        $result4 = 0;
        $result5 = 0;
        $result = 0;
        $maxvalresult = 0;
        $finalresult = 0;
        $usedFormula = NULL;
        $flag = 0;
        $evalformula = NULL;
        $maxvalformula = NULL;
        if ($evalcondition1 != NULL || $json2array['evalrule']['evalrule_formula1'] != NULL) {
            if ($evalcondition1 != NULL) {
                if (eval("return ($evalcondition1);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula1'];
                    $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula1'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                    $usedFormula = $evalformula;
                    $result1 = eval("return ($evalformula);");
                    $result = $result1;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula1'];
                $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula1'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

               $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata); $usedFormula = $evalformula;
                $result1 = eval("return ($evalformula);");
                $result = $result1;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition2 != NULL || $json2array['evalrule']['evalrule_formula2'] != NULL) && $flag != 1) {
            if ($evalcondition2 != NULL) {
                if (eval("return ($evalcondition2);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula2'];
                    $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula2'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                   $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata); $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $usedFormula = $evalformula;
                    $result2 = eval("return ($evalformula);");
                    $result = $result2;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula2'];
                $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula2'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $usedFormula = $evalformula;
                $result2 = eval("return ($evalformula);");
                $result = $result2;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition3 != NULL || $json2array['evalrule']['evalrule_formula3'] != NULL) && $flag != 1) {
            if ($evalcondition3 != NULL) {
                if (eval("return ($evalcondition3);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula3'];
                    $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula3'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $usedFormula = $evalformula;
                    $result3 = eval("return ($evalformula);");
                    $result = $result3;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula3'];
                $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula3'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $usedFormula = $evalformula;
                $result3 = eval("return ($evalformula);");
                $result = $result3;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition4 != NULL || $json2array['evalrule']['evalrule_formula4'] != NULL) && $flag != 1) {
            if ($evalcondition4 != NULL) {
                if (eval("return ($evalcondition4);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula4'];
                    $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula4'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $usedFormula = $evalformula;
                    $result4 = eval("return ($evalformula);");
                    $result = $result4;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula4'];
                $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula4'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $usedFormula = $evalformula;
                $result4 = eval("return ($evalformula);");
                $result = $result4;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition5 != NULL || $json2array['evalrule']['evalrule_formula5'] != NULL) && $flag != 1) {
            if ($evalcondition5 != NULL) {
                if (eval("return ($evalcondition5);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula5'];
                    $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula5'];
                    $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                    $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                    $usedFormula = $evalformula;
                    $result5 = eval("return ($evalformula);");
                    $result = $result5;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula5'];
                $rate_rivision_formula = $json2array['evalrule']['rate_revision_formula5'];
                $rate = $this->rate_factor_effect($rateflag = 'RRR', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
                $fixedrate = $this->rate_factor_effect($rateflag = 'RR2', $formdata, $rate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);

                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                $usedFormula = $evalformula;
                $result5 = eval("return ($evalformula);");
                $result = $result5;
            }
        }


        if ($json2array['evalrule']['max_value_condition_flag'] == "Y") {
            $maxvalformula = $json2array['evalrule']['max_value_formula'];

            $maxvalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $additionalrate2,$comparisonrate, $formdata); $this->calculation_part($jsonarray['usageitemlist'], $maxvalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
            $maxvalformula = str_replace('ABE', $comparisonrate, $maxvalformula);
            $maxvalresult = eval("return ($maxvalformula);");

            // pr($rate)."-".pr($result);
            // pr($comparisonrate)."-".pr($maxvalresult);


            if ($maxvalresult > $result and $result > 0) {
                $finalresult = $maxvalresult;
                $usedFormula = $maxvalformula;
            } else {
                $finalresult = $result;
            }
        } else {
            $finalresult = $result;
        }


        $resultarray['derivedresult'] = $result;
        $resultarray['maxvalresult'] = $maxvalresult;
        $resultarray['finalresult'] = $finalresult;
        $resultarray['usedFormula'] = $usedFormula;
        $resultarray['rate_rivision_formula'] = $rate_rivision_formula;
         $resultarray['eff_rrr'] = $rate;
         $resultarray['eff_rr2']=$fixedrate;

        //   pr($resultarray);exit;
        return $resultarray;
    }

    public function singlecalculationslab($formdata = NULL, $json2array = NULL, $rate = NULL, $slabfield = NULL, $slabfieldvalue = NULL, $fixedrate = NULL, $jsonarray = NULL, $comparisonrate = NULL, $additionalrate = NULL, $ratefactorarray = NULL) {
        $evalcondition1 = $json2array['evalrule']['evalrule_cond1'];
        $evalcondition2 = $json2array['evalrule']['evalrule_cond2'];
        $evalcondition3 = $json2array['evalrule']['evalrule_cond3'];
        $evalcondition4 = $json2array['evalrule']['evalrule_cond4'];
        $evalcondition5 = $json2array['evalrule']['evalrule_cond5'];

        foreach ($jsonarray['usageitemlist'] as $usageitem) {
            $evalcondition1 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition1);
            $evalcondition2 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition2);
            $evalcondition3 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition3);
            $evalcondition4 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition4);
            $evalcondition5 = str_replace($usageitem['usagelinkcategory']['uasge_param_code'], $formdata['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $evalcondition5);
        }
        $result1 = 0;
        $result2 = 0;
        $result3 = 0;
        $result4 = 0;
        $result5 = 0;
        $result = 0;
        $maxvalresult = 0;
        $finalresult = 0;
        $usedFormula = NULL;
        $flag = 0;
        $evalformula = NULL;
        $maxvalformula = NULL;
        if ($evalcondition1 != NULL || $json2array['evalrule']['evalrule_formula1'] != NULL) {
            if ($evalcondition1 != NULL) {
                if (eval("return ($evalcondition1);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula1'];
                    $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $usedFormula = $evalformula;
                    $result1 = eval("return ($evalformula);");
                    $result = $result1;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula1'];
                $explodearray = explode("RRR*", $evalformula);
                if (count($explodearray) > 1) {
                    $usedFormula = substr($explodearray[1], 0, 3);
                }
                $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $usedFormula = $evalformula;
                $result1 = eval("return ($evalformula);");
                $result = $result1;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition2 != NULL || $json2array['evalrule']['evalrule_formula2'] != NULL) && $flag != 1) {
            if ($evalcondition2 != NULL) {
                if (eval("return ($evalcondition2);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula2'];
                    $explodearray = explode("RRR*", $evalformula);
                    if (count($explodearray) > 1) {
                        $usedFormula = substr($explodearray[1], 0, 3);
                    }
                    $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $usedFormula = $evalformula;
                    $result2 = eval("return ($evalformula);");
                    $result = $result2;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula2'];
                $explodearray = explode("RRR*", $evalformula);
                if (count($explodearray) > 1) {
                    $usedFormula = substr($explodearray[1], 0, 3);
                }
                $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $usedFormula = $evalformula;
                $result2 = eval("return ($evalformula);");
                $result = $result2;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition3 != NULL || $json2array['evalrule']['evalrule_formula3'] != NULL) && $flag != 1) {
            if ($evalcondition3 != NULL) {
                if (eval("return ($evalcondition3);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula3'];
                    $explodearray = explode("RRR*", $evalformula);
                    if (count($explodearray) > 1) {
                        $usedFormula = substr($explodearray[1], 0, 3);
                    }
                    $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $usedFormula = $evalformula;
                    $result3 = eval("return ($evalformula);");
                    $result = $result3;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula3'];
                $explodearray = explode("RRR*", $evalformula);
                if (count($explodearray) > 1) {
                    $usedFormula = substr($explodearray[1], 0, 3);
                }
                $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $usedFormula = $evalformula;
                $result3 = eval("return ($evalformula);");
                $result = $result3;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition4 != NULL || $json2array['evalrule']['evalrule_formula4'] != NULL) && $flag != 1) {
            if ($evalcondition4 != NULL) {
                if (eval("return ($evalcondition4);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula4'];
                    $explodearray = explode("RRR*", $evalformula);
                    if (count($explodearray) > 1) {
                        $usedFormula = substr($explodearray[1], 0, 3);
                    }
                    $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $usedFormula = $evalformula;
                    $result4 = eval("return ($evalformula);");
                    $result = $result4;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula4'];
                $explodearray = explode("RRR*", $evalformula);
                if (count($explodearray) > 1) {
                    $usedFormula = substr($explodearray[1], 0, 3);
                }
                $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $usedFormula = $evalformula;
                $result4 = eval("return ($evalformula);");
                $result = $result4;
            }
        } else {
            $flag = 1;
        }

        if (($evalcondition5 != NULL || $json2array['evalrule']['evalrule_formula5'] != NULL) && $flag != 1) {
            if ($evalcondition5 != NULL) {
                if (eval("return ($evalcondition5);") == 1) {
                    $evalformula = $json2array['evalrule']['evalrule_formula5'];
                    $explodearray = explode("RRR*", $evalformula);
                    if (count($explodearray) > 1) {
                        $usedFormula = substr($explodearray[1], 0, 3);
                    }
                    $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                    $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                    $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                    $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                    $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                    $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                    $usedFormula = $evalformula;
                    $result5 = eval("return ($evalformula);");
                    $result = $result5;
                }
            } else {
                $evalformula = $json2array['evalrule']['evalrule_formula5'];
                $explodearray = explode("RRR*", $evalformula);
                if (count($explodearray) > 1) {
                    $usedFormula = substr($explodearray[1], 0, 3);
                }
                $evalformula = str_replace($slabfield, $slabfieldvalue, $evalformula);
                $evalformula = $this->calculation_part($jsonarray['usageitemlist'], $evalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
                $evalformula = str_replace('FORMULA1', $result1, $evalformula);
                $evalformula = str_replace('FORMULA2', $result2, $evalformula);
                $evalformula = str_replace('FORMULA3', $result3, $evalformula);
                $evalformula = str_replace('FORMULA4', $result4, $evalformula);
                $usedFormula = $evalformula;
                $result5 = eval("return ($evalformula);");
                $result = $result5;
            }
        }

        //pr($result);exit;
        if ($json2array['evalrule']['max_value_condition_flag'] == "Y") {
            $maxvalformula = $json2array['evalrule']['max_value_formula'];
            $maxvalformula = str_replace($slabfield, $slabfieldvalue, $maxvalformula);
//            pr($comparisonrate);
//            exit;
            $comparisonrate = $this->rate_factor_effect($rateflag = 'ABE', $formdata, $comparisonrate, $additionalrate, $fixedrate, $comparisonrate, $rate_rivision_formula, $ratefactorarray);
//            pr($comparisonrate);
//            exit;
            $maxvalformula = $this->calculation_part($jsonarray['usageitemlist'], $maxvalformula, $rate, $fixedrate, $additionalrate, $comparisonrate, $formdata);
            $maxvalformula = str_replace('ABE', $comparisonrate, $maxvalformula);
            $maxvalresult = eval("return ($maxvalformula);");
            if ($maxvalresult > $result and $result > 0) {
                $finalresult = $maxvalresult;
                $usedFormula = $maxvalformula;
            } else {
                $finalresult = $result;
            }
        } else {
            $finalresult = $result;
        }

        $resultarray['derivedresult'] = $result;
        $resultarray['maxvalresult'] = $maxvalresult;
        $resultarray['finalresult'] = $finalresult;
        $resultarray['usedFormula'] = $usedFormula;
        return $resultarray;
    }

}
