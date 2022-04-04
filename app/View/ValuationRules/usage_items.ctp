<script>
    $(document).ready(function () {
        $('.AreaRelated').hide()
        $('.divoutput').hide()
        if ($('#fieldtype').val() == '1') {
            $('.AreaRelated').show();
        }

        if ($('#item_rate_flag').val() == 'Y') {
            $('.divoutput').show()
        }

        $('#item_rate_flag').change(function () {
            if ($('#item_rate_flag').val() == 'Y') {
                $('.divoutput').show();
            } else {
                $('.divoutput').hide();
            }

        });

        $('#fieldtype').change(function () {
            ($('#fieldtype').val() == '1') ? ($('.AreaRelated').show()) : ($('.AreaRelated').hide());
        });

        $('#tableitemlist').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
        });
    });</script>


<?php echo $this->Form->create('itemlist', array('id' => 'itemlist', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblusageinputitem'); ?></h3></center>
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
                                <label><?php echo __('lblusageitemname') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('usage_param_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_param_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'usage_param_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

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
                            $paramtype = array('1' => __('lblisareafield'), '2' => __('is_list_field_flag'), '3' => __('is_textbox_numeric'), '4' => __('is_textbox_string'));
                            // PR($paramtype);
                            echo $this->Form->input('fieldtype', array('options' => array($paramtype), 'empty' => '--Select Item Type--', 'id' => 'fieldtype', 'class' => 'form-control input-sm', 'label' => false));
                            ?>
                            <span id="usage_param_type_id_error" class="form-error"></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>

                <!--<div style="border:#5bc0de solid thin">-->

                <div  class="rowht"></div>
                <div class="row inputItemRelated textboxRelated AreaRelated">
                    <div class="form-group">
                        <label class="inputItemRelated  col-md-3" id="Select_Item_Type"><?php echo __('lblunitcategory'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3 ">
                            <?php echo $this->Form->input('unit_cat_id', array('options' => array($UnitCategory), 'empty' => '--select--', 'id' => 'unit_cat_id', 'class' => 'inputItemRelated form-control input-sm', 'label' => false)); ?>
                            <span id="unit_cat_id_error" class="form-error"></span>
                        </div>
                    </div>

                </div>
                <div  class="rowht"></div> 
                <div class="row inputItemRelated textboxRelated AreaRelated" >
                    <div class="form-group">
                        <label for="area_type_flag" class="control-label col-sm-3"><?php echo __('lblareatype'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php
                            $area_type_flag = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('area_type_flag', array('options' => array($area_type_flag), 'empty' => '--select--', 'id' => 'area_type_flag', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                            ?> 
                        </div>  
                        <div class="col-sm-1"></div> 
                    </div>
                </div>
                <?php //----------------------------------------------Single Unit Flag Div with Unit-- dated 28-June-2017---------------------------  ?>
                <div class="row inputItemRelated textboxRelated AreaRelated">
                    <div class="form-group">
                        <label for="single_unit_flag" class="control-label col-sm-3"><?php echo __('lblIsSingleUnit'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">

                            <?php
                            $single_unit_flag = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('single_unit_flag', array('options' => array($single_unit_flag), 'empty' => '--select--', 'id' => 'single_unit_flag', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                            ?>

                        </div>  
                        <div class="col-sm-1"></div> 
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row inputItemRelated textboxRelated">
                    <div class="form-group">
                        <label for="is_list_field_flag" class="control-label col-sm-3"><?php echo __('lblisitemrate'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-2"> 
                            <?php
                            $item_rate_flag = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('item_rate_flag', array('options' => array($item_rate_flag), 'default' => 'N', 'id' => 'item_rate_flag', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                            ?>  

                        </div>
                        <div class="col-sm-1"></div>
                        <label for="is_list_field_flag" class="divoutput control-label col-sm-3"><?php echo __('lbloutputitem'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-3"> 
                            <?php echo $this->Form->input('output_item_id', array('label' => false, 'id' => 'output_item_id', 'class' => 'divoutput form-control input-sm', 'options' => $outputitem, 'empty' => '--Select Output Item--')); ?>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div>

                <div class="row inputItemRelated  " >
                    <div class="form-group">
                        <label for="is_input_hidden" class="control-label col-sm-3"><?php echo __('lblis_input_hidden'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php
                            $is_input_hidden = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('is_input_hidden', array('options' => array($is_input_hidden), 'default' => 'N', 'id' => 'is_input_hidden', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                            ?>  
                        </div>  
                        <div class="col-sm-1"></div> 
                    </div>
                </div>
                <div  class="rowht"></div>

                <div  class="rowht inputItemRelated">&nbsp;</div>


                <div  class="rowht"></div><div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row center" >


                    <?php if (isset($editflag)) { ?>
                        <button id="btnadd" name="btnadd" class="btn btn-info ">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                        </button>
                    <?php } else { ?>
                        <button id="btnadd" name="btnadd" class="btn btn-info ">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                        </button>
                    <?php } ?>

                    <a href="<?php echo $this->webroot; ?>ValuationRules/usage_items" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

                    <?php echo $this->Form->input('hfid', array('label' => false, 'type' => 'hidden', 'id' => 'hfid')); ?>



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
                                <th class="center"><?php echo __('lblusageitemname') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center"><?php echo __('lblitemtype'); ?></th>
                            <th class="center"><?php echo __('lblitemcode'); ?></th>
                            <!--<th class="center"><?php echo __('lblDisplayOrder'); ?></th>-->
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
                                    <!--<td><?php echo (($itemlistrecord1['itemlist']['display_order']) ? $itemlistrecord1['itemlist']['display_order'] : ' - '); ?></td>-->
                                    <td>
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'usage_items', $itemlistrecord1['itemlist']['usage_param_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'input_usage_items_remove', $itemlistrecord1['itemlist']['usage_param_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?>

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
