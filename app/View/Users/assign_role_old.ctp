<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
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

        $('#myTable').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#module_id').change(function () {
            var module_id = $("#module_id option:selected").val();

            var i;
            $.getJSON("<?php echo $this->webroot; ?>Users/get_role", {module_id: module_id}, function (data)
            {

                var sc;
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#role_id option").remove();
                $("#role_id").append(sc);
            });
        });

        $('#cmdAdd').click(function () {
            var username = $('#username').val();
            var module_val = 1;
            var role_val = $('#role_id').val();

            if (role_val != null && module_val != '' && username != '') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>Users/saverole",
                    data: {'module_val': module_val, 'role_val': role_val.toString(), 'username': username},
                    success: function (data) {

                        alert(data);
                        return false;
                        var rege = /^[0-9]+$/;

                        if (data.match(rege))
                        {
                            return true;
                        }
                        else
                        {
                            alert('Not valid data');
                        }
                    }
                });
            }
            else {
                alert('Please enter username and select module/role');
                return false;
            }
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
            alert("Invalid password");
            return false;
        }

        if (Pass.length > 0 && Pass1.length > 0)
        {
            var SHA1Hash = hex_sha1(Pass);
            var SHA1Hash1 = hex_sha1(Pass1);
            document.getElementById("password").value = SHA1Hash;
            document.getElementById("r_password").value = SHA1Hash1;
        }
    }

    function formsubmit() {
        document.getElementById("actiontype").value = '1';
        $('#assign_role').submit();
    }
</script>

<?php echo $this->Form->create('assign_role', array('type' => 'file', 'id' => 'assign_role')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblassignroletouser'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="user_id" class="col-sm-3 control-label"><?php echo __('lblselectuser'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('user_id', array('options' => array($user_id), 'empty' => '--select--', 'id' => 'user_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="user_id_error" class="form-error"><?php echo $errarr['user_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="module_id" class="col-sm-3 control-label"><?php echo __('lblselectmodule'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('module_id', array('options' => array($module), 'empty' => '--select--', 'id' => 'module_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="module_id_error" class="form-error"><?php echo $errarr['module_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div> 
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="module_id" class="col-sm-3 control-label"><?php echo __('lblselectrole'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" id="ipDetailsCheck">
                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <input type='hidden' value='0' name='actiontype' class="btn btn-info" id='actiontype'/>
                        <input name="cmdSave" value="<?php echo __('btnsave'); ?>"  id="cmdSave" class="btn btn-info" type="submit">
                    </div>  
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover">
                        <thead >
                            <tr>
                                <th class="center"><?php echo __('lblid'); ?></th>
                                <th class="center"><?php echo __('lblusername'); ?></th>
                                <th class="center"><?php echo __('lblmodulename'); ?></th>
                                <th class="center"><?php echo __('lblrollid'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($user); $i++) { ?>
                                <tr>
                                    <td><?php echo $user[$i]['0']['id'] ?></td>
                                    <td><?php echo $user[$i]['0']['username'] ?></td>
                                    <td><?php echo $user[$i]['0']['module_name'] ?></td>
                                    <td><?php echo $user[$i]['0']['role_name'] ?></td>
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