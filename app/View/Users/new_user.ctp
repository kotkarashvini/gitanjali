<?php
echo $this->element("Helper/jqueryhelper");
?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('JS');
?>
<script>
    $(document).ready(function () {
        $('#newuser').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
if($("#authetication_type option:selected").val()==2 ||$("#authetication_type option:selected").val()==3){
       $("#divbiocap").show();
}
        $('#authetication_type').change(function () {
//               alert('authetication_type');
            var authetication_type = $("#authetication_type option:selected").val();
            if (authetication_type == '2' || authetication_type == '3') {
                $("#divbiocap").show();
            } else {
                $("#divbiocap").hide();
            }
        });

        $('#employee_id').change(function () {
            var emp_code = $("#employee_id option:selected").val();
            var i;
            var sc;
            $.post("<?php echo $this->webroot; ?>Users/get_employeedetail", {emp_code: emp_code}, function (data)
            {
//              alert(data.emp_fname);
//             return false; 
                $("#full_name").val(data.emp_fname + ' ' + data.emp_mname + ' ' + data.emp_lname);
                $("#mobile_no").val(data.contact_no);
                $("#email_id").val(data.email_id);
            }, 'json');
        });
        
         $('#office_id').change(function () {
            var office = $("#office_id option:selected").val();
            //var token = $("#token").val();
//            alert(office);
            var i;
            $.post("<?php echo $this->webroot; ?>Users/get_employeelist", {office: office}, function (data)
            {
               
                var sc = '<option value="empty">--Select Employee--</option>';
                $.each(JSON.parse(data), function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#employee_id option").remove();
                $("#employee_id").append(sc);
            });
        })
        


    });

   
function after_validation_check() {
        var pass = $("#password").val();
        var r_password = $("#r_password").val();
        var user = $("#username").val();
        var SALT = "<?php echo $this->Session->read("salt"); ?>";
       // $("#password").val(encrypt(pass, SALT));
        $("#username").val(encrypt(user, SALT));
        //$("#r_password").val(encrypt(r_password, SALT));
        
          //sha256 password
        var SHA1Hashpass = hex_sha256(pass);
        var SHA1Hashpassresult = hex_sha256(SALT + SHA1Hashpass);
        document.getElementById("password").value = SHA1Hashpass;
        
        //sha256 r_password
         var SHA1Hashre_pass = hex_sha256(r_password);
        var SHA1Hashre_passresult = hex_sha256(SALT + SHA1Hashre_pass);
        document.getElementById("r_password").value = SHA1Hashre_pass;
        
        
        //sha1
//        $("#password").val(encrypt(pass, SALT));
//        $("#username").val(encrypt(user, SALT));
//        $("#r_password").val(encrypt(r_password, SALT));
    }


</script>
<?php echo $this->Form->create('new_user', array('type' => 'file', 'id' => 'new_user')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblnewuserreg'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/new_user_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="office_id" class="col-sm-3 control-label"><?php echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('options' => array($officedec), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm', 'label' => false)); ?>

                            <span id="role_id_error" class="form-error"><?php //echo $errarr['role_id_error']; ?></span>
                        </div>
                        <label for="employee_id" class="col-sm-3 control-label"><?php echo __('lblempid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('employee_id', array('options' => array($Empcode), 'empty' => '--select--', 'id' => 'employee_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="employee_id_error" class="form-error"><?php //echo $errarr['employee_id_error']; ?></span>
                             <?php
                            if (isset($this->request->data['new_user']['user_id'])) {
                                echo $this->Form->input('user_id', array('label' => false, 'id' => 'user_id', 'type' => 'hidden', 'class' => 'form-control input-sm', 'maxlength' => "20", 'value' => $this->request->data['new_user']['user_id']));
                            }
                            ?>
                        </div>
                        
<!--                        <label for="corp_coun_id" class="col-sm-3 control-label"><?php echo __('lbllocalgovbody'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php //echo $this->Form->input('corp_coun_id', array('options' => array($corp_coun), 'empty' => '--select--', 'id' => 'corp_coun_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="corp_coun_id_error" class="form-error"><?php //echo $errarr['corp_coun_id_error']; ?></span>
                            <?php
//                            if (isset($this->request->data['new_user']['user_id'])) {
//                                echo $this->Form->input('user_id', array('label' => false, 'id' => 'user_id', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => "20", 'value' => $this->request->data['new_user']['user_id']));
//                            }
                            ?>
                           <span id="user_id_error" class="form-error"><?php // echo $errarr['user_id_error'];           ?></span>
                        </div>-->
                        </div>
                </div>
                <div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label"><?php echo __('lblauthtype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('authetication_type', array('options' => array($type), 'empty' => '--select--', 'id' => 'authetication_type', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="authetication_type_error" class="form-error"><?php //echo $errarr['authetication_type_error']; ?></span>
                        </div>
                        
                        <div class="row">
                            <div id='divbiocap' hidden="true" class="form-group">
                                <label for="biometric_capture_flag" class="col-sm-3 control-label"><?php echo __('lblbiometriclogincapture'); ?> :<span style="color: #ff0000">*</span></label>    
                                <div  class="col-sm-3" >
                                    <?php echo $this->Form->input('biometric_capture_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'biometric_capture_flag', 'name' => 'biometric_capture_flag')); ?></div>
                                <span id="ubiometric_capture_flag_error" class="form-error"><?php //echo $errarr['biometric_capture_flag_error'];           ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                
                <div class="row">
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label"><?php echo __('lblusername'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => "50")); ?>
                            <span id="username_error" class="form-error"><?php //echo $errarr['username_error']; ?></span>
                        </div>
                        <label for="role_id" class="col-sm-3 control-label"><?php echo __('lblassignrole'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>

                            <span id="role_id_error" class="form-error"><?php //echo $errarr['role_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <?php //if($id==NULL){?>
                <div class="row">
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><?php echo __('lblpassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('password', array('label' => false, 'id' => 'password', 'type' => 'password', 'class' => 'form-control input-sm', 'maxlength' => "50")); ?>
                            <span id="password_error" class="form-error"><?php //echo $errarr['password_error']; ?></span>
                        </div>
                        <label for="r_password" class="col-sm-3 control-label"><?php echo __('lblrepassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('r_password', array('label' => false, 'id' => 'r_password', 'type' => 'password', 'class' => 'form-control input-sm', 'maxlength' => "12")); ?>
                            <span id="r_password_error" class="form-error"><?php //echo $errarr['r_password_error']; ?></span>
                        </div>
                    </div>
                </div>
                <?php// }?>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="full_name" class="col-sm-3 control-label"><?php echo __('lblfullname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('full_name', array('label' => false, 'id' => 'full_name', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => '100')); ?>
                            <span id="full_name_error" class="form-error"><?php //echo $errarr['full_name_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="mobile_no" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => "10")); ?>
                            <span id="mobile_no_error" class="form-error"><?php //echo $errarr['mobile_no_error']; ?></span>
                        </div>
                        <label for="email_id" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => '100')); ?>
                            <span id="email_id_error" class="form-error"><?php //echo $errarr['email_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group center">
<!--                        <a href="<?php //echo $this->webroot; ?>Users/new_user"  class="btn btn-info"> </span><?php echo __('lblnewuser'); ?></a> -->
                        <button id="cmdSubmit" type="submit" name="cmdSubmit" class="btn btn-info "  >
                            <?php
                            if (!isset($this->request->data['new_user']['user_id'])) {
                                echo __('btnsave');
                            } else {
                                echo __('lblbtnupdate');
                            }
                            ?>
                        </button>
<!--                        <button id="cmdCancel" name="cmdCancel" class="btn btn-info "  type="reset" >
                            <?php //echo __('btncancel'); ?>
                        </button> -->
                          <a href="<?php echo $this->webroot; ?>Users/new_user/" class="btn btn-info"><span class="glyphicon glyphicon-floppy-remove"><?php echo __('btncancel'); ?></span> </a>    
                    </div>
                </div>
            </div>
              <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                
                    <table id="newuser" class="table table-striped table-bordered table-hover">
                        <thead >
                            <tr>
                                <th class="center"><?php echo __('lblempid'); ?></th>
                                <th class="center"><?php echo __('lblusername'); ?></th>
                                <th class="center"> <?php echo __('lblfullname'); ?></th>
                                <th class="center"><?php echo __('lblmobileno'); ?></th>
                                <th class="center"><?php echo __('lblemailid'); ?></th>
                                <th class="center"><?php echo __('lblbiometric'); ?></th>
                                <th class="center"><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                           //pr($userrecord);
                            for ($i = 0; $i < count($userrecord); $i++) { //pr($userrecord);
                                ?>
                                <tr>
                                    <td><?php echo $userrecord[$i][0]['employee_id'] ?></td>
                                    <td><?php echo $userrecord[$i][0]['username']; ?></td>
                                    <td><?php echo $userrecord[$i][0]['full_name'] ?></td>
                                    <td><?php echo $userrecord[$i][0]['mobile_no'] ?></td>
                                    <td><?php echo $userrecord[$i][0]['email_id'] ?></td>
                                    <td><?php echo @$type[$userrecord[$i][0]['authetication_type']]; ?></td>
                                    <td> 
                                        <?php
                                         $newid =   $userrecord[$i][0]['user_id'];
//                                        $newid = $this->requestAction(
//                                                array('controller' => 'Users', 'action' => 'encrypt', $user[$i]['User']['user_id'], $this->Session->read("salt"),
//                                        ));
                                        ?>
                                         <a href="<?php echo $this->webroot; ?>Users/new_user/<?php echo $newid; ?>" class="btn btn-success"><span class="fa fa-pencil"></span> </a>  
                                       <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_new_user', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
               
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
