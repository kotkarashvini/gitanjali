<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divinterface').slideDown(1000);
        }
        else {
            $('#divinterface').hide();
        }
        $('#tableinterface').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, intid, flddesc, fldtype, fldlen, inptype) {
            document.getElementById("actiontype").value = '1';
        $('#hfid').val(id);
        $('#interface_id').val(intid);
        $('#ext_interface_param_fld').val(flddesc);
        $('#ext_interface_param_fld_type').val(fldtype);
        $('#ext_interface_param_fld_length').val(fldlen);
        $('#ext_interface_param_inout_type').val(inptype);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script>

<?php echo $this->Form->create('extintfielddetails', array('id' => 'external_interface', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblextinterfielddetails'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/extintfielddetails_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="interface_id"class="col-sm-3 control-label"><?php echo __('lblinterfacename'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php // echo $this->Form->input('interface_id', array('label' => false, 'id' => 'interface_id', 'class' => 'form-control input-sm', 'options' => array($interface), 'empty' => '--Select--')); ?>
                            <?php echo $this->Form->input('interface_id', array('label' => false, 'id' => 'interface_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $interface))); ?>

<!--<span id="interface_id_error" class="form-error"><?php // echo $errarr['interface_id_error'];   ?></span>-->
                            <span id="interface_id_error" class="form-error"><?php echo $errarr['interface_id_error']; ?></span>
                        </div>
                        <label for="ext_interface_param_fld" class="col-sm-3 control-label"><?php echo __('lblinterfacefielddesc'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ext_interface_param_fld', array('label' => false, 'id' => 'ext_interface_param_fld', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="ext_interface_param_fld_error" class="form-error"><?php echo $errarr['ext_interface_param_fld_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="ext_interface_param_fld_type" class="col-sm-3 control-label"><?php echo __('lblinterfacefieldtype'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ext_interface_param_fld_type', array('label' => false, 'id' => 'ext_interface_param_fld_type', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="ext_interface_param_fld_type_error" class="form-error"><?php echo $errarr['ext_interface_param_fld_type_error']; ?></span>
                        </div>
                        <label for="ext_interface_param_fld_length" class="col-sm-3 control-label"><?php echo __('lblinterfacefieldlength'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ext_interface_param_fld_length', array('label' => false, 'id' => 'ext_interface_param_fld_length', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "8")) ?>
                            <span id="ext_interface_param_fld_length_error" class="form-error"><?php echo $errarr['ext_interface_param_fld_length_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="ext_interface_param_inout_type" class="col-sm-3 control-label"><?php echo __('lblinterfacefieldinput'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ext_interface_param_inout_type', array('label' => false, 'id' => 'ext_interface_param_inout_type', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="ext_interface_param_inout_type_error" class="form-error"><?php echo $errarr['ext_interface_param_inout_type_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button id="btnadd" type="submit"name="btnadd" class="btn btn-info "   onclick="javascript: return formadd();">
                                <span class="glyphicon"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                            <!--                            </div>
                                                        <div class="col-sm-1">-->
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary" id="divinterface">
            <div class="box-body">
                    <table id="tableinterface" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblinterfacename'); ?></th>
                                <th class="center"><?php echo __('lblinterfacefielddesc'); ?></th>
                                <th class="center"><?php echo __('lblinterfacefieldtype'); ?></th>
                                <th class="center"><?php echo __('lblinterfacefieldlength'); ?></th>
                                <th class="center"><?php echo __('lblinterfacefieldinput'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($interfacerecord); $i++) { ?>
                            <tr>
                                <td class="center"><?php echo $interfacerecord[$i][0]['interface_desc_en']; ?></td>
                                <td ><?php echo $interfacerecord[$i][0]['ext_interface_param_fld']; ?></td>
                                <td ><?php echo $interfacerecord[$i][0]['ext_interface_param_fld_type']; ?></td>
                                <td ><?php echo $interfacerecord[$i][0]['ext_interface_param_fld_length']; ?></td>
                                <td ><?php echo $interfacerecord[$i][0]['ext_interface_param_inout_type']; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $interfacerecord[$i][0]['id']; ?>'),
                                                    ('<?php echo $interfacerecord[$i][0]['interface_id']; ?>'),
                                                    ('<?php echo $interfacerecord[$i][0]['ext_interface_param_fld']; ?>'),
                                                    ('<?php echo $interfacerecord[$i][0]['ext_interface_param_fld_type']; ?>'),
                                                    ('<?php echo $interfacerecord[$i][0]['ext_interface_param_fld_length']; ?>'),
                                                    ('<?php echo $interfacerecord[$i][0]['ext_interface_param_inout_type']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_extintfielddetails', $interfacerecord[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($interfacerecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
</div>