<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>

    $(document).ready(function () {

        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'});

        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tableratedata').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        //alert('hi');

        if ($('#village_id').val() != '')
        {
            $("#village_id").prop("disabled", false);
        }

        if ($('#taluka_id').val() != '')
        {
            $("#taluka_id").prop("disabled", false);
        }
        if ($('#level1_id').val() != '')
        {
            $("#level1_id").prop("disabled", false);
        }
        if ($('#level1_list_id').val() != '')
        {
            $("#divlevel1list").fadeIn("slow");
            $("#level1_list_id").prop("disabled", false);
        }
        if ($('#level2_id').val() != '')
        {
            $("#divlevel2").fadeIn("slow");
            $("#level2_id").prop("disabled", false);
        }
        if ($('#level2_list_id').val() != '')
        {
            $("#divlevel2list").fadeIn("slow");
            $("#level2_list_id").prop("disabled", false);
        }
        if ($('#level3_id').val() != '')
        {
            $("#divlevel3").fadeIn("slow");
            $("#level3_id").prop("disabled", false);
        }
        if ($('#level3_list_id').val() != '')
        {
            $("#divlevel3list").fadeIn("slow");
            $("#level3_list_id").prop("disabled", false);
        }
        if ($('#level4_id').val() != '')
        {
            $("#divlevel4").fadeIn("slow");
            $("#level4_id").prop("disabled", false);
        }
        if ($('#level4_list_id').val() != '')
        {
            $("#divlevel4list").fadeIn("slow");
            $("#level4_list_id").prop("disabled", false);
        }


        var level1 = '<?php echo $configure[0]['levelconfig']['is_level_1_id']; ?>';
        var level2 = '<?php echo $configure[0]['levelconfig']['is_level_2_id']; ?>';
        var level3 = '<?php echo $configure[0]['levelconfig']['is_level_3_id']; ?>';
        var level4 = '<?php echo $configure[0]['levelconfig']['is_level_4_id']; ?>';
        var emptycheck = '<option value="">select</option>';

// Get Land Type
        $('#district_id').change(function () {
            var distid = $("#district_id option:selected").val();
            $.getJSON('gettaluka', {dist: distid}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);



            });
//            $.getJSON('getlandtype', {distid: distid}, function (data)
//            {
//                var sc = '<option value="">select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#Developedland").prop("disabled", false);
//                $("#Developedland option").remove();
//                $("#Developedland").append(sc);
//
//            });
        });

// Get Village name
        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            $.getJSON('getvillagename', {taluka: taluka}, function (data)
            {
                var sc = '<option value="">select</option>';
                var sc1 = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
//                $.each(data.data2, function (index, val) {
//                    sc1 += "<option value=" + index + ">" + val + "</option>";
//                });

                $("#village_id option").remove();
                $("#village_id").append(sc);
                $("#village_id").prop("disabled", false);

//                $("#valutation_zone_id option").remove();
//                $("#valutation_zone_id").append(sc1);
//                $("#valutation_zone_id").prop("disabled", false);
            });
        });

//Get Level 1 code
        $('#village_id').change(function () {
            var villagename = $("#village_id option:selected").val();
            var i;
            $.getJSON('getlevel1', {villagename: villagename}, function (data)
            {
                var sc = '<option value="">select</option>';
                var sc1 = '<option value="">select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });

                $("#level1_id").prop("disabled", false);
                $("#level1_id option").remove();
                $("#level1_id").append(sc);

//                if (sc1 != emptycheck) {
//                    $("#divvalzone").fadeIn("slow");
//                    $("#divvalzone1").fadeIn("slow");
//                    $("#valutation_zone_id option").remove();
//                    $("#valutation_zone_id").append(sc1);
//                    $("#valutation_zone_id").prop("disabled", false);
//                } else {
//                    $("#divvalzone").hide();
//                    $("#divvalzone1").hide();
//                }
            });
        });

        //level 1 dropdown list code
        $('#level1_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            var level1_list = $("#level1_id option:selected").val();
            $.getJSON('getlevel1_list', {level1_list: level1_list, village_id: village_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                var sc1 = '<option value="">select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });
                var sc2 = data['flag'];
                var sc3 = data['surveyno'];
                if (level2 == 1) {
                    if (sc != emptycheck) {
                        $("#divlevel1list").fadeIn("slow");
                        $("#level1_list_id").prop("disabled", false);
                        $("#level1_list_id option").remove();
                        $("#level1_list_id").append(sc);
                    } else {
                        $("#divlevel1list").hide();
                    }
                    if (sc1 != emptycheck) {
//                         alert("hii");
                        $("#divlevel2").fadeIn("slow");
                        $("#level2_id").prop("disabled", false);
                        $("#level2_id option").remove();
                        $("#level2_id").append(sc1);
                    } else {
                        $("#divlevel2").hide();
                    }
                    $("#saveflag").val(sc2);
                    $("#surveyno").val(sc3);
                    $("#divlevel2list").hide();
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                    if (sc2 == 'Y') {
                        $("#divlevel2").hide();
                        $("#level2_id").prop("disabled", true);
//                        $("#slab_rate_flag").val('N');
//                        $("#slab_rate_flag").prop("disabled", true);
                        $("#divrange1").fadeIn("slow");
                        $("#divrange2").fadeIn("slow");
                        $('#range_from').prop("readonly", true);
                        $('#range_to').prop("readonly", true);
                    } else {
//                        $("#slab_rate_flag").prop("disabled", false);
                        $("#divrange1").hide();
                        $("#divrange2").hide();

                    }

                } else if (level2 == 0 && level3 == 1) {
                    if (sc != emptycheck) {
                        $("#divlevel1list").fadeIn("slow");
                        $("#level1_list_id").prop("disabled", false);
                        $("#level1_list_id option").remove();
                        $("#level1_list_id").append(sc);
                    } else {
                        $("#divlevel1list").hide();
                    }
                    if (sc1 != emptycheck) {
                        $("#divlevel3").fadeIn("slow");
                        $("#level3_id").prop("disabled", false);
                        $("#level3_id option").remove();
                        $("#level3_id").append(sc1);
                    } else {
                        $("#divlevel3").hide();
                    }
                    $("#saveflag").val(sc2);
                    $("#surveyno").val(sc3);
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                    if (sc2 == 'Y') {
                        $("#divlevel3").hide();
                        $("#level3_id").prop("disabled", true);
//                        $("#slab_rate_flag").val('N');
//                        $("#slab_rate_flag").prop("disabled", true);
                        $("#divrange1").fadeIn("slow");
                        $("#divrange2").fadeIn("slow");
                        $('#range_from').prop("readonly", true);
                        $('#range_to').prop("readonly", true);
                    } else {
//                        $("#slab_rate_flag").prop("disabled", false);
                        $("#divrange1").hide();
                        $("#divrange2").hide();
                    }
                } else if (level2 == 0 && level3 == 0 && level4 == 1) {
                    if (sc != emptycheck) {
                        $("#divlevel1list").fadeIn("slow");
                        $("#level1_list_id").prop("disabled", false);
                        $("#level1_list_id option").remove();
                        $("#level1_list_id").append(sc);
                    } else {
                        $("#divlevel1list").hide();
                    }
                    if (sc1 != emptycheck) {
                        $("#divlevel4").fadeIn("slow");
                        $("#level4_id").prop("disabled", false);
                        $("#level4_id option").remove();
                        $("#level4_id").append(sc1);
                    } else {
                        $("#divlevel4").hide();
                    }
                    $("#saveflag").val(sc2);
                    $("#surveyno").val(sc3);
                    $("#divlevel4list").hide();
                    if (sc2 == 'Y') {
                        $("#divlevel4").hide();
                        $("#level4_id").prop("disabled", true);
//                        $("#slab_rate_flag").val('N');
//                        $("#slab_rate_flag").prop("disabled", true);
                        $("#divrange1").fadeIn("slow");
                        $("#divrange2").fadeIn("slow");
                        $('#range_from').prop("readonly", true);
                        $('#range_to').prop("readonly", true);
                    } else {
//                        $("#slab_rate_flag").prop("disabled", false);
                        $("#divrange1").hide();
                        $("#divrange2").hide();
                    }
                }
            });
        });

        //list 1 dropdown list code

        $('#level1_list_id').change(function () {
            var saveflag = document.getElementById('saveflag').value;
            if (saveflag == 'Y')
            {
                var level1_list_id = $("#level1_list_id option:selected").val();
                $.getJSON('getlevel1surveynoid', {level1_list_id: level1_list_id}, function (data)
                {
                    var sc3 = data['frange'];
                    var sc4 = data['trange'];

                    $("#range_from").val(sc3);
                    $("#range_to").val(sc4);
                });
            } else {
                return false;
            }

        });

        //level 2 dropdown list code

        $('#level2_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            var level2_list = $("#level2_id option:selected").val();
            var level1_list_id = $("#level1_list_id option:selected").val();
            var i;
            $.getJSON('getlevel2_list', {level2_list: level2_list, village_id: village_id, level1_list_id: level1_list_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                var sc1 = '<option value="">select</option>';
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });
                var sc2 = data['flag'];
                var sc3 = data['surveyno'];

                if (level3 == 1) {
                    if (sc != emptycheck) {
                        $("#divlevel2list").fadeIn("slow");
                        $("#level2_list_id").prop("disabled", false);
                        $("#level2_list_id option").remove();
                        $("#level2_list_id").append(sc);
                    } else {
                        $("#divlevel2list").hide();
                    }
                    if (sc1 != emptycheck) {
                        $("#divlevel3").fadeIn("slow");
                        $("#level3_id").prop("disabled", false);
                        $("#level3_id option").remove();
                        $("#level3_id").append(sc1);
                    } else {
                        $("#divlevel3").hide();
                    }
                    $("#saveflag").val(sc2);
                    $("#surveyno").val(sc3);
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                    if (sc2 == 'Y') {
                        $("#divlevel3").hide();
                        $("#level3_id").prop("disabled", true);
//                        $("#slab_rate_flag").val('N');
//                        $("#slab_rate_flag").prop("disabled", true);
                        $("#divrange1").fadeIn("slow");
                        $("#divrange2").fadeIn("slow");
                        $('#range_from').prop("readonly", true);
                        $('#range_to').prop("readonly", true);
                    } else {
//                        $("#slab_rate_flag").prop("disabled", false);
                        $("#divrange1").hide();
                        $("#divrange2").hide();
                    }
                } else {
                    if (sc != emptycheck) {
                        $("#divlevel2list").fadeIn("slow");
                        $("#level2_list_id").prop("disabled", false);
                        $("#level2_list_id option").remove();
                        $("#level2_list_id").append(sc);
                    } else {
                        $("#divlevel2list").hide();
                    }
                    if (sc1 != emptycheck) {
                        $("#divlevel4").fadeIn("slow");
                        $("#level4_id").prop("disabled", false);
                        $("#level4_id option").remove();
                        $("#level4_id").append(sc1);
                    } else {
                        $("#divlevel4").hide();
                    }
                    $("#saveflag").val(sc2);
                    $("#surveyno").val(sc3);
                    $("#divlevel4list").hide();
                    if (sc2 == 'Y') {
                        $("#divlevel3").hide();
                        $("#level3_id").prop("disabled", true);
//                        $("#slab_rate_flag").val('N');
//                        $("#slab_rate_flag").prop("disabled", true);
                        $("#divrange1").fadeIn("slow");
                        $("#divrange2").fadeIn("slow");
                        $('#range_from').prop("readonly", true);
                        $('#range_to').prop("readonly", true);
                    } else {
//                        $("#slab_rate_flag").prop("disabled", false);
                        $("#divrange1").hide();
                        $("#divrange2").hide();
                    }
                }
            });
        });


        //list 2 dropdown list code

        $('#level2_list_id').change(function () {
            var saveflag = document.getElementById('saveflag').value;
            if (saveflag == 'Y')
            {
                var level2_list_id = $("#level2_list_id option:selected").val();
                $.getJSON('getlevel2surveynoid', {level2_list_id: level2_list_id}, function (data)
                {
                    var sc3 = data['frange'];
                    var sc4 = data['trange'];
                    $("#range_from").val(sc3);
                    $("#range_to").val(sc4);
                });
            } else {
                return false;
            }

        });


        //level 3 dropdown list code
        $('#level3_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            var level3_list = $("#level3_id option:selected").val();
            var level2_list_id = $("#level2_list_id option:selected").val();
            var i;
            $.getJSON('getlevel3_list', {level3_list: level3_list, village_id: village_id, level2_list_id: level2_list_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                var sc1 = '<option value="">select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });

                var sc2 = data['flag'];
                var sc3 = data['surveyno'];
                if (sc != emptycheck) {
                    $("#divlevel3list").fadeIn("slow");
                    $("#level3_list_id").prop("disabled", false);
                    $("#level3_list_id option").remove();
                    $("#level3_list_id").append(sc);
                } else {
                    $("#divlevel3list").hide();
                }
                if (sc1 != emptycheck) {
                    $("#divlevel4").fadeIn("slow");
                    $("#level4_id").prop("disabled", false);
                    $("#level4_id option").remove();
                    $("#level4_id").append(sc1);
                } else {
                    $("#divlevel4").hide();
                }
                $("#saveflag").val(sc2);
                $("#surveyno").val(sc3);
                $("#divlevel4list").hide();
                if (sc2 == 'Y') {
                    $("#divlevel4").hide();
                    $("#level4_id").prop("disabled", true);
//                    $("#slab_rate_flag").val('N');
//                    $("#slab_rate_flag").prop("disabled", true);
                    $("#divrange1").fadeIn("slow");
                    $("#divrange2").fadeIn("slow");
                    $('#range_from').prop("readonly", true);
                    $('#range_to').prop("readonly", true);
                } else {
//                    $("#slab_rate_flag").prop("disabled", false);
                    $("#divrange1").hide();
                    $("#divrange2").hide();
                }
            });
        });

        //list 3 dropdown list code

        $('#level3_list_id').change(function () {
            var saveflag = document.getElementById('saveflag').value;
            if (saveflag == 'Y')
            {
                var level3_list_id = $("#level3_list_id option:selected").val();
                $.getJSON('getlevel3surveynoid', {level3_list_id: level3_list_id}, function (data)
                {
                    var sc3 = data['frange'];
                    var sc4 = data['trange'];
                    $("#range_from").val(sc3);
                    $("#range_to").val(sc4);
                });
            } else {
                return false;
            }

        });


        //level 4 dropdown list code
        $('#level4_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            var level4_list = $("#level4_id option:selected").val();
            var level3_list_id = $("#level3_list_id option:selected").val();
            $.getJSON('getlevel4_list', {level4_list: level4_list, village_id: village_id, level3_list_id: level3_list_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                var sc2 = data['flag'];
                var sc3 = data['surveyno'];
                if (sc != emptycheck) {
                    $("#divlevel4list").fadeIn("slow");
                    $("#level4_list_id").prop("disabled", false);
                    $("#level4_list_id option").remove();
                    $("#level4_list_id").append(sc);
                } else {
                    $("#divlevel4list").hide();
                }
                $("#saveflag").val(sc2);
                $("#surveyno").val(sc3);
                if (sc2 == 'Y') {
//                    $("#slab_rate_flag").val('N');
//                    $("#slab_rate_flag").prop("disabled", true);
                    $("#divrange1").fadeIn("slow");
                    $("#divrange2").fadeIn("slow");
                    $('#range_from').prop("readonly", true);
                    $('#range_to').prop("readonly", true);
                } else {
//                    $("#slab_rate_flag").prop("disabled", false);
                    $("#divrange1").hide();
                    $("#divrange2").hide();
                }
            });
        });

        //list 4 dropdown list code

        $('#level4_list_id').change(function () {
            var saveflag = document.getElementById('saveflag').value;
            if (saveflag == 'Y')
            {
                var level4_list_id = $("#level4_list_id option:selected").val();
                $.getJSON('getlevel4surveynoid', {level4_list_id: level4_list_id}, function (data)
                {
                    var sc3 = data['frange'];
                    var sc4 = data['trange'];
                    $("#range_from").val(sc3);
                    $("#range_to").val(sc4);
                });
            } else {
                return false;
            }

        });


// usage sub categry
        $('#usage_main_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id option:selected").val();
            var i;
            $.getJSON('getusagesub', {usage_main_catg_id: usage_main_catg_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (sc != emptycheck) {
                    $("#divcatsub").fadeIn("slow");
                    $("#divcatsub1").fadeIn("slow");
                    $("#usage_sub_catg_id").prop("disabled", false);
                    $("#usage_sub_catg_id option").remove();
                    $("#usage_sub_catg_id").append(sc);
                    $("#divcatsubsub").hide();
                    $("#divcatsubsub1").hide();
                    $("#divcons").hide();
                    $("#divcons1").hide();
                    $("#divroad").hide();
                    $("#divroad1").hide();
                    $("#divud1").hide();
                    $("#divud11").hide();
                    $("#divud2").hide();
                    $("#divud21").hide();
                } else {
                    $("#divcatsub").hide();
                    $("#divcatsub1").hide();
                }
            });
        });

// usage sub sub categry
        $('#usage_sub_catg_id').change(function () {
            var usage_sub_catg_id = $("#usage_sub_catg_id option:selected").val();
            var i;
            $.getJSON('getusagesubsub', {usage_sub_catg_id: usage_sub_catg_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (sc != emptycheck) {
                    $("#divcatsubsub").fadeIn("slow");
                    $("#divcatsubsub1").fadeIn("slow");
                    $("#usage_sub_sub_catg_id").prop("disabled", false);
                    $("#usage_sub_sub_catg_id option").remove();
                    $("#usage_sub_sub_catg_id").append(sc);
                } else {
                    $("#divcatsubsub").hide();
                    $("#divcatsubsub1").hide();
                }
            });
        });

        //Slab Rate Flag Dropdownlist

//        $('#slab_rate_flag').change(function () {
//            var slab_rate_flag = $("#slab_rate_flag option:selected").val();
//            if (slab_rate_flag == 'Y') {
//                $('#range_from').prop("readonly", false);
//                $('#range_to').prop("readonly", false);
//            } else {
//                $('#range_from').prop("readonly", true);
//                $('#range_to').prop("readonly", true);
//                $('#range_from').val('');
//                $('#range_to').val('');
//            }
//        });

        // 4 dropdownlist visibility
        $('#usage_sub_sub_catg_id').change(function () {
            var usage_sub_sub_catg_id = $("#usage_sub_sub_catg_id option:selected").val();
            var i;
            $.getJSON('getvisibility', {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
            {
                var sc1 = data['cflag'];
                var sc2 = data['rflag'];
                var sc3 = data['ud1flag'];
                var sc4 = data['ud2flag'];
                if (sc1 == 'Y') {
                    $("#divcons").fadeIn("slow");
                    $("#divcons1").fadeIn("slow");
                    $("#construction_type_id").prop("disabled", false);
                } else {
                    $("#divcons").hide();
                    $("#divcons1").hide();
                    $("#construction_type_id").prop("disabled", true);
                }
                if (sc2 == 'Y') {
                    $("#divroad").fadeIn("slow");
                    $("#divroad1").fadeIn("slow");
                    $("#road_vicinity_id").prop("disabled", false);
                } else {
                    $("#road_vicinity_id").prop("disabled", true);
                    $("#divroad").hide();
                    $("#divroad1").hide();
                }
                if (sc3 == 'Y') {
                    $("#divud1").fadeIn("slow");
                    $("#divud11").fadeIn("slow");
                    $("#user_defined_dependency_1").prop("disabled", false);
                } else {
                    $("#divud1").hide();
                    $("#divud11").hide();
                    $("#user_defined_dependency_1").prop("disabled", true);
                }
                if (sc4 == 'Y') {
                    $("#divud2").fadeIn("slow");
                    $("#divud21").fadeIn("slow");
                    $("#user_defined_dependency_2").prop("disabled", false);
                } else {
                    $("#divud2").hide();
                    $("#divud21").hide();
                    $("#user_defined_dependency_2").prop("disabled", true);
                }


            });
        });

        //Get Valuation subzone code
//        $('#valutation_zone_id').change(function () {
//            var valzone = $("#valutation_zone_id option:selected").val();
//            $.getJSON('getvalsubzone', {valzone: valzone}, function (data)
//            {
//                var sc = '<option value="">select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                if (data != '') {
//                    $("#divvalsubzone").fadeIn("slow");
//                    $("#divvalsubzone1").fadeIn("slow");
//                    $("#valutation_subzone_id").prop("disabled", false);
//                    $("#valutation_subzone_id option").remove();
//                    $("#valutation_subzone_id").append(sc);
//                } else {
//                    $("#divvalsubzone").hide();
//                    $("#divvalsubzone1").hide();
//                }
//            });
//        });

        var actiontype = document.getElementById("actiontype").value;
        var selectflag = document.getElementById("selectflag").value;
        if (actiontype == 1) {
//            $('#slab_rate_flag').val('N');
            $('#usage_main_catg_id').val('');
            $('#usage_sub_catg_id').val('');
            $('#usage_sub_sub_catg_id').val('');
            $('#prop_unit').val('');
            $('#prop_rate').val('');
            $('#land_rate').val('');
            $('#construction_rate').val('');
            $('#construction_type_id').val('');
            $('#road_vicinity_id').val('');
            $('#user_defined_dependency_1').val('');
            $('#user_defined_dependency_2').val('');
            $('#finyear_id').val('');
            $('#effective_date').val('');
            $('#hfupdateflag').val('');

        }

        if (actiontype == 3) {
            if (document.getElementById("saveflag").value == 'Y' && document.getElementById("rdbsort").value == 'S') {
                $('#divbtn').show();
                $('#divraterecord').show();
                $('#rdbsort').val('');
            } else {
                $('#divbtn').show();
                $('#divraterecord').show();
                $('#rdbsort').val('');
            }
        }

        if (selectflag == 'Y') {
            $('#divgridrecord').slideDown(1000);
        }

        $('#rdbsort1').click(function () {
            document.getElementById('rdbsort').value = 'R';
            $('#saveflag').val('N');
            $('#selectflag').val('');
            $.getJSON('getratedropdown', {}, function (data)
            {
                var sc = '<option value="">All Records</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#searchvillage option").remove();
                $("#searchvillage").append(sc);
            });

        });
        $('#rdbsort2').click(function () {
            document.getElementById('rdbsort').value = 'S';
            $('#saveflag').val('Y');
            $('#selectflag').val('');
            $.getJSON('getsurveyratedropdown', {}, function (data)
            {
                var sc = '<option value="">All Records</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#searchvillage option").remove();
                $("#searchvillage").append(sc);
            });
        });



    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
//        var village_id = $('#village_id').val();
//        if (village_id == '') {
//
//            $('#village_id').focus();
//            alert('Please Select Village');
//            return false;
//        }

    }
    function viewrecord() {
        $('#divbtn').show();
        return false;

    }


    function formupdate(id, district_id, village_id, taluka_id, level1_id,
            level2_id, level3_id, level4_id, prop_level1_list_id, prop_level2_list_id,
            prop_level3_list_id, prop_level4_list_id, range_from, usage_main_catg_id,
            usage_sub_catg_id, usage_sub_sub_catg_id, prop_unit, prop_rate, land_rate,
            construction_rate, construction_type_id, road_vicinity_id,
            user_defined_dependency_1, user_defined_dependency_2, finyear_id,
            effective_date, range_to, valutation_zone_id, valutation_subzone_id) {
        document.getElementById("actiontype").value = '2';
        var level1 = '<?php echo $configure[0]['levelconfig']['is_level_1_id']; ?>';
        var level2 = '<?php echo $configure[0]['levelconfig']['is_level_2_id']; ?>';
        var level3 = '<?php echo $configure[0]['levelconfig']['is_level_3_id']; ?>';
        var level4 = '<?php echo $configure[0]['levelconfig']['is_level_4_id']; ?>';
        var emptycheck = '<option value="">select</option>';

        // Start Get Land Type
        var distid = district_id;
        $.getJSON('gettaluka', {dist: distid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
            $('#taluka_id').val(taluka_id);

            //   Start Get Village name and Valation Zone function
            var taluka = taluka_id;
            if (taluka != '') {
                $.getJSON('getvillagename', {taluka: taluka}, function (data)
                {
                    var sc = '<option value="">select</option>';
//                    var sc1 = '<option value="">select</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
//                    $.each(data.data2, function (index, val) {
//                        sc1 += "<option value=" + index + ">" + val + "</option>";
//                    });

                    $("#village_id option").remove();
                    $("#village_id").append(sc);
                    $("#village_id").prop("disabled", false);
                    $('#village_id').val(village_id);

//                    $("#valutation_zone_id option").remove();
//                    $("#valutation_zone_id").append(sc1);
//                    $("#valutation_zone_id").prop("disabled", false);
//                    $('#village_id').val(village_id);
//                    $('#valutation_zone_id').val(valutation_zone_id);

                    // Start GEt Level 1 
                    var villagename = village_id;
                    if (villagename != '') {
                        $.getJSON('getlevel1', {villagename: villagename}, function (data)
                        {
                            var sc = '<option value="">select</option>';
                            var sc1 = '<option value="">select</option>';
                            $.each(data.data1, function (index, val) {
                                sc += "<option value=" + index + ">" + val + "</option>";
                            });
                            $.each(data.data2, function (index, val) {
                                sc1 += "<option value=" + index + ">" + val + "</option>";
                            });

                            $("#level1_id").prop("disabled", false);
                            $("#level1_id option").remove();
                            $("#level1_id").append(sc);
                            $('#level1_id').val(level1_id);

//                            if (sc1 != emptycheck) {
//                                $("#divvalzone").fadeIn("slow");
//                                $("#divvalzone1").fadeIn("slow");
//                                $("#valutation_zone_id option").remove();
//                                $("#valutation_zone_id").append(sc1);
//                                $("#valutation_zone_id").prop("disabled", false);
//                                $('#village_id').val(village_id);
//                                $('#valutation_zone_id').val(valutation_zone_id);
//                            } else {
//                                $("#divvalzone").hide();
//                                $("#divvalzone1").hide();
//                            }

                            // Start Get level 1 list and levvel 2
                            var village_id = village_id;
                            var level1_list = level1_id;
                            if (level1_list != '') {
                                $.getJSON('getlevel1_list', {level1_list: level1_list, village_id: village_id}, function (data)
                                {
                                    var sc = '<option value="">select</option>';
                                    var sc1 = '<option value="">select</option>';
                                    $.each(data.data1, function (index, val) {
                                        sc += "<option value=" + index + ">" + val + "</option>";
                                    });
                                    $.each(data.data2, function (index, val) {
                                        sc1 += "<option value=" + index + ">" + val + "</option>";
                                    });
                                    var sc2 = data['flag'];
                                    var sc3 = data['surveyno'];
                                    if (level2 == 1) {
                                        if (sc != emptycheck) {
                                            $("#divlevel1list").fadeIn("slow");
                                            $("#level1_list_id").prop("disabled", false);
                                            $("#level1_list_id option").remove();
                                            $("#level1_list_id").append(sc);
                                            $('#level1_list_id').val(prop_level1_list_id);
                                        } else {
                                            $("#divlevel1list").hide();
                                        }
                                        if (sc1 != emptycheck) {
                                            $("#divlevel2").fadeIn("slow");
                                            $("#level2_id").prop("disabled", false);
                                            $("#level2_id option").remove();
                                            $("#level2_id").append(sc1);
                                            $('#level2_id').val(level2_id);
                                        } else {
                                            $("#divlevel2").hide;
                                        }
                                        $("#saveflag").val(sc2);
                                        $("#surveyno").val(sc3);
                                        $("#divlevel2list").hide();
                                        $("#divlevel3").hide();
                                        $("#divlevel3list").hide();
                                        $("#divlevel4").hide();
                                        $("#divlevel4list").hide();
                                        if (sc2 == 'Y') {
                                            $("#divlevel2").hide();
                                            $("#level2_id").prop("disabled", true);
//                                            $("#slab_rate_flag").val('N');
//                                            $("#slab_rate_flag").prop("disabled", true);
                                            $("#divrange1").fadeIn("slow");
                                            $("#divrange2").fadeIn("slow");
                                            $('#range_from').prop("readonly", true);
                                            $('#range_to').prop("readonly", true);
                                        } else {
//                                            $("#slab_rate_flag").prop("disabled", false);
                                            $("#divrange1").hide();
                                            $("#divrange2").hide();

                                        }

                                    } else if (level2 == 0 && level3 == 1) {
                                        if (sc != emptycheck) {
                                            $("#divlevel1list").fadeIn("slow");
                                            $("#level1_list_id").prop("disabled", false);
                                            $("#level1_list_id option").remove();
                                            $("#level1_list_id").append(sc);
                                            $('#level1_list_id').val(prop_level1_list_id);
                                        } else {
                                            $("#divlevel1list").hide();
                                        }
                                        if (sc1 != emptycheck) {
                                            $("#divlevel3").fadeIn("slow");
                                            $("#level3_id").prop("disabled", false);
                                            $("#level3_id option").remove();
                                            $("#level3_id").append(sc1);
                                            $('#level3_id').val(level3_id);
                                        } else {
                                            $("#divlevel3").hide();
                                        }
                                        $("#saveflag").val(sc2);
                                        $("#surveyno").val(sc3);
                                        $("#divlevel3list").hide();
                                        $("#divlevel4").hide();
                                        $("#divlevel4list").hide();
                                        if (sc2 == 'Y') {
                                            $("#divlevel3").hide();
                                            $("#level3_id").prop("disabled", true);
//                                            $("#slab_rate_flag").val('N');
//                                            $("#slab_rate_flag").prop("disabled", true);
                                            $("#divrange1").fadeIn("slow");
                                            $("#divrange2").fadeIn("slow");
                                            $('#range_from').prop("readonly", true);
                                            $('#range_to').prop("readonly", true);
                                        } else {
//                                            $("#slab_rate_flag").prop("disabled", false);
                                            $("#divrange1").hide();
                                            $("#divrange2").hide();
                                        }
                                    } else if (level2 == 0 && level3 == 0 && level4 == 1) {
                                        if (sc != emptycheck) {
                                            $("#divlevel1list").fadeIn("slow");
                                            $("#level1_list_id").prop("disabled", false);
                                            $("#level1_list_id option").remove();
                                            $("#level1_list_id").append(sc);
                                            $('#level1_list_id').val(prop_level1_list_id);
                                        } else {
                                            $("#divlevel1list").hide();
                                        }
                                        if (sc1 != emptycheck) {
                                            $("#divlevel4").fadeIn("slow");
                                            $("#level4_id").prop("disabled", false);
                                            $("#level4_id option").remove();
                                            $("#level4_id").append(sc1);
                                            $('#level4_id').val(level4_id);
                                        } else {
                                            $("#divlevel4").hide();
                                        }
                                        $("#saveflag").val(sc2);
                                        $("#surveyno").val(sc3);
                                        $("#divlevel4list").hide();
                                        if (sc2 == 'Y') {
                                            $("#divlevel4").hide();
                                            $("#level4_id").prop("disabled", true);
//                                            $("#slab_rate_flag").val('N');
//                                            $("#slab_rate_flag").prop("disabled", true);
                                            $("#divrange1").fadeIn("slow");
                                            $("#divrange2").fadeIn("slow");
                                            $('#range_from').prop("readonly", true);
                                            $('#range_to').prop("readonly", true);
                                        } else {
//                                            $("#slab_rate_flag").prop("disabled", false);
                                            $("#divrange1").hide();
                                            $("#divrange2").hide();
                                        }
                                    }

                                    // Start Get level 2 dropdown list code
                                    var village_id = village_id;
                                    var level2_list = level2_id;
                                    var level1_list_id = prop_level1_list_id;
                                    if (level2_list != '' && level1_list_id != '') {
                                        $.getJSON('getlevel2_list', {level2_list: level2_list, village_id: village_id, level1_list_id: level1_list_id}, function (data)
                                        {
                                            var sc = '<option value="">select</option>';
                                            $.each(data.data1, function (index, val) {
                                                sc += "<option value=" + index + ">" + val + "</option>";
                                            });
                                            var sc1 = '<option value="">select</option>';
                                            $.each(data.data2, function (index, val) {
                                                sc1 += "<option value=" + index + ">" + val + "</option>";
                                            });
                                            var sc2 = data['flag'];
                                            var sc3 = data['surveyno'];
                                            if (level3 == 1) {
                                                if (sc != emptycheck) {
                                                    $("#divlevel2list").fadeIn("slow");
                                                    $("#level2_list_id").prop("disabled", false);
                                                    $("#level2_list_id option").remove();
                                                    $("#level2_list_id").append(sc);
                                                    $('#level2_list_id').val(prop_level2_list_id);
                                                } else {
                                                    $("#divlevel2list").hide();
                                                }
                                                if (sc1 != emptycheck) {
                                                    $("#divlevel3").fadeIn("slow");
                                                    $("#level3_id").prop("disabled", false);
                                                    $("#level3_id option").remove();
                                                    $("#level3_id").append(sc1);
                                                    $('#level3_id').val(level3_id);
                                                } else {
                                                    $("#divlevel3").hide();
                                                }
                                                $("#saveflag").val(sc2);
                                                $("#surveyno").val(sc3);
                                                $("#divlevel3list").hide();
                                                $("#divlevel4").hide();
                                                $("#divlevel4list").hide();
                                                if (sc2 == 'Y') {
                                                    $("#divlevel3").hide();
                                                    $("#level3_id").prop("disabled", true);
//                                                    $("#slab_rate_flag").val('N');
//                                                    $("#slab_rate_flag").prop("disabled", true);
                                                    $("#divrange1").fadeIn("slow");
                                                    $("#divrange2").fadeIn("slow");
                                                    $('#range_from').prop("readonly", true);
                                                    $('#range_to').prop("readonly", true);
                                                } else {
//                                                    $("#slab_rate_flag").prop("disabled", false);
                                                    $("#divrange1").hide();
                                                    $("#divrange2").hide();
                                                }
                                            } else {
                                                if (sc != emptycheck) {
                                                    $("#divlevel2list").fadeIn("slow");
                                                    $("#level2_list_id").prop("disabled", false);
                                                    $("#level2_list_id option").remove();
                                                    $("#level2_list_id").append(sc);
                                                    $('#level2_list_id').val(prop_level2_list_id);
                                                } else {
                                                    $("#divlevel2list").hide();
                                                }
                                                if (sc1 != emptycheck) {
                                                    $("#divlevel4").fadeIn("slow");
                                                    $("#level4_id").prop("disabled", false);
                                                    $("#level4_id option").remove();
                                                    $("#level4_id").append(sc1);
                                                    $('#level4_id').val(level4_id);
                                                } else {
                                                    $("#divlevel4").hide();
                                                }
                                                $("#saveflag").val(sc2);
                                                $("#surveyno").val(sc3);
                                                $("#divlevel4list").hide();
                                                if (sc2 == 'Y') {
                                                    $("#divlevel3").hide();
                                                    $("#level3_id").prop("disabled", true);
//                                                    $("#slab_rate_flag").val('N');
//                                                    $("#slab_rate_flag").prop("disabled", true);
                                                    $("#divrange1").fadeIn("slow");
                                                    $("#divrange2").fadeIn("slow");
                                                    $('#range_from').prop("readonly", true);
                                                    $('#range_to').prop("readonly", true);
                                                } else {
//                                                    $("#slab_rate_flag").prop("disabled", false);
                                                    $("#divrange1").hide();
                                                    $("#divrange2").hide();
                                                }
                                            }

                                            //Start Get level 3 dropdown list code
                                            var village_id = village_id;
                                            var level3_list = level3_id;
                                            var level2_list_id = prop_level2_list_id;
                                            if (level3_list != '' && level2_list_id != '') {
                                                $.getJSON('getlevel3_list', {level3_list: level3_list, village_id: village_id, level2_list_id: level2_list_id}, function (data)
                                                {
                                                    var sc = '<option value="">select</option>';
                                                    var sc1 = '<option value="">select</option>';
                                                    $.each(data.data1, function (index, val) {
                                                        sc += "<option value=" + index + ">" + val + "</option>";
                                                    });

                                                    $.each(data.data2, function (index, val) {
                                                        sc1 += "<option value=" + index + ">" + val + "</option>";
                                                    });

                                                    var sc2 = data['flag'];
                                                    var sc3 = data['surveyno'];
                                                    if (sc != emptycheck) {
                                                        $("#divlevel3list").fadeIn("slow");
                                                        $("#level3_list_id").prop("disabled", false);
                                                        $("#level3_list_id option").remove();
                                                        $("#level3_list_id").append(sc);
                                                        $('#level3_list_id').val(prop_level3_list_id);

                                                    } else {
                                                        $("#divlevel3list").hide();
                                                    }
                                                    if (sc1 != emptycheck) {
                                                        $("#divlevel4").fadeIn("slow");
                                                        $("#level4_id").prop("disabled", false);
                                                        $("#level4_id option").remove();
                                                        $("#level4_id").append(sc1);
                                                        $('#level4_id').val(level4_id);

                                                    } else {
                                                        $("#divlevel4").hide();
                                                    }
                                                    $("#saveflag").val(sc2);
                                                    $("#surveyno").val(sc3);
                                                    $("#divlevel4list").hide();
                                                    if (sc2 == 'Y') {
                                                        $("#divlevel4").hide();
                                                        $("#level4_id").prop("disabled", true);
//                                                        $("#slab_rate_flag").val('N');
//                                                        $("#slab_rate_flag").prop("disabled", true);
                                                        $("#divrange1").fadeIn("slow");
                                                        $("#divrange2").fadeIn("slow");
                                                        $('#range_from').prop("readonly", true);
                                                        $('#range_to').prop("readonly", true);
                                                    } else {
//                                                        $("#slab_rate_flag").prop("disabled", false);
                                                        $("#divrange1").hide();
                                                        $("#divrange2").hide();
                                                    }
                                                    // Start Get level 4 dropdown list code
                                                    var village_id = village_id;
                                                    var level4_list = level4_id;
                                                    var level3_list_id = prop_level3_list_id;
                                                    if (level4_list != '' && level3_list_id != '') {
                                                        $.getJSON('getlevel4_list', {level4_list: level4_list, village_id: village_id, level3_list_id: level3_list_id}, function (data)
                                                        {
                                                            var sc = '<option value="">select</option>';
                                                            $.each(data.data1, function (index, val) {
                                                                sc += "<option value=" + index + ">" + val + "</option>";
                                                            });
                                                            var sc2 = data['flag'];
                                                            var sc3 = data['surveyno'];
                                                            if (sc != emptycheck) {
                                                                $("#divlevel4list").fadeIn("slow");
                                                                $("#level4_list_id").prop("disabled", false);
                                                                $("#level4_list_id option").remove();
                                                                $("#level4_list_id").append(sc);
                                                                $('#level4_list_id').val(prop_level4_list_id);
                                                            } else {
                                                                $("#divlevel4list").hide();
                                                            }
                                                            $("#saveflag").val(sc2);
                                                            $("#surveyno").val(sc3);
                                                            if (sc2 == 'Y') {
//                                                                $("#slab_rate_flag").val('N');
//                                                                $("#slab_rate_flag").prop("disabled", true);
                                                                $("#divrange1").fadeIn("slow");
                                                                $("#divrange2").fadeIn("slow");
                                                                $('#range_from').prop("readonly", true);
                                                                $('#range_to').prop("readonly", true);
                                                            } else {
//                                                                $("#slab_rate_flag").prop("disabled", false);
                                                                $("#divrange1").hide();
                                                                $("#divrange2").hide();
                                                            }
                                                        });
                                                    } //  End Get level 4 dropdown list code
                                                });
                                            } // End Get level 3 dropdown list code
                                        });
                                    } // Start Get level 2 dropdown list code

                                });
                            } // End Get level 1 list and levvel 2
                        });
                    } // End GEt Level 1 

                });
            }  //  End Get Village name and Valation Zone function      
        }); // End  Get Land Type










        // Start Get usage sub categry
        var usage_main_catg_id = usage_main_catg_id;
        if (usage_main_catg_id != '') {
            $.getJSON('getusagesub', {usage_main_catg_id: usage_main_catg_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (sc != emptycheck) {
                    $("#divcatsub").fadeIn("slow");
                    $("#divcatsub1").fadeIn("slow");
                    $("#usage_sub_catg_id").prop("disabled", false);
                    $("#usage_sub_catg_id option").remove();
                    $("#usage_sub_catg_id").append(sc);
                    $('#usage_sub_catg_id').val(usage_sub_catg_id);
                    $("#divcatsubsub").hide();
                    $("#divcatsubsub1").hide();
                    $("#divcons").hide();
                    $("#divcons1").hide();
                    $("#divroad").hide();
                    $("#divroad1").hide();
                    $("#divud1").hide();
                    $("#divud11").hide();
                    $("#divud2").hide();
                    $("#divud21").hide();
                } else {
                    $("#divcatsub").hide();
                    $("#divcatsub1").hide();
                }

                // Start Get usage sub sub categry
                var usagesub = usage_sub_catg_id;
                if (usagesub != '') {
                    $.getJSON('getusagesubsub', {usage_sub_catg_id: usagesub}, function (data)
                    {
                        var sc = '<option value="">select</option>';
                        $.each(data, function (index, val) {
                            sc += "<option value=" + index + ">" + val + "</option>";
                        });
                        if (sc != emptycheck) {
                            $("#divcatsubsub").fadeIn("slow");
                            $("#divcatsubsub1").fadeIn("slow");
                            $("#usage_sub_sub_catg_id").prop("disabled", false);
                            $("#usage_sub_sub_catg_id option").remove();
                            $("#usage_sub_sub_catg_id").append(sc);
                            $('#usage_sub_sub_catg_id').val(usage_sub_sub_catg_id);
                        } else {
                            $("#divcatsubsub").hide();
                            $("#divcatsubsub1").hide();
                        }
                        // Start 4 dropdownlist visibility
                        var usagesubsub = usage_sub_sub_catg_id;
                        if (usagesubsub != '') {
                            $.getJSON('getvisibility', {usage_sub_sub_catg_id: usagesubsub}, function (data)
                            {
                                var sc1 = data['cflag'];
                                var sc2 = data['rflag'];
                                var sc3 = data['ud1flag'];
                                var sc4 = data['ud2flag'];
                                if (sc1 == 'Y') {
                                    $("#divcons").fadeIn("slow");
                                    $("#divcons1").fadeIn("slow");
                                    $("#construction_type_id").prop("disabled", false);

                                } else {
                                    $("#divcons").hide();
                                    $("#divcons1").hide();
                                    $("#construction_type_id").prop("disabled", true);
                                }
                                if (sc2 == 'Y') {
                                    $("#divroad").fadeIn("slow");
                                    $("#divroad1").fadeIn("slow");
                                    $("#road_vicinity_id").prop("disabled", false);
                                } else {
                                    $("#road_vicinity_id").prop("disabled", true);
                                    $("#divroad").hide();
                                    $("#divroad1").hide();
                                }
                                if (sc3 == 'Y') {
                                    $("#divud1").fadeIn("slow");
                                    $("#divud11").fadeIn("slow");
                                    $("#user_defined_dependency_1").prop("disabled", false);
                                } else {
                                    $("#divud1").hide();
                                    $("#divud11").hide();
                                    $("#user_defined_dependency_1").prop("disabled", true);
                                }
                                if (sc4 == 'Y') {
                                    $("#divud2").fadeIn("slow");
                                    $("#divud21").fadeIn("slow");
                                    $("#user_defined_dependency_2").prop("disabled", false);
                                } else {
                                    $("#divud2").hide();
                                    $("#divud21").hide();
                                    $("#user_defined_dependency_2").prop("disabled", true);
                                }
                            });
                        }
                    });
                } // End Get usage sub sub categry
            });
        }// End Get usage sub categry



        //Slab Rate Flag Dropdownlist
//        if (slab_rate_flag == 'Y') {
//            $('#range_from').prop("readonly", false);
//            $('#range_to').prop("readonly", false);
//        }


        //Get Valuation subzone code
//        var valzone = valutation_zone_id;
//        if (valzone != '') {
//            $.getJSON('getvalsubzone', {valzone: valzone}, function (data)
//            {
//                var sc = '<option value="">select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                if (data != '') {
//                    $("#divvalsubzone").fadeIn("slow");
//                    $("#divvalsubzone1").fadeIn("slow");
//                    $("#valutation_subzone_id").prop("disabled", false);
//                    $("#valutation_subzone_id option").remove();
//                    $("#valutation_subzone_id").append(sc);
//                    $("#valutation_subzone_id").append(valutation_subzone_id);
//                } else {
//                    $("#divvalsubzone").hide();
//                    $("#divvalsubzone1").hide();
//                }
//            });
//        }

        $('#id1').val(id);
        $('#district_id').val(district_id);
        $('#range_from').val(range_from);
        $('#usage_main_catg_id').val(usage_main_catg_id);
        $('#prop_unit').val(prop_unit);
        $('#prop_rate').val(prop_rate);
        $('#land_rate').val(land_rate);
        $('#construction_rate').val(construction_rate);
        $('#range_to').val(range_to);
//        $('#slab_rate_flag').val(slab_rate_flag);
        $('#construction_type_id').val(construction_type_id);
        $('#road_vicinity_id').val(road_vicinity_id);
        $('#user_defined_dependency_1').val(user_defined_dependency_1);
        $('#user_defined_dependency_2').val(user_defined_dependency_2);
        $('#finyear_id').val(finyear_id);
        $('#effective_date').val(effective_date);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html("Save");
        if (level1_id == '') {
            $("#level1_id").prop("disabled", true);
        }
        if (level2_id == '') {
            $("#divlevel2").hide();
            $("#level2_id").prop("disabled", true);
        }
        if (level3_id == '') {
            $("#divlevel3").hide();
            $("#level3_id").prop("disabled", true);
        }
        if (level4_id == '') {
            $("#divlevel4").hide();
            $("#level4_id").prop("disabled", true);
        }
        if (prop_level1_list_id == '') {
            $("#divlevel1list").hide();
            $("#level1_list_id").prop("disabled", true);
        }
        if (prop_level2_list_id == '') {
            $("#divlevel2list").hide();
            $("#level2_list_id").prop("disabled", true);
        }
        if (prop_level3_list_id == '') {
            $("#divlevel3list").hide();
            $("#level3_list_id").prop("disabled", true);
        }
        if (prop_level4_list_id == '') {
            $("#divlevel4list").hide();
            $("#level4_list_id").prop("disabled", true);
        }
//        if (slab_rate_flag == 'Y') {
//            $('#range_from').prop("readonly", false);
//            $('#range_to').prop("readonly", false);
//        }
        if (construction_type_id == '') {
            $("#divcons").hide();
            $("#divcons1").hide();
            $("#construction_type_id").prop("disabled", true);
        }
        if (road_vicinity_id == '') {
            $("#divroad").hide();
            $("#divroad1").hide();
            $("#road_vicinity_id").prop("disabled", true);
        }
        if (user_defined_dependency_1 == '') {
            $("#divud1").hide();
            $("#divud11").hide();
            $("#user_defined_dependency_1").prop("disabled", true);
        }
        if (user_defined_dependency_2 == '') {
            $("#divud2").hide();
            $("#divud21").hide();
            $("#user_defined_dependency_2").prop("disabled", true);
        }
        return false;
    }



    function search() {
        var rdbsort1 = $('#rdbsort1').is(':checked');
        var rdbsort2 = $('#rdbsort2').is(':checked');
        if (rdbsort1 == false && rdbsort2 == false) {

            $('#rdbsort1').focus();
            alert('Please Select Record Type');
            window.scrollTo(500, 200);
            return false;
        }
        // alert('hi');
        document.getElementById("actiontype").value = '3';
        var village = $('#searchvillage').val();
        $('#hfvillage').val(village);
        $('#rdbsort').val('');

        $('#rate').submit();
    }
    function formselect(id) {
        $('#id1').val(id);
        $('#selectflag').val('Y');
        document.getElementById("actiontype").value = '3';
        $('#rate').submit();
    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '4';
            $('#id1').val(id);
        } else {
            return false;
        }
    }

</script>


<?php echo $this->Form->create('rate', array('id' => 'rate')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a  href="<?php echo $this->webroot; ?>helpfiles/Property Rate Chart/rate_<?php echo $language; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo __('help'); ?> <span class="fa fa-question fa-circle-o"></span></a>
             <!--<center><h3 class="box-title" style="font-weight: bolder"></h3></center>-->
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblrateasperlocation'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="panel panel-default " style=" border: 2px solid black;">
                    <div class="panel-body form-group"><br>
                        <div class="row">
                            <div class="form-group">
                                <label for="district_id" class="col-sm-2 control-label"><?php echo __('lblfineyer'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('finyear_id', array('options' => array($finyear), 'id' => 'finyear_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                    <span id="finyear_id_error" class="form-error"><?php echo $errarr['finyear_id_error']; ?></span>
                                </div>
                                <label for="ratetype_id" class="col-sm-2 control-label"><?php echo __('lblratetype'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('ratetype_id', array('options' => array($ratetypedata), 'id' => 'ratetype_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                    <span id="ratetype_id_error" class="form-error"><?php echo $errarr['ratetype_id_error']; ?></span>
                                </div>
                                <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbleffedate'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('effective_date', array('type' => 'text', 'id' => 'effective_date', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                    <span id="effective_date_error" class="form-error"><?php echo $errarr['effective_date_error']; ?></span>
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="form-group">
                                <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('taluka_id', array('options' => array($taluka), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></div>
                                <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-2"><?php echo $this->Form->input('village_id', array('options' => array($villagenname), 'empty' => '--select--', 'id' => 'village_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                    <span id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <br><hr style="border: 2px #000 solid "><br>
                        <?php for ($i = 0; $i < count($configure); $i++) { ?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-2"   id="divlevel1lbl">
                                        <label for="lblLevel1" class="control-label"><?php echo __('lbllocation'); ?></label>
                                    </div>
                                    <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                        <div class="col-sm-2"   divlevel1field>
                                            <?php echo $this->Form->input('level1_id', array('options' => array($level1propertydata), 'empty' => '--select--', 'id' => 'level1_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                            <span id="level1_id_error" class="form-error"><?php echo $errarr['level1_id_error']; ?></span>


                                        </div>
                                        <div class="col-sm-2" id="divlevel1list" hidden="true">
                                            <?php echo $this->Form->input('level1_list_id', array('options' => array($level1propertylist), 'empty' => '--select--', 'id' => 'list_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                        </div> <?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                        <div class="col-sm-2" id="divlevel2" hidden="true">
                                            <?php echo $this->Form->input('level2_id', array('options' => array($level2propertydata), 'empty' => '--select--', 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                        </div> 
                                        <div class="col-sm-2" id="divlevel2list" hidden="true">
                                            <?php echo $this->Form->input('level2_list_id', array('options' => array($level2propertylist), 'empty' => '--select--', 'id' => 'list_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                        </div><?php } ?>

                                </div>  
                            </div>
                            <hr style="border: 1px #ddd solid ">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-2" >
                                        &nbsp;
                                    </div>
                                    <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                        <div class="col-sm-2" id="divlevel3" hidden="true">
                                            <?php echo $this->Form->input('level3_id', array('options' => array($level3propertydata), 'empty' => '--select--', 'id' => 'level3_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                            <span id="level3_id_error" class="form-error"><?php echo $errarr['level3_id_error']; ?></span>

                                        </div>
                                        <div class="col-sm-2" id="divlevel3list" hidden="true">
                                            <?php echo $this->Form->input('level3_list_id', array('options' => array($level3propertylist), 'empty' => '--select--', 'id' => 'level3_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                            <span id="level3_list_id_error" class="form-error"><?php echo $errarr['level3_list_id_error']; ?></span>
                                        </div><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                        <div class="col-sm-2" id="divlevel4" hidden="true">
                                            <?php echo $this->Form->input('level4_id', array('options' => array($level4propertydata), 'empty' => '--select--', 'id' => 'level4_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                            <span id="level4_id_error" class="form-error"><?php echo $errarr['level4_id_error']; ?></span>
                                        </div>
                                        <div class="col-sm-2" id="divlevel4list" hidden="true">
                                            <?php echo $this->Form->input('level4_list_id', array('options' => array($level4propertylist), 'empty' => '--select--', 'id' => 'level4_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                            <span id="level4_list_id_error" class="form-error"><?php echo $errarr['level4_list_id_error']; ?></span>

                                        </div><?php } ?>
                                    <div class="col-sm-2" id="divrange1" hidden="true">
                                        <label>Range</label>
                                    </div>
                                    <div class="col-sm-2" id="divrange2" hidden="true">
                                        <div class="col-sm-8 row">
                                            <?php echo $this->Form->input('range_from', array('label' => false, 'id' => 'range_from', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly', 'placeholder' => 'From')) ?>
                                            <span id="range_from_error" class="form-error"><?php echo $errarr['range_from_error']; ?></span>

                                        </div>

                                        <div class="col-sm-8 row">
                                            <?php echo $this->Form->input('range_to', array('label' => false, 'id' => 'range_to', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly', 'placeholder' => 'To')) ?>
                                            <span id="range_to_error" class="form-error"><?php echo $errarr['range_to_error']; ?></span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <br><hr style="border: 2px #000 solid "><br>
                        <div class="row">
                            <div class="col-sm-2 center">
                                <label><?php echo __('lblusamaincat'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divcatsub" hidden="true">
                                <label><?php echo __('lblsubcat'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divcatsubsub" hidden="true">
                                <label><?php echo __('lblsubccategory'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divcons" hidden="true">
                                <label><?php echo __('lblconstuctiontye'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divroad" hidden="true">
                                <label><?php echo __('lblroadvicinity'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divud1" hidden="true">
                                <label><?php echo __('lbluserdefineddependency1'); ?></label>
                            </div>
                            <div class="col-sm-2 center" id="divud2" hidden="true">
                                <label><?php echo __('lbluserdefineddependency2'); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('usage_main_catg_id', array('options' => array($usagemain), 'empty' => '--select--', 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                <span id="usage_main_catg_id_error" class="form-error"><?php echo $errarr['usage_main_catg_id_error']; ?></span>
                            </div>
                            <div class="col-sm-2" id="divcatsub1" hidden="true">
                                <?php echo $this->Form->input('usage_sub_catg_id', array('options' => array($usagesub), 'empty' => '--select--', 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="usage_sub_catg_id_error" class="form-error"><?php echo $errarr['usage_sub_catg_id_error']; ?></span>

                            </div>
                            <div class="col-sm-2" id="divcatsubsub1" hidden="true">
                                <?php echo $this->Form->input('usage_sub_sub_catg_id', array('options' => array($usagesubsub), 'empty' => '--select--', 'id' => 'usage_sub_sub_catg_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="usage_sub_sub_catg_id_error" class="form-error"><?php echo $errarr['usage_sub_sub_catg_id_error']; ?></span>
                            </div>
                            <div class="col-sm-2" id="divcons1" hidden="true">
                                <?php echo $this->Form->input('construction_type_id', array('options' => array($constuctiontype), 'empty' => '--select--', 'id' => 'construction_type_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="construction_type_id_error" class="form-error"><?php echo $errarr['construction_type_id_error']; ?></span>
                            </div>
                            <div class="col-sm-2" id="divroad1" hidden="true">
                                <?php echo $this->Form->input('road_vicinity_id', array('options' => array($roadvicinity), 'empty' => '--select--', 'id' => 'road_vicinity_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="road_vicinity_id_error" class="form-error"><?php echo $errarr['road_vicinity_id_error']; ?></span>
                            </div>

                            <div class="col-sm-2" id="divud11" hidden="true">
                                <?php echo $this->Form->input('user_defined_dependency1_id', array('options' => array($userdependency1), 'empty' => '--select--', 'id' => 'user_defined_dependency1_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="user_defined_dependency1_id_error" class="form-error"><?php echo $errarr['user_defined_dependency1_id_error']; ?></span>
                            </div>
                            <div class="col-sm-2" id="divud21" hidden="true">
                                <?php echo $this->Form->input('user_defined_dependency2_id', array('options' => array($userdependency2), 'empty' => '--select--', 'id' => 'user_defined_dependency2_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="user_defined_dependency2_id_error" class="form-error"><?php echo $errarr['user_defined_dependency2_id_error']; ?></span>
                            </div>
                        </div>
                        <br><hr style="border: 2px #000 solid "><br>      
                        <div class="row">
                            <!--                            <div class="col-sm-2 center">
                                                            <label><?php // echo __('lblslabrate');                 ?></label>
                                                        </div>-->
                            <!--                            <div class="col-sm-2 center" id="divvalzone" hidden="true">
                                                            <label><?php echo __('lblvalzone'); ?></label>
                                                        </div>
                                                        <div class="col-sm-2 center" id="divvalsubzone" hidden="true">
                                                            <label><?php echo __('lblvalsubzone'); ?></label>
                                                        </div>-->
                            <div class="col-sm-2 center">
                                <label><?php echo __('lblpropertyrate'); ?></label>
                            </div>
                            <div class="col-sm-2 center">
                                <label><?php echo __('lblpropertyunit'); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <!--                            <div class="col-sm-2">
                            <?php
                            //
//                                $slabrateflag = array('N' => "NO", 'Y' => "YES");
//                                echo $this->Form->input('slab_rate_flag', array('options' => $slabrateflag, 'label' => false, 'id' => 'slab_rate_flag', 'class' => 'form-control input-sm'));
                            ?>
                                                        </div>-->
                            <!--                            <div class="col-sm-2" id="divvalzone1" hidden="true">
                            <?php // echo $this->Form->input('valutation_zone_id', array('options' => array($valuationzone), 'empty' => '--select--', 'id' => 'valutation_zone_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                                        </div>-->
                            <!--                            <div class="col-sm-2" id="divvalsubzone1" hidden="true">
                            <?php // echo $this->Form->input('valutation_subzone_id', array('options' => array($valuationsubzone), 'empty' => '--select--', 'id' => 'valutation_subzone_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                                        </div>-->
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('prop_rate', array('label' => false, 'id' => 'prop_rate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="prop_rate_error" class="form-error"><?php echo $errarr['prop_rate_error']; ?></span>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('prop_unit', array('options' => array($propunit), 'empty' => '--select--', 'id' => 'prop_unit', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                <span id="prop_unit_error" class="form-error"><?php echo $errarr['prop_unit_error']; ?></span>

                            </div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button id="btnviewrecord" name="btnviewrecord" class="btn btn-primary " style="text-align: center;" onclick="javascript: return viewrecord();" ><span class="glyphicon glyphicon-record"></span>&nbsp;<?php echo __('lblviewupdate'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp
                        <button type="button" class="btn btn-info" id="btnSubmit" name="btnSubmit" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Masters', 'action' => 'rate')); ?>';">
                            <span class="glyphicon glyphicon-remove"></span> <?php echo __('btncancel'); ?>
                        </button>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" id="divbtn" hidden="true">
                    <div class="form-group">
                        <div class="row" style="text-align: center;">
                            <input type="radio" id="rdbsort1" name="rdbsort5" val="R" <?php if ($rdbsort == 'R') { ?>checked="checked" <?php } ?>> &nbsp;&nbsp;<?php echo __('lblraterecord'); ?>
                            <input type="radio" id="rdbsort2" name="rdbsort5" val="S" <?php if ($rdbsort == 'S') { ?>checked="checked" <?php } ?>> &nbsp;&nbsp;<?php echo __('lblsurveyraterecord'); ?>
                            <?php echo $this->Form->input('rdbsort', array('label' => false, 'id' => 'rdbsort', 'type' => 'hidden', 'value' => $rdbsort)); ?>
                            <span id="rdbsort_error" class="form-error"><?php echo $errarr['rdbsort_error']; ?></span>

                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row" style="text-align: center;">
                            <div class="col-sm-3">&nbsp;</div>
                            <label for="searchvillage" class="col-sm-2 control-label"><?php echo __('lblsearchvillage'); ?></label>
                            <div class="col-sm-3"><?php echo $this->Form->input('searchvillage', array('options' => array($searchvillage), 'empty' => 'All Records', 'id' => 'searchvillage', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span id="searchvillage_error" class="form-error"><?php echo $errarr['searchvillage_error']; ?></span>

                            </div>
                            <div class="col-sm-3"><button id="btnsurveyraterecord" name="btnsurveyraterecord"  class="btn btn-primary " style="text-align: center;" onclick="javascript: return search();"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;<?php echo __('lblsearch'); ?></button></div>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div  id="divraterecord" hidden="true">
                    <div class="form-group" id="rate">
                        <table id="tableratedata" class="table table-striped table-bordered table-condensed" width="100%">  
                            <thead >  
                                <tr> 
                                    <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbladmvillage'); ?></td>
                                    <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbldellandtype'); ?></td>
                                    <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbllocation'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblusamaincat'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblsubcat'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblsubccategory'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblpropertyrate'); ?></td>
                                    <td style="text-align: center;width:12%; font-weight:bold;"><?php echo __('lblpropertyunit'); ?></td>
                                    <td style="text-align: center; font-weight:bold; width: 8%;"><?php echo __('lblaction'); ?> </td>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < count($raterecord); $i++) { ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['village_id']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['developed_land_types_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['list_1_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['range_from']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['range_to']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['usage_main_catg_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['usage_sub_catg_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['usage_sub_sub_catg_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['prop_rate']; ?></td>
                                        <td style="text-align: center;"><?php echo $raterecord[$i][0]['unit_desc_en']; ?></td>
                                        <td style="text-align: center;">
                                            <button id="btnselectgrid" name="btnselectgrid" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formselect(('<?php echo $raterecord[$i][0]['id']; ?>'));"><?php echo __('lblSelect'); ?></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table> 
                        <?php if (!empty($raterecord)) { ?>
                            <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                            <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblupdateordeleterecord'); ?></h3>
            </div>
            <div class="box-body">
                <?php for ($i = 0; $i < count($gridrecord); $i++) { ?>
                    <div class="row" id="divgridrecord" hidden="true">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfineyer'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['finyear_desc']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblratetype'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['ratetype_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbleffedate'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['effective_date']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['district_name_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['village_id']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbldellandtype'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['developed_land_types_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel1'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['level_1_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel1list'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['list_1_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbldivlvl2'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['level_2_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel2list'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['list_2_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbldistlvl'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['level_3_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel3list'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['list_3_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel4'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['level_4_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblLevel4list'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['list_4_desc_en']; ?></div>
    <!--                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblslabrate'); ?></label>
                                            <div class="col-sm-2"><?php // echo $gridrecord[$i][0]['slab_rate_flag'];                 ?></div>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['range_from']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['range_to']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblusamaincat'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['usage_main_catg_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblsubcat'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['usage_sub_catg_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblsubccategory'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['usage_sub_sub_catg_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpropertyrate'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['prop_rate']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpropertyunit'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['unit_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbllandrate'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['land_rate']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblconstructionrate'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['construction_rate']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblconstuctiontye'); ?> </label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['construction_type_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblroadvicinity'); ?></label>
                                            <div class="col-sm-2"><?php echo $gridrecord[$i][0]['road_vicinity_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-3 control-label"><?php echo __('lbluserdefineddependency1'); ?></label>
                                            <div class="col-sm-3"><?php echo $gridrecord[$i][0]['user_defined_dependency1_desc_en']; ?></div>
                                            <label for="party_id" class="col-sm-3 control-label"><?php echo __('lbluserdefineddependency2'); ?></label>
                                            <div class="col-sm-3"><?php echo $gridrecord[$i][0]['user_defined_dependency2_desc_en']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                                <div class="row" style="text-align: center">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-info"
                                                    onclick="javascript: return formupdate('<?php echo $gridrecord[$i][0]['id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['district_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['village_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['taluka_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level1_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level2_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level3_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level4_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level1_list_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level2_list_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level3_list_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['level4_list_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['range_from']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['usage_main_catg_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['usage_sub_catg_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['usage_sub_sub_catg_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['prop_unit']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['prop_rate']; ?>', '<?php echo $gridrecord[$i][0]['land_rate']; ?>', '<?php echo $gridrecord[$i][0]['construction_rate']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['construction_type_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['road_vicinity_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['user_defined_dependency_1']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['user_defined_dependency_2']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['finyear_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['effective_date']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['range_to']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['valutation_zone_id']; ?>',
                                                                    '<?php echo $gridrecord[$i][0]['valutation_subzone_id']; ?>');">
                                                <?php echo __('lblbtnupdate'); ?> </button>&nbsp;&nbsp;&nbsp;&nbsp; 
                                            <button id="btndelete" name="btndelete" class="btn btn-info" style="text-align: center;"  onclick="javascript: return formdelete(('<?php echo $gridrecord[$i][0]['id']; ?>'));">
                                                <?php echo __('lblbtndelete'); ?>  </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button id="btnselect" name="btndelete" class="btn btn-info " style="text-align: center;"   onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Masters', 'action' => 'rate')); ?>';">
                                                <span class="glyphicon glyphicon-remove"></span><?php echo __('btncancel'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $hfid; ?>' name='id1' id='id1'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $saveflag; ?>' name='saveflag' id='saveflag'/>
    <input type='hidden' value='<?php echo $selectflag; ?>' name='selectflag' id='selectflag'/>
    <input type='hidden' value='<?php echo $surveyno; ?>' name='surveyno' id='surveyno'/>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfvillage; ?>' name='hfvillage' id='hfvillage'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
