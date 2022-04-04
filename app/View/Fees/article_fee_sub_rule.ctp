
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/></noscript>

<script>
    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblEvalRule,tblEvalSubRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        $('.copy').click(function () {
            $('.copy').css('background-color', '#FFF');
            $(this).css('background-color', '#206b67');
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        //------------------------------------------------------------------------------------------------------
        var host = "<?php echo $this->webroot; ?>";
        //------------------------------------------------------------------------------------------------------
        $("#usageRow,#maxParaRow,#locationRow,#sub_rule_desc_row,#footer").hide();
        $("#btnSaveSubRule").attr('disabled', true);

//---------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'usage_sub_catg_id', '');
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#usage_sub_catg_id").change(function () {
            getUsageList($("#usage_main_catg_id").val(), $(this).val(), 'usage_sub_sub_catg_id', '');
        });
        $("#fee_rule_cond1,#fee_rule_formula1,#fee_rule_cond2,#fee_rule_formula2,#fee_rule_cond3,#fee_rule_formula3,#fee_rule_cond4,#fee_rule_formula4,#fee_rule_cond5,#fee_rule_formula5,#maxformula").val('');
        var txtfield = '';
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $('input.cndpr').focus(function () {
            txtfield = $(this).attr('id');
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $('input:radio[name="data[frm][max_value_condition_flag]"]').change(function () {
            ($(this).val() == 'Y') ? $("#maxParaRow").show() : $("#maxParaRow").hide();
        });

//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#maxvalueparameterlist,#operatorsignmaxorid").change(function () {
            var cvalue = $("#maxformula").val();
            $("#maxformula").val(cvalue.trim() + $(this).val().trim());
            $("#maxvalueparameterlist,#operatorsignmaxorid").val('');
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#parameter_id,#operator_sign").change(function () {
            if ($('#fee_rule_formula1').val() == '') {
                txtfield = 'fee_rule_formula1';
            } else if ($('#' + txtfield).attr('data-cndpr') == 'Y') {
                txtfield = txtfield;
            } else {
                alert('click in conditions or formula inputbox');
            }
            $("#" + txtfield).val($("#" + txtfield).val().trim());
            $("#" + txtfield).val($("#" + txtfield).val() + $(this).val());
            $("#parameter_id,#operator_sign").val('');
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#landtype_id").change(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
            if ($(this).val() == 1) {
                $(".ulb_type").show();
                $(".corp_id").show();
            } else {
                $(".ulb_type").hide();
                $("#ulb_type_id").val("");
                $(".corp_id").hide();
                $("#corp_id").val("");
            }
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#btnReset").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
            $(":checkbox").attr("checked", false);
            $("#actionid").val("");
            $("##feeRule_id").val("");
            $("#hdnsubruleid").val("");
            $('#subrulelistdiv').html("");
            window.location = host + "Fees/feesrule";
            return false;
        });

//---------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//        $("#btnSave").click(function () {
//            $(':input').each(function () {
//                $(this).val($.trim($(this).val()))
//            });
//            $.ajax(
//                    {
//                        type: 'post',
//                        url: host + 'saveFeeSubRule',
//                        data: $("#frmid").serialize(),
//                        success: function (result)
//                        {
//                            if (result == 1) {
//                                alert("Subrule Saved SuccessFully");
//                                $("#hsrflg").val('N');
//                                $("#actionid").val('U');
//                                $("#btnSave").attr('disabled', false);
//                                $("#btnSaveSubRule").attr('disabled', true);
//                            } else
//                                alert(result);
//                        }
//                    });
//            getSubruleList($("#feeRule_id option:selected").val());
//            return false;
//        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
    });
//------*-----------$------------------*------------$----------------*----------------*----------------$---------------*------------$--------------$------------------    
    var host = "<?php echo $this->webroot; ?>";
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    function getUsageList(from1Id, from2Id, forId, forValue) {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        $.post(host + "get" + forId, {usage_main_catg_id: from1Id, usage_sub_catg_id: from2Id}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#" + forId + " option").remove();
            $("#" + forId).append(sc);
            if (forValue) {
                $("#" + forId).val(forValue);
            }
        }, 'json');
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    function setSubRuleData(sbrid, opt_flag, max_flag, out_id, order_id, min, max, ulb_type_id, f1, c1, sub_desc, calc_desc, c2, f2, c3, f3, c4, f4, c5, f5, max_formula) {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        $("#flashMessage").html("");
        $('#subrule_id').html('Subrule Id :- ' + sbrid);
        $('input:radio[name="data[frm][optional_flag]"][value=' + opt_flag + ']').prop('checked', true);
        $("html, body").animate({scrollTop: '150'}, "slow");
        $("#hdnsubruleid").val(sbrid);
//        $("#sub_rule_desc").val(sub_desc);
//        $("#fee_cal_desc").val(calc_desc);
//        $("#fee_rule_cond1").val(c1);
//        $("#fee_rule_formula1").val(f1);
//        $("#fee_rule_cond2").val(c2);
//        $("#fee_rule_formula2").val(f2);
//        $("#fee_rule_cond3").val(c3);
//        $("#fee_rule_formula3").val(f3);
//        $("#fee_rule_cond4").val(c4);
//        $("#fee_rule_formula4").val(f4);
//        $("#fee_rule_cond5").val(c5);
//        $("#fee_rule_formula5").val(f5);
//        $("#outputitemid").val(out_id);
//        $('#min_value').val(min);
//        $('#max_value').val(max);
  $("#fee_subrule_desc").val(sub_desc);
        $("#fee_calucation_desc").val(calc_desc);
        $("#fee_rule_cond1").val(c1);
        $("#fee_rule_formula1").val(f1);
        $("#fee_rule_cond2").val(c2);
        $("#fee_rule_formula2").val(f2);
        $("#fee_rule_cond3").val(c3);
        $("#fee_rule_formula3").val(f3);
        $("#fee_rule_cond4").val(c4);
        $("#fee_rule_formula4").val(f4);
        $("#fee_rule_cond5").val(c5);
        $("#fee_rule_formula5").val(f5);
        $("#fee_output_item_id").val(out_id);
        $('#min_value').val(min);
        $('#max_value').val(max);
        if (ulb_type_id != 0) {
            $("#local_gov_body").val(ulb_type_id);
        } else {
            $("#local_gov_body").val('');
        }

        $("#out_item_order").val(order_id);
        $('input:radio[name="data[frm][max_value_condition_flag]"][value=' + max_flag + ']').attr('checked', true);
        if (max_flag === 'Y') {
            $("#maxParaRow").show();
            $("#maxformula").val(max_formula);
        } else {
            $("#maxParaRow").hide();
            $("#maxformula").val('');
        }
        return false;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    function removeSubRule(rid, rsid) {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        var conf = confirm('Are You Sure to delete this subrule');
        if (!conf) {
            return false;
        } else {
            $.ajax({
                type: 'post',
                url: host + 'removeFeeSubRule',
                data: {fee_rule_id: rid, fee_sub_rule_id: rsid},
                success: function (result)
                {
                    if (result == 1) {
                        $("#subrule_" + rsid).fadeOut(300);
                    } else
                        alert(result);
                }
            });

            return false;
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    //-------------------------------------------------------Copy Rule---------------------------------------------------------------
    function copySubRule(copy_id) {
        $('#copy_sub_rule_id').val(copy_id);
        alert('All Items,Subrules from Rule Id:"' + copy_id + '"  are Copied');
        return false;
    }
    //-------------------------------------------------------Paste Rule---------------------------------------------------------------
    function pasteSubRule(toSubruleId) {
        if ($('#copy_sub_rule_id').val() != '' && $('#copy_sub_rule_id').val()) {
            if (confirm('Are You Sure? Do you want to copy all items,conditions and formula from Rule No. ' + $('#copy_sub_rule_id').val() + ' to ' + toSubruleId)) {

                $.post(host + "copyFeeSubRule", {from_subrule_id: $('#copy_sub_rule_id').val(), to_subrule_id: toSubruleId}, function (data) {
                    if (data == 1) {
                        alert('all formulas copied from ' + $('#copy_sub_rule_id').val() + ' to ' + toSubruleId);
                        $('#footer').show();
                    } else {
                        alert('Sorry! Error in Coping Rule');
                    }
                });
            }
        } else {
            alert('please First Copy Rule!');
        }
        return false;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
</script>

<?php
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?>


<div class="row">
    <div class="col-md-12">
        <div class="btn-arrow">

            <a href="<?php echo $this->webroot; ?>Fees/fee_rule_index" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . __('lblList'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . __('lbllevelname'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule_item_linkage/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . __('lblItemLinkage'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/linked_feeitems_config/<?php echo $this->Session->read('csrftoken'); ?>"  class="btn btn-success btn-arrow-right"><?php echo "Item Link"; ?></a>  
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_sub_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo __('lblsubrule') ?></a>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <label class="box-title headbolder" id="rule_id_label" style="text-align: center; background-color: #E6B800"><?php echo $fee_rule; ?> </label>
                <center><h3 class="box-title headbolder"><?php echo __('lblsubrule'); ?></h3></center>  
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Fee Rule/article_fee_sub_rule_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div  class="rowht"></div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="subrule_id" style="text-align: center;color: white; background-color: #E6B800"></label>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2"><?php echo __('subruleDesc'); ?></label>                            
                        <div class="col-sm-10" ><?php echo $this->Form->input($fields[2], array('id' => 'fee_subrule_desc', 'type' => 'text', 'placeholder' => 'Sub Rule Description', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="fee_subrule_desc_error" class="form-error"><?php //echo $errarr['fee_subrule_desc_error']; ?></span>
                        </div>                            
                    </div>
                </div> 
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2"><?php echo __('FeeCalculationDesc'); ?></label>                            
                        <div class="col-sm-10" ><?php echo $this->Form->input($fields[24], array('id' => 'fee_calucation_desc', 'type' => 'text', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="fee_calucation_desc_error" class="form-error"><?php //echo $errarr['fee_calucation_desc_error']; ?></span></div>                            
                    </div>
                </div> 

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Output Item" class="control-label col-sm-2"><?php echo __('lbloutputitem'); ?></label>                            
                        <div class="col-sm-4" ><?php echo $this->Form->input($fields[11], array('id' => 'fee_output_item_id', 'options' => $outitemlist, 'label' => false, 'class' => 'form-control input-sm', 'required')); ?>
                            <span id="fee_output_item_id_error" class="form-error"><?php //echo $errarr['fee_output_item_id_error']; ?></span></div>                                 
                        <label for="Output Display Order" class="control-label col-sm-2" id=""><?php echo __('lblDisplayOrder'); ?></label>                            
                        <div class="col-sm-4" ><?php echo $this->Form->input($fields[12], array('id' => 'fee_output_item_order', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="fee_output_item_order_error" class="form-error"><?php //echo $errarr['fee_output_item_order_error']; ?></span></div>
                    </div>
                </div>

                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <label for="Minimum Value" class="control-label col-sm-2"><?php echo __('lblMinValue'); ?></label>                            
                        <div class="col-sm-4" ><?php echo $this->Form->input($fields[37], array('id' => 'min_value', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="min_value_error" class="form-error"><?php //echo $errarr['min_value_error']; ?></span></div>                          
                        <label for="Maximum Value" class="control-label col-sm-2" id=""><?php echo __('lblmaxval'); ?></label>                            
                        <div class="col-sm-4" ><?php echo $this->Form->input($fields[38], array('id' => 'max_value',  'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="max_value_error" class="form-error"><?php //echo $errarr['max_value_error']; ?></span></div>   
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Optional Fees Flag" class="control-label col-sm-2"><?php echo __('lblOptionalFlag'); ?></label>            
                        <div class="col-sm-3"> <?php echo $this->Form->input($fields[39], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'optional_flag')); ?>
                        </div>   
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">    
                        <label for="select Govt Body" class="control-label col-sm-2" ><?php //echo __('lbllocalgoberningbody'); ?></label>   
                        <div class="col-sm-4">  <?php echo $this->Form->input($fields[31], array('type' => 'select', 'label' => false, 'options' => $gov_body_type,'type' => 'hidden', 'empty' => '--Select--', 'multiple' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm')); ?>
                            <span id="ulb_type_id_error" class="form-error"><?php //echo $errarr['local_gov_body_error'];     ?></span></div> 
                    </div>
                </div>


               
                    <div class="row">

                        <div class="form-group">
                            <label for="max value checking " class="control-label col-sm-2"><?php echo __('maxvaluecheck'); ?></label>            
                            <div class="col-sm-3"> <?php echo $this->Form->input($fields[10], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'max_value_condition_flag')); ?>
                                 <?php //echo $this->Form->input($fields[10], array('type' => 'hidden', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'max_value_condition_flag')); ?>

                            </div>
                        </div>
                    </div>

                    <div  class="rowht"></div>
                    <div id="maxParaRow">
                        <div class="row">
                            <div class="form-group">
                                <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectmaxpara'); ?></label>            
                                <div class="col-sm-4"><?php echo $this->Form->input('maxvalueparameterlist', array('type' => 'select', 'empty' => '-select-', 'options' => $inputItems, 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'maxvalueparameterlist')); ?>
<!--                                        <span id="maxvalueparameterlist_error" class="form-error"><?php //echo $errarr['max_value_formula_error'];     ?></span>--></div>
                                <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                                <div class="col-sm-4"><?php echo $this->Form->input('operatorsignmax', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'operatorsignmax')); ?>
<!--                                        <span id="operatorsignmax_error" class="form-error"><?php //echo $errarr['max_value_formula_error'];     ?></span>--></div>
                            </div>
                        </div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <div class="form-group">
                                <label for="Max Value Formula" class="control-label col-sm-2"><?php echo __('lblmaxvalueformula'); ?></label>                                        
                                <div class="col-sm-4"><?php echo $this->Form->input($fields[9], array('id' => 'max_value_formula', 'placeholder' => 'Max Value Formula', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="max_value_formula_error" class="form-error"><?php //echo $errarr['max_value_formula_error']; ?></span></div>                                                       
                            </div>
                        </div>
                    </div>


                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectpara'); ?></label>            
                            <div class="col-sm-4"><?php echo $this->Form->input('parameterlist', array('type' => 'select', 'empty' => '-select-', 'options' => $inputItems, 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'parameter_id')); ?></div>
                            <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                            <div class="col-sm-4"><?php echo $this->Form->input('operatorsign', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'operator_sign')); ?></div>
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 onlyforrule"></div>
                            <label for="Conditions " class="control-label col-sm-5"><?php echo __('lblcondition'); ?></label>            
                            <label for="Formulas" class="control-label col-sm-5"><?php echo __('lblformula'); ?></label>                                        
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class='conditionsAndFormulas' >

                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-2 onlyforrule"></div>
                                <div class="col-sm-5"><?php echo $this->Form->input($fields[3], array('id' => 'fee_rule_cond1', 'placeholder' => 'Condition 1', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="fee_rule_cond1_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error']; ?></span></div> 

                            </div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[4], array('id' => 'fee_rule_formula1', 'placeholder' => 'Formula 1', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm', 'required')); ?>
                                <span id="fee_rule_formula1_error" class="form-error"><?php //echo $errarr['fee_rule_formula1_error']; ?></span></div>                                                       
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 onlyforrule"></div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[5], array('id' => 'fee_rule_cond2', 'placeholder' => 'Condition 2', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_cond2_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div> 
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[6], array('id' => 'fee_rule_formula2', 'placeholder' => 'Formula 2', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_formula2_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div>                                                       

                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 onlyforrule"></div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[13], array('id' => 'fee_rule_cond3', 'placeholder' => 'Condition 3', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_cond3_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div>                                                       
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[14], array('id' => 'fee_rule_formula3', 'placeholder' => 'Formula 3', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_formula3_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div> 

                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 onlyforrule"></div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[15], array('id' => 'fee_rule_cond4', 'placeholder' => 'Condition 4', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_cond4_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div> 
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[16], array('id' => 'fee_rule_formula4', 'placeholder' => 'Formula 4', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_formula4_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div> 

                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 onlyforrule"></div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[17], array('id' => 'fee_rule_cond5', 'placeholder' => 'Condition 5', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_cond5_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div>
                            <div class="col-sm-5"><?php echo $this->Form->input($fields[18], array('id' => 'fee_rule_formula5', 'placeholder' => 'Formula 5', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="fee_rule_formula5_error" class="form-error"><?php //echo $errarr['fee_rule_cond1_error'];     ?></span></div>

                        </div>
                    </div>
                
                <div  class="rowht"></div>
                <div id="subrulelistdiv">
                </div>

                <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">                               
                        <?php
                        echo $this->Form->button(__('btnsave'), array('id' => 'btnSave', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
//                                echo $this->Form->button(__('lblNewRule'), array('id' => 'btnReset', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                        echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                        ?>
                    </div>
                    <div class="hidden Input">
                        <?php
                        echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                        echo $this->Form->input('subruleid', array('id' => 'hdnsubruleid', 'type' => 'hidden'));
                        echo $this->Form->input('copy', array('id' => 'copy_sub_rule_id', 'type' => 'hidden', 'readOnly' => 'true'));
                        ?>
                    </div>
                </div>
            </div>


        </div>



        <div class="box box-primary">
            <div class="box-body">
                <table  id="tblEvalRule" class="table table-striped table-bordered table-hover">
                    <thead>                        
                        <?php
                        echo "<tr>"
                        . "<th class='width10 center'>" . __('lblsrno') . "</th>"
                        . "<th class='center width5'>" . __('lblid') . "</th>"
                        . "<th class='width10'>" . __('lbloutputitem') . "</th>"
                        . "<th class='width10'>" . __('lblDisplayOrder') . "</th>"
                        . "<th class='center'>" . __('lblcond1') . "</th>"
                        . "<th class='center'>" . __('lblformula1') . "</th>"
                        . "<th class='center'>" . __('lbllocalgoberningbody') . "</th>"
                        . "<th class='width10 center'>" . __('lblaction') . "</th>"
                        . "</tr>";
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        $srno = 1;
                        if ($feeSubruleData) {
                            foreach ($feeSubruleData as $erd1) {

                                $erd = $erd1['article_fee_subrule'];
                                echo "<tr id =subrule_" . $erd['fee_subrule_id'] . ">"
                                . "<td class='tblbigdata'>" . $srno++ . "</td>"
                                . "<td class='tblbigdata'>" . $erd['fee_subrule_id'] . "</td>"
                                . "<td class='tblbigdata'>" . $erd1['item']['fee_item_desc_' . $lang] . "</td>"
                                . "<td class='tblbigdata'>" . $erd['fee_output_item_order'] . "</td>"
                                . "<td class='tblbigdata'>" . $erd['fee_rule_cond1'] . "</td>"
                                . "<td class='tblbigdata'>" . $erd['fee_rule_formula1'] . "</td>"
                                . "<td class='tblbigdata'>" . $erd1['gov_body']['class_description_' . $lang] . "</td>";
                                echo "<td class='tblbigdata'>"
                                . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('onclick' => "javascript: return setSubRuleData('" . $erd[$fields[1]] . "','" . $erd[$fields[39]] . "','" . $erd[$fields[10]] . "','" . $erd[$fields[11]] . "','" . $erd[$fields[12]] . "','" . $erd[$fields[37]] . "','" . $erd[$fields[38]] . "','" . $erd[$fields[31]] . "','" . $erd[$fields[4]] . "','" . $erd[$fields[3]] . "','" . $erd[$fields[2]] . "','" . $erd[$fields[24]] . "','" . $erd[$fields[5]] . "','" . $erd[$fields[6]] . "','" . $erd[$fields[13]] . "','" . $erd[$fields[14]] . "','" . $erd[$fields[15]] . "','" . $erd[$fields[16]] . "','" . $erd[$fields[17]] . "','" . $erd[$fields[18]] . "','" . $erd[$fields[9]] . "');"))
                                . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('onclick' => "javascript: return removeSubRule('" . $erd[$fields[0]] . "','" . $erd[$fields[1]] . "');"))
                                . $this->Form->button('<i class = "fa fa-files-o"></i>', array('title' => 'Copy', 'class' => "copy", 'onclick' => 'javascript: return copySubRule(' . $erd['fee_subrule_id'] . ')'))
                                . $this->Form->button('<i class = "fa fa-clipboard"></i>', array('title' => 'Paste', 'class' => "", 'onclick' => 'javascript: return pasteSubRule(' . $erd['fee_subrule_id'] . ')'))
                                . "</td>"
                                . "</tr>";
                            }
                        }
                        ?>
                    <tbody>
                    <tfoot id="footer">
                        <tr><td colspan="7" class="text-danger danger"><?php echo __('lblplsrefpageupdaterule'); ?></td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
