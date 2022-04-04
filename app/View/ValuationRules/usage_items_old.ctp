<?php //------Created by Saddam,------ Updated on 05-June-2017 by Shridhar                                                                                ?>

<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        $('.divoutput,#single_unit_div,.unit').hide();
        $('#item_rate_flagY').click(function () {
            $('.divoutput').show();
        });
        $('#item_rate_flagN').click(function () {
            $('.divoutput').hide();
        });
        $('#usage_param_type_id').change(function () {
            if ($(this).val() == 1) {
                $('.inputItemRelated').show();
            }
            else {
                $('.inputItemRelated,.divoutput').hide();
            }
        });
        $('input:radio[name="data[itemlist][area_field_flag]"]').change(function () {
            areaFieldChange($(this).val());
        });
        $('input:radio[name="data[itemlist][single_unit_flag]"]').change(function () {
            singleUnitFlagChange($(this).val());
        });
        $('#tableitemlist').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
        });
    });</script>

<script>
    var host = "<?php echo $this->webroot; ?>";
    function areaFieldChange(areaFlag) {
        (areaFlag == 'Y') ? ($('#single_unit_div').show()) : ($('#single_unit_div').hide());
    }
    function singleUnitFlagChange(singleUnitFlag) {
        (singleUnitFlag == 'Y') ? ($('.unit').show()) : ($('.unit').hide());
    }
    function removeItem(remove_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Item ? ')) {
            if (confirm('Are You Sure Item and its list will be deleted ? ')) {
                status = $.ajax({
                    type: "POST",
                    url: host + 'removeUsageItem',
                    data: {remove_id: remove_id},
                    async: false,
                    success: function () {
                        //                        window.location.reload(true);
                    }
                }).responseText;
                if (status == 0) {
                    $('#' + Base64.decode(remove_id)).fadeOut(300);
                }
                else {
                    alert(status);
                }
            }
        }
        return false;
    }


    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'usage_param_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>id, usage_param_type_id, area_field_flag, singleUnitFlag, unitId, range_field_flag, is_list_field_flag, item_rate_flag, unit_cat_id, output_item_id, display_order) {
<?php
foreach ($languagelist as $langcode) {
    ?>

            $('#usage_param_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(usage_param_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>),
<?php } ?>
        $("html, body").animate({scrollTop: '150'}, "slow");
        $('#usage_param_type_id').val(usage_param_type_id);
        $('#display_order').val(display_order);
        $('#output_item_id').val(output_item_id);
        $('input:radio[name="data[itemlist][area_field_flag]"][value=' + area_field_flag + ']').prop('checked', true);
        if (area_field_flag == 'Y') {
            $('#single_unit_div').show();
            $('input:radio[name="data[itemlist][single_unit_flag]"][value=' + singleUnitFlag + ']').prop('checked', true);
            (singleUnitFlag == 'Y') ? ($('.unit').show(), $('#unit_id').val(unitId)) : ($('.unit').hide(), $('#unit_id').val(null));
        } else {
            $('#single_unit_div').hide();
            $('input:radio[name="data[itemlist][single_unit_flag]"][value="N"]').prop('checked', true);
            $('#unit_id').val(null);
        }
        $('input:radio[name="data[itemlist][range_field_flag]"][value=' + range_field_flag + ']').prop('checked', true);
        $('input:radio[name="data[itemlist][is_list_field_flag]"][value=' + is_list_field_flag + ']').prop('checked', true);
        $('input:radio[name="data[itemlist][item_rate_flag]"][value=' + item_rate_flag + ']').prop('checked', true);
        (item_rate_flag == 'Y') ? ($('.divoutput').show()) : ($('.divoutput').hide());
        $('#unit_cat_id').val(unit_cat_id);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        if (usage_param_type_id == 1) {
            $('.inputItemRelated').show();
        }
        else {
            $('.inputItemRelated,.divoutput').hide();
        }
        return false;
    }


</script> 
<?php echo $this->Form->create('itemlist', array('id' => 'itemlist', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblusageitemhead'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Item/usage_items_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row" id="selectitemlist">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblitemlistname') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('usage_param_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_param_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'usage_param_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['usage_param_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div> 
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-3" id="Select_Item_Type"><?php echo __('lblselectitemtype'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php 
                            $paramtype=array('1'=>__('lblinput'),'2'=>__('lbloutput'));
                            echo $this->Form->input('usage_param_type_id', array('options' => array($paramtype), 'empty' => '--Select Item Type--', 'id' => 'usage_param_type_id', 'class' => 'form-control input-sm', 'label' => false, 'required')); ?>
                            <span id="usage_param_type_id_error" class="form-error"><?php echo $errarr['usage_param_type_id_error']; ?></span>
                        </div>
                        <label class="col-md-3" id="Select_Item_Type"><?php echo __('lblDisplayOrder'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('display_order', array('id' => 'display_order', 'type' => 'text', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="display_order_error" class="form-error"><?php echo $errarr['display_order_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                 <div class="row inputItemRelated">
                    <div class="form-group">
                        <label for="range_field_flag" class="control-label col-sm-3"><?php echo __('lblisrangefield'); ?></label>            
                        <div class="col-sm-3"> <?php echo $this->Form->input('range_field_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'range_field_flag')); ?></div>                
                        <label for="is_list_field_flag" class="control-label col-sm-3"><?php echo __('lblis_list_field_flag'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_list_field_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_list_field_flag')); ?></div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <!--<div style="border:#5bc0de solid thin">-->

                <div class="row inputItemRelated">
                    <div class="form-group">
                        <label for="area_field_flag" class="control-label col-sm-3"><?php echo __('lblisareafield'); ?></label>            
                        <div class="col-sm-3"> <?php echo $this->Form->input('area_field_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'area_field_flag')); ?></div>  
                    </div>
                </div>                
                <div  class="rowht"></div>
                <?php //----------------------------------------------Single Unit Flag Div with Unit-- dated 28-June-2017---------------------------?>
                <div class="row" id="single_unit_div">
                    <div class="form-group">
                        <label for="single_unit_flag" class="control-label col-sm-3"><?php echo __('lblIsSingleUnit'); ?></label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('single_unit_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'single_unit_flag')); ?></div>  
                        <div class="col-sm-1"></div>
                        <label class="col-md-3 unit" id="Select_Unit"><?php echo __('lblunit'); ?></label>
                        <div class="col-sm-3 unit">
                            <?php echo $this->Form->input('unit_id', array('options' => $areaUnits, 'id' => 'unit_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="usage_param_type_id_error" class="form-error"><?php //echo $errarr['usage_param_type_id_error'];                           ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <!--</div>-->
                <div  class="rowht"></div>

               
                <div class="row inputItemRelated">
                    <div class="form-group">
                        <label for="is_list_field_flag" class="control-label col-sm-3"><?php echo __('lblisitemrate'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('item_rate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'item_rate_flag')); ?></div>
                        <div class="col-sm-1"></div>
                        <label for="is_list_field_flag" class="divoutput control-label col-sm-3"><?php echo __('lbloutputitem'); ?></label>            
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('output_item_id', array('label' => false, 'id' => 'output_item_id', 'class' => 'divoutput form-control input-sm', 'options' => $outputitem, 'empty' => '--Select Output Item--')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht inputItemRelated">&nbsp;</div>

                <div class="row">
                    <div class="form-group">
                        <label class="inputItemRelated col-md-3" id="Select_Item_Type"><?php echo __('lblunitcategory'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('unit_cat_id', array('options' => array($UnitCategory), 'empty' => '--select--', 'id' => 'unit_cat_id', 'class' => 'inputItemRelated form-control input-sm', 'label' => false)); ?>
                            <span id="unit_cat_id_error" class="form-error"><?php echo $errarr['unit_cat_id_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group tdselect">
                        <button id="btnadd" name="btnadd" class="btn btn-info">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover" id="tableitemlist">
                    <thead >  
                        <tr>
                            <th class="center"><?php echo __('lblsrno'); ?></th>
                            <th class="center"><?php echo __('lblid'); ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblitemlistname') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center"><?php echo __('lblitemtype'); ?></th>
                            <th class="center"><?php echo __('lblitemcode'); ?></th>
                            <th class="center"><?php echo __('lblDisplayOrder'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        if ($itemlistrecord) {
                            $srNo = 1;
                            ?>
                            <?php foreach ($itemlistrecord as $itemlistrecord1): ?>
                                <tr id='<?php echo $itemlistrecord1['itemlist']['usage_param_id']; ?>'>  
                                    <td><?php echo $srNo++; ?></td>
                                    <td><?php echo $itemlistrecord1['itemlist']['usage_param_id']; ?></td>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td class="tblbigdata"><?php echo $itemlistrecord1['itemlist']['usage_param_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td><?php echo $itemlistrecord1['paramtype']['usage_param_type_desc_' . $laug]; ?></td>
                                    <td><?php echo (($itemlistrecord1['itemlist']['usage_param_code'] && ($itemlistrecord1['itemlist']['usage_param_type_id'] == 1 || $itemlistrecord1['itemlist']['usage_param_type_id'] == 99)) ? $itemlistrecord1['itemlist']['usage_param_code'] : ' - '); ?></td>
                                    <td><?php echo (($itemlistrecord1['itemlist']['display_order']) ? $itemlistrecord1['itemlist']['display_order'] : ' - '); ?></td>
                                    <td>
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  
                                                onclick="javascript: return formupdate(
                                                <?php
                                                foreach ($languagelist as $langcode) {
                                                    ?>
                                                                        ('<?php echo $itemlistrecord1['itemlist']['usage_param_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                <?php } ?>
                                                                    ('<?php echo base64_encode($itemlistrecord1['itemlist']['usage_param_id']); ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['usage_param_type_id']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['area_field_flag']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['single_unit_flag']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['unit_id']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['range_field_flag']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['is_list_field_flag']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['item_rate_flag']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['unit_cat_id']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['output_item_id']; ?>'),
                                                                            ('<?php echo $itemlistrecord1['itemlist']['display_order']; ?>')
                                                                            );">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <?php echo $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('title' => 'Delete', 'type' => 'button', 'onclick' => "javascript: return removeItem('" . (base64_encode($itemlistrecord1['itemlist']['usage_param_id'])) . "')")); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php
                            unset($itemlistrecord1);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
