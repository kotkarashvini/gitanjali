<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>
<script>
    $(function () {
        $("#draggable").draggable();
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#prop_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        $("input:radio[name='data[frm][old_data_flag]']").change(function () {
             if ($(this).val() == 'Y') {
                $('#adj_amt').prop('readonly', true);
            } else {
                $('#adj_amt').prop('readonly', false);
                $('#adj_amt').removeAttr('max');
                
            }
        });

        $('#fee_calculation_div').hide();
        getExemption();
        $('#exemption_div').hide();
        $('input:radio[name="data[frm][exemption_flag]"]').change(function () {
            ($(this).val() == 'Y') ? ($('#exemption_div').show()) : ($('#exemption_div').hide());
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
        ($('#sd_adj_flag').val() == 'Y') ? $('#sd_adj_div').show() : $('#sd_adj_div').hide();
        ($('#delay_flag').val() == 'Y') ? $('#delay_div').show() : $('#delay_div').hide();
        //------------------------------------------------------------------------------------------------------------------------------------------------
        calculateSD();
        getStmpCacl();
        var host = "<?php echo $this->webroot; ?>";
        //------------------------------------------------------------------------------------------------------------------------------------------------
        $.getJSON(host + "getFeeRuleList", {article_id: $('#article_id').val(), fee_type_id: 1}, function (data)
        {
            var sc = "<option value=''>--Select--</option>";
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#feeRule_id option").remove();
            $("#feeRule_id").append(sc);
            getArticleFeeItems();
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
        $("#feeRule_id").change(function () {
            if ($(this).val()) {
                getArticleFeeItems();
                $('#fee_calculation_div').show();
            } else {
                $('#fee_calculation_div').hide();
            }

        });
        //------------------------------------------------------------------------------------------------------------------------------------------------

        $("#btnCalcAndSave").click(function (e) {
            if ($('#feeRule_id').val()) {
                e.preventDefault();
                caculateMV();
                calculateSD();
                getStmpCacl();
                update_sd();
            } else {
                alert('Please Select Fee Rule');
                $('#feerule_id').focus();
                return false;
            }
        });
        $('#exemption_feeRule_id').change(function () {
            $.post(host + 'getFeeRuleInputs', {feerule_id: $(this).val()}, function (ExmItemData) {
                $("#ExmArticle_items").html("");
                $("#ExmArticle_items").html(ExmItemData);
                $("#FAS").val($('#onlineSDAmount').val());
                $('#FAS').prop('readOnly', true);
                set_values();
            });
        });
        $("#btnCalculateExemption").click(function () {
            var exm_arg = {doc_token_no: $('#token_no').val(), article_id: 9998, fee_rule_id: $('#exemption_feeRule_id').val(), FAS: $('#onlineSDAmount').val(), FAG: $('#FAG').val()};
            $.ajax({
                type: 'post',
                url: host + 'calculateFees',
                data: exm_arg,
//                data: ,
                success: function (result) {
                    getExemption();
                }
            });
            return false;
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
        $('#btnNext').click(function () {
            $("#tblproperty_list tr:even").css("background-color", "#eeeeee");
            $("#tblproperty_list tr:odd").css("background-color", "#ffffff");
            $.getJSON(host + 'getFeesCalcFlag', {doc_token_no: $('#token_no').val()}, function (data) {
                var prop_count = 0;
                $.each(data, function (index) {
                    prop_count++;
                    $("#" + index).css('background-color', '#FFA5A5');
                });
                if (prop_count !== 0) {
                    alert('Sorry! You are not allowed to continue. \n Please Calculate Stamp Duty for all Property.');
                    return false;
                } else {
                    if ($('#frmid')[0].checkValidity()) {
                        $('#onlineSDAmount').val($('#onlineSD').val());
                        $('#counterSDAmount').val($('#counterSD').val());
                        $("#frmid").submit();
                    } else {
                        if ($("input:radio[name='data[frm][old_data_flag]']").val() == 'N') {
                            $("#frmid").submit();
                        } else {
                            alert('adjustment amount should be between ' + $('#adj_amt').attr('min') + ' and ' + $('#adj_amt').attr('max'));
                            return false;

                        }
                    }
                }
            });
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
        $('#online_adj_doc_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            getAdjDocExsAmt();
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
        $('#online_adj_doc_no').change(function () {
            getAdjDocExsAmt();
        });
        //------------------------------------------------------------------------------------------------------------------------------------------------
    });
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    var host = '<?php echo $this->webroot; ?>';
    function getExemption() {
        $.post(host + 'viewExemption', {doc_token_no: $('#token_no').val()}, function (exmData) {
            $("#exemptionRecord").html("");
            $("#exemptionRecord").html(exmData);
        });
    }
    function calculateSD() {
        if ($('#frmid')[0].checkValidity()) {
            if ($('#feeRule_id').val() != '' || $('#feeRule_id').val() != 0 || $('#feeRule_id').val() != 'NaN') {
                $.ajax({
                    type: 'post',
                    url: host + 'calculateFees',
                    data: $("#frmid").serialize(),
                    success: function (result) {
//                        $('#viewData').html(result);

                        getStmpCacl();
                    }
                });
            } else {
                alert('Please Select valuation Rule');
                $('#feeRule_id').focus();
            }
        } else {
            $("input").each(function () {
                if ($(this).val() == "") {
                    $(this).css("background-color", "##ff6666");
                }
            });
        }
        return false;
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    function update_sd() {
        $.ajax({
            type: 'post',
            url: host + 'updateSD',
            data: $("#frmid").serialize(),
            success: function (result) {
                if (result == 1) {
                    alert('SD Updated');
                }
//                $('#ulb_type_id').val('');
            }

        });
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    function getStmpCacl() {
        $.post(host + 'viewSDCalc', {doc_token_no: $('#token_no').val(), fee_type_id: 1, 'lang': $('#lang').val()}, function (SDCalc) {
            $("#SDCalcDetail").html("");
            $("#SDCalcDetail").html(SDCalc);
            $('#onlineSDAmount').val($('#onlineSD').val());
            $('#counterSDAmount').val($('#counterSD').val());
            $('#tmpTotalSD').val(+$('#onlineSD').val() + +$('#counterSD').val());
        });
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    function getArticleFeeItems() {
//        alert($('#token_no').val());
        $.post(host + 'getFeeRuleInputs', {feerule_id: $("#feeRule_id option:selected").val(), 'doc_token_no': $('#token_no').val(), 'lang': $('#lang').val()}, function (itemdata) {
            $("#article_items").html("");
            $("#article_items").html(itemdata);
            set_values();
        });
    }
    //------------------------------------------------------------------------------------------------------------------------------------------------
    function set_values() {
        var repeat = 0;
        $("input[name='data[frm][FAA]']").val($('#val_amt').val());
    }
//------------------------------------------------------------------------------------------------------------------------------------------------
    function caculateMV() {
        if ($('#feeRule_id').val()) {
            if ($('#frmid')[0].checkValidity()) {
                $.ajax({
                    type: 'post',
                    url: host + 'calculateMV',
                    data: $("#frmid").serialize(),
                    success: function (result) {
                        $("input[name='data[frm][OMV]']").val(result);
                        var cons_amt = Math.round($("input[name='data[frm][cons_amt]']").val());
                        $("input[name='data[frm][cons_amt]']").val((!cons_amt) ? Math.round(result) : cons_amt);
                    }
                });
            } else {
                $("input").each(function () {
                    if ($(this).val() == "") {
                        $(this).css("background-color", "##ff6666");
                    }
                    $(this).on('click', function () {
                        // $("#time").val($(this).parent().attr('id'));
                    });
                });
            }
        } else {
            alert('Please Select Rule');
            return false;
        }
//        preventDefault();

        return false;
    }
//------------------------------------------------------------------------------------------------------------------------------------------------
    function getamount(val_id, ulb_id, land_type_id) {
        $('#ulb_type_id').val(ulb_id);
        $('#land_type_id').val(land_type_id);
        $.post(host + 'Citizenentry/get_valuation_amt', {val_id: val_id}, function (data) {
            if (data == 'a') {
                alert('Please valuate again');
                return false;
            } else {
                $('#val_amt').val(data);
                $("input[name='data[frm][FAA]']").val(Math.round(data));
//                $("input[name='data[frm][FAR]']").val(land_type_id);
                $("input[name='data[frm][cons_amt]']").val(Math.round(data));
            }
        });
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    function getAdjDocExsAmt() {
        var doc_no = $('#online_adj_doc_no').val();
        var doc_date = $('#online_adj_doc_date').val();
        if (doc_no != '' && doc_date != '') {
            $.post(host + 'getAdjDocExsAmt', {'adj_doc_no': doc_no, 'adj_doc_date': doc_date}, function (exsAmt) {
                $('#adj_amt').val(exsAmt);
                $('#adj_amt').prop('readonly', false);
                $('#adj_amt').attr('max', exsAmt);
            });
            $.post(host + 'getAdjDocExsAmtDetail', {'adj_doc_no': doc_no, 'adj_doc_date': doc_date}, function (oldDocDetail) {
                $('#adj_doc_detail').html(oldDocDetail);
            });
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------

</script>

<?php echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical')); ?>


<?php
//echo $this->element("Citizenentry/stampduty_validationscript");
echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
//clientside validation
?>

<!--<div><label>Amount $
        <input type="number" placeholder="0.00" required name="price" min="0" value="0" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.parentNode.parentNode.style.backgroundColor = /^\d+(?:\.\d{1,2})?$/.test(this.value) ? 'inherit' : 'red'"></label>
</div>-->
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblstampduty'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?> :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('token_no', array('label' => false, 'id' => 'token_no', 'value' => $citizen_token_no, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            <?php echo $this->Form->input('lang', array('type' => 'hidden', 'id' => 'lang', 'value' => $lang)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblfeecalculation'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Select feerule" class="control-label col-sm-2" ><?php echo __('lblfeerule'); ?></label>   
                        <div class="col-sm-6">  <?php echo $this->Form->input('fee_rule_id', array('type' => 'select', 'label' => false, 'multiple' => false, 'empty' => '--select--', 'id' => 'feeRule_id', 'class' => 'form-control input-sm')); ?> </div>
                        <?php echo $this->Form->input('article_id', array('type' => 'hidden', 'label' => false, 'value' => $this->Session->read('article_id'), 'id' => 'feeRule_id', 'class' => 'form-control input-sm')); ?> 
                        <?php echo $this->Form->input('FAI', array('type' => 'hidden', 'label' => false, 'value' => $this->Session->read('no_of_pages'), 'id' => 'no_of_pages', 'class' => 'form-control input-sm')); ?> 
                    </div>
                </div>
                <br>
                <?php if ($property_list) { ?>
                    <div class="table-responsive" style="height:35vh; overflow-y: scroll;">
                        <table class="table table-striped table-bordered table-hover" id="tblproperty_list">
                            <thead>
                                <tr>
                                    <th class="center width10">
                                        <?php echo __('lblaction'); ?>
                                    </th>
                                    <th class="center">
                                        <?php echo __('lblproid'); ?>
                                    </th>
                                    <th class="center">
                                        <?php echo __('lblpropertydetails'); ?>
                                    </th>
                                    <th>
                                        <?php echo __('lblusage'); ?>
                                    </th>
                                    <th class="center">
                                        <?php echo __('lbllocation'); ?>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($property_list as $key => $property) { ?>
                                    <tr id="<?php echo $property[0]['property_id']; ?>">
                                        <td class="tblbigdata">                                          
                                            <input type="radio" value="<?php echo $property[0]['property_id']; ?>" name="property_list" onclick="javascript: return getamount('<?php echo $property[0]['val_id']; ?>', '<?php echo $property[0]['ulb_type_id']; ?>', '<?php echo $property[0]['developed_land_types_id']; ?>');">   
                                        </td>
                                        <td class="tblbigdata"><?php echo $property[0]['property_id']; ?></td>
                                        <td class="tblbigdata">
                                            <?php
                                            $prop_name = "";
                                            foreach ($property_pattern as $key1 => $pattern) {
                                                if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                    $prop_name .= "  " . $pattern[0]['pattern_desc_' . $lang] . " : <small>" . $pattern[0]['field_value_' . $lang] . "</small><br>";
                                                }
                                            }
                                            echo substr($prop_name, 1);
                                            ?>
                                        </td>
                                        <td class="tblbigdata">
                                            <?php echo $property[0]['evalrule_desc_' . $lang]; ?>
                                        </td>
                                        <td class="tblbigdata">
                                            <?php echo $property[0]['village_name_' . $lang]; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>                       
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="box box-primary" id="fee_calculation_div">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblfeecalculation'); ?></h3>
            </div>
            <div class="box-body">
                <div  class="rowht">&nbsp;</div>
                <div class="row" id="article_items">
                    <div class="form-group" >
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="amt" class="col-sm-3 control-label"><?php echo __('lblconsiderationamount'); ?></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('cons_amt', array('type' => 'text', 'label' => false, 'id' => 'cons_amt', 'class' => 'form-control input-sm')); ?>                             
                            <?php // echo $this->Form->input('total_sd', array('type' => 'hidden', 'label' => false, 'id' => 'sdAmount', 'value' => $citizen_token_no));    ?> 
                            <span id="cons_amt_error" class="form-error"><?php echo $errarr['cons_amt_error']; ?></span>
                        </div>                           
                    </div>
                    <input type="hidden" class="form-control" readonly="readonly" id="val_amt">  
                    <input type="hidden" class="form-control" readonly="readonly" id="article_id" value="<?php echo $article_id; ?>">  
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <div class="col-sm-12">
                            <?php echo $this->Form->button(__('lblsaveandcal'), array('id' => 'btnCalcAndSave', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;"; ?>
                        </div>
                    </div>
                </div>
                <div class="row" id="viewData"> 

                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblstampdutycalamt'); ?></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive"  id="SDCalcDetail"  style="height:35vh; overflow-y: scroll;">                    
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2" ><?php echo __('lblonlinepay'); ?></label>
                        <div class="col-sm-2"><?php echo $this->Form->input('online_sd_amt', array('label' => false, 'id' => 'onlineSDAmount', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>
                        <label for="" class="control-label col-sm-2" ><?php echo __('lblcounterpay'); ?></label>
                        <div class="col-sm-2"><?php echo $this->Form->input('counter_sd_amt', array('label' => false, 'id' => 'counterSDAmount', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>
                        <label for="" class=" control-label col-sm-2"><?php echo __('lblTotal'); ?><span style="color: #ff0000"></span></label>  
                        <div class="col-sm-2"><?php echo $this->Form->input('total', array('label' => false, 'id' => 'tmpTotalSD', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?> </div>                            
                        <input type="hidden"  id="sd_adj_flag" value="<?php echo $sd_adj_flag; ?>">
                    </div>
                </div>
            </div>
        </div>
        <!---------------------------------------------------------- exemption-----------------------------------------------------------------------------------> 
        <?php if ($exemption == 'Y') { ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder"><?php echo __('lblFeeExemption'); ?></h3>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2" ><?php echo __('lblhaveexemption'); ?></label>
                            <div class="col-sm-3"><?php echo $this->Form->input('exemption_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select')); ?></div> 
                        </div>
                    </div>
                </div>
                <div id="exemption_div">                
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <label for="Select feerule" class="control-label col-sm-2" ><?php echo __('lblExemptionFeerule'); ?></label>   
                                <div class="col-sm-6">  <?php echo $this->Form->input('exemption_fee_rule_id', array('type' => 'select', 'label' => false, 'multiple' => false, 'options' => $exemption_rule, 'empty' => '--select--', 'id' => 'exemption_feeRule_id', 'class' => 'form-control input-sm')); ?> </div>                            
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row" id="ExmArticle_items">
                            <div class="form-group" >
                            </div>
                        </div>                    
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group center">
                                <div class="col-sm-12">
                                    <?php echo $this->Form->button(__('lblsaveandcal'), array('id' => 'btnCalculateExemption', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;"; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="exemptionRecord"> 
                        </div>
                        <div class="notice" align="center"><b><?php echo __('lblnote'); ?><?php echo __('lblfinalstampdutyexemption'); ?></b></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!---------------------------------------------------------------------------stamp duty adjustment---------------------------------------------------->
        <div class="box box-primary" id="sd_adj_div">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblstampdutyadjust'); ?></h3>
            </div>
            <div  class="rowht">&nbsp;</div>
            <div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="old doc No." class="control-label col-sm-2" ><?php echo __('lblolddockid'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('online_adj_doc_no', array('type' => 'text', 'label' => false, 'id' => 'online_adj_doc_no', 'class' => 'form-control input-sm')); ?> 
                                <span id="online_adj_doc_no_error" class="form-error"><?php echo $errarr1['online_adj_doc_no_error']; ?></span>
                            </div>

                            <label for="old doc date" class="control-label col-sm-2" ><?php echo __('lblolddockdate'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('online_adj_doc_date', array('type' => 'text', 'label' => false, 'id' => 'online_adj_doc_date', 'class' => 'form-control input-sm', 'readOnly')); ?> 
                                <span id="online_adj_doc_date_error" class="form-error"><?php // echo $errarr1['online_adj_doc_date_error'];                                         ?></span>
                            </div>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblofficename'); ?></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('link_office_id', array('type' => 'select', 'label' => false, 'id' => 'link_office_id', 'class' => 'form-control input-sm', 'options' => $office)); ?>
                                <span id="link_office_id_error" class="form-error"><?php //echo $errarr['local_language_id_error'];        ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group">
                            <label for="" class="control-label col-sm-2" ><?php echo __('lblolddataflag'); ?></label>   
                            <div class="col-sm-2">  
                                <?php echo $this->Form->input('old_data_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'old_data_flag')); ?>
                            </div>

                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="online Adjustment Amount" class="control-label col-sm-2" ><?php echo __('lbladjustmentamt'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('online_adj_amt', array('type' => 'number', 'min' => 0, 'max' => 0, 'label' => false, 'id' => 'adj_amt', 'readonly' => TRUE, 'class' => 'form-control input-sm')); ?> 
                                <!--<span id="online_adj_amt_error" class="form-error"><?php // echo $errarr1['online_adj_amt_error'];                                         ?></span>-->
                            </div>
                            <div  class="col-sm-6" id="adj_doc_detail">

                            </div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
            </div>
        </div>


        <div class="box box-primary" hidden='true'>
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblLateFee'); ?></h3>
            </div>
            <div id="delay_div" class="center">
                <div class="row">
                    <div class="form-group">
                        <label for="Late Fee Amount" class="control-label col-sm-2" ><?php echo __('lblLateFee'); ?></label>   
                        <div class="col-sm-2">  <?php echo $this->Form->input('late_fee', array('type' => 'numeric', 'label' => false, 'id' => 'late_fee', 'class' => 'form-control input-sm', 'readonly' => TRUE)); ?> </div>
                        <?php echo $this->Form->input('late_fee_required', array('type' => 'hidden', 'label' => false, 'id' => "delay_flag", 'value' => $delay_flag, 'class' => 'form-control input-sm')); ?> 
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div  class="rowht">&nbsp;</div>
            <div class="row center" >
                <div class="form-group">                            
                    <input type="hidden"  id="continue_flag">
                    <?php echo $this->Form->input('ulb_type_id', array('type' => 'hidden', 'label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm')); ?> 
                    <?php echo $this->Form->input('FAR', array('type' => 'hidden', 'label' => false, 'id' => 'land_type_id', 'class' => 'form-control input-sm')); ?> 
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                    <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                    <button type="reset"  id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                    <button type="button"  id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnsave') . ' & ' . __('btnnext'); ?></button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php echo $this->Form->end(); ?>
