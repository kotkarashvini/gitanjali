<?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">

    $(document).ready(function () {

        $('#payment_mode_id').change(function (e) {
            var mode = $('#payment_mode_id').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details_simple_reciept', {mode: mode}, function (data)
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

</script>

<div class="row">
    <div class="col-lg-12">
        <?php echo $this->Form->create('simple_reciept', array('url' => array('controller' => 'Registration', 'action' => 'simple_reciept')), array('id' => 'payment', 'class' => 'form-vertical')); ?>   

        <div class="box box-primary"> 

            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreceipt'); ?></h3></center>
            </div> 
            <div class="box-body">
                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
                        
                        <div class="row">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lbldocregno'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'id' => 'doc_reg_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                                 <span id="doc_reg_no_error" class="form-error"><?php echo $errarr['doc_reg_no_error']; ?></span>
                                </div>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('payment_mode_id', array('label' => false, 'id' => 'payment_mode_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_counter))) ?>                         
                                 <span id="payment_mode_id_error" class="form-error"><?php echo $errarr['payment_mode_id_error']; ?></span>
                                </div> 
                            </div> 
                        </div><br>
                        
                        
                        <div class="col-md-12" id="paydetails"></div> 
                        <br>
                       
                        <div class="row center">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button id="btnadd" type="submit" name="btnadd" class="btn btn-info ">

                                        <span class="glyphicon glyphicon-plus"></span> <?php echo __('btnsave'); ?>

                                    </button>
                                    <button id="btncancel" name="btncancel" class="btn btn-info " type="reset">
                                        <span class="glyphicon glyphicon-reset"><?php ?></span><?php echo __('lblreset'); ?>
                                    </button>

                                </div>
                            </div>
                        </div>

                        <!--</fieldset>-->
                    </div>
                </div>
            </div>

        </div>

        <?php echo $this->Form->end(); ?>          
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreceiptlist'); ?></h3></center>
                <!--<div class="box-title"><?php //echo __('lblreceiptlist'); ?></div>-->
            </div> 
            <div class="panel-body">
                <table id="Doclist" class="table table-striped table-bordered table-hover" >
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblpaymode'); ?></th>
                            <th class="center"><?php echo __('lblpaymenthead'); ?></th>
                            <th class="center"><?php echo __('lbldocno'); ?></th>
                            <th class="center"><?php echo __('lbldepamt'); ?> </th>
                            <th class="center"><?php echo __('lblaction'); ?> </th>

                    </thead>
                    <tbody>
                        <?php
                        //pr($paymentdetails);
                        foreach ($paymentdetails as $paydetails) {
                            $paydetails = $paydetails[0];
                            ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $paydetails['payment_mode_desc_' . $lang]; ?></td>
                                <td class="tblbigdata"><?php echo $paydetails['fee_item_desc_' . $lang];
                            ?></td>                                
                                <td class="tblbigdata"><?php echo $paydetails['doc_reg_no']; ?></td>
                                <td class="tblbigdata"><?php echo $paydetails['pamount']; ?></td>
                                <td class="tblbigdata"> 
                                    <?php echo $this->Html->link('PDF', array('controller' => 'Registration', 'action' => 'simple_reciept_print', $paydetails['payment_id']), array('class' => 'btn btn-danger', 'escape' => false)); ?>
                                </td>


                            <?php }
                            ?>

                    </tbody>
                </table> 

            </div>
        </div>   


    </div> 

</div>


<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });

</script>