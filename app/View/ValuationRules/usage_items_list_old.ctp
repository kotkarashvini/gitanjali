<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    $('#tableunit').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Save');
    }
    if ($('#hfhidden1').val() === 'Y')
    {

    }
    });</script>
<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'item_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id, item_desc_id, item_id) {
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#item_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(item_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>),<?php } ?>
    $("html, body").animate({scrollTop: '150'}, "slow");
            $('#item_desc_id').val(item_desc_id);
            $('#item_id').val(item_id);
            $('#hfupdateflag').val('Y');
            $('#hfid').val(id);
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('configlistitems', array('id' => 'unit', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblconfiglistitemsdescription'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Item Values/usage_items_list_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="item_id" class="col-sm-3 control-label"><?php echo __('lblusageitemhead'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('item_id', array('options' => array($listoptions), 'empty' => '--select Property Item --', 'id' => 'item_id', 'class' => 'form-control input-sm', 'label' => false, 'maxlength' => '20')); ?>
                            <span id="item_id_error" class="form-error"><?php echo $errarr['item_id_error']; ?></span>
                        </div>
                        <label for="item_id" class="col-sm-1 control-label"><?php echo __('lblList') . ' ' . __('lblid'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-1">
                            <?php echo $this->Form->input('item_desc_id', array('id' => 'item_desc_id', 'class' => 'form-control input-sm', 'type' => 'text', 'label' => false, 'maxlength' => '5')); ?>
                            <span id="item_desc_id_error" class="form-error"><?php echo $errarr['item_desc_id_error']; ?></span>   
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblitemlistname') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('item_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'item_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '200')) ?>
                                <span id="<?php echo 'item_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['item_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <table id="tableunit" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center width10"><?php echo __('lblusageitemhead'); ?></th>
                            <th class="center width10"><?php echo __('lblitemcode'); ?></th>
                            <td ><?php echo __('lblList') . ' ' . __('lblid'); ?></td>
                            <?php foreach ($languagelist as $langcode) { ?>
                                <th class="center"><?php echo __('lblitemlistname') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                            <?php } ?>

                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>                        
                        <?php foreach ($configlistitems as $configlistitems1): ?>
                            <tr>
                                <td class = 'center width5 tblbigdata'> <?php echo $configlistitems1['item']['usage_param_desc_' . $laug] ?></td>
                                <td class = 'center width5 tblbigdata'> <?php echo $configlistitems1['item']['usage_param_code'] ?></td>
                                <td class = 'center width5 tblbigdata'><?php echo $configlistitems1['configlistitems']['item_desc_id']; ?></td>
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <td class = 'center width5 tblbigdata'><?php echo $configlistitems1['configlistitems']['item_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                <td class = 'center width5 tblbigdata'>
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                    <?php foreach ($languagelist as $langcode) {
                                        ?>
                                                        ('<?php echo $configlistitems1['configlistitems']['item_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                    ('<?php echo $configlistitems1['configlistitems']['id']; ?>'),
                                                            ('<?php echo $configlistitems1['configlistitems']['item_desc_id']; ?>'),
                                                            ('<?php echo $configlistitems1['configlistitems']['item_id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <a> <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'remove_usage_list_item', base64_encode($configlistitems1['configlistitems']['id'])), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Do you want to delete this item ?')); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($configlistitems); ?>
                    </tbody>
                </table> 
                <?php if (!empty($unitrecord)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>