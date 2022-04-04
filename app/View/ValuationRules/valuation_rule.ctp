<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<script>
    $(document).ready(function () {
        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblEvalRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        $("#additionalRateRow,#additionalRate1Row").hide();
        var host = "<?php echo $this->webroot; ?>";
//------------------------------------------------------------On Load-----------------------------------------------------------------------------

        if ($('#usage_main_catg_id').val() != null) {
            //  getUsageList($('#usage_main_catg_id option:selected').val(), '', 'usage_sub_catg_id', $('#sub_id').val());
        }

        if ($('#rule_id').val()) {
            $('#rule_id_label').html('Valuation Rule Id : ' + $('#rule_id').val());
            $.post(host + 'getRuleFlags', {rule_id: $('#rule_id').val()}, function (flags) {
                $('input:radio[name="data[frmevalrule][contsruction_type_flag]"][value=' + flags['con'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][depreciation_flag]"][value=' + flags['dep'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][road_vicinity_flag]"][value=' + flags['rdv'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][user_defined_dependency1_flag]"][value=' + flags['udd1'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][user_defined_dependency2_flag]"][value=' + flags['udd2'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][tdr_flag]"][value=' + flags['tdr'] + ']').prop('checked', true);


                $('input:radio[name="data[frmevalrule][rate_compare_flag]"][value=' + flags['cmp'] + ']').prop('checked', true);
                (flags['cmp'] == 'Y') ? ($('#rateCmpRow').show()) : ($('#rateCmpRow').hide());

                $('input:radio[name="data[frmevalrule][additional_rate_flag]"][value=' + flags['add_'] + ']').prop('checked', true);
                (flags['add_'] == 'Y') ? ($('#additionalRateRow').show()) : ($('#additionalRateRow').hide());

                $('input:radio[name="data[frmevalrule][additional1_rate_flag]"][value=' + flags['add_1'] + ']').prop('checked', true);
                (flags['add_1'] == 'Y') ? ($('#additionalRate1Row').show()) : ($('#additionalRate1Row').hide());

                $('input:radio[name="data[frmevalrule][is_urban]"][value=' + flags['urban'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][is_rural]"][value=' + flags['rural'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][is_influence]"][value=' + flags['influence'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][rate_revision_flag]"][value=' + flags['rrf'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][skip_val_flag]"][value=' + flags['skip_val_flag'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][is_boundary_applicable]"][value=' + flags['is_boundary_applicable'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][tah_process_flag]"][value=' + flags['tah_process_flag'] + ']').prop('checked', true);
                $('input:radio[name="data[frmevalrule][display_flag]"][value=' + flags['display_flag'] + ']').prop('checked', true);


            }, 'json');
        }

//-------------------------------------------------------Main Usage-------------------------------------------------------------------------------        
        $('#usage_main_catg_id').change(function () {
            getUsageList($(this).val(), '', 'usage_sub_catg_id', '');
        });
//-------------------------------------------------------Comparision------------------------------------------------------------------------------
        ratecmpchangeEvent($('input:radio[name="data[frmevalrule][rate_compare_flag]"]:checked').val());
//------------------------------------------------------------------------------------------------------------------------------------------------         
        $('input:radio[name="data[frmevalrule][rate_compare_flag]"]').change(function () {
            ratecmpchangeEvent($(this).val());
        });
//------------------------------------------------------------------------------------------------------------------------------------------------         
        $("#cmp_usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'cmp_usage_sub_catg_id', '');
        });
//-----------------------------------------------------Additional---------------------------------------------------------------------------------
        $('input:radio[name="data[frmevalrule][additional_rate_flag]"]').change(function () {
            addRateChangeEvent($(this).val());
        });
//------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#add_usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'add_usage_sub_catg_id', '');
        });
//-----------------------------------------------------Additional---------------------------------------------------------------------------------
        $('input:radio[name="data[frmevalrule][additional1_rate_flag]"]').change(function () {
            addRate1ChangeEvent($(this).val());
        });
//------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#add1_usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'add1_usage_sub_catg_id', '');
        });
//------------------------------------------------------Save Rule-------------------------------------------------------------------------
        $("#btnSave").click(function () {
            //-------------------------------------------------------------------
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            //-------------------------------------------------------------------
            var action = $("#actionid").val();
            if (action != 'U' && action != 'SRU' && action != 'SRA') {
                $("#actionid").val('SV');
            }

            $("#hsrflg").val($('input:radio[name="data[frmevalrule][subrule_flag]"] option:selected').val());
            $('#frmevalrule').submit();

        });
//------------------------------------------------------------------------------------------------------------------------------------------------                                            
        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>/ValuationRules";
            return false;
        });

    });
//---------------------------------------------------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function ratecmpchangeEvent(cmpRate_flag) {
        if (cmpRate_flag == 'Y') {
            $("#rateCmpRow").show();
        } else {
            $("#rateCmpRow").hide();
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function addRateChangeEvent(addRate_flag) {
        if (addRate_flag == 'Y') {
            $("#additionalRateRow").show();
        } else {
            $("#additionalRateRow").hide();
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function addRate1ChangeEvent(addRate_flag) {
        if (addRate_flag == 'Y') {
            $("#additionalRate1Row").show();
        } else {
            $("#additionalRate1Row").hide();
        }
    }
//--------------------------------------------------------Get Usage Sub,Sub_sub Category -------------------------------------------------------------
    function getUsageList(usageMain, usageSub, listForId, forValue) {

        var forUsage = listForId.replace('add_', '');
        forUsage = forUsage.replace('cmp_', '');
        forUsage = forUsage.replace('add1_', '');
        $.post(host + "get" + forUsage, {usage_main_catg_id: usageMain, usage_sub_catg_id: usageSub}, function (data) {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#" + listForId + " option").remove();
            $("#" + listForId).append(sc);

            if (forValue) {
                // alert(listForId+ "  "+forValue);
                $("#" + listForId).val(forValue);
            }

        }, 'json');
    }

</script>
<?php echo $this->element("Property/rule_menu"); ?>

<?php
echo $this->Form->create('frmevalrule', array('id' => 'frmevalrule', 'class' => 'form-vertical'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblevalrule'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/valuation_rule_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body" id="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="rule_id_label" style="text-align: center;color: white; background-color: #E6B800"></label>
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="form-group">
                            <span id="fin_yr_idhide">
                                <label for="financial Year" class="control-label col-sm-2"><?php echo __('lblfineyer'); ?></label>
                                <div class="col-sm-2" >
                                    <?php
                                    echo $this->Form->input($name[37], array('options' => $finyearList, 'multiple' => false, 'id' => 'fin_year', 'label' => false, 'class' => 'form-control input-sm'));
                                    ?>
                                    <span id="fin_year_error" class="form-error"></span>
                                    <?php echo $this->Form->input('rule_id', array('id' => 'rule_id', 'type' => 'hidden', 'class' => 'form-control input-sm', 'value' => $this->Session->read('valuation_rule_id'))); ?>
                                    <span id="rule_id_error" class="form-error"></span>
                                    <?php echo $this->Form->input('us_id', array('type' => 'hidden', 'id' => 'sub_id')); ?>
                                    <span id="sub_id_error" class="form-error"></span>
                                    <?php echo $this->Form->input('usage_sub_sub_catg_id', array('id' => 'ussctg_id', 'type' => 'hidden', 'class' => 'form-control input-sm')); ?>
                                    <span id="ussctg_id_error" class="form-error"></span>
                                </div>
                            </span>   
                            <label for="Effective Date" class="control-label col-sm-2"><?php echo __('lbleffedate'); ?></label>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[38], array('id' => 'effective_date', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span id="effective_date_error" class="form-error"></span>
                            </div>


                        </div>
                    </div>

                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblusamaincat'); ?></label>
                            <div class="col-sm-4" > <?php echo $this->Form->input('usage_main_catg_id', array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span id="usage_main_catg_id_error" class="form-error"></span>
                            </div>

                            <label for="usage_sub_catg_id" class="control-label col-sm-2"><?php echo __('lblUsagesubcategoryname'); ?></label>
                            <div class="col-sm-4"> <?php echo $this->Form->input('usage_sub_catg_id', array('type' => 'select', 'options' => $rrrscatglist, 'empty' => '--Select Option--', 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm chosen-select')); ?>
                                <span id="usage_sub_catg_id_error" class="form-error"></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php //-------------------------Rule Description---------------------------?>
            <div class="box-body">
                <div class="well well-sm">
                    <div  class="rowht"></div>
                    <div class="row">

                        <div class="form-group">
                            <?php
                            foreach ($languagelist as $key => $langcode) {
                                ?>
                                <div class="col-md-3">
                                    <label><?php echo __('lblruledescen') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                        <span style="color: #ff0000">*</span>
                                    </label>    
                                    <?php echo $this->Form->input('evalrule_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'evalrule_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                    <span id="<?php echo 'evalrule_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                    </span>
                                </div>

                            <?php } ?>

                        </div>
                    </div> 
                    <!--                    DOLR CODE-->
                    <!--                    <div  class="rowht"></div>
                                        <div class="row">
                                            <div class="col-sm-12"  >
                                                <div class="form-group">
                                                    <label for="dolr_usage_code" class="control-label col-sm-2"><?php //echo __('lbldolrusagecode');    ?></label>
                                                    <div class="col-sm-2" ><?php //echo $this->Form->input('dolr_usage_code', array('type' => 'text', 'id' => 'dolr_usage_code', 'label' => false, 'class' => 'form-control input-sm'));                           ?></div>
                                                </div>
                                            </div>                       
                                        </div> -->
                    <!----------------------------------------->
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="contsruction_type_flag" class="col-sm-2 control-label"><?php echo __('lblconstuctiontye'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('contsruction_type_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'contsruction_type_flag')); ?></div>   
                            <span id="contsruction_type_flag_error" class="form-error"></span>
                            <label for="depreciation_flag" class="control-label col-sm-2"><?php echo __('lbldepreciation'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('depreciation_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'depreciation_flag')); ?></div> 
                            <span id="depreciation_flag_error" class="form-error"></span>
                            <label for="road_vicinity_flag" class="control-label col-sm-2"><?php echo __('lblroadvicinity'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('road_vicinity_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'road_vicinity_flag')); ?></div> 
                            <span id="road_vicinity_flag_error" class="form-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="user_defined_dependency1_flag" class="control-label col-sm-2"><?php echo __('lbluserdependencyflag1'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency1_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency1_flag')); ?></div> 
                            <span id="user_defined_dependency1_flag_error" class="form-error"></span>
                            <label for="user_defined_dependency2_flag" class="control-label col-sm-2"><?php echo __('lbluserdependencyflag2'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency2_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency2_flag')); ?></div> 
                            <span id="user_defined_dependency2_flag_error" class="form-error"></span>
                        </div>
                    </div>
                </div>    
            </div>
            <?php // Additional Rate------------------------------; ?>
            <div class="box-body">
                <div class="well well-sm">
                    <div class="row">
                        <div class="form-group">
                            <label for="Additional Rate Flag" class="control-label col-sm-4"><?php echo __('lblAdditionRateRequired'); ?> [RR1] </label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input($name[47], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'addRateFlag')); ?></div> 
                            <span id="additional_rate_flag_error" class="form-error"></span>
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row" id="additionalRateRow">

                        <div class="col-sm-12">

                            <div class="row">
                                <div class="form-group">
                                    <label for="usage_main_catg_id for Additional Rate" class="control-label col-sm-3"><?php echo __('lblusamaincat'); ?> </label>
                                    <label for="usage_sub_catg_id for Additional Rate" class="control-label col-sm-3"><?php echo __('lblsubcat'); ?></label> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3" ><?php echo $this->Form->input($name[59], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'add_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                        <span id="add_usage_main_catg_id_error" class="form-error"></span>
                                    </div>

                                    <div class="col-sm-3" ><?php echo $this->Form->input($name[60], array('type' => 'select', 'options' => $rr1scatglist, 'empty' => '--Select Option--', 'id' => 'add_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                        <span id="add_usage_sub_catg_id_error" class="form-error"></span>  
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php // Additional Rate 1 ------------------------------; 
                ?>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="Additional Rate 1 Flag" class="control-label col-sm-4"><?php echo __('lblAdditionRateRequired') . ' 1'; ?> [RR5] </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[69], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'addRateFlag_1')); ?></div> 
                            </div>
                        </div>
                        <div  class="rowht"></div>
                        <div class="row" id="additionalRate1Row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="usage_main_catg_id for Additional Rate 1" class="control-label col-sm-3"><?php echo __('lblusamaincat'); ?></label>
                                        <label for="usage_sub_catg_id for Additional Rate 1" class="control-label col-sm-3"><?php echo __('lblsubcat'); ?></label> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[66], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'add1_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                            <span id="add1_usage_main_catg_id_error" class="form-error"></span> 
                                        </div>
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[67], array('type' => 'select', 'options' => $rr5scatglist, 'empty' => '--Select Option--', 'id' => 'add1_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                            <span id="add1_usage_sub_catg_id_error" class="form-error"></span> 
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php //!--/********************* rate Comparism********************************/-- ?>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="Rate Compare Flag" class="control-label col-sm-4"><?php echo __('lblRateCompareRequired'); ?> [ABE]</label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[42], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                                <span id="rate_compare_flag_error" class="form-error"></span>
                            </div>
                        </div>  
                        <div  class="rowht"></div>
                        <div class="row" id="rateCmpRow">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="usage_main_catg_id for Compare Rate" class="control-label col-sm-3"><?php echo __('lblCmpUsamaincat'); ?></label>
                                        <label for="usage_sub_catg_id for Compare Rate" class="control-label col-sm-3"><?php echo __('lblCmpUsagesubcat'); ?></label>                                     
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[43], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'cmp_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                            <span id="cmp_usage_main_catg_id_error" class="form-error"></span>
                                        </div>
                                        <div class="col-sm-3" ><?php
                                            echo $this->Form->input($name[44], array('type' => 'select', 'options' => $cmpscatglist, 'empty' => '--Select Option--', 'id' => 'cmp_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm'));
                                            ?> <span id="cmp_usage_sub_catg_id_error" class="form-error"></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="TDR Applicable" class="control-label col-sm-4"><?php echo __('lblTDRApplicable'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[53], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'tdr_flag')); ?></div>                                                           
                                <span id="tdrFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="lblis_boundary_applicable" class="control-label col-sm-4"><?php echo __('lblis_boundary_applicable'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('is_boundary_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_boundary_applicable')); ?></div>                                                           
                                <span id="tdrFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="skip_val_flag" class="control-label col-sm-4"><?php echo __('lblskip_val_flag'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('skip_val_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'skip_val_flag')); ?></div>                                                           
                                <span id="tdrFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="tah_process_flag" class="control-label col-sm-4"><?php echo __('lbltah_process_flag'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('tah_process_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'tah_process_flag')); ?></div>                                                           
                                <span id="tdrFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="display_flag" class="control-label col-sm-4"><?php echo __('lbldisplay_flag'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('display_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'display_flag')); ?></div>                                                           
                                <span id="tdrFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="form-group">
                                <label for="refno" class="control-label  col-sm-4"><?php echo __('lblReferenceNo'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[46], array('id' => 'reference_no', 'type' => 'textarea', 'label' => false, 'placeholder' => 'Rule Reference', 'class' => 'form-control input-sm')); ?>
                                    <span id="reference_no_error" class="form-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row top-buffer">
                            <div class="form-group">
                                <label for="Rule Applicable For" class="control-label col-sm-3"><?php echo __('lblruleapplicable'); ?> </label>            
                                <label for="Rule Applicable For Urbon" class="control-label col-sm-1"><?php echo __('lblUrban') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[56], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_urban')); ?></div>                                                            
                                <span id="urbanFlag_error" class="form-error"></span>
                                <label for="Rule Applicable For Rural" class="control-label col-sm-1"><?php echo __('lblRural') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[57], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_rural')); ?></div>                                                            
                                <span id="ruralFlag_error" class="form-error"></span>
                                <label for="Rule Applicable For Influence" class="control-label col-sm-1"><?php echo __('lblInfluence') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[58], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_influence')); ?></div>                                                            
                                <span id="influenceFlag_error" class="form-error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="well well-sm">
                        <div class="row center" >
                            <div class="form-group">
                                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <button id="btnSave"  class="btn btn-info "  type="button" >
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>                           
                                <button id="btnExit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;<?php echo __('lblexit'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php //Display Rule List --   ?>                        
            </div>
        </div>
    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Js->writeBuffer(); ?> 

