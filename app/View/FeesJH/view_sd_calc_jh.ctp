<?php

$total = NULL;
if ($calc_Data && $calc_detail) {//display data if both detail available
    $html = NULL;
    $html.="<table border=1 width=80% align=center>";
    $rule_id = $prop_id = NULL;
    $totalSD = 0;
    $SrProp = $SrRule = $SrCalc = 1;
    foreach ($calc_Data as $calc) {

        if ($prop_id != $calc['fees_calculation']['property_id']) {
            $html.="<tr style='background-color: #AC7471;'>  <td colspan=3> Property Id:" . $calc['fees_calculation']['property_id'] . " </td> </tr>";
            $prop_id = $calc['fees_calculation']['property_id'];
            $SrRule = 1;
        }

        if ($prop_id == $calc['fees_calculation']['property_id'] && $rule_id != $calc['fees_calculation']['fee_rule_id']) {
            $html.="<tr style='background-color: #F9E0B3;'>   <td colspan=3>Fee Rule:" . $calc['fee_rule']['fee_rule_desc_en'] . "</td></tr>";
            $rule_id = $calc['fees_calculation']['fee_rule_id'];
        }

        foreach ($calc_detail as $cd) {
            $cds = $cd['fees_calculation_detail'];
            if ($calc['fees_calculation']['fee_calc_id'] == $cds['fee_calc_id']) {
               
                $html.="<tr style='background-color: #999A75;'><td  align=center>" . $SrCalc++ . "</td> <td> " . $cd['item']['fee_item_desc_en'] . "</td><td>" . $cds['final_value'] . "</td></tr>";
                $totalSD+=$cds['final_value'];
            }
        }
    $html.="<tr style='background-color: #FFFFF;'><td colspan=3 align=center></td></tr>"; 
    }
    $html.="<tr style='background-color: #A36134;'><td colspan=2 align=center><b>Total</b></td><td><b>" . $totalSD . "</b></td></tr>";
    $html.="</table>";
   
    echo $html;
}
//
?>