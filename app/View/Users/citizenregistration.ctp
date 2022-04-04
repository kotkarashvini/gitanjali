<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('JS');
?>

<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {


        $('#deed_office').hide();
        $('#adv_bar').hide();
        var form = document.getElementById("citizenregistration");
        form.reset();

        $('#reload').click(function ()
        {
            var captcha = $("#captcha_image");
            captcha.attr('src', captcha.attr('src') + '?' + Math.random());
            return false;
        });

        $("#reg_type").change(function () {
            var reg_type = $("#reg_type option:selected").val();

            //alert(reg_type);return false;
            if (reg_type == 1) {
                $('#deed_office').hide();
                $('#adv_bar').hide();
            } else if (reg_type == 3) {
                $('#deed_office').show();
                $('#adv_bar').show();
            }
            else {
                $('#deed_office').show();
                $('#adv_bar').hide();
            }
        });




//        $('input[type=password]').disableAutocomplete();
//        $("#pan_no").attr("autocomplete", "off");
//        $("#uid").attr("autocomplete", "off");
//        $("#user_pass").attr("autocomplete", "off");
//        $("#re_user_pass").attr("autocomplete", "off");
//        $("#hint_answer").attr("autocomplete", "off");

        $("#contact_details").show();
        $("#uiddiv").show();
        $("#nri_details").hide();


        var host = '<?php echo $this->webroot; ?>';
        $("#showHide").click(function () {
            if ($('#hint_answer').attr("type") == "password") {
                $('#hint_answer').attr("type", "text");
            }
            else {
                $('#hint_answer').attr("type", "password");
            }

        });


        $('#state_id').change(function () {
            var state = $("#state_id option:selected").val();


            var i;
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        })


        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {

            var taluka = $("#taluka_id option:selected").val();
            var token = $("#token").val();
            //alert(taluka);return false;
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regoffice", {taluka_id: taluka, token: token}, function (data)
            {
                var sc1 = '<option value="">--select--</option>';
                $.each(data.office, function (index1, val1) {

                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });

                $("#office_id option").remove();
                $("#office_id").append(sc1);
            }, 'json');
        })

        $('#re_user_pass').blur(function () {
            verifypassword();
        });

        $("#pan_no").blur(function () {
            var type = $("#id_type option:selected").val();
            var desc = $("#pan_no").val();
            if (type != 'empty')
            {

                $.post(host + 'Users/get_validation_rule', {type: type}, function (data)
                {
                    var pattern = $.trim(data.pattern);
                    var message = data.message;
                    var error_code = data.error_code;
                    switch (error_code) {
<?php foreach ($allrule as $rule) { ?>
                            case '<?php echo $rule[0]['error_code'] ?>' :
                                var regex = <?php echo $rule[0]['pattern_rule_client']; ?>;
                                var message = '<?php echo $rule[0]['error_messages_' . $laug]; ?>';
                                break;
<?php } ?>
                    }
                    if (!desc.match(regex))
                    {
                        $("#pan_no").val('');
                        // $("#identificationtype_desc_en").focus();
                        $("#pan_no_error").text(message);
                        return false;
                    } else
                    {
                        $("#pan_no_error").text('');
                        return true;
                    }
                }, 'json');
            }

        });


        $("#citizen_typeI").click(function () {

            $("#contact_details").show();
            $("#nri_details").hide();
            $("#uiddiv").show();
            $("#address").val('');
            $("#lbldeed_writer").show();
            $("#deed_writer").show();

            var type = 'I';
            $.getJSON("<?php echo $this->webroot; ?>Users/getIdentificationlist", {type: type}, function (data)
            {
                var sc = '<option value="empty">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#id_type option").remove();
                $("#id_type").append(sc);
            });


        });

        $("#citizen_typeN").click(function () {
            $("#contact_details").hide();
            $("#nri_details").show();
            $("#uiddiv").hide();
            $("#building").val('');
            $("#street").val('');
            $("#city").val('');
            $("#pincode").val('');
            $("#state_id").val('');
            $("#district_id").val('');
            $("#taluka_id").val('');
            $("#lbldeed_writer").hide();
            $("#deed_writer").hide();

            var type = 'N';
            $.getJSON("<?php echo $this->webroot; ?>Users/getIdentificationlist", {type: type}, function (data)
            {
                var sc = '<option value="empty">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#id_type option").remove();
                $("#id_type").append(sc);
            });
        });




    });

    function verifypassword() {
        if ($('#re_user_pass').val() != '') {
            if ($('#re_user_pass').val() != $('#user_pass').val()) {
                alert('Password does not match');
                $("#re_user_pass").val('');
                $('#re_user_pass').focus();
                return false;
            }
        }
    }
    function checkusername() {

        var username = $('#user_name').val();
        if (username != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkusercitizen",
                data: {'username': username},
                success: function (data) {
                    if (data == 1)
                    {
                        $("#user_name").val('');


                        $('#user_name').focus();
                        alert('user name already exist');
                        return false;
                    }
                }
            });
        }
    }

    function checkemail() {

        var email = $('#email_id').val();
        // alert(email);
        if (email != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkemailcitizen",
                data: {'email': email},
                success: function (data) {
                    if (data == 1)
                    {
                        $("#email_id").val('');


                        $('#email_id').focus();
                        alert('Email ID already exist');
                        return false;
                    }
                }
            });
        }
    }

    function checkmobileno() {
        var mobile = $('#mobile_no').val();
        //alert(mobile);return false;
        if (mobile != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkmobilenocitizen",
                data: {'mobile': mobile},
                success: function (data) {

                    if (data == 1)
                    {
                        $("#mobile_no").val('');
                        alert('Mobile no. already exist');
                        $('#mobile_no').focus();
                        return false;
                    }
                }
            });
        }
    }

    function checkuid() {
        var uid = $('#uid').val();
        if (uid != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkuidcitizen",
                data: {'uid': uid},
                success: function (data) {

                    if (data == 1)
                    {
                        $("#uid").val('');
                        alert('UID already exist');
                        $('#uid').focus();
                        return false;
                    }
                }
            });
        }
    }

    function checkuidproof() {
        var pan_no = $('#pan_no').val();
        if (pan_no != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkidproofcitizen",
                data: {'pan_no': pan_no},
                success: function (data) {

                    if (data == 1)
                    {
                        $("#pan_no").val('');
                        alert('ID proof number already exist');
                        $('#pan_no').focus();
                        return false;
                    }
                }
            });
        }
    }

    function after_validation_check() {

//     verifypassword();
//            checkemail();
//            checkmobileno();         
//            checkusername();

        var pass = $("#user_pass").val();
        var r_password = $("#re_user_pass").val();
        var user = $("#user_name").val();

        var uid = $("#uid").val();
        var id_number = $("#pan_no").val();

        var SALT = "<?php echo $this->Session->read("salt"); ?>";
        //alert(SALT);
//        $("#user_pass").val(encrypt(pass, SALT));
$("#user_name").val(encrypt(user, SALT));
//        $("#re_user_pass").val(encrypt(r_password, SALT));
//        
//       
        var SHA1Hashuid = hex_sha256(uid);
//        alert(SHA1Hashuid);
        var SHA1Hashuidresult = hex_sha256(SALT + SHA1Hashuid);
        document.getElementById("uid").value = SHA1Hashuid;
//        
        var SHA1Hashpass = hex_sha256(pass);
//        alert(SHA1Hashpass);
        var SHA1Hashpassresult = hex_sha256(SALT + SHA1Hashpass);
        document.getElementById("user_pass").value = SHA1Hashpass;

        var SHA1Hashre_pass = hex_sha256(r_password);
//        alert(SHA1Hashre_pass);
        var SHA1Hashre_passresult = hex_sha256(SALT + SHA1Hashre_pass);
        document.getElementById("re_user_pass").value = SHA1Hashre_pass;

        var SHA1Hashid_number = hex_sha256(id_number);
//        alert(SHA1Hashid_number);
        var SHA1Hashidtyperesult = hex_sha256(SALT + SHA1Hashid_number);
        document.getElementById("pan_no").value = SHA1Hashid_number;

//          return false;
    }
</script>


<?php echo $this->Session->flash('auth'); ?> 
<?php echo $this->Form->create('citizenregistration', array('id' => 'citizenregistration', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblcitizenreg'); ?></h3></center>

                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2"><?php echo __('Citizen Type'); ?></div>
                            <div class="col-sm-2"> <?php echo $this->Form->input('citizen_type', array('type' => 'radio', 'options' => array('I' => '&nbsp;' . __('Indian') . '&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;' . __('NRI') . '&nbsp;&nbsp;&nbsp;'), 'value' => 'I', 'legend' => false, 'div' => false, 'id' => 'citizen_type')); ?></div>                            

                            <label for="contact_person" class="col-sm-2 control-label" id='lbldeed_writer'><?php echo __('Select Type'); ?></label>  
                            <div class="col-sm-2">  <?php echo $this->Form->input('reg_type', array('label' => false, 'id' => 'reg_type', 'class' => 'form-control input-sm', 'options' => array($reg_type))); ?></div>                            



                        </div>
                    </div>
                </div>


                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="contact_person" class="col-sm-3 control-label"><?php echo __('lblcontactperson'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('contact_fname', array('label' => false, 'placeholder' => 'First Name', 'id' => 'contact_fname', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <span id="contact_fname_error" class="form-error"><?php echo $errarr['contact_fname_error']; ?></span>
                                </div>

                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('contact_mname', array('label' => false, 'placeholder' => 'Middle Name', 'id' => 'contact_mname', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <span id="contact_mname_error" class="form-error"><?php echo $errarr['contact_mname_error']; ?></span>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('contact_lname', array('label' => false, 'placeholder' => 'Last Name', 'id' => 'contact_lname', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <span id="contact_lname_error" class="form-error"><?php echo $errarr['contact_lname_error']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary" >
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblcontactperadd'); ?></h3>
                    </div>
                    <div class="box-body" id="contact_details">
                        <div class="row">
                            <div class="form-group">
                                <label for="building" class="col-sm-3 control-label"><?php echo __('lblbuildingnamenofloor'); ?></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('building', array('label' => false, 'id' => 'building', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                                    <span id="building_error" class="form-error"><?php echo $errarr['building_error']; ?></span>
                                </div>
                                <label for="street" class="col-sm-3 control-label"><?php echo __('lblstreetlocality'); ?></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('street', array('label' => false, 'id' => 'street', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <span id="street_error" class="form-error"><?php echo $errarr['street_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="city" class="col-sm-3 control-label"><?php echo __('lblcity'); ?></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100')); ?>
                                    <span id="city_error" class="form-error"><?php echo $errarr['city_error']; ?></span>
                                </div>
                                <label for="pin_code" class="col-sm-3 control-label"><?php echo __('lblpincode'); ?></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'type' => 'text', 'class' => 'form-control', 'maxlength' => '6')); ?>
                                    <span id="pincode_error" class="form-error"><?php echo $errarr['pincode_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="state" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmstate'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'empty' => '----select----', 'options' => array($State))); ?>
                                    <span id="state_id_error" class="form-error"><?php echo $errarr['state_id_error']; ?></span>
                                </div>
        <!--                        <label for="Division" class="col-sm-3 control-label"><?php echo __('lbladmdivision'); ?></label>    
                                <div class="col-sm-3">
                                <?php //echo $this->Form->input('division_id', array('label' => false, 'id' => 'division_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Division--')));  ?>
                                    <span id="division_id_error" class="form-error"><?php //echo $errarr['division_id_error'];                                     ?></span>
                                </div>-->
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="district" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array())); ?>
                                    <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                                </div>
                                <label for="taluka" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array())); ?>
                                    <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row" id="deed_office">
                            <div class="form-group">
                                <label for="office" class="col-sm-3 control-label" id="lbl_deed_writer_off"><?php echo __('Select Office'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3" >
                                    <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'empty' => '--Select Office--', 'options' => array())); ?>
                                </div>
                                <label for="office" class="col-sm-3 control-label" id="lbl_licence_no"><?php echo __('Licence Number'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3" >
                                    <?php echo $this->Form->input('licence_no', array('label' => false, 'id' => 'licence_no', 'class' => 'form-control input-sm')); ?>
                                    <span id="licence_no_error" class="form-error"><?php //echo $errarr['licence_no_error'];        ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row" id="adv_bar">
                            <div class="form-group">
                                <label for="office" class="col-sm-3 control-label" id="lbl_name_of_bar"><?php echo __('Name of Bar'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3" >
                                    <?php echo $this->Form->input('name_of_bar', array('label' => false, 'id' => 'name_of_bar', 'class' => 'form-control input-sm')); ?>
                                    <span id="name_of_bar_error" class="form-error"><?php //echo $errarr['name_of_bar_error'];        ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" id="nri_details">
                        <div class="row">
                            <div class="form-group">
                                <label for="building" class="col-sm-3 control-label"><?php echo __('Address'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'type' => 'text', 'class' => 'form-control')); ?>

                                    <span id="address_error" class="form-error"><?php //echo $errarr['address_error'];                      ?></span>
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
                                <label for="e_mail" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'text', 'class' => 'form-control', 'onblur' => 'checkemail()', 'maxlength' => '25')); ?>
                                    <span id="email_id_error" class="form-error"><?php echo $errarr['email_id_error']; ?></span>
                                </div>
                                <label for="mobile_no" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'tel', 'class' => 'form-control', 'onblur' => 'checkmobileno()', 'maxlength' => '10')); ?>
                                    <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="id_type" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lblidproof'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('id_type', array('label' => false, 'id' => 'id_type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select ID Proof--', $idtype))); ?>
                                    <span id="id_type_error" class="form-error"><?php echo $errarr['id_type_error']; ?></span>
                                </div>
                                <label for="pan_no" class="col-sm-3 control-label" id="pan_lable"><?php echo __('lblidproofno'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3" id="pantxt">
                                    <?php echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'type' => 'password', 'class' => 'form-control ', 'onblur' => 'checkuidproof()', 'readonly', 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                    <!--<span id="pan_no_error" class="form-error"><?php //echo $errarr['pan_no_error'];                       ?></span>-->
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row" id="uiddiv">
                            <div class="form-group">


                                <label for="pan_no" class="col-sm-3 control-label" id="pan_lable"><?php echo __('lbluid'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-3" id="pantxt">
                                    <?php echo $this->Form->input('uid', array('label' => false, 'id' => 'uid', 'type' => 'password', 'class' => 'form-control ', 'onblur' => 'checkuid()', 'maxlength' => '12', 'readonly', 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                    <!--<span id="uid_error" class="form-error"><?php //echo $errarr['uid_error'];  ?></span>-->
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
                                    <?php echo $this->Form->input('user_name', array('label' => false, 'id' => 'user_name', 'type' => 'text', 'class' => 'form-control', 'onblur' => 'checkusername()', 'maxlength' => '50')); ?>
                                    <span id="user_name_error" class="form-error"><?php echo $errarr['user_name_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label"><?php echo __('lblpassword'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('user_pass', array('label' => false, 'id' => 'user_pass', 'type' => 'password', 'class' => 'form-control', 'maxlength' => '50', 'readonly', 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                    <!--<span id="user_pass_error" class="form-error"><?php //echo $errarr['user_pass_error'];  ?></span>-->
                                </div>
                                <label for="re_user_pass" class="col-sm-3 control-label"><?php echo __('lblrepassword'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('re_user_pass', array('label' => false, 'id' => 're_user_pass', 'type' => 'password', 'class' => 'form-control', 'maxlength' => '50', 'readonly', 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                    <!--<span id="re_user_pass_error" class="form-error"><?php //echo $errarr['re_user_pass_error'];  ?></span>-->
                                </div>

                            </div>
                            <?php // echo $this->Form->input('hfSaltedStr', array('type' => 'hidden', 'id' => 'hfSaltedStr', 'value' => $saltstring)); ?>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="captcha" class="col-sm-3 control-label"><?php echo __('lblcaptcha'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('captcha', array('label' => false, 'id' => 'captcha', 'class' => 'form-control', 'onblur' => 'checkcaptcha()', 'maxlength' => '6')); ?>
                                    <span id="captcha_error" class="form-error"><?php echo $errarr['captcha_error']; ?></span>
                                </div>
                                <div class="col-sm-3">   
                                    <div class="input-group">
                                        <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'class' => 'img-rounded img-thumbnail')); ?>
                                        <span class="input-group-addon cursor_pointer" id="reload"><i class="fa fa-refresh  text-success"></i></span>
                                    </div>

                                </div>

                                <div class="col-sm-3">

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
                                    <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_question', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Hint Question--', $hintquestion))); ?>
                                    <span id="hint_question_error" class="form-error"><?php echo $errarr['hint_question_error']; ?></span>
                                </div>
                                <label for="qst_ans" class="col-sm-3 control-label"><?php echo __('lblhintans'); ?></label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('hint_answer', array('label' => false, 'id' => 'hint_answer', 'type' => 'password', 'class' => 'form-control', 'maxlength' => '255', 'readonly', 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                    <span id="hint_answer_error" class="form-error"><?php echo $errarr['hint_answer_error']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="text-align: center">
                    <button type="submit" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                        <span class="glyphicon glyphicon-ok"></span> <?php echo __('btnsubmit'); ?>
                    </button>
                    <button type="button" class="btn btn-info" id="btnSubmit" name="btnSubmit" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'welcomenote')); ?>';">
                        <span class="glyphicon glyphicon-remove"></span> <?php echo __('btncancel'); ?>
                    </button>
                </div>
            </div>
            <input type="hidden" name="appl_name" id="appl_name" val=""/>
            <input type="hidden" name="dist_name" id="dist_name" val="" />
            <input type="hidden" name="state_name" id="state_name"  val=""/>
            <input type="hidden" name="country_name" id="country_name"  val=""/>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
