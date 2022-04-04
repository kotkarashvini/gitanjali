<?php
echo $this->Html->script('JS');
$doc_lang = $this->Session->read('doc_lang');
?>  
<script language="javascript" type="text/javascript">


    $(document).ready(function () {
        $("#otp").hide();
        $("#lblotp").hide();
        if (!navigator.onLine)
        {
            //window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }
        

        $("#btnsubmit").click(function ()
        {

            var newpassword = $("#newpassword ").val();
            var rpassword = $("#rpassword ").val();
            var username = $("#username ").val();

            //  var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;

            if (username.length > 0)
            {
                var SHA1Hash = username;

                $.ajax({
                    type: "POST",
                    url: "checkusername2",
                    data: {'user_name': SHA1Hash},
                    success: function (data) {
                        if (data === 'r0')
                        {
                            alert('Username did not match!');
                            return false;
                        } else if (data === 'r1') {
                            var SHA1Hash1 = hex_sha1(newpassword);
                            var SHA1Hash2 = hex_sha1(rpassword);
                            document.getElementById("username").value = SHA1Hash;
                            document.getElementById("newpassword").value = SHA1Hash1;
                            document.getElementById("rpassword").value = SHA1Hash2;
                            $('#resetpassword_citizen').submit();

                        }
                    }
                });
            }
        });
       

//otp button click otpsave
//        $('#btnotp1').click(function () {
//            alert();
//            $("#otp").show();
//            $("#lblotp").show();
            
              $('#btnotp1').click(function () {
            $("#otp").show();
            $("#lblotp").show();
            $("#btnSubmit").show();
            $('#btnotp').hide();

            var username = $('#username').val();
//            alert(username);

             $.post('<?php echo $this->webroot; ?>Users/otpresetpasswordcitizen', {username: username}, function (data)
            {
//                alert(data); return false;
                alert('OTP Sent On Registerd Mobile');
            });
            return false;
        });

           

    });
    
    
    function after_validation_check() {
        var password1 = $("#password1").val();
        var newpassword = $("#newpassword").val();
        var rpassword = $("#rpassword").val();


        var SALT = "<?php echo $this->Session->read("salt"); ?>";
        $("#password1").val(encrypt(password1, SALT));
        $("#newpassword").val(encrypt(newpassword, SALT));
        $("#rpassword").val(encrypt(rpassword, SALT));
       // return false;
    }





</script>

<?php echo $this->Form->create('resetpassword_citizen'); ?>

<div class="login-box"> 

    <div class="login-box-body">
        <a href="<?php echo $this->webroot; ?>helpfiles/Users/resetpassword_<?php echo $doc_lang; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo __('help'); ?> <span class="fa fa-question fa-circle-o"></span></a>
        <p class="login-box-msg"><?php echo __('lblresetpass'); ?></p>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblusername'); ?>
                </span> 
                <?php echo $this->Form->input('username', array('label' => false, 'type' => 'text', 'id' => 'username', 'class' => ' form-control', 'value' => $user)); ?>
                <span id="username_error" class="form-error"><?php //echo $errarr['username_error'];          ?></span>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

        </div>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblcurrentpass'); ?>
                </span>
                <?php echo $this->Form->input('password1', array('label' => false, 'type' => 'password', 'id' => 'password1', 'class' => ' form-control', 'data-toggle' => 'tooltip', 'title' => 'Enter Current Password')); ?>
                <span id="password1_error" class="form-error"><?php echo $errarr['password1_error']; ?></span>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>

        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblnewpass'); ?>
                </span>
                <?php echo $this->Form->input('newpassword', array('label' => false, 'type' => 'password', 'id' => 'newpassword', 'class' => ' form-control', 'data-toggle' => 'tooltip', 'title' => 'Enter New Password')); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                <span id="newpassword_error" class="form-error"><?php echo $errarr['newpassword_error']; ?></span>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>

        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblconfirmpass'); ?>
                </span>
                <?php echo $this->Form->input('rpassword', array('label' => false, 'type' => 'password', 'id' => 'rpassword', 'class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Confirm Password')); ?>
                <span id="rpassword_error" class="form-error"><?php echo $errarr['rpassword_error']; ?></span>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>
        <div class="col-sm-12">
            <center>
                <div class="col-sm-12" style="text-align: center">
                    <button type="button" class="btn btn-info" style="width: 40%;" id="btnotp1" name="btnotp1" style="width: 100%">
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
            <button type="submit" class="btn btn-info" id="btnsubmit" name="btnsubmit" onclick="javascript: return EncryptSHA1();" style="width: 100%">
                <span class="glyphicon"></span> <?php echo __('btnsubmit'); ?>
            </button>
        </div>

    </div>
</div>
<div class="box-header with-border">
        <center><h3 class="box-title headbolder" style="color: red">After Successfully Password Change... This Session will be Logout Automatically...!!!  You Should Have To Login Once Again With New Password......!!!</h3></center>
           
            </div>






<?php echo $this->Form->end(); ?>

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