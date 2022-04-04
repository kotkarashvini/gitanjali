<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<script>
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
    http://localhost/NGDRShttp://localhost/NGDRS
</script>
<?php
?>
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
        function disableBack() {
            window.history.forward()
        }
        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }
    });
//    $(document).ready(function () {
//        function formSuccess() {
//            alert('Success!');
//        }
//        function formFailure() {
//            alert('Failure!');
//        }
//        $("#User").validationEngine({
//            onFormSuccess: formSuccess,
//            onFormFailure: formFailure
//        });
//    });
    function EncryptSHA1()
    {
        var Pass = $("#password").val();
        if (Pass.length > 0)
        {
            var SHA1Hash = hex_sha1(Pass);
            var salt = $("#hfSaltedStr ").val();
            SHA1Hash = hex_sha1(salt + SHA1Hash);
            var SHA1Hash1 = salt + SHA1Hash;
            //alert(SHA1Hash);
            document.getElementById("password").value = SHA1Hash;
            //$('#User').submit();
        }
    }
</script>

<?php echo $this->Form->create('User', array('id' => 'User', 'autocomplete' => 'off')); ?>
<!--<input type="hidden" name="name" value="CSRF-TOKEN" />-->
<div class="login-box"> 
    <div class="login-box-body">
        <p class="login-box-msg"><?php echo __('lblsigntostartsession'); ?></p>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblusername'); ?>
                </span> 
                <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'class' => 'form-control', 'autocomplete' => 'off', 'autofocus')); ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div align="center"> <span  id="username_error" class="form-error"><?php echo $errarr['username_error']; ?></span></div>
        </div>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblpassword'); ?>  
                </span>
                <?php echo $this->Form->input('password', array('label' => false, 'id' => 'password', 'class' => 'form-control ', 'autocomplete' => 'off')); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div align="center"> <span  id="password_error" class="form-error"><?php echo $errarr['password_error']; ?></span></div>
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
                <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image')); ?>
                <?php echo $this->Form->input('hfSaltedStr', array('type' => 'hidden', 'id' => 'hfSaltedStr', 'value' => $saltstring)); ?>
                <?php echo $this->Form->input('hfLoginCount', array('type' => 'hidden', 'id' => 'hfLoginCount', 'value' => $logincount1)); ?>
            </center>
        </div>
        <div class="form-group">
            <center>
                <button type="submit" class="btn_classic btn_color"  id="btnSubmit" name="btnSubmit" onclick="javascript: return EncryptSHA1();">
                    <div class="light"></div> <?php echo __('lbllogin'); ?>
                </button>
            </center>
            <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>
        </div>
        <center>
            <?php echo $this->Html->link(__('Forgot Password?'), array('action' => 'forgotpassword'), array('target' => '_self')); ?><br>
            <?php echo $this->Html->link(__('Sign Up'), array('action' => 'registration'), array('target' => '_self')); ?>
        </center>
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