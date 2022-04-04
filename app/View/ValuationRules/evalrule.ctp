<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblEvalRule,#tblEvalSubRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });

        $("#roadvicinityrow,#userdependancy1row,#userdependancy2row,#maxpararow,#additionalRateRow,#subrule_ouder,.rrf").hide();

        if ('<?php echo $ruleid; ?>') {
            getSubruleList('<?php echo $ruleid; ?>');
        }

        if ("<?php echo $scatg; ?>" != '') {
            getUsageList("<?php echo $mcatg; ?>", '', 'usage_sub_catg_id', "<?php echo $scatg; ?>");
            getUsageList("<?php echo $mcatg; ?>", "<?php echo $scatg; ?>", 'usage_sub_sub_catg_id', "<?php echo $sscatg; ?>");
        }
        var host = "<?php echo $this->webroot; ?>";
        $(":checkbox").attr("checked", false);
        $("#c1,#f1,#c2,#f2,#c3,#f3,#c4,#f4,#c5,#f5,#maxformula,#rf1,#rf2").val('');

        if ($("#actionid").val()) {
            getssdata($("#usage_main_catg_id").val(), $("#usage_sub_catg_id").val(), $("#usage_sub_sub_catg_id").val());
        }
//-------------------------------------------------------Main Usage-------------------------------------------------------------------------------        
        $('#usage_main_catg_id').change(function () {
            getUsageList($(this).val(), '', 'usage_sub_catg_id', '');
        });
        $('#usage_sub_catg_id').change(function () {
            getUsageList($('#usage_main_catg_id').val(), $(this).val(), 'usage_sub_sub_catg_id', '');
        });
        $('#usage_sub_sub_catg_id').change(function () {
            getssdata($("#usage_main_catg_id").val(), $("#usage_sub_catg_id").val(), $(this).val());
        });
//-------------------------------------------------------Comparision-------------------------------------------------------------------------------        
        ratecmpchangeEvent($('input:radio[name="data[frmevalrule][rate_compare_flag]"]:checked').val());
        $('input:radio[name="data[frmevalrule][rate_compare_flag]"]').change(function () {
            ratecmpchangeEvent($(this).val());
        });
        $("#cmp_usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'cmp_usage_sub_catg_id', '');
        });
        $("#cmp_usage_sub_catg_id").change(function () {
            getUsageList($('#cmp_usage_main_catg_id').val(), $(this).val(), 'cmp_usage_sub_sub_catg_id', '');
        });
//-----------------------------------------------------Additional---------------------------------------------------------------------------------
        $('input:radio[name="data[frmevalrule][additional_rate_flag]"]').change(function () {
            addRateChangeEvent($(this).val());
        });
        $("#add_usage_main_catg_id").change(function () {
            getUsageList($(this).val(), '', 'add_usage_sub_catg_id', '');
        });
        $("#add_usage_sub_catg_id").change(function () {
            getUsageList($('#add_usage_main_catg_id').val(), $(this).val(), 'add_usage_sub_sub_catg_id', '');
        });
//-----------------------------------------------------------------Conditions and Forumula-------------------------------------------------------------------------------
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

        //-------------------------------------------------Other Formula and conditions-------------------
        $("#parameter_id,#operator_id").change(function () {
            if (txtfield == '' || (txtfield != '' && txtfield.length > 2)) {
                txtfield = 'f1';
            }
            var rate = $(this).val().trim();
            var cvalue = $("#" + txtfield).val().trim();
            $("#" + txtfield).val(cvalue + rate);
            $("#parameter_id,#operator_id").val('');
        });

//---------------------------------------------------------------------------------------------------------------------------------------------------------
        rateLocationchangeEvent($('input:radio[name="data[frmevalrule][dependency_flag]"]:checked').val());
        $('input:radio[name="data[frmevalrule][dependency_flag]"]').change(function () {
            rateLocationchangeEvent($(this).val());
        });
        //$("#rateCmpRow").hide();

        $('input:radio[name="data[frmevalrule][max_value_condition_flag]"]').change(function () {
            if ($(this).val() == 'Y') {
                $("#maxpararow").show();
            } else {
                $("#maxpararow").hide();
            }
        });
//-----------------------------------------------------Subrule Flag---------------------------------------------------------------------------------        
        $('input:radio[name="data[frmevalrule][subrule_flag]"]').change(function () {
            subruleChangeEvent($(this).val());
        });
//-----------------------------------------------------Rate Revision ---------------------------------------------------------------------------------
        $('input:radio[name="data[frmevalrule][rate_revision_flag]"]').change(function () {
            rateRevChangeEvent($(this).val());
        });
//-----------------------------------------------------Additional---------------------------------------------------------------------------------
        $("#btnCancel").click(function () {
            $(":checkbox").attr("checked", false);
            $("#actionid").val("");
        });
//------------------------------------------------------Save Rule/SubRule-------------------------------------------------------------------------
        $("#btnSave").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var action = $("#actionid").val();
            if (action != 'U' && action != 'SRU' && action != 'SRA') {
                $("#actionid").val('SV');
            }
            if ($("#usage_main_catg_id").val() == '')
            {
                $("#usage_main_catg_id").focus();
                $("#usage_main_catg_id").val('');
                alert("Please Select Main Category");
                return false;
            } else if ($("#usage_sub_catg_id").val() == '')
            {
                $("#usage_sub_catg_id").focus();
                $("#usage_sub_catg_id").val('');
                alert("Please Select Sub Category");
                return false;
            } else if ($("#usage_sub_sub_catg_id").val() == '')
            {
                $("#usage_sub_sub_catg_id").focus();
                $("#usage_sub_sub_catg_id").val('');
                alert("Please Select Sub Sub Category");
                return false;
            } else if ($("#outputitemid").val() == '')
            {
                $("#item_list_id").focus();
                $("#item_list_id").val('');
                alert("Please Select Output Item");
                return false;
            } else {
                $("#hsrflg").val($('input:radio[name="data[frmevalrule][subrule_flag]"] option:selected').val());
                //---------------------------------------------------------------Add Update Subrule-------------------------------------------------------                
                if (action == 'SRU' || action == 'SRA') {// add or update subrule
                    $.ajax(
                            {
                                type: 'post',
                                url: host + 'saveEvalSubRule',
                                data: $("#frmEvalRule").serialize(),
                                success: function (result)
                                {
                                    if (result == 1) {
                                        alert("Subrule Saved SuccessFully");
                                        $('#subruleid').val('');
                                        $("#lbl_subrule_id").html('');
                                        $('input:radio[name="data[frmevalrule][subrule_flag]"][value=N]').attr('checked', true);
                                        $('#actionid').val('U');
                                    }
                                    else {
                                        alert(result);
                                    }

                                }
                            });
                    getSubruleList($("#hdnruleid").val());
                    //------------------------------------------------------------------------------------------------------------------------------------
                    return false;
                } else {
                    $('#frmEvalRule').submit();
                    $('#evalrule').submit();
                }
            }
        });
//------------------------------------------------------------------------------------------------------------------------------------------------                                            
        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
        /*-----------------------------------For State,Div,dist,......*/

        $('#division_id').change(function () {
            var div = $("#division_id option:selected").val();
            setdistlist(div, '');
        });
        function getSubdivision(div, dist) {
            $.post(host + 'getSubDivision', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: ''}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#subdivision_id option").remove();
                $("#subdivision_id").append(sc);
            },'json');
        }

        function getTaluka(div, dist, subdiv) {
            $.post(host + 'getTaluka', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            },'json');
        }
        function getCircle(div, dist, subdiv, taluka) {
            $.post(host + 'getCircle', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv, taluka_id: taluka}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#circle_id option").remove();
                $("#circle_id").append(sc);
            },'json');
        }
        function getVillage(div, dist, subdiv, taluka, circle) {
            $.post(host + 'getVillage', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv, taluka_id: taluka, circle_id: circle}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            },'json');
        }

        var dist = '<?php echo $configure[0][0]['is_dist']; ?>';
        var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
        var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
        var circle = '<?php echo $configure[0][0]['is_block']; ?>';
        //sub division
        $('#state_id').change(function () {
            var div = $("#division_id option:selected").val();
            var dist = $("#state_id option:selected").val();
            if (subdiv != 'Y') {
                var subdiv = "";
                getTaluka(div, dist, subdiv);
            } else {
                getSubdivision(div, dist);
            }
        });
        // Taluka
        $('#subdivision_id').change(function () {
            var div = $("#division_id option:selected").val();
            var dist = $("#state_id option:selected").val();
            var subdiv = $("#subdivision_id option:selected").val();
            getTaluka(div, dist, subdiv);
        });
        // Circle
        $('#taluka_id').change(function () {
            var div = $("#division_id").val();
            var dist = $("#state_id").val();
            var subdiv = $("#subdivision_id").val();
            if (subdiv != 'Y') {
                subdiv = "";
            }
            var tal = $("#taluka_id").val();
            if (circle != 'Y') {
                var circle = "";
                getVillage(div, dist, subdiv, tal, circle);
            } else {

                getCircle(div, dist, subdiv, tal);
            }
            setlandtype($("#taluka_id").val(), '');
        });
        $("#landtype_id").change(function () {
            if ($(this).val() == 1) {
                $(".ulb_type").show();
                $(".corp_id").show();
            } else {
                $(".ulb_type").hide();
                $("#ulb_type_id").val("");
                $(".corp_id").hide();
                $("#corp_id").val("");
            }
        });
        // Governing Body
        $('#circle_id').change(function () {
            var ulb = $("#circle_id option:selected").val();
            $.post('getulb', {ulb: ulb}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#ulb_type_id option").remove();
                $("#ulb_type_id").append(sc);
            },'json');
        });
        //Corporation List
        $('#ulb_type_id').change(function () {
            var ulb = $("#ulb_type_id option:selected").val();
            $.post('getcorp', {ulb: ulb}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            },'json');
        });
//        if ($("#hdnruleid").val() != '') {
//            getSubruleList($("#hdnruleid").val());
//            return false;
//        }
    });
//---------------------------------------------------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
    var dist = '<?php echo $configure[0][0]['is_dist']; ?>';
    var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
    var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
    var circle = '<?php echo $configure[0][0]['is_block']; ?>';
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function rateLocationchangeEvent(rate_Loc_flag) {
        if (rate_Loc_flag == 'Y') {
            $("#selectvillagemapping").show();
        } else {
            $("#selectvillagemapping").hide();
        }
    }
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
//-------------------------------------------------------------------Rate Revision Flag--------------------------------------------------------------------------------
    function rateRevChangeEvent(Rate_rev_flag) {
        if (Rate_rev_flag == 'Y') {
            $(".rrf").show();
        } else {
            $(".rrf").show();
//            $(".rrf").val('');
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------

    function getUsageList(usageMain, usageSub, listForId, forValue) {
        var forUsage = listForId.replace('add_', '');
        forUsage = forUsage.replace('cmp_', '');
        $.post(host + "get" + forUsage, {usage_main_catg_id: usageMain, usage_sub_catg_id: usageSub}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#" + listForId + " option").remove();
            $("#" + listForId).append(sc);
            if (forValue) {
                $("#" + listForId).val(forValue);
            }
        },'json');
    }

//---------------------------------------------------------------------------------------Update Rule------------------------------------------------------------
    function formupdate(ruleid, rde, c1, f1, c2, f2, c3, f3, c4, f4, c5, f5, altfrmla, roadvicinity, udd1, udd2, mxflg, mxformula, subrlflag, rdl, dil, dfid, divid, distid, subdivid, talukaid, circleid, ulbtypeid, ulbid, villageid, landtypeid, stateid, loc_def_flag, ratecmpflag, cmpmainc_id, cmpsubc_id, cmpsubsubc_id, rule_ref_no, ad_rate_flag, addMainc_id, addSubc_id, addSubSubc_id, tdr_flag, urbon_flag, rural_flag, influence_flag, rate_rev_flag, rrf1, rrf2, rrf3, rrf4, rrf5) {
        $("#flashMessage").html("");
        $('input:radio[name="data[frmevalrule][rate_compare_flag]"][value=' + ratecmpflag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][dependency_flag]"][value=' + loc_def_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][tdr_flag]"][value=' + tdr_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][additional_rate_flag]"][value=' + ad_rate_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][is_urban]"][value=' + urbon_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][is_rural]"][value=' + rural_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][is_influence]"][value=' + influence_flag + ']').prop('checked', true);
        $('input:radio[name="data[frmevalrule][rate_revision_flag]"][value=' + rate_rev_flag + ']').prop('checked', true);
        rateLocationchangeEvent(loc_def_flag);
        ratecmpchangeEvent(ratecmpflag);
        addRateChangeEvent(ad_rate_flag);
        $("#rule_ref_no").val(rule_ref_no);
        $("#rule_ref_no").prop('title', rule_ref_no);
        $("#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_sub_catg_id,#cmp_usage_sub_sub_catg_id").empty();
        $("#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_sub_catg_id,#cmp_usage_sub_sub_catg_id").append('<option value="">--select--</option>');
        $("#usage_main_catg_id,#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_main_catg_id").val('');
        if (ratecmpflag == 'Y') {// for Compare Rate Usage
            $("#cmp_usage_main_catg_id").val(cmpmainc_id);
            getUsageList(cmpmainc_id, '', 'cmp_usage_sub_catg_id', cmpsubc_id);
            getUsageList(cmpmainc_id, cmpsubc_id, 'cmp_usage_sub_sub_catg_id', cmpsubsubc_id);
        }

        if (ad_rate_flag == 'Y') {// for Addition Rate
            $("#add_usage_main_catg_id").val(addMainc_id);
            getUsageList(addMainc_id, '', 'add_usage_sub_catg_id', addSubc_id);
            getUsageList(addMainc_id, addSubc_id, 'add_usage_sub_sub_catg_id', addSubSubc_id);
        }
        var mcatg;
        var subcatg;
        var subsubcatg;
        $.post(host + "getcategoryids", {evalruleid: ruleid}, function (maindata)
        {
            mcatg = maindata['usage_main_catg_id'];
            subcatg = maindata['usage_sub_catg_id'];
            subsubcatg = maindata['usage_sub_sub_catg_id'];
            $("#usage_main_catg_id").val(mcatg);
            getUsageList(mcatg, '', 'usage_sub_catg_id', subcatg);
            getUsageList(mcatg, subcatg, 'usage_sub_sub_catg_id', subsubcatg);
            getssdata(mcatg, subcatg, subsubcatg);
        },'json');
        //-----------------------------------For State,Div,dist,......
        var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
        var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
        var circle = '<?php echo $configure[0][0]['is_block']; ?>';
        if (loc_def_flag == 'Y') {
            $("#division_id").val(divid);
            setdistlist(divid, distid);
            if (subdiv == 'Y') {
                setsubdivlist(distid, subdivid, talukaid);
            }
            if (tal == 'Y')
            {
                settallist(divid, distid, subdivid, talukaid, landtypeid);
                setlandtype(talukaid, landtypeid);
            }
            if (circle == 'Y') {
                setcircle(talukaid, circleid, ulbtypeid);
            } else {
                setvillagelist(divid, distid, subdivid, talukaid, circleid, villageid);
            }
            $("#ulb_type_id").val(ulbtypeid);
            setulblist(ulbtypeid, ulbid);
        } else {
            $("#state_id,#subdivision_id,#taluka_id,#circle_id,#landtype_id,#village_id").empty();
            $("#state_id,#subdivision_id,#taluka_id,#circle_id,#landtype_id,#village_id").append('<option value="">--select--</option>');
            $("#state_id,#division_id,#state_id,#subdivision_id,#taluka_id,#circle_id,#landtype_id,#village_id,#ulb_type_id,#corp_id").val('');
        }
        //        setlandtype(villageid, landtypeid);

        $(":checkbox").attr("checked", false);
        if (dil) {
            var temp = new Array();
            temp = dil.split(",");
            for (var i = 0; i < temp.length; i++) {
                $("input[type=checkbox][value=" + temp[i] + "]").prop("checked", "true");
            }
        }

        $('input:radio[name="data[frmevalrule][max_value_condition_flag]"][value=' + mxflg + ']').prop('checked', true);
        if (mxflg === 'Y') {
            $("#maxpararow").show();
            $("#maxformula").val(mxformula);
        } else {
            $("#maxpararow").hide();
            $("#maxformula").val('');
        }
        subruleChangeEvent(subrlflag);
        $('input:radio[name="data[frmevalrule][subrule_flag]"][value="N"]').prop('checked', true);
        $("#rule_desc_ll").val(rdl);
        if (roadvicinity) {
            $("#roadvicinityrow").show();
            $("#roadvicinityid").val(roadvicinity);
        } else {
            $("#roadvicinityid").val(0);
            $("#roadvicinityrow").hide();
        }

        if (udd1) {
            $("#userdependancy1row").show();
            $("#userdefineddependency1").val(udd1);
        } else {
            $("#userdefineddependency1").val(0);
            $("#userdependancy1row").hide();
        }
        if (udd2) {
            $("#userdependancy2row").show();
            $("#userdefineddependency2").val(udd2);
        } else {
            $("#userdefineddependency2").val(0);
            $("#userdependancy2row").hide();
        }
        if (subrlflag == 'Y') {
            getSubruleList(ruleid);
        } else {
            $("#subrulelistdiv").text('');
        }
        $("#hsrflg").val("");
        //-----------------------------------------------------
        $("#hdnruleid").val(ruleid);
        $("#rule_desc_en").val(rde);
        $("#c1").val(c1);
        $("#f1").val(f1);
        $("#c2").val(c2);
        $("#f2").val(f2);
        $("#c3").val(c3);
        $("#f3").val(f3);
        $("#c4").val(c4);
        $("#f4").val(f4);
        $("#c5").val(c5);
        $("#f5").val(f5);
        $("#af").val(altfrmla);
        $("#outputitemid").val(dfid);
        $('#rrf1').val(rrf1);
        $('#rrf2').val(rrf2);
        $('#rrf3').val(rrf3);
        $('#rrf4').val(rrf4);
        $('#rrf5').val(rrf5);
        $("#actionid").val('U');
        window.scrollTo(500, 200);
        return false;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function getssdata(usage_main_catg_id, usage_sub_catg_id, usage_sub_sub_catg_id) {
        $.post(host + "getparamlist", {usage_main_catg_id: usage_main_catg_id, usage_sub_catg_id: usage_sub_catg_id, usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
        {
            var sc = "<option value=''> --select-- </option>";
            var prlist = "<ul>";
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
                prlist += "<li>" + index + " : " + val + "</li>";
            });
            $("#maxparaid option").remove();
            $("#parameter_id option").remove();
            $("#parameter_id").append(sc);
            $("#maxparaid").append(sc);
            prlist += "</ul>";
            $("#paradescid").html("");
            $("#paradescid").html(prlist);
        },'json');
        $.post(host + "getcdrflags", {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
        {
            if (data['road_vicinity_flag'] == 'Y') {
                $("#roadvicinityrow").show();
            } else {
                $("#roadvicinity").val(0);
                $("#roadvicinityrow").hide();
            }

            if (data['user_defined_dependency1_flag'] == 'Y') {
                $("#userdependancy1row").show();
            } else {
                $("#userdefineddependency1").val(0);
                $("#userdependancy1row").hide();
            }
            if (data['user_defined_dependency2_flag'] == 'Y') {
                $("#userdependancy2row").show();
            } else {
                $("#userdefineddependency2").val(0);
                $("#userdependancy2row").hide();
            }
        },'json');
        $.post(host + "getsubsubruledesc", {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
        {
            if ($("#actionid").val() != 'U') {
                $("#rule_desc_en").val(data['usage_sub_sub_catg_desc_en']);
                $("#rule_desc_ll").val(data['usage_sub_sub_catg_desc_ll']);
            }
        },'json');
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function getSubruleList(ruleid) {
        $('#subrulelistdiv').html('');
        $.ajax({
            type: 'post',
            url: host + 'getSubruleList',
            data: {'ruleid': ruleid},
            success: function (result)
            {
                $('#subrulelistdiv').html(result);

            }
        });
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function subruleChangeEvent(subruleflag) {
        $("#flashMessage").html("");
        if (subruleflag == 'Y') {
            if ($("#hdnruleid").val() != '') {
                $("#actionid").val('SRA');
                $.post(host + 'getMaxEvalSubRuleOrder', {ruleid: $("#hdnruleid").val()}, function (outdata)
                {
                    $("#out_item_order").val(outdata);
                },'json');
            } else {
                $("#out_item_order").val(1);
            }

            $("#subruleid").val('');
            $('#subrule_ouder').show();
        } else {
            $("#out_item_order").val(1);
            $('#subrule_ouder').hide();
        }
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
    function getSubruleDetails(rid, rsid) {
        $("#flashMessage").html("");
        $('#subrule_ouder').show();//show subruleourder
        $("#hdnruleid").val(rid);
        window.scrollTo(0, 450);
        $("#selectvillagemapping").hide();
        $("#hdnruleid").val(rid);
        $("#subruleid").val(rsid);
        $("#lbl_subrule_id").html('Subrule_Id: ' + rsid);
        $.post(host + 'getSubrule', {ruleid: rid, subruleid: rsid}, function (sbrldata) {
            $("#c1").val(sbrldata['evalsubrule_cond1']);
            $("#f1").val(sbrldata['evalsubrule_formula1']);
            $("#c2").val(sbrldata['evalsubrule_cond2']);
            $("#f2").val(sbrldata['evalsubrule_formula2']);
            $("#c3").val(sbrldata['evalsubrule_cond3']);
            $("#f3").val(sbrldata['evalsubrule_formula3']);
            $("#c4").val(sbrldata['evalsubrule_cond4']);
            $("#f4").val(sbrldata['evalsubrule_formula4']);
            $("#c5").val(sbrldata['evalsubrule_cond5']);
            $("#f5").val(sbrldata['evalsubrule_formula5']);
            $('input:radio[name="data[frmevalrule][rate_revision_flag]"][value=' + sbrldata['rate_revision_flag'] + ']').prop('checked', true);
            rateRevChangeEvent(sbrldata['rate_revision_flag']);
            $("#rrf1").val(sbrldata['rate_revision_formula1']);
            $("#rrf2").val(sbrldata['rate_revision_formula2']);
            $("#rrf3").val(sbrldata['rate_revision_formula3']);
            $("#rrf4").val(sbrldata['rate_revision_formula4']);
            $("#rrf5").val(sbrldata['rate_revision_formula5']);

            $("#roadvicinityid").val(sbrldata['road_vicinity_id']);
            $("#userdefineddependency1").val(sbrldata['user_defined_dependency1_id']);
            $("#userdefineddependency2").val(sbrldata['user_defined_dependency2_id']);
            $("#r1").val(sbrldata['rate1']);
            $("#r2").val(sbrldata['rate2']);
            $("#outputitemid").val(sbrldata['output_item_id']);
            $("#out_item_order").val(sbrldata['out_item_order']);
            $('input:radio[name="data[frmevalrule][max_value_condition_flag]"][value=' + sbrldata['max_value_condition_flag'] + ']').prop('checked', true);
            if (sbrldata['max_value_condition_flag'] === 'Y') {
                $("#maxpararow").show();
                $("#maxformula").val(sbrldata['max_value_formula']);
            } else {
                $("#maxpararow").hide();
                $("#maxformula").val('');
            }
            $("#actionid").val('SRU');
        },'json');
        return false;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    function removeSubRule(rid, rsid) {
        var conf = confirm('Are You Sure to delete this Sub Rule');
        if (!conf) {
            return false;
        } else {
            $.ajax({
                type: 'post',
                url: host + 'removeEvalSubRule',
                data: {rule_id: rid, sub_rule_id: rsid},
                success: function (result)
                {
                    if (result == 1) {
                        $("#subrule_" + rsid).fadeOut(300);
                    }
                    else
                        alert(result);
                }
            });
            return false;
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------Update-----------------------------------------------------------------------------
//-----------------------------------------Get Div,District,Sub Div, Taluka-----------------------------

    function setdistlist(divid, distid) {
        $.post(host + 'getDistrict', {state_id: "<?php echo $stateid; ?>", division_id: divid}, function (udistdata)
        {
            var sc = '<option value="">select</option>';
            $.each(udistdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#state_id option").remove();
            $("#state_id").append(sc);
            $("#state_id").val(distid);
        },'json');
    }

    function setsubdivlist(divid, distid, subdivid) {
        $.post(host + 'getSubDivision', {state_id: "<?php echo $stateid; ?>", division_id: divid, state_id: distid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#subdivision_id option").remove();
            $("#subdivision_id").append(sc);
            $("#subdivision_id").val(subdivid);
        },'json');
    }

    function settallist(divid, distid, subdivid, talukaid) {
        $.post(host + 'getTaluka', {state_id: "<?php echo $stateid; ?>", division_id: divid, state_id: distid, subdivision_id: subdivid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
            $("#taluka_id").val(talukaid);
        },'json');
    }

    function setlandtype(talukaid, landtypeid) {
        $.post(host + 'getLandtype', {tal: talukaid}, function (ltdata)
        {
            var sc = '<option value="">--select--</option>';
            $.each(ltdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#landtype_id").prop("disabled", false);
            $("#landtype_id option").remove();
            $("#landtype_id").append(sc);
            $("#landtype_id").val(landtypeid);
        },'json');
    }

    function setcircle(talukaid, circleid, ulbtypeid) {
        $.post('getcircle', {tal: talukaid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#circle_id option").remove();
            $("#circle_id").append(sc);
            $("#circle_id").append(circleid);
        },'json');
    }

    function setvillagelist(divid, distid, subdivid, talukaid, circleid, villageid) {
        $.post(host + 'getVillage', {state_id: "<?php echo $stateid; ?>", division_id: divid, district_id: distid, subdivision_id: subdivid, taluka_id: talukaid, circle_id: circleid}, function (villagelistdata)
        {
            var sc = '<option value="">select</option>';
            $.each(villagelistdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#village_id option").remove();
            $("#village_id").append(sc);
            $("#village_id").val(villageid);
        },'json');
    }

    function setulblist(ulbtypeid, ulbid) {
        $.post('getcorp', {ulb: ulbtypeid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#corp_id option").remove();
            $("#corp_id").append(sc);
            $("#corp_id").val(ulbid);
        },'json');
    }




    function formdelete(id) {
        $("#hdnruleid").val(id);
        $("#actionid").val('D');
        var conf = confirm('Are You Sure to Delete this Rule');
        if (!conf) {
            return false;
        }
        else {
            $('#frmEvalRule').submit();
        }
    }
</script>

<?php
echo $this->Form->create('frmevalrule', array('id' => 'frmEvalRule', 'class' => 'form-vertical'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblevalrule'); ?></h3></center>
            </div>
            <div class="box-body">
                <div style="border: 1px black solid">
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="financial Year" class="control-label col-sm-2"><?php echo __('lblfineyer'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[37], array('options' => $finyearList, 'multiple' => false, 'id' => 'fin_year', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="Effective Date" class="control-label col-sm-2"><?php echo __('lbleffedate'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[38], array('id' => 'effective_date', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="refno" class="control-label col-sm-2"><?php echo __('lblReferenceNo'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[46], array('id' => 'rule_ref_no', 'label' => false, 'placeholder' => 'Rule Reference', 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="usage_main_catg_id" class="control-label col-sm-3"><?php echo __('lblusamaincat'); ?></label>
                                <label for="usage_sub_catg_id" class="control-label col-sm-3"><?php echo __('lblUsagesubcategoryname'); ?></label>
                                <label for="usage_sub_sub_catg_id" class="control-label col-sm-6"><?php echo __('lblsubsubcategorydesc'); ?></label>
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-3" ><?php echo $this->Form->input('maincategory', array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <div class="col-sm-3" ><?php echo $this->Form->input('subcategory', array('type' => 'select', 'options' => $scatglist, 'empty' => '--Select Option--', 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <div class="col-sm-6" ><?php echo $this->Form->input('subsbucategory', array('type' => 'select', 'options' => $sscatglist, 'empty' => '--Select Option--', 'id' => 'usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>                            
                        </div>
                    </div>

                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="rule desc en" class="control-label col-sm-6"><?php echo __('lblruledescen'); ?></label>
                                        <label for="rule desc local" class="control-label col-sm-6"><?php echo __('lblruledescll'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6" ><?php echo $this->Form->input($name[1], array('id' => 'rule_desc_en', 'placeholder' => 'Rule Description in English', 'label' => false, 'class' => 'form-control input-sm')); ?></div>            
                                        <div class="col-sm-6" ><?php echo $this->Form->input($name[21], array('id' => 'rule_desc_ll', 'placeholder' => 'Rule Description in Local Language', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Rate Compare Flag" class="control-label col-sm-4"><?php echo __('lblRateCompareRequired'); ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[42], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                            </div>
                        </div>
                    </div>                    
                    <div class="row" id="rateCmpRow">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="usage_main_catg_id for Compare Rate" class="control-label col-sm-3"><?php echo __('lblCmpUsamaincat'); ?></label>
                                        <label for="usage_sub_catg_id for Compare Rate" class="control-label col-sm-3"><?php echo __('lblCmpUsagesubcat'); ?></label>
                                        <label for="usage_sub_sub_catg_id for Compare Rate" class="control-label col-sm-3"><?php echo __('lblCmpUsageSubsubcat'); ?></label>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[43], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'cmp_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[44], array('type' => 'select', 'options' => $scatglist, 'empty' => '--Select Option--', 'id' => 'cmp_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                        <div class="col-sm-6" ><?php echo $this->Form->input($name[45], array('type' => 'select', 'options' => $sscatglist, 'empty' => '--Select Option--', 'id' => 'cmp_usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Additional Rate Flag" class="control-label col-sm-4"><?php echo __('lblAdditionRateRequired'); ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[47], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="additionalRateRow">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="usage_main_catg_id for Additional Rate" class="control-label col-sm-3"><?php echo __('lblusamaincat'); ?></label>
                                        <label for="usage_sub_catg_id for Additional Rate" class="control-label col-sm-3"><?php echo __('lblsubcat'); ?></label>
                                        <label for="usage_sub_sub_catg_id for Additional Rate" class="control-label col-sm-3"><?php echo __('lblsubccategory'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[59], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'add_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                        <div class="col-sm-3" ><?php echo $this->Form->input($name[60], array('type' => 'select', 'options' => $scatglist, 'empty' => '--Select Option--', 'id' => 'add_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                        <div class="col-sm-6" ><?php echo $this->Form->input($name[61], array('type' => 'select', 'options' => $sscatglist, 'empty' => '--Select Option--', 'id' => 'add_usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Location Dependancy Flag" class="control-label col-sm-4"><?php echo __('lblRateLocationDependancyRequired'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[36], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div>                                                            
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="selectvillagemapping" class="table-responsive">
                        <div class="col-sm-12">
                            <table id="tablevillagemapping" class="table table-striped table-bordered table-hover">  
                                <thead >  
                                    <tr>
                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmstate'); ?></td>
                                        <?php for ($i = 0; $i < count($configure); $i++) { ?>                                            
                                            <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblDivision'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmdistrict'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblSubDivision'); ?> </td><?php } ?>
                                            <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmtaluka'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblCircle'); ?> </td><?php } ?>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lbldellandtype'); ?> </td>                                          
                                            <td style="text-align: center; font-weight:bold;" class="ulb_type"><?php echo __('lblCorporationClass'); ?> </td>
                                            <td style="text-align: center; font-weight:bold;" class="corp_id"><?php echo __('lblcorpcouncillist'); ?></td>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmvillage'); ?> </td>                                          
                                        <?php } ?>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center"><?php echo $this->Form->input($name[12], array('options' => $statelist, 'empty' => '--select--', 'id' => 'state_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                        <?php for ($i = 0; $i < count($configure); $i++) { ?>                                        
                                            <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>                                            
                                                <td style="text-align: center"><?php echo $this->Form->input($name[27], array('options' => $divisionlist, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                            } else {
                                                echo $this->Form->input($name[27], array('type' => 'hidden', 'value' => '0'));
                                            }
                                            ?>
                                            <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input($name[28], array('options' => $districtlist, 'empty' => '--select--', 'id' => 'state_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                            } else {
                                                echo $this->Form->input($name[28], array('type' => 'hidden', 'value' => '0'));
                                            }
                                            ?>
                                            <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input($name[29], array('empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                            } else {
                                                echo $this->Form->input($name[29], array('type' => 'hidden', 'value' => '0'));
                                            }
                                            ?>
                                            <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input($name[30], array('empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                            } else {
                                                echo $this->Form->input($name[30], array('type' => 'hidden', 'value' => '0'));
                                            }
                                            ?>
                                            <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input($name[31], array('empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                            } else {
                                                echo $this->Form->input($name[31], array('type' => 'hidden', 'value' => '0'));
                                            }
                                            ?>
                                            <td style="text-align: center"><?php echo $this->Form->input($name[32], array('empty' => '--select--', 'id' => 'landtype_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                            <td style="text-align: center" class="ulb_type"> <?php echo $this->Form->input($name[33], array('options' => $ulbname, 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                            <td style="text-align: center" class="corp_id"><?php echo $this->Form->input($name[34], array('empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                            <td style="text-align: center"><?php echo $this->Form->input($name[35], array('empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="TDR Applicable" class="control-label col-sm-4"><?php echo __('lblTDRApplicable'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[53], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div>                                                            
                            </div>
                        </div>
                    </div>

                    <div class="row top-buffer">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Rule Applicable For" class="control-label col-sm-3"><?php echo __('lblruleapplicable'); ?> </label>            
                                <label for="Rule Applicable For Urbon" class="control-label col-sm-1"><?php echo __('lblUrban') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[56], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'urbanFlag')); ?></div>                                                            
                                <label for="Rule Applicable For Rural" class="control-label col-sm-1"><?php echo __('lblRural') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[57], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ruralFlag')); ?></div>                                                            
                                <label for="Rule Applicable For Influence" class="control-label col-sm-1"><?php echo __('lblInfluence') . ": "; ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[58], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'influenceFlag')); ?></div>                                                            
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Sub Rule" class="control-label col-sm-4"><?php echo __('lblmultiplerate'); ?></label>            
                                <div class="col-sm-3"><?php echo $this->Form->input($name[20], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'subruleflag')); ?></div> 
                            </div>
                        </div>
                    </div>

                </div>
                <!-- -----------------------*-*-*-**-***-*-*Subrule Section *-**-*-**-*-*-*-*-**------------------------------- -->
                <div style="border: 1px black solid">             
                    <div  class="rowh top-buffer">&nbsp;</div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">                                                                
                                <div class="col-sm-4"></div>
                                <label for="construction type" class="control-label col-sm-2" style="background-color: #DFFFDE; color: #001f3f;" id="lbl_subrule_id"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="roadvicinityrow">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="construction type" class="control-label col-sm-2"><?php echo __('lblroadvicinity'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[24], array('options' => $roadvicinitylist, 'empty' => '--Select--', 'id' => 'roadvicinityid', 'label' => false, 'class' => 'form-control input-sm')); ?></div>           
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="userdependancy1row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="user defined dependancy 1" class="control-label col-sm-3"><?php echo __('userdefineddependency1'); ?></label>                                
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[25], array('options' => $userdd1list, 'empty' => '--Select--', 'id' => 'userdefineddependency1', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row" id="userdependancy2row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="user defined dependancy 2" class="control-label col-sm-3"><?php echo __('userdefineddependency2'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[26], array('options' => $userdd2list, 'empty' => '--Select--', 'id' => 'userdefineddependency2', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-7">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">

                                            <label for="rule desc en" class="control-label col-sm-3"><?php echo __('lbloutputitem'); ?></label>                            
                                            <div class="col-sm-5" ><?php echo $this->Form->input($name[22], array('id' => 'outputitemid', 'options' => $outitemlist, 'empty' => '--Select Option--', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                                            <div id="subrule_ouder">    
                                                <label for="Output Display Order" class="control-label col-sm-2"><?php echo __('lblDisplayOrder'); ?></label>                            
                                                <div class="col-sm-2" ><?php echo $this->Form->input('out_item_order', array('id' => 'out_item_order', 'type' => 'Number', 'min' => '1', 'max' => '200', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5" style="background-color: lightblue">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="Parameter Desc" class="control-label col-sm-3" align="center"><?php echo __('lblitemdesc'); ?></label>                                        
                                            <div class="col-sm-9" style="height: 100px;overflow-y: scroll;padding-left: 0px; border: 2px #00529B ridge" id="paradescid"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                   
                    <!-- -----------------------------------------------------Dependent Upon -------------------------------------------- -->
                    <div class="row top-buffer" hidden="true">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Dependant Upon" class="control-label col-sm-2" ><?php echo __('lbldependantupon'); ?></label>                                        
                                <div class="col-sm-4" style="height: 100px;overflow-y: scroll;padding-left: 30px; border: 2px #00529B ridge"> <?php echo $this->Form->input($name[23], array('type' => 'select', 'options' => $dependancylist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'dulist')); ?></div>
                            </div>
                        </div>
                    </div>
                    <!-- --------------------------------------------------------Max Value Check -------------------------------------------- -->
                    <div class="row top-buffer">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="max value checking " class="control-label col-sm-2"><?php echo __('maxvaluecheck'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[19], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'maxflag')); ?></div>                            
                            </div>
                        </div>
                    </div>                     
                    <div id="maxpararow">
                        <div class="form-group">
                            <div class="row top-buffer">
                                <div class="col-sm-12">
                                    <div class="form-group">    
                                        <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectmaxpara'); ?></label>            
                                        <div class="col-sm-3"><?php echo $this->Form->input('maxvalueparameterlist', array('type' => 'select', 'options' => $maxvalueparameterslist, 'empty' => '-select-', 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'maxparaid')); ?></div>
                                        <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                                        <div class="col-sm-3"><?php echo $this->Form->input('operatorsignmax', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'maxoptorid')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div  class="rowht top-buffer">&nbsp;</div>
                            <div class="row ">
                                <div class="col-sm-12">
                                    <div class="form-group">    
                                        <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblmaxvalueformula'); ?></label>                                        
                                        <div class="col-sm-8"><?php echo $this->Form->input($name[18], array('id' => 'maxformula', 'placeholder' => 'Max Value Formula', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -----------------------------------------------------Rate Revision Flag -------------------------------------------- -->
                    <div class="row top-buffer">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="TDR Applicable" class="control-label col-sm-2"><?php echo __('lblRateRevFlag'); ?> </label>
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[64], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div>                                                            
                            </div>
                        </div>
                    </div>
                    <!-- -------------------------------------------------------- Conditions & Formula  -------------------------------------------- -->
                    <div class="row top-buffer">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectpara'); ?></label>            
                                <div class="col-sm-4"><?php echo $this->Form->input('parameterlist', array('type' => 'select', 'empty' => '-select-', 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'parameter_id')); ?></div>
                                <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                                <div class="col-sm-4"><?php echo $this->Form->input('operatorsign', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'operator_id')); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row top-buffer">
                        <div class="col-sm-12">
                            <div class="form-group">                                
                                <label for="Select Parameter" class="control-label col-sm-4"><?php echo __('lblcondition'); ?></label>            
                                <label for="Select Parameter" class="control-label col-sm-4"><span class="rrf"><?php echo __('lblRateRevFlag'); ?> </span></label> 
                                <label for="Select Parameter" class="control-label col-sm-4"><?php echo __('lblformula'); ?></label>                                        

                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[2], array('id' => 'c1', 'placeholder' => 'Condition 1', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-4 "><?php echo $this->Form->input($name[48], array('id' => 'rrf1', 'title' => 'Rate Revision Formula 1', 'placeholder' => 'Rate Revision Formula 1', 'label' => false, 'class' => 'cndpr form-control input-sm rrf')); ?></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[3], array('id' => 'f1', 'placeholder' => 'Formula 1', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[4], array('id' => 'c2', 'placeholder' => 'Condition 2', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-4 "><?php echo $this->Form->input($name[49], array('id' => 'rrf2', 'title' => 'Rate Revision Formula 2', 'placeholder' => 'Rate Revision Formula 2', 'label' => false, 'class' => 'cndpr form-control input-sm rrf')); ?></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[5], array('id' => 'f2', 'placeholder' => 'Formula 2', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht">&nbsp;</div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[6], array('id' => 'c3', 'placeholder' => 'Condition 3', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-4 "><?php echo $this->Form->input($name[50], array('id' => 'rrf3', 'title' => 'Rate Revision Formula 3', 'placeholder' => 'Rate Revision Formula 3', 'label' => false, 'class' => 'cndpr rrf form-control input-sm ')); ?></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[7], array('id' => 'f3', 'placeholder' => 'Formula 3', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[8], array('id' => 'c4', 'placeholder' => 'Condition 4', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-4 "><?php echo $this->Form->input($name[51], array('id' => 'rrf4', 'title' => 'Rate Revision Factor 4', 'placeholder' => 'Rate Revision Formula 4', 'label' => false, 'class' => 'cndpr rrf form-control input-sm')); ?></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[9], array('id' => 'f4', 'placeholder' => 'Formula 4', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[10], array('id' => 'c5', 'placeholder' => 'Condition 5', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-4 "><?php echo $this->Form->input($name[52], array('id' => 'rrf5', 'title' => 'Rate Revision Factor 5', 'placeholder' => 'Rate Revision Formula 5', 'label' => false, 'class' => 'cndpr rrf form-control input-sm')); ?></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[11], array('id' => 'f5', 'placeholder' => 'Formula 5', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-2 onlyforrule"></div>
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-2"></div>                                                       
                                            <div class="col-sm-4"><?php echo $this->Form->input($name[62], array('id' => 'af', 'placeholder' => 'Alternate Formula', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                                <div class="row" style="text-align: center">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <?php
                                            echo $this->Form->input('hid', array('type' => 'hidden', 'id' => 'hdnruleid', 'value' => $ruleid));
                                            echo $this->Form->input('subruleid', array('id' => 'subruleid', 'type' => 'hidden'));
                                            echo $this->Form->input('hsrflg', array('type' => 'hidden', 'id' => 'hsrflg', 'value' => $hsrflg));
                                            echo $this->Form->input('action', array('type' => 'hidden', 'id' => 'actionid', 'value' => $hfaction));
                                            ?>                                            
                                            <?php
                                            echo $this->Form->button(__('btnsave'), array('id' => 'btnSave', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                                            echo $this->Form->reset(__('lblreset'), array('id' => 'btnCancel', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                                            echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div  class="rowht">&nbsp;</div>

                                <div class="row" id="subrulelistdiv" >                                   
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table  id="tblEvalRule" class="table table-striped table-bordered table-hover">
                        <thead >                        
                            <?php
                            echo "<tr>"
                            . "<td class='center width5'>" . __('lblsrno') . "</td>"
                            . "<td class='center width5'>" . __('lblid') . "</td>"
                            . "<td class='center'>" . __('lblReferenceNo') . "</td>"
                            . "<td class='center'>" . __('lblevalrule') . "</td>"
                            . "<td class='center width10'>" . __('maxvaluecheck') . "</td>"
                            . "<td class='center width5'>" . __('lblsubrule') . "</td>"
                            . "<td class='center width10'>" . __('lblaction') . "</td>"
                            . "</tr>";
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $srno = 1;
                            foreach ($evalruledata as $erd) {
                                $erd = $erd['evalrule'];
                                echo "<tr>"
                                . "<td class='center tblbigdata'>" . $srno++ . "</td>"
                                . "<td class='center tblbigdata'>" . $erd['evalrule_id'] . "</td>"
                                . "<td class='center tblbigdata'>" . $erd['reference_no'] . "</td>"
                                . "<td class='center tblbigdata'>" . $erd['evalrule_desc_' . $lang] . "</td>"
                                . "<td class='center tblbigdata'>" . $erd['max_value_condition_flag'] . "</td>"
                                . "<td class='center tblbigdata'>" . $erd['subrule_flag'] . "</td>";
                                echo "<td class='center width10'>"
                                . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "btn btn-default btnUpdate", 'id' => $erd[$name[0]], 'onclick' => "javascript: return formupdate('" . $erd[$name[0]] . "','" . $erd[$name[1]]
                                    . "','" . $erd[$name[2]] . "','" . $erd[$name[3]] . "','" . $erd[$name[4]] . "','" . $erd[$name[5]] . "','" . $erd[$name[6]] . "','" . $erd[$name[7]] . "','" . $erd[$name[8]] . "','" . $erd[$name[9]] . "','" . $erd[$name[10]] . "','" . $erd[$name[11]] . "','" . $erd[$name[62]]
                                    . "','" . $erd[$name[24]] . "','" . $erd[$name[25]] . "','" . $erd[$name[26]] . "','" . $erd[$name[19]] . "','" . $erd[$name[18]] . "','" . $erd[$name[20]] . "','" . $erd[$name[21]] . "','" . $erd[$name[23]] . "','" . $erd[$name[22]] . "','" . $erd[$name[27]]
                                    . "','" . $erd[$name[28]] . "','" . $erd[$name[29]] . "','" . $erd[$name[30]] . "','" . $erd[$name[31]] . "','" . $erd[$name[33]] . "','" . $erd[$name[34]] . "','" . $erd[$name[35]] . "','" . $erd[$name[32]] . "','" . $erd[$name[12]] . "','" . $erd[$name[36]]
                                    . "','" . $erd[$name[42]] . "','" . $erd[$name[43]] . "','" . $erd[$name[44]] . "','" . $erd[$name[45]] . "','" . $erd[$name[46]] . "','" . $erd[$name[47]] . "','" . $erd[$name[59]] . "','" . $erd[$name[60]] . "','" . $erd[$name[61]]
                                    . "','" . $erd[$name[53]] . "','" . $erd[$name[56]] . "','" . $erd[$name[57]] . "','" . $erd[$name[58]]
                                    . "','" . $erd[$name[64]] . "','" . $erd[$name[48]] . "','" . $erd[$name[49]] . "','" . $erd[$name[50]] . "','" . $erd[$name[51]] . "','" . $erd[$name[52]] . "');"))
                                . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('class' => "btn btn-default", 'onclick' => 'javascript: return formdelete(' . $erd['evalrule_id'] . ')'))
                                . "</td>"
                                . "</tr>";
                            }
                            ?>
                        <tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>