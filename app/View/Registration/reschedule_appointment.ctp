<script>

    $(document).ready(function () {
        $("#appointment_date").val();

        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        
         var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            if (app_date != '')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date, csrftoken: csrftoken}, function (data)
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
                            $.post('<?php echo $this->webroot; ?>Citizenentry/slot_alocation', {office_id: office_id, app_date: app_date, shift_id: shift_id, csrftoken: csrftoken}, function (data)
                            {

                                $("#slot").html(data);
                            });
                        }
                    }

                });
            }

        $('#appointment_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            startDate: "today",
            endDate: '<?php echo $normal_days; ?>',
            dayOfWeekDisabled: [0, 6],
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date, csrftoken: csrftoken}, function (data)
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
                        $.post('<?php echo $this->webroot; ?>Citizenentry/slot_alocation', {office_id: office_id, app_date: app_date, shift_id: shift_id, csrftoken: csrftoken}, function (data)
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
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date, csrftoken: csrftoken}, function (data)
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
                            $.post('<?php echo $this->webroot; ?>Citizenentry/slot_alocation', {office_id: office_id, app_date: app_date, shift_id: shift_id, csrftoken: csrftoken}, function (data)
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
<?php
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Registration/main_menu");
echo $this->Html->css('popup');

?>

    <?php echo $this->Form->create('appointment', array('id' => 'appointment')); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblappointmentdetails'); ?></h3></center>
                        <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/tatkalappoinment_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblapppt'); ?></p>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?>:-<span style="color: #ff0000"></span></label>   
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('token_no', array('label' => false, 'id' => '', 'value' => $tokenval, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="box box-primary">
                <div class="box-body">
                 
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-3"></div>

                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $office_id)); ?>
                                </div>

                            </div>

                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label for="appointment_date" class="col-sm-2 control-label"><?php echo __('lbldate'); ?><span style="color: #ff0000">*</span></label>   
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm','value'=>date('d-m-Y', strtotime($date)))); ?>

                                </div>

                            </div>

                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label for="shift_id" class="col-sm-2 control-label"><?php echo __('lblselectshift'); ?><span style="color: #ff0000">*</span></label>   
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('shift_id', array('label' => false, 'id' => 'shift_id', 'class' => 'form-control input-sm', 'options' => array($officeshift))); ?>
                                </div>

                            </div>

                        </div>
                        <div  class="rowht"></div>
                        <div class="row" id="slot"> 


                        </div>
                        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                        <div class="row center" >
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                                <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                 
                </div>
            </div>
        </div>
    </div>


<?php echo $this->Form->end(); ?>