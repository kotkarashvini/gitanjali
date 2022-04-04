<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#paymentmode_id').change(function (e) {
            var mode = $('#paymentmode_id').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>NewCase/get_payment_details', {mode: mode}, function (data)
                {
                    $('#paydetails').html(data);
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
                    $(".chosen-select").select2();
                    $('#bank_id').change(function (e) {
                        var bank = $(this).val();
                        if (bank !== '')
                        {
                            $.getJSON('<?php echo $this->webroot; ?>NewCase/get_bank_branch', {bank: bank}, function (data)
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
                                        $.getJSON('<?php echo $this->webroot; ?>NewCase/get_bank_branch_code', {branch: branch}, function (data)
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
            $.post('<?php echo $this->webroot; ?>NewCase/get_payment_details', {mode: mode, id: id}, function (data)
            {
                $('#paydetails').html(data);
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
                $(".chosen-select").select2();
                $('#bank_id').change(function (e) {
                    var bank = $(this).val();
                    if (bank !== '')
                    {
                        $.getJSON('<?php echo $this->webroot; ?>NewCase/get_bank_branch', {bank: bank}, function (data)
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
                                    $.getJSON('<?php echo $this->webroot; ?>NewCase/get_bank_branch_code', {branch: branch}, function (data)
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

<div class="row">
    <div class="col-lg-12">
        <?php
        echo $this->element("NewCase/main_menu");
        echo $this->element("NewCase/property_menu");
        ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpayment'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Selected Case :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>
            </div>  
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpayment'); ?>&nbsp;<?php echo __('Mode'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">

                    <label for="" class="col-sm-3 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-3"> 
                        <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode))) ?>                         
                    </div> 
                </div> 
                <div  class="rowht">&nbsp;</div>
                <div class="row" id="paydetails">
                </div> 
            </div> 
            <table id="tablebehaviouraldetails" class="table table-striped table-bordered table-hover" >
                <thead >  
                    <tr>  
                        <th class="center"><?php echo __('lblpaymode'); ?></th>
                        <th class="center"><?php echo __('lblpayhead'); ?></th>
                        <th class="center"><?php echo __('lblpayername'); ?></th>
                        <th class="center"><?php echo __('lbldepamt'); ?> </th>
                        <th class="center width10"><?php echo __('lblaction'); ?></th>
                    </tr>  
                </thead>
                <tbody>
                    <?php
                    foreach ($payment as $paydetails) {
                        $paydetails = $paydetails[0];
                        if (isset($payment_mode[$paydetails['payment_mode_id']])) {
                            ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $paydetails['payment_mode_desc_en']; ?></td>
                                <td class="tblbigdata"><?php // PR($paydetails);
                                echo $accounthead[$paydetails['payment_mode_id']]; ?></td>                                
                                <td class="tblbigdata"><?php echo $paydetails['payee_fname_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
                                <td class="tblbigdata"><?php echo $paydetails['pamount']; ?></td>
                                <td>
                                    <input type="button" class="btn btn-info" value="Edit" onclick="edit_payment('<?php echo $paydetails['payment_mode_id']; ?>', '<?php echo $paydetails['id']; ?>');"> 
                                    <a   class="btn btn-info" href="<?php echo $this->webroot; ?>NewCase/payment/<?php echo $paydetails['id']; ?>">Delete</a>
                                </td>
                                <?php
                            }
                        };
                        ?>
                        <?php ($payment1); ?>
                </tbody>
            </table> 
        </div>
    </div>
</div>
<?php echo $this->Js->writeBuffer(); ?>

