<?php
echo $this->element("Registration/main_menu");
?>
<br>
<?php
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?> 


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblpaymentprocess'); ?></h3></center>
            </div>
            <div class="box-body">
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                       
                        <div class="col-sm-8">

                            <label for="" class="col-sm-4 control-label"><?php echo __('lblfeerule'); ?><span style="color: #ff0000"></span></label>
                            <div class="col-sm-8" > 
                                <?php echo $this->Form->input('fee_rule_id', array('type' => 'select', 'options' => array('empty' => '--Select--', $feerules), 'id' => 'feerule_id', 'label' => false, 'class' => 'form-control')); ?>
                                <?php echo $this->Form->input('article_id', array('type' => 'hidden', 'id' => 'article_id', 'label' => false, 'value' => $article_id)); ?>
                                <?php echo $this->Form->input('token_no', array('type' => 'hidden', 'id' => 'token_no', 'label' => false, 'value' => $token)); ?>

                            </div>
                        </div>
                    </div>

                </div>

                <div  class="rowht">&nbsp;</div>
                <fieldset class="scheduler-border ">
                    <legend class="scheduler-border"><?php echo __('lbllistofproperties'); ?></legend>
 
                    <table class="table table-striped table-bordered table-hover">
                        <thead style="background-color: rgb(204, 255, 229);">
                        <th style="width:10%;">
                            <?php echo __('lblaction'); ?>
                        </th>
                        <th>
                            <?php echo __('lblpropertydetails'); ?>
                        </th>
                        <th>
                            <?php echo __('lbllocation'); ?>
                        </th>
                        </thead>
                        <tbody>
                            <?php foreach ($property_list as $key => $property) { ?>
                                <tr>
                                    <td>
                                        <input type="radio" value="<?php echo $property[0]['property_id']; ?>" name="property_list" onclick="javascript: return getamount('<?php echo $property[0]['val_id']; ?>','<?php echo $property[0]['village_id']; ?>');">    
                                    </td>
                                    <td class="adress">
                                        <?php
                                        $prop_name = "";
                                        foreach ($property_pattern as $key1 => $pattern) {
                                            if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                $prop_name.= "  <b>" . $pattern[0]['pattern_desc_' . $lang] . " </b>: <small>" . $pattern[0]['field_value_' . $lang] . "</small>";
                                            }
                                        }
                                        echo substr($prop_name, 1);
                                        ?>
                                    </td>
                                    <td class="adress">
                                        <?php echo $property[0]['village_name_en']; ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                </fieldset>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8" id="ItemListDiv">


                        </div> 
                        <div class="col-sm-2"></div>
                    </div>
                </div>



                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-2" ></div> 
                    <div class="col-sm-8" > 
                        <fieldset class="scheduler-border ">
                            <legend class="scheduler-border"><?php echo __('lblconsiderationamt'); ?></legend>
                            <div class="row">
                                <div class="col-sm-4" > 
                                    <label><?php echo __('lblconsiderationamount'); ?></label>
                                </div>
                                <div class="col-md-8" > 
                                    <?php echo $this->Form->input('cons_amt', array('type' => 'text', 'id' => 'cons_amt', 'label' => false)); ?>
                                    <input type="hidden" class="form-control" readonly="readonly" id="val_amt">  
                                </div>
                            </div>
                            <div class="col-sm-2" ></div> <div class="col-sm-2" ></div> 
                        </fieldset> 

                    </div>
                    <!--                    <div class="col-sm-2" ></div> -->
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row">
                    <div class="col-sm-12 center">   
                        <?php echo $this->Form->button('Calculate', array('type' => 'Button', 'id' => 'btnCalcAndSave', 'class' => "btn btn-primary")); ?>
                        <?php echo $this->Form->button('View', array('type' => 'Button', 'class' => "btn btn-warning", 'id' => 'btnview', 'data-toggle' => "modal", 'data-target' => "#viewDataModal", 'disabled' => true)); ?> 
                    </div> 
                </div>

            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblstampdutycalamt'); ?></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive"  id="SDCalcDetail"  style="height:35vh; overflow-y: scroll;">

                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2"><label for="" class=" control-label"><?php echo __('lblTotal'); ?><span style="color: #ff0000"></span></label> </div>
                        <div class="col-sm-2"><?php echo $this->Form->input('sd_amt', array('label' => false, 'id' => 'sdAmount', 'style' => 'font-weight:bold;font-size:16px;', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?></div>

                        <input type="hidden"  id="sd_adj_flag" value="<?php echo $sd_adj_flag; ?>">
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div id="sd_adj_div">
                    <div class="row">
                        <div class="form-group">
                            <label for="old doc No." class="control-label col-sm-2" ><?php echo __('lblolddockid'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('adj_doc_no', array('type' => 'text', 'label' => false, 'id' => 'adj_doc_no', 'class' => 'form-control input-sm')); ?> </div>
                            <label for="old doc date" class="control-label col-sm-2" ><?php echo __('lblolddockdate'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('adj_doc_date', array('type' => 'text', 'label' => false, 'id' => 'adj_doc_date', 'class' => 'form-control input-sm')); ?> </div>
                            <label for="old doc date" class="control-label col-sm-2" ><?php echo __('lbladjustmentamt'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('adj_amt', array('type' => 'number', 'min' => 0, 'max' => 0, 'label' => false, 'id' => 'adj_amt', 'readonly' => TRUE, 'class' => 'form-control input-sm')); ?> </div>
                        </div>
                    </div>

                </div>
                <div  class="rowht">&nbsp;</div>
                <div id="delay_div">
                    <div class="row">
                        <div class="form-group">
                            <label for="Late Fee Amount" class="control-label col-sm-2" ><?php echo __('lblLateFee'); ?></label>   
                            <div class="col-sm-2">  <?php echo $this->Form->input('late_fee', array('type' => 'numeric', 'label' => false, 'id' => 'late_fee', 'class' => 'form-control input-sm', 'readonly' => TRUE)); ?> </div>
                            <?php echo $this->Form->input('late_fee_required', array('type' => 'hidden', 'label' => false, 'id' => "delay_flag", 'value' => $delay_flag, 'class' => 'form-control input-sm')); ?> 
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" style="text-align: center;">
                    <div class="form-group">                            
                        <input type="hidden"  id="continue_flag">

                        <button type="reset" style="width: 100px;" id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                        <button type="button" style="width: 100px;" id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnsave'); ?></button>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

   <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'readonly')) ?>
 
     <?php echo $this->form->end(); ?> 
<script>

    $(document).ready(function () {

        ($('#sd_adj_flag').val() == 'Y') ? $('#sd_adj_div').show() : $('#sd_adj_div').hide();
        ($('#delay_flag').val() == 'Y') ? $('#delay_div').show() : $('#delay_div').hide();

        $("#feerule_id").on('change', function () {

            var rulelist = '';
            //$('.article_rule_id input[type="checkbox"]').each(function () {
            //    if ($(this).prop('checked') === true) {
            var ruleid = $(this).val();
            // if (rulelist === '') {
            rulelist = ruleid;
            ///  }else{
            // rulelist = rulelist + ',' + ruleid; 
            // }
            // }
            //}); 
            if (rulelist === '') {
                $('#ItemListDiv').html('');
            } else {
                $.post('<?php echo $this->webroot; ?>getFeeRuleInputs', {feerule_id: rulelist}, function (data) {
                    $('#ItemListDiv').html(data);
                    $('input[name="property_list"]').prop('checked', false);
                });
            }


        });

        $("#btnCalcAndSave").click(function (e) {
            e.preventDefault();
            caculateMV();
            if ($('#frmid')[0].checkValidity()) {
                $.ajax(
                        {
                            type: 'post',
                            url: host + 'calculateFees',
                            data: $("#frmid").serialize(),
                            success: function (result)
                            {
                                $('#viewData').html(result);
                                $("#viewDataModal").modal('show');
                                $("#btnview").prop("disabled", false);
                                     getStmpCacl();
                            }
                        });
            } else {

                $("input").each(function () {
                    if ($(this).val() == "")
                    {
                        $(this).css("background-color", "##ff6666");
                    }
                    $(this).on('click', function () {

                        $("#time").val($(this).parent().attr('id'));



                    });
                });
            }
            return false;
        });

        $('#adj_doc_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            getAdjDocExsAmt();
        });
        $('#adj_doc_no').change(function () {
            getAdjDocExsAmt();
        });
    });

    var host = "<?php echo $this->webroot; ?>";
    function caculateMV() {
//        preventDefault();
        if ($('#frmid')[0].checkValidity()) {
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
        } else {

            $("input").each(function () {
                if ($(this).val() == "")
                {
                    $(this).css("background-color", "##ff6666");
                }
                $(this).on('click', function () {

                    $("#time").val($(this).parent().attr('id'));

                });
            });
        }
        return false;
    }

    function getamount(val_id,village_id)
    {

        $.post('<?php echo $this->webroot; ?>Citizenentry/get_valuation_amt', {val_id: val_id}, function (data)
        {

            if (data == 'a')
            {
                alert('Please valuate again');
                return false;
            } else
            {
                $('#frmFAA').val(data);
                $('#village_id').val(village_id);

            }


        });
    }
    getStmpCacl();
    function getStmpCacl() {
       
        $.post(host + 'viewSDCalc', {doc_token_no:<?php echo $token; ?>, fee_type_id: 1}, function (SDCalc) {
            $("#SDCalcDetail").html("");
            $("#SDCalcDetail").html(SDCalc);
            $('#sdAmount').val($('#onlineSD').val()+$('#counterSD').val());
        });
    }

    $('#btnNext').click(function () {
        $("#tblproperty_list tr:even").css("background-color", "#FFFFF");
        $("#tblproperty_list tr:odd").css("background-color", "#FFFFF");
        $.getJSON(host + 'getFeesCalcFlag', {doc_token_no: <?php echo $token; ?>}, function (data) {
            var prop_count = 0;
            $.each(data, function (index) {
                prop_count++;
                $("#" + index).css('background-color', '#FFA5A5');
            });
            if (prop_count !== 0) {
                alert('Sorry! You are not allowed to continue. \n Please Calculate Stamp Duty for all Property.');
                return false;
            } else {
                $("#frmid").submit();
                
            }
        });
    });

    function getAdjDocExsAmt() {
        var doc_no = $('#adj_doc_no').val();
        var doc_date = $('#adj_doc_date').val();
        if (doc_no != '' && doc_date != '') {
            $.post(host + 'getAdjDocExsAmt', {adj_doc_no: doc_no, adj_doc_date: doc_date}, function (exsAmt) {
                $('#adj_amt').val(exsAmt);
                $('#adj_amt').prop('readonly', false);
                $('#adj_amt').attr('max', exsAmt);

            });
        }
    }
</script>
<div id="viewDataModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Stamp Duty Calculation</h4>
            </div>
            <div class="modal-body" id="viewData">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>