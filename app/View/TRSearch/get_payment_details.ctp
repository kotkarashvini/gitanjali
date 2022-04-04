<?php

///pr($payment_mode);

if (isset($paymentfields) && !empty($paymentfields)) {
    ?>
    <?php echo $this->Form->create('payment', array('url' => array('controller' => 'TRSearch', 'action' => 'payment_verification')), array('id' => 'payment', 'class' => 'form-vertical')); ?>   
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

    <?php
    $upadteflag = 0;
    foreach ($paymentfields as $field) {
        $field = $field['PaymentFields'];
        if (isset($payment) && !empty($payment)) {
            $upadteflag = 1;
            $payment1[$field['field_name']] = $payment[0]['payment'][$field['field_name']];
            $payment1['payment_mode_id'] = $payment[0]['payment']['payment_mode_id'];
        } else {
            $payment1[$field['field_name']] = '';
            $payment1['payment_mode_id'] = $field['payment_mode_id'];
        }


        $lblbtnsave = 'Save';
        if ($field['payment_mode_id'] == 1) {
            $lblbtnsave = 'Verify And Save';
        }
        ?>
<div class="form-group">
    <label> <?php echo $field['field_name_desc_' . $lang] ?> <span class="star" ><?php echo $field['required_mark']; ?></span></label>

        <?php
        if ($field['field_name'] == 'account_head_code') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm', 'label' => false, 'options' => $accounthead, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'bank_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $bank_master, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'branch_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $branch_master, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'cos_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $office, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if($field['is_list_field']=='N'){
            echo $this->Form->input($field['field_name'], array('label' => false, 'id' => $field['field_name'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $payment1[$field['field_name']]));
        }else{
            $drop_option= $this->requestAction(array('controller' => 'TRSearch', 'action' => 'get_payment_field_values',$field['field_id'] ));  
              echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $drop_option, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
       
        }
        ?>
    <span class="form-error" id="<?php echo $field['field_name']; ?>_error"></span>
</div> 
<!--<div class="clearfix"></div>-->
    <?php }
    ?>
    <?php
    echo $this->Form->input('payment_mode_id', array('label' => false, 'id' => 'payment_mode_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $payment1['payment_mode_id']));
    if ($upadteflag == 1) {
        echo $this->Form->input('payment_id', array('label' => false, 'id' => 'id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $payment[0]['payment']['payment_id']));
    }
    ?>

<br>

<div class="row center">
    <div class="col-lg-12">
        <div class="form-group">
            <button id="btnadd" type="submit" name="btnadd" class="btn btn-info ">
                    <?php if ($upadteflag == 1) {
                        ?>
                <span class="glyphicon glyphicon-plus"></span> <?php echo __('btnupdate'); ?>
                    <?php } else { ?>
                <span class="glyphicon glyphicon-plus"></span> <?php echo $lblbtnsave; ?>
                    <?php } ?>
            </button>
            <button id="btncancel" name="btncancel" class="btn btn-info " type="reset">
                <span class="glyphicon glyphicon-reset"><?php ?></span><?php echo __('lblreset'); ?>
            </button>
                <?php echo $this->Html->link('New Entry', array('controller' => 'TRSearch', 'action' => 'payment_verification'), array('class' => 'btn btn-danger', 'escape' => false));
                ?>
        </div>
    </div>
</div>       
    <?php echo $this->Form->end(); ?>   

<?php } else { ?>

<div class="alert alert-warning">
    No Data Found !
</div>
<?php } ?>

<script>

    $(document).bind('_pay_chart',
            function () {
                $('#pamount').on('keyup', function () {
                    var amt = parseInt($('#pamount').val());

<?php
$preference = 0;
$lastpreference=0;
foreach ($feedetails as $fee) {
    if (is_numeric($fee[0]['fee_preference'])) {
        $preference = $fee[0]['fee_preference'];
    } else {
        $preference++;
    }
    $matchflag=0;
    foreach ($modemapping as $accdetails) {
        if($fee[0]['fee_item_id']==$accdetails['PaymentModeMapping']['fee_item_id']){
           $matchflag=1; 
           $lastpreference=$preference;
        }
    }
    ?>
                    $("#currentpay<?php echo $preference; ?>").html('');
        <?php
    if($matchflag){
    
    ?>
                    // alert('<?php //echo $fee[0]['fee_item_id']; ?>');
                    if ($.isNumeric(amt)) {
                        var totalsd = parseInt($("#sdtotal<?php echo $preference; ?>").html());
                        var balance = parseInt($("#balance<?php echo $preference; ?>").html());
                        var currentpay = 0;
                        if ($.isNumeric(balance) && balance > 0) {
                            if (amt > balance) {
                                $("#currentpay<?php echo $preference; ?>").html(balance);
                                $("#currentpay<?php echo $preference; ?>").css("color", "red");
                                amt = amt - balance;
                            } else {
                                $("#currentpay<?php echo $preference; ?>").html(amt);
                                $("#currentpay<?php echo $preference; ?>").css("color", "red");
                                amt = 0;
                            }
                        } else {
                            $("#currentpay<?php echo $preference; ?>").html('');
                        }
                    } else {
                        $("#currentpay<?php echo $preference; ?>").html('');
                    }
    <?php } ?>
<?php } ?>
                    if ($.isNumeric(amt) && amt > 0) {
                        var last = parseInt($("#currentpay<?php echo $lastpreference; ?>").html());
                        if ($.isNumeric(last)) {
                            $("#currentpay<?php echo $lastpreference; ?>").html(amt + last);
                        } else {
                            $("#currentpay<?php echo $lastpreference; ?>").html(amt);
                        }
                    }
                    // }
                })



            });
    $(document).bind('_pay_event',
            function () {
                $('#pdate').datepicker({
                    todayBtn: "linked",
                    language: "it",
                    autoclose: true,
                    todayHighlight: true,
                    format: "dd-mm-yyyy",
                    startDate: '<?php echo @$payment_mode['start_date']?>',
                    endDate: '<?php echo @$payment_mode['end_date']?>'
                });
                $('#estamp_issue_date').datepicker({
                    todayBtn: "linked",
                    language: "it",
                    autoclose: true,
                    todayHighlight: true,
                    format: "dd-mm-yyyy",
                    startDate: '<?php echo @$payment_mode['start_date']?>',
                    endDate: '<?php echo @$payment_mode['end_date']?>'
                });

                $('#bank_id').change(function (e) {

                    var bank = $(this).val();

                    if (bank !== '')
                    {
                        if ($('#branch_id').length)
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
                                        if ($('#ifsc_code').length)
                                        {
                                            $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                                            {
                                                $.each(data, function (index, val) {
                                                    $("#ifsc_code").val(val);
                                                });
                                            });
                                        }
                                    }
                                });


                            });
                        }
                    }
                });
            });
</script>