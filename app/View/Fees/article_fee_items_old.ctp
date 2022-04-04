<?php //------------------------------updated on 04-July-2017 by Shridhar-------------                                                                   ?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<script type="text/javascript">
    $(document).ready(function () {
//--------------------------------------------------------------------------------------------------------------------------------------
        $('.min, .max,#rounding_div,.round_value,#list_flag_div,.headcode,#online_compultion_flag_div').hide();
        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divfees_items').slideDown(1000);
        }
        else {
            $('#divfees_items').hide();
        }
//--------------------------------------------------------------------------------------------------------------------------------------
        $('#tablefees_items').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
//--------------------------------------------------------------------------------------------------------------------------------------
        $("#fee_param_type_id").change(function () {
            param_type_change($(this).val(), '', '');
        });
//--------------------------------------------------------------------------------------------------------------------------------------
        $("input:radio[name='data[fees_items][fee_rounding_flag]']").change(function () {
            if ($(this).val() == 'Y') {
                $('.round_value').show();
            } else {
                $('.round_value').hide()
            }
        });

//-------------------------------------------------------------------------------------------------------------------------
        $('#btnadd').click(function () {
            if ($('#fees_items')[0].checkValidity()) {
                $('#fees_items').submit();
            }
            else {
                alert('fill all mandatory info');
                return false;
            }
        });
    });

    //------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
    //------------------------------------------------------------------------------------------------------------
    function round_change(round_flag) {
        if (round_flag == 'Y') {
            $('.round_value').show();
        }
        else {
            $('.round_value').hide();
        }
    }
    function param_type_change(param_type_id, min_value, max_value) {
        (param_type_id == 2 || param_type_id == 6) ? ($('.min,.max,#rounding_div,.headcode,.fee_type').show(), $('#list_flag_div').hide(), $('input:radio[name="data[fees_items][list_flag]"][value=N]').prop('checked', true)) : ($('.min,.max,#rounding_div,.headcode,.fee_type').hide(), $('#list_flag_div').show());
        (param_type_id == 2 || param_type_id == 6) ? ($('#online_compultion_flag_div').show()) : ($('#online_compultion_flag_div').hide());
        $('#min_value').val(min_value);
        $('#max_value').val(max_value);
    }
    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

//    function formupdate(id, fee_item_desc_en, fee_param_type_id, fee_type_id, fee_item_desc_ll, fee_item_desc_ll1, fee_item_desc_ll2, fee_item_desc_ll3, fee_item_desc_ll4) {
    function formupdate(id, fee_item_desc_en, fee_param_type_id, fee_type_id, o_compultion_flag, account_head_code, fee_item_desc_ll, fee_item_desc_ll1, fee_item_desc_ll2, fee_item_desc_ll3, fee_item_desc_ll4, min_value, max_value, round_flag, round_id, list_flag) {
        $('#hfid').val(id);
        $('#fee_item_desc_en').val(fee_item_desc_en);
        $('#fee_param_type_id').val(fee_param_type_id);
        $('#account_head_code').val(account_head_code);

        param_type_change(fee_param_type_id, min_value, max_value);
        $('#fee_type_id').val(fee_type_id);
        $('#fee_item_desc_ll').val(fee_item_desc_ll);
        $('#fee_item_desc_ll1').val(fee_item_desc_ll1);
        $('#fee_item_desc_ll2').val(fee_item_desc_ll2);
        $('#fee_item_desc_ll3').val(fee_item_desc_ll3);
        $('#fee_item_desc_ll4').val(fee_item_desc_ll4);
        $('#hfupdateflag').val('Y');
        //----------------------------------------------------------------
        (fee_param_type_id == 1 || fee_param_type_id == 5) ? ($('#list_flag_div').show()) : ($('#list_flag_div').hide());
        $('input:radio[name="data[fees_items][online_compultion_flag]"][value=' + o_compultion_flag + ']').prop('checked', true);
        $('input:radio[name="data[fees_items][list_flag]"][value=' + list_flag + ']').prop('checked', true);
        $('input:radio[name="data[fees_items][fee_rounding_flag]"][value=' + round_flag + ']').prop('checked', true);
        $('#rounding_id').val(round_id);
        round_change(round_flag);
        ///---------------------------------------------------------------
        $('#btnadd').html('Save');
        return false;
    }
    //---------------------------------------------------------------------------------------Remove  Article Fee Item ------------------------------------------------------------      
    function removeFeeItem(remove_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Item ? ')) {
            status = $.ajax({
                type: "POST",
                url: host + 'removeFeeItem',
                data: {remove_id: remove_id},
                async: false,
                success: function () {
//                        window.location.reload(true);
                }
            }).responseText;
            if (status == 0) {
                remove_id = Base64.decode(remove_id);
                $('#' + remove_id).fadeOut(300);
            }
            else {
                alert(status);
            }
        }
        return false;
    }
</script>

<?php echo $this->Form->create('fees_items', array('id' => 'fees_items', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeesitem'); ?></h3></center>
				<div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/fees/article_fee_items.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row" >
                    <div class="form-group">
                        <label for="fee_item_desc_en" class="col-sm-2 control-label"><?php echo __('lblfeesitem'); ?><span style="color: #ff0000">*</span></label>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $language1) {

                            if ($i % 6 == 0) {
                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('fee_item_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'fee_item_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'maxlength' => "100")) ?>
                                <span id="<?php echo 'fee_item_desc_' . $language1['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['fee_item_desc_' . $language1['mainlanguage']['language_code'] . '_error']; ?>
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
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="fee_param_type_id"  class="col-sm-2 control-label"><?php echo __('lblitemtype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('fee_param_type_id', array('label' => false, 'id' => 'fee_param_type_id', 'class' => 'form-control input-sm', 'options' => array($itemtype), 'empty' => '--Select--')); ?>
                            <span id="fee_param_type_id_error" class="form-error"><?php echo $errarr['fee_param_type_id_error']; ?></span>
                        </div>
                        <div class="row fee_type">
                            <label for="fee_type_id"  class="col-sm-2 fee_type control-label"><?php echo __('lblfeetype'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('fee_type_id', array('label' => false, 'id' => 'fee_type_id', 'class' => 'form-control input-sm fee_type', 'options' => $feetype)); ?>
                                <!--<span id="fee_param_type_id_error" class="form-error"><?php //echo $errarr['fee_param_type_id_error'];  ?></span>-->
                            </div>
                        </div>
                        <div class="row" id="online_compultion_flag_div">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="list flag" class="control-label col-sm-2"><?php echo __('lblOnlineCompultionflag'); ?></label>            
                                    <div class="col-sm-3"><?php echo $this->Form->input('online_compultion_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'online_compultion_flag')); ?></div> 
                                </div>
                            </div>
                        </div>

                        <div class="row" id="list_flag_div">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="list flag" class="control-label col-sm-2"><?php echo __('lbllistflag'); ?></label>            
                                    <div class="col-sm-3"><?php echo $this->Form->input('list_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'listflag')); ?></div> 
                                </div>
                            </div>
                        </div>

                        <label for="min Value"  class="min col-sm-2 control-label"><?php echo __('lblMinValue'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="min col-sm-2" >
                            <?php echo $this->Form->input('min_value', array('label' => false, 'id' => 'min_value', 'class' => 'form-control input-sm')); ?>
                            <span id="min_value_error" class="form-error"><?php //echo $errarr['min_value_error'];                                                                                                                                         ?></span>
                        </div>
                        <label for="max Value"  class="max col-sm-2 control-label"><?php echo __('lblmaxval'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="max col-sm-2">
                            <?php echo $this->Form->input('max_value', array('label' => false, 'id' => 'max_value', 'class' => 'form-control input-sm')); ?>
                            <span id="max_value_error" class="form-error"><?php //echo $errarr['max_value_error'];                                                                                                                                         ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row headcode">
                    <div class="form-group">
                        <label for="Account Head Code"  class="col-sm-2 control-label"> <?php echo __('lblAccountHeadCode'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="min col-sm-2" >
                            <?php echo $this->Form->input('account_head_code', array('label' => false, 'id' => 'account_head_code', 'class' => 'form-control input-sm')); ?>                                                                                                                               
                         <span id="account_head_code_error" class="form-error"><?php //echo $errarr['account_head_code_error'];    ?></span>
                        </div>
                    </div> 
                </div>

                <div  class="rowht"></div>
                <div class="row" id="rounding_div">
                    <div class="form-group">
                        <label for="fee_rounding_flag"  class="col-sm-2 control-label"><?php echo __('lblfeeroundingflag'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3"><?php echo $this->Form->input('fee_rounding_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'fee_rounding_flag')); ?></div>
                        <label for=""class="round_value col-sm-2 control-label"><?php echo __('lblrounding'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('rounding_id', array('label' => false, 'id' => 'rounding_id', 'class' => 'round_value form-control input-sm', 'options' => $rounding_list, 'empty' => '--Select--')); ?>
<!--                            <span id="fee_param_type_id_error" class="form-error"><?php echo $errarr['rounding_id']; ?></span>-->
                        </div>
                    </div>
                </div>               
            </div>
            <div  class="rowht"></div>
            <div class="row">
                <div class="form-group center">
                    <button id="btnadd" type="submit" name="btnadd" class="btn btn-info "    onclick="javascript: return formadd();">
                        <?php echo __('lblbtnAdd'); ?></button> &nbsp;&nbsp;
                    <button id="btnadd" name="btncancel" class="btn btn-info "    onclick="javascript: return forcancel();">
                        <?php echo __('btncancel'); ?></button>
                </div>
            </div>
        </div>




        <div class="box box-primary">

            <div class="box-body" id="divfees_items">
               
                    <table id="tablefees_items" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblfeesitem'); ?></th>
                                <th class="center"><?php echo __('lblitemtype'); ?></th>
                                <th class="center"><?php echo __('lblitemcode'); ?></th>
                                <th class="width10 center"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($fees_items); $i++) { ?>
                            <tr id="<?php echo $fees_items[$i][0]['fee_item_id']; ?>">
                                <td ><?php echo $fees_items[$i][0]['fee_item_desc_' . $laug]; ?></td>
                                <td ><?php echo $fees_items[$i][0]['usage_param_type_desc_' . $laug]; ?></td>
                                <td ><?php echo ($fees_items[$i][0]['fee_param_code']) ? $fees_items[$i][0]['fee_param_code'] : '-'; ?></td>
                                <!--<td ><?php echo $fees_items[$i][0]['fee_type_desc_' . $laug]; ?></td>-->
                                <td >
                                    <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $fees_items[$i][0]['fee_item_id']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_en']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_param_type_id']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_type_id']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['online_compultion_flag']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['account_head_code']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_ll']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_ll1']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_ll2']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_ll3']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_item_desc_ll4']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['min_value']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['max_value']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['fee_rounding_flag']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['rounding_id']; ?>'),
                                                    ('<?php echo $fees_items[$i][0]['list_flag']; ?>')
                                                    );">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <?php echo $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('title' => 'Delete', 'type' => 'button', 'onclick' => "javascript: return removeFeeItem('" . (base64_encode($fees_items[$i][0]['fee_item_id'])) . "')")); ?>

                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($fees_items)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
              
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <!--<input type='hidden' value='<?php //echo $hfactionval;                                       ?>' name='hfaction' id='hfaction'/>-->
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>