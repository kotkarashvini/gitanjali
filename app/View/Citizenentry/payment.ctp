<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
<?php if (isset($RequestData['payment_mode_id'])) { ?>
            $.post('<?php echo $this->webroot; ?>Citizenentry/get_payment_details', {mode: <?php echo $RequestData['payment_mode_id']; ?>, csrftoken: csrftoken}, function (data)
            {
                $('#paydetails').html(data);
                $(document).trigger('_page_ready');
                show_request_data();
                show_error_messages();
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
                        $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken: csrftoken}, function (data)
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
                                    $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken: csrftoken}, function (data)
                                    {
                                        $.each(data, function (index, val) {
                                            $("#ifsc_code").val(val);
                                        });
                                    }, 'json');

                                }
                            });


                        }, 'json');

                    }
                });
            });
<?php } ?>


        $('#paymentmode_id').change(function (e) {
            var mode = $('#paymentmode_id').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_payment_details', {mode: mode, csrftoken: csrftoken}, function (data)
                {
                    $('#paydetails').html(data);
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
                    $(".chosen-select").select2();

                    $('#bank_id').change(function (e) {

                        var bank = $(this).val();

                        if (bank !== '')
                        {
                            $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken: csrftoken}, function (data)
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
                                        $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken: csrftoken}, function (data)
                                        {
                                            $.each(data, function (index, val) {
                                                $("#ifsc_code").val(val);
                                            });
                                        }, 'json');

                                    }
                                });


                            }, 'json');

                        }
                    });
                });

            }
        });






    });
    var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
    function edit_payment(mode, id)
    {
        if (mode === '' && id === '')
        {
        } else {
            $.post('<?php echo $this->webroot; ?>Citizenentry/get_payment_details', {mode: mode, id: id, csrftoken: csrftoken}, function (data)
            {
                $('#paydetails').html(data);
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
                $(".chosen-select").select2();
                $('#bank_id').change(function (e) {

                    var bank = $(this).val();

                    if (bank !== '')
                    {
                        $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken: csrftoken}, function (data)
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
                                    $.post('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken: csrftoken}, function (data)
                                    {
                                        $.each(data, function (index, val) {
                                            $("#ifsc_code").val(val);
                                        });
                                    }, 'json');

                                }
                            });


                        }, 'json');

                    }
                });
            });

        }
    }


</script>

<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
?>
<?php echo $this->element("Registration/main_menu"); ?>
<?php echo $this->element("Citizenentry/property_menu"); ?>

<div class="row">
    <div class="col-lg-12">
        <?php if ($stamp_duty_details) { ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblstampduty'); ?></h3></center>
                </div>
                <div class="box-body">
                    <?php
                    // pr($stamp_duty_details);exit;
                    if ($stamp_duty_details) {
                        echo "<table class='table table-striped table-bordered table-hover'> "
                        . "<thead> <tr> ";
                        foreach ($stamp_duty_details as $sd) {
                            echo "<th>" . $sd['item']['fee_item_desc_' . $lang] . "</th> ";
                        }
                        echo " <th>" . __('lblTotal') . "</th>";
                        echo " </tr></thead>";
                        echo "<tbody> <tr>";
                        $total_amt = 0;
                        foreach ($stamp_duty_details as $sd) {
                            echo "<td> &#8377;" . $sd[0]['fees'] . "/-</td>";
                            $total_amt+=$sd[0]['fees'];
                        }
                        echo " <td> &#8377;" . $total_amt . "/-</td>";
                        echo "</tr></tbody>";
                        echo "</table>";
                    }
                    ?>
                </div>
            </div>
            <?php //------------------------------------------------------------Total after exemption & Adjustment--------------------------------------------  ?>
            <?php if ($exemption_flag == 'Y') { ?>
                                <!--                <script>
                                                var app = angular.module('plunker', []);
                                                app.controller('sumControler', function ($scope) {
                                                    $scope.totalAfterExemption = Number($scope.online_amt) - Number($scope.exemption_amt);
                                                });
                                            </script>-->

                <div class="box box-primary">
                    <div class="row" id="exemptionRecord"> 
                    </div>  
                    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

                    <div class="row" ng-app="plunker">
                        <div class="col-sm-12">
                            <div class="form-group" ng-controller="sumControler">
                                <label for="" class=" control-label col-sm-2"><?php echo __('lblonlinepay'); ?><span style="color: #ff0000"></span></label>  
                                <div class="col-sm-2"><?php echo $this->Form->input('online_amt', array('label' => false, 'value' => $total_amt, 'ng-model' => 'online_amt', 'class' => 'TotalSD form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>                            
                                <label for="" class="control-label col-sm-2" ><?php echo __('lblFeeExemption'); ?></label>
                                <div class="col-sm-2"> <?php echo $this->Form->input('exemption_amt', array('label' => false, 'value' => $exemption_amount, 'id' => 'exemption_amt', 'ng-model' => 'exemption_amt', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>
                                <label for="" class="control-label col-sm-2" ><?php echo __('lblTotal') . ' ' . __('lblonlinepay'); ?></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('online_final_amt', array('label' => false, 'id' => 'totalAfterExemption', 'value' => ($total_amt - $exemption_amount), 'ng-model' => 'totalAfterExemption', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php //------------------------------------------------------------End of Total after exemption & Adjustment-------------------------------------------- ?>        

        <?php } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpayment'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/payment_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $tokenval, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblengdatarequired'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>  
        </div>  


        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblmode'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode))) ?>                         
                        </div>                     

                        <div class="col-sm-4 col-sm-offset-2"> 
                            <ul>
                                <li>
                                    <?php //pr($payment_url[0]['external_links']);exit; ?>
                                    <a href="<?php echo $payment_url[0]['external_links']['url_address']; ?>" target="'_blank'" class=""><?php echo $payment_url[0]['external_links']['link_desc_' . $lang]; ?>  </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <div  class="rowht"></div>
                <div class="row" id="paydetails">

                </div> 
                <div  class="rowht"></div>
                <table id="tablebehaviouraldetails" class="table table-striped table-bordered table-hover" >
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblpaymode'); ?></th>
                            <th class="center"><?php echo __('lblpaymenthead'); ?></th>
                            <th class="center"><?php echo __('lblpayername'); ?></th>
                            <th class="center"><?php echo __('lbldepamt'); ?> </th>
                            <th class="center"><?php echo __('lblotherdetails'); ?> </th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($payment as $paydetails) {
                            $paydetails = $paydetails[0];
                            if (isset($payment_mode[$paydetails['payment_mode_id']])) {
                                //pr($paydetails);
                                ?>
                                <tr>
                                    <td class="tblbigdata"><?php echo $paydetails['payment_mode_desc_' . $doc_lang]; ?></td>
                                    <td class="tblbigdata"><?php
                                        echo $paydetails['fee_item_desc_' . $doc_lang];
                                        ?></td>                                
                                    <td class="tblbigdata"><?php echo $paydetails['payee_fname_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $paydetails['pamount']; ?></td>
                                    <td class="tblbigdata"><?php
                                        foreach ($paymentfields as $tranfield) {
                                            if ($tranfield['PaymentFields']['is_transaction_flag'] == 'Y' and $tranfield['PaymentFields']['payment_mode_id'] == $paydetails['payment_mode_id']) {
                                                echo $tranfield['PaymentFields']['field_name_desc_en'] . " : " . $paydetails[$tranfield['PaymentFields']['field_name']] . "<br>";
                                            }
                                        }
                                        ?></td>


                                    <td >
                                        <input type="button" class="btn btn-info" value="Edit" onclick="edit_payment('<?php echo $paydetails['payment_mode_id']; ?>', '<?php echo $paydetails['payment_id']; ?>');"> 
                                        <a   class="btn btn-info" href="<?php echo $this->webroot; ?>Citizenentry/payment/<?php echo $this->Session->read('csrftoken') ?>/<?php echo $paydetails['payment_id']; ?>">Delete</a>

                                    </td>
                                    <?php
                                }
                            };
                            ?>
                            <?php unset($payment1); ?>
                    </tbody>
                </table> 
            </div> 

        </div>
    </div>
</div>

<?php echo $this->Js->writeBuffer(); ?>

