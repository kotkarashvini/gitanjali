<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>

    $(document).ready(function () {

        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tableUsagemainmain').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        if ($('#hfhidden2').val() === 'Y')
        {
            $('#tableUsagesub').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }


        if ($('#hfhidden3').val() == 'Y')
        {
            $('#tablesubsubcategory').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }

        if ($('#hfhidden4').val() === 'Y')
        {
            $('#tableitem').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }

// --------------------Grid hide and show -------------------------------------//
        //   div main
        $('#btnshowmain').hide();
        $("#btnhidemain").click(function () {
            $('#btnshowmain').show();
            $('#btnhidemain').hide();
            $('#divmain').hide();
            $('#usage_main_catg_desc_en').val('');
            $('#usage_main_catg_desc_ll').val('');
            $('#dolr_usgaecode1').val('');

            $('#btnshowsub').show();
            $('#btnhidesub').hide();
            $('#divsub').hide();
            $('#usage_sub_catg_desc_en').val('');
            $('#usage_sub_catg_desc_ll').val('');
            $('#dolr_usage_code2').val('');

            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_sub_sub_catg_desc_en').val('');
            $('#usage_sub_sub_catg_desc_ll').val('');
            $('#dolr_usage_code').val('');
            return false;
        });
        $("#btnshowmain").click(function () {
            $('#btnhidemain').show();
            $('#btnshowmain').hide();
            $('#divmain').slideDown(1000);
            return false;
        });

// -------------------------div sub----------------------------------------------------------------------------------------------------------------
        $('#btnhidesub').hide();
        $('#divsub').hide();

        if ($('#usage_sub_catg_desc_en').val() != '') {
            $('#btnhidesub').show();
            $('#btnshowsub').hide();
            $('#divsub').slideDown(1000);
        }
        $("#btnhidesub").click(function () {
            $('#btnshowsub').show();
            $('#btnhidesub').hide();
            $('#divsub').hide();
            $('#usage_sub_catg_desc_en').val('');
            $('#usage_sub_catg_desc_ll').val('');
            $('#dolr_usage_code2').val('');

            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_sub_sub_catg_desc_en').val('');
            $('#usage_sub_sub_catg_desc_ll').val('');
            $('#dolr_usage_code').val('');
            return false;
        });
        $("#btnshowsub").click(function () {
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var updateflag = $('#hfupdateflag').val();
            if (usage_main_catg_desc_en != '' && updateflag == 'MU') {
                $('#btnhidesub').show();
                $('#btnshowsub').hide();
                $('#divsub').slideDown(1000);
                return false;
            } else {
                alert('Please Select Main category description!!!');
                return false;
            }
        });

// -----------------------------------div subsub-------------------------------------------------------------------------------------------
        $('#btnhidesubsub').hide();
        $('#divsubsub').hide();

        if ($('#usage_sub_sub_catg_desc_en').val() != '') {
            $('#btnhidesubsub').show();
            $('#btnshowsubsub').hide();
            $('#divsubsub').slideDown(1000);
        }
        $("#btnhidesubsub").click(function () {
            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_sub_sub_catg_desc_en').val('');
            $('#usage_sub_sub_catg_desc_ll').val('');
            $('#dolr_usage_code').val('');
            return false;
        });
        $("#btnshowsubsub").click(function () {
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var updateflag = $('#hfupdateflag').val();
            if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '' && updateflag == 'SU') {
                $('#btnhidesubsub').show();
                $('#btnshowsubsub').hide();
                $('#divsubsub').slideDown(1000);
                return false;
            } else {
                alert('Please Select Sub category description!!!');
                return false;
            }
        });
               
//------------------------------------------location div---------------------------------------------------------------
        $('#divloc').hide();
        $('#btnhideloc').hide();
        
         if ($('#state_id').val() != '') {
            $('#btnshowloc').show();
            $('#btnhideloc').hide();
            $('#divloc').slideDown(1000);
        }
        
         $("#btnshowloc").click(function () {
            var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var updateflag = $('#hfupdateflag').val();
            if (usage_sub_sub_catg_desc_en != '' && updateflag == 'SSU') {
                $('#btnhideloc').show();
                $('#btnshowloc').hide();
                $('#divloc').slideDown(1000);
                return false;
            } else {
                alert('Please Select Sub Sub Category description!!!');
                return false;
            }

        });
        $("#btnhideloc").click(function () {
            $('#btnshowloc').show();
            $('#btnhideloc').hide();
            $('#divloc').hide();
            return false;
        });
        
        // ----------------------------------Item Div-----------------------//
        $('#divitem').hide();
        $('#btnhideitem').hide();

        if ($('#usage_param_id').val() != '') {
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').slideDown(1000);
        }
        $("#btnshowitem").click(function () {
            var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var updateflag = $('#hfupdateflag').val();
            if (usage_sub_sub_catg_desc_en != '' && updateflag == 'SSU') {
                $('#btnhideitem').show();
                $('#btnshowitem').hide();
                $('#divitem').slideDown(1000);
                return false;
            } else {
                alert('Please Select Sub Sub Category description!!!');
                return false;
            }

        });
        $("#btnhideitem").click(function () {
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
            $('#usage_param_id').val('');
            return false;
        });



        // ---------------------------------------------------------//

        $("#usage_main_catg_desc_en,#usage_main_catg_desc_ll,#dolr_usgaecode1,#btnupdatemain,#btnadd,#btncancel,#btncancelsub,#btncancelsubsub").click(function () {
            var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            if (usage_main_catg_desc_en !== '' && usage_sub_catg_desc_en !== '' && usage_sub_sub_catg_desc_en !== '') {
                $('#usage_sub_catg_desc_en').val('');
                $('#usage_sub_catg_desc_ll').val('');
                $('#dolr_usage_code').val('');

                $('#usage_sub_sub_catg_desc_en').val('');
                $('#usage_sub_sub_catg_desc_ll').val('');
                $('#dolr_usage_code').val('');
                $('#contsruction_type_flag').val('N');
                $('#depreciation_flag').val('N');
                $('#road_vicinity_flag').val('N');
                $('#user_defined_dependency1_flag').val('N');
                $('#user_defined_dependency2_flag').val('N');

                $('#btnshowsub').show();
                $('#btnhidesub').hide();
                $('#divsub').hide();

                $('#btnshowsubsub').show();
                $('#btnhidesubsub').hide();
                $('#divsubsub').hide();
            } else if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '') {
                $('#usage_sub_catg_desc_en').val('');
                $('#usage_sub_catg_desc_ll').val('');
                $('#dolr_usage_code2').val('');
                $('#btnshowsub').show();
                $('#btnhidesub').hide();
                $('#divsub').hide();
            }
        });
        $("#usage_sub_catg_desc_en,#usage_sub_catg_desc_ll,#dolr_usage_code2,#btnupdatesub,#btnaddsub,#btncancel,#btncancelsub,#btncancelsubsub").click(function () {
            var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '' && usage_sub_sub_catg_desc_en != '') {
                $('#usage_sub_sub_catg_desc_en').val('');
                $('#usage_sub_sub_catg_desc_ll').val('');
                $('#dolr_usage_code').val('');
                $('#contsruction_type_flag').val('N');
                $('#depreciation_flag').val('N');
                $('#road_vicinity_flag').val('N');
                $('#user_defined_dependency1_flag').val('N');
                $('#user_defined_dependency2_flag').val('N');
                $('#btnshowsubsub').show();
                $('#btnhidesubsub').hide();
                $('#divsubsub').hide();
            }
        });


//Reset button---------
        $('#btncancel').click(function () {
            $('#usage_main_catg_desc_en').val('');
            $('#usage_main_catg_desc_ll').val('');
            $('#dolr_usgaecode1').val('');
            $('#btnadd').html('Add');
            $('#hfupdateflag').val('S');
            return false;
        });
        $('#btncancelsub').click(function () {
            $('#usage_sub_catg_desc_en').val('');
            $('#usage_sub_catg_desc_ll').val('');
            $('#dolr_usage_code2').val('');
            $('#hfupdateflag').val('S');
            return false;
        });
        $('#btncancelsubsub').click(function () {
            $('#usage_sub_sub_catg_desc_en').val('');
            $('#usage_sub_sub_catg_desc_ll').val('');
            $('#dolr_usage_code').val('');
            $('#contsruction_type_flag').val('N');
            $('#depreciation_flag').val('N');
            $('#road_vicinity_flag').val('N');
            $('#user_defined_dependency1_flag').val('N');
            $('#user_defined_dependency2_flag').val('N');
            $('#hfupdateflag').val('S');
            return false;
        });
//-------------------



        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '1') {
            $('#btnadd').html('Save');
        }
        if (actiontype == '2') {
            $('#btnaddsub').html('Save');
        }
        if (actiontype == '3') {
            $('#btnaddsubsub').html('Save');
        }
        if (actiontype == '4') {
            $('#btnadditem').html('Save');
        }

    });

</script> 
<script>
    $(document).ready(function () {
        $('#btnadd').click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var usage_main_catg_desc_ll = $('#usage_main_catg_desc_ll').val();
            var dolr_usgaecode = $('#dolr_usgaecode1').val();
            if (usage_main_catg_desc_en === '') {
                alert('Please enter category description!!!');
                $('#usage_main_catg_desc_en').focus();
                return false;
            }
            var actiontype = 'S';
            if ($('#hfupdateflag').val() !== 'S') {
                actiontype = 'U';
            }
            var id = $('#hfid').val();

            $.getJSON('saveusagemaincategory', {usage_main_catg_desc_en: usage_main_catg_desc_en, usage_main_catg_desc_ll: usage_main_catg_desc_ll, dolr_usgaecode: dolr_usgaecode, actiontype: actiontype, id: id}, function (data)
            {
//                alert(data.id);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var maincatdesc = "'" + usage_main_catg_desc_en + "'";
                    var maincatdescll = "'" + usage_main_catg_desc_ll + "'";
                    var dolrusagecode = "'" + dolr_usgaecode + "'";
                    var rmainid = "'" + data.usage_main_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actiontype === 'S') {
                        $('#tableUsagemainmain tr:last').after('<tr id="' + data.id + '">\n\
                        <td style="text-align: center;">Maharashtra</td>\n\
                        <td style="text-align: center;">' + usage_main_catg_desc_en + '</td> \n\
                        <td style="text-align: center;">' + dolr_usgaecode + '</td> \n\
                        <td style="text-align: center;">' + usage_main_catg_desc_ll + '</td>\n\
                        <td style="text-align: center;">\n\
                        <button id="btnupdatemain" name="btnupdate" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdate(' + maincatdesc + ',' + maincatdescll + ',' + dolrusagecode + ',' + rmainid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');
                    } else if (actiontype === 'U') {
                        $('#' + id).fadeOut();
                        $('#tableUsagemainmain tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td> <td style="text-align: center;">' + dolr_usgaecode + '</td> <td style="text-align: center;">' + usage_main_catg_desc_ll + '</td><td style="text-align: center;"><button id="btnupdatemain" name="btnupdate" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdate(' + maincatdesc + ',' + maincatdescll + ',' + dolrusagecode + ',' + rmainid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Updated');
                    }
                    $('#usage_main_catg_desc_en').val('');
                    $('#usage_main_catg_desc_ll').val('');
                    $('#dolr_usgaecode1').val('');
                } else {
                    alert(data);
                }


            });
            return false;
        });
    });
    function formupdate(usage_main_catg_desc_en, usage_main_catg_desc_ll, dolr_usgaecode, usage_main_catg_id, id) {
        document.getElementById("actiontype").value = '1';
        $('#usage_main_catg_desc_en').val(usage_main_catg_desc_en);
        $('#usage_main_catg_desc_ll').val(usage_main_catg_desc_ll);
        $('#dolr_usgaecode1').val(dolr_usgaecode);
        $('#hfupdateflag').val('MU');
        $('#hfcode').val(usage_main_catg_id);
        $('#hfid').val(id);
//         ajaxindicatorstart('loading data.. please wait..');
//         $this->set('Usagesubrecord', $gridsub);
        //$('#usagecategory').submit();
    }
</script> 
<script>
    $(document).ready(function () {
        $('#btnaddsub').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var usage_sub_catg_desc_ll = $('#usage_sub_catg_desc_ll').val();
            var dolr_usage_code = $('#dolr_usage_code2').val();
            var main_id = $('#hfcode').val();
            if (usage_sub_catg_desc_en === '') {
                alert('Please enter category description!!!');
                $('#usage_sub_catg_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'SU') {
                actiontype = 'U';
            }
            var id = $('#hfsubid').val();
//            alert(actiontype);
            $.getJSON('saveusagesubcategory', {usage_main_catg_desc_en: usage_main_catg_desc_en, usage_sub_catg_desc_en: usage_sub_catg_desc_en, usage_sub_catg_desc_ll: usage_sub_catg_desc_ll, dolr_usage_code: dolr_usage_code, actiontype: actiontype, id: id, main_id: main_id}, function (data)
            {
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var subcatdescen = "'" + usage_sub_catg_desc_en + "'";
                    var subcatdescll = "'" + usage_sub_catg_desc_ll + "'";
                    var dolrusagecode = "'" + dolr_usage_code + "'";
                    var rsubid = "'" + data.usage_sub_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actiontype === 'S') {

//                    $('#tableUsagesub tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td><td style="text-align: center;">' + usage_sub_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_catg_desc_ll + '</td> <td style="text-align: center;">' + dolr_usage_code + '</td><td style="text-align: center;"><button id="btnupdatesub" name="btnupdatesub" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdatesub(' + usage_sub_catg_desc_en + ',' + usage_sub_catg_desc_ll + ',' + dolr_usage_code + ',' + data.usage_sub_catg_id + ',' + data.id + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        $('#tableUsagesub tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_catg_desc_en + '</td> <td style="text-align: center;">' + dolr_usage_code + '</td><td style="text-align: center;">' + usage_sub_catg_desc_ll + '</td><td style="text-align: center;"><button id="btnupdatesub" name="btnupdatesub" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdatesub(' + subcatdescen + ',' + subcatdescll + ',' + dolrusagecode + ',' + rsubid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');
                    } else if (actiontype === 'U') {
                        $('#' + id).fadeOut();
                        $('#tableUsagesub tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_catg_desc_en + '</td> <td style="text-align: center;">' + dolr_usage_code + '</td><td style="text-align: center;">' + usage_sub_catg_desc_ll + '</td><td style="text-align: center;"><button id="btnupdatesub" name="btnupdatesub" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdatesub(' + subcatdescen + ',' + subcatdescll + ',' + dolrusagecode + ',' + rsubid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    //$('#usage_main_catg_desc_en').val('');
                    $('#usage_sub_catg_desc_en').val('');
                    $('#usage_sub_catg_desc_ll').val('');
                    $('#dolr_usage_code2').val('');
                } else {
                    alert(data);
                }
            });
            return false;
        });
    });
    function formupdatesub(usage_sub_catg_desc_en, usage_sub_catg_desc_ll, dolr_usage_code, usage_sub_catg_id, id) {
        document.getElementById("actiontype").value = '2';
        $('#usage_sub_catg_desc_en').val(usage_sub_catg_desc_en);
        $('#usage_sub_catg_desc_ll').val(usage_sub_catg_desc_ll);
        $('#dolr_usage_code').val(dolr_usage_code);
        $('#hfsubid').val(id);
        $('#hfsubcode').val(usage_sub_catg_id);
        $('#hfupdateflag').val('SU');
//         ajaxindicatorstart('loading data.. please wait..');
        $('#usagecategory').submit();
    }
</script>
<script>
    $(document).ready(function () {
        $('#btnaddsubsub').click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var usage_sub_sub_catg_desc_ll = $('#usage_sub_sub_catg_desc_ll').val();
            var dolr_usage_code = $('#dolr_usage_code').val();
            var constuctionflag = $('input[name="data[usagecategory][contsruction_type_flag]"]:checked').val();
            var depreciationflag = $('input[name="data[usagecategory][depreciation_flag]"]:checked').val();
            var roadvicinityflag = $('input[name="data[usagecategory][road_vicinity_flag]"]:checked').val();
            var userdepflag1 = $('input[name="data[usagecategory][user_defined_dependency1_flag]"]:checked').val();
            var userdepflg2 = $('input[name="data[usagecategory][user_defined_dependency2_flag]"]:checked').val();
            var main_id = $('#hfcode').val();
            var sub_id = $('#hfsubcode').val();
//alert(constuctionflag);
//return  false;
            if (usage_sub_catg_desc_en === '') {
                alert('Please enter category description!!!');
                $('#usage_sub_catg_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'SSU') {
                actiontype = 'U';
            }
            var id = $('#hfsubsubid').val();
            $.getJSON('saveusagesubsubcategory', {usage_main_catg_desc_en: usage_main_catg_desc_en, usage_sub_catg_desc_en: usage_sub_catg_desc_en, usage_sub_sub_catg_desc_en: usage_sub_sub_catg_desc_en, usage_sub_sub_catg_desc_ll: usage_sub_sub_catg_desc_ll, dolr_usage_code: dolr_usage_code, constuctionflag: constuctionflag, depreciationflag: depreciationflag, roadvicinityflag: roadvicinityflag, userdepflag1: userdepflag1, userdepflg2: userdepflg2, actiontype: actiontype, id: id, main_id: main_id, sub_id: sub_id}, function (data)
            {
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var subsubcatdesc = "'" + usage_sub_sub_catg_desc_en + "'";
                    var subsubcatdescll = "'" + usage_sub_sub_catg_desc_ll + "'";
                    var dolrusagecode = "'" + dolr_usage_code + "'";
                    var constuctionflag1 = "'" + constuctionflag + "'";
                    var depreciationflag1 = "'" + depreciationflag + "'";
                    var roadvicinityflag1 = "'" + roadvicinityflag + "'";
                    var userdepflag11 = "'" + userdepflag1 + "'";
                    var userdepflag12 = "'" + userdepflg2 + "'";
                    var rsubsubid = "'" + data.usage_sub_sub_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actiontype === 'S') {
                        $('#tablesubsubcategory tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_sub_catg_desc_en + '</td><td style="text-align: center;">' + dolr_usage_code + '</td><td style="text-align: center;">' + usage_sub_sub_catg_desc_ll + '</td><td style="text-align: center;"><button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdatesubsub(' + subsubcatdesc + ',' + subsubcatdescll + ',' + dolrusagecode + ',' + constuctionflag1 + ',' + depreciationflag1 + ',' + roadvicinityflag1 + ',' + userdepflag11 + ',' + userdepflag12 + ',' + rsubsubid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');
                    } else if (actiontype === 'U') {
                        $('#' + id).fadeOut();
                        $('#tablesubsubcategory tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">Maharashtra</td><td style="text-align: center;">' + usage_main_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_catg_desc_en + '</td> <td style="text-align: center;">' + usage_sub_sub_catg_desc_en + '</td><td style="text-align: center;">' + dolr_usage_code + '</td><td style="text-align: center;">' + usage_sub_sub_catg_desc_ll + '</td><td style="text-align: center;"><button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdatesubsub(' + subsubcatdesc + ',' + subsubcatdescll + ',' + dolrusagecode + ',' + constuctionflag1 + ',' + depreciationflag1 + ',' + roadvicinityflag1 + ',' + userdepflag11 + ',' + userdepflag12 + ',' + rsubsubid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Updated');
                    }
                    //$('#usage_main_catg_desc_en').val('');
                    $('#usage_sub_sub_catg_desc_en').val('');
                    $('#usage_sub_sub_catg_desc_ll').val('');
                    $('#dolr_usage_code').val('');
                } else {
                    alert(data);
                }
            });
            return false;
        });
    });
    function formupdatesubsub(usage_sub_sub_catg_desc_en, usage_sub_sub_catg_desc_ll, dolr_usage_code,
            contsruction_type_flag, depreciation_flag, road_vicinity_flag, user_defined_dependency1_flag, user_defined_dependency2_flag, usage_sub_sub_catg_id, id) {

        document.getElementById("actiontype").value = '3';
        $('#usage_sub_sub_catg_desc_en').val(usage_sub_sub_catg_desc_en);
        $('#usage_sub_sub_catg_desc_ll').val(usage_sub_sub_catg_desc_ll);
        $('#dolr_usage_code').val(dolr_usage_code);
        $('#contsruction_type_flag').val(contsruction_type_flag);
        $('#depreciation_flag').val(depreciation_flag);
        $('#road_vicinity_flag').val(road_vicinity_flag);
        $('#user_defined_dependency1_flag').val(user_defined_dependency1_flag);
        $('#user_defined_dependency2_flag').val(user_defined_dependency2_flag);
        $('#hfsubsubid').val(id);
        $('#hfsubsubcode').val(usage_sub_sub_catg_id);
        $('#hfupdateflag').val('SSU');
        $('input:radio[name="data[usagecategory][contsruction_type_flag]"][value=' + contsruction_type_flag + ']').attr('checked', true);
        $('input:radio[name="data[usagecategory][depreciation_flag]"][value=' + depreciation_flag + ']').attr('checked', true);
        $('input:radio[name="data[usagecategory][road_vicinity_flag]"][value=' + road_vicinity_flag + ']').attr('checked', true);
        $('input:radio[name="data[usagecategory][user_defined_dependency1_flag]"][value=' + user_defined_dependency1_flag + ']').attr('checked', true);
        $('input:radio[name="data[usagecategory][user_defined_dependency2_flag]"][value=' + user_defined_dependency2_flag + ']').attr('checked', true);
//         ajaxindicatorstart('loading data.. please wait..');
        $('#usagecategory').submit();
    }
</script>
<script>
    $(document).ready(function () {

        $('#btnadditem').click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var usagemain = $('#usage_main_catg_desc_en').val();
            var usagesub = $('#usage_sub_catg_desc_en').val();
            var usagesubsub = $('#usage_sub_sub_catg_desc_en').val();
            var item_list_id = $("#usage_param_id option:selected").val();
            var item = $("#usage_param_id option:selected").text();
            var main_id = $('#hfcode').val();
            var sub_id = $('#hfsubcode').val();
            var subsub_id = $('#hfsubsubcode').val();

            if (item_list_id === '') {
                alert('Please Select Item!!!');
                $('#item_list_id').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'IU') {
                actiontype = 'U';
            }
            var id = $('#hfitemid').val();
            $.getJSON('saveusagelinkitem', {actiontype: actiontype, item_list_id: item_list_id, id: id, main_id: main_id, sub_id: sub_id, subsub_id: subsub_id}, function (data)
            {
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {

                    var ritemid = "'" + data.usage_param_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actiontype === 'S') {
                        $('#tableitem tr:last').after('<tr id="' + data.id + '">\n\
                        <td style="text-align: center;">Maharashtra</td>\n\
                        <td style="text-align: center;">' + usagemain + '</td>\n\
                        <td style="text-align: center;">' + usagesub + '</td>\n\
                        <td style="text-align: center;">' + usagesubsub + '</td>\n\
                        <td style="text-align: center;">' + item + '</td>\n\
                        <td style="text-align: center;"><button id="btnupdateitem" name="btnupdateitem" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdateitem(' + ritemid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');
                    } else if (actiontype === 'U') {
                        $('#' + id).fadeOut();
                        $('#tableitem tr:last').after('<tr id="' + data.id + '">\n\
                        <td style="text-align: center;">Maharashtra</td>\n\
                        <td style="text-align: center;">' + usagemain + '</td>\n\
                        <td style="text-align: center;">' + usagesub + '</td>\n\
                        <td style="text-align: center;">' + usagesubsub + '</td>\n\
                        <td style="text-align: center;">' + item + '</td>\n\
                        <td style="text-align: center;"><button id="btnupdateitem" name="btnupdateitem" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formupdateitem(' + ritemid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Updated');
                    }
                    $('#item_list_id').val('');
                } else {
                    alert(data);
                }
            });
            return false;
        });
    });
    function formupdateitem(usage_param_id, id) {

        document.getElementById("actiontype").value = '4';
        $("#hfitemid").val(id);
        $("#usage_param_id").val(usage_param_id);
        $('#hfupdateflag').val('IU');

    }
</script>
<script>
    $(document).ready(function () {
        $("#level1_id").change(function () {
            var level1 = $('#level1_id').val();
            if (level1 === 'Y') {
                $("#level1_list_id").prop("disabled", false);
            } else {
                $("#level1_list_id").prop("disabled", true);
                $("#level2_id").prop("disabled", true);
                $("#level2_list_id").prop("disabled", true);
                $("#level3_id").prop("disabled", true);
                $("#level3_list_id").prop("disabled", true);
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level1_list_id').val('');
                $('#level2_id').val('');
                $('#level2_list_id').val('');
                $('#level3_id').val('');
                $('#level3_list_id').val('');
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level1_list_id").change(function () {
            var level1list = $('#level1_list_id').val();
            if (level1list === 'Y') {
                $("#level2_id").prop("disabled", false);
            } else {
                $("#level2_id").prop("disabled", true);
                $("#level2_list_id").prop("disabled", true);
                $("#level3_id").prop("disabled", true);
                $("#level3_list_id").prop("disabled", true);
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level2_id').val('');
                $('#level2_list_id').val('');
                $('#level3_id').val('');
                $('#level3_list_id').val('');
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level2_id").change(function () {
            var level2 = $('#level2_id').val();
            if (level2 === 'Y') {
                $("#level2_list_id").prop("disabled", false);
            } else {
                $("#level2_list_id").prop("disabled", true);
                $("#level3_id").prop("disabled", true);
                $("#level3_list_id").prop("disabled", true);
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level2_list_id').val('');
                $('#level3_id').val('');
                $('#level3_list_id').val('');
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level2_list_id").change(function () {
            var level2list = $('#level2_list_id').val();
            if (level2list === 'Y') {
                $("#level3_id").prop("disabled", false);
            } else {
                $("#level3_id").prop("disabled", true);
                $("#level3_list_id").prop("disabled", true);
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level3_id').val('');
                $('#level3_list_id').val('');
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level3_id").change(function () {
            var level3 = $('#level3_id').val();
            if (level3 === 'Y') {
                $("#level3_list_id").prop("disabled", false);
            } else {
                $("#level3_list_id").prop("disabled", true);
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level3_list_id').val('');
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level3_list_id").change(function () {
            var level3list = $('#level3_list_id').val();
            if (level3list === 'Y') {
                $("#level4_id").prop("disabled", false);
            } else {
                $("#level4_id").prop("disabled", true);
                $("#level4_list_id").prop("disabled", true);
                $('#level4_id').val('');
                $('#level4_list_id').val('');
            }
        });
        $("#level4_id").change(function () {
            var level4 = $('#level4_id').val();
            if (level4 === 'Y') {
                $("#level4_list_id").prop("disabled", false);
            } else {
                $("#level4_list_id").prop("disabled", true);
                $('#level4_list_id').val('');
            }
        });

        $('#btnaddloc').click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var main_id = $('#hfcode').val();
            var sub_id = $('#hfsubcode').val();
            var subsub_id = $('#hfsubsubcode').val();
            var div = $('#division_id').val();
            var dis = $('#state_id').val();
            var subdiv = $('#subdivision_id').val();
            var tal = $('#taluka_id').val();
            var cir = $('#circle_id').val();
            var ulb = $('#ulb_type_id').val();
            var corp = $('#corp_id').val();
            var devl = $('#developed_land_types_id').val();
            var vil = $('#village_id').val();
            var l1 = $('#level1_id').val();
            var ll1 = $('#level1_list_id').val();
            var l2 = $('#level2_id').val();
            var ll2 = $('#level2_list_id').val();
            var l3 = $('#level3_id').val();
            var ll3 = $('#level3_list_id').val();
            var l4 = $('#level4_id').val();
            var ll4 = $('#level4_list_id').val();

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'SSU') {
                actiontype = 'U';
            }
            var id = $('#hfsubsubid').val();
            $.getJSON('saveusagelocation', {main_id: main_id, sub_id: sub_id, subsub_id: subsub_id, div: div, dis: dis, subdiv: subdiv, tal: tal,
                cir: cir, ulb: ulb, corp: corp, devl: devl, vil: vil, l1: l1, ll1: ll1, l2: l2, ll2: ll2, l3: l3, ll3: ll3, l4: l4, ll4: ll4}, function (data)
            {
                alert(data);
            });
            return false;
        });

    });

</script> 

<?php echo $this->Form->create('usagecategory', array('id' => 'usagecategory')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<?php
if ($this->Session->read("sess_langauge") == 'en') {
    $dlang = 'en';
    $llang = "ll";
} else {
    $dlang = 'll';
    $llang = "en";
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-success">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagecategory'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table style="width: 100%"><tr><td><big><b><?php echo __('lblusamaincat'); ?></b></big></td><td style="text-align: right"><button id="btnshowmain"  class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidemain" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                            </div>
                            <div id="divmain">
                                <div class="panel-body">
                                    <div id="selectUsagemainmain" >
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="usage_main_catg_desc_en" class="col-sm-3 control-label"><?php echo __('lblusagemaincategoryname'); ?><span style="color: #ff0000">*</span></label>    
                                                    <div class="col-sm-2" >
                                                        <?php echo $this->Form->input('usage_main_catg_desc_en', array('label' => false, 'id' => 'usage_main_catg_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('usage_main_catg_desc_ll', array('label' => false, 'id' => 'usage_main_catg_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                    </div>
                                                    <label for="dolr_usgaecode" class="col-sm-2 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('dolr_usgaecode', array('label' => false, 'id' => 'dolr_usgaecode1', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                                        <div class="row" style="text-align: center">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" >
                                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                                                    </button>
                                                    <button id="btncancel" name="btncancel" class="btn btn-primary " style="text-align: center;" >
                                                        <span class="glyphicon glyphicon-reset"><?php ?></span><?php echo __('lblreset'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table id="tableUsagemainmain" class="table table-striped table-bordered table-hover">  
                                    <thead >  
                                        <tr style="text-align: center; width: 5%;">  
                                            <td style="text-align: center; width: 10%;"><?php echo __('lbladmstate'); ?></td>
                                            <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                                <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblusagemaincategoryname_ll'); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblusagemaincategoryname_ll'); ?></td>
                                            <?php } ?>
                                            <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                                        </tr>  
                                    </thead>
                                    <?php foreach ($usagemainrecord as $usagemainrecord1): ?>
                                        <tr style="text-align: center; width: 5%;" id="<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>">
                                            <td style="text-align: center"><?php echo $state; ?></td>
                                            <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_' . $dlang]; ?></td>
                                            <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?></td>
                                            <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_' . $llang]; ?></td>
                                            <td style="text-align: center;">
                                                <button id="btnupdatemain" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                        onclick="javascript: return formupdate(('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_en']; ?>'), ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_ll']; ?>'), ('<?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?>'), ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_id']; ?>'), ('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php unset($usagemainrecord1); ?>
                                </table> 
                            </div>
                            <?php if (!empty($usagemainrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" >
                                <table style="width: 100%"><tr><td><big><b><?php echo __('lblsubcat'); ?></b></big></td><td style="text-align: right"><button id="btnshowsub"  class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidesub" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                            </div>
                            <div id="divsub">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div id="selectUsagesub" >
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="usage_sub_catg_desc_en" class="col-sm-3 control-label" ><?php echo __('lblUsagesubcategoryname'); ?><span style="color: #ff0000">*</span></label>    
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('usage_sub_catg_desc_en', array('label' => false, 'id' => 'usage_sub_catg_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('usage_sub_catg_desc_ll', array('label' => false, 'id' => 'usage_sub_catg_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                        <label for="dolr_usage_code" class="col-sm-2 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('dolr_usage_code', array('label' => false, 'id' => 'dolr_usage_code2', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                                            <div class="row" style="text-align: center">
                                                <div class="col-lg-12 tdselect">
                                                    <div class="form-group">
                                                        <button id="btnaddsub" name="btnaddsub" class="btn btn-primary " style="text-align: center;" >
                                                            <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                                                        </button>
                                                        <button id="btncancelsub" name="btncancelsub" class="btn btn-primary "  >
                                                            <span class="glyphicon "></span><?php echo __('lblreset'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="tableUsagesub" class="table table-striped table-bordered table-hover">  
                                        <thead >  
                                            <tr style="text-align: center; width: 5%;">  
                                                <td style="text-align: center; width: 10%;"><?php echo __('lbladmstate'); ?></td>
                                                <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname_ll'); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname_ll'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                    <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname_ll'); ?></td>
                                                <?php } ?>
                                                <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>

                                        <?php foreach ($Usagesubrecord as $Usagesubrecord1): ?>
                                            <tr style="text-align: center; width: 5%;"  id="<?php echo $Usagesubrecord1[0]['id']; ?>">
                                                <td style="text-align: center"><?php echo $state; ?></td>
                                                <td style="text-align: center;"><?php echo $Usagesubrecord1[0]['usage_main_catg_desc_' . $dlang]; ?></td>
                                                <td style="text-align: center;"><?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_' . $dlang]; ?></td>
                                                <td style="text-align: center;"><?php echo $Usagesubrecord1[0]['dolr_usage_code']; ?></td>
                                                <td style="text-align: center;"><?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_' . $llang]; ?></td>
                                                <td style="text-align: center;">
                                                    <button id="btnupdatesub" name="btnupdatesub" class="btn btn-default " style="text-align: center;" 
                                                            onclick="javascript: return formupdatesub(('<?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_en']; ?>'), ('<?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_ll']; ?>'), ('<?php echo $Usagesubrecord1[0]['dolr_usage_code']; ?>'), ('<?php echo $Usagesubrecord1[0]['usage_sub_catg_id']; ?>'), ('<?php echo $Usagesubrecord1[0]['id']; ?>'));">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php unset($Usagesubrecord1); ?>
                                    </table> 
                                </div>

                                <div class="row col-sm-2">&nbsp;</div>
                                <?php if (!empty($Usagesubrecord)) { ?>
                                    <input type="hidden" value="Y" id="hfhidden2"/><?php } else { ?>
                                    <input type="hidden" value="N" id="hfhidden2"/><?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table style="width: 100%"><tr><td><big><b><?php echo __('lblsubccategory'); ?></b></big></td><td style="text-align: right"><button id="btnshowsubsub"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidesubsub" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                            </div>
                            <div id="divsubsub">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div id="selectsubsubcategory">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="construction_type_desc_en" class="col-sm-3 control-label"><?php echo __('lblsubsubcategorydesc'); ?><span style="color: #ff0000">*</span></label>    
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('usage_sub_sub_catg_desc_en', array('label' => false, 'id' => 'usage_sub_sub_catg_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('usage_sub_sub_catg_desc_ll', array('label' => false, 'id' => 'usage_sub_sub_catg_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                        <label for="dolr_usage_code" class="col-sm-2 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('dolr_usage_code', array('label' => false, 'id' => 'dolr_usage_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="contsruction_type_flag" class="col-sm-2 control-label"><?php echo __('lblconstuctiontypehead'); ?></label>            
                                                    <div class="col-sm-2"> <?php echo $this->Form->input('contsruction_type_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'contsruction_type_flag')); ?></div>   

                                                    <label for="depreciation_flag" class="control-label col-sm-2"><?php echo __('lbldepreciation'); ?></label>            
                                                    <div class="col-sm-2"> <?php echo $this->Form->input('depreciation_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'depreciation_flag')); ?></div> 

                                                    <label for="road_vicinity_flag" class="control-label col-sm-2"><?php echo __('Road Vicinity'); ?></label>            
                                                    <div class="col-sm-2"> <?php echo $this->Form->input('road_vicinity_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'road_vicinity_flag')); ?></div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="user_defined_dependency1_flag" class="control-label col-sm-2"><?php echo __('lbluserdependencyflag1'); ?></label>            
                                                    <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency1_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency1_flag')); ?></div> 

                                                    <label for="user_defined_dependency2_flag" class="control-label col-sm-2"><?php echo __('lbluserdependencyflag2'); ?></label>            
                                                    <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency2_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency2_flag')); ?></div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                                        <div class="row" style="text-align: center">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button id="btnaddsubsub" name="btnaddsubsub" class="btn btn-primary " style="text-align: center;" >
                                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                                                    </button>
                                                    <button id="btncancelsubsub" name="btncancelsubsub" class="btn btn-primary " style="text-align: center;" >
                                                        <span class=""></span><?php echo __('lblreset'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>

                                        <table id="tablesubsubcategory" class="table table-striped table-bordered table-hover">  
                                            <thead >  
                                                <tr>  
                                                    <td style="text-align: center;"><?php echo __('lbladmstate'); ?></td>
                                                    <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                                        <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lblsubsubcategorydesc'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lblsubsubcategorydesc_ll'); ?></td>
                                                    <?php } else { ?>
                                                        <td style="text-align: center;"><?php echo __('lblsubsubcategorydesc'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lbldolrusagecode'); ?></td>
                                                        <td style="text-align: center;"><?php echo __('lblsubsubcategorydesc_ll'); ?></td>
                                                    <?php } ?>
                                                    <td style="text-align: center;"><?php echo __('lblaction'); ?></td>
                                                </tr>  
                                            </thead>
                                            <tbody>
                                                <?php foreach ($subsubcategoryrecord as $subsubcategoryrecord1): ?>
                                                    <tr id="<?php echo $subsubcategoryrecord1[0]['id']; ?>">
                                                        <td style="text-align: center;"><?php echo $state; ?></td>
                                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1[0]['usage_main_catg_desc_' . $dlang]; ?></td>
                                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1[0]['usage_sub_catg_desc_' . $dlang]; ?></td>
                                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_' . $dlang]; ?></td>
                                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1[0]['dolr_usage_code']; ?></td>
                                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_' . $llang]; ?></td>
                                                        <td style="text-align: center; width: 15%">
                                                            <button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-default " style="text-align: center;" 
                                                                    onclick="javascript: return formupdatesubsub(('<?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_en']; ?>'), ('<?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_ll']; ?>'), ('<?php echo $subsubcategoryrecord1[0]['dolr_usage_code']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['contsruction_type_flag']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['depreciation_flag']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['road_vicinity_flag']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['user_defined_dependency1_flag']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['user_defined_dependency2_flag']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_id']; ?>'),
                                                                                        ('<?php echo $subsubcategoryrecord1[0]['id']; ?>'));">
                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php unset($subsubcategoryrecord1); ?>
                                            </tbody>
                                        </table> 
                                        <?php if (!empty($subsubcategoryrecord)) { ?>
                                            <input type="hidden" value="Y" id="hfhidden3"/><?php } else { ?>
                                            <input type="hidden" value="N" id="hfhidden3"/><?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table style="width: 100%"><tr><td><big><b><?php echo __('lbldivtolevel'); ?></b></big></td><td style="text-align: right"><button id="btnshowloc"  class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhideloc" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                            </div>
                            <div id="divloc">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php $flag = array('Y' => "YES", 'N' => "NO"); ?>
                                    <table id="table2" class="table table-striped table-bordered table-condensed" width="100%">  
                                        <thead >  
                                            <tr> 
                                                <td style="text-align: center;width:11%; font-weight:bold; "><?php echo __('lbladmdivision'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbladmdistrict'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblSubDivision'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbladmtaluka'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblCircle'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblCorporationClass'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblcorpcouncillist'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLandType'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lbladmvillage'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center"><?php echo $this->Form->input('division_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'division_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('state_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'state_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('subdivision_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'subdivision_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('taluka_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('circle_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'circle_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('ulb_type_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'ulb_type_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('corp_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'corp_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('developed_land_types_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'developed_land_types_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('village_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'village_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                    <table id="table2" class="table table-striped table-bordered table-condensed" width="100%">  
                                        <thead >  
                                            <tr> 
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel1'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel1list'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel2'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel2list'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel3'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel3list'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel4'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel4list'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblaction'); ?> </td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center"><?php echo $this->Form->input('level1_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level1_id', 'label' => false, 'class' => 'form-control input-sm')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level1_list_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level1_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level2_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level2_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level2_list_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level2_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level3_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level3_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level3_list_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level3_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level4_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level4_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><?php echo $this->Form->input('level4_list_id', array('options' => array($flag), 'empty' => '--select--', 'id' => 'level4_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                                <td style="text-align: center"><button id="btnaddloc" name="btnaddloc" class="btn btn-primary " style="text-align: center;"><span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?></button></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                                 </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" >
                                <table style="width: 100%"><tr><td><big><b><?php echo __('lblusageitemhead'); ?></b></big></td><td style="text-align: right"><button id="btnshowitem"  class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhideitem" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                            </div>
                            <div id="divitem">
                                <div class="panel-body">
                                    <div class="row" style="text-align: center">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label"><?php echo __('lblitemlistname'); ?><span style="color: #ff0000">*</span></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('usage_param_id', array('options' => $usgitem, 'empty' => '--select--', 'id' => 'usage_param_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button id="btnadditem" name="btnadditem" class="btn btn-primary " style="text-align: center;" >
                                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="height: 5px;">&nbsp;</div>

                                    <table id="tableitem" class="table table-striped table-bordered table-hover">  
                                        <thead >  
                                            <tr>  
                                                <td style="text-align: center;"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblUsagesubcategoryname'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblsubsubcategorydesc'); ?></td>
                                                <td style="text-align: center;">Item</td>

                                                <td style="text-align: center;"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php foreach ($griditem as $griditem1): ?>
                                                <tr id="<?php echo $griditem1[0]['id']; ?>">
                                                    <td style="text-align: center;"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $griditem1[0]['usage_main_catg_desc_' . $dlang]; ?></td>
                                                    <td style="text-align: center;"><?php echo $griditem1[0]['usage_sub_catg_desc_' . $dlang]; ?></td>
                                                    <td style="text-align: center;"><?php echo $griditem1[0]['usage_sub_sub_catg_desc_' . $dlang]; ?></td>
                                                    <td style="text-align: center;"><?php echo $griditem1[0]['usage_param_desc_' . $dlang]; ?></td>
                                                    <td style="text-align: center; width: 15%">
                                                        <button id="btnupdateitem" name="btnupdateitem" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdateitem(('<?php echo $griditem1[0]['usage_param_id']; ?>'),
                                                                                        ('<?php echo $griditem1[0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php unset($griditem1); ?>
                                        </tbody>
                                    </table> 
                                    <?php if (!empty($griditem)) { ?>
                                        <input type="hidden" value="Y" id="hfhidden4"/><?php } else { ?>
                                        <input type="hidden" value="N" id="hfhidden4"/><?php } ?>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfsubid; ?>' name='hfsubid' id='hfsubid'/>
    <input type='hidden' value='<?php echo $hfsubsubid; ?>' name='hfsubsubid' id='hfsubsubid'/>
    <input type='hidden' value='<?php echo $hfitemid; ?>' name='hfitemid' id='hfitemid'/>
    <input type='hidden' value='<?php echo $hfcode; ?>' name='hfcode' id='hfcode'/>
    <input type='hidden' value='<?php echo $hfsubcode; ?>' name='hfsubcode' id='hfsubcode'/>
    <input type='hidden' value='<?php echo $hfsubsubcode; ?>' name='hfsubsubcode' id='hfsubsubcode'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




