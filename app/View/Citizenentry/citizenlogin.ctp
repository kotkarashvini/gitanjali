
<!--<script type="text/javascript">
    function onload_call() {
        if (!document.getElementById)
            return false;
        var f = document.getElementById('password');
        f.setAttribute("autocomplete", "off");
    }
</script>-->
<script language="javascript" type="text/javascript">
    function after_validation_check() {
        var Pass = $("#password").val();
        //var SHA1Hash = hex_sha1(Pass);
        
        var SHA1Hash = hex_sha256(Pass);
        var salt = $("#hfSaltedStr").val();
        //SHA1Hash = hex_sha1(salt + SHA1Hash);
        SHA1Hash = hex_sha256(salt + SHA1Hash);
        var SHA1Hash1 = salt + SHA1Hash;
        document.getElementById("password").value = SHA1Hash;
    }

    $(document).ready(function () {
        $("#otp").hide();
        $("#lblotp").hide();
        $("#btnSubmit").hide();
        if (!navigator.onLine)
        {
            window.location = '../cterror.html';
        }
        $("#password ").val('');
        $("#username ").val('');
        $("#captcha ").val('');
//        $('input[type=password]').disableAutocomplete();
//        $("#username").attr("autocomplete", "off");
//        $("#password").attr("autocomplete", "off");
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
           
            $("#btnSubmit").show();
            $("#btnotp").hide();

            var username = $('#username').val();
            var pwd = $('#password').val();
             var password = hex_sha256(pwd);
           
            $.post('<?php echo $this->webroot; ?>Citizenentry/otpsavecitizen', {username: username,password:password}, function (data)
            {
              // alert(data);
                if(data=='Authentication failed')
                {
                    alert('Authentication failed');
                }
                else{
                     $("#otp").show();
            $("#lblotp").show();
                    alert('OTP Sent On Registerd Mobile');
                }
//                return false;
               
            });
            return false;
        });

    

    });

//
//
//    function EncryptSHA1()
//    {
//
//        var Pass = $("#password ").val();
//        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
//        if (!Pass.match(password))
//        {
//            $("#password ").val('');
//            $("#username ").val('');
////            alert("Invalid username or password");
//            return false;
//        }
//
//        if (Pass.length > 0)
//        {
//            var SHA1Hash = hex_sha1(Pass);
//            var salt = $("#hfSaltedStr ").val();
//            SHA1Hash = hex_sha1(salt + SHA1Hash);
//            var SHA1Hash1 = salt + SHA1Hash;
//            //alert(SHA1Hash);
//            document.getElementById("password").value = SHA1Hash;
//            //$('#User').submit();
//        }
//    }
</script>

<?php echo $this->Form->create('User', array('id' => 'User', 'autocomplete' => 'off')); ?>


<div class="login-box">
    <div class="login-logo">
        
        <h2>
            Login Citizen
           
        </h2>
     
    </div>

    <div class="login-box-body log_border">
        <p class="login-box-msg"><?php echo __('lblsigntostartsession'); ?></p>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control', 'id' => 'username', 'placeholder' => 'Enter Username')); ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            <span  id="username_error" class="form-error"><?php echo $errarr['username_error']; ?></span>
        </div>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control', 'id' => 'password', 'placeholder' => 'Enter Password')); ?>
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <span  id="password_error" class="form-error"><?php echo $errarr['password_error']; ?></span>
        </div>



        <div class="form-group has-feedback">

            <?php echo $this->Form->input('captcha', array('label' => false, 'class' => 'form-control', 'id' => 'captcha', 'class' => 'form-control', 'placeholder' => 'Enter Captcha')); ?>
        </div>

        <div class="form-group has-feedback">
            <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'class' => 'img-rounded img-thumbnail')); ?>
            <button type="button" id="reload" class="btn btn-default btn-reload btn-lrg ajax">
                <i class="fa fa-spin fa-refresh"></i>
            </button>
            <?php echo $this->Form->input('hfSaltedStr', array('type' => 'hidden', 'id' => 'hfSaltedStr', 'value' => $saltstring)); ?>
            <?php echo $this->Form->input('hfLoginCount', array('type' => 'hidden', 'id' => 'hfLoginCount', 'value' => $logincount1)); ?>
        </div>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('otp', array('label' => false, 'class' => 'form-control', 'id' => 'otp', 'autocomplete' => 'off', 'placeholder' => 'Enter OTP','value'=>$otp)); ?>
<!--            <span class="glyphicon glyphicon-lock form-control-feedback"></span>-->
        </div>

        <div class="row">
            <div class="col-xs-4" style="float:right;">
                <button type="submit" class="btn btn-primary btn-block" id="btnotp" name="btnotp">
                    <span class="glyphicon"></span> <?php echo __('lblgetotp'); ?>
                </button>
            </div>
        </div>
         <div class="row">
            <div class="col-xs-4" style="float:right;">              
                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" name="btnSubmit">
                    <span class="glyphicon"></span> <?php echo __('lbllogin'); ?>
                </button>
                <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>
            </div>
        </div>
        
                <?php echo $this->Html->link(__('Forgot Password?'), array('controller' => 'Citizenuser','action' => 'forgotpassword_citizen'), array('target' => '_self')); ?>
     

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

    });
</script>
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