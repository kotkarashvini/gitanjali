
<script type="text/javascript">

    $(document).ready(function () {
        $("#fdate").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'});
        $("#tdate").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'});

 if ($('#hfhidden1').val() == 'Y') {
            $('#tablepayment').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $('#paymentmode_selection').change(function (e) {
            var mode = $('#paymentmode_selection').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                return;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details2', {mode: mode}, function (data)
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
                            $.getJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank}, function (data)
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
                                        $.getJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch}, function (data)
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
<script>
    function formprint(payment_id) {

        document.getElementById("printflag").value = 'Y';
        document.getElementById("hfid").value = payment_id;
    }
</script>

<?php echo $this->Form->create('inspection_search', array('url' => array('controller' => 'Registration', 'action' => 'inspection_search')), array('id' => 'inspection_search', 'class' => 'form-vertical')); ?>   
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblinspectionsearch'); ?></h3></center>
            </div><div class="rowht"></div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group" id="paymentmode_selection_div">
                        <div class="col-sm-1"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblpresentername'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('presenter_name_en', array('label' => false, 'id' => 'presenter_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                         <span id="presenter_name_en_error" class="form-error"><?php echo $errarr['presenter_name_en_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbladvtname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('advocate_name_en', array('label' => false, 'id' => 'advocate_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                         
                           <span id="advocate_name_en_error" class="form-error"><?php echo $errarr['advocate_name_en_error']; ?></span>
                        </div>
                    </div> 
                </div>
                
                <div class="rowht"></div>
                
                <div class="row">
                    <div class="form-group" id="paymentmode_selection_div">
                        <div class="col-sm-1"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblAddress'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-8"> 
                            <?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                           <span id="address_error" class="form-error"><?php echo $errarr['address_error']; ?></span>
                        </div>
                    </div> 
                </div>
                <div class="rowht"></div> 
                <div class="row">
                    <div class="form-group" id="paymentmode_selection_div">
                        <div class="col-sm-1"></div>
                        <label for="" class="col-sm-2"><?php echo __('lblfromdate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('fdate', array('label' => false, 'id' => 'fdate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                             <span id="fdate_error" class="form-error"><?php echo $errarr['fdate_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('tdate', array('label' => false, 'id' => 'tdate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                     
                          <span id="tdate_error" class="form-error"><?php echo $errarr['tdate_error']; ?></span>
                        </div>
                    </div> 
                </div>
                <div class="rowht"></div> 
                
                <div class="row">
                    <div class="form-group" id="paymentmode_selection_div">
                        <div class="col-sm-1"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_counter))) ?>                         
                        
                           <span id="paymentmode_selection_error" class="form-error"><?php //echo $errarr['paymentmode_id_error']; ?></span>
                        </div> 
                    </div> 
                </div>
                <div class="col-md-12" id="paydetails"></div> 
                
                <div class="rowht"></div>   <div class="rowht"></div>
                <div class="row center">
                    <div class="col-sm-12">
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
            </div>
            </div>
            <div class="panel panel-primary">
            <?php if (!empty($result)) { ?>
                <div class="box-body">
                    <table class="table table-striped table-bordered table-hover" id="tablepayment">
                        <thead>
                            <tr>
                                <th style="text-align: center;"><?php echo __('lblpresentername'); ?></th> 
                                <th style="text-align: center;"><?php echo __('lblpaidamt'); ?></th>
                                <th style="text-align: center;"><?php echo __('lblprint'); ?></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php for($i=0; $i<count($result);$i++) { ?>
                            <tr >
                                <td scope="row" style="text-align: center; font-weight:bold;"><?php echo $result[$i][0]['presenter_name_en']; ?></td>
                                <td style="text-align: center;"><?php echo $result[$i][0]['pamount']; ?></td>
                                <td>
                                    <?php echo $this->Html->link('PRINT', array('controller' => 'Registration', 'action' => 'inspection_search_print', $result[$i][0]['payment_id']), array('class' => 'btn btn-danger', 'escape' => false)); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                         
                    </table>
                </div>
            <?php } ?>
            <?php
                    if (!empty($result)) {
                        ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
        </div>
    </div> 
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $printflag; ?>' name='printflag' id='printflag'/>
    <?php echo $this->Form->end(); ?>   
</div>
