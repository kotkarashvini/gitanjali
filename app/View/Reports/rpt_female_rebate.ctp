<script type="text/javascript">
    $(document).ready(function () {
        $('#officeid').hide();
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": false
        });

       $('#usercreate_flag').change(function () {
            
            if (this.value == 'O') {
                $('#officeid').hide();
            } else {
                $('#officeid').show();
            }
        });
    });

    function func() {
        var radios = $("#usercreate_flag").val();
        $("#rdbtn").val(radios);
    }
</script>

<style>
    .table-responsive
    {
        overflow-y:auto;
        height:400px;
    }
</style>
<style>
    @media only screen{
        .yesprint{
            display: none;
        }
    }
</style>
<?php
echo $this->Form->create('rpt_female_rebate', array('id' => 'rpt_female_rebate', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
            <div class="box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class="box-title headbolder"> <?php echo __('Female Rebate Details'); ?> </h3></center>
            </div>
            <div class="box-body">

                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="usercreate" class="control-label col-sm-2"><?php echo __('Select Records By:'); ?></label>            
                    <div class="col-sm-2"> 
                        <?php //echo $this->Form->input('usercreate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Office Wise&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;All Offices'), 'value' => $rdbtn, 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usercreate_flag', 'name' => 'usercreate_flag')); ?>
                        <?php echo $this->Form->input('usercreate_flag', array('label' => false, 'id' => 'usercreate_flag', 'class' => 'form-control input-sm', 'value' => $rdbtn, 'options' => array(' ' => 'select', $usercreate_flag))); ?>
                        <span id="usercreate_flag_error" class="form-error"><?php //echo $errarr['usercreate_flag_error'];                                     ?></span>
                    </div> 
                    <div class="col-sm-2"> </div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 


                <div class="row" id="officeid">
                    <div class="col-sm-2"></div>
                    <label for="office_id" class="col-sm-2 control-label"><?php echo __('Select Office:'); ?></label> 
                    <div class="col-sm-4"> 
                        <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array($office), 'empty' => '--Select--')); ?>
                        <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];                                    ?></span>
                    </div>
                    <div class="col-sm-2"> </div>

                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 



                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date:'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                                        ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="to-error"><?php //echo $errarr['to_error'];                                              ?></span>
                    </div>

                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" onclick="func();"> <?php echo __('lblsearch'); ?> </button></div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 

            </div>
        </div>
            <?php
            if (!empty($f_debate)) {
                // pr($f_debate); exit;
                ?>
                <div class="box box-primary">
                    <div class="box-body">

                        <div id="selectdocument" class="table-responsive">
                            <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                                <thead class="center">  
                                    <tr >  
                                        <th><?php echo __('Token No.'); ?></th>
                                        <th><?php echo __('SRO Office'); ?></th>
                                        <th><?php echo __('Presenter'); ?></th>
                                        <th><?php echo __('Deed No.'); ?></th>
                                        <th><?php echo __('Exec Date'); ?></th>
                                        <th><?php echo __('DocValue'); ?></th>
                                        <th><?php echo __('St.Value(Before Rebate)'); ?></th>
                                        <th><?php echo __('Reg.Fee(Before Rebate)'); ?></th>
                                        <th><?php echo __('Total(Before Rebate)'); ?></th>
                                        <th><?php echo __('St.Value(After Rebate)'); ?></th>
                                        <th><?php echo __('Reg.Fee(After Rebate)'); ?></th>
                                        <th><?php echo __('Total(After Rebate)'); ?></th>
                                        <th><?php echo __('Loss'); ?></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($f_debate as $rec):
                                        ?>
                                        <tr>
                                            <td ><?php echo $rec[0]['token_no']; ?></td>
                                            <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                            <td ><?php echo $rec[0]['party_full_name_en']; ?></td>
                                            <td ><?php echo $rec[0]['final_doc_reg_no']; ?></td>
                                            <td ><?php echo date('Y-m-d', strtotime($rec[0]['final_stamp_date'])); ?></td>
                                            <td ><?php
                                                // doc value
                                                $trans_amt = max($rec[0]['cons_amt'], $rec[0]['valamt']);
                                                if ($trans_amt === NULL) {
                                                    echo 0;
                                                } else {
                                                    echo $trans_amt;
                                                }
                                                ?></td>
                                            <td ><?php
                                        // St.Value(Before Rebate)
                                        $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
                                        foreach ($result as $k => $val) {
                                            if ($val[0]['fee_item_desc_en'] == 'Stamp Duty') {
                                                echo $val[0]['totalsd'];
                                            }
                                        }
                                                ?></td>
                                            <td ><?php
                                        // Reg.Fee(Before Rebate)
                                        $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
                                        if (!empty($result)) {
                                            $c = 0;
                                            foreach ($result as $k => $val) {
                                                if ($val[0]['fee_item_desc_en'] == 'A1') {
                                                    echo $val[0]['totalsd'];
                                                }
                                            }
                                        } else {
                                            echo '';
                                        }
                                                ?></td>
                                            <td ><?php
                                        // Total(Before Rebate)
                                        $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
                                        if (!empty($result)) {
                                            $t1 = 0;
                                            $a = 0;
                                            $b = 0;
                                            foreach ($result as $k => $val) {

                                                if ($val[0]['fee_item_desc_en'] != '') {

                                                    if ($val[0]['fee_item_desc_en'] == 'Stamp Duty') {
                                                        $a = $val[0]['totalsd'];
                                                    } else if ($val[0]['fee_item_desc_en'] == 'A1') {
                                                        $b = $val[0]['totalsd'];
                                                    } else {
                                                        echo '';
                                                    }
                                                }
                                            }
                                        }
                                        $t1 = $a + $b;
                                        echo '<b>' . $t1 . '</b>';
                                        $inc[] = $t1;
                                        ?></td>
                                            <td ><?php
                                        //St.Value(After Rebate)
                                        $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
                                        if (!empty($result)) {
                                            $st1 = 0;
//                                                    foreach ($result as $k => $val) {
//                                                        if ($val[0]['fee_item_desc_en'] == 'Stamp Duty') {
//                                                            $st1 = $val[0]['totalsd'] - $val[0]['totalsd1'];
                                            echo $st1;
//                                                        }
//                                                    }
                                        }
                                                ?></td>
                                            <td ><?php
                                        // Reg.Fee(After Rebate)
                                        $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
                                        if (!empty($result)) {
                                            $st2 = 0;
//                                                    foreach ($result as $k => $val) {
//                                                        if ($val[0]['fee_item_desc_en'] == 'A1') {
//                                                            //     if ($val[0]['fee_item_desc_en'] == 'A1') {
//                                                            $st2 = $val[0]['totalsd'] - $val[0]['totalsd1'];
                                            echo $st2;
//                                                        }
//                                                    }
                                        }
                                                ?></td>
                                            <td ><?php
                                        //Total(After Rebate)
                                        $t2 = 0;
                                        $t2 = $st1 + $st2;
                                        echo '<b>' . $t2 . '</b>';
                                                ?></td>
                                            <td ><?php
                                        //loss
                                        $e = $t1 - $t2;
                                        echo $e;
                                        $r[] = $e;
                                        //pr($r);
                                                ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="box-body" id="frebate">
                            <div class="yesprint">
                                <center><h4 class = "box-title headbolder"><?php //echo $office;        ?> </h4></center>
                            </div>
                            <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Rebate Deed'); ?></b>:<?php echo count($f_debate); ?></label>

                            <label for="" class="col-sm-3"><b><?php echo __('Total Doc Value'); ?></b>:<?php
                                    foreach ($f_debate as $data) {
                                        $row[] = max($data[0]['cons_amt'], $data[0]['valamt']);
                                    }
                                    $rs = "&#8377;";
                                    echo $rs . array_sum($row);
                                    ?></label>

                            <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Income'); ?></b>:<?php
                            foreach ($f_debate as $data1) {
                                $row1[] = $data1[0]['final_amt'];
                            }
                            $ret = array();
                            foreach ($row1 as $key => $value) {
                                $ret[$key] = $row1[$key] - $inc[$key];
                            }
                            $rs = "&#8377;";
                            echo $rs . array_sum($ret);
                                    ?></label>

                        <!--          <label for="" class="col-sm-3 control-label"><b><?php //echo __('Total Rebate Deed');       ?></b>:<?php
//                                $c = 0;
//                                foreach ($f_debate as $rec) {
//                                    $result = $this->requestAction(array('controller' => 'Reports', 'action' => 'getstampdata', $rec[0]['token_no'], $rec[0]['article_id']));
//                                    if (!empty($result)) {
//                                        foreach ($result as $k => $val) {
//                                            //  pr($f_debate);
//                                            if ($val[0]['fee_item_desc_en'] == 'A1') {
//                                                if ($val[0]['totalsd'] > 0) {
//                                                    $c++;
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            echo $c;
                                    ?>
                                </label>-->

                            <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Loss'); ?></b>:<?php echo $rs . array_sum($r); ?><?php ?></label>
                        </div>


                        <div  class="rowht"></div>  <div  class="rowht"></div>
                        <div class="row" style="text-align: center">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <!--<button type="button" class="btn btn-success" id="excelsheet"><?php //echo __('Export To Excel'); ?></button>-->
                                    <button type="button" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i>Downloading">Export To Excel</button>
                                    <button type="button" class="btn btn-success" id="printtotal"><?php echo __('Print'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<input type='hidden' value='<?php echo $rdbtn; ?>' name='rdbtn' id='rdbtn'/>

<script>
    $("document").ready(function () {
        excel = new ExcelGen({
            "src_id": "tabledoc",
            "show_header": "true"
        });
//        $("#excelsheet").click(function () {
//            excel.generate();
//        });
        
        $('#load').on('click', function () {
            excel.generate();
            var $this = $(this);
            $this.button('loading');
            setTimeout(function () { $this.button('reset');}, 1000);
        });
    });
</script>

<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printtotal').on('click', function () {
            $.print("#frebate");
        });
    });
</script>
<?php echo $this->Form->end(); ?>