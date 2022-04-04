<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {
    $('#tablebank').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>

<script>
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formcancel() {
    document.getElementById("actiontype").value = '2';
    }

    function formupdate(mf_serial,
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'function_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>  id) {
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#function_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(function_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#mf_serial').val(mf_serial);
            $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
</script> 

<?php echo $this->Form->create('propertylist', array('id' => 'propertylist', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblminorfunlisthead'); ?></h3></center>
           <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/admin/propertylist_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <label for="mf_serial" class="col-sm-3 control-label"><?php echo __('lblminorfunction'); ?> Sequence:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mf_serial', array('label' => false, 'id' => 'mf_serial', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="mf_serial_error" class="form-error"><?php echo $errarr['mf_serial_error']; ?></span>
                        </div>
                    </div>
                </div>  
                <div  class="rowht"></div><div  class="rowht"></div>   <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblminorfunctiondescription') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('function_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'function_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '200'))
                                ?>
                                <span id="<?php echo 'function_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['function_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                 <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebank" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblminorfunctiondescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblmajourfunseq'); ?></th>    
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($propertylist as $propertylist1): ?>
                                <tr>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $propertylist1['minorfunction']['function_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td ><?php echo $propertylist1['minorfunction']['mf_serial']; ?></td>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $propertylist1['minorfunction']['mf_serial']; ?>'),
                                        <?php foreach ($languagelist as $langcode) { ?>
                                                        ('<?php echo $propertylist1['minorfunction']['function_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $propertylist1['minorfunction']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_propertylist', $propertylist1['minorfunction']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($propertylist1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($propertylist)) { ?>
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
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




