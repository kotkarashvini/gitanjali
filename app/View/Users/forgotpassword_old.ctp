<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

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
        var SHA1Hash1 = hex_sha1(newpassword);
        var SHA1Hash2 = hex_sha1(cpassword);
        document.getElementById("newpassword").value = SHA1Hash1;
        document.getElementById("cpassword").value = SHA1Hash2;

        $('#forgotpassword').submit();
    }

</script>

<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
echo $this->Html->script('jquery-2.1.3');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
?>

<?php echo $this->Form->create('forgotpassword'); ?>
<input type="hidden" name="name" value="CSRF-TOKEN" />

<div class="well" id="divuserdetails">
    <div class="container" style="margin-top:10px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $this->Html->image('forgot_password.png'); ?>
                    </div>

                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width:50%;">
                                                <i>User name</i>
                                            </span> 
                                            <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'maxlength' => '100', 'class' => 'form-control')); ?>
                                        </div>
                                    </div>
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
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" onclick = "javascript: return userdetailsubmit();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<div class="well" id="divhintdetails" hidden="true">
    <div class="container" style="margin-top:10px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Secret Question</strong>
                    </div>

                    <div ><br><br>
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div >
                                        <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_question', 'type' => 'text', 'style' => 'width:100%; ','class' => 'form-control', 'readonly' => 'readonly', 'value' => $hintquestion[0]['hint']['questions'])); ?>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width:35%;">
                                                <i>Answer</i>
                                            </span> 
                                            <?php echo $this->Form->input('hint_answer', array('label' => false, 'placeholder' => 'Enter Your Answer', 'id' => 'hint_answer', 'type' => 'text', 'class' => 'form-control validate[required],custom[onlyLetterSp]')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" onclick = "javascript: return hintdetailsubmit();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="well" id="divotpdetails" hidden="true">
    <div class="container" style="margin-top:10px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>One Time Password(OTP)</strong>
                    </div>

                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    
                                    <div class="form-group">
                                        <div class="input-group" style="text-align: center;">
                                            
                                                <i>One Time Password (OTP) is sent via SMS to registered mobile no ********<?php echo $newmobileno ?> <br>Do not share OTP with anyone.</i>
                                            
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width:35%;">
                                                <i>Enter OTP</i>
                                            </span>
                                            <?php echo $this->Form->input('txtotp', array('label' => false, 'id' => 'txtotp', 'type' => 'text', 'class' => 'form-control validate[required],custom[onlyLetterSp]')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" onclick = "javascript: return otpdetailsubmit();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="well" id="divchangepassword" hidden="true">
    <div class="container" style="margin-top:10px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Change Password</strong>
                    </div>

                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                   
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width:55%;">
                                                <i>New Password</i>
                                            </span> 
                                            <?php echo $this->Form->input('newpassword', array('label' => false, 'type' => 'password', 'id' => 'newpassword', 'class' => ' form-control validate[required ,minSize[8],maxSize[100]')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width:55%;">
                                                <i>Confirm Password</i>
                                            </span>
                                            <?php echo $this->Form->input('cpassword', array('label' => false, 'type' => 'password', 'id' => 'cpassword', 'class' => 'form-control validate[required ,equals[newpassword]')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" onclick = "javascript: return changepasswordsubmit();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <input type='hidden' value='0' name='actiontype' id='actiontype'/>
    <!--<input type='hidden' value='0' name='actiontype1' id='actiontype1'/>-->
    <?php echo $this->Form->input('recorddata', array('label' => false, 'id' => 'recorddata', 'type' => 'hidden', 'value' => $recorddata)); ?>
</div>
<input type='hidden' value='<?php //echo $_SESSION["token"]; ?>' name='token' id='token'/>

<?php echo $this->Form->end(); ?>