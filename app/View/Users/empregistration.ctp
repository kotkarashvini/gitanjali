<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
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
        $("#empregistration").validationEngine({
            onFormSuccess: formSuccess,
            onFormFailure: formFailure
        });

        $('#contact_fname').val('');
        $('#contact_mname').val('');
        $('#contact_lname').val('');
        $('#org_name').val('');
        $('#email_id').val('');
        $('#mobile_no').val('');
        $('#pan_no').val('');
        $('#user_name').val('');
        $('#pwd').val('');
        $('#cpwd').val('');
        $('#captcha').val('');
        $('#hint_answer').val('');
        $('#building').val('');
        $('#street').val('');
        $('#pincode').val('');
        $('#city').val('');
        $('#org_name1').hide();
        $('#pan_lable').hide();
        $('#panshow').hide();
        $('#star').hide();
        $('#pan_no').hide();

        $('#emp_type').change(function () {
            var emp_type = $('#emp_type').val();
            if (emp_type == 1)
            {
                $('#org_name1').show();
            } else
            {
                $('#org_name1').hide();
            }
        });

        $('#id_type').change(function () {
            if ($('#id_type').val() == 'empty')
            {
                $('#pan_lable').hide();
                $('#panshow').hide();
                $('#star').hide();
                $('#pan_no').hide();
            }
            else
            {
                $('#pan_lable').show();
                $('#panshow').show();
                $('#star').show();
                $('#pan_no').show();
            }
        });

        if ($('#id_type').val() == 'empty')
        {
            $('#pan_lable').hide();
            $('#panshow').hide();
            $('#pan_no').hide();
        }

//        $('#state_id').change(function () {
//            var state = $("#state_id option:selected").val();
//            var token = $("#token").val();
//            var i;
//            $.getJSON("<?php echo $this->webroot; ?>regdivision", {state: state, token: token}, function (data)
//            {
//                var sc = '<option value="empty">--Select Division--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#division_id option").remove();
//                $("#division_id").append(sc);
//            });
//        })
//
//        $('#division_id').change(function () {
//            var division = $("#division_id option:selected").val();
//            //alert(division);return false;
//            var token = $("#token").val();
//            var i;
//            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {division: division, token: token}, function (data)
//            {
//                alert(data);return false;
//                var sc = '<option value="empty">--Select District--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#district_id option").remove();
//                $("#district_id").append(sc);
//            });
//        })
//
//        $('#district_id').change(function () {
//            var district = $("#district_id option:selected").val();
//            var token = $("#token").val();
//            var i;
//            $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data)
//            {
//                var sc = '<option value="empty">--Select Taluka--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#taluka_id option").remove();
//                $("#taluka_id").append(sc);
//            });
//        })

        $('#cmdSubmit').click(function () {

            var contact_fname = $('#contact_fname').val();
            var contact_lname = $('#contact_lname').val();
            var emp_type = $('#emp_type').val();
            var org_name = $('#org_name').val();
            var building = $('#building').val();
            var street = $('#street').val();
            var city = $('#city').val();
            var pincode = $('#pincode').val();
            var state_id = $('#state_id').val();
            var division_id = $('#division_id').val();
            var district_id = $('#district_id').val();
            var taluka_id = $('#taluka_id').val();
            var email_id = $('#email_id').val();
            var mobile_no = $('#mobile_no').val();
            var id_type = $('#id_type').val();
            var pan = $('#pan_no').val();
            var user_name = $('#user_name').val();
            var pwd = $('#pwd').val();
            var cpwd = $('#cpwd').val();
            var cp = $('#captcha').val();
            var que = $('#hint_que').val();
            var ans = $('#hint_answer').val();

            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var numbers = /^[0-9]+$/;
            var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
            var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
            var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
            var alphbets = /^[a-z A-Z ]+$/;
            var alphbetscity = /^[ A-Za-z-() ]*$/;
            var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

            if (contact_fname == '') {
                alert('Enter First Name');
                $('#contact_fname').focus();
                return false;
            }
            if (!contact_fname.match(alphbets) || contact_fname.length > 100) {
                $('#contact_fname').focus();
                alert('Only alphabets with max length 100 are allowed in first name');
                return false;
            }
//            if (contact_mname != '') {
//                if (!contact_mname.match(alphbets) || contact_mname.length > 100) {
//                    alert('Only alphabets with max length 100 are allowed in middle name');
//                    $('#contact_mname').focus();
//                    return false;
//                }
//            }
//            if (!contact_mname.match(alphbets) || contact_mname.length > 100) {
//                $('#contact_mname').focus();
//                alert('Only alphabets with max length 100 are allowed in middle name');
//                return false;
//            }

            if (contact_lname == '') {
                alert('Enter Last Name');
                $('#contact_lname').focus();
                return false;
            }
            if (!contact_lname.match(alphbets) || contact_lname.length > 100) {
                $('#contact_lname').focus();
                alert('Only alphabets with max length 100 are allowed in last name');
                return false;
            }

            if (emp_type == 'empty') {
                alert('Please select employee type.');
                $('#emp_type').focus();
                return false;
            }

            if (emp_type != 'empty') {
                $('#emp_type_name').val($("#emp_type option:selected").text());
                if ($('#emp_type').val() == '1')
                {
                    if (org_name == '')
                    {
                        alert('Please enter organization name.');
                        $('#org_name').focus();
                        return false;
                    }
                    if (!org_name.match(Alphanum))
                    {
                        alert('Only alphabets and numbers are allowed in Organization name.');
                        $('#org_name').focus();
                        return false;
                    }
                }
            }

            if (building != '') {
                if (!building.match(alphanumsapcedot) || building.length > 100)
                {
                    $('#building').focus();
                    alert('Special characters(Space, Comma, Dot, Underscore & Dash) allowed with alphabets and numbers with max length 100 in Building name');
                    return false;
                }
            }

            if (street != '') {
                if (!street.match(alphanumsapcedot) || street.length > 100)
                {
                    $('#street').focus();
                    alert('Special characters(Space, Comma, Dot, Underscore & Dash) allowed with alphabets and numbers with max length 100 in street name');
                    return false;
                }
            }

            if (city == '') {
                alert('Enter City Name');
                $('#city').focus();
                return false;
            }

            if (!city.match(alphbetscity) || city.length > 100) {
                $('#city').focus();
                alert('Only alphabets,space,(),dash with max length 100 are allowed in city name');
                return false;
            }

            if (pincode != '') {
                if (!pincode.match(numbers) || pincode.length > 6 || pincode.length < 6)
                {
                    $('#pincode').focus();
                    alert('Only 0 to 9 numbers are allowed with min and max length 6 digits in Pincode.');
                    return false;
                }
            }


            if (state_id == 'empty') {
                alert('Please select State.');
                $('#state_id').focus();
                return false;

            }
            else if (division_id == 'empty') {
                alert('Please select Division.');
                $('#division_id').focus();
                return false;
            }
            else if (district_id == 'empty') {
                alert('Please select District.');
                $('#district_id').focus();
                return false;
            }
            else if (taluka_id == 'empty') {
                alert('Please select Taluka.');
                $('#taluka_id').focus();
                return false;
            }

            if (email_id == '' || !email_id.match(mailformat)) {
                alert('Enter Valid Email ID');
                $('#email_id').focus();
                return false;
            }
            if (mobile_no == '' || mobile_no.length < 10) {
                alert('Enter Mobile number');
                $('#mobile_no').focus();
                return false;
            }
            if (!mobile_no.match(numbers) || mobile_no.length > 12) {
                $('#mobile_no').focus();
                alert('Enter valid Mobile number');
                return false;
            }

            if (id_type != 'empty') {
                $('#sttype_name').val($("#id_type option:selected").text());
                if ($('#id_type').val() == '1')
                {
                    if (pan == '' || !pan.match(Alphanum) || pan.length != 10)
                    {
                        alert('Please enter valid PAN ID with length 10.');
                        $('#pan_no').focus();
                        return false;
                    }
                }

                if ($('#id_type').val() == '2')
                {
                    if (pan == '' || !pan.match(numbers) || pan.length != 10)
                    {
                        alert('Please enter valid CRN number with length 10 digits.');
                        $('#pan_no').focus();
                        return false;
                    }

                }

                if ($('#id_type').val() == '3')
                {
                    if (pan == '' || !pan.match(numbers) || pan.length != 12)
                    {
                        alert('Please enter valid UID number with length 12 digits.');
                        $('#pan_no').focus();
                        return false;
                    }
                }
            }

            if (user_name == '')
            {
                alert('Enter USer Name.');
                $('#user_name').focus();
                return false;
            }
            if (!user_name.match(Alphanumdot) || user_name.length > 30)
            {
                $('#user_name').focus();
                alert('Atleast one Upper or Lower case  alphabets , dot(.), numbers with max length 30 are allowed in User Name.');
                return false;
            }

            if (pwd == '')
            {
                alert("Enter Password")
                $('#pwd').focus();
                return false;
            }
            if (!pwd.match(password) || pwd.length < 8)
            {
                alert('* Only Hash(#),At Sign(@) & Star(*)  with at least one capital,one small alphabet & one number with min length 8 allowed in Password field');
                $('#pwd').focus();
                return false;
            }
            if (cpwd == '' || !cpwd.match(password))
            {
                alert('* Password does not match');
                $('#cpwd').focus();
                return false;
            }
            if (pwd != cpwd)
            {
                alert('* Password does not match');
                $('#cpwd').focus();
                return false;
            }
            var SHA1Hash1 = hex_sha1(pwd);
            var SHA1Hash2 = hex_sha1(cpwd);
            document.getElementById("pwd").value = SHA1Hash1;
            document.getElementById("cpwd").value = SHA1Hash1;

            if (cp == '')
            {
                alert('Enter Text shown in the image ');
                $('#captcha').focus();
                return false;
            }

            if (que == 'empty')
            {
                alert('Please select hint question. ');
                return false;
            }

            if (ans == '')
            {
                alert('Enter Answer ');
                $('#hint_answer').focus();
                return false;
            } else
            {
                $('#empregistration').submit();
            }
        });

        $("#cmdAvailable").click(function () {
            var user_name = $('#user_name').val();
            if (user_name == '')
            {
                alert('Please enter User Name.');
            }
            else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>checkuser",
                    data: {'c': user_name},
                    success: function (data) {
                        if (data == 1)
                        {
                            alert('User name already exist.');
                            return false;
                        }
                        else {
                            alert('User name is available for usage.');
                        }
                    }
                });
            }
        });
    });

    function checkcaptcha() {
        var cp = $('#captcha').val();
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
            }
        });
    }

    function checkemail() {

        var email = $('#email_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>checkemail",
            data: {'email': email},
            success: function (data) {
                if (data == 1)
                {
                    alert('Email ID already exist');
                    $('#email_id').focus();
                    return false;
                }
            }
        });
    }

    function checkmobileno() {
        var mobile = $('#mobile_no').val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>checkmobileno",
            data: {'mobile': mobile},
            success: function (data) {
                if (data == 1)
                {
                    alert('Mobile no. already exist');
                    $('#mobile_no').focus();
                    return false;
                }
            }
        });
    }
</script>

<?php
echo $this->Html->script('jquery_validationui');
echo $this->Html->script('languages/jquery.validationEngine-en');
echo $this->Html->script('jquery.validationEngine');
echo $this->Html->css('validationEngine.jquery');
?>
<?php echo $this->Session->flash('auth'); ?> 
<?php echo $this->Form->create('empregistration', array('id' => 'empregistration', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="contact_person" class="col-sm-3"><?php echo __('lblcontactperson'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('contact_fname', array('label' => false, 'placeholder' => 'First Name', 'id' => 'contact_fname', 'type' => 'text', 'maxlength' => '100', 'class' => 'form-control validate[required ,maxSize[100],custom[onlyLetterSp]]')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('contact_mname', array('label' => false, 'placeholder' => 'Middle Name', 'id' => 'contact_mname', 'type' => 'text', 'maxlength' => '100', 'class' => 'form-control validate[maxSize[100],custom[onlyLetterSp]]')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('contact_lname', array('label' => false, 'placeholder' => 'Last Name', 'id' => 'contact_lname', 'type' => 'text', 'maxlength' => '100', 'class' => 'form-control validate[required ,maxSize[100],custom[onlyLetterSp]]')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group"> 
                        <label for="Select_Employee_Type" class="col-sm-3 control-label"><?php echo __('lblusertype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('emp_type', array('label' => false, 'id' => 'emp_type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $emptype))); ?>
                            <input type="hidden" name="emp_type_name" id="emp_type_name" val="" />
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="org_name" class="col-sm-3 control-label"><?php echo __('lblorganizationname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-9">
                            <?php echo $this->Form->input('org_name', array('label' => false, 'id' => 'org_name', 'maxlength' => '100', 'type' => 'text', 'class' => 'form-control validate[maxSize[100],custom[onlyLetterNumberSpaceDashComma]]')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblcontactperadd'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="building" class="col-sm-3 control-label"><?php echo __('lblbuildingnamenofloor'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('building', array('label' => false, 'id' => 'building', 'maxlength' => '100', 'type' => 'text', 'class' => 'form-control validate[maxSize[100],custom[onlyLetterNumberSpaceDashComma]]')); ?>
                        </div>
                        <label for="street" class="col-sm-3 control-label"><?php echo __('lblstreetlocality'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('street', array('label' => false, 'id' => 'street', 'maxlength' => '100', 'type' => 'text', 'class' => 'form-control validate[maxSize[100],custom[onlyLetterNumberSpaceDashComma]]')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="city" class="col-sm-3 control-label"><?php echo __('lblcity'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'maxlength' => '100', 'onkeypress' => 'return onlyCity(event,this);', 'type' => 'text', 'class' => 'form-control validate[required ,maxSize[100]]')); ?>
                        </div>
                        <label for="pin_code" class="col-sm-3 control-label"><?php echo __('lblpincode'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'onkeypress' => 'return onlyNumbers(event,this);', 'maxlength' => '6', 'type' => 'text', 'onkeypress' => 'return onlyNumbers(event,this);', 'class' => 'form-control validate[minSize[6],maxSize[6],custom[integer]]')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="state" class="col-sm-3 control-label"><?php echo __('lbladmstate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select State--', $State))); ?>
                        </div>
                        <label for="Division" class="col-sm-3 control-label"><?php echo __('lbladmdivision'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('division_id', array('label' => false, 'id' => 'division_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Division--', $division))); ?>
                        </div>
                    </div>

                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="district" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--', $District))); ?>
                        </div>
                        <label for="taluka" class="col-sm-3 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Taluka--', $taluka))); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblcontactperdetails'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="e_mail" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'email', 'class' => 'form-control validate[required,custom[email]]', 'onblur' => 'checkemail()')); ?>
                        </div>
                        <label for="mobile_no" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'onkeypress' => 'return onlyNumbers(event,this);', 'maxlength' => '12', 'type' => 'tel', 'class' => 'form-control validate[required,minSize[10],maxSize[12],custom[mobile]]', 'onblur' => 'checkmobileno()')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="id_type" class="col-sm-3 control-label"><?php echo __('lblidproof'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('id_type', array('label' => false, 'id' => 'id_type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select ID Proof--', $idtype))); ?>
                        </div>
                        <label for="pan_no" class="col-sm-3 control-label" id="pan_lable"><?php echo __('lblidproofno'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3" id="pantxt">
                            <?php echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'type' => 'text', 'maxlength' => '12', 'class' => 'form-control validate[maxSize[12]]')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblseluseridpass'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="user_name" class="col-sm-3 control-label"><?php echo __('lblusername'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('user_name', array('label' => false, 'id' => 'user_name', 'type' => 'text', 'maxlength' => '100', 'class' => 'form-control validate[required ,minSize[5],maxSize[100],custom[onlyLetterNumberdotnotspace]]')); ?>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-info" id="cmdAvailable" name="cmdAvailable">
                                <span class="glyphicon glyphicon-check"></span><?php echo __('lblchkavailability'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><?php echo __('lblpassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('user_pass', array('label' => false, 'id' => 'pwd', 'type' => 'password', 'maxlength' => '100', 'class' => 'form-control validate[required ,minSize[8],maxSize[100]]')); ?>
                        </div>
                        <label for="re_user_pass" class="col-sm-3 control-label"><?php echo __('lblrepassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('re_user_pass', array('label' => false, 'id' => 'cpwd', 'type' => 'password', 'maxlength' => '100', 'class' => 'form-control validate[required]')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <p class="help-block" style="color: red;">* Only Hash(#),At Sign(@) & Star(*)  with at least one capital,one small alphabet & one number allowed in Password field.</p>    
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="captcha" class="col-sm-3 control-label"><?php echo __('lblcaptcha'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('captcha', array('label' => false, 'maxlength' => '6', 'id' => 'captcha', 'class' => 'form-control', 'onblur' => 'checkcaptcha()')); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'style' => 'width:100%', 'style' => 'hight:50px !important')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblpasswordloss'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="question" class="col-sm-3 control-label"><?php echo __('lblhintqst'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_que', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Hint Question--', $hintquestion))); ?>
                        </div>
                        <label for="qst_ans" class="col-sm-3 control-label"><?php echo __('lblhintans'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('hint_answer', array('label' => false, 'id' => 'hint_answer', 'maxlength' => '50', 'type' => 'text', 'class' => 'form-control validate[required,maxSize[50]],custom[onlyLetterSp]')); ?>
                        </div>
                        <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="text-align: center">
            <button type="button" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                <span class="glyphicon glyphicon-ok"></span><?php echo __('btnsubmit'); ?>
            </button>
            <button type="button" class="btn btn-info" id="btnSubmit" name="btnSubmit" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'welcomenote')); ?>';">
                <span class="glyphicon glyphicon-remove"></span><?php echo __('btncancel'); ?>
            </button>
        </div>
        <input type="hidden" name="appl_name" id="appl_name" val=""/>
        <input type="hidden" name="dist_name" id="dist_name" val="" />
        <input type="hidden" name="state_name" id="state_name"  val=""/>
        <input type="hidden" name="country_name" id="country_name"  val=""/>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<script language="JavaScript" type="text/javascript">
    var message = "Right Click Not Allowed";
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