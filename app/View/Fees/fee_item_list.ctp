<?php //------------------------------updated on 14-June-2017 by Shridhar-------------                                         ?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        $('#tablefeeitemlist').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });</script>

<script type="text/javascript">
    //--------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
    //--------------------------------------------------------------------
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }

    //---------------------------------------------------------------------------------------Remove  List Item ------------------------------------------------------------      
    function removeListItem(rw_id, remove_id) {
        var status = 1;
        if (confirm('Are you sure to Delete? ')) {
            status = $.ajax({
                type: "POST",
                url: host + 'removeFeeListItem',
                data: {remove_id: remove_id},
                async: false,
                success: function () {
//                        window.location.reload(true);
                }
            }).responseText;
            if (status == 0) {
                $('#' + rw_id).fadeOut(300);
            }
            else {
                alert(status);
            }
        }
        return false;
    }

    //------------------------------dyanamic function creation for collecting parameters in update function
    function formupdate(fee_item_id, list_item_value,display_order,
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'document_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>
    id) {
        var r = confirm("Are you sure to Edit?");
        if (r == true) {
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    ?>
            $('#fee_item_list_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(document_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

        $('#hfid').val(id);
        $('#list_item_value').val(list_item_value);
        $('#fee_item_id').val(fee_item_id);
        $('#display_order').val(display_order);
        $('#btnadd').html('Update');
                return false;
    }
    }
</script>  
<?php echo $this->Form->create('article_fee_item_list', array('id' => 'article_fee_item_list', 'autocomplete' => 'off')); ?>


<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeeitemlist'); ?></h3></center>
				<div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/fees/fee_item_list.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="fee_item_desc_en"  class="col-sm-3 control-label"><?php echo __('lblfeesitem'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('fee_item_id', array('options' => array($feeitemdata), 'empty' => '--select--', 'id' => 'fee_item_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="fee_item_id_error" class="form-error"><?php echo $errarr['fee_item_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row" >
                    <div class="form-group">
                        <label for="rule_desc_en" class="col-sm-3 control-label"><?php echo __('lblfeeitemlistdesc'); ?><span style="color: #ff0000">*</span></label>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $language1) {

                            if ($i % 6 == 0) {

                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('fee_item_list_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'fee_item_list_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'maxlength' => "100")) ?>
                                <span id="<?php echo 'fee_item_list_desc_' . $language1['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['fee_item_list_desc_' . $language1['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>

                            <?php
                            if ($i % 6 == 0) {
                                if ($i > 1) {
                                    echo "</div><br>";
                                }
                            }
                            $i++;
                        }
                        ?> 
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <label for="rule_desc_en" class="col-sm-3 control-label"><?php echo __('lblfeeitemvalue'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3"><?php echo $this->Form->input('list_item_value', array('label' => FALSE, 'id' => 'list_item_value', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                    <span id="list_item_value_error" class="form-error"><?php //echo $errarr['list_item_value_error']; ?></span>
                    
                    </div>
                </div>
                  <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="display_order" class="col-sm-3 control-label"><?php echo __('lblDisplayOrder'); ?></label>
                        <div class="col-sm-3"><?php echo $this->Form->input('display_order', array('label' => FALSE, 'id' => 'display_order', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                    <span id="display_order_error" class="form-error"><?php echo $errarr['display_order']; ?></span>
                    
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                <div class="row" style="text-align: center">
                    <div class="row center">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                                <a href="<?php echo $this->webroot; ?>Fees/fee_item_list" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'id' => 'csrftoken', 'value' => $this->Session->read("csrftoken")));
                            echo $this->Form->input('item', array('type' => 'hidden', 'id' => 'hfid'));
                            ?>
                        </div>
                    </div>  
                </div>
            </div>
        </div>    

        <div class="panel panel-primary">
            <div class="panel-body">
                <table id="tablefeeitemlist" class="table table-striped table-bordered table-hover">  
                    <thead style="background-color: rgb(204, 255, 229);">  
                        <tr>  
                            <!--<td style="text-align: center; width: 10%;"><?php echo __('lblstate'); ?></td>-->
                            <th class="center"><?php echo __('lblfeesitem'); ?></th>
                            <?php
//  creating dyanamic table header using same array of config language
                            foreach ($languagelist as $langcode) {
                                // pr($langcode);
                                ?>
                                <td style="text-align: center;"><?php echo __('lblfeeitemlistdesc') . "  " . $langcode['mainlanguage']['language_name']; ?></td>
                            <?php } ?>   

                            <td style="text-align: center; width: 10%;"><?php echo __('lblfeeitemvalue'); ?></td>
                            <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                        </tr>  
                    </thead>

                    <tbody>
                        <?php foreach ($feeitemlistrecord as $feeitemlistrecord1): ?>
                            <tr id="<?php echo $feeitemlistrecord1['article_fee_item_list']['fee_item_list_id']; ?>">
                                <td> <?php echo $feeitemlistrecord1['article_fee_items']['fee_item_desc_en']; ?></td>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td style="text-align: center;"><?php echo $feeitemlistrecord1['article_fee_item_list']['fee_item_list_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>    
                                <?php } ?>
                                <td><?php echo $feeitemlistrecord1['article_fee_item_list']['list_item_value']; ?></td>
                                <td style="text-align: center;">
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn-sm btn-success " style="text-align: center;"  onclick="javascript: return formupdate(('<?php echo $feeitemlistrecord1['article_fee_item_list']['fee_item_id']; ?>'), ('<?php echo $feeitemlistrecord1['article_fee_item_list']['list_item_value']; ?>'), ('<?php echo $feeitemlistrecord1['article_fee_item_list']['display_order']; ?>'),
                                    <?php
                                    //  creating dyanamic parameters  using same array of config language for sending to update function
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                                    ('<?php echo $feeitemlistrecord1['article_fee_item_list']['fee_item_list_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                ('<?php echo base64_encode($feeitemlistrecord1['article_fee_item_list']['fee_item_list_id']); ?>')
                                                        );">

                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <?php echo $this->Form->button('<span class="glyphicon glyphicon-remove" ></span>' , array('class' => 'btn-sm btn-danger'),array('title' => 'Delete', 'type' => 'button',  'onclick' => "javascript: return removeListItem(" . $feeitemlistrecord1['article_fee_item_list']['fee_item_list_id'] . ", '" . (base64_encode($feeitemlistrecord1['article_fee_item_list']['fee_item_list_id'])) . "')")); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($feeitemlistrecord1); ?>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>    
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>