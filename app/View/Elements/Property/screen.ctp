 
<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {
//        if ($("#developed_land_types_id").text() === 'Urban') {
//            $("#lbl_corp").show();
//        } else {
//            $("#lbl_corp").hide();
//        }

        $("#showsurveyno").click(function () {
            $("#surveyno_modal_body").html("<p>Loading ...... Please Wait!</p>");

            var currentElement = $('#village_id');
            if (!$.isNumeric(currentElement.val()))
            {
                $('form:first').submit();
            } else {

                $.post("<?php echo $this->webroot; ?>Property/getsurveynumbers",
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
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                        },
                function (data, status) {

                    $("#surveyno_modal_body").html(data);

                    $('#surveytbl').DataTable();
                    $('#myModal').modal('show');


                });
            }
        });

        $("#viewrate").click(function () {
            var currentElement = $('#village_id');
            if (!$.isNumeric(currentElement.val()))
            {
                $('form:first').submit();
            } else {
                $.post("<?php echo $this->webroot; ?>Property/getallrates",
                        {
                            finyear_id: $("#fin_yr_id").val(),
                            district: $("#district_id").val(),
                            landtype: $("#Developedland").val(),
                            taluka: $("#taluka_id").val(),
                            council: $("#corp_id").val(),
                            village: $("#village_id").val(),
                            survey_no: $("#survey_no").val(),
                            lavel1: $("#level1_id").val(), lavel1_list: $("#level1_list_id").val(),
                            lavel2: $("#level_2_desc_eng").val(), lavel2_list: $("#level2_list_id").val(),
                            lavel3: $("#level_3_desc_eng").val(), lavel3_list: $("#level3_list_id").val(),
                            lavel4: $("#level_4_desc_eng").val(), lavel4_list: $("#level4_list_id").val(),
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                        },
                function (data, status) {
                    $("#rate_modal_body").html(data);

                    $('#ratetbl').DataTable({"bSort": false});

                    $('#rateModal').modal('show');
                });
            }

        });

        $("#division_id").on('change', function () {
            $.postJSON('<?php echo $this->webroot; ?>Property/division_change_event', {division_id: $("#division_id").val()}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        });

        $("#subdivision_id").on('change', function () {
            $.postJSON('<?php echo $this->webroot; ?>Property/subdivision_change_event', {subdivision_id: $("#subdivision_id").val()}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        });

        $('#search_rule').keyup(function () {
            var valThis = $(this).val().toLowerCase();
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                var usagecatid = $(this).val();
                var label = $("label[for='usage_cat_id" + usagecatid + "']").html().toLowerCase();
                if (label.indexOf(valThis) > -1) {
                    //$(this).show();
                    //$("label[for='usage_cat_id" + usagecatid + "']").show();
                    $("label[for='usage_cat_id" + usagecatid + "']").parent('div').show();
                } else {
                    //$(this).hide();
                    //$("label[for='usage_cat_id" + usagecatid + "']").hide();
                    $("label[for='usage_cat_id" + usagecatid + "']").parent('div').hide();
                }
            });
        });


        if (
                $('#hfconstructionflag').val() === 'Y' ||
                $('#hfdepreciationflag').val() === 'Y' ||
                $('#hfroadvicinityflag').val() === 'Y' ||
                $('#hfuserdependency1flag').val() === 'Y' ||
                $('#hfuserdependency2flag').val() === 'Y'
                ) {
            $("#dependencypanel").fadeIn("slow");
        } else {
            $("#dependencypanel").fadeOut("slow");
        }



        if ($('#hfconstructionflag').val() === 'Y') {
            $("#hfconstructionflag").val('Y');
            $("#divconstructiontype").fadeIn("slow");
        } else {
            $("#hfconstructionflag").val('N');
            $("#divconstructiontype").fadeOut("slow");
        }

        if ($('#hfdepreciationflag').val() === 'Y') {
            $("#hfdepreciationflag").val('Y');
            $("#divdepreciationtype").fadeIn("slow");
        } else {
            $("#hfdepreciationflag").val('N');
            $("#divdepreciationtype").fadeOut("slow");

        }

        if ($('#hfroadvicinityflag').val() === 'Y') {
            $("#hfroadvicinityflag").val('Y');
            $("#divroadvicinity").fadeIn("slow");
        } else {
            $("#hfroadvicinityflag").val('N');
            $("#divroadvicinity").fadeOut("slow");
        }

        if ($('#hfuserdependency1flag').val() === 'Y') {
            $("#hfuserdependency1flag").val('Y');
            $("#divuserdependency1").fadeIn("slow");
        } else {
            $("#hfuserdependency1flag").val('N');
            $("#divuserdependency1").fadeOut("slow");
        }

        if ($('#hfuserdependency2flag').val() === 'Y') {
            $("#hfuserdependency2flag").val('Y');
            $("#divuserdependency2").fadeIn("slow");
        } else {
            $("#hfuserdependency2flag").val('N');
            $("#divuserdependency2").fadeOut("slow");
        }
        if ($('#hfboundaryflag').val() === 'Y') {
            $("#boundarydiv").fadeIn("slow");
        } else {
            $("#boundarydiv").fadeOut("slow");
        }





        initiate_usage_event();
        // initiate datatable for valuation rule 
        $('#ValuationRule').DataTable();


        $("#developed_land_types_id").change(function () {
            getTaluka($("#district_id").val());
//            if ($("#developed_land_types_id").val() == 1) {
//                $("#lbl_corp").show();
//            } else {
//                $("#lbl_corp").hide();
//            }
        });



    });

    function ruleChangeEvent() {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        jQuery.post('<?php echo $this->webroot; ?>Property/rulechangeevent', {village_id: $("#village_id option:selected").val(), district_id: $("#district_id option:selected").val(), csrftoken: csrftoken}, function (data) {
            $('#ItemListDiv').html(data);
            $(document).trigger('_page_ready');

            $('.usage_cat_id input[type="checkbox"]').each(function () {
                if ($(this).prop('checked') === true) {
                    jQuery.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 1, behavioral_id: 1, usage_id: $(this).val(), csrftoken: csrftoken}, function (data) {
                        $("#behavioral_patterns").html(data);
                        $(document).trigger('_page_ready');
                    });
                }

            });
            var rulestr = '';
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                if ($(this).prop('checked') === true) {
                    rulestr = rulestr + "," + $(this).val();
                }
            });
            if (rulestr !== '') {
                jQuery.post('<?php echo $this->webroot; ?>Citizenentry/property_fields', {village_id: $("#village_id").val(), usage_id: rulestr}, function (data) {
                    $("#property_fields").html(data);
                    $(document).trigger('_page_ready');
                    $(document).trigger('_propfields_event');

                });
            }


            $('input[name = "data[propertyscreennew][AAN]"]').val('');
        });
    }

    //To Fetch Subrule.....by Dependancy Attributes (i.e Construction Type,Deprecication Type, Road Vicinity, UDD1,UDD2)
    function selectrule() {
        var usagecatlist = '';
        $('.usage_cat_id input[type="checkbox"]').each(function () {
            if ($(this).prop('checked') === true) {
                var usagecatid = $(this).val();
                if (usagecatlist === '') {
                    usagecatlist = usagecatid;
                } else {
                    usagecatlist = usagecatlist + ',' + usagecatid;
                }
            }
        });
        //alert(usagecatlist);
        if (usagecatlist !== '') {
            $.postJSON('<?php echo $this->webroot; ?>Property/fetchsubrule', {cType: $("#construction_type_id option:selected").val(), dType: $("#depreciation_id option:selected").val(), rVicinity: $("#road_vicinity_id option:selected").val(), UDD1: $("#user_defined_dependency1_id option:selected").val(), UDD2: $("#user_defined_dependency2_id option:selected").val()}, function (data)
            {

            });
        }
    }

    function selectevalrule() {
        var usage_sub_sub_catg_id = $("#usage_sub_sub_catg_id option:selected").val();
        var evalrule_id = $("#evalrule_id option:selected").val();
        document.getElementById("actiontype").value = '2';
        document.getElementById("hfusage_sub_sub_catg_id").value = usage_sub_sub_catg_id;
        document.getElementById("hfevalrule_id").value = evalrule_id;
        $('#propertyscreennew').submit();
    }

    function formsave() {
        document.getElementById("actiontype").value = '3';

<?php if (!empty($locationsearchconf)) { ?>
            if ($("#survey_no").val().length > 0)
            {
                $.post("<?php echo $this->webroot; ?>Citizenentry/validatesurveynumbers",
                        {
                            csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>',
                            district: $("#district_id").val(),
                            landtype: $("#Developedland").val(),
                            taluka: $("#taluka_id").val(),
                            council: $("#corp_id").val(),
                            village: $("#village_id").val(),
                            lavel1: $("#level1_id").val(), lavel1_list: $("#level1_list_id").val(),
                            lavel2: $("#level_2_desc_eng").val(), lavel2_list: $("#level2_list_id").val(),
                            lavel3: $("#level_3_desc_eng").val(), lavel3_list: $("#level3_list_id").val(),
                            lavel4: $("#level_4_desc_eng").val(), lavel4_list: $("#level4_list_id").val(),
                            attribute_value: $("#survey_no").val(),
                            attribute_id: 'NA'

                        },
                function (data, status) {
                    if (data === 'success')
                    {
                        $("#survey_no").css("background-color", "#ffffff");
                        $('#propertyscreennew').submit();

                    } else {
                        $("#survey_no").css("background-color", "#f0ad4e");
                        $("#survey_no").focus();
                        $("#survey_no_error").html('Invalid servey Number');
                        return false;
                    }

                });

            } else {
                $('#propertyscreennew').submit();
            }
<?php } else { ?>
            $('#propertyscreennew').submit();
<?php } ?>

    }


</script>
<script>
    $(document).ready(function () {

//            if(!$.isNumeric($('#corp_id').val()) ){
//          $("#lbl_corp").hide(); 
//        }



        if ($.isNumeric($('#district_id').val()) && !$.isNumeric($('#taluka_id').val())) {
            dist_change_event();
        }


        $('#district_id').change(function () {
            dist_change_event();

        });
        // Circle
        $('#taluka_id').change(function () {

            taluka_change_event();
        });
        $('#circle_id').change(function () {
            circle_change_event();
        });


        $('#corp_id').change(function () {
            corp_id_chang_event();
        });


        $('#valutation_zone_id').change(function () {
            valutation_zone_change_event();
        });

        //village drop down list code
        $('#village_id').change(function () {
            village_id_chage_event();
            // $("#level1_list_id option").remove();
        });

        $('#level1_id').change(function () {
            level1_id_change_event();
        });

        if (document.getElementById("hflevel1list").value === '1') {
            $("#divlevel1list").show();
            $("#level1_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel2").value === '1') {
            $("#divlevel2").show();
            $("#level_2_desc_eng").prop("disabled", false);
        }

        //Level 2   
        $('#level_2_desc_eng').change(function () {
            level2_id_change_event();
        });

        if (document.getElementById("hflevel2list").value === '1') {
            $("#divlevel2list").show();
            $("#level2_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel3").value === '1') {
            $("#divlevel3").show();
            $("#level_3_desc_eng").prop("disabled", false);
        }

        //Level 3
        $('#level_3_desc_eng').change(function () {
            var level3list = $("#level_3_desc_eng option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Property/Level3_change_event', {level3list: level3list, village_id: village_id}, function (data)
            {
                if (data['level3listflag'].toString() === '1') {
                    var sc = '<option>--select--</option>';
                    $.each(data.data1, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel3list").val('1');
                    $("#level3_list_id option").remove();
                    $("#level3_list_id").append(sc);
                    $("#divlevel3list").fadeIn("slow");
                    $("#level3_list_id").prop("disabled", false);
                } else {
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
                if (data['level4flag'].toString() === '1') {
                    var sc1 = '<option>--select--</option>';
                    $.each(data.data2, function (index, val) {
                        sc1 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel4").val('1');
                    $("#level_4_desc_eng option").remove();
                    $("#level_4_desc_eng").append(sc1);
                    $("#divlevel4").fadeIn("slow");
                    $("#level_4_desc_eng").prop("disabled", false);
                } else {
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            });
        });

        if (document.getElementById("hflevel3list").value === '1') {
            $("#divlevel3list").show();
            $("#level3_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel4").value === '1') {
            $("#divlevel4").show();
            $("#level_4_desc_eng").prop("disabled", false);
        }

        //Level 4
        $('#level_4_desc_eng').change(function () {
            var level4list = $("#level_4_desc_eng option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Property/Level4_change_event', {level4list: level4list, village_id: village_id}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#hflevel4list").val('1');
                $("#level4_list_id option").remove();
                $("#level4_list_id").append(sc);
                $("#divlevel4list").fadeIn("slow");
                $("#level4_list_id").prop("disabled", false);
            });
        });

        if (document.getElementById("hflevel4list").value === '1') {
            $("#divlevel4list").show();
            $("#level4_list_id").prop("disabled", false);
        }



        /////madhuri code start
        $('#check_survey').click(function () {
            var survey_no = $("#survey_no").val();
            var village_id = $("#village_id option:selected").val();

            if (survey_no == '')
            {
                alert('Please enter survey number');
                $.postJSON('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: village_id}, function (data)
                {
                    var sc1 = '<option value="">--select--</option>';

                    $.each(data.data2, function (index, val) {
                        sc1 += "<option value=" + index + ">" + val + "</option>";

                    });

                    $("#level1_id option").remove();
                    $("#level1_id").append(sc1);
                    $("#divlevel1list").hide();

                    //makecombosearch();

                });
                return false;
            } else
            {
                var currentElement = $('#village_id');
                if (!$.isNumeric(currentElement.val()))
                {
                    $('form:first').submit();
                } else {

                    $.postJSON('<?php echo $this->webroot; ?>Property/get_zone', {survey_no: survey_no, village_id: village_id}, function (data1)
                    {

                        var sc1 = '';
                        $.each(data1, function (index1, val1) {

                            sc1 += "<option value=" + index1 + " >" + val1 + "</option>";
                        });

                        $("#level1_id option").remove();
                        $("#level1_id").append(sc1);
                    });
                    $.postJSON('<?php echo $this->webroot; ?>Property/get_location', {survey_no: survey_no, village_id: village_id}, function (data)
                    {

                        if (Object.keys(data).length > 0)
                        {

                            var sc = '<option>--select--</option>';
                            $.each(data, function (index, val) {

                                sc += "<option value=" + index + ">" + val + "</option>";
                            });

                            $("#level1_list_id option").remove();
                            $("#level1_list_id").append(sc);
                            $("#divlevel1list").fadeIn("slow");
                            $("#level1_list_id").prop("disabled", false);
                        } else
                        {
                            alert('This survey number is not available');
                            $.postJSON('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: village_id}, function (data)
                            {
                                var sc1 = '<option value="">--select--</option>';

                                $.each(data.data2, function (index, val) {
                                    sc1 += "<option value=" + index + ">" + val + "</option>";

                                });

                                $("#level1_id option").remove();
                                $("#level1_id").append(sc1);
                                $("#divlevel1list").hide();

                                //makecombosearch();

                            });
                        }

                    });
                }
            }

        });
        //madhuri code end


    });
    function getTaluka(dist) {
        //alert(dist);
        $.postJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: dist, subdivision_id: $("#subdivision_id").val()}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
        });
    }
    function circle_change_event() {
        //alert(dist);
        var circle_id = $('#circle_id').val();
        var developed_land_types_id = $('#developed_land_types_id').val();
        $.postJSON("<?php echo $this->webroot; ?>Property/circle_change_event", {circle_id: circle_id, developed_land_types_id: developed_land_types_id}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.village, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $("#village_id").prop("disabled", false);
            $("#village_id option").remove();
            $("#village_id").append(sc);
        });
    }


    function formview(valid) {
        $("#actiontype").val(11);
        $("#val_id").val(valid);
        jQuery.post("<?php echo $this->webroot; ?>" + 'rptview', {action: 'V', valno: valid, csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'}, function (data) {
            $('#rpt_modal_body').html(data);
            $('#myModal_rpt').modal("show");

        });
        return false;

    }

    function usage_filter(sub_cat_id)
    {

        $.post("<?php echo $this->webroot; ?>Property/usage_filter",
                {
                    sub_cat_id: sub_cat_id,
                    village_id: $('#village_id').val(),
                    csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                },
        function (data, status) {

            $("#usage-list").html(data);
            initiate_usage_event();


        });
    }
    function usage_filter_land_type(village_id)
    {
        $.post("<?php echo $this->webroot; ?>Property/usage_filter",
                {
                    village_id: village_id,
                    csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                },
        function (data, status) {

            $("#usage-list").html(data);
            initiate_usage_event();


        });
    }

    function initiate_usage_event() {
        $(".usage_cat_id").on('change', function () {

            var currentElement = $('#village_id');
            if (!$.isNumeric(currentElement.val())) {
                $('form:first').submit();
            } else if ($('#level1_id').length > 0 && $('#level1_id').children('option').length > 1 && !$.isNumeric($('#level1_id').val())) { // FOR CHECK  DYNAMIC FIELDS  EXIST               
                $('form:first').submit();
            } else if ($('#level1_list_id').length > 0 && $('#level1_list_id').children('option').length > 1 && !$.isNumeric($('#level1_list_id').val())) { // FOR CHECK  DYNAMIC FIELDS  EXIST

                $('form:first').submit();
            } else {
                var usagecatlist = '';
                $('.usage_cat_id input[type="checkbox"]').each(function () {

                    if ($(this).prop('checked') === true) {

                        var usagecatid = $(this).val();
                        //alert(usagecatid);
                        if (usagecatlist === '') {
                            usagecatlist = usagecatid;
                        } else {
                            usagecatlist = usagecatlist + ',' + usagecatid;
                        }


                    }
                });

                $("#construction_type_id").val($("#construction_type_id option:first").val());
                $("#depreciation_id").val($("#depreciation_id option:first").val());
                $("#road_vicinity_id").val($("#road_vicinity_id option:first").val());
                $("#user_defined_dependency1_id").val($("#user_defined_dependency1_id option:first").val());
                $("#user_defined_dependency2_id").val($("#user_defined_dependency2_id option:first").val());
                if (usagecatlist !== '') {

                    $.post("<?php echo $this->webroot; ?>Property/checkrate_exist",
                            {
                                usagecatlist: usagecatlist,
                                district: $("#district_id").val(),
                                landtype: $("#Developedland").val(),
                                taluka: $("#taluka_id").val(),
                                council: $("#corp_id").val(),
                                village: $("#village_id").val(),
                                survey_no: $("#survey_no").val(),
                                lavel1: $("#level1_id").val(), lavel1_list: $("#level1_list_id").val(),
                                lavel2: $("#level_2_desc_eng").val(), lavel2_list: $("#level2_list_id").val(),
                                lavel3: $("#level_3_desc_eng").val(), lavel3_list: $("#level3_list_id").val(),
                                lavel4: $("#level_4_desc_eng").val(), lavel4_list: $("#level4_list_id").val(),
                                csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                            },
                    function (data, status) {
                        if ($.trim(data) === 'success') {

                            $.postJSON('<?php echo $this->webroot; ?>Property/usagecategory_change_event', {usagecatid: usagecatlist}, function (data)
                            {
                                if (
                                        data['hfconstructionflag'].toString() === 'Y' ||
                                        data['hfdepreciationflag'].toString() === 'Y' ||
                                        data['hfroadvicinityflag'].toString() === 'Y' ||
                                        data['hfuserdependency1flag'].toString() === 'Y' ||
                                        data['hfuserdependency2flag'].toString() === 'Y'
                                        ) {
                                    $("#dependencypanel").fadeIn("slow");
                                } else {
                                    $("#dependencypanel").fadeOut("slow");
                                }



                                if (data['hfconstructionflag'].toString() === 'Y') {
                                    $("#hfconstructionflag").val('Y');
                                    $("#divconstructiontype").fadeIn("slow");
                                } else {
                                    $("#hfconstructionflag").val('N');
                                    $("#divconstructiontype").fadeOut("slow");
                                }

                                if (data['hfdepreciationflag'].toString() === 'Y') {
                                    $("#hfdepreciationflag").val('Y');
                                    $("#divdepreciationtype").fadeIn("slow");
                                } else {
                                    $("#hfdepreciationflag").val('N');
                                    $("#divdepreciationtype").fadeOut("slow");

                                }

                                if (data['hfroadvicinityflag'].toString() === 'Y') {
                                    $("#hfroadvicinityflag").val('Y');
                                    $("#divroadvicinity").fadeIn("slow");
                                } else {
                                    $("#hfroadvicinityflag").val('N');
                                    $("#divroadvicinity").fadeOut("slow");
                                }

                                if (data['hfuserdependency1flag'].toString() === 'Y') {
                                    $("#hfuserdependency1flag").val('Y');
                                    $("#divuserdependency1").fadeIn("slow");
                                } else {
                                    $("#hfuserdependency1flag").val('N');
                                    $("#divuserdependency1").fadeOut("slow");
                                }

                                if (data['hfuserdependency2flag'].toString() === 'Y') {
                                    $("#hfuserdependency2flag").val('Y');
                                    $("#divuserdependency2").fadeIn("slow");
                                } else {
                                    $("#hfuserdependency2flag").val('N');
                                    $("#divuserdependency2").fadeOut("slow");
                                }
                                if (data['hfboundaryflag'].toString() === 'Y') {
                                    $("#hfboundaryflag").val('Y');
                                    $("#boundarydiv").fadeIn("slow");
                                } else {
                                    $("#hfboundaryflag").val('N');
                                    $("#boundarydiv").fadeOut("slow");
                                }

                                ruleChangeEvent();
                            });

                        } else if (data === 'fail') {
                            $('#ItemListDiv').html('');
                            $("#dependencypanel").fadeOut("slow");
                            $('#ratenotfound').modal('show');

                        }


                    });

                } else {
                    $("#dependencypanel").fadeOut("slow");
                    $('#ItemListDiv').html('');
                }




            }







        });
    }

///change evnt function start

    function checkrate_exist(usagecatlist) {

        var rvalue = 'validate';
        var currentElement = $('#village_id');
        if (!$.isNumeric(currentElement.val()))
        {
            $('form:first').submit();

        } else {
            rvalue = 'fail';


        }



    }

    function dist_change_event()
    {
        var dist = $("#district_id option:selected").val();

        $("#divlevel1list").hide();
        $.postJSON('<?php echo $this->webroot; ?>Property/get_corp_list', {district: dist}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            var flag = 0;
            $.each(data.corp, function (index, val) {
                flag = 1;
                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $("#corp_id option").remove();
            $("#corp_id").append(sc);
//            if (flag === 1 && $("#developed_land_types_id").val() == 1) {
//                $("#lbl_corp").show();
//            } else {
//                $("#lbl_corp").hide();
//            }

<?php if ($configure[0][0]['is_subdiv'] == 'Y') { ?>
                get_subdivision(dist);
<?php } ?>
            getTaluka(dist);


        });
    }
    function get_subdivision(district_id) {
        $.postJSON('<?php echo $this->webroot; ?>Property/getsubdiv', {district_id: district_id}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            $.each(data, function (index, val) {

                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#subdivision_id option").remove();
            $("#subdivision_id").append(sc);
        });
    }
    function taluka_change_event()
    {
        var tal = $("#taluka_id option:selected").val();
        var dist = $("#district_id option:selected").val();
        var landtype = $("#developed_land_types_id option:selected").val();
        var corp = $("#corp_id option:selected").val();
        var finyear = $("#fin_yr_id option:selected").val();

        $("#divlevel1list").hide();

        $.postJSON('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal, dist: dist, landtype: landtype, corp: corp, finyear: finyear}, function (data)
        {

            $("#city_village").show();

            var sc = '<option value="">--select--</option>';
            $.each(data.village, function (index, val) {

                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $("#village_id option").remove();
            $("#village_id").append(sc);
            $("#lblvillage_id").show();
            $("#lblcitytown").hide();

            var sc = '<option value="">--select--</option>';
            $.each(data.circle, function (index, val) {

                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $("#circle_id option").remove();
            $("#circle_id").append(sc);


            $.postJSON('<?php echo $this->webroot; ?>Property/get_corp_list', {taluka: tal}, function (data2)
            {

                var sc2 = '<option value="">--select--</option>';
                var flag = 0;
                $.each(data2.corp, function (index2, val2) {
                    flag = 1;
                    sc2 += "<option value=" + index2 + ">" + val2 + "</option>";
                });

                $("#corp_id option").remove();
                $("#corp_id").append(sc2);

//                if (flag === 1 && $("#developed_land_types_id").val() == 1) {
//                    $("#lbl_corp").show();
//                } else {
//                    $("#lbl_corp").hide();
//                }
            });




        });
    }

    function corp_id_chang_event()
    {
        var corp = $("#corp_id option:selected").val();
        var dist = $("#district_id option:selected").val();
        var landtype = $("#developed_land_types_id option:selected").val();
        var tal = $("#taluka_id option:selected").val();
        var finyear = $("#fin_yr_id option:selected").val();
        $("#divlevel1list").hide();
        $.postJSON('<?php echo $this->webroot; ?>Property/corp_change_event', {tal: tal, corp: corp, dist: dist, finyear: finyear, landtype: landtype}, function (data)
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


        });
    }

    function valutation_zone_change_event()
    {
        var zone_id = $("#valutation_zone_id option:selected").val();
        var tal = $("#taluka_id option:selected").val();
        var land_type = $("#Developedland option:selected").val();
        $("#divlevel1list").hide();

        $.postJSON('<?php echo $this->webroot; ?>Property/vibhag_change_event', {zone_id: zone_id, tal: tal, land_type: land_type}, function (data)
        {
            var sc2 = '<option value="">--select--</option>';
            $.each(data.village, function (index, val) {
                sc2 += "<option value=" + index + ">" + val + "</option>";
            });
            $("#village_id").prop("disabled", false);
            $("#village_id option").remove();
            $("#village_id").append(sc2);
        });
    }

    function village_id_chage_event()
    {
        var village_id = $("#village_id option:selected").val();
        $("#divlevel1list").hide();
        // $("#viewrate").show();
        $.postJSON('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: village_id}, function (data)
        {
            var sc1 = '<option value="">--select--</option>';

            $.each(data.data2, function (index, val) {
                sc1 += "<option value=" + index + ">" + val + "</option>";

            });

            $("#level1_id option").remove();
            $("#level1_id").append(sc1);


            //makecombosearch();
            usage_filter_land_type(village_id);
        });
    }

    function level1_id_change_event()
    {

        var level1list = $("#level1_id option:selected").val();
        var village_id = $("#village_id option:selected").val();
        $.postJSON('<?php echo $this->webroot; ?>Property/Level1_change_event', {level1list: level1list, village_id: village_id}, function (data)
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

            $(".chosen-select").select2();
        });


    }

    function level2_id_change_event()
    {
        var level2list = $("#level_2_desc_eng option:selected").val();
        var village_id = $("#village_id option:selected").val();
        $.postJSON('<?php echo $this->webroot; ?>Property/Level2_change_event', {level2list: level2list, village_id: village_id}, function (data)
        {
            if (data['level2listflag'].toString() === '1') {
                var sc = '<option>--select--</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#hflevel2list").val('1');
                $("#level2_list_id option").remove();
                $("#level2_list_id").append(sc);
                $("#divlevel2list").fadeIn("slow");
                $("#level2_list_id").prop("disabled", false);
            } else {
                $("#divlevel2list").hide();
                $("#divlevel3").hide();
                $("#divlevel3list").hide();
                $("#divlevel4").hide();
                $("#divlevel4list").hide();
            }

            if (data['level3flag'].toString() === '1') {
                var sc1 = '<option>--select--</option>';
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#hflevel3").val('1');
                $("#level_3_desc_eng option").remove();
                $("#level_3_desc_eng").append(sc1);
                $("#divlevel3").fadeIn("slow");
                $("#level_3_desc_eng").prop("disabled", false);
            } else {
                $("#divlevel3").hide();
                $("#divlevel3list").hide();
                $("#divlevel4").hide();
                $("#divlevel4list").hide();
            }
        });
    }
</script> 

<?php $viewrate = $this->requestAction(array('controller' => 'Citizenentry', 'action' => 'check_view_rate_config'));
?> 
<div id="valuationscreen">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblpropertyscreenhead'); ?></h3></center>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/property/propertyscreennew_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">  
                            <div class="col-sm-2" id="fin_yr_idhide">
                                <label  class="control-label"><?php echo __('lblfineyer'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('finyear_id', array('options' => $finyearList, 'id' => 'fin_yr_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                <span class="form-error" id="finyear_id_error"></span>
                            </div>

                            <?php if ($configure[0][0]['is_div'] == 'Y') { ?>
                                <div class="col-sm-2">
                                    <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                                    <span class="form-error" id="division_id_error"></span>
                                </div>
                            <?php } ?> 
                            <?php if ($configure[0][0]['is_dist'] == 'Y') { ?>
                                <div class="col-sm-2">
                                    <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                                    <span class="form-error" id="district_id_error"></span>
                                </div>
                            <?php } ?> 
                            <?php if ($configure[0][0]['is_subdiv'] == 'Y') { ?>
                                <div class="col-sm-2">
                                    <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdivision'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                    <span class="form-error" id="subdivision_id_error"></span>
                                </div>
                            <?php } ?>
                            <div class="col-sm-2" >
                                <label for="developed_land_types_id" class="control-label" ><?php echo __('lbldellandtype'); ?> <span class="star">*</span></label> 
                                <?php echo $this->Form->input('developed_land_types_id', array('options' => $landtypes, 'empty' => '--select--', 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                <span class="form-error" id="developed_land_types_id_error"></span>
                            </div>
                            <?php if ($configure[0][0]['is_taluka'] == 'Y') { ?>

                                <div class="col-sm-2" >
                                    <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                    <span class="form-error" id="taluka_id_error"></span>
                                </div>

                            <?php } ?>  

                            <?php if ($configure[0][0]['is_circle'] == 'Y') { ?>

                                <div class="col-sm-2">
                                    <label for="circle_id" class="control-label"><?php echo __('lbladmcircle'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('circle_id', array('options' => $circle, 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                    <span class="form-error" id="circle_id_error"></span>
                                </div>

                            <?php } ?>  

                            <div class="col-sm-2" id="lbl_corp">
                                <label for="corp_id" class="control-label" ><?php echo __('lbllocalbody'); ?></label>  
                                <?php echo $this->Form->input('corp_id', array('options' => array($corpclasslist), 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                <span class="form-error" id="corp_id_error"></span>
                            </div>

                            <div class="col-sm-2" id="city_village">
                                <label for="village_id" class="control-label" id="lblvillage_id"><?php echo __('lblcityvillage'); ?> <span class="star">*</span></label>
                                <label for="village_id" class="control-label" hidden="true" id="lblcitytown"><?php echo __('lblcityarea'); ?></label>
                                <?php echo $this->Form->input('village_id', array('options' => $villagenname, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                <span class="form-error" id="village_id_error"></span>
                            </div>
                            <div class="clearfix"></div>

                        </div>
                    </div>

                    <?php if (!empty($locationsearchconf)) { ?>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="col-sm-12"  >
                                <div class="form-group">
                                    <div class="col-sm-2" >
                                        <label for="survey_no" class="control-label" ><?php echo __('lblsurveyno'); ?></label>                              
                                    </div> 
                                    <div class="col-sm-2" >
                                        <?php echo $this->Form->input('survey_no', array('div' => false, 'type' => 'text', 'id' => 'survey_no', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                        <span id="survey_no_error" class="form-error"></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button"  class="btn btn-primary btn-sm"  id="check_survey" name="btnSave"><i class="fa fa-check-square-o" aria-hidden="true"></i> <?php echo __('lblbtncheck'); ?> </button>
                                    </div>
                                    <div class="col-sm-2 pull-left"  >
                                        <button type="button"  class="btn btn-primary btn-sm"  id="showsurveyno"><span class="fa fa-search"></span> <?php echo __('lblshowsurveyno'); ?> </button>                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <hr class="hr1">
                    <?php if ($configure1[0]['levelconfig']['is_level_1_id'] == 1 || $configure1[0]['levelconfig']['is_level_2_id'] == 1 || $configure1[0]['levelconfig']['is_level_3_id'] == 1 || $configure1[0]['levelconfig']['is_level_4_id'] == 1) { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-2"   id="divlevel1lbl">
                                        <label for="lblLevel1" class="control-label"><?php echo __('lbllocation'); ?></label>
                                    </div>
                                    <div class="col-sm-2"   divlevel1field>
                                        <?php echo $this->Form->input('level1_id', array('empty' => '--select--', 'options' => array($level1propertydata), 'id' => 'level1_id', 'label' => false, 'class' => 'form-control input-sm ')); ?>
                                        <span class="form-error" id="level1_id_error"></span>
                                    </div>
                                    <div class="col-sm-4" id="divlevel1list" hidden="true">
                                        <?php echo $this->Form->input('level1_list_id', array('options' => array('empty' => '--select--', $level1propertylist), 'id' => 'level1_list_id', 'label' => false, 'class' => 'form-control input-sm ', 'disabled' => 'disabled')); ?>
                                        <span class="form-error" id="level1_list_id_error"></span>
                                    </div>
                                    <div class="col-sm-2" id="divlevel2" hidden="true">
                                        <?php echo $this->Form->input('level2_id', array('options' => array('empty' => '--select--', $level2propertydata), 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm ', 'disabled' => 'disabled')); ?>
                                    </div>
                                    <div class="col-sm-2" id="divlevel2list" hidden="true">
                                        <?php echo $this->Form->input('level2_list_id', array('options' => array($level2propertylist), 'empty' => '--select--', 'id' => 'level2_list_id', 'label' => false, 'class' => 'form-control input-sm ', 'disabled' => 'disabled')); ?>
                                    </div>
                                    <?php if ($viewrate == 'Y') { ?>

                                        <!--//madhuri code start-->
                                        <div class="col-sm-2 pull-right"  >
                                            <button type="button"  class="btn btn-primary btn-sm"  id="viewrate"><span class="fa fa-search"></span><?php echo __('lblviewrate'); ?> </button>                      
                                        </div>
                                        <!--//madhuri code end-->
                                    <?php } ?>

                                </div>  
                            </div>
                        </div>
                        <div class="row" style="display:none">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-2" >
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-2" id="divlevel3" hidden="true">
                                        <?php echo $this->Form->input('level3_id', array('options' => array('empty' => '--select--', $level3propertydata), 'id' => 'level_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                    </div>
                                    <div class="col-sm-2" id="divlevel3list" hidden="true">
                                        <?php echo $this->Form->input('level3_list_id', array('options' => array($level3propertylist), 'empty' => '--select--', 'id' => 'level3_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                    </div>
                                    <div class="col-sm-2" id="divlevel4" hidden="true">
                                        <?php echo $this->Form->input('level4_id', array('options' => array('empty' => '--select--', $level4propertydata), 'id' => 'level_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                    </div>
                                    <div class="col-sm-2" id="divlevel4list" hidden="true">
                                        <?php echo $this->Form->input('level4_list_id', array('options' => array($level4propertylist), 'empty' => '--select--', 'id' => 'level4_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($propgroup) { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-body"> 
                        <div class="col-sm-2" >
                            <label>Select Property Group</label>
                        </div>
                        <div class="col-sm-2" >
                            <?php echo $this->Form->input('property_group_flag', array('options' => array('A' => 'A (Party 1)', 'B' => 'B (Party 2)'), 'empty' => '--select--', 'id' => 'property_group_flag', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span class="form-error" id="property_group_flag_error"></span>
                        </div>                            
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-sm-8 divborder1">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="box-tools pull-left"><?php echo __('lblpropertyusage'); ?></li>
                                    <li class="box-tools pull-right">
                                        <div class="input-group"> 
                                            <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span> 
                                            <?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'brn btn-search')); ?>
                                        </div> 
                                    </li>
                                </ul>
                            </div>
                            <div class="usage-list" id="usage-list">
                                <?php echo $this->Form->input('usage_cat_id', array('type' => 'select', 'options' => $usagecategory, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => ' usage_cat_id')); ?>
                            </div>
                            <div class="panel-footer">  <span class="form-error" id="usage_cat_id_error"></span></div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="box-tools pull-left"><?php echo __('lblusamaincat'); ?></li>
                                </ul>
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel-group" id="accordion">
                                            <?php
                                            foreach ($maincat_id as $key => $main) {
                                                ?> 
                                                <div class="panel panel-danger">
                                                    <div class="panel-heading">
                                                        <h5 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>"> 
                                                                <i class="more-less glyphicon glyphicon-plus"></i>
                                                                <div class="text-success">  <?php echo $main; ?></div> 
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse">
                                                        <div class="panel-body no-padding">
                                                            <table class="table">
                                                                <?php
                                                                foreach ($usage_rule_link as $subcat) {
                                                                    if ($subcat['0']['usage_main_catg_id'] == $key) {
                                                                        ?>
                                                                        <tr>
                                                                            <th>
                                                                                <span href="" class="usage-filter" onclick="usage_filter('<?php echo $subcat['0']['usage_sub_catg_id']; ?>');"> 
                                                                                    <?php echo $subcat['0']['usage_sub_catg_desc_' . $lang]; ?>
                                                                                </span>
                                                                            </th>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row" id="dependencypanel"  hidden="false">
                            <fieldset class="scheduler-border ">
                                <legend class="scheduler-border"><?php echo __('lbldependencyattributes'); ?></legend>
                                <div class="row"> 
                                    <div class="col-md-12"> 
                                        <div  id="divconstructiontype" class="col-sm-5 pad"> 
                                            <div class="col-sm-6">
                                                <label for="construction_type_id" class="control-label"><?php echo __('lblconstuctiontye'); ?> <span class="star">*</span></label>
                                            </div>  
                                            <div class="col-sm-5 col-md-offset-1 ">
                                                <?php echo $this->Form->input('construction_type_id', array('options' => $construction_type_id, 'empty' => '--select--', 'id' => 'construction_type_id', 'label' => false, 'class' => 'form-control input-sm usage-input-fields', 'onchange' => 'javascript:selectrule();')); ?>
                                                <span class="form-error" id="construction_type_id_error"></span>

                                            </div>  
                                        </div>  
                                        <div id="divdepreciationtype" class="col-sm-5 pad">
                                            <div class="col-sm-5">
                                                <label for="depreciation_id" class="control-label"> <?php echo __('lblpropage'); ?> <span class="star">*</span></label>
                                            </div>  
                                            <div class="col-sm-5">
                                                <?php echo $this->Form->input('depreciation_id', array('options' => $depreciation_id, 'empty' => '--select--', 'id' => 'depreciation_id', 'label' => false, 'class' => 'form-control input-sm usage-input-fields', 'onchange' => 'javascript:selectrule();')); ?>
                                                <span class="form-error" id="depreciation_id_error"></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-12"> 
                                        <div  id="divroadvicinity" class="col-sm-5 pad"> 
                                            <div class="col-sm-6">                                     
                                                <label for="road_vicinity_id" class="control-label"><?php echo __('lblroadvicinity'); ?> <span class="star">*</span></label>
                                            </div>
                                            <div class="col-sm-5 col-md-offset-1 ">
                                                <?php echo $this->Form->input('road_vicinity_id', array('options' => $road_vicinity_id, 'empty' => '--select--', 'id' => 'road_vicinity_id', 'label' => false, 'class' => 'form-control input-sm usage-input-fields', 'onchange' => 'javascript:selectrule();')); ?>
                                                <span class="form-error" id="road_vicinity_id_error"></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="divuserdependency1" class="col-sm-6 pad">
                                    <div class="col-sm-6">
                                        <label for="user_defined_dependency1_id" class="control-label"><?php echo __('lbluserdependency1'); ?> <span class="star">*</span></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('user_defined_dependency1_id', array('options' => $user_defined_dependancy1, 'empty' => '--select--', 'id' => 'user_defined_dependency1_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?>
                                        <span class="form-error" id="user_defined_dependency1_id_error"></span>

                                    </div>
                                </div>
                                <div id="divuserdependency2" class="col-sm-6 pad">
                                    <div class="col-sm-6">
                                        <label for="user_defined_dependency2_id" class="control-label"><?php echo __('lbluserdependency2'); ?> <span class="star">*</span></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('user_defined_dependency2_id', array('options' => $user_defined_dependancy2, 'empty' => '--select--', 'id' => 'user_defined_dependency2_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?>
                                        <span class="form-error" id="user_defined_dependency2_id_error"></span>

                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row" id="ItemListDiv">
                            <?php echo $this->element('Property/rulechangeevent'); ?>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

</div>


<?php // Hidden Fields       ?>
<input type='hidden' value=<?php echo $actiontype; ?> name='actiontype' id='actiontype'/> 
<input type='hidden' value=<?php echo $hfusage_sub_sub_catg_id; ?> name='hfusage_sub_sub_catg_id' id='hfusage_sub_sub_catg_id'/>
<input type='hidden' value=<?php echo $hfevalrule_id; ?> name='hfevalrule_id' id='hfevalrule_id'/>
<input type='hidden' value=<?php echo $hfconstructionflag; ?> name='hfconstructionflag' id='hfconstructionflag'/>
<input type='hidden' value=<?php echo $hfdepreciationflag; ?> name='hfdepreciationflag' id='hfdepreciationflag'/>
<input type='hidden' value=<?php echo $hfroadvicinityflag; ?> name='hfroadvicinityflag' id='hfroadvicinityflag'/>
<input type='hidden' value=<?php echo $hfuserdependency1flag; ?> name='hfuserdependency1flag' id='hfuserdependency1flag'/>
<input type='hidden' value=<?php echo $hfuserdependency2flag; ?> name='hfuserdependency2flag' id='hfuserdependency2flag'/>

<input type='hidden' value=<?php echo $hflevel1list; ?> name='hflevel1list' id='hflevel1list'/>
<input type='hidden' value=<?php echo $hflevel2; ?> name='hflevel2' id='hflevel2'/>
<input type='hidden' value=<?php echo $hflevel2list; ?> name='hflevel2list' id='hflevel2list'/>
<input type='hidden' value=<?php echo $hflevel3; ?> name='hflevel3' id='hflevel3'/>
<input type='hidden' value=<?php echo $hflevel3list; ?> name='hflevel3list' id='hflevel3list'/>
<input type='hidden' value=<?php echo $hflevel4; ?> name='hflevel4' id='hflevel4'/>
<input type='hidden' value=<?php echo $hflevel4list; ?> name='hflevel4list' id='hflevel4list'/>
<input type='hidden' value=<?php echo $valuation_id; ?> name='lastinsertid' id='lastinsertid'/>
<input type='hidden'  name='hfboundaryflag' id='hfboundaryflag' value="<?php echo @$hfboundaryflag; ?>"/>

<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Survey Numbers</h4>
            </div>
            <div class="modal-body" id="surveyno_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!--madhuri code-->
<div id="rateModal" class="modal fade" role="dialog">
    <div class="modal-dialog2">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rate chart</h4>
            </div>
            <div class="modal-body" id="rate_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="myModal_rpt" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblpropertyscreenhead'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--/ Modal -->
<script>
    $("document").ready(function () {
        function toggleIcon(e) {
            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('glyphicon-plus glyphicon-minus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);
    });
</script>
<div id="ratenotfound" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ERROR DIALOG</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger"><span class="glyphicon glyphicon-record"></span> <strong>Rate Not Found!</strong> <hr class="message-inner-separator"><p>Check Your Location And Usage Rule.</p></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>