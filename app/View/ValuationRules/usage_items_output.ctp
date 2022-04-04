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
                <center><h3 class="box-title headbolder"><?php echo __('lblusageoutputitem'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Item/usage_items_output<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                  <div class="row" id="selectitemlist">
                    <div class="form-group">
                        
                            <div class="col-md-3">
                                <label><?php echo __('lbldisplayorder') ; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('display_order' , array('label' => false, 'id' => 'display_order', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'display_order_error'; ?>" class="form-error"> </span>
                            </div> 
                       
                    </div>
                </div> 
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

                    <a href="<?php echo $this->webroot; ?>ValuationRules/usage_items_output" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
                            <!--<th class="center"><?php echo __('lblitemcode'); ?></th>-->
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
                                    <!--<td><?php echo (($itemlistrecord1['itemlist']['usage_param_code'] && ($itemlistrecord1['itemlist']['usage_param_type_id'] == 1 || $itemlistrecord1['itemlist']['usage_param_type_id'] == 99)) ? $itemlistrecord1['itemlist']['usage_param_code'] : ' - '); ?></td>-->
                                    <td><?php echo (($itemlistrecord1['itemlist']['display_order']) ? $itemlistrecord1['itemlist']['display_order'] : ' - '); ?></td>
                                    <td>
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'usage_items_output', $itemlistrecord1['itemlist']['usage_param_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'output_usage_items_remove', $itemlistrecord1['itemlist']['usage_param_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?>

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
