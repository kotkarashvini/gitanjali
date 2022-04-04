<?php
$tokenval = $this->Session->read("Selectedtoken");
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
$doc_lang = $this->Session->read('doc_lang');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="col-sm-12"> 
                    <div class="row">

                        <label for="" class="col-sm-2 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                        </div>

                        <div class="col-sm-4 pull-right">
                            <ul class="pull-right list-inline"> 
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b><?php echo __('lblalreadyhavevalation'); ?></b> <span class="caret"></span></a>
                                    <ul id="login-dp" class="dropdown-menu">
                                        <li>
                                            <div class="row">
                                                <div class="col-md-12"> 
                                                    <?php
                                                    echo $this->Form->create('copyvaluation', array(
                                                        'url' => array('controller' => 'Citizenentry', 'action' => 'copyvaluation'),
                                                        'id' => 'copyvaluation'
                                                    ));
                                                    ?>
                                                    <div class="form-group">
                                                        <label  for="Enter Valuation ID"><?php echo __('lblentervaluationid'); ?></label>
                                                        <?php echo $this->Form->input('valuation_id', array('label' => false, 'id' => 'valuation_id', 'type' => 'text', 'class' => 'form-control')); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary btn-block"><?php echo __('lblbtnAdd'); ?></button>
                                                    </div>
                                                    <?php echo $this->Form->end(); ?>
                                                </div>

                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Form->create('propertyscreennew', array('url' => array('controller' => 'citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken')), 'id' => 'propertyscreennew'));

echo $this->element("Property/screen");
?>


<script>
    $(document).ready(function () {
        // $("#loader").hide();
        $("#fin_yr_idhide").fadeOut();
        $('#prop_list_tbl').DataTable();
        if ($('#village_id').val() != '') {
            $("#city_village").show();
        }
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post('<?php echo $this->webroot; ?>Citizenentry/multiple_property_allowed', {csrftoken: csrftoken}, function (data)
        {

            if (data == 'N')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_property_count', {csrftoken: csrftoken}, function (data1)
                {

                    if (<?php echo $this->Session->read("article_id"); ?> == 32) {

                        if (data1 == 2)
                        {
                            $('#propentry').hide();
                            $('#valuationscreen').hide();
                        } else {
                            $('#propentry').show();
                            $('#valuationscreen').show();
                        }
                    } else {
                        if (data1 > 0)
                        {
<?php if (isset($prop_result) && !empty($prop_result)) { ?>
                                $('#propentry').show();
                                $('#valuationscreen').show();
<?php } else { ?>
                                $('#propentry').hide();
                                $('#valuationscreen').hide();
<?php } ?>

                        } else
                        {
                            $('#propentry').show();
                            $('#valuationscreen').show();
                        }
                    }
                });

            } else
            {
                if (<?php echo $this->Session->read("article_id"); ?> == 32) {
                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_property_count', {csrftoken: csrftoken}, function (data1)
                    {

                        if (data1 == 2)
                        {
                            $('#propentry').hide();
                            $('#valuationscreen').hide();
                        }
                    });
                } else {
                    $('#propentry').show();
                    $('#valuationscreen').show();
                }
            }


        });
        $("#add_attribute").click(function () {

            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $("#add_attribute").html('Please Wait...');
            var currentElement = $('#district_id');
            if (!$.isNumeric(currentElement.val()))
            {
                currentElement.parent().addClass('has-error');
                currentElement.css("background-color", "#FFC0CB");
                currentElement.focus();
                $("#add_attribute").html('Add');
                alert("Please Select Location");
                return 0;
            } else {
                currentElement.parent().removeClass('has-error');
                currentElement.css("background-color", "#FFFFFF");
            }

            var currentElement = $('#taluka_id');
            if (!$.isNumeric($('#taluka_id').val()) && !$.isNumeric($('#corp_id').val()))
            {
                currentElement.parent().addClass('has-error');
                $('#corp_id').parent().addClass('has-error');
                currentElement.css("background-color", "#FFC0CB");
                $('#corp_id').css("background-color", "#FFC0CB");
                currentElement.focus();
                $("#add_attribute").html('Add');
                alert("Please Select Location");
                return 0;
            } else {
                currentElement.parent().removeClass('has-error');
                $('#corp_id').parent().removeClass('has-error');
                currentElement.css("background-color", "#FFFFFF");
                $('#corp_id').css("background-color", "#FFFFFF");
            }

            var currentElement = $('#village_id');
            if (!$.isNumeric(currentElement.val()))
            {
                currentElement.parent().addClass('has-error');
                currentElement.css("background-color", "#FFC0CB");
                currentElement.focus();
                $("#add_attribute").html('Add');
                alert("Please Select Location");
                return 0;
            } else {
                currentElement.parent().removeClass('has-error');
                currentElement.css("background-color", "#FFFFFF");
            }

            var elements = [];
            elements.push($('#level1_id'));
            elements.push($('#level2_id'));
            elements.push($('#level3_id'));
            elements.push($('#level4_id'));
            elements.push($('#level1_list_id'));
            elements.push($('#level2_list_id'));
            elements.push($('#level3_list_id'));
            elements.push($('#level4_list_id'));
            var errflag = 0;
            $.each(elements, function (index, currentElement) {
                if ($(currentElement).children('option').length > 1 && $(currentElement).is(":visible") && !$.isNumeric(currentElement.val())) {
                    currentElement.parent().addClass('has-error');
                    currentElement.css("background-color", "#FFC0CB");
                    currentElement.focus();
                    errflag = 1;
                } else {
                    currentElement.parent().removeClass('has-error');
                    currentElement.css("background-color", "#FFFFFF");
                }
            });
            if (errflag == 0)
            {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                $.post("<?php echo $this->webroot; ?>Citizenentry/validatesurveynumbers",
                        {
                            district: $("#district_id").val(),
                            landtype: $("#Developedland").val(),
                            taluka: $("#taluka_id").val(),
                            council: $("#corp_id").val(),
                            village: $("#village_id").val(),
                            lavel1: $("#level1_id").val(), lavel1_list: $("#level1_list_id").val(),
                            lavel2: $("#level_2_desc_eng").val(), lavel2_list: $("#level2_list_id").val(),
                            lavel3: $("#level_3_desc_eng").val(), lavel3_list: $("#level3_list_id").val(),
                            lavel4: $("#level_4_desc_eng").val(), lavel4_list: $("#level4_list_id").val(),
                            attribute_value: $("#attribute_value").val(),
                            attribute_id: $("#attribute_id").val(),
                            csrftoken: csrftoken

                        },
                function (data, status) {
                    if (data.trim() === 'success')
                    {
                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                        $.post("<?php echo $this->webroot; ?>Citizenentry/add_property_attribute",
                                {
                                    attribute_id: $("#attribute_id").val(),
                                    attribute_value: $("#attribute_value").val(),
                                    attribute_value1: $("#attribute_value1").val(),
                                    attribute_value2: $("#attribute_value2").val(),
                                    type: 'S',
                                    csrftoken: csrftoken
                                },
                        function (data, status) {

                            $("#prop_attribute").html(data);
                            $("#add_attribute").html('Add');
                            $("#attribute_value").val('');
                            $("#attribute_value1").val('');
                            $("#attribute_value2").val('');
                            $("#attribute_value").css("background-color", "white");
                        });
                    } else {

                        $("#add_attribute").html('Add');
                        $("#attribute_value").css("background-color", "#f0ad4e");
                    }

                });
            } else {
                alert("Please Select Location");
                $("#add_attribute").html('Add');
            }




        });
        $("#add_attribute_p").click(function () {

            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $("#add_attribute_p").html('Please Wait...');
            $.post("<?php echo $this->webroot; ?>Citizenentry/add_property_attribute",
                    {
                        attribute_id: $("#attribute_id_p").val(),
                        attribute_value: $("#attribute_value_p").val(),
                        attribute_value1: $("#attribute_value1_p").val(),
                        attribute_value2: $("#attribute_value2_p").val(),
                        type: 'P',
                        csrftoken: csrftoken
                    },
            function (data, status) {

                $("#prop_attribute_p").html(data);
                $("#add_attribute_p").html('Add');
                $("#attribute_value_p").val('');
                $("#attribute_value1_p").val('');
                $("#attribute_value2_p").val('');
                // $('#prop_attribute_tbl').DataTable();


            });
        });
//        $("#village_id").on('change', function () {
//            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
//            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 1, behavioral_id: 1, csrftoken: csrftoken}, function (data)
//            {
//
//
//                $("#behavioral_patterns").html(data);
//            });
//        });
         $("#btnSave").click(function () {

            $.post('<?php echo $this->webroot; ?>Citizenentry/check_property_attribute', {
                csrftoken: csrftoken

            }, function (data)
            {
                if (data == 'Y') {
                    
                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_prohibited_prop', {village_id: $("#village_id").val(), csrftoken: csrftoken}, function (data)
                    {
                        var obj = jQuery.parseJSON(data);
                        if (obj.status === 1) {
                            $("#actiontype").val(3);
                            $("#propertyscreennew").submit();
                        } else if (obj.status === 2)
                        {
                            $("#prohibited_id").val(obj.prohibited_id);
                            $("#prohibited_flag").val('Y');
                            alert(obj.msg);
                            $("#actiontype").val(3);
                            $("#propertyscreennew").submit();

                        } else if (obj.status === 3) {
                            alert(obj.msg);
                            return false; 
                        }
                        else if (obj.status === 4) {
                            alert(obj.msg);
                            return false;
                        }

                    }); 

                } else {
                    alert('Add Property Addtribute');
                    return false;
                }

            });
        });
   

        $("#check_prohibition").click(function () {          
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_prohibited_prop', {village_id: $("#village_id").val(), csrftoken: csrftoken}, function (data)
            {
                var obj = jQuery.parseJSON(data);
                alert(obj.msg);               
            });
        });

        $("#attribute_id").change(function () {
            // var attribute_id=$("#attribute_id").val();
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            if ($.isNumeric(this.value))
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_attribute_subpart', {attribute_id: this.value, csrftoken: csrftoken}, function (data)
                {
                    if (data == 'NA') {
                        $("#attribute_id1_div").hide();
                        $("#attribute_id2_div").hide();
                        $("#attribute_value").val('');
                        $("#attribute_value1").val('');
                        $("#attribute_value2").val('');
                    } else if (data == 'A') {
                        $("#attribute_id1_div").show();
                        $("#attribute_id2_div").show();
                    }

                });
            }
        });
        $("#attribute_id_p").change(function () {
            // var attribute_id=$("#attribute_id").val();
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            if ($.isNumeric(this.value))
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/check_attribute_subpart', {attribute_id: this.value, csrftoken: csrftoken}, function (data)
                {
                    if (data == 'NA') {
                        $("#attribute_id1_p_div").hide();
                        $("#attribute_id2_p_div").hide();
                        $("#attribute_value_p").val('');
                        $("#attribute_value1_p").val('');
                        $("#attribute_value2_p").val('');
                    } else if (data == 'A') {
                        $("#attribute_id1_p_div").show();
                        $("#attribute_id2_p_div").show();
                    }

                });
            }
        });


        //land record
        $("#Getrecordlandrecord").click(function () {
            var currentElement = $('#village_id');
            var landtype = $("#developed_land_types_id").val();
            if (landtype != 2) {
                alert('This service only available for Rural land type');
                return false;
            }

            if (!$.isNumeric(currentElement.val()))
            {
                $('form:first').submit();
            } else {
                $.post("<?php echo $this->webroot; ?>Property/getlandrecord",
                        {
//                        
                            district: $("#district_id").val(),
                            taluka: $("#taluka_id").val(),
                            village: $("#village_id").val(),
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                        },
                function (data, status) {
                    if (data == 0) {
                        alert('Please enter volume and page number');
                        return false;
                    }

                    $("#land_modal_body").html(data);


//                    $('#ratetbl').DataTable({"bSort": false});

                    $('#getRecordlandModal').modal('show');
                });
            }

        });
        
        $("#Getrecordlandrecord1").click(function () {
        alert('ss');
        $.post("<?php echo $this->webroot; ?>Property/getlandrecordtest",
                        {
//                        
                            district: $("#district_id").val(),
                            taluka: $("#taluka_id").val(),
                            village: $("#village_id").val(),
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                        },
                function (data, status) {
                    if (data == 0) {
                        alert('Please enter volume and page number');
                        return false;
                    }

                    $("#land_modal_body").html(data);
                    $('#getRecordlandModal').modal('show');
                });
        });



        //holdind record service

        $("#Getholdingdet").click(function () {
            var currentElement = $('#corp_id');
            var landtype = $("#developed_land_types_id").val();
            if (landtype != 1) {
                alert('This service only available for Urban land type');
                return false;
            }

            if (!$.isNumeric(currentElement.val()))
            {
                $('form:first').submit();
            } else {
                $.post("<?php echo $this->webroot; ?>Property/getholdingrecord",
                        {
//                        
                            corp_id: $("#corp_id").val(),
                            village: $("#village_id").val(),
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                        },
                function (data, status) {
                    if (data == 0) {
                        alert('Please enter Holding number');
                        return false;
                    }

                    $("#holding_modal_body").html(data);


//                    $('#ratetbl').DataTable({"bSort": false});

                    $('#getholdingrecModal').modal('show');
                });
            }

        });

    });
    function after_validation_check_propertyscreennew123(e) {
       
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        if ($("#actiontype").val() < 3) {
            e.preventDefault();
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_prohibited_prop', {village_id: $("#village_id").val(), csrftoken: csrftoken}, function (data)
            {
                var obj = jQuery.parseJSON(data);
                if (obj.status === 1) {
                    $("#actiontype").val(3);
                    $("#propertyscreennew").submit();
                } else if (obj.status === 2)
                {
                    $("#prohibited_id").val(obj.prohibited_id);
                    $("#prohibited_flag").val('Y');
                    alert(obj.msg);
                    $("#actiontype").val(3);
                    $("#propertyscreennew").submit();

                } else if (obj.status === 3) {
                    alert(obj.msg);
                    return false;
                }
                else if (obj.status === 4) {
                    alert(obj.msg);
                    return false;
                }

            });
        } /// action type


    }
    
    function attribute_remove(attribute_index_id, flag) {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post("<?php echo $this->webroot; ?>Citizenentry/add_property_attribute",
                {
                    attribute_index_id: attribute_index_id,
                    type: flag,
                    csrftoken: csrftoken,
                    action: 'remove'
                },
        function (data, status) {
            $("#prop_attribute").html(data);
        });

    }
    function viewsd(fee_calc_id)
    {
        $.post('<?php echo $this->webroot; ?>fees/view_fee_calculation', {fee_calc_id: fee_calc_id}, function (data)
        {
            $("#sd_modal_body").html(data);
        });
    }

  
 

</script>

<div id="propentry">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-body" id="behavioral_patterns">
                    <?php
                    $flag = 1;
                    $doc_lang = $this->Session->read('doc_lang');
                    if (is_null($doc_lang)) {
                        $doc_lang = 'en';
                    }
                    foreach ($BehavioralPatterns as $key => $Pattens) {
                        $values_en = '';
                        $values_ll = '';
                        foreach ($trnbehavioral as $behavioral) {

                            if ($Pattens[0]['field_id'] == $behavioral['TrnBehavioralPatterns']['field_id']) {
                                $values_en = $behavioral['TrnBehavioralPatterns']['field_value_en'];
                                $values_ll = $behavioral['TrnBehavioralPatterns']['field_value_ll'];
                            }
                        }
                        if ($flag) {
                            $flag = 0;
                            ?>   
                            <div class="box-header with-border">
                                <h3 class="box-title headbolder" ><?php echo $Pattens[0]['behavioral_desc_display_' . $doc_lang]; ?></h3>
                            </div>
                        <?php } ?>
                        <div  class="rowht"></div>
                        <div class="row">
                            <div class="form-group">
                                <?php if ($doc_lang != 'en') {
                                    ?>
                                    <label class="col-sm-2 control-label"><?php echo $Pattens[0]['pattern_desc_ll']; ?><span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>

                                    <div class="col-sm-3"> 
                                        <?php
                                        echo $this->Form->input('value', array('label' => false, 'id' => 'field_ll' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value_ll][]', 'value' => $values_ll));
                                        ?>
                                        <span id="<?php echo 'field_ll' . $Pattens[0]['field_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                     ?></span>
                                    </div> 
                                <?php } ?>
                                <label class="col-sm-3 control-label"><?php echo $Pattens[0]['pattern_desc_en']; ?>:-<span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>

                                <div class="col-sm-3"> 
                                    <?php
                                    echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[property_details][pattern_id][]', 'value' => $Pattens[0]['field_id']));
                                    echo $this->Form->input('value', array('label' => false, 'id' => 'field_en' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value_en][]', 'value' => $values_en));
                                    ?>
                                    <span id="<?php echo 'field_en' . $Pattens[0]['field_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                     ?></span>
                                </div> 

                            </div>
                        </div> 
                    <?php }
                    ?>
                </div>

                <div class="box-body" id="property_fields">
                    <?php echo $this->element('Citizenentry/property_fields'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder"><?php echo __('lblpropertyattribute') . ' ' . __('lbllandrecdatafetching'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('attributelist', array('label' => false, 'class' => 'form-control', 'type' => 'select', 'id' => 'attribute_id', 'empty' => '--Select--', 'options' => $attributes)); ?>
                            </div> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('attribute_value', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value', 'placeholder' => __('lblattrivalue'))); ?>
                                <span id="attribute_value_error" class="form-error"></span>
                            </div>
                            <div class="col-sm-3" id="attribute_id1_div">
                                <?php echo $this->Form->input('attribute_value1', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value1', 'placeholder' => __('lblattrivalue_part1'))); ?>
                                <span id="attribute_value1_error" class="form-error"></span>
                            </div>
                            <div class="col-sm-3" id="attribute_id2_div">
                                <?php echo $this->Form->input('attribute_value2', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value2', 'placeholder' => __('lblattrivalue_part2'))); ?>
                                <span id="attribute_value2_error" class="form-error"></span>
                            </div>
                            <div class="col-sm-1">
                                <?php echo $this->Form->button('Add', array('type' => 'button', 'label' => false, 'class' => 'btn btn-info', 'name' => 'add', 'id' => 'add_attribute')); ?>
                            </div> 
                        </div>

                    </div>
                    <br>
                    <div  id="prop_attribute">

                        <div class="form-group">
                        <table class="table table-bordered" id="prop_attribute_tbl">
                                <thead>
                                    <tr>
                                        <th class="center">
                                            <?php echo __('lblattriname'); ?> 
                                        </th>
                                        <th class="center">
                                            <?php echo __('lblattrivalue'); ?> 
                                        </th>
                                        <th>
                                            <?php echo __('lblattrivalue_part1'); ?>
                                        </th>  
                                        <th>
                                            <?php echo __('lblattrivalue_part2'); ?>
                                        </th>
                                        <th>
                                            <?php echo __('lblaction'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($prop_attributes_seller)) {
                                        foreach ($prop_attributes_seller as $key => $prop_attribute) {
                                            ?>
                                            <tr>
                                                <th>
                                                    <?php echo @$attributes[$prop_attribute['attribute_id']]; ?>
                                                </th>
                                                <th>
                                                    <?php echo @$prop_attribute['attribute_value']; ?>
                                                </th>
                                                <th>
                                                    <?php echo @$prop_attribute['attribute_value1']; ?>
                                                </th>
                                                <th>
                                                    <?php echo @$prop_attribute['attribute_value2']; ?>
                                                </th>
                                                <th>
                                                    <button type="button" class="btn btn-info" onclick="return attribute_remove('<?php echo $key; ?>', 'S');">Remove</button>
                                                </th>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <?php if ($regval == 'Y') { ?>
        <!--Purchesar attributes-->
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title headbolder"><?php echo __('lblpurchaserpropattri'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <!--<label for="prop_attribute" class="col-sm-2 control-label"><?php //echo __('lblpropertyattribute');             ?></label>--> 
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('attributelist_p', array('label' => false, 'class' => 'form-control', 'type' => 'select', 'empty' => '--Select--', 'options' => $attributes, 'id' => 'attribute_id_p')); ?>

                                </div> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('attribute_value_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value_p', 'placeholder' => 'Value')); ?>
                                    <span id="attribute_value_p_error" class="form-error"></span>
                                </div> 
                                <div class="col-sm-3" id="attribute_id1_p_div">
                                    <?php echo $this->Form->input('attribute_value1_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value1_p', 'placeholder' => 'Hissa-1')); ?>
                                    <span id="attribute_value1_p_error" class="form-error"></span>
                                </div> 
                                <div class="col-sm-3" id="attribute_id2_p_div">
                                    <?php echo $this->Form->input('attribute_value2_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'attribute_value2_p', 'placeholder' => 'Hissa-2')); ?>
                                    <span id="attribute_value2_p_error" class="form-error"></span>
                                </div> 
                                <div class="col-sm-1">
                                    <?php echo $this->Form->button('Add', array('type' => 'button', 'label' => false, 'class' => 'btn btn-info', 'name' => 'add', 'id' => 'add_attribute_p')); ?>
                                </div> 
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row"  id="prop_attribute_p">
                            <div class="form-group">
                                <table class="table table-bordered" id="prop_attribute_tbl_p">
                                    <thead>
                                        <tr>
                                            <th class="center">
                                                <?php echo __('lblattriname'); ?> 
                                            </th>
                                            <th class="center">
                                                <?php echo __('lblattrivalue'); ?> 
                                            </th>
                                            <th>
                                                <?php echo __('lblattrivalue_part1'); ?>
                                            </th>  
                                            <th>
                                                <?php echo __('lblattrivalue_part2'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($prop_attributes_pur)) {
                                            foreach ($prop_attributes_pur as $key => $prop_attribute) {
                                                ?>
                                                <tr>
                                                    <th>
                                                        <?php echo $attributes[$key]; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $prop_attribute['attribute_value']; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $prop_attribute['attribute_value1']; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $prop_attribute['attribute_value2']; ?>
                                                    </th>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

    <?php } ?>


    <div class="row" id="boundarydiv">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder"><?php echo __('lblpropdetailsandotherdetails'); ?></h3>
                </div>
                <div class="box-body">
                    <?php
                    $doc_lang = $this->Session->read('doc_lang');
                    if (!empty($doc_lang) and $doc_lang != 'en') {
                        ?>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('unique_property_no_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('unique_property_no_ll', array('label' => false, 'id' => 'unique_property_no_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="unique_property_no_ll_error" class="form-error"></span>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class=" col-sm-5">
                                    <label><?php echo __('boundries_east_' . $doc_lang); ?> </label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_east_ll', array('label' => false, 'id' => 'boundries_east_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="boundries_east_ll_error" class="form-error"></span>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label> <?php echo __('boundries_west_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_west_ll', array('label' => false, 'id' => 'boundries_west_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="boundries_west_ll_error" class="form-error"></span>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label> <?php echo __('boundries_south_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_south_ll', array('label' => false, 'id' => 'boundries_south_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="boundries_south_ll_error" class="form-error"></span>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class=" col-sm-5">
                                    <label><?php echo __('boundries_north_' . $doc_lang); ?> </label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_north_ll', array('label' => false, 'id' => 'boundries_north_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="boundries_north_ll_error" class="form-error"></span>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('remark_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('remark_ll', array('label' => false, 'id' => 'remark_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="remark_ll_error" class="form-error"></span>
                                </div> 
                            </div>  
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('अतिरिक�?त माहिती'); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('additional_information_ll', array('label' => false, 'id' => 'additional_information_ll', 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
                                    <span id="additional_information_ll_error" class="form-error"></span>
                                </div> 
                            </div>  
                        </div>

                    <?php } ?>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class=" col-sm-5">
                                <label><?php echo __('lbluniquepropnu'); ?></label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('unique_property_no_en', array('label' => false, 'id' => 'unique_property_no_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="unique_property_no_en_error" class="form-error"></span>
                            </div> 
                        </div> 
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundryeast'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_east_en', array('label' => false, 'id' => 'boundries_east_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="boundries_east_en_error" class="form-error"></span>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundrywest'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_west_en', array('label' => false, 'id' => 'boundries_west_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="boundries_west_en_error" class="form-error"></span>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundriessouth'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_south_en', array('label' => false, 'id' => 'boundries_south_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="boundries_south_en_error" class="form-error"></span>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundriesnorth'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_north_en', array('label' => false, 'id' => 'boundries_north_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="boundries_north_en_error" class="form-error"></span>
                            </div> 
                        </div>
                        <div class="rowht"></div>

                        <?php if (empty($remarkconfig)) { ?>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('lblremark'); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('remark_en', array('label' => false, 'id' => 'remark_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="remark_en_error" class="form-error"></span>
                                </div> 
                            </div> 
                        <?php } ?>



                        <div class="rowht"></div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lbladditionalinfo'); ?></label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('additional_information_en', array('label' => false, 'id' => 'additional_information_en', 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
                                <span id="additional_information_en_error" class="form-error"></span>
                            </div> 
                        </div>  
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-body">
                    <?php echo $this->Form->input('prohibited_id', array('label' => false, 'id' => 'prohibited_id', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
                    <?php echo $this->Form->input('prohibited_flag', array('label' => false, 'id' => 'prohibited_flag', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
                    <?php echo $this->Form->input('property_id', array('label' => false, 'id' => 'property_id', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
                    <div  class="rowht"></div>
                    <div class="row center">
                        <div class="form-group">
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                            <div  class="rowht">&nbsp;</div>
                            <input type="button" name="check_pro" value="<?php echo __('lblbtnchkpropertyprohibition'); ?>" class="btn btn-info" id ="check_prohibition" >
                            <input type="button" name="save" value="<?php echo __('btnsave'); ?>" class="btn btn-info" id ="btnSave" >
                            <?php if ($landrecordbutton == 'Y') { ?>
                                <button type="button"  class="btn btn-primary btn-sm"  id="Getrecordlandrecord"><span class="fa fa-search"></span><?php echo 'GetLandrecord'; ?> </button>  
                                <button type="button"  class="btn btn-primary btn-sm"  id="Getrecordlandrecord1"><span class="fa fa-search"></span><?php echo 'GetLandrecordTestButton'; ?> </button>
                            <?php } ?>

                            <?php if ($holdingrecordbutton == 'Y') { ?>
                                <button type="button"  class="btn btn-primary btn-sm"  id="Getholdingdet"><span class="fa fa-search"></span><?php echo 'Holding Details'; ?> </button>
                            <?php } ?>

				

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

</div>

<div class="row" id="listproperty">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblpropertydetails'); ?></h3>
            </div>

            <div class="box-body">
                <table id="prop_list_tbl" class="table table-striped table-bordered table-hover"> 
                    <thead >
                        <tr >
                            <th class="center">   <?php echo __('lblpropertydetails'); ?>  </th>
                            <th class="center">   <?php echo __('lblcityvillage'); ?> </th>
                            <th class="center">   <?php echo __('lbllocation'); ?> </th>
                            <th class="center"> <?php echo __('lblusage'); ?>  </th>
                            <th class="center" style="width: 30%;">   <?php echo __('lblaction'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //pr($property_list);exit;


                        foreach ($property_list as $key => $property) {
                            //pr($property);exit;
                            ?>
                            <tr>
                                <td class="tblbigdata">
                                    <?php
                                    $prop_name = "";
                                    foreach ($property_pattern as $key1 => $pattern) {
                                        if ($property['property_details_entry']['property_id'] == $pattern[0]['mapping_ref_val']) {
                                            $prop_name .= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                        }
                                    }

                                    echo substr($prop_name, 1);
                                    ?>
                                </td>
                                <td class="tblbigdata">
                                    <?php echo $property['village']['village_name_' . $lang]; ?>
                                </td>
                                <td class="tblbigdata">
                                    <?php echo $property['level1']['level_1_desc_' . $lang]; ?> =>
                                    <?php echo $property['level1_list']['list_1_desc_' . $lang]; ?>
                                </td>
                                <td class="tblbigdata">
                                    <?php echo $property['evalrule']['evalrule_desc_' . $lang]; ?>
                                </td>
                                <td class="tblbigdata">
                                    <?php if ($property['property_details_entry']['val_id'] == NUll || $property['property_details_entry']['val_id'] == 0) { ?>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#novaluation">View Valuation</button>
                                    <?php } else { ?>
                                        <input type="button" class="btn btn-primary" value="View Valuation" onclick="javascript: return formview('<?php echo base64_encode($property['property_details_entry']['val_id']); ?>');">
                                        <?php
                                        //   echo $this->Html->link('PDF', array('class' => '', 'controller' => 'Reports', 'action' => 'rptview', 'P', base64_encode($property['property_details_entry']['val_id'])), array('class' => 'btn btn-info'));
                                    }
                                    ?>
                                    <?php
                                    echo $this->Html->link('Edit', array('controller' => 'citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken'), $property['property_details_entry']['property_id']), array('confirm' => 'Are you sure you wish to Edit this property?', 'class' => 'btn btn-info')
                                    );


                                    echo $this->Html->link('Delete', array('controller' => 'Citizenentry', 'action' => 'property_remove', $property['property_details_entry']['property_id']), array('confirm' => 'Please Use Update Option If Possible,Delete Will Delete All Party Related To This Property,Are You Sure You Wish To Delete This Property?', 'class' => 'btn btn-info')
                                    );
                                    ?>
                                </td>
                            </tr>
                                <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div> 
<?php echo $this->Form->end(); ?>

<div class="sample1"></div>

<div id="myModal_sd" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblsdview'); ?></h4>
            </div>
            <div class="modal-body" id="sd_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>



<!-- Modal -->
<div id="novaluation" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblvaluationnotcalculate'); ?></h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b><span class="fa fa-warning"></span><?php echo __('lblfollowingreason'); ?> </b></li>
                    <li class="list-group-item"><?php echo __('lblratenotavailable'); ?></li>
                    <li class="list-group-item"><b><span class="fa fa-info-circle"></span><?php echo __('lblsuggestion'); ?> </b></li>
                    <li class="list-group-item"><?php echo __('lblselectcorrectloc'); ?></li>
                    <li class="list-group-item"><?php echo __('lblchkrateavailable'); ?></li>
                    <li class="list-group-item"><?php echo __('lblselcorrectusagerule'); ?></li>
                </ul> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>



<div id="getRecordlandModal" class="modal fade" role="dialog">
    <div class="modal-dialog3">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Land Record chart</h4>
            </div>
            <div class="modal-body" id="land_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>  




<div id="getholdingrecModal" class="modal fade" role="dialog">
    <div class="modal-dialog3">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Holding Details Chart</h4>
            </div>
            <div class="modal-body" id="holding_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
