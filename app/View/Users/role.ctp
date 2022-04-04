<script>
    $(document).ready(function () {
        $('#tablerole').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formupdate(role_id, role_name, module_name, valid_for_months, id) {
        document.getElementById("actiontype").value = '1';
        $('#role_id').val(role_id);
        $('#role_name').val(role_name);
        $('#module_id').val(module_name);
        $('#valid_for_months').val(valid_for_months);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '4';
            document.getElementById("hfid").value = id;
            $('#id1').val(id);
        } else {
            return false;
        }
    }
//    function formcancel() {
//        document.getElementById("actiontype").value = '3';
//
//    }
</script>
<?php echo $this->Form->create('role', array('id' => 'role', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder" ><?php echo __('lblrole'); ?></h3></center>
                 <!--<a  href="<?php echo $this->webroot;?>helpfiles/admin/role_<?php echo $laug; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo  __('help');?> <span class="fa fa-question fa-circle-o"></span></a>-->
                 <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot;?>helpfiles/admin/role_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="role_id" class="col-sm-2 control-label"><?php echo __('lblenterroleid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('role_id', array('label' => false, 'id' => 'role_id', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=> '8')) ?>
                            <span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>
                        </div>
                        <label for="role_name" class="col-sm-2 control-label"><?php echo __('lblrolename'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('role_name', array('label' => false, 'id' => 'role_name', 'class' => 'form-control input-sm', 'type' => 'text','maxlength' => '255')) ?>
                            <span id="role_name_error" class="form-error"><?php echo $errarr['role_name_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="module_id" class="col-sm-2 control-label"><?php echo __('lblselectmodule'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('module_id', array('label' => false, 'id' => 'module_id', 'class' => 'form-control input-sm', 'options' => array($module_id), 'empty' => '--Select--')); ?>
                            <span id="module_id_error" class="form-error"><?php echo $errarr['module_id_error']; ?></span>
                        </div>
                        <label for="valid_for_months" class="col-sm-2 control-label"><?php echo __('lblvalidateformonths'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('valid_for_months', array('label' => false, 'id' => 'valid_for_months', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="valid_for_months_error" class="form-error"><?php echo $errarr['valid_for_months_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info" onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button id="btncancel" name="btncancel" class="btn btn-info" type="reset">
                            <?php echo __('btncancel'); ?>
                        </button>
                    </div>
                </div>
            </div>
              <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectrole" class="table-responsive">
                    <table id="tablerole" class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>  
                            <tr>  
                                <th class="center"><?php echo __('lblrollid'); ?></th>
                                <th class="center"><?php echo __('lblrolename'); ?></th>
                                <th class="center"><?php echo __('lblselectmodule'); ?></th>
                                <th class="center"><?php echo __('lblvalidateformonths'); ?></th>
                                <!--<th class="center"><?php echo __('lblaction'); ?></th>-->
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($rolerecord as $rolerecord1): ?>
                                <tr>
                                    <td><?php echo $rolerecord1['0']['role_id']; ?></td>
                                    <td><?php echo $rolerecord1['0']['role_name']; ?></td>
                                    <td><?php echo $rolerecord1['0']['module_name']; ?></td>
                                    <td><?php echo $rolerecord1['0']['valid_for_months']; ?></td>
<!--                                    <td class="width10 center">
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default " onclick="javascript: return formupdate(
                                                        ('<?php echo $rolerecord1['0']['role_id']; ?>'),
                                                        ('<?php echo $rolerecord1['0']['role_name']; ?>'),
                                                        ('<?php echo $rolerecord1['0']['module_id']; ?>'),
                                                        ('<?php echo $rolerecord1['0']['valid_for_months']; ?>'),
                                                        ('<?php echo $rolerecord1['0']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_role', $rolerecord1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>-->
                                <?php endforeach; ?>
                                <?php unset($rolerecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($rolerecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>