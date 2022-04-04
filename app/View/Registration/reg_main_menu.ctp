<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
        
          $("#function_list").on("change",function(){
           
            if($("#function_list").val()!=''){
             $("#action").val($("#function_list").val());
             $("#controller").val('Registration');
         }else{
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

    function formupdate(mainmenu_desc_en, controller, submenuflag, action, display_order,id) {
         var r=confirm("Are you sure to edit");
         if(r==true){
        document.getElementById("actiontype").value = '2';
        // alert(submenuflag);
        $('#mainmenu_desc_en').val(mainmenu_desc_en);
        $('#mm_serial').val(display_order);
        $('#controller').val(controller);
        $('#submenuflag').val(submenuflag);
        $('#action').val(action);

        $('#hfupdateflag').val('Y');
        $('input:radio[name="data[reg_main_menu][submenuflag]"][value=' + submenuflag + ']').attr('checked', true);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        return false;
    }
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


<?php echo $this->Form->create('reg_main_menu', array('id' => 'reg_main_menu', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo __('lblregmainmenu'); ?></h3></center>
                 <div class="box-tools ">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/reg_main_menu.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                   
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblregmainmenu') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('mainmenu_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'mainmenu_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '200'))
                                ?>
                                <span id="<?php echo 'mainmenu_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>"  class="form-error"></span>
                                
                                
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
                                'documentindex' => 'Newly Submitted Documents',
                                'documentindex2' => 'CheckIn Documents'                                                          
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
                        
                        <label for="action" class="col-sm-3 control-label"><?php echo __('lblaction'); ?><span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('action', array('label' => false, 'id' => 'action', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                             <span id="action_error"  class="form-error"></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
<!--                        <label for="submenuflag" class="col-sm-3 control-label"><?php echo __('lblsubmenuflag'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php //echo $this->Form->input('submenuflag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'submenuflag')); ?>
                        </div>-->
                        <label for="mm_serial" class="col-sm-3 control-label"><?php echo __('lblDisplayOrder'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mm_serial', array('label' => false, 'id' => 'mm_serial', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="mm_serial_error"  class="form-error"></span>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                    <div class="row" style="text-align: center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <br>  <br>
                                <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" 
                                        onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?>
                                </button>
                                <a href="<?php echo $this->webroot; ?>Registration/reg_main_menu" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                                
                                <!--<button id="btnadd" name="btncancel" class="btn btn-info "  type="reset">-->
                                <!--<span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?></button>-->
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
                                    <td style="text-align: center;font-weight:bold; "><?php echo __('lblmainmenu'); ?></td>
                                    <td style="text-align: center;font-weight:bold; "><?php echo __('lblcontroller'); ?></td>
                                    <td style="text-align: center;font-weight:bold; "><?php echo __('lblaction'); ?></td>
                                    <td style="text-align: center;font-weight:bold; "><?php echo __('lblaction'); ?></td>
                                </tr>  
                            </thead>   
                            <tbody>
                                <?php foreach ($mainmenurecord as $mainmenurecord1): ?>
                                    <tr>

         <!--<td style="text-align: center;"><?php // echo $mainmenurecord1['0']['minor_desc'];   ?></td>-->
                                        <td style="text-align: center;"><?php echo $mainmenurecord1['RegistrationMainmenu']['mainmenu_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $mainmenurecord1['RegistrationMainmenu']['controller']; ?></td>
                                        <td style="text-align: center;"><?php echo $mainmenurecord1['RegistrationMainmenu']['action']; ?></td>
                                        <td style="text-align: center;">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-success " 
                                                    onclick="javascript: return formupdate('<?php echo $mainmenurecord1['RegistrationMainmenu']['mainmenu_desc_en']; ?>',
                                                                        '<?php echo $mainmenurecord1['RegistrationMainmenu']['controller']; ?>',
                                                                        '<?php echo $mainmenurecord1['RegistrationMainmenu']['submenuflag']; ?>',
                                                                        '<?php echo $mainmenurecord1['RegistrationMainmenu']['action']; ?>',
                                                                         '<?php echo $mainmenurecord1['RegistrationMainmenu']['mm_serial']; ?>',
                                                                        '<?php echo $mainmenurecord1['RegistrationMainmenu']['mainmenu_id']; ?>');">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'reg_main_menu_delete', $mainmenurecord1['RegistrationMainmenu']['mainmenu_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>       
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php unset($mainmenurecord1); ?>
                            </tbody>
                        </table>
                        <?php if (!empty($mainmenurecord)) { ?>
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




