<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>
<!--<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });    
</script>-->
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $("#effective_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
//        $('#tblEvalRule,tblEvalSubRule').dataTable({
//            "iDisplayLength": 5,
//            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
//        });
//        $('.hidden').show();
        $("#constructionrow,#depreciationrow,#roadvicinityrow,#userdependancy1row,#userdependancy2row,#maxpararow").hide();

        getSubruleList('<?php echo $ruleid; ?>');
        $("#usage_main_catg_id").val("");
        $("#usage_main_catg_id").val("<?php echo $mcatg; ?>");
        $("#usage_sub_catg_id").val("<?php echo $scatg; ?>");
        $("#usage_sub_sub_catg_id").val("<?php echo $sscatg; ?>");
        // }
        var host = "<?php echo $this->webroot; ?>";
        $(":checkbox").attr("checked", false);
        $("#c1,#f1,#c2,#f2,#c3,#f3,#c4,#f4,#c5,#f5,#maxformula,#rf1,#rf2").val('');

        $('#usage_main_catg_id').change(function () {
            getSubCat($("#usage_main_catg_id").val(), '');
        });

        $('#usage_sub_catg_id').change(function () {
            getSubSubCat($("#usage_main_catg_id").val(), $("#usage_sub_catg_id").val(), '');
        });

//        $("#construction_type").change(function () {
//            var str = $("#usage_sub_sub_catg_id option:selected").text() + " " + $("option:selected", this).text();
//            $("#rule_desc_en").val($.trim(str.replace(/\--Select--/g, ' ')));
//        });
        if ($("#actionid").val()) {
            getssdata($("#usage_main_catg_id").val(), $("#usage_sub_catg_id").val(), $("#usage_sub_sub_catg_id").val());
        }

        $('#usage_sub_sub_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id").val();
            var usage_sub_catg_id = $("#usage_sub_catg_id").val();
            getssdata(usage_main_catg_id, usage_sub_catg_id, $(this).val());
        });

        var txtfield = '';
        $('input.cndpr').focus(function () {
            txtfield = $(this).attr('id');
        });

        rateLocationchangeEvent($('input:radio[name="data[frmevalrule][dependency_flag]"]:checked').val());
        $('input:radio[name="data[frmevalrule][dependency_flag]"]').change(function () {
            rateLocationchangeEvent($(this).val());
        });

        //$("#rateCmpRow").hide();
        ratecmpchangeEvent($('input:radio[name="data[frmevalrule][rate_compare_flag]"]:checked').val());
        $('input:radio[name="data[frmevalrule][rate_compare_flag]"]').change(function () {
            ratecmpchangeEvent($(this).val());
        });

        $("#cmp_usage_main_catg_id").change(function () {
            cmpSubCat($(this).val(), '');
        });
        $("#cmp_usage_sub_catg_id").change(function () {
            cmpSubSubCat($("#cmp_usage_main_catg_id option:selected").val(), $(this).val(), '');
        });
        $('input:radio[name="data[frmevalrule][max_value_condition_flag]"]').change(function () {
            if ($(this).val() == 'Y') {
                $("#maxpararow").show();
            }
            else {
                $("#maxpararow").hide();
            }
        });
        $('input:radio[name="data[frmevalrule][subrule_flag]"]').change(function () {
            subruleChangeEvent($(this).val());
        });
        $("#maxparaid,#maxoptorid").change(function () {
            var cvalue = $("#maxformula").val();
            $("#maxformula").val(cvalue + $(this).val());
            $("#maxparaid,#maxoptorid").val('');
        });

        $("#parameter_id,#operator_id").change(function () {
            if (txtfield == '') {
                txtfield = 'f1';

            }
            var rate = $(this).val().trim();
            var cvalue = $("#" + txtfield).val().trim();
            $("#" + txtfield).val(cvalue + rate);
            $("#parameter_id,#operator_id").val('');
        });

        $("#btnCancel").click(function () {
            $(":checkbox").attr("checked", false);
            $("#actionid").val("");
        });

        $("#btnSave").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var action = $("#actionid").val();
            if (action != 'U' && action != 'SRU' && action != 'SRD' && action != 'SRA') {
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
            }
            else {
                $("#hsrflg").val($('input:radio[name="data[frmevalrule][subrule_flag]"] option:selected').val());
                $('#evalrule').submit();
            }
        });

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
            $.getJSON(host + 'getSubDivision', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: ''}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#subdivision_id option").remove();
                $("#subdivision_id").append(sc);

            });
        }

        function getTaluka(div, dist, subdiv) {
            $.getJSON(host + 'getTaluka', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);

            });
        }
        function getCircle(div, dist, subdiv, taluka) {
            $.getJSON(host + 'getCircle', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv, taluka_id: taluka}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#circle_id option").remove();
                $("#circle_id").append(sc);
            });
        }
        function getVillage(div, dist, subdiv, taluka, circle) {
            $.getJSON(host + 'getVillage', {state_id: "<?php echo $stateid; ?>", division_id: div, state_id: dist, subdivision_id: subdiv, taluka_id: taluka, circle_id: circle}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
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
            }
            else {
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
            }
            else {

                getCircle(div, dist, subdiv, tal);
            }
            setlandtype($("#taluka_id").val(), '');
        });

        $("#landtype_id").change(function () {
            if ($(this).val() == 1) {
                $(".ulb_type").show();
                $(".corp_id").show();

            }
            else {
                $(".ulb_type").hide();
                $("#ulb_type_id").val("");
                $(".corp_id").hide();
                $("#corp_id").val("");
            }
        });
        // Governing Body
        $('#circle_id').change(function () {
            var ulb = $("#circle_id option:selected").val();
            $.getJSON('getulb', {ulb: ulb}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#ulb_type_id option").remove();
                $("#ulb_type_id").append(sc);
            });
        });

        //Corporation List
        $('#ulb_type_id').change(function () {
            var ulb = $("#ulb_type_id option:selected").val();
            $.getJSON('getcorp', {ulb: ulb}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            });
        });

        if ($("#hdnruleid").val() != '') {
            getSubruleList($("#hdnruleid").val());
            return false;
        }
    });

    function getSubCat(maincat, subcatg) {
        $.getJSON(host + "getsubcategory", {usage_main_catg_id: maincat}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#usage_sub_catg_id option").remove();
            $("#usage_sub_catg_id").append(sc);
            $("#usage_sub_catg_id").val(subcatg);
        });
    }

    function getSubSubCat(maincat, subcat, subsubcatg) {
        $.getJSON(host + "getsubsubcategory", {usage_main_catg_id: maincat, usage_sub_catg_id: subcat}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#usage_sub_sub_catg_id option").remove();
            $("#usage_sub_sub_catg_id").append(sc);
            $("#usage_sub_sub_catg_id").val(subsubcatg);
        });
    }
//---------------------------------------Update-----------------------------------------------------------------------------
//-----------------------------------------Get Div,District,Sub Div, Taluka-----------------------------
    var host = "<?php echo $this->webroot; ?>";
    var dist = '<?php echo $configure[0][0]['is_dist']; ?>';
    var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
    var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
    var circle = '<?php echo $configure[0][0]['is_block']; ?>';
    function setdistlist(divid, distid) {
        $.getJSON(host + 'getDistrict', {state_id: "<?php echo $stateid; ?>", division_id: divid}, function (udistdata)
        {
            var sc = '<option value="">select</option>';
            $.each(udistdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#state_id option").remove();
            $("#state_id").append(sc);
            $("#state_id").val(distid);
        });
    }

    function setsubdivlist(divid, distid, subdivid) {
        $.getJSON(host + 'getSubDivision', {state_id: "<?php echo $stateid; ?>", division_id: divid, state_id: distid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#subdivision_id option").remove();
            $("#subdivision_id").append(sc);
            $("#subdivision_id").val(subdivid);

        });
    }

    function settallist(divid, distid, subdivid, talukaid) {
        $.getJSON(host + 'getTaluka', {state_id: "<?php echo $stateid; ?>", division_id: divid, state_id: distid, subdivision_id: subdivid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
            $("#taluka_id").val(talukaid);

        });
    }

    function setlandtype(talukaid, landtypeid) {
        $.getJSON(host + 'getLandtype', {tal: talukaid}, function (ltdata)
        {
            var sc = '<option value="">--select--</option>';
            $.each(ltdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#landtype_id").prop("disabled", false);
            $("#landtype_id option").remove();
            $("#landtype_id").append(sc);
            $("#landtype_id").val(landtypeid);
        });
    }

    function setcircle(talukaid, circleid, ulbtypeid) {
        $.getJSON('getcircle', {tal: talukaid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#circle_id option").remove();
            $("#circle_id").append(sc);
            $("#circle_id").append(circleid);
        });
    }

    function setvillagelist(divid, distid, subdivid, talukaid, circleid, villageid) {
        $.getJSON(host + 'getVillage', {state_id: "<?php echo $stateid; ?>", division_id: divid, state_id: distid, subdivision_id: subdivid, taluka_id: talukaid, circle_id: circleid}, function (villagelistdata)
        {
            var sc = '<option value="">select</option>';
            $.each(villagelistdata, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#village_id option").remove();
            $("#village_id").append(sc);
            $("#village_id").val(villageid);
        });
    }

    function setulblist(ulbtypeid, ulbid) {
        $.getJSON('getcorp', {ulb: ulbtypeid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#corp_id option").remove();
            $("#corp_id").append(sc);
            $("#corp_id").val(ulbid);
        });
    }

    function updatesubrule(rid, rsid) {
        $("#flashMessage").html("");
        subruleChangeEvent('Y');
        $("#hdnruleid").val(rid);
        window.scrollTo(0, 450);
        $("#selectvillagemapping").hide();
        $("#hdnruleid").val(rid);
        $("#subruleid").val(rsid);
        $.getJSON(host + 'getSubrule', {ruleid: rid, subruleid: rsid}, function (sbrldata) {
            $("#c1").val(sbrldata['evalsubrule_cond1']);
            $("#f1").val(sbrldata['evalsubrule_formula1']);
            $("#c2").val(sbrldata['evalsubrule_cond2']);
            $("#f2").val(sbrldata['evalsubrule_formula2']);
            $("#c3").val(sbrldata['evalsubrule_cond3']);
            $("#f3").val(sbrldata['evalsubrule_formula3']);
            $("#c4").val(sbrldata['evalsubrule_cond4']);
            $("#f4").val(sbrldata['evalsubrule_formula4']);
            $("#c5").val(sbrldata['evalsubrule_cond5']);

            $("#dvr1").val(sbrldata['derived_rate1']);
            $("#dvr2").val(sbrldata['derived_rate2']);
            $("#dvr3").val(sbrldata['derived_rate3']);
            $("#dvr4").val(sbrldata['derived_rate4']);
            $("#dvr5").val(sbrldata['derived_rate5']);
            //$("#construction_type").val(sbrldata['construction_type_id']);
            //$("#depreciation").val(sbrldata['depreciation_id']);
            $("#roadvicinityid").val(sbrldata['road_vicinity_id']);
            $("#userdefineddependency1").val(sbrldata['user_defined_dependency1_id']);
            $("#userdefineddependency2").val(sbrldata['user_defined_dependency2_id']);
            $("#r1").val(sbrldata['rate1']);
            $("#r2").val(sbrldata['rate2']);
            $("#outputitemid").val(sbrldata['output_item_id']);
            $("#out_item_order").val(sbrldata['out_item_order']);
            $('input:radio[name="data[frmevalrule][max_value_condition_flag]"]').attr('checked', false);
            $('input:radio[name="data[frmevalrule][max_value_condition_flag]"][value=' + sbrldata['max_value_condition_flag'] + ']').attr('checked', true);
            if (sbrldata['max_value_condition_flag'] === 'Y') {
                $("#maxpararow").show();
                $("#maxformula").val(sbrldata['max_value_formula']);
            }
            else {
                $("#maxpararow").hide();
                $("#maxformula").val('');
            }
            $("#actionid").val('SRU');
        });

        return false;
    }

    function deletesubrule(rid, rsid) {
        $("#hdnruleid").val(rid);
        $("#subruleid").val(rsid);
        var conf = confirm('Are You Sure to delete this subrule');
        if (!conf) {
            return false;
        } else {
            $("#actionid").val('SRD');
        }
    }


    function formupdate(ruleid, rde, c1, f1, c2, f2, c3, f3, c4, f4, c5, f5, roadvicinity, udd1, udd2, mxflg, mxformula, subrlflag, rdl, dil, dfid, divid, distid, subdivid, talukaid, circleid, ulbtypeid, ulbid, villageid, landtypeid, stateid, deflag, ratecmpflag, cmpmainc_id, cmpsubc_id, cmpsubsubc_id, rule_ref_no, ad_rate_flag, dvr1, dvr2, dvr3, dvr4, dvr5) {
        $('input:radio[name="data[frmevalrule][rate_compare_flag]"][value=' + ratecmpflag + ']').attr('checked', true);
        $('input:radio[name="data[frmevalrule][dependency_flag]"][value=' + deflag + ']').attr('checked', true);
        $('input:radio[name="data[frmevalrule][additional_rate_flag]"][value=' + ad_rate_flag + ']').attr('checked', true);
        rateLocationchangeEvent(deflag);
        ratecmpchangeEvent(ratecmpflag);

        $("#flashMessage").html("");
        $("#rule_ref_no").val(rule_ref_no);
        $("#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_sub_catg_id,#cmp_usage_sub_sub_catg_id").empty();
        $("#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_sub_catg_id,#cmp_usage_sub_sub_catg_id").append('<option value="">--select--</option>');
        $("#usage_main_catg_id,#usage_sub_catg_id,#usage_sub_sub_catg_id,#cmp_usage_main_catg_id").val('');
        //        $("#selectvillagemapping").show();

        if (ratecmpflag == 'Y') {
            $("#cmp_usage_main_catg_id").val(cmpmainc_id);
            cmpSubCat(cmpmainc_id, cmpsubc_id);
            cmpSubSubCat(cmpmainc_id, cmpsubc_id, cmpsubsubc_id);
        }

        if (deflag == 'Y') {
            $("#state_id").val(stateid);
        }
        else {
            $("#state_id").val('');
        }
        if (landtypeid == 1) {
            $(".ulb_type").show();
            $(".corp_id").show();

        }
        else {
            $(".ulb_type").hide();
            $("#ulb_type_id").val("");
            $(".corp_id").hide();
            $("#corp_id").val("");
        }
        var host = "<?php echo $this->webroot; ?>";
        var mcatg;
        var subcatg;
        var subsubcatg;
        $.getJSON(host + "getcategoryids", {evalruleid: ruleid}, function (maindata)
        {
            mcatg = maindata['usage_main_catg_id'];
            subcatg = maindata['usage_sub_catg_id'];
            subsubcatg = maindata['usage_sub_sub_catg_id'];
            $("#usage_main_catg_id").val(mcatg);
            getSubCat(mcatg, subcatg);
            getSubSubCat(mcatg, subcatg, subsubcatg);
            getssdata(mcatg, subcatg, subsubcatg);
        });

        //-----------------------------------For State,Div,dist,......
        var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
        var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
        var circle = '<?php echo $configure[0][0]['is_block']; ?>';
        if (deflag == 'Y') {
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
            }
            else {
                setvillagelist(divid, distid, subdivid, talukaid, circleid, villageid);
            }
            $("#ulb_type_id").val(ulbtypeid);
            setulblist(ulbtypeid, ulbid);
        }
        else {
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
                $("input[type=checkbox][value=" + temp[i] + "]").attr("checked", "true");
            }
        }
        $('input:radio[name="data[frmevalrule][max_value_condition_flag]"][value=' + mxflg + ']').attr('checked', true);
        if (mxflg === 'Y') {
            $("#maxpararow").show();
            $("#maxformula").val(mxformula);
        }
        else {
            $("#maxpararow").hide();
            $("#maxformula").val('');
        }
        subruleChangeEvent(subrlflag);
        $('input:radio[name="data[frmevalrule][subrule_flag]"][value="N"]').attr('checked', true);

        $("#rule_desc_ll").val(rdl);

        if (roadvicinity) {
            $("#roadvicinityrow").show();
            $("#roadvicinityid").val(roadvicinity);
        }
        else {
            $("#roadvicinityid").val(0);
            $("#roadvicinityrow").hide();
        }

        if (udd1) {
            $("#userdependancy1row").show();
            $("#userdefineddependency1").val(udd1);
        }
        else {
            $("#userdefineddependency1").val(0);
            $("#userdependancy1row").hide();
        }
        if (udd2) {
            $("#userdependancy2row").show();
            $("#userdefineddependency2").val(udd2);
        }
        else {
            $("#userdefineddependency2").val(0);
            $("#userdependancy2row").hide();
        }
        if (subrlflag == 'Y') {
            getSubruleList(ruleid);
        }
        else {
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
        $("#dvr1").val(dvr1);
        $("#dvr2").val(dvr2);
        $("#dvr3").val(dvr3);
        $("#dvr4").val(dvr4);
        $("#dvr5").val(dvr5);
        $("#outputitemid").val(dfid);
        $("#actionid").val('U');
        window.scrollTo(500, 200);
        return false;
    }

    function getssdata(usage_main_catg_id, usage_sub_catg_id, usage_sub_sub_catg_id) {

        $.getJSON(host + "getparamlist", {usage_main_catg_id: usage_main_catg_id, usage_sub_catg_id: usage_sub_catg_id, usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
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
        });
        $.getJSON(host + "getcdrflags", {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
        {
            if (data['road_vicinity_flag'] == 'Y') {
                $("#roadvicinityrow").show();
            }
            else {
                $("#roadvicinity").val(0);
                $("#roadvicinityrow").hide();
            }

            if (data['user_defined_dependency1_flag'] == 'Y') {
                $("#userdependancy1row").show();
            }
            else {
                $("#userdefineddependency1").val(0);
                $("#userdependancy1row").hide();
            }
            if (data['user_defined_dependency2_flag'] == 'Y') {
                $("#userdependancy2row").show();
            }
            else {
                $("#userdefineddependency2").val(0);
                $("#userdependancy2row").hide();
            }
        });

        $.getJSON(host + "getsubsubruledesc", {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
        {
            if ($("#actionid").val() != 'U') {
                $("#rule_desc_en").val(data['usage_sub_sub_catg_desc_en']);
                $("#rule_desc_ll").val(data['usage_sub_sub_catg_desc_ll']);
            }
        });

    }

    function getSubruleList(ruleid) {
        $.getJSON(host + 'getSubrule', {ruleid: ruleid, subruleid: '0'}, function (data)
        {
            var sc = "<h3 align=center> Sub Rule Detail</h3>";
            sc += '<table  id="tblEvalSubRule" class="table table-striped table-bordered table-hover">';
            sc += "<thead><tr>";
            sc += "<td align=center><b>Sr. No.</b></td>";
            sc += "<td align=center><b>Id</b></td>";
            sc += "<td align=center><b>Condition 1</b></td>";
            sc += "<td align=center><b>Formula 1</b></td>";
            sc += "<td align=center><b>Condition 2</b></td>";
            sc += "<td align=center><b>Formula 2</b></td>";
            sc += "<td align=center><b>Is Max</b></td>";
            sc += "<td align=center><b>Output Item</b></td>";
            sc += "<td align=center><b>Order</b></td>";
            sc += "<td align=center><b>Action</b></td>";
            sc += "</tr></thead><tbody>";
            $i = 1;
            $.each(data, function (key, val) {
                sc += "<tr>";
                sc += "<td align=center width=5%><b>" + $i++ + "</b></td>";
                sc += "<td align=center width=5%>" + val[0]['subrule_id'] + "</td>";
                sc += "<td>" + val[0]['evalsubrule_cond1'] + "</td>";
                sc += "<td>" + val[0]['evalsubrule_formula1'] + "</td>";
                sc += "<td>" + val[0]['evalsubrule_cond2'] + "</td>";
                sc += "<td>" + val[0]['evalsubrule_formula2'] + "</td>";
                sc += "<td align=center width=5%>" + val[0]['max_value_condition_flag'] + "</td>";
                sc += "<td>" + val[0]['usage_param_desc_en'] + "</td>";
                sc += "<td align=center>" + val[0]['out_item_order'] + "</td>";
                sc += "<td align=center width=8%>" + "<button class='btn btn-default' onClick='return updatesubrule(" + val[0]['evalrule_id'] + "," + val[0]['subrule_id'] + ");'><span class='glyphicon glyphicon-pencil'></span> </button>";
                sc += "<button class='btn btn-default' onClick='return deletesubrule(" + val[0]['evalrule_id'] + "," + val[0]['subrule_id'] + ");'><span class='glyphicon glyphicon-remove'></span> </button>";
                sc += "</td></tr>";
            });
            sc += '</tbody></table>';
            $("#subrulelistdiv").text('');
            $("#subrulelistdiv").append(sc);
        });
    }

    function rateLocationchangeEvent(rate_Loc_flag) {
        if (rate_Loc_flag == 'Y') {
            $("#selectvillagemapping").show();
        }
        else {
            $("#selectvillagemapping").hide();
        }
    }
    function ratecmpchangeEvent(rate_flag) {
        if (rate_flag == 'Y') {
            $("#rateCmpRow").show();
        }
        else {
            $("#rateCmpRow").hide();
        }
    }

    function cmpSubCat(maincat, subcatg) {
        $.getJSON(host + "getsubcategory", {usage_main_catg_id: maincat}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#cmp_usage_sub_catg_id option").remove();
            $("#cmp_usage_sub_catg_id").append(sc);
            $("#cmp_usage_sub_catg_id").val(subcatg);
        });
    }

    function cmpSubSubCat(maincat, subcat, subsubcatg) {
        $.getJSON(host + "getsubsubcategory", {usage_main_catg_id: maincat, usage_sub_catg_id: subcat}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#cmp_usage_sub_sub_catg_id option").remove();
            $("#cmp_usage_sub_sub_catg_id").append(sc);
            $("#cmp_usage_sub_sub_catg_id").val(subsubcatg);
        });

    }

    function subruleChangeEvent(subruleflag) {
        $("#flashMessage").html("");
        if (subruleflag == 'Y') {
            if ($("#hdnruleid").val() != '') {
                $("#actionid").val('SRA');
                $.getJSON(host + 'getMaxOrder', {ruleid: $("#hdnruleid").val()}, function (outdata)
                {
                    $("#out_item_order").val(outdata);
                });
            } else {
                $("#out_item_order").val(1);
            }

            $("#subruleid").val('');
        }
        else {
            $("#out_item_order").val(1);
        }
    }
    function formdelete(id) {
        $("#hdnruleid").val(id);
        $("#actionid").val('D');
        var conf = confirm('Are You Sure to Delete this Rule');
        if (!conf) {
            return false;
        }
    }
</script>

<?php
echo $this->Form->create('frmevalrule', array('id' => 'evalrule', 'class' => 'form-vertical'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-success">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblevalrule'); ?></b></div>
            <div class="panel-body">
                <div style="border: 1px black solid">
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="financial Year" class="control-label col-sm-2"><?php echo __('lblfineyer'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[37], array('options' => $finyearList, 'multiple' => false, 'id' => 'fin_year', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="Effective Date" class="control-label col-sm-2"><?php echo __('lbleffedate'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[38], array('id' => 'effective_date', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="refno" class="control-label col-sm-2"><?php echo __('lblReferenceNo'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[46], array('id' => 'rule_ref_no', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblusamaincat'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('maincategory', array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="usage_sub_catg_id" class="control-label col-sm-2"><?php echo __('lblUsagesubcategoryhead'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('subcategory', array('type' => 'select', 'options' => $scatglist, 'empty' => '--Select Option--', 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="usage_sub_sub_catg_id" class="control-label col-sm-2"><?php echo __('lblsubsubcategorydesc'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('subsbucategory', array('type' => 'select', 'options' => $sscatglist, 'empty' => '--Select Option--', 'id' => 'usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="rule desc en" class="control-label col-sm-2"><?php echo __('lblevalrule'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[1], array('id' => 'rule_desc_en', 'label' => false, 'class' => 'form-control input-sm')); ?></div>            
                                <label for="rule desc local" class="control-label col-sm-2"><?php echo __('lblevalrulell'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[21], array('id' => 'rule_desc_ll', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Rate Compare Flag" class="control-label col-sm-4"><?php echo __('lblRateCompareRequired'); ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[42], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                                <label for="Additional Rate Compare Flag" class="control-label col-sm-4"><?php echo __('lblAdditionRateRequired'); ?> </label>            
                                <div class="col-sm-2"> <?php echo $this->Form->input($name[47], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="rateCmpRow">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblCmpUsamaincat'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[43], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'cmp_usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="usage_sub_catg_id" class="control-label col-sm-2"><?php echo __('lblCmpUsagesubcategoryhead'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[44], array('type' => 'select', 'options' => $scatglist, 'empty' => '--Select Option--', 'id' => 'cmp_usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <label for="usage_sub_sub_catg_id" class="control-label col-sm-2"><?php echo __('lblCmpSubsubcategorydesc'); ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input($name[45], array('type' => 'select', 'options' => $sscatglist, 'empty' => '--Select Option--', 'id' => 'cmp_usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Location Dependancy Flag" class="control-label col-sm-4"><?php echo __('lblRateLocationDependancyRequired'); ?> </label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[36], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ratecmpFlag')); ?></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="selectvillagemapping" class="table-responsive">
                        <div class="col-lg-12">
                            <table id="tablevillagemapping" class="table table-striped table-bordered table-hover">  
                                <thead >  
                                    <tr>
                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmstate'); ?></td>
                                        <?php for ($i = 0; $i < count($configure); $i++) { ?>                                            
                                            <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblDivision'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblDistrict'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblSubDivision'); ?> </td><?php } ?>
                                            <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmtaluka'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblCircle'); ?> </td><?php } ?>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblLandType'); ?> </td>                                          
                                            <td style="text-align: center; font-weight:bold;" class="ulb_type"><?php echo __('lblCorporationClass'); ?> </td>
                                            <td style="text-align: center; font-weight:bold;" class="corp_id"><?php echo __('lblcorporation'); ?></td>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblVillage'); ?> </td>                                          
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
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Sub Rule" class="control-label col-sm-4"><?php echo __('lblmultiplerate'); ?></label>            
                                <div class="col-sm-3"><?php echo $this->Form->input($name[20], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'subruleflag')); ?></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="constructionrow">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="construction type" class="control-label col-sm-3"><?php echo __('lblconstuctiontypehead'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[14], array('options' => $constructiontype, 'empty' => '--Select--', 'id' => 'construction_type', 'label' => false, 'class' => 'form-control input-sm')); ?></div>           
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="depreciationrow">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="depreciation" class="control-label col-sm-3"><?php echo __('lbldepreciation'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[15], array('options' => $depreciation, 'empty' => '--Select--', 'id' => 'depreciation', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="roadvicinityrow">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="construction type" class="control-label col-sm-3"><?php echo __('lblroadvicinity'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[24], array('options' => $roadvicinitylist, 'empty' => '--Select--', 'id' => 'roadvicinityid', 'label' => false, 'class' => 'form-control input-sm')); ?></div>           
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="userdependancy1row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="user defined dependancy 1" class="control-label col-sm-3"><?php echo __('userdefineddependency1'); ?></label>                                
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[25], array('options' => $userdd1list, 'empty' => '--Select--', 'id' => 'userdefineddependency1', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="userdependancy2row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="user defined dependancy 2" class="control-label col-sm-3"><?php echo __('userdefineddependency2'); ?></label>
                                <div class="col-sm-4" ><?php echo $this->Form->input($name[26], array('options' => $userdd2list, 'empty' => '--Select--', 'id' => 'userdefineddependency2', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                </div>
                <div style="border: 1px black solid">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="rule desc en" class="control-label col-sm-3"><?php echo __('lbloutputitem'); ?></label>                            
                                            <div class="col-sm-5" ><?php echo $this->Form->input($name[22], array('id' => 'outputitemid', 'options' => $outitemlist, 'empty' => '--Select Option--', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                                            <label for="Output Display Order" class="control-label col-sm-2"><?php echo __('lblDisplayOrder'); ?></label>                            
                                            <div class="col-sm-2" ><?php echo $this->Form->input('out_item_order', array('id' => 'out_item_order', 'type' => 'Number', 'min' => '1', 'max' => '20', 'label' => false, 'class' => 'form-control input-sm')); ?></div>                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" style="background-color: lightblue">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="Parameter Desc" class="control-label col-sm-3" align="center"><?php echo __('lblitemdesc'); ?></label>                                        
                                            <div class="col-sm-9" style="height: 100px;overflow-y: scroll;padding-left: 30px; border: 2px #00529B ridge" id="paradescid"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" hidden="true">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Dependant Upon" class="control-label col-sm-2" ><?php echo __('lbldependantupon'); ?></label>                                        
                                <div class="col-sm-4" style="height: 100px;overflow-y: scroll;padding-left: 30px; border: 2px #00529B ridge"> <?php echo $this->Form->input($name[23], array('type' => 'select', 'options' => $dependancylist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'dulist')); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="max value checking " class="control-label col-sm-2"><?php echo __('maxvaluecheck'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input($name[19], array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'maxflag')); ?></div>                            
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row" id="maxpararow">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <label for="Select Parameter" class="control-label col-sm-3"><?php echo __('lblselectmaxpara'); ?></label>            
                                    <div class="col-sm-3"><?php echo $this->Form->input('maxvalueparameterlist', array('type' => 'select', 'options' => $maxvalueparameterslist, 'empty' => '-select-', 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'maxparaid')); ?></div>
                                    <label for="Select Operator" class="control-label col-sm-3"><?php echo __('lblselectoperator'); ?></label>                        
                                    <div class="col-sm-3"><?php echo $this->Form->input('operatorsignmax', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'maxoptorid')); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <label for="Select Parameter" class="control-label col-sm-4"><?php echo __('lblmaxvalueformula'); ?></label>                                        
                                    <div class="col-sm-8"><?php echo $this->Form->input($name[18], array('id' => 'maxformula', 'placeholder' => 'Max Value Formula', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Select Parameter" class="control-label col-sm-2"><?php echo __('lblselectpara'); ?></label>            
                                <div class="col-sm-4"><?php echo $this->Form->input('parameterlist', array('type' => 'select', 'empty' => '-select-', 'multiple' => false, 'label' => false, 'class' => 'form-control input-sm', 'id' => 'parameter_id')); ?></div>
                                <label for="Select Operator" class="control-label col-sm-2"><?php echo __('lblselectoperator'); ?></label>                        
                                <div class="col-sm-4"><?php echo $this->Form->input('operatorsign', array('type' => 'select', 'empty' => '-select-', 'options' => $operators, 'multiple' => false, 'class' => 'form-control input-sm', 'label' => false, 'id' => 'operator_id')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
    <!--                                <label for="Select Parameter" class="control-label col-sm-4 onlysubrule"><?php //echo __('lblratehead');                                                                                                                                                                                                   ?></label>            -->
                                <div class="col-sm-2 onlyforrule"></div>
                                <label for="Select Parameter" class="control-label col-sm-5"><?php echo __('lblcondition'); ?></label>            
                                <label for="Select Parameter" class="control-label col-sm-3 hidden"><?php echo __('lblDerivedRate'); ?></label> 
                                <label for="Select Parameter" class="control-label col-sm-5"><?php echo __('lblformula'); ?></label>                                        

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-sm-2 onlyforrule"></div>
        <!--                                    <div class="col-sm-4 onlysubrule"><?php //echo $this->Form->input('r1', array('id' => 'rf1', 'placeholder' => 'Rate 1', 'label' => false, 'class' => 'cndpr form-control input-sm'));                                                                                                                                                                                                    ?></div>-->
                                            <div class="col-sm-3"><?php echo $this->Form->input($name[2], array('id' => 'c1', 'placeholder' => 'Condition 1', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-3 " ><?php echo $this->Form->input($name[48], array('id' => 'dvr1', 'placeholder' => 'Derived Rate 1', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                            <div class="col-sm-3"><?php echo $this->Form->input($name[3], array('id' => 'f1', 'placeholder' => 'Formula 1', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-sm-2 onlyforrule"></div>
        <!--                                    <div class="col-sm-4 onlysubrule"><?php //echo $this->Form->input('r2', array('id' => 'rf2', 'placeholder' => 'Rate 2', 'label' => false, 'class' => 'cndpr form-control input-sm'));                                                                                                                                                                                                    ?></div>-->
                                            <div class="col-sm-3"><?php echo $this->Form->input($name[4], array('id' => 'c2', 'placeholder' => 'Condition 2', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                            <div class="col-sm-3 "><?php echo $this->Form->input($name[49], array('id' => 'dvr2', 'placeholder' => 'Derived Rate 2', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                            <div class="col-sm-3"><?php echo $this->Form->input($name[5], array('id' => 'f2', 'placeholder' => 'Formula 2', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                <div id="onlyrulerow">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="col-sm-2 onlyforrule"></div>
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[6], array('id' => 'c3', 'placeholder' => 'Condition 3', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                                <div class="col-sm-3 "><?php echo $this->Form->input($name[50], array('id' => 'dvr3', 'placeholder' => 'Derived Rate 3', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[7], array('id' => 'f3', 'placeholder' => 'Formula 3', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="col-sm-2 onlyforrule"></div>
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[8], array('id' => 'c4', 'placeholder' => 'Condition 4', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                                <div class="col-sm-3 "><?php echo $this->Form->input($name[51], array('id' => 'dvr4', 'placeholder' => 'Derived Rate 4', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[9], array('id' => 'f4', 'placeholder' => 'Formula 4', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="col-sm-2 onlyforrule"></div>
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[10], array('id' => 'c5', 'placeholder' => 'Condition 5', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>
                                                <div class="col-sm-3 "><?php echo $this->Form->input($name[52], array('id' => 'dvr5', 'placeholder' => 'Derived Rate 5', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       
                                                <div class="col-sm-3"><?php echo $this->Form->input($name[11], array('id' => 'f5', 'placeholder' => 'Formula 5', 'label' => false, 'class' => 'cndpr form-control input-sm')); ?></div>                                                       

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                                <div class="row" style="text-align: center">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="hidden" name="hid" id="hdnruleid" value="<?php echo $ruleid; ?>">
                                            <input type="hidden" name="hsrflg" id="hsrflg" value="<?php echo $hsrflg; ?>">
                                            <input type="hidden" name="action" id="actionid" value="<?php echo $hfaction; ?>">
                                            <?php
                                            echo $this->Form->button(__('btnsave'), array('id' => 'btnSave', 'class' => 'btn btn-primary')) . "&nbsp;&nbsp;";
                                            echo $this->Form->reset(__('lblreset'), array('id' => 'btnCancel', 'class' => 'btn btn-primary')) . "&nbsp;&nbsp;";
                                            echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-primary'));
                                            echo $this->Form->input('subruleid', array('id' => 'subruleid', 'type' => 'hidden'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>

                                <div class="row" id="subrulelistdiv" widht="85%">
<!--                                    <div class="col-sm-8">

                                    </div>-->
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblevalrule'); ?></b></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table  id="tblEvalRule" width="99.5%" class="table table-striped table-bordered table-condensed">
                                        <thead >                        
                                            <?php
                                            echo "<tr>"
                                            . "<td align=center width=3%>" . __('lblsrno') . "</td>"
                                            . "<td align=center>" . __('Id') . "</td>"
                                            . "<td align=center>" . __('lblReferenceNo') . "</td>"
                                            . "<td align=center>" . __('lblevalrule') . "</td>"
                                            . "<td align=center width=8%>" . __('maxvaluecheck') . "</td>"
                                            . "<td align=center>" . __('lblsubrule') . "</td>"
                                            . "<td align=center width=10%>" . __('lblaction') . "</td>"
                                            . "</tr>";
                                            ?>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno = 1;
                                            foreach ($evalruledata as $erd) {
                                                $erd = $erd[0];
                                                echo "<tr>"
                                                . "<td align=center>" . $srno++ . "</td>"
                                                . "<td align=center>" . $erd['evalrule_id'] . "</td>"
                                                . "<td align=center>" . $erd['reference_no'] . "</td>"
                                                . "<td>" . $erd['evalrule_desc_' . $lang] . "</td>"
                                                . "<td align=center>" . $erd['max_value_condition_flag'] . "</td>"
                                                . "<td align=center>" . $erd['subrule_flag'] . "</td>";
                                                echo "<td style='text-align: center;'>"
                                                . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "btn btn-default btnUpdate", 'id' => $erd[$name[0]], 'onclick' => "javascript: return formupdate('" . $erd[$name[0]] . "','" . $erd[$name[1]] . "','" . $erd[$name[2]] . "','" . $erd[$name[3]] . "','" . $erd[$name[4]] . "','" . $erd[$name[5]] . "','" . $erd[$name[6]] . "','" . $erd[$name[7]] . "','" . $erd[$name[8]] . "','" . $erd[$name[9]] . "','" . $erd[$name[10]] . "','" . $erd[$name[11]] . "','" . $erd[$name[24]] . "','" . $erd[$name[25]] . "','" . $erd[$name[26]] . "','" . $erd[$name[19]] . "','" . $erd[$name[18]] . "','" . $erd[$name[20]] . "','" . $erd[$name[21]] . "','" . $erd[$name[23]] . "','" . $erd[$name[22]] . "','" . $erd[$name[27]] . "','" . $erd[$name[28]] . "','" . $erd[$name[29]] . "','" . $erd[$name[30]] . "','" . $erd[$name[31]] . "','" . $erd[$name[33]] . "','" . $erd[$name[34]] . "','" . $erd[$name[35]] . "','" . $erd[$name[32]] . "','" . $erd[$name[12]] . "','" . $erd[$name[36]] . "','" . $erd[$name[42]] . "','" . $erd[$name[43]] . "','" . $erd[$name[44]] . "','" . $erd[$name[45]] . "','" . $erd[$name[46]] . "','" . $erd[$name[47]] . "','" . $erd[$name[48]] . "','" . $erd[$name[49]] . "','" . $erd[$name[50]] . "','" . $erd[$name[51]] . "','" . $erd[$name[52]] . "');"))
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

            </div>
        </div>
    </div>
</div>


