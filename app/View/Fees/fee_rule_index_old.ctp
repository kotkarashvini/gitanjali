<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<script>
    $(document).ready(function () {
//        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblFeeRule').dataTable({
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
//---------------------------------------------------------------------------------------Update Rule------------------------------------------------------------  
    function formupdate(rl_id) {
        $('#fee_rule_id').val(rl_id);
        $('#frmIndex').submit();
    }
//---------------------------------------------------------------------------------------Update Rule------------------------------------------------------------      
    function removeRule(rl_id, remove_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Rule ? ')) {
            if (confirm('Are You Sure Rule? Linked Item and Subrule will be deleted for this Rule ? ')) {
                status = $.ajax({
                    type: "POST",
                    url: host + 'removeFeeRule',
                    data: {rule_id: remove_id},
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
        alert('All Items,Subrules from Rule Id:"' + Base64.decode(copy_id) + '"  are Copied');
        return false;
    }
    //-------------------------------------------------------Paste Rule---------------------------------------------------------------
    function pasteRule(to_id) {
        if ($('#copy_rule_id').val() != '' && $('#copy_rule_id').val()) {
            if (confirm('Are You Sure? Do you want to copy all items,conditions and formula from Rule No. ' + Base64.decode($('#copy_rule_id').val()) + ' to ' + Base64.decode(to_id))) {

                $.post(host + "copyFeeRule", {from_id: $('#copy_rule_id').val(), to_id: to_id}, function (data) {
                    if (data == 1) {
                        alert('all formulas copied from ' + Base64.decode($('#copy_rule_id').val()) + ' to ' + Base64.decode(to_id));
                        $('#footer').show();
                    }
                    else if (data == 0) {
                        alert('Sorry! Error in Coping Rule');
                    }
                    else {
                        alert(data);
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
<?php
echo $this->Form->create('index', array('id' => 'frmFeeRuleIndex'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <div style="float: left;">
                    <?php echo $this->Form->button(__('lblNewRule'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
                </div>
               <h3 class="box-title" style="font-weight: bolder;padding-left:500px;padding-top:7px"><?php echo __('lblfeerule'); ?></h3>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Fee Rule/fee_rule_index_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <!-- Display Rule List -->  
            <div class="box-body">
                <table  id="tblFeeRule" class="table table-striped table-bordered table-hover" width="100%">
                    <thead >                        
                        <?php
                        echo "<tr>"
                        . "<td class='center width5'>" . __('lblsrno') . "</td>"
                        . "<td class='center width5'>" . __('lblid') . "</td>"
                        . "<td class='center'>" . __('lblReferenceNo') . "</td>"
                        . "<td class='center'>" . __('lblArticle') . "</td>"
                        . "<td class='center'>" . __('lblfeerule') . "</td>"
                        . "<td class='center  width10'>" . __('lblaction') . "</td>"
                        . "</tr>";
                        ?>                            
                    </thead>
                    <tbody>
                        <?php
                        $srno = 1;
                        foreach ($ruleList as $erd1) {
                            $erd = $erd1['article_fee_rule'];
                            echo "<tr id='" . $erd['fee_rule_id'] . "'>"
                            . "<td class='center tblbigdata'>" . $srno++ . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['fee_rule_id'] . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['reference_no'] . "</td>"
                            . "<td class='center tblbigdata'>" . $erd1['article']['article_desc_' . $lang] . "</td>"
                            . "<td class='center tblbigdata'>" . $erd['fee_rule_desc_' . $lang] . "</td>";
                            echo "<td class='center tblbigdata' width='15%'>"
                            . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('title' => 'Edit', 'class' => "", 'type' => 'submit', 'id' => $erd[$field[0]], 'onclick' => " formupdate('" . $erd['fee_rule_id'] . "');"))
                            . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('title' => 'Delete', 'class' => "", 'onclick' => "javascript: return removeRule(" . $erd['fee_rule_id'] . ",'" . base64_encode($erd['fee_rule_id']) . "')"))
                            . $this->Form->button('<i class = "fa fa-files-o"></i>', array('title' => 'Copy', 'class' => "copy", 'onclick' => "javascript: return copyRule('" . base64_encode($erd['fee_rule_id']) . "')"))
                            . $this->Form->button('<i class = "fa fa-clipboard"></i>', array('title' => 'Paste', 'class' => "", 'onclick' => "javascript: return pasteRule('" . base64_encode($erd['fee_rule_id']) . "')"))
                            . "</td>"
                            . "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot id="footer">
                        <tr><td colspan="5" class="text-danger danger"><?php echo __('lblplsrefpageupdaterule'); ?></td></tr>
                    </tfoot>
                </table>
                <input type="hidden" name="feeRuleId" id="fee_rule_id">
                <?php echo $this->Form->input('copy', array('id' => 'copy_rule_id', 'type' => 'hidden', 'readOnly' => 'true')); ?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?> 
