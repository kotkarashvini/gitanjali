<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>
<script>
    $(document).ready(function () {
        $('#heaaring_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#date_of_order').Zebra_DatePicker({
            view: 'years'
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate() {
        document.getElementById("actiontype").value = '1';
    }
</script>   

<script type='text/javascript'>
    function Sum() {
        // Your total
        var sum = 0;
        sum += Number($('#stamp_duty').val());
        sum += Number($('#registration_fees').val());
//        sum += Number($('#surcharge').val());
//        sum += Number($('#interest').val());
        sum += Number($('#penalty').val());
        alert(sum);

        $("#total_amount").val(sum);

    }
</script>

<?php echo $this->Form->create('judgement_details', array('id' => 'judgement_details', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder">Judgement Details</h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Number:-<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <b><?php echo $case_code_id; ?></b>
                        </div>

                        <label for="" class="col-sm-2 control-label">Stamp Duty Revised<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            : <?php echo $stamp_duty_revised; ?>
                        </div>
                    </div>
                </div>
                <!--<br>-->
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <b><?php echo $ccms_case; ?></b>
                        </div>
                    </div>
                </div>
                <!--                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Selected Case:-<span style="color: #ff0000"></span></label>    
                                        <div class="col-sm-2">
                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                        <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>    
                                        <div class="col-sm-2">
                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_code, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                        <label for="" class="col-sm-2 control-label">Case Year:-<span style="color: #ff0000"></span></label>    
                                        <div class="col-sm-2">
                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_year, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                    </div>
                                </div>-->
            </div>

        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="date_of_order" class="col-sm-2 0control-label">Date of Judgement<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('date_of_order', array('label' => false, 'id' => 'date_of_order', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="date_of_order_error" class="form-error"><?php echo $errarr['date_of_order_error'];    ?></span>
                        </div>
                        <label for="date_of_order" class="col-sm-2 0control-label">Place of Judgement<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('place_of_hearing', array('label' => false, 'id' => 'place_of_hearing', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $office))); ?>
                            <span id="place_of_hearing_error" class="form-error"><?php echo $errarr['place_of_hearing_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="stamp_duty" class="col-sm-2 0control-label">Stamp Duty<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('stamp_duty', array('label' => false, 'id' => 'stamp_duty', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="stamp_duty_error" class="form-error"><?php echo $errarr['stamp_duty_error']; ?></span>
                        </div>
                        <label for="registration_fees" class="col-sm-2 0control-label">Registration Fees<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('registration_fees', array('label' => false, 'id' => 'registration_fees', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="registration_fees_error" class="form-error"><?php echo $errarr['registration_fees_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <!--                <div class="row">
                                    <div class="form-group">
                                        <label for="surcharge" class="col-sm-2 0control-label">Surcharge<span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                <?php echo $this->Form->input('surcharge', array('label' => false, 'id' => 'surcharge', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                                            <span id="surcharge_error" class="form-error"><?php echo $errarr['surcharge_error']; ?></span>
                                        </div>
                                        <label for="interest" class="col-sm-2 0control-label">Interest<span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                <?php echo $this->Form->input('interest', array('label' => false, 'id' => 'interest', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                                            <span id="interest_error" class="form-error"><?php echo $errarr['interest_error']; ?></span>
                                        </div>
                                    </div>
                                </div>-->
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="penalty" class="col-sm-2 0control-label">Penalty<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('penalty', array('label' => false, 'id' => 'penalty', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="penalty_error" class="form-error"><?php echo $errarr['penalty_error']; ?></span>
                        </div>
                        <label for="total_amount"class="col-sm-2 0control-label">Total Amount<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('total_amount', array('label' => false, 'id' => 'total_amount', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="total_amount_error" class="form-error"><?php echo $errarr['total_amount_error']; ?></span>
                        </div>
                        <div>       
                            <button id="demo" name="demo" class="btn btn-info " type="button"  onclick="javascript: return Sum();">&nbsp;&nbsp;<?php echo __('Calculate Total'); ?></button>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="remark" class="col-sm-2 0control-label">Final Judgement <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('final_judgement', array('label' => false, 'id' => 'final_judgement', 'type' => 'textarea', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="final_judgement_error" class="form-error"><?php echo $errarr['final_judgement_error']; ?></span>
                        </div>
                        <label for="remark" class="col-sm-2 0control-label">Remark <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'type' => 'textarea', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>  
                <div class="row">
                    <div class="form-group">
                        <label for="remark" class="col-sm-2 0control-label">Party present for Judgement<span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3">
                            <div class="usage-list" id="usage-list">
                                <?php echo $this->Form->input('usage_cat_id', array('type' => 'select', 'options' => $listdata, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => ' usage_cat_id')); ?>
                            </div>
                            <!--<span id="case_status_id_error" class="form-error"><?php echo $errarr['case_status_id_error']; ?></span>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remark" class="col-sm-2 0control-label">Case Status<span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('case_status_id', array('label' => false, 'id' => 'case_status_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $case_status))); ?>
                            <span id="case_status_id_error" class="form-error"><?php echo $errarr['case_status_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    <button type="reset"  id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                    <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript
                            : return formadd();">
                        &nbsp;&nbsp;<?php echo __('Next'); ?>
                    </button>
                </div>  
            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

