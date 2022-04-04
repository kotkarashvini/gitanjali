<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
//        $('#s').keyup(function () {
//            var valThis = $(this).val().toLowerCase();
//            $('.countryList>li').each(function () {
//                var text = $(this).text().toLowerCase();
//                (text.indexOf(valThis) == 0) ? $(this).show() : $(this).hide();
//            });
//        });


        document.getElementById('newdoc').style.backgroundColor = 'wheat';
        document.getElementById('valuation_entry').style.backgroundColor = 'wheat';


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

//        $('#search_rule').on('keyup', function () {
//            var query = this.value;
//            $('.usage_cat_id input[type="checkbox"]').each(function (i, elem) {
////            $('[id^="chk"]').each(function (i, elem) {
//                if (elem.value.indexOf(query) !== -1) {
//                    elem.style.display = 'inline';
//                } else {
//                    elem.style.display = 'none';
//                }
//            });
//        });

        var vwflag = "<?php echo $pdfflag; ?>";
        if (vwflag == 11) {
            $("#viewData").html("<?php echo $design; ?> ");
            $('#viewData').dialog({
                modal: true,
                height: 800,
                width: 800,
                title: 'Valuation',
                open: function () {
                    var closeBtn = $('.ui-dialog-titlebar-close');
                    closeBtn.append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
                }

            });
            $("#btnPdf").prop("disabled", false);
            $("#btnView").prop("disabled", false);
        } else if (vwflag == 1) {
            $("#btnPdf").prop("disabled", false);
            $("#btnView").prop("disabled", false);
        }
        else {
            $("#btnPdf").prop("disabled", true);
            $("#btnView").prop("disabled", true);
        }

        $("#btnView").click(function () {
            $("#actiontype").val('11');
        });
        $("#btnPdf").click(function () {
            $("#actiontype").val('12');
        });

        if (!navigator.onLine)
        {
            //window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward();
        }
        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };

        if (document.getElementById('hfconstructionflag').value === 'Y') {
            $("#divconstructiondepreciation").show();
            $('#divconstructiontype').show();
        }
        else {
            $('#divconstructiontype').hide();
        }

        if (document.getElementById('hfdepreciationflag').value === 'Y') {
            $("#divconstructiondepreciation").show();
            $('#divdepreciationtype').show();
        }
        else {
            $('#divdepreciationtype').hide();
        }

        if (document.getElementById('hfconstructionflag').value === 'N' && document.getElementById('hfdepreciationflag').value === 'N') {
            $("#divconstructiondepreciation").hide();
        }

        if (document.getElementById('hfroadvicinityflag').value === 'Y') {
            $("#divroadvicinityuser1").show();
            $('#divroadvicinity').show();
        }
        else {
            $('#divroadvicinity').hide();
        }

        if (document.getElementById('hfuserdependency1flag').value === 'Y') {
            $("#divroadvicinityuser1").show();
            $('#divuserdependency1').show();
        }
        else {
            $('#divuserdependency1').hide();
        }

        if (document.getElementById('hfroadvicinityflag').value === 'N' && document.getElementById('hfuserdependency1flag').value === 'N') {
            $("#divroadvicinityuser1").hide();
        }

        if (document.getElementById('hfuserdependency2flag').value === 'Y') {
            $('#divuserdependency2').show();
        }
        else {
            $('#divuserdependency2').hide();
        }


        $(".usage_cat_id").on('change', function () {
            //alert("Thanks for clicking.");
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
            //alert(usagecatlist);
            $("#construction_type_id").val('');
            $("#depreciation_id").val('');
            $("#road_vicinity_id").val('');
            $("#user_defined_dependency1_id").val('');
            $("#user_defined_dependency2_id").val('');
            if (usagecatlist !== '') {
                $.post('<?php echo $this->webroot; ?>Property/usagecategory_change_event', {usagecatid: usagecatlist}, function (data)
                {
                    //$("#hflevel2").val('1');
                    //alert(data['hfconstructionflag'].toString());
                    if (data['hfconstructionflag'].toString() === 'N' && data['hfdepreciationflag'].toString() === 'N') {
                        $("#divconstructiondepreciation").fadeOut("slow");
                    }
                    if (data['hfconstructionflag'].toString() === 'Y') {
                        $("#hfconstructionflag").val('Y');
                        $("#divconstructiondepreciation").fadeIn("slow");
                        $("#divconstructiontype").fadeIn("slow");
                    } else {
                        $("#hfconstructionflag").val('N');
                        $("#divconstructiontype").fadeOut("slow");

                    }
                    if (data['hfdepreciationflag'].toString() === 'Y') {
                        $("#hfdepreciationflag").val('Y');
                        $("#divconstructiondepreciation").fadeIn("slow");
                        $("#divdepreciationtype").fadeIn("slow");
                    } else {
                        $("#hfdepreciationflag").val('N');
                        $("#divdepreciationtype").fadeOut("slow");

                    }

                    if (data['hfroadvicinityflag'].toString() === 'N' && data['hfuserdependency1flag'].toString() === 'N') {
                        $("#divroadvicinityuser1").fadeOut("slow");
                    }
                    if (data['hfroadvicinityflag'].toString() === 'Y') {
                        $("#hfroadvicinityflag").val('Y');
                        $("#divroadvicinityuser1").fadeIn("slow");
                        $("#divroadvicinity").fadeIn("slow");
                    } else {
                        $("#hfroadvicinityflag").val('N');
                        $("#divroadvicinity").fadeOut("slow");

                    }
                    if (data['hfuserdependency1flag'].toString() === 'Y') {
                        $("#hfuserdependency1flag").val('Y');
                        $("#divroadvicinityuser1").fadeIn("slow");
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
                    $("#actiontype").val('5');
                    $('#valuation_entry').submit();
                },'json');
            } else {
                $("#divconstructiondepreciation").fadeOut("slow");
                $("#divroadvicinityuser1").fadeOut("slow");
                $("#divuserdependency2").fadeOut("slow");
//                $("#actiontype").val('5');
//                $('#valuation_entry').submit();
            }
            //$('#valuation_entry').submit();
        });


    });

    function selectrule() {
//        var usage_sub_sub_catg_id = $("#usage_sub_sub_catg_id option:selected").val();
//        document.getElementById("actiontype").value = '1';
//        document.getElementById("hfusage_sub_sub_catg_id").value = usage_sub_sub_catg_id;
//        $('#valuation_entry').submit();

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
        //alert(usagecatlist);
        if (usagecatlist !== '') {
//            $.getJSON('usagecategory_change_event', {usagecatid: usagecatlist}, function (data)
//            {
            $("#actiontype").val('1');
            $('#valuation_entry').submit();
//            });
        }
    }

    function selectevalrule() {
        var usage_sub_sub_catg_id = $("#usage_sub_sub_catg_id option:selected").val();
        var evalrule_id = $("#evalrule_id option:selected").val();
        document.getElementById("actiontype").value = '2';
        document.getElementById("hfusage_sub_sub_catg_id").value = usage_sub_sub_catg_id;
        document.getElementById("hfevalrule_id").value = evalrule_id;
        $('#valuation_entry').submit();
    }

    function formsave() {
        document.getElementById("actiontype").value = '3';
        $('#valuation_entry').submit();
    }

    function selectsubsubcategory() {
        document.getElementById("actiontype").value = '4';
        $('#valuation_entry').submit();
    }

</script>
<script>
    $(document).ready(function () {
        if ($("#tal_id").val() == "") {
            var dist = $("#dist_id option:selected").val();
            getTaluka(dist);
        }
//        //district
//        $('#div_id').change(function () {
////             alert('hii');
//            var div = $("#div_id option:selected").val();
//            $.getJSON('getdist', {div: div}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                if (dist == 1) {
//                    $("#dist_id").prop("disabled", false);
//                    $("#dist_id option").remove();
//                    $("#dist_id").append(sc);
//                } else if (dist == 0 && subdiv == 1) {
//                    $("#subdivision_id").prop("disabled", false);
//                    $("#subdivision_id option").remove();
//                    $("#subdivision_id").append(sc);
//                } else if (dist == 0 && subdiv == 0 && tal == 1) {
//                    $("#tal_id").prop("disabled", false);
//                    $("#tal_id option").remove();
//                    $("#tal_id").append(sc);
//                } else if (dist == 0 && subdiv == 0 && tal == 0 && circle == 1) {
//                    $("#circle_id").prop("disabled", false);
//                    $("#circle_id option").remove();
//                    $("#circle_id").append(sc);
//                } else {
//                    $("#ulb_type_id").prop("disabled", false);
//                    $("#ulb_type_id option").remove();
//                    $("#ulb_type_id").append(sc);
//                }
//            });
//        });

        //District Change Event
        $('#dist_id').change(function () {
            var dist = $("#dist_id option:selected").val();
            getTaluka(dist);
        });

//        // Taluka
//        $('#subdivision_id').change(function () {
//            var subdiv = $("#subdivision_id option:selected").val();
//            $.getJSON('gettalukaname', {subdiv: subdiv}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                if (tal == 1) {
//                    $("#tal_id").prop("disabled", false);
//                    $("#tal_id option").remove();
//                    $("#tal_id").append(sc);
//                } else if (tal == 0 && circle == 1) {
//                    $("#circle_id").prop("disabled", false);
//                    $("#circle_id option").remove();
//                    $("#circle_id").append(sc);
//                } else {
//                    $("#ulb_type_id").prop("disabled", false);
//                    $("#ulb_type_id option").remove();
//                    $("#ulb_type_id").append(sc);
//                }
//            });
//        });

        // Circle
        $('#tal_id').change(function () {
            var tal = $("#tal_id option:selected").val();

            $.post('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal}, function (data)
            {
                //var sc1 = "<option value='9999999999'>NA</option>";
                //alert(data['corp'].size);
//                var sc1;
//                $.each(data.corp, function (index, val) {
//                    sc1 += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#corp_id").prop("disabled", false);
//                $("#corp_id option").remove();
//                $("#corp_id").append(sc1);
//                $("#corp_id").val('9999999999');
//
//                var sc2 = '<option>--select--</option>';
//                $.each(data.village, function (index, val) {
//                    sc2 += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#village_id").prop("disabled", false);
//                $("#village_id option").remove();
//                $("#village_id").append(sc2);

                var sc3 = '<option>--select--</option>';
                $.each(data.landtype, function (index, val) {
                    sc3 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#Developedland").prop("disabled", false);
                $("#Developedland option").remove();
                $("#Developedland").append(sc3);
            },'json');
        });

//        // Governing Body
//        $('#circle_id').change(function () {
//            var ulb = $("#circle_id option:selected").val();
//            $.getJSON('getulb', {ulb: ulb}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#ulb_type_id").prop("disabled", false);
//                $("#ulb_type_id option").remove();
//                $("#ulb_type_id").append(sc);
//            });
//        });

//        //Corporation List
//        $('#ulb_type_id').change(function () {
//            var ulb = $("#ulb_type_id option:selected").val();
//            $.getJSON('getcorp', {ulb: ulb}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#corp_id").prop("disabled", false);
//                $("#corp_id option").remove();
//                $("#corp_id").append(sc);
//            });
//        });

        $('#Developedland').change(function () {
            var landtype = $("#Developedland option:selected").val();
            var tal = $("#tal_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Property/land_change_event', {landtype: landtype, tal: tal}, function (data)
            {
                if (landtype === '1') {
                    var sc = '<option>--select--</option>';
                    $.each(data.corp, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#corp_id").prop("disabled", false);
                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                    $("#corp_id").prop("disabled", false);
                } else {
                    var sc = '<option>--select--</option>';
                    $.each(data.village, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#village_id").prop("disabled", false);
                    $("#village_id option").remove();
                    $("#village_id").append(sc);
                    var sc1 = '<option>--select--</option>';
                    $("#corp_id option").remove();
                    $("#corp_id").append(sc1);
                    $("#corp_id").prop("disabled", true);
                }
            },'json');
        });

        if (document.getElementById("Developedland").value === '1') {
            //$("#divlevel1list").show();
            $("#corp_id").prop("disabled", false);
        }

        //Village
        $('#corp_id').change(function () {
            var corp = $("#corp_id option:selected").val();
            var tal = $("#tal_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Property/corp_change_event', {corp: corp, tal: tal}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc2);
            },'json');
        });

        //village drop down list code
        $('#village_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: village_id}, function (data)
            {
//                var sc = '<option>--select--</option>';
//                $.each(data.data1, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#Developedland").prop("disabled", false);
//                $("#Developedland option").remove();
//                $("#Developedland").append(sc);
                var sc1 = '<option>--select--</option>';
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });

                $("#level_1_desc_eng option").remove();
                $("#level_1_desc_eng").append(sc1);
            },'json');
        });

        $('#level_1_desc_eng').change(function () {
            var level1list = $("#level_1_desc_eng option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Property/Level1_change_event', {level1list: level1list, village_id: village_id}, function (data)
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
                }
                else {
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
                }
                else {
                    $("#divlevel2").hide();
                    $("#divlevel2list").hide();
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            },'json');
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
            var level2list = $("#level_2_desc_eng option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Property/Level2_change_event', {level2list: level2list, village_id: village_id}, function (data)
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
                }
                else {
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
                }
                else {
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            },'json');
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
            $.post('<?php echo $this->webroot; ?>Property/Level3_change_event', {level3list: level3list, village_id: village_id}, function (data)
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
                }
                else {
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
                }
                else {
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            },'json');
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
            $.post('<?php echo $this->webroot; ?>Property/Level4_change_event', {level4list: level4list, village_id: village_id}, function (data)
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
            },'json');
        });

        if (document.getElementById("hflevel4list").value === '1') {
            $("#divlevel4list").show();
            $("#level4_list_id").prop("disabled", false);
        }
    });
    function getTaluka(dist) {
        $.post("<?php echo $this->webroot; ?>districtchangeevent", {dist: dist}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#tal_id").prop("disabled", false);
            $("#tal_id option").remove();
            $("#tal_id").append(sc);

//                //var sc1 = "<option value='9999999999'>NA</option>";
//                var sc1;
//                $.each(data.corp, function (index, val) {
//                    sc1 += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#corp_id").prop("disabled", false);
//                $("#corp_id option").remove();
//                $("#corp_id").append(sc1);
//                $("#corp_id").val('9999999999');
//
//                var sc2 = '<option>--select--</option>';
//                $.each(data.village, function (index, val) {
//                    sc2 += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#village_id").prop("disabled", false);
//                $("#village_id option").remove();
//                $("#village_id").append(sc2);

        },'json');
    }
</script>

<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>


<?php echo $this->Form->create('valuation_entry', array('id' => 'valuation_entry', 'class' => 'form-vertical')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpropertyscreenhead'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <ul class="btn-group btn-group-justified">
                                <div class="btn-group"><input type="button" class=" btn btn-danger"  onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/genernal_info';" value="<?php echo __('lblyourdock'); ?>"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-danger"  value="<?php echo __('lblchangeprofile'); ?>"></div>
                                <div class="btn-group"><input type="button" style="color:blue" id="newdoc" class="btn btn-primary" onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/genernalinfoentry';" value="<?php echo __('lblnewdock'); ?>"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-danger" value="<?php echo __('lblreports'); ?>"></div>
                            </ul>
                            <div class="btn-group btn-group-justified" id="test">
                                <?php //$this->Html->link($this->Form->button('Button'), array('Controller' => 'citizenentry', 'action' => 'genernalinfoentry'), array('escape' => false, 'title' => "Click to view somethin"));  ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/genernalinfoentry/<?php echo $Selectedtoken; ?>';" value="A:- General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/property_details/<?php echo $Selectedtoken; ?>';" value="B:- Property Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" style="color: blue; background-color: wheat"  onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/valuation_entry/<?php echo $Selectedtoken; ?>';" value="C:- valuation" id="valuation_entry"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="D:- Stamp Duty"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="E:- Payment"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" onclick="location.href = '<?php echo $this->webroot; ?>citizenentry/party_entry/<?php echo $Selectedtoken; ?>';" value="F:- Party"id="party"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="G:- Witness"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="H:- Slot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lbladministrativeblocks'); ?></h3></center>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lblfineyer" class="control-label col-sm-2"><?php echo __('lblfineyer'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('finyear_id', array('options' => $finyearList, 'id' => 'fin_yr_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                <?php if ($configure[0][0]['is_dist'] == 'Y') { ?>
                                    <label for="district_id" class="control-label col-sm-2"><?php echo __('lblDistrict'); ?></label>
                                    <div class="col-sm-2" ><?php echo $this->Form->input('district_id', array('options' => $districtdata, 'value' => '25', 'id' => 'dist_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                    <?php
                                }
                                if ($configure[0][0]['is_taluka'] == 'Y') {
                                    ?>
                                    <label for="taluka_id" class="control-label col-sm-2"><?php echo __('lbladmtaluka'); ?></label>
                                    <div class="col-sm-2" ><?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'tal_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php if ($configure1[0]['levelconfig']['is_developed_land_types_id'] == 1) { ?>
                                    <label for="developed_land_types_id" class="control-label col-sm-2"><?php echo __('lblLandType'); ?></label>
                                    <div class="col-sm-2" ><?php echo $this->Form->input('developed_land_types_id', array('options' => $Developedland, 'empty' => '--select--', 'id' => 'Developedland', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <?php } ?>
                                <label for="corp_id" class="control-label col-sm-2"><?php echo __('lblcorporation'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('corp_id', array('options' => $corpclasslist, 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></div>
                                <label for="village_id" class="control-label col-sm-2"><?php echo __('lblVillage'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('village_id', array('options' => $villagenname, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($configure1[0]['levelconfig']['is_level_1_id'] == 1 || $configure1[0]['levelconfig']['is_level_2_id'] == 1 || $configure1[0]['levelconfig']['is_level_3_id'] == 1 || $configure1[0]['levelconfig']['is_level_4_id'] == 1) { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="lblLevel1" class="control-label col-sm-2"><?php echo __('lbllocation'); ?></label>
                                    <div class="col-sm-2"><?php echo $this->Form->input('level1_id', array('options' => array('empty' => '--select--', $level1propertydata), 'id' => 'level_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                    <div class="col-sm-2" id="divlevel1list" hidden="true"><?php echo $this->Form->input('level1_list_id', array('options' => array($level1propertylist), 'empty' => '--select--', 'id' => 'level1_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                    <div class="col-sm-2" id="divlevel2" hidden="true"><?php echo $this->Form->input('level2_id', array('options' => array('empty' => '--select--', $level2propertydata), 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                    <div class="col-sm-2" id="divlevel2list" hidden="true"><?php echo $this->Form->input('level2_list_id', array('options' => array($level2propertylist), 'empty' => '--select--', 'id' => 'level2_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-2" >&nbsp;</div>
                                    <div class="col-sm-2" id="divlevel3" hidden="true"><?php echo $this->Form->input('level3_id', array('options' => array('empty' => '--select--', $level3propertydata), 'id' => 'level_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                    <div class="col-sm-2" id="divlevel3list" hidden="true"><?php echo $this->Form->input('level3_list_id', array('options' => array($level3propertylist), 'empty' => '--select--', 'id' => 'level3_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                    <div class="col-sm-2" id="divlevel4" hidden="true"><?php echo $this->Form->input('level4_id', array('options' => array('empty' => '--select--', $level4propertydata), 'id' => 'level_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                    <div class="col-sm-2" id="divlevel4list" hidden="true"><?php echo $this->Form->input('level4_list_id', array('options' => array($level4propertylist), 'empty' => '--select--', 'id' => 'level4_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                    <?php } ?>

                    <!--                        <div class="row">
                                                <div class="col-sm-2" >&nbsp;</div>
                                                <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblusamaincat'); ?></label>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('usage_main_catg_id', array('options' => array(0 => '--Select Option--', $maincat_id), 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('usage_sub_catg_id', array('options' => array(0 => '--Select Option--', $subcat_id), 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('usage_sub_sub_catg_id', array('options' => array(0 => '--Select Option--', $subsubcat_id), 'id' => 'usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectsubsubcategory();')); ?></div>
                                            </div>
                                            <br>-->
                    <div  class="rowht">&nbsp;</div>


                </div>
            </div>


            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblpropertyusage'); ?></h3></center>
                </div>

                <div class="box-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-sm-10" >&nbsp;</div>
                                <div class="col-sm-2" style="text-align: right"><?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="usage_cat_id" class="control-label col-sm-2" ><?php echo __('lblpropertyusage'); ?></label>                                        
                                <div class="col-sm-10" style="height: 121px;overflow-y: scroll;"> <?php echo $this->Form->input('usage_cat_id', array('type' => 'select', 'options' => $usagecategory, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => 'form-control input-sm usage_cat_id')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="divconstructiondepreciation" hidden="true">
                        <div id="divconstructiontype" hidden="true">
                            <label for="construction_type_id" class="control-label col-sm-2"><?php echo __('lblconstuctiontye'); ?></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('construction_type_id', array('options' => array(0 => '--Select Option--', $construction_type_id), 'id' => 'construction_type_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?></div>
                        </div>
                        <div id="divdepreciationtype" hidden="true">
                            <label for="depreciation_id" class="control-label col-sm-2"> <?php echo __('lblage'); ?></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('depreciation_id', array('options' => array(0 => '--Select Option--', $depreciation_id), 'id' => 'depreciation_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?></div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="divroadvicinityuser1" hidden="true">
                        <div id="divroadvicinity" hidden="true">
                            <label for="road_vicinity_id" class="control-label col-sm-2"><?php echo __('lblroadvicinity'); ?></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('road_vicinity_id', array('options' => array(0 => '--Select Option--', $road_vicinity_id), 'id' => 'road_vicinity_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?></div>
                        </div>
                        <div id="divuserdependency1" hidden="true">
                            <label for="user_defined_dependency1_id" class="control-label col-sm-2"><?php echo __('lbluserdefineddependency1'); ?></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('user_defined_dependency1_id', array('options' => array(0 => '--Select Option--', $user_defined_dependency1_id), 'id' => 'user_defined_dependency1_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?></div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="divuserdependency2" hidden="true">
                        <div>
                            <label for="user_defined_dependency2_id" class="control-label col-sm-2"><?php echo __('lbluserdefineddependency2'); ?></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('user_defined_dependency2_id', array('options' => array(0 => '--Select Option--', $user_defined_dependency2_id), 'id' => 'user_defined_dependency2_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectrule();')); ?></div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>

                    <!--                        <div class="row">
                                                <div class="col-sm-2" >&nbsp;</div>
                                                <label for="evalrule_id" class="control-label col-sm-2"><?php echo __('lblselectrule'); ?></label>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('evalrule_id', array('options' => array(0 => '--Select Option--', $evalrule_id), 'id' => 'evalrule_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectevalrule();')); ?></div>
                                            </div>-->


                    <div  class="rowht">&nbsp;</div>
                    <?php
                    if ($usageitemlist != NULL) {
                        foreach ($usageitemlist as $usageitemlist1) {
                            if ($usageitemlist1['itemlist']['usage_param_type_id'] != '5') {
                                if ($usageitemlist1['usagelinkcategory']['item_rate_flag'] == 'Y') {
                                    ?>
                                    <div class="row">
                                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-sm-2"><?php echo $usageitemlist1['subsub']['usage_sub_sub_catg_desc_en'] . ' : ' . $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => '0')); ?></div>
                                        <?php if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') { ?>
                                            <div class="col-sm-1" ><?php
                                                $options = ClassRegistry::init('fillDropdown')->getdropdown('unit');
                                                echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit', 'label' => false, 'class' => 'form-control input-sm'));
                                                ?>
                                            </div>
                                            <?php if ($areatype != '2') { ?>
                                                <div class="col-sm-1" ><?php
                                                    $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype');
                                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype', 'label' => false, 'class' => 'form-control input-sm'));
                                                    ?>
                                                </div>
                                            <?php }
                                            ?>
                                            <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted'; ?>" class="control-label col-sm-2"><?php echo __('lblConvertedarea'); ?></label>
                                            <div class="col-sm-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')); ?></div>
                                        <?php }
                                        ?>
                                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id']; ?>" class="control-label col-sm-1"><?php echo 'Item Rate'; ?></label>
                                        <div class="col-sm-1" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $usageitemlist1['0']['item_rate'], 'readonly')); ?></div>
                                        <?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'hf', array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'hf', 'type' => 'hidden')); ?>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-sm-4"><?php echo $usageitemlist1['subsub']['usage_sub_sub_catg_desc_en'] . ' : ' . $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'], 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                                        <?php if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') { ?>

                                            <div class="col-sm-1" ><?php
                                                $options = ClassRegistry::init('fillDropdown')->getdropdown('unit');
                                                echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit', 'label' => false, 'class' => 'form-control input-sm'));
                                                ?>
                                            </div>
                                            <?php if ($areatype != '2') { ?>
                                                <div class="col-sm-1" ><?php
                                                    $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype');
                                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype', 'label' => false, 'class' => 'form-control input-sm'));
                                                    ?>
                                                </div>
                                            <?php }
                                            ?>
                                            <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted'; ?>" class="control-label col-sm-2"><?php echo __('lblConvertedarea'); ?></label>
                                            <div class="col-sm-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')); ?></div>
                                        <?php }
                                        ?>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <?php
                                }
                            }
                        }
                        ?>

                        <div hidden="true">
                            <?php
                            if ($subruleconditions != NULL) {
                                foreach ($subruleconditions as $subruleconditions1) {
                                    ?>
                                    <div class="row">
                                        <label for="derivedresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'derivedresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                        <label for="maxvalresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?> <?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'maxvalresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                        <label for="finalresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo __('lblfinal'); ?><?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('finalresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'finalresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <?php
                                }
                            } else {
                                $slabflag = 0;
                                if ($rate != NULL) {
                                    foreach ($rate as $rate1) {
                                        if ($rate1['rate']['slab_rate_flag'] == 'Y') {
                                            $slabflag = 1;
                                        }
                                    }
                                }
                                if ($slabflag == 0) {
                                    ?>
                                    <div class="row">
                                        <label for="derivedresult" class="control-label col-sm-2"><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult', array('label' => false, 'id' => 'derivedresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                        <label for="maxvalresult" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult', array('label' => false, 'id' => 'maxvalresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                        <label for="finalresult" class="control-label col-sm-2"><?php echo __('lblfinal'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                                        <div class="col-sm-2" ><?php echo $this->Form->input('finalresult', array('label' => false, 'id' => 'finalresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                    </div>
                                    <div  class="rowht">&nbsp;</div>
                                    <?php
                                } else {
                                    if ($rate != NULL) {
                                        foreach ($rate as $rate1) {
                                            $tofield = $rate1['rate']['range_to'];
                                            if ($rate1['rate']['range_to'] == NULL) {
                                                $tofield = 'Above';
                                            }
                                            ?>
                                            <div class="row">
                                                <label for="derivedresult" class="control-label col-sm-2"><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'derivedresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                                <label for="maxvalresult" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'maxvalresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                                <label for="finalresult" class="control-label col-sm-2"><?php echo __('lblfinal'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                                <div class="col-sm-2" ><?php echo $this->Form->input('finalresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'finalresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                            </div>
                                            <div  class="rowht">&nbsp;</div>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div id="viewData" style="display:none;"></div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: center">
                                <button type="button" style="width: 130px;" class="btn btn-primary" id="btnSubmit" name="btnSubmit" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => 'valuation_entry')); ?>';"><?php echo __('lblnewvaluation'); ?></button>
                                <button id="btnSave" style="width: 130px;" name="btnSave" class="btn btn-primary" onclick="javascript: return formsave();"><?php echo __('lblsaveandcal'); ?></button>
                                <button id="btnView" style="width: 130px;"  class="btn btn-primary" onclick="javascript: return formsave();" ><?php echo __('lblview'); ?></button>
                                <button id="btnPdf" style="width: 130px;"  class="btn btn-primary" onclick="javascript: return formsave();" ><?php echo __('lbldownloadpdf'); ?></button>
                                <button id="btnCancel" style="width: 110px;" name="btnCancel" class="btn btn-primary" onclick="javascript: return formcancel();"><?php echo __('btncancel'); ?></button>
                                <button id="btnExit" style="width: 110px;" name="btnExit" class="btn btn-primary" onclick="javascript: return formexit();"><?php echo __('lblexit'); ?></button>
                            </div>
                        </div>   
                    <?php } ?>
                </div>
            </div>


        </div>
    </div>
    <div class="row">
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
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script language="JavaScript" type="text/javascript">
    var message = "Not Allowed Right Click";
    function rtclickcheck(keyp)
    {
        if (navigator.appName === "Netscape" && keyp.which === 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") !== -1 && event.button === 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>


<div class="customer_care" style="display: block; position: absolute; width: 8%;right:">
    <span class="mobs">Status 
        <?php
//        for ($i = 0; $i < count($alllevel); $i++) {
//            for ($j = 0; $j < count($status); $j++) {
//                if ($alllevel[$i][0]['appl_level_id'] == $status[$j][0]['level_id']) {
//                    
        ?>
        <br><input type="checkbox" name="General information" value="General information">
        <br><input type="checkbox" name="" value="Property Details">
        <br><input type="checkbox" name="" value="Valuation">
        <br><input type="checkbox" name="" value="Stamp Duty">
        <br><input type="checkbox" name="" value="Paymanets">
        <br><input type="checkbox" name="" value="Partyy">
        <br><input type="checkbox" name="" value="Witness">
        <br><input type="checkbox" name="" value="Slot">
        <?php
//                }
//            }
//        }
//        
        ?>
    </span>
</div>                   