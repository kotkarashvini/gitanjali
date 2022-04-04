<script>
    $(document).ready(function () {
        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblEvalSubRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        $('.copy').click(function () {
            $('.copy').css('background-color', '#FFF');
            $(this).css('background-color', '#206b67');
        });
        $('#frmValSubRule')[0].reset();
        $("#maxpararow,.rrf,#footer").hide();
        //----------------------------------------------------------------------------------------------------------------------------------------------------
        ('<?php echo $usage_dependancy['road_vicinity_flag']; ?>' == 'Y') ? ($('#roadvicinityrow').show()) : ($('#roadvicinityrow').hide());
        ('<?php echo $usage_dependancy['user_defined_dependency1_flag']; ?>' == 'Y') ? ($('#userdependancy1row').show()) : ($('#userdependancy1row').hide());
        ('<?php echo $usage_dependancy['user_defined_dependency2_flag']; ?>' == 'Y') ? ($('#userdependancy2row').show()) : ($('#userdependancy2row').hide());
        //-----------------------------------------------------------------Conditions and Forumula-------------------------------------------------------------------------------
        $(".cndpr").val('');
        var txtfield = '';
        $('input.cndpr').focus(function () {
            txtfield = $(this).attr('id');
        });
        //-------------------------------------------------Maxformula-------------------
        $("#maxparaid,#maxoptorid").change(function () {
            var cvalue = $("#maxformula").val().trim();
            $("#maxformula").val(cvalue + $(this).val());
            $("#maxparaid,#maxoptorid").val('');
        });
        //-------------------------------------------------------------------------------------------------------------------------------------
        $('input:radio[name="data[frmValSubRule][max_value_condition_flag]"]').change(function () {
            ($(this).val() == 'Y') ? ($("#maxpararow").show()) : ($("#maxpararow").hide());
        });
        //-------------------------------------------------------------------------------------------------------------------------------------
        $('input:radio[name="data[frmValSubRule][rate_revision_flag]"]').change(function () {
            ($(this).val() == 'Y') ? ($(".rrf").show()) : ($(".rrf").hide());
        });
        //-------------------------------------------------------------------------------------------------------------------------------------
        //-------------------------------------------------Other Formula and conditions-------------------
        $("#parameter_id,#operator_id").change(function () {
            var inputfield = '';
            if ($('#evalsubrule_formula1').val() == '') {
                inputfield = 'evalsubrule_formula1';
            } else if ($('#' + txtfield).attr('data-cndpr') == 'Y') {
                inputfield = txtfield;
            } else {
                alert('click in conditions or formula inputbox');
            }
            if (inputfield) {
                var rate = $(this).val().trim();
                var cvalue = $("#" + inputfield).val().trim();
                $("#" + inputfield).val(cvalue + rate);
            }
            $("#parameter_id,#operator_id").val('');
        });
        //---------------------------------------------------------------------------------------------------------------------------------------------------------
        $('#btnNew').click(function () {
            $('#frmValSubRule')[0].reset();
        });
        $('#btnSave').click(function () {
            $('#frmValSubRule').submit();
        });
    });
//--------------------------/*----------------------/*-------------------------------/*--------------------------------/*-----------------------------/*-------
//---------------------------------------------------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
//---------------------------------------------------------------------------------------------------------------------------------------------------

    function editSubrule(id, out_id, out_order, max_flag, rate_rev_flag, f1, c1, f2, c2, f3, c3, f4, c4, f5, c5, af, mf, rf1, rf2, rf3, rf4, rf5, udd3, udd4, udd5) {
        $("html, body").animate({scrollTop: '150'}, "slow");
        $('#subruleid').val(id);
        $('#subrule_id').html('Subrule Id : ' + id);
        $('#output_item_id').val(out_id);
        $('#output_item_id').focus();
        $('#out_item_order').val(out_order);
        $('input:radio[name="data[frmValSubRule][max_value_condition_flag]"][value=' + max_flag + ']').prop('checked', true);
        (max_flag == 'Y') ? ($("#maxpararow").show()) : ($("#maxpararow").hide());
        $('input:radio[name="data[frmValSubRule][rate_revision_flag]"][value=' + rate_rev_flag + ']').prop('checked', true);
        (rate_rev_flag == 'Y') ? ($(".rrf").show()) : ($(".rrf").hide());
        $('#evalsubrule_formula1').val(f1);
        $('#rate_revision_formula1').val(rf1);
        $('#evalsubrule_cond1').val(c1);
        $('#evalsubrule_formula2').val(f2);
        $('#rate_revision_formula2').val(rf2);
        $('#evalsubrule_cond2').val(c2);
        $('#evalsubrule_formula3').val(f3);
        $('#rate_revision_formula3').val(rf3);
        $('#evalsubrule_cond3').val(c3);
        $('#evalsubrule_formula4').val(f4);
        $('#rate_revision_formula4').val(rf4);
        $('#evalsubrule_cond4').val(c4);
        $('#evalsubrule_formula5').val(f5);
        $('#rate_revision_formula5').val(rf5);
        $('#evalsubrule_cond5').val(c5);
        $('#max_value_formula').val(mf);
        $('#alternate_formula').val(af);
        $('#road_vicinity_id').val(udd3);
        $('#user_defined_dependency1_id').val(udd4);
        $('#user_defined_dependency2_id').val(udd5);

        return false;
    }
//------------------------------- remove Subrule -----------------------------------------------------------------------------    
    function removeSubRule(rl_id, sb_rl_id) {
        var status = 1;
        if (confirm('Do U Want to Delete this Rule ? ')) {
            if (confirm('Are You Sure Rule,Item and Subrule will be deleted for this Rule ? ')) {
                status = $.ajax({
                    type: "POST",
                    url: host + 'removeValSubRule',
                    data: {rule_id: rl_id, sub_rule_id: sb_rl_id},
                    async: false,
                    success: function () {
//                        window.location.reload(true);
                    }
                }).responseText;
                if (status == 0) {
                    $('#' + sb_rl_id).fadeOut(300);
                } else {
                    alert(status);
                }
            }
        }
        return false;
    }
    //----------------------------------------------------------------------------------------------------------------------
    function copySubrule(copy_id) {
        $('#copy_subrule_id').val(copy_id);
        alert('All Formulas & Conditions from Subrule Id:"' + copy_id + '"  are Copied');
        return false;
    }
    //----------------------------------------------------------------------------------------------------------------------
    function pasteSubrule(to_id) {
        if ($('#copy_subrule_id').val() != '') {
            if (confirm('Are You Sure? You want to copy all formula from Rule No. ' + $('#copy_subrule_id').val() + ' to ' + to_id)) {

                $.post(host + "copyValSubrule", {from_id: $('#copy_subrule_id').val(), to_id: to_id}, function (data) {
                    if (data == 1) {
                        alert('all formulas copied from Subrule Id "' + $('#copy_subrule_id').val() + '" to "' + to_id + '"');
                        $('#footer').show();
                    }
                });
            }
        } else {
            alert('please First Copy Subrule!');
        }
        return false;
    }
</script>

<?php echo $this->element("Property/rule_menu"); ?>
<?php echo $this->Form->create('frmValSubRule', array('id' => 'frmValSubRule', 'class' => 'form-vertical')); ?>

<hr width=100%  align=left>
<div class="box box-primary">
    <div class="box-header with-border">
        <center><h2 class="box-title" align="center" style="font-weight: bolder"><?php echo __('lblsubrule'); ?></h2></center>

        <div class="box-tools pull-right">
            <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/valuation_sub_rule_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i><?php echo __('Help'); ?> </a>
        </div> 
    </div>
    <div class="box-body">
        <h6 class="box-heading" style="font-weight: bolder; background-color: #E6B800;"><?php echo "Rule : " . $ruleid . ' - ' . $rulename ?></h6>
        <div  class="rowht"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="col-xs-12" id="subrule_id" style="text-align: center;color: white; background-color: #E6B800"></label>
                </div>
            </div>
        </div>
        <div  class="rowht"></div>



        <div class="col-md-6">

            <div class="row">
                <div class="form-group">
                    <!--<div class="col-sm-7">-->
                    <!--<div class="row">-->
                    <div class="form-group">                            
                        <label for="Output Item" class="control-label col-sm-4"><?php echo __('lbloutputitem'); ?> <span style="color:red">*</span></label>                            
                        <div class="col-sm-8" >
                            <?php echo $this->Form->input($name[14], array('id' => 'output_item_id', 'options' => $outitemlist, 'empty' => '--Select Option--', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="output_item_id_error" class="form-error"></span>
                        </div>                            

                    </div>
                    <!--</div>-->
                    <!--</div>-->
                    <!--<div class="col-sm-5">-->
                    <!--<div class="row">-->
                    <!--<div class="col-sm-12">-->




                    <!--</div>-->
                    <!--</div>-->
                    <!--</div>-->
                </div>
            </div>  




            <div  class="rowh top-buffer">&nbsp;</div>
            <div class="row">
                <div class="form-group" id="subrule_ouder">
                    <label for="Output Display Order" class="control-label col-sm-4"><?php echo __('lblDisplayOrder'); ?></label>              
                    <div class="col-sm-8" >
                        <?php echo $this->Form->input($name[22], array('id' => 'out_item_order', 'type' => 'Number', 'min' => '1', 'max' => '200', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        <span id="out_item_order_error" class="form-error"></span>
                    </div>           
                </div>
            </div>

            <div  class="rowh top-buffer">&nbsp;</div>
            <div class="row" id="roadvicinityrow">
                <div class="form-group">
                    <label for="Road Vicinity Id" class="control-label col-sm-4"><?php echo __('lblroadvicinity'); ?><span style="color:red">*</span></label>
                    <div class="col-sm-8" >
                        <?php echo $this->Form->input($name[17], array('options' => $roadvicinitylist, 'empty' => '--Select--', 'id' => 'road_vicinity_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        <span id="road_vicinity_id_error" class="form-error"></span>
                    </div>           
                </div>
            </div>

            <div class="row" id="userdependancy1row">
                <div class="form-group">
                    <label for="user defined dependancy 1" class="control-label col-sm-4"><?php echo __('lbluser_defined_dependency1_id'); ?><span style="color:red">*</span></label>                                
                    <div class="col-sm-8" >
                        <?php echo $this->Form->input($name[18], array('options' => $userdd1list, 'empty' => '--Select--', 'id' => 'user_defined_dependency1_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        <span id="user_defined_dependency1_id_error" class="form-error"></span>
                    </div>
                </div>
            </div>

            <div class="row" id="userdependancy2row">
                <div class="form-group">
                    <label for="user defined dependancy 2" class="control-label col-sm-4"><?php echo __('user_defined_dependency2_id'); ?><span style="color:red">*</span></label>
                    <div class="col-sm-8" ><?php echo $this->Form->input($name[19], array('options' => $userdd2list, 'empty' => '--Select--', 'id' => 'user_defined_dependency2_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        <span id="user_defined_dependency2_id_error" class="form-error"></span>
                    </div>
                </div>
            </div> 


        </div>

        <div class="col-md-6">


            <div class="panel-group">
              
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('lblitemdesc'); ?> 
                        </div>
                    </div>
                    <div class="panel-heading"> 
                     <?php
                    foreach ($inputlists as $listItem) {
                        if ($listItem['itemlist']['is_list_field_flag'] == 'Y') {
                            echo '<a data-toggle="collapse" href="#collapse' . $listItem['itemlist']['usage_param_id'] . '">' . $listItem['itemlist']['usage_param_code'] . ' : ' . $listItem['itemlist']['usage_param_desc_' . $lang] . ' <span class="fa fa-arrow-down pull-right"></span></a><br>';
                        } else {
                            echo '<a disabled>' . $listItem['itemlist']['usage_param_code'] . ' : ' . $listItem['itemlist']['usage_param_desc_' . $lang] . '</a><br>';
                        }
                        ?>
                        <div id="collapse<?php echo $listItem['itemlist']['usage_param_id']; ?>" class="panel-collapse collapse">

                            <?php
                            if (isset($listItem['itemlist']['options'])) {
                                foreach ($listItem['itemlist']['options'] as $key => $option) {
                                    ?>
                                    <ul class="list-group">                            
                                        <li class="list-group-item"><?php echo $key . " : " . $option; ?></li>                            
                                    </ul>
                                <?php }
                            }
                            ?>

                        </div>

                    <?php }
                    ?>  
</div>

                </div>

            </div>

        </div>




        <div class="col-md-12" >


            <!-- --------------------------------------------------------Max Value Check -------------------------------------------- -->
            <div class="row top-buffer">
                <div class="form-group">
                    <label for="max value checking " class="control-label col-sm-2"><?php echo __('maxvaluecheck'); ?><span style="color:red">*</span></label>            
                    <div class="col-sm-3"> <?php echo $this->Form->input($name[13], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'max_value_condition_flag')); ?>
                        <span id="max_value_condition_flag_error" class="form-error"></span>
                    </div> 
                </div>
            </div> 
            <div  class="rowht"></div> <div  class="rowht"></div> 
            <div id="maxpararow">
                <div class="form-group">
                    <div class="row top-buffer">
                        <div class="form-group">    
                            <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectmaxpara'); ?></label>            
                            <div class="col-sm-4"><?php echo $this->Form->input('maxvalueparameterlist', array('type' => 'select', 'options' => $maxvalueparameterslist, 'empty' => '-select-', 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'maxparaid')); ?></div>
                            <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                            <div class="col-sm-4"><?php echo $this->Form->input('operatorsignmax', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'maxoptorid')); ?></div>
                        </div>
                    </div>
                    <div  class="rowht top-buffer">&nbsp;</div>
                    <div class="row ">
                        <div class="form-group">    
                            <label for="Max Formula" class="control-label col-sm-2"><?php echo __('lblmaxvalueformula'); ?></label>                                        
                            <div class="col-sm-4"><?php echo $this->Form->input($name[12], array('id' => 'max_value_formula', 'placeholder' => 'Max Value Formula', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                <span id="max_value_formula_error" class="form-error"></span>
                            </div>
                            <label for="" class="control-label col-sm-3"></label>                                        
                        </div>
                    </div>
                </div>
            </div>
            <div  class="rowht"></div> <div  class="rowht"></div> 









            <!-- -----------------------------------------------------Rate Revision Flag -------------------------------------------- -->
            <div class="row top-buffer">
                <div class="form-group">
                    <label for="Rate Revision Flag" class="control-label col-sm-2"><?php echo __('lblRateRevFlag') . ' ?'; ?> </label>
                    <div class="col-sm-3"> <?php echo $this->Form->input($name[39], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'rate_revision_flag')); ?>
                        <span id="rate_revision_flag_error" class="form-error"></span>
                    </div> 
                </div>
            </div>
            <div  class="rowht"></div> <div  class="rowht"></div> 
            <!-- -------------------------------------------------------- Conditions & Formula  -------------------------------------------- -->
            <div class="row top-buffer">
                <div class="form-group">
                    <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectpara'); ?></label>            
                    <div class="col-sm-4"><?php echo $this->Form->input('parameterlist', array('type' => 'select', 'empty' => '-select-', 'options' => $inputlistoptions, 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'parameter_id')); ?></div>
                    <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                    <div class="col-sm-4"><?php echo $this->Form->input('operatorsign', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'operator_id')); ?></div>
                </div>
            </div>
            <div  class="rowht"></div> <div  class="rowht"></div> 

            <div class="row top-buffer">
                <div class="form-group">                                
                    <label for="Conditions" class="control-label col-sm-4"><?php echo __('lblcondition'); ?></label>            
                    <label for="Rate Revfision Formula" class="control-label col-sm-4"><span class="rrf"><?php echo __('lblRateRevFlag'); ?> </span></label> 
                    <label for="Main Formula" class="control-label col-sm-4"><?php echo __('lblformula'); ?></label>                                        
                </div>
            </div>
            <div  class="rowht"></div> 
            <div  class="rowht"></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="row">
                            <!--<div class="col-sm-12">-->
                            <div class="form-group">
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[3], array('id' => 'evalsubrule_cond1', 'placeholder' => 'Condition 1', 'label' => false, 'data-cndpr' => 'Y', 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_cond1_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4 ">
<?php echo $this->Form->input($name[32], array('id' => 'rate_revision_formula1', 'title' => 'Rate Revision Formula 1', 'data-cndpr' => 'Y', 'placeholder' => ' Rate Revision Formula 1', 'label' => false, 'class' => 'cndpr form-control input-sm rrf')); ?>
                                    <span id="rate_revision_formula1_error" class="form-error"></span>
                                </div>                                                       
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[4], array('id' => 'evalsubrule_formula1', 'placeholder' => 'Formula 1', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_formula1_error" class="form-error"></span>
                                </div>                                                       

                            </div>
                            <!--</div>-->
                        </div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <!--<div class="col-sm-12">-->
                            <div class="form-group">
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[5], array('id' => 'evalsubrule_cond2', 'placeholder' => 'Condition 2', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_cond2_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4 ">
<?php echo $this->Form->input($name[33], array('id' => 'rate_revision_formula2', 'title' => 'Rate Revision Formula 2', 'data-cndpr' => 'Y', 'placeholder' => 'Rate Revision Formula 2', 'label' => false, 'class' => 'cndpr form-control input-sm rrf')); ?>
                                    <span id="rate_revision_formula2_error" class="form-error"></span>
                                </div> 
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[6], array('id' => 'evalsubrule_formula2', 'placeholder' => 'Formula 2', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_formula2_error" class="form-error"></span>
                                </div>

                            </div>
                            <!--</div>-->
                        </div>
                        <div  class="rowht"></div>

                        <div class="row">
                            <!--<div class="col-sm-12">-->
                            <div class="form-group">
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[26], array('id' => 'evalsubrule_cond3', 'placeholder' => 'Condition 3', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_cond3_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4 ">
<?php echo $this->Form->input($name[34], array('id' => 'rate_revision_formula3', 'title' => 'Rate Revision Formula 3', 'data-cndpr' => 'Y', 'placeholder' => 'Rate Revision Formula 3', 'label' => false, 'class' => 'cndpr rrf form-control input-sm ')); ?>
                                    <span id="rate_revision_formula3_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[27], array('id' => 'evalsubrule_formula3', 'placeholder' => 'Formula 3', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_formula3_error" class="form-error"></span>
                                </div>

                            </div>
                            <!--</div>-->
                        </div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <!--<div class="col-sm-12">-->
                            <div class="form-group">
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[28], array('id' => 'evalsubrule_cond4', 'placeholder' => 'Condition 4', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_cond4_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4 ">
<?php echo $this->Form->input($name[35], array('id' => 'rate_revision_formula4', 'title' => 'Rate Revision Factor 4', 'data-cndpr' => 'Y', 'placeholder' => 'Rate Revision Formula 4', 'label' => false, 'class' => 'cndpr rrf form-control input-sm')); ?>
                                    <span id="rate_revision_formula4_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[29], array('id' => 'evalsubrule_formula4', 'placeholder' => 'Formula 4', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_formula4_error" class="form-error"></span>
                                </div>

                            </div>
                            <!--</div>-->
                        </div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <!--<div class="col-sm-12">-->
                            <div class="form-group">
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[30], array('id' => 'evalsubrule_cond5', 'placeholder' => 'Condition 5', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_cond5_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4 ">
<?php echo $this->Form->input($name[36], array('id' => 'rate_revision_formula5', 'title' => 'Rate Revision Factor 5', 'data-cndpr' => 'Y', 'placeholder' => 'Rate Revision Formula 5', 'label' => false, 'class' => 'cndpr rrf form-control input-sm')); ?>
                                    <span id="rate_revision_formula5_error" class="form-error"></span>
                                </div>
                                <div class="col-sm-4">
<?php echo $this->Form->input($name[31], array('id' => 'evalsubrule_formula5', 'placeholder' => 'Formula 5', 'data-cndpr' => 'Y', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?>
                                    <span id="evalsubrule_formula5_error" class="form-error"></span>
                                </div>

                            </div>
                            <!--</div>-->
                        </div>
                        <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                        <!--                    <div class="row">
                                                <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="col-sm-2 onlyforrule"></div>
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-2"></div>                                                       
                                                    <div class="col-sm-4">
<?php //echo $this->Form->input($name[37], array('id' => 'alternate_formula', 'placeholder' => 'Alternate Formula', 'label' => false, 'class' => 'cndpr form-control input-sm'));    ?>
                                                        <span id="alternate_formula_error" class="form-error"></span>
                                                    </div>
                        
                                                </div>
                                                </div>
                                            </div>-->

                        <div class="hidden">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                            echo $this->Form->input('subruleid', array('id' => 'subruleid', 'type' => 'hidden'));
                            ?>                                                                           
                        </div>

                        <!--Form button Group-->
                        <div class="box-body">
                            <!--<div class="well well-sm">-->
                            <div class="row center" >
                                <div class="form-group">                                
                                    <button id="btnSave"  class="btn btn-info" type="submit">
                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                    </button>
                                    <button id="btnNew" class="btn btn-info "  type="button">
                                        <span class=""></span>&nbsp;&nbsp;<?php echo __('lblNewRule'); ?>
                                    </button>
                                    <button id="btnExit" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;<?php echo __('lblexit'); ?>
                                    </button>
                                </div>
                            </div>
                            <!--</div>-->
                        </div>
<?php echo $this->Form->input('copy', array('id' => 'copy_subrule_id', 'type' => 'hidden', 'readOnly' => 'true')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="box box-primary">
    <div class="box-body">
        <!--<div class="table-responsive">-->                            
        <table  id="tblEvalSubRule" class="table table-striped table-bordered table-hover" style="overflow: scroll;">
            <thead>
                <tr>
                    <th class='center'><?php echo __('lblsrno'); ?></th>
                    <th class='center'><?php echo __('lblid'); ?></th>
                    <th class='center'><?php echo __('lblcond1'); ?></th>
                    <th class='center'><?php echo __('lblformula1'); ?></th>
                    <th class='center'><?php echo __('lblismax'); ?></th>
                    <th class='center'><?php echo __('lblroadvicinity'); ?></th>
                    <th class='center'><?php echo __('lbloutputitem'); ?></th>
                    <th class='center'><?php echo __('lblorder'); ?></th>
                    <th class='center'><?php echo __('lblaction'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($subrule_list as $val) {
                    $slist = $val[0];
                    ?>
                    <tr id = "<?php echo $slist['subrule_id']; ?>" >
                        <td class = 'center width5 tblbigdata'> <?php echo $i++; ?> </td>
                        <td class = 'center width5 tblbigdata'> <?php echo $slist['subrule_id']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['evalsubrule_cond1']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['evalsubrule_formula1']; ?> </td>
                        <td class = 'center width5 tblbigdata'> <?php echo $slist['max_value_condition_flag']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo (($slist['road_vicinity_desc_en'] != null) ? $slist['road_vicinity_desc_en'] : '-NA-') ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['usage_param_desc_en'] ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['out_item_order'] ?> </td>
                        <td class = 'center width10 tblbigdata'> <?php
                            echo $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "editSubrule", 'title' => "Edit", 'id' => $slist['subrule_id'], 'onclick' => "javascript: return editSubrule('"
                                . $slist['subrule_id'] . "','" . $slist['output_item_id'] . "','" . $slist['out_item_order'] . "','" . $slist['max_value_condition_flag'] . "','" . $slist['rate_revision_flag']
                                . "','" . $slist['evalsubrule_formula1'] . "','" . $slist['evalsubrule_cond1'] . "','" . $slist['evalsubrule_formula2'] . "','" . $slist['evalsubrule_cond2']
                                . "','" . $slist['evalsubrule_formula3'] . "','" . $slist['evalsubrule_cond3'] . "','" . $slist['evalsubrule_formula4'] . "','" . $slist['evalsubrule_cond4']
                                . "','" . $slist['evalsubrule_formula5'] . "','" . $slist['evalsubrule_cond5'] . "','" . $slist['alternate_formula'] . "','" . $slist['max_value_formula']
                                . "','" . $slist['rate_revision_formula1'] . "','" . $slist['rate_revision_formula2'] . "','" . $slist['rate_revision_formula3'] . "','" . $slist['rate_revision_formula4'] . "','" . $slist['rate_revision_formula5'] . "','" . $slist['road_vicinity_id'] . "','" . $slist['user_defined_dependency1_id'] . "','" . $slist['user_defined_dependency2_id'] . "');"));
                            ?>
                            <button title="Delete" class = '' onClick = 'return removeSubRule(<?php echo $slist['evalrule_id']; ?>, <?php echo $slist['subrule_id']; ?>);'><span class = 'glyphicon glyphicon-remove'></span> </button>
                            <button class = 'copy' onClick = 'return copySubrule(<?php echo $slist['subrule_id']; ?>);'><i class="fa fa-files-o"></i></button>
                            <button class = '' onClick = 'return pasteSubrule(<?php echo $slist['subrule_id']; ?>);'><i class="fa fa-clipboard"></i></button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot id="footer">
                <tr><td colspan="9" class="text-danger danger"><?php echo __('lblplsrefpageupdaterule'); ?></td></tr>
            </tfoot>
        </table>                        

        <!--</div>-->
    </div>
</div>
<?php

function replace_ops($originalString) {
    $find_what = array('[DOT]', '[PLUS]', '[MINUS]', '[MULTIPLY]', '[DIVIDE]', '[AND]', '[OR]', '[EQUAL_TO]', '[NOT_EQUAL_TO]', '[LESS_THAN]', '[LESS_THAN_EQUAL]', '[GREATER_THAN]', '[GREATER_THAN_EQUAL]', '[EQUAL]');
    $replace_with = array('.', '+', '-', '*', '/', '&&', '||', '==', '!=', '<', '<=', '>', '>=', '=');
    return str_replace($find_what, $replace_with, $originalString);
}
?>
<?php echo $this->Form->end(); ?>