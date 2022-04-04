<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divgrid').slideDown(1000);
        }
        else {
            $('#divgrid').hide();
        }
        $('#tablegrid').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(fee_type_id, payment_mode_id) {
        $('input:checkbox').removeAttr('checked');
        var values = payment_mode_id;
        $('#fee_type_id').val(fee_type_id);
        $("#conffeetpe").find('[value=' + values.join('], [value=') + ']').prop("checked", true);
        $('#hfid').val(fee_type_id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;

    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
</script>

<?php echo $this->Form->create('conf_feetype_payment_mode', array('id' => 'conf_feetype_payment_mode', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeeandpaymentmodelnk'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/fee/conf_feetype_payment_mode_<?php echo $language; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="fee_type_id " class="col-sm-2 control-label"><?php echo __('lblfeetype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('fee_type_id', array('label' => false, 'id' => 'fee_type_id', 'class' => 'form-control input-sm', 'options' => array($feetype), 'empty' => '--Select--')); ?>
                            <span id="fee_type_id_error" class="form-error"><?php //echo $errarr['fee_type_id_error'];  ?></span></div> 
                    </div>
                    <label for="payment_mode_id " class="col-sm-2 control-label"><?php echo __('lblpaymentmode'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-4">
                        <div class="conffeetpe" id="conffeetpe">
                            <?php //echo $this->Form->input('payment_mode_id', array('label' => false, 'id' => 'payment_mode_id', 'multiple' => 'checkbox', 'class' => 'form-control input-sm', 'options' => array($payment_mode), 'class' => 'confpay')); ?>
                              <?php echo $this->Form->input('payment_mode_id', array('label' => false, 'id' => 'payment_mode_id', 'multiple' => 'checkbox', 'class' => 'form-control input-sm', 'options' => array($payment_mode), 'class' => 'payment_mode_id')); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="payment_mode_id_error" class="form-error"><?php //echo $errarr['payment_mode_id_error'];  ?></span></div> 
                    </div>
                </div>
            </div>
        </div>
        <div  class="rowht"></div>
        <div  class="rowht"></div>
        <div class="row">
            <div class="form-group center">
                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="box box-primary">

    <div class="box-body" id="divgrid">

        <table id="tablegrid" class="table table-striped table-bordered table-hover">  
            <thead >  
                <tr>  
                    <th class="center"><?php echo __('lblfeetype'); ?></th>
                    <th class="center"><?php echo __('lblpaymentmode'); ?></th>
                    <th class="center width10"><?php echo __('lblaction'); ?></th>
                </tr>  
            </thead>

            <?php for ($i = 0; $i < count($grid); $i++) { ?>
                <tr>
                    <td><?php echo $grid[$i][0]['fee_type_desc_' . $language]; ?></td>
                    <td>
                        <?php
                        $pay_name = "";
                        $k = 1;
                        $ids = "";
                        for ($j = 0; $j < count($grid1); $j++) {
                            if ($grid[$i][0]['fee_type_id'] == $grid1[$j][0]['fee_type_id']) {
                                $pay_name.= " " . "$k ) " . $grid1[$j][0]['payment_mode_desc_' . $language] . "<br>";
                                $ids.="," . $grid1[$j][0]['payment_mode_id'];
                                $k++;
                            }
                        }
                        echo substr($pay_name, 1);
                        ?>
                    </td>
                    <td>
                        <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                        '<?php echo $grid[$i][0]['fee_type_id']; ?>',
                                <?php echo "[" . substr($ids, 1) . "]"; ?>)">
                            <span class="glyphicon glyphicon-pencil"></span></button>

                        <button id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $grid[$i][0]['fee_type_id']; ?>'));">
                            <span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
            <?php } ?>
        </table> 
        <?php if (!empty($grid)) { ?>
            <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
            <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

    </div>
</div>
</div>   
<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




