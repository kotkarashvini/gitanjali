

<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
?>
<!--<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });

</script>-->
<script language="javascript" type="text/javascript">


    $(document).ready(function () {

        $("#otp").hide();
        $("#lblotp").hide();
        if (!navigator.onLine)
        {
            window.location = '../cterror.html';
        }

        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }


    });
    $(document).ready(function () {
        function formSuccess() {
            alert('Success!');
        }

        function formFailure() {
            alert('Failure!');
        }
        $("#User1").validationEngine({
            onFormSuccess: formSuccess,
            onFormFailure: formFailure
        });


//otp button click otpsave
      
//otp button click otpsave
        $('#btnotp').click(function () {
            $("#otp").show();
            $("#lblotp").show();
              $("#btnok").show();

//            var username = $('#username').val();
            $.post('<?php echo $this->webroot; ?>Users/otpsavesro', { }, function (data)
            {
                alert('OTP Sent On Registerd Mobile');
            });
            return false;
        });
    });




</script>



<?php echo $this->Form->create('biometricotp', array('autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary"> 

            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo 'OTP'; ?></h3></center>

            </div>
            <div class="box-body">

                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-6">
                            <label class="control-label col-sm-12" ><?php echo __('lblfingerverificationfailed'); ?></label>
                        </div>
                        <div id="divotp" class="col-sm-2 tdadd" >
                            <button type="submit" class="btn btn-info"  id="btnotp" name="btnotp" style="width: 100%">
                                <span class="glyphicon"></span> <?php echo __('lblgetotp'); ?>
                            </button>
                        </div>
                    </div>   
                </div>   
                <div class="rowht"></div><div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="lblotp" class="control-label col-sm-2" id="lblotp"><?php echo __('lblenterotp'); ?></label>
                        <div class="col-sm-2" style="text-align: center">
                            <?php echo $this->Form->input('otp', array('label' => false, 'id' => 'otp', 'autocomplete' => 'off')); ?></div>
                    </div>
                </div>
                <div class="rowht"></div>
                <div class="rowht"></div>
                <div class="rowht"></div>
                <div class="row">
                    <div class="form-group center" hidden="true" id="btnok">
                        <button type="submit" class="btn btn-info"  id="btnSubmit" name="btnSubmit">
                            <span class="glyphicon"></span> <?php echo __('lblok'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
         <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
        <input type='hidden' value='<?php //echo $actiontype;    ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php // echo $cap;    ?>' name='cap' id='cap'/>
        <input type='hidden' value='<?php // echo $biometcount;    ?>' name='biometcount' id='biometcount'/>
        <?php // echo $this->Form->input('biometcount', array('type' => 'text', 'id' => 'biometcount', 'value' => $biometcount)); ?>
    </div>

    <?php echo $this->Form->end(); ?>
</div>



<script language="JavaScript" type="text/javascript">
    var message = "Not Allowed Right Click";
    function rtclickcheck(keyp)
    {
        if (navigator.appName == "Netscape" && keyp.which == 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>