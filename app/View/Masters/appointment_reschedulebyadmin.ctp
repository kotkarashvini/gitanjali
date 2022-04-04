<script>
    $(document).ready(function () {
        $("#appointment_date").val();

        $('#appointment_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            dayOfWeekDisabled: [0, 6],
            startDate: '+0d',
            endDate: '+7d',
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            //if(app_date.getDay() == 6 || app_date.getDay() == 0) alert('Weekend!');

            $.post('<?php echo $this->webroot; ?>Masters/check_appointmentdate', {app_date: app_date}, function (data)
            {
                if (data.trim() == 'b')
                {
                    alert('Holiday ! Select another date');
                    $("#slot").html('');
                    return false;
                }
                if (data.trim() == 'a')
                {

                    var office_id = $("#office_id").val();
                    var shift_id = $("#shift_id").val();

                    if (shift_id == '')
                    {
                        alert('Please select shift');
                    }
                    else
                    {
                        $.post('<?php echo $this->webroot; ?>Masters/slot_alocation_admin', {office_id: office_id, app_date: app_date, shift_id: shift_id}, function (data)
                        {

                            $("#slot").html(data);
                        });
                    }
                }

            });


        });

        $("#shift_id").change(function () {

            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();


            if (app_date != '' && office_id != '')
            {
                $.post('<?php echo $this->webroot; ?>Masters/check_appointmentdate', {app_date: app_date}, function (data)
                {
                    if (data.trim() == 'b')
                    {
                        alert('Holiday ! Select another date');
                        $("#slot").html('');
                        return false;
                    }
                    if (data.trim() == 'a')
                    {

                        var office_id = $("#office_id").val();
                        var shift_id = $("#shift_id").val();

                        if (shift_id == '')
                        {
                            alert('Please select shift');
                        }
                        else
                        {
                            $.post('<?php echo $this->webroot; ?>Masters/slot_alocation_admin', {office_id: office_id, app_date: app_date, shift_id: shift_id}, function (data)
                            {
                                $("#slot").html(data);
                            });
                        }
                    }

                });
            }
        });
       
    });
</script>
<script>

    function formsearch() {
        
        document.getElementById("actiontype").value = '1';
    }

    function formsaveapp() {
        document.getElementById("actiontype").value = '2';
    }

    function formcancel() {
        document.getElementById("actiontype").value = '3';
    }

</script>

<?php echo $this->Form->create('appointment_reschedulebyadmin', array('id' => 'appointment_reschedulebyadmin', 'class' => 'form-vertical')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class = "box box-primary">
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="office_id" class="col-sm-2 control-label">Select Office</label>  
                <div class="col-sm-4">
                    <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select ----', 'options' => $office)); ?>
                </div>
            </div>     
        </div>
        <div  class="rowht"></div>  <div  class="rowht"></div> 
        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="token_no " class="col-sm-2 control-label"><?php echo __('Token No.'); ?><span style="color: #ff0000">*</span></label>    
                <div class="col-sm-2"><?php echo $this->Form->input("token_no", array('id' => 'token_no', 'legend' => false, 'class' => 'form-control input-sm', 'label' => false)); ?>
                    <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                              ?></span>
                </div>
                <div class="col-sm-3"><button id="go" class="btn btn-primary" type="submit" onclick="javascript: return formsearch();"> <?php echo __('Submit'); ?> </button></div>
               
            </div>  
        </div>
    </div>
</div>

<?php if (!empty($appointment)) { ?>
    <div class="box box-primary">
        <div class="box-body">
            <div class="box-header with-border">
                <h4 class="box-title headbolder"><?php echo __('Appointment Details'); ?></h4>
            </div>
            <div  class="rowht">&nbsp;</div>
            <div class="row">
                <label for="" class="col-sm-2 control-label"><?php echo __('lblappointmentdt'); ?>:-<span style="color: #ff0000"></span></label>   
                <div class="col-sm-2">
                    <?php echo '<b>' . $appointment[0][0]['appointment_date'] . '</b>'; ?>
                </div>

                <label for="" class="col-sm-2 control-label"><?php echo __('lblappintmenttime'); ?>:-<span style="color: #ff0000"></span></label>   
                <div class="col-sm-2">
                    <?php echo '<b>' . $appointment[0][0]['sheduled_time'] . '</b>'; ?>
                </div>

                <label for="" class="col-sm-2 control-label"><?php echo __('lblslotno'); ?>:-<span style="color: #ff0000"></span></label>   
                <div class="col-sm-2">
                    <?php echo '<b>' . $appointment[0][0]['slot_no'] . '</b>'; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="box-header with-border">
                <h4 class="box-title headbolder"><?php echo __('Rescheduled Appointment'); ?></h4>
            </div>
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="appointment_date" class="col-sm-2 control-label"><?php echo __('lbldate'); ?><span style="color: #ff0000">*</span></label>   
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
                        <span id="appointment_date_error" class="form-error"><?php //echo $errarr['appointment_date_error'];?></span>
                    </div>
                </div>
            </div>
            <div  class="rowht">&nbsp;</div>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="shift_id" class="col-sm-2 control-label"><?php echo __('lblselectshift'); ?><span style="color: #ff0000">*</span></label>   
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('shift_id', array('label' => false, 'id' => 'shift_id', 'class' => 'form-control input-sm', 'empty' => '--Select Shift--', 'options' => array($officeshift))); ?>
                        <span id="shift_id_error" class="form-error"><?php //echo $errarr['shift_id_error'];?></span>
                    </div>

                </div>
            </div>
            <div class="row" id="slot"> 


            </div>
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row center" >
                <div class="col-sm-12">
                    <?php // echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                    <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsaveapp();"><?php echo __('btnsave'); ?></button>
                    <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return formcancel();"><?php echo __('btncancel'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<input type='hidden' value='<?php echo $action; ?>' name='actiontype' id='actiontype'/>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>



