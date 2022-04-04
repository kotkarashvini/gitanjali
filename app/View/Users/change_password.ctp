
<?php include 'csrf-magic.php'; ?>
<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
?>
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

</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        if (!navigator.onLine)
        {
            // document.body.innerHTML = 'Loading...';
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
        function formSuccess() {
            alert('Success!');
        }


        function formFailure() {
            alert('Failure!');
        }
        $("#change_password").validationEngine({
            onFormSuccess: formSuccess,
            onFormFailure: formFailure
        });
        $("#cpsubmit").click(function ()
        {
            var oldPass = $("#oldpassword ").val();
            var newpassword = $("#newpassword ").val();
            var cpassword = $("#cpassword ").val();
            var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
            if (oldPass == '')
            {
                $('#oldpassword').focus();

                return false;
            }
            if (newpassword == '')
            {
                $('#newpassword').focus();

                return false;
            }
            if (!newpassword.match(password) || newpassword.length < 8)
            {

                $('#newpassword').focus();
                return false;
            }
            if (cpassword == '')
            {

                $('#cpassword').focus();
                return false;
            }
            if (newpassword != cpassword)
            {

                $('#cpassword').focus();
                return false;
            }
            if (oldPass.length > 0)
            {
                var SHA1Hash = hex_sha1(oldPass);
                var SHA1Hash1 = hex_sha1(newpassword);
                var SHA1Hash2 = hex_sha1(cpassword);
                document.getElementById("oldpassword").value = SHA1Hash;
                document.getElementById("newpassword").value = SHA1Hash1;
                document.getElementById("cpassword").value = SHA1Hash2;
                $.ajax({
                    type: "POST",
                    url: "checkpassword",
                    data: {'pwd': SHA1Hash},
                    success: function (data) {
                        if (data == 1)
                        {
                            $('#change_password').submit();
                        }
                        else {
                            alert('Old Password did not match!');
                            return false;
                        }
                    }
                });
            }
        });

    });
    function EncryptSHA1()
    {
        var oldPass = $("#oldpassword ").val();
        if (oldPass.length > 0)
        {
            var SHA1Hash = hex_sha1(oldPass);
            $.ajax({
                type: "POST",
                url: "checkpassword",
                data: {'pwd': SHA1Hash},
                success: function (data) {
                    if (data == 1)
                    {
                        return true;
                    }
                    else {
                        alert('Old password  not correct.');
                        return false;
                    }
                }
            });
        }
    }

</script>

<?php echo $this->Form->create('change_password', array('id' => 'change_password', 'autocomplete' => 'off')); ?>

<div class="login-box"> 

    <div class="login-box-body">
        <p class="login-box-msg"><?php echo __('lblchangepass'); ?></p>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lbloldpass'); ?>
                </span> 
                <?php echo $this->Form->input('oldpassword', array('label' => false, 'type' => 'password', 'placeholder' => 'Old password', 'id' => 'oldpassword', 'class' => ' form-control input-xlarge validate[required]', 'onblur' => 'EncryptSHA1()')); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

        </div>

        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblnewpass'); ?>
                </span>
                <?php echo $this->Form->input('newpassword', array('label' => false, 'type' => 'password', 'placeholder' => 'New password', 'id' => 'newpassword', 'class' => ' form-control input-xlarge validate[required ,minSize[8],maxSize[100]')); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>

        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon" style="width:50%;">
                    <?php echo __('lblconfirmpass'); ?>
                </span>
                <?php echo $this->Form->input('cpassword', array('label' => false, 'type' => 'password', 'placeholder' => 'Confirm password', 'id' => 'cpassword', 'class' => 'form-control input-xlarge validate[required ,equals[newpassword]')); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>
        <div class="form-group" style="text-align: center">
                <input class="btn btn-lg btn-primary btn-block" type="button" id="cpsubmit" value="Submit">
        </div>
    </div>
      <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
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