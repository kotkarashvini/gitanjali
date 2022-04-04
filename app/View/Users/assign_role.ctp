<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {

        $('#myTable').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#office_id').change(function () {
            var office_id = $("#office_id option:selected").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Users/get_officeuser", {office_id: office_id}, function (data)
            {
                var sc;
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#user_id option").remove();
                $("#user_id").append(sc);
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
                        } else
                        {
                            alert('Not valid data');
                        }
                    }
                });
            } else {
                alert('Please enter username and select module/role');
                return false;
            }
        });
    });

    function formsubmit() {
        document.getElementById("actiontype").value = '1';
        $('#assign_role').submit();
    }
</script>

<?php echo $this->Form->create('assign_role', array('type' => 'file', 'id' => 'assign_role')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblassignroletouser'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/assign_role_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="office_id" class="col-sm-3 control-label"><?php echo __('lbloffice'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('options' => $office, 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="user_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="user_id" class="col-sm-3 control-label"><?php echo __('lblselectuser'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('user_id', array('options' => $user_id, 'empty' => '--select--', 'id' => 'user_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="user_id_error" class="form-error"><?php echo $errarr['user_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div>


                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="role_id" class="col-sm-3 control-label"><?php echo __('lblselectrole'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" id="ipDetailsCheck">
                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <input type='hidden' value='0' name='actiontype' class="btn btn-info" id='actiontype'/>
                        <?php if (isset($editflag)) { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                        <?php } else { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                        <?php } ?>
                        <a href="<?php echo $this->webroot; ?>Users/assign_role" class="btn btn-info "><?php echo __('btncancel'); ?></a>


                    </div>  
                </div>
                <?php echo $this->Form->input('userroles_id', array('label' => false, 'id' => 'userroles_id', 'type' => 'text')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <table id="myTable" class="table table-striped table-bordered table-hover">
            <thead >
                <tr>
                    <th class="center"><?php echo __('lbloffice'); ?></th>
                    <th class="center"><?php echo __('lblusername'); ?></th>
                    <th class="center"><?php echo __('lblrollid'); ?></th>
                    <th class="center"><?php echo __('lblaction'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user as $user1):
                    ?>
                    <tr>
                        <td><?php echo $user1[0]['office_name_en'] ?></td>
                        <td><?php echo $user1[0]['username'] ?></td>
                        <td><?php echo $user1[0]['role_name_en'] ?></td>
                        <td>
                           <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'assign_role', $user1[0]['userroles_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>
                           <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_assign_role', $user1[0]['userroles_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>  
    </div>
</div>


