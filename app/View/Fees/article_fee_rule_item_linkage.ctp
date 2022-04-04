<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<script>
    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblArticleFeeRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        //-------------------------------------------------------
        var host = "<?php echo $this->webroot; ?>";
        //---------------------------------------------------------
        $('#search_rule').keyup(function () {
            var valThis = $(this).val().toLowerCase();
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                var usagecatid = $(this).val();
                var label = $("label[for='usage_cat_id" + usagecatid + "']").html().toLowerCase();
                if (label.indexOf(valThis) > -1) {
                    //$(this).show();
                    //$("label[for='usage_cat_id" + usagecatid + "']").show();
                    $("label[for='usage_cat_id" + usagecatid + "']").parent('div').show();
                } else {
                    //$(this).hide();
                    //$("label[for='usage_cat_id" + usagecatid + "']").hide();
                    $("label[for='usage_cat_id" + usagecatid + "']").parent('div').hide();
                }
            });
        });
        //------------------------------------------------------------------------------------------------
        $("#btnReset").click(function () {
            $(":checkbox").attr("checked", false);
            $("#actionid").val("");
            $("#ruleid").val("");
            window.location = host + "Fees/article_feerule_item_linkage";
            return false;
        });
        //------------------------------------------------------------------------------------------------
        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
        //------------------------------------------------------------------------------------------------
        $("#btnSaveRule").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
        });

    });
    //------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
    //------------------------------------------------------------------------------------------------------------

    function removeItem(item_lnk_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Item ?')) {
            status = $.ajax({
                type: "POST",
                url: host + 'removeFeeRuleItem',
                data: {item_id: item_lnk_id},
                async: false,
                success: function () {
//                        window.location.reload(true);
                }
            }).responseText;
            if (status == 1) {
                $('#' + item_lnk_id).remove().fadeOut(300);
            }
            else {
                alert(status);
            }
        }
        location.reload();
    }
    //--------------------------------------------------------------------------------------------------------------
</script>

<?php
//pr($fieldname);
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="btn-arrow">
                            
                <a href="<?php echo $this->webroot; ?>Fees/fee_rule_index" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . ' ' . __('lblList'); ?></a>            
                <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . ' ' . __('lbllevelname'); ?></a>            
                <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule_item_linkage/<?php echo $this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo __('lblfeerule') . ' ' . __('lblItemLinkage'); ?></a>            
                  <a href="<?php echo $this->webroot; ?>Fees/linked_feeitems_config/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo "Item Link"; ?></a>  
                <a href="<?php echo $this->webroot; ?>Fees/article_fee_sub_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblsubrule') ?></a>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeerule') . " with " . __('lblItemLinkage'); ?></h3></center>
                 <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot;?>helpfiles/Fee Rule/article_fee_rule_item_linkage_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <!--<div style="border: 2px lightsalmon solid">-->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="rule_id_label" style="text-align: center;color: white; background-color: #E6B800"><?php echo $fee_rule; ?> </label>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>

                <div  class="rowht"></div>   <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="panel-title" style="float: right"><?php echo __('lblinputitem'); ?></li>

                                    <li class="box-tools pull-left"> 
                                        
                                        <div class="input-group"> 
                                            <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span> 
                                            <?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'brn btn-search')); ?>
                                        </div> 
                                    </li>
                                </ul>
                            </div>

                            <div  id="usage-list">
                                <?php echo $this->Form->input('fee_item_list', array('type' => 'select', 'options' => $inputItemlist, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => 'usage_cat_id')); ?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row center">
                    <div class="col-lg-12">
                        <div class="form-group">                               
                            <?php
                            echo $this->Form->button(__('btnsave'), array('id' => 'btnSaveRule', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
//                            echo $this->Form->button(__('lblNewRule'), array('id' => 'btnReset', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                            echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                            ?>
                        </div>
                        <div class="hidden Input">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                            echo $this->Form->input('rule_id', array('id' => 'ruleid', 'type' => 'hidden'));
                            echo $this->Form->input('frmaction', array('id' => 'actionid', 'type' => 'hidden'));
                            ?>
                        </div>
                    </div>
                </div>


                <!--</div>-->
            </div>

        </div>
<!--        <div class="box box-primary">
            <div class="box-header">
                <center><h3 class="box-title headbolder"><?php echo __('lblItemLinkage'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row ">
                    <div class="col-lg-12">
                        <div class="form-group">  
                            <div class="col-xs-2"></div>
                            <div class="col-xs-8">
                                <ul class="list-group">                                  
                                    <?php
//                                    if ($linkedInputs) {
//                                        foreach ($linkedInputs as $value) {
//                                            echo '<li class="list-group-items box list-group-item-info" style="list-style:none;" id=' . $value['conf_article_feerule_items']['article_rule_item_id'] . '>' . $value['item']['fee_param_code'] . ' : ' . $value['item']['fee_item_desc_' . $lang] . '<div class="pull-right"> <button title="Remove" class=" danger glyphicon glyphicon-trash" onClick="return removeItem(' . $value['conf_article_feerule_items']['article_rule_item_id'] . ')"></button></div>' . '</li>';
//                                        }
//                                    }
//                                    ?>
                                </ul>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>-->
    </div>
</div>



