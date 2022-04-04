
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {

        $('#newuser').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        function formSuccess() {
            alert('Success!');
        }

        function formFailure() {
            alert('Failure!');
        }
        $("#new_user").validationEngine({
            onFormSuccess: formSuccess,
            onFormFailure: formFailure
        });

    });

    function EncryptSHA1() {
        var Pass = $("#password ").val();
        var Pass1 = $("#r_password ").val();

        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        if (!Pass.match(password) && !Pass1.match(password))
        {
            $("#password ").val('');
            $("#r_password ").val('');
            //  alert("Invalid password");
            //return false;
        }

        if (Pass.length > 0 && Pass1.length > 0)
        {
            var SHA1Hash = hex_sha1(Pass);
            var SHA1Hash1 = hex_sha1(Pass1);
//            alert(SHA1Hash);
            document.getElementById("password").value = SHA1Hash;
            document.getElementById("r_password").value = SHA1Hash1;
        }
    }
</script>

<?php echo $this->Form->create('new_user', array('type' => 'file', 'id' => 'new_user')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblnewuserreg'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="corp_coun_id" class="col-sm-3 control-label"><?php echo __('lbllocalgovbody'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('corp_coun_id', array('options' => array($corp_coun), 'empty' => '--select--', 'id' => 'corp_coun_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <?php
                            if (isset($this->request->data['new_user']['user_id'])) {
                                echo $this->Form->input('user_id', array('label' => false, 'id' => 'user_id', 'type' => 'hidden', 'class' => 'form-control input-sm', 'maxlength' => "20", 'value' => $this->request->data['new_user']['user_id']));
                            }
                            ?>
                            <span id="corp_coun_id_error" class="form-error"><?php echo $errarr['corp_coun_id_error']; ?></span>
                        </div>
                        <label for="type" class="col-sm-3 control-label"><?php echo __('lblauthtype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('authetication_type', array('options' => array($type), 'empty' => '--select--', 'id' => 'authetication_type', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="authetication_type_error" class="form-error"><?php echo $errarr['authetication_type_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label"><?php echo __('lblusername'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('username', array('label' => false, 'id' => 'username', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => "20")); ?>
                            <span id="username_error" class="form-error"><?php echo $errarr['username_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row">
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><?php echo __('lblpassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('password', array('label' => false, 'id' => 'password', 'type' => 'password', 'class' => 'form-control validate[required]', 'maxlength' => "15")); ?>
                            <span id="password_error" class="form-error"><?php echo $errarr['password_error']; ?></span>
                        </div>
                        <label for="r_password" class="col-sm-3 control-label"><?php echo __('lblrepassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('r_password', array('label' => false, 'id' => 'r_password', 'type' => 'password', 'class' => 'form-control validate[required ,equals[password]', 'maxlength' => "15")); ?>
                            <span id="r_password_error" class="form-error"><?php echo $errarr['r_password_error']; ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="employee_id" class="col-sm-3 control-label"><?php echo __('lblempid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('employee_id', array('label' => false, 'id' => 'employee_id', 'type' => 'text', 'class' => 'form-control validate[required,custom[integer]]')); ?>
                            <span id="employee_id_error" class="form-error"><?php echo $errarr['employee_id_error']; ?></span>
                        </div>
                        <label for="full_name" class="col-sm-3 control-label"><?php echo __('lblfullname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('full_name', array('label' => false, 'id' => 'full_name', 'type' => 'text', 'class' => 'form-control validate[required,custom[onlyLetterNumber]]')); ?>
                            <span id="full_name_error" class="form-error"><?php echo $errarr['full_name_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="mobile_no" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'text', 'class' => 'form-control', 'maxlength' => "10")); ?>
                            <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                        </div>
                        <label for="email_id" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'text', 'class' => 'form-control validate[required,custom[email]]')); ?>
                            <span id="email_id_error" class="form-error"><?php echo $errarr['email_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <a href="<?php echo $this->webroot; ?>Users/new_user"  class="btn btn-info"> </span><?php echo __('lblnewuser'); ?></a> 
                        <button id="cmdSubmit" type="submit"name="cmdSubmit" class="btn btn-info " onclick = "javascript: return EncryptSHA1();">
                            <span class="glyphicon "></span><?php
                            if (!isset($this->request->data['new_user']['user_id'])) {
                                echo __('btnsave');
                            } else {
                                echo __('lblbtnupdate');
                            }
                            ?>
                        </button>

                        <button id="cmdCancel" name="cmdCancel" class="btn btn-info "  type="reset" >
                            <span class="glyphicon "></span><?php echo __('btncancel'); ?>
                        </button> 


                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div class="table-responsive">
                    <table id="newuser" class="table table-striped table-bordered table-hover">
                        <thead >
                            <tr>
                                <th class="center"><?php echo __('lblempid'); ?></th>
                                <th class="center"><?php echo __('lblusername'); ?></th>
                                <th class="center"> <?php echo __('lblfullname'); ?></th>
                                <th class="center"><?php echo __('lblmobileno'); ?></th>
                                <th class="center"><?php echo __('lblemailid'); ?></th>
                                <!--<th class="center"><?php // echo __('lblofficeid'); ?></th>-->
                                <!--<th class="center"><?php //echo __('lblrolename'); ?></th>-->
                                <th class="center"><?php echo __('lblbiometric'); ?></th>
                                <th class="center"><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($user); $i++) { ?>
                                <tr>
                                    <td><?php echo $user[$i]['User']['employee_id'] ?></td>
                                    <td><?php echo $user[$i]['User']['username']; ?></td>
                                    <td><?php echo $user[$i]['User']['full_name'] ?></td>
                                    <td><?php echo $user[$i]['User']['mobile_no'] ?></td>
                                    <td><?php echo $user[$i]['User']['email_id'] ?></td>
                                    <!--<td><?php // echo $user[$i]['User']['office_id'] ?></td>-->
                                    <!--<td><?php //echo $role[$user[$i]['User']['role_id']] ?></td>-->
                                    <td><?php echo $type[$user[$i]['User']['authetication_type']]; ?></td>
                                    <td> 
                                        <a   class="btn btn-default" href="<?php echo $this->webroot; ?>Users/new_user/<?php echo $user[$i]['User']['user_id']; ?>"><?php echo __('lblbtnedit'); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
