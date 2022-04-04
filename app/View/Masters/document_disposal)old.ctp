 
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag == 'Y')
    {
    $('#btnadd').html('Save');
    }

    if ($('#hfhidden1').val() == 'Y')
    {
    $('#tableDocument_disposal').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    });</script>

<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }

    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'disposal_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> disposal_id) {
    document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#disposal_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(disposal_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfupdateflag').val('Y');
            $('#hfid').val(disposal_id);
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('Document_disposal', array('id' => 'Document_disposal', 'autocomplete' => 'off')); ?>

<div class="row">

    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldisposalmethod'); ?></h3></center>
          <a  href="<?php echo $this->webroot;?>helpfiles/admin/Document_disposal_<?php echo $laug; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo  __('help');?> <span class="fa fa-question fa-circle-o"></span></a>                                                   
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbldisposalmethod') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('disposal_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'disposal_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '200')) ?>
                                <span id="<?php echo 'disposal_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['disposal_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                                <!--'required' =>'required'-->
                            </div>

                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="table-responsive">
                    <table id="tableDocument_disposal" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lbldisposalmethod') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($divisionrecord as $divisionrecord1): ?>
                                <tr>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $divisionrecord1['DocumentDisposal']['disposal_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  
                                                onclick="javascript: return formupdate(
                                                <?php foreach ($languagelist as $langcode) { ?>
                                                                    ('<?php echo $divisionrecord1['DocumentDisposal']['disposal_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                <?php } ?>
                                                                ('<?php echo $divisionrecord1['DocumentDisposal']['disposal_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'Document_disposal_delete', $divisionrecord1['DocumentDisposal']['disposal_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($divisionrecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($divisionrecord)) { ?>
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




