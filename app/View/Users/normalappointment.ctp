<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        $('#from,#to').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });

        $('#checkdate').hide();
        if (!navigator.onLine) {
            // window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward()
        }


        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }

        function formSuccess() {
            alert('Success!');
        }

        function formFailure() {
            alert('Failure!');
        }

        $("#cmdSubmit").click(function () {

            var office_id = $("#office_id option:selected").val();
            var from = $('#from').val();
            var to = $('#to').val();
            var cp = $('#captcha').val();
            //alert(cp);return false;
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkcaptcha",
                data: {'cp': cp},
                success: function (data) {

                    if (data == 0)
                    {
                        alert('Captcha does not match.');
                        $('#captcha').focus();
                        return false;
                    }
                    else if (data == 1)
                    {
                        $.post('<?php echo $this->webroot; ?>Users/get_available_appointment', {office_id: office_id, from: from, to: to}, function (data1)
                        {
                            $('#apptable').html(data1);
                        });
                    }
                }
            });

        });

        $("#bydate").click(function () {
            $('#checkdate').show();

        });



    });

    function checkcaptcha() {

    }



</script>

<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
?>
<?php echo $this->Session->flash('auth'); ?> 
<?php echo $this->Form->create('normalappointment', array('id' => 'normalappointment', 'autocomplete' => 'off')); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblappointmentavailability'); ?></h3></center>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lblselofc'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Office--', $office))); ?>
                        </div>
                        <label for="captcha" class="col-sm-2 control-label"><?php echo __('lblcaptcha'); ?>:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('captcha', array('label' => false, 'maxlength' => '6', 'id' => 'captcha', 'class' => 'form-control', 'onblur' => 'checkcaptcha()')); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'style' => 'width:100%', 'style' => 'hight:50px !important')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-info" id="bydate" name="cmdSubmit">
                                <?php echo __('lblcheckbydate'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row" id="checkdate">
                    <div class="form-group">
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?> :<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm')); ?>
                        </div>
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm')); ?>
                        </div>

                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <button type="button" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                            <?php echo __('lblcheckappoavailability'); ?>
                        </button>

                    </div>
                </div>

            </div>
        </div>

        <div id="apptable">

        </div>

    </div>
</div>


<?php echo $this->Form->end(); ?>

<script language="JavaScript" type="text/javascript">
    var message = "Right Click Not Allowed";

    function onlyNumbers(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else {
                return true;
            }
            if ((charCode > 47 && charCode < 58) || charCode == 08)
                return true;
            else
                return false;
        }
        catch (err) {
            alert(err.Description);
        }
    }

    function onlyCity(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else {
                return true;
            }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 40 || charCode == 41 || charCode == 45 || charCode == 08)
                return true;
            else
                return false;
        }
        catch (err) {
            alert(err.Description);
        }
    }
</script>