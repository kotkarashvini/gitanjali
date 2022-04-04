<script type="text/javascript">
    $(document).ready(function () {
         if ($('#hfhidden1').val() == 'Y')
    {
    $('#tableLanguage').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tableLanguage').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    });
    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
    function formupdate(id, state_name_en, language_name) {
        $('#hfid').val(id);
        $('#state_id').val(state_name_en);
        $('#language_id').val(language_name);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>
<?php echo $this->Form->create('config_language', array('id' => 'config_language')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbllangconfig'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ConfigLanguage/config_language_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="language_name" class="col-sm-2 control-label"><?php echo __('lblselectstate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $statename))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="state_id_error" class="form-error"><?php echo $errarr['state_id_error']; ?></span>
                        </div>
                        <label for="language_id" class="col-sm-2 control-label"><?php echo __('lblselectlang'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('language_id', array('label' => false, 'id' => 'language_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $language))); ?>
                            <span id="language_id_error" class="form-error"><?php echo $errarr['language_id_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button type="submit"  id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsave();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                        <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tableLanguage" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbladmstate'); ?></th>
                            <th class="center"><?php echo __('lbllangname'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach ($Config_language as $Config_language1): ?>
                                <td ><?php echo $Config_language1[0]['state_name_en']; ?></td>
                                <td ><?php echo $Config_language1[0]['language_name']; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $Config_language1[0]['id']; ?>'),
                                                    ('<?php echo $Config_language1[0]['state_id']; ?>'),
                                                    ('<?php echo $Config_language1[0]['language_id']; ?>')
                                                    );">
                                        <span class="glyphicon glyphicon-pencil"></span></button>
                                    <!--                                <button id="btndelete" name="btndelete" class="btn btn-default "  
                                                                            onclick="javascript: return formdelete(('<?php echo $Config_language1[0]['id']; ?>'));">
                                                                        <span class="glyphicon glyphicon-remove"></span></button>-->
                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'Masters', 'action' => 'encrypt', $Config_language1[0]['id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>

                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'config_language_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                    <?php unset($Config_language1); ?>
                </table> 
                 <?php if (!empty($Config_language)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
</div>