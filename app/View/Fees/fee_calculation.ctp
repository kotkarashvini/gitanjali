<style>
    input:focus {
        background-color: #b3ffff;
    }
</style>
<script>

    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $('#tblEvalRule,tblEvalSubRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        $('#GBodyDiv').hide();
        $('#district_id,#taluka_id,#village_id,#corp_id').prop('disabled', true);
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        var host = "<?php echo $this->webroot; ?>";
//---------------------------------------------------------------------------------------------------------------------------------------------------------        
        $("#article_id").change(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
            if ($(this).val()) {
                $.post(host + "getFeeRuleList", {article_id: $(this).val()}, function (data)
                {
                    var sc = null;
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#fee_rule_id option").remove();
                    $("#fee_rule_id").append(sc);
                    if ($("#fee_rule_id").val()) {
                        getArticleFeeItems();
                    }
                }, 'json');
                checkDependancy();
            }
            else {
                $("#fee_rule_id option").remove();
                $("#article_items").html("");
            }
        });

//---------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#fee_rule_id").change(function () {
            if ($(this).val()) {
                getArticleFeeItems();
            }
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#btnCalcAndSave").click(function (e) {
            e.preventDefault();
            caculateMV();
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });

          
                $('#frmid').submit();
               // alert('calculate');
           
            return false;
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------
        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
    });
//---------------------------------------------------------------------------------------------------------------
    function checkDependancy() {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        $.post(host + 'getArticleGovBodyFlag', {article_id: $("#article_id option:selected").val()}, function (address_def_flag) {
            var def_flag = 'A';
            def_flag = address_def_flag.trim();
            if (def_flag === 'N') {
                $('#GBodyDiv').hide();
                $('#district_id,#taluka_id,#village_id,#corp_id').prop('disabled', true);
            } else if (def_flag === 'Y') {
                $('#GBodyDiv').show();
                $('#district_id,#taluka_id,#village_id,#corp_id').prop('disabled', false);
            }
        });
        return false;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    var host = "<?php echo $this->webroot; ?>";
    function getArticleFeeItems() {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        $.post(host + 'getFeeRuleInputs1', {feerule_id: $("#fee_rule_id option:selected").val()}, function (itemdata) {
            $("#article_items").html("");
            $("#article_items").html(itemdata);
            $(document).trigger('_page_ready');
        });
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    function caculateMV() {
//        preventDefault();
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
//        if ($('#frmid')[0].checkValidity()) {
        if ($('#article_id').val() && $('#fee_rule_id').val()) {
            $.ajax(
                    {
                        type: 'post',
                        url: host + 'calculateMV',
                        data: $("#frmid").serialize(),
                        success: function (result)
                        {
                            $("input[name='data[frm][OMV]']").val(result);
                        }
                    });
        }
        else {
          //  alert('fdhjksf');
           // return false;
            $("#frmid").submit();
        }
        return false;
    }
    //---------------------------------------------------------------------------------------------------------------------------------------------------------
</script>
<?php
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeecalculation'); ?></h3></center>
            </div>
            <div class="box-body">
                <div  class="rowht"></div>              
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Select Article" class="control-label col-sm-2" ><?php echo __('lblArticle'); ?></label>   
                        <div class="col-sm-6">
                            <?php echo $this->Form->input($field[9], array('type' => 'select', 'empty' => '--select--', 'options' => $articlelist, 'label' => false, 'multiple' => false, 'id' => 'article_id', 'class' => 'form-control input-sm')); ?> 
                            <span id="article_id_error" class="form-error"><?php //echo $errarr['party_fname_ll_error'];                          ?></span>
                        </div>
                    </div>
                </div>

                <div id="GBodyDiv">
                    <?php echo $this->Element('common/address'); ?>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Select feerule" class="control-label col-sm-2" ><?php echo __('lblfeerule'); ?></label>   
                        <div class="col-sm-6">  <?php echo $this->Form->input($field[10], array('type' => 'select', 'label' => false, 'multiple' => false, 'id' => 'fee_rule_id', 'class' => 'form-control input-sm')); ?> </div>
                    </div>
                </div>

                <div  class="rowht"></div>              
                <div class="row" hidden="true">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group">                               
                                <div class="col-sm-8" > <label for="usage_cat_id" class="control-label title1" ><?php echo __('lblfeerule'); ?></label>                                        </div>
                                <div class="col-sm-4" style="text-align: right"><?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">                                   
                                <div class="usage-list" id="usage-list">
                                    <div class="col-sm-12" ><?php echo $this->Form->input($field[8], array('id' => 'feeRuleId', 'label' => false, 'multiple' => 'checkbox', 'class' => 'form-control input-sm')); ?></div>            
                                </div>
                            </div>
                        </div> 
                    </div>

                </div>
                <div id="article_items" class="col-sm-12">

                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="amt" class="col-sm-2 control-label"><?php echo __('lblconsiderationamount'); ?></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('cons_amt', array('type' => 'text', 'label' => false, 'id' => 'cons_amt', 'class' => 'form-control input-sm')); ?>                             
                            <?php // echo $this->Form->input('total_sd', array('type' => 'hidden', 'label' => false, 'id' => 'sdAmount', 'value' => $citizen_token_no));       ?> 
                            <span id="cons_amt_error" class="form-error"><?php // echo $errarr['cons_amt_error'];                              ?></span>
                        </div>                           
                    </div>
                    <input type="hidden" class="form-control" readonly="readonly" id="val_amt">  
                    <input type="hidden" class="form-control" readonly="readonly" id="article_id" value="<?php // echo $article_id;                              ?>">  
                </div> 
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div> <div  class="rowht"></div>   <div  class="rowht"></div>     
                <div class="row center">
                    <div class="form-group">                               
                        <?php
                        echo $this->Form->button(__('lblsaveandcal'), array('id' => 'btnCalcAndSave', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
//                                echo $this->Form->button(__('lblview'), array('id' => 'btnView', 'class' => 'btn btn-primary')) . "&nbsp;&nbsp;";
                        echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                        ?>
                    </div>
                    <div class="hidden Input">
                        <?php echo $this->Form->input('action', array('id' => 'actionid', 'value' => $hfaction, 'type' => 'hidden')); ?>
                    </div>
                    <div id="viewData" align="center">
<?php echo @$result; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
