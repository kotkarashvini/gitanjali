<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
//--------------------------Table Pagination-------------------------------------
    if ($('#hfhidden1').val() === 'Y') {
    $('#tableUsagemainmain').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    if ($('#hfhidden2').val() === 'Y') {
    $('#tableUsagesub').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    if ($('#hfhidden3').val() == 'Y') {
    $('#tablesubsubcategory').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[10, 15, - 1], [10, 15, "All"]]
    });
    }
    if ($('#hfhidden4').val() === 'Y') {
    $('#tableitem').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[10, 15, - 1], [10, 15, "All"]]
    });
    }
// ==============================Grid hide and show =============================================================//
//------------------- Getting Value of design----------------------
    var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
            var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
            var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
            var usage_param_id = $('#usage_param_id').val();
            var updateflag = $('#hfupdateflag').val();
//----------------------------------div main category---------------------------------------
            $('#btnshowmain').hide();
            $("#btnhidemain").click(function () {
    $('#btnshowmain').show();
            $('#btnhidemain').hide();
            $('#divmain').hide();
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('#btnshowsub').show();
            $('#btnhidesub').hide();
            $('#divsub').hide();
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
        $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
    $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
            return false;
    });
            $("#btnshowmain").click(function () {
    $('#btnhidemain').show();
            $('#btnshowmain').hide();
            $('#divmain').slideDown(1000);
            return false;
    });
// -------------------------div sub catergory----------------------------------------------------------------------------------------------------------------
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
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
        $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
    $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
            return false;
    });
            $("#btnshowsub").click(function () {
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
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    return false;
    });
            $("#btnshowsubsub").click(function () {
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
//----------------------------------Item Div-----------------------//
            $('#divitem').hide();
            $('#btnhideitem').hide();
            if ($('#usage_param_id').val() != '') {
    $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').slideDown(1000);
    }
    $("#btnshowitem").click(function () {
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
// --------------------------------Click Events-------------------------------//


            $("<?php foreach ($fieldlist1 as $listlevel => $level1) { ?> #<?php echo $listlevel; ?>, <?php } ?>#btnadd,#btncancel,#btnupdate").click(function () {

    if (usage_main_catg_desc_en !== '' && usage_sub_catg_desc_en !== '' && usage_sub_sub_catg_desc_en !== '') {
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
        $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
    $('#btnshowsub').show();
            $('#btnhidesub').hide();
            $('#divsub').hide();
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('input[name="consflag"][value="N"]').prop('checked', 'checked');
            $('input[name=depflag][value="N"]').prop('checked', 'checked');
            $('input[name=roadflag][value="N"]').prop('checked', 'checked');
            $('input[name=ud1flag][value="N"]').prop('checked', 'checked');
            $('input[name=ud2flag][value="N"]').prop('checked', 'checked');
            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
    } else if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '') {
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
        $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
    $('#btnshowsub').show();
            $('#btnhidesub').hide();
            $('#divsub').hide();
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('input[name="consflag"][value="N"]').prop('checked', 'checked');
            $('input[name=depflag][value="N"]').prop('checked', 'checked');
            $('input[name=roadflag][value="N"]').prop('checked', 'checked');
            $('input[name=ud1flag][value="N"]').prop('checked', 'checked');
            $('input[name=ud2flag][value="N"]').prop('checked', 'checked');
            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
    }
    });
            $("<?php foreach ($fieldlist2 as $listlevel => $level2) { ?> #<?php echo $listlevel; ?>, <?php } ?>#btnaddsub,#btncancelsub,#btnupdatesub").click(function () {

    if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '' && usage_sub_sub_catg_desc_en != '') {
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('input[name="consflag"][value="N"]').prop('checked', 'checked');
            $('input[name=depflag][value="N"]').prop('checked', 'checked');
            $('input[name=roadflag][value="N"]').prop('checked', 'checked');
            $('input[name=ud1flag][value="N"]').prop('checked', 'checked');
            $('input[name=ud2flag][value="N"]').prop('checked', 'checked');
            $('#btnshowsubsub').show();
            $('#btnhidesubsub').hide();
            $('#divsubsub').hide();
            $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
    }
    });
            $("<?php foreach ($fieldlist3 as $listlevel => $level3) { ?> #<?php echo $listlevel; ?>, <?php } ?>#btnaddsubsub,#btncancelsubsub,#btnupdatesubsub").click(function () {

    if (usage_main_catg_desc_en != '' && usage_sub_catg_desc_en != '' && usage_sub_sub_catg_desc_en != '') {
    $('#usage_param_id').val('');
            $('#btnshowitem').show();
            $('#btnhideitem').hide();
            $('#divitem').hide();
    }
    });
//-----------------------------Reset button-----------------------------------------------------------
            $('#btncancel').click(function () {
    $('#btnadd').show();
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
//    $('#btnadd').html('Add');
    $('#hfupdateflag').val('');
            return false;
    });
            $('#btncancelsub').click(function () {
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
        $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
    $('#hfupdateflag').val('');
            return false;
    });
            $('#btncancelsubsub').click(function () {
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
        $('#<?php echo $listlevel; ?>').val('');<?php } ?>
    $('input[name="consflag"][value="N"]').prop('checked', 'checked');
            $('input[name=depflag][value="N"]').prop('checked', 'checked');
            $('input[name=roadflag][value="N"]').prop('checked', 'checked');
            $('input[name=ud1flag][value="N"]').prop('checked', 'checked');
            $('input[name=ud2flag][value="N"]').prop('checked', 'checked');
            $('#hfupdateflag').val('');
            return false;
    });
//-------------------Action Change Event-------------------------------------------------------------
            var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '1') {
//    $('#btnadd').html('Save');
    $('#btnadd').hide();
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

    });</script> 
<!--start script for main category along with dyanamic structure and validations-->
<script>

            $(document).ready(function () {
    ////---------------------Form element events validations-----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist1 as $listkey => $listcontrol) {

    foreach ($listcontrol as $controltype => $valrule) {
        $rulearr = explode(",", $valrule);
        //  foreach ($rulearr as $singlerule) {

        if ($controltype == 'text') {
            $event = "keyup";
        } else if ($controltype == 'select') {
            $event = "change";
        } else if ($controltype == 'radio') {
            $event = "";
        }
        if (!empty($event)) {
            ?>
                $('#<?php echo $listkey; ?>').<?php echo $event; ?>(function (event)
                {//for checking function is_alpha or etc
            <?php
            foreach ($rulearr as $singlerule) {

                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                    }
                }
                ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val()))
                    {
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                    event.preventDefault();
                                    // alert();
                                    return;
                        <?php
                    }
                }
                ?>
                    } else {
                    $('#<?php echo $listkey; ?>_error').html('');
                            $("#<?php echo $listkey; ?>").parent().removeClass("field-error");
                    }
            <?php } ?>
                });
            <?php
        }
    }
}
?>
    $('#btnadd').click(function () {
    $(':input').each(function () {
    $(this).val($.trim($(this).val()));
    });
            //dyanamic variable creat
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
        var <?php echo $listlevel; ?> = $("#<?php echo $listlevel; ?>").val();
<?php } ?>
    var maincat = $('#usage_main_catg_desc_' + '<?php echo $laug ?>').val();
            //---------------------FORM SUBMIT validations -----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist1 as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
    foreach ($listcontrol as $controltype => $valrule) {
        ?>
        <?php
        $rulearr = explode(",", $valrule);
        foreach ($rulearr as $singlerule) {
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                }
            }
            ?>
                // result = <?php echo $valrule; ?>(<?php echo $listkey; ?>);
            <?php if ($controltype == 'select' OR $controltype == 'text') { ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val())) {
            <?php } else if ($controltype == 'radio') {
                ?>   var frmid = $('form:first').attr('id');
                            // alert($('form:first').attr('id'));      
                            if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()))  {
                    //  alert('IN');
            <?php }
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                        $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                $("#<?php echo $listkey; ?>").parent().addClass("field-error");
                                $("#<?php echo $listkey; ?>").focus();
                                return false;
                                //  (-temp FOR SERVER )
                    <?php
                }
            }
            ?>
                }
                else{
                $('#<?php echo $listkey; ?>_error').html('');
                        $("#<?php echo $listkey; ?>").removeClass("field-error");
                        // return false;
                }
            <?php
        }
    }
    ?>
    <?php
}
//}
?>
    var actionval = 'S';
            if ($('#hfupdateflag').val() === 'MU') {
    actionval = 'U';
    }
    var id = $('#hfid').val();
            $.post('<?php echo $this->webroot; ?>Usagecatgmullan/saveusagemaincategory', {<?php foreach ($languagelist as $langcode) { ?>
    <?php
    echo 'usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'] . " : " . 'usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'] . " ,";
}
?>dolr_usgaecode1: dolr_usgaecode1, actionval: actionval, id: id}, function (data)
            {
            var data = $.parseJSON(data);
                    if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
            $.each(data, function (index, val) {
            if (index == 'errorcode'){

            $.each(data[index], function (index1, val1) {
            $('#' + index1).text(val1);
            });
                    return false;
            } else{

            //-------------------------------------------------------------------dyanamic variable declaration--------------------------------------------------------------------
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
                var <?php echo $listlevel . '_l1'; ?> = "'" + <?php echo $listlevel; ?> + "'";
<?php } ?>
            var rmainid = "'" + data.usage_main_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actionval === 'S') {
            $('#tableUsagemainmain tr:last').after('<tr id="' + data.id + '">\n\
                                <td > <?php echo $state_name; ?></td>\n\
                                <td >' + maincat + '</td> \n\
                                <td >' + dolr_usgaecode1 + '</td> \n\
                                <td >\n\
                                <button id="btnupdatemain" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate('
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?><?php
    echo " + " . $listlevel . '_l1' . " + ','";
}
?> + rmainid + ',' + rid + ');">\n\
                                     <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Saved');
            } else if (actionval === 'U') {
            $('#' + id).fadeOut();
                    $('#tableUsagemainmain tr:last').after('<tr id="' + data.id + '">\n\
                                <td > <?php echo $state_name; ?></td>\n\
                                <td >' + maincat + '</td> \n\
                                <td >' + dolr_usgaecode1 + '</td> \n\
                                <td >\n\
                                <button id="btnupdatemain" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate('
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?><?php
    echo " + " . $listlevel . '_l1' . " + ','";
}
?> + rmainid + ',' + rid + ');">\n\
                                     <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Updated');
            }

            }
            return false;
            });
            } else {
            alert(data);
            }
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
                $('#<?php echo $listlevel; ?>').val('');<?php } ?>
            $('#hfupdateflag').val('');
                    $('#btnadd').html('Add');
                    return false;
            });
    });
    });
            function formupdate(<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'usage_main_catg_desc_' . $langcode['mainlanguage']['language_code']; ?>, <?php } ?>
            dolr_usgaecode1, usage_main_catg_id, id) {
            document.getElementById("actiontype").value = '1';
<?php foreach ($fieldlist1 as $listlevel => $level1) { ?>
                $('#<?php echo $listlevel; ?>').val(<?php echo $listlevel; ?>); <?php } ?>
            $('#hfupdateflag').val('MU');
                    $('#hfcode').val(usage_main_catg_id);
                    $('#hfid').val(id);
            }
</script> 
<!--END OF script for main category-->
<!--start script for sub category along with dyanamic structure and validations-->
<script>
    $(document).ready(function () {
<?php
foreach ($fieldlist2 as $listkey => $listcontrol) {

    foreach ($listcontrol as $controltype => $valrule) {
        $rulearr = explode(",", $valrule);
        //  foreach ($rulearr as $singlerule) {

        if ($controltype == 'text') {
            $event = "keyup";
        } else if ($controltype == 'select') {
            $event = "change";
        } else if ($controltype == 'radio') {
            $event = "";
        }
        if (!empty($event)) {
            ?>
                $('#<?php echo $listkey; ?>').<?php echo $event; ?>(function (event)
                {//for checking function is_alpha or etc
            <?php
            foreach ($rulearr as $singlerule) {

                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                    }
                }
                ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val()))
                    {
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                    event.preventDefault();
                                    // alert();
                                    return;
                        <?php
                    }
                }
                ?>
                    } else {
                    $('#<?php echo $listkey; ?>_error').html('');
                            $("#<?php echo $listkey; ?>").parent().removeClass("field-error");
                    }
            <?php } ?>
                });
            <?php
        }
    }
}
?>
    $('#btnaddsub').click(function () {

    $(':input').each(function () {
    $(this).val($.trim($(this).val()));
    });
<?php foreach ($fieldlist2 as $listlevel => $level1) { ?>
        var <?php echo $listlevel; ?> = $("#<?php echo $listlevel; ?>").val();
<?php } ?>
    var maincat = $('#usage_main_catg_desc_' + '<?php echo $laug ?>').val();
            var subcat = $('#usage_sub_catg_desc_' + '<?php echo $laug ?>').val();
            var main_id = $('#hfcode').val();
<?php
foreach ($fieldlist2 as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
    foreach ($listcontrol as $controltype => $valrule) {
        ?>
        <?php
        $rulearr = explode(",", $valrule);
        foreach ($rulearr as $singlerule) {
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                }
            }
            ?>
                // result = <?php echo $valrule; ?>(<?php echo $listkey; ?>);
            <?php if ($controltype == 'select' OR $controltype == 'text') { ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val())) {
            <?php } else if ($controltype == 'radio') {
                ?>   var frmid = $('form:first').attr('id');
                            // alert($('form:first').attr('id'));      
                            if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()))  {
                    //  alert('IN');
            <?php }
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                        $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                $("#<?php echo $listkey; ?>").parent().addClass("field-error");
                                $("#<?php echo $listkey; ?>").focus();
                                return false;
                                //  (-temp FOR SERVER )
                    <?php
                }
            }
            ?>
                }
                else{
                $('#<?php echo $listkey; ?>_error').html('');
                        $("#<?php echo $listkey; ?>").removeClass("field-error");
                        // return false;
                }
            <?php
        }
    }
    ?>
    <?php
}
//}
?>
    var actionval = 'S';
            if ($('#hfupdateflag').val() === 'SU') {
    actionval = 'U';
    }
    var id = $('#hfsubid').val();
    
                                                  
                                                
    <?php 
    //foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ 
    ?>
    //var vd = $('input:radio[name="data[usagecategory][subcatg_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val();
    //alert(vd);
    <?php
    //} } 
    ?>
     
     
            $.post('<?php echo $this->webroot; ?>Usagecatgmullan/saveusagesubcategory', {<?php foreach ($languagelist as $langcode) { ?>
    <?php
    echo 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . " : " . 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . " ,";
}
?>
    <?php foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ ?>
            //var vd = $('input:radio[name="data[usagecategory][usage_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val();
            <?php echo 'subcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag'." : " ?> $('input:radio[name="data[usagecategory][subcatg_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val()
,<?php }  } ?>
            dolr_usage_code2: dolr_usage_code2, actionval: actionval, id: id, main_id: main_id}, function (data)
            {
            var data = $.parseJSON(data);
                    if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
            $.each(data, function (index, val) {
            if (index == 'errorcode'){
            $.each(data[index], function (index1, val1) {
            $('#' + index1).text(val1);
            });
                    return false;
            } else {
            //variable declarations
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
                var <?php echo $listlevel . '_l2'; ?> = "'" + <?php echo $listlevel; ?> + "'";
<?php } ?>

            var rsubid = "'" + data.usage_sub_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actionval === 'S') {

            $('#tableUsagesub tr:last').after('<tr id="' + data.id + '">\n\
                        <td ><?php echo $state_name; ?></td>\n\
                        <td >' + maincat + '</td> \n\
                        <td >' + subcat + '</td>\n\
                        <td >' + dolr_usage_code2 + '</td>\n\
                        <td ><button id="btnupdatesub" name="btnupdatesub" class="btn btn-default "  onclick="javascript: return formupdatesub(' <?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
    <?php
    echo " + " . $listlevel . '_l2' . " + ','";
}
?> + rsubid + ',' + rid + ');">\n\
                            <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Saved');
            } else if (actionval === 'U') {
            $('#' + id).fadeOut();
                    $('#tableUsagesub tr:last').after('<tr id="' + data.id + '">\n\
                        <td ><?php echo $state_name; ?></td>\n\
                        <td >' + maincat + '</td> \n\
                        <td >' + subcat + '</td>\n\
                        <td >' + dolr_usage_code2 + '</td>\n\
                        <td ><button id="btnupdatesub" name="btnupdatesub" class="btn btn-default "  onclick="javascript: return formupdatesub(' <?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
    <?php
    echo " + " . $listlevel . '_l2' . " + ','";
}
?> + rsubid + ',' + rid + ');">\n\
                            <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Update');
            }

            }
            return false;
            });
            } else {
            alert(data);
            }
<?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
                $('#<?php echo $listlevel; ?>').val(''); <?php } ?>
            $('#hfupdateflag').val('');
                    $('#btnaddsub').html('Add');
                    return false;
            });
    });
    });
            function formupdatesub(<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']; ?>, <?php } ?>
            dolr_usage_code2, usage_sub_catg_id <?php foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ ?>
            ,<?php echo 'subcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag'?>
<?php } } ?>, id) {
            document.getElementById("actiontype").value = '2';
                <?php foreach ($fieldlist2 as $listlevel => $level2) { ?>
                                $('#<?php echo $listlevel; ?>').val(<?php echo $listlevel; ?>); <?php } ?>
                            $('#hfsubid').val(id);
                                    $('#hfsubcode').val(usage_sub_catg_id);
                                    $('#hfupdateflag').val('SU');

                                   
                                     <?php

                                    foreach ($languagelist as $langcode) {
                                      if($langcode['mainlanguage']['language_code']!='en'){

                                    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
                                    ?>
                                        //alert('<?php echo $langcode['mainlanguage']['language_code']; ?>');            
                                    $('input:radio[name="data[usagecategory][subcatg_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag]"]').filter('[value="' + subcatg_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag + '"]').attr('checked', true);

                                       //alert('bb');
                                       // var vd = $('input:radio[name="data[usagecategory][subcatg_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val();
                                       // alert(vd);
                        
                                    <?php } } ?>

                
            }
</script>
<!--END OF script for sub category-->

<!--start script for sub sub category along with dyanamic structure and validations-->
<script>
    $(document).ready(function () {
    ////---------------------Form element events validations-----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist3 as $listkey => $listcontrol) {

    foreach ($listcontrol as $controltype => $valrule) {
        $rulearr = explode(",", $valrule);
        //  foreach ($rulearr as $singlerule) {

        if ($controltype == 'text') {
            $event = "keyup";
        } else if ($controltype == 'select') {
            $event = "change";
        } else if ($controltype == 'radio') {
            $event = "";
        }
        if (!empty($event)) {
            ?>
                $('#<?php echo $listkey; ?>').<?php echo $event; ?>(function (event)
                {//for checking function is_alpha or etc
            <?php
            foreach ($rulearr as $singlerule) {

                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                    }
                }
                ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val()))
                    {
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                    event.preventDefault();
                                    // alert();
                                    return;
                        <?php
                    }
                }
                ?>
                    } else {
                    $('#<?php echo $listkey; ?>_error').html('');
                            $("#<?php echo $listkey; ?>").parent().removeClass("field-error");
                    }
            <?php } ?>
                });
            <?php
        }
    }
}
?>
    $('#btnaddsubsub').click(function () {
    $(':input').each(function () {
    $(this).val($.trim($(this).val()));
    });
            //dyanamic variable creat
<?php foreach ($fieldlist3 as $listlevel => $level1) { ?>
        var <?php echo $listlevel; ?> = $("#<?php echo $listlevel; ?>").val();
<?php } ?>
    var maincat = $('#usage_main_catg_desc_' + '<?php echo $laug ?>').val();
            var subcat = $('#usage_sub_catg_desc_' + '<?php echo $laug ?>').val();
            var subsubcat = $('#usage_sub_sub_catg_desc_' + '<?php echo $laug ?>').val();
            var constuctionflag = $('input[name="consflag"]:checked').val();
            var depreciationflag = $('input[name="depflag"]:checked').val();
            var roadvicinityflag = $('input[name="roadflag"]:checked').val();
            var userdepflag1 = $('input[name="ud1flag"]:checked').val();
            var userdepflg2 = $('input[name="ud2flag"]:checked').val();
            var main_id = $('#hfcode').val();
            var sub_id = $('#hfsubcode').val();
            //---------------------FORM SUBMIT validations -----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist3 as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
    foreach ($listcontrol as $controltype => $valrule) {
        ?>
        <?php
        $rulearr = explode(",", $valrule);
        foreach ($rulearr as $singlerule) {
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                }
            }
            ?>
                // result = <?php echo $valrule; ?>(<?php echo $listkey; ?>);
            <?php if ($controltype == 'select' OR $controltype == 'text') { ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val())) {
            <?php } else if ($controltype == 'radio') {
                ?>   var frmid = $('form:first').attr('id');
                            // alert($('form:first').attr('id'));      
                            if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()))  {
                    //  alert('IN');
            <?php }
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                        $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                $("#<?php echo $listkey; ?>").parent().addClass("field-error");
                                $("#<?php echo $listkey; ?>").focus();
                                return false;
                                //  (-temp FOR SERVER )
                    <?php
                }
            }
            ?>
                }
                else{
                $('#<?php echo $listkey; ?>_error').html('');
                        $("#<?php echo $listkey; ?>").removeClass("field-error");
                        // return false;
                }
            <?php
        }
    }
    ?>
    <?php
}
//}
?>

    var actionval = 'S';
            if ($('#hfupdateflag').val() === 'SSU') {
    actionval = 'U';
    }
    var id = $('#hfsubsubid').val();
            $.post('<?php echo $this->webroot; ?>Usagecatgmullan/saveusagesubsubcategory', { <?php foreach ($languagelist as $langcode) { ?>
    <?php
    echo 'usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . " : " . 'usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . " ,";
}
?>
         <?php foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ ?>
            //var vd = $('input:radio[name="data[usagecategory][usage_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val();
            <?php echo 'subsubcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag'." : " ?> $('input:radio[name="data[usagecategory][subsubcatg_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val()
        ,<?php }  } ?>

            dolr_usage_code: dolr_usage_code, constuctionflag: constuctionflag, depreciationflag: depreciationflag, roadvicinityflag: roadvicinityflag, userdepflag1: userdepflag1, userdepflg2: userdepflg2, actionval: actionval, id: id, main_id: main_id, sub_id: sub_id}, function (data)
            {
            var data = $.parseJSON(data);
                    if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {

            $.each(data, function (index, val) {
            if (index == 'errorcode'){
            $.each(data[index], function (index1, val1) {
            $('#' + index1).text(val1);
            });
                    return false;
            } else {

<?php foreach ($fieldlist3 as $listlevel => $level1) { ?>
                var <?php echo $listlevel . '_l3'; ?> = "'" + <?php echo $listlevel; ?> + "'";
<?php } ?>
            var constuctionflag1 = "'" + constuctionflag + "'";
                    var depreciationflag1 = "'" + depreciationflag + "'";
                    var roadvicinityflag1 = "'" + roadvicinityflag + "'";
                    var userdepflag11 = "'" + userdepflag1 + "'";
                    var userdepflag12 = "'" + userdepflg2 + "'";
                    var rsubsubid = "'" + data.usage_sub_sub_catg_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actionval === 'S') {
            $('#tablesubsubcategory tr:last').after('<tr id="' + data.id + '">\n\
                                <td >Maharashtra</td>\n\
                                <td >' + maincat + '</td> \n\
                                <td >' + subcat + '</td> \n\
                                <td >' + subsubcat + '</td>\n\
                                <td >' + dolr_usage_code + '</td>\n\
                                <td ><button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-default "  onclick="javascript: return formupdatesubsub('<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
    <?php
    echo " + " . $listlevel . '_l3' . " + ','";
}
?> + constuctionflag1 + ',' + depreciationflag1 + ',' + roadvicinityflag1 + ',' + userdepflag11 + ',' + userdepflag12 + ',' + rsubsubid + ',' + rid + ');">\n\
                                    <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Saved');
            } else if (actionval === 'U') {
            $('#' + id).fadeOut();
                    $('#tablesubsubcategory tr:last').after('<tr id="' + data.id + '">\n\
                                <td >Maharashtra</td>\n\
                                <td >' + maincat + '</td> \n\
                                <td >' + subcat + '</td> \n\
                                <td >' + subsubcat + '</td>\n\
                                <td >' + dolr_usage_code + '</td>\n\
                                <td ><button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-default "  onclick="javascript: return formupdatesubsub('<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
    <?php
    echo " + " . $listlevel . '_l3' . " + ','";
}
?> + constuctionflag1 + ',' + depreciationflag1 + ',' + roadvicinityflag1 + ',' + userdepflag11 + ',' + userdepflag12 + ',' + rsubsubid + ',' + rid + ');">\n\
                                    <span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Updated');
            }

            }
            return false;
            });
            } else {
            alert(data);
            }
<?php foreach ($fieldlist3 as $listlevel => $level3) { ?>
                $('#<?php echo $listlevel; ?>').val('');<?php } ?>
//            $("input:radio").attr("checked", false);
            $('#hfupdateflag').val('');
                    $('input[name="consflag"][value="N"]').prop('checked', 'checked');
                    $('input[name=depflag][value="N"]').prop('checked', 'checked');
                    $('input[name=roadflag][value="N"]').prop('checked', 'checked');
                    $('input[name=ud1flag][value="N"]').prop('checked', 'checked');
                    $('input[name=ud2flag][value="N"]').prop('checked', 'checked');
                    $('#btnaddsubsub').html('Add');
                    return false;
            });
    });
            $('input[name="consflag"][value="' + '<?php echo $r1; ?>' + '"]').prop('checked', 'checked');
            $('input[name="depflag"][value="' + '<?php echo $r2; ?>' + '"]').prop('checked', 'checked');
            $('input[name="roadflag"][value="' + '<?php echo $r3; ?>' + '"]').prop('checked', 'checked');
            $('input[name="ud1flag"][value="' + '<?php echo $r4; ?>' + '"]').prop('checked', 'checked');
            $('input[name="ud2flag"][value="' + '<?php echo $r5; ?>' + '"]').prop('checked', 'checked');
    });
            function formupdatesubsub(<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code']; ?>, <?php } ?>
            dolr_usage_code, contsruction_type_flag, depreciation_flag, road_vicinity_flag, user_defined_dependency1_flag,
                    user_defined_dependency2_flag, usage_sub_sub_catg_id <?php foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ ?>
            ,<?php echo 'subsubcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag'?>
<?php } } ?>, id) {

            document.getElementById("actiontype").value = '3';
<?php foreach ($fieldlist3 as $listlevel => $level1) { ?>
                $('#<?php echo $listlevel; ?>').val(<?php echo $listlevel; ?>); <?php } ?>
           // $("input:radio").attr("checked", false);
                    $('input[name="consflag"][value="' + contsruction_type_flag + '"]').prop('checked', 'checked');
                    $('input[name=depflag][value="' + depreciation_flag + '"]').prop('checked', 'checked');
                    $('input[name=roadflag][value="' + road_vicinity_flag + '"]').prop('checked', 'checked');
                    $('input[name=ud1flag][value="' + user_defined_dependency1_flag + '"]').prop('checked', 'checked');
                    $('input[name=ud2flag][value="' + user_defined_dependency2_flag + '"]').prop('checked', 'checked');
                    $('#hfsubsubid').val(id);
                    $('#hfsubsubcode').val(usage_sub_sub_catg_id);
                    $('#hfupdateflag').val('SSU');
                    
                   
                    
                    <?php

                    foreach ($languagelist as $langcode) {
                      if($langcode['mainlanguage']['language_code']!='en'){

                    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
                    ?>
                        //alert('<?php echo $langcode['mainlanguage']['language_code']; ?>');            
                    $('input:radio[name="data[usagecategory][subsubcatg_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag]"]').filter('[value="' + subsubcatg_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag + '"]').attr('checked', true);
                 
                   // alert('ff');
                   // var vdc = $('input:radio[name="data[usagecategory][subsubcatg_<?php echo $langcode['mainlanguage']['language_code'] ?>_activation_flag]"]:checked').val();
                   // alert(vdc);
                    <?php } } ?>
                                        
                                        
            }
</script>
<script>
    $(document).ready(function () {
////---------------------Form element events validations-----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist4 as $listkey => $listcontrol) {

    foreach ($listcontrol as $controltype => $valrule) {
        $rulearr = explode(",", $valrule);
        //  foreach ($rulearr as $singlerule) {

        if ($controltype == 'text') {
            $event = "keyup";
        } else if ($controltype == 'select') {
            $event = "change";
        } else if ($controltype == 'radio') {
            $event = "";
        }
        if (!empty($event)) {
            ?>
                $('#<?php echo $listkey; ?>').<?php echo $event; ?>(function (event)
                {//for checking function is_alpha or etc
            <?php
            foreach ($rulearr as $singlerule) {

                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                    }
                }
                ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val()))
                    {
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                            $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                    event.preventDefault();
                                    // alert();
                                    return;
                        <?php
                    }
                }
                ?>
                    } else {
                    $('#<?php echo $listkey; ?>_error').html('');
                            $("#<?php echo $listkey; ?>").parent().removeClass("field-error");
                    }
            <?php } ?>
                });
            <?php
        }
    }
}
?>
    $('#btnadditem').click(function () {
    $(':input').each(function () {
    $(this).val($.trim($(this).val()));
    });
            var usagemain = $('#usage_main_catg_desc_en').val();
            var usagesub = $('#usage_sub_catg_desc_en').val();
            var usagesubsub = $('#usage_sub_sub_catg_desc_en').val();
            var usage_param_id = $("#usage_param_id option:selected").val();
            var item = $("#usage_param_id option:selected").text();
            var main_id = $('#hfcode').val();
            var sub_id = $('#hfsubcode').val();
            var subsub_id = $('#hfsubsubcode').val();
            //---------------------FORM SUBMIT validations -----------------------------------------------------------------------------------------------------------------
<?php
foreach ($fieldlist4 as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
    foreach ($listcontrol as $controltype => $valrule) {
        ?>
        <?php
        $rulearr = explode(",", $valrule);
        foreach ($rulearr as $singlerule) {
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                }
            }
            ?>
                // result = <?php echo $valrule; ?>(<?php echo $listkey; ?>);
            <?php if ($controltype == 'select' OR $controltype == 'text') { ?>
                    if (!regex.test($('#<?php echo $listkey; ?>').val())) {
            <?php } else if ($controltype == 'radio') {
                ?>   var frmid = $('form:first').attr('id');
                            // alert($('form:first').attr('id'));      
                            if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()))  {
                    //  alert('IN');
            <?php }
            ?>
            <?php
            foreach ($result_codes as $errorkey => $error_record) {
                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                    ?>
                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                        $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                $("#<?php echo $listkey; ?>").parent().addClass("field-error");
                                $("#<?php echo $listkey; ?>").focus();
                                return false;
                                //  (-temp FOR SERVER )
                    <?php
                }
            }
            ?>
                }
                else{
                $('#<?php echo $listkey; ?>_error').html('');
                        $("#<?php echo $listkey; ?>").removeClass("field-error");
                        // return false;
                }
            <?php
        }
    }
    ?>
    <?php
}
//}
?>
    var actionval = 'S';
            if ($('#hfupdateflag').val() === 'IU') {
    actionval = 'U';
    }
    var id = $('#hfitemid').val();
            $.post('<?php echo $this->webroot; ?>Usagecatgmullan/saveusagelinkitem', {actionval: actionval, usage_param_id: usage_param_id, id: id, main_id: main_id, sub_id: sub_id, subsub_id: subsub_id}, function (data)
            {
            var data = $.parseJSON(data);
                    if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {

            var ritemid = "'" + data.usage_param_id + "'";
                    var rid = "'" + data.id + "'";
                    if (actionval === 'S') {
            $('#tableitem tr:last').after('<tr id="' + data.id + '">\n\
                        <td >Maharashtra</td>\n\
                        <td >' + usagemain + '</td>\n\
                        <td >' + usagesub + '</td>\n\
                        <td >' + usagesubsub + '</td>\n\
                        <td >' + item + '</td>\n\
                        <td ><button id="btnupdateitem" name="btnupdateitem" class="btn btn-default "  onclick="javascript: return formupdateitem(' + ritemid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Saved');
            } else if (actionval === 'U') {
            $('#' + id).fadeOut();
                    $('#tableitem tr:last').after('<tr id="' + data.id + '">\n\
                        <td >Maharashtra</td>\n\
                        <td >' + usagemain + '</td>\n\
                        <td >' + usagesub + '</td>\n\
                        <td >' + usagesubsub + '</td>\n\
                        <td >' + item + '</td>\n\
                        <td ><button id="btnupdateitem" name="btnupdateitem" class="btn btn-default "  onclick="javascript: return formupdateitem(' + ritemid + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                    alert('Record Updated');
            }
            $('#usage_param_id').val('');
                    $('#hfupdateflag').val('');
                    $('#btnadditem').html('Add');
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

<?php echo $this->Form->create('usagecategory', array('id' => 'usagecategory')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblusagecatandlinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Usage Category linkage/usagecategory_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="box box-primary">
                    <div class="box-header with-border" >
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <b><?php echo __('lblusamaincat'); ?></b>
                                    </th>
                                    <th style="text-align: right">
                                        <button id="btnshowmain"  class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button> 
                                        <button id="btnhidemain" class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="divmain">
                        <div class="box-body">
                            <div class="row" id="selectUsagemainmain">
                                <div class="form-group">
                                    <label for="usage_main_catg_desc_" class="col-sm-3 control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label>    
                                    <?php
                                    $i = 1;
                                    foreach ($languagelist as $key => $langcode) {

                                        if ($i % 6 == 0) {
                                            echo "<div class=row>";
                                        }
                                        ?>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $langcode['mainlanguage']['language_name'])) ?>
                                            <span id="<?php echo 'usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['usage_main_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                        </div>
                                        <?php
                                        if ($i % 6 == 0) {
                                            if ($i > 1) {
                                                echo "</div><br>";
                                            }
                                        }
                                        $i++;
                                    }
                                    ?> 
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label for="dolr_usgaecode" class="col-sm-3 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('dolr_usgaecode1', array('label' => false, 'id' => 'dolr_usgaecode1', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="dolr_usgaecode1_error" class="form-error"><?php echo $errarr['dolr_usgaecode1_error']; ?></span>
                                    </div>
                                    <div class="col-sm-4">
                                        <button id="btnadd" name="btnadd" class="btn btn-info "  type="button">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                        </button>
                                        <button id="btncancel" name="btncancel" class="btn btn-info "  >
                                            <span class="glyphicon glyphicon-reset"></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: #3c8dbc; height: 1px;">
                            <table id="tableUsagemainmain" class="table table-striped table-bordered table-hover">  
                                <thead >  
                                    <tr >  
                                        <th class="center"><?php echo __('lbladmstate'); ?></th>
                                        <?php // foreach ($languagelist as $langcode) {     ?>
                                        <th class="center"><?php echo __('lblusagemaincategoryname'); ?></th>
                                        <?php // }      ?>
                                        <th class="center"><?php echo __('lbldolrusagecode'); ?></th>
                                        <th class="center width10"><?php echo __('lblaction'); ?></th>
                                    </tr>  
                                </thead>
                                <?php
                                if (isset($usagemainrecord)) {
                                    foreach ($usagemainrecord as $usagemainrecord1):
                                        ?>
                                        <tr  id="<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>">
                                            <td class="tblbigdata"><?php echo $state_name; ?></td>
                                            <td class="tblbigdata"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?></td>
                                            <td >
                                                <button id="btnupdatemain" name="btnupdate" class="btn btn-default "  
                                                        onclick="javascript: return formupdate(
                                                        <?php foreach ($languagelist as $langcode) { ?>
                                                                        ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                        <?php } ?>
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?>'),
                                                                            ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_id']; ?>'),
                                                                            ('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                    <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');                                                                                                                                                 ?></span><?php //echo __('lblbtnupdate');                                                                                                                                               ?>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                ?>
                                <?php unset($usagemainrecord1); ?>
                            </table>
                            <?php if (!empty($usagemainrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border" >
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <b><?php echo __('lblsubcat'); ?></b>
                                    </th>
                                    <th style="text-align: right">
                                        <button id="btnshowsub"  class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button> 
                                        <button id="btnhidesub" class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button> 
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="divsub">
                        <div class="box-body">
                            <div class="row" id="selectUsagesub">
                                <div class="form-group">
                                    <label for="usage_sub_catg_desc_" class="col-sm-3 control-label"><?php echo __('lblsubcat'); ?><span style="color: #ff0000">*</span></label>    
                                    <?php
                                    $i = 1;
                                    foreach ($languagelist as $key => $langcode) {
                                        if ($i % 6 == 0) {
                                            echo "<div class=row>";
                                        }
                                        ?>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $langcode['mainlanguage']['language_name'])) ?>
                                            <span id="<?php echo 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                        </div>
                                        <?php
                                        if ($i % 6 == 0) {
                                            if ($i > 1) {
                                                echo "</div><br>";
                                            }
                                        }
                                        $i++;
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <?php
                                        //pr($lngcd);
                                            foreach ($languagelist as $key => $langcode) {
                                                $langcd=$langcode['mainlanguage']['language_code'];
                                                $nn2='data[usagecategory][subcatg_'.$langcd.'_activation_flag]';
                                                $matchid='subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag';
                                                $langnm=$langcode['mainlanguage']['language_name'];
                                                if($langcd!='en'){
                                                    //pr('data[usagecategory][subcatg_'.$langcd.'_activation_flag]');
                                                    
                                                   
                                        ?>
                                        <br>
                                        <label for="census_code" control-label><?php echo $langcode['mainlanguage']['language_name'].' activation flag'; ?></label> 
                                        <input type="radio" value="Y" name="<?php echo $nn2;?>"  id="activationY" <?php if($lngcd) foreach($lngcd as $kt => $valt){ if($kt==$matchid){ if($valt=='Y') { ?> checked <?php } } } ?>>Yes
                                        <input type="radio" value="N" name="<?php echo $nn2;?>"  id="activationN" <?php if($lngcd) foreach($lngcd as $kt => $valt){ if($kt==$matchid){ if($valt=='N') { ?> checked <?php } } } ?>>No
                                        <!--<span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>-->
                                        <?php
                                            
                                        }
                                            }
                                        ?>
                                    </div> 
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label for="dolr_usage_code" class="col-sm-3 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('dolr_usage_code2', array('label' => false, 'id' => 'dolr_usage_code2', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="dolr_usage_code2_error" class="form-error"><?php echo $errarr['dolr_usage_code2_error']; ?></span>
                                    </div>
                                    <div class="col-sm-4 tdselect">
                                        <button id="btnaddsub" name="btnaddsub" class="btn btn-info "  type="button" >
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                        </button>
                                        <button id="btncancelsub" name="btncancelsub" class="btn btn-info "  >
                                            <span class="glyphicon "></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: #3c8dbc; height: 1px;">
                            <table id="tableUsagesub" class="table table-striped table-bordered table-hover" >  
                                <thead >  
                                    <tr >  
                                        <th class="center"><?php echo __('lbladmstate'); ?></th>
                                        <th class="center"><?php echo __('lblusagemaincategoryname'); ?></th>
                                        <th class="center"><?php echo __('lblUsagesubcategoryname'); ?></th>
                                        <th class="center"><?php echo __('lbldolrusagecode'); ?></th>
                                        <th class="center width10"><?php echo __('lblaction'); ?></th>
                                    </tr>  
                                </thead>
                                <div></div>
                                <?php
                                if (isset($Usagesubrecord)) {
                                    foreach ($Usagesubrecord as $Usagesubrecord1):
                                        ?>
                                        <tr   id="<?php echo $Usagesubrecord1[0]['id']; ?>">
                                            <td class="tblbigdata"><?php echo $state_name; ?></td>
                                            <td class="tblbigdata"><?php echo $Usagesubrecord1[0]['usage_main_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $Usagesubrecord1[0]['dolr_usage_code']; ?></td>
                                            <td >
                                                <button id="btnupdatesub" name="btnupdatesub" class="btn btn-default "  
                                                        onclick="javascript: return formupdatesub(
                                                        <?php foreach ($languagelist as $langcode) { ?>
                                                                                ('<?php echo $Usagesubrecord1[0]['usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                        <?php } ?>
                                                                            ('<?php echo $Usagesubrecord1[0]['dolr_usage_code']; ?>'),
                                                                                    ('<?php echo $Usagesubrecord1[0]['usage_sub_catg_id']; ?>')
                                                                            
                                                                                     <?php foreach ($languagelist as $langcode) {
                                                                                    if($langcode['mainlanguage']['language_code']!='en'){
                                                                                        ?>
                                                                                    ,('<?php echo $Usagesubrecord1[0]['subcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag']; ?>')
                                                                                    <?php }
                                                                                    }
                                                                                    ?>
                                                                                            
                                                                                    ,('<?php echo $Usagesubrecord1[0]['id']; ?>')
                                                                                    );">
                                                    <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');                                                                                                                               ?></span><?php //echo __('lblbtnupdate');                                                                                                                             ?>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                ?>
                                <?php unset($Usagesubrecord1); ?>
                            </table> 
                            <?php if (!empty($Usagesubrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden2"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden2"/><?php } ?>
                        </div>
                    </div>
                </div>
                <div class="box box-primary" >
                    <div class="box-header with-border" >
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <b><?php echo __('lblsubccategory'); ?></b>
                                    </th>
                                    <th style="text-align: right">
                                        <button id="btnshowsubsub"  class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button> 
                                        <button id="btnhidesubsub" class="btn btn-default "  >
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button> 
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="divsubsub">
                        <div class="box-body">
                            <div class="row" id="selectsubsubcategory" style="padding-left:10px">
                                <div class="form-group">
                                        <label for="usage_sub_sub_catg_desc_" class="col-sm-3 control-label"><?php echo __('lblsubccategory'); ?><span style="color: #ff0000">*</span></label>    
                                </div>
                                <?php
                                $i = 1;
                                foreach ($languagelist as $key => $langcode) {
                                    if ($i % 6 == 0) {
                                        echo "<div class=row>";
                                    }
                                    ?>
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $langcode['mainlanguage']['language_name'])) ?>
                                        <span id="<?php echo 'usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                    </div>
                                    <?php
                                    if ($i % 6 == 0) {
                                        if ($i > 1) {
                                            echo "</div><br>";
                                        }
                                    }
                                    $i++;
                                }
                                ?> 
                            </div>
                        </div><br>
                       
                        <div class="row" style="margin-left:0.5px">
                            <div class="form-group">
                                <label for="dolr_usage_code" class="col-sm-3 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('dolr_usage_code', array('label' => false, 'id' => 'dolr_usage_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="dolr_usage_code_error" class="form-error"><?php echo $errarr['dolr_usage_code_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht"></div>
                        <div class="row" style="margin-left:0.5px">
                            <div class="form-group">
                                <label for="contsruction_type_flag" class="col-sm-3 control-label" ><?php echo __('lblconstuctiontye'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('contsruction_type_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'contsruction_type_flag', 'name' => 'consflag')); ?></div>   
                                <label for="depreciation_flag" class="control-label col-sm-3"><?php echo __('lbldepreciation'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('depreciation_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'depreciation_flag', 'name' => 'depflag')); ?></div> 
                                <label for="road_vicinity_flag" class="control-label col-sm-3"><?php echo __('lblroadvicinity'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('road_vicinity_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'road_vicinity_flag', 'name' => 'roadflag')); ?></div> 
                                <label for="user_defined_dependency1_flag" class="control-label col-sm-3"><?php echo __('lbluserdependencyflag1'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('user_defined_dependency1_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency1_flag', 'name' => 'ud1flag')); ?></div> 
                                <label for="user_defined_dependency2_flag" class="control-label col-sm-3"><?php echo __('lbluserdependencyflag2'); ?></label>            
                                <div class="col-sm-3"> <?php echo $this->Form->input('user_defined_dependency2_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'user_defined_dependency2_flag', 'name' => 'ud2flag')); ?></div> 
                            </div>
                        </div>
                        <div class="row" style="margin-left:0.5px">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <?php
                                       // pr($lngcd_two);
                                            foreach ($languagelist as $key => $langcode) {
                                                $langcd=$langcode['mainlanguage']['language_code'];
                                                $nn3='data[usagecategory][subsubcatg_'.$langcd.'_activation_flag]';
                                                $matchid2='subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag';
                                                $langnm=$langcode['mainlanguage']['language_name'];
                                                if($langcd!='en'){
                                                    //pr('data[usagecategory][subcatg_'.$langcd.'_activation_flag]');
                                                    
                                                   
                                        ?>
                                        <br>
                                        <label for="census_code" control-label><?php echo $langcode['mainlanguage']['language_name'].' activation flag'; ?></label> 
                                        <input type="radio" value="Y" name="<?php echo $nn3;?>"  id="activationttwoY" <?php if($lngcd_two) foreach($lngcd_two as $kt2 => $valt2){ if($kt2==$matchid2){ if($valt2=='Y') { ?> checked <?php } } } ?>>Yes
                                        <input type="radio" value="N" name="<?php echo $nn3;?>"  id="activationtwoN" <?php if($lngcd_two) foreach($lngcd_two as $kt2 => $valt2){ if($kt2==$matchid2){ if($valt2=='N') { ?> checked <?php } } } ?>>No
                                        <!--<span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>-->
                                        <?php
                                            
                                        }
                                            }
                                        ?>
                                    </div> 
                                    
                                </div>
                            </div>
                        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                        <div class="row center" >
                            <div class="form-group">
                                <button id="btnaddsubsub" name="btnaddsubsub" class="btn btn-info "  type="button" >
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                </button>
                                <button id="btncancelsubsub" name="btncancelsubsub" class="btn btn-info "  >
                                    <span class=""></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                </button>
                            </div>
                        </div>
                        <hr style="background-color: #3c8dbc; height: 1px;">
                        <table id="tablesubsubcategory" class="table table-striped table-bordered table-hover" style="width:98.5%;margin-left:10px">  
                            <thead >  
                                <tr>  
                                    <th class="center"><?php echo __('lbladmstate'); ?></th>
                                    <th class="center"><?php echo __('lblusagemaincategoryname'); ?></th>
                                    <th class="center"><?php echo __('lblUsagesubcategoryname'); ?></th>
                                    <th class="center"><?php echo __('lblsubsubcategorydesc'); ?></th>
                                    <th class="center"><?php echo __('lbldolrusagecode'); ?></th>
                                    <th class="center width10"><?php echo __('lblaction'); ?></th>
                                </tr>  
                            </thead>
                            <?php
                            if (isset($subsubcategoryrecord)) {
                                foreach ($subsubcategoryrecord as $subsubcategoryrecord1):
                                    ?>
                                    <tr id="<?php echo $subsubcategoryrecord1[0]['id']; ?>">
                                        <td class="tblbigdata"><?php echo $state_name; ?></td>
                                        <td class="tblbigdata"><?php echo $subsubcategoryrecord1[0]['usage_main_catg_desc_' . $laug]; ?></td>
                                        <td class="tblbigdata"><?php echo $subsubcategoryrecord1[0]['usage_sub_catg_desc_' . $laug]; ?></td>
                                        <td class="tblbigdata"><?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_' . $laug]; ?></td>
                                        <td class="tblbigdata"><?php echo $subsubcategoryrecord1[0]['dolr_usage_code']; ?></td>
                                        <td style="text-align: center; width: 15%">
                                            <button id="btnupdatesubsub" name="btnupdatesubsub" class="btn btn-default "  
                                                    onclick="javascript: return formupdatesubsub(
                                                    <?php foreach ($languagelist as $langcode) { ?>
                                                                            ('<?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                    <?php } ?>
                                                                        ('<?php echo $subsubcategoryrecord1[0]['dolr_usage_code']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['contsruction_type_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['depreciation_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['road_vicinity_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['user_defined_dependency1_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['user_defined_dependency2_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1[0]['usage_sub_sub_catg_id']; ?>')
                                                                                <?php foreach ($languagelist as $langcode) {
                                                                                    if($langcode['mainlanguage']['language_code']!='en'){
                                                                                        ?>
                                                                                    ,('<?php echo $subsubcategoryrecord1[0]['subsubcatg_' . $langcode['mainlanguage']['language_code'].'_activation_flag']; ?>')
                                                                                    <?php }
                                                                                    }
                                                                                    ?>
                                                                                ,('<?php echo $subsubcategoryrecord1[0]['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');                                                                                                                   ?></span><?php //echo __('lblbtnupdate');                                                                                                                   ?>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            }
                            ?>
                            <?php unset($subsubcategoryrecord1); ?>
                        </table> 
                        <?php if (!empty($subsubcategoryrecord)) { ?>
                            <input type="hidden" value="Y" id="hfhidden3"/><?php } else { ?>
                            <input type="hidden" value="N" id="hfhidden3"/><?php } ?>
                    </div>
                </div>
            </div>

            <div class="box box-primary" style="width: 98.5%;
    margin-left: 12px;">
                <div class="box-header with-border" >
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <b><?php echo __('lblusageitemhead'); ?></b>
                                </th>
                                <th style="text-align: right">
                                    <button id="btnshowitem"  class="btn btn-default "  >
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button> 
                                    <button id="btnhideitem" class="btn btn-default "  >
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button> 
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="divitem">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="" class="col-sm-4 control-label"><?php echo __('lblitemlistname'); ?><span style="color: #ff0000">*</span></label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('usage_param_id', array('options' => $usgitem, 'empty' => '--select--', 'id' => 'usage_param_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                    <span id="usage_param_id_error" class="form-error"><?php echo $errarr['usage_param_id_error']; ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <button id="btnadditem" name="btnadditem" class="btn btn-info "  type="button" style="margin-left:25px">
                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?></button>
                                </div>
                            </div>
                        </div>

                        <hr style="background-color: #3c8dbc; height: 1px;">
                        <table id="tableitem" class="table table-striped table-bordered table-hover">  
                            <thead >  
                                <tr>  
                                    <th class="center"><?php echo __('lbladmstate'); ?></th>
                                    <th class="center"><?php echo __('lblusagemaincategoryname'); ?></th>
                                    <th class="center"><?php echo __('lblUsagesubcategoryname'); ?></th>
                                    <th class="center"><?php echo __('lblsubsubcategorydesc'); ?></th>
                                    <th class="center"><?php echo __('lblitemlist'); ?></th>
                                    <th class="center width10"><?php echo __('lblaction'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                if (isset($griditem)) {
                                    foreach ($griditem as $griditem1):
                                        ?>
                                        <tr id="<?php echo $griditem1[0]['id']; ?>">
                                            <td class="tblbigdata"><?php echo $state_name; ?></td>
                                            <td class="tblbigdata"><?php echo $griditem1[0]['usage_main_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $griditem1[0]['usage_sub_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $griditem1[0]['usage_sub_sub_catg_desc_' . $laug]; ?></td>
                                            <td class="tblbigdata"><?php echo $griditem1[0]['usage_param_desc_' . $laug]; ?></td>
                                            <td >
                                                <button id="btnupdateitem" name="btnupdateitem" class="btn btn-default "  
                                                        onclick="javascript: return formupdateitem(('<?php echo $griditem1[0]['usage_param_id']; ?>'),
                                                                                    ('<?php echo $griditem1[0]['id']; ?>'));">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                ?>
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

<input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
<!--<span id="actiontype_error" class="form-error"><?php // echo $errarr['actiontype_error'];      ?></span>-->
<!--<input type='hidden' value='<?php // echo $hfactionval;      ?>' name='hfaction' id='hfaction'/>-->
<input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<span id="hfid_error" class="form-error"><?php //echo $errarr['hfid_error'];      ?></span>
<input type='hidden' value='<?php echo $hfsubid; ?>' name='hfsubid' id='hfsubid'/>
<span id="hfsubid_error" class="form-error"><?php //echo $errarr['hfsubid_error'];      ?></span>
<input type='hidden' value='<?php echo $hfsubsubid; ?>' name='hfsubsubid' id='hfsubsubid'/>
<span id="hfsubsubid_error" class="form-error"><?php //echo $errarr['hfsubsubid_error'];      ?></span>
<input type='hidden' value='<?php echo $hfitemid; ?>' name='hfitemid' id='hfitemid'/>
<span id="hfitemid_error" class="form-error"><?php //echo $errarr['hfitemid_error'];      ?></span>
<input type='hidden' value='<?php echo $hfcode; ?>' name='hfcode' id='hfcode'/>
<span id="hfcode_error" class="form-error"><?php //echo $errarr['hfcode_error'];      ?></span>
<input type='hidden' value='<?php echo $hfsubcode; ?>' name='hfsubcode' id='hfsubcode'/>
<span id="hfsubcode_error" class="form-error"><?php //echo $errarr['hfsubcode_error'];      ?></span>
<input type='hidden' value='<?php echo $hfsubsubcode; ?>' name='hfsubsubcode' id='hfsubsubcode'/>
<span id="hfsubsubcode_error" class="form-error"><?php //echo $errarr['hfsubsubcode_error'];      ?></span>
<input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
<span id="hfupdateflag_error" class="form-error"><?php //echo $errarr['hfupdateflag_error'];      ?></span>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
<!--<input type='hidden' value='<?php //echo $hfselectflag;                                                                                              ?>' name='hfselectflag' id='hfselectflag'/>-->
<!--<input type='hidden' value='<?php // echo $hfdeleteflag;   ?>' name='hfdeleteflag' id='hfdeleteflag'/>-->
<!--<input type='hidden' value='<?php // echo $r1;   ?>' name='r1' id='r1'/>
<input type='hidden' value='<?php // echo $r2;      ?>' name='r2' id='r2'/>
<input type='hidden' value='<?php // echo $r3;      ?>' name='r3' id='r3'/>
<input type='hidden' value='<?php // echo $r4;      ?>' name='r4' id='r4'/>
<input type='hidden' value='<?php // echo $r5;      ?>' name='r5' id='r5'/>-->

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




