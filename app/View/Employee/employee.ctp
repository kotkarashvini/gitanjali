<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>

    $(document).ready(function () {

        $('#tabledivisionnew').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#state_id').change(function () {
            var state = $("#state_id option:selected").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Employee/get_district_name", {state: state}, function (data)
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
            //var token = $("#token").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Employee/gettaluka", {district: district}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $("#uid_no").blur(function () {
           
            var host = '<?php echo $this->webroot; ?>';
            var type = $("#id_type option:selected").val();

            var desc = $("#uid_no").val();
            if (type != 'empty')
            {

                $.post(host + 'Employee/get_validation_rule', {type: type}, function (data)
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
                        $("#uid_no").val('');
                        // $("#identificationtype_desc_en").focus();
                        $("#uid_no_error").text(message);
                        return false;
                    } else
                    {
                        $("#uid_no_error").text('');
                        return true;
                    }
                }, 'json');
            }

        });

    });




//    function after_validation_check() {
//        //  EncryptSHA1();
//        var pass = $("#password").val();
//        var r_password = $("#r_password").val();
//        var user = $("#username").val();
//        var SALT = "<?php //echo $this->Session->read("salt");  ?>";
//        $("#password").val(encrypt(pass, SALT));
//        $("#username").val(encrypt(user, SALT));
//        $("#r_password").val(encrypt(r_password, SALT));
//    }
</script>


<?php echo $this->Form->create('employee', array('id' => 'employee', 'class' => 'form-vertical')); ?>

<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblemployeeregi'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Employee/employee_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="emp_code" class="col-sm-2 control-label"><?php echo __('lblempid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_code', array('label' => false, 'id' => 'emp_code', 'value' => $empcode, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off', 'readonly' => 'readonly')) ?>
                            <?php // echo $this->Form->input('emp_id', array('label' => false, 'id' => 'emp_id', 'value' => $empid, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_code_error" class="form-error"><?php //echo $errarr['emp_code_error'];  ?></span>
                        </div>
                        <label for="designation_id" class="col-sm-2 control-label"><?php echo __('lbldesignation'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('designation_id', array('label' => false, 'id' => 'designation_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $designation))); ?>
                            <span id="designation_id_error" class="form-error"><?php //echo $errarr['designation_id_error'];                  ?></span>
                        </div>
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $office))); ?>
                            <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="salutation" class="col-sm-2 control-label"><?php echo __('lblSalutation'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('salutation_id', array('label' => false, 'id' => 'salutation_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                            <span id="salutation_error" class="form-error"><?php //echo $errarr['salutation_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="emp_fname" class="col-sm-2 control-label"><?php echo __('lblfname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_fname', array('label' => false, 'id' => 'emp_fname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_fname_error" class="form-error"><?php //echo $errarr['emp_fname_error'];  ?></span>
                        </div>
                        <label for="emp_mname" class="col-sm-2 control-label"><?php echo __('lblmname'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_mname', array('label' => false, 'id' => 'emp_mname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_mname_error" class="form-error"><?php //echo $errarr['emp_mname_error'];  ?></span>
                        </div>
                        <label for="father_lname" class="col-sm-2 control-label"><?php echo __('lbllname'); ?> <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_lname', array('label' => false, 'id' => 'emp_lname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="emp_lname_error" class="form-error"><?php //echo $errarr['emp_lname_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">

                        <label for="qualification_id" class="col-sm-2 control-label"><?php echo __('lblqualification'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('qualification_id', array('label' => false, 'id' => 'qualification_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $qualification))); ?>
                            <span id="qualification_id_error" class="form-error"><?php //echo $errarr['qualification_id_error'];  ?></span>
                        </div>
                        <label for="dept_id" class="col-sm-2 control-label"><?php echo __('lbldept'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('dept_id', array('label' => false, 'id' => 'dept_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $department))); ?>
                            <span id="dept_id_error" class="form-error"><?php //echo $errarr['dept_id_error'];  ?></span>
                        </div>
                        <label for="reporting_officer_email_id" class="col-sm-2 control-label"><?php echo __('lblreportingofficeremailid1'); ?></label> 

                        <div class="col-sm-2">
                            <?php echo $this->Form->input('reporting_officer_email_id', array('options' => array($Empcode), 'empty' => '--select--', 'id' => 'reporting_officer_email_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="employee_id_error" class="form-error"><?php //echo $errarr['employee_id_error'];                  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="building_no" class="col-sm-2 control-label"><?php echo __('lblbuildingnamenofloor'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('building_no', array('label' => false, 'id' => 'building_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="building_no_error" class="form-error"><?php //echo $errarr['building_no_error'];  ?></span>
                        </div>
                        <label for="flat_no" class="col-sm-2 control-label"><?php echo __('lblflat'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('flat_no', array('label' => false, 'id' => 'flat_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="flat_no_error" class="form-error"><?php //echo $errarr['flat_no_error'];  ?></span>
                        </div>
                        <label for="road_name" class="col-sm-2 control-label"><?php echo __('lblroadname'); ?></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('road_name', array('label' => false, 'id' => 'road_name', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="road_name_error" class="form-error"><?php //echo $errarr['road_name_error'];  ?></span>
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
                            <span id="state_id_error" class="form-error"><?php //echo $errarr['state_id_error'];  ?></span>
                        </div>
                        <label for="dist_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">

                            <?php echo $this->Form->input('dist_id', array('label' => false, 'id' => 'dist_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array($distdata))); ?>
                            <?php //echo $this->Form->input('dist_id', array('label' => false, 'id' => 'dist_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--', $District))); ?>
                            <span id="dist_id_error" class="form-error"><?php //echo $errarr['dist_id_error'];                  ?></span>
                        </div>
                        <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array($talukadata))); ?>
                            <?php //echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Taluka--', $taluka))); ?>
                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="locality" class="col-sm-2 control-label"><?php echo __('lblstreetlocality'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('locality', array('label' => false, 'id' => 'locality', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="locality_error" class="form-error"><?php //echo $errarr['locality_error'];  ?></span>
                        </div>
                        <label for="city" class="col-sm-2 control-label"><?php echo __('lblcity'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="city_error" class="form-error"><?php //echo $errarr['city_error'];  ?></span>
                        </div>
                        <label for="village" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('village', array('label' => false, 'id' => 'village', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20', 'autocomplete' => 'off')) ?>
                            <span id="village_error" class="form-error"><?php //echo $errarr['village_error'];  ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="pincode" class="col-sm-2 control-label"><?php echo __('lblpincode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '6', 'autocomplete' => 'off')) ?>
                            <span id="pincode_error" class="form-error"><?php //echo $errarr['pincode_error'];  ?></span>
                        </div>
                        <label for="contact_no" class="col-sm-2 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('contact_no', array('label' => false, 'id' => 'contact_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'data-inputmask' => "'alias':, 'ip'", 'data-mask' => "", 'autocomplete' => 'off')) ?>
                            <span id="contact_no_error" class="form-error"><?php //echo $errarr['contact_no_error'];  ?></span>
                        </div>

                        <label for="contact_no1" class="col-sm-2 control-label"><?php echo __('lblcontactno'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('contact_no1', array('label' => false, 'id' => 'contact_no1', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'autocomplete' => 'off')) ?>
                            <span id="contact_no1_error" class="form-error"><?php //echo $errarr['contact_no1_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">

                        <label for="email_id" class="col-sm-2 control-label"><?php echo __('lblemailid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '30', 'autocomplete' => 'off')) ?>
                            <span id="email_id_error" class="form-error"><?php // echo $errarr['email_id_error'];                      ?></span>
                        </div>
<!--                        <label for="uid_no" class="col-sm-2 control-label"><?php // echo __('lbluid');                        ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                        <?php // echo $this->Form->input('uid_no', array('label' => false, 'id' => 'uid_no', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=>'14','autocomplete'=>'off'))  ?>
                            <span id="uid_no_error" class="form-error"><?php // echo $errarr['uid_no_error'];                       ?></span>
                        </div>-->
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="id_type" class="col-sm-2 control-label"><?php echo __('lblidproof'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('id_type', array('label' => false, 'id' => 'id_type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select ID Proof--', $idtype))); ?>
                            <span id="id_type_error" class="form-error"><?php //echo $errarr['id_type_error'];  ?></span>
                        </div>
                        <label for="uid_no" class="col-sm-2 control-label" id="pan_lable"><?php echo __('lblidproofno'); ?><span style="color: #ff0000"></span></label>
                        <div class="col-sm-2" id="pantxt">
                            <?php echo $this->Form->input('uid_no', array('label' => false, 'id' => 'uid_no', 'type' => 'text', 'maxlength' => '12', 'class' => 'form-control validate[maxSize[12]]', 'autocomplete' => 'off')); ?>
                            <span id="uid_no_error" class="form-error"><?php //echo $errarr['uid_no_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="question" class="col-sm-2 control-label"><?php echo __('lblhintqst'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('hint_question', array('label' => false, 'id' => 'hint_question', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Hint Question--', $hintquestion))); ?>
                            <span id="hint_question_error" class="form-error"><?php //echo $errarr['hint_question_error'];  ?></span>
                        </div>
                        <label for="qst_ans" class="col-sm-2 control-label"><?php echo __('lblhintans'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('hint_answer', array('label' => false, 'id' => 'hint_answer', 'maxlength' => '50', 'type' => 'text', 'class' => 'form-control validate[required,maxSize[50]],custom[onlyLetterSp]', 'autocomplete' => 'off')); ?>
                            <span id="hint_answer_error" class="form-error"><?php //echo $errarr['hint_answer_error'];  ?></span>
                        </div>
                        <input type='hidden' value='<?php echo $_SESSION["token"]; ?>' name='token' id='token'/>

                    </div>

                </div>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group">

                       <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                            
                        <a href="<?php echo $this->webroot; ?>Employee/employee/" class="btn btn-info"><span class="glyphicon glyphicon-floppy-remove"><?php echo __('btncancel'); ?></span> </a>    
                    </div>


                </div>
            </div>
        </div>

         <div class="horizontal-scrollable">
       
        <div class="box box-primary">

            <div class="box-body" style="overflow-x:auto">
                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center">Employee Code</th>
                            <th class="center"><?php echo __('lblfname'); ?></th>
                            <th class="center"><?php echo __('lblmname'); ?></th>
                            <th class="center"><?php echo __('lbllname'); ?></th>
                            <th class="center"><?php echo __('lblusername'); ?></th>
                            <th class="center"><?php echo __('lbladmdistrict'); ?></th>
                            <th class="center"><?php echo __('lblofficename'); ?></th>
                            <th class="center"><?php echo __('lbldesignation'); ?></th>
                            <th class="center"><?php echo __('lblcontactno'); ?></th>
                            <th class="width10 center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($employeerecord as $employeerecord1): ?>

                            <tr>
                                <td ><?php echo $employeerecord1[0]['emp_code'] ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_fname'] ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_mname']; ?></td>
                                <td ><?php echo $employeerecord1[0]['emp_lname']; ?></td>

                                <td ><?php echo $employeerecord1[0]['username']; ?></td>
                                <td ><?php echo $District[$employeerecord1[0]['dist_id']]; ?></td>
                                <td ><?php echo $officedec[$employeerecord1[0]['office_id']]; ?></td>
                                <td ><?php echo $designationdec[$employeerecord1[0]['designation_id']]; ?></td>
                                <td ><?php echo $employeerecord1[0]['contact_no']; ?></td>
                                <td class="width10 center"> 
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'employee', $employeerecord1[0]['emp_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'employee_delete', $employeerecord1[0]['emp_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                               
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
            </div>


    </div>

    <?php echo $this->Form->input('emp_id', array('label' => false, 'id' => 'emp_id', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>