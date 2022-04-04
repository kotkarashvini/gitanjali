<?php
$tokenval = $this->Session->read("Selectedtoken");
echo $this->Form->create('propertyscreennew', array('url' => array('controller' => 'citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken')), 'id' => 'propertyscreennew'));
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
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element("Property/screen"); ?>


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
                        }
                        else {
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
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_prohibited_prop', {village_id: $("#village_id").val(), csrftoken: csrftoken}, function (data)
            {
                //document.getElementById("").value = '3';

                $("#actiontype").val(3);
                if (data != 'N' && data != 'E' && $.isNumeric(data))
                {

                    $("#prohibited_id").val(data);
                    $("#prohibited_flag").val('Y');
                    var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_config_prohibition', {csrftoken: csrftoken}, function (data1)
                    {
                        if (data1 == 'Y')
                        {
                            alert('This is Prohibited Property');
                            return false;
                        } else
                        {
                            alert('This is Prohibited Property');
                            $("#propertyscreennew").submit();
                            //$("*").prop("disabled",true);
                            $('body').append('<div style="display:none"><div><img src="../img/ajax-loader.gif"><div></div></div><div class="bg"></div></div>');
                        }
                    });
                }
                else if (data.trim() === 'E')
                {
                    alert('Please enter details');
                    return false;
                }
                else if (data.trim() === 'N')
                {

                    $("#propertyscreennew").submit();
                    // $("*").prop("disabled",true);
                    $('body').append('<div style="display:none"><div><img src="../img/ajax-loader.gif"><div></div></div><div class="bg"></div></div>');
                }
            });
        });
        $("#check_prohibition").click(function () {
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_prohibited_prop', {village_id: $("#village_id").val(), csrftoken: csrftoken}, function (data)
            {
                var data = data.trim();
                if (data != 'N' && data != 'E' && $.isNumeric(data))
                {
                    $("#prohibited_id").val(data);
                    var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_config_prohibition', {}, function (data1)
                    {
                      
                        if (data1 == 'Y')
                        {
                            alert('This is Prohibited Property');
                            return false;
                        } else
                        {
                            alert('This is Prohibited Property');
                        }
                    });
                } else if (data == 'N')
                {
                    alert('This is not Prohibited Property');
                }
                else if (data == 'E')
                {
                    alert('Please select Property');
                }
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
    });
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
                    foreach ($BehavioralPatterns as $key => $Pattens) {
                        if ($flag) {
                            $flag = 0;
                            ?>   
                            <div class="box-header with-border">
                                <h3 class="box-title headbolder" ><?php echo $Pattens[0]['behavioral_desc_' . $doc_lang]; ?></h3>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-sm-offset-1  col-sm-2">
                                <label><?php echo $Pattens[0]['pattern_desc']; ?></label>
                            </div>
                            <div class="col-sm-2"> 
                                <?php
                                echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[property_details][pattern_id][]', 'value' => $Pattens[0]['field_id']));
                                echo $this->Form->input('value', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value][]'));
                                ?>
                            </div> 
                        </div> 
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder"><?php echo __('lblpropertyattribute') . '  (Land Record data fetching attributes)'; ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('attributelist', array('label' => false, 'class' => 'form-control', 'type' => 'select', 'id' => 'attribute_id', 'empty' => '--Select--', 'options' => $attributes)); ?>
                            </div> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('attributevalue', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value', 'id' => 'attribute_value', 'placeholder' => 'Value')); ?>
                            </div>
                            <div class="col-sm-3" id="attribute_id1_div">
                                <?php echo $this->Form->input('attributevalue', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value1', 'id' => 'attribute_value1', 'placeholder' => __('lblattrivalue_part1'))); ?>
                            </div>
                            <div class="col-sm-3" id="attribute_id2_div">
                                <?php echo $this->Form->input('attributevalue', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value2', 'id' => 'attribute_value2', 'placeholder' => __('lblattrivalue_part2'))); ?>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($prop_attributes_seller)) {
                                        foreach ($prop_attributes_seller as $key => $prop_attribute) {
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
                                <!--<label for="prop_attribute" class="col-sm-2 control-label"><?php //echo __('lblpropertyattribute');   ?></label>--> 
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('attributelist_p', array('label' => false, 'class' => 'form-control', 'type' => 'select', 'empty' => '--Select--', 'options' => $attributes, 'id' => 'attribute_id_p')); ?>
                                </div> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('attributevalue_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value_p', 'id' => 'attribute_value_p', 'placeholder' => 'Value')); ?>
                                </div> 
                                <div class="col-sm-3" id="attribute_id1_p_div">
                                    <?php echo $this->Form->input('attributevalue1_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value1_p', 'id' => 'attribute_value1_p', 'placeholder' => 'Hissa-1')); ?>
                                </div> 
                                <div class="col-sm-3" id="attribute_id2_p_div">
                                    <?php echo $this->Form->input('attributevalue2_p', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'attribute_value2_p', 'id' => 'attribute_value2_p', 'placeholder' => 'Hissa-2')); ?>
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
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class=" col-sm-5">
                                    <label><?php echo __('boundries_east_' . $doc_lang); ?> </label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_east_ll', array('label' => false, 'id' => 'boundries_east_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label> <?php echo __('boundries_west_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_west_ll', array('label' => false, 'id' => 'boundries_west_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label> <?php echo __('boundries_south_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_south_ll', array('label' => false, 'id' => 'boundries_south_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class=" col-sm-5">
                                    <label><?php echo __('boundries_north_' . $doc_lang); ?> </label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('boundries_north_ll', array('label' => false, 'id' => 'boundries_north_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                </div> 
                            </div>
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('remark_' . $doc_lang); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('remark_ll', array('label' => false, 'id' => 'remark_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                </div> 
                            </div>  
                            <div class="rowht"></div>
                            <div class="row">
                                <div class="  col-sm-5">
                                    <label><?php echo __('अतिरिक्त माहिती'); ?></label>
                                </div>
                                <div class="col-sm-5"> 
                                    <?php echo $this->Form->input('additional_information_ll', array('label' => false, 'id' => 'additional_information_ll', 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
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
                            </div> 
                        </div> 
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundryeast'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_east_en', array('label' => false, 'id' => 'boundries_east_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundrywest'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_west_en', array('label' => false, 'id' => 'boundries_west_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundriessouth'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_south_en', array('label' => false, 'id' => 'boundries_south_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblboundriesnorth'); ?> </label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('boundries_north_en', array('label' => false, 'id' => 'boundries_north_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lblremark'); ?></label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('remark_en', array('label' => false, 'id' => 'remark_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div> 
                        </div> 
                        <div class="rowht"></div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="  col-sm-5">
                                <label><?php echo __('lbladditionalinfo'); ?></label>
                            </div>
                            <div class="col-sm-5"> 
                                <?php echo $this->Form->input('additional_information_en', array('label' => false, 'id' => 'additional_information_en', 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
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
                            <input type="button" name="check_pro" value="Check Property Prohibition" class="btn btn-info" id ="check_prohibition" >
                            <input type="button" name="save" value="Save" class="btn btn-info" id ="btnSave" >
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
                            <th class="center">
                                <?php echo __('lblusage'); ?>
                            </th>
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
                                        echo $this->Html->link('PDF', array('class' => '', 'controller' => 'Reports', 'action' => 'rptview', 'P', base64_encode($property['property_details_entry']['val_id'])), array('class' => 'btn btn-info'));
                                    }
                                    ?>
                                    <?php
                                    echo $this->Html->link('Edit', array('controller' => 'citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken'), $property['property_details_entry']['property_id']), array('confirm' => 'Are you sure you wish to Edit this property?', 'class' => 'btn btn-info')
                                    );

                                    echo $this->Html->link('Delete', array('controller' => 'Citizenentry', 'action' => 'property_remove', $this->Session->read('csrftoken'), $property['property_details_entry']['property_id']), array('confirm' => 'Are you sure you wish to delete this property?', 'class' => 'btn btn-info')
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





<?php
//---------------------------------------------------------------------------------------------------------------------

if (isset($prop_result) && !empty($prop_result)) {
    $prop_result = $prop_result[0]['property_details_entry'];
// pr($prop_result);
    ?>
    <script>

        $(document).ready(function () {
            //This handles the queues    
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
    <?php
    if (isset($prop_result['district_id']) and is_numeric($prop_result['district_id'])) {
        ?>
                $("#district_id").val('<?php echo $prop_result['district_id'] ?>');
                //                $("#survey_no").val(<?php //echo $prop_result['survey_no'];                  ?>);
                var dist = <?php echo $prop_result['district_id'] ?>;
                var type = '<?php echo $prop_result['developed_land_types_id']; ?>';
                $("#developed_land_types_id").val(type);
                // $("#viewrate").hide();
                //             $("#village_id").val('');           
                //             $("#taluka_id").val('');
                //$("#corp_id").val('');            
                $(".chosen-select").select2();
                if (type == 1)
                {
                    $("#lbl_corp").show();
                    $("#taluka").show();
                    $("#city_village").hide();
                } else if (type == 2)
                {
                    $("#taluka_id").val('');
                    $("#lbl_corp").hide();
                    $("#taluka").show();
                    $("#city_village").hide();
                } else if (type == 3)
                {
                    $("#taluka_id").val('');
                    $("#lbl_corp").hide();
                    $("#taluka").show();
                    $("#city_village").hide();
                }
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                $.post('<?php echo $this->webroot; ?>Property/get_corp_list', {district: dist, csrftoken: csrftoken}, function (data)
                {


                    var sc = '<option>--select--</option>';
                    $.each(data.corp, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                    $("#corp_id").val(<?php echo $prop_result['corp_id']; ?>);
                    $.post("<?php echo $this->webroot; ?>districtchangeevent", {dist: dist, csrftoken: csrftoken}, function (data)
                    {
                        var sc = '<option>--select--</option>';
                        $.each(data.taluka, function (index, val) {
                            sc += "<option value=" + index + ">" + val + "</option>";
                        });
                        $("#taluka_id").prop("disabled", false);
                        $("#taluka_id option").remove();
                        $("#taluka_id").append(sc);
        <?php if (isset($prop_result['taluka_id']) and is_numeric($prop_result['taluka_id'])) {
            ?>
                            $("#taluka_id").val('<?php echo $prop_result['taluka_id'] ?>');
                            var tal = $("#taluka_id option:selected").val();
                            var dist = $("#district_id option:selected").val();
                            var landtype = $("#developed_land_types_id option:selected").val();
                            var corp = $("#corp_id option:selected").val();
                            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                            $("#divlevel1list").hide();
                            $.post('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal, dist: dist, landtype: landtype, corp: corp, csrftoken: csrftoken}, function (data)
                            {
                                $("#city_village").show();
                                var sc = '<option>--select--</option>';
                                $.each(data.village, function (index, val) {

                                    sc += "<option value=" + index + ">" + val + "</option>";
                                });
                                // $("#corp_id").prop("disabled", false);
                                // $("#valutation_zone_id").prop("disabled", false);

                                $("#village_id option").remove();
                                $("#village_id").append(sc);
                                $("#village_id option").remove();
                                $("#village_id").append(sc);
                                $("#lblvillage_id").show();
                                $("#lblcitytown").hide();
                                //makecombosearch();

                                $('#corp_id').change(function () {
                                    var corp = $("#corp_id option:selected").val();
                                    var dist = $("#district_id option:selected").val();
                                    var landtype = $("#developed_land_types_id option:selected").val();
                                    var tal = $("#taluka_id option:selected").val();
                                    var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                                    $("#divlevel1list").hide();
                                    $.post('<?php echo $this->webroot; ?>Property/corp_change_event', {tal: tal, corp: corp, dist: dist, landtype: landtype, csrftoken: csrftoken}, function (data)
                                    {

                                        $("#city_village").show();
                                        var sc2 = '<option value="">--select--</option>';
                                        $.each(data.village, function (index, val) {
                                            sc2 += "<option value=" + index + ">" + val + "</option>";
                                        });
                                        $("#village_id").prop("disabled", false);
                                        $("#village_id option").remove();
                                        $("#village_id").append(sc2);
                                        $("#lblvillage_id").hide();
                                        $("#lblcitytown").show();
                                    }, 'json');
                                });
                                fill_village_location();
                            }, 'json');
        <?php }
        ?>

                    }, 'json');
                }, 'json');
    <?php } ?>



            function fill_village_location()
            {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
    <?php
    if (isset($prop_result['village_id']) and is_numeric($prop_result['village_id'])) {
        ?>
                    $("#village_id").val('<?php echo $prop_result['village_id'] ?>');
                    $.post('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: <?php echo $prop_result['village_id']; ?>, csrftoken: csrftoken}, function (data)
                    {
                        var sc1 = '<option value="">--select--</option>';
                        $.each(data.data2, function (index, val) {
                            sc1 += "<option value=" + index + ">" + val + "</option>";
                        });
                        $("#level1_id option").remove();
                        $("#level1_id").append(sc1);
                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                        $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: <?php echo $prop_result['village_id'] ?>, ref_id: 1, behavioral_id: 1, ref_val:<?php echo $prop_result['property_id']; ?>, csrftoken: csrftoken}, function (data)
                        {
                            $("#behavioral_patterns").html(data);
                        });
        <?php
        if (isset($prop_result['level1_id']) and is_numeric($prop_result['level1_id'])) {
            ?>
                            $("#level1_id").val('<?php echo $prop_result['level1_id'] ?>');
                            $.post('<?php echo $this->webroot; ?>Property/Level1_change_event', {level1list: <?php echo $prop_result['level1_id']; ?>, village_id: <?php echo $prop_result['village_id']; ?>, csrftoken: csrftoken}, function (data)
                            {
                                if (data['level1listflag'].toString() === '1') {
                                    var sc = '<option>--select--</option>';
                                    $.each(data.data1, function (index, val) {
                                        sc += "<option value=" + index + ">" + val + "</option>";
                                    });
                                    $("#hflevel1list").val('1');
                                    $("#level1_list_id option").remove();
                                    $("#level1_list_id").append(sc);
                                    $("#divlevel1list").fadeIn("slow");
                                    $("#level1_list_id").prop("disabled", false);
                                } else {
                                    $("#divlevel1list").hide();
                                    $("#divlevel2").hide();
                                    $("#divlevel2list").hide();
                                    $("#divlevel3").hide();
                                    $("#divlevel3list").hide();
                                    $("#divlevel4").hide();
                                    $("#divlevel4list").hide();
                                }
                                if (data['level2flag'].toString() === '1') {
                                    var sc1 = '<option>--select--</option>';
                                    $.each(data.data2, function (index, val) {
                                        sc1 += "<option value=" + index + ">" + val + "</option>";
                                    });
                                    $("#hflevel2").val('1');
                                    $("#level_2_desc_eng option").remove();
                                    $("#level_2_desc_eng").append(sc1);
                                    $("#divlevel2").fadeIn("slow");
                                    $("#level_2_desc_eng").prop("disabled", false);
                                } else {
                                    $("#divlevel2").hide();
                                    $("#divlevel2list").hide();
                                    $("#divlevel3").hide();
                                    $("#divlevel3list").hide();
                                    $("#divlevel4").hide();
                                    $("#divlevel4list").hide();
                                }
            <?php
            if (isset($prop_result['level1_list_id']) and is_numeric($prop_result['level1_list_id'])) {
                ?>
                                    $("#level1_list_id").val('<?php echo $prop_result['level1_list_id'] ?>');
            <?php } ?>
                                //makecombosearch();
                            }, 'json');
        <?php } ?>
                        //makecombosearch();
                        usage_filter_land_type(<?php echo $prop_result['village_id']; ?>);
                    }, 'json');
        <?php
    }
    ?>
            }




            //------------ Hedings-------------------------------
            $("#unique_property_no_en").val("<?php echo $prop_result['unique_property_no_en']; ?>");
            $("#boundries_east_en").val("<?php echo $prop_result['boundries_east_en']; ?>");
            $("#boundries_west_en").val("<?php echo $prop_result['boundries_west_en']; ?>");
            $("#boundries_south_en").val("<?php echo $prop_result['boundries_south_en']; ?>");
            $("#boundries_north_en").val("<?php echo $prop_result['boundries_north_en']; ?>");
            $("#remark_en").val("<?php echo $prop_result['remark_en']; ?>");
            $("#unique_property_no_ll").val("<?php echo $prop_result['unique_property_no_ll']; ?>");
            $("#boundries_east_ll").val("<?php echo $prop_result['boundries_east_ll']; ?>");
            $("#boundries_west_ll").val("<?php echo $prop_result['boundries_west_ll']; ?>");
            $("#boundries_south_ll").val("<?php echo $prop_result['boundries_south_ll']; ?>");
            $("#boundries_north_ll").val("<?php echo $prop_result['boundries_north_ll']; ?>");
            $("#remark_ll").val("<?php echo $prop_result['remark_ll']; ?>");
            $("#property_id").val("<?php echo $prop_result['property_id']; ?>");
            $(".chosen-select").select2();
        });

    </script>
    <?php
}
?>
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
                <h4 class="modal-title">Valuation Not Calculated </h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b><span class="fa fa-warning"></span> Following are the reasons</b></li>
                    <li class="list-group-item">Rate Not Available</li>
                    <li class="list-group-item"><b><span class="fa fa-info-circle"></span> Suggestions</b></li>
                    <li class="list-group-item">Select Correct locations</li>
                    <li class="list-group-item">Check Rate Available</li>
                    <li class="list-group-item">Select Correct Usage Rule</li>
                </ul> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
