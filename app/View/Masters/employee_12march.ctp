
<script>

    $(document).ready(function () {

        $('#tabledivisionnew').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#authetication_type').change(function () {
//               alert('authetication_type');
            var authetication_type = $("#authetication_type option:selected").val();
            if (authetication_type == '2' || authetication_type == '3') {
                $("#divbiocap").show();
            } else {
                $("#divbiocap").hide();
            }
        });


        $('usercreate_flag').change(function () {
            if ($(this).val() == 'Y') {
                $("#createuser").show();
            }
            else {
                $("#createuser").hide();
            }
        });

        $('input[type=radio][name=usercreate_flag]').change(function () {
            if (this.value == 'Y') {
                $('#createuser').show();

            } else {
                $('#createuser').hide();
            }
        });


        $('#state_id').change(function () {
            var state = $("#state_id option:selected").val();


            var i;
            $.getJSON("<?php echo $this->webroot; ?>Masters/get_district_name", {state: state}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#dist_id option").remove();
                $("#dist_id").append(sc);
            });
        })


        $('#dist_id').change(function () {
            var district = $("#dist_id option:selected").val();
            var token = $("#token").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Masters/get_taluka_name1", {district: district, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

//    $('#taluka_id').change(function () {
//
//        var taluka = $("#taluka_id option:selected").val();
//        var token = $("#token").val();
//        //alert(taluka);return false;
//        var i;
//        $.getJSON("<?php echo $this->webroot; ?>Masters/regoffice", {taluka_id: taluka, token: token}, function (data)
//        {
//            var sc1 = '<option value="">--select--</option>';
//            $.each(data.office, function (index1, val1) {
//
//                sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
//            });
//
////            $("#office_id option").remove();
////            $("#office_id").append(sc1);
//        }, 'json');
//    })



    });

    function checkusername() {

        var username = $('#username').val();
        if (username != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkusersro",
                data: {'username': username},
                success: function (data) {
                    if (data == 'r1')
                    {
                        $("#username").val('');


                        $('#username').focus();
                        alert('user name already exist');
                        return false;
                    }
                }
            });
        }
    }

//    function EncryptSHA1() {
//        var Pass = $("#password ").val();
//        var Pass1 = $("#r_password ").val();
//        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
//        if (!Pass.match(password) && !Pass1.match(password))
//        {
//            $("#password ").val('');
//            $("#r_password ").val('');
//            //  alert("Invalid password");
//            //return false;
//        }
//
//        if (Pass.length > 0 && Pass1.length > 0)
//        {
//            var SHA1Hash = hex_sha1(Pass);
//            var SHA1Hash1 = hex_sha1(Pass1);
////            alert(Pass);
////             alert(Pass1);
//            document.getElementById("password").value = SHA1Hash;
//            document.getElementById("r_password").value = SHA1Hash1;
//        }
//    }
//    -------------------------------------------------------



    function formupdate(emp_code, salutation, emp_fname, emp_mname, emp_lname, office_id, qualification_id, building_no, flat_no,
            road_name, locality, city, village, taluka_id, dist_id, state_id, designation_id, pincode, email_id, contact_no, contact_no1, id_type, uid_no, dept_id, reporting_officer_email_id, hint_question, hint_answer, usercreate_flag, corp_coun_id, authetication_type, username, role_id, password, r_password, full_name, mobile_no, id)
    {
//alert(hint_question);
        //document.getElementById("actiontype").value = '2';
        //dyanamic function creation for Assigning value to text boxes in update function  according to language code   
//            $('input:radio[name="data[article][gov_body_applicable]"]').filter('[value="' + gov_body_applicable + '"]').attr('checked', true);

 
            var state = state_id;
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Masters/get_district_name", {state: state}, function (data)
            {
                var sc = dist_id;
              
                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#dist_id option").remove();
                $("#dist_id").append(sc);
                 $('#dist_id').val(dist_id);
            });
            
            
    
    
            var district = dist_id;
            var token = $("#token").val();
            var i1;
            $.getJSON("<?php  echo $this->webroot; ?>Masters/get_taluka_name1", {district: district, token: token}, function (data1)
            {
                var sc1 =taluka_id;
                $.each(data1, function (index1, val1) {
                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc1);
                $('#taluka_id').val(taluka_id);
            });
        
      

//alert(taluka_id);
//alert(dist_id);
//alert(state_id);
        if (usercreate_flag == 'Y') {
            
        alert(emp_code);
//            alert(usercreate_flag);

            var $radios = $('input:radio[name=usercreate_flag]');
            if ($radios.is(':checked') === true) {
                $radios.filter('[value=N]').prop('checked', false);
                $radios.filter('[value=Y]').prop('checked', true);
            }
            
            
            $("#password").prop('disabled', true);
$("#r_password").prop('disabled', true);
$("#username").prop('disabled', true);
//    usercreate_flag

$('#hfusername').val(username);
$('#hfpwd').val(password);
        $('#createuser').show();
            $('#hfid').val(emp_code);
            $('#emp_code').val(emp_code);
            $('#salutation').val(salutation);
            $('#emp_fname').val(emp_fname);
            $('#emp_mname').val(emp_mname);
            $('#emp_lname').val(emp_lname);
            $('#office_id').val(office_id);
            $('#qualification_id').val(qualification_id);

            $('#building_no').val(building_no);
            //$('#designation_id').val(designation_id);
            $('#flat_no').val(flat_no);
            $('#road_name').val(road_name);
            $('#locality').val(locality);
            $('#city').val(city);
            $('#village').val(village);
            $('#taluka_id').val(taluka_id);
            $('#dist_id').val(dist_id);
            $('#state_id').val(state_id);
            $('#designation_id').val(designation_id);
            $('#pincode').val(pincode);
            $('#email_id').val(email_id);
            $('#contact_no').val(contact_no);
            $('#contact_no1').val(contact_no1);
            $('#id_type').val(id_type);
            $('#uid_no').val(uid_no);
            $('#dept_id').val(dept_id);
            $('#reporting_officer_email_id').val(reporting_officer_email_id);
            $('#hint_question').val(hint_question);
            $('#hint_answer').val(hint_answer);
            $('#usercreate_flag').val(usercreate_flag);
            $('#corp_coun_id').val(corp_coun_id);
            $('#authetication_type').val(authetication_type);
            //$('#username').val(username);
            $('#role_id').val(role_id);
           // $('#password').val(password);
           // $('#r_password').val(r_password);
            $('#full_name').val(full_name);
            $('#mobile_no').val(mobile_no);


            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
        } else {
            var $radios = $('input:radio[name=usercreate_flag]');
            if ($radios.is(':checked') === false) {
                $radios.filter('[value=N]').prop('checked', true);
            }
            $('#createuserradio').hide();
            $('#hfid').val(id);
            $('#emp_code').val(emp_code);
            $('#salutation').val(salutation);
            $('#emp_fname').val(emp_fname);
            $('#emp_mname').val(emp_mname);
            $('#emp_lname').val(emp_lname);
            $('#office_id').val(office_id);
            $('#qualification_id').val(qualification_id);

            $('#building_no').val(building_no);
            //$('#designation_id').val(designation_id);
            $('#flat_no').val(flat_no);
            $('#road_name').val(road_name);
            $('#locality').val(locality);
            $('#city').val(city);
            $('#village').val(village);
            $('#taluka_id').val(taluka_id);
            $('#dist_id').val(dist_id);
            $('#state_id').val(state_id);
            $('#designation_id').val(designation_id);
            $('#pincode').val(pincode);
            $('#email_id').val(email_id);
            $('#contact_no').val(contact_no);
            $('#contact_no1').val(contact_no1);
            $('#id_type').val(id_type);
            $('#uid_no').val(uid_no);
            $('#dept_id').val(dept_id);
            $('#reporting_officer_email_id').val(reporting_officer_email_id);
            $('#hint_question').val(hint_question);
            $('#hint_answer').val(hint_answer);

            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
        }

//        $('#createuserradio').hide();
//        $('#hfid').val(id);
//        $('#emp_code').val(emp_code);
//        $('#salutation').val(salutation);
//        $('#emp_fname').val(emp_fname);
//        $('#emp_mname').val(emp_mname);
//        $('#emp_lname').val(emp_lname);
//        $('#office_id').val(office_id);
//        $('#qualification_id').val(qualification_id);
//
//        $('#building_no').val(building_no);
//        //$('#designation_id').val(designation_id);
//        $('#flat_no').val(flat_no);
//        $('#road_name').val(road_name);
//        $('#locality').val(locality);
//        $('#city').val(city);
//        $('#village').val(village);
//        $('#taluka_id').val(taluka_id);
//        $('#dist_id').val(dist_id);
//        $('#state_id').val(state_id);
//        $('#designation_id').val(designation_id);
//        $('#pincode').val(pincode);
//        $('#email_id').val(email_id);
//        $('#contact_no').val(contact_no);
//        $('#contact_no1').val(contact_no1);
//        $('#id_type').val(id_type);
//        $('#uid_no').val(uid_no);
//        $('#dept_id').val(dept_id);
//        $('#reporting_officer_email_id').val(reporting_officer_email_id);
//        $('#hint_question').val(hint_question);
//        $('#hint_answer').val(hint_answer);
//
//        $('#hfupdateflag').val('Y');
//        $('#btnadd').html('Save');


        return false;
    }

    function after_validation_check() {
        //  EncryptSHA1();
        var pass = $("#password").val();
        var r_password = $("#r_password").val();
        var user = $("#username").val();
        var SALT = "<?php echo $this->Session->read("salt"); ?>";
        $("#password").val(encrypt(pass, SALT));
        $("#username").val(encrypt(user, SALT));
        $("#r_password").val(encrypt(r_password, SALT));
    }
</script>


<?php echo $this->Form->create('employee', array('id' => 'employee', 'class' => 'form-vertical')); ?>

<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblemployeeregi'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="emp_code" class="col-sm-2 control-label"><?php echo __('lblempid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_code', array('label' => false, 'id' => 'emp_code', 'value' => $empcode, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_code_error" class="form-error"><?php echo $errarr['emp_code_error']; ?></span>
                        </div>
                        <label for="designation_id" class="col-sm-2 control-label"><?php echo __('lbldesignation'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('designation_id', array('label' => false, 'id' => 'designation_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $designation))); ?>
                            <span id="designation_id_error" class="form-error"><?php //echo $errarr['designation_id_error'];            ?></span>
                        </div>
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $office))); ?>
                            <span id="office_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="salutation" class="col-sm-2 control-label"><?php echo __('lblSalutation'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('salutation', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                            <span id="salutation_error" class="form-error"><?php echo $errarr['salutation_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="emp_fname" class="col-sm-2 control-label"><?php echo __('lblfname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_fname', array('label' => false, 'id' => 'emp_fname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_fname_error" class="form-error"><?php echo $errarr['emp_fname_error']; ?></span>
                        </div>
                        <label for="emp_mname" class="col-sm-2 control-label"><?php echo __('lblmname'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_mname', array('label' => false, 'id' => 'emp_mname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_mname_error" class="form-error"><?php echo $errarr['emp_mname_error']; ?></span>
                        </div>
                        <label for="father_lname" class="col-sm-2 control-label"><?php echo __('lbllname'); ?> <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_lname', array('label' => false, 'id' => 'emp_lname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_lname_error" class="form-error"><?php echo $errarr['emp_lname_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">

                        <label for="qualification_id" class="col-sm-2 control-label"><?php echo __('lblqualification'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('qualification_id', array('label' => false, 'id' => 'qualification_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $qualification))); ?>
                            <span id="qualification_id_error" class="form-error"><?php echo $errarr['qualification_id_error']; ?></span>
                        </div>
                        <label for="dept_id" class="col-sm-2 control-label"><?php echo __('lbldept'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('dept_id', array('label' => false, 'id' => 'dept_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $department))); ?>
                            <span id="dept_id_error" class="form-error"><?php echo $errarr['dept_id_error']; ?></span>
                        </div>
                        <label for="reporting_officer_email_id" class="col-sm-2 control-label"><?php echo __('lblreportingofficeremailid1'); ?></label> 

                        <div class="col-sm-2">
                            <?php echo $this->Form->input('reporting_officer_email_id', array('options' => array($Empcode), 'empty' => '--select--', 'id' => 'reporting_officer_email_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="employee_id_error" class="form-error"><?php //echo $errarr['employee_id_error'];            ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="building_no" class="col-sm-2 control-label"><?php echo __('lblbuildingnamenofloor'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('building_no', array('label' => false, 'id' => 'building_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="building_no_error" class="form-error"><?php echo $errarr['building_no_error']; ?></span>
                        </div>
                        <label for="flat_no" class="col-sm-2 control-label"><?php echo __('lblflat'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('flat_no', array('label' => false, 'id' => 'flat_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="flat_no_error" class="form-error"><?php echo $errarr['flat_no_error']; ?></span>
                        </div>
                        <label for="road_name" class="col-sm-2 control-label"><?php echo __('lblroadname'); ?></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('road_name', array('label' => false, 'id' => 'road_name', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="road_name_error" class="form-error"><?php echo $errarr['road_name_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="state_id" class="col-sm-2 control-label"><?php echo __('lbladmstate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'empty' => '----select----', 'options' => array($State))); ?>
                            <?php //echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select State--', $State))); ?>
                            <span id="state_id_error" class="form-error"><?php echo $errarr['state_id_error']; ?></span>
                        </div>
                        <label for="dist_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">

                            <?php echo $this->Form->input('dist_id', array('label' => false, 'id' => 'dist_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array())); ?>
                            <?php //echo $this->Form->input('dist_id', array('label' => false, 'id' => 'dist_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--', $District))); ?>
                            <span id="dist_id_error" class="form-error"><?php //echo $errarr['dist_id_error'];            ?></span>
                        </div>
                        <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array())); ?>
                            <?php //echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Taluka--', $taluka))); ?>
                            <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="locality" class="col-sm-2 control-label"><?php echo __('lblstreetlocality'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('locality', array('label' => false, 'id' => 'locality', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="locality_error" class="form-error"><?php echo $errarr['locality_error']; ?></span>
                        </div>
                        <label for="city" class="col-sm-2 control-label"><?php echo __('lblcity'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="city_error" class="form-error"><?php echo $errarr['city_error']; ?></span>
                        </div>
                        <label for="village" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('village', array('label' => false, 'id' => 'village', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="village_error" class="form-error"><?php echo $errarr['village_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="pincode" class="col-sm-2 control-label"><?php echo __('lblpincode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '6', 'autocomplete' => 'off')) ?>
                            <span id="pincode_error" class="form-error"><?php echo $errarr['pincode_error']; ?></span>
                        </div>
                        <label for="contact_no" class="col-sm-2 control-label"><?php echo __('lblcontactno'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('contact_no', array('label' => false, 'id' => 'contact_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'data-inputmask' => "'alias':, 'ip'", 'data-mask' => "")) ?>
                            <span id="contact_no_error" class="form-error"><?php echo $errarr['contact_no_error']; ?></span>
                        </div>

                        <label for="contact_no1" class="col-sm-2 control-label"><?php echo __('lblcontactno'); ?>&nbsp; 1<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('contact_no1', array('label' => false, 'id' => 'contact_no1', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'autocomplete' => 'off')) ?>
                            <span id="contact_no1_error" class="form-error"><?php echo $errarr['contact_no1_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">

                        <label for="email_id" class="col-sm-2 control-label"><?php echo __('lblemailid'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '30', 'autocomplete' => 'off')) ?>
                            <span id="email_id_error" class="form-error"><?php // echo $errarr['email_id_error'];                ?></span>
                        </div>
<!--                        <label for="uid_no" class="col-sm-2 control-label"><?php // echo __('lbluid');                 ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                        <?php // echo $this->Form->input('uid_no', array('label' => false, 'id' => 'uid_no', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=>'14','autocomplete'=>'off')) ?>
                            <span id="uid_no_error" class="form-error"><?php // echo $errarr['uid_no_error'];                 ?></span>
                        </div>-->
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="id_type" class="col-sm-2 control-label"><?php echo __('lblidproof'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('id_type', array('label' => false, 'id' => 'id_type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select ID Proof--', $idtype))); ?>
                            <span id="id_type_error" class="form-error"><?php echo $errarr['id_type_error']; ?></span>
                        </div>
                        <label for="uid_no" class="col-sm-2 control-label" id="pan_lable"><?php echo __('lblidproofno'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2" id="pantxt">
                            <?php echo $this->Form->input('uid_no', array('label' => false, 'id' => 'uid_no', 'type' => 'text', 'maxlength' => '12', 'class' => 'form-control validate[maxSize[12]]')); ?>
                            <span id="uid_no_error" class="form-error"><?php echo $errarr['uid_no_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="question" class="col-sm-2 control-label"><?php echo __('lblhintqst'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_question', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Hint Question--', $hintquestion))); ?>
                            <span id="hint_question_error" class="form-error"><?php echo $errarr['hint_question_error']; ?></span>
                        </div>
                        <label for="qst_ans" class="col-sm-2 control-label"><?php echo __('lblhintans'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('hint_answer', array('label' => false, 'id' => 'hint_answer', 'maxlength' => '50', 'type' => 'text', 'class' => 'form-control validate[required,maxSize[50]],custom[onlyLetterSp]')); ?>
                            <span id="hint_answer_error" class="form-error"><?php echo $errarr['hint_answer_error']; ?></span>
                        </div>
                        <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>
                    </div>

                </div>
                <div id='createuserradio'class="row">
                    <div class="form-group">
                        <label for="usercreate" class="control-label col-sm-4"><?php echo __('lblnewuserreg'); ?></label>            
                        <div class="col-sm-2"> 

<!--                                        <input type="radio" value="Y" name="usercreate_flagY" id="usercreate_flagY"   <?php if ($usercreate['employee']['usercreate_flag'] == 'Y') { ?> checked <?php } ?>>Yes
             <input type="radio" value="N" name="usercreate_flagN" id="usercreate_flagN" <?php if ($usercreate['employee']['usercreate_flag'] == 'N') { ?> checked <?php } ?>>No-->
                            <?php echo $this->Form->input('usercreate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usercreate_flag', 'name' => 'usercreate_flag')); ?>
                        </div> 
                    </div>
                </div>

                <div class="row" id="createuser" hidden="true">
                    <div class="col-lg-12">

                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title headbolder"><?php echo __('lblnewuserreg'); ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="corp_coun_id" class="col-sm-3 control-label"><?php echo __('lbllocalgovbody'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('corp_coun_id', array('options' => array($corp_coun), 'empty' => '--select--', 'id' => 'corp_coun_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                            <span id="corp_coun_id_error" class="form-error"><?php //echo $errarr['corp_coun_id_error'];                 ?></span>
                                        </div>
                                        <label for="type" class="col-sm-3 control-label"><?php echo __('lblauthtype'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('authetication_type', array('options' => array($type), 'empty' => '--select--', 'id' => 'authetication_type', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                            <span id="authetication_type_error" class="form-error"><?php //echo $errarr['authetication_type_error'];                 ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht"></div>
                                <div class="row">
                                    <div id='divbiocap' hidden="true" class="form-group">
                                        <label for="biometric_capture_flag" class="col-sm-3 control-label"><?php echo __('lblbiometriclogincapture'); ?> :<span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('biometric_capture_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'biometric_capture_flag', 'name' => 'biometric_capture_flag')); ?></div>
                                        <span id="biometric_capture_flag_error" class="form-error"><?php //echo $errarr['biometric_capture_flag_error'];                 ?></span>
                                    </div>
                                </div>

                                <div  class="rowht"></div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="username" class="col-sm-3 control-label"><?php echo __('lblusername'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'type' => 'text', 'class' => 'form-control input-sm', 'onblur' => 'checkusername()', 'maxlength' => "20")); ?>
                                            <span id="username_error" class="form-error"><?php //echo $errarr['username_error'];                 ?></span>
                                        </div>
                                        <label for="role_id" class="col-sm-3 control-label"><?php echo __('lblassignrole'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>

                                            <span id="role_id_error" class="form-error"><?php //echo $errarr['authetication_type_error'];              ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht"></div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="password" class="col-sm-3 control-label"><?php echo __('lblpassword'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('password', array('label' => false, 'id' => 'password', 'type' => 'password', 'class' => 'form-control validate[required]', 'maxlength' => "10", 'onfocus' => '$(this).removeAttr("readonly");')); ?>
                                            <span id="password_error" class="form-error"><?php //echo $errarr['password_error'];                 ?></span>
                                        </div>
                                        <label for="r_password" class="col-sm-3 control-label"><?php echo __('lblrepassword'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('r_password', array('label' => false, 'id' => 'r_password', 'type' => 'password', 'class' => 'form-control validate[required ,equals[password]', 'maxlength' => "10")); ?>
                                            <span id="r_password_error" class="form-error"><?php //echo $errarr['r_password_error'];                 ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht"></div>
                                <div class="row">
                                    <div class="form-group">
                <!--                        <label for="employee_id" class="col-sm-3 control-label"><?php echo __('lblempid'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                        <?php // echo $this->Form->input('employee_id', array('label' => false, 'id' => 'employee_id', 'type' => 'text', 'class' => 'form-control validate[required,custom[integer]]')); ?>
                                        <span id="employee_id_error" class="form-error"><?php // echo $errarr['employee_id_error'];                 ?></span>
                                        </div>-->
                                        <label for="full_name" class="col-sm-3 control-label"><?php echo __('lblfullname'); ?></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('full_name', array('label' => false, 'id' => 'full_name', 'type' => 'text', 'class' => 'form-control validate[required,custom[onlyLetterNumber]]')); ?>
                                            <span id="full_name_error" class="form-error"><?php //echo $errarr['full_name_error'];                 ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht"></div>
                                <div class="row">
<!--                                    <div class="form-group">
                                        <label for="mobile_no" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'text', 'class' => 'form-control validate[required,minSize[10],maxSize[12],custom[phone]]')); ?>
                                            <span id="mobile_no_error" class="form-error"><?php //echo $errarr['mobile_no_error'];                 ?></span>
                                        </div>
                                        <label for="email_id" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?><span style="color: #ff0000">*</span></label>    
                                        <div class="col-sm-3">
                                        <?php // echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'text', 'class' => 'form-control validate[required,custom[email]]')); ?>
                                            <span id="email_id_error" class="form-error"><?php //echo $errarr['email_id_error'];                 ?></span>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group">

                        <button id="btnadd" name="btnadd" class="btn btn-info ">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                        <input type="reset" id="btnCancel" name="btnCancel" class="btn btn-info " value="<?php echo __('btncancel'); ?>"> 
                    </div>


                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center">Employee Code</th>
                            <th class="center"><?php echo __('lblfname'); ?></th>
                            <th class="center"><?php echo __('lblmname'); ?></th>
                            <th class="center"><?php echo __('lbllname'); ?></th>
                            <th class="center"><?php echo __('lblusername'); ?></th>

                            <th class="center"><?php echo __('lblofficename'); ?></th>
                            <th class="center"><?php echo __('lbldesignation'); ?></th>
                            <th class="center"><?php echo __('lblcontactno'); ?></th>
                            <th class="width10 center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($employeerecord as $employeerecord1):  ?>

                            <tr>
                                <td ><?php echo $employeerecord1[0]['emp_code'] ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_fname'] ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_mname']; ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_lname']; ?></td>

                                <td ><?php echo $employeerecord1[0]['username']; ?></td>
                                <td ><?php echo $officedec[$employeerecord1[0]['office_id']]; ?></td>
                                <td ><?php echo $designationdec[$employeerecord1[0]['designation_id']]; ?></td>
                                <td ><?php echo $employeerecord1[0]['contact_no']; ?></td>
                                <td class="width10 center">
                                   <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                    ('<?php echo $employeerecord1[0]['emp_code']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['salutation']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['emp_fname']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['emp_mname']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['emp_lname']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['office_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['qualification_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['building_no']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['flat_no']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['road_name']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['locality']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['city']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['village']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['taluka_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['dist_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['state_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['designation_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['pincode']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['email_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['contact_no']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['contact_no1']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['id_type']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['uid_no']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['dept_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['reporting_officer_email_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['hint_question']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['hint_answer']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['usercreate_flag']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['corp_coun_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['authetication_type']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['username']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['role_id']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['password']; ?>'),
                                                    ('<?php //echo $employeerecord1[0]['r_password']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['full_name']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['mobile_no']; ?>'),
                                                    ('<?php echo $employeerecord1[0]['id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'employee_delete', $employeerecord1[0]['emp_code']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td> 
                            </tr> 

<?php endforeach; ?>
                        <?php unset($employeerecord1); ?>
                    </tbody>
                </table> 
<?php if (!empty($employeerecord)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>



    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
     <input type='hidden' value='<?php echo $hfpwd; ?>' name='hfpwd' id='hfpwd'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>