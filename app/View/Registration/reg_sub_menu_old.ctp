<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        $('#is_stampN').on("click", function () {
            $("#stampiddiv").hide();
        });
        $('#is_stampY').on("click", function () {
            $("#stampiddiv").show();
        });

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

    function formupdate(submenu_desc_en, mainmenu_id, sm_serial, is_stamp, stamp_id, id) {
        document.getElementById("actiontype").value = '2';
        // alert(submenuflag);
        $('#submenu_desc_en').val(submenu_desc_en);

//        $('#controller').val(controller);
        $('#mainmenu_id').val(mainmenu_id);
//        $('#action').val(action);
        $('#sm_serial').val(sm_serial);

        $('input:radio[name="data[reg_sub_menu][is_stamp]"][value=' + is_stamp + ']').attr('checked', true);
        $('#stamp_id').append($('<option>').val(stamp_id).text(stamp_id));
        $('#stamp_id').val(stamp_id);
        if (is_stamp === 'N') {
            $("#stampiddiv").hide();
        }else{
             $("#stampiddiv").show();
        }
        $('#hfupdateflag').val('Y');
        //alert(id);
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


<?php echo $this->Form->create('reg_sub_menu', array('id' => 'reg_sub_menu', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo __('lblregsubmenu'); ?></h3></center>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <label for="mainmenu_id" class="col-sm-3 control-label"><?php echo __('lblselectmainmenu'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mainmenu_id', array('options' => array($mainmenuid), 'empty' => '--select--', 'id' => 'mainmenu_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="mainmenu_id_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>  
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblregsubmenu') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('submenu_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'submenu_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '200'))
                                ?>
                                <span id="<?php echo 'submenu_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['submenu_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <!--                 <div class="row">
                                    <div class="form-group">
                                        <label for="controller" class="col-sm-3 control-label">Controller Name<span style="color: #ff0000">*</span></label>  
                                        <div class="col-sm-3">
                <?php //echo $this->Form->input('controller', array('label' => false, 'id' => 'controller', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        </div>
                                          <label for="action" class="col-sm-2 control-label">Action Name<span style="color: #ff0000">*</span></label>  
                                        <div class="col-sm-3">
                <?php // echo $this->Form->input('action', array('label' => false, 'id' => 'action', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        </div>
                                    </div>
                                </div>-->

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="sm_serial" class="col-sm-3 control-label"><?php echo __('lblDisplayOrder'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('sm_serial', array('label' => false, 'id' => 'sm_serial', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="sm_serial_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>


                <div class="row">
                    <div class="form-group">
                        <label for="controller" class="col-sm-3 control-label"><?php echo __('lblisstamp'); ?><span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('is_stamp', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select is_stamp', 'id' => 'is_stamp')); ?>
                        </div>
                    </div>
                    <div class="form-group" id="stampiddiv">
                        <label for="action" class="col-sm-2 control-label"><?php echo __('lblstampid'); ?><span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3">
                            <?php
                            $stampid = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10);
                            //  pr($functionid_used);
                            $stampid = array_diff_key($stampid, $stamp_id_used);

                            echo $this->Form->input('stamp_id', array('options' => array($stampid), 'label' => false, 'id' => 'stamp_id', 'class' => 'form-control input-sm'))
                            ?>

                            <?php //echo $this->Form->input('stamp_id', array('label' => false, 'id' => 'stamp_id', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" 
                                    onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                            </button>
                            <button id="btnadd" name="btncancel" class="btn btn-info " type="reset"  >
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?></button>
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
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblsubmenuname'); ?></td>
<!--                                     <td style="text-align: center;font-weight:bold; ">Controller Name</td>
                                  <td style="text-align: center;font-weight:bold; ">Action Name</td>-->
                                <td style="text-align: center;font-weight:bold; "><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>   
                        <tbody>
                            <?php foreach ($submenurecord as $submenurecord1): ?>
                                <tr>

                     <!--<td style="text-align: center;"><?php // echo $submenurecord1['0']['minor_desc'];      ?></td>-->
                                    <td style="text-align: center;"><?php echo $submenurecord1['RegistrationSubmenu']['submenu_desc_en']; ?></td>
    <!--                                     <td style="text-align: center;"><?php //echo $submenurecord1['RegistrationSubmenu']['controller'];      ?></td>
                                    <td style="text-align: center;"><?php //echo $submenurecord1['RegistrationSubmenu']['action'];      ?></td>-->
                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " 
                                                onclick="javascript: return formupdate('<?php echo $submenurecord1['RegistrationSubmenu']['submenu_desc_en']; ?>',
                                                                    //                                                             '<?php //echo $submenurecord1['RegistrationSubmenu']['controller']; ?>',
                                                                    '<?php echo $submenurecord1['RegistrationSubmenu']['mainmenu_id']; ?>',
                                                                    //                                                                  '<?php //echo $submenurecord1['RegistrationSubmenu']['action']; ?>',
                                                                    '<?php echo $submenurecord1['RegistrationSubmenu']['sm_serial']; ?>',
                                                                    '<?php echo $submenurecord1['RegistrationSubmenu']['is_stamp']; ?>',
                                                                    '<?php echo $submenurecord1['RegistrationSubmenu']['stamp_id']; ?>',
                                                                    '<?php echo $submenurecord1['RegistrationSubmenu']['submenu_id']; ?>');">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'reg_sub_menu_delete', $submenurecord1['RegistrationSubmenu']['submenu_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>       
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($submenurecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($submenurecord)) { ?>
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




