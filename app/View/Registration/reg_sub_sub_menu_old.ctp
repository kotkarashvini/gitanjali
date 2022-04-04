<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {

        $("#function_list").on("change", function () {

            if ($("#function_list").val() != '') {
                $("#action").val($("#function_list").val());
                $("#controller").val('Registration');
            } else {
                $("#action").val('');
                $("#controller").val('');
            }
        });


        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag == 'Y')
        {
            $('#btnadd').html('Save');
        }

        if ($('#hfhidden1').val() == 'Y')
        {
            $('#tableminorfunction').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tableminorfunction').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
    });


</script>
<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(subsubmenu_desc_en, controller, submenu_id, action, ssm_serial, role_id, function_sr_no, function_hierarchy,function_order, id) {
        document.getElementById("actiontype").value = '2';
        $('#subsubmenu_desc_en').val(subsubmenu_desc_en);
        $('#controller').val(controller);
        $('#submenu_id').val(submenu_id);
        $('#role_id').val(role_id);
        $('#action').val(action);
        $('#function_hierarchy').val(function_hierarchy);       
        $('input:checkbox').removeAttr('checked');
      //  $('.function_hierarchy').closest("input:checkbox").removeAttr('checked');
        var result = function_hierarchy.split('-');
        $.each(result, function( index, value ) {            
             $('#function_hierarchy'+value).prop('checked', true);
         });
        
        $('#function_sr_no').append($('<option>').val(function_sr_no).text(function_sr_no));
        $('#function_sr_no').val(function_sr_no);
        $('#function_order').val(function_order);
        $('#ssm_serial').val(ssm_serial);
        $('#hfupdateflag').val('Y');
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        return false;
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

</script> 


<?php echo $this->Form->create('reg_sub_sub_menu', array('id' => 'reg_sub_sub_menu', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo __('lblregsubsubmenu'); ?></h3></center>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><?php echo __('lblstamphierarchy'); ?></button>
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <label for="submenu_id" class="col-sm-3 control-label"> <?php echo __('lblselectsubmenu'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('submenu_id', array('options' => array($submenuid), 'empty' => '--select--', 'id' => 'submenu_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="submenu_id_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>  
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblregsubsubmenu') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('subsubmenu_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'subsubmenu_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '200'))
                                ?>
                                <span id="<?php echo 'subsubmenu_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['subsubmenu_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="controller" class="col-sm-3 control-label"><?php echo __('Map Registration Screen'); ?><span style="color: #ff0000"></span></label>  
                        <div class="col-sm-3">
                            <?php
                            $functionlist = array(
                                '' => '--Select--',
                                'document_checklist' => 'Document Check List',
                                'document_presentation' => 'Document Presentation',
                                'payment_verification' => 'Payment Verification',
                                'party' => 'Party Admission',
                                'document_identification' => 'Identifire Admission',
                                'document_witness' => 'Witness Admission',
                                'document_final' => 'Final Step Of Registration',
                                'document_upload' => 'Document Upload'
                            );
                            $functionlist = array_diff_key($functionlist, $functionlist_used);

                            echo $this->Form->input('function_list', array('options' => array($functionlist), 'label' => false, 'id' => 'function_list', 'class' => 'form-control input-sm'))
                            ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="controller" class="col-sm-3 control-label"><?php echo __('lblcontroller'); ?><span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('controller', array('label' => false, 'id' => 'controller', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="controller_error"  class="form-error"></span>
                        </div>
                        <label for="action" class="col-sm-2 control-label"><?php echo __('lblaction'); ?><span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('action', array('label' => false, 'id' => 'action', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                       <span id="action_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="ssm_serial" class="col-sm-3 control-label"><?php echo __('lblDisplayOrder'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ssm_serial', array('label' => false, 'id' => 'ssm_serial', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                      <span id="ssm_serial_error"  class="form-error"></span>
                        </div>
                    </div>
                    <div class="form-group">   

                        <label for="function_order" class="col-sm-2 control-label"><?php echo __('Function Serial Number'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('function_order', array('label' => false, 'id' => 'function_order', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="function_order_error"  class="form-error"></span>
                        </div>                        
                    </div>   
                    <div class="form-group">
                        <label for="role_id" class="col-sm-3 control-label"><?php echo __('lblrollid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('role_id', array('options' => array($roledata), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="role_id_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="ssm_serial" class="col-sm-3 control-label"><?php echo __('lblfunctionid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php
                            $functionid = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10);
                            //  pr($functionid_used);
                            $functionid = array_diff_key($functionid, $functionid_used);

                            echo $this->Form->input('function_sr_no', array('options' => array($functionid), 'label' => false, 'id' => 'function_sr_no', 'class' => 'form-control input-sm'))
                            ?>
                            <span id="function_sr_no_error"  class="form-error"></span>
                        </div>
                        
                        <label for="ssm_serial" class="col-sm-2 control-label"><?php echo __('lblworkflow'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-3">
                            <?php //echo $this->Form->input('function_hierarchy', array('label' => false, 'id' => 'function_hierarchy', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => 'ex:-1-2-3')) ?>
                            <?php echo $this->Form->input('function_hierarchy', array('type' => 'select', 'options' => $function_hierarchy, 'id' => 'function_hierarchy', 'multiple' => 'checkbox', 'label' => false, 'class' => ' function_hierarchy')); ?>


                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" 
                                    onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                            </button>
                            <button id="btnadd" name="btncancel" class="btn btn-info " type="reset">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tableminorfunction" class="table table-striped table-bordered table-hover">  
                        <thead style="background-color: rgb(204, 255, 229);">  
                            <tr>  
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblfunctionid'); ?></td>
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblsubsubmenuname'); ?></td>
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblcontroller'); ?></td>

                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblaction'); ?></td>
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblrolename'); ?></td>
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblworkflow'); ?></td>
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>   
                        <tbody>
                            <?php foreach ($subsubmenurecord as $subsubmenurecord1): ?>
                                <tr>


                                    <td style="text-align: center;"><?php echo $subsubmenurecord1['RegistrationSubsubmenu']['function_sr_no']; ?></td>
                                    <td style="text-align: center;"><?php echo $subsubmenurecord1['RegistrationSubsubmenu']['subsubmenu_desc_en']; ?></td>
                                    <td style="text-align: center;"><?php echo $subsubmenurecord1['RegistrationSubsubmenu']['controller']; ?></td>
                                    <td style="text-align: center;"><?php echo $subsubmenurecord1['RegistrationSubsubmenu']['action']; ?></td>
                                    <td style="text-align: center;"><?php echo $roledata[$subsubmenurecord1['RegistrationSubsubmenu']['role_id']]; ?></td>
                                    <td style="text-align: center;"><?php echo $subsubmenurecord1['RegistrationSubsubmenu']['function_hierarchy']; ?></td>
                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " 
                                                onclick="javascript: return formupdate('<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['subsubmenu_desc_en']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['controller']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['submenu_id']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['action']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['ssm_serial']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['role_id']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['function_sr_no']; ?>',
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['function_hierarchy']; ?>',
                                                                     '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['function_order']; ?>',                                                                    
                                                                    '<?php echo $subsubmenurecord1['RegistrationSubsubmenu']['subsubmenu_id']; ?>');">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'reg_sub_sub_menu_delete', $subsubmenurecord1['RegistrationSubsubmenu']['subsubmenu_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>       
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($subsubmenurecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($subsubmenurecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?> 
                </div>
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblstamphierarchy'); ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __('lblstamps'); ?></th>
                            <th><?php echo __('lblfunctions'); ?></th>
                            <th><?php echo __('lblflags'); ?></th>
                            <th><?php echo __('lblworkflow'); ?></th>
                            <th><?php echo __('lblrolename'); ?></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        $notice = "";
                        foreach ($stamp_conf as $stamp) {   //pr($stamp);
                            ?>
                            <tr class="bg-success">
                                <th><?php echo $stamp['stamp_desc']; ?></th>
                                <th></th>
                                <th><?php //echo $stamp['stamp_flag'];    ?></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php
                            if (isset($stamp['functions'])) {
                                foreach ($stamp['functions'] as $function) {
                                    ?>

                                    <tr>
                                        <th><?php echo $function['function_sr_no']; ?></th>
                                        <th><?php echo $function['function_desc']; ?></th>
                                        <th><?php //echo $function['function_flag'];    ?></th>
                                        <th><?php echo $function['work_flow']; ?></th>
                                        <th><?php echo $function['role']; ?></th>
                                    </tr>

                                    <?php
                                }
                            } else {
                                $notice .= "<br>Functions Not Avalable for " . $stamp['stamp_desc'];
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <span class="form-error"> <?php
                    if (!empty($notice)) {
                        echo "Errors : " . $notice;
                    }
                    ?></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>
