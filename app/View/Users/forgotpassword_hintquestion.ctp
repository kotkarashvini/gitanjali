<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
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

        // $('#username').val('');
        $('#email_id').val('');
        $('#mobile_no').val('');

        $('#username').focus();
        if (!navigator.onLine)
        {
            // document.body.innerHTML = 'Loading...';
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

        //Show record div on get record
        var recorddata = document.getElementById('recorddata').value;
        if (recorddata == '1') {
            $('#divhintdetails').show();
            $('#divuserdetails').hide();
            $('#divotpdetails').hide();
            $('#divchangepassword').hide();
        }
        else if (recorddata == '2') {
            $('#divhintdetails').hide();
            $('#divuserdetails').hide();
            $('#divotpdetails').show();
            $('#divchangepassword').hide();
        }
        else if (recorddata == '3') {
            $('#divhintdetails').hide();
            $('#divuserdetails').hide();
            $('#divotpdetails').hide();
            $('#divchangepassword').show();
        }
    });

    function userdetailsubmit()
    {
        document.getElementById("actiontype").value = '1';//Get Record
        var username = document.getElementById('username').value;
        //var email_id = document.getElementById('email_id').value;
        //var mobile_no = document.getElementById('mobile_no').value;
//          alert(mobile_no.length);
//        if (mobile_no.length == 12)
//        {
//            var mobile_no = mobile_no.substring(2, 12);
//
//        }
// if (username != '' && email_id != '' && mobile_no != '') {
        if (username != '') {
            //var Hash_username = hex_sha1(username);
//            var Hash_email_id = hex_sha1(email_id);
//            var Hash_mobile_no = hex_sha1(mobile_no);
//            alert(mobile_no.length);
//            //document.getElementById("username").value = Hash_username;
//            document.getElementById("email_id").value = Hash_email_id;
//            document.getElementById("mobile_no").value = Hash_mobile_no;
            $('#forgotpassword').submit();
        }
        else {
            if (username == '') {
                alert('Please enter username');
                return false;
            }
//            if (email_id == '') {
//                alert('Please enter Email-Id');
//                return false;
//            }
//            if (mobile_no == '') {
//                alert('Please enter mobile no');
//                return false;
//            }
        }

    }

    function hintdetailsubmit()
    {
        document.getElementById("actiontype").value = '2';//Get Record
        var hint_answer = document.getElementById('hint_answer').value;
        if (hint_answer != '') {
            $('#forgotpassword').submit();
        }
        else {
            alert('Please enter answer');
            return false;
        }
    }

    function otpdetailsubmit()
    {

        document.getElementById("actiontype").value = '3';//Get Record
        var txtotp = document.getElementById('txtotp').value;
        if (txtotp != '') {
            $('#forgotpassword').submit();
        }
        else {
            alert('Please enter OTP');
            return false;
        }
    }

    function changepasswordsubmit()
    {
        document.getElementById("actiontype").value = '4';//Get Record
        var newpassword = $("#newpassword ").val();
        var cpassword = $("#cpassword ").val();
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;

        if (newpassword == '')
        {
            alert('Please enter new password');
            $('#newpassword').focus();


            return false;
        }
        if (!newpassword.match(password) || newpassword.length < 8)
        {
            alert('* Only Hash(#),At Sign(@) & Star(*)  with at least one capital,one small alphabet & one number with min length 8 allowed in Password field');

            $('#newpassword').focus();
            return false;
        }
        if (cpassword == '')
        {
            alert('Please enter confirm password ');
            $('#cpassword').focus();
            return false;
        }
        if (newpassword != cpassword)
        {
            alert('Password does not match');
            $('#cpassword').focus();
            return false;
        }
//        var SHA1Hash1 = hex_sha1(newpassword);
//        var SHA1Hash2 = hex_sha1(cpassword);
        
        var SHA1Hash1 = hex_sha256(newpassword);
        var SHA1Hash2 = hex_sha256(cpassword);
        document.getElementById("newpassword").value = SHA1Hash1;
        document.getElementById("cpassword").value = SHA1Hash2;

        $('#forgotpassword').submit();
    }

</script>

<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->script('jquery.min');
echo $this->Html->css('validationEngine.jquery');
?>

<?php echo $this->Form->create('forgotpassword', array('id' => 'forgotpassword', 'autocomplete' => 'off')); ?>
<input type="hidden" name="name" value="CSRF-TOKEN" />

<div class="login-box" id="divuserdetails">
    <div class="login-logo">
        <h1>
           <?php echo __('lblforgotpassword'); ?>
            <small>&nbsp;</small>
        </h1>
    </div>
    <div class="login-box-body log_border">
        <p class="login-box-msg"><?php echo __('lblprovideuname'); ?></p>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('username', array('label' => false, 'placeholder' => 'Enter Username', 'id' => 'username', 'maxlength' => '100', 'class' => 'form-control')); ?>
        </div>

        <div class="form-group has-feedback">
            <!--                                    <div class="form-group">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon" style="width:50%;">
                                                                                    <i>E-Mail ID</i>
                                                                                </span> 
            <?php //echo $this->Form->input('email_id', array('label' => false, 'type' => 'text', 'id' => 'email_id', 'class' => ' form-control')); ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon" style="width:50%;">
                                                                                    <i>Mobile No.</i>
                                                                                </span> 
            <?php //echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'maxlength' => '12', 'class' => 'form-control')); ?>
                                                                            </div>
                                                                        </div>-->
        </div>

        <div class="row">
            <div class="col-xs-12">  
                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" name="btnSubmit" onclick = "javascript: return userdetailsubmit();">
                    <span class="glyphicon"></span> <?php echo __('lblbtnsend'); ?>
                </button>
            </div>
        </div>

    </div>
</div> 

<div class="login-box" id="divhintdetails" hidden="true">
    <div class="login-logo">
        <h1>
            <?php echo __('lblsecretque'); ?>
            <small>&nbsp;</small>
        </h1>
    </div>
    <div class="login-box-body log_border">
        <p class="login-box-msg"><?php echo __('lblprovideans'); ?></p></p>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_question', 'type' => 'text', 'style' => 'width:100%; ', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $hintquestion[0]['hint']['questions_en'])); ?>
        </div>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('hint_answer', array('label' => false, 'placeholder' => 'Enter Your Answer', 'id' => 'hint_answer', 'type' => 'text', 'class' => 'form-control validate[required],custom[onlyLetterSp]')); ?>
        </div>
        <div class="row">
            <div class="col-xs-12">  
                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" name="btnSubmit" onclick = "javascript: return hintdetailsubmit();">
                    <span class="glyphicon"></span> <?php echo __('lblbtnsend'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="login-box" id="divotpdetails" hidden="true">
    <div class="login-logo">
        <h1>
            <?php echo __('lblotp'); ?>
            <small>&nbsp;</small>
        </h1>
    </div>
    <div class="login-box-body log_border">
        <p class="login-box-msg"><?php echo __('lblprovideotp'); ?></p>
        <div class="form-group has-feedback" style="text-align: center;">
            <i >One Time Password (OTP) is sent via SMS to registered mobile no ********<?php echo $newmobileno ?> <br>Do not share OTP with anyone.</i>
        </div>
        <div class="form-group has-feedback">
            <?php echo $this->Form->input('txtotp', array('label' => false, 'id' => 'txtotp', 'type' => 'text', 'placeholder' => 'Enter OTP', 'class' => 'form-control validate[required]','autocomplete' => 'off')); ?>
        </div>

        <div class="row">
            <div class="col-xs-12"> 
                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" name="btnSubmit" onclick = "javascript: return otpdetailsubmit();">
                    <span class="glyphicon"></span> <?php echo __('lblbtnsend'); ?>
                </button>
            </div>
        </div>

    </div>
</div>

<div class="login-box" id="divchangepassword" hidden="true">
    <div class="login-logo">
        <h1>
            <?php echo __('lblchangepass'); ?>
            <small>&nbsp;</small>
        </h1>
    </div>
    <div class="login-box-body log_border">
        <p class="login-box-msg"><?php echo __('lblprovidedetails'); ?></p>
        <div class="form-group has-feedback">
            <?php echo $this->Form->input('newpassword', array('label' => false, 'type' => 'password', 'placeholder' => 'Enter Password', 'id' => 'newpassword', 'class' => ' form-control validate[required ,minSize[8],maxSize[100]')); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?php echo $this->Form->input('cpassword', array('label' => false, 'type' => 'password', 'placeholder' => 'Re-Enter Password', 'id' => 'cpassword', 'class' => 'form-control validate[required ,equals[newpassword]')); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">  
                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" name="btnSubmit" onclick = "javascript: return changepasswordsubmit();">
                    <span class="glyphicon"></span> <?php echo __('btnsubmit'); ?>
                </button>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <input type='hidden' value='0' name='actiontype' id='actiontype'/>
    <?php echo $this->Form->input('recorddata', array('label' => false, 'id' => 'recorddata', 'type' => 'hidden', 'value' => $recorddata)); ?>
</div>
<input type='hidden' value='<?php //echo $_SESSION["token"];        ?>' name='token' id='token'/>

<?php echo $this->Form->end(); ?>