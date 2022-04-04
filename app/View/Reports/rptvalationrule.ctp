<style>
    td{

        padding-left: 1%;
        padding-top: 2px;
        padding-bottom:2px;

    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblruledetails'); ?></h3></center>
                
                
            </div>
            <div class="box-body">
                <table border="1" align="center" width="95%">    
                    <?php
                    $html = "";
                    $rlsrno = 1;
                    $html.="<tr><td><b>Sr No.<b></td><td><b>Rule No.</b></td><td><b>Reference No.</b></td><td><b>Rule Description</b></th></tr>";
                    foreach ($rule as $rl) {
                        $rl = $rl[0];
                        $html.="<tr style=background-color:#C0C0F0;><td align=center>" . $rlsrno++ . "</td><td align=center>" . $rl['evalrule_id'] . "</td><td>" . $rl['reference_no'] . "</td><td>" . $rl['evalrule_desc_en'] . "<br>(" . $rl['evalrule_desc_ll'] . ")</td></tr>";
                        $html.="<tr><td colspan=4><b>Category</b>";
                        $html.="<br>" . $rl['usage_main_catg_desc_en'] . " => " . $rl['usage_sub_catg_desc_en'] . " => " . $rl['usage_sub_sub_catg_desc_en'] . "</td></tr>";
                        //If have Sub Rule
                        if ($rl['subrule_flag'] == 'Y') {
                            $html.= "<tr><td colspan=4 style='padding-left: 1%'><b>Input Items:</b><ol>";
                            foreach ($lnkdata as $lnk) {
                                $lnk = $lnk[0];

                                if ($rl['evalrule_id'] == $lnk['evalrule_id']) {
                                    $html.= "<li>" . $lnk['input_item'] . "</li>";
                                }
                            }
                            $html.= "</ol></td></tr>";
                            foreach ($subrule as $srl) {
                                $srl = $srl[0];
                                if ($rl['evalrule_id'] == $srl['evalrule_id']) {
                                    $html.= "<tr><td colspan=4 style=background-color:#D0D0E0;>Output Item: " . $srl['output_item'] . "</td></tr>";
//                    if ($srl['evalrule_id'] == $lnk['evalrule_id']) {
//                        $html.= "<li>" . $lnk['input_item'] . "</li>";
//                    }

                                    $html.="<tr><td colspan=3><b>Conditions</b></td><td><b>Formula</b></td></b>";
                                    if ($srl['evalsubrule_formula1']) {
                                        $html.="<tr><td colspan=3>" . $srl['evalsubrule_cond1'] . "</td><td>" . $srl['evalsubrule_formula1'] . "</td></b>";
                                    }
                                    if ($srl['evalsubrule_formula2']) {
                                        $html.="<tr><td colspan=3>" . $srl['evalsubrule_cond2'] . "</td><td>" . $srl['evalsubrule_formula2'] . "</td></b>";
                                    }
                                    if ($srl['evalsubrule_formula3']) {
                                        $html.="<tr><td colspan=3>" . $srl['evalsubrule_cond3'] . "</td><td>" . $srl['evalsubrule_formula3'] . "</td></b>";
                                    }
                                    if ($srl['evalsubrule_formula4']) {
                                        $html.="<tr><td colspan=3>" . $srl['evalsubrule_cond4'] . "</td><td>" . $srl['evalsubrule_formula4'] . "</td></b>";
                                    }
                                    if ($srl['evalsubrule_formula5']) {
                                        $html.="<tr><td colspan=3>" . $srl['evalsubrule_cond5'] . "</td><td>" . $srl['evalsubrule_formula5'] . "</td></b>";
                                    }
                                }
                            }
                        }
                        // if not Have Subrule
                        else {
                            $html.= "<tr><td colspan=3 style='padding-left: 5px;'><b>Input Items:</b><ol>";
                            foreach ($lnkdata as $lnk) {
                                $lnk = $lnk[0];

                                if ($rl['evalrule_id'] == $lnk['evalrule_id']) {
                                    $html.= "<li>" . $lnk['input_item'] . "</li>";
                                }
                            }
                            $html.= "</ol></td>";
                            $html.= "<td><b>Output Item: </b>" . $rl['output_item'] . "</td></tr>";

                            $html.="<tr><td colspan=3><b>Conditions</b></td><td><b>Formula</b></td></b>";
                            if ($rl['evalrule_formula1']) {
                                $html.="<tr><td colspan=3>" . $rl['evalrule_cond1'] . "</td><td>" . $rl['evalrule_formula1'] . "</td></b>";
                            }
                            if ($rl['evalrule_formula2']) {
                                $html.="<tr><td colspan=3>" . $rl['evalrule_cond2'] . "</td><td>" . $rl['evalrule_formula2'] . "</td></b>";
                            }
                            if ($rl['evalrule_formula3']) {
                                $html.="<tr><td colspan=3>" . $rl['evalrule_cond3'] . "</td><td>" . $rl['evalrule_formula3'] . "</td></b>";
                            }
                            if ($rl['evalrule_formula4']) {
                                $html.="<tr><td colspan=3>" . $rl['evalrule_cond4'] . "</td><td>" . $rl['evalrule_formula4'] . "</td></b>";
                            }
                            if ($rl['evalrule_formula5']) {
                                $html.="<tr><td colspan=3>" . $rl['evalrule_cond5'] . "</td><td>" . $rl['evalrule_formula5'] . "</td></b>";
                            }
                        }
                        $html.= "<tr style='background-color:yellow'><td colspan=4></td></tr>";
                    }
                    echo $html;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

