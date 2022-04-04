<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Save');
    }
    if ($('#hfhidden1').val() === 'Y')
    {
    $('#tablebehavioural').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tablebehavioural').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#payment_mode_desc_en').focus();
    }
    });</script>
<script>
    $(document).ready(function () {
        $('#tablebehavioural').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>  
<script>
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }

 

 
</script> 

<?php echo $this->Form->create('fee_item_type', array('id' => 'fee_item_type', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeeitemtype'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/fee/fee_item_type_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group" >
                         <div class="col-md-2 ">
                        <label for="usage_param_type_id" class=" control-label"><?php echo __('lblfeeitemid'); ?><span style="color: #ff0000">*</span></label>
                        <?php echo $this->Form->input("usage_param_type_id", array('label' => false, 'id' => 'usage_param_type_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '2')) ?>
                        <span id="village_code_error" class="form-error"></span>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblfeeitemtypename') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('usage_param_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_param_type_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '255'))
                                ?>
                                <span id="<?php echo 'usage_param_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['usage_param_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                         
                   
                      
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                      
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                
                
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
<!--                        <button id="btnadd" name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php //echo __('lblbtnAdd'); ?>
                        </button>-->
                         <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>
 <a href="<?php echo $this->webroot; ?>Fee/fee_item_type" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                 <th class="center"> <?php echo __('lblfeeitemid'); ?></th>
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"> <?php echo __('lblfeeitemtypename') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($feeitem as $feeitem1): ?>
                                <tr>
                                    <td ><?php echo $feeitem1['0']['usage_param_type_id']; ?></td>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $feeitem1['0']['usage_param_type_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td>
                                        <!--<a href="<?php echo $this->webroot; ?>Fee/fee_item_type/<?php echo $feeitem1['0']['id']; ?>" class="btn-sm btn-success"><span class="fa fa-pencil"></span> </a>-->    
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'fee_item_type', $feeitem1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_fee_item_type', $feeitem1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($feeitem1); ?>
                        </tbody>
                    </table>
                    <?php// if (!empty($payment_mode)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php// } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php// } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php //echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php //echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php// echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




