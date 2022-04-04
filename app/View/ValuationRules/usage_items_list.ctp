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
  
</script> 
<?php echo $this->Form->create('configlistitems', array('id' => 'unit', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('id', array('id' => 'id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'label' => false)); ?>

<div class="row">
    <div class="col-lg-12">
         <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
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
                            <span id="item_id_error" class="form-error"></span>
                        </div>
                        <label for="item_id" class="col-sm-1 control-label"><?php echo __('lblList') . ' ' . __('lblid'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-1">
                            <?php echo $this->Form->input('item_desc_id', array('id' => 'item_desc_id', 'class' => 'form-control input-sm', 'type' => 'text', 'label' => false, 'maxlength' => '5')); ?>
                            <span id="item_desc_id_error" class="form-error"></span>   
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
                                <span id="<?php echo 'item_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                     <?php if (isset($editflag)) { ?>
                        <button id="btnadd" name="btnadd" class="btn btn-info ">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                        </button>
                    <?php } else { ?>
                        <button id="btnadd" name="btnadd" class="btn btn-info ">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                        </button>
                    <?php } ?>
                    <a href="<?php echo $this->webroot; ?>ValuationRules/usage_items_list" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    
                    
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
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'usage_items_list', $configlistitems1['configlistitems']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Do you want to edit this item ?')); ?> 
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'remove_usage_item_list', $configlistitems1['configlistitems']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Do you want to delete this item ?')); ?> 
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