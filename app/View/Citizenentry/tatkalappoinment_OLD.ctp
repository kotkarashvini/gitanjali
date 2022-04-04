<script>

    $(document).ready(function () {
        $("#appointment_date").val();
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        $('#appointment_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            startDate: "today",
            endDate:'<?php echo $tatkal_days; ?>',
            daysOfWeekDisabled:[0,6],
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();
            if (app_date != '' && office_id != '' && shift_id != '')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date,csrftoken:csrftoken}, function (data)
                {

                    if (data.trim() == 'b')
                    {
                        alert('Holiday ! Select another date');
                        $("#slot").html('');
                        $("#appointment_date").val('');
                        return false;
                    }
                    if (data.trim() == 'a')
                    {

                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_maxappointmentday', {app_date: app_date, office_id: office_id, shift_id: shift_id,csrftoken:csrftoken}, function (data1)
                        {
                            if (data1.trim() != 'b')
                            {
                                alert('Select appoinment date before ' + data1 + ' days');
                                $("#slot").html('');
                                $("#maxdays").html(data1.trim());

                                return false;
                            }
                            else
                            {

                                var office_id = $("#office_id").val();

                                var shift_id = $("#shift_id").val();

                                if (shift_id == '')
                                {
                                    alert('Please select shift');
                                }
                                else
                                {
                                    $.post('<?php echo $this->webroot; ?>Citizenentry/tatkal_slot_alocation', {office_id: office_id, app_date: app_date, shift_id: shift_id,csrftoken:csrftoken}, function (data2)
                                    {

                                        $("#slot").html(data2.trim());
                                    });
                                }
                            }
                        });
                    }

                });

            }

        });



        $("#shift_id").change(function () {
       
            var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            if (app_date != '' && office_id != '' && shift_id != '')
            { 

                $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date,csrftoken:csrftoken}, function (data)
                {

                    if (data.trim() == 'b')
                    {
                        alert('Holiday ! Select another date');
                        $("#slot").html('');
                        $("#appointment_date").val('');
                        return false;
                    }
                    if (data.trim() == 'a')
                    {

                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_maxappointmentday', {app_date: app_date, office_id: office_id, shift_id: shift_id,csrftoken:csrftoken}, function (data1)
                        {
                            if (data1.trim() != 'b')
                            {
                                alert('Select appoinment date before ' + data1.trim() + ' days');
                                $("#slot").html('');
                                $("#maxdays").html(data1.trim());

                                return false;
                            }
                            else
                            {

                                var office_id = $("#office_id").val();

                                var shift_id = $("#shift_id").val();

                                if (shift_id == '')
                                {
                                    alert('Please select shift');
                                }
                                else
                                {
                                    $.post('<?php echo $this->webroot; ?>Citizenentry/tatkal_slot_alocation', {office_id: office_id, app_date: app_date, shift_id: shift_id,csrftoken:csrftoken}, function (data2)
                                    {

                                        $("#slot").html(data2.trim());
                                    });
                                }
                            }
                        });
                    }

                });

            }

        });

        $("#cancelapt").click(function () {
            $.post('<?php echo $this->webroot; ?>Citizenentry/cancel_appointment', {csrftoken:csrftoken}, function (data)
            {
                if (data.trim() == 1)
                {
                    alert('Appointment cancel successufully');
                   window.location.href = "<?php echo $this->webroot; ?>Citizenentry/tatkalappoinment/<?php echo $this->Session->read('csrftoken');?>";
                }
            });

        });
        
     

    });
    
    function formsave(){
      var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
      var app_date = $("#appointment_date").val();
            var office_id = $("#office_id").val();
            var shift_id = $("#shift_id").val();

            if (app_date != '' && office_id != '' && shift_id != '')
            { 

                $.post('<?php echo $this->webroot; ?>Citizenentry/check_appointmentdate', {app_date: app_date,csrftoken:csrftoken}, function (data)
                {

                    if (data.trim() == 'b')
                    {
                        alert('Holiday ! Select another date');
                        $("#slot").html('');
                        $("#appointment_date").val('');
                        return false;
                    }
                    if (data.trim() == 'a')
                    {

                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_maxappointmentday', {app_date: app_date, office_id: office_id, shift_id: shift_id,csrftoken:csrftoken}, function (data1)
                        {
                            if (data1.trim() != 'b')
                            {
                                alert('Select appoinment date before ' + data1.trim() + ' days');
                                $("#slot").html('');
                                $("#maxdays").html(data1.trim());

                                return false;
                            }
                            else
                            {
                           
                                    $("#tatkalappoinment").submit();
                               
                               
                            }
                        });
                    }

                });

            }else{
            alert('Please select Appointment Details');
            return false;
            }
    }
    
     function payment(){
     
          window.open('<?php echo $this->webroot; ?>WebService/payu_payment_entry');
          return false;
        
       
    }
</script> 

<?php
echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
 $doc_lang = $this->Session->read('doc_lang');
?>
<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
?>
<?php if ($submission_flag == 'Y') { ?>
    <?php echo $this->Form->create('tatkalappoinment', array('id' => 'tatkalappoinment')); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lbltatkalappidetails'); ?></h3><a href="<?php echo $this->webroot;?>helpfiles/citizenentry/tatkalappoinment_<?php echo $doc_lang; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo  __('Help');?> <span class="fa fa-question fa-circle-o"></span></a></center>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="box-body">
                                         <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-1"></div>
                                            <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?> :-<span style="color: #ff0000"></span></label>   
                                           
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $tokenval, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                            </div> 
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-5">
                                                    <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('Tatkal Appointment Fee:-'.$amount.'  Rs.'); ?></br>
                                                        
                                                       
                                                </div>
                                        </div>
                                            </div>
<!--                                        <div class="col-sm-3">
                                      
                                        <div class="row">
                                            <div class="form-group">
                                                
                                            </div>
                                        </div>
                                        </div>
                                   -->

                                    </div>
                                </div>
                                 

                            </div>
                        </div>
                    </div>
                    <?php if (!empty($appointment)) { ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box box-primary">

<!--                                    <button type="button" style="width: 150px; float:right;"  name="btnCancel" class="btn btn-info" value="Cancel Appoinment"  id="cancelapt">
                                        <?php //echo __('lblctncancelappointment'); ?>
                                    </button>-->
                                    <div class="box-danger center"><big><b><?php echo __('lblappointmentdetails'); ?> </b></big></div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-1"></div>
                                                <label for="" class="col-sm-2 control-label"><?php echo __('lblappointmentdt'); ?> :-<span style="color: #ff0000"></span></label>   
                                                <div class="col-sm-2">
                                                    <?php echo $appointment[0]['appointment']['appointment_date']; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-1"></div>
                                                <label for="" class="col-sm-2 control-label"><?php echo __('lblappintmenttime'); ?> :-<span style="color: #ff0000"></span></label>   
                                                <div class="col-sm-2">
                                                    <?php echo $appointment[0]['appointment']['sheduled_time']; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-1"></div>
                                                <label for="" class="col-sm-2 control-label"><?php echo __('lblslotno'); ?> :-<span style="color: #ff0000"></span></label>   
                                                <div class="col-sm-2">
                                                    <?php echo $appointment[0]['appointment']['slot_no']; ?>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } else { ?>

                        <a href="<?php echo $this->webroot; ?>Citizenentry/appointment/<?php echo $this->Session->read('csrftoken');?>" class="btn btn-primary "><button type="button" style="width: 150px;"  name="btnCancel" class="btn btn-primary" value=" Appoinment" >
                                <?php echo __('lblnormatappointment'); ?></button></a>   
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">

                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $office_id)); ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="col-sm-3"></div>
                                    <label for="appointment_date" class="col-sm-2 control-label"><?php echo __('lbldate'); ?>:-<span style="color: #ff0000">*</span></label>   
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
                                    </div>

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
                        <br>


                        <div class="row" id="slot"> 


                        </div>
                    </div>
                    <input type="hidden" id="maxdays" name="maxdays">
                    <div class="row" style="text-align: center;">
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                            
                            <!--<button id="makepayment"><?php //echo __('Make Payment'); ?></button>-->
                            <!--<input type="button" id="makepayment" name="makepayment" class="btn btn-info" value="<?php echo __('Make Payment'); ?>" onclick="javascript: return payment()">-->
                            <input type="button" id="btnNext" name="btnNext" class="btn btn-info" value="<?php echo __('Proceed'); ?>" onclick="javascript: return formsave()">
                            <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <br>
                </div>
            </div>

        <?php } ?>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblpleasefirstsubmitapplication'); ?></h3></center>
                </div>
            </div></div>
    </div>
<?php } ?>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Make Payment</h4>
            </div>
            <div class="modal-body" id="surveyno_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>