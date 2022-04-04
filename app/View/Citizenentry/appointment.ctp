<script>

    $(document).ready(function () {
        $("#appointment_date").val();
        $("#reschedule").hide();
        $("#reschedule_flag").val('N');


        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        $('#appointment_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            dayOfWeekDisabled: [0, 6],
            startDate: '<?php echo $startday; ?>',
            endDate: '<?php echo $normal_days; ?>',
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            //if(app_date.getDay() == 6 || app_date.getDay() == 0) alert('Weekend!');

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

        $("#cancelapt").click(function () {

            $("#reschedule").show();
            $("#reschedule_flag").val('Y');


        });


    });



//            $.post('<?php echo $this->webroot; ?>Citizenentry/cancel_appointment', {csrftoken: csrftoken}, function (data)
//            {
//                if (data.trim() == 1)
//                {
//                    alert('Appointment cancel successufully');
//                    window.location.href = "<?php echo $this->webroot; ?>Citizenentry/appointment/<?php echo $this->Session->read('csrftoken'); ?>";
//                                    }
//                                });
//



</script> 
<?php
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
?>
<?php if ($submission_flag == 'Y') { ?>
    <?php echo $this->Form->create('appointment', array('id' => 'appointment')); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblappointmentdetails') . '  :-  ' . $officename; ?></h3></center>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/tatkalappoinment_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div>
                </div>
                <?php if ($tatkal_paid == 'Y') { ?>
                    <div class="box-header with-border">
                        <center style="color: red;"><h3 class="box-title headbolder"><?php echo __('You have paid tatkal appointment fee now click on Tatkal appointment button'); ?></h3></center>

                    </div>
                <?php } ?>
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
                                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $tokenval, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <div class="box box-primary">
                <div class="box-body">
                    <?php if (!empty($appointment)) { ?>
                        <div id="aptdetails">
                            <div class="box box-primary">

                                <div class="box-header with-border">
                                    <h3 class="box-title headbolder"><?php echo __('lblappointmentdetails'); ?></h3>
                                    <?php if ($reschedule == 'Y') { ?>
                                        <span style="font-weight: bold; color: red;">&nbsp;&nbsp;&nbsp;(First Cancel the Old Appointment and then take New Appointment)</span>
                                        <button type="button" style="width: 170px; float:right;"  name="btnCancel" class="btn btn-info" value="Reschedule Appoinment"  id="cancelapt">
                                            <?php echo __('lblctncancelappointment'); ?>
                                        </button>
                                    <?php } ?>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-1"></div>
                                            <label for="" class="col-sm-2 control-label"><?php echo __('lblappointmentdt'); ?><span style="color: #ff0000"></span></label>   
                                            <div class="col-sm-2">
                                                <?php echo $appointment[0]['appointment']['appointment_date']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-1"></div>
                                            <label for="" class="col-sm-2 control-label"><?php echo __('lblappintmenttime'); ?><span style="color: #ff0000"></span></label>   
                                            <div class="col-sm-2">
                                                <?php echo $appointment[0]['appointment']['sheduled_time']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-1"></div>
                                            <label for="" class="col-sm-2 control-label"><?php echo __('lblslotno'); ?><span style="color: #ff0000"></span></label>   
                                            <div class="col-sm-2">
                                                <?php echo $appointment[0]['appointment']['slot_no']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="reschedule">
                            <div class="box-header with-border">
                                <h3 class="box-title headbolder"><?php echo __('Reschedule Appointment'); ?></h3>

                            </div>
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
                                        <?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
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
                                    <?php echo $this->Form->input('reschedule_flag', array('label' => false, 'id' => 'reschedule_flag', 'type' => 'hidden')); ?>
                                    <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                                    <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                                </div>
                            </div>


                        </div>
                        <div  class="rowht">&nbsp;</div>
                    <?php } else { ?>

                        <div class="row left" >
                            <div class="col-sm-12">
                                <!--                               For Tatkal Appointment-->
                                <?php if ($tatkal == 'Y') {
                                    if ($tatkal_availbility == 'Y') {
                                        ?>
                                        <a href="<?php echo $this->webroot; ?>Citizenentry/tatkalappoinment/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-info ">
                                            <button type="button" style="width: 150px;"  name="btnCancel" class="btn btn-info" value="Tatkal Appoinment" >
                <?php echo __('btntatkalappointment'); ?>
                                            </button>
                                        </a>
            <?php }
        } ?>
                                <!--                               For Tatkal Appointment-->

                                <!--                               For Government Office Appointment-->
                                <?php
                                if ($gov_apt == 'Y') {
                                    if ($gov_apt_availbility == 'Y') {
                                        if ($gov_apt_show == 'Y') {
                                            ?>
                                            <a href="<?php echo $this->webroot; ?>Citizenentry/govappoinment/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-info ">
                                                <button type="button" style="width: 250px;"  name="btnCancel" class="btn btn-info" value="Government Office Appoinment" >
                    <?php echo __('Governement Office Appointment'); ?>
                                                </button>
                                            </a>
                <?php }
            }
        } ?>
                                <!--                               For Government Office Appointment-->
                            </div>
                        </div>

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
        <?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
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
    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="headbolder center" style="color: red"><?php echo __('lblpleasefirstsubmitapplication'); ?></h3> 
                </div>
            </div>
        </div>
    </div>
<?php } ?>



<?php echo $this->Form->end(); ?>