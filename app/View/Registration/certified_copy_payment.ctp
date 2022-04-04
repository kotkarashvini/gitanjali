 <?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">
    $(document).ready(function () {
        $('#paymentmode_selection1').change(function (e) {
            var mode = $('#paymentmode_selection1').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details_certified_copy', {mode: mode, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                {
                    $('#paydetails1').html('');
                    $('#paydetails1').html(data);
<?php if (isset($RequestData)) { ?>
    <?php if (isset($RequestData)) { ?>
                            if ($('#frmpay3_validation').val() == 'Y') {
                                show_error_messages();
                                show_request_data();
                                $('#frmpay1_validation').val('N');
                                $('#frmpay2_validation').val('N');
                                $('#frmpay3_validation').val('N');
                            }

    <?php } ?>
<?php } ?>
                    $(document).trigger('_page_ready');
                    $('#pdate').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });
                    $('#estamp_issue_date').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });

                    $('#bank_id').change(function (e) {

                        var bank = $(this).val();

                        if (bank !== '')
                        {
                            $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                            {
                                var sc = '<option>--select--</option>';
                                $.each(data, function (index, val) {
                                    sc += "<option value=" + index + ">" + val + "</option>";
                                });
                                $("#branch_id option").remove();
                                $("#branch_id").append(sc);


                                $('#branch_id').change(function (e) {

                                    var branch = $(this).val();

                                    if (branch !== '')
                                    {
                                        $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                                        {
                                            $.each(data, function (index, val) {
                                                $("#ifsc_code").val(val);
                                            });
                                        });

                                    }
                                });


                            });

                        }
                    });
                });

            }
        });

        $('#paymentmode_selection').change(function (e) {
            var mode = $('#paymentmode_selection').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details_certified_copy', {mode: mode}, function (data)
                {
                     $('#paydetails1').html('');
                    $('#paydetails').html(data);
                    $(document).trigger('_page_ready');
<?php if (isset($RequestData)) { ?>
                        if ($('#frmpay1_validation').val() == 'Y') {
                            show_error_messages();
                            show_request_data();
                            $('#frmpay1_validation').val('N');
                            $('#frmpay2_validation').val('N');
                            $('#frmpay3_validation').val('N');
                        }
<?php } ?>
                    $('#pdate').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });
                    $('#estamp_issue_date').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });

                    $('#bank_id').change(function (e) {

                        var bank = $(this).val();

                        if (bank !== '')
                        {
                            $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                            {
                                var sc = '<option>--select--</option>';
                                $.each(data, function (index, val) {
                                    sc += "<option value=" + index + ">" + val + "</option>";
                                });
                                $("#branch_id option").remove();
                                $("#branch_id").append(sc);


                                $('#branch_id').change(function (e) {

                                    var branch = $(this).val();

                                    if (branch !== '')
                                    {
                                        $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                                        {
                                            $.each(data, function (index, val) {
                                                $("#ifsc_code").val(val);
                                            });
                                        });

                                    }
                                });


                            });

                        }
                    });
                });

            }
        });
    });

    function edit_payment(mode, id)
    {
        if (mode === '' && id === '')
        {
        } else {
            $.post('<?php echo $this->webroot; ?>Registration/get_payment_details_certified_copy', {mode: mode, id: id, paymenttoken: 'certifiedcopy', csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
            {
                 $('#paydetails1').html('');
                $('#paydetails').html(data);
<?php if (isset($RequestData)) { ?>
                        if ($('#frmpay2_validation').val() == 'Y') {
                            show_error_messages();
                            show_request_data();
                            $('#frmpay1_validation').val('N');
                            $('#frmpay2_validation').val('N');
                            $('#frmpay3_validation').val('N');
                        }

<?php } ?>
                    $(document).trigger('_page_ready');
                $('#pdate').datepicker({
                    todayBtn: "linked",
                    language: "it",
                    autoclose: true,
                    todayHighlight: true,
                    format: "dd-mm-yyyy"
                });
                $('#estamp_issue_date').datepicker({
                    todayBtn: "linked",
                    language: "it",
                    autoclose: true,
                    todayHighlight: true,
                    format: "dd-mm-yyyy"
                });
                $("#paymententry").click();
                $('#paymentmode_selection_div').hide();
                $('#bank_id').change(function (e) {

                    var bank = $(this).val(mode);

                    if (bank !== '')
                    {
                        $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                        {
                            var sc = '<option>--select--</option>';
                            $.each(data, function (index, val) {
                                sc += "<option value=" + index + ">" + val + "</option>";
                            });
                            $("#branch_id option").remove();
                            $("#branch_id").append(sc);


                            $('#branch_id').change(function (e) {

                                var branch = $(this).val();

                                if (branch !== '')
                                {
                                    $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                                    {
                                        $.each(data, function (index, val) {
                                            $("#ifsc_code").val(val);
                                        });
                                    });

                                }
                            });


                        });

                    }
                });
            });

        }
    }

</script>


<?php $water_mark = ""; ?>
<div class="row">
    <div class="col-lg-12">
        <!--<h1 class="breadcrumb"> Certified Copy Payment</h1>-->
        <div class="panel panel-primary">

            <div class="panel-heading">
                <?php // echo __('lbltokenno'); ?> : <?php // echo $feedetails[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php if(!empty($feedetails)){ echo $feedetails[0][0]['doc_reg_no']; } ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="box-title text-uppercase text-muted" style="font-weight: bolder"><?php echo __('lblcertcopy'); ?> <?php echo __('lblpaymentverify'); ?></h3></center>
            </div>


            <div class="box-body">
                <!--<div class="col-md-12" >--> 



                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="pill" href="#home"><?php echo __('lblpaymetdashboard'); ?></a></li>
                    <li><a data-toggle="pill" href="#menu1" id="paymententry"><?php echo __('lblnewpaymententry'); ?> </a></li>
                    <!--<li><a data-toggle="pill" href="#menu2" id="paymententry"><?php echo __('lblnewpaymentverification'); ?> </a></li>-->
                    <!--<li><a data-toggle="modal" data-target="#paymententry_view" id="cpayment"><?php echo __('lblviewcitizendata'); ?></a></li>-->

                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active"> 
                        <table  class="table table-condensed" >

                            <thead>  

                                <tr class="bg-warning">  
                                    <th><?php echo __('lblpaymenthead'); ?></th>
                                    <th><?php echo __('lblamttobepaid'); ?></th>
                                    <th><?php echo __('lblpaidamt'); ?></th>
                                    <th><?php echo __('lblbalamt'); ?></th>
                                    <th><?php echo __('lblpaymode'); ?></th> 
                                    <th><?php echo __('lblpayername'); ?></th>
                                    <th><?php echo __('lblReferenceNo'); ?>  </th>
                                    <th><?php echo __('lbldepamt'); ?> </th>

                                    <th><?php echo __('lblaction'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>

                                <?php
//                                    pr($payment);exit;
                                $verifyed = 'Y';
                                $sdamount = 0;
                                $paidamount = 0;
//                                    pr($feedetails);exit;
                                //$test = 0;
                                if(!empty($feedetails)){
                                foreach ($feedetails as $fee):
                                    $sdamount+=$fee[0]['totalsd'];
                                    $amount = 0;

                                    if(!empty($payment)){
                                    foreach ($payment as $paydetails):
                                        $paydetails = $paydetails[0];

                                        if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                                            $amount+=$paydetails['pamount'];
                                            $paidamount+=$paydetails['pamount'];
                                            //$test++;
                                        }
                                    
                                    endforeach;
                                    }
                                    ?>
                                    <tr class="bg-info">
                                        <td> <?php echo $fee[0]['fee_item_desc_' . $lang]; ?> </td>
                                        <td> <?php echo $fee[0]['totalsd']; ?>  </td>
                                        <td> 
                                            <?php
                                            echo $amount;
                                            ?>
                                        </td>
                                        <td class="bg-danger"> 
                                            <?php echo $fee[0]['totalsd'] - $amount; ?>
                                        </td> 
                                        <?php
                                        $extrarow = 0;
                                        if(!empty($payment)){
                                        
                                        foreach ($payment as $paydetails):
                                            $paydetails = $paydetails[0];
                                            if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                                                $extrarow++;
                                                if ($extrarow > 1) {
                                                    echo "</tr><tr><td colspan='4'></td>";
                                                }
                                                if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {

                                                    $result = $this->requestAction(array('controller' => 'Registration', 'action' => 'payment_verification_status'), array('pass' => $paydetails));
                                                    foreach ($result as $key => $options) {
                                                        $status = $key;
                                                    }
                                                    ?>

                                                    <td class="bg-success"><?php echo $paydetails['payment_mode_desc_' . $lang]; ?></td>

                                                    <td class="bg-success"> <?php echo $paydetails['payee_full_name_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
                                                    <td class="bg-success"> <ul class="list-inline">                                        
                                                            <?php
                                                            if (is_array($options)) {
                                                                foreach ($options as $key => $value) {
                                                                    echo "<li>" . $paymentfields[$key] . " : <span class='text-primary'>" . $value . " </span></li>";
                                                                }
                                                            }
                                                            ?></ul></td>
                                                    <td class="bg-success"><span class="fa fa-rupee"></span> <?php echo $paydetails['pamount']; ?></td>
                                                    <td width="15%" class="bg-info">    
                                                        <div class="btn-group">                                         
                                                            <?php
                                                            echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'cert_copy_payment_receipt', $paydetails['payment_id'], $water_mark), array('class' => 'btn btn-danger', 'escape' => false));

                                                            if ($status == 99 && $feedetails[0][0]['payment_accept_flag'] == 'N') { //and $documents[0][0][$funflag] == 'N'
                                                                ?>
                                                                <input type="button" class="btn btn-danger" value="Edit"    onclick="edit_payment('<?php echo $paydetails['payment_mode_id']; ?>', '<?php echo $paydetails['payment_id']; ?>');"> 
                                                                <a   class="btn btn-danger" href="<?php echo $this->webroot; ?>Registration/certified_copy_payment_delete/<?php echo $paydetails['payment_id']; ?>">Delete</a>
                                                            <?php } ?>
                                                        </div>   
                                                    </td>       
                                                    <?php
                                                }
                                            }
                                        endforeach;
                                        }
                                        ?> 
                                    </tr>
                                    <tr>
                                        <td colspan="9">
                                            <!--<hr>-->
                                        </td>
                                    </tr>
                                <?php 
                                endforeach; }
                                ?>           

                                <tr class="bg-warning">
                                    <td><?php echo __('lbltotal'); ?></td>
                                    <td><?php echo $sdamount; ?></td>
                                    <td> <?php echo $paidamount; ?> </td>
                                    <td class="bg-danger"><?php echo $sdamount - $paidamount; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>


                            </tbody>
                        </table> 
                        <div class="panel-footer center"> 
                            <?php
                            $btnaccept_label = 'Accept';
                            echo $this->Form->create('certified_copy_payment');
                              echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); 
                            echo $this->Form->input("status", array('type' => 'hidden', 'label' => FALSE, 'value' => $verifyed));
                            echo $this->Form->input("totalpaid", array('type' => 'hidden', 'label' => FALSE, 'value' => $paidamount));
                            echo $this->Form->input("tobepaid", array('type' => 'hidden', 'label' => FALSE, 'value' => $sdamount));

                            if ($paidamount >= $sdamount) {
                                echo $this->Form->input(__($btnaccept_label), array('type' => 'submit', 'name' => 'btnaccept', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success'));
                            } else {
                                echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-disabled'));
                            }

                            echo $this->Form->end();
                            ?>

                        </div>


                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <br>

                        <!--                    <fieldset class="scheduler-border ">
                                                <legend class="scheduler-border">Counter Payment</legend>-->
                        <div class="col-md-12">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_counter))) ?>                         
                                </div> 

                            </div> 
                        </div>
                        <br>   <br>
                        <div class="col-md-12" id="paydetails"> 

                        </div> 
                        <!--</fieldset>-->
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <br>

                        <!--                    <fieldset class="scheduler-border ">
                                                <legend class="scheduler-border">Counter Payment</legend>-->
                        <div class="col-md-12">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection1', 'class' => 'form-control input-sm paymentmode_id', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_online))) ?>                         
                                </div> 

                            </div> 
                        </div>
                        <br>   <br>
                        <div class="col-md-12" id="paydetails1"> 

                        </div> 
                        <!--</fieldset>-->
                    </div>

                </div>








                <!--</div>  col-12 -->

            </div>

        </div>

    </div>  

</div>