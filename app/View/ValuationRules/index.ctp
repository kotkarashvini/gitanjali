<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<script>
    $(document).ready(function () {
        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblEvalRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        $('#footer').hide();
        $('.copy').click(function () {
            $('.copy').css('background-color', '#FFF');
            $(this).css('background-color', '#206b67');
        });
    });
//---------------------------------------------------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
//--------------------------------------------------------Get Usage Sub,Sub_sub Category -------------------------------------------------------------------------------------------

    function getUsageList(usageMain, usageSub, listForId, forValue) {
        var forUsage = listForId.replace('add_', '');
        forUsage = forUsage.replace('cmp_', '');
        $.post(host + "get" + forUsage, {usage_main_catg_id: usageMain, usage_sub_catg_id: usageSub}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#" + listForId + " option").remove();
            $("#" + listForId).append(sc);
            if (forValue) {
                $("#" + listForId).val(forValue);
            }
        }, 'json');
    }

//---------------------------------------------------------------------------------------Update Rule------------------------------------------------------------  
    function formupdate(rl_id) {

        if (confirm('Do You Want to Edit this Rule ? ')) {
            $('#val_rule_id').val(rl_id);
            $('#frmIndex').submit();
        }

    }
//---------------------------------------------------------------------------------------Update Rule------------------------------------------------------------      
    function removeRule(rl_id) {
        var status = 1;
        if (confirm('Do You Want to Delete this Rule ? ')) {
            if (confirm('Are You Sure Rule? Item and Subrule will be deleted for this Rule ? ')) {
                status = $.ajax({
                    type: "POST",
                    url: host + 'removeValRule',
                    data: {rule_id: rl_id},
                    async: false,
                    success: function () {
//                        window.location.reload(true);
                    }
                }).responseText;
                if (status == 0) {
                    $('#' + rl_id).fadeOut(300);
                }
                else {
                    alert(status);
                }
            }
        }
        return false;
    }
    //-------------------------------------------------------Copy Rule---------------------------------------------------------------
    function copyRule(copy_id) {
        $('#copy_rule_id').val(copy_id);
        alert('All Items,Subrules from Rule Id:"' + copy_id + '"  are Copied');
        return false;
    }
    //-------------------------------------------------------Paste Rule---------------------------------------------------------------
    function pasteRule(to_id) {
        if ($('#copy_rule_id').val() != '') {

            if ($('#copy_rule_id').val() == to_id) {
                alert('Can not copy to same rule');
                return false;
            } else if (confirm('Are You Sure? Do you want to copy all items,conditions and formula from Rule No. ' + $('#copy_rule_id').val() + ' to ' + to_id)) {

                $.post(host + "copyValRule", {from_id: $('#copy_rule_id').val(), to_id: to_id}, function (data) {
                    if (data == 1) {
                        alert('all formulas copied from ' + $('#copy_rule_id').val() + ' to ' + to_id);
                        $('#footer').show();
                    }
                });
            }


        }
        else {
            alert('please First Copy Rule!');
        }
        return false;
    }
</script>
<?php echo $this->Form->create('index', array('id' => 'frmIndex')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <div style="float: left;">
                    <?php echo $this->Form->button(__('lblNewRule'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
                </div> 
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblevalrule'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/index_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <!-- Display Rule List -->  
            <div class="box-body">
                <table  id="tblEvalRule" class="table table-striped table-bordered table-hover" width="100%">
                    <thead>                        
                        <?php
                        echo "<tr>"
                        . "<td class='center width5'>" . __('lblsrno') . "</td>"
                        . "<td class='center width5'>" . __('lblid') . "</td>"
                        . "<td class='center'>" . __('lblevalrule') . "</td>"
                        . "<td class='center'>" . __('lbldisplayflag') . "</td>"
                        . "<td class='center'>" . __('lblReferenceNo') . "</td>"
                        . "<td class='center width10'>" . __('lblaction') . "</td>"
                        . "</tr>";
                        ?>                            
                    </thead>
                    <tbody>
                        <?php
                        $srno = 1;
                        foreach ($ruleList as $erd) {
                            $erd = $erd['evalrule'];
                            echo "<tr id='" . $erd['evalrule_id'] . "'>"
                            . "<td class='center tblbigdata'>" . $srno++ . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['evalrule_id'] . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['evalrule_desc_' . $lang] . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['display_flag'] . "</td>"
                            . "<td class='center tblbigdata'><span hidden=true>" . $erd['reference_no'] . "</span> <button type='button' data-toggle='modal'  class='btn btn-success'  data-target='#modal" . $erd['evalrule_id'] . "' > View</button></td>";

                            echo "<td class='center tblbigdata' width='15%'>"
                            . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('title' => 'Edit', 'class' => "", 'type' => 'submit', 'id' => $erd[$name[0]], 'onclick' => " formupdate('" . $erd['evalrule_id'] . "');"))
                            . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('title' => 'Delete', 'class' => "", 'onclick' => 'javascript: return removeRule(' . $erd['evalrule_id'] . ')'))
                            . $this->Form->button('<i class = "fa fa-files-o"></i>', array('title' => 'Copy', 'class' => "copy", 'onclick' => 'javascript: return copyRule(' . $erd['evalrule_id'] . ')'))
                            . $this->Form->button('<i class = "fa fa-clipboard"></i>', array('title' => 'Paste', 'class' => "", 'onclick' => 'javascript: return pasteRule(' . $erd['evalrule_id'] . ')'))
                            . "</td>"
                            . "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot id="footer">
                        <tr><td colspan="5" class="text-danger danger"><?php echo __('lblplsrefpageupdaterule'); ?></td></tr>
                    </tfoot>
                </table>
                <input type="hidden" name="val_rule_id" id="val_rule_id">
                <?php echo $this->Form->input('copy', array('id' => 'copy_rule_id', 'type' => 'hidden', 'readOnly' => 'true')); ?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?> 


<?php
foreach ($ruleList as $erd) {
    $erd = $erd['evalrule'];
    ?>

    <div class="modal fade" id="modal<?php echo $erd['evalrule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo __('lblReferenceNo'); ?></h5>

                </div>
                <div class="modal-body" style="min-width:350px">
                    <?php
                    echo nl2br($erd['reference_no']);
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

<?php } ?>