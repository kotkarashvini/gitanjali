<?php
foreach ($stampconfig as $stamprec) {
    if (isset($stamprec['functions'])) {
        foreach ($stamprec['functions'] as $funrec) {
            if ($funrec['action'] == $this->request->params['action']) {
                $btnaccept_label = $funrec['btnaccept'];
                $stampflag = $stamprec['stamp_flag'];
                $funflag = $funrec['function_flag'];
            }
        }
    }
}


echo $this->element("Registration/main_menu");
$tokenval = $this->Session->read("reg_token");
?>
<br>
<?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">

    function edit_payment(mode, id)
    {
        if (mode === '' && id === '')
        {
        } else {
            $.post('<?php echo $this->webroot; ?>Registration/get_payment_details', {mode: mode, id: id, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
            {
                $(document).unbind('_pay_chart');
                $(document).unbind('_pay_event');
                $('#paydetails1').html('');
                $('#paydetails').html(data);
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
                $(document).trigger('_pay_chart');
                $(document).trigger('_pay_event');

                $("#paymententry").click();
                $('#paymentmode_selection_div').hide();
            });

        }
    }

    $(document).ready(function () {

        $.post('<?php echo $this->webroot; ?>/Reports/summary1_report', {doc_token_no: '<?php echo base64_encode($token); ?>'}, function (regSummary1) {
            $("#rptRegSummary1").html("");
            $("#rptRegSummary1").html(regSummary1);
        });
        $.post('<?php echo $this->webroot; ?>/Reports/receipt_report', {doc_token_no: '<?php echo base64_encode($token); ?>'}, function (data) {
            $("#rptreceipt").html("");
            $("#rptreceipt").html(data);

        });

//---------------------------------------------get stamp Summary 1------------------------------------------------------------------------


        $('#paymentmode_selection1').change(function (e) {
            var mode = $('#paymentmode_selection1').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details', {mode: mode, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                {
                    $(document).unbind('_pay_chart');
                    $(document).unbind('_pay_event');
                    $('#paydetails').html('');
                    $('#paydetails1').html(data);

<?php if (isset($RequestData)) { ?>
                        if ($('#frmpay1_validation').val() == 'Y') {
                            show_error_messages();
                            show_request_data();
                            $('#frmpay1_validation').val('N');
                            $('#frmpay2_validation').val('N');
                            $('#frmpay3_validation').val('N');
                        }
<?php } ?>
                    $(document).trigger('_page_ready');
                    $(document).trigger('_pay_chart');
                    $(document).trigger('_pay_event');
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
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details', {mode: mode, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                {
                    $(document).unbind('_pay_chart');
                    $(document).unbind('_pay_event');
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
                    $(document).trigger('_pay_chart');
                    $(document).trigger('_pay_event');

                });

            }
        });
<?php
if (isset($RequestData)) {
    if (isset($RequestData['payment_id'])) {
        ?>
                edit_payment(<?php echo $RequestData['payment_mode_id']; ?>, <?php echo $RequestData['payment_id']; ?>);
        <?php
    } else {
        ?>
                $("#paymentmode_selection1 option").each(function () {
                    if ($(this).val() ==<?php echo $RequestData['payment_mode_id']; ?>) { // EDITED THIS LINE
                        $(this).attr("selected", "selected");
                        $(this).trigger('change');
                    }
                });
                $("#paymentmode_selection option").each(function () {
                    if ($(this).val() ==<?php echo $RequestData['payment_mode_id']; ?>) { // EDITED THIS LINE
                        $(this).attr("selected", "selected");
                        $(this).trigger('change');
                    }
                });

        <?php
    }
}
?>
    });


</script>

<?php
$message = $this->Session->Flash();
if (!empty($message)) {
    ?>
    <div class="alert alert-info alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $message; ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="pull-right"> 
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Payment/payment_verification_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div>
                    <div class="pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="box-title headbolder"><?php echo __('lblpaymentverify'); ?></h3></center>
            </div>


            <div class="box-body">
                <div class="panel with-nav-tabs panel-danger">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1primary" data-toggle="tab"><?php echo __('lblpaymetdashboard'); ?></a></li>
                            <?php if ($documents[0][0][$funflag] == 'N' || empty($regconf_hide_paytab)) { ?>
                                <li><a href="#tab2primary" data-toggle="tab" id="paymententry"><?php echo __('lblnewpaymententry'); ?> </a></li>
                                <li><a href="#tab3primary" data-toggle="tab" ><?php echo __('lblnewpaymentverification'); ?></a></li>    
                            <?php } ?>   
                            <li><a href="#tab4primary" data-toggle="tab"><?php echo __('lblviewcitizendata'); ?></a></li>
                            <li><a  data-toggle="modal" data-target="#modelviewsummer1"><?php echo __('lblviewsummer1'); ?></a></li>  
                            <li><a href="#tab7primary" data-toggle="tab" ><?php echo __('lblreceipt'); ?></a></li>  
                            <li><a href="#tab6primary" data-toggle="tab"><?php echo __('lblnewpaymenthead'); ?></a></li>
                            <li><a href="#tab8primary" data-toggle="tab"><?php echo __('lbladjustmentamt'); ?></a></li>
                             <li><a href="#tab9primary" data-toggle="tab"><?php echo __('Document Refuse'); ?></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1primary">
                                <?php echo $this->element("Registration/payment/dashboard"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab2primary">
                                <?php echo $this->element("Registration/payment/newpayment"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab3primary">
                                <?php echo $this->element("Registration/payment/payment_verification"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab4primary">
                                <?php echo $this->element("Registration/payment/payment_entry_data"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab5primary">
                                <?php echo $this->element("Registration/payment/summary1_report"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab6primary">
                                <?php echo $this->element("Registration/payment/payment_head_entry"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab7primary">
                                <?php echo $this->element("Registration/payment/receipt"); ?>
                            </div>
                            <div class="tab-pane fade" id="tab8primary">
                                <?php echo $this->element("Registration/payment/paymentadjustment"); ?>
                            </div>
                             <div class="tab-pane fade" id="tab9primary">
                                <?php echo $this->element("Registration/payment/refuse_document"); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div> 
</div> 
<!-- Registration Summary 1 -->


<div id="modelviewsummer1" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblviewsummer1'); ?></h4>
            </div>
            <div class="modal-body"> 
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <div id="rptRegSummary1">

                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>



<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#summary1print').on('click', function () {
            $.print("#rptRegSummary1");
        });
        $('#receiptprint').on('click', function () {
            $.print("#rptreceipt");
        });
        $('#btnrefuseprint').on('click', function () {
            $.print("#divprintrefuse");
        });
    });


</script>

<input type="hidden" id="frmpay1_validation" value="Y">
<input type="hidden" id="frmpay2_validation" value="Y">
<input type="hidden" id="frmpay3_validation" value="Y">