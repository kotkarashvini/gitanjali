
<script>
    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblArticleFeeRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        var host = "<?php echo $this->webroot; ?>";

        $("#btnReset").click(function () {
            $(":checkbox").attr("checked", false);
            $("#actionid").val("");
            $("#ruleid").val("");
            window.location = host + "Fees/article_feerule";
            return false;
        });

        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
        $("#btnSaveRule").click(function () {
            $("#frmid").submit();
        });
//---------------------------------------------------------------------//

        $('#search_rule').keyup(function () {
            var valThis = $(this).val().toLowerCase();
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                var item_id = $(this).val();
                var label = $("label[for='usage_param_id" + item_id + "']").html().toLowerCase();
                if (label.indexOf(valThis) > -1) {
                    $("label[for='usage_param_id" + item_id + "']").parent('div').show();
                } else {
                    $("label[for='usage_param_id" + item_id + "']").parent('div').hide();
                }
            });
        });

//---------------------------------------------------------\\

    });

//-----------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";


//--------------------------------remove Rule--Item---------------------------------------------------------------------
    function removeItem(item_lnk_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Item ?')) {
            status = $.ajax({
                type: "POST",
                url: host + 'removeRuleItem',
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
        return false;
    }
    //-----------------------------------------------------------------------------------------------------------------------------
</script>
<?php echo $this->element("Property/rule_menu"); ?>
<?php
//pr($fieldname);
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeerule') . " with " . __('lblItemLinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/rule_items_linkage_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="rule_id_label" style="text-align: center;color: white; background-color: #E6B800"><?php echo $ruleid . " : " . $rulename; ?></label>
                        </div>
                    </div>
                </div>
                <!--<div style="border: 2px lightsalmon solid">-->
                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">                           
                        <label for="Input Items" class="control-label col-sm-2" ><?php echo __('lbloutputitem'); ?></label> 
                        <div class="col-sm-6" style="height:25vh;overflow-y: scroll; border: 2px #00529B ridge;padding-left: 3%; "> 
                            <?php echo $this->Form->input('out_put_id', array('type' => 'select', 'options' => $outputitemlist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'dpendtattrlist')); ?>
                        </div> 
                    </div>
                </div>
                <div  class="rowht"></div>

                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">     
                        <div class="col-sm-12">
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

                                <div id="usage-list">
                                    <?php echo $this->Form->input('usage_param_id', array('type' => 'select', 'options' => $inputitemlist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'usage_param_id', 'class' => 'usage_cat_id')); ?>                                   
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row center">
                    <div class="col-lg-12">
                        <div class="form-group">                               
                            <?php
                            echo $this->Form->button(__('btnsave'), array('id' => 'btnSaveRule', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";

                            echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                            ?>
                        </div>                        
                        <div class="hidden Input">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                            echo $this->Form->input('rule_id', array('id' => 'ruleid', 'type' => 'hidden', 'value' => $this->Session->read("rule_id")));
                            echo $this->Form->input('frmaction', array('id' => 'actionid', 'type' => 'hidden'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>