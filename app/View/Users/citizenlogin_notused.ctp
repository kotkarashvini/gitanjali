
<script type="text/javascript">

    function onload_call() {
        if (!document.getElementById)
            return false;
        var f = document.getElementById('password');
        f.setAttribute("autocomplete", "off");
    }
</script>

<script language="javascript" type="text/javascript">


    $(document).ready(function () {

        $("#otp").hide();
        $("#lblotp").hide();
        if (!navigator.onLine)
        {
            window.location = '../cterror.html';
        }
        $("#password ").val('');
        $("#username ").val('');
        $("#captcha ").val('');
        $('input[type=password]').disableAutocomplete();
        $("#username").attr("autocomplete", "off");
        $("#password").attr("autocomplete", "off");

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
        $('#btnotp').click(function () {
            $("#otp").show();
            $("#lblotp").show();

            var username = $('#username').val();
            $.post('<?php echo $this->webroot; ?>Users/otpsave', {username: username}, function (data)
            {
                alert(data);
            });
            return false;
        });
    });




</script>


<style type="text/css">
    .panel-heading {
        padding: 5px 15px;
    }

    .panel-footer {
        padding: 1px 15px;
        color: #A0A0A0;
    }

    .profile-img {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
</style>

<?php echo $this->Form->create('User', array('id' => 'User', 'autocomplete' => 'off')); ?>
<input type="hidden" name="name" value="CSRF-TOKEN" />
<?php echo $Laug; ?>
<div class="login-box">
    <div class="login-box-body">
        <p class="login-box-msg"> <?php echo __('lblsigntostartsession'); ?></p>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblusername'); ?>
                </span> 
                <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'class' => 'form-control validate[required ,custom[onlyLetterNumberdotnotspace]]', 'autocomplete' => 'off')); ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
        </div>


        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblpassword'); ?>
                </span>
                <?php echo $this->Form->input('password', array('label' => false, 'id' => 'password', 'class' => 'form-control validate[required]', 'autocomplete' => 'off')); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblcaptcha'); ?>
                </span>
                <?php echo $this->Form->input('captcha', array('label' => false, 'id' => 'captcha', 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="form-group">
            <center>
                <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'style' => 'width:80%', 'style' => 'hight:50px !important')); ?>
                <?php echo $this->Form->input('hfSaltedStr', array('type' => 'hidden', 'id' => 'hfSaltedStr', 'value' => $saltstring)); ?>
                <?php echo $this->Form->input('hfLoginCount', array('type' => 'hidden', 'id' => 'hfLoginCount', 'value' => $logincount1)); ?>
            </center>                                  
        </div>


        <div class="col-sm-12">
            <center>

                <div class="col-sm-12" style="text-align: center">
                    <button type="submit" class="btn btn-info" style="width: 40%;" id="btnotp" name="btnotp" style="width: 100%">
                        <span class="glyphicon"></span> <?php echo __('lblgetotp'); ?>

                    </button>
                </div>
            </center>                                
        </div>

        <div class="col-sm-12">
            <center>
                <label for="usage_sub_sub_catg_id" class="control-label col-sm-4" id="lblotp"><?php echo __('lblenterotp'); ?></label>
                <div class="col-sm-8" style="text-align: center">

                    <?php echo $this->Form->input('otp', array('label' => false, 'id' => 'otp', 'autocomplete' => 'off')); ?></div>
                <div class="col-sm-4">

                </div>
            </center>                                
        </div>

        <div class="form-group">
            <center>




            </center>                                  
        </div>
        <br>
        <div class="form-group">
            <center> <br><br>
                <button type="submit" class="btn btn-info" style="width: 40%;" id="btnSubmit" name="btnSubmit" style="width: 100%">
                    <span class="glyphicon"></span> <?php echo __('lbllogin'); ?>
                </button>
            </center>
            <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>
        </div>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>


    </div>
</div> 


<?php echo $this->Form->end(); ?>

<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#reload').click(function ()
        {
            var captcha = $("#captcha_image");
            captcha.attr('src', captcha.attr('src') + '?' + Math.random());
            return false;
        });
    });</script>
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