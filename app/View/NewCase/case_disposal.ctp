<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>
<script>
    $(document).ready(function () {
        $('#heaaring_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#judgement_date').Zebra_DatePicker({
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
        sum += Number($('#receipt_number').val());
        sum += Number($('#remark').val());
        sum += Number($('#surcharge').val());
        sum += Number($('#interest').val());
        sum += Number($('#penalty').val());
        alert(sum);

        $("#total_amount").val(sum);

    }
</script>

<?php echo $this->Form->create('case_disposal', array('id' => 'case_disposal', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder">Case Disposal</h3></center>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Selected Case:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
<!--                        <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                        <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_code, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                        <label for="" class="col-sm-2 control-label">Case Year:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                        <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_year, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>-->
                    </div>
                </div>
            </div>

        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="judgement_date" class="col-sm-2 0control-label">Judgement Date<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $judge_date, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            <?php // echo $this->Form->input('judgement_date', array('label' => false, 'id' => 'judgement_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <!--<span id="judgement_date_error" class="form-error"><?php echo $errarr['judgement_date_error']; ?></span>-->
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="receipt_number" class="col-sm-2 0control-label">Receipt number<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('receipt_number', array('label' => false, 'id' => 'receipt_number','value' => $receptId, 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off','readonly')); ?>  
                            <span id="receipt_number_error" class="form-error"><?php echo $errarr['receipt_number_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
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
                        <div class="col-md-5 col-md-offset-1" >
                            <label>   <?php echo __('Select Case Status'); ?></label>
                            <?php echo $this->Form->input('case_status_id', array('label' => false, 'id' => 'case_status_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $case_status))); ?>
                            <span id="case_status_id_error" class="form-error"><?php echo $errarr['case_status_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div style="float: right;">       
                    <button id="demo" name="demo" class="btn btn-info " type="button"  onclick="javascript: return Sum();">&nbsp;&nbsp;<?php echo __('Calculate Total'); ?></button>
                </div>
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

