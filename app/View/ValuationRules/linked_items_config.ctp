<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<style>th{border:1px solid black; text-align:center;} td{padding:50px;}</style>
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
echo $this->Form->create('frm', array('id' => 'frmid'));
echo $this->Form->input('csrftoken', array('type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
?>

<div class="row">
    <div class="col-lg-12">        
        <div class="box box-primary">
            <div class="box-header">
                <center><h3 class="box-title headbolder"><?php echo __('lblItemLinkage'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="rule_id_label" style="text-align: center;color: white; background-color: #E6B800"><?php echo $ruleid . " : " . $rulename; ?></label>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row ">
                    <div class="col-lg-12">
                        <div class="form-group">                              
                            <div class="col-xs-12">

                                <?php
                                if ($linkedInputs) {
//                                    echo $this->Form->create
                                    echo "<table width=100% class='table table-striped' border=1>";
                                    echo '<head>'
                                    . '<tr>'
                                    . '<th>' . __('lblfeesitem') . '</th>'
                                    . '<th>' . __('lblDisplayOrder') . '</th>'
                                    . '<th>' . __('lbliscompulsory') . '</th>'
                                    . '<th>' . __('lbldelitem') . '</th>'
                                    . '</tr>'
                                    . '</head>';
                                    foreach ($linkedInputs as $value) {
                                        echo "<tr class 'list-group-items box list-group-item-info' id=" . $value['usagelinkcategory']['usage_lnk_id'] . ">"
                                        . $this->Form->create($value['usagelinkcategory']['usage_lnk_id'], array('id' => 'frmid', 'class' => 'form-vertical'));
                                        echo "<td align=left>" . $value['item']['usage_param_code'] . ' : ' . $value['item']['usage_param_desc_' . $lang] . "</td>";
                                        echo '<td>' . $this->Form->input('display_order', array('type' => 'number', 'label' => FALSE, 'legend' => false, 'div' => false, 'value' => $value['usagelinkcategory']['display_order'], 'id' => 'display_order')) . "</td>";
                                        echo '<td>' . $this->Form->input('mandate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => $value['usagelinkcategory']['mandate_flag'], 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'mandate_flag')) . "</td>";
                                        echo '<td>' . '<div> <button title="Remove" class=" danger glyphicon glyphicon-trash" onClick="return removeItem(' . $value['usagelinkcategory']['usage_lnk_id'] . ')"></button></div>' . '</td>';
//                                        echo $this->Form->end();
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                }
                                ?>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 center">
                            <?php echo $this->Form->button(__('Submit'), array('id' => 'btnSaveRule','type'=>'submit','class' => 'btn btn-info'));?>
                            <!--<button type="submit" id="btnSaveRule">Submit</button>-->
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
