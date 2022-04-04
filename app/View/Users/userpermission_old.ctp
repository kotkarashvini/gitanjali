<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        //  $("#actiontype").val(2);
        function formSuccess() {
            alert('Success!');
        }

        function formFailure() {
            alert('Failure!');
        }
//        $("#new_user").validationEngine({
//            onFormSuccess: formSuccess,
//            onFormFailure: formFailure
//        });

//        $('#myTable').dataTable({
//            "iDisplayLength": 5,
//            "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
//        });

        $('#module_id').change(function () {
            $("#actiontype").val(2);
            $('#userpermission').submit();
        });

        $('#role_id').change(function () {
            document.getElementById("actiontype").value = '3';
            $('#userpermission').submit();
        });

        if (document.getElementById('hfhidden1').value === 'Y') {
            $('#divpermission').slideDown(1000);
        }
        else {
            $('#divpermission').hide();
        }
    });

    function formsubmit() {
        document.getElementById("actiontype").value = '1';
        $('#userpermission').submit();
    }
</script>

<?php echo $this->Form->create('userpermission', array('id' => 'userpermission', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbluserpermission'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="module_id" class="col-sm-3 control-label"><?php echo __('lblselectmodule'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('module_id', array('options' => array($module), 'empty' => '--select--', 'id' => 'module_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <!--<span id="module_id_error" class="form-error"><?php echo $errarr['module_id_error']; ?></span>-->
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="role_id" class="col-sm-3 control-label"><?php echo __('lblselectrole'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">

                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <!--<span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>-->
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row" id="divpermission" hidden="true">
                    <div class="col-lg-12">
                       
                        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <center><h3 class="box-title headbolder"><?php echo __('lblforms'); ?></h3></center>
                            </div>
                            <div class="box-body">
                                <div class="col-sm-3"></div>
                                <div class="row conffeetpe111">
                                    <div class="form-group" id="chkpermission">
                                        <label for="formlist" class="col-sm-4 control-label"><?php echo __('lblselectforms'); ?><span style="color: #ff0000">*</span></label> 
                                        <div class="col-sm-8" ><?php
                                            $ngprformlist_strtolower = array_map('strtolower', $ngprformlist);
                                            $ngprformlist_uppcase = array_map('ucwords', $ngprformlist_strtolower);
                                            echo $this->Form->input('formlist', array('label' => false,
                                                'type' => 'select',
                                                'options' => $ngprformlist_uppcase,
                                                'multiple' => 'checkbox',
                                                'selected' => $permissionarray,
                                                 'class' => 'confpay11'   
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                     <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
                                    <div class="col-sm-12 center">
                                        <button id="btnSave" name="btnSave" class="btn btn-info" onclick="javascript: return formsubmit();"><?php echo __('btnsave'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <?php if (!empty($ngprformlist)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

